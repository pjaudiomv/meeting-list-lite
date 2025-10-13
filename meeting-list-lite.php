<?php
/**
 * Plugin Name:       Meeting List Lite
 * Plugin URI:        https://wordpress.org/plugins/meeting-list-lite/
 * Description:       This is a WordPress plugin with minimal settings for displaying meeting lists.
 * Install:           Drop this directory in the "wp-content/plugins/" directory and activate it. You need to specify "[tsml_ui]" in the code section of a page or a post.
 * Contributors:      pjaudiomv
 * Version:           1.1.1
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

	private const MEETINGLISTLITE_VERSION = '1.1.1';
	private const SETTINGS_GROUP = 'meetinglistlite-group';
	private const TSML_CDN_URL = 'https://tsml-ui.code4recovery.org/app.js';
	private const REWRITE_VERSION = '1.0';

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
		register_activation_hook( __FILE__, [ $this, 'activate' ] );
		register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );
	}

	/**
	 * Plugin activation callback.
	 * Registers rewrite rules and flushes permalinks.
	 *
	 * @return void
	 */
	public function activate(): void {
		$base_path = get_option( 'meetinglistlite_base_path', '' );
		if ( ! empty( $base_path ) ) {
			$this->register_rewrite_rules( $base_path );
			flush_rewrite_rules();
		}
		update_option( 'meetinglistlite_rewrite_version', self::REWRITE_VERSION );
	}

	/**
	 * Plugin deactivation callback.
	 * Flushes rewrite rules to clean up.
	 *
	 * @return void
	 */
	public function deactivate(): void {
		flush_rewrite_rules();
		delete_option( 'meetinglistlite_rewrite_version' );
	}


	/**
	 * Register custom rewrite rules.
	 *
	 * @param string $base_path The base path for the rewrite rule (e.g., 'meetings').
	 * * @return void
	 */
	private function register_rewrite_rules( string $base_path ): void {
		if ( empty( $base_path ) ) {
			return;
		}
		$base_path = trim( $base_path, '/' );
		add_rewrite_rule(
			'^' . preg_quote( $base_path, '/' ) . '(/.*)?$',
			'index.php?pagename=' . $base_path,
			'top'
		);
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
		$base_path = get_option( 'meetinglistlite_base_path', '' );

		if ( ! empty( $base_path ) ) {
			$this->register_rewrite_rules( $base_path );

			if ( get_option( 'meetinglistlite_rewrite_version' ) !== self::REWRITE_VERSION ) {
				flush_rewrite_rules();
				update_option( 'meetinglistlite_rewrite_version', self::REWRITE_VERSION );
			}
		}

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
		$base_path = get_option( 'meetinglistlite_base_path', '' );

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
		$base_path_attr = ! empty( $base_path ) ? ' data-path="/' . esc_attr( trim( $base_path, '/' ) ) . '"' : '';

		$content = '<div class="meetinglistlite-fullwidth">';
		$content .= '<div id="tsml-ui" data-src="' . esc_url( $data_src ) . '"' . $timezone_attr . $google_key_attr . $base_path_attr . '></div>';
		$content .= '</div>';
		return $content;
	}

	/**
	 * Get default base CSS.
	 *
	 * @return string Default CSS styles.
	 */
	private static function get_default_css(): string {
		return '.meetinglistlite-fullwidth {
	width: 100vw !important;
	position: relative !important;
	left: 50% !important;
	margin-left: -50vw !important;
	padding: 20px !important;
	box-sizing: border-box !important;
	max-width: none !important;
}

