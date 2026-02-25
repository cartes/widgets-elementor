<?php
namespace ELPL\Widgets;

if (!defined('ABSPATH')) {
	exit;
}

// Load SVG previews
require_once ELPL_PLUGIN_DIR . 'includes/svg-previews.php';

/**
 * Elementor Post Layout Widget
 */
class ELPL_Post_Layout_Widget extends \Elementor\Widget_Base
{

	/**
	 * Get widget name
	 */
	public function get_name()
	{
		return 'elpl_post_layout';
	}

	/**
	 * Get widget title
	 */
	public function get_title()
	{
		return esc_html__('Post Layout con Ads', 'elementor-post-layout');
	}

	/**
	 * Get widget icon
	 */
	public function get_icon()
	{
		return 'eicon-posts-grid';
	}

	/**
	 * Get widget categories
	 */
	public function get_categories()
	{
		return array('cartes-widgets');
	}

	/**
	 * Get widget keywords
	 */
	public function get_keywords()
	{
		return array('posts', 'layout', 'ads', 'google', 'category');
	}

	/**
	 * Register widget controls
	 */
	protected function register_controls()
	{
		// Content Section
		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__('Configuración', 'elementor-post-layout'),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		// Category Selector
		$this->add_control(
			'post_category',
			array(
				'label' => esc_html__('Categoría de Posts', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $this->get_post_categories(),
				'default' => '',
				'label_block' => true,
			)
		);

		// Layout Selector
		$this->add_control(
			'layout_type',
			array(
				'label' => esc_html__('Tipo de Layout', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'layout_1' => esc_html__('Layout 1: Post grande izquierda', 'elementor-post-layout'),
					'layout_2' => esc_html__('Layout 2: Post grande derecha', 'elementor-post-layout'),
				),
				'default' => 'layout_1',
				'label_block' => true,
			)
		);

		// Layout 1 Preview
		$this->add_control(
			'layout_1_preview',
			array(
				'label' => esc_html__('Preview Layout 1', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => elpl_get_layout_1_svg(),
				'condition' => array(
					'layout_type' => 'layout_1',
				),
			)
		);

		// Layout 2 Preview
		$this->add_control(
			'layout_2_preview',
			array(
				'label' => esc_html__('Preview Layout 2', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => elpl_get_layout_2_svg(),
				'condition' => array(
					'layout_type' => 'layout_2',
				),
			)
		);

		// Google Ads Code
		$this->add_control(
			'google_ads_code',
			array(
				'label' => esc_html__('Código de Google Ads', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'rows' => 10,
				'default' => '',
				'placeholder' => esc_html__('Pega aquí el código HTML de Google Ads', 'elementor-post-layout'),
				'description' => esc_html__('El código se mostrará en la posición 4 del layout', 'elementor-post-layout'),
			)
		);

		$this->add_control(
			'date_format',
			array(
				'label' => esc_html__('Formato de Fecha', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'd M Y',
				'placeholder' => 'd M Y',
				'description' => 'Ejemplo: d M Y, F j, Y. Usa los formatos de fecha de PHP.',
			)
		);

		$this->add_control(
			'show_date_small',
			array(
				'label' => esc_html__('Mostrar fecha en notas pequeñas', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Sí', 'elementor-post-layout'),
				'label_off' => esc_html__('No', 'elementor-post-layout'),
				'return_value' => 'yes',
				'default' => '',
			)
		);

		$this->add_control(
			'show_category_bar',
			array(
				'label' => esc_html__('Mostrar barra de categoría', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Sí', 'elementor-post-layout'),
				'label_off' => esc_html__('No', 'elementor-post-layout'),
				'return_value' => 'yes',
				'default' => 'yes',
				'description' => esc_html__(
					'Muestra una barra con "Leer más [Categoría]" sobre el post grande.',
					'elementor-post-layout'
				),
			)
		);

		$this->end_controls_section();

		// Style Section
		$this->start_controls_section(
			'style_section_large',
			array(
				'label' => esc_html__('Estilo: Nota Grande', 'elementor-post-layout'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		// Category Bar Style
		$this->add_control(
			'category_bar_heading',
			array(
				'label' => esc_html__('Barra de Categoría', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'show_category_bar' => 'yes',
				),
			)
		);

		$this->add_control(
			'category_bar_bg_color',
			array(
				'label' => esc_html__('Color de Fondo', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#e21a22', // Red from the reference image
				'selectors' => array(
					'{{WRAPPER}} .elpl-category-bar' => 'background-color: {{VALUE}};',
				),
				'condition' => array(
					'show_category_bar' => 'yes',
				),
			)
		);

		$this->add_control(
			'category_bar_text_color',
			array(
				'label' => esc_html__('Color de Texto', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .elpl-category-bar, {{WRAPPER}} .elpl-category-bar a' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'show_category_bar' => 'yes',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'category_bar_typography',
				'selector' => '{{WRAPPER}} .elpl-category-bar, {{WRAPPER}} .elpl-category-bar a',
				'condition' => array(
					'show_category_bar' => 'yes',
				),
			)
		);

		// Large Title
		$this->add_control(
			'large_title_heading',
			array(
				'label' => esc_html__('Título', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'large_title_color',
			array(
				'label' => esc_html__('Color', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elpl-large-post .elpl-post-title a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'large_title_hover_color',
			array(
				'label' => esc_html__('Color (Hover)', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elpl-large-post .elpl-post-title a:hover' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'large_title_typography',
				'selector' => '{{WRAPPER}} .elpl-large-post .elpl-post-title a',
			)
		);

		// Large Excerpt
		$this->add_control(
			'large_excerpt_heading',
			array(
				'label' => esc_html__('Extracto', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'large_excerpt_color',
			array(
				'label' => esc_html__('Color', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elpl-large-post .elpl-post-excerpt p' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'large_excerpt_typography',
				'selector' => '{{WRAPPER}} .elpl-large-post .elpl-post-excerpt p',
			)
		);

		// Large Date
		$this->add_control(
			'large_date_heading',
			array(
				'label' => esc_html__('Fecha', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'large_date_color',
			array(
				'label' => esc_html__('Color', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elpl-large-post .elpl-post-date' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'large_date_typography',
				'selector' => '{{WRAPPER}} .elpl-large-post .elpl-post-date',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section_small',
			array(
				'label' => esc_html__('Estilo: Notas Pequeñas', 'elementor-post-layout'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		// Small Title
		$this->add_control(
			'small_title_heading',
			array(
				'label' => esc_html__('Título', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$this->add_control(
			'small_title_color',
			array(
				'label' => esc_html__('Color', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elpl-small-post .elpl-post-title-small a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'small_title_hover_color',
			array(
				'label' => esc_html__('Color (Hover)', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elpl-small-post .elpl-post-title-small a:hover' => 'color: {{VALUE}} !important;',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'small_title_typography',
				'selector' => '{{WRAPPER}} .elpl-small-post .elpl-post-title-small a',
			)
		);

		// Small Date
		$this->add_control(
			'small_date_heading',
			array(
				'label' => esc_html__('Fecha', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => array(
					'show_date_small' => 'yes',
				),
			)
		);

		$this->add_control(
			'small_date_color',
			array(
				'label' => esc_html__('Color', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elpl-small-post .elpl-post-date-small' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'show_date_small' => 'yes',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'small_date_typography',
				'selector' => '{{WRAPPER}} .elpl-small-post .elpl-post-date-small',
				'condition' => array(
					'show_date_small' => 'yes',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'style_section_general',
			array(
				'label' => esc_html__('Estilos Generales', 'elementor-post-layout'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		// Gap between posts
		$this->add_control(
			'posts_gap',
			array(
				'label' => esc_html__('Espacio entre posts', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array('px'),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 50,
					),
				),
				'default' => array(
					'unit' => 'px',
					'size' => 15,
				),
				'selectors' => array(
					'{{WRAPPER}} .elpl-layout' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elpl-small-column' => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elpl-category-bar' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Get post categories for dropdown
	 */
	private function get_post_categories()
	{
		$categories = get_categories(
			array(
				'orderby' => 'name',
				'order' => 'ASC',
				'hide_empty' => false,
			)
		);

		$options = array('' => esc_html__('Selecciona una categoría', 'elementor-post-layout'));

		foreach ($categories as $category) {
			$options[$category->term_id] = esc_html($category->name);
		}

		return $options;
	}

	/**
	 * Render widget output on the frontend
	 */
	protected function render()
	{
		$settings = $this->get_settings_for_display();

		$category_id = isset($settings['post_category']) ? absint($settings['post_category']) : 0;
		$layout_type = isset($settings['layout_type']) ? sanitize_text_field($settings['layout_type']) : 'layout_1';
		$google_ads_code = isset($settings['google_ads_code']) ? $settings['google_ads_code'] : '';
		$date_format = !empty($settings['date_format']) ? $settings['date_format'] : 'F j, Y';
		$show_date_small = ('yes' === $settings['show_date_small']);
		$show_category_bar = ('yes' === $settings['show_category_bar']);

		// Sanitize Google Ads code - allow safe HTML
		$google_ads_code = wp_kses_post($google_ads_code);

		// Query posts
		$query_args = array(
			'post_type' => 'post',
			'posts_per_page' => 4, // 4 posts (3 small + 1 large)
			'post_status' => 'publish',
			'orderby' => 'date',
			'order' => 'DESC',
		);

		if ($category_id > 0) {
			$query_args['cat'] = $category_id;
		}

		$posts_query = new \WP_Query($query_args);

		if (!$posts_query->have_posts()) {
			echo '<div class="elpl-no-posts">';
			echo '<p>' . esc_html__('No se encontraron posts en esta categoría.', 'elementor-post-layout') . '</p>';
			echo '</div>';
			return;
		}

		// Render layout
		echo '<div class="elpl-layout elpl-' . esc_attr($layout_type) . '">';

		if ('layout_1' === $layout_type) {
			$this->render_layout_1(
				$posts_query,
				$google_ads_code,
				$date_format,
				$show_date_small,
				$show_category_bar,
				$category_id
			);
		} else {
			$this->render_layout_2(
				$posts_query,
				$google_ads_code,
				$date_format,
				$show_date_small,
				$show_category_bar,
				$category_id
			);
		}

		echo '</div>';

		wp_reset_postdata();
	}

	/**
	 * Render Layout 1: Large post left + 4 small posts right
	 */
	private function render_layout_1(
		$posts_query,
		$google_ads_code,
		$date_format,
		$show_date_small,
		$show_category_bar,
		$category_id
	) {
		$posts = $posts_query->posts;

		echo '<div class="elpl-column elpl-large-column">';
		if (isset($posts[0])) {
			$this->render_large_post($posts[0], $date_format, $show_category_bar, $category_id);
		}
		echo '</div>';

		echo '<div class="elpl-column elpl-small-column">';
		for ($i = 1; $i < 4; $i++) {
			if (isset($posts[$i])) {
				$this->render_small_post(
					$posts[$i],
					$date_format,
					$show_date_small
				);
			}
		}
		// Google Ads in position 4
		if (!empty($google_ads_code)) {
			echo '<div class="elpl-ad-container">';
			echo $google_ads_code; // Already sanitized with wp_kses_post
			echo '</div>';
		}
		echo '</div>';
	}

	/**
	 * Render Layout 2: 4 small posts left + large post right
	 */
	private function render_layout_2(
		$posts_query,
		$google_ads_code,
		$date_format,
		$show_date_small,
		$show_category_bar,
		$category_id
	) {
		$posts = $posts_query->posts;

		echo '<div class="elpl-column elpl-small-column">';
		for ($i = 1; $i < 4; $i++) {
			if (isset($posts[$i])) {
				$this->render_small_post(
					$posts[$i],
					$date_format,
					$show_date_small
				);
			}
		}
		// Google Ads in position 4
		if (!empty($google_ads_code)) {
			echo '<div class="elpl-ad-container">';
			echo $google_ads_code; // Already sanitized with wp_kses_post
			echo '</div>';
		}
		echo '</div>';

		echo '<div class="elpl-column elpl-large-column">';
		if (isset($posts[0])) {
			$this->render_large_post($posts[0], $date_format, $show_category_bar, $category_id);
		}
		echo '</div>';
	}

	/**
	 * Render large post
	 */
	private function render_large_post($post, $date_format, $show_category_bar, $category_id = 0)
	{
		echo '<article class="elpl-post elpl-large-post">';

		// Category Bar
		if ($show_category_bar) {
			$category_to_show = null;

			// If a category is selected in the widget settings, use it
			if ($category_id > 0) {
				$category_to_show = get_category($category_id);
			}

			// Fallback to post's first category if no category is selected or category not found
			if (!$category_to_show || is_wp_error($category_to_show)) {
				$categories = get_the_category($post->ID);
				if (!empty($categories)) {
					$category_to_show = $categories[0];
				}
			}

			if ($category_to_show) {
				$category_name = $category_to_show->name;
				$category_link = get_category_link($category_to_show->term_id);

				echo '<div class="elpl-category-bar">';
				echo '<a href="' . esc_url($category_link) . '" class="elpl-category-link">';
				echo '<span class="elpl-category-icon">+</span> ';
				echo esc_html__('Leer más ', 'elementor-post-layout') . esc_html($category_name);
				echo '</a>';
				echo '</div>';
			}
		}

		// Featured image
		if (has_post_thumbnail($post->ID)) {
			echo '<div class="elpl-post-thumbnail">';
			echo '<a href="' . esc_url(get_permalink($post->ID)) . '">';
			echo get_the_post_thumbnail($post->ID, 'large', array('class' => 'elpl-post-image'));
			echo '</a>';
			echo '</div>';
		}

		// Content
		echo '<span class="elpl-post-date">' . esc_html(get_the_date($date_format, $post->ID)) . '</span>';
		echo '<div class="elpl-post-content">';
		echo '<h2 class="elpl-post-title">';
		echo '<a href="' . esc_url(get_permalink($post->ID)) . '">' . esc_html(get_the_title($post->ID)) .
			'</a>';
		echo '</h2>';
		echo '<div class="elpl-post-excerpt">';
		echo '<p>' . esc_html(wp_trim_words(get_the_excerpt($post->ID), 30)) . '</p>';
		echo '</div>';
		echo '<div class="elpl-post-meta">';
		echo '</div>';
		echo '</div>';

		echo '</article>';
	}

	/**
	 * Render widget output in the editor (JS Template)
	 */
	protected function _content_template()
	{
		?>
		<# var layout_type=settings.layout_type || 'layout_1' ; #>
			<div class="elpl-layout elpl-{{ layout_type }} elpl-editor-preview">
				<div class="elpl-editor-placeholder"
					style="padding: 20px; border: 2px dashed #ccc; border-radius: 8px; text-align: center; background: #fafafa; position: relative;">
					<div
						style="margin-bottom: 15px; font-weight: bold; color: #555; display: flex; align-items: center; justify-content: center; gap: 8px;">
						<i class="eicon-post-list" style="font-size: 20px;"></i>
						<span><?php esc_html_e('Vista Previa: Layout de Posts', 'elementor-post-layout'); ?></span>
						<span
							style="background: #e0e0e0; padding: 2px 8px; border-radius: 10px; font-size: 10px; text-transform: uppercase;">{{
							layout_type }}</span>
					</div>
					<div class="elpl-editor-mockup">
						<# if ( 'layout_1'===layout_type ) { #>
							<div style="display: flex; gap: 15px;">
								<div class="elpl-mock-large"
									style="flex: 2; background: #e0e0e0; height: 180px; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #777; font-size: 12px;">
									Post Destacado</div>
								<div class="elpl-mock-small-grid"
									style="flex: 1; display: flex; flex-direction: column; gap: 8px;">
									<div style="background: #f0f0f0; height: 39px; border-radius: 4px;"></div>
									<div style="background: #f0f0f0; height: 39px; border-radius: 4px;"></div>
									<div style="background: #f0f0f0; height: 39px; border-radius: 4px;"></div>
								</div>
							</div>
							<# } else { #>
								<div style="display: flex; gap: 15px;">
									<div class="elpl-mock-small-grid"
										style="flex: 1; display: flex; flex-direction: column; gap: 8px;">
										<div style="background: #f0f0f0; height: 39px; border-radius: 4px;"></div>
										<div style="background: #f0f0f0; height: 39px; border-radius: 4px;"></div>
										<div style="background: #f0f0f0; height: 39px; border-radius: 4px;"></div>
									</div>
									<div class="elpl-mock-large"
										style="flex: 2; background: #e0e0e0; height: 180px; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #777; font-size: 12px;">
										Post Destacado</div>
								</div>
								<# } #>
					</div>
					<# if ( settings.google_ads_code ) { #>
						<div class="elpl-ads-preview"
							style="background: #fff3cd; color: #856404; padding: 8px; border: 1px solid #ffeeba; margin-top: 15px; font-size: 10px; border-radius: 4px; text-align: left; display: flex; align-items: center; gap: 5px;">
							<i class="eicon-google"></i>
							<span><?php esc_html_e('Código de Google Ads Detectado (Se mostrará en la posición 4)', 'elementor-post-layout'); ?></span>
						</div>
						<# } #>
				</div>
			</div>
			<?php
	}

	/**
	 * Render small post
	 */
	private function render_small_post($post, $date_format, $show_date)
	{
		echo '<article class="elpl-post elpl-small-post">';

		// Featured image
		if (has_post_thumbnail($post->ID)) {
			echo '<div class="elpl-post-thumbnail-small">';
			echo '<a href="' . esc_url(get_permalink($post->ID)) . '">';
			echo get_the_post_thumbnail($post->ID, 'large', array('class' => 'elpl-post-image-small'));
			echo '</a>';
			echo '</div>';
		}

		// Content
		echo '<div class="elpl-post-content-small">';
		echo '<h3 class="elpl-post-title-small">';
		echo '<a href="' . esc_url(get_permalink($post->ID)) . '">' . esc_html(get_the_title($post->ID)) . '</a>';
		echo '</h3>';
		if ($show_date) {
			echo '<div class="elpl-post-meta-small">';
			echo '<span class="elpl-post-date-small">' . esc_html(get_the_date($date_format, $post->ID)) .
				'</span>';
			echo '</div>';
		}
		echo '</div>';

		echo '</article>';
	}
}