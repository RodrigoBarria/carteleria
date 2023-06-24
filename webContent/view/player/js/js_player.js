$(function(){
	get_lista_players();
	evento_btn_salirPlayerArchivo();
	evento_btn_salirErrorPlayer();
});

/*Obtiene todos los players*/
function get_lista_players(){

	var data = {cliente:$("#idCliente").val()};

	$.ajax({
		data:JSON.stringify(data),
		//url: "http://186.103.176.235/guardianone/lib/controller/cliente_maquinas.controller.php/ListMaquinasPlayers",
		url: "url: http://localhost/cartelería/src/controller/ControllerPlayers",
		type: 'POST',
		dataType: 'json',
		success: function(respuesta){
			
			try{
				var tabla = "";

				if(respuesta["listaMaquinas"]=="CONSULTA VACIA" && data.cliente=="41"){
	  				tabla += '<tr>';
					tabla += '<td>5</td>';
					tabla += '<td>TEST III</td>';
					tabla += '<td>';
					tabla += '<a class="btn_addPlayerArchivo" data-id_player="5" data-player="TEST III">';
					tabla += '<span class="glyphicon glyphicon-pencil"></span></a></td>';
					tabla += '<td><a class="btn_skins" data-id_player="5">';
					tabla += '<span class="glyphicon glyphicon-pencil"></span></a></td>';
	  			}else{
	  				$.each(respuesta["listaMaquinas"], function(id, val){
						tabla += '<tr>';
						tabla += '<td>'+val.maquina_cliente+'</td>';
						tabla += '<td>'+val.nombre_maquina+'</td>';
						tabla += '<td>';
						tabla += '<a class="btn_addPlayerArchivo" data-id_player="'+val.maquina_cliente+'" data-player="'+val.nombre_maquina+'">';
						tabla += '<span class="glyphicon glyphicon-pencil"></span></a></td>';
						tabla += '<td><a class="btn_skins" data-id_player="'+val.maquina_cliente+'">';
						tabla += '<span class="glyphicon glyphicon-pencil"></span></a></td>';
					});
	  			}

				$("#tbd_players").html(tabla);
				evento_btn_verPlayerArchivo();
				evento_btn_skins();
			}catch(err){
				console.log(err.message);
			}
		}
	});
}

/*Muestra la sección de archivos del player seleccionado*/
function evento_btn_verPlayerArchivo(){
	$("#tbl_players .btn_addPlayerArchivo").click(function(){
		$("#div_players").hide();
		$("#tl_player").text($(this).data("player")).data("id_player", $(this).data("id_player"));
		$("#div_playerArchivo").show();
		get_player_archivo($(this).data("id_player"));
		evento_btn_addPlayerArchivo();
	});	
}