#tsml-ui {
	width: 100% !important;
	min-height: 600px !important;
}';
	}


	/**
	 * Get default TSML UI configuration.
	 * We do this because default config is program specific.
	 * @return array Default configuration array.
	 */
	private static function get_default_tsml_config(): array {
		// Check if data source contains 'client_interface' (BMLT server indicator)
		$data_src = get_option( 'meetinglistlite_data_src', '' );
		if ( str_contains( $data_src, 'client_interface' ) ) {
			// Return NA-specific configuration for BMLT servers
			return [
				'strings' => [
					'en' => [
						'types' => [
							'BT' => 'Basic Text',
							'CPT' => '12 Concepts',
							'JFT' => 'Just For Today',
							'IP' => 'IP Study',
							'IW' => 'It Works How and Why',
							'LC' => 'Living Clean',
							'SPAD' => 'Spiritual Principle a Day',
							'SWG' => 'Step Working Guide Study',
						],
						'type_descriptions' => [
							'O' => 'This meeting is open to addicts and non-addicts alike.',
							'C' => 'This meeting is closed to non-addicts.',
						],
					],
				],
			];
		}

		// Default configuration for non-BMLT sources, program agnostic
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
		// Basic CSS sanitization - remove potentially dangerous content
		$css = wp_strip_all_tags( $input );

		// Remove dangerous protocols from url() functions
		$css = preg_replace( '/url\s*\(\s*["\']?\s*javascript:/i', 'url(', $css );
		$css = preg_replace( '/url\s*\(\s*["\']?\s*data:/i', 'url(', $css );
		$css = preg_replace( '/url\s*\(\s*["\']?\s*vbscript:/i', 'url(', $css );

		// Remove CSS expressions and other dangerous constructs
		$css = preg_replace( '/expression\s*\(/i', '', $css );
		$css = preg_replace( '/-moz-binding\s*:/i', '', $css );
		$css = preg_replace( '/behaviour\s*:/i', '', $css );

		// Remove @import to prevent loading external stylesheets
		$css = preg_replace( '/@import/i', '', $css );

		return $css;
	}

	/**
	 * Sanitize base path input.
	 *
	 * @param string $input Raw base path input.
	 * @return string Sanitized base path.
	 */
	public static function sanitize_base_path( string $input ): string {
		$old_value = get_option( 'meetinglistlite_base_path', '' );
		$new_value = sanitize_text_field( trim( $input, '/' ) );

		// If the value changed, flush rewrite rules
		if ( $old_value !== $new_value ) {
			// Schedule a rewrite flush for the next page load
			update_option( 'meetinglistlite_rewrite_version', '' );
		}

		return $new_value;
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

		$custom_css = (string) get_option( 'meetinglistlite_custom_css', '' );
		// If custom CSS is empty, use default CSS
		if ( '' === $custom_css ) {
			$custom_css = self::get_default_css();
		}
		$custom_css = self::sanitize_custom_css( $custom_css ); // Last-mile Escaping
		wp_register_style( 'meetinglistlite-custom', false, [], self::MEETINGLISTLITE_VERSION );
		wp_enqueue_style( 'meetinglistlite-custom' );
		wp_add_inline_style( 'meetinglistlite-custom', $custom_css );

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
	 * 'meetinglistlite_tsml_config', `meetinglistlite_google_key`, and 'meetinglistlite_custom_css'.
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
			'meetinglistlite_base_path',
			[
				'type' => 'string',
				'sanitize_callback' => [ static::class, 'sanitize_base_path' ],
				'default' => '',
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
		$meetinglistlite_base_path = get_option( 'meetinglistlite_base_path', '' );
		$meetinglistlite_tsml_config = get_option( 'meetinglistlite_tsml_config', '' );
		$meetinglistlite_custom_css = get_option( 'meetinglistlite_custom_css', '' );
		$default_config_json = wp_json_encode( self::get_default_tsml_config(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
		if ( '' === $meetinglistlite_tsml_config ) {
			$meetinglistlite_tsml_config = $default_config_json;
		}
		if ( '' === $meetinglistlite_custom_css ) {
			$meetinglistlite_custom_css = self::get_default_css();
		}
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
							<label for="meetinglistlite_data_src">Needs to be valid TSML JSON/Sheet. This can be a comma-separated string with multiple feed URLs.</label>
						</td>
					</tr>
					<tr style="vertical-align: top;">
						<th scope="row">Google API Key</th>
						<td>
							<input type="text" name="meetinglistlite_google_key" id="meetinglistlite_google_key" size="60" value="<?php echo esc_attr( $meetinglistlite_google_key ); ?>" /><br />
							<label for="meetinglistlite_google_key">Only needed if using Google Sheets</label>
						</td>
					</tr>
					<tr style="vertical-align: top;">
						<th scope="row">Base Path for Pretty URLs</th>
						<td>
							<input type="text" name="meetinglistlite_base_path" id="meetinglistlite_base_path" size="40" value="<?php echo esc_attr( $meetinglistlite_base_path ); ?>" placeholder="" /><br />
							<label for="meetinglistlite_base_path">
								Optional. Enable pretty URLs like <code>/meetings/slug-name</code> instead of hash routing <code>/meetings/#/slug-name</code>.<br />
								Enter the page slug (e.g., "meetings") where you've added the [tsml_ui] shortcode. Leave empty to disable.<br />
								<strong>Note:</strong> After changing this setting, go to <a href="<?php echo esc_url( admin_url( 'options-permalink.php' ) ); ?>">Settings → Permalinks</a> and click "Save Changes" to update rewrite rules.
							</label>
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
							<textarea name="meetinglistlite_tsml_config" id="meetinglistlite_tsml_config" rows="15" cols="80" style="font-family: monospace; font-size: 12px;"><?php echo esc_textarea( $meetinglistlite_tsml_config ); ?></textarea><br />
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
							<textarea name="meetinglistlite_custom_css" id="meetinglistlite_custom_css" rows="15" cols="80" style="font-family: monospace; font-size: 12px;"><?php echo esc_textarea( $meetinglistlite_custom_css ); ?></textarea><br />
							<label for="meetinglistlite_custom_css">Additional CSS to customize the appearance of the meeting list.</label> <a href="https://github.com/code4recovery/tsml-ui/?tab=readme-ov-file#customize-theme-colors" target="_blank" rel="noopener noreferrer">View TSML UI CSS Documentation</a><br />
							<p><strong>Example:</strong></p>
							<pre style="background: #f0f0f0; padding: 10px; margin-top: 5px; font-size: 11px;">/* Customize theme colors */
#tsml-ui {
  --alert-background: #faf4e0;
  --alert-text: #998a5e;
  --background: #fff;
  --border-radius: 4px;
  --focus: #0d6efd40;
  --font-family: system-ui, -apple-system, sans-serif;
  --font-size: 16px;
  --in-person: #146c43;
  --inactive: #b02a37;
  --link: #0d6efd;
  --online: #0a58ca;
  --online-background-image: url(https://images.unsplash.com/photo-1588196749597-9ff075ee6b5b?crop=entropy&cs=tinysrgb&fit=crop&fm=jpg&h=1440&ixid=MnwxfDB8MXxhbGx8fHx8fHx8fHwxNjIyMTIzODkw&ixlib=rb-1.2.1&q=80&utm_campaign=api-credit&utm_medium=referral&utm_source=unsplash_source&w=1920);
  --text: #212529;
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
