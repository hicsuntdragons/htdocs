
$(document).ready(function() {
	$('#clock').countdown('2017/04/21 00:00:00', function(event) {
		$(this).html(event.strftime('%D dias %H:%M:%S'));
	});
});

function enviarForm()
{
	
	event.preventDefault();
	var str = $('#formInscricao').serialize();
	//alert(str);
	$.ajax({
		type: "POST",
		url: "ajax/add.php",
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
		  source: 'ajax/autocomp.php',
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

function newForm()
{
	//var str = $('#formInscricao').serialize();
	//alert(str);
	$.ajax({
		type: "GET",
		url: "ajax/new.php",
		dataType: "html"
		
	
	
	}).done(function(msg){
		$('#retorno').hide().html(msg).fadeIn('slow');
		
		$('div#retorno input[type=text]').focus(function() {
			$('.help-block').html('');
			$('p#pet').html('');
		});
		$('#nome_pet').autocomplete({
			minLength: 5,
		  source: 'ajax/autocomp.php',
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
		url: "ajax/acomp.php",
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
		url: "ajax/addTrabalho.php",
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
	var formData = new FormData($('form#FormTrabalho')[0]);
	event.preventDefault();
	if($('input[type=checkbox][name=checkAutoriza]').prop("checked")==false){ 
		alert('Você não autorizou o ENEPET 2017 a usar o seu resumo.');
	}else{
		$.ajax({
			type: "POST",
			url: "ajax/uploadTrabalho.php",
			data: formData,
			//use contentType, processData for sure.
			contentType: false,
			processData: false,
			cache: false,
			beforeSend: function() {
				// $('.modal .ajax_data').prepend('<img src="' +
					// base_url +
					// '"asset/images/ajax-loader.gif" />');
				$("form#FormTrabalho button[type=submit]").html("<i class=\"fa fa-clock-o\"></i>Enviando...").prop("readonly",true);
				//$(".modal").modal("show");
			},
			success: function(msg) {
				if(msg == 'cpf'){
					alert('Erro no CPF!');
					$("form#FormTrabalho button[type=submit]").html("Enviar novamente...").prop("readonly",false);
				}else if(msg == 'npdf'){
					alert('Erro. Não é arquivo PDF.');
					$("form#FormTrabalho button[type=submit]").html("Enviar novamente...").prop("readonly",false);
				}else if(msg=='vazio'){
					alert('Campos vazios.');
					$("form#FormTrabalho button[type=submit]").html("Enviar novamente...").prop("readonly",false);
				}else if(msg=='arquivo'){
					alert('Erro ao cadastrar no banco de dados.');
					$("form#FormTrabalho button[type=submit]").html("Enviar novamente...").prop("readonly",false);
				}else{
					alert('Sucesso.');
					limparTrabalho($('form#FormTrabalho input[name=cpf]').val());
				}
			},
			error: function() {
				alert('Erro no envio. Tente novamente.');
				$("form#FormTrabalho button[type=submit]").html("Enviar novamente...").prop("readonly",false);
			}
		});
	}
	
}
function uploadTrabalho2(id)
{

	$("div#coautores :input").map(function(){
		 if( !$(this).val() ) {
			  $(this).remove();
		}  
	});
	var formData = new FormData($('form#FormTrabalho')[0]);
	formData.append('id', id);
	event.preventDefault();
	if($('input[type=checkbox][name=checkAutoriza]').prop("checked")==false){ 
		alert('Você não autorizou o ENEPET 2017 a usar o seu resumo.');
	}else{
		$.ajax({
			type: "POST",
			url: "ajax/uploadTrabalhoCorrig.php",
			data: formData,
			//use contentType, processData for sure.
			contentType: false,
			processData: false,
			beforeSend: function() {
				// $('.modal .ajax_data').prepend('<img src="' +
					// base_url +
					// '"asset/images/ajax-loader.gif" />');
				$("form#FormTrabalho button[type=submit]").html("<i class=\"fa fa-clock-o\"></i>Enviando...").prop("readonly",true);
				//$(".modal").modal("show");
			},
			success: function(msg) {
				alert(msg);
				
				limparTrabalho($('form#FormTrabalho input[name=cpf]').val());
			},
			error: function() {
				alert('Erro no envio. Tente novamente.');
				$("form#FormTrabalho button[type=submit]").html("Enviar novamente...").prop("readonly",false);
			}
		});
	}
	
}
function uploadComprovante(cpf)
{
	var formData = new FormData($('form#FormPagamento')[0]);
	formData.append('cpf', cpf);
	event.preventDefault();
	$.ajax({
		type: "POST",
		url: "ajax/uploadComprovante.php",
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
		url: "ajax/updateTrabalho.php",
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
		url: "ajax/statusTrabalho.php",
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
			event.preventDefault(); 
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