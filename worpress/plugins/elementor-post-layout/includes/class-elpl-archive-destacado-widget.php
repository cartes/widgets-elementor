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
        if (is_category() || is_tag() || is_tax()) {
            $obj = get_queried_object();
            $query_args['tax_query'] = [
                [
                    'taxonomy' => $obj->taxonomy,
                    'field' => 'term_id',
                    'terms' => $obj->term_id,
                ],
            ];
        } elseif (is_post_type_archive()) {
            $query_args['post_type'] = get_query_var('post_type');
        } elseif (is_author()) {
            $query_args['author'] = get_queried_object_id();
        } else {
            $query_args['post_type'] = 'post';
        }

        $query = new \WP_Query($query_args);

        if (!$query->have_posts()) {
            echo '<p>' . esc_html__('No se encontraron posts.', 'elementor-post-layout') . '</p>';
            return;
        }

        $counter = 0;
        ?>
        <div class="elpl-archive-destacado-module">
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
                    <?php if ($counter > 1)
                        echo '</div>'; ?>
                </div>
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
                        gap: 20px !important;
                    }

                    .elpl-destacado-side article {
                        width: calc(50% - 10px);
                    }
                }

                @media (max-width: 767px) {
                    .elpl-destacado-side {
                        flex-direction: column;
                    }

                    .elpl-destacado-side article {
                        width: 100%;
                    }
                }
            </style>
            <?php
    }
}
