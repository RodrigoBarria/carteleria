/*Muestra la sección de skins*/
function evento_btn_skins(){
	$("#tbl_players .btn_skins").click(function(){
		$("#div_players").hide();
		$("#data_miPlayer").data("id_player", $(this).data("id_player"));
		$("#div_skins").show();
		get_skins();
		evento_btn_addSkinPlayer();
		evento_btn_salirSkinPlayer();
		evento_fl_imagenFondo();
	});	
}

/*Muestra los distintos tipos de skin de un player*/
function get_skins(){
	$.ajax({
		data: {accion:"getSkinsPlayer"},
	    url: controller+"/ControllerSkins.php",
	    type: 'POST',
	    dataType: 'json',
		success: function(respuesta){

			var skin="";

			$.each(respuesta, function(id, val){

				skin+='<div class="col-sm-4"><label class="cursor" for="rd_skin'+val.id_skin+'">';
				skin+='<div class="thumbnail">';
				skin+='<img src="'+val.img+'" alt="'+val.descripcion+'" style="width:100%">';
				skin+='<div class="caption">';
				skin+='<p><input type="radio" class="rd_skin" name="rd_skin" id="rd_skin'+val.id_skin+'" data-id_skin="'+val.id_skin+'" data-banner="';

				val.descripcion.indexOf("Banner")>-1 ? skin+='1">' : skin+='0">';

				skin+='</p>';
				skin+='<label for="rd_skin'+val.id_skin+'">'+val.descripcion+'</label>';
				skin+='</div>';
				skin+='</div></label>';
				skin+='</div>';
			});

			$("#contenedor_skins").html(skin);
			evento_rd_skin();

			$.ajax({
				data: {accion:"getUnSkinPlayer", idPlayer: $("#data_miPlayer").data("id_player")},
			    url: controller+"/ControllerPlayers.php",
			    type: 'POST',
			    dataType: 'json',
				success: function(respuesta){
					if(respuesta.length!=0){
						$("#txt_banner").val(respuesta[0].banner);
						$("#rd_skin"+respuesta[0].id_skin).attr('checked', true);
						$("#color_skin").val(respuesta[0].color);

						if(respuesta[0].img_fondo!=""){
							setImgFondo(carteleria+respuesta[0].img_fondo);
						}
					}
				}
			});
		}
	});
}

/*Borra la imagen de fondo de un player*/
function evento_btn_deleteFondo(){
	$("#btn_deleteFondo").click(function(){
		$("#thn_fondo").remove();
		$("#fl_imagenFondo, #txt_fileImgFondo").val("");
	});
}

/*Muestra la opción de agregar texto del banner a la skin que corresponda*/
function evento_rd_skin(){
	$(".rd_skin").click(function(){
		if($(this).data("banner")==1){
			$("#modal_banner").modal();
		}else{
			$("#txt_banner").val("");
		}
	});
}

/*Llama a la función 'add_skin_player' al pulsar 'btn_addSkinPlayer'*/
function evento_btn_addSkinPlayer(){
	$("#btn_addSkinPlayer").click(function(){
		add_skin_player();
	});
}

/*Vuelve a la vista principal de players al presionar 'btn_salirSkinPlayer'*/
function evento_btn_salirSkinPlayer(){
	$("#btn_salirSkinPlayer").click(function(){
		cargar_vista_players();
	});
}

/*Da la funcionalidad al selector de imagenes de fondo*/
function evento_fl_imagenFondo(){
	$('.browse').click(function(){
	  	var file = $(this).parent().parent().parent().find('.file');
	  	file.trigger('click');
	});
	
	$('.file').change(function(event){
	  	$(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
	  	var file = event.target.files[0];

	  	if(file.type.match('image')){
	  		var fileReader = new FileReader();
    		fileReader.onload = function(){
    			setImgFondo(fileReader.result);
        	};
        
        	fileReader.readAsDataURL(file);
	  	}else{
	  		$('#modal_mensajePlayer').modal('show');
			$('#mbd_mensajePlayer, #mf_mensajePlayer, #mbd_mensajeErrorPlayer').hide();
			$("#mbd_mensajeErrorSkin, #mf_mensajeErrorPlayer").show();
			$("#fl_imagenFondo, #txt_fileImgFondo").val("");

	  	}
	});
}

/*Agrega una configuración de skin a un player*/
function add_skin_player(){

	var objSkinPlayer = {
		idPlayer: $("#data_miPlayer").data("id_player"),
		banner: $("#txt_banner").val(),
		color: $("#color_skin").val()
	};

	$(".rd_skin").each(function(){
		if($(this).is(':checked')){
			objSkinPlayer.idSkin = $(this).data("id_skin");
		}
	});

	var formData = new FormData();
	formData.append("accion", "addSkinPlayer");
	formData.append("datos", JSON.stringify(objSkinPlayer));

	if($("#fl_imagenFondo")[0].files[0]){
		formData.append("imgFondo", $("#fl_imagenFondo")[0].files[0]);
	}else{
		if($("#thn_fondo").length>0){
			formData.append("imgFondo", "na");
		}else{
			formData.append("imgFondo", "");
		}
	}

	$.ajax({
		url: controller+"/ControllerPlayers.php",
		type: 'POST',
		contentType: false,
		data: formData,
		processData: false,
		cache: false,
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
}

/*Muestra la imagen de fondo asignada al player*/
function setImgFondo(urlImg){
	var fondo='<div class="col-sm-12" id="thn_fondo">';
	fondo+='<a id="btn_deleteFondo" class="close">×</a>';
	fondo+='<div class="thumbnail">';
	fondo+='<img src="'+urlImg+'" style="width:100%">';
	fondo+='</div>';
	fondo+='</div>';

	$("#preview_fondo").html(fondo);
	evento_btn_deleteFondo();
}