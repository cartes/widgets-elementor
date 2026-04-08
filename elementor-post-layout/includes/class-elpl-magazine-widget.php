<?php
namespace ELPL\Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Elementor Magazine Widget (Grilla de Revistas)
 */
class ELPL_Magazine_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'elpl_magazine_widget';
    }

    public function get_title()
    {
        return esc_html__('Grilla de Revistas', 'elementor-post-layout');
    }

    public function get_icon()
    {
        return 'eicon-posts-grid';
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

        // Fetch Post Types
        $post_types = get_post_types(['public' => true], 'objects');
        $post_type_options = [];
        foreach ($post_types as $pt) {
            $post_type_options[$pt->name] = $pt->label;
        }

        $this->add_control(
            'post_type',
            [
                'label' => esc_html__('Tipo de Post', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $post_type_options,
                'default' => 'post',
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
                    '6' => '6',
                ],
                'default' => '3',
                'prefix_class' => 'elpl-mag-cols-',
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => esc_html__('Cantidad de Posts', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 9,
            ]
        );

        $this->add_control(
            'show_image',
            [
                'label' => esc_html__('Mostrar Imagen', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Sí', 'elementor-post-layout'),
                'label_off' => esc_html__('No', 'elementor-post-layout'),
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'image_size',
            [
                'label' => esc_html__('Tamaño de Imagen', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'thumbnail' => 'Thumbnail',
                    'medium' => 'Médium',
                    'medium_large' => 'Médium Large',
                    'large' => 'Large',
                    'full' => 'Full',
                ],
                'default' => 'medium_large',
                'condition' => [
                    'show_image' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_ratio',
            [
                'label' => esc_html__('Ratio de Imagen', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '1/1' => '1:1 (Cuadrado)',
                    '4/3' => '4:3 (Horizontal)',
                    '3/4' => '3:4 (Vertical)',
                    '2/3' => '2:3 (Revista/Poster)',
                    '16/9' => '16:9 (Panorámico)',
                ],
                'default' => '3/4',
                'condition' => [
                    'show_image' => 'yes',
                ],
                'selectors' => [
                    '{{WRAPPER}} .elpl-mag-image' => 'aspect-ratio: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label' => esc_html__('Mostrar Título', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Sí', 'elementor-post-layout'),
                'label_off' => esc_html__('No', 'elementor-post-layout'),
                'default' => 'yes',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'title_tag',
            [
                'label' => esc_html__('Etiqueta del Título', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                    'div' => 'div',
                    'span' => 'span',
                    'p' => 'p',
                ],
                'default' => 'h3',
                'condition' => [
                    'show_title' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_excerpt',
            [
                'label' => esc_html__('Mostrar Extracto', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Sí', 'elementor-post-layout'),
                'label_off' => esc_html__('No', 'elementor-post-layout'),
                'default' => 'no',
            ]
        );

        $this->add_control(
            'excerpt_length',
            [
                'label' => esc_html__('Longitud del Extracto', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 25,
                'condition' => [
                    'show_excerpt' => 'yes',
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
                'default' => 'yes',
                'separator' => 'before',
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
            'orderby',
            [
                'label' => esc_html__('Ordenar por', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'date' => esc_html__('Fecha', 'elementor-post-layout'),
                    'title' => esc_html__('Título', 'elementor-post-layout'),
                    'rand' => esc_html__('Aleatorio', 'elementor-post-layout'),
                    'menu_order' => esc_html__('Orden de Menú', 'elementor-post-layout'),
                ],
                'default' => 'date',
            ]
        );

        $this->add_control(
            'order',
            [
                'label' => esc_html__('Orden', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'ASC' => esc_html__('Ascendente', 'elementor-post-layout'),
                    'DESC' => esc_html__('Descendente', 'elementor-post-layout'),
                ],
                'default' => 'DESC',
            ]
        );

        $this->end_controls_section();

        // --- STYLE SECTION ---
        $this->start_controls_section(
            'section_style_layout',
            [
                'label' => esc_html__('Estilos de Layout', 'elementor-post-layout'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'column_gap',
            [
                'label' => esc_html__('Espacio entre Columnas', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 30,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elpl-mag-grid' => 'gap: {{size}}px {{row_gap.size}}px;',
                    '{{WRAPPER}} .elpl-mag-grid' => 'grid-column-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'row_gap',
            [
                'label' => esc_html__('Espacio entre Filas', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'default' => [
                    'size' => 35,
                ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elpl-mag-grid' => 'grid-row-gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Style Content
        $this->start_controls_section(
            'section_style_content',
            [
                'label' => esc_html__('Contenido', 'elementor-post-layout'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius Imagen', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elpl-mag-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; overflow: hidden;',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color del Título', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elpl-mag-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Tipografía del Título', 'elementor-post-layout'),
                'selector' => '{{WRAPPER}} .elpl-mag-title',
            ]
        );

        $this->add_control(
            'meta_color',
            [
                'label' => esc_html__('Color de Journal/Meta', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elpl-mag-journal-number' => 'color: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typography',
                'label' => esc_html__('Tipografía de Journal/Meta', 'elementor-post-layout'),
                'selector' => '{{WRAPPER}} .elpl-mag-journal-number',
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
        $ppp = !empty($settings['posts_per_page']) ? absint($settings['posts_per_page']) : 9;
        $offset = !empty($settings['offset']) ? absint($settings['offset']) : 0;
        $cols = !empty($settings['columns']) ? absint($settings['columns']) : 3;
        $paged = 1;

        if (get_query_var('paged')) {
            $paged = get_query_var('paged');
        } elseif (get_query_var('page')) {
            $paged = get_query_var('page');
        }

        $query_args = [
            'post_type' => $settings['post_type'],
            'post_status' => 'publish',
            'posts_per_page' => $ppp,
            'offset' => $offset + (($paged - 1) * $ppp),
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
        ];

        $query = new \WP_Query($query_args);

        if (!$query->have_posts()) {
            echo '<p>' . esc_html__('No se encontraron publicaciones.', 'elementor-post-layout') . '</p>';
            return;
        }

        $grid_class = 'elpl-mag-grid elpl-mag-cols-' . $cols;
        ?>

        <div class="elpl-mag-module">
            <div class="<?php echo esc_attr($grid_class); ?>">
                <?php
                while ($query->have_posts()):
                    $query->the_post();
                    $thumbnail = get_the_post_thumbnail_url(get_the_ID(), $settings['image_size']);
                    $journal_number = get_post_meta(get_the_ID(), 'journalDetailNumber', true);
                    ?>
                    <article class="elpl-mag-post">
                        <?php if ($settings['show_image'] === 'yes' && $thumbnail): ?>
                            <div class="elpl-mag-image" style="background-image: url('<?php echo esc_url($thumbnail); ?>');">
                                <a href="<?php the_permalink(); ?>" class="elpl-full-link"></a>
                            </div>
                        <?php endif; ?>

                        <div class="elpl-mag-content">
                            <?php
                            $journal_number = get_post_meta(get_the_ID(), 'journalDetailNumber', true);
                            if (empty($journal_number) && function_exists('get_field')) {
                                $journal_number = get_field('journalDetailNumber', get_the_ID());
                            }

                            if ($journal_number):
                                $post_date = get_the_date('F Y');
                                // Use specific date format per mockup (lowercase month)
                                $post_date_lower = strtolower($post_date);
                                ?>
                                <div class="elpl-mag-journal-number">
                                    Revista N&deg; <?php echo esc_html($journal_number); ?>, <?php echo esc_html($post_date_lower); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($settings['show_title'] === 'yes'): ?>
                                <<?php echo esc_attr($settings['title_tag']); ?> class="elpl-mag-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </<?php echo esc_attr($settings['title_tag']); ?>>
                            <?php endif; ?>

                            <?php if ($settings['show_excerpt'] === 'yes'): ?>
                                <div class="elpl-mag-excerpt">
                                    <?php echo wp_trim_words(get_the_excerpt(), $settings['excerpt_length']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
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
            <?php endif; ?>
        </div>

        <style>
            .elpl-mag-grid {
                display: grid;
                gap: 30px;
            }

            .elpl-mag-cols-1 {
                grid-template-columns: repeat(1, 1fr);
            }

            .elpl-mag-cols-2 {
                grid-template-columns: repeat(2, 1fr);
            }

            .elpl-mag-cols-3 {
                grid-template-columns: repeat(3, 1fr);
            }

            .elpl-mag-cols-4 {
                grid-template-columns: repeat(4, 1fr);
            }

            .elpl-mag-cols-6 {
                grid-template-columns: repeat(6, 1fr);
            }

            .elpl-mag-post {
                display: flex;
                flex-direction: column;
            }

            .elpl-mag-image {
                width: 100%;
                background-size: cover;
                background-position: center;
                margin-bottom: 15px;
                position: relative;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }

            .elpl-full-link {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 2;
            }

            .elpl-mag-journal-number {
                font-size: 14px;
                font-weight: 700;
                color: #e21a22;
                /* Default red for magazines */
                margin-bottom: 8px;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            .elpl-mag-title {
                margin: 0 0 10px 0;
                font-weight: 700;
                line-height: 1.3;
            }

            .elpl-mag-title a {
                color: #111;
                text-decoration: none;
                transition: color 0.3s ease;
            }

            .elpl-mag-title a:hover {
                color: #e21a22;
            }

            .elpl-mag-excerpt {
                font-size: 14px;
                line-height: 1.5;
                color: #444;
            }

            /* Pagination */
            .elpl-pagination {
                display: flex;
                gap: 10px;
                justify-content: center;
                align-items: center;
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
                background-color: #e21a22;
                color: #fff;
                border-color: #e21a22;
            }

            .elpl-pagination .page-numbers:hover:not(.current) {
                background-color: #f5f5f5;
            }

            @media (max-width: 1024px) {
                .elpl-mag-grid:not(.elpl-mag-cols-1) {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (max-width: 767px) {
                .elpl-mag-grid {
                    grid-template-columns: 1fr !important;
                }
            }
        </style>
        <?php
    }

    protected function _content_template()
    {
    }
}