/*Obtiene los archivos asociados a un player*/
function get_player_archivo(id_player){
	$.ajax({
		data: {accion:"getArchivoPorPlayer", idPlayer: id_player},
		url: controller+"/ControllerArchivosPlayer.php",
		type: 'POST',
		dataType: 'json',
		success: function(respuesta){
			$("#data_misArchivos").data("mis_archivos", respuesta);
			var archivos="";
			var posicion=1;
			$.each(respuesta, function(id, val){

				var arrayNombre = val.nombre_archivo.split(".");

			  	archivos+='<div class="img-archivo thumbnail arrastrar" data-prioridad="'+val.prioridad+'">';
			  	archivos+='<div>';
			  	archivos+='<p class="posicionAp">'+(posicion++)+'</p>';
			  	archivos+='<img src="'+carteleria+val.ruta_preview+'" class="img-responsive img-preview" data-id_archivo="'+val.id_archivo+'" data-ext="'+arrayNombre[arrayNombre.length-1]+'">';
				archivos+='</div>';
				archivos+='<div class="caption">';
				archivos+='<h4 class="oculta-texto">';
				archivos+='<label style="display: inline !important;">'+val.nombre_archivo+'</label>';
				archivos+='</h4>';
				archivos+='<form class="form-inline">';
				archivos+='<div class="form-group">';

				if(arrayNombre[arrayNombre.length-1]=="mp4"){
					if(val.duracion!="00:00:00"){
						archivos+='<input type="checkbox" id="check_duracionArchivo'+val.id_archivo+'" class="check_duracionArchivo" checked="true">';
						archivos+=' <label for="pwd">Duración:</label> ';
					}else{
						archivos+='<input type="checkbox" id="check_duracionArchivo'+val.id_archivo+'" class="check_duracionArchivo">';
						archivos+=' <label for="pwd">Duración:</label> ';
					}
				}else{
					archivos+=' <label for="pwd">Duración: </label>';
					archivos+='<input type="checkbox" style="opacity:0;"> ';
				}

				archivos+='</div>';
				archivos+='</form>';
				archivos+='<form class="form-inline">';
				archivos+='<div class="form-group">';

				if(val.duracion!="00:00:00"){
					var tiempo = val.duracion.split(":");
					archivos+='<input type="text" class="form-control archivoDuracion check_duracionArchivo'+val.id_archivo+'" placeholder="hh" maxlength="2" value="'+tiempo[0]+'">:';
					archivos+='<input type="text" class="form-control archivoDuracion check_duracionArchivo'+val.id_archivo+'" placeholder="mm" maxlength="2" value="'+tiempo[1]+'">:';
					archivos+='<input type="text" class="form-control archivoDuracion check_duracionArchivo'+val.id_archivo+'" placeholder="ss" maxlength="2" value="'+tiempo[2]+'">';
					if(arrayNombre[arrayNombre.length-1]=="mp4"){
						archivos+=' <button type="button" id="popper'+val.id_archivo+'" class="btn btn-default popper" data-toggle="popover" data-id_archivo="'+val.id_archivo+'">';
						archivos+='<span class="glyphicon glyphicon-volume-up"><span></button>';
						archivos+='<div class="oculto" id="popper-content'+val.id_archivo+'"><label class="pull-left">Volumen:</label>'; 
						archivos+=' <span class="pull-right rg_volumen'+val.id_archivo+' txt_porcentaje" id="sp_volumen'+val.id_archivo+'">'+val.volumen+'%</span>';
						archivos+='<input type="range" id="rg_volumen'+val.id_archivo+'" class="rg_volumen" value="'+val.volumen+'"></div>';
						archivos+='<input type="hidden" class="rg_volumen'+val.id_archivo+'hidden volumenPorcentaje" data-volumen="50">';
					}
				}else{
					archivos+='<input type="text" class="form-control archivoDuracion check_duracionArchivo'+val.id_archivo+'" placeholder="hh" disabled="true" maxlength="2">:';
					archivos+='<input type="text" class="form-control archivoDuracion check_duracionArchivo'+val.id_archivo+'" placeholder="mm" disabled="true" maxlength="2">:';
					archivos+='<input type="text" class="form-control archivoDuracion check_duracionArchivo'+val.id_archivo+'" placeholder="ss" disabled="true" maxlength="2">';
					if(arrayNombre[arrayNombre.length-1]=="mp4"){
						archivos+=' <button type="button" id="popper'+val.id_archivo+'" class="btn btn-default popper" data-toggle="popover" data-id_archivo="'+val.id_archivo+'">';
						archivos+='<span class="glyphicon glyphicon-volume-up"><span></button>';
						archivos+='<div class="oculto" id="popper-content'+val.id_archivo+'"><label class="pull-left">Volumen:</label>'; 
						archivos+=' <span class="pull-right rg_volumen'+val.id_archivo+' txt_porcentaje" id="sp_volumen'+val.id_archivo+'">'+val.volumen+'%</span>';
						archivos+='<input type="range" id="rg_volumen'+val.id_archivo+'" class="rg_volumen" value="'+val.volumen+'"></div>';
						archivos+='<input type="hidden" class="rg_volumen'+val.id_archivo+'hidden volumenPorcentaje" data-volumen="50">';
					}
				}

				archivos+='</div>';
				archivos+='</form>';
				archivos+='</div>';				
			    archivos+='</div>';
		  	});

			$("#div_miPlayerArchivo").html(archivos);
			get_archivos();
		}
	});
}

