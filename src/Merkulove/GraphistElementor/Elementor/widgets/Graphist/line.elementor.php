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
use Elementor\Repeater;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Merkulove\GraphyElementor\Unity\Plugin as UnityPlugin;

/** @noinspection PhpUnused */
/**
 * Line Chart - Custom Elementor Widget
 * @method start_controls_section( string $string, array $array )
 **/
class line_elementor extends Widget_Base {

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
            'mdp-graphy-line',
            UnityPlugin::get_url() . 'js/line' . UnityPlugin::get_suffix() . '.js',
            [],
            UnityPlugin::get_version(),
            false
        );

        wp_register_script(
            'mdp-graphy-line-popup',
            UnityPlugin::get_url() . 'js/line.popup' . UnityPlugin::get_suffix() . '.js',
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

        /** Connect a shortcode with dynamic data by default */
        add_shortcode( 'line_chart', [ $this, 'line_chart_shortcode' ]);

    }

    /**
     * Return a widget name.
     *
     * @return string
     **/
    public function get_name() {
        return 'mdp-graphy-line';
    }

    /**
     * Return the widget title that will be displayed as the widget label.
     *
     * @return string
     **/
    public function get_title() {
        return esc_html__( 'Line Chart', 'graphy-elementor' );
    }

    /**
     * Set the widget icon.
     *
     * @return string
     */
    public function get_icon() {
        return 'mdp-line-elementor-widget-icon';
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
        return [ 'Merkulove', 'chart', 'line', 'graphy', 'chartist' ];
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
            'mdp-graphy-chartist',
            'mdp-graphy',
            'mdp-graphy-line',
            'mdp-graphy-line-popup'
        ];
    }

    /**
     * Default dynamic data for line chart.
     *
     * @return false|string
     */
    function line_chart_shortcode(){

        $mas= [
            'labels'  => ['Jan', 'Feb', 'Mar', 'Apr'],
            'color'  => ['red', 'black', 'blue', 'gray'],
            'line_height' => [2, 2, 2, 3],
            'line_width' => [2, 2, 2, 3],
            'line_spacing' => [5, 0, 0, 5],
            'point_width' => [7, 7, 7, 7],
            'series' => [
                [12, 9, 7, 8, 5],
                [2, 1, 3.5, 7, 3],
                [1, 3, 4, 5, 6, 13.5],
                [3, 5, 6, 7, 8, 15]
            ]
        ];

        return json_encode($mas);

    }

    /**
     * Add the widget controls.
     *
     * @since 1.0.0
     * @access protected
     *
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
                'placeholder'           => esc_html__( 'Line Chart', 'graphy-elementor' ),
                'default'               => esc_html__( 'Line Chart', 'graphy-elementor' ),
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
                'placeholder'           => esc_html__( 'An example of a simple line chart with three series. You can edit this example in real time.', 'graphy-elementor' ),
                'default'               => esc_html__( 'An example of a simple line chart with three series. You can edit this example in real time.', 'graphy-elementor' ),
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

        /** Data type. */
        $this->add_control(
            'data_type',
            [
                'label' => esc_html__( 'Data type', 'graphy-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'statistical',
                'options' => [
                    'statistical'  => esc_html__( 'Statistical', 'graphy-elementor' ),
                    'dynamic' => esc_html__( 'Dynamic', 'graphy-elementor' ),
                ],
            ]
        );

        /** Dynamic Data. */
        $this->add_control(
            'dynamic_data_shortcode',
            [
                'label'                 => esc_html__( 'Dynamic Data', 'graphy-elementor' ),
                'label_block'           => true,
                'type'                  => Controls_Manager::TEXTAREA,
                'dynamic'               => ['active' => true],
                'description'           => esc_html__( 'Default shortcode [line_chart]', 'graphy-elementor' ),
                'placeholder'           => esc_html__( 'Enter your shortcode', 'graphy-elementor' ),
                'condition'             => ['data_type' => 'dynamic']
            ]
        );

        /** The number of lines on the chart. */
        $this->add_control(
            'number_lines',
            [
                'label' => esc_html__( 'The number of lines on the chart', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => 2,
                'condition' => ['data_type' => 'statistical']
            ]
        );

        /** Dynamically add fields. */
        $repeater = new Repeater();

        /** Label text. */
        $repeater->add_control(
            'label_text',
            [
                'label' => esc_html__( 'Label text', 'graphy-elementor' ),
                'type'  => Controls_Manager::TEXT,
            ]
        );

        /** Chart label list. */
        $this->add_control(
            'graph_label_list',
            [
                'label' => esc_html__( 'Chart label list', 'graphy-elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['label_text' => 'Jan'],
                    ['label_text' => 'Feb'],
                    ['label_text' => 'Mar']
                ],
                'title_field' => 'Label text  - {{{label_text}}}',
                'condition' => ['data_type' => 'statistical']
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

        /** Smoothing. */
        $this->add_control(
            'line_smoothing',
            [
                'label' => esc_html__( 'Smoothing', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 1,
                'default' => 3,
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
            'line_animation_duration',
            [
                'label' => esc_html__( 'Line animation duration(ms)', 'graphy-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 5000,
                        'step' => 50
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1000,
                ],
                'condition' => ['show_animation' => 'yes']
            ]
        );

        $this->add_control(
            'line_animation_delay',
            [
                'label' => esc_html__( 'Line animation delay(ms)', 'graphy-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 5000,
                        'step' => 50
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 1000,
                ],
                'condition' => ['show_animation' => 'yes']
            ]
        );

        $this->add_control(
            'axis_animation_duration',
            [
                'label' => esc_html__( 'Axis animation duration(ms)', 'graphy-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 5000,
                        'step' => 50
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 500,
                ],
                'condition' => ['show_animation' => 'yes']
            ]
        );

        $this->add_control(
            'axis_animation_delay',
            [
                'label' => esc_html__( 'Axis animation delay(ms)', 'graphy-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 2000,
                        'step' => 10
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 80,
                ],
                'condition' => ['show_animation' => 'yes']
            ]
        );



        /** End section content. */
        $this->end_controls_section();

        /** Section Item 1. */
        $this->start_controls_section( 'section_item_1', [
            'label' => esc_html__( 'Item 1', 'graphy-elementor' ),
            'condition'   => [
                'data_type' => 'statistical',
                'number_lines' => array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10)
            ]
        ] );

        /** Item name. */
        $this->add_control(
            'item_name_1',
            [
                'label' => esc_html__( 'Item name', 'graphy-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Item name 1', 'graphy-elementor' ),
                'placeholder' => esc_html__( 'Item name 1', 'graphy-elementor' ),
            ]
        );

        /** Line style. */
        $this->add_control(
            'line_style_1',
            [
                'label' => esc_html__( 'Line style', 'graphy-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphy-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphy-elementor' ),
                'return_value' => 'yes',
            ]
        );

        /** Line width. */
        $this->add_control(
            'line_width_1',
            [
                'label' => esc_html__( 'Line width', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 15,
                'step' => 1,
                'default' => 1,
                'condition' => ['line_style_1' => 'yes']
            ]
        );

        /** Line height. */
        $this->add_control(
            'line_height_1',
            [
                'label' => esc_html__( 'Line height', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 15,
                'step' => 1,
                'default' => 1,
                'condition' => ['line_style_1' => 'yes']
            ]
        );

        /** Spacing. */
        $this->add_control(
            'spacing_1',
            [
                'label' => esc_html__( 'Spacing', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 25,
                'step' => 1,
                'default' => 5,
                'condition' => ['line_style_1' => 'yes']
            ]
        );

        /** Point width. */
        $this->add_control(
            'point_width_1',
            [
                'label' => esc_html__( 'Point width', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 3,
                'max' => 15,
                'step' => 1,
                'default' => 3,
                'condition' => ['line_style_1' => 'yes']
            ]
        );

        /** Line color. */
        $this->add_control(
            'line_color_1',
            [
                'label' => esc_html__( 'Line color', 'graphy-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#4CAF50'
            ]
        );

        /** Dynamically add fields. */
        $repeater_item_1 = new Repeater();

        /** Value field. */
        $repeater_item_1->add_control(
            'value_chart_1',
            [
                'label' => esc_html__( 'Value', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
            ]
        );

        /** Points on the chart. */
        $this->add_control(
            'value_list_item_1',
            [
                'label' => esc_html__( 'Points on the chart', 'graphy-elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_item_1->get_controls(),
                'default' => [
                    ['value_chart_1' => 100],
                    ['value_chart_1' => 90],
                    ['value_chart_1' => 80],
                    ['value_chart_1' => 70],
                    ['value_chart_1' => 60],
                    ['value_chart_1' => 140],
                    ['value_chart_1' => 90],
                    ['value_chart_1' => 80],
                ],
                'title_field' => '{{{value_chart_1}}}',
            ]
        );

        /** End section Item 1. */
        $this->end_controls_section();

        /** Section Item 2. */
        if ( true ) {

            /** Section Item 2. */
            $this->start_controls_section( 'section_item_2', [
                'label' => esc_html__( 'Item 2', 'graphy-elementor' ),
                'condition'   => [
                    'data_type' => 'statistical',
                    'number_lines' => array(2, 3, 4, 5, 6, 7, 8, 9, 10)
                ]
            ] );

            /** Item name. */
            $this->add_control(
                'item_name_2',
                [
                    'label' => esc_html__( 'Item name', 'graphy-elementor' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__( 'Item name 2', 'graphy-elementor' ),
                    'placeholder' => esc_html__( 'Item name 2', 'graphy-elementor' ),
                ]
            );

            /** Line style. */
            $this->add_control(
                'line_style_2',
                [
                    'label' => esc_html__( 'Line style', 'graphy-elementor' ),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( 'Show', 'graphy-elementor' ),
                    'label_off' => esc_html__( 'Hide', 'graphy-elementor' ),
                    'return_value' => 'yes',
                ]
            );

            /** Line width. */
            $this->add_control(
                'line_width_2',
                [
                    'label' => esc_html__( 'Line width', 'graphy-elementor' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 15,
                    'step' => 1,
                    'default' => 1,
                    'condition' => ['line_style_2' => 'yes']
                ]
            );

            /** Line height. */
            $this->add_control(
                'line_height_2',
                [
                    'label' => esc_html__( 'Line height', 'graphy-elementor' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 15,
                    'step' => 1,
                    'default' => 1,
                    'condition' => ['line_style_2' => 'yes']
                ]
            );

            /** Spacing. */
            $this->add_control(
                'spacing_2',
                [
                    'label' => esc_html__( 'Spacing', 'graphy-elementor' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 1,
                    'max' => 25,
                    'step' => 1,
                    'default' => 5,
                    'condition' => ['line_style_2' => 'yes']
                ]
            );

            /** Point width. */
            $this->add_control(
                'point_width_2',
                [
                    'label' => esc_html__( 'Point width', 'graphy-elementor' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 3,
                    'max' => 15,
                    'step' => 1,
                    'default' => 3,
                    'condition' => ['line_style_2' => 'yes']
                ]
            );

            /** Line color. */
            $this->add_control(
                'line_color_2',
                [
                    'label' => esc_html__( 'Line color', 'graphy-elementor' ),
                    'type' => Controls_Manager::COLOR,
                    'scheme' => [
                        'type' => Color::get_type(),
                        'value' => Color::COLOR_1,
                    ],
                    'default' => '#F44336'
                ]
            );

            /** Dynamically add fields. */
            $repeater_item_2 = new Repeater();

            /** Value field. */
            $repeater_item_2->add_control(
                'value_chart_2',
                [
                    'label' => esc_html__( 'Value', 'graphy-elementor' ),
                    'type' => Controls_Manager::NUMBER,
                ]
            );

            /** Points on the chart. */
            $this->add_control(
                'value_list_item_2',
                [
                    'label' => esc_html__( 'Points on the chart', 'graphy-elementor' ),
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater_item_2->get_controls(),
                    'default' => [
                        ['value_chart_2' => 100],
                        ['value_chart_2' => 140],
                        ['value_chart_2' => 120],
                        ['value_chart_2' => 140],
                        ['value_chart_2' => 80],
                        ['value_chart_2' => 140],
                        ['value_chart_2' => 60],
                        ['value_chart_2' => 50],
                    ],
                    'title_field' => '{{{value_chart_2}}}',
                ]
            );

            /** End section Item 2. */
            $this->end_controls_section();

        } // END Section Item 2.

        /** Section Item 3. */
        $this->start_controls_section( 'section_item_3', [
            'label' => esc_html__( 'Item 3', 'graphy-elementor' ),
            'condition'   => [
                'data_type' => 'statistical',
                'number_lines' => array(3, 4, 5, 6, 7, 8, 9, 10)
            ]
        ] );

        /** Item name. */
        $this->add_control(
            'item_name_3',
            [
                'label' => esc_html__( 'Item name', 'graphy-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Item name 3', 'graphy-elementor' ),
                'placeholder' => esc_html__( 'Item name 3', 'graphy-elementor' ),
            ]
        );

        /** Line style. */
        $this->add_control(
            'line_style_3',
            [
                'label' => esc_html__( 'Line style', 'graphy-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphy-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphy-elementor' ),
                'return_value' => 'yes',
            ]
        );

        /** Line width. */
        $this->add_control(
            'line_width_3',
            [
                'label' => esc_html__( 'Line width', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 15,
                'step' => 1,
                'default' => 1,
                'condition' => ['line_style_3' => 'yes']
            ]
        );

        /** Line height. */
        $this->add_control(
            'line_height_3',
            [
                'label' => esc_html__( 'Line height', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 15,
                'step' => 1,
                'default' => 1,
                'condition' => ['line_style_3' => 'yes']
            ]
        );

        /** Spacing. */
        $this->add_control(
            'spacing_3',
            [
                'label' => esc_html__( 'Spacing', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 25,
                'step' => 1,
                'default' => 5,
                'condition' => ['line_style_3' => 'yes']
            ]
        );

        /** Point width. */
        $this->add_control(
            'point_width_3',
            [
                'label' => esc_html__( 'Point width', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 3,
                'max' => 15,
                'step' => 1,
                'default' => 3,
                'condition' => ['line_style_3' => 'yes']
            ]
        );

        /** Line color. */
        $this->add_control(
            'line_color_3',
            [
                'label' => esc_html__( 'Line color', 'graphy-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#673AB7'
            ]
        );

        /** Dynamically add fields. */
        $repeater_item_3 = new Repeater();

        /** Value field. */
        $repeater_item_3->add_control(
            'value_chart_3',
            [
                'label' => esc_html__( 'Value', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
            ]
        );

        /** Points on the chart. */
        $this->add_control(
            'value_list_item_3',
            [
                'label' => esc_html__( 'Points on the chart', 'graphy-elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_item_3->get_controls(),
                'title_field' => '{{{value_chart_3}}}',
            ]
        );

        /** End section Item 3. */
        $this->end_controls_section();

        /** Section Item 4. */
        $this->start_controls_section( 'section_item_4', [
            'label' => esc_html__( 'Item 4', 'graphy-elementor' ),
            'condition'   => [
                'data_type' => 'statistical',
                'number_lines' => array(4, 5, 6, 7, 8, 9, 10)
            ]
        ] );

        /** Item name. */
        $this->add_control(
            'item_name_4',
            [
                'label' => esc_html__( 'Item name', 'graphy-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Item name 4', 'graphy-elementor' ),
                'placeholder' => esc_html__( 'Item name 4', 'graphy-elementor' ),
            ]
        );

        /** Line style. */
        $this->add_control(
            'line_style_4',
            [
                'label' => esc_html__( 'Line style', 'graphy-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphy-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphy-elementor' ),
                'return_value' => 'yes',
            ]
        );

        /** Line width. */
        $this->add_control(
            'line_width_4',
            [
                'label' => esc_html__( 'Line width', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 15,
                'step' => 1,
                'default' => 1,
                'condition' => ['line_style_4' => 'yes']
            ]
        );

        /** Line height. */
        $this->add_control(
            'line_height_4',
            [
                'label' => esc_html__( 'Line height', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 15,
                'step' => 1,
                'default' => 1,
                'condition' => ['line_style_4' => 'yes']
            ]
        );

        /** Spacing. */
        $this->add_control(
            'spacing_4',
            [
                'label' => esc_html__( 'Spacing', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 25,
                'step' => 1,
                'default' => 5,
                'condition' => ['line_style_4' => 'yes']
            ]
        );

        /** Point width. */
        $this->add_control(
            'point_width_4',
            [
                'label' => esc_html__( 'Point width', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 3,
                'max' => 15,
                'step' => 1,
                'default' => 3,
                'condition' => ['line_style_4' => 'yes']
            ]
        );

        /** Line color. */
        $this->add_control(
            'line_color_4',
            [
                'label' => esc_html__( 'Line color', 'graphy-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#03A9F4'
            ]
        );

        /** Dynamically add fields. */
        $repeater_item_4 = new Repeater();

        /** Value field. */
        $repeater_item_4->add_control(
            'value_chart_4',
            [
                'label' => esc_html__( 'Value', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
            ]
        );

        /** Points on the chart. */
        $this->add_control(
            'value_list_item_4',
            [
                'label' => esc_html__( 'Points on the chart', 'graphy-elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_item_4->get_controls(),
                'title_field' => '{{{value_chart_4}}}',
            ]
        );

        /** End section Item 4. */
        $this->end_controls_section();

        /** Section Item 5. */
        $this->start_controls_section( 'section_item_5', [
            'label' => esc_html__( 'Item 5', 'graphy-elementor' ),
            'condition'   => [
                'data_type' => 'statistical',
                'number_lines' => array(5, 6, 7, 8, 9, 10)
            ]
        ] );

        /** Item name. */
        $this->add_control(
            'item_name_5',
            [
                'label' => esc_html__( 'Item name', 'graphy-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Item name 5', 'graphy-elementor' ),
                'placeholder' => esc_html__( 'Item name 5', 'graphy-elementor' ),
            ]
        );

        /** Line style. */
        $this->add_control(
            'line_style_5',
            [
                'label' => esc_html__( 'Line style', 'graphy-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphy-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphy-elementor' ),
                'return_value' => 'yes',
            ]
        );

        /** Line width. */
        $this->add_control(
            'line_width_5',
            [
                'label' => esc_html__( 'Line width', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 15,
                'step' => 1,
                'default' => 1,
                'condition' => ['line_style_5' => 'yes']
            ]
        );

        /** Line height. */
        $this->add_control(
            'line_height_5',
            [
                'label' => esc_html__( 'Line height', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 15,
                'step' => 1,
                'default' => 1,
                'condition' => ['line_style_5' => 'yes']
            ]
        );

        /** Spacing. */
        $this->add_control(
            'spacing_5',
            [
                'label' => esc_html__( 'Spacing', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 25,
                'step' => 1,
                'default' => 5,
                'condition' => ['line_style_5' => 'yes']
            ]
        );

        /** Point width. */
        $this->add_control(
            'point_width_5',
            [
                'label' => esc_html__( 'Point width', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 3,
                'max' => 15,
                'step' => 1,
                'default' => 3,
                'condition' => ['line_style_5' => 'yes']
            ]
        );

        /** Line color. */
        $this->add_control(
            'line_color_5',
            [
                'label' => esc_html__( 'Line color', 'graphy-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#FF9800'
            ]
        );

        /** Dynamically add fields. */
        $repeater_item_5 = new Repeater();

        /** Value field. */
        $repeater_item_5->add_control(
            'value_chart_5',
            [
                'label' => esc_html__( 'Value', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
            ]
        );

        /** Points on the chart. */
        $this->add_control(
            'value_list_item_5',
            [
                'label' => esc_html__( 'Points on the chart', 'graphy-elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_item_5->get_controls(),
                'title_field' => '{{{value_chart_5}}}',
            ]
        );

        /** End section Item 5. */
        $this->end_controls_section();

        /** Section Item 6. */
        $this->start_controls_section( 'section_item_6', [
            'label' => esc_html__( 'Item 6', 'graphy-elementor' ),
            'condition'   => [
                'data_type' => 'statistical',
                'number_lines' => array(6, 7, 8, 9, 10)
            ]
        ] );

        /** Item name. */
        $this->add_control(
            'item_name_6',
            [
                'label' => esc_html__( 'Item name', 'graphy-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Item name 6', 'graphy-elementor' ),
                'placeholder' => esc_html__( 'Item name 6', 'graphy-elementor' ),
            ]
        );

        /** Line style. */
        $this->add_control(
            'line_style_6',
            [
                'label' => esc_html__( 'Line style', 'graphy-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphy-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphy-elementor' ),
                'return_value' => 'yes',
            ]
        );

        /** Line width. */
        $this->add_control(
            'line_width_6',
            [
                'label' => esc_html__( 'Line width', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 15,
                'step' => 1,
                'default' => 1,
                'condition' => ['line_style_6' => 'yes']
            ]
        );

        /** Line height. */
        $this->add_control(
            'line_height_6',
            [
                'label' => esc_html__( 'Line height', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 15,
                'step' => 1,
                'default' => 1,
                'condition' => ['line_style_6' => 'yes']
            ]
        );

        /** Spacing. */
        $this->add_control(
            'spacing_6',
            [
                'label' => esc_html__( 'Spacing', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 25,
                'step' => 1,
                'default' => 5,
                'condition' => ['line_style_6' => 'yes']
            ]
        );

        /** Point width. */
        $this->add_control(
            'point_width_6',
            [
                'label' => esc_html__( 'Point width', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 3,
                'max' => 15,
                'step' => 1,
                'default' => 3,
                'condition' => ['line_style_6' => 'yes']
            ]
        );

        /** Line color. */
        $this->add_control(
            'line_color_6',
            [
                'label' => esc_html__( 'Line color', 'graphy-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#607D8B'
            ]
        );

        /** Dynamically add fields. */
        $repeater_item_6 = new Repeater();

        /** Value field. */
        $repeater_item_6->add_control(
            'value_chart_6',
            [
                'label' => esc_html__( 'Value', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
            ]
        );

        /** Points on the chart. */
        $this->add_control(
            'value_list_item_6',
            [
                'label' => esc_html__( 'Points on the chart', 'graphy-elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_item_6->get_controls(),
                'title_field' => '{{{value_chart_6}}}',
            ]
        );

        /** End section Item 6. */
        $this->end_controls_section();

        /** Section Item 7. */
        $this->start_controls_section( 'section_item_7', [
            'label' => esc_html__( 'Item 7', 'graphy-elementor' ),
            'condition'   => [
                'data_type' => 'statistical',
                'number_lines' => array(7, 8, 9, 10)
            ]
        ] );

        /** Item name. */
        $this->add_control(
            'item_name_7',
            [
                'label' => esc_html__( 'Item name', 'graphy-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Item name 7', 'graphy-elementor' ),
                'placeholder' => esc_html__( 'Item name 7', 'graphy-elementor' ),
            ]
        );

        /** Line style. */
        $this->add_control(
            'line_style_7',
            [
                'label' => esc_html__( 'Line style', 'graphy-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphy-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphy-elementor' ),
                'return_value' => 'yes',
            ]
        );

        /** Line width. */
        $this->add_control(
            'line_width_7',
            [
                'label' => esc_html__( 'Line width', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 15,
                'step' => 1,
                'default' => 1,
                'condition' => ['line_style_7' => 'yes']
            ]
        );

        /** Line height. */
        $this->add_control(
            'line_height_7',
            [
                'label' => esc_html__( 'Line height', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 15,
                'step' => 1,
                'default' => 1,
                'condition' => ['line_style_7' => 'yes']
            ]
        );

        /** Spacing. */
        $this->add_control(
            'spacing_7',
            [
                'label' => esc_html__( 'Spacing', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 25,
                'step' => 1,
                'default' => 5,
                'condition' => ['line_style_7' => 'yes']
            ]
        );

        /** Point width. */
        $this->add_control(
            'point_width_7',
            [
                'label' => esc_html__( 'Point width', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 3,
                'max' => 15,
                'step' => 1,
                'default' => 3,
                'condition' => ['line_style_7' => 'yes']
            ]
        );

        /** Line color. */
        $this->add_control(
            'line_color_7',
            [
                'label' => esc_html__( 'Line color', 'graphy-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#795548'
            ]
        );

        /** Dynamically add fields. */
        $repeater_item_7 = new Repeater();

        /** Value field. */
        $repeater_item_7->add_control(
            'value_chart_7',
            [
                'label' => esc_html__( 'Value', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
            ]
        );

        /** Points on the chart. */
        $this->add_control(
            'value_list_item_7',
            [
                'label' => esc_html__( 'Points on the chart', 'graphy-elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_item_7->get_controls(),
                'title_field' => '{{{value_chart_7}}}',
            ]
        );

        /** End section Item 7. */
        $this->end_controls_section();

        /** Section Item 8. */
        $this->start_controls_section( 'section_item_8', [
            'label' => esc_html__( 'Item 8', 'graphy-elementor' ),
            'condition'   => [
                'data_type' => 'statistical',
                'number_lines' => array(8, 9, 10)
            ]
        ] );

        /** Item name. */
        $this->add_control(
            'item_name_8',
            [
                'label' => esc_html__( 'Item name', 'graphy-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Item name 8', 'graphy-elementor' ),
                'placeholder' => esc_html__( 'Item name 8', 'graphy-elementor' ),
            ]
        );

        /** Line style. */
        $this->add_control(
            'line_style_8',
            [
                'label' => esc_html__( 'Line style', 'graphy-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphy-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphy-elementor' ),
                'return_value' => 'yes',
            ]
        );

        /** Line width. */
        $this->add_control(
            'line_width_8',
            [
                'label' => esc_html__( 'Line width', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 15,
                'step' => 1,
                'default' => 1,
                'condition' => ['line_style_8' => 'yes']
            ]
        );

        /** Line height. */
        $this->add_control(
            'line_height_8',
            [
                'label' => esc_html__( 'Line height', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 15,
                'step' => 1,
                'default' => 1,
                'condition' => ['line_style_8' => 'yes']
            ]
        );

        /** Spacing. */
        $this->add_control(
            'spacing_8',
            [
                'label' => esc_html__( 'Spacing', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 25,
                'step' => 1,
                'default' => 5,
                'condition' => ['line_style_8' => 'yes']
            ]
        );

        /** Point width. */
        $this->add_control(
            'point_width_8',
            [
                'label' => esc_html__( 'Point width', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 3,
                'max' => 15,
                'step' => 1,
                'default' => 3,
                'condition' => ['line_style_8' => 'yes']
            ]
        );

        /** Line color. */
        $this->add_control(
            'line_color_8',
            [
                'label' => esc_html__( 'Line color', 'graphy-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#9E9E9E'
            ]
        );

        /** Dynamically add fields. */
        $repeater_item_8 = new Repeater();

        /** Value field. */
        $repeater_item_8->add_control(
            'value_chart_8',
            [
                'label' => esc_html__( 'Value', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
            ]
        );

        /** Points on the chart. */
        $this->add_control(
            'value_list_item_8',
            [
                'label' => esc_html__( 'Points on the chart', 'graphy-elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_item_8->get_controls(),
                'title_field' => '{{{value_chart_8}}}',
            ]
        );

        /** End section Item 8. */
        $this->end_controls_section();

        /** Section Item 9. */
        $this->start_controls_section( 'section_item_9', [
            'label' => esc_html__( 'Item 9', 'graphy-elementor' ),
            'condition'   => [
                'data_type' => 'statistical',
                'number_lines' => array(9, 10)
            ]
        ] );

        /** Item name. */
        $this->add_control(
            'item_name_9',
            [
                'label' => esc_html__( 'Item name', 'graphy-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Item name 9', 'graphy-elementor' ),
                'placeholder' => esc_html__( 'Item name 9', 'graphy-elementor' ),
            ]
        );

        /** Line style. */
        $this->add_control(
            'line_style_9',
            [
                'label' => esc_html__( 'Line style', 'graphy-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphy-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphy-elementor' ),
                'return_value' => 'yes',
            ]
        );

        /** Line width. */
        $this->add_control(
            'line_width_9',
            [
                'label' => esc_html__( 'Line width', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 15,
                'step' => 1,
                'default' => 1,
                'condition' => ['line_style_9' => 'yes']
            ]
        );

        /** Line height. */
        $this->add_control(
            'line_height_9',
            [
                'label' => esc_html__( 'Line height', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 15,
                'step' => 1,
                'default' => 1,
                'condition' => ['line_style_9' => 'yes']
            ]
        );

        /** Spacing. */
        $this->add_control(
            'spacing_9',
            [
                'label' => esc_html__( 'Spacing', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 25,
                'step' => 1,
                'default' => 5,
                'condition' => ['line_style_9' => 'yes']
            ]
        );

        /** Point width. */
        $this->add_control(
            'point_width_9',
            [
                'label' => esc_html__( 'Point width', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 3,
                'max' => 15,
                'step' => 1,
                'default' => 3,
                'condition' => ['line_style_9' => 'yes']
            ]
        );

        /** Line color. */
        $this->add_control(
            'line_color_9',
            [
                'label' => esc_html__( 'Line color', 'graphy-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#FFEB3B'
            ]
        );

        /** Dynamically add fields. */
        $repeater_item_9 = new Repeater();

        /** Value field. */
        $repeater_item_9->add_control(
            'value_chart_9',
            [
                'label' => esc_html__( 'Value', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
            ]
        );

        /** Points on the chart. */
        $this->add_control(
            'value_list_item_9',
            [
                'label' => esc_html__( 'Points on the chart', 'graphy-elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_item_9->get_controls(),
                'title_field' => '{{{value_chart_9}}}',
            ]
        );

        /** End section Item 9. */
        $this->end_controls_section();

        /** Section Item 10. */
        $this->start_controls_section( 'section_item_10', [
            'label' => esc_html__( 'Item 10', 'graphy-elementor' ),
            'condition' => [
                'data_type' => 'statistical',
                'number_lines' => 10
            ]
        ] );

        /** Item name. */
        $this->add_control(
            'item_name_10',
            [
                'label' => esc_html__( 'Item name', 'graphy-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => esc_html__( 'Item name 10', 'graphy-elementor' ),
                'placeholder' => esc_html__( 'Item name 10', 'graphy-elementor' ),
            ]
        );

        /** Line style. */
        $this->add_control(
            'line_style_10',
            [
                'label' => esc_html__( 'Line style', 'graphy-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Show', 'graphy-elementor' ),
                'label_off' => esc_html__( 'Hide', 'graphy-elementor' ),
                'return_value' => 'yes',
            ]
        );

        /** Line width. */
        $this->add_control(
            'line_width_10',
            [
                'label' => esc_html__( 'Line width', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 15,
                'step' => 1,
                'default' => 1,
                'condition' => ['line_style_10' => 'yes']
            ]
        );

        /** Line height. */
        $this->add_control(
            'line_height_10',
            [
                'label' => esc_html__( 'Line height', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 15,
                'step' => 1,
                'default' => 1,
                'condition' => ['line_style_10' => 'yes']
            ]
        );

        /** Spacing. */
        $this->add_control(
            'spacing_10',
            [
                'label' => esc_html__( 'Spacing', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 25,
                'step' => 1,
                'default' => 5,
                'condition' => ['line_style_10' => 'yes']
            ]
        );

        /** Point width. */
        $this->add_control(
            'point_width_10',
            [
                'label' => esc_html__( 'Point width', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 3,
                'max' => 15,
                'step' => 1,
                'default' => 3,
                'condition' => ['line_style_10' => 'yes']
            ]
        );

        /** Line color. */
        $this->add_control(
            'line_color_10',
            [
                'label' => esc_html__( 'Line color', 'graphy-elementor' ),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Color::get_type(),
                    'value' => Color::COLOR_1,
                ],
                'default' => '#3F51B5'
            ]
        );

        /** Dynamically add fields. */
        $repeater_item_10 = new Repeater();

        /** Value field. */
        $repeater_item_1->add_control(
            'value_chart_10',
            [
                'label' => esc_html__( 'Value', 'graphy-elementor' ),
                'type' => Controls_Manager::NUMBER,
            ]
        );

        /** Points on the chart. */
        $this->add_control(
            'value_list_item_10',
            [
                'label' => esc_html__( 'Points on the chart', 'graphy-elementor' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater_item_10->get_controls(),
                'title_field' => '{{{value_chart_10}}}',
            ]
        );

        /** End section Item 10. */
        $this->end_controls_section();

        /** Style tab. */
        $this->start_controls_section(
                'style_section',
                [
                    'label' => esc_html__( 'Style Section', 'graphy-elementor' ),
                    'tab' => Controls_Manager::TAB_STYLE
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
                    '{{WRAPPER}} .mdp-line-header-align' => 'text-align: {{title_text_align}};',
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
                    '{{WRAPPER}} .mdp-line-header-margin' => 'margin: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
                ],
                'toggle' => true,
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
                    '{{WRAPPER}} .mdp-line-description-align' => 'text-align: {{description_text_align}};',
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
                    '{{WRAPPER}} .mdp-line-description-margin' => 'margin: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
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
                    '{{WRAPPER}} .mdp-line-conventions-align' => 'text-align: {{conventions_title_align}};',
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
                    '{{WRAPPER}} .mdp-line-conventions-margin' => 'margin: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
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
                    '{{WRAPPER}} .mdp-line-conventions-list-align' => 'text-align: {{conventions_style_align}};',
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
                    '{{WRAPPER}} .mdp-line-conventions-list-margin' => 'margin: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
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

	    $this->add_control(
		    'graph_grid_heading',
		    [
			    'label' => esc_html__( 'Grid', 'graphy-elementor' ),
			    'type' => Controls_Manager::HEADING,
		    ]
	    );

	    $this->add_control(
		    'width',
		    [
			    'label' => esc_html__( 'Dash size', 'graphy-elementor' ),
			    'type' => Controls_Manager::SLIDER,
			    'size_units' => [ 'px' ],
			    'range' => [
				    'px' => [
					    'min' => 0,
					    'max' => 50,
					    'step' => 1,
				    ],
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .ct-grid' => 'stroke-dasharray: {{SIZE}}{{UNIT}};',
			    ],
		    ]
	    );

	    $this->add_control(
		    'graph_grid_hor_color',
		    [
			    'label' => esc_html__( 'Horizontal lines color', 'graphy-elementor' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} .ct-grid.ct-horizontal' => 'stroke: {{VALUE}}',
			    ],
		    ]
	    );

	    $this->add_control(
		    'graph_grid_ver_color',
		    [
			    'label' => esc_html__( 'Vertical lines color', 'graphy-elementor' ),
			    'type' => Controls_Manager::COLOR,
			    'selectors' => [
				    '{{WRAPPER}} .ct-grid.ct-vertical' => 'stroke: {{VALUE}}',
			    ],
		    ]
	    );

	    $this->add_control(
		    'graph_point_heading',
		    [
			    'label' => esc_html__( 'Point', 'graphy-elementor' ),
			    'type' => Controls_Manager::HEADING,
		    ]
	    );

	    $this->add_control(
		    'display_point',
		    [
			    'label' => esc_html__( 'Display points', 'graphy-elementor' ),
			    'type' => Controls_Manager::SWITCHER,
			    'label_on' => esc_html__( 'Show', 'graphy-elementor' ),
			    'label_off' => esc_html__( 'Hide', 'graphy-elementor' ),
			    'default' => 'yes',
			    'return_value' => 'yes',
		    ]
	    );

        /** End section style. */
        $this->end_controls_section();

    }

    /**
     * Get chart values.
     *
     * @param $settings
     *
     * @return array
     */
    public function get_arrayLine( $settings ) {

        /** The number of lines that will need to be plotted. */
        $number_lines = $settings['number_lines'];

        /** In the array we save points of one line. */
        $line = array();

        /** The array stores all the lines that need to be plotted. */
        $lines = array();

        for ( $i = 1; $i <= $number_lines; $i++ ) {

            /** Create an array with points on which the graph line is built. */
            foreach ( $settings["value_list_item_$i"] as $val ) { $line[] = $val["value_chart_$i"]; }

            if( isset($line) ){
                /** Save a new line to the array. */
                $lines[] = $line;

                /** After each cycle, we clear the array. */
                unset($line);
            }

        }

        return $lines;

    }

    /**
     * We return the list of signatures.
     *
     * @param $settings
     *
     * @return array
     */
    public function get_arrayLabels( $settings ) {

        /** We get an array with graph data. */
        $valueList = $settings['graph_label_list'];

        /** Create an array to store the results. */
        $res = array();

        /** We write the labels of the graph into the array. */
        foreach ($valueList as $val){ $res[] = $val['label_text']; }

        return $res;

    }

    /**
     * Chart Fill Color.
     *
     * @param $settings - Array with line colors.
     * @param $globalClass - A unique class for each instance of the widget.
     *
     * @return string
     */
    public function get_lineColor( $settings, $globalClass ) {

        /** The number of lines that will need to be plotted. */
        $number_lines = $settings['number_lines'];

        /** We create an array in which we will store the alphabet. */
        $Alphabet = array();

        /** We create a string variable where we will store styles. */
        $res = '';

        /** We generate the alphabet and save it in an array. */
        for ( $i = ord('a'), $iMax = ord( 'z' ); $i <= $iMax; $i++ ){ $Alphabet[] = chr( $i ); }

        /** We generate styles for charts class and save them in a string. */
        $c = -1;
        for ( $i = 1; $i <= $number_lines; $i++ ) {

            $c++;

            $res .= sprintf(
                '.%s > svg > g > .ct-series-%s > .ct-line, '.
                '.%s > svg > g > .ct-series-%s > .ct-point{ stroke: %s !important; }',
                esc_attr($globalClass),
                esc_attr($Alphabet[$c]),
                esc_attr($globalClass),
                esc_attr($Alphabet[$c]),
                esc_attr($settings["line_color_$i"])
            );

            if( $settings["line_style_$i"] === 'yes' ){
                $res .= sprintf(
            '.%s > svg > g > .ct-series-%s .ct-line{ stroke-dasharray: %s %s; '.
                    'animation: dashoffset 1s linear infinite; stroke-width: %spx; }',
                    esc_attr($globalClass),
                    esc_attr($Alphabet[$c]),
                    esc_attr($settings["line_height_$i"]),
                    esc_attr($settings["spacing_$i"]),
                    esc_attr($settings["line_width_$i"])
                );

                $res .= sprintf(
            '.%s > svg > g > .ct-series-%s .ct-point{ stroke-width: %spx; }',
                    esc_attr($globalClass),
                    esc_attr($Alphabet[$c]),
                    esc_attr($settings["point_width_$i"])
                );
            }

        }

        return $res;

    }

    /**
     * Set the color of the lines for the data transmitted dynamically.
     *
     * @param $settings - Array with line colors.
     * @param $globalClass - A unique class for each instance of the widget.
     *
     * @return string
     */
    public function get_dynamic_lineColor( $settings, $globalClass ) {

        $number_lines = 0;

        /** The number of lines that will need to be plotted. */
        foreach($settings as $key => $val){ if( $key === 'series'){ $number_lines = count ($val); } }

        /** We create an array in which we will store the alphabet. */
        $Alphabet = array();

        /** We create a string variable where we will store styles. */
        $res = '';
        /** We generate the alphabet and save it in an array. */
        for ( $i = ord( 'a' ), $iMax = ord( 'z' ); $i <= $iMax; $i ++ ) { $Alphabet[] = chr( $i ); }
        /** We generate styles for charts class and save them in a string. */
        $c = - 1;
        for ( $i = 0; $i <= $number_lines; $i ++ ) {
            $c ++;

            $res .= sprintf(
             '.%s > svg > g > .ct-series-%s .ct-line{ stroke-dasharray: %s %s; '.
                    'animation: dashoffset 1s linear infinite; stroke-width: %spx; }',
                    esc_attr($globalClass),
                    esc_attr($Alphabet[$c]),
                    esc_attr($settings['line_height'][$i]),
                    esc_attr($settings["line_spacing"][$i]),
                    esc_attr($settings["line_width"][$i])

            );

            $res .= sprintf(
            '.%s > svg > g > .ct-series-%s .ct-point{ stroke-width: %spx; }',
                    esc_attr($globalClass),
                    esc_attr($Alphabet[ $c ]),
                    esc_attr($settings["point_width"][$i])
            );

            $res .= sprintf(
            '.%s > svg > g > .ct-series-%s > .ct-line, '.
                    '.%s > svg > g > .ct-series-%s > .ct-point{ stroke: %s !important; }',
                    esc_attr($globalClass),
                    esc_attr($Alphabet[ $c ]),
                    esc_attr($globalClass),
                    esc_attr($Alphabet[ $c ]),
                    esc_attr($settings['color'][$i])
            );
        }

        return $res;

    }

    /**
     *  Graph Conventions.
     *
     * @param $settings
     *
     * @return string
     */
    public function get_graphConventions( $settings ) {

        /** The number of lines that will need to be plotted. */
        $number_lines = $settings['number_lines'];

        /** The array stores all the lines that need to be plotted. */
        $lines_html = '<ul class="mdp-typography-list mdp-line-conventions-list-align '.
                      'mdp-line-conventions-list-margin" style="color: %s;">';
        $lines = sprintf( $lines_html,  esc_attr($settings['conventions_list_color']) );

        for ( $i = 1; $i <= $number_lines; $i++ ) {
            $lines .= sprintf(
             '<li><span class="mdp-item-color" style="background-color: %s;"></span>%s</li>',
                    esc_attr($settings["line_color_$i"]),
                    esc_html($settings["item_name_$i"])
            );
        }

        $lines .= '</ul>';

        return $lines;

    }

    /**
     * Display labels for the data transferred dynamically.
     *
     * @param $settings
     * @param $elSettings
     * @return string
     */
    public function get_dynamic_graphConventions( $settings, $elSettings) {

        $number_lines = 0;

        /** The number of lines that will need to be plotted. */
        foreach ($settings as $key => $val){ if( $key === 'series'){ $number_lines = count ($val); } }

        /** The array stores all the lines that need to be plotted. */
        $lines_html = '<ul class="mdp-typography-list mdp-line-conventions-list-align '.
                      'mdp-line-conventions-list-margin" style="color: %s;">';
        $lines = sprintf( $lines_html, esc_attr($elSettings['conventions_list_color']) );

        for ( $i = 0; $i <= $number_lines; $i++ ) {
            $lines .= sprintf(
            '<li><span class="mdp-item-color" style="background-color: %s;"></span>%s</li>',
                    esc_attr($settings["color"][$i]),
                    esc_html($settings["labels"][$i])
            );
        }

        $lines .= '</ul>';

        return $lines;

    }

    /**
     *  Returns an array with dynamic data for building lines.
     *
     * @param $dynamic - dynamic data transmitted by shortcode.
     *
     * @return array
     */
    public function get_items_dynamic( $dynamic ) {

        $Items = [];

        if( is_array($dynamic) ){
            foreach($dynamic as $key => $val ) { if ( $key === 'series' ) { $Items = $val; } }
        }

        return $Items;

    }

    /**
     *  We return an array with signatures for points on the chart.
     *
     * @param $dynamic - dynamic data transmitted by shortcode.
     *
     * @return array
     */
    public function get_labels_dynamic( $dynamic ) {

        $Labels = [];

        if( is_array($dynamic) ){
            foreach($dynamic as $key => $val ) {
                if ( $key === 'labels' ) {
                    $Labels = $val;
                }
            }
        }

        return $Labels;

    }

    /**
     * @param $settings
     */
    public function get_app_title( $settings ){
        if( $settings['show_title'] === 'yes' ){
            $title_html = '<h3 class="mdp-typography-title mdp-shadow-title mdp-line-header-align '.
                          'mdp-line-header-margin" style="color: %s;">%s</h3>';
            echo sprintf(
                $title_html,
                esc_attr($settings['title_color']),
                esc_html($settings['chart_title']) );
        }
    }

    /**
     * @param $settings
     *
     * @return string
     */
    public function get_app_description( $settings ){
        $description_html = '<p class="mdp-typography-description mdp-line-description-align '.
                             'mdp-line-description-margin" style="color: %s;">%s</p>';
        return sprintf(
            $description_html,
            esc_attr($settings['description_color']),
            esc_html($settings['chart_description']) );
    }

    /**
     * @param $settings
     *
     * @return string
     */
    public function get_app_title_legend( $settings ){
        $title_legend_html = '<h4 class="mdp-typography-legend mdp-shadow-legend mdp-line-conventions-align '.
                             'mdp-line-conventions-margin" style="color: %s;">%s</h4>';
        return sprintf(
            $title_legend_html,
            esc_attr($settings['conventions_title_color']),
            esc_html($settings['legend_title']) );
    }

    /**
     * @param $settings
     * @param $dynamic
     */
    public function get_app_conventions( $settings, $dynamic ){
        if( $settings['dots_graph_legend'] === 'yes' ){

            echo sprintf(
        '<div class="mdp-graph-conventions">%s %s</div>',
                $this->get_app_title_legend( $settings ),
                wp_kses_post( $this->get_graphConventions( $settings ) )
            );

            if( $settings["data_type"] === 'dynamic' ) {
                echo sprintf(
            '<div class="mdp-graph-conventions">%s %s</div>',
                    $this->get_app_title_legend( $settings ),
                    wp_kses_post( $this->get_dynamic_graphConventions( $dynamic, $settings ) )
                );
            }

        }
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

        /** Dynamic data transmitted by shortcode in json format. */
        $dynamic_json = $settings["dynamic_data_shortcode"];

        /** Convert json data to an array. */
        $dynamic = json_decode( $dynamic_json, true );

        /** We will smell an array of graph values. */
        $Items = $this->get_items_dynamic( $dynamic );
        $Labels = $this->get_labels_dynamic( $dynamic );
        if( $settings["data_type"] === 'statistical' ) {
            $Items = $this->get_arrayLine( $settings );
            $Labels = $this->get_arrayLabels( $settings );
        }

        /** We write the section style class to a variable. */
        $globalClass = sprintf( 'mdp-%s', $this->get_id() );

        /** We translate the data from the array with a list of values into a string. */
        $point = '';
        foreach ($Items as $Item){
            if ( $Item !== null ) {
                $point .= '[';
                    foreach ( $Item as $val ) { $point .= $val . ', '; }
                $point .= '], ';
            }
        }

        /** Convert the array of chart labels to a string. */
        $separatedLabels = implode(", ", $Labels );

        echo sprintf('<style>%s</style>', $this->get_lineColor( $settings, $globalClass ) );
        if( $settings["data_type"] === 'dynamic' ) {
            echo sprintf('<style>%s</style>', $this->get_dynamic_lineColor( $dynamic, $globalClass ) );
        }

        $this->get_app_title( $settings );

        if( $settings['show_description'] === 'yes' &&
            $settings['after_title'] === 'yes' ){ echo $this->get_app_description( $settings ); }

        echo sprintf(
            '<div id="%s" class="ct-golden-section mdp-%s %s"></div>',
                    esc_attr($globalClass),
                    esc_attr($this->get_id()),
	                $settings['display_point'] !== 'yes' ? 'mdp-hide-points' : '' );

        $this->get_app_conventions( $settings, $dynamic );

        if( $settings['show_description'] === 'yes' &&
            $settings['after_title'] === '' ){ echo $this->get_app_description( $settings ); }

        $script_default = "<script>ready( () => { ".
            "window.Line( '%s', '%s', '%s', [%s], '%s', '%s', '%s', '%s', '%s', '%s', %s, %s, %s, %s ); ".
            "});</script>";

        $script_popup = "<script>ready( () => { ".
            "window.LinePopup( '%s', '%s', [%s], '%s', '%s', '%s', '%s', '%s', '%s', %s, %s, %s, %s ); ".
            "});</script>";

        if( !is_admin() ) {
            $script_default = "<script>window.addEventListener( 'DOMContentLoaded', (event) => { ready( () => { ".
                "window.Line( '%s', '%s', '%s', [%s], '%s', '%s', '%s', '%s', '%s', '%s', %s, %s, %s, %s ); ".
                "}); });</script>";
            $script_popup = "<script>window.addEventListener( 'DOMContentLoaded', (event) => { ready( () => { ".
                "window.LinePopup( '%s', '%s', [%s], '%s', '%s', '%s', '%s', '%s', '%s', %s, %s, %s, %s ); ".
                "}); });</script>";
        }

        $print_script = sprintf(
            $script_default,
    esc_attr($this->get_id()),
            esc_attr($separatedLabels),
            esc_attr($globalClass),
            esc_attr($point),
            esc_attr($settings['line_smoothing']),
            esc_attr($settings['show_grid_x']),
            esc_attr($settings['show_label_x']),
            esc_attr($settings['show_grid_y']),
            esc_attr($settings['show_label_y']),
            esc_attr($settings['show_animation']),
            !empty( $settings[ 'axis_animation_delay' ]['size'] ) ?
                esc_attr($settings[ 'axis_animation_delay' ]['size']) : 0,
            !empty( $settings[ 'axis_animation_duration' ]['size'] ) ?
                esc_attr($settings[ 'axis_animation_duration' ]['size']) : 0,
            !empty( $settings[ 'line_animation_delay' ]['size'] ) ?
                esc_attr($settings[ 'line_animation_delay' ]['size']) : 0,
            !empty( $settings[ 'line_animation_duration' ]['size'] ) ?
                esc_attr($settings[ 'line_animation_duration' ]['size']) : 0
        );

        if( $settings['show_popup'] === 'yes' ){

            $print_script = sprintf(
                $script_popup,
        esc_attr($separatedLabels),
                esc_attr($globalClass),
                esc_attr($point),
                esc_attr($settings['line_smoothing']),
                esc_attr($settings['show_grid_x']),
                esc_attr($settings['show_label_x']),
                esc_attr($settings['show_grid_y']),
                esc_attr($settings['show_label_y']),
                esc_attr($settings['show_animation']),
                !empty( $settings[ 'axis_animation_delay' ]['size'] ) ?
                    esc_attr($settings[ 'axis_animation_delay' ]['size']) : 0,
                !empty( $settings[ 'axis_animation_duration' ]['size'] ) ?
                    esc_attr($settings[ 'axis_animation_duration' ]['size']) : 0,
                !empty( $settings[ 'line_animation_delay' ]['size'] ) ?
                    esc_attr($settings[ 'line_animation_delay' ]['size']) : 0,
                !empty( $settings[ 'line_animation_duration' ]['size'] ) ?
                    esc_attr($settings[ 'line_animation_duration' ]['size']) : 0
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
