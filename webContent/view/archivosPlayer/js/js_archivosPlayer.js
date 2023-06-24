$(function(){
	if($("#view").length!=0){
		get_players();
		get_archivos();
		oculta_scroll_archivo();
		arrastra_archivo();
		evento_btn_archivoPlayer();
		evento_btn_salirArchivoPlayer();
		evento_btn_salirErrorArchivoPlayer();
		evento_btn_player();
		evento_btn_transArchivo();
	}
});

/*Obtiene todos los players*/
function get_players(){
	
	var data = {cliente:$("#idCliente").val()};

	$.ajax({
		data: JSON.stringify(data),
		//url: "http://186.103.176.235/guardianone/lib/controller/cliente_maquinas.controller.php/ListMaquinasPlayers",
        //url: "http://plataforma.g1-plus.cl/lib/controller/cliente_maquinas.controller.php/ListMaquinasPlayers",
		url: "http://localhost/cartelería/src/controller/controllerPlayers",

        type: 'POST',
        dataType: "json",
	  	success: function(respuesta){

	  		try{
	  			var players="";

		  		players+='<div class="panel panel-default">';
				players+='<div class="panel-heading">';
				players+='<h4 class="panel-title oculta-texto">';
				players+='<input type="checkbox" id="check_allPlayers"> ';
				players+='<label class="control-label"> Seleccione</label>';
				players+='</h4>';
				players+='</div>';
				players+='<div id="panelPlayer">';
				players+='<div class="panel-body">';
				players+='<ul class="list-group">';

	  			if(respuesta["listaMaquinas"]=="CONSULTA VACIA" && data.cliente=="41"){
	  				players+='<li class="list-group-item">';
					players+='<input type="checkbox" id="player5" class="check_player" data-id="5"> ';
					players+='<label for="player5"> TEST III</label></li>';
	  			}else if(respuesta["listaMaquinas"]=="CONSULTA VACIA"){
	  				players+='<li class="list-group-item">';
					players+='<label>Sin Players</label></li>';
	  			}else{
	  				$.each(respuesta["listaMaquinas"], function(id, val){
					    players+='<li class="list-group-item">';
					    players+='<input type="checkbox" id="player'+val.maquina_cliente+'" class="check_player" data-id="'+val.maquina_cliente+'"> ';
					    players+='<label for="player'+val.maquina_cliente+'"> '+val.nombre_maquina+'</label></li>';
			  		});
	  			}
		  		players+='</ul>';
				players+='</div>';
			    players+='</div>';
			    players+='</div>';

			    $("#contenedor_players").html(players);
		  		evento_check_allPlayer();
			}
			catch(err){
			    console.log(err.message);
			}
	  	}
	});
}

/*Obtiene y muestra todos los archivos*/
function get_archivos(){
	$.ajax({
		data: {accion:"getArchivos"},
        url: controller+'/ControllerArchivos.php',
        type: 'POST',
        dataType: "json",
	  	success: function(respuesta){

	  		var archivos="";
	  		var posicion=1;

	  		$.each(respuesta, function(id, val){

	  			var arrayNombre = val.nombre_archivo.split(".");

		  		archivos+='<div class="img-archivo thumbnail arrastrar">';
		  		archivos+='<div>';
		  		archivos+='<p class="posicion">'+(posicion++)+'</p>';
		  		archivos+='<img src="'+carteleria+val.ruta_preview+'" data-id="'+val.id_archivo+'" data-ext="'+arrayNombre[arrayNombre.length-1]+'" class="img-responsive img-preview">';
				archivos+='</div>';
				archivos+='<div class="caption">';
				archivos+='<h4 class="oculta-texto">';
				archivos+='<label style="display: inline !important;">'+val.nombre_archivo+'</label>';
				archivos+='</h4>';
				archivos+='<form class="form-inline">';
				archivos+='<div class="form-group">';
				
				if(arrayNombre[arrayNombre.length-1]=="mp4"){
					archivos+='<input type="checkbox" id="check_duracionArchivo'+val.id_archivo+'" class="check_duracionArchivo">';
					archivos+=' <label for="check_duracionArchivo">Duración:</label> ';
				}else{
					archivos+=' <label>Duración: </label>';
					archivos+='<input type="checkbox" style="opacity:0;"> ';
				}

				archivos+='</div>';
				archivos+='</form>';
				archivos+='<form class="form-inline">';
				archivos+='<div class="form-group">';

				if(arrayNombre[arrayNombre.length-1]=="mp4"){
					archivos+='<input type="text" class="form-control archivoDuracion check_duracionArchivo'+val.id_archivo+'" placeholder="hh" disabled="true" maxlength="2">:';
					archivos+='<input type="text" class="form-control archivoDuracion check_duracionArchivo'+val.id_archivo+'" placeholder="mm" disabled="true" maxlength="2">:';
					archivos+='<input type="text" class="form-control archivoDuracion check_duracionArchivo'+val.id_archivo+'" placeholder="ss" disabled="true" maxlength="2">';

					archivos+=' <button type="button" id="popper'+val.id_archivo+'" class="btn btn-default popper" data-toggle="popover" data-id_archivo="'+val.id_archivo+'">';
					archivos+='<span class="glyphicon glyphicon-volume-up"><span></button>';
					archivos+='<div class="oculto" id="popper-content'+val.id_archivo+'"><label class="pull-left">Volumen:</label>'; 
					archivos+=' <span class="pull-right rg_volumen'+val.id_archivo+' txt_porcentaje" id="sp_volumen'+val.id_archivo+'">50%</span>';
					archivos+='<input type="range" id="rg_volumen'+val.id_archivo+'" class="rg_volumen"></div>';
					archivos+='<input type="hidden" class="rg_volumen'+val.id_archivo+'hidden volumenPorcentaje" data-volumen="50">';

				}else{
					archivos+='<input type="text" class="form-control archivoDuracion check_duracionArchivo'+val.id_archivo+'" placeholder="hh" maxlength="2">:';
					archivos+='<input type="text" class="form-control archivoDuracion check_duracionArchivo'+val.id_archivo+'" placeholder="mm" maxlength="2">:';
					archivos+='<input type="text" class="form-control archivoDuracion check_duracionArchivo'+val.id_archivo+'" placeholder="ss" maxlength="2">';
				}

				archivos+='</div>';
				archivos+='</form>';
				archivos+='</div>';				
		        archivos+='</div>';
	  		});

	  		$("#div_archivos").html(archivos);
	  		evento_check_duracionArchivo();
	  		set_popover_volumen();
	  		setVolumenVideo();
	  		isTiempo();
	  		set_popover_volumenPrincipalPlayer();
	  		setAllVolumenPlayer();
	  	}
	});
}

