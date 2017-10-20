
$(document).ready(function() {
	$('#clock').countdown('2017/04/21 00:00:00', function(event) {
		$(this).html(event.strftime('%D dias %H:%M:%S'));
	});
});

function enviarForm()
{

		$('#formInscricao').on('submit',function (e)
		{
			e.preventDefault();
			
			var str = $('#formInscricao').serialize();
			//alert(str);
			$.ajax({
				type: "POST",
				url: "server.php?p=add",
				dataType: "html",
				data: str
				
			
			
			}).done(function(msg){
				$('#retorno').hide().html(msg).fadeIn('slow');
				$('div#retorno input[type=text]').focus(function() {
					$('.help-block').html('');
					$('p#pet').html('');
				});
				$('#nome_pet').autocomplete({
					minLength: 5,
				  source: 'server.php?p=autocomp',
				  appendTo: "#inscricoes",
					response: function(event, ui) {
						// ui.content is the array that's about to be sent to the response callback.
						
						$('p#pet').hide().html("Não foi encontrado no banco de dados o nome do seu pet. <a href=\"server.php?p=listaPets\" target=\"_blank\">Clique aqui para acessar a lista com nome de todos os PETS do nordeste!</a> Depois, tente digitar corretamente o nome do seu PET.").fadeIn('slow');
						
						if (ui.content.length>0) {
							//$('#nome_pet').val('');
							$('p#pet').html('');
							//$("#empty-message").empty();
						}
						//alert('contagem: '+ui.content.length);
					}
				});
			});
			
		});

}
function newForm()
{
	//var str = $('#formInscricao').serialize();
	//alert(str);
	$.ajax({
		type: "GET",
		url: "server.php?p=new",
		dataType: "html"
		
	
	
	}).done(function(msg){
		$('#retorno').hide().html(msg).fadeIn('slow');
		
		$('div#retorno input[type=text]').focus(function() {
			$('.help-block').html('');
			$('p#pet').html('');
		});
		$('#nome_pet').autocomplete({
			minLength: 5,
		  source: 'server.php?p=autocomp',
		  appendTo: "#inscricoes",
			response: function(event, ui) {
				// ui.content is the array that's about to be sent to the response callback.
				
				$('p#pet').hide().html("Não foi encontrado no banco de dados o nome do seu pet. <a href=\"server.php?p=listaPets\" target=\"_blank\">Clique aqui para acessar a lista com nome de todos os PETS do nordeste!</a> Depois, tente digitar corretamente o nome do seu PET.").fadeIn('slow');
				
				if (ui.content.length>0) {
					//$('#nome_pet').val('');
					$('p#pet').html('');
					//$("#empty-message").empty();
				}
				//alert('contagem: '+ui.content.length);

			}
		});

	});
	
}

$('#novoLogin').on('submit',function (e)
{
	e.preventDefault();
	var str = $('#novoLogin').serialize();
	//alert(str);
	$.ajax({
		type: "POST",
		url: "server.php?p=acomp",
		dataType: "html",
		data: str
		
	
	
	}).done(function(msg){
		$('#retornoAcomp').hide().html(msg).fadeIn('slow');
		$('div#acompanhamento').stop().animate({
			scrollTop:300
		}, 1500, 'easeInOutExpo');
		event.preventDefault();
	});
	
});

