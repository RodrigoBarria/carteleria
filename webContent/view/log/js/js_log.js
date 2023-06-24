var idCliente=0;

$(function(){
	get_lista_clientes();
	evento_btn_siguienteLog();
	evento_btn_anteriorLog();
});

/*Muestra la tabla de log*/
function get_log(){
	var nroPagina = $("#nroPagina").val();
	$.ajax({
		data: {accion:"getLogEventos", pagina: nroPagina, idCliente: idCliente},
        url: controller+'/ControllerLog.php',
        type: 'POST',
        dataType: "json",
	  	success: function(respuesta){
	  		var tabla = "";
			var cantidadPaginas = respuesta.paginasLog;
			delete respuesta.paginasLog;

			$("#cantidadPagina").val(cantidadPaginas);

			var index=(nroPagina-1)*5;
			$.each(respuesta, function(id, val){
				tabla += '<tr>';
				tabla += '<td>'+(++index)+'</td>';
				tabla += '<td>'+val.evento+'</td>';
				tabla += '<td>'+val.nombre+' '+val.apellido+'</td>';
				tabla += '<td>'+val.fecha+'</td>';
			});

			if(cantidadPaginas==0){
				nroPagina=0;
			}

			$("#lbl_pagina").html(nroPagina+" de "+cantidadPaginas);
			$("#tbd_log").html(tabla);
	  	}
	});
}

/**/
function get_lista_clientes(){
	$.ajax({
		data: {accion:"getListaClientes"},
        url: controller+'/ControllerClientes.php',
        type: 'POST',
        dataType: "json",
	  	success: function(respuesta){
	  		
	  		var option = '<option value="">--Seleccione--</option>';

	  		$.each(respuesta, function(id, val){
	  			option+='<option value="'+val.id_cliente+'">'+val.nombre+'</option>';
	  		});
			
			$("#sl_clientes").html(option);
			evento_sl_clientes();
	  	}
	});
}

/**/
function evento_sl_clientes(){
	$("#sl_clientes").change(function(){
        idCliente = $(this).val();
        $("#nroPagina").val("1")
        get_log(); 
	});
}

/*Avanza una página en la tabla de log*/
function evento_btn_siguienteLog(){
	$("#btn_siguienteLog").click(function(){
		if($("#nroPagina").val()!=$("#cantidadPagina").val()){
			$("#nroPagina").val(parseInt($("#nroPagina").val()) +1);
			get_log();
		}
	});
}

/*Retrocede una página en la tabla de log*/
function evento_btn_anteriorLog(){
	$("#btn_anteriorLog").click(function(){
		if($("#nroPagina").val()!="1"){
			$("#nroPagina").val(parseInt($("#nroPagina").val()) -1);
			get_log();
		}
	});
}