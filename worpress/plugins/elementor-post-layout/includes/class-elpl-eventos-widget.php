<?php
namespace ELPL\Widgets;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!defined('ELPL_VERSION')) {
    define('ELPL_VERSION', '1.5.3');
}

/**
 * Elementor Eventos Widget.
 */
class ELPL_Eventos_Widget extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'elpl_eventos';
    }

    public function get_title()
    {
        return esc_html__('Eventos (Tribe Events)', 'elementor-post-layout');
    }

    public function get_icon()
    {
        return 'eicon-calendar';
    }

    public function get_categories()
    {
        return array('cartes-widgets');
    }

    protected function register_controls()
    {
        // Content Section
        $this->start_controls_section(
            'content_section',
            array(
                'label' => esc_html__('Configuración', 'elementor-post-layout'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'num_posts',
            array(
                'label' => esc_html__('Número de Eventos', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'default' => 6,
            )
        );

        $this->add_control(
            'columnas',
            array(
                'label' => esc_html__('Columnas', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ),
                'default' => '3',
            )
        );

        $this->add_control(
            'show_info_link',
            array(
                'label' => esc_html__('Mostrar enlace "+ INFO"', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Sí', 'elementor-post-layout'),
                'label_off' => esc_html__('No', 'elementor-post-layout'),
                'return_value' => 'yes',
                'default' => 'yes',
            )
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section_eventos',
            array(
                'label' => esc_html__('Estilo de Eventos', 'elementor-post-layout'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'date_bg_color',
            array(
                'label' => esc_html__('Color de Fondo de Fecha', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#E30613',
                'selectors' => array(
                    '{{WRAPPER}} .elpl-evento-date-box' => 'background-color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'date_text_color',
            array(
                'label' => esc_html__('Color de Texto de Fecha', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'selectors' => array(
                    '{{WRAPPER}} .elpl-evento-date-box' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'title_color',
            array(
                'label' => esc_html__('Color de Título', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
                'selectors' => array(
                    '{{WRAPPER}} .elpl-evento-title' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name' => 'title_typography',
                'selector' => '{{WRAPPER}} .elpl-evento-title',
            )
        );

        $this->add_control(
            'info_link_color',
            array(
                'label' => esc_html__('Color de Enlace Info', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#E30613',
                'selectors' => array(
                    '{{WRAPPER}} .elpl-evento-info-link' => 'color: {{VALUE}};',
                ),
                'condition' => array(
                    'show_info_link' => 'yes',
                ),
            )
        );

        $this->add_control(
            'grid_gap',
            array(
                'label' => esc_html__('Espacio entre eventos', 'elementor-post-layout'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range' => array(
                    'px' => array(
                        'min' => 0,
                        'max' => 50,
                    ),
                ),
                'default' => array(
                    'unit' => 'px',
                    'size' => 20,
                ),
                'selectors' => array(
                    '{{WRAPPER}} .elpl-eventos-grid' => 'gap: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        // Check if The Events Calendar plugin is active
        if (!class_exists('Tribe__Events__Main')) {
            echo '<p>' . esc_html__('El plugin "The Events Calendar" no está instalado o activado.', 'elementor-post-layout') . '</p>';
            return;
        }

        $args = array(
            'post_type' => 'tribe_events',
            'posts_per_page' => absint($settings['num_posts']),
            'post_status' => 'publish',
            'meta_key' => '_EventStartDate',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => '_EventStartDate',
                    'value' => date('Y-m-d H:i:s'),
                    'compare' => '>=',
                    'type' => 'DATETIME',
                ),
            ),
        );

        $query = new \WP_Query($args);

        if (!$query->have_posts()) {
            echo '<p>' . esc_html__('No hay eventos futuros disponibles.', 'elementor-post-layout') . '</p>';
            return;
        }

        $total_events = $query->post_count;
        $configured_columns = absint($settings['columnas']);

        // Mostrar aviso si hay menos eventos que columnas (solo visible para editores/admin)
        if ($total_events < $configured_columns && \Elementor\Plugin::$instance->editor->is_edit_mode()) {
            echo '<div style="background: #fff3cd; color: #856404; padding: 10px; border: 1px solid #ffeeba; margin-bottom: 20px; border-radius: 4px; font-size: 13px;">';
            echo '<strong>' . esc_html__('Aviso:', 'elementor-post-layout') . '</strong> ' . sprintf(esc_html__('Solo se encontraron %d eventos próximos. El widget se ajustará automáticamente a %d columnas para evitar espacios vacíos.', 'elementor-post-layout'), absint($total_events), absint($total_events));
            echo '</div>';
        }

        $actual_columns = min($configured_columns, $total_events);

        echo '<div class="elpl-eventos-module">';
        echo '<div class="elpl-eventos-grid" style="--elpl-cols: ' . esc_attr($actual_columns) . ';">';

        while ($query->have_posts()) {
            $query->the_post();

            // Get event date
            $event_date = tribe_get_start_date(get_the_ID(), false, 'j');
            $event_month = tribe_get_start_date(get_the_ID(), false, 'M');
            $event_year = tribe_get_start_date(get_the_ID(), false, 'Y');
            ?>
            <div class="elpl-evento-card">
                <a href="<?php the_permalink(); ?>" class="elpl-evento-link">
                    <div class="elpl-evento-content">
                        <div class="elpl-evento-date-box">
                            <div class="elpl-evento-day"><?php echo esc_html($event_date); ?></div>
                            <div class="elpl-evento-month"><?php echo esc_html(strtoupper($event_month)); ?></div>
                        </div>
                        <div class="elpl-evento-info">
                            <h3 class="elpl-evento-title"><?php echo esc_html(get_the_title()); ?></h3>
                            <div class="elpl-evento-year"><?php echo esc_html($event_year); ?></div>
                            <?php if ('yes' === $settings['show_info_link']): ?>
                                <div class="elpl-evento-info-link">+ INFO</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            </div>
            <?php
        }

        echo '</div>'; // End Grid Container
        echo '</div>'; // End Module
    }

    /**
     * Render widget output in the editor (JS Template)
     */
    protected function _content_template()
    {
        ?>
        <# var columns=settings.columnas || 3; var num_posts=settings.num_posts || 3; #>
            <div class="elpl-eventos-module elpl-editor-preview">
                <div class="elpl-editor-placeholder"
                    style="padding: 20px; border: 2px dashed #ffeeba; border-radius: 8px; background: #fffcf5;">
                    <div
                        style="margin-bottom: 15px; font-weight: bold; color: #856404; display: flex; align-items: center; justify-content: center; gap: 10px;">
                        <i class="eicon-calendar" style="font-size: 24px; color: #e21a22;"></i>
                        <span><?php esc_html_e('Eventos Próximos (Editor)', 'elementor-post-layout'); ?></span>
                    </div>
                    <div class="elpl-eventos-grid-mock"
                        style="display: grid; grid-template-columns: repeat({{ columns }}, 1fr); gap: 15px;">
                        <# for ( var i=0; i < num_posts; i++ ) { #>
                            <div
                                style="background: #fff; border-radius: 4px; border: 1px solid #ffeeba; display: flex; padding: 10px; gap: 10px; align-items: center;">
                                <div
                                    style="background: #e21a22; width: 40px; height: 40px; border-radius: 4px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #fff; font-size: 10px;">
                                    <div style="font-weight: bold;">01</div>
                                    <div style="font-size: 8px; text-transform: uppercase;">ENE</div>
                                </div>
                                <div style="flex: 1;">
                                    <div
                                        style="background: #eee; height: 10px; width: 90%; margin-bottom: 5px; border-radius: 2px;">
                                    </div>
                                    <div style="background: #f9f9f9; height: 6px; width: 40%; border-radius: 1px;"></div>
                                </div>
                            </div>
                            <# } #>
                    </div>
                </div>
            </div>
            <?php
    }
}
