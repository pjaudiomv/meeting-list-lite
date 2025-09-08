<?php
/**
 * Plugin Name:       Meeting List Lite
 * Plugin URI:        https://wordpress.org/plugins/meeting-list-lite/
 * Description:       This is a WordPress plugin with minimal settings for displaying meeting lists.
 * Install:           Drop this directory in the "wp-content/plugins/" directory and activate it. You need to specify "[mll]" in the code section of a page or a post.
 * Contributors:      pjaudiomv
 * Version:           1.0.0
 * Requires PHP:      8.0
 * Requires at least: 5.3
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

namespace MLLPlugin;

if ( ! defined( 'WPINC' ) ) {
	die( 'Sorry, but you cannot access this page directly.' );
}

/**
 * Class MLL
 * @package MLLPlugin
 */
class MLL {

	private const SETTINGS_GROUP = 'mll-group';
	private const TSML_CDN_URL = 'https://cdn.aws.bmlt.app/tsml.js';
	private const DEFAULT_DATA_SRC = 'https://cdn.aws.bmlt.app/sample.json';

	private $plugin_dir;
	/**
	 * Singleton instance of the class.
	 *
	 * @var null|self
	 */
	private static ?self $instance = null;

	/**
	 * Constructor method for initializing the plugin.
	 */
	public function __construct() {
		$this->plugin_dir = plugin_dir_url( __FILE__ );
		// Register the 'plugin_setup' method to be executed during the 'init' action hook
		add_action( 'init', [ $this, 'plugin_setup' ] );
	}

	/**
	 * Setup method for initializing the plugin.
	 *
	 * This method checks if the current context is in the admin dashboard or not.
	 * If in the admin dashboard, it registers admin-related actions and settings.
	 * If not in the admin dashboard, it sets up a shortcode and associated actions.
	 *
	 * @return void
	 */
	public function plugin_setup(): void {
		if ( is_admin() ) {
			// If in the admin dashboard, register admin menu and settings actions
			add_action( 'admin_menu', [ static::class, 'create_menu' ] );
			add_action( 'admin_init', [ static::class, 'register_settings' ] );
		} else {
			// If not in the admin dashboard, set up a shortcode and associated actions
			add_action( 'wp_enqueue_scripts', [ $this, 'assets' ] );
			add_shortcode( 'mll', [ static::class, 'setup_shortcode' ] );
		}
	}

	/**
	 * Setup and render the MLL shortcode.
	 *
	 * This method processes the attributes provided to the [mll] shortcode and
	 * sets up the necessary shortcode attributes for rendering the meeting list
	 * If no shortcode attributes are provided, default values from plugin options
	 * are used.
	 *
	 * @param string|array $attrs Shortcode attributes.
	 * @return string The HTML for the MLL shortcode.
	 */
	public static function setup_shortcode( string|array $attrs = [] ): string {
		$option_data_src = get_option( 'mll_data_src' );
		$data_src = ! empty( $attrs['data_src'] ) ? sanitize_url( $attrs['data_src'] ) : ( ! empty( $option_data_src ) ? sanitize_url( $option_data_src ) : sanitize_url( self::DEFAULT_DATA_SRC ) );
		$timezone = sanitize_text_field( get_option( 'timezone_string' ) );
		$timezone_attr = ! empty( $timezone ) ? ' data-timezone="' . esc_attr( $timezone ) . '"' : '';
		$content = '<style>.mll-fullwidth{width:100vw!important;position:relative!important;left:50%!important;margin-left:-50vw!important;padding:20px!important;box-sizing:border-box!important;max-width:none!important}#tsml-ui{width:100%!important;min-height:600px!important}</style>';
		$content .= '<div class="mll-fullwidth">';
		$content .= '<div id="tsml-ui" data-src="' . esc_attr( $data_src ) . '"' . $timezone_attr . '></div>';
		$content .= '</div>';
		return $content;
	}