/*Obtiene todos los archivos no asociados al player seleccionado*/
function get_archivos(){
	var arrayArchivo = [];
	$("#div_miPlayerArchivo img").each(function(){
		arrayArchivo.push($(this).data("id_archivo"));
	});

	if(arrayArchivo.length==0){
		arrayArchivo.push(0);
	}

	$.ajax({
		data: {accion:"getArchivoNoPlayer", archivosPlayer: arrayArchivo},
        url: controller+'/ControllerArchivosPlayer.php',
        type: 'POST',
        dataType: "json",
	  	success: function(respuesta){
	  		var posicion=1;
	  		var archivos="";

	  		$.each(respuesta, function(id, val){

	  			var arrayNombre = val.nombre_archivo.split(".");

		  		archivos+='<div class="img-archivo arrastrar thumbnail">';
		  		archivos+='<div>';
		  		archivos+='<p class="posicionAp">'+(posicion++)+'</p>';
		  		archivos+='<img src="'+carteleria+val.ruta_preview+'" data-id_archivo="'+val.id_archivo+'" ';
		  		archivos+='data-id_player_archivo="'+0+'" data-ext="'+arrayNombre[arrayNombre.length-1]+'" class="img-responsive img-preview">';
				archivos+='</div>';
				archivos+='<div class="caption">';
				archivos+='<h4 class="oculta-texto">';
				archivos+='<label style="display: inline !important;">'+val.nombre_archivo+'</label>';
				archivos+='</h4>';
				archivos+='<form class="form-inline">';
				archivos+='<div class="form-group">';

				if(arrayNombre[arrayNombre.length-1]=="mp4"){
					archivos+='<input type="checkbox" id="check_duracionArchivo'+val.id_archivo+'" class="check_duracionArchivo">';
					archivos+=' <label for="pwd">Duración:</label> ';
				}else{
					archivos+=' <label for="pwd">Duración: </label>';
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

	  		$("#div_allArchivos").html(archivos);
	  		evento_check_duracionArchivo();
	  		set_popover_volumen();
	  		setVolumenVideo();
	  		isTiempo();
			arrastra_PlayerArchivo();
			set_popover_volumenPrincipal();
			setAllVolumen();
	  	}
	});
}


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

/*Permite arrastrar archivos entre '#div_miPlayerArchivo' y '#div_allArchivos'*/
function arrastra_PlayerArchivo(){
	$("#div_miPlayerArchivo, #div_allArchivos").sortable({
      connectWith: ".connectedSortable",
      helper: "clone",
      appendTo: document.body,
      update: function(event, ui){
      	var posicion=1;
      	$(".arrastrar .posicionAp").each(function(){
      		$(this).text(posicion++);
      	});
      },
    }).disableSelection();
}

/*Llama a la función 'add_player_archivo' al hacer click en 'btn_addPlayerArchivo'*/
function evento_btn_addPlayerArchivo(){
	$("#btn_addPlayerArchivo").click(function(){
		add_player_archivo();
	});
}

/*Agrega archivos a los players*/
function add_player_archivo(){
	var objPlayerArchivo = {};
	var arrayPlayer = [{idPlayer: $("#tl_player").data("id_player")}];
	var arrayArchivo = [];
	var cuentaVacio=0;

	/*Archivos*/
	$("#div_miPlayerArchivo img").each(function(){

		var tiempo=formato_tiempo_archivo("div_miPlayerArchivo", $(this).data("id_archivo"));
		var volVideo=0;

		if($(".rg_volumen"+$(this).data("id_archivo")+"hidden").length){
			volVideo = $(".rg_volumen"+$(this).data("id_archivo")+"hidden").data("volumen");
		}

		var archivo = {idArchivo: $(this).data("id_archivo"), duracion: tiempo, volumen: volVideo};
		tiempo=="00:00:00"? archivo.archivoCompleto=1 : archivo.archivoCompleto=0;

		if(tiempo=="00:00:00" && $(this).data("ext")!="mp4"){
			cuentaVacio++;
		}

		arrayArchivo.push(archivo);
	});

	if(cuentaVacio==0){
		objPlayerArchivo.player = arrayPlayer;
		objPlayerArchivo.archivo = arrayArchivo;

		$.ajax({
			data: {accion:"addUnArchivoPlayer", datos: JSON.stringify(objPlayerArchivo)},
			url: controller+"/ControllerArchivosPlayer.php",
			type: 'POST',
			beforeSend:function(){
				$('#mbd_mensajeErrorPlayer, #mf_mensajeErrorPlayer, #mbd_mensajeErrorSkin').hide();
			  	$('#modal_mensajePlayer').modal('show');
			  	$('#mbd_mensajePlayer').show();   
		    },
			success: function(respuesta){
				var mensaje = '<h4>'+respuesta+'</h4>';
				$("#mbd_mensajePlayer").html(mensaje);
				$("#mf_mensajePlayer").show();
			}
		});
	}else{
		$('#modal_mensajePlayer').modal('show');
		$('#mbd_mensajePlayer, #mf_mensajePlayer, #mbd_mensajeErrorSkin').hide();
		$("#mbd_mensajeErrorPlayer, #mf_mensajeErrorPlayer").show();
	}
}

/*Formato de duración de archivo*/
function formato_tiempo_archivo(contenedor, idArchivo){
	var tiempo = "";
	$("#"+contenedor+ " .check_duracionArchivo"+idArchivo).each(function(idTiempo, val){
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

/*Vuelve al menú principal de players*/
function evento_btn_salirPlayerArchivo(){
	$(".btn_salirPlayerArchivo").click(function(){
		cargar_vista_players();
	});
}

/*Vuelve al menú principal de players*/
function evento_btn_salirErrorPlayer(){
	$(".btn_salirErrorPlayer").click(function(){
		$('#modal_mensajePlayer').modal('hide'); 
	});
}

/*Control general de volumen*/
function setAllVolumen(){
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

function set_popover_volumenPrincipal(){
	$('#popperPrincipal').popover({
	    content: $('#popper-content-principal'),
	    placement: 'top',
	    html: true
	});

	$('#popperPrincipal').popover('show');
	$('#popperPrincipal').popover('hide');
	$('#popper-content-principal').show();
}