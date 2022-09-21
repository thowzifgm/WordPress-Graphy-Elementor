<?php /** @noinspection PhpUndefinedClassInspection */
/**
 * Graphy for Elementor
 * Creates modern and stylish Elementor widgets to display awesome charts and graphs.
 *
 * @encoding        UTF-8
 * @version         1.2.6
 * @contributors    Abdullah Thowzif Hameed (thowzif@live.com)
 **/

namespace Merkulove\GraphyElementor;

/** Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit;
}

use Exception;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Merkulove\GraphyElementor\Unity\Plugin as UnityPlugin;

/** @noinspection PhpUnused */
/**
 * Line Chart - Custom Elementor Widget
 * @method start_controls_section( string $string, array $array )
 **/
class bipolar_bar_elementor extends Widget_Base {

    /**
     * Use this to sort widgets.
     * A smaller value means earlier initialization of the widget.
     * Can take negative values.
     * Default widgets and widgets from 3rd party developers have 0 $mdp_order
     **/
    public $mdp_order = 1;

    /**
     * Widget base constructor.
     * Initializing the widget base class.
     *
     * @access public
     * @throws Exception If arguments are missing when initializing a full widget instance.
     * @param array      $data Widget data. Default is an empty array.
     * @param array|null $args Optional. Widget default arguments. Default is null.
     *
     * @return void
     **/
    public function __construct( $data = [], $args = null ) {

        parent::__construct( $data, $args );

        /** Register styles */
        wp_register_style(
        'mdp-graphy-elementor-admin',
            UnityPlugin::get_url() . 'css/elementor-admin' . UnityPlugin::get_suffix() . '.css',
                [],
                UnityPlugin::get_version()
        );
        wp_register_style(
        'mdp-graphy-chartist',
            UnityPlugin::get_url() . 'css/chartist' . UnityPlugin::get_suffix() . '.css',
                [],
                UnityPlugin::get_version()
        );
        wp_register_style(
        'mdp-graphy',
            UnityPlugin::get_url() . 'css/graphy' . UnityPlugin::get_suffix() . '.css',
                [],
                UnityPlugin::get_version()
        );

        /** Register widget scripts */
        wp_register_script(
        'mdp-graphy-chartist',
            UnityPlugin::get_url() . 'js/chartist' . UnityPlugin::get_suffix() . '.js',
                [],
                UnityPlugin::get_version(),
       false
        );

        wp_register_script(
            'mdp-graphy-bipolar-bar',
            UnityPlugin::get_url() . 'js/bipolar.bar' . UnityPlugin::get_suffix() . '.js',
            [],
            UnityPlugin::get_version(),
            false
        );

        wp_register_script(
            'mdp-graphy-bipolar-bar-popup',
            UnityPlugin::get_url() . 'js/bipolar.bar.popup' . UnityPlugin::get_suffix() . '.js',
            [],
            UnityPlugin::get_version(),
            false
        );

        wp_register_script(
        'mdp-graphy',
            UnityPlugin::get_url() . 'js/graphy' . UnityPlugin::get_suffix() . '.js',
                [],
                UnityPlugin::get_version(),
       false
        );

    }

    /**
     * Return a widget name.
     *
     * @return string
     **/
    public function get_name() {
        return 'mdp-graphy-bipolar-bar';
    }

    /**
     * Return the widget title that will be displayed as the widget label.
     *
     * @return string
     **/
    public function get_title() {
        return esc_html__( 'Bipolar Bar Chart', 'graphy-elementor' );
    }

    /**
     * Set the widget icon.
     *
     * @return string
     */
    public function get_icon() {
        return 'mdp-bipolar-bar-elementor-widget-icon';
    }

    /**
     * Set the category of the widget.
     *
     * @return array with category names
     **/
    public function get_categories() {
        return [ 'graphy-category' ];
    }

    /**
     * Get widget keywords. Retrieve the list of keywords the widget belongs to.
     *
     * @access public
     *
     * @return array Widget keywords.
     **/
    public function get_keywords() {
        return [ 'Merkulove', 'chart', 'bipolar', 'bar', 'graphy', 'chartist' ];
    }

    /**
     * Get style dependencies.
     * Retrieve the list of style dependencies the widget requires.
     *
     * @access public
     *
     * @return array Widget styles dependencies.
     **/
    public function get_style_depends() {
        return [ 'mdp-graphy', 'mdp-graphy-chartist', 'mdp-graphy-elementor-admin' ];
    }