	/**
	 * Enqueue plugin styles and scripts.
	 *
	 * This method is responsible for enqueueing the necessary CSS and JavaScript
	 * files for the MLL plugin to function correctly.
	 *
	 * @return void
	 */
	public function assets(): void {
		wp_enqueue_script(
			'mll_tsml_ui',
			self::TSML_CDN_URL,
			[],
			'4.0',
			[
				'in_footer' => true,
				'strategy' => 'async',
			]
		);
		$tsml_ui_config = [
			'distance_unit' => 'mi',
			'calendar_enabled' => false,
			'show' => [
				'controls' => true,
				'title' => false,
			],
            'map' => [
                'tiles' => [
                    'attribution' => '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                    'url' => 'https://{s}s.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png',
                ],
                'tiles_dark' => [
                    'attribution' => '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                    'url' => 'https://{s}s.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png',
                ],
            ],
			'strings' => [
				'en' => [
					'region' => 'Area',
					'types' => [
						'inactive' => 'Inactive',
					],
					'type_descriptions' => [
						'O' => 'This meeting is open to addicts and non-addicts alike. All are welcome',
						'C' => 'This meeting is closed to non-addicts',
					],
				],
			],
		];
		wp_localize_script(
			'mll_tsml_ui',
			'tsml_react_config',
			$tsml_ui_config
		);
	}

	/**
	 * Register plugin settings with WordPress.
	 *
	 * This method registers the plugin settings with WordPress using the
	 * `register_setting` function. It defines the settings for 'mll_data_src',
	 * and 'mll_timezone'.
	 *
	 * @return void
	 */
	public static function register_settings(): void {
		register_setting(
			self::SETTINGS_GROUP,
			'mll_data_src',
			[
				'type' => 'string',
				'sanitize_callback' => 'sanitize_url',
			]
		);
	}

	/**
	 * Create the plugin's settings menu in the WordPress admin.
	 *
	 * This method adds the MLL plugin's settings page to the WordPress admin menu.
	 * It also adds a settings link in the list of plugins on the plugins page.
	 *
	 * @return void
	 */
	public static function create_menu(): void {
		add_options_page(
			esc_html__( 'Meeting List Lite Settings', 'mll' ),  // Page Title
			esc_html__( 'Meeting List Lite', 'mll' ),          // Menu Title
			'manage_options',                                  // Capability
			'mll',                                            // Menu Slug
			[ static::class, 'draw_settings' ]                // Callback function to display the page content
		);
		// Add a settings link in the plugins list
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ static::class, 'settings_link' ] );
	}

	/**
	 * Add a "Settings" link for the plugin in the WordPress admin.
	 *
	 * This method adds a "Settings" link for the MLL plugin in the WordPress admin
	 * under the plugins list.
	 *
	 * @param array $links An array of plugin action links.
	 *
	 * @return array An updated array of plugin action links.
	 */
	public static function settings_link( array $links ): array {
		// Add a "Settings" link for the plugin in the WordPress admin
		$settings_url = admin_url( 'options-general.php?page=mll' );
		$links[] = "<a href='{$settings_url}'>Settings</a>";
		return $links;
	}

	/**
	 * Display the plugin's settings page.
	 *
	 * This method renders and displays the settings page for the MLL plugin in the WordPress admin.
	 * It includes form fields for configuring plugin settings such as theme, language, layout, and special keytags.
	 *
	 * @return void
	 */
	public static function draw_settings(): void {
		$mll_data_src = esc_attr( get_option( 'mll_data_src', self::DEFAULT_DATA_SRC ) );
		?>
		<div class="wrap">
			<h2>Meeting List Lite Settings</h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'mll-group' ); ?>
				<?php do_settings_sections( 'mll-group' ); ?>
				<table class="form-table">
					<tr style="vertical-align: top;">
						<th scope="row">Data Source URL</th>
						<td>
							<input type="text" name="mll_data_src" id="mll_data_src" size="80" value="<?php echo esc_attr( $mll_data_src ); ?>" /><br />
							<label for="mll_data_src">Needs to be valid TSML JSON</label>
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Get an instance of the MLL plugin class.
	 *
	 * This method ensures that only one instance of the MLL class is created during the plugin's lifecycle.
	 *
	 * @return self An instance of the MLL class.
	 */
	public static function get_instance(): self {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}

MLL::get_instance();
