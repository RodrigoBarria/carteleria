//var controllerListaClientes = "http://186.103.176.235/guardianone/lib/controller/clientes.controller.php/ListClientes";
var controllerListaClientes = "url: http://localhost/cartelería/src/controller/ControllerClientes";

$(function(){
	get_clientes();
	evento_btn_siguienteCliente();
	evento_btn_anteriorCliente();
	evento_btn_formAddCliente();
	evento_btn_addCliente();
	evento_btn_salirFormCliente();
	evento_btn_formModCliente();
	evento_btn_generaClaveCliente();
	evento_btn_generaClaveClienteMod();
});

/*Solicita todas los clientes, estas se muestran en una tabla*/
function get_clientes(){
	var nroPagina = $("#nroPagina").val();
	$.ajax({
		data: {accion:"getClientes", pagina: nroPagina},
        url: controller+'/ControllerClientes.php',
        type: 'POST',
        dataType: "json",
	  	success: function(respuesta){
	  		var tabla = "";
			var cantidadPaginas = respuesta.paginasCliente;
			delete respuesta.paginasCliente;

			$("#cantidadPagina").val(cantidadPaginas);

			$.each(respuesta, function(id, val){
				tabla += '<tr>';
				tabla += '<td>'+val.id_cliente+'</td>';
				tabla += '<td><a class="btn_clienteUsuario" data-id="'+val.id_cliente+'">'+val.nombre+'</a></td>';
				tabla += '<td>'+val.clave+'</td>';
				tabla += '<td>';
				tabla += '<a class="btn_formModCliente" data-id_cliente="'+val.id_cliente+'">';
				tabla += '<span class="glyphicon glyphicon-pencil"></span></a></td>';
			});

			if(cantidadPaginas==0){
				nroPagina=0;
			}

			$("#lbl_pagina").html(nroPagina+" de "+cantidadPaginas);
			$("#tbd_cliente").html(tabla);
			evento_btn_clienteUsuario();
	  	}
	});
}

/*Avanza una página en la tabla de cliente*/
function evento_btn_siguienteCliente(){
	$("#btn_siguienteCliente").click(function(){
		if($("#nroPagina").val()!=$("#cantidadPagina").val()){
			$("#nroPagina").val(parseInt($("#nroPagina").val()) +1);
			get_clientes();
		}
	});
}

/*Retrocede una página en la tabla de cliente*/
function evento_btn_anteriorCliente(){
	$("#btn_anteriorCliente").click(function(){
		if($("#nroPagina").val()!="1"){
			$("#nroPagina").val(parseInt($("#nroPagina").val()) -1);
			get_clientes();
		}
	});
}

/*Muestra los usuarios correspondientes al cliente seleccionado*/
function evento_btn_clienteUsuario(){
	$(".btn_clienteUsuario").click(function(){
		getUsuariosCliente($(this).data("id"));
	});
}

/*Muestra el formulario para agregar clientes*/
function evento_btn_formAddCliente(){
	$("#btn_formAddCliente").click(function(){
		$("#div_clientes").hide();
		$("#form_addCliente").show();
		get_lista_clientes();
		genera_clave_cliente("add");
		cerrar_msjErrorCliente();
	});
}

/*Llama a la función 'add_cliente' si los datos de 'form_addCliente' son validos*/
function evento_btn_addCliente(){
	$("#btn_addCliente").click(function(){
		if($("#form_addCliente")[0].checkValidity()){
			add_cliente();
		}else{
			$("#msj_errorCliente").show();
		}
	});
}

function getUsuariosCliente(idcliente){
	$.ajax({
		data: {accion:"getUsuariosCliente", idCliente: idcliente},
        url: controller+'/ControllerClientes.php',
        type: 'POST',
		dataType: "json",
	  	success: function(respuesta){
			$("#pager_cliente").hide();
			var breadcrumb = '<li>Cartelería</li>';
	    		breadcrumb+= '<li>Clientes</li>';
				breadcrumb+= '<li class="active">Usuarios</li>';
				breadcrumb+= '<p class="pull-right">';
				breadcrumb+= '<img src="webContent/img/nuevo_estilo/navegadores_compatibles.png" width="180"></p>';
			$("#brc_cliente").html(breadcrumb);
			var btn_atras = '<button type="button" class="btn btn-danger btn_atras_clientes">';
				btn_atras+= '<span class="glyphicon glyphicon-arrow-left"></span> Salir</button>';
			$("#div_btn_cliente").html(btn_atras);
			var tabla = '<table class="table table-condensed table-hover table-bordered">';
				tabla+= '<thead><tr><th>#</th><th>Login</th><th>Nombre</th><th>Perfil</th><th>Clave</th></tr></thead>';
				tabla+= '<tbody>';

				$.each(respuesta, function(id, val){
					tabla += '<tr>';
					tabla += '<td>'+val.id_usuario+'</td>';
					tabla += '<td>'+val.login_usuario+'</td>';
					tabla += '<td>'+val.nombre+' '+val.apellido+'</td>';
					tabla += '<td>'+val.perfil+'</td>';
					tabla += '<td>'+val.clave_usuario+'</td>';
				});

				tabla+= '</tbody>';
				tabla+= '<table>';
			$("#tablaCliente").html(tabla);
			evento_atras_cliente();

	  	},error: function (xhr, ajaxOptions, thrownError) {
            console.log(xhr);
            console.log(ajaxOptions);
			console.log(thrownError);
        }
	});
}

