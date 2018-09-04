<?php

add_action( 'init', 'sed_video_cpt', 1 );

function sed_video_cpt() {
    $participante = new Sed_Post_Type(
        'Participante', // Nome (Singular) do Post Type.
        'participante' // Slug do Post Type.
    );

    $participante->set_labels(
        array(
        	'name'               => _x( 'Participantes', 'post type general name', 'sed' ),
        	'singular_name'      => _x( 'Participante', 'post type singular name', 'sed' ),
        	'menu_name'          => _x( 'Participantes', 'admin menu', 'sed' ),
        	'name_admin_bar'     => _x( 'Participante', 'Adicionar Novo on admin bar', 'sed' ),
        	'add_new'            => _x( 'Adicionar Novo', 'Participante', 'sed' ),
        	'add_new_item'       => __( 'Adicionar Novo Participante', 'sed' ),
        	'new_item'           => __( 'Novo Participante', 'sed' ),
        	'edit_item'          => __( 'Editar Participante', 'sed' ),
        	'view_item'          => __( 'Visualizar Participante', 'sed' ),
        	'all_items'          => __( 'Todos Participantes', 'sed' ),
        	'search_items'       => __( 'Pesquisar Participantes', 'sed' ),
        	'parent_item_colon'  => __( 'Participantes Pai:', 'sed' ),
        	'not_found'          => __( 'Nenhum Participante encontrada.', 'sed' ),
        	'not_found_in_trash' => __( 'Nenhum Participante encontrada na lixeira.', 'sed' )
        )
    );

    $participante->set_arguments(
        array(
            'supports' => array( '' )
        )
    );
}

add_action( 'cmb2_admin_init', 'sed_register_credenciado_metabox' );
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function sed_register_credenciado_metabox() {

	$prefix = 'dados_';

	$dados = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => esc_html__( 'Dados', 'sed' ),
		'object_types'  => array( 'participante' ), // Post type
		// 'show_on_cb' => 'yourprefix_show_if_front_page', // function should return a bool value
		// 'context'    => 'normal',
		// 'priority'   => 'high',
		// 'show_names' => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // true to keep the metabox closed by default
		// 'classes'    => 'extra-class', // Extra cmb2-wrap classes
		// 'classes_cb' => 'yourprefix_add_some_classes', // Add classes through a callback.
	) );

	$dados->add_field( array(
		'name'       => esc_html__( 'Nome', 'sed' ),
		'id'         => $prefix . 'nome',
		'type'       => 'text',
		'attributes' => array(
			'required' => 'required',
		),
	) );

	$dados->add_field( array(
		'name'       => esc_html__( 'E-mail', 'sed' ),
		'id'         => $prefix . 'email',
		'type'       => 'text_email',
		'attributes' => array(
			'required' => 'required',
		),
	) );

	$dados->add_field( array(
		'name'       => esc_html__( 'Telefone', 'sed' ),
		'id'         => $prefix . 'fone',
		'type'       => 'text',
		'attributes' => array(
			'required' => 'required',
		),
	) );

	$dados->add_field( array(
		'name'       => esc_html__( 'CPF', 'sed' ),
		'id'         => $prefix . 'cpf',
		'type'       => 'text',
		'attributes' => array(
			'required' => 'required',
			// 'type' => 'number',
			// 'pattern' => '\d*',
		),
	) );

	$dados->add_field( array(
		'name'       => esc_html__( 'Data de Nascimento', 'sed' ),
		'id'         => $prefix . 'data',
		'type'       => 'text_date',
		'attributes' => array(
			'required' => 'required',
			'date_format' => 'Y-M-D',
		),
	) );

	$dados->add_field( array(
		'name'       => esc_html__( 'Quantidade de filhos', 'sed' ),
		'id'         => $prefix . 'filhos',
		'type'       => 'select',
		'attributes' => array(
			'required' => 'required',
		),
		'options' => 'sed_qtd_filhos_select'
	) );

	$dados->add_field( array(
		'name'       => esc_html__( 'Quantidade de animais de estimação', 'sed' ),
		'id'         => $prefix . 'pets',
		'type'       => 'select',
		'attributes' => array(
			'required' => 'required',
		),
		'options' => 'sed_qtd_filhos_select'
	) );

	$prefix = 'rifa_';

	$rifa = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => esc_html__( 'Número', 'sed' ),
		'object_types'  => array( 'participante' ), // Post type
		// 'show_on_cb' => 'yourprefix_show_if_front_page', // function should return a bool value
		'context'    => 'side',
		'priority'   => 'high',
		// 'show_names' => true, // Show field names on the left
		// 'cmb_styles' => false, // false to disable the CMB stylesheet
		// 'closed'     => true, // true to keep the metabox closed by default
		// 'classes'    => 'extra-class', // Extra cmb2-wrap classes
		// 'classes_cb' => 'yourprefix_add_some_classes', // Add classes through a callback.
	) );

	$post_id = isset( $_GET[ 'post' ] ) ? $_GET[ 'post' ] : 0;
	$numero = get_post_meta( $post_id, '_numero_rifa', true );
	
	$rifa->add_field( array(
		'name'       => esc_html__( 'Número do sorteado:', 'sed' ) . ' ' . (string)$numero,
		'id'         => $prefix . 'numero',
		'type'       => 'title',
	) );

}