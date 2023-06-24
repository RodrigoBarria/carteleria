$(function(){
	get_usuarios();
	evento_btn_siguienteUsuario();
	evento_btn_anteriorUsuario();
	evento_btn_formAddUsuario();
	evento_btn_addUsuario();
	evento_btn_salirFormUsuario();
});

/*Solicita todas los usuarios, estas se muestran en una tabla*/
function get_usuarios(){
	var nroPagina = $("#nroPagina").val();
	$.ajax({
		data: {accion:"getUsuarios", pagina: nroPagina},
        url: controller+'/ControllerUsuarios.php',
        type: 'POST',
        dataType: "json",
	  	success: function(respuesta){

	  		var tabla = "";
			var cantidadPaginas = respuesta.paginasUsuario;
			delete respuesta.paginasUsuario;

			$("#cantidadPagina").val(cantidadPaginas);

			$.each(respuesta, function(id, val){
				tabla += '<tr>';
				tabla += '<td>'+val.id_usuario+'</td>';
				tabla += '<td>'+val.login_usuario+'</td>';
				tabla += '<td>'+val.nombre+' '+val.apellido+'</td>';
				tabla += '<td>'+val.perfil+'</td>';
				tabla += '<td>';
				tabla += '<a class="btn_formModUsuario" data-id_usuario="'+val.id_usuario+'">';
				tabla += '<span class="glyphicon glyphicon-pencil"></span></a></td>';
				tabla += '<td><a class="btn_borraUsuario" data-id_usuario="'+val.id_usuario+'" data-login="'+val.login_usuario+'">';
				tabla += '<span class="glyphicon glyphicon-trash text-danger"></span></a></td>';
			});

			if(cantidadPaginas==0){
				nroPagina=0;
			}

			$("#lbl_pagina").html(nroPagina+" de "+cantidadPaginas);
			$("#tbd_usuario").html(tabla);
			evento_btn_formModUsuario();
			evento_btn_modUsuario();
			delete_usuario();
	  	}
	});
}

/*Avanza una página en la tabla de usuario*/
function evento_btn_siguienteUsuario(){
	$("#btn_siguienteUsuario").click(function(){
		if($("#nroPagina").val()!=$("#cantidadPagina").val()){
			$("#nroPagina").val(parseInt($("#nroPagina").val()) +1);
			get_usuarios();
		}
	});
}

/*Retrocede una página en la tabla de usuario*/
function evento_btn_anteriorUsuario(){
	$("#btn_anteriorUsuario").click(function(){
		if($("#nroPagina").val()!="1"){
			$("#nroPagina").val(parseInt($("#nroPagina").val()) -1);
			get_usuarios();
		}
	});
}

/*Muestra el formulario para agregar usuarios*/
function evento_btn_formAddUsuario(){
	$("#btn_formAddUsuario").click(function(){
		$("#div_usuarios").hide();
		$("#form_addUsuario").show();
		cerrar_msjErrorUsuario();
		cerrar_msjErrorClaveUsuario();
	});	
}

/*Cierra el mensaje de error al presionar 'btn_cerrarErrorUsuario'*/
function cerrar_msjErrorUsuario(){
	$("#btn_cerrarErrorUsuario").click(function(){
		$("#msj_errorUsuario").hide();
	});
}

/*Cierra el mensaje de error al presionar 'btn_cerrarErrorClaveUsuario'*/
function cerrar_msjErrorClaveUsuario(){
	$("#btn_cerrarErrorClaveUsuario").click(function(){
		$("#msj_errorClaveUsuario").hide();
	});
}

/*Vuelve al menú de usuarios*/
function evento_btn_salirFormUsuario(){
	$(".btn_salirFormUsuario").click(function(){
		cargar_vista_usuario();
	});
}

/*Llama a la función 'add_usuario' si los datos de 'form_addUsuario' son validos*/
function evento_btn_addUsuario(){
	$("#btn_addUsuario").click(function(){
		if($("#form_addUsuario")[0].checkValidity()){
			if(comparaClave()){
				add_usuario();
			}else{
				$("#msj_errorClaveUsuario").show();
			}
		}else{
			$("#msj_errorUsuario").show();
		}
	});
}

/*Compara si los campos de claves son iguales*/
function comparaClave(){
	if($("#txt_claveUsuario").val() === $("#txt_confirmaClave").val()){
		return true;
	}else{
		return false;
	}
}

