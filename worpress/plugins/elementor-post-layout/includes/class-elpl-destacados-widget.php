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

		$this->end_controls_section();

		// Style * Version: 1.5.2
		$this->start_controls_section(
			'style_section_posts',
			array(
				'label' => esc_html__('Estilo de Posts', 'elementor-post-layout'),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
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

		$this->add_control(
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

		$this->add_control(
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

		echo '<div class="elpl-destacados-module">';

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
		echo '</div>'; // End Module
		wp_reset_postdata();
	}

	/**
	 * Render widget output in the editor (JS Template)
	 */
	protected function _content_template()
	{
		?>
		<# var columns=settings.columnas || 3; var num_posts=settings.num_posts || 6; #>
			<div class="elpl-destacados-module elpl-editor-preview">
				<div class="elpl-editor-placeholder"
					style="padding: 20px; border: 2px dashed #ddd; border-radius: 8px; background: #fff;">
					<div
						style="margin-bottom: 15px; font-weight: bold; color: #444; display: flex; align-items: center; justify-content: center; gap: 10px;">
						<i class="eicon-gallery-grid" style="font-size: 24px; color: #e21a22;"></i>
						<span><?php esc_html_e('Destacados Dinámicos (Editor)', 'elementor-post-layout'); ?></span>
					</div>
					<div class="elpl-posts-grid-mock"
						style="display: grid; grid-template-columns: repeat({{ columns }}, 1fr); gap: 15px;">
						<# for ( var i=0; i < num_posts; i++ ) { #>
							<div style="background: #f5f5f5; border-radius: 4px; overflow: hidden; border: 1px solid #eee;">
								<div style="background: #eee; height: 100px;"></div>
								<div style="padding: 10px;">
									<div
										style="background: #ddd; height: 12px; width: 80%; margin-bottom: 8px; border-radius: 2px;">
									</div>
									<div style="background: #ececec; height: 8px; width: 60%; border-radius: 2px;"></div>
								</div>
							</div>
							<# } #>
					</div>
				</div>
			</div>
			<?php
	}
}
