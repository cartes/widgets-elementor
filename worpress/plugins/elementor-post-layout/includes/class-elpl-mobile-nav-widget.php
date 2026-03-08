<?php
namespace ELPL\Widgets;

/**
 * Elementor Mobile Nav Widget
 *
 * Renders a WordPress menu as a styled mobile navigation list.
 * Items with children get an accordion toggle (⊕/⊖).
 * Menu items with the CSS class "highlight" are rendered in the accent color.
 */

if (!defined('ABSPATH')) {
    exit;
}

class ELPL_Mobile_Nav_Widget extends \Elementor\Widget_Base
{

    public function get_name()
    {
        return 'elpl_mobile_nav';
    }

    public function get_title()
    {
        return esc_html__('Menú Móvil', 'elementor-post-layout');
    }

    public function get_icon()
    {
        return 'eicon-nav-menu';
    }

    public function get_categories()
    {
        return array('cartes-widgets');
    }

    public function get_script_depends()
    {
        return array('elpl-mobile-nav');
    }

    public function get_style_depends()
    {
        return array('elpl-widget-styles');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function get_registered_menus()
    {
        $menus = wp_get_nav_menus();
        $options = array('' => esc_html__('— Seleccionar menú —', 'elementor-post-layout'));
        foreach ($menus as $menu) {
            $options[$menu->term_id] = esc_html($menu->name);
        }
        return $options;
    }

    // ── Controls ─────────────────────────────────────────────────────────────

    protected function register_controls()
    {
        /* Content */
        $this->start_controls_section(
            'section_content',
            array(
                'label' => esc_html__('Menú', 'elementor-post-layout'),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'menu_id',
            array(
                'label'       => esc_html__('Menú de WordPress', 'elementor-post-layout'),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'options'     => $this->get_registered_menus(),
                'default'     => '',
                'label_block' => true,
            )
        );

        $this->end_controls_section();

        /* Style – General */
        $this->start_controls_section(
            'section_style_general',
            array(
                'label' => esc_html__('Estilo general', 'elementor-post-layout'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'text_color',
            array(
                'label'     => esc_html__('Color de texto', 'elementor-post-layout'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#1a1a1a',
                'selectors' => array(
                    '{{WRAPPER}} .elpl-mnav-item > a,
                     {{WRAPPER}} .elpl-mnav-item > .elpl-mnav-toggle' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'accent_color',
            array(
                'label'     => esc_html__('Color acento (highlight)', 'elementor-post-layout'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#e63323',
                'selectors' => array(
                    '{{WRAPPER}} .elpl-mnav-item.highlight > a,
                     {{WRAPPER}} .elpl-mnav-item.highlight > .elpl-mnav-toggle .elpl-mnav-label' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'menu_typography',
                'label'    => esc_html__('Tipografía', 'elementor-post-layout'),
                'selector' => '{{WRAPPER}} .elpl-mnav-item > a,
                               {{WRAPPER}} .elpl-mnav-item > .elpl-mnav-toggle .elpl-mnav-label',
            )
        );

        $this->add_control(
            'item_spacing',
            array(
                'label'      => esc_html__('Separación entre ítems (px)', 'elementor-post-layout'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range'      => array('px' => array('min' => 0, 'max' => 40)),
                'default'    => array('unit' => 'px', 'size' => 16),
                'selectors'  => array(
                    '{{WRAPPER}} .elpl-mnav-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        $this->end_controls_section();

        /* Style – Accordion items */
        $this->start_controls_section(
            'section_style_accordion',
            array(
                'label' => esc_html__('Ítems con submenú', 'elementor-post-layout'),
                'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'border_color',
            array(
                'label'     => esc_html__('Color del borde', 'elementor-post-layout'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#1a1a1a',
                'selectors' => array(
                    '{{WRAPPER}} .elpl-mnav-toggle' => 'border-color: {{VALUE}};',
                    '{{WRAPPER}} .elpl-mnav-toggle .elpl-mnav-icon' => 'border-color: {{VALUE}}; color: {{VALUE}};',
                ),
            )
        );

        $this->add_control(
            'border_radius',
            array(
                'label'      => esc_html__('Radio de borde (px)', 'elementor-post-layout'),
                'type'       => \Elementor\Controls_Manager::SLIDER,
                'size_units' => array('px'),
                'range'      => array('px' => array('min' => 0, 'max' => 40)),
                'default'    => array('unit' => 'px', 'size' => 8),
                'selectors'  => array(
                    '{{WRAPPER}} .elpl-mnav-toggle' => 'border-radius: {{SIZE}}{{UNIT}};',
                ),
            )
        );

        /* Style – Submenu items */
        $this->add_control(
            'submenu_color',
            array(
                'label'     => esc_html__('Color texto submenú', 'elementor-post-layout'),
                'type'      => \Elementor\Controls_Manager::COLOR,
                'default'   => '#444444',
                'selectors' => array(
                    '{{WRAPPER}} .elpl-mnav-submenu a' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'     => 'submenu_typography',
                'label'    => esc_html__('Tipografía submenú', 'elementor-post-layout'),
                'selector' => '{{WRAPPER}} .elpl-mnav-submenu a',
            )
        );

        $this->end_controls_section();
    }

    // ── Render ───────────────────────────────────────────────────────────────

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $menu_id  = absint($settings['menu_id'] ?? 0);

        if (!$menu_id) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
                echo '<p style="color:#999;padding:10px;">' . esc_html__('Selecciona un menú en los ajustes del widget.', 'elementor-post-layout') . '</p>';
            }
            return;
        }

        $items = wp_get_nav_menu_items($menu_id);
        if (!$items) {
            return;
        }

        // Build a simple nested structure (max 2 levels)
        $top_level  = array();
        $children   = array();

        foreach ($items as $item) {
            if ((int) $item->menu_item_parent === 0) {
                $top_level[] = $item;
            } else {
                $children[(int) $item->menu_item_parent][] = $item;
            }
        }

        echo '<nav class="elpl-mnav" aria-label="' . esc_attr__('Menú principal', 'elementor-post-layout') . '">';
        echo '<ul class="elpl-mnav-list">';

        foreach ($top_level as $item) {
            $item_id   = (int) $item->ID;
            $has_sub   = !empty($children[$item_id]);
            $css_class = trim($item->classes ? implode(' ', (array) $item->classes) : '');
            $url       = esc_url($item->url);
            $label     = esc_html($item->title);

            echo '<li class="elpl-mnav-item ' . esc_attr($css_class) . ($has_sub ? ' elpl-mnav-has-sub' : '') . '">';

            if ($has_sub) {
                // Accordion toggle — entire row is a button; label is a link
                echo '<div class="elpl-mnav-toggle" role="button" tabindex="0" aria-expanded="false">';
                echo '<a href="' . $url . '" class="elpl-mnav-label" tabindex="-1">' . $label . '</a>';
                echo '<span class="elpl-mnav-icon" aria-hidden="true"></span>';
                echo '</div>';

                echo '<ul class="elpl-mnav-submenu" hidden>';
                foreach ($children[$item_id] as $child) {
                    echo '<li class="elpl-mnav-sub-item">';
                    echo '<a href="' . esc_url($child->url) . '">' . esc_html($child->title) . '</a>';
                    echo '</li>';
                }
                echo '</ul>';
            } else {
                echo '<a href="' . $url . '">' . $label . '</a>';
            }

            echo '</li>';
        }

        echo '</ul>';
        echo '</nav>';
    }
}