/*construye el control de volumen de videos*/
function set_popover_volumen(){

	$(".popper").each(function(){

		var idArchivo = $(this).data("id_archivo");

		$('#popper'+idArchivo).popover({
	        content: $('#popper-content'+idArchivo),
	        placement: 'top',
	        html: true
	    });

		$('#popper'+idArchivo).popover('show');
	    $('#popper'+idArchivo).popover('hide');
	    $('#popper-content'+idArchivo).show();
	});

}

/*Llama a la función 'add_archivo_player' al hacer click en 'btn_archivoPlayer'*/
function evento_btn_archivoPlayer(){
	$("#btn_archivoPlayer").click(function(){
		add_archivo_player();
	});
}

/*Agrega archivos a los players*/
function add_archivo_player(){

	var objArchivoPlayer = {};
	var arrayPlayer = [];
	var arrayArchivo = [];
	var cuentaVacio=0;

	/*check player*/
	$(".check_player").each(function(){
		if($(this).is(":checked")){
			var player = {idPlayer: $(this).data("id")};
			arrayPlayer.push(player);
		}
	});

	
	/*archivos*/
	$("#div_archivoPlayer img").each(function(){

		var tiempo=formato_tiempo_archivo($(this).data("id"));
		var volVideo=0;

		if($(".rg_volumen"+$(this).data("id")+"hidden").length){
			volVideo = $(".rg_volumen"+$(this).data("id")+"hidden").data("volumen");
		}

		var archivo = {idArchivo: $(this).data("id"), duracion: tiempo, volumen: volVideo};
		tiempo=="00:00:00"? archivo.archivoCompleto=1 : archivo.archivoCompleto=0;

		if(tiempo=="00:00:00" && $(this).data("ext")!="mp4"){
			cuentaVacio++;
		}

		arrayArchivo.push(archivo);
	});

	if(arrayPlayer.length==0 || arrayArchivo.length==0){

		$("#mbd_mensajeArchivoPlayer, #mf_mensajeArchivoPlayer").hide();
		$("#mbd_mensajeErrorArchivoPlayer").html("<h4>Debe seleccionar por lo menos un player y un archivo</h4>");
		$("#mbd_mensajeErrorArchivoPlayer, #mf_mensajeErrorArchivoPlayer").show();
		$("#modal_mensajeArchivoPlayer").modal("show");

	}else{

		if(cuentaVacio==0){
			objArchivoPlayer.player = arrayPlayer;
			objArchivoPlayer.archivo = arrayArchivo;

			$.ajax({
				data: {accion:"addArchivoPlayer", datos: JSON.stringify(objArchivoPlayer)},
				url: controller+"/ControllerArchivosPlayer.php",
				type: 'POST',
				beforeSend: function(){
					$('#mbd_mensajeErrorArchivoPlayer, #mf_mensajeErrorArchivoPlayer').hide();
					$('#mbd_mensajeArchivoPlayer').show(); 
			  		$('#modal_mensajeArchivoPlayer').modal('show'); 
				},
				success: function(respuesta){
					$("#mbd_mensajeArchivoPlayer").html('<h4>'+respuesta+'</h4>');
					$("#mf_mensajeArchivoPlayer").show();
				}
			});
		}else{
			$("#mbd_mensajeArchivoPlayer, #mf_mensajeArchivoPlayer").hide();
			$("#mbd_mensajeErrorArchivoPlayer").html("<h4>Los archivos flash e imágenes deben llevar un tiempo de duración</h4>");
			$("#mbd_mensajeErrorArchivoPlayer, #mf_mensajeErrorArchivoPlayer").show();
			$("#modal_mensajeArchivoPlayer").modal("show");
		}
	}	
}

