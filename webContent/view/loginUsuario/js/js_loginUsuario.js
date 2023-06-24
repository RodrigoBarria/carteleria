$(function(){
	if($("#div_loginUsuario").length!=0){
		login_usuario();
		evita_tecla_enter_usuario()
	}
});

/*Validación del login de usuario*/
function login_usuario(){
	$("#btnIngresarUsuario").click(function(){

		if($("#txtLoginUsuario").val().trim() == "" || $("#txtClaveUsuario").val().trim() == ""){
			$("#msjErrorLoginUsuario").html("Debe ingresar los datos de acceso");
			$("#txtLoginUsuario").focus();
		}else{
			var usuario = {login: $("#txtLoginUsuario").val(), clave: $("#txtClaveUsuario").val()};

			$.ajax({
				data: {accion:"validaLoginUsuario", usuario: JSON.stringify(usuario)},
				url: controller+"/ControllerLogin.php",
				type: 'POST',
				success: function(respuesta){
					if(respuesta=="ok"){
						location.reload();
					}else{
						console.log(respuesta);
						$("#msjErrorLoginUsuario").html("Clave o usuario incorrecto");
						$("#txtLoginUsuario").val("").focus();
						$("#txtClaveUsuario").val("");
					}
				}
			});
		}
		
	});
}

/*Evitar hacer submit al presionar la tecla enter*/
function evita_tecla_enter_usuario(){
	$('#txtLoginUsuario, #txtClaveUsuario').keypress(function(e) {
    	if(e.which == 13) {
        	return false;
    	}
	});
}