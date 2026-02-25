<?php
/**
 * Plugin Name: Elementor Post Layout Widget
 * Description: Extensión de Elementor para crear layouts personalizados con posts de categorías y espacios para anuncios de Google
 * Version: 1.7.0
 * Author: Cristian Cartes
 * Requires at least: 5.9
 * Requires PHP: 7.4
 * Text Domain: elementor-post-layout
 * Domain Path: /languages
 */

namespace ELPL;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

// Define plugin constants
define('ELPL_VERSION', '1.8.0');
define('ELPL_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ELPL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('ELPL_PLUGIN_FILE', __FILE__);

/**
 * Main Plugin Class
 */
final class Elementor_Post_Layout
{

	/**
	 * Instance
	 *
	 * @var Elementor_Post_Layout The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Elementor_Post_Layout An instance of the class.
	 */
	public static function instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 */
	public function __construct()
	{
		add_action('plugins_loaded', array($this, 'init'));
	}

	/**
	 * Initialize the plugin
	 */
	public function init()
	{
		// Check if Elementor is installed and activated
		if (!did_action('elementor/loaded')) {
			add_action('admin_notices', array($this, 'admin_notice_missing_elementor'));
			return;
		}

		// Check for required Elementor version
		if (!version_compare(ELEMENTOR_VERSION, '3.0.0', '>=')) {
			add_action('admin_notices', array($this, 'admin_notice_minimum_elementor_version'));
			return;
		}

		// Check for required PHP version
		if (version_compare(PHP_VERSION, '7.4', '<')) {
			add_action('admin_notices', array($this, 'admin_notice_minimum_php_version'));
			return;
		}

		// Register widget
		add_action('elementor/widgets/register', array($this, 'register_widgets'));

		// Register widget categories
		add_action('elementor/elements/categories_registered', array($this, 'register_widget_categories'));

		// Register widget styles
		add_action('elementor/frontend/after_enqueue_styles', array($this, 'enqueue_widget_styles'));

		// Register widget scripts
		add_action('elementor/frontend/after_register_scripts', array($this, 'enqueue_widget_scripts'));

		// Load plugin text domain
		add_action('init', array($this, 'load_textdomain'), 11);
	}

	/**
	 * Admin notice for missing Elementor
	 */
	public function admin_notice_missing_elementor()
	{
		if (!current_user_can('activate_plugins')) {
			return;
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__('"%1$s" requiere que "%2$s" esté instalado y activado.', 'elementor-post-layout'),
			'<strong>' . esc_html__('Elementor Post Layout Widget', 'elementor-post-layout') . '</strong>',
			'<strong>' . esc_html__('Elementor', 'elementor-post-layout') . '</strong>'
		);

		printf('<div class="notice notice-warning is-dismissible"><p>%s</p></div>', wp_kses_post($message));
	}

	/**
	 * Admin notice for minimum Elementor version
	 */
	public function admin_notice_minimum_elementor_version()
	{
		if (!current_user_can('activate_plugins')) {
			return;
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__('"%1$s" requiere "%2$s" versión %3$s o superior.', 'elementor-post-layout'),
			'<strong>' . esc_html__('Elementor Post Layout Widget', 'elementor-post-layout') . '</strong>',
			'<strong>' . esc_html__('Elementor', 'elementor-post-layout') . '</strong>',
			'3.0.0'
		);

		printf('<div class="notice notice-warning is-dismissible"><p>%s</p></div>', wp_kses_post($message));
	}

	/**
	 * Admin notice for minimum PHP version
	 */
	public function admin_notice_minimum_php_version()
	{
		if (!current_user_can('activate_plugins')) {
			return;
		}

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__('"%1$s" requiere "%2$s" versión %3$s o superior.', 'elementor-post-layout'),
			'<strong>' . esc_html__('Elementor Post Layout Widget', 'elementor-post-layout') . '</strong>',
			'<strong>' . esc_html__('PHP', 'elementor-post-layout') . '</strong>',
			'7.4'
		);

		printf('<div class="notice notice-warning is-dismissible"><p>%s</p></div>', wp_kses_post($message));
	}

	/**
	 * Register Widgets
	 */
	public function register_widgets($widgets_manager)
	{
		$widget_files = array(
			'includes/class-elpl-widget.php',
			'includes/class-elpl-destacados-widget.php',
			'includes/class-elpl-eventos-widget.php',
			'includes/class-elpl-noticias-generales-widget.php',
			'includes/class-elpl-opinion-widget.php',
			'includes/class-elpl-universal-posts-widget.php',
			'includes/class-elpl-multimedia-widget.php',
			'includes/class-elpl-archive-posts-widget.php',
			'includes/class-elpl-archive-destacado-widget.php',
			'includes/class-elpl-magazine-widget.php',
		);

		foreach ($widget_files as $file) {
			$path = ELPL_PLUGIN_DIR . $file;
			if (file_exists($path)) {
				require_once $path;
			}
		}

		if (class_exists('\ELPL\Widgets\ELPL_Post_Layout_Widget')) {
			$widgets_manager->register(new \ELPL\Widgets\ELPL_Post_Layout_Widget());
		}

		if (class_exists('\ELPL\Widgets\ELPL_Destacados_Widget')) {
			$widgets_manager->register(new \ELPL\Widgets\ELPL_Destacados_Widget());
		}

		if (class_exists('\ELPL\Widgets\ELPL_Eventos_Widget')) {
			$widgets_manager->register(new \ELPL\Widgets\ELPL_Eventos_Widget());
		}

		if (class_exists('\ELPL\Widgets\ELPL_Noticias_Generales_Widget')) {
			$widgets_manager->register(new \ELPL\Widgets\ELPL_Noticias_Generales_Widget());
		}

		if (class_exists('\ELPL\Widgets\ELPL_Opinion_Widget')) {
			$widgets_manager->register(new \ELPL\Widgets\ELPL_Opinion_Widget());
		}

		if (class_exists('\ELPL\Widgets\ELPL_Universal_Posts_Widget')) {
			$widgets_manager->register(new \ELPL\Widgets\ELPL_Universal_Posts_Widget());
		}

		if (class_exists('\ELPL\Widgets\ELPL_Multimedia_Widget')) {
			$widgets_manager->register(new \ELPL\Widgets\ELPL_Multimedia_Widget());
		}

		if (class_exists('\ELPL\Widgets\ELPL_Archive_Posts_Widget')) {
			$widgets_manager->register(new \ELPL\Widgets\ELPL_Archive_Posts_Widget());
		}

		if (class_exists('\ELPL\Widgets\ELPL_Archive_Destacado_Widget')) {
			$widgets_manager->register(new \ELPL\Widgets\ELPL_Archive_Destacado_Widget());
		}

		if (class_exists('\ELPL\Widgets\ELPL_Magazine_Widget')) {
			$widgets_manager->register(new \ELPL\Widgets\ELPL_Magazine_Widget());
		}
	}

	/**
	 * Register Widget Categories
	 */
	public function register_widget_categories($elements_manager)
	{
		$elements_manager->add_category(
			'cartes-widgets',
			array(
				'title' => esc_html__('Cartes widgets', 'elementor-post-layout'),
				'icon' => 'fa fa-plug',
			)
		);
	}

	/**
	 * Enqueue widget styles
	 */
	public function enqueue_widget_styles()
	{
		wp_enqueue_style(
			'elpl-widget-styles',
			ELPL_PLUGIN_URL . 'assets/css/widget-styles.css',
			array(),
			ELPL_VERSION
		);
	}

	/**
	 * Enqueue widget scripts
	 */
	public function enqueue_widget_scripts()
	{
		// Register scripts if needed in the future
	}

	/**
	 * Load plugin text domain
	 */
	public function load_textdomain()
	{
		load_plugin_textdomain(
			'elementor-post-layout',
			false,
			dirname(plugin_basename(__FILE__)) . '/languages'
		);
	}
}

// Initialize the plugin
Elementor_Post_Layout::instance();
