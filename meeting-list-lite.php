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
	private const DEFAULT_TIMEZONE = 'America/New_York';
    private const DEFAULT_DATA_SRC = '';

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
        $timezone = ! empty( $attrs['timezone'] ) ? sanitize_text_field( strtoupper( $attrs['timezone'] ) ) : sanitize_text_field( get_option( 'mll_timezone', self::DEFAULT_TIMEZONE ) );
        $datasrc = ! empty( $attrs['data_src'] ) ? sanitize_text_field( strtoupper( $attrs['data_src'] ) ) : sanitize_text_field( get_option( 'mll_data_src', self::DEFAULT_DATA_SRC ) );
        return '<div id="tsml-ui" data-src="'.$datasrc.'" data-timezone="' .$timezone. '"></div>';
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
        wp_enqueue_script('tsml_lite_ui', 'https://cdn.aws.bmlt.app/tsml.js', [], '4.0', ['in_footer' => true, 'strategy' => 'async']);
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
		// Register plugin settings with WordPress
        register_setting(
            self::SETTINGS_GROUP,
            'mll_data_src',
            [
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ]
        );
		register_setting(
			self::SETTINGS_GROUP,
			'mll_timezone',
			[
				'type' => 'string',
				'default' => self::DEFAULT_TIMEZONE,
				'sanitize_callback' => 'sanitize_text_field',
			]
		);
        $tsml_ui_config = [];
        wp_localize_script(
            'tsml_ui',
            'tsml_react_config',
            $tsml_ui_config
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
		// Create the plugin's settings page in the WordPress admin menu
		add_options_page(
			esc_html__( 'Meeting List Lite Settings', 'mll' ), // Page Title
			esc_html__( 'Meeting List Lite', 'mll' ),          // Menu Title
			'manage_options',            // Capability
			'mll',                      // Menu Slug
			[ static::class, 'draw_settings' ]      // Callback function to display the page content
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
		// Display the plugin's settings page
		$mll_data_src = esc_attr( get_option( 'mll_data_src' ) );
		$mll_timezone = esc_attr( get_option( 'mll_timezone' ) );
		$allowed_html = [
			'select' => [
				'id'   => [],
				'name' => [],
			],
			'option' => [
				'value'   => [],
				'selected'   => [],
			],
		];
		?>
		<div class="wrap">
			<h2>MLL Settings</h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'mll-group' ); ?>
				<?php do_settings_sections( 'mll-group' ); ?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">Time Zone</th>
						<td>
							<?php
							echo wp_kses(
								static::render_select_option(
									'mll_timezone',
									$mll_timezone,
									[
										'America/Chicago' => 'America/Chicago',
                                        'America/Denver' => 'America/Denver',
										'America/Los_Angeles' => 'America/Los_Angeles',
                                        'America/New_York' => 'America/New_York',
									]
								),
								$allowed_html
							);
							?>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">Data Source URL</th>
						<td>
                            <input type="text" name="mll_data_src" size="60" value="<?php echo $mll_data_src; ?>" /><br />
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
     * Render a dropdown select input for plugin settings.
     *
     * This method generates the HTML markup for a dropdown select input field with the specified name
     * and options. It also preselects the option that matches the provided selected value.
     *
     * @param string $name          The name attribute for the select input.
     * @param string $selected_value The value to be preselected in the dropdown.
     * @param array  $options       An associative array of options (value => label) for the dropdown.
     *
     * @return string The generated HTML markup for the select input.
     */
    private static function render_select_option( string $name, string $selected_value, array $options ): string {
        // Render a dropdown select input for settings
        $select_html = "<select id='$name' name='$name'>";
        foreach ( $options as $value => $label ) {
            $selected = selected( $selected_value, $value, false );
            $select_html .= "<option value='$value' $selected>$label</option>";
        }
        $select_html .= '</select>';

        return $select_html;
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
