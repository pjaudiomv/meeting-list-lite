<?php
/**
 * Plugin Name:       Meeting List Lite
 * Plugin URI:        https://wordpress.org/plugins/meeting-list-lite/
 * Description:       This is a WordPress plugin with minimal settings for displaying meeting lists.
 * Install:           Drop this directory in the "wp-content/plugins/" directory and activate it. You need to specify "[tsml_ui]" in the code section of a page or a post.
 * Contributors:      pjaudiomv
 * Version:           1.0.1
 * Requires PHP:      8.0
 * Requires at least: 5.3
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

namespace MEETINGLISTLITEPlugin;

if ( ! defined( 'WPINC' ) ) {
	die( 'Sorry, but you cannot access this page directly.' );
}

/**
 * Class MEETINGLISTLITE
 * @package MEETINGLISTLITEPlugin
 */
class MEETINGLISTLITE {

	private const MEETINGLISTLITE_VERSION = '1.0.0';
	private const SETTINGS_GROUP = 'meetinglistlite-group';
	private const TSML_CDN_URL = 'https://tsml-ui.code4recovery.org/app.js';

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
			add_shortcode( 'tsml_ui', [ static::class, 'setup_shortcode' ] );
			add_action(
				'wp',
				function () {
					if ( is_singular() ) {
						$post = get_post();
						if ( $post && has_shortcode( $post->post_content, 'tsml_ui' ) ) {
							add_action( 'wp_enqueue_scripts', [ $this, 'assets' ] );
						}
					}
				}
			);
		}
	}

	/**
	 * Setup and render the MEETINGLISTLITE shortcode.
	 *
	 * This method processes the attributes provided to the [tsml_ui] shortcode and
	 * sets up the necessary shortcode attributes for rendering the meeting list
	 * If no shortcode attributes are provided, default values from plugin options
	 * are used.
	 *
	 * @param string|array $attrs Shortcode attributes.
	 * @return string The HTML for the MEETINGLISTLITE shortcode.
	 */
	public static function setup_shortcode( string|array $attrs = [] ): string {
		$attrs = shortcode_atts(
			[
				'data_src'   => '',
				'google_key' => '',
				'timezone'   => '',
			],
			(array) $attrs,
			'tsml_ui'
		);
		$option_data_src   = esc_url_raw( get_option( 'meetinglistlite_data_src' ) );
		$option_google_key = sanitize_text_field( get_option( 'meetinglistlite_google_key' ) );
		$data_src = $attrs['data_src']
			? esc_url_raw( $attrs['data_src'] )
			: ( ! empty( $option_data_src ) ? $option_data_src : '' );
		$google_key = $attrs['google_key']
			? sanitize_text_field( $attrs['google_key'] )
			: ( ! empty( $option_google_key ) ? $option_google_key : '' );
		$timezone = $attrs['timezone']
			? sanitize_text_field( $attrs['timezone'] )
			: sanitize_text_field( get_option( 'timezone_string' ) );
		$timezone_attr   = $timezone ? ' data-timezone="' . esc_attr( $timezone ) . '"' : '';
		$google_key_attr = $google_key ? ' data-google="' . esc_attr( $google_key ) . '"' : '';
		$content = '<div class="meetinglistlite-fullwidth">';
		$content .= '<div id="tsml-ui" data-src="' . esc_url( $data_src ) . '"' . $timezone_attr . $google_key_attr . '></div>';
		$content .= '</div>';
		return $content;
	}

	/**
	 * Get default TSML UI configuration.
	 * We do this because default config is program specific.
	 * @return array Default configuration array.
	 */
	private static function get_default_tsml_config(): array {
		return [
			'strings' => [
				'en' => [
					'type_descriptions' => [
						'O' => null,
						'C' => null,
					],
				],
			],
		];
	}

	/**
	 * Get TSML UI configuration (custom or default).
	 *
	 * @return array Configuration array.
	 */
	private static function get_tsml_config(): array {
		$custom_config_json = get_option( 'meetinglistlite_tsml_config' );
		$default_config = self::get_default_tsml_config();

		if ( empty( $custom_config_json ) ) {
			return $default_config;
		}

		$custom_config = json_decode( $custom_config_json, true );

		// If JSON is invalid, return default config
		if ( json_last_error() !== JSON_ERROR_NONE ) {
			return $default_config;
		}

		// Merge custom config with default (custom overrides default)
		return array_replace_recursive( $default_config, $custom_config );
	}

	/**
	 * Validate and sanitize TSML UI config JSON.
	 *
	 * @param string $input Raw JSON input.
	 * @return string Sanitized JSON or empty string if invalid.
	 */
	public static function sanitize_tsml_config( string $input ): string {
		// Allow empty input
		if ( empty( trim( $input ) ) ) {
			return '';
		}

		// Decode JSON to validate it
		$decoded = json_decode( $input, true );

		// Check for JSON errors
		if ( json_last_error() !== JSON_ERROR_NONE ) {
			add_settings_error(
				'meetinglistlite_tsml_config',
				'invalid_json',
				'Invalid JSON format in TSML UI Configuration. Please check your syntax.',
				'error'
			);
			// Return the previous valid value
			return get_option( 'meetinglistlite_tsml_config', '' );
		}

		// Re-encode to ensure clean JSON
		return wp_json_encode( $decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
	}

	/**
	 * Sanitize custom CSS input.
	 *
	 * @param string $input Raw CSS input.
	 * @return string Sanitized CSS.
	 */
	public static function sanitize_custom_css( string $input ): string {
		if ( empty( trim( $input ) ) ) {
			return '';
		}
		// Basic CSS sanitization - remove script tags and potentially dangerous content
		$css = wp_strip_all_tags( $input );
		$css = preg_replace( '/javascript:/i', '', $css );
		$css = preg_replace( '/expression\s*\(/i', '', $css );
		$css = preg_replace( '/vbscript:/i', '', $css );
		$css = preg_replace( '/@import/i', '', $css );
		$css = preg_replace( '/url\s*\([^)]*\)/i', '', $css );
		$css = preg_replace( '/@supports\b/i', '', $css );
		return $css;
	}

	/**
	 * Enqueue plugin styles and scripts.
	 *
	 * This method is responsible for enqueueing the necessary CSS and JavaScript
	 * files for the MEETINGLISTLITE plugin to function correctly.
	 *
	 * @return void
	 */
	public function assets(): void {
		wp_enqueue_script(
			'meetinglistlite_tsml_ui',
			self::TSML_CDN_URL,
			[],
			self::MEETINGLISTLITE_VERSION,
			[
				'in_footer' => true,
				'strategy' => 'defer',
			]
		);
		wp_register_style( 'meetinglistlite-base', false, [], self::MEETINGLISTLITE_VERSION );
		wp_enqueue_style( 'meetinglistlite-base' );
		$base_css = '.meetinglistlite-fullwidth{width:100vw!important;position:relative!important;left:50%!important;margin-left:-50vw!important;padding:20px!important;box-sizing:border-box!important;max-width:none!important}#tsml-ui{width:100%!important;min-height:600px!important}';
		wp_add_inline_style( 'meetinglistlite-base', $base_css );
		$custom_css = (string) get_option( 'meetinglistlite_custom_css', '' );
		$custom_css = self::sanitize_custom_css( $custom_css ); // Last-mile Escaping
		if ( '' !== $custom_css ) {
			wp_register_style( 'meetinglistlite-custom', false, [], self::MEETINGLISTLITE_VERSION );
			wp_enqueue_style( 'meetinglistlite-custom' );
			wp_add_inline_style( 'meetinglistlite-custom', $custom_css );
		}
		$tsml_ui_config = self::get_tsml_config();
		wp_localize_script(
			'meetinglistlite_tsml_ui',
			'tsml_react_config',
			$tsml_ui_config
		);
	}

	/**
	 * Register plugin settings with WordPress.
	 *
	 * This method registers the plugin settings with WordPress using the
	 * `register_setting` function. It defines the settings for 'meetinglistlite_data_src',
	 * 'meetinglistlite_timezone' and 'meetinglistlite_tsml_config'.
	 *
	 * @return void
	 */
	public static function register_settings(): void {
		register_setting(
			self::SETTINGS_GROUP,
			'meetinglistlite_data_src',
			[
				'type' => 'string',
				'sanitize_callback' => 'esc_url_raw',
			]
		);
		register_setting(
			self::SETTINGS_GROUP,
			'meetinglistlite_google_key',
			[
				'type' => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			]
		);
		register_setting(
			self::SETTINGS_GROUP,
			'meetinglistlite_tsml_config',
			[
				'type' => 'string',
				'sanitize_callback' => [ static::class, 'sanitize_tsml_config' ],
			]
		);
		register_setting(
			self::SETTINGS_GROUP,
			'meetinglistlite_custom_css',
			[
				'type' => 'string',
				'sanitize_callback' => [ static::class, 'sanitize_custom_css' ],
			]
		);
	}

	/**
	 * Create the plugin's settings menu in the WordPress admin.
	 *
	 * This method adds the MEETINGLISTLITE plugin's settings page to the WordPress admin menu.
	 * It also adds a settings link in the list of plugins on the plugins page.
	 *
	 * @return void
	 */
	public static function create_menu(): void {
		add_options_page(
			esc_html__( 'Meeting List Lite Settings', 'meeting-list-lite' ), // Page Title
			esc_html__( 'Meeting List Lite', 'meeting-list-lite' ),         // Menu Title
			'manage_options',                                  // Capability
			'meetinglistlite',                                            // Menu Slug
			[ static::class, 'draw_settings' ]                // Callback function to display the page content
		);
		// Add a settings link in the plugins list
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ static::class, 'settings_link' ] );
	}

	/**
	 * Add a "Settings" link for the plugin in the WordPress admin.
	 *
	 * This method adds a "Settings" link for the MEETINGLISTLITE plugin in the WordPress admin
	 * under the plugins list.
	 *
	 * @param array $links An array of plugin action links.
	 *
	 * @return array An updated array of plugin action links.
	 */
	public static function settings_link( array $links ): array {
		// Add a "Settings" link for the plugin in the WordPress admin
		$settings_url = esc_url( admin_url( 'options-general.php?page=meetinglistlite' ) );
		$links[] = "<a href='{$settings_url}'>" . esc_html__( 'Settings', 'meeting-list-lite' ) . '</a>';
		return $links;
	}

	/**
	 * Display the plugin's settings page.
	 *
	 * This method renders and displays the settings page for the MEETINGLISTLITE plugin in the WordPress admin.
	 * It includes form fields for configuring plugin settings such as theme, language, layout, and special keytags.
	 *
	 * @return void
	 */
	public static function draw_settings(): void {
		$meetinglistlite_data_src = get_option( 'meetinglistlite_data_src' );
		$meetinglistlite_google_key = get_option( 'meetinglistlite_google_key' );
		$meetinglistlite_tsml_config = get_option( 'meetinglistlite_tsml_config', '' );
		$meetinglistlite_custom_css = get_option( 'meetinglistlite_custom_css', '' );
		$default_config_json = wp_json_encode( self::get_default_tsml_config(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
		?>
		<div class="wrap">
			<h2>Meeting List Lite Settings</h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'meetinglistlite-group' ); ?>
				<?php do_settings_sections( 'meetinglistlite-group' ); ?>
				<table class="form-table">
					<tr style="vertical-align: top;">
						<th scope="row">Data Source URL</th>
						<td>
							<input type="text" name="meetinglistlite_data_src" id="meetinglistlite_data_src" size="80" value="<?php echo esc_attr( $meetinglistlite_data_src ); ?>" /><br />
							<label for="meetinglistlite_data_src">Needs to be valid TSML JSON/Sheet</label>
						</td>
					</tr>
					<tr style="vertical-align: top;">
						<th scope="row">Google API Key</th>
						<td>
							<input type="text" name="meetinglistlite_google_key" id="meetinglistlite_google_key" size="60" value="<?php echo esc_attr( $meetinglistlite_google_key ); ?>" /><br />
							<label for="meetinglistlite_google_key">Only needed if using Google Sheets</label>
						</td>
					</tr>
				</table>

				<hr style="margin: 30px 0;">

				<h3>Advanced Settings</h3>
				<p>These settings provide fine-grained control over the TSML UI appearance and behavior. <a href="https://github.com/code4recovery/tsml-ui/?tab=readme-ov-file#configure" target="_blank" rel="noopener noreferrer">View full configuration documentation</a></p>

				<table class="form-table">
					<tr style="vertical-align: top;">
						<th scope="row">TSML UI Configuration</th>
						<td>
							<textarea name="meetinglistlite_tsml_config" id="meetinglistlite_tsml_config" rows="20" cols="80" style="font-family: monospace; font-size: 12px;"><?php echo esc_textarea( $meetinglistlite_tsml_config ); ?></textarea><br />
							<label for="meetinglistlite_tsml_config">Custom TSML UI configuration in JSON format. Leave empty to use defaults.</label><br />
							<details>
								<summary><strong>Show Default Configuration</strong></summary>
								<pre style="background: #f0f0f0; padding: 10px; margin-top: 10px; overflow: auto; max-height: 400px; font-size: 11px;"><?php echo esc_html( $default_config_json ); ?></pre>
							</details>
						</td>
					</tr>
					<tr style="vertical-align: top;">
						<th scope="row">Custom CSS</th>
						<td>
							<textarea name="meetinglistlite_custom_css" id="meetinglistlite_custom_css" rows="10" cols="80" style="font-family: monospace; font-size: 12px;"><?php echo esc_textarea( $meetinglistlite_custom_css ); ?></textarea><br />
							<label for="meetinglistlite_custom_css">Additional CSS to customize the appearance of the meeting list.</label><br />
							<p><strong>Example:</strong></p>
							<pre style="background: #f0f0f0; padding: 10px; margin-top: 5px; font-size: 11px;">/* Change the primary color */
#tsml-ui .btn-primary {
	background-color: #007cba;
	border-color: #007cba;
}

/* Hide certain elements */
#tsml-ui .meeting-type {
	display: none;
}</pre>
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Get an instance of the MEETINGLISTLITE plugin class.
	 *
	 * This method ensures that only one instance of the MEETINGLISTLITE class is created during the plugin's lifecycle.
	 *
	 * @return self An instance of the MEETINGLISTLITE class.
	 */
	public static function get_instance(): self {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}

MEETINGLISTLITE::get_instance();
