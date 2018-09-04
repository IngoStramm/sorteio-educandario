<?php

function sed_debug( $debug ) {
	echo '<pre>';
	var_dump( $debug );
	echo '</pre>';
}

function sed_qtd_filhos_select() {

	$filhos = [];
	for ( $i = 0; $i < 14; $i++ ) { 
		$filhos[] = $i;
	}
	return $filhos;
}

// Define o Número como título do Credenciado
add_filter( 'wp_insert_post_data', 'sed_participante_title', 99, 2 );

function sed_participante_title( $data, $postarr ) {

	if ( $data[ 'post_type' ] == 'participante' && isset( $_POST[ '_numero_rifa' ] ) && !empty( $_POST[ '_numero_rifa' ] ) ) :

		// Combine address with term
		$title = $_POST[ '_numero_rifa' ];
		$data[ 'post_title' ] = $title;

	endif;

	return $data;

}

add_action( 'wp_ajax_nopriv_sed_envia_participante', 'sed_envia_participante' );
add_action( 'wp_ajax_sed_envia_participante', 'sed_envia_participante' );


function sed_envia_participante() {

	$response = [];
	$response[ 'validado' ] = false;

	// Valida os campos
	if( !isset( $_POST[ 'nome' ] ) && empty( $_POST[ 'nome' ] ) ) :
		$response[ 'message' ] = __( 'Nome vazio!', 'sed' );
	elseif( !isset( $_POST[ 'email' ] ) && empty( $_POST[ 'email' ] ) ) :
		$response[ 'message' ] = __( 'E-mail vazio!', 'sed' );
	elseif( !isset( $_POST[ 'fone' ] ) && empty( $_POST[ 'fone' ] ) ) :
		$response[ 'message' ] = __( 'Telefone vazio!', 'sed' );
	elseif( !isset( $_POST[ 'data' ] ) && empty( $_POST[ 'data' ] ) ) :
		$response[ 'message' ] = __( 'Data vazia!', 'sed' );
	elseif( !isset( $_POST[ 'filhos' ] ) && empty( $_POST[ 'filhos' ] ) ) :
		$response[ 'message' ] = __( 'Quantidade de filhos vazia!', 'sed' );
	elseif( !isset( $_POST[ 'pets' ] ) && empty( $_POST[ 'pets' ] ) ) :
		$response[ 'message' ] = __( 'Quantidade de animais de estimação vazia!', 'sed' );
	elseif( !isset( $_POST[ 'cpf' ] ) && empty( $_POST[ 'cpf' ] ) ) :
		$response[ 'message' ] = __( 'CPF vazio!', 'sed' );
	// Campos validados
	else :
		$response[ 'validado' ] = true;
		$nome = $_POST[ 'nome' ];
		$email = $_POST[ 'email' ];
		$fone = $_POST[ 'fone' ];
		$data = $_POST[ 'data' ];
		$filhos = $_POST[ 'filhos' ];
		$pets = $_POST[ 'pets' ];
		$cpf = $_POST[ 'cpf' ];
		$cpf_valido = sed_valida_cpf( $cpf );
		$encontrou_cpf = sed_busca_cpf( $cpf );
		// Verifica se é um CPF válido
		if( !$cpf_valido ) :
			$response[ 'cpf_valido' ] = false;
			$response[ 'message' ] = __( 'O CPF de número "', 'sed' ) . $cpf . __( '" não é um CPF válido.', 'sed' );
		// Verifica se o CPF já existe
		elseif( $encontrou_cpf ) :
			$response[ 'cpf_valido' ] = true;
			$response[ 'duplicado' ] = true;
			$response[ 'message' ] = __( 'O CPF de número "', 'sed' ) . $cpf . __( '" já está cadastrado e concorrendo ao sorteio.', 'sed' );
		// É um CPF novo
		else :
			$response[ 'cpf_valido' ] = true;
			$response[ 'duplicado' ] = false;
			$exclude_numbers = sed_busca_numeros();
			$novo_numero = sed_gera_numero( 0, 9999, $exclude_numbers );
			// Verifica se ainda é possível gerar novos números
			if( $novo_numero === false ) :
				$response[ 'message' ] = __( 'Infelimzente não foi possível gerar um novo número para você. A capacidade máxima do sorteio foi atingida.', 'sed' );
			else :
				$novo_numero = sed_formata_numero( $novo_numero );
				$response[ 'message' ] = __( 'Cadastro efetuado com sucesso!', 'sed' );
				$response[ 'numero' ] = $novo_numero;

				$postarr = array(
					'post_author'		=> 1,
					'post_title'		=> $novo_numero,
					'post_type'			=> 'participante',
					'post_status'		=> 'publish',
					'comment_status'	=> 'closed',
					'ping_status'		=> 'closed',
					'meta_input'		=> array(
												'dados_nome'		=> $nome,
												'dados_email'		=> $email,
												'dados_fone'		=> $fone,
												'dados_data'		=> $data,
												'dados_filhos'		=> $filhos,
												'dados_pets'		=> $pets,
												'dados_cpf'			=> $cpf,
												'_numero_rifa'		=> $novo_numero
											)
				);

				$response[ 'exclude_numbers'] = $exclude_numbers;
				$verifica_exclude = ( 1 - count( $exclude_numbers ) < 0 );
				$response[ 'verifica_exclude'] = $verifica_exclude;

				$posted = wp_insert_post( $postarr, false );
				$response[ 'posted' ] = $posted;

				// assumes $to, $subject, $message have already been defined earlier...
				 
				$domain = get_option( 'siteurl' ); //or home
				$domain = str_replace( 'https://', '', $domain );
				$domain = str_replace( 'http://', '', $domain );
				$domain = str_replace( 'www', '', $domain ); //add the . after the www if you don't want it
				$response[ 'domain' ] = $domain;

				$to = $email;
				$subject = __( 'Sorteio Educandário & Golden Premier', 'sed' );

				$body = '<p>';
				$body .= __( 'Olá', 'sed' ) . ', ' . $nome . '!';
				$body .= '</p>';
				$body .= '<p>';
				$body .= __( 'Você está participando do sorteio do Educandário & Golden Premier.', 'sed' );
				$body .= ' ';
				$body .= __( 'Este é o seu número:', 'sed' );
				$body .= '</p>';
				$body .= '<h3>' . $novo_numero . '</h3>';
				$body .= '<p>' . __( 'Para maiores informações, visite:', 'sed' ) . ' <a href="' . site_url() . '">' . site_url() . '</a>.';
				$body .= '<p><small><a href="' . get_permalink( 675, false ) . '">' . __( 'Leia as regras do concurso', 'sed' ) . '</a></small></p>';

				$headers = array( 'Content-Type: text/html; charset=UTF-8' );
				$headers[] = 'From: ' . get_bloginfo( 'name' ) . ' <sorteio@' . $domain . '>';
				$headers[] = 'Bcc: ingo@laf.marketing'; // note you can just use a simple email address
				 
				$sent = wp_mail( $to, $subject, $body, $headers );
				$response[ 'sent' ] = $sent;
				$response[ 'to' ] = $to;
				$response[ 'subject' ] = $subject;
			endif;
		endif;
	endif;

	wp_send_json_success( $response );
	wp_die();

}

