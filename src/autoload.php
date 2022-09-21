<?php
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

/**
 * Autoload class used to register custom __autoload() implementation for plugin.
 *
 * @since 1.0.0
 *
 **/
final class Autoload {

    /**
     * Plugin root Namespace.
     *
     * @since 1.0.0
     * @var string
     **/
    private static $namespace = 'Merkulove\\';

    /**
     * Shorthand for DIRECTORY_SEPARATOR.
     * Brevity is the Soul of Wit.
     *
     * @since 1.0.0
     * @var string
     **/
    private static $DS = DIRECTORY_SEPARATOR;

    /**
     * Custom autoloader for plugin.
     *
     * @param string $class - Called class name.
     *
     * @static
     * @since  1.0.0
     * @access public
     *
     * @return void
     **/
    public static function load( $class ) {

        /** Bail if the class is not in our namespace. */
        if ( 0 !== strpos( $class, self::$namespace ) ) { return; }

        /** Classes from Graphy for Elementor. */
        $file_p = self::get_plugin_class_file( $class );

        /** If Class exists in Graphy for Elementor - load it. */
        self::include_class( $file_p );

        /** Classes from Unity. */
        $file_u = self::get_unity_class_file( $class );

        /** Secondly we load classes from Unity. */
        self::include_class( $file_u );

    }

    /**
     * Build file path for classes from Unity directory.
     *
     * @param string $class - Called class name.
     *
     * @static
     * @since  1.0.0
     * @access private
     *
     * @return string - Path to class file.
     **/
    private static function get_unity_class_file( $class ) {

        /** Build the filename. */
        $file = realpath( __DIR__ );

        return $file . self::$DS . str_replace( ['GraphyElementor\\', '\\'], ['', self::$DS], $class ) . '.php';

    }

    /**
     * Build file path for classes from Graphy for Elementor directory.
     *
     * @param string $class - Called class name.
     *
     * @static
     * @since  1.0.0
     * @access private
     *
     * @return string - Path to class file.
     **/
    private static function get_plugin_class_file( $class ) {

        /** Build the filename. */
        $file = realpath( __DIR__ );

        return $file . self::$DS . str_replace( '\\', self::$DS, $class ) . '.php';

    }

    /**
     * Includes and evaluates the specified file only once.
     *
     * @param string $file - Path to class file.
     *
     * @static
     * @since  1.0.0
     * @access private
     *
     * @return string - Path to class file.
     **/
    private static function include_class( $file ) {

        /** If Class file exists - load it. */
        if ( file_exists( $file ) ) {

            /** @noinspection PhpIncludeInspection */
            include_once( $file );

        }

    }

}

/** Register plugin custom autoloader. */
spl_autoload_register( __NAMESPACE__ .'\Autoload::load' );

