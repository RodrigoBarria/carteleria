/*Ruta post*/
var controller = "src/controller";
var carteleria = "src";

/*Formato del calendario en español*/
$.datepicker.regional['es'] = {
 	closeText: 'Cerrar',
	prevText: '< Ant',
	nextText: 'Sig >',
	currentText: 'Hoy',
	monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
	monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
	dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
	dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
	dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
	weekHeader: 'Sm',
	dateFormat: 'dd-mm-yy',
	firstDay: 1,
	isRTL: false,
	showMonthAfterYear: false,
	yearSuffix: ''
};
$.datepicker.setDefaults($.datepicker.regional['es']);

$(function(){
	eventos_btn_menu();
	volver_inicio();
	setInterval(hora, 1000);
});

/*Acción de los botones de la barra de navegación*/
function eventos_btn_menu(){

	$('#btn_player').on("click", function(e){
		 cargar_vista_players();
		if($(".navbar-toggle").css("display")!="none"){
			$(".navbar-toggle").click();
		}
	});

	$('#btn_archivo').on("click", function(e){
		 cargar_vista_archivos();
		if($(".navbar-toggle").css("display")!="none"){
			$(".navbar-toggle").click();
		}
	});

	$('#btn_clientes').on("click", function(e){
		 cargar_vista_clientes();
		if($(".navbar-toggle").css("display")!="none"){
			$(".navbar-toggle").click();
		}
	});

	$('#btn_usuario').on("click", function(e){
		cargar_vista_usuario();
		if($(".navbar-toggle").css("display")!="none"){
			$(".navbar-toggle").click();
		}
	});

	$('#btn_ayuda').on("click", function(e){
		cargar_vista_ayuda();
		if($(".navbar-toggle").css("display")!="none"){
			$(".navbar-toggle").click();
		}
	});

	$('#btn_log').on("click", function(e){
		cargar_vista_log();
		if($(".navbar-toggle").css("display")!="none"){
			$(".navbar-toggle").click();
		}
	});
}

/*Carga desde un html externo la vista de players*/
function cargar_vista_players(){
	$.ajax({
        url: 'webContent/view/player/player.php',
        type: 'POST',
		async: true,
		dataType: "html",
	  	success:function(respuesta){
	  		$('#view').html(respuesta);
			$('html, body').animate({scrollTop:0},0);
			$('body').css('overflow','scroll');
	  	}
	});
}

/*Carga desde un html externo la vista de archivos*/
function cargar_vista_archivos(){
	$.ajax({
        url: 'webContent/view/archivos/archivos.php',
        type: 'POST',
		async: true,
		dataType: "html",
	  	success:function(respuesta){
	  		$('#view').html(respuesta);
			$('html, body').animate({scrollTop:0},0);
			$('body').css('overflow','scroll');
	  	}
	});
}

/*Carga desde un html externo la vista de clientes*/
function cargar_vista_clientes(){
	$.ajax({
        url: 'webContent/view/cliente/cliente.php',
        type: 'POST',
		async: true,
		dataType: "html",
	  	success:function(respuesta){
	  		$('#view').html(respuesta);
			$('html, body').animate({scrollTop:0},0);
			$('body').css('overflow','scroll');
	  	}
	});
}

/*Carga desde un html externo la vista de ayuda*/
function cargar_vista_ayuda(){
	$.ajax({
		url: 'webContent/view/ayuda/ayuda.php',
		type: 'POST',
		async: true,
		dataType: 'html',
		success:function(respuesta){
			$("#view").html(respuesta);
			$('html, body').animate({scrollTop:0},0);
			$('body').css('overflow', 'scroll');
		}
	});
}

/*Carga desde un html externo la vista de usuario*/
function cargar_vista_usuario(){
	$.ajax({
		url: 'webContent/view/usuarios/usuarios.php',
		type: 'POST',
		async: true,
		dataType: 'html',
		success:function(respuesta){
			$("#view").html(respuesta);
			$('html, body').animate({scrollTop:0},0);
			$('body').css('overflow', 'scroll');
		}
	});
}

/*Carga desde un html externo la vista de log*/
function cargar_vista_log(){
	$.ajax({
		url: 'webContent/view/log/log.php',
		type: 'POST',
		async: true,
		dataType: 'html',
		success:function(respuesta){
			$("#view").html(respuesta);
			$('html, body').animate({scrollTop:0},0);
			$('body').css('overflow', 'scroll');
		}
	});
}

/*Muestra en el sitio la hora actual*/
function hora(){
	var fecha = $.datepicker.formatDate("dd-mm-yy", new Date($.now()));
	var hora = new Date($.now()).toTimeString().split(' ')[0];
	$(".sp_tiempo").html(hora + " / " + fecha);
}

/*Permite volver al principio de la página presionando el botón 'scrol-to-top'*/
function volver_inicio(){
	$(window).scroll(function(){
		if($(this).scrollTop()>100){
        	$('.scroll-to-top').fadeIn();
     	}else{
        	$('.scroll-to-top').fadeOut();
      	}
    });

	// al hacer click, animar el scroll hacia arriba
    $('.scroll-to-top').click(function(e){
      	e.preventDefault();
      	$('html, body').animate({scrollTop:0},800);
    });
}
