var urlPreview="";
var rutaImg="webContent/img/";
var sizeTotal=21474836480;

$(function(){
    get_size_upload();
    get_all_archivos();
	evento_btn_addArchivo();
	evento_btn_salirArchivo();
	preview_archivo();
});

/*Obtiene todos los archivos y los dibuja en un contenedor*/
function get_all_archivos(){
  $.ajax({
    data: {accion:"getArchivos"},
        url: controller+'/ControllerArchivos.php',
        type: 'POST',
        dataType: "json",
      success: function(respuesta){

        var archivos="";

        $.each(respuesta, function(id, val){

            var arrayNombre = val.nombre_archivo.split(".");
            var ext = arrayNombre.pop();
            var nombreArchivo = "";

            $.each(arrayNombre, function(id, value){
                nombreArchivo+=value;
            });

            archivos+='<div class="img-archivo thumbnail">';
            archivos+='<a class="btn_deleteArchivo close" data-id="'+val.id_archivo+'" data-archivo="'+val.nombre_archivo+'">×</a>';
            archivos+='<div>';
            archivos+='<img src="'+carteleria+val.ruta_preview+'" class="img-responsive img-preview" title="'+val.nombre_archivo+'">';
            archivos+='</div>';
            archivos+='<div class="caption nombrePrincipal'+val.id_archivo+'"><div class="col-sm-9 lbl-nombre-archivo">';
            archivos+='<h4 class="oculta-texto h4-nombre-archivo">';
            archivos+='<label style="display: inline !important;">'+val.nombre_archivo+'</label>';
            archivos+='</h4></div><div class="col-sm-3 lbl-nombre-archivo">';
            archivos+='<h4><button type="button" class="btn btn-primary btn-xs btn_modNombreArchivo pull-right" data-id="'+val.id_archivo+'"><span class="glyphicon glyphicon-pencil"></span></button></h4> ';
            archivos+='</div></div>';
            archivos+='<div class="caption oculto editaNombre'+val.id_archivo+'">';
            archivos+='<form class="form-inline">';
            archivos+='<input type="text" class="form-control nombreArchivo" value="'+nombreArchivo+'" data-ext="'+ext+'" maxlength="50"> ';
            archivos+='<button type="button" class="btn btn-success btn-xs btn_guardaNombreArchivo" data-id="'+val.id_archivo+'" data-nombre="'+nombreArchivo+'">';
            archivos+='<span class="glyphicon glyphicon-ok"></span></button> ';
            archivos+='<button type="button" class="btn btn-danger btn-xs btn_cancelaModNombreArchivo" data-id="'+val.id_archivo+'" data-nombre="'+nombreArchivo+'">';
            archivos+='<span class="glyphicon glyphicon-remove"></span></button></form>';
            archivos+='</div>';
            archivos+='</div>';
        });

        $("#contenedor_archivos").html(archivos);
        evento_btn_deleteArchivo();
        evento_btn_modNombreArchivo();
        evento_btn_guardaNombreArchivo();
        evento_btn_cancelaModNombreArchivo();
      }
  });
}

/*Muestra input para cambiar nombre del archivo*/
function evento_btn_modNombreArchivo(){
    $(".btn_modNombreArchivo").click(function(){
        $(".nombrePrincipal"+$(this).data("id")).hide();
        $(".editaNombre"+$(this).data("id")).show();
    });
}

/*Guarda nuevo nombre de archivo*/
function evento_btn_guardaNombreArchivo(){
    $(".btn_guardaNombreArchivo").click(function(){

        var arrayDatos = {
            idArchivo: $(this).data("id"),
            nombreAntiguo: $(this).data("nombre"),
            nombreNuevo: $(".editaNombre"+$(this).data("id")+" .nombreArchivo").val(),
            ext: "."+$(".editaNombre"+$(this).data("id")+" .nombreArchivo").data("ext")
        };

        $.ajax({
            data: {accion: "renombrarArchivo", datos: JSON.stringify(arrayDatos)},
            url: controller+"/ControllerArchivos.php",
            type: 'POST',
            beforeSend:function(){ 
                $('#modal_msjUsuario').modal('show');    
            },
            success: function(respuesta){
                var mensajeUsuario = "<h4>"+respuesta+"</h4>";
                $('#mf_msjUsuario').show()
                $('#mbd_msjUsuario').html(mensajeUsuario);
            }
        });
    });
}