/*Formato de duración de archivo*/
function formato_tiempo_archivo(idArchivo){
	var tiempo = "";
	$(".check_duracionArchivo"+idArchivo).each(function(idTiempo, val){
		if($(this).val()!=""){
			if($(this).val().length==1){
				tiempo+=0;
			}
			if(2>idTiempo){
				tiempo+=$(this).val()+":";
			}else{
				tiempo+=$(this).val();
			}
		}else{
			if(2>idTiempo){
				tiempo+="00"+":";
			}else{
				tiempo+="00";
			}
		}
	});

	return tiempo;
}

/*Limpia form archivosPlayers*/
function evento_btn_salirArchivoPlayer(){
	$(".btn_salirArchivoPlayer").click(function(){
		location.reload();
	});
}

/*Cierra el modal de error*/
function evento_btn_salirErrorArchivoPlayer(){
	$(".btn_salirErrorArchivoPlayer").click(function(){
		$("#modal_mensajeArchivoPlayer").modal("hide");
	});
}

/*Permite arrastrar archivos en div 'div_transferencia'*/
function arrastra_archivo(){
	$("#div_archivos, #div_archivoPlayer").sortable({
    	connectWith: ".connectedSortable",
      	helper: "clone",
      	appendTo: document.body,
      	update: function(event, ui){
      		var posicion=1;
      		$(".arrastrar .posicion").each(function(){
      			$(this).text(posicion++);
      		});
      	},
    }).disableSelection();
}

/*Ocultar scroll*/
function oculta_scroll_archivo(){
	$(".scroll").bind('scroll', function(){
        // "Desactivar" el scroll horizontal
        if($(this).scrollLeft()!==0){
        	$(this).scrollLeft(0);
        }
    });
}

/*Marca o desmarca todos los checks de players*/
function evento_check_allPlayer(){
	$("#check_allPlayers").click(function(){

		if($(this).is(':checked')){
			$(".check_player").prop('checked',true);
		}else{
			$(".check_player").prop('checked',false);
		}
	});
}

/*Habilita o deshabilita el input de tiempo asociado al check*/
function evento_check_duracionArchivo(){
	$(".check_duracionArchivo").click(function(){
		var idCheckDuracionArchivo=$(this).attr("id");

		if($(this).is(':checked')){
			$("."+idCheckDuracionArchivo).prop('disabled',false);
		}else{
			$("."+idCheckDuracionArchivo).prop('disabled',true);
			$("."+idCheckDuracionArchivo).val("");
		}
	});
}

/*Captura los cambios de volumen del video*/
function setVolumenVideo(){
	$(".rg_volumen").on("change mousemove", function(){
		var idRgVolumen=$(this).attr("id");
		$("."+idRgVolumen).text($(this).val()+"%");
		$("."+idRgVolumen+"hidden").data("volumen", $(this).val());
	});
}

/*Permite ingresar sólo números a los inputs de tiempo*/
function isTiempo(){
	$(".archivoDuracion").keyup(function (){
 		this.value = (this.value + '').replace(/[^0-9]/g, '');
 		if(this.value>=60){
 			$(this).val("00");
 		}
	});
}

/*Traspasa los archivos contenidos en 'div_archivoPlayer' a 'contenedor_archivos'*/
function evento_btn_player(){
	$("#btn_tabPlayer").click(function(){
		$("#div_archivoPlayer").detach().appendTo('#contenedor_archivos');
	});
}

/*Traspasa los archivos contenidos en 'div_archivoPlayer' a 'contenedor_archivoPlayer'*/
function evento_btn_transArchivo(){
	$("#btn_tabTransArchivo").click(function(){
		$("#div_archivoPlayer").detach().appendTo('#contenedor_archivoPlayer');
	});
}

/*Control general de volumen*/
function setAllVolumenPlayer(){
	$("#rg_volumenPrincipal").on("change mousemove", function(){
		set_popover_volumen();
		var volumen= $(this).val();
		$("#sp_volumenPrincipal").text(volumen+"%");

		$(".volumenPorcentaje").each(function(){
			$(this).data("volumen", volumen);
		});

		$(".rg_volumen").each(function(){
			$(this).val(volumen);
		});

		$(".txt_porcentaje").each(function(){
			$(this).text(volumen+"%");
		});
		
	});
}

/*Construye el control principal de volumen de archivos*/
function set_popover_volumenPrincipalPlayer(){
	$('#popperPrincipal').popover({
	    content: $('#popper-content-principal'),
	    placement: 'top',
	    html: true
	});

	$('#popperPrincipal').popover('show');
	$('#popperPrincipal').popover('hide');
	$('#popper-content-principal').show();
}