/*Agrega un usuario nuevo*/
function add_usuario(){
	var datosUsuario = {
		loginUsuario:$("#txt_loginUsuario").val(),
		nombreUsuario: $("#txt_nombreUsuario").val(),
		apellidoUsuario: $("#txt_apellidoUsuario").val(),
		claveUsuario: $("#txt_claveUsuario").val(),
		idPerfilUsuario: $("#sl_perfilUsuario").val()
	};

	$.ajax({
		data: {accion:"addUsuario", datos: JSON.stringify(datosUsuario)},
        url: controller+'/ControllerUsuarios.php',
        type: 'POST',
        beforeSend:function(){ 
	  		$('#modal_mensajeUsuario').modal('show');    
        },
	  	success: function(respuesta){
	  		var mensaje = '<h4>'+respuesta+'</h4>';
	  		$("#mbd_mensajeUsuario").html(mensaje);
	  		$("#mf_mensajeUsuario").show();
	  	}
	});
}

/*Muestra el formulario para modificar usuarios*/
function evento_btn_formModUsuario(){
	$(".btn_formModUsuario").click(function(){
		$("#txt_idUsuarioMod").val($(this).data("id_usuario"));
		$.ajax({
			data: {accion:"getUnUsuario", idUsuario: $(this).data("id_usuario")},
	        url: controller+'/ControllerUsuarios.php',
	        type: 'POST',
	        dataType: "json",
		  	success: function(respuesta){
		  		$("#txt_loginUsuarioMod").val(respuesta[0].login_usuario);
		  		$("#txt_nombreUsuarioMod").val(respuesta[0].nombre);
		  		$("#txt_apellidoUsuarioMod").val(respuesta[0].apellido);
		  		$("#sl_perfilUsuarioMod").val(respuesta[0].id_perfil_usuario);
		  		$("#div_usuarios").hide();
				$("#form_modUsuario").show();
				cerrar_msjErrorUsuarioMod();
				cerrar_msjErrorClaveUsuarioMod();
		  	}
		});
	});	
}

/*Cierra el mensaje de error al presionar 'btn_cerrarErrorUsuarioMod'*/
function cerrar_msjErrorUsuarioMod(){
	$("#btn_cerrarErrorUsuarioMod").click(function(){
		$("#msj_errorUsuarioMod").hide();
	});
}

/*Cierra el mensaje de error al presionar 'btn_cerrarErrorClaveUsuarioMod'*/
function cerrar_msjErrorClaveUsuarioMod(){
	$("#btn_cerrarErrorClaveUsuarioMod").click(function(){
		$("#msj_errorClaveUsuarioMod").hide();
	});
}

/*Llama a la función 'mod_usuario' si los datos de 'form_modUsuario' son validos*/
function evento_btn_modUsuario(){
	$("#btn_modUsuario").click(function(){
		if($("#form_modUsuario")[0].checkValidity()){
			if(comparaClaveMod()){
				mod_usuario();
			}else{
				$("#msj_errorClaveUsuarioMod").show();
			}
		}else{
			$("#msj_errorUsuarioMod").show();
		}
	});
}

/*Compara si los campos de claves del formulario de modificación son iguales*/
function comparaClaveMod(){
	if($("#txt_claveUsuarioMod").val() === $("#txt_confirmaClaveMod").val()){
		return true;
	}else{
		return false;
	}
}

/*Modifica un usuario*/
function mod_usuario(){
	var datosUsuario = {
		idUsuario: $("#txt_idUsuarioMod").val(),
		loginUsuario:$("#txt_loginUsuarioMod").val(),
		nombreUsuario: $("#txt_nombreUsuarioMod").val(),
		apellidoUsuario: $("#txt_apellidoUsuarioMod").val(),
		claveUsuario: $("#txt_claveUsuarioMod").val(),
		idPerfilUsuario: $("#sl_perfilUsuarioMod").val()
	};
	
	$.ajax({
		data: {accion:"updateUsuario", datos: JSON.stringify(datosUsuario)},
        url: controller+'/ControllerUsuarios.php',
        type: 'POST',
	  	beforeSend:function(){ 
	  		$('#modal_mensajeUsuario').modal('show');    
        },
	  	success: function(respuesta){
	  		var mensaje = '<h4>'+respuesta+'</h4>';
	  		$("#mbd_mensajeUsuario").html(mensaje);
	  		$("#mf_mensajeUsuario").show();
	  	}
	});
}

/*Borra un usuario*/
function delete_usuario(){
	$(".btn_borraUsuario").click(function(){
		var mensajeEliminar = confirm("¿Desea borrar al usuario " + $(this).data("login") + "?");

		if(mensajeEliminar){
			$.ajax({
				data: {accion:"deleteUsuario", idUsuario: $(this).data("id_usuario"), loginUsuario: $(this).data("login")},
		        url: controller+'/ControllerUsuarios.php',
		        type: 'POST',
			  	beforeSend:function(){ 
			  		$('#modal_mensajeUsuario').modal('show');    
		        },
			  	success: function(respuesta){
			  		var mensaje = '<h4>'+respuesta+'</h4>';
			  		$("#mbd_mensajeUsuario").html(mensaje);
			  		$("#mf_mensajeUsuario").show();
			  	}
			});
		}
	});
}