function sed_busca_numeros() {

	$numeros = [];

	$args = array(
		'post_type'				=> 'participante',
		'meta_key'				=> '_numero_rifa',
		'posts_per_page'		=> -1
	);
	$query_participantes = new WP_Query( $args );

	if ( $query_participantes->have_posts() ) : 
		while ( $query_participantes->have_posts() ) : 
			$query_participantes->the_post();

			$post_id = get_the_ID();
			$numeros[] = get_post_meta( $post_id, '_numero_rifa', true );

		endwhile;

		wp_reset_postdata();

	endif;


	return $numeros;

}

function sed_busca_cpf( $cpf ) {

	$response = false;

	$args = array(
		'post_type'				=> 'participante',
		'meta_key'				=> 'dados_cpf',
		'meta_value'			=> $cpf,
		'posts_per_page'		=> -1
	);
	$query_participantes = new WP_Query( $args );

	if ( $query_participantes->have_posts() ) : 
		while ( $query_participantes->have_posts() ) : 
			$query_participantes->the_post();

			$post_id = get_the_ID();
			$post_cpf = get_post_meta( $post_id, 'dados_cpf', true );
			$post_nome = get_post_meta( $post_id, 'dados_nome', true );
			if( $post_cpf == $cpf )
				$response = true;

		endwhile;

		wp_reset_postdata();

	endif;


	return $response;

}

