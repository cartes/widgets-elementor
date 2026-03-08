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
		add_action('wp_enqueue_scripts', array($this, 'enqueue_widget_scripts'));

		// AJAX handlers for load-more pagination
		add_action('wp_ajax_elpl_load_more_posts', array($this, 'ajax_load_more_posts'));
		add_action('wp_ajax_nopriv_elpl_load_more_posts', array($this, 'ajax_load_more_posts'));

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
			'includes/class-elpl-destacados-top-widget.php',
			'includes/class-elpl-mobile-nav-widget.php',
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

		if (class_exists('\ELPL\Widgets\ELPL_Destacados_Top_Widget')) {
			$widgets_manager->register(new \ELPL\Widgets\ELPL_Destacados_Top_Widget());
		}

		if (class_exists('\ELPL\Widgets\ELPL_Mobile_Nav_Widget')) {
			$widgets_manager->register(new \ELPL\Widgets\ELPL_Mobile_Nav_Widget());
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
		wp_enqueue_script(
			'elpl-widgets',
			ELPL_PLUGIN_URL . 'assets/js/elpl-widgets.js',
			array(),
			ELPL_VERSION,
			true
		);

		wp_register_script(
			'elpl-mobile-nav',
			ELPL_PLUGIN_URL . 'assets/js/elpl-mobile-nav.js',
			array(),
			ELPL_VERSION,
			true
		);

		wp_localize_script('elpl-widgets', 'elplWidgets', array(
			'ajaxUrl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('elpl_load_more'),
		));
	}

	/**
	 * AJAX handler: load more posts for the Destacados widget (and future widgets).
	 */
	public function ajax_load_more_posts()
	{
		check_ajax_referer('elpl_load_more', 'nonce');

		$widget = sanitize_key(wp_unslash($_POST['widget'] ?? ''));
		$category = sanitize_key(wp_unslash($_POST['category'] ?? ''));
		$per_page = absint($_POST['per_page'] ?? 7);
		$offset = absint($_POST['offset'] ?? 0);
		$date_format = sanitize_text_field(wp_unslash($_POST['date_format'] ?? 'd M Y'));
		$show_date = sanitize_key(wp_unslash($_POST['show_date'] ?? 'yes'));
		$show_excerpt = sanitize_key(wp_unslash($_POST['show_excerpt'] ?? 'yes'));
		$post_type = sanitize_key(wp_unslash($_POST['post_type'] ?? 'post'));
		$meta_persona = sanitize_key(wp_unslash($_POST['meta_persona'] ?? 'opinioncolumn_opinionAuthor'));
		$meta_cargo = sanitize_key(wp_unslash($_POST['meta_cargo'] ?? 'person_cargo'));
		$category_id = absint($_POST['category_id'] ?? 0);
		$show_image = sanitize_key(wp_unslash($_POST['show_image'] ?? 'yes'));
		$meta_type = sanitize_key(wp_unslash($_POST['meta_type'] ?? 'date'));
		$meta_data_str = sanitize_text_field(wp_unslash($_POST['meta_data'] ?? ''));
		$meta_data = !empty($meta_data_str) ? explode(',', $meta_data_str) : array();
		$meta_separator = sanitize_text_field(wp_unslash($_POST['meta_separator'] ?? '///'));
		$taxonomy = sanitize_key(wp_unslash($_POST['taxonomy'] ?? ''));
		$term_id = absint($_POST['term_id'] ?? 0);
		$author_id = absint($_POST['author_id'] ?? 0);

		$args = array(
			'post_type' => $post_type ?: 'post',
			'posts_per_page' => $per_page,
			'offset' => $offset,
			'post_status' => 'publish',
			'orderby' => 'date',
			'order' => 'DESC',
		);

		if (!empty($category)) {
			$args['category_name'] = $category;
		}

		// Universal widget uses term ID, which takes precedence over slug
		if ($category_id > 0) {
			unset($args['category_name']);
			$args['cat'] = $category_id;
		}

		if (!empty($taxonomy) && $term_id > 0) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $term_id,
				),
			);
		}

		if ($author_id > 0) {
			$args['author'] = $author_id;
		}

		$query = new \WP_Query($args);

		$html = '';
		$has_more = false;

		if ($query->have_posts()) {
			ob_start();
			while ($query->have_posts()) {
				$query->the_post();
				if ('elpl_noticias_generales' === $widget) {
					$thumb_url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
					?>
					<div class="elpl-ng-card elpl-ng-list-card">
						<a href="<?php the_permalink(); ?>" class="elpl-ng-list-thumb">
							<img src="<?php echo esc_url((string) $thumb_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
						</a>
						<div class="elpl-ng-list-content">
							<?php if (!empty($meta_data)): ?>
								<div class="elpl-ng-meta" style="font-size: 0.85em; color: #777; margin-bottom: 8px;">
									<?php foreach ($meta_data as $meta): ?>
										<span class="elpl-ng-meta-<?php echo esc_attr($meta); ?>">
											<?php
											switch ($meta) {
												case 'author':
													echo esc_html(get_the_author_meta('display_name', get_post_field('post_author', get_the_ID())));
													break;
												case 'date':
													echo esc_html(get_the_date('', get_the_ID()));
													break;
												case 'time':
													echo esc_html(get_the_time('', get_the_ID()));
													break;
												case 'comments':
													echo esc_html(get_comments_number(get_the_ID()));
													break;
												case 'modified':
													echo esc_html(get_the_modified_date('', get_the_ID()));
													break;
											}
											?>
										</span>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
							<h4 class="elpl-ng-title"><a href="<?php the_permalink(); ?>"><?php echo esc_html(get_the_title()); ?></a></h4>
						</div>
					</div>
					<?php
				} elseif ('elpl_opinion_widget' === $widget) {
					$opinion_id = get_the_ID();
					$persona_id = get_post_meta($opinion_id, $meta_persona, true);
					if (is_array($persona_id) && !empty($persona_id)) {
						if (isset($persona_id['opinionAuthor'])) {
							$persona_id = $persona_id['opinionAuthor'];
						} else {
							$persona_id = array_values($persona_id)[0];
						}
					}
					$persona_name = '';
					$persona_cargo_val = '';
					$persona_img = '';
					if ($persona_id) {
						$persona_post = get_post($persona_id);
						if ($persona_post && $persona_post->post_type === 'person') {
							$persona_name = $persona_post->post_title;
							$persona_cargo_val = get_post_meta($persona_id, $meta_cargo, true);
							if (is_array($persona_cargo_val) && !empty($persona_cargo_val)) {
								$persona_cargo_val = $persona_cargo_val[0];
							}
							$persona_img = get_the_post_thumbnail_url($persona_id, 'medium');
						}
					}
					?>
					<div class="elpl-opinion-card">
						<div class="elpl-opinion-card-top">
							<div class="elpl-opinion-persona-img"
								style="background-image: url('<?php echo esc_url((string) $persona_img); ?>'); background-position: 10% 10%;">
								<?php if (!$persona_img): ?><i class="eicon-person"></i><?php endif; ?>
							</div>
							<div class="elpl-opinion-content">
								<div class="elpl-opinion-date"><?php echo esc_html(get_the_date('M j, Y')); ?></div>
								<h3 class="elpl-opinion-title">
									<a href="<?php the_permalink(); ?>"><?php echo esc_html(get_the_title()); ?></a>
								</h3>
								<div class="elpl-opinion-excerpt">
									<?php echo esc_html(wp_trim_words(get_the_excerpt(), 18)); ?>
								</div>
							</div>
						</div>
						<div class="elpl-opinion-meta-box">
							<div class="elpl-persona-name"><?php echo esc_html($persona_name); ?></div>
							<div class="elpl-persona-title"><?php echo esc_html($persona_cargo_val); ?></div>
						</div>
					</div>
					<?php
				} elseif ('elpl_universal_posts_widget' === $widget) {
					$thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'medium');
					?>
					<article class="elpl-universal-card">
						<?php if ('yes' === $show_image && $thumbnail): ?>
							<div class="elpl-universal-image" style="background-image: url('<?php echo esc_url($thumbnail); ?>');">
								<a href="<?php the_permalink(); ?>" class="elpl-full-link"></a>
							</div>
						<?php endif; ?>
						<div class="elpl-universal-content">
							<?php if ('yes' === $show_date): ?>
								<div class="elpl-universal-date"><?php echo esc_html(get_the_date('M j, Y')); ?></div>
							<?php endif; ?>
							<h3 class="elpl-universal-title">
								<a href="<?php the_permalink(); ?>"><?php echo esc_html(get_the_title()); ?></a>
							</h3>
						</div>
					</article>
					<?php
				} elseif ('elpl_multimedia_widget' === $widget) {
					$thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'medium');
					?>
					<article class="elpl-multimedia-card">
						<a href="<?php the_permalink(); ?>" class="elpl-multimedia-link">
							<div class="elpl-multimedia-thumbnail">
								<?php if ($thumbnail): ?>
									<img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
								<?php else: ?>
									<div class="elpl-multimedia-no-image"></div>
								<?php endif; ?>
								<div class="elpl-multimedia-play-overlay">
									<div class="elpl-multimedia-play-icon">
										<svg width="68" height="48" viewBox="0 0 68 48" xmlns="http://www.w3.org/2000/svg">
											<path
												d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z"
												fill="currentColor" />
											<path d="M 45,24 27,14 27,34" fill="#fff" />
										</svg>
									</div>
								</div>
							</div>
							<div class="elpl-multimedia-content">
								<h3 class="elpl-multimedia-title"><?php echo esc_html(get_the_title()); ?></h3>
								<div class="elpl-multimedia-date"><?php echo esc_html(get_the_date('M j, Y')); ?></div>
							</div>
						</a>
					</article>
					<?php
				} elseif ('elpl_post_layout' === $widget) {
					// Formato lista horizontal: imagen izq. + título der. (igual a render_small_post)
					$thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'large');
					$post_cats = get_the_category();
					?>
					<article class="elpl-post elpl-small-post">
						<?php if ($thumbnail): ?>
							<div class="elpl-post-thumbnail-small">
								<a href="<?php the_permalink(); ?>">
									<img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr(get_the_title()); ?>"
										class="elpl-post-image-small">
								</a>
							</div>
						<?php endif; ?>
						<div class="elpl-post-content-small">
							<?php if (!empty($post_cats)): ?>
								<span class="elpl-small-category-mobile">
									<a href="<?php echo esc_url(get_category_link($post_cats[0]->term_id)); ?>"
										style="color:inherit;text-decoration:none;">
										<?php echo esc_html($post_cats[0]->name); ?>
									</a>
								</span>
							<?php endif; ?>
							<h3 class="elpl-post-title-small">
								<a href="<?php the_permalink(); ?>"><?php echo esc_html(get_the_title()); ?></a>
							</h3>
							<?php if ('yes' === $show_date): ?>
								<div class="elpl-post-meta-small">
									<span class="elpl-post-date-small"><?php echo esc_html(get_the_date($date_format)); ?></span>
								</div>
							<?php endif; ?>
						</div>
					</article>
					<?php
				} elseif ('elpl_destacados_top' === $widget) {

					// Secondary post card for Destacados Top widget
					switch ($meta_type) {
						case 'category':
							$cats = get_the_category();
							$meta_display = $cats ? esc_html($cats[0]->name) : '';
							break;
						case 'author':
							$meta_display = esc_html(get_the_author());
							break;
						case 'none':
							$meta_display = '';
							break;
						case 'date':
						default:
							$meta_display = esc_html(get_the_date($date_format));
					}
					?>
					<div class="elpl-top-post">
						<a href="<?php the_permalink(); ?>" class="elpl-top-post-link">
							<div class="elpl-top-post-image">
								<?php the_post_thumbnail('medium_large', array('class' => 'elpl-top-post-thumb')); ?>
							</div>
							<div class="elpl-top-post-text">
								<?php if ($meta_display !== ''): ?>
									<div class="elpl-top-post-date"><?php echo $meta_display; ?></div>
								<?php endif; ?>
								<h3 class="elpl-top-post-title"><?php echo esc_html(get_the_title()); ?></h3>
							</div>
						</a>
					</div>
					<?php
				} elseif ('elpl_archive_destacado_widget' === $widget) {
					?>
					<article class="elpl-archive-post elpl-side-post">
						<div class="elpl-archive-image"
							style="background-image: url('<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>');">
							<a href="<?php the_permalink(); ?>" class="elpl-full-link"></a>
						</div>
						<div class="elpl-archive-content">
							<h3 class="elpl-archive-title"><a href="<?php the_permalink(); ?>">
									<?php echo esc_html(get_the_title()); ?>
								</a></h3>
						</div>
					</article>
					<?php
				} else {
					// Default: Destacados card format
					?>
					<div class="elpl-grid-post">
						<a href="<?php the_permalink(); ?>" class="elpl-grid-image-link">
							<div class="elpl-grid-image-container">
								<?php the_post_thumbnail('medium', array('class' => 'elpl-grid-img')); ?>
							</div>
							<div class="elpl-grid-content">
								<?php if ('yes' === $show_date): ?>
									<div class="elpl-post-date"><?php echo esc_html(get_the_date($date_format)); ?></div>
								<?php endif; ?>
								<h3 class="elpl-grid-title"><?php echo esc_html(get_the_title()); ?></h3>
								<?php if ('yes' === $show_excerpt): ?>
									<div class="elpl-grid-excerpt">
										<?php echo esc_html(wp_trim_words(get_the_excerpt(), 15)); ?>
									</div>
								<?php endif; ?>
							</div>
						</a>
					</div>
					<?php
				}
			}
			$html = ob_get_clean();

			$next_offset = $offset + $per_page;
			// Check whether there are more posts after this batch
			$count_query = new \WP_Query(array_merge($args, array(
				'posts_per_page' => 1,
				'offset' => $next_offset,
				'fields' => 'ids',
				'no_found_rows' => false,
			)));
			$has_more = $count_query->have_posts();
			wp_reset_postdata();
		}

		wp_send_json_success(array(
			'html' => $html,
			'has_more' => $has_more,
			'next_offset' => $offset + $per_page,
		));
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
