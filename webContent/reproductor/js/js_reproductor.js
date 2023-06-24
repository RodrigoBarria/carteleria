$(function(){
	configPlayer();
});

var rutaCarteleria="../../src";

/*Consulta la configuración de skin de un player*/
function configPlayer(){

	var data = {idCliente:getParameterByName('idcliente'), idPlayer:getParameterByName('idmaquina')};
	$.ajax({
		data: {accion:"getConfigPlayer", datos: JSON.stringify(data)},
		url: rutaCarteleria+"/controller/ControllerReproductor.php",
		type: 'POST',
		dataType: 'json',
		success: function(respuesta){
			if(respuesta.length!=0){
				
				if(respuesta[0].id_skin==3){
					$("#div_player").hide();
				}else{
					if(respuesta[0].id_skin!=3){
						if(respuesta[0].banner!=""){
							$(".banner").html('<marquee>'+respuesta[0].banner+'</marquee>').show();
						}else{
							$(".banner").hide();
						}
						ver_archivos();
					}
				}				
			}
		}
	});
}

/*Consulta los archivos asociados a un player*/
function ver_archivos(){

	//var anchoPantalla = getParameterByName('ancho');
	//var altoPantalla = getParameterByName('alto');

	var data = {idCliente:getParameterByName('idcliente'), idPlayer:getParameterByName('idmaquina')};

	$.ajax({
		data: {accion:"getArchivosReproductor", datos: JSON.stringify(data)},
		url: rutaCarteleria+"/controller/ControllerReproductor.php",
		type: 'POST',
		dataType: 'json',
		success: function(respuesta){
			var reproducir=0; //Usado para controlar el archivo a reproducir
			var milisegundos=0; //Tiempo de duración del archivo
			reproductor();

			/*Realiza la reproducción de los archivos*/
			function reproductor(){
				milisegundos=0;
				var arrayNombre = respuesta[reproducir].nombre_archivo.split(".");
				var arrayDuracion = respuesta[reproducir].duracion.split(":");
				
				/*Si el archivo es una imágen*/
				if(arrayNombre[arrayNombre.length-1]=="png" || arrayNombre[arrayNombre.length-1]=="jpg"){
					var imgPlayer = document.getElementById('imgPlayer');
					imgPlayer.src = rutaCarteleria+respuesta[reproducir].ruta_archivo;
					milisegundos = (((parseInt(arrayDuracion[0])*3600)*1000)+((parseInt(arrayDuracion[1])*60)*1000)+(parseInt(arrayDuracion[2]))*1000);
					$("#imgPlayer").show();
					
					/*Pasar al siguiente archivo*/
					setTimeout(function(){
						reproducir==(respuesta.length-1) ? reproducir=0 : reproducir++;
						$("#imgPlayer").hide();
						imgPlayer.src="";
	    				setTimeout(reproductor,1000);
					}, milisegundos);

				/*Si el archivo es un video*/		
				}else if(arrayNombre[arrayNombre.length-1]=="mp4"){
					var videoPlayer = document.getElementById('videoPlayer');
					videoPlayer.src = rutaCarteleria+respuesta[reproducir].ruta_archivo;
					videoPlayer.volume= parseInt(respuesta[reproducir].volumen)/100;

					$("#videoPlayer").show();

					/*Muestra el video el tiempo establecido por el usuario*/
					if(respuesta[reproducir].duracion!="00:00:00"){
						milisegundos = (((parseInt(arrayDuracion[0])*3600)*1000)+((parseInt(arrayDuracion[1])*60)*1000)+(parseInt(arrayDuracion[2]))*1000);
						setTimeout(function(){
							reproducir==(respuesta.length-1) ? reproducir=0 : reproducir++;
							$("#videoPlayer").hide();
							videoPlayer.src="";
							setTimeout(reproductor,1000);

						}, milisegundos);
					/*Muestra el video hasta que termine*/	
					}else{
						videoPlayer.onended = function(){
							reproducir==(respuesta.length-1) ? reproducir=0 : reproducir++;
							videoPlayer.src="";
							$("#videoPlayer").hide();
							console.log(reproducir);
							setTimeout(reproductor,1000);
						}
					}
				/*Si el archivo es un flash*/	
				}else if((arrayNombre[arrayNombre.length-1]=="swf")){
					milisegundos = (((parseInt(arrayDuracion[0])*3600)*1000)+((parseInt(arrayDuracion[1])*60)*1000)+(parseInt(arrayDuracion[2]))*1000);
					$("#videoPlayer, #imgPlayer").hide();
					var flash='<embed id="flashPlayer" src="'+rutaCarteleria+respuesta[reproducir].ruta_archivo+'" '
					flash+='type="application/x-shockwave-flash" width="100%" height="720"></embed>';
					$("#flashPlayer").html(flash);
					$("#flashPlayer").show();

					/*Pasar al siguiente archivo*/
					setTimeout(function(){
						reproducir==(respuesta.length-1) ? reproducir=0 : reproducir++;
						$("#flashPlayer").html("").hide();
	    				setTimeout(reproductor,1000);
					}, milisegundos);
				}
			}
		}
	});
}

/*Obtiene un parametro de la url por su nombre*/
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}