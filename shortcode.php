<?php

add_shortcode( 'form_sorteio', 'sed_form_sorteio' );

// [form_sorteio]
function sed_form_sorteio( $atts ) {
	$output = '';
	$output .= '
		<div class="wpcf7-form form-sorteio">

			<div class="form-flat">

				<span class="wpcf7-form-control-wrap">

					<input type="text" name="nome" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required nome" aria-required="true" aria-invalid="false" placeholder="' . __( 'Nome', 'sed' ) . '" />

				</span>

				<span class="wpcf7-form-control-wrap">

					<input type="email" name="nome" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required email" aria-required="true" aria-invalid="false" placeholder="' . __( 'E-mail', 'sed' ) . '" />

				</span>

				<span class="wpcf7-form-control-wrap">

					<input type="text" name="cpf" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required fone fone-mask" aria-required="true" aria-invalid="false" placeholder="' . __( 'Telefone', 'sed' ) . '" />

				</span>

				<span class="wpcf7-form-control-wrap">

					<input type="text" name="cpf" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required cpf cpf-mask" aria-required="true" aria-invalid="false" placeholder="' . __( 'CPF', 'sed' ) . '" />

				</span>

				<span class="wpcf7-form-control-wrap">

					<input type="text" name="nome" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required data data-mask" aria-required="true" aria-invalid="false" placeholder="' . __( 'Data de Nascimento', 'sed' ) . '" />

				</span>

				<span class="wpcf7-form-control-wrap">

					

						<select class="filhos">

							<option value="">' . __( 'Quantidade de filhos', 'sed' ) . '</option>';

							$qtd_filhos = sed_qtd_filhos_select();

							foreach( $qtd_filhos as $filho ) :
								$output .= '<option value="' . $filho . '">' . $filho . '</option>';
							endforeach;

	$output .=			'</select>

					</label>

				</span>

				<span class="wpcf7-form-control-wrap">

					<select class="pets">

						<option value="">' . __( 'Quantidade de animais de estimação', 'sed' ) . '</option>';

						$qtd_pets = sed_qtd_filhos_select();

						foreach( $qtd_pets as $pet ) :
							$output .= '<option value="' . $pet . '">' . $pet . '</option>';
						endforeach;

$output .=			'</select>


				</span>

				<input type="submit" value="' . __( 'Enviar!', 'sed' ) . '" class="wpcf7-form-control wpcf7-submit button"><span class="ajax-loader"></span>

			</div>

		<div class="wpcf7-response-output wpcf7-display-none"></div></div>
		';
	// $output .= fc_busca_cpf( '03245262701' );

	// $excluir = [];

	// for ( $i = 9998; $i> 0; $i-- ) { 
	// 	$excluir[] = $i;
	// }


	// $output = sed_gera_numero( 0, 9999, $excluir );

	return $output;
}