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
            'title_color',
            array(
                'label' => esc_html__('Color de Títulos', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .elpl-ng-title a' => 'color: {{VALUE}};',
                ),
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
     * Render widget output on the frontend.
     *
     * @since 1.5.7
     * @access protected
     */
    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $args = array(
            'post_type' => 'post',
            'posts_per_page' => 6,
        );

        if (!empty($settings['posts_category'])) {
            $args['category_name'] = $settings['posts_category'];
        }

        $query = new \WP_Query($args);

        if ($query->have_posts()) {
            $posts = $query->posts;
            $featured_posts = array_slice($posts, 0, 2);
            $list_posts = array_slice($posts, 2, 4);
            $is_reversed = 'yes' === $settings['reverse_columns'] ? ' elpl-ng-reversed' : '';
            ?>
            <div class="elpl-noticias-generales-module<?php echo esc_attr($is_reversed); ?>">

                <!-- Column 1: Featured (2 posts) -->
                <div class="elpl-ng-column elpl-ng-featured-col">
                    <div class="elpl-ng-featured-grid">
                        <?php
                        foreach ($featured_posts as $post) {
                            setup_postdata($post);
                            $thumbnail_url = get_the_post_thumbnail_url($post->ID, 'medium_large');
                            ?>
                            <div class="elpl-ng-card elpl-ng-featured-card">
                                <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="elpl-ng-thumb-link">
                                    <div class="elpl-ng-thumbnail"
                                        style="background-image: url('<?php echo esc_url($thumbnail_url); ?>');"></div>
                                </a>
                                <div class="elpl-ng-content">
                                    <h3 class="elpl-ng-title"><a href="<?php echo esc_url(get_permalink($post->ID)); ?>">
                                            <?php echo esc_html($post->post_title); ?>
                                        </a></h3>
                                    <?php if ('yes' === $settings['show_excerpt']): ?>
                                        <div class="elpl-ng-excerpt">
                                            <?php echo esc_html(wp_trim_words(get_the_excerpt($post->ID), 25)); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php
                        }
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>

                <!-- Column 2: List (4 posts) -->
                <div class="elpl-ng-column elpl-ng-list-col">
                    <div class="elpl-ng-list">
                        <?php
                        foreach ($list_posts as $post) {
                            setup_postdata($post);
                            $thumbnail_url = get_the_post_thumbnail_url($post->ID, 'thumbnail');
                            ?>
                            <div class="elpl-ng-card elpl-ng-list-card">
                                <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="elpl-ng-list-thumb">
                                    <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($post->post_title); ?>">
                                </a>
                                <div class="elpl-ng-list-content">
                                    <h4 class="elpl-ng-title"><a href="<?php echo esc_url(get_permalink($post->ID)); ?>">
                                            <?php echo esc_html($post->post_title); ?>
                                        </a></h4>
                                </div>
                            </div>
                            <?php
                        }
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>

            </div>
            <?php
        } else {
            echo esc_html__('No se encontraron noticias.', 'elementor-post-layout');
        }
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
