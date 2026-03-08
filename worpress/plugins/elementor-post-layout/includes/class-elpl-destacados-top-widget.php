<?php
namespace ELPL\Widgets;

/**
 * Elementor Destacados Top Widget
 *
 * Implements a "Featured + Grid" layout with highly responsive controls.
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!defined('ELPL_VERSION')) {
    define('ELPL_VERSION', '1.6.0');
}

class ELPL_Destacados_Top_Widget extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'elpl_destacados_top';
    }

    public function get_title()
    {
        return esc_html__('Destacados Top', 'elementor-post-layout');
    }

    public function get_version()
    {
        return '1.6.0';
    }

    public function get_icon()
    {
        return 'eicon-posts-ticker';
    }

    public function get_categories()
    {
        return array('cartes-widgets');
    }

    protected function register_controls()
    {
        /* ── CONFIGURACIÓN ─────────────────────────────────────────────── */
        $this->start_controls_section(
            'section_query',
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

        $this->add_responsive_control(
            'num_posts',
            array(
                'label' => esc_html__('Número de Posts Total', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'default' => 7,
                'tablet_default' => 7,
                'mobile_default' => 4,
            )
        );

        $this->add_control(
            'destacado_pos',
            array(
                'label' => esc_html__('Posición del Destacado (1 = primero)', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'default' => 1,
            )
        );

        $this->add_control(
            'columnas',
            array(
                'label' => esc_html__('Columnas (Grilla inferior)', 'elementor-post-layout'),
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
            'offset',
            array(
                'label' => esc_html__('Offset (Desplazamiento)', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 0,
                'default' => 0,
            )
        );

        /* ── Metadatos: Destacado ────────────────────────────────────────── */
        $this->add_control(
            'feat_meta_heading',
            array(
                'label' => esc_html__('Metadato — Nota Destacada', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'feat_meta_type',
            array(
                'label' => esc_html__('Metadato a mostrar', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'date' => esc_html__('Fecha', 'elementor-post-layout'),
                    'category' => esc_html__('Categoría (primera)', 'elementor-post-layout'),
                    'author' => esc_html__('Autor', 'elementor-post-layout'),
                    'none' => esc_html__('Ninguno', 'elementor-post-layout'),
                ),
                'default' => 'date',
            )
        );

        $this->add_control(
            'feat_date_format',
            array(
                'label' => esc_html__('Formato de Fecha', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'M j, Y',
                'placeholder' => 'M j, Y',
                'condition' => array('feat_meta_type' => 'date'),
            )
        );

        /* ── Extracto: Destacado (responsivo) ───────────────────────────── */
        $this->add_responsive_control(
            'show_feat_excerpt',
            array(
                'label' => esc_html__('Mostrar Extracto (Destacado)', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => array(
                    'block' => array(
                        'title' => esc_html__('Visible', 'elementor-post-layout'),
                        'icon' => 'eicon-eye',
                    ),
                    'none' => array(
                        'title' => esc_html__('Oculto', 'elementor-post-layout'),
                        'icon' => 'eicon-eye-close',
                    ),
                ),
                'default' => 'block',
                'tablet_default' => 'block',
                'mobile_default' => 'block',
                'selectors' => array(
                    '{{WRAPPER}} .elpl-top-feat-excerpt' => 'display: {{VALUE}};',
                ),
                'toggle' => false,
                'separator' => 'before',
            )
        );

        /* ── Metadatos: Secundarios ─────────────────────────────────────── */
        $this->add_control(
            'grid_meta_heading',
            array(
                'label' => esc_html__('Metadato — Notas Secundarias', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_control(
            'grid_meta_type',
            array(
                'label' => esc_html__('Metadato a mostrar', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'date' => esc_html__('Fecha', 'elementor-post-layout'),
                    'category' => esc_html__('Categoría (primera)', 'elementor-post-layout'),
                    'author' => esc_html__('Autor', 'elementor-post-layout'),
                    'none' => esc_html__('Ninguno', 'elementor-post-layout'),
                ),
                'default' => 'date',
            )
        );

        $this->add_control(
            'grid_date_format',
            array(
                'label' => esc_html__('Formato de Fecha', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'M j, Y',
                'placeholder' => 'M j, Y',
                'condition' => array('grid_meta_type' => 'date'),
            )
        );

        /* ── Extracto: Secundarios (responsivo) ─────────────────────────── */
        $this->add_responsive_control(
            'show_grid_excerpt',
            array(
                'label' => esc_html__('Mostrar Extracto (Secundarios)', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => array(
                    'block' => array(
                        'title' => esc_html__('Visible', 'elementor-post-layout'),
                        'icon' => 'eicon-eye',
                    ),
                    'none' => array(
                        'title' => esc_html__('Oculto', 'elementor-post-layout'),
                        'icon' => 'eicon-eye-close',
                    ),
                ),
                'default' => 'none',
                'tablet_default' => 'none',
                'mobile_default' => 'none',
                'selectors' => array(
                    '{{WRAPPER}} .elpl-top-post-excerpt' => 'display: {{VALUE}};',
                ),
                'toggle' => false,
                'separator' => 'before',
            )
        );

        /* ── Visibilidad del Destacado ───────────────────────────────────── */
        $this->add_control(
            'featured_visibility_heading',
            array(
                'label' => esc_html__('Visibilidad del Post Destacado', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_responsive_control(
            'featured_visibility',
            array(
                'label' => esc_html__('Mostrar Destacado', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => array(
                    'block' => array(
                        'title' => esc_html__('Visible', 'elementor-post-layout'),
                        'icon' => 'eicon-eye',
                    ),
                    'none' => array(
                        'title' => esc_html__('Oculto', 'elementor-post-layout'),
                        'icon' => 'eicon-eye-close',
                    ),
                ),
                'default' => 'block',
                'tablet_default' => 'block',
                'mobile_default' => 'block',
                'selectors' => array(
                    '{{WRAPPER}} .elpl-top-featured' => 'display: {{VALUE}} !important;',
                ),
                'toggle' => false,
            )
        );

        $this->end_controls_section();

        /* ── STYLE: Post Destacado ─────────────────────────────────────────── */
        $this->start_controls_section(
            'section_style_featured',
            array(
                'label' => esc_html__('Post Destacado', 'elementor-post-layout'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'featured_image_height',
            array(
                'label' => esc_html__('Alto de Imagen', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => array('px', 'vh'),
                'range' => array('px' => array('min' => 100, 'max' => 800)),
                'default' => array('unit' => 'px', 'size' => 250),
                'tablet_default' => array('unit' => 'px', 'size' => 250),
                'mobile_default' => array('unit' => 'px', 'size' => 200),
                'selectors' => array(
                    '{{WRAPPER}} .elpl-top-feat-img' => 'height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'featured_image_width',
            array(
                'label' => esc_html__('Ancho Máximo de Imagen', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => array('%', 'px'),
                'range' => array(
                    '%' => array('min' => 10, 'max' => 100),
                    'px' => array('min' => 100, 'max' => 1000),
                ),
                'default' => array('unit' => 'px', 'size' => 370), /* Fixed 370px width like shortcode */
                'tablet_default' => array('unit' => '%', 'size' => 100),
                'mobile_default' => array('unit' => '%', 'size' => 100),
                'selectors' => array(
                    '{{WRAPPER}} .elpl-top-feat-img-link' => 'flex: 0 0 {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'featured_title_color',
            array(
                'label' => esc_html__('Color Título', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => array(
                    '{{WRAPPER}} .elpl-top-feat-title a' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name' => 'featured_title_typography',
                'label' => esc_html__('Tipografía Título', 'elementor-post-layout'),
                'selector' => '{{WRAPPER}} .elpl-top-feat-title',
            )
        );

        $this->add_responsive_control(
            'featured_date_color',
            array(
                'label' => esc_html__('Color Fecha', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#B70015',
                'selectors' => array(
                    '{{WRAPPER}} .elpl-top-feat-date' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name' => 'featured_date_typography',
                'label' => esc_html__('Tipografía Fecha', 'elementor-post-layout'),
                'selector' => '{{WRAPPER}} .elpl-top-feat-date',
            )
        );

        $this->add_responsive_control(
            'featured_excerpt_color',
            array(
                'label' => esc_html__('Color Extracto', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#555555',
                'selectors' => array(
                    '{{WRAPPER}} .elpl-top-feat-excerpt' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name' => 'featured_excerpt_typography',
                'label' => esc_html__('Tipografía Extracto', 'elementor-post-layout'),
                'selector' => '{{WRAPPER}} .elpl-top-feat-excerpt',
            )
        );

        $this->add_responsive_control(
            'featured_excerpt_words',
            array(
                'label' => esc_html__('Palabras del Extracto', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 5,
                'max' => 100,
                'default' => 25,
                'tablet_default' => 25,
                'mobile_default' => 15,
            )
        );

        $this->add_responsive_control(
            'featured_margin_bottom',
            array(
                'label' => esc_html__('Margen Inferior del Bloque', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range' => array('px' => array('min' => 0, 'max' => 100)),
                'default' => array('unit' => 'px', 'size' => 40),
                'selectors' => array(
                    '{{WRAPPER}} .elpl-top-featured' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'feat_wrapper_gap',
            array(
                'label' => esc_html__('Espacio imagen / texto', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range' => array('px' => array('min' => 0, 'max' => 80)),
                'default' => array('unit' => 'px', 'size' => 20),
                'selectors' => array(
                    '{{WRAPPER}} .elpl-top-feat-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        // Control to change flex direction on mobile vs desktop.
        $this->add_responsive_control(
            'featured_layout_direction',
            array(
                'label' => esc_html__('Dirección del Layout', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => array(
                    'row' => array(
                        'title' => esc_html__('Imagen Izquierda', 'elementor-post-layout'),
                        'icon' => 'eicon-h-align-left',
                    ),
                    'row-reverse' => array(
                        'title' => esc_html__('Imagen Derecha', 'elementor-post-layout'),
                        'icon' => 'eicon-h-align-right',
                    ),
                    'column' => array(
                        'title' => esc_html__('Imagen Arriba', 'elementor-post-layout'),
                        'icon' => 'eicon-v-align-top',
                    ),
                    'column-reverse' => array(
                        'title' => esc_html__('Imagen Abajo', 'elementor-post-layout'),
                        'icon' => 'eicon-v-align-bottom',
                    ),
                ),
                'default' => 'row',
                'tablet_default' => 'column',
                'mobile_default' => 'column',
                'selectors' => array(
                    '{{WRAPPER}} .elpl-top-feat-wrapper' => 'flex-direction: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        /* ── STYLE: Posts Normales (Secundarias) ───────────────────────────── */
        $this->start_controls_section(
            'section_style_grid',
            array(
                'label' => esc_html__('Posts Secundarios', 'elementor-post-layout'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_responsive_control(
            'grid_gap',
            array(
                'label' => esc_html__('Espacio entre columnas y filas', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range' => array('px' => array('min' => 0, 'max' => 80)),
                'default' => array('unit' => 'px', 'size' => 20),
                'selectors' => array(
                    '{{WRAPPER}} .elpl-top-grid' => 'gap: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'grid_item_gap',
            array(
                'label' => esc_html__('Espacio imagen / texto (interno)', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range' => array('px' => array('min' => 0, 'max' => 40)),
                'default' => array('unit' => 'px', 'size' => 12),
                'selectors' => array(
                    '{{WRAPPER}} .elpl-top-post-link' => 'gap: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'grid_post_layout',
            array(
                'label' => esc_html__('Posición de Imagen', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => array(
                    'column' => array(
                        'title' => esc_html__('Arriba', 'elementor-post-layout'),
                        'icon' => 'eicon-v-align-top',
                    ),
                    'column-reverse' => array(
                        'title' => esc_html__('Abajo', 'elementor-post-layout'),
                        'icon' => 'eicon-v-align-bottom',
                    ),
                    'row' => array(
                        'title' => esc_html__('Izquierda', 'elementor-post-layout'),
                        'icon' => 'eicon-h-align-left',
                    ),
                    'row-reverse' => array(
                        'title' => esc_html__('Derecha', 'elementor-post-layout'),
                        'icon' => 'eicon-h-align-right',
                    ),
                ),
                'default' => 'column',
                'tablet_default' => 'column',
                'mobile_default' => 'column',
                'selectors' => array(
                    '{{WRAPPER}} .elpl-top-post-link' => 'flex-direction: {{VALUE}};',
                ),
                'toggle' => false,
            )
        );

        $this->add_responsive_control(
            'grid_image_width',
            array(
                'label' => esc_html__('Ancho imagen (layout horizontal)', 'elementor-post-layout'),
                'description' => esc_html__('Aplica cuando la imagen está a la izquierda o derecha.', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => array('%', 'px'),
                'range' => array(
                    '%' => array('min' => 20, 'max' => 80),
                    'px' => array('min' => 60, 'max' => 400),
                ),
                'default' => array('unit' => '%', 'size' => 40),
                'mobile_default' => array('unit' => '%', 'size' => 35),
                'selectors' => array(
                    '{{WRAPPER}} .elpl-top-post-image' => 'flex: 0 0 {{SIZE}}{{UNIT}}; max-width: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'grid_image_height',
            array(
                'label' => esc_html__('Alto de Imagen', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => array('px', 'vh'),
                'range' => array('px' => array('min' => 50, 'max' => 400)),
                'default' => array('unit' => 'px', 'size' => 220),
                'mobile_default' => array('unit' => 'px', 'size' => 180),
                'selectors' => array(
                    '{{WRAPPER}} .elpl-top-post-thumb' => 'height: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_responsive_control(
            'grid_title_color',
            array(
                'label' => esc_html__('Color Título', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#000000',
                'selectors' => array(
                    '{{WRAPPER}} .elpl-top-post-title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name' => 'grid_title_typography',
                'selector' => '{{WRAPPER}} .elpl-top-post-title',
            )
        );

        $this->add_responsive_control(
            'grid_date_color',
            array(
                'label' => esc_html__('Color Fecha', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#B70015',
                'selectors' => array(
                    '{{WRAPPER}} .elpl-top-post-date' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name' => 'grid_date_typography',
                'selector' => '{{WRAPPER}} .elpl-top-post-date',
            )
        );

        /* ── Extracto de Posts Secundarios ─────────────────────────────── */
        $this->add_control(
            'grid_excerpt_style_heading',
            array(
                'label' => esc_html__('Extracto', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_responsive_control(
            'grid_excerpt_color',
            array(
                'label' => esc_html__('Color Extracto', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#555555',
                'selectors' => array(
                    '{{WRAPPER}} .elpl-top-post-excerpt' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name' => 'grid_excerpt_typography',
                'label' => esc_html__('Tipografía Extracto', 'elementor-post-layout'),
                'selector' => '{{WRAPPER}} .elpl-top-post-excerpt',
            )
        );

        $this->add_responsive_control(
            'grid_excerpt_words',
            array(
                'label' => esc_html__('Palabras del Extracto', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 5,
                'max' => 100,
                'default' => 15,
                'tablet_default' => 15,
                'mobile_default' => 10,
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
                'default' => '#B70015',
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
                'default' => '#8f000e',
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
                'default' => array('top' => 4, 'right' => 4, 'bottom' => 4, 'left' => 4, 'unit' => 'px'),
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
                'default' => array('top' => 12, 'right' => 20, 'bottom' => 12, 'left' => 20, 'unit' => 'px'),
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
                'range' => array('px' => array('min' => 0, 'max' => 80)),
                'default' => array('unit' => 'px', 'size' => 20),
                'selectors' => array(
                    '{{WRAPPER}} .elpl-load-more-wrap' => 'margin-top: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'load_more_alignment',
            array(
                'label' => esc_html__('Alineación', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => array(
                    'left' => array('title' => esc_html__('Izquierda', 'elementor-post-layout'), 'icon' => 'eicon-text-align-left'),
                    'center' => array('title' => esc_html__('Centrado', 'elementor-post-layout'), 'icon' => 'eicon-text-align-center'),
                    'right' => array('title' => esc_html__('Derecha', 'elementor-post-layout'), 'icon' => 'eicon-text-align-right'),
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
        foreach ($categories as $cat) {
            $options[$cat->slug] = $cat->name;
        }
        return $options;
    }

    /**
     * Returns the meta value string for the current post in The Loop,
     * based on the meta_type setting.
     */
    private function get_post_meta_display($meta_type, $date_format)
    {
        switch ($meta_type) {
            case 'category':
                $cats = get_the_category();
                return $cats ? esc_html($cats[0]->name) : '';
            case 'author':
                return esc_html(get_the_author());
            case 'none':
                return '';
            case 'date':
            default:
                return esc_html(get_the_date($date_format));
        }
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $num_posts = max(1, absint($settings['num_posts']));
        $num_posts_mobile = !empty($settings['num_posts_mobile']) ? max(1, absint($settings['num_posts_mobile'])) : $num_posts;
        $offset = absint($settings['offset']);
        $columnas = max(1, min(5, intval($settings['columnas'])));
        $dest_pos = max(0, absint($settings['destacado_pos']) - 1); // 0-indexed

        // Metadatos independientes por sección
        $feat_meta_type = !empty($settings['feat_meta_type']) ? $settings['feat_meta_type'] : 'date';
        $feat_date_format = !empty($settings['feat_date_format']) ? $settings['feat_date_format'] : 'M j, Y';
        $grid_meta_type = !empty($settings['grid_meta_type']) ? $settings['grid_meta_type'] : 'date';
        $grid_date_format = !empty($settings['grid_date_format']) ? $settings['grid_date_format'] : 'M j, Y';

        // Palabras del extracto (desktop default; tablet/mobile se manejan via CSS display pero
        // el número de palabras usará el valor de desktop como fallback en PHP)
        $feat_excerpt_words = !empty($settings['featured_excerpt_words']) ? max(5, absint($settings['featured_excerpt_words'])) : 25;
        $grid_excerpt_words = !empty($settings['grid_excerpt_words']) ? max(5, absint($settings['grid_excerpt_words'])) : 15;

        $args = array(
            'post_type'      => 'post',
            'posts_per_page' => max($num_posts, $num_posts_mobile), // consultar el mayor entre desktop y mobile
            'offset'         => $offset,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        );

        if (!empty($settings['post_category'])) {
            $args['category_name'] = $settings['post_category'];
        }

        $query = new \WP_Query($args);

        if (!$query->have_posts()) {
            echo '<p>' . esc_html__('No hay posts disponibles.', 'elementor-post-layout') . '</p>';
            return;
        }
        ?>
        <div class="elpl-top-module" data-elpl-module="1">
        <?php
            // Número total a consultar: el mayor entre desktop y mobile
            $num_posts_total  = max($num_posts, $num_posts_mobile);

            // Máx de posts de grilla visibles por breakpoint (−1 porque 1 es el destacado)
            $desktop_grid_max = max(0, $num_posts        - 1);
            $mobile_grid_max  = max(0, $num_posts_mobile - 1);

            $wid = esc_attr($this->get_id());
            $has_diff = ($num_posts !== $num_posts_mobile);
            if ($has_diff):
            ?>
            <style>
                <?php if ($num_posts_mobile < $num_posts): /* mobile muestra MENOS */ ?>
                @media(max-width:767px) {
                    .elementor-element-<?php echo $wid; ?> .elpl-top-post--m-hidden { display: none; }
                }
                <?php else: /* mobile muestra MÁS — ocultar extra en desktop */ ?>
                @media(min-width:768px) {
                    .elementor-element-<?php echo $wid; ?> .elpl-top-post--d-hidden { display: none; }
                }
                <?php endif; ?>
            </style>
            <?php endif; ?>

            <?php /* ── DESTACADO ──────────────────────────────────────────────── */
            $query->rewind_posts();
            while ($query->have_posts()):
                $query->the_post();
                if ($query->current_post !== $dest_pos) {
                    continue;
                }
                ?>
                <div class="elpl-top-featured">
                    <div class="elpl-top-feat-wrapper">
                        <a href="<?php the_permalink(); ?>" class="elpl-top-feat-img-link">
                            <?php the_post_thumbnail('full', array('class' => 'elpl-top-feat-img')); ?>
                        </a>
                        <div class="elpl-top-feat-body">
                            <?php
                            $feat_meta = $this->get_post_meta_display($feat_meta_type, $feat_date_format);
                            if ($feat_meta !== ''): ?>
                                <div class="elpl-top-feat-date">
                                    <?php echo $feat_meta; ?>
                                </div>
                            <?php endif; ?>
                            <h2 class="elpl-top-feat-title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                            <p class="elpl-top-feat-excerpt">
                                <?php echo esc_html(wp_trim_words(get_the_excerpt(), $feat_excerpt_words)); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <?php
                break; // Show only one featured post
            endwhile;
            ?>

            <?php /* ── GRILLA SECUNDARIAS ─────────────────────────────────────── */
            ?>
            <div class="elpl-top-grid elpl-top-grid-cols-<?php echo esc_attr($columnas); ?>">
                <?php
                $query->rewind_posts();
                $grid_post_idx = 0;
                while ($query->have_posts()):
                    $query->the_post();
                    if ($query->current_post === $dest_pos) {
                        continue; // saltarse el destacado
                    }
                    $grid_post_idx++;
                    // Determinar visibilidad: ocultar en mobile si supera mobile_grid_max, ocultar en desktop si supera desktop_grid_max
                    $hidden_cls = '';
                    if ($grid_post_idx > $mobile_grid_max && $num_posts_mobile <= $num_posts) {
                        $hidden_cls = ' elpl-top-post--m-hidden'; // mobile muestra menos
                    } elseif ($grid_post_idx > $desktop_grid_max && $num_posts_mobile > $num_posts) {
                        $hidden_cls = ' elpl-top-post--d-hidden'; // desktop muestra menos
                    }
                    ?>
                    <div class="elpl-top-post<?php echo esc_attr($hidden_cls); ?>">
                        <a href="<?php the_permalink(); ?>" class="elpl-top-post-link">
                            <div class="elpl-top-post-image">
                                <?php the_post_thumbnail('medium_large', array('class' => 'elpl-top-post-thumb')); ?>
                            </div>
                            <div class="elpl-top-post-text">
                                <?php
                                $grid_meta = $this->get_post_meta_display($grid_meta_type, $grid_date_format);
                                if ($grid_meta !== ''): ?>
                                    <div class="elpl-top-post-date">
                                        <?php echo $grid_meta; ?>
                                    </div>
                                <?php endif; ?>
                                <h3 class="elpl-top-post-title">
                                    <?php the_title(); ?>
                                </h3>
                                <p class="elpl-top-post-excerpt">
                                    <?php echo esc_html(wp_trim_words(get_the_excerpt(), $grid_excerpt_words)); ?>
                                </p>
                            </div>
                        </a>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>

            <?php
            // ── BOTÓN CARGAR MÁS (solo mobile, controlado por CSS) ───────────────
            $num_posts_total   = max($num_posts, $num_posts_mobile); // ya referenciado arriba
            $button_offset     = $offset + $num_posts_total; // todos los posts del DOM (máximo entre desktop y mobile)
            // posts ocultos en mobile que el JS puede revelar antes de ir al servidor
            $hidden_grid_count = max(0, $num_posts - $num_posts_mobile); // solo aplica cuando desktop > mobile
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
            $has_db_more = $more_check->have_posts() ? 'true' : 'false';
            $no_more_class = (!$more_check->have_posts() && $hidden_grid_count <= 0) ? ' elpl-no-more' : '';
            wp_reset_postdata();

            printf(
                '<div class="elpl-load-more-wrap"><button class="elpl-load-more-btn%s" data-widget="elpl_destacados_top" data-grid=".elpl-top-grid" data-category="%s" data-per-page="%d" data-offset="%d" data-date-format="%s" data-meta-type="%s" data-hidden-count="%d" data-has-db-more="%s">%s</button></div>',
                esc_attr($no_more_class),
                esc_attr($settings['post_category'] ?? ''),
                $num_posts_mobile,
                $button_offset,
                esc_attr($grid_date_format),
                esc_attr($grid_meta_type),
                $hidden_grid_count,
                $has_db_more,
                esc_html__('Cargar más', 'elementor-post-layout')
            );
            ?>

        </div><!-- .elpl-top-module -->
        <?php
    }

    protected function _content_template()
    {
        ?>
        <# var cols=settings.columnas || 3; var n=settings.num_posts || 7; #>
            <div class="elpl-top-module elpl-editor-preview">
                <div
                    style="margin-bottom: 20px; font-weight: bold; color: #444; display: flex; align-items: center; justify-content: center; gap: 10px;">
                    <i class="eicon-posts-ticker" style="font-size: 24px; color: #B70015;"></i>
                    <span>
                        <?php esc_html_e('Destacados Top (Editor)', 'elementor-post-layout'); ?>
                    </span>
                </div>

                <div class="elpl-top-featured">
                    <div class="elpl-top-feat-wrapper">
                        <div class="elpl-top-feat-img-link" style="background:#eee; height:300px; width:100%;"></div>
                        <div class="elpl-top-feat-body">
                            <div style="background:#B70015; height:14px; width:20%; margin-bottom:12px; border-radius:2px;">
                            </div>
                            <div style="background:#333; height:28px; width:90%; margin-bottom:10px; border-radius:2px;"></div>
                            <div style="background:#333; height:28px; width:75%; margin-bottom:15px; border-radius:2px;"></div>
                            <div style="background:#aaa; height:16px; width:100%; margin-bottom:5px; border-radius:2px;"></div>
                            <div style="background:#aaa; height:16px; width:80%; border-radius:2px;"></div>
                        </div>
                    </div>
                </div>

                <div class="elpl-top-grid elpl-top-grid-cols-{{ cols }}">
                    <# for(var i=0; i < n-1; i++) { #>
                        <div class="elpl-top-post">
                            <div class="elpl-top-post-image" style="background:#eee; height:180px; margin-bottom: 12px;"></div>
                            <div class="elpl-top-post-text">
                                <div style="background:#B70015; height:12px; width:40%; margin-bottom:8px; border-radius:2px;">
                                </div>
                                <div style="background:#333; height:18px; width:95%; margin-bottom:4px; border-radius:2px;">
                                </div>
                                <div style="background:#333; height:18px; width:60%; border-radius:2px;"></div>
                            </div>
                        </div>
                        <# } #>
                </div>
            </div>
            <?php
    }
}
