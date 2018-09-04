<?php

add_action( 'wp_enqueue_scripts', 'sed_scripts' );

function sed_scripts() {

	if ( in_array( $_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' ) ) ) :
		wp_enqueue_script( 'sed-livereload', 'http://localhost:35729/livereload.js?snipver=1', array(), null, true );
	endif;

	$min = ( in_array( $_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' ) ) ) ? '' : '.min';

	wp_enqueue_style( 'sed-style', SED_URL . 'assets/css/sorteio-educandario.css', array(), false, 'all' );

	wp_enqueue_script( 'jquery-mask', SED_URL . 'assets/js/jquery.mask' . $min . '.js', array( 'jquery' ), '1.14.15', true );

	wp_register_script( 'sed-script', SED_URL . 'assets/js/sorteio-educandario' . $min . '.js', array( 'jquery', 'jquery-mask' ), '1.0.0', true );

	wp_enqueue_script( 'sed-script' );

	wp_localize_script( 'sed-script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

}