/**
 * Returns a random integer between $min and $max ( inclusive ) and
 * excludes integers in $exclude_numbers, returns false if no such number
 * exists.
 * 
 * $exclude_numbers is assumed to be sorted in increasing order and each
 * element should be unique.
 */
function sed_gera_numero( $min, $max, $exclude_numbers = array() ) {

	sort( $exclude_numbers );

    if ( $max - count( $exclude_numbers ) < $min ) {
        return false;
    }

    // $pos is the position that the random number will take
    // of all allowed positions
    $pos = mt_rand( 0, $max - $min - count( $exclude_numbers ) );

    // $num being the random number
    $num = $min;

    // while $pos > 0, step to the next position
    // and decrease if the next position is available
    for ( $i = 0; $i < count( $exclude_numbers ); $i += 1 ) {

        // if $num is on an excluded position, skip it
        if ( $num == $exclude_numbers[ $i ] ) {
            $num += 1;
            continue;
        }

        $dif = $exclude_numbers[ $i ] - $num;

        // if the position is after the next excluded number,
        // go to the next excluded number
        if ( $pos >= $dif ) {
            $num += $dif;

            // -1 because we're now at an excluded position
            $pos -= $dif - 1;
        } else {
            // otherwise, return the free position
            return $num + $pos;
        }
    }

    // return the number plus the open positions we still had to go
    return $num + $pos;
}

function sed_formata_numero( $num ) {
	return str_pad( $num, 4, '0', STR_PAD_LEFT );
}

function sed_valida_cpf( $cpf = null ) {

	// Verifica se um número foi informado
	if( empty( $cpf ) ) :
		return false;
	endif;

	// Elimina possivel mascara
	$cpf = preg_replace( "/[^0-9]/", "", $cpf );
	$cpf = str_pad( $cpf, 11, '0', STR_PAD_LEFT );
	
	// Verifica se o numero de digitos informados é igual a 11 
	if( strlen( $cpf ) != 11 ) :
		return false;
	// Verifica se nenhuma das sequências invalidas abaixo 
	// foi digitada. Caso afirmativo, retorna falso
	elseif(	$cpf == '00000000000' || 
				$cpf == '11111111111' || 
				$cpf == '22222222222' || 
				$cpf == '33333333333' || 
				$cpf == '44444444444' || 
				$cpf == '55555555555' || 
				$cpf == '66666666666' || 
				$cpf == '77777777777' || 
				$cpf == '88888888888' || 
				$cpf == '99999999999' ) :
		return false;
	 // Calcula os digitos verificadores para verificar se o
	 // CPF é válido
	 else :
		
		for ($t = 9; $t < 11; $t++) {
			
			for ( $d = 0, $c = 0; $c < $t; $c++ ) {
				$d += $cpf{$c} * ( ( $t + 1 ) - $c );
			}

			$d = ( ( 10 * $d ) % 11 ) % 10;

			if ( $cpf{$c} != $d ) :
				return false;
			endif;
		}

		return true;
	endif;
}