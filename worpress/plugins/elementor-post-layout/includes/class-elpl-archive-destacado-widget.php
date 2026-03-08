<?php
namespace ELPL\Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Elementor Archive Destacado Widget
 * 66/33 Layout for the first 3 posts in an archive.
 */
class ELPL_Archive_Destacado_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'elpl_archive_destacado_widget';
    }

    public function get_title()
    {
        return esc_html__('Archive Destacado (66/33)', 'elementor-post-layout');
    }

    public function get_icon()
    {
        return 'eicon-post-list';
    }

    public function get_categories()
    {
        return array('cartes-widgets');
    }

    protected function register_controls()
    {
        // --- LAYOUT SECTION ---
        $this->start_controls_section(
            'section_layout',
            [
                'label' => esc_html__('Diseño (Layout)', 'elementor-post-layout'),
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => esc_html__('Cantidad de Posts', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3,
                'description' => esc_html__('Para este layout se recomiendan 3 posts.', 'elementor-post-layout'),
            ]
        );

        $this->add_control(
            'show_meta',
            [
                'label' => esc_html__('Mostrar Metadatos', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Sí', 'elementor-post-layout'),
                'label_off' => esc_html__('No', 'elementor-post-layout'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'grid_gap',
            [
                'label' => esc_html__('Espacio entre notas (Gap)', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elpl-destacado-grid' => 'gap: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elpl-destacado-side' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // --- QUERY SECTION ---
        $this->start_controls_section(
            'section_query',
            [
                'label' => esc_html__('Consulta (Query)', 'elementor-post-layout'),
            ]
        );

        $this->add_control(
            'offset',
            [
                'label' => esc_html__('Salto de Posts (Offset)', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 0,
            ]
        );

        $this->add_control(
            'mobile_batch',
            [
                'label' => esc_html__('Posts por carga (Mobile)', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'default' => 3,
                'description' => esc_html__('Cantidad de posts que se cargan al pulsar "Cargar más" en mobile.', 'elementor-post-layout'),
            ]
        );

        $this->end_controls_section();

        // --- STYLE SECTION (Main Post) ---
        $this->start_controls_section(
            'section_style_main',
            [
                'label' => esc_html__('Post Principal (Grande)', 'elementor-post-layout'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'main_title_typography',
                'label' => esc_html__('Tipografía de Título', 'elementor-post-layout'),
                'selector' => '{{WRAPPER}} .elpl-destacado-main .elpl-archive-title',
            ]
        );

        $this->add_control(
            'main_title_color',
            [
                'label' => esc_html__('Color de Título', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elpl-destacado-main .elpl-archive-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // --- STYLE SECTION (Side Posts) ---
        $this->start_controls_section(
            'section_style_side',
            [
                'label' => esc_html__('Posts Laterales (Pequeños)', 'elementor-post-layout'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'side_title_typography',
                'label' => esc_html__('Tipografía de Título', 'elementor-post-layout'),
                'selector' => '{{WRAPPER}} .elpl-destacado-side .elpl-archive-title',
            ]
        );

        $this->add_control(
            'side_title_color',
            [
                'label' => esc_html__('Color de Título', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elpl-destacado-side .elpl-archive-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // --- STYLE SECTION (Metadata) ---
        $this->start_controls_section(
            'section_style_meta',
            [
                'label' => esc_html__('Metadatos', 'elementor-post-layout'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_meta' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'meta_color',
            [
                'label' => esc_html__('Color de Metadatos', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elpl-archive-meta, {{WRAPPER}} .elpl-archive-meta span' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typography',
                'label' => esc_html__('Tipografía de Metadatos', 'elementor-post-layout'),
                'selector' => '{{WRAPPER}} .elpl-archive-meta',
            ]
        );

        $this->end_controls_section();

        // ── Estilo: Botón de Paginación (Mobile) ─────────────────────────────
        $this->start_controls_section(
            'style_section_load_more',
            array(
                'label' => esc_html__('Botón Cargar Más (Mobile)', 'elementor-post-layout'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );

        $this->start_controls_tabs('load_more_btn_tabs');

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

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $ppp = !empty($settings['posts_per_page']) ? absint($settings['posts_per_page']) : 3;
        $offset = !empty($settings['offset']) ? absint($settings['offset']) : 0;
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $query_args = [
            'post_status' => 'publish',
            'posts_per_page' => $ppp,
            'offset' => $offset + (($paged - 1) * $ppp),
            'orderby' => 'date',
            'order' => 'DESC',
        ];

        // Context detection
        $taxonomy_slug = '';
        $term_id = 0;
        $author_id = 0;

        if (is_category() || is_tag() || is_tax()) {
            $obj = get_queried_object();
            $taxonomy_slug = $obj->taxonomy;
            $term_id = $obj->term_id;

            $query_args['tax_query'] = [
                [
                    'taxonomy' => $taxonomy_slug,
                    'field' => 'term_id',
                    'terms' => $term_id,
                ],
            ];
        } elseif (is_post_type_archive()) {
            $query_args['post_type'] = get_query_var('post_type');
        } elseif (is_author()) {
            $author_id = get_queried_object_id();
            $query_args['author'] = $author_id;
        } else {
            $query_args['post_type'] = 'post';
        }

        $query = new \WP_Query($query_args);

        if (!$query->have_posts()) {
            echo '<p>' . esc_html__('No se encontraron posts.', 'elementor-post-layout') . '</p>';
            return;
        }

        $counter = 0;
        
        $mobile_batch = !empty($settings['mobile_batch']) ? absint($settings['mobile_batch']) : 3;

        // Check if there are more posts beyond the initially loaded ones
        $more_check_args = $query_args;
        $more_check_args['posts_per_page'] = 1;
        $more_check_args['offset'] = $query_args['offset'] + $ppp;
        $more_check_args['fields'] = 'ids';
        $more_check = new \WP_Query($more_check_args);
        $no_more_class = $more_check->have_posts() ? '' : ' elpl-no-more';
        wp_reset_postdata();

        ?>
        <div class="elpl-archive-destacado-module" data-elpl-module="1">
            <div class="elpl-destacado-grid">
                <?php while ($query->have_posts()):
                    $query->the_post();
                    $counter++; ?>
                    <?php if ($counter === 1): ?>
                        <div class="elpl-destacado-main">
                            <article class="elpl-archive-post">
                                <div class="elpl-archive-image"
                                    style="background-image: url('<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>');">
                                    <a href="<?php the_permalink(); ?>" class="elpl-full-link"></a>
                                </div>
                                <div class="elpl-archive-content">
                                    <?php if ($settings['show_meta'] === 'yes'): ?>
                                        <div class="elpl-archive-meta">
                                            <span class="elpl-archive-date">
                                                <?php echo get_the_date(); ?>
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                    <h3 class="elpl-archive-title"><a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a></h3>
                                </div>
                            </article>
                        </div>
                        <div class="elpl-destacado-side">
                        <?php else: ?>
                            <article class="elpl-archive-post elpl-side-post">
                                <div class="elpl-archive-image"
                                    style="background-image: url('<?php echo get_the_post_thumbnail_url(get_the_ID(), 'medium'); ?>');">
                                    <a href="<?php the_permalink(); ?>" class="elpl-full-link"></a>
                                </div>
                                <div class="elpl-archive-content">
                                    <h3 class="elpl-archive-title"><a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a></h3>
                                </div>
                            </article>
                        <?php endif; ?>
                    <?php endwhile;
                wp_reset_postdata(); ?>
                    <?php if ($counter > 1) {
                        echo '</div>'; 
                    } ?>
                </div>
            </div>

            <div class="elpl-load-more-wrap">
                <button class="elpl-load-more-btn<?php echo esc_attr($no_more_class); ?>" data-widget="elpl_archive_destacado_widget"
                    data-grid=".elpl-destacado-side" data-category=""
                    data-per-page="<?php echo esc_attr($mobile_batch); ?>"
                    data-offset="<?php echo esc_attr($query_args['offset'] + $ppp); ?>" data-show-date="no" data-show-excerpt="no"
                    data-taxonomy="<?php echo esc_attr($taxonomy_slug); ?>"
                    data-term-id="<?php echo esc_attr($term_id); ?>"
                    data-author-id="<?php echo esc_attr($author_id); ?>">
                    <?php esc_html_e('Cargar más', 'elementor-post-layout'); ?>
                </button>
            </div>

            <style>
                .elpl-destacado-grid {
                    display: grid;
                    grid-template-columns: 2fr 1fr;
                    max-width: 100%;
                    box-sizing: border-box;
                }

                .elpl-destacado-side {
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                }

                .elpl-archive-post {
                    height: 100%;
                    display: flex;
                    flex-direction: column;
                }

                .elpl-destacado-main .elpl-archive-image {
                    aspect-ratio: 16/10;
                    background-size: cover;
                    background-position: center;
                    margin-bottom: 15px;
                }

                .elpl-destacado-side .elpl-archive-image {
                    aspect-ratio: 16/9;
                    background-size: cover;
                    background-position: center;
                    margin-bottom: 10px;
                }

                .elpl-archive-title {
                    margin: 5px 0;
                    font-weight: 700;
                    line-height: 1.3;
                }

                .elpl-archive-title a {
                    color: #111;
                    text-decoration: none;
                }

                .elpl-archive-meta {
                    font-size: 13px;
                    font-weight: 700;
                    margin-bottom: 5px;
                }

                @media (max-width: 1024px) {
                    .elpl-destacado-grid {
                        grid-template-columns: 1fr;
                    }

                    .elpl-destacado-side {
                        flex-direction: row;
                        flex-wrap: wrap;
                        gap: 20px !important;
                    }

                    .elpl-destacado-side article {
                        width: calc(50% - 10px);
                    }
                }

                @media (max-width: 767px) {
                    .elpl-destacado-side {
                        flex-direction: column;
                        flex-wrap: nowrap;
                    }

                    .elpl-destacado-side article {
                        width: 100%;
                    }
                }
                
                @media (min-width: 768px) {
                    .elpl-archive-destacado-module .elpl-load-more-wrap {
                        display: none !important;
                    }
                }
            </style>
            <?php
    }
}
