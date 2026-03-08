<?php
namespace ELPL\Widgets;

/**
 * Elementor Destacados Dinámicos Widget.
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

if (!defined('ELPL_VERSION')) {
	define('ELPL_VERSION', '1.5.2');
}

class ELPL_Destacados_Widget extends \Elementor\Widget_Base
{

	public function get_name()
	{
		return 'elpl_destacados';
	}

	public function get_title()
	{
		return esc_html__('Destacados Dinámicos', 'elementor-post-layout');
	}

	public function get_version()
	{
		return '1.6.0';
	}

	public function get_icon()
	{
		return 'eicon-featured-image';
	}

	public function get_categories()
	{
		return array('cartes-widgets');
	}

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

		$this->add_control(
			'post_category',
			array(
				'label' => esc_html__('Categoría', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $this->get_post_categories(),
				'default' => '',
				'label_block' => true,
			)
		);

		$this->add_control(
			'num_posts',
			array(
				'label' => esc_html__('Número de Posts', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 20,
				'default' => 7,
			)
		);

		$this->add_control(
			'offset',
			array(
				'label' => esc_html__('Offset (Desplazamiento)', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0,
				'default' => 0,
			)
		);

		$this->add_control(
			'columnas',
			array(
				'label' => esc_html__('Columnas (Grilla)', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => array(
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5',
				),
				'default' => '3',
			)
		);

		$this->add_control(
			'date_format',
			array(
				'label' => esc_html__('Formato de Fecha', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'd M Y',
				'placeholder' => 'd M Y',
			)
		);

		$this->add_control(
			'show_excerpt',
			array(
				'label' => esc_html__('Mostrar Extracto (Destacado)', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Sí', 'elementor-post-layout'),
				'label_off' => esc_html__('No', 'elementor-post-layout'),
				'return_value' => 'yes',
				'default' => 'yes',
			)
		);

		$this->add_control(
			'show_date',
			array(
				'label' => esc_html__('Mostrar Fecha', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Sí', 'elementor-post-layout'),
				'label_off' => esc_html__('No', 'elementor-post-layout'),
				'return_value' => 'yes',
				'default' => 'yes',
			)
		);

		$this->add_responsive_control(
			'image_position',
			array(
				'label' => esc_html__('Posición de Imagen', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => array(
					'column' => array(
						'title' => esc_html__('Arriba', 'elementor-post-layout'),
						'icon' => 'eicon-v-align-top',
					),
					'row' => array(
						'title' => esc_html__('Izquierda', 'elementor-post-layout'),
						'icon' => 'eicon-h-align-left',
					),
					'row-reverse' => array(
						'title' => esc_html__('Derecha', 'elementor-post-layout'),
						'icon' => 'eicon-h-align-right',
					),
					'column-reverse' => array(
						'title' => esc_html__('Abajo', 'elementor-post-layout'),
						'icon' => 'eicon-v-align-bottom',
					),
				),
				'default' => 'row',
				'tablet_default' => 'row',
				'mobile_default' => 'column',
				'selectors' => array(
					'{{WRAPPER}} .elpl-grid-image-link' => 'flex-direction: {{VALUE}};',
				),
				'separator' => 'before',
			)
		);

		$this->add_responsive_control(
			'image_width',
			array(
				'label' => esc_html__('Ancho de Imagen', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array('%', 'px'),
				'range' => array(
					'%' => array('min' => 10, 'max' => 100),
					'px' => array('min' => 50, 'max' => 800),
				),
				'default' => array('unit' => '%', 'size' => 40),
				'tablet_default' => array('unit' => '%', 'size' => 45),
				'mobile_default' => array('unit' => '%', 'size' => 100),
				'selectors' => array(
					'{{WRAPPER}} .elpl-grid-image-container' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Style * Version: 1.5.2
		$this->start_controls_section(
			'style_section_posts',
			array(
				'label' => esc_html__('Estilo de Posts', 'elementor-post-layout'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'title_color',
			array(
				'label' => esc_html__('Color de Título', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elpl-grid-title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .elpl-grid-title',
			)
		);

		$this->add_responsive_control(
			'date_color',
			array(
				'label' => esc_html__('Color de Fecha', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elpl-post-date' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'show_date' => 'yes',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'date_typography',
				'selector' => '{{WRAPPER}} .elpl-post-date',
				'condition' => array(
					'show_date' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'excerpt_color',
			array(
				'label' => esc_html__('Color de Extracto', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elpl-grid-excerpt' => 'color: {{VALUE}};',
				),
				'condition' => array(
					'show_excerpt' => 'yes',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .elpl-grid-excerpt',
				'condition' => array(
					'show_excerpt' => 'yes',
				),
			)
		);

		$this->add_control(
			'grid_gap',
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
					'size' => 20,
				),
				'selectors' => array(
					'{{WRAPPER}} .elpl-posts-grid' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);



		$this->end_controls_section();

		// ── Estilo: Botón de Paginación (Mobile) ─────────────────────────────
		$this->start_controls_section(
			'style_section_load_more',
			array(
				'label' => esc_html__('Botón de Paginación (Mobile)', 'elementor-post-layout'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->start_controls_tabs('load_more_btn_tabs');

		// ── Tab: Normal ───────────────────────────────────────────────────────
		$this->start_controls_tab(
			'load_more_btn_normal',
			array('label' => esc_html__('Normal', 'elementor-post-layout'))
		);

		$this->add_control(
			'load_more_color',
			array(
				'label' => esc_html__('Color de Texto', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .elpl-load-more-btn' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'load_more_bg_color',
			array(
				'label' => esc_html__('Color de Fondo', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#e21a22',
				'selectors' => array(
					'{{WRAPPER}} .elpl-load-more-btn' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		// ── Tab: Hover ────────────────────────────────────────────────────────
		$this->start_controls_tab(
			'load_more_btn_hover',
			array('label' => esc_html__('Hover', 'elementor-post-layout'))
		);

		$this->add_control(
			'load_more_color_hover',
			array(
				'label' => esc_html__('Color de Texto', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .elpl-load-more-btn:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'load_more_bg_color_hover',
			array(
				'label' => esc_html__('Color de Fondo', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#c0151c',
				'selectors' => array(
					'{{WRAPPER}} .elpl-load-more-btn:hover' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name' => 'load_more_typography',
				'selector' => '{{WRAPPER}} .elpl-load-more-btn',
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name' => 'load_more_border',
				'selector' => '{{WRAPPER}} .elpl-load-more-btn',
			)
		);

		$this->add_control(
			'load_more_border_radius',
			array(
				'label' => esc_html__('Radio de Borde', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array('px', '%'),
				'default' => array(
					'top' => 4,
					'right' => 4,
					'bottom' => 4,
					'left' => 4,
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .elpl-load-more-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'load_more_padding',
			array(
				'label' => esc_html__('Padding', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array('px', 'em'),
				'default' => array(
					'top' => 12,
					'right' => 20,
					'bottom' => 12,
					'left' => 20,
					'unit' => 'px',
				),
				'selectors' => array(
					'{{WRAPPER}} .elpl-load-more-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'load_more_margin_top',
			array(
				'label' => esc_html__('Margen Superior', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array('px'),
				'range' => array(
					'px' => array('min' => 0, 'max' => 80),
				),
				'default' => array('unit' => 'px', 'size' => 20),
				'selectors' => array(
					'{{WRAPPER}} .elpl-load-more-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'load_more_alignment',
			array(
				'label' => esc_html__('Alineación (Mobile)', 'elementor-post-layout'),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => array(
					'left' => array(
						'title' => esc_html__('Izquierda', 'elementor-post-layout'),
						'icon' => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__('Centrado', 'elementor-post-layout'),
						'icon' => 'eicon-text-align-center',
					),
					'right' => array(
						'title' => esc_html__('Derecha', 'elementor-post-layout'),
						'icon' => 'eicon-text-align-right',
					),
				),
				'default' => 'center',
				'selectors' => array(
					'{{WRAPPER}} .elpl-load-more-wrap' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	private function get_post_categories()
	{
		$categories = get_categories(array('hide_empty' => false));
		$options = array('' => esc_html__('Todas', 'elementor-post-layout'));
		foreach ($categories as $category) {
			$options[$category->slug] = $category->name;
		}
		return $options;
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();

		$args = array(
			'post_type' => 'post',
			'posts_per_page' => absint($settings['num_posts']),
			'offset' => absint($settings['offset']),
			'post_status' => 'publish',
			'orderby' => 'date',
			'order' => 'DESC',
		);

		if (!empty($settings['post_category'])) {
			$args['category_name'] = $settings['post_category'];
		}

		$query = new \WP_Query($args);

		if (!$query->have_posts()) {
			echo '<p>' . esc_html__('No hay posts disponibles.', 'elementor-post-layout') . '</p>';
			return;
		}

		echo '<div class="elpl-destacados-module" data-elpl-module="1">';

		// Start Grid Container
		echo '<div class="elpl-posts-grid" style="grid-template-columns: repeat(' . esc_attr($settings['columnas']) . ', 1fr);">';

		while ($query->have_posts()) {
			$query->the_post();
			?>
			<div class="elpl-grid-post">
				<a href="<?php the_permalink(); ?>" class="elpl-grid-image-link">
					<div class="elpl-grid-image-container">
						<?php the_post_thumbnail('medium', array('class' => 'elpl-grid-img')); ?>
					</div>
					<div class="elpl-grid-content">
						<?php if ('yes' === $settings['show_date']): ?>
							<div class="elpl-post-date"><?php echo esc_html(get_the_date($settings['date_format'])); ?></div>
						<?php endif; ?>
						<h3 class="elpl-grid-title"><?php echo esc_html(get_the_title()); ?></h3>
						<?php if ('yes' === $settings['show_excerpt']): ?>
							<div class="elpl-grid-excerpt">
								<?php echo esc_html(wp_trim_words(get_the_excerpt(), 15)); ?>
							</div>
						<?php endif; ?>
					</div>
				</a>
			</div>
			<?php
		}

		echo '</div>'; // End Grid Container

		// "Cargar más" button — visible only on mobile via CSS, hidden on desktop.
		$initial_offset = absint($settings['offset']);
		$num_posts = absint($settings['num_posts']);
		$button_offset = $initial_offset + $num_posts; // next batch starts here
		$show_date_val = ('yes' === $settings['show_date']) ? 'yes' : 'no';
		$show_excerpt_val = ('yes' === $settings['show_excerpt']) ? 'yes' : 'no';

		// Check if there are more posts beyond the currently displayed ones.
		$more_check_args = array(
			'post_type' => 'post',
			'posts_per_page' => 1,
			'offset' => $button_offset,
			'post_status' => 'publish',
			'fields' => 'ids',
			'no_found_rows' => false,
		);
		if (!empty($settings['post_category'])) {
			$more_check_args['category_name'] = $settings['post_category'];
		}
		$more_check = new \WP_Query($more_check_args);
		$no_more_class = $more_check->have_posts() ? '' : ' elpl-no-more';
		wp_reset_postdata();

		printf(
			'<div class="elpl-load-more-wrap"><button class="elpl-load-more-btn%s" data-widget="elpl_destacados" data-grid=".elpl-posts-grid" data-category="%s" data-per-page="%d" data-offset="%d" data-date-format="%s" data-show-date="%s" data-show-excerpt="%s">%s</button></div>',
			esc_attr($no_more_class),
			esc_attr($settings['post_category'] ?? ''),
			$num_posts,
			$button_offset,
			esc_attr($settings['date_format']),
			esc_attr($show_date_val),
			esc_attr($show_excerpt_val),
			esc_html__('Cargar más', 'elementor-post-layout')
		);

		echo '</div>'; // End Module
		wp_reset_postdata();
	}

	/**
	 * Render widget output in the editor (JS Template)
	 */
	protected function _content_template()
	{
		?>
		<# var columns=settings.columnas || 1; var num_posts=settings.num_posts || 6; #>
			<div class="elpl-destacados-module elpl-editor-preview">
				<div class="elpl-editor-placeholder"
					style="padding: 20px; border: 2px dashed #ddd; border-radius: 8px; background: #fff;">
					<div
						style="margin-bottom: 15px; font-weight: bold; color: #444; display: flex; align-items: center; justify-content: center; gap: 10px;">
						<i class="eicon-featured-image" style="font-size: 24px; color: #e21a22;"></i>
						<span><?php esc_html_e('Destacados Dinámicos (Editor)', 'elementor-post-layout'); ?></span>
					</div>
					<div class="elpl-posts-grid-mock elpl-posts-grid"
						style="grid-template-columns: repeat({{ columns }}, 1fr);">
						<# for ( var i=0; i < num_posts; i++ ) { #>
							<div class="elpl-grid-post">
								<div class="elpl-grid-image-link">
									<div class="elpl-grid-image-container" style="background: #eee; border-radius: 4px;">
									</div>
									<div class="elpl-grid-content">
										<div
											style="background: #e21a22; height: 14px; width: 35%; margin-bottom: 10px; border-radius: 2px;">
										</div>
										<div
											style="background: #333; height: 20px; width: 90%; margin-bottom: 8px; border-radius: 2px;">
										</div>
										<div style="background: #333; height: 20px; width: 75%; border-radius: 2px;"></div>
									</div>
								</div>
							</div>
							<# } #>
					</div>
				</div>
			</div>
			<?php
	}
}
