





function inscritosPorEstado()
{
	//var str = $('#formInscricao').serialize();
	//alert(str);
	$.ajax({
		type: "GET",
		url: "ajax/server.php?p=porEstado",
		dataType: "json"
		
	
	
	}).done(function(data){
		
		$.plot($("#flot-por-estado"), data, {
			series: {
				pie: {
					show: true
				}
			},
			grid: {
				hoverable: true
			},
			legend: {
				show: false
			},
			tooltip: true,
			tooltipOpts: {
				content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
				shifts: {
					x: 20,
					y: 0
				},
				defaultTheme: false
			}
		});
	});
	
}
function barraAlojamento()
{
	//var str = $('#formInscricao').serialize();
	//alert(str);
	$.ajax({
		type: "GET",
		url: "ajax/server.php?p=porAlojamento",
		dataType: "json"
		
	
	
	}).done(function(dado){
		
		Morris.Bar({
			element: 'morris-alojamento',
			data: dado,
			xkey: 'y',
			ykeys: ['a', 'b', 'c'],
			labels: ['Não pago', 'Enviado', 'Pago'],
			hideHover: 'auto',
			resize: true
		});
	});
	
}
function barraEstado()
{
	//var str = $('#formInscricao').serialize();
	//alert(str);
	$.ajax({
		type: "GET",
		url: "ajax/server.php?p=trabPorEstado",
		dataType: "json"
		
	
	
	}).done(function(dado){
		
		Morris.Bar({
			element: 'morris-trabalhos-estado',
			data: dado,
			xkey: 'y',
			ykeys: ['a'],
			labels: ['Envio'],
			hideHover: 'auto',
			resize: true
		});
	});
	
}
function trabalhoArea()
{
	//var str = $('#formInscricao').serialize();
	//alert(str);
	$.ajax({
		type: "GET",
		url: "ajax/server.php?p=area",
		dataType: "json"
		
	
	
	}).done(function(data){
		
		$.plot($("#flot-area-conhecimento"), data, {
			series: {
				pie: {
					show: true
				}
			},
			grid: {
				hoverable: true
			},
			legend: {
				show: false
			},
			tooltip: true,
			tooltipOpts: {
				content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
				shifts: {
					x: 20,
					y: 0
				},
				defaultTheme: false
			}
		});
	});
	
}
function pagamentos()
{
	//var str = $('#formInscricao').serialize();
	//alert(str);
	$.ajax({
		type: "GET",
		url: "ajax/server.php?p=pag",
		dataType: "json"
		
	
	
	}).done(function(data){
		
		$.plot($("#flot-pagamentos"), data, {
			series: {
				pie: {
					show: true
				}
			},
			grid: {
				hoverable: true
			},
			legend: {
				show: false
			},
			tooltip: true,
			tooltipOpts: {
				content: "%p.0%, %s", // show percentages, rounding to 2 decimal places
				shifts: {
					x: 20,
					y: 0
				},
				defaultTheme: false
			}
		});
	});
	
}

function numeros()
{
	//var str = $('#formInscricao').serialize();
	//alert(str);
	$.ajax({
		type: "GET",
		url: "ajax/server.php?p=numeros",
		dataType: "json"
		
	
	
	}).done(function(data){
		$('#num_inscricoes').hide().html(data['num_inscricoes']).fadeIn('slow');
		$('#num_pagamentos').hide().html(data['num_pagamentos']).fadeIn('slow');
		$('#num_submissoes').hide().html(data['num_submissoes']).fadeIn('slow');
		$('#confirmados').hide().html(data['confirmados']).fadeIn('slow');
	});
	
}
