<?php
namespace ELPL\Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Elementor Archive Posts Widget (Grilla de Archivo)
 * Specialized for Archive templates with manual overrides.
 */
class ELPL_Archive_Posts_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'elpl_archive_posts_widget';
    }

    public function get_title()
    {
        return esc_html__('Grilla de Archivo (Pro Style)', 'elementor-post-layout');
    }

    public function get_icon()
    {
        return 'eicon-archive-posts';
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
                'prefix_class' => 'elpl-grid-cols-',
            ]
        );

        $this->add_control(
            'posts_per_page',
            [
                'label' => esc_html__('Cantidad de Posts', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 10,
                'description' => esc_html__('Sobreescribe la configuración global de WordPress.', 'elementor-post-layout'),
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
            'image_position',
            [
                'label' => esc_html__('Posición de Imagen', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'top' => esc_html__('Arriba', 'elementor-post-layout'),
                    'left' => esc_html__('Izquierda', 'elementor-post-layout'),
                    'right' => esc_html__('Derecha', 'elementor-post-layout'),
                ],
                'default' => 'top',
                'condition' => [
                    'show_image' => 'yes',
                ],
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
                'default' => 'medium',
                'condition' => [
                    'show_image' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label' => esc_html__('Ancho de Imagen', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['%', 'px', 'vw'],
                'range' => [
                    '%' => [
                        'min' => 10,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 50,
                        'max' => 800,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elpl-archive-image' => 'width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elpl-archive-image-left .elpl-archive-image' => 'flex: 0 0 {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elpl-archive-image-right .elpl-archive-image' => 'flex: 0 0 {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'show_image' => 'yes',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_ratio',
            [
                'label' => esc_html__('Ratio de Imagen', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0.1,
                        'max' => 3.0,
                        'step' => 0.05,
                    ],
                ],
                'default' => [
                    'size' => 1.77,
                ],
                'selectors' => [
                    '{{WRAPPER}} .elpl-archive-image' => 'aspect-ratio: {{SIZE}};',
                ],
                'condition' => [
                    'show_image' => 'yes',
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
                'default' => 'yes',
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
            'meta_data',
            [
                'label' => esc_html__('Metadatos a mostrar', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => [
                    'author' => esc_html__('Autor', 'elementor-post-layout'),
                    'date' => esc_html__('Fecha', 'elementor-post-layout'),
                    'comments' => esc_html__('Comentarios', 'elementor-post-layout'),
                    'time' => esc_html__('Hora', 'elementor-post-layout'),
                ],
                'default' => ['date', 'author'],
                'condition' => [
                    'show_meta' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_read_more',
            [
                'label' => esc_html__('Mostrar "Leer más"', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Sí', 'elementor-post-layout'),
                'label_off' => esc_html__('No', 'elementor-post-layout'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label' => esc_html__('Texto "Leer más"', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Leer más »', 'elementor-post-layout'),
                'condition' => [
                    'show_read_more' => 'yes',
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
                'description' => esc_html__('Número de posts a saltar.', 'elementor-post-layout'),
            ]
        );

        $categories = get_categories(['hide_empty' => false]);
        $category_options = ['' => esc_html__('Todas las categorías', 'elementor-post-layout')];
        foreach ($categories as $category) {
            $category_options[$category->slug] = $category->name;
        }

        $this->add_control(
            'category_filter',
            [
                'label' => esc_html__('Categoría', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $category_options,
                'default' => '',
                'description' => esc_html__('Filtra los posts por la categoría seleccionada.', 'elementor-post-layout'),
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
                    '{{WRAPPER}} .elpl-archive-grid' => 'column-gap: {{SIZE}}{{UNIT}};',
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
                    '{{WRAPPER}} .elpl-archive-grid' => 'row-gap: {{SIZE}}{{UNIT}};',
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

        $this->add_control(
            'title_color',
            [
                'label' => esc_html__('Color del Título', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .elpl-archive-title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__('Tipografía del Título', 'elementor-post-layout'),
                'selector' => '{{WRAPPER}} .elpl-archive-title',
            ]
        );

        $this->add_control(
            'image_border_radius',
            [
                'label' => esc_html__('Border Radius Imagen', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .elpl-archive-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                'separator' => 'before',
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

        $this->add_control(
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
        $ppp = !empty($settings['posts_per_page']) ? absint($settings['posts_per_page']) : 10;
        $offset = !empty($settings['offset']) ? absint($settings['offset']) : 0;
        $cols = !empty($settings['columns']) ? absint($settings['columns']) : 3;
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $query_args = [
            'post_status' => 'publish',
            'posts_per_page' => $ppp,
            'offset' => $offset + (($paged - 1) * $ppp),
            'orderby' => $settings['orderby'],
            'order' => $settings['order'],
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

        if (!empty($settings['category_filter'])) {
            $query_args['category_name'] = sanitize_text_field($settings['category_filter']);
            unset($query_args['tax_query']); // Overwrite context if a specific category is chosen
        }

        $query = new \WP_Query($query_args);

        if (!$query->have_posts()) {
            echo '<p>' . esc_html__('No se encontraron posts.', 'elementor-post-layout') . '</p>';
            return;
        }

        $grid_class = 'elpl-archive-grid elpl-archive-cols-' . $cols;
        if ($settings['image_position'] !== 'top') {
            $grid_class .= ' elpl-archive-image-' . $settings['image_position'];
        }
        ?>

        <div class="elpl-archive-module">
            <div class="<?php echo esc_attr($grid_class); ?>">
                <?php
                while ($query->have_posts()):
                    $query->the_post();
                    $thumbnail = get_the_post_thumbnail_url(get_the_ID(), $settings['image_size']);
                    ?>
                    <article class="elpl-archive-post">
                        <?php if ($settings['show_image'] === 'yes' && $thumbnail): ?>
                            <div class="elpl-archive-image" style="background-image: url('<?php echo esc_url($thumbnail); ?>');">
                                <a href="<?php the_permalink(); ?>" class="elpl-full-link"></a>
                            </div>
                        <?php endif; ?>

                        <div class="elpl-archive-content">
                            <?php if ($settings['show_meta'] === 'yes' && !empty($settings['meta_data'])): ?>
                                <div class="elpl-archive-meta">
                                    <?php foreach ($settings['meta_data'] as $meta_key): ?>
                                        <span class="elpl-archive-meta-item elpl-archive-meta-<?php echo esc_attr($meta_key); ?>">
                                            <?php
                                            switch ($meta_key) {
                                                case 'author':
                                                    echo '<span class="elpl-meta-icon eicon-user"></span> ' . get_the_author();
                                                    break;
                                                case 'date':
                                                    echo '<span class="elpl-meta-icon eicon-calendar"></span> ' . get_the_date();
                                                    break;
                                                case 'comments':
                                                    echo '<span class="elpl-meta-icon eicon-comments"></span> ' . get_comments_number();
                                                    break;
                                                case 'time':
                                                    echo '<span class="elpl-meta-icon eicon-clock"></span> ' . get_the_time();
                                                    break;
                                            }
                                            ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($settings['show_title'] === 'yes'): ?>
                                <<?php echo esc_attr($settings['title_tag']); ?> class="elpl-archive-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </<?php echo esc_attr($settings['title_tag']); ?>>
                            <?php endif; ?>

                            <?php if ($settings['show_excerpt'] === 'yes'): ?>
                                <div class="elpl-archive-excerpt">
                                    <?php echo wp_trim_words(get_the_excerpt(), $settings['excerpt_length']); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($settings['show_read_more'] === 'yes'): ?>
                                <a href="<?php the_permalink(); ?>" class="elpl-archive-read-more">
                                    <?php echo esc_html($settings['read_more_text']); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </article>
                    <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>

            <?php if ($settings['show_pagination'] === 'yes'): ?>
                <div class="elpl-pagination">
                    <?php
                    echo paginate_links([
                        'total' => $query->max_num_pages,
                        'current' => $paged,
                        'prev_text' => '&laquo;',
                        'next_text' => '&raquo;',
                    ]);
                    ?>
                </div>
            <?php endif; ?>
        </div>

        <style>
            .elpl-archive-grid {
                display: grid;
                /* El gap base será reemplazado por los controles de style de Elementor: row-gap y column-gap */
                column-gap: 30px;
                row-gap: 35px;
            }

            .elpl-archive-cols-1 {
                grid-template-columns: repeat(1, 1fr);
            }

            .elpl-archive-cols-2 {
                grid-template-columns: repeat(2, 1fr);
            }

            .elpl-archive-cols-3 {
                grid-template-columns: repeat(3, 1fr);
            }

            .elpl-archive-cols-4 {
                grid-template-columns: repeat(4, 1fr);
            }

            .elpl-archive-cols-6 {
                grid-template-columns: repeat(6, 1fr);
            }

            .elpl-archive-post {
                display: flex;
                flex-direction: column;
            }

            .elpl-archive-image {
                width: 100%;
                /* aspect-ratio: 16/9; Eliminado en favor del slider en los estilos */
                background-size: cover;
                background-position: center;
                margin-bottom: 15px;
                position: relative;
            }

            .elpl-archive-image-left .elpl-archive-post,
            .elpl-archive-image-right .elpl-archive-post {
                flex-direction: row;
                align-items: flex-start;
                gap: 20px;
            }

            .elpl-archive-image-right .elpl-archive-post {
                flex-direction: row-reverse;
            }

            .elpl-archive-image-left .elpl-archive-image,
            .elpl-archive-image-right .elpl-archive-image {
                flex: 0 0 40%;
                max-width: 40%;
                margin-bottom: 0;
            }

            .elpl-archive-image-left .elpl-archive-content,
            .elpl-archive-image-right .elpl-archive-content {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
            }

            .elpl-archive-title {
                margin: 10px 0;
                font-weight: 700;
            }

            .elpl-archive-title a {
                color: #111;
                text-decoration: none;
            }

            .elpl-archive-meta {
                font-size: 12px;
                color: #888;
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                margin-bottom: 5px;
            }

            .elpl-archive-meta-item {
                display: flex;
                align-items: center;
                gap: 5px;
            }

            .elpl-meta-icon {
                font-size: 14px;
            }

            .elpl-archive-excerpt {
                font-size: 14px;
                line-height: 1.5;
                color: #444;
            }

            .elpl-archive-read-more {
                display: inline-block;
                margin-top: 10px;
                color: #e21a22;
                font-weight: 700;
                text-decoration: none;
            }

            .elpl-pagination {
                display: flex;
                gap: 10px;
                justify-content: center;
            }

            .elpl-pagination a,
            .elpl-pagination span {
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

            @media (max-width: 1024px) {
                .elpl-archive-grid:not(.elpl-archive-cols-1) {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (max-width: 767px) {
                .elpl-archive-grid {
                    grid-template-columns: 1fr !important;
                }

                .elpl-archive-image-left .elpl-archive-post,
                .elpl-archive-image-right .elpl-archive-post {
                    flex-direction: column;
                }

                .elpl-archive-image-left .elpl-archive-image,
                .elpl-archive-image-right .elpl-archive-image {
                    width: 100%;
                }
            }
        </style>
        <?php
    }

    protected function _content_template()
    {
    }
}