/*Oculta input para cambiar nombre del archivo*/
function evento_btn_cancelaModNombreArchivo(){
    $(".btn_cancelaModNombreArchivo").click(function(){
        $(".editaNombre"+$(this).data("id")).hide();
        $(".editaNombre"+$(this).data("id")+" .nombreArchivo").val($(this).data("nombre"));
        $(".nombrePrincipal"+$(this).data("id")).show();
    });
}


/*Obtiene el tamaño de la carpeta 'upload' del cliente que este en sesión*/
function get_size_upload(){
    $.ajax({
        data: {accion:"getSizeUpload"},
        url: controller+'/ControllerArchivos.php',
        type: 'POST',
        success: function(respuesta){
            $('body').data('sizeCliente',respuesta);
            var sizePorcentaje=Math.round(((100*respuesta)/sizeTotal)*100)/100;
            var size = size_archivo(respuesta);
            $('#progress_size').css('width', sizePorcentaje+'%');
            $("#detalle_disco").html(size+" ocupados de 20 GB");
        }
    });
}

/*Vista previa del archivo a subir*/
function preview_archivo(){
    $('#fl_archivo').change(function(event){
		$("#preview, #formato, #tamanio").empty();
		var file = event.target.files[0];
        var img = document.createElement('img');

        if((parseInt($('body').data('sizeCliente'))+parseInt(file.size))<sizeTotal){
            if((parseInt(file.size)/10000)/100<=1000){

                if(file.type.match('image')){
                    var fileReader = new FileReader();
                    fileReader.onload = function(){
                        img.src = fileReader.result;
                        urlPreview=fileReader.result;
                        document.getElementById('preview').appendChild(img);
                    };
                    fileReader.readAsDataURL(file);
                }else if(file.type.match('mp4')){
                    $('#btn_addArchivo').attr("disabled", true);
                    img.src = rutaImg+"cargando.gif";
                    document.getElementById('preview').appendChild(img);
                    $("#preview").show();

                    var video = $('#preview_video');
                    video[0].src = URL.createObjectURL(file)+"#t=0,2";
                    URL.revokeObjectURL(video[0].src)
			
                    setTimeout(function(){
                        var preview = document.getElementById('preview_video');
                        var canvas = document.createElement('canvas');
                        canvas.getContext("2d").drawImage(preview, 0, 0, 320, 176);
                        urlPreview=canvas.toDataURL();
                        img.src=urlPreview;
                        $("#preview").empty();
                        document.getElementById('preview').appendChild(img);
                        preview.pause();
                        preview.src="";
                         $('#btn_addArchivo').attr("disabled", false);
                    }, 2000);

                }else if(file.type.match('x-shockwave-flash')){
                    img.src = rutaImg+"swf_preview.png";
                    toDataURL(img.src, function(dataUrl){urlPreview=dataUrl});
                    document.getElementById('preview').appendChild(img);
                }else{
                    $('#modal_msjUsuario').modal('show');
                    $('#mf_msjUsuario').show()
                    $('#mbd_msjUsuario').html("<h4>Tipo de archivo no admitido</h4>");
                }
                
                var formato = file.type.split("/");
                $("#formato").text("Formato: "+formato[1]);
                $("#tamanio").text("Tamaño: "+size_archivo(file.size));
                $("#preview").show();
                
            }else{
                alert("El tamaño del archivo sobrepasa el límite permitido");
                $("#fl_archivo").val("");
            }
        }else{
            alert("No hay espacio suficiente para subir el archivo");
        }
        
	});
}

