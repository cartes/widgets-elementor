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
                'default' => 'opinioncolumn_opinionAuthor',
                'description' => esc_html__('Campo en el post de Opinion que contiene el ID de la Persona (Ej: opinioncolumn_opinionAuthor).', 'elementor-post-layout'),
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
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $num_posts = !empty($settings['opinion_posts_per_page']) ? absint($settings['opinion_posts_per_page']) : 6;
        $meta_persona = !empty($settings['meta_persona_key']) ? sanitize_text_field($settings['meta_persona_key']) : 'opinioncolumn_opinionAuthor';
        $meta_cargo = !empty($settings['meta_cargo_key']) ? sanitize_text_field($settings['meta_cargo_key']) : 'person_cargo';

        $query_args = [
            'post_type' => 'opinion',
            'posts_per_page' => $num_posts,
            'post_status' => 'publish',
        ];

        $query = new \WP_Query($query_args);

        if (!$query->have_posts()) {
            echo '<p>' . esc_html__('No se encontraron columnas de opinión.', 'elementor-post-layout') . '</p>';
            return;
        }
        ?>

        <div class="elpl-opinion-module">
            <div class="elpl-opinion-grid">
                <?php
                while ($query->have_posts()):
                    $query->the_post();
                    $opinion_id = get_the_ID();
                    $persona_id = get_post_meta($opinion_id, $meta_persona, true);

                    $persona_name = '';
                    $persona_cargo = '';
                    $persona_img = '';

                    if ($persona_id) {
                        $persona_post = get_post($persona_id);
                        if ($persona_post && $persona_post->post_type === 'person') {
                            $persona_name = $persona_post->post_title;
                            $persona_cargo = get_post_meta($persona_id, $meta_cargo, true);
                            $persona_img = get_the_post_thumbnail_url($persona_id, 'medium');
                        }
                    }
                    ?>
                    <div class="elpl-opinion-card">
                        <div class="elpl-opinion-card-top">
                            <div class="elpl-opinion-persona-img"
                                style="background-image: url('<?php echo esc_url($persona_img); ?>'); background-position: 10% 10%;">
                                <?php if (!$persona_img): ?>
                                    <i class="eicon-person"></i>
                                <?php endif; ?>
                            </div>
                            <div class="elpl-opinion-content">
                                <div class="elpl-opinion-date">
                                    <?php echo get_the_date('M j, Y'); ?>
                                </div>
                                <h3 class="elpl-opinion-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>
                            </div>
                        </div>
                        <div class="elpl-opinion-meta-box">
                            <div class="elpl-persona-name">
                                <?php echo esc_html($persona_name); ?>
                            </div>
                            <div class="elpl-persona-title">
                                <?php echo esc_html($persona_cargo); ?>
                            </div>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        </div>

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
