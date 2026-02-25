<?php
namespace ELPL\Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Elementor Grilla de Contenidos Universal Widget
 */
class ELPL_Universal_Posts_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'elpl_universal_posts_widget';
    }

    public function get_title()
    {
        return esc_html__('Grilla de Contenidos', 'elementor-post-layout');
    }

    public function get_icon()
    {
        return 'eicon-post-grid';
    }

    public function get_categories()
    {
        return array('cartes-widgets');
    }

    private function get_available_post_types()
    {
        $post_types = get_post_types(array('public' => true), 'objects');
        $options = array();
        foreach ($post_types as $post_type) {
            $options[$post_type->name] = $post_type->label;
        }
        return $options;
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Configuración', 'elementor-post-layout'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'post_type',
            [
                'label' => esc_html__('Tipo de Contenido', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_available_post_types(),
                'default' => 'post',
            ]
        );

        $options = ['' => esc_html__('Todas', 'elementor-post-layout')];
        if (function_exists('get_categories')) {
            $categories = get_categories(['hide_empty' => false]);
            if (!empty($categories) && !is_wp_error($categories)) {
                foreach ($categories as $category) {
                    $options[$category->term_id] = $category->name;
                }
            }
        }

        $this->add_control(
            'category_id',
            [
                'label' => esc_html__('Categoría', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $options,
                'default' => '',
                'condition' => [
                    'post_type' => 'post',
                ],
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => esc_html__('Cantidad de Posts', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => esc_html__('Columnas', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'default' => '3',
            ]
        );

        $this->add_control(
            'show_image',
            [
                'label' => esc_html__('Mostrar Imagen', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Sí', 'elementor-post-layout'),
                'label_off' => esc_html__('No', 'elementor-post-layout'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_date',
            [
                'label' => esc_html__('Mostrar Fecha', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Sí', 'elementor-post-layout'),
                'label_off' => esc_html__('No', 'elementor-post-layout'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Estilos', 'elementor-post-layout'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'date_color',
            [
                'label' => esc_html__('Color de la Fecha', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#e21a22',
                'selectors' => [
                    '{{WRAPPER}} .elpl-universal-date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $post_type = !empty($settings['post_type']) ? sanitize_text_field($settings['post_type']) : 'post';
        $num_posts = !empty($settings['posts_per_page']) ? absint($settings['posts_per_page']) : 6;
        $cols = !empty($settings['columns']) ? absint($settings['columns']) : 3;

        $query_args = [
            'post_type' => $post_type,
            'posts_per_page' => $num_posts,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
        ];

        if ('post' === $post_type && !empty($settings['category_id'])) {
            $query_args['cat'] = absint($settings['category_id']);
        }

        $query = new \WP_Query($query_args);

        if (!$query->have_posts()) {
            echo '<p>' . esc_html__('No se encontraron contenidos.', 'elementor-post-layout') . '</p>';
            return;
        }
        ?>

        <div class="elpl-universal-module elpl-cols-<?php echo esc_attr($cols); ?>">
            <div class="elpl-universal-grid">
                <?php
                while ($query->have_posts()):
                    $query->the_post();
                    $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                    ?>
                    <article class="elpl-universal-card">
                        <?php if ('yes' === $settings['show_image'] && $thumbnail): ?>
                            <div class="elpl-universal-image" style="background-image: url('<?php echo esc_url($thumbnail); ?>');">
                                <a href="<?php the_permalink(); ?>" class="elpl-full-link"></a>
                            </div>
                        <?php endif; ?>

                        <div class="elpl-universal-content">
                            <?php if ('yes' === $settings['show_date']): ?>
                                <div class="elpl-universal-date">
                                    <?php echo get_the_date('M j, Y'); ?>
                                </div>
                            <?php endif; ?>

                            <h3 class="elpl-universal-title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h3>
                        </div>
                    </article>
                    <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        </div>
        <?php
    }

    protected function _content_template()
    {
        ?>
        <# var cols=settings.columns || 3; var num=settings.posts_per_page || 6; #>
            <div class="elpl-universal-module elpl-cols-{{ cols }} elpl-editor-preview">
                <div class="elpl-universal-grid">
                    <# for ( var i=0; i < num; i++ ) { #>
                        <div class="elpl-universal-card">
                            <# if ( settings.show_image==='yes' ) { #>
                                <div class="elpl-universal-image" style="background: #eee;"></div>
                                <# } #>
                                    <div class="elpl-universal-content">
                                        <# if ( settings.show_date==='yes' ) { #>
                                            <div class="elpl-universal-date"
                                                style="color: #e21a22; font-size: 11px; font-weight: bold; margin-bottom: 5px;">
                                                JUL 10, 2025</div>
                                            <# } #>
                                                <div
                                                    style="background: #333; height: 16px; width: 90%; margin-bottom: 8px; border-radius: 2px;">
                                                </div>
                                                <div style="background: #333; height: 16px; width: 60%; border-radius: 2px;">
                                                </div>
                                    </div>
                        </div>
                        <# } #>
                </div>
            </div>
            <?php
    }
}