/*Llama a la función 'addArchivo' al hacer click en 'btn_addArchivo'*/
function evento_btn_addArchivo(){
	$("#btn_addArchivo").click(function(){
        if($('#fl_archivo').get(0).files.length != 0){
            addArchivo();
        }else{
            alert("Debe seleccionar un archivo");
        }
	});
}

/*Envia los datos del archivo contenido en 'fl_archivo' para ser guardados*/
function addArchivo(){

  	var archivo = $('#fl_archivo')[0];
    var formData = new FormData();
    formData.append("archivo", archivo.files[0]);
    formData.append("accion", "uploadArchivo");
    formData.append("preview", urlPreview);
    
    $.ajax({
        url: controller+'/ControllerArchivos.php',
        type: 'POST',
		contentType: false,
	  	data: formData,
	  	processData: false,
	  	cache: false,
        xhr: function(){
            var xhr = $.ajaxSettings.xhr();
            xhr.upload.addEventListener('progress', function(ev){
                if(ev.lengthComputable){
                    var progreso = (100*ev.loaded)/ev.total;
                    var arrayprogreso = progreso.toString().split(".");
                    $('#barra_progreso').html((arrayprogreso[0]-1)+"%").css('width', (arrayprogreso[0]-1)+'%');
                }
            }, false);
            return xhr;
        },
	  	beforeSend:function(){
	  		$('#modal_guardaArchivo').modal('show');    
        },
	  	success:function(respuesta){
	  		var mensajeUsuario = "<h4>"+respuesta+"</h4>";
	  		$('#mf_guardaArchivo').show()
	  		$('#mbd_guardaArchivo').html(mensajeUsuario);
	  	},
        error:function(jqXHR, exception){
            var mensajeUsuario = "<h4>Se ha producido un error</h4>";
            $('#mf_guardaArchivo').show()
            $('#mbd_guardaArchivo').html(mensajeUsuario);
        }
	});
}

/*Vuelve al menú de archivos*/
function evento_btn_salirArchivo(){
	$("#btn_salirGuardaArchivo, #btn_salirMsjUsuario").click(function(){
		cargar_vista_archivos();
	});
}

/*Pasar imagen a dataUrl*/
function toDataURL(url, callback){
    var xhr = new XMLHttpRequest();
    xhr.onload = function(){
        var reader = new FileReader();
        reader.onloadend = function(){
            callback(reader.result);
        }
        reader.readAsDataURL(xhr.response);
    };
    xhr.open('GET', url);
    xhr.responseType = 'blob';
    xhr.send();
}

/*Envia los datos de un archivo para ser eliminado*/
function evento_btn_deleteArchivo(){
    $(".img-archivo").on('click','a.btn_deleteArchivo', function(){
        var id_archivo = $(this).data("id");
        var nombre_archivo = $(this).data("archivo");
        var mensajeEliminar = confirm("¿Borrar el archivo "+nombre_archivo+"?");

        if(mensajeEliminar){
            var objArchivo = {idArchivo: id_archivo, nombreArchivo: nombre_archivo};

            $.ajax({
                data: {accion:"deleteArchivo", datos: JSON.stringify(objArchivo)},
                url: controller+"/ControllerArchivos.php",
                type: 'POST',
                beforeSend:function(){ 
                    $('#modal_msjUsuario').modal('show');    
                },
                success: function(respuesta){

                    var mensajeUsuario = "<h4>"+respuesta+"</h4>";
                    $('#mf_msjUsuario').show()
                    $('#mbd_msjUsuario').html(mensajeUsuario);
                }
            });
        }
    });
}

/*Determina el tamaño de un archivo ya sea en kb, mb o gb*/
function size_archivo(size){

    if(size<1048576){
        return (Math.round((size/1024)*100)/100)+" kb";
    }else if(size>1048576 && size<1073741824){
        return (Math.round((size/1048576)*100)/100)+" mb";
    }else if(size>1073741824){
        return (Math.round((size/1073741824)*100)/100)+" gb";
    }
}