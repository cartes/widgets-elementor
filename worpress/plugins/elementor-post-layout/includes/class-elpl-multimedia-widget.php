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

        $this->add_responsive_control(
            'columns',
            [
                'label' => esc_html__('Columnas', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '2',
                'tablet_default' => '2',
                'mobile_default' => '1',
                'options' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elpl-multimedia-grid' => '--elpl-media-cols: {{VALUE}};',
                ],
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

        $this->add_control(
            'mobile_initial_posts',
            [
                'label'   => esc_html__('Posts iniciales (Mobile)', 'elementor-post-layout'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'default' => 3,
                'min'     => 1,
                'max'     => 20,
                'description' => esc_html__('Número de posts visibles al cargar en mobile. El resto se revelan con "Cargar más".', 'elementor-post-layout'),
            ]
        );

        $this->add_control(
            'mobile_batch',
            [
                'label'   => esc_html__('Posts por carga (Mobile)', 'elementor-post-layout'),
                'type'    => \Elementor\Controls_Manager::NUMBER,
                'default' => 4,
                'min'     => 1,
                'max'     => 20,
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

        // Load More Button Style Section
        $this->start_controls_section(
            'style_section_load_more',
            [
                'label' => esc_html__('Botón Cargar Más (Mobile)', 'elementor-post-layout'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'lm_align',
            [
                'label'   => esc_html__('Alineación', 'elementor-post-layout'),
                'type'    => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left'   => ['title' => esc_html__('Izquierda', 'elementor-post-layout'), 'icon' => 'eicon-text-align-left'],
                    'center' => ['title' => esc_html__('Centro',    'elementor-post-layout'), 'icon' => 'eicon-text-align-center'],
                    'right'  => ['title' => esc_html__('Derecha',   'elementor-post-layout'), 'icon' => 'eicon-text-align-right'],
                ],
                'default'   => 'center',
                'selectors' => ['{{WRAPPER}} .elpl-load-more-wrap' => 'text-align: {{VALUE}};'],
            ]
        );

        $this->start_controls_tabs('lm_tabs');

        $this->start_controls_tab('lm_tab_normal', ['label' => esc_html__('Normal', 'elementor-post-layout')]);

        $this->add_control(
            'lm_text_color',
            [
                'label'     => esc_html__('Color de Texto', 'elementor-post-layout'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .elpl-load-more-btn' => 'color: {{VALUE}};'],
            ]
        );

        $this->add_control(
            'lm_bg_color',
            [
                'label'     => esc_html__('Color de Fondo', 'elementor-post-layout'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .elpl-load-more-btn' => 'background-color: {{VALUE}};'],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('lm_tab_hover', ['label' => esc_html__('Hover', 'elementor-post-layout')]);

        $this->add_control(
            'lm_text_color_hover',
            [
                'label'     => esc_html__('Color de Texto', 'elementor-post-layout'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .elpl-load-more-btn:hover' => 'color: {{VALUE}};'],
            ]
        );

        $this->add_control(
            'lm_bg_color_hover',
            [
                'label'     => esc_html__('Color de Fondo', 'elementor-post-layout'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'selectors' => ['{{WRAPPER}} .elpl-load-more-btn:hover' => 'background-color: {{VALUE}};'],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'lm_typography',
                'label'    => esc_html__('Tipografía', 'elementor-post-layout'),
                'selector' => '{{WRAPPER}} .elpl-load-more-btn',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => 'lm_border',
                'selector' => '{{WRAPPER}} .elpl-load-more-btn',
            ]
        );

        $this->add_control(
            'lm_border_radius',
            [
                'label'      => esc_html__('Radio de Borde', 'elementor-post-layout'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors'  => [
                    '{{WRAPPER}} .elpl-load-more-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'lm_padding',
            [
                'label'      => esc_html__('Padding', 'elementor-post-layout'),
                'type'       => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .elpl-load-more-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'lm_margin_top',
            [
                'label'     => esc_html__('Margen Superior del Bloque', 'elementor-post-layout'),
                'type'      => \Elementor\Controls_Manager::SLIDER,
                'range'     => ['px' => ['min' => 0, 'max' => 100]],
                'selectors' => ['{{WRAPPER}} .elpl-load-more-wrap' => 'margin-top: {{SIZE}}px;'],
                'default'   => ['size' => 20],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $post_type = !empty($settings['post_type']) ? sanitize_text_field($settings['post_type']) : 'multimedia';
        $cols = !empty($settings['columns']) ? absint($settings['columns']) : 2;
        $is_pagination_active = ($settings['show_pagination'] === 'yes');

        $num_posts      = !empty($settings['posts_per_page']) ? absint($settings['posts_per_page']) : 6;
        $mobile_initial = !empty($settings['mobile_initial_posts']) ? absint($settings['mobile_initial_posts']) : 3;
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
        <?php if ($mobile_initial < $num_posts): ?>
        <style>
        @media(max-width:767px) {
            .elementor-element-<?php echo esc_attr($this->get_id()); ?> .elpl-multimedia-card.elpl-top-post--m-hidden { display: none; }
        }
        </style>
        <?php endif; ?>

        <div class="elpl-multimedia-module" data-elpl-module="1">
            <div class="elpl-multimedia-grid">
                <?php
                $post_idx = 0;
                while ($query->have_posts()):
                    $query->the_post();
                    $post_idx++;
                    $hidden_cls = ($post_idx > $mobile_initial) ? ' elpl-top-post--m-hidden' : '';
                    $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                    ?>
                    <article class="elpl-multimedia-card<?php echo esc_attr($hidden_cls); ?>">
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

            <?php
            // ── "Cargar más" — mobile only ────────────────────────────────
            $mobile_batch  = absint($settings['mobile_batch'] ?? 4);
            $hidden_count  = max(0, $num_posts - $mobile_initial);
            $button_offset = $num_posts;

            $more_check    = new \WP_Query([
                'post_type'      => $post_type,
                'posts_per_page' => 1,
                'offset'         => $button_offset,
                'post_status'    => 'publish',
                'fields'         => 'ids',
                'no_found_rows'  => false,
            ]);
            $has_db_more   = $more_check->have_posts() ? 'true' : 'false';
            $no_more_class = (!$more_check->have_posts() && $hidden_count <= 0) ? ' elpl-no-more' : '';
            wp_reset_postdata();
            ?>
            <div class="elpl-multimedia-mobile-more"></div>
            <div class="elpl-load-more-wrap">
                <button class="elpl-load-more-btn<?php echo esc_attr($no_more_class); ?>"
                    data-widget="elpl_multimedia_widget"
                    data-grid=".elpl-multimedia-grid"
                    data-post-type="<?php echo esc_attr($post_type); ?>"
                    data-per-page="<?php echo esc_attr($mobile_batch); ?>"
                    data-offset="<?php echo esc_attr($button_offset); ?>"
                    data-hidden-count="<?php echo esc_attr($hidden_count); ?>"
                    data-has-db-more="<?php echo esc_attr($has_db_more); ?>">
                    <?php esc_html_e('Cargar más', 'elementor-post-layout'); ?>
                </button>
            </div>

        </div><!-- .elpl-multimedia-module -->
        <?php
    }

    protected function _content_template()
    {
        ?>
        <# var num=settings.posts_per_page || 6; #>
            <div class="elpl-multimedia-module elpl-editor-preview">
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
