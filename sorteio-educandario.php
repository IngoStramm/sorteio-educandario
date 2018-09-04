<?php
/**
 * Plugin Name: Sorteio Educandário
 * Plugin URI: http://laf.marketing
 * Description: Plugin do Sorteio do Educandário
 * Version: 1.0.0
 * Author: Ingo Stramm
 * Author URI: https://laf.marketing
 * Text Domain: sed
 */
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

define( 'SED_DIR', plugin_dir_path( __FILE__ ) );
define( 'SED_URL', plugin_dir_url( __FILE__ ) );

require_once SED_DIR . '/tgm/tgm.php';
require_once SED_DIR . '/functions.php';
require_once SED_DIR . '/scripts.php';
require_once SED_DIR . '/classes/class-post-type.php';
require_once SED_DIR . '/participante.php';
require_once SED_DIR . '/shortcode.php';