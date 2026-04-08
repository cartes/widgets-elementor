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

        $this->add_responsive_control(
            'image_position',
            [
                'label' => esc_html__('Posición de Imagen', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'top' => esc_html__('Arriba', 'elementor-post-layout'),
                    'bottom' => esc_html__('Abajo', 'elementor-post-layout'),
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
                'label' => esc_html__('Mostrar Extracto (Desktop)', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Sí', 'elementor-post-layout'),
                'label_off' => esc_html__('No', 'elementor-post-layout'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_excerpt_mobile',
            [
                'label' => esc_html__('Mostrar Extracto (Mobile)', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Sí', 'elementor-post-layout'),
                'label_off' => esc_html__('No', 'elementor-post-layout'),
                'default' => 'no',
                'description' => esc_html__('Controla si se muestra el extracto en dispositivos móviles.', 'elementor-post-layout'),
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
                'label' => esc_html__('Metadatos a mostrar (Desktop)', 'elementor-post-layout'),
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
            'meta_data_mobile',
            [
                'label' => esc_html__('Metadatos a mostrar (Mobile)', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => [
                    'author' => esc_html__('Autor', 'elementor-post-layout'),
                    'date' => esc_html__('Fecha', 'elementor-post-layout'),
                    'comments' => esc_html__('Comentarios', 'elementor-post-layout'),
                    'time' => esc_html__('Hora', 'elementor-post-layout'),
                ],
                'default' => ['date'],
                'description' => esc_html__('Selecciona qué metadatos mostrar en dispositivos móviles. Si se deja vacío, se usará la configuración de Desktop.', 'elementor-post-layout'),
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

        // Responsive image position
        $img_pos_desktop = !empty($settings['image_position']) ? $settings['image_position'] : 'top';
        $img_pos_tablet = !empty($settings['image_position_tablet']) ? $settings['image_position_tablet'] : $img_pos_desktop;
        $img_pos_mobile = !empty($settings['image_position_mobile']) ? $settings['image_position_mobile'] : 'top';

        $grid_class = 'elpl-archive-grid elpl-archive-cols-' . $cols;
        // Desktop image position class
        if ($img_pos_desktop !== 'top') {
            $grid_class .= ' elpl-archive-image-' . $img_pos_desktop;
        }
        // Tablet image position class
        if ($img_pos_tablet !== 'top') {
            $grid_class .= ' elpl-archive-image-tablet-' . $img_pos_tablet;
        }
        // Mobile image position class
        if ($img_pos_mobile !== 'top') {
            $grid_class .= ' elpl-archive-image-mobile-' . $img_pos_mobile;
        }

        // Responsive excerpt
        $show_excerpt_desktop = ($settings['show_excerpt'] === 'yes');
        $show_excerpt_mobile = ($settings['show_excerpt_mobile'] === 'yes');
        $excerpt_differs = ($show_excerpt_desktop !== $show_excerpt_mobile);

        // Responsive meta data
        $meta_desktop = !empty($settings['meta_data']) ? $settings['meta_data'] : [];
        $meta_mobile = !empty($settings['meta_data_mobile']) ? $settings['meta_data_mobile'] : $meta_desktop;
        $meta_differs = ($meta_desktop !== $meta_mobile);
        ?>

        <div class="elpl-archive-module" data-elpl-module="archive">
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
                            <div class="elpl-archive-header">
                                <?php if ($settings['show_meta'] === 'yes' && !empty($meta_desktop)): ?>
                                    <?php // Desktop meta (hidden on mobile if mobile has different selection) ?>
                                    <div class="elpl-archive-meta<?php echo $meta_differs ? ' elpl-archive-meta-desktop' : ''; ?>">
                                        <?php foreach ($meta_desktop as $meta_key): ?>
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

                                    <?php if ($meta_differs && !empty($meta_mobile)): ?>
                                        <?php // Mobile meta (only shown on mobile) ?>
                                        <div class="elpl-archive-meta elpl-archive-meta-mobile">
                                            <?php foreach ($meta_mobile as $meta_key): ?>
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
                                <?php endif; ?>

                                <?php if ($settings['show_title'] === 'yes'): ?>
                                    <<?php echo esc_attr($settings['title_tag']); ?> class="elpl-archive-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </<?php echo esc_attr($settings['title_tag']); ?>>
                                <?php endif; ?>
                            </div>

                            <?php if ($show_excerpt_desktop || $show_excerpt_mobile): ?>
                                <?php
                                $excerpt_class = 'elpl-archive-excerpt';
                                if ($excerpt_differs) {
                                    if ($show_excerpt_desktop && !$show_excerpt_mobile) {
                                        $excerpt_class .= ' elpl-archive-excerpt-desktop-only';
                                    } elseif (!$show_excerpt_desktop && $show_excerpt_mobile) {
                                        $excerpt_class .= ' elpl-archive-excerpt-mobile-only';
                                    }
                                }
                                ?>
                                <div class="<?php echo esc_attr($excerpt_class); ?>">
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
                <?php
                // Load More Button (Mobile only via CSS)
                $ajax_meta = !empty($meta_mobile) ? implode(',', $meta_mobile) : '';
                $current_cat = !empty($settings['category_filter']) ? $settings['category_filter'] : '';
                
                // Context-based data
                $ajax_taxonomy = '';
                $ajax_term_id = 0;
                $ajax_author_id = 0;
                $ajax_post_type = 'post';

                if (is_category() || is_tag() || is_tax()) {
                    $obj = get_queried_object();
                    $ajax_taxonomy = $obj->taxonomy;
                    $ajax_term_id = $obj->term_id;
                } elseif (is_post_type_archive()) {
                    $ajax_post_type = get_query_var('post_type');
                } elseif (is_author()) {
                    $ajax_author_id = get_queried_object_id();
                }
                ?>
                <div class="elpl-load-more-wrap">
                    <button class="elpl-load-more-btn"
                            data-widget="elpl_archive_posts_widget"
                            data-grid=".elpl-archive-grid"
                            data-per-page="<?php echo esc_attr($ppp); ?>"
                            data-offset="<?php echo esc_attr($offset + $ppp); ?>"
                            data-image-size="<?php echo esc_attr($settings['image_size']); ?>"
                            data-show-image="<?php echo esc_attr($settings['show_image']); ?>"
                            data-show-title="<?php echo esc_attr($settings['show_title']); ?>"
                            data-title-tag="<?php echo esc_attr($settings['title_tag']); ?>"
                            data-show-excerpt="<?php echo esc_attr($show_excerpt_mobile ? 'yes' : 'no'); ?>" 
                            data-excerpt-length="<?php echo esc_attr($settings['excerpt_length']); ?>"
                            data-meta-data="<?php echo esc_attr($ajax_meta); ?>"
                            data-show-read-more="<?php echo esc_attr($settings['show_read_more']); ?>"
                            data-read-more-text="<?php echo esc_attr($settings['read_more_text']); ?>"
                            data-category="<?php echo esc_attr($current_cat); ?>"
                            data-post-type="<?php echo esc_attr($ajax_post_type); ?>"
                            data-taxonomy="<?php echo esc_attr($ajax_taxonomy); ?>"
                            data-term-id="<?php echo esc_attr($ajax_term_id); ?>"
                            data-author-id="<?php echo esc_attr($ajax_author_id); ?>">
                        <?php echo esc_html__('Cargar más', 'elementor-post-layout'); ?>
                    </button>
                </div>

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

            /* Desktop: image position left/right */
            .elpl-archive-image-left .elpl-archive-post,
            .elpl-archive-image-right .elpl-archive-post {
                flex-direction: row;
                align-items: flex-start;
                gap: 20px;
            }

            .elpl-archive-image-right .elpl-archive-post {
                flex-direction: row-reverse;
            }

            /* Desktop: image position bottom */
            .elpl-archive-image-bottom .elpl-archive-post {
                flex-direction: column-reverse;
            }

            .elpl-archive-image-bottom .elpl-archive-image {
                margin-bottom: 0;
                margin-top: 15px;
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

            /* Responsive meta: mobile block hidden by default, desktop block shown */
            .elpl-archive-meta-mobile {
                display: none;
            }

            /* Responsive excerpt: visibility classes */
            .elpl-archive-excerpt-mobile-only {
                display: none;
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

                /* Tablet: image position overrides */
                .elpl-archive-image-tablet-left .elpl-archive-post {
                    flex-direction: row !important;
                    align-items: flex-start;
                    gap: 20px;
                }

                .elpl-archive-image-tablet-right .elpl-archive-post {
                    flex-direction: row-reverse !important;
                    align-items: flex-start;
                    gap: 20px;
                }

                .elpl-archive-image-tablet-bottom .elpl-archive-post {
                    flex-direction: column-reverse !important;
                }

                .elpl-archive-image-tablet-bottom .elpl-archive-image {
                    margin-bottom: 0;
                    margin-top: 15px;
                }

                .elpl-archive-image-tablet-left .elpl-archive-image,
                .elpl-archive-image-tablet-right .elpl-archive-image {
                    flex: 0 0 40%;
                    max-width: 40%;
                    margin-bottom: 0;
                }

                .elpl-archive-image-tablet-left .elpl-archive-content,
                .elpl-archive-image-tablet-right .elpl-archive-content {
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                    justify-content: flex-start;
                }

                /* Tablet: if no tablet-specific position, reset left/right on smaller screens */
                .elpl-archive-grid:not([class*="elpl-archive-image-tablet-"]).elpl-archive-image-left .elpl-archive-post,
                .elpl-archive-grid:not([class*="elpl-archive-image-tablet-"]).elpl-archive-image-right .elpl-archive-post {
                    /* Keep desktop behavior on tablet by default */
                }
            }

            @media (max-width: 767px) {
                .elpl-archive-grid {
                    grid-template-columns: 1fr !important;
                }

                /* Mobile: reset desktop/tablet image position to top (default behavior) */
                .elpl-archive-image-left .elpl-archive-post,
                .elpl-archive-image-right .elpl-archive-post,
                .elpl-archive-image-bottom .elpl-archive-post,
                .elpl-archive-image-tablet-left .elpl-archive-post,
                .elpl-archive-image-tablet-right .elpl-archive-post,
                .elpl-archive-image-tablet-bottom .elpl-archive-post {
                    flex-direction: column !important;
                }

                .elpl-archive-image-left .elpl-archive-image,
                .elpl-archive-image-right .elpl-archive-image,
                .elpl-archive-image-bottom .elpl-archive-image,
                .elpl-archive-image-tablet-left .elpl-archive-image,
                .elpl-archive-image-tablet-right .elpl-archive-image,
                .elpl-archive-image-tablet-bottom .elpl-archive-image {
                    flex: none !important;
                    max-width: 100% !important;
                    width: 100%;
                    margin-top: 0;
                }

                /* Mobile: image position overrides if explicitly set */
                .elpl-archive-image-mobile-left .elpl-archive-post {
                    display: grid !important;
                    grid-template-columns: 40% 1fr;
                    gap: 15px;
                }

                .elpl-archive-image-mobile-right .elpl-archive-post {
                    display: grid !important;
                    grid-template-columns: 1fr 40%;
                    gap: 15px;
                }

                .elpl-archive-image-mobile-left .elpl-archive-image {
                    grid-column: 1;
                    grid-row: 1;
                    margin-bottom: 0;
                }

                .elpl-archive-image-mobile-right .elpl-archive-image {
                    grid-column: 2;
                    grid-row: 1;
                    margin-bottom: 0;
                }

                .elpl-archive-image-mobile-left .elpl-archive-content,
                .elpl-archive-image-mobile-right .elpl-archive-content {
                    display: contents;
                }

                .elpl-archive-image-mobile-left .elpl-archive-header {
                    grid-column: 2;
                    grid-row: 1;
                    margin-top: 0;
                }

                .elpl-archive-image-mobile-right .elpl-archive-header {
                    grid-column: 1;
                    grid-row: 1;
                    margin-top: 0;
                }

                .elpl-archive-image-mobile-left .elpl-archive-excerpt,
                .elpl-archive-image-mobile-left .elpl-archive-read-more,
                .elpl-archive-image-mobile-right .elpl-archive-excerpt,
                .elpl-archive-image-mobile-right .elpl-archive-read-more {
                    grid-column: 1 / span 2;
                }

                .elpl-archive-image-mobile-bottom .elpl-archive-post {
                    flex-direction: column-reverse !important;
                }

                .elpl-archive-image-mobile-bottom .elpl-archive-image {
                    margin-bottom: 0;
                    margin-top: 15px;
                }

                .elpl-archive-image-mobile-left .elpl-archive-image,
                .elpl-archive-image-mobile-right .elpl-archive-image {
                    flex: none !important;
                    max-width: 100% !important;
                    width: 100%;
                }

                /* Mobile meta visibility */
                .elpl-archive-meta-desktop {
                    display: none !important;
                }

                .elpl-archive-meta-mobile {
                    display: flex !important;
                }

                /* Mobile excerpt visibility */
                .elpl-archive-excerpt-desktop-only {
                    display: none !important;
                }

                .elpl-archive-excerpt-mobile-only {
                    display: block !important;
                }
            }
        </style>
        <?php
    }

    protected function _content_template()
    {
    }
}
