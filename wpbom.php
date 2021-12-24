<?php
/**
Plugin Name: WpBom
Plugin URI: https://gitlab.com/sepbit/wpbom
Description: WordPress integration with OWASP CycloneDX and Dependency Track
Version: 1.0.1
Author: Vitor Guia
Author URI: https://vitor.guia.nom.br

@package WpBom
 */

namespace Sepbit\WpBom;

defined( 'ABSPATH' ) || exit();

define( 'SEPBIT_WPBOM_VER', '1.0.0' );
define( 'SEPBIT_WPBOM_DIR', plugin_dir_path( __FILE__ ) );
define( 'SEPBIT_WPBOM_URL', plugin_dir_url( __FILE__ ) );
define( 'SEPBIT_WPBOM_PRE', '_wpbom' );

require SEPBIT_WPBOM_DIR . 'vendor/autoload.php';

add_action( 'admin_init', array( __NAMESPACE__ . '\Controllers\CycloneDXController', 'json' ) );
add_action( 'cmb2_init', array( __NAMESPACE__ . '\Controllers\OptionsPageController', 'options_page' ) );
add_action( 'upgrader_process_complete', array( __NAMESPACE__ . '\Controllers\DependencyTrackController', 'auto_update' ) );
add_action( 'deleted_plugin', array( __NAMESPACE__ . '\Controllers\DependencyTrackController', 'auto_update' ) );
add_action( 'deleted_theme', array( __NAMESPACE__ . '\Controllers\DependencyTrackController', 'auto_update' ) );