function campoTrabalho(cpf)
{
	//var str = $('#novoLogin').serialize();
	//alert(cpf);
	$.ajax({
		type: "POST",
		url: "server.php?p=addTrabalho",
		dataType: "html",
		data: 'cpf='+cpf
		
	
	
	}).done(function(msg){
		$('#enviarTrabalho').hide().html(msg).fadeIn('slow');
		//alert($('div#enviarTrabalho').offset().top);
		$('div#acompanhamento').stop().animate({
			scrollTop: 1200
		}, 1500, 'easeInOutExpo');
		event.preventDefault();
	});
	
}
function limparTrabalho(cpf)
{
	$('#enviarTrabalho').html('');
	updateTrabalho(cpf);
}
function uploadTrabalho()
{

	$('#FormTrabalho').on('submit',function (e)
	{
		e.preventDefault();
	});
	
	var formData = new FormData($('form#FormTrabalho')[0]);
	if($('input[type=checkbox][name=checkAutoriza]').prop("checked")==false){ 
		alert('Você não autorizou o ECEEL a usar o seu resumo.');
	}else{
		$.ajax({
			type: "POST",
			url: "uploadTrabalho.php",
			data: formData,
			//use contentType, processData for sure.
			contentType: false,
			processData: false,
			cache: false,
			beforeSend: function() {
				// $('.modal .ajax_data').prepend('<img src="' +
					// base_url +
					// '"asset/images/ajax-loader.gif" />');
				$("form#FormTrabalho button[type=submit]").html("<i class=\"fa fa-clock-o\"></i>Enviando...").prop("disabled",true);
				//$(".modal").modal("show");
			},
			success: function(msg) {
			
				if(msg == 'cpf'){
					alert('Erro no CPF!');
				}else if(msg == 'npdf'){
					alert('Erro. Não é arquivo PDF.');
				}else if(msg=='vazio'){
					alert('Campos vazios.');
				}else if(msg=='arquivo'){
					alert('Erro ao cadastrar no banco de dados.');
				}else if(msg=='Erroarquivo'){
					alert('Erro interno. Contate a ouvidoria do evento.');
					$("form#FormTrabalho button[type=submit]").html("Enviar novamente...").prop("disabled",false);
				}else if(msg=='sucesso'){
					alert('Sucesso.');
					limparTrabalho($('form#FormTrabalho input[name=cpf]').val());
				}else{
					alert('WTF?');
					$("form#FormTrabalho button[type=submit]").html("Enviar novamente...").prop("disabled",false);
				}
				$("form#FormTrabalho button[type=submit]").html("Enviar novamente...").prop("disabled",false);
			},
			error: function() {
				alert('Erro no envio. Tente novamente.');
				$("form#FormTrabalho button[type=submit]").html("Enviar novamente...").prop("disabled",false);
			}
		});
	}
}
function uploadTrabalho2(id)
{

	$('#FormTrabalho').on('submit',function (e)
	{
		e.preventDefault();
	
	});
	
	$("div#coautores :input").map(function(){
		 if( !$(this).val() ) {
			  $(this).remove();
		}  
	});
	var formData = new FormData($('form#FormTrabalho')[0]);
	formData.append('id', id);
	if($('input[type=checkbox][name=checkAutoriza]').prop("checked")==false){ 
		alert('Você não autorizou o ECEEL a usar o seu resumo.');
	}else{
		$.ajax({
			type: "POST",
			url: "uploadTrabalhoCorrig.php",
			data: formData,
			//use contentType, processData for sure.
			contentType: false,
			processData: false,
			beforeSend: function() {
				// $('.modal .ajax_data').prepend('<img src="' +
					// base_url +
					// '"asset/images/ajax-loader.gif" />');
				$("form#FormTrabalho button[type=submit]").html("<i class=\"fa fa-clock-o\"></i>Enviando...").prop("disabled",true);
				//$(".modal").modal("show");
			},
			success: function(msg) {
				//$( '#FormTrabalho' ).replaceWith($( '#FormTrabalho' ).html());
				alert(msg);
				
				limparTrabalho($('form#FormTrabalho input[name=cpf]').val());
			},
			error: function() {
			
				$( '#FormTrabalho' ).replaceWith($( '#FormTrabalho' ).html());
				alert('Erro no envio. Tente novamente.');
				$("form#FormTrabalho button[type=submit]").html("Enviar novamente...").prop("disabled",false);
			}
		});
	}
	
}
function uploadComprovante(cpf)
{

	$('#FormPagamento').on('submit',function (e)
	{
		e.preventDefault();
	});
	
	var formData = new FormData($('form#FormPagamento')[0]);
	formData.append('cpf', cpf);
	$.ajax({
		type: "POST",
		url: "uploadComprovante.php",
		data: formData,
		//use contentType, processData for sure.
		contentType: false,
		processData: false,
		beforeSend: function() {
			// $('.modal .ajax_data').prepend('<img src="' +
				// base_url +
				// '"asset/images/ajax-loader.gif" />');
			$("form#FormPagamento button[type=submit]").html("Enviando...").prop("disabled",true);
			//$(".modal").modal("show");
		},
		success: function(msg) {
		
			if(msg == 'cpf'){
				alert('Erro no CPF!');
				$("form#FormPagamento button[type=submit]").html("Enviar novamente...").prop("disabled",false);
			}else if(msg == 'naoeimg'){
				alert('Erro. Não é imagem {*.jpg, *.jpeg, *.png}.');
				$("form#FormPagamento button[type=submit]").html("Enviar novamente...").prop("disabled",false);
			}else if(msg=='vazio'){
				alert('Campo Comprovante Vazio.');
				$("form#FormPagamento button[type=submit]").html("Enviar novamente...").prop("disabled",false);
			}else if(msg=='erro'){
				alert('Erro. Faça o login novamente.');
				$("form#FormPagamento button[type=submit]").html("Erro. Faça o login novamente.").prop("disabled",true);
			}else if(msg=='naoinserido'){
				alert('Erro ao cadastrar no banco de dados.');
				$("form#FormPagamento button[type=submit]").html("Enviar novamente...").prop("disabled",false);
			}else{
				alert('Sucesso. Comprovante enviado.');
				$("form#FormPagamento button[type=submit]").html("Pronto. Comprovante Enviado.").prop("disabled",true);
				$("form#FormPagamento input").html("Pronto. Comprovante Enviado.").prop("disabled",true);
			}
		},
		error: function() {
			alert('Erro no envio. Tente novamente.');
			$("form#FormPagamento button[type=submit]").html("Enviar novamente...").prop("disabled",false);
		}
	});
}

function updateTrabalho(cpf)
{
	//var str = $('#novoLogin').serialize();
	//alert(cpf);
	$.ajax({
		type: "POST",
		url: "server.php?p=updateTrabalho",
		dataType: "html",
		data: 'cpf='+cpf
		
	
	
	}).done(function(msg){
		$('tbody#atualizaTrab').hide().html(msg).fadeIn('slow');
	});
	
}
function statusTrabalho(id)
{
	//var str = $('#novoLogin').serialize();
	//alert(cpf);
	$.ajax({
		type: "POST",
		url: "server.php?p=statusTrabalho",
		dataType: "html",
		data: 'id='+id
		
	
	
	}).done(function(msg){
		$('div#enviarTrabalho').hide().html(msg).fadeIn('slow');
		$('div#acompanhamento').stop().animate({
			scrollTop: 1200
		}, 1500, 'easeInOutExpo');
		event.preventDefault();
	});
	
}
function abrir(pag)
{

	if(pag){
		//alert(pag);
		if($('a[href=#'+pag+'][id=link]').length)
		{
			//event.preventDefault(); 
			$('a[href=#'+pag+'][id=link]').trigger('click');
		}
		window.history.pushState({url: "?p="}, "Teste" ,"?"); 
	}
	
		// });​ 
}
function addCoautor()
{
	$("div#coautores :input").map(function(){
		 if( !$(this).val() ) {
			  $(this).remove();
		}  
	});
	$('div#coautores').hide().append('<input type="text" class="form-control" name="coautor[]" id="coautor" placeholder="Digite o nome do Coautor">').fadeIn('slow');

}