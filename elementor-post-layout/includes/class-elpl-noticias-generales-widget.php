<?php
namespace ELPL\Widgets;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor Noticias Generales Widget.
 */
class ELPL_Noticias_Generales_Widget extends \Elementor\Widget_Base
{

    /**
     * Get widget name.
     *
     * @since 1.5.7
     * @access public
     * @return string Widget name.
     */
    public function get_name()
    {
        return 'elpl_noticias_generales';
    }

    /**
     * Get widget title.
     *
     * @since 1.5.7
     * @access public
     * @return string Widget title.
     */
    public function get_title()
    {
        return esc_html__('Noticias Generales', 'elementor-post-layout');
    }

    /**
     * Get widget icon.
     *
     * @since 1.5.7
     * @access public
     * @return string Widget icon.
     */
    public function get_icon()
    {
        return 'eicon-post-list';
    }

    /**
     * Get widget categories.
     *
     * @since 1.5.7
     * @access public
     * @return array Widget categories.
     */
    public function get_categories()
    {
        return array('cartes-widgets');
    }

    /**
     * Register widget controls.
     *
     * @since 1.5.7
     * @access protected
     */
    protected function register_controls()
    {

        $this->start_controls_section(
            'content_section',
            array(
                'label' => esc_html__('Contenido', 'elementor-post-layout'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'posts_category',
            array(
                'label' => esc_html__('Categoría', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_post_categories(),
                'default' => '',
                'description' => esc_html__('Selecciona la categoría de noticias.', 'elementor-post-layout'),
            )
        );

        $this->add_control(
            'show_excerpt',
            array(
                'label' => esc_html__('Mostrar Extracto (Destacadas)', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Sí', 'elementor-post-layout'),
                'label_off' => esc_html__('No', 'elementor-post-layout'),
                'return_value' => 'yes',
                'default' => 'yes',
            )
        );

        $this->add_control(
            'reverse_columns',
            array(
                'label' => esc_html__('Invertir Columnas', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Sí', 'elementor-post-layout'),
                'label_off' => esc_html__('No', 'elementor-post-layout'),
                'return_value' => 'yes',
                'default' => 'no',
            )
        );

        $this->add_control(
            'mobile_batch',
            array(
                'label' => esc_html__('Posts por carga (Mobile)', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'default' => 4,
                'description' => esc_html__('Cantidad de posts que se cargan al pulsar "Cargar más" en mobile.', 'elementor-post-layout'),
            )
        );

        $this->add_control(
            'num_posts_mobile',
            array(
                'label' => esc_html__('Posts iniciales (Mobile)', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 30,
                'default' => 6,
                'description' => esc_html__('Cantidad de posts visibles al cargar la página en mobile. Solo afecta mobile — el desktop siempre muestra su diseño fijo.', 'elementor-post-layout'),
            )
        );

        $this->add_control(
            'meta_data',
            [
                'label' => esc_html__('Metadatos', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => ['date', 'comments'],
                'options' => [
                    'author' => esc_html__('Autor', 'elementor-post-layout'),
                    'date' => esc_html__('Fecha', 'elementor-post-layout'),
                    'time' => esc_html__('Hora', 'elementor-post-layout'),
                    'comments' => esc_html__('Comentarios', 'elementor-post-layout'),
                    'modified' => esc_html__('Fecha de Modificación', 'elementor-post-layout'),
                ],
            ]
        );

        $this->add_control(
            'meta_separator',
            [
                'label' => esc_html__('Separador', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '///',
                'selectors' => [
                    '{{WRAPPER}} .elpl-ng-meta span + span:before' => 'content: "{{VALUE}}"; padding: 0 4px;',
                ],
                'condition' => [
                    'meta_data!' => [],
                ],
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            array(
                'label' => esc_html__('Estilo', 'elementor-post-layout'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'heading_title_style',
            array(
                'label' => esc_html__('Título', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_responsive_control(
            'title_color',
            array(
                'label' => esc_html__('Color', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elpl-ng-title, {{WRAPPER}} .elpl-ng-title a' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elpl-ng-title, {{WRAPPER}} .elpl-ng-title a',
            )
        );

        $this->add_responsive_control(
            'title_spacing',
            array(
                'label' => esc_html__('Espaciado', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 100,
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elpl-ng-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->add_control(
            'heading_meta_style',
            array(
                'label' => esc_html__('Metadatos', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_responsive_control(
            'meta_color',
            array(
                'label' => esc_html__('Color', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elpl-ng-meta' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'meta_separator_color',
            array(
                'label' => esc_html__('Color del Separador', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elpl-ng-meta span + span:before' => 'color: {{VALUE}};',
                ),
                'condition' => [
                    'meta_data!' => [],
                ],
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name' => 'meta_typography',
                'selector' => '{{WRAPPER}} .elpl-ng-meta',
            )
        );

        $this->add_control(
            'excerpt_color',
            array(
                'label' => esc_html__('Color de Extracto', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elpl-ng-excerpt' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'show_excerpt' => 'yes',
                ),
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

    /**
     * Get post categories.
     */
    private function get_post_categories()
    {
        $categories = get_categories(array('hide_empty' => 0));
        $options = array('' => esc_html__('Todas', 'elementor-post-layout'));

        foreach ($categories as $category) {
            $options[$category->slug] = $category->name;
        }

        return $options;
    }

    /**
     * Render Meta Data
     */
    protected function render_meta_data($post)
    {
        $settings = $this->get_settings_for_display();
        if (empty($settings['meta_data'])) {
            return;
        }
        ?>
        <div class="elpl-ng-meta" style="font-size: 0.85em; margin-bottom: 8px;">
            <?php
            foreach ($settings['meta_data'] as $meta) {
                echo '<span class="elpl-ng-meta-' . esc_attr($meta) . '">';
                switch ($meta) {
                    case 'author':
                        echo esc_html(get_the_author_meta('display_name', $post->post_author));
                        break;
                    case 'date':
                        echo esc_html(get_the_date('', $post->ID));
                        break;
                    case 'time':
                        echo esc_html(get_the_time('', $post->ID));
                        break;
                    case 'comments':
                        echo esc_html(get_comments_number($post->ID));
                        break;
                    case 'modified':
                        echo esc_html(get_the_modified_date('', $post->ID));
                        break;
                }
                echo '</span>';
            }
            ?>
        </div>
        <?php
    }

    /**
     * Render widget output on the frontend.
     *
     * @since 1.5.7
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $mobile_batch = absint($settings['mobile_batch'] ?? 4);
        $num_posts_mobile = max(1, absint($settings['num_posts_mobile'] ?? 6));
        $category_val = $settings['posts_category'] ?? '';

        // ══════════════════════════════════════════════════════════════════════
        // DESKTOP: query fija de 6 posts — NO TOCAR BAJO NINGUNA CIRCUNSTANCIA
        // ══════════════════════════════════════════════════════════════════════
        $desktop_args = array(
            'post_type' => 'post',
            'posts_per_page' => 6,
        );
        if (!empty($category_val)) {
            $desktop_args['category_name'] = $category_val;
        }
        $desktop_query = new \WP_Query($desktop_args);

        if ($desktop_query->have_posts()) {
            $posts = $desktop_query->posts;
            $featured_posts = array_slice($posts, 0, 2);
            $list_posts = array_slice($posts, 2, 4);
            $is_reversed = ('yes' === $settings['reverse_columns']) ? ' elpl-ng-reversed' : '';
            ?>
            <div class="elpl-noticias-generales-module<?php echo esc_attr($is_reversed); ?>">

                <!-- Column 1: Featured (2 posts) -->
                <div class="elpl-ng-column elpl-ng-featured-col">
                    <div class="elpl-ng-featured-grid">
                        <?php foreach ($featured_posts as $post):
                            setup_postdata($post);
                            $thumb = get_the_post_thumbnail_url($post->ID, 'medium_large'); ?>
                            <div class="elpl-ng-card elpl-ng-featured-card">
                                <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="elpl-ng-thumb-link">
                                    <div class="elpl-ng-thumbnail" style="background-image: url('<?php echo esc_url($thumb); ?>');">
                                    </div>
                                </a>
                                <div class="elpl-ng-content">
                                    <h3 class="elpl-ng-title"><a
                                            href="<?php echo esc_url(get_permalink($post->ID)); ?>"><?php echo esc_html($post->post_title); ?></a>
                                    </h3>
                                    <?php $this->render_meta_data($post); ?>
                                    <?php if ('yes' === $settings['show_excerpt']): ?>
                                        <div class="elpl-ng-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt($post->ID), 25)); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach;
                        wp_reset_postdata(); ?>
                    </div>
                </div>

                <!-- Column 2: List (4 posts) -->
                <div class="elpl-ng-column elpl-ng-list-col">
                    <div class="elpl-ng-list">
                        <?php foreach ($list_posts as $post):
                            setup_postdata($post);
                            $thumb = get_the_post_thumbnail_url($post->ID, 'thumbnail'); ?>
                            <div class="elpl-ng-card elpl-ng-list-card">
                                <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="elpl-ng-list-thumb">
                                    <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($post->post_title); ?>">
                                </a>
                                <div class="elpl-ng-list-content">
                                    <h4 class="elpl-ng-title"><a
                                            href="<?php echo esc_url(get_permalink($post->ID)); ?>"><?php echo esc_html($post->post_title); ?></a>
                                    </h4>
                                    <?php $this->render_meta_data($post); ?>
                                </div>
                            </div>
                        <?php endforeach;
                        wp_reset_postdata(); ?>
                    </div>
                </div>

            </div><!-- .elpl-noticias-generales-module -->
            <?php
        }

        // ══════════════════════════════════════════════════════════════════════
        // MOBILE: query INDEPENDIENTE con num_posts_mobile posts.
        // Visible solo en ≤767px (display:none en desktop via CSS global).
        // El módulo desktop (.elpl-noticias-generales-module) se oculta
        // en mobile via la regla CSS global agregada en widget-styles.css.
        // ══════════════════════════════════════════════════════════════════════
        $mobile_args = array(
            'post_type' => 'post',
            'posts_per_page' => $num_posts_mobile,
        );
        if (!empty($category_val)) {
            $mobile_args['category_name'] = $category_val;
        }
        $mobile_query = new \WP_Query($mobile_args);

        // Verificar si hay más posts en BD tras num_posts_mobile
        $more_check_args = array(
            'post_type' => 'post',
            'posts_per_page' => 1,
            'offset' => $num_posts_mobile,
            'post_status' => 'publish',
            'fields' => 'ids',
        );
        if (!empty($category_val)) {
            $more_check_args['category_name'] = $category_val;
        }
        $more_check = new \WP_Query($more_check_args);
        $no_more_class = $more_check->have_posts() ? '' : ' elpl-no-more';
        wp_reset_postdata();
        ?>
        <div class="elpl-ng-mobile-section" data-elpl-module="1">
            <div class="elpl-ng-mobile-more">
                <?php if ($mobile_query->have_posts()):
                    while ($mobile_query->have_posts()):
                        $mobile_query->the_post();
                        $thumb = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail'); ?>
                        <div class="elpl-ng-card elpl-ng-list-card">
                            <a href="<?php the_permalink(); ?>" class="elpl-ng-list-thumb">
                                <img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title_attribute(); ?>">
                            </a>
                            <div class="elpl-ng-list-content">
                                <?php $this->render_meta_data($post); ?>
                                <h4 class="elpl-ng-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                            </div>
                        </div>
                    <?php endwhile;
                    wp_reset_postdata();
                endif; ?>
            </div><!-- .elpl-ng-mobile-more -->
            <div class="elpl-load-more-wrap">
                <button class="elpl-load-more-btn<?php echo esc_attr($no_more_class); ?>" data-widget="elpl_noticias_generales"
                    data-grid=".elpl-ng-mobile-more" data-category="<?php echo esc_attr($category_val); ?>"
                    data-per-page="<?php echo esc_attr($mobile_batch); ?>"
                    data-offset="<?php echo esc_attr($num_posts_mobile); ?>" data-show-date="no" data-show-excerpt="no"
                    data-meta-data="<?php echo esc_attr(implode(',', $settings['meta_data'] ?? array())); ?>">
                    <?php esc_html_e('Cargar más', 'elementor-post-layout'); ?>
                </button>
            </div>
        </div><!-- .elpl-ng-mobile-section -->
        <?php
    }

    /**
     * Render widget output in the editor (JS Template)
     */
    protected function _content_template()
    {
        ?>
        <# var show_title=settings.show_title==='yes' ; #>
            <div class="elpl-noticias-generales elpl-editor-preview">
                <div class="elpl-editor-placeholder"
                    style="padding: 20px; border: 2px dashed #e0e0e0; border-radius: 8px; background: #fff; text-align: center;">
                    <div
                        style="margin-bottom: 20px; font-weight: bold; color: #333; display: flex; align-items: center; justify-content: center; gap: 10px;">
                        <i class="eicon-post-list" style="font-size: 24px;"></i>
                        <span><?php esc_html_e('Noticias Generales (Editor)', 'elementor-post-layout'); ?></span>
                    </div>

                    <# if ( show_title && settings.section_title ) { #>
                        <div
                            style="text-align: left; margin-bottom: 15px; border-bottom: 2px solid #e21a22; padding-bottom: 5px;">
                            <span
                                style="background: #e21a22; color: #fff; padding: 2px 10px; font-size: 12px; font-weight: bold; text-transform: uppercase;">
                                {{ settings.section_title }}
                            </span>
                        </div>
                        <# } #>

                            <div class="elpl-ng-grid-mock"
                                style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px;">
                                <# for ( var i=0; i < 4; i++ ) { #>
                                    <div
                                        style="background: #fdfdfd; border: 1px solid #eee; border-radius: 4px; overflow: hidden;">
                                        <div style="background: #eee; height: 120px;"></div>
                                        <div style="padding: 12px; text-align: left;">
                                            <div
                                                style="background: #e21a22; height: 10px; width: 30%; margin-bottom: 8px; border-radius: 2px;">
                                            </div>
                                            <div
                                                style="background: #ddd; height: 12px; width: 90%; margin-bottom: 10px; border-radius: 2px;">
                                            </div>
                                            <div
                                                style="background: #f2f2f2; height: 8px; width: 100%; margin-bottom: 4px; border-radius: 1px;">
                                            </div>
                                            <div style="background: #f2f2f2; height: 8px; width: 80%; border-radius: 1px;">
                                            </div>
                                        </div>
                                    </div>
                                    <# } #>
                            </div>
                </div>
            </div>
            <?php
    }
}
