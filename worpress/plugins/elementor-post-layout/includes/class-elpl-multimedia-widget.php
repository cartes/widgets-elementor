<?php
namespace ELPL\Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Elementor Multimedia Widget (Videos)
 */
class ELPL_Multimedia_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'elpl_multimedia_widget';
    }

    public function get_title()
    {
        return esc_html__('Multimedia', 'elementor-post-layout');
    }

    public function get_icon()
    {
        return 'eicon-play';
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
                'default' => 'multimedia',
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => esc_html__('Cantidad de Videos', 'elementor-post-layout'),
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
                'default' => '2',
            ]
        );

        $this->add_control(
            'show_pagination',
            [
                'label' => esc_html__('Paginación', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Sí', 'elementor-post-layout'),
                'label_off' => esc_html__('No', 'elementor-post-layout'),
                'default' => 'no',
                'separator' => 'before',
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
            'play_button_color',
            [
                'label' => esc_html__('Color del Botón Play', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FF0000',
                'selectors' => [
                    '{{WRAPPER}} .elpl-multimedia-play-icon' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'date_color',
            [
                'label' => esc_html__('Color de la Fecha', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#666',
                'selectors' => [
                    '{{WRAPPER}} .elpl-multimedia-date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius de Imagen', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elpl-multimedia-thumbnail' => 'border-radius: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elpl-multimedia-thumbnail img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Tipografía del Título', 'elementor-post-layout'),
                'selector' => '{{WRAPPER}} .elpl-multimedia-title',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'date_typography',
                'label' => esc_html__('Tipografía de la Fecha', 'elementor-post-layout'),
                'selector' => '{{WRAPPER}} .elpl-multimedia-date',
            ]
        );

        $this->end_controls_section();

        // --- STYLE SECTION (Pagination) ---
        $this->start_controls_section(
            'section_style_pagination',
            [
                'label' => esc_html__('Paginación', 'elementor-post-layout'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_pagination' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_spacing',
            [
                'label' => esc_html__('Espaciado Superior', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elpl-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
                'default' => [
                    'size' => 30,
                    'unit' => 'px',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'pagination_typography',
                'selector' => '{{WRAPPER}} .elpl-pagination a, {{WRAPPER}} .elpl-pagination span',
            ]
        );

        $this->start_controls_tabs('pagination_tabs');

        $this->start_controls_tab('pagination_normal', ['label' => esc_html__('Normal', 'elementor-post-layout')]);

        $this->add_control(
            'pagination_color',
            [
                'label' => esc_html__('Color', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elpl-pagination a, {{WRAPPER}} .elpl-pagination span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_bg_color',
            [
                'label' => esc_html__('Color de Fondo', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elpl-pagination a, {{WRAPPER}} .elpl-pagination span' => 'background-color: {{VALUE}}; border: 1px solid {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('pagination_active', ['label' => esc_html__('Activo', 'elementor-post-layout')]);

        $this->add_control(
            'pagination_active_color',
            [
                'label' => esc_html__('Color Activo', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elpl-pagination .current' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'pagination_active_bg_color',
            [
                'label' => esc_html__('Color de Fondo Activo', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elpl-pagination .current' => 'background-color: {{VALUE}}; border: 1px solid {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $post_type = !empty($settings['post_type']) ? sanitize_text_field($settings['post_type']) : 'multimedia';
        $cols = !empty($settings['columns']) ? absint($settings['columns']) : 2;
        $is_pagination_active = ($settings['show_pagination'] === 'yes');

        $num_posts = !empty($settings['posts_per_page']) ? absint($settings['posts_per_page']) : 6;
        $paged = 1;

        if ($is_pagination_active) {
            if (get_query_var('paged')) {
                $paged = get_query_var('paged');
            } elseif (get_query_var('page')) {
                $paged = get_query_var('page');
            }
        }

        $query_args = [
            'post_type' => $post_type,
            'posts_per_page' => $num_posts,
            'paged' => $paged,
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
        ];

        $query = new \WP_Query($query_args);

        if (!$query->have_posts()) {
            echo '<p>' . esc_html__('No se encontraron videos.', 'elementor-post-layout') . '</p>';
            return;
        }
        ?>

        <div class="elpl-multimedia-module elpl-cols-<?php echo esc_attr($cols); ?>">
            <div class="elpl-multimedia-grid">
                <?php
                while ($query->have_posts()):
                    $query->the_post();
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
                                <h3 class="elpl-multimedia-title">
                                    <?php the_title(); ?>
                                </h3>
                                <div class="elpl-multimedia-date">
                                    <?php echo get_the_date('M j, Y'); ?>
                                </div>
                            </div>
                        </a>
                    </article>
                    <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>

            <?php if ($settings['show_pagination'] === 'yes' && $query->max_num_pages > 1): ?>
                <div class="elpl-pagination">
                    <?php
                    $big = 999999999;
                    echo paginate_links([
                        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                        'format' => '?paged=%#%',
                        'total' => $query->max_num_pages,
                        'current' => $paged,
                        'prev_text' => '&laquo;',
                        'next_text' => '&raquo;',
                        'type' => 'plain'
                    ]);
                    ?>
                </div>
                <style>
                    .elpl-pagination {
                        display: flex;
                        gap: 10px;
                        justify-content: center;
                        align-items: center;
                        margin-top: 30px;
                    }

                    .elpl-pagination .page-numbers {
                        padding: 8px 15px;
                        border: 1px solid #ddd;
                        text-decoration: none;
                        color: #333;
                        border-radius: 4px;
                        transition: all 0.3s ease;
                    }

                    .elpl-pagination .current {
                        background-color: #FF0000;
                        color: #fff;
                        border-color: #FF0000;
                    }

                    .elpl-pagination .page-numbers:hover:not(.current) {
                        background-color: #f5f5f5;
                    }
                </style>
            <?php endif; ?>
        </div>
        <?php
    }

    protected function _content_template()
    {
        ?>
        <# var cols=settings.columns || 2; var num=settings.posts_per_page || 6; #>
            <div class="elpl-multimedia-module elpl-cols-{{ cols }} elpl-editor-preview">
                <div class="elpl-multimedia-grid">
                    <# for ( var i=0; i < num; i++ ) { #>
                        <div class="elpl-multimedia-card">
                            <div class="elpl-multimedia-link">
                                <div class="elpl-multimedia-thumbnail">
                                    <div class="elpl-multimedia-no-image" style="background: #ddd;"></div>
                                    <div class="elpl-multimedia-play-overlay">
                                        <div class="elpl-multimedia-play-icon" style="background-color: #FF0000;">
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
                                    <div
                                        style="background: #333; height: 16px; width: 90%; margin-bottom: 8px; border-radius: 2px;">
                                    </div>
                                    <div
                                        style="background: #333; height: 16px; width: 60%; margin-bottom: 8px; border-radius: 2px;">
                                    </div>
                                    <div class="elpl-multimedia-date" style="color: #666; font-size: 12px;">Sep 29, 2025</div>
                                </div>
                            </div>
                        </div>
                        <# } #>
                </div>
            </div>
            <?php
    }
}
