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

		$this->add_responsive_control(
			'show_category_bar',
			array(
				'label' => esc_html__('Mostrar barra de categoría', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => array(
					'block' => array(
						'title' => esc_html__('Mostrar', 'elementor-post-layout'),
						'icon' => 'eicon-eye',
					),
					'none' => array(
						'title' => esc_html__('Ocultar', 'elementor-post-layout'),
						'icon' => 'eicon-eye-close',
					),
				),
				'default' => 'block',
				'tablet_default' => 'block',
				'mobile_default' => 'block',
				'selectors' => array(
					'{{WRAPPER}} .elpl-category-bar' => 'display: {{VALUE}};',
				),
				'description' => esc_html__(
					'Muestra u oculta la barra de categoría por dispositivo.',
					'elementor-post-layout'
				),
			)
		);


		$this->add_control(
			'hr_mobile',
			array(
				'type' => \Elementor\Controls_Manager::DIVIDER,
				'label' => esc_html__('Paginación Mobile', 'elementor-post-layout'),
			)
		);

		$this->add_control(
			'num_posts_mobile',
			array(
				'label' => esc_html__('Posts iniciales (Mobile)', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 20,
				'default' => 4,
				'description' => esc_html__('Cantidad de posts que se muestran al cargar en mobile.', 'elementor-post-layout'),
			)
		);

		$this->add_control(
			'mobile_batch',
			array(
				'label' => esc_html__('Posts por carga (Mobile)', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 20,
				'default' => 3,
				'description' => esc_html__('Cantidad de posts que se cargan al pulsar "Cargar más" en mobile.', 'elementor-post-layout'),
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
			)
		);

		$this->add_control(
			'category_bar_bg_color',
			array(
				'label' => esc_html__('Color de Fondo', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#e21a22',
				'selectors' => array(
					'{{WRAPPER}} .elpl-category-bar' => 'background-color: {{VALUE}};',
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
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'category_bar_typography',
				'selector' => '{{WRAPPER}} .elpl-category-bar, {{WRAPPER}} .elpl-category-bar a',
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

		// ── Estilo: Botón de Paginación (Mobile) ─────────────────────────────────
		$this->start_controls_section(
			'style_section_load_more',
			array(
				'label' => esc_html__('Botón de Paginación (Mobile)', 'elementor-post-layout'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs('load_more_btn_tabs');

		$this->start_controls_tab('load_more_btn_normal', array('label' => esc_html__('Normal', 'elementor-post-layout')));

		$this->add_control('load_more_color', array(
			'label' => esc_html__('Color de Texto', 'elementor-post-layout'),
			'type' => \Elementor\Controls_Manager::COLOR,
			'default' => '#ffffff',
			'selectors' => array('{{WRAPPER}} .elpl-load-more-btn' => 'color: {{VALUE}};'),
		));

		$this->add_control('load_more_bg_color', array(
			'label' => esc_html__('Color de Fondo', 'elementor-post-layout'),
			'type' => \Elementor\Controls_Manager::COLOR,
			'default' => '#e21a22',
			'selectors' => array('{{WRAPPER}} .elpl-load-more-btn' => 'background-color: {{VALUE}};'),
		));

		$this->end_controls_tab();

		$this->start_controls_tab('load_more_btn_hover', array('label' => esc_html__('Hover', 'elementor-post-layout')));

		$this->add_control('load_more_color_hover', array(
			'label' => esc_html__('Color de Texto', 'elementor-post-layout'),
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => array('{{WRAPPER}} .elpl-load-more-btn:hover' => 'color: {{VALUE}};'),
		));

		$this->add_control('load_more_bg_color_hover', array(
			'label' => esc_html__('Color de Fondo', 'elementor-post-layout'),
			'type' => \Elementor\Controls_Manager::COLOR,
			'default' => '#c0151c',
			'selectors' => array('{{WRAPPER}} .elpl-load-more-btn:hover' => 'background-color: {{VALUE}};'),
		));

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(\Elementor\Group_Control_Typography::get_type(), array(
			'name' => 'load_more_typography',
			'selector' => '{{WRAPPER}} .elpl-load-more-btn',
		));

		$this->add_group_control(\Elementor\Group_Control_Border::get_type(), array(
			'name' => 'load_more_border',
			'selector' => '{{WRAPPER}} .elpl-load-more-btn',
		));

		$this->add_control('load_more_border_radius', array(
			'label' => esc_html__('Radio de Borde', 'elementor-post-layout'),
			'type' => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array('px', '%'),
			'default' => array('top' => 4, 'right' => 4, 'bottom' => 4, 'left' => 4, 'unit' => 'px'),
			'selectors' => array('{{WRAPPER}} .elpl-load-more-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'),
		));

		$this->add_control('load_more_padding', array(
			'label' => esc_html__('Padding', 'elementor-post-layout'),
			'type' => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => array('px', 'em'),
			'default' => array('top' => 12, 'right' => 20, 'bottom' => 12, 'left' => 20, 'unit' => 'px'),
			'selectors' => array('{{WRAPPER}} .elpl-load-more-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'),
		));

		$this->add_control('load_more_margin_top', array(
			'label' => esc_html__('Margen Superior', 'elementor-post-layout'),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => array('px'),
			'range' => array('px' => array('min' => 0, 'max' => 80)),
			'default' => array('unit' => 'px', 'size' => 20),
			'selectors' => array('{{WRAPPER}} .elpl-load-more-wrap' => 'margin-top: {{SIZE}}{{UNIT}};'),
		));

		$this->add_control('load_more_alignment', array(
			'label' => esc_html__('Alineación (Mobile)', 'elementor-post-layout'),
			'type' => \Elementor\Controls_Manager::CHOOSE,
			'options' => array(
				'left' => array('title' => esc_html__('Izquierda', 'elementor-post-layout'), 'icon' => 'eicon-text-align-left'),
				'center' => array('title' => esc_html__('Centrado', 'elementor-post-layout'), 'icon' => 'eicon-text-align-center'),
				'right' => array('title' => esc_html__('Derecha', 'elementor-post-layout'), 'icon' => 'eicon-text-align-right'),
			),
			'default' => 'center',
			'selectors' => array('{{WRAPPER}} .elpl-load-more-wrap' => 'text-align: {{VALUE}};'),
		));

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
		// show_category_bar ahora es responsive (block/none via CSS selector);
		// el render PHP siempre muestra el HTML — Elementor aplica display:none/block via su CSS generado.
		$show_category_bar = ('none' !== ($settings['show_category_bar'] ?? 'block'));
		$num_posts_mobile = max(1, absint($settings['num_posts_mobile'] ?? 4));
		$mobile_batch = max(1, absint($settings['mobile_batch'] ?? 3));


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

		// Mobile responsive styles
		?>
		<style>
			@media (max-width: 767px) {

				/* ── Layout principal: apila columnas verticalmente ── */
				.elpl-layout {
					flex-direction: column !important;
				}

				.elpl-column {
					width: 100% !important;
					flex: none !important;
				}

				/* Romper el grid de 2 columnas del desktop: lista vertical */
				.elpl-small-column {
					display: flex !important;
					flex-direction: column !important;
					grid-template-columns: unset !important;
				}

				/* ── NOTA GRANDE: título y categoría ENCIMA de la imagen ── */
				.elpl-large-post {
					display: flex !important;
					flex-direction: column !important;
				}

				/* Barra de categoría: order 1 → aparece primero */
				.elpl-large-post .elpl-category-bar {
					order: 1 !important;
				}

				/* Contenido (título + extracto): order 2 */
				.elpl-large-post .elpl-post-content {
					order: 2 !important;
				}

				/* Imagen: order 3 → va debajo del título */
				.elpl-large-post .elpl-post-thumbnail {
					order: 3 !important;
				}

				/* Fecha: order 4 */
				.elpl-large-post .elpl-post-date {
					order: 4 !important;
				}

				/* Título grande: peso y tamaño para móvil */
				.elpl-large-post .elpl-post-title a {
					font-weight: 800 !important;
					font-size: 22px !important;
					line-height: 1.3 !important;
				}

				/* Categoría encima del título (solo mobile, noticia destacada) */
				.elpl-large-post-category-mobile {
					display: block !important;
					color: var(--e-global-color-primary, #e21a22) !important;
					font-size: 12px !important;
					font-weight: 700 !important;
					text-transform: uppercase !important;
					margin-bottom: 4px !important;
					letter-spacing: 0.5px !important;
				}

				/* Ocultar extracto en móvil */
				.elpl-large-post .elpl-post-excerpt {
					display: none !important;
				}

				/* Imagen grande a ancho completo */
				.elpl-large-post .elpl-post-image {
					width: 100% !important;
					height: auto !important;
					object-fit: cover !important;
				}

				/* ── NOTAS PEQUEÑAS: lista horizontal (imagen izq. + texto der.) ── */
				.elpl-small-post {
					display: flex !important;
					flex-direction: row !important;
					align-items: flex-start !important;
					gap: 12px !important;
					border-bottom: 1px solid #ddd !important;
					padding: 15px 0 !important;
					margin: 0 !important;
				}

				/* Imagen pequeña: ancho fijo a la izquierda */
				.elpl-small-post .elpl-post-thumbnail-small {
					flex: 0 0 130px !important;
					width: 130px !important;
					height: 90px !important;
					overflow: hidden !important;
					border-radius: 4px !important;
				}

				.elpl-small-post .elpl-post-image-small {
					width: 100% !important;
					height: 100% !important;
					object-fit: cover !important;
					display: block !important;
				}

				/* Contenido a la derecha */
				.elpl-small-post .elpl-post-content-small {
					flex: 1 !important;
					display: flex !important;
					flex-direction: column !important;
					justify-content: flex-start !important;
				}

				/* Categoría en rojo (visible solo en móvil) */
				.elpl-small-category-mobile {
					display: block !important;
					color: #e21a22 !important;
					font-size: 11px !important;
					font-weight: 700 !important;
					text-transform: uppercase !important;
					margin-bottom: 4px !important;
					letter-spacing: 0.5px !important;
				}

				/* Título pequeño: negrita */
				.elpl-small-post .elpl-post-title-small a {
					font-weight: 700 !important;
					font-size: 14px !important;
					line-height: 1.4 !important;
				}

				/* Ocultar fecha en notas pequeñas en móvil */
				.elpl-small-post .elpl-post-meta-small {
					display: none !important;
				}

				/* Ocultar layout desktop en mobile */
				.elpl-layout.elpl-layout-desktop-only {
					display: none !important;
				}
			}
		</style>
		<?php
		// Render layout
		echo '<div class="elpl-layout elpl-layout-desktop-only elpl-' . esc_attr($layout_type) . '">';


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

		echo '</div>'; // .elpl-layout

		// ══════════════════════════════════════════════════════════════════════
		// MOBILE: sección independiente con num_posts_mobile posts + Load More
		// Visible solo en ≤767px. El bloque desktop (.elpl-layout-desktop-only)
		// se oculta en mobile via la pseudo-regla CSS de arriba.
		// ══════════════════════════════════════════════════════════════════════
		$mobile_args = array(
			'post_type' => 'post',
			'posts_per_page' => $num_posts_mobile,
			'post_status' => 'publish',
			'orderby' => 'date',
			'order' => 'DESC',
		);
		if ($category_id > 0) {
			$mobile_args['cat'] = $category_id;
		}
		$mobile_query = new \WP_Query($mobile_args);

		$more_check_args = array(
			'post_type' => 'post',
			'posts_per_page' => 1,
			'offset' => $num_posts_mobile,
			'post_status' => 'publish',
			'fields' => 'ids',
		);
		if ($category_id > 0) {
			$more_check_args['cat'] = $category_id;
		}
		$more_check = new \WP_Query($more_check_args);
		$no_more_class = $more_check->have_posts() ? '' : ' elpl-no-more';
		wp_reset_postdata();

		echo '<div class="elpl-layout-mobile-section" data-elpl-module="1">';

		echo '<div class="elpl-layout-mobile-grid">';
		if ($mobile_query->have_posts()) {
			$is_first = true;
			while ($mobile_query->have_posts()) {
				$mobile_query->the_post();
				$p = get_post();
				if ($is_first) {
					$this->render_large_post($p, $date_format, $show_category_bar, $category_id);
					$is_first = false;
				} else {
					$this->render_small_post($p, $date_format, $show_date_small);
				}
			}
			wp_reset_postdata();
		}
		echo '</div>'; // .elpl-layout-mobile-grid
		echo '<div class="elpl-load-more-wrap">';
		printf(
			'<button class="elpl-load-more-btn%s" data-widget="elpl_post_layout" data-grid=".elpl-layout-mobile-grid" data-category="" data-category-id="%d" data-per-page="%d" data-offset="%d" data-show-date="%s" data-show-excerpt="no">%s</button>',
			esc_attr($no_more_class),
			$category_id,
			$mobile_batch,
			$num_posts_mobile,
			$show_date_small ? 'yes' : 'no',
			esc_html__('Cargar más', 'elementor-post-layout')
		);
		echo '</div>'; // .elpl-load-more-wrap
		echo '</div>'; // .elpl-layout-mobile-section

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

		// Category label: visible only on mobile, above the title
		$cat_label = null;
		if ($category_id > 0) {
			$cat_label = get_category($category_id);
		}
		if (!$cat_label || is_wp_error($cat_label)) {
			$cats = get_the_category($post->ID);
			if (!empty($cats)) {
				$cat_label = $cats[0];
			}
		}
		if ($cat_label) {
			echo '<span class="elpl-large-post-category-mobile" style="display:none;">' . esc_html($cat_label->name) . '</span>';
		}

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

		// Category label (hidden on desktop via CSS, visible on mobile)
		$post_categories = get_the_category($post->ID);
		if (!empty($post_categories)) {
			$cat = $post_categories[0];
			echo '<span class="elpl-small-category-mobile" style="display:none;">';
			echo '<a href="' . esc_url(get_category_link($cat->term_id)) . '" style="color:inherit;text-decoration:none;">';
			echo esc_html($cat->name);
			echo '</a>';
			echo '</span>';
		}

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