	/**
	 * Get script dependencies.
	 * Retrieve the list of script dependencies the element requires.
	 *
	 * @access public
     *
	 * @return array Element scripts dependencies.
	 **/
	public function get_script_depends() {
        return [
                'mdp-graphy',
                'mdp-graphy-bipolar-bar',
                'mdp-graphy-bipolar-bar-popup',
                'mdp-graphy-chartist'
        ];
    }

    /**
     * Add the widget controls.
     *
     * @since 1.0.0
     * @access protected
     * @return void with category names
     **/
    protected function register_controls() {

        /** Content tab. */
        $this->start_controls_section(
                'section_content',
                       [ 'label' => esc_html__( 'Content', 'graphy-elementor' ) ]
        );

        /** Displayed in a pop-up window */
        $this->add_control(
            'show_popup',
            [
                'label' => esc_html__( 'Displayed in a pop-up window', 'graphy-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphy-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphy-elementor' ),
                'return_value' => 'yes',
            ]
        );

        /** Title display condition. */
        $this->add_control(
            'show_title',
            [
                'label' => esc_html__( 'Show title', 'graphy-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphy-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphy-elementor' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        /** Title text. */
        $this->add_control(
            'chart_title',
            [
                'label'                 => esc_html__( 'Title', 'graphy-elementor' ),
                'label_block'           => true,
                'type'                  => Controls_Manager::TEXT,
                'dynamic'               => ['active' => true],
                'placeholder'           => esc_html__( 'Bi-polar bar char', 'graphy-elementor' ),
                'default'               => esc_html__( 'Bi-polar bar char', 'graphy-elementor' ),
                'condition'             => ['show_title' => 'yes']
            ]
        );

        /** Description display condition. */
        $this->add_control(
            'show_description',
            [
                'label' => esc_html__( 'Show description', 'graphy-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphy-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphy-elementor' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        /** Description text. */
        $this->add_control(
            'chart_description',
            [
                'label'                 => esc_html__( 'Description', 'graphy-elementor' ),
                'label_block'           => true,
                'type'                  => Controls_Manager::TEXTAREA,
                'dynamic'               => ['active' => true],
                'placeholder'           => esc_html__( 'A bi-polar bar chart with a range limit set with low and high. There is also an interpolation function used to skip every odd grid line / label.', 'graphy-elementor' ),
                'default'               => esc_html__( 'A bi-polar bar chart with a range limit set with low and high. There is also an interpolation function used to skip every odd grid line / label.', 'graphy-elementor' ),
                'condition'             => ['show_description' => 'yes']
            ]
        );

        /** Show description after title. */
        $this->add_control(
            'after_title',
            [
                'label' => esc_html__( 'Show description after title', 'graphy-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'graphy-elementor' ),
                'label_off' => esc_html__( 'No', 'graphy-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => ['show_description' => 'yes']
            ]
        );

        /** Divider. */
        $this->add_control(
            'value_hr',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        /** Dynamically add fields. */
        $repeater = new Repeater();

        /** Value field. */
        $repeater->add_control(
            'value_chart',
            [
                'label' => esc_html__( 'Value', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
            ]
        );

        /** Chart background color. */
        $repeater->add_control(
            'background_color',
            [
                'label' => esc_html__( 'Background', 'graphy-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#9E9E9E'
            ]
        );

        /** Item name. */
        $repeater->add_control(
            'item_name',
            [
                'label' => esc_html__( 'Item name', 'graphy-elementor' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Item name', 'graphy-elementor' ),
            ]
        );

        /** Chart value list. */
        $this->add_control(
            'value_list',
            [
                'label' => esc_html__( 'Chart value list', 'graphy-elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [ 'item_name' => 'Item name 1', 'value_chart' => 11, 'background_color' => '#4CAF50' ],
                    [ 'item_name' => 'Item name 2', 'value_chart' => -3, 'background_color' => '#03A9F4' ],
                    [ 'item_name' => 'Item name 3', 'value_chart' => -5, 'background_color' => '#795548' ],
                    [ 'item_name' => 'Item name 4', 'value_chart' => -3, 'background_color' => '#FF9800' ],
                ],
                'title_field' => '{{{item_name}}} : {{{value_chart}}}',
            ]
        );

        /** Chart settings heading. */
        $this->add_control(
            'chart_options',
            [
                'label' => esc_html__( 'Chart settings', 'graphy-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        /** Show grid X. */
        $this->add_control(
            'show_grid_x',
            [
                'label' => esc_html__( 'Show grid X', 'graphy-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphy-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphy-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        /** Show label X. */
        $this->add_control(
            'show_label_x',
            [
                'label' => esc_html__( 'Show label X', 'graphy-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphy-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphy-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        /** Show grid Y. */
        $this->add_control(
            'show_grid_y',
            [
                'label' => esc_html__( 'Show grid Y', 'graphy-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphy-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphy-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        /** Show label Y. */
        $this->add_control(
            'show_label_y',
            [
                'label' => esc_html__( 'Show label Y', 'graphy-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphy-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphy-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        /** Bar width. */
        $this->add_control(
            'bar_width',
            [
                'label' => esc_html__( 'Bar width', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 5,
                'default' => 10,
            ]
        );

        /** Chart conventions. */
        $this->add_control(
            'dots_graph_legend',
            [
                'label' => esc_html__( 'Chart conventions', 'graphy-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphy-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphy-elementor' ),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        /** Text graph conventions. */
        $this->add_control(
            'legend_title',
            [
                'label' => esc_html__( 'Title graph conventions', 'graphy-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Chart conventions', 'graphy-elementor' ),
                'placeholder' => esc_html__( 'Title graph conventions', 'graphy-elementor' ),
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        /** Show animation. */
        $this->add_control(
            'show_animation',
            [
                'label' => esc_html__( 'Show animation', 'graphy-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphy-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphy-elementor' ),
                'return_value' => 'yes',
            ]
        );

        /** Speed animation. */
        $this->add_control(
            'speed_animation',
            [
                'label' => esc_html__( 'Speed animation', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'default' => 500,
                'condition' => ['show_animation' => 'yes']
            ]
        );

        $this->end_controls_section();

        /** Style tab. */
        $this->start_controls_section(
                'style_section',
                       [
                           'label' => esc_html__( 'Style Section', 'plugin-name' ),
                           'tab' => Controls_Manager::TAB_STYLE,
                       ]
        );

        /** Header settings. */
        $this->add_control(
            'title_options',
            [
                'label' => esc_html__( 'Header', 'graphy-elementor' ),
                'type' => Controls_Manager::HEADING,
                'condition' => ['show_title' => 'yes']
            ]
        );

        /** Header typography. */
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => esc_html__( 'Typography', 'graphy-elementor' ),
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .mdp-typography-title',
                'condition' => ['show_title' => 'yes']
            ]
        );

        /** Header shadow. */
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'title_shadow',
                'label' => esc_html__( 'Shadow', 'graphy-elementor' ),
                'selector' => '{{WRAPPER}} .mdp-shadow-title',
                'condition' => ['show_title' => 'yes']
            ]
        );

        /** Header color. */
        $this->add_control(
            'title_color',
            [
                'label' => esc_html__( 'Color', 'graphy-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#3d3d3d',
                'condition' => ['show_title' => 'yes']
            ]
        );

        /** Header alignment. */
        $this->add_responsive_control(
            'title_text_align',
            [
                'label' => esc_html__( 'Alignment', 'graphy-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'graphy-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'graphy-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'graphy-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .mdp-bipobar-header-align' => 'text-align: {{title_text_align}};',
                ],
                'toggle' => true,
                'label_block' => true,
                'condition' => ['show_title' => 'yes']
            ]
        );

        /** Indentation for the header. */
        $this->add_responsive_control(
            'title_margin',
            [
                'label' => esc_html__( 'Margin', 'graphy-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'desktop_default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-bipobar-header-margin' => 'margin: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
                ],
                'condition' => ['show_title' => 'yes']
            ]
        );

        $this->add_control(
            'divider_title',
            [
                'type' => Controls_Manager::DIVIDER,
                'condition' => ['show_title' => 'yes']
            ]
        );

        /** Description settings. */
        $this->add_control(
            'description_options',
            [
                'label' => esc_html__( 'Description', 'graphy-elementor' ),
                'type' => Controls_Manager::HEADING,
                'condition'  => ['show_description' => 'yes']
            ]
        );

        /** Description typography. */
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'description_typography',
                'label' => esc_html__( 'Typography', 'graphy-elementor' ),
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .mdp-typography-description',
                'condition'  => ['show_description' => 'yes']
            ]
        );

        /** Description text color. */
        $this->add_control(
            'description_color',
            [
                'label' => esc_html__( 'Color', 'graphy-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#595959',
                'condition'  => ['show_description' => 'yes']
            ]
        );

        /** Description text alignment. */
        $this->add_responsive_control(
            'description_text_align',
            [
                'label' => esc_html__( 'Alignment', 'graphy-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'graphy-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'graphy-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'graphy-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__( 'Justify', 'graphy-elementor' ),
                        'icon' => 'fa fa-align-justify',
                    ],
                ],
                'default' => 'Justify',
                'selectors' => [
                    '{{WRAPPER}} .mdp-bipobar-description-align' => 'text-align: {{description_text_align}};',
                ],
                'toggle' => true,
                'label_block' => true,
                'condition'  => ['show_description' => 'yes']
            ]
        );

        /** Indentation for the description. */
        $this->add_responsive_control(
            'description_margin',
            [
                'label' => esc_html__( 'Margin', 'graphy-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'desktop_default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-bipobar-description-margin' => 'margin: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
                ],
                'condition'  => ['show_description' => 'yes']
            ]
        );

        $this->add_control(
            'divider_description',
            [
                'type' => Controls_Manager::DIVIDER,
                'condition'  => ['show_description' => 'yes']
            ]
        );

        /** Chart legend header settings. */
        $this->add_control(
            'graph_title_options',
            [
                'label' => esc_html__( 'Conventions', 'graphy-elementor' ),
                'type' => Controls_Manager::HEADING,
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        /** Chart legend typography. */
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'legend_typography',
                'label' => esc_html__( 'Typography', 'graphy-elementor' ),
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .mdp-typography-legend',
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        /** Chart legend shadow. */
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'legend_shadow',
                'label' => esc_html__( 'Shadow', 'graphy-elementor' ),
                'selector' => '{{WRAPPER}} .mdp-shadow-legend',
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        /** Chart conventions text color. */
        $this->add_control(
            'conventions_title_color',
            [
                'label' => esc_html__( 'Color', 'graphy-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#595959',
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        /** Description text alignment. */
        $this->add_responsive_control(
            'conventions_title_align',
            [
                'label' => esc_html__( 'Alignment', 'graphy-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'graphy-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'graphy-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'graphy-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .mdp-bipobar-conventions-align' => 'text-align: {{conventions_title_align}};',
                ],
                'toggle' => true,
                'label_block' => true,
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        /** Indentation for the graph conventions. */
        $this->add_responsive_control(
            'conventions_title_margin',
            [
                'label' => esc_html__( 'Margin', 'graphy-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'desktop_default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-bipobar-conventions-margin' => 'margin: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
                ],
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        $this->add_control(
            'divider_conventions',
            [
                'type' => Controls_Manager::DIVIDER,
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        /** Chart legend list style. */
        $this->add_control(
            'graph_list_options',
            [
                'label' => esc_html__( 'List conventions', 'graphy-elementor' ),
                'type' => Controls_Manager::HEADING,
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        /** Chart legend list typography. */
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'graph_list__typography',
                'label' => esc_html__( 'Typography', 'graphy-elementor' ),
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .mdp-typography-list',
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        /** Legend list color. */
        $this->add_control(
            'conventions_list_color',
            [
                'label' => esc_html__( 'Color', 'graphy-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#595959',
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        /** List style alignment. */
        $this->add_responsive_control(
            'conventions_style_align',
            [
                'label' => esc_html__( 'Alignment', 'graphy-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'graphy-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'graphy-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'graphy-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .mdp-bipobar-conventions-list-align' => 'text-align: {{conventions_style_align}};',
                ],
                'toggle' => true,
                'label_block' => true,
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        /** List style margin. */
        $this->add_responsive_control(
            'conventions_list_margin',
            [
                'label' => esc_html__( 'Margin', 'graphy-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'devices' => [ 'desktop', 'tablet', 'mobile' ],
                'desktop_default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'mobile_default' => [
                    'top' => '15',
                    'right' => '15',
                    'bottom' => '15',
                    'left' => '15',
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .mdp-bipobar-conventions-list-margin' => 'margin: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
                ],
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        $this->add_control(
            'divider_legend',
            [
                'type' => Controls_Manager::DIVIDER,
                'condition' => ['dots_graph_legend' => 'yes']
            ]
        );

        /** Chart legend list style. */
        $this->add_control(
            'graph_labels_heading',
            [
                'label' => esc_html__( 'Labels', 'graphy-elementor' ),
                'type' => Controls_Manager::HEADING,
            ]
        );

        /** Chart legend list typography. */
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'graph_labels_typo',
                'scheme' => Typography::TYPOGRAPHY_1,
                'selector' => '{{WRAPPER}} .ct-labels span',
            ]
        );

        /** Legend list color. */
        $this->add_control(
            'graph_labels_color',
            [
                'label' => esc_html__( 'Color', 'graphy-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .ct-labels span' => 'color: {{VALUE}}',
                ],
            ]
        );

        /** End section style. */
        $this->end_controls_section();

    }

    /**
     * Returns chart array values.
     *
     * @param $settings
     * @since 1.0.0
     * @return array
     */
    public function get_arrayValues( $settings ) {

        /** We get an array with graph data. */
        $valueList = $settings['value_list'];

        /** Create an array to store the results. */
        $res = array();

        /** We write the values of the graph into the array. */
        foreach ($valueList as $val){
            $res[] = $val['value_chart'];
        }

        return $res;

    }

    /**
     * We return the list of signatures.
     *
     * @param $settings
     * @since 1.0.0
     * @return string
     */
    public function get_arrayLabels($settings) {

        /** We get an array with graph data. */
        $valueList = $settings['value_list'];

        /** Create an array to store the results. */
        $res = '';

        /** We write the labels of the graph into the array. */
        foreach ($valueList as $key => $val){
            end($valueList );
            if ( $key === key( $valueList ) ){
                $res .= "{$val['item_name']}";
            } else {
                $res .= "{$val['item_name']}, ";
            }
        }

        return $res;

    }

    /**
     * We generate styles for charts class and save them in a string.
     *
     * @param $settings
     * @param $globalClass
     * @return string
     * @since 1.0.0
     */
    public function get_backgroundColor( $settings, $globalClass ) {

        /** We get an array with graph data. */
        $valueList = $settings['value_list'];

        /** We create an array in which we will store the alphabet. */
        $Alphabet = array();

        /** We create a string variable where we will store styles. */
        $res = '';

        /** We generate the alphabet and save it in an array. */
        for ( $i=ord('a'); $i<=ord('z'); $i++ ){
            $Alphabet[] = chr($i);
        }

        /** We generate styles for charts class and save them in a string. */
        $i = 0;
        foreach ($valueList as $val){
            $i++;
            $res_html = '%s > svg > g > .ct-series-a > .ct-bar:nth-child(%s)'.
                        '{ stroke: %s !important; stroke-width: %spx !important; }';
            $res .= sprintf(
                        $res_html,
                        esc_attr($globalClass),
                        esc_attr($i),
                        esc_attr($val['background_color']),
                        esc_attr($settings['bar_width'])
            );
        }

        return $res;
    }

    /**
     * @param $settings
     *
     * @return string
     */
    public function get_graphConventions( $settings ) {

        /** We get an array with graph data. */
        $valueList = $settings['value_list'];

        /** The array stores all the lines that need to be plotted. */
        $lines_html = '<ul class="mdp-typography-list mdp-bipobar-conventions-list-align '.
                      'mdp-bipobar-conventions-list-margin" style="color: %s;">';
        $lines = sprintf( $lines_html, $settings['conventions_list_color'] );

        foreach ($valueList as $val){
            $lines .= sprintf(
             '<li><span class="mdp-item-color" style="background-color: %s;"></span>%s</li>',
                    esc_attr($val["background_color"]),
                    esc_html($val["item_name"])
            );
        }

        $lines .= '</ul>';

        return $lines;

    }

    /**
     *
     *
     * @param $settings
     */
    public function get_app_title( $settings ){
        if( $settings['show_title'] === 'yes' ){
            $title_html = '<h3 class="mdp-typography-title mdp-shadow-title mdp-bipobar-header-align '.
                          'mdp-bipobar-header-margin" style="color: %s;">%s</h3>';
            echo sprintf( $title_html, esc_attr($settings['title_color']), esc_html($settings['chart_title']) );
        }
    }

    /**
     * @param $settings
     *
     * @return string
     */
    public function get_app_description( $settings ){
        $description_html = '<p class="mdp-typography-description mdp-bipobar-description-align '.
                            'mdp-bipobar-description-margin" style="color: %s;">%s</p>';
        return sprintf(
            $description_html,
            esc_attr($settings['description_color']),
            esc_html($settings['chart_description'])
        );
    }

    /**
     * @param $settings
     *
     * @return string
     */
    public function get_app_title_legend( $settings ){
        $title_legend_html = '<h4 class="mdp-typography-legend mdp-shadow-legend mdp-bipobar-conventions-align '.
                             'mdp-bipobar-conventions-margin" style="color: %s;">%s</h4>';
        return sprintf(
            $title_legend_html,
            esc_attr($settings['conventions_title_color']),
            esc_html($settings['legend_title']) );
    }

    /**
     * Render Frontend Output. Generate the final HTML on the frontend.
     *
     * @since 1.0.0
     * @access protected
     **/
    protected function render() {

        /** We get all the values from the admin panel. */
        $settings = $this->get_settings_for_display();

        /** We will smell an array of graph values. */
        $Items = $this->get_arrayValues($settings);

        /** We get the sum of all graph values. */
        $sumItems = array_sum($Items);

        $maxItem = max($Items);
        $minItem = abs ( min($Items) );

        /** Determine the maximum and minimum point on the chart. */
        if( $maxItem >= $minItem ){
            $scale = $maxItem;
        } else {
            $scale = $minItem;
        }

        /** We get an array with the names of the values of the graph. */
        $Labels = $this->get_arrayLabels($settings);

        /** We write the section style class to a variable. */
        $globalClass = '.mdp-' . $this->get_id();

        /** Convert the array of chart points to a string. */
        $separatedPoints = implode(", ", $Items);

        echo sprintf('<style>%s</style>', $this->get_backgroundColor( $settings, $globalClass ) );

        $this->get_app_title( $settings );

        if( $settings['show_description'] === 'yes' &&
            $settings['after_title'] === 'yes' ){ echo $this->get_app_description( $settings ); }

        echo sprintf(
            '<div id="%s" class="ct-golden-section mdp-%s"></div>',
            esc_attr($globalClass),
            esc_attr($this->get_id()) );

        if( $settings['dots_graph_legend'] === 'yes' ){
           echo sprintf(
             '<div class="mdp-graph-conventions">%s %s</div>',
                    $this->get_app_title_legend( $settings ),
                    wp_kses_post( $this->get_graphConventions( $settings ) )
           );
        }

        if ( $settings['show_description'] === 'yes' &&
                   $settings['after_title'] === '' ){ echo $this->get_app_description( $settings ); }


        $script_default = "<script>ready( () => { ".
            "window.BipolarBar( '%s', '%s', '%s', [%s], %s, '%s', '%s', '%s', '%s', '%s', %s ); ".
            "});</script>";

        $script_popup = "<script>ready( () => { ".
            "window.BipolarBarPopup( '%s', [%s], '%s', %s, '%s', '%s', '%s', '%s', '%s', %s ); ".
            "});</script>";

        if( !is_admin() ) {
            $script_default = "<script>window.addEventListener( 'DOMContentLoaded', (event) => { ready( () => { ".
                "window.BipolarBar( '%s', '%s', '%s', [%s], %s, '%s', '%s', '%s', '%s', '%s', %s ); ".
                "}); });</script>";
            $script_popup = "<script>window.addEventListener( 'DOMContentLoaded', (event) => { ready( () => { ".
                "window.BipolarBarPopup( '%s', [%s], '%s', %s, '%s', '%s', '%s', '%s', '%s', %s ); ".
                "}); });</script>";
        }

        $print_script = sprintf(
            $script_default,
            esc_attr($this->get_id()),
            esc_attr($Labels),
            esc_attr($globalClass),
            esc_attr($separatedPoints),
            esc_attr($scale),
            esc_attr($settings['show_grid_x']),
            esc_attr($settings['show_label_x']),
            esc_attr($settings['show_grid_y']),
            esc_attr($settings['show_label_y']),
            esc_attr($settings['show_animation']),
            esc_attr($settings['speed_animation'])
        );

        if( $settings['show_popup'] === 'yes' ){

            $print_script = sprintf(
                $script_popup,
                esc_attr($Labels),
                esc_attr($separatedPoints),
                esc_attr($globalClass),
                esc_attr($scale),
                esc_attr($settings['show_grid_x']),
                esc_attr($settings['show_label_x']),
                esc_attr($settings['show_grid_y']),
                esc_attr($settings['show_label_y']),
                esc_attr($settings['show_animation']),
                esc_attr($settings['speed_animation'])
            );

        }

        echo  $print_script;

    }

    /**
     * Return link for documentation
     * Used to add stuff after widget
     *
     * @access public
     *
     * @return string
     **/
    public function get_custom_help_url() {

        return 'https://docs.merkulov.design/category/graphy';

    }

}
