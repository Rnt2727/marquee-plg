<?php
namespace Elementor;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Marquee_Advanced extends Widget_Base {
    
    public function __construct($data = [], $args = null) {
        parent::__construct($data, $args);
        add_action('elementor/frontend/after_enqueue_scripts', [$this, 'enqueue_scripts']);
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script('marquee-advanced-frontend');
    }

    public function get_name() {
        return 'marquee_advanced';
    }

    public function get_categories() {
        return [ 'basic' ]; // Cambiado de 'jws-elements' a 'basic' para mayor compatibilidad
    }

    public function get_title() {
        return esc_html__( 'Marquee Advanced', 'marquee-advanced' ); // Cambiado el dominio de texto
    }

    public function get_icon() {
        return 'eicon-bullet-list';
    }

    public function get_keywords() {
        return [ 'slider', 'carousel', 'marquee' ];
    }
    
    public function get_script_depends() {
        return [ 'imagesloaded', 'marquee-advanced-frontend' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'section_icon',
            [
                'label' => esc_html__( 'List', 'marquee-advanced' ),
            ]
        );
        
        $this->add_control(
            'skin',
            [
                'label'     => esc_html__( 'Skin', 'marquee-advanced' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'default',
                'options'   => [
                    'default'   => esc_html__( 'Default', 'marquee-advanced' ),
                    'icon_text_bg'   => esc_html__( 'Icon text background', 'marquee-advanced' ),
                ],
            ]
        );
    
        $this->add_control(
            'reversed',
            [
                'label' => esc_html__( 'Reversed', 'marquee-advanced' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'On', 'marquee-advanced' ),
                'label_off' => esc_html__( 'Off', 'marquee-advanced' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        
        $this->add_control(
            'scroll',
            [
                'label' => esc_html__( 'Scroll', 'marquee-advanced' ),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'On', 'marquee-advanced' ),
                'label_off' => esc_html__( 'Off', 'marquee-advanced' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );
        
        $repeater = new Repeater();
        
        $repeater->add_control(
            'item_content_type',
            [
                'label' => __( 'Content type', 'marquee-advanced' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'icon_text',
                'options' => [
                    'icon_text' => [
                        'title' => __( 'Icon Text', 'marquee-advanced' ),
                        'icon' => 'eicon-nerd'
                    ],
                    'tinymce' => [
                        'title' => __( 'TinyMCE', 'marquee-advanced' ),
                        'icon' => 'eicon-text-area'
                    ],
                    'image' => [
                        'title' => __( 'Image', 'marquee-advanced' ),
                        'icon' => 'eicon-image-bold'
                    ],
                ],
                'toggle' => false,
            ]
        );

        $repeater->add_control(
            'text',
            [
                'label' => esc_html__( 'Text', 'marquee-advanced' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => esc_html__( 'List Item', 'marquee-advanced' ),
                'default' => esc_html__( 'List Item', 'marquee-advanced' ),
                'dynamic' => [
                    'active' => true,
                ],
                'condition' => [
                    'item_content_type' => 'icon_text',
                ]
            ]
        );

        $repeater->add_control(
            'selected_icon',
            [
                'label' => esc_html__( 'Icon', 'marquee-advanced' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fas fa-check',
                    'library' => 'fa-solid',
                ],
                'fa4compatibility' => 'icon',
                'condition' => [
                    'item_content_type' => 'icon_text',
                ]
            ]
        );
        
        $repeater->add_control(
            'tinymce_content', [
                'label' => __( 'Tinymce Content', 'marquee-advanced' ),
                'type' => Controls_Manager::WYSIWYG,
                'default' => __( '<p>Item content. Click the edit button to change this text.</p>' , 'marquee-advanced' ),
                'show_label' => false,
                'condition'=> [
                    'item_content_type' => 'tinymce'
                ],
            ]
        );
        
        $repeater->add_control(
            'image',
            [
                'label' => esc_html__( 'Image', 'marquee-advanced' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'item_content_type' => 'image'
                ],
            ]
        );

        $repeater->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'image',
                'default' => 'full',
                'condition' => [
                    'item_content_type' => 'image'
                ],
            ]
        );
        
        $repeater->add_control(
            'link',
            [
                'label' => esc_html__( 'Link', 'marquee-advanced' ),
                'type' => Controls_Manager::URL,
                'dynamic' => [
                    'active' => true,
                ],
                'placeholder' => esc_html__( 'https://your-link.com', 'marquee-advanced' ),
            ]
        );
        
        $repeater->add_responsive_control(
            'item_margin',
            [
                'label' => esc_html__( 'Item Margin', 'marquee-advanced' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'icon_list',
            [
                'label' => '',
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'text' => esc_html__( 'Item #1', 'marquee-advanced' ),
                        'selected_icon' => [
                            'value' => 'fas fa-check',
                            'library' => 'fa-solid',
                        ],
                    ],
                    [
                        'text' => esc_html__( 'Item #2', 'marquee-advanced' ),
                        'selected_icon' => [
                            'value' => 'fas fa-times',
                            'library' => 'fa-solid',
                        ],
                    ],
                    [
                        'text' => esc_html__( 'Item #3', 'marquee-advanced' ),
                        'selected_icon' => [
                            'value' => 'fas fa-dot-circle',
                            'library' => 'fa-solid',
                        ],
                    ],
                ],
                'title_field' => '{{{ elementor.helpers.renderIcon( this, selected_icon, {}, "i", "panel" ) || \'<i class="{{ icon }}" aria-hidden="true"></i>\' }}} {{{ text }}}',
            ]
        );

        $this->end_controls_section();
   
        $this->start_controls_section(
            'section_icon_list',
            [
                'label' => esc_html__( 'List', 'marquee-advanced' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'space_between',
            [
                'label' => esc_html__( 'Space Between', 'marquee-advanced' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .item' => 'margin-right: calc({{SIZE}}{{UNIT}}/2); margin-left: calc({{SIZE}}{{UNIT}}/2)',
                    '{{WRAPPER}} .marquee-list-items' => 'margin-right: calc(-{{SIZE}}{{UNIT}}/2); margin-left: calc(-{{SIZE}}{{UNIT}}/2)',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_icon_style',
            [
                'label' => esc_html__( 'Icon', 'marquee-advanced' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => esc_html__( 'Color', 'marquee-advanced' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .mar-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .mar-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'marquee-advanced' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .mar-icon' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_color_hover',
            [
                'label' => esc_html__( 'Hover', 'marquee-advanced' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-item:hover .mar-icon i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .elementor-icon-list-item:hover .mar-icon svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_bgcolor_hover',
            [
                'label' => esc_html__( 'Background Hover Color', 'marquee-advanced' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-item:hover .mar-icon' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => esc_html__( 'Size', 'marquee-advanced' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 14,
                ],
                'range' => [
                    'px' => [
                        'min' => 6,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mar-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_self_align',
            [
                'label' => esc_html__( 'Alignment', 'marquee-advanced' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'marquee-advanced' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'marquee-advanced' ),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'marquee-advanced' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .mar-icon' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => esc_html__( 'Padding', 'marquee-advanced' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .mar-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_self_margin',
            [
                'label' => esc_html__( 'Margin', 'marquee-advanced' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .jws-menu-list .mar-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_text_style',
            [
                'label' => esc_html__( 'Text', 'marquee-advanced' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => esc_html__( 'Text Color', 'marquee-advanced' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'text_color_hover',
            [
                'label' => esc_html__( 'Hover Color', 'marquee-advanced' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .elementor-icon-list-item:hover a , {{WRAPPER}} .elementor-icon-list-item.active a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'list-icon_border',
                'label' => esc_html__( 'Border', 'marquee-advanced' ),
                'selector' => '{{WRAPPER}} .jws-menu-list li a',
            ]
        );

        $this->add_control(
            'list-icon_border_hover',
            [
                'label' => esc_html__( 'Border Hover Color', 'marquee-advanced' ),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .jws-menu-list li:hover a' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'text_indent',
            [
                'label' => esc_html__( 'Text Indent', 'marquee-advanced' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} a' => is_rtl() ? 'padding-right: {{SIZE}}{{UNIT}};' : 'padding-left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'icon_typography',
                'selector' => '{{WRAPPER}} .item',
            ]
        );

        $this->end_controls_section();


       

    }
    
    protected function get_item_content_image($item,$repeater_setting_key) { 
        ?>
        <figure><?php
            Group_Control_Image_Size::print_attachment_image_html( $item );
        ?></figure>
        <?php
    }
    
    protected function get_item_content_tinymce($item,$repeater_setting_key) { 
        echo ''.$item['tinymce_content'];
    }
    
    protected function get_item_content_icon_text($item,$repeater_setting_key) { 
        if ( !empty( $item['selected_icon']['value'] )) :
            ?>
            <span class="mar-icon">
                <?php
                    Icons_Manager::render_icon( $item['selected_icon'], [ 'aria-hidden' => 'true' ] );
                ?>
            </span>
        <?php endif; ?>
        <span class="mar-text">
        <?php 
           echo ''.$item['text'];
        ?>
        </span>
        <?php  
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $this->add_render_attribute( 'main_list', 'class', 'jws-marquee' );
        
        $option = array(
            'reversed' => $settings['reversed'] == 'yes' ? 1 : 0,
            'scroll'   => $settings['scroll'] == 'yes' ? 1 : 0,
        );
        
        $this->add_render_attribute( 'main_list', 'data-marquee-options', wp_json_encode($option) );
        
        $this->add_render_attribute( 'icon_list', 'class', 'marquee-list-items d-flex' );
        $this->add_render_attribute( 'icon_list', 'class', $settings['skin'] );
        ?>
        <div <?php echo ''.$this->get_render_attribute_string( 'main_list' ); ?>>
            <?php if(!empty($settings['title_menu'])) : ?>
                <div class="menu-list-title"><?php echo esc_html($settings['title_menu']); ?></div>
            <?php endif; ?>
            <div <?php echo ''.$this->get_render_attribute_string( 'icon_list' ); ?>>
                <?php
                $actual_link = (function_exists('check_url')) ? check_url() : '';
                foreach ( $settings['icon_list'] as $index => $item ) :
                    $repeater_setting_key = $this->get_repeater_setting_key( 'text', 'icon_list', $index );
    
                    $item_key = 'item_' . $index;
                    if ( ! empty( $item['link']['url'] ) ) {
                        if ( $actual_link == $item['link']['url'] ) {
                          $this->add_render_attribute( $item_key, 'class', 'active' );          
                        }
                    }
                    $this->add_render_attribute($item_key, 'class', 'item '.$item['item_content_type'] );
                    $this->add_render_attribute($item_key, 'class', 'elementor-repeater-item-'.$item['_id'] );
                    ?>
                    <div <?php echo ''.$this->get_render_attribute_string( $item_key ); ?>>
                        <?php
                        $link_key = 'link_' . $index;
                        if ( ! empty( $item['link']['url'] ) ) {
                            $this->add_link_attributes( $link_key, $item['link'] );
                        }
                        echo '<a ' . $this->get_render_attribute_string( $link_key ) . '>';
                        
                        $content_type = $item['item_content_type'];
                        $this->{'get_item_content_' . $content_type}( $item,$repeater_setting_key );
                        ?>
                        </a>
                    </div>
                    <?php
                endforeach;
                ?>
            </div>
        </div>
        <?php
    }

    protected function content_template() {
        // Puedes dejar esto vacío o implementar una vista de backbone si es necesario
    }

    public function on_import( $element ) {
        return Icons_Manager::on_import_migration( $element, 'icon', 'selected_icon', true );
    }
}