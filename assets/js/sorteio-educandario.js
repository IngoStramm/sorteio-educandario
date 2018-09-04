jQuery( document ).ready( function( $ ) {

	var sed_masks = function(){
		console.log('message: 13');
		// $( '.rg-mask' ).find( 'input' ).mask( '00.000.000-0' );
		$( '.cpf-mask' ).mask('000.000.000-00', { reverse: true } );
		$( '.cep-mask' ).mask( '00000-000' );
		$( '.data-mask' ).mask( '00/00/0000' );
		$( '.fone-mask' ).mask( '(00) 90000-0000' );
	};

	
	var sed_form_participante = function() {
		// console.log('sed_verifica_cpf: 6');
		$( '.form-sorteio' ).each( function(){
			// console.log('each');
			var form = $( this );
			var btn = form.find( '.wpcf7-submit.button' );
			var form_response = form.find( '.wpcf7-response-output.wpcf7-display-none' );
			form_response.empty();
			form.find( '.error' ).removeClass( 'error' );
			btn.off().click(function(e){
				e.preventDefault();

				var nome = form.find( '.nome' );
				var email = form.find( '.email' );
				var fone = form.find( '.fone' );
				var cpf = form.find( '.cpf' );
				var data = form.find( '.data' );
				var filhos = form.find( '.filhos' );
				var pets = form.find( '.pets' );

				form.addClass( 'processing' );

				// console.log('click!');

				if( nome.val() === '' ) {
					nome.addClass( 'error' );
				} else {
					nome.removeClass( 'error' );
				}

				if( email.val() === '' ) {
					email.addClass( 'error' );
				} else {
					email.removeClass( 'error' );						
				}

				if( fone.val() === '' ) {
					fone.addClass( 'error' );
				} else {
					fone.removeClass( 'error' );
				}

				if( data.val() === '' ) {
					data.addClass( 'error' );
				} else {
					data.removeClass( 'error' );
				}

				if( filhos.val() === '' ) {
					filhos.addClass( 'error' );
				} else {
					filhos.removeClass( 'error' );
				}

				if( pets.val() === '' ) {
					pets.addClass( 'error' );
				} else {
					pets.removeClass( 'error' );
				}

				if( cpf.val() === '' ) {
					cpf.addClass( 'error' );
				} else {
					cpf.removeClass( 'error' );
				}

				if( nome.val() === '' || email.val() === '' || fone.val() === ''|| cpf.val() === '' || data.val() === ''|| filhos.val() === ''|| pets.val() === '' ) {
					form.removeClass( 'processing' );
					return;
				}

				$.ajax({
					type: 'POST',
					url: ajax_object.ajax_url,
					data: {
						action : 'sed_envia_participante',
						nome: nome.val(),
						email: email.val(),
						fone: fone.val(),
						cpf: cpf.val(),
						data: data.val(),
						filhos: filhos.val(),
						pets: pets.val()
					},
					dataType: 'json',
					success: function( response ) {

						var output = '';

						// console.log('response.data.message: ' + response.data.message );
						// console.log('response.data.validado: ' + response.data.validado );
						// console.log('response.data.url: ' + response.data.url );

						if( !response.data.validado ) {
							console.log( 'Erro de validação!' );
						} else if( !response.data.cpf_valido ) {
							console.log( 'CPF inválido' );
							cpf.addClass( 'error' );
							output = response.data.message;
						} else if( response.data.duplicado ) {
							console.log( 'CPF duplicado' );
							output = response.data.message;
						} else {
							console.log('Validação OK e CPF único');
							// form.hide();
						}

						output = '<p>' +response.data.message + '</p>';
						if( typeof response.data.numero !== 'undefined' && response.data.numero !== null ) {
							output += '<p>Seu número é <span class="number"><strong>' + response.data.numero + '</strong></span></p>';
						}
						form_response.html( output );

					},
					complete: function( response ) {
						form.removeClass( 'processing' );
					}
				});
			}); // $( btn ).click
		});
	};

	var valida_cpf = function( strCPF ) {
		var Soma;
		var Resto;
		Soma = 0;
		if( strCPF === '00000000000' ) {
			return false;
		}

		console.log('strCPF: ' + strCPF);
		for( i = 1; i <= 9; i++ ) {
			console.log('substring: ' + strCPF.substring( i-1, i ) );
			console.log('parseInt: ' + parseInt( strCPF.substring( i-1, i ), 10 ) );
			Soma = Soma + parseInt( strCPF.substring( i-1, i ), 10 ) * ( 11 - i );
		}
		
		Resto = ( Soma * 10 ) % 11;

		console.log('Resto: ' + Resto);

		if( ( Resto === 10 ) || ( Resto === 11 ) ) {
			Resto = 0;
		}
		if( Resto !== parseInt( strCPF.substring( 9, 10 ), 10 ) ) {
			console.log('Erro: #1');
			return false;
		}

		Soma = 0;
		for( i = 1; i <= 10; i++ ) {
			Soma = Soma + parseInt( strCPF.substring( i-1, i ), 10 ) * ( 12 - i );
		}
		Resto = (Soma * 10) % 11;

		if( ( Resto === 10 ) || ( Resto === 11 ) ) {
			Resto = 0;
		}
		if( Resto !== parseInt( strCPF.substring( 10, 11 ), 10 ) ) {
			console.log('Erro: #2');
			return false;
		}
		return true;
	};

	$(document).ready(function(){
		sed_form_participante();
		sed_masks();
	}); // $(document).ready

});