// Vuelve a la pantalla principal de clientes
function evento_atras_cliente(){
	$(".btn_atras_clientes").click(function(){
		cargar_vista_clientes();
	});
}

/*envía los datos para agregar un cliente nuevo al sistema*/
function add_cliente(){
	var datosCliente = {
		idCliente: $("#sl_addCliente").val(),
		nombre: $("#sl_addCliente option:selected").text(),
		clave: $("#txt_claveCliente").val()
	};

	$.ajax({
		data: {accion:"addCliente", datos: JSON.stringify(datosCliente)},
        url: controller+'/ControllerClientes.php',
        type: 'POST',
        beforeSend:function(){
	  		$('#modal_mensajeCliente').modal('show');
        },
	  	success: function(respuesta){

	  		var arrayRespuesta = respuesta.split("|");
	  		var mensaje = '<h4>'+arrayRespuesta[1]+'</h4>';

	  		if(arrayRespuesta[0]=="ok"){
	  			mensaje+='<p>'+arrayRespuesta[2]+'</p>';
	  			mensaje+='<p>'+arrayRespuesta[3]+'</p>';
	  			mensaje+='<p>'+arrayRespuesta[4]+'</p>';
	  		}

	  		$('#mf_mensajeCliente').show()
	  		$('#mbd_mensajeCliente').html(mensaje);
	  	}
	});
}

/*Cierra el mensaje de error al presionar 'btn_cerrarErrorCliente'*/
function cerrar_msjErrorCliente(){
	$("#btn_cerrarErrorCliente").click(function(){
		$("#msj_errorCliente").hide();
	});
}

/*Vuelve al menú de clientes*/
function evento_btn_salirFormCliente(){
	$(".btn_salirFormCliente").click(function(){
		cargar_vista_clientes();
	});
}

/*Obtiene la lista de clientes activos en G1*/
function get_lista_clientes(){
	$.ajax({
				url: controllerListaClientes,
        type: 'POST',
        dataType: "json",
	  	success: function(allClientes){
	  		var option = '<option value="">--Seleccione al cliente--</option>';

	  		$.ajax({
	  			data: {accion:"getListaClientes"},
		        url: controller+'/ControllerClientes.php',
		        type: 'POST',
		        dataType: "json",
			  	success: function(clientesCarteleria){

			  		$.each(allClientes["listaClientes"], function(idCliente, valCliente){
			  			var match=0;
			  			$.each(clientesCarteleria, function(id, val){
			  				if(valCliente.cliente==val.id_cliente){
			  					match++;
			  				}
			  			});

			  			if(match==0){
			  				option+='<option value="'+valCliente.cliente+'">'+valCliente.nombre+'</option>';
			  			}
			  		});
			  		$("#sl_addCliente").html(option)
			  	}
			});
	  	}
	});
}

/*al presionar 'btn_generaClaveCliente' llama a la función 'genera_clave_cliente' indicandole el tipo de formulario en el que se encuentra*/
function evento_btn_generaClaveCliente(){
	$("#btn_generaClaveCliente").click(function(){
		genera_clave_cliente("add");
	});
}

/*Cargar en el formulario de 'Modificar cliente' los datos correspondientes a la opción seleccionada*/
function evento_btn_formModCliente(){
	$("#tablaCliente").on('click','a.btn_formModCliente', function(){

		var id_cliente = $(this).data('id_cliente');

		$.ajax({
			data: {accion:"getUnCliente", idCliente: id_cliente},
	        url: controller+'/ControllerClientes.php',
	        type: 'POST',
	        dataType: "json",
		  	success: function(respuesta){

		  		$("#txt_idClienteMod").val(id_cliente);
				$("#lg_nombreClienteMod").text(respuesta[0].nombre);
				$("#txt_claveClienteMod").val(respuesta[0].clave);
				$("#div_clientes").hide();
				$("#form_modCliente").show();
				evento_btn_modCliente();
		  	}
		});
	});
}

/*al presionar 'btn_generaClaveClienteMod' llama a la función 'genera_clave_cliente' indicandole el tipo de formulario en el que se encuentra*/
function evento_btn_generaClaveClienteMod(){
	$("#btn_generaClaveClienteMod").click(function(){
		genera_clave_cliente("mod");
	});
}

/*Llama a la función 'updateCliente'*/
function evento_btn_modCliente(){
	$("#btn_modCliente").click(function(){
		update_cliente();
	});
}

/*Actualiza la clave de un cliente*/
function update_cliente(){

	var formCliente = {
		idCliente: $("#txt_idClienteMod").val(),
		clave: $("#txt_claveClienteMod").val()
	};

	$.ajax({
		data: {accion:"updateCliente", datos: JSON.stringify(formCliente)},
	    url: controller+'/ControllerClientes.php',
	    type: 'POST',
        beforeSend:function(){
	  		$('#modal_mensajeCliente').modal('show');
        },
		success: function(respuesta){
	  		var mensaje = '<h4>'+respuesta+'</h4>';
	  		$('#mf_mensajeCliente').show()
	  		$('#mbd_mensajeCliente').html(mensaje);
		}
	});
}

/*Solicita una clave aleatoria*/
function genera_clave_cliente(accion){
	$.ajax({
		data: {accion:"generaClaveCliente"},
        url: controller+'/ControllerClientes.php',
        type: 'POST',
	  	success: function(respuesta){
	  		if(accion=="add"){
	  			$("#txt_claveCliente").val(respuesta);
	  		}else{
	  			$("#txt_claveClienteMod").val(respuesta);
	  		}
	  	}
	});
}
