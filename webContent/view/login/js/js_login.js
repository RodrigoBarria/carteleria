$(function(){
	login_cliente();
	evita_tecla_enter();
	logout();
});

/*Validación del login de usuario*/
function login_cliente(){
	$("#btnIngresarCliente").click(function(){

		if($("#txtClave").val().trim() == ""){
			$("#msjErrorLogin").html("Debe ingresar la clave");
			$("#txtClave").focus();
		}else{
			var cliente = {clave: $("#txtClave").val()};

			$.ajax({
				data: {accion:"validaLogin", cliente: JSON.stringify(cliente)},
				url: controller+"/ControllerLogin.php",
				type: 'POST',
				success: function(respuesta){
					if(respuesta=="ok"){
						location.reload();
					}else{
						console.log(respuesta);
						$("#msjErrorLogin").html("Clave o usuario incorrecto");
						$("#txtClave").val("").focus();
					}
				}
			});
		}
		
	});
}

/*Solicita cerrar la sesión actual*/
function logout(){
	$(".btn_logout").click(function(){
		$.ajax({
			data: {accion:"logout"},
			url: controller+"/ControllerLogin.php",
			type: 'POST',
			success: function(respuesta){
				location.reload();
			}
		});
	});	
}

/*Evitar hacer submit al presionar la tecla enter*/
function evita_tecla_enter(){
	$('#txtClave').keypress(function(e) {
    	if(e.which == 13) {
        	return false;
    	}
	});
}