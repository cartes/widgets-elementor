<?php
namespace ELPL\Widgets;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Elementor Columnas de Opinión Widget
 */
class ELPL_Opinion_Widget extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'elpl_opinion_widget';
    }

    public function get_title()
    {
        return esc_html__('Columnas de Opinión', 'elementor-post-layout');
    }

    public function get_icon()
    {
        return 'eicon-person';
    }

    public function get_categories()
    {
        return array('cartes-widgets');
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
            'section_title',
            [
                'label' => esc_html__('Título de la Sección', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Columnas de opinión', 'elementor-post-layout'),
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label' => esc_html__('Texto Leer más', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Leer más Comunas de Opinión', 'elementor-post-layout'),
            ]
        );

        $this->add_control(
            'read_more_url',
            [
                'label' => esc_html__('URL Leer más', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => esc_html__('https://your-link.com', 'elementor-post-layout'),
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'opinion_posts_per_page',
            [
                'label' => esc_html__('Número de posts', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
            ]
        );

        $this->add_control(
            'mobile_batch',
            [
                'label' => esc_html__('Posts por carga (Mobile)', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'default' => 4,
                'description' => esc_html__('Cantidad de posts que se cargan al pulsar "Cargar más" en mobile.', 'elementor-post-layout'),
            ]
        );

        $this->add_control(
            'num_posts_mobile',
            [
                'label' => esc_html__('Posts iniciales (Mobile)', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'default' => 3,
                'description' => esc_html__('Cuántas columnas de opinión se muestran al cargar en mobile.', 'elementor-post-layout'),
            ]
        );

        $this->add_control(
            'hr_meta',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'meta_persona_key',
            [
                'label' => esc_html__('Meta Key: Vínculo Persona', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'opinioncolumn-form',
                'description' => esc_html__('Campo en el post de Opinion que contiene el ID de la Persona (Ej: opinioncolumn-form).', 'elementor-post-layout'),
            ]
        );

        $this->add_control(
            'meta_cargo_key',
            [
                'label' => esc_html__('Meta Key: Cargo Persona', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'person_cargo',
                'description' => esc_html__('Campo en el post de Persona que contiene su cargo.', 'elementor-post-layout'),
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
            'primary_color',
            [
                'label' => esc_html__('Color Principal (Rojo)', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#e21a22',
                'selectors' => [
                    '{{WRAPPER}} .elpl-opinion-meta-box' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .elpl-opinion-header' => 'border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} .elpl-opinion-tab' => 'border-bottom-color: {{VALUE}};',
                    '{{WRAPPER}} .elpl-opinion-read-more' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elpl-opinion-date' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // ── Estilo: Botón de Paginación (Mobile) ─────────────────────────────
        $this->start_controls_section(
            'style_section_load_more',
            [
                'label' => esc_html__('Botón de Paginación (Mobile)', 'elementor-post-layout'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('load_more_btn_tabs');

        $this->start_controls_tab('load_more_btn_normal', ['label' => esc_html__('Normal', 'elementor-post-layout')]);

        $this->add_control('load_more_color', [
            'label' => esc_html__('Color de Texto', 'elementor-post-layout'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#ffffff',
            'selectors' => ['{{WRAPPER}} .elpl-load-more-btn' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('load_more_bg_color', [
            'label' => esc_html__('Color de Fondo', 'elementor-post-layout'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#e21a22',
            'selectors' => ['{{WRAPPER}} .elpl-load-more-btn' => 'background-color: {{VALUE}};'],
        ]);

        $this->end_controls_tab();

        $this->start_controls_tab('load_more_btn_hover', ['label' => esc_html__('Hover', 'elementor-post-layout')]);

        $this->add_control('load_more_color_hover', [
            'label' => esc_html__('Color de Texto', 'elementor-post-layout'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => ['{{WRAPPER}} .elpl-load-more-btn:hover' => 'color: {{VALUE}};'],
        ]);

        $this->add_control('load_more_bg_color_hover', [
            'label' => esc_html__('Color de Fondo', 'elementor-post-layout'),
            'type' => \Elementor\Controls_Manager::COLOR,
            'default' => '#c0151c',
            'selectors' => ['{{WRAPPER}} .elpl-load-more-btn:hover' => 'background-color: {{VALUE}};'],
        ]);

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(\Elementor\Group_Control_Typography::get_type(), [
            'name' => 'load_more_typography',
            'selector' => '{{WRAPPER}} .elpl-load-more-btn',
        ]);

        $this->add_group_control(\Elementor\Group_Control_Border::get_type(), [
            'name' => 'load_more_border',
            'selector' => '{{WRAPPER}} .elpl-load-more-btn',
        ]);

        $this->add_control('load_more_border_radius', [
            'label' => esc_html__('Radio de Borde', 'elementor-post-layout'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', '%'],
            'default' => ['top' => 4, 'right' => 4, 'bottom' => 4, 'left' => 4, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .elpl-load-more-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('load_more_padding', [
            'label' => esc_html__('Padding', 'elementor-post-layout'),
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => ['px', 'em'],
            'default' => ['top' => 12, 'right' => 20, 'bottom' => 12, 'left' => 20, 'unit' => 'px'],
            'selectors' => ['{{WRAPPER}} .elpl-load-more-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
        ]);

        $this->add_control('load_more_margin_top', [
            'label' => esc_html__('Margen Superior', 'elementor-post-layout'),
            'type' => \Elementor\Controls_Manager::SLIDER,
            'size_units' => ['px'],
            'range' => ['px' => ['min' => 0, 'max' => 80]],
            'default' => ['unit' => 'px', 'size' => 20],
            'selectors' => ['{{WRAPPER}} .elpl-load-more-wrap' => 'margin-top: {{SIZE}}{{UNIT}};'],
        ]);

        $this->add_control('load_more_alignment', [
            'label' => esc_html__('Alineación (Mobile)', 'elementor-post-layout'),
            'type' => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
                'left' => ['title' => esc_html__('Izquierda', 'elementor-post-layout'), 'icon' => 'eicon-text-align-left'],
                'center' => ['title' => esc_html__('Centrado', 'elementor-post-layout'), 'icon' => 'eicon-text-align-center'],
                'right' => ['title' => esc_html__('Derecha', 'elementor-post-layout'), 'icon' => 'eicon-text-align-right'],
            ],
            'default' => 'center',
            'selectors' => ['{{WRAPPER}} .elpl-load-more-wrap' => 'text-align: {{VALUE}};'],
        ]);

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $num_posts = !empty($settings['opinion_posts_per_page']) ? absint($settings['opinion_posts_per_page']) : 6;
        $num_mobile = max(1, absint($settings['num_posts_mobile'] ?? 3));
        $mobile_batch = absint($settings['mobile_batch'] ?? 4);
        $meta_persona = !empty($settings['meta_persona_key']) ? sanitize_text_field($settings['meta_persona_key']) : 'opinioncolumn_opinionAuthor';
        $meta_cargo = !empty($settings['meta_cargo_key']) ? sanitize_text_field($settings['meta_cargo_key']) : 'person_cargo';

        // ══════════════════════════════════════════════════════════════════════
        // DESKTOP: query con opinion_posts_per_page — NO TOCAR
        // ══════════════════════════════════════════════════════════════════════
        $desktop_query = new \WP_Query([
            'post_type' => 'opinion',
            'posts_per_page' => $num_posts,
            'post_status' => 'publish',
        ]);

        if ($desktop_query->have_posts()):
            ?>
            <style>
                @media (min-width: 768px) {
                    .elpl-opinion-desktop {
                        display: block !important;
                    }

                    .elpl-opinion-mobile {
                        display: none !important;
                    }
                }

                @media (max-width: 767px) {
                    .elpl-opinion-desktop {
                        display: none !important;
                    }

                    .elpl-opinion-mobile {
                        display: block !important;
                    }
                }
            </style>
            <div class="elpl-opinion-module elpl-opinion-desktop" data-elpl-module="1">

                <div class="elpl-opinion-grid">
                    <?php
                    while ($desktop_query->have_posts()):
                        $desktop_query->the_post();
                        $opinion_id = get_the_ID();
                        $persona_id = get_post_meta($opinion_id, $meta_persona, true);
                        if (is_array($persona_id) && !empty($persona_id)) {
                            if (isset($persona_id['opinionAuthor'])) {
                                $persona_id = $persona_id['opinionAuthor'];
                            } else {
                                $persona_id = array_values($persona_id)[0];
                            }
                        }
                        $persona_name = '';
                        $persona_cargo = '';
                        $persona_img = '';
                        if ($persona_id) {
                            $persona_post = get_post($persona_id);
                            if ($persona_post && $persona_post->post_type === 'person') {
                                $persona_name = $persona_post->post_title;
                                $persona_cargo = get_post_meta($persona_id, $meta_cargo, true);
                                if (is_array($persona_cargo) && !empty($persona_cargo)) {
                                    $persona_cargo = $persona_cargo[0];
                                }
                                $persona_img = get_the_post_thumbnail_url($persona_id, 'medium');
                            }
                        }
                        ?>
                        <div class="elpl-opinion-card">
                            <div class="elpl-opinion-card-top">
                                <div class="elpl-opinion-persona-img"
                                    style="background-image: url('<?php echo esc_url($persona_img); ?>'); background-position: 10% 10%;">
                                    <?php if (!$persona_img): ?><i class="eicon-person"></i><?php endif; ?>
                                </div>
                                <div class="elpl-opinion-content">
                                    <div class="elpl-opinion-date"><?php echo get_the_date('M j, Y'); ?></div>
                                    <h3 class="elpl-opinion-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <div class="elpl-opinion-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 18)); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="elpl-opinion-meta-box">
                                <div class="elpl-persona-name"><?php echo esc_html($persona_name); ?></div>
                                <div class="elpl-persona-title"><?php echo esc_html($persona_cargo); ?></div>
                            </div>
                        </div>
                    <?php endwhile;
                    wp_reset_postdata(); ?>
                </div><!-- .elpl-opinion-grid -->
            </div><!-- .elpl-opinion-desktop -->
            <?php
        endif;

        // ══════════════════════════════════════════════════════════════════════
        // MOBILE: query INDEPENDIENTE con num_posts_mobile posts.
        // Visible solo en ≪767px. El bloque desktop se oculta en mobile via CSS.
        // ══════════════════════════════════════════════════════════════════════
        $mobile_query = new \WP_Query([
            'post_type' => 'opinion',
            'posts_per_page' => $num_mobile,
            'post_status' => 'publish',
        ]);

        $more_check = new \WP_Query([
            'post_type' => 'opinion',
            'posts_per_page' => 1,
            'offset' => $num_mobile,
            'post_status' => 'publish',
            'fields' => 'ids',
        ]);
        $no_more_class = $more_check->have_posts() ? '' : ' elpl-no-more';
        wp_reset_postdata();
        ?>
        <div class="elpl-opinion-module elpl-opinion-mobile" data-elpl-module="1">
            <div class="elpl-opinion-grid elpl-opinion-mobile-more">
                <?php
                if ($mobile_query->have_posts()):
                    while ($mobile_query->have_posts()):
                        $mobile_query->the_post();
                        $opinion_id = get_the_ID();
                        $persona_id = get_post_meta($opinion_id, $meta_persona, true);
                        if (is_array($persona_id) && !empty($persona_id)) {
                            if (isset($persona_id['opinionAuthor'])) {
                                $persona_id = $persona_id['opinionAuthor'];
                            } else {
                                $persona_id = array_values($persona_id)[0];
                            }
                        }
                        $persona_name = '';
                        $persona_cargo = '';
                        $persona_img = '';
                        if ($persona_id) {
                            $persona_post = get_post($persona_id);
                            if ($persona_post && $persona_post->post_type === 'person') {
                                $persona_name = $persona_post->post_title;
                                $persona_cargo = get_post_meta($persona_id, $meta_cargo, true);
                                if (is_array($persona_cargo) && !empty($persona_cargo)) {
                                    $persona_cargo = $persona_cargo[0];
                                }
                                $persona_img = get_the_post_thumbnail_url($persona_id, 'medium');
                            }
                        }
                        ?>
                        <div class="elpl-opinion-card">
                            <div class="elpl-opinion-card-top">
                                <div class="elpl-opinion-persona-img"
                                    style="background-image: url('<?php echo esc_url($persona_img); ?>'); background-position: 10% 10%;">
                                    <?php if (!$persona_img): ?><i class="eicon-person"></i><?php endif; ?>
                                </div>
                                <div class="elpl-opinion-content">
                                    <div class="elpl-opinion-date"><?php echo get_the_date('M j, Y'); ?></div>
                                    <h3 class="elpl-opinion-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <div class="elpl-opinion-excerpt"><?php echo esc_html(wp_trim_words(get_the_excerpt(), 18)); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="elpl-opinion-meta-box">
                                <div class="elpl-persona-name"><?php echo esc_html($persona_name); ?></div>
                                <div class="elpl-persona-title"><?php echo esc_html($persona_cargo); ?></div>
                            </div>
                        </div>
                    <?php endwhile;
                    wp_reset_postdata();
                endif; ?>
            </div><!-- .elpl-opinion-mobile-more -->
            <div class="elpl-load-more-wrap">
                <button class="elpl-load-more-btn<?php echo esc_attr($no_more_class); ?>" data-widget="elpl_opinion_widget"
                    data-grid=".elpl-opinion-mobile-more" data-post-type="opinion"
                    data-per-page="<?php echo esc_attr($mobile_batch); ?>" data-offset="<?php echo esc_attr($num_mobile); ?>"
                    data-meta-persona="<?php echo esc_attr($meta_persona); ?>"
                    data-meta-cargo="<?php echo esc_attr($meta_cargo); ?>" data-show-date="no" data-show-excerpt="no">
                    <?php esc_html_e('Cargar más', 'elementor-post-layout'); ?>
                </button>
            </div>
        </div><!-- .elpl-opinion-mobile -->
        <?php
    }



    protected function _content_template()
    {
        ?>
        <# var title=settings.section_title || 'Columnas de opinión' ; var read_more=settings.read_more_text
            || 'Leer más Comunas de Opinión' ; var num_posts=settings.opinion_posts_per_page || 6; #>
            <div class="elpl-opinion-module elpl-editor-preview">
                <div class="elpl-opinion-grid">
                    <# for ( var i=0; i < num_posts; i++ ) { #>
                        <div class="elpl-opinion-card">
                            <div class="elpl-opinion-card-top">
                                <div class="elpl-opinion-persona-img"
                                    style="background: #eee; display: flex; align-items: center; justify-content: center; color: #ccc;">
                                    <i class="eicon-person" style="font-size: 40px;"></i>
                                </div>
                                <div class="elpl-opinion-content">
                                    <div class="elpl-opinion-date"
                                        style="color: #e21a22; font-size: 10px; font-weight: bold; margin-bottom: 5px;">JUL 10,
                                        2025</div>
                                    <div
                                        style="background: #333; height: 14px; width: 90%; margin-bottom: 6px; border-radius: 2px;">
                                    </div>
                                    <div style="background: #333; height: 14px; width: 70%; border-radius: 2px;"></div>
                                </div>
                            </div>
                            <div class="elpl-opinion-meta-box" style="background: #e21a22; color: #fff; padding: 10px;">
                                <div style="background: #fff; height: 12px; width: 60%; margin-bottom: 5px; opacity: 0.9;">
                                </div>
                                <div style="background: #fff; height: 8px; width: 80%; opacity: 0.7;"></div>
                            </div>
                        </div>
                        <# } #>
                </div>
            </div>
            <?php
    }
}
