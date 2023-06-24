<div class="view_archivos">
	<div id ="div_archivos">
		<ol class="breadcrumb">
	        <li>Cartelería</li>
	    	<li class="active">Archivos</li>
	    	<p class="pull-right"><img src="webContent/img/nuevo_estilo/navegadores_compatibles.png" width="180"></p>
	    </ol>


		<div class="col-xs-12 col-sm-12">
			<div class="progress" style="margin-bottom: 0;">
				 <div id="progress_size" class="progress-bar" role="progressbar">
				  </div>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12">
			<p id="detalle_disco" class="pull-right"></p>
		</div>


		<div class="pull-left col-sm-3 col-xs-4">
			<h4>Detalle archivo</h4>
			<div class="contenedor-preview">
				<div id="preview" class="oculto">
					<img src="" id="img_archivo">
				</div>
				<div>
					<strong id="formato"></strong><br>
					<strong id="tamanio"></strong>
				</div>
			</div>
		</div>

		<div class="col-sm-9 col-xs-12">
		    <form enctype="multipart/form-data">
	            <div class="form-group files">
	                <h4 class="pull-left">Subir archivo (Máximo: 1 gb, Formatos: mp4, jpg, png, swf)</h4>
	                <a id="btn_addArchivo" class="pull-right">
						<img src="webContent/img/nuevo_estilo/btn_subir.png" alt="Subir" width="90" height="45" style="margin-top: -15px;">
					</a>
	            	<input type="file" class="form-control" id="fl_archivo" accept=".mp4,.jpg,.png,.swf">
	            </div>
	        </form>
		</div><br><br>

		<div col-sm-12 col-xs-12>
			<h4 class="pull-left">Mis archivos</h4>
			<div class="scroll-allArchivos div_allArchivos">
				<div id="contenedor_archivos"></div>
			</div>
		</div>
	</div>

	<!--Modal Confirma guarda archivo-->
	<div class="modal fade" id="modal_guardaArchivo" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                	<h4 id="msj_archivoSubido" class="modal-title"></h4>
                </div>
                <div id="mbd_guardaArchivo" class="modal-body">
                	<h4>Subiendo archivo...</h4>

                	<div class="progress">
						<div id="barra_progreso" class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
						</div>
					</div>
                </div>
                <div id="mf_guardaArchivo" class="modal-footer oculto">
                	<button type="button" id="btn_salirGuardaArchivo" class="btn btn-danger"><i class='fa fa-times'></i> Cerrar</button>
            	</div>
        	</div>
    	</div>
    </div>

    <!--Modal mensaje usuario-->
	<div class="modal fade" id="modal_msjUsuario" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                	<h4 class="modal-title"></h4>
                </div>
                <div id="mbd_msjUsuario" class="modal-body">
                	<h4>Espere...</h4>

                	<div class="progress">
    					<div class="progress-bar progress-bar-striped progress-bar-danger" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:100%">
    					</div>
  					</div>
                </div>
                <div id="mf_msjUsuario" class="modal-footer oculto">
                	<button type="button" id="btn_salirMsjUsuario" class="btn btn-danger"><i class='fa fa-times'></i> Cerrar</button>
            	</div>
        	</div>
    	</div>
    </div>


</div>

<video id="preview_video" class="oculto" autoplay muted="true">
	<source src="">
</video>

<script src="webContent/view/archivos/js/js_archivos.js"></script>
