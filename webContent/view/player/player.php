<div class="view_player">

	<!--Vista principal players-->
	<div id ="div_players">
		<ol class="breadcrumb">
	        <li>Cartelería</li>
	    	<li class="active">Players</li>
	    	<p class="pull-right"><img src="webContent/img/nuevo_estilo/navegadores_compatibles.png" width="180"></p>
	    </ol>

		<div id="tablaPlayers" class="table-responsive">
			<table id="tbl_players" class="tbl-players table table-condensed table-hover table-bordered">
				<thead>
					<tr>
						<th>#</th><th>Nombre</th><th>Archivos</th><th>Skins</th>
					</tr>
				</thead>
				<tbody id="tbd_players">
				</tbody>
			</table>
		</div>
	</div>

	<!--Agregar archivos a un player-->

	<div id="div_playerArchivo" class="oculto">

		<ol class="breadcrumb">
	        <li>Cartelería</li>
	    	<li>Players</li>
	    	<li class="active">Archivos</li>
	    	<p class="pull-right"><img src="webContent/img/nuevo_estilo/navegadores_compatibles.png" width="180"></p>
	    </ol>

		<div class="form-group col-sm-12">
			<button type="button" class="btn btn-danger pull-right btn_salirPlayerArchivo">
				<span class="glyphicon glyphicon glyphicon-ban-circle"></span> Cancelar
			</button>
			<span class="pull-right">&nbsp;</span>
			<button type="button" class="btn btn-success pull-right" id="btn_addPlayerArchivo">
        		<span class="glyphicon glyphicon glyphicon-floppy-disk"></span> Guardar
        	</button>
        </div><br><br>

		<!--Archivos-->
		<div>
			<h4 id="tl_player"></h4>

			<!--Archivos para playesr-->
			<div class="col-sm-6">
				<h4>Arrastre aquí sus archivos
				<button type="button" id="popperPrincipal" class="btn btn-default popper pull-right" data-toggle="popover">
					<span class="glyphicon glyphicon-volume-up"><span>
				</button>

				<div class="oculto" id="popper-content-principal">
					<label class="pull-left">Volumen:</label>
					<span class="pull-right" id="sp_volumenPrincipal">50%</span>
					<input type="range" id="rg_volumenPrincipal" class="rg_volumenPrincipal" value="50">
				</div></h4>

				<input type="hidden" id="data_misArchivos">
				<div class="scroll">
					<div id="div_miPlayerArchivo" class="connectedSortable">
					</div>
				</div>
			</div>

			<!--Todos los archivos-->
			<div class="col-sm-6">
				<h4>Archivos</h4>
				<div class="scroll">
					<div id="div_allArchivos" class="connectedSortable">
					</div>
				</div>
			</div>
		</div>
	</div>

	<!--Vista skins-->

	<div id="div_skins" class="oculto">
		<ol class="breadcrumb">
	        <li>Cartelería</li>
	    	<li>Players</li>
	    	<li class="active">Skins</li>
	    	<p class="pull-right"><img src="webContent/img/nuevo_estilo/navegadores_compatibles.png" width="180"></p>
	    </ol>
		<input type="hidden" id="data_miPlayer">
		<div class="col-sm-3">

			<div class="form-group">
				<label>Color de fondo</label>
				<input type="color" class="form-control" id="color_skin" value="#000000">
			</div>

			<div class="form-group">
				<label>Imagen de fondo</label>
		    	<input type="file" class="file" id="fl_imagenFondo" accept=".jpg,.png">
		    	<div class="input-group col-xs-12">
		      		<span class="input-group-addon"><i class="glyphicon glyphicon-picture"></i></span>
		      		<input type="text" id="txt_fileImgFondo" class="form-control input-sm" disabled placeholder="Seleccione una imagen">
		      		<span class="input-group-btn">
		        		<button class="browse btn btn-primary input-sm" type="button"><i class="glyphicon glyphicon-search"></i></button>
		     		</span>
		    	</div>
		  	</div>
		  	<div class="form-group" id="preview_fondo">

		  	</div>

		</div>
		<div id="contenedor_skins" class="col-sm-9">
		</div>

		<div class="form-group col-sm-12">
			<button id="btn_salirSkinPlayer" type="button" class="btn btn-danger pull-right">
				<span class="glyphicon glyphicon glyphicon-ban-circle"></span> Cancelar
			</button>
			<span class="pull-right">&nbsp;</span>
			<button type="button" class="btn btn-success pull-right" id="btn_addSkinPlayer">
        		<span class="glyphicon glyphicon glyphicon-floppy-disk"></span> Guardar
        	</button>
        </div>

	</div>

	<!-- Modal -->
	<div class="modal fade" id="modal_banner" role="dialog">
	    <div class="modal-dialog modal-lg">
	     	<div class="modal-content">
	        	<div class="modal-header">
	          		<button type="button" class="close" data-dismiss="modal">&times;</button>
	          		<h4 class="modal-title">Ingrese texto del banner</h4>
	        	</div>
	        	<div class="modal-body">
	          		<textarea id="txt_banner" cols="30" rows="10" class="form-control"></textarea>
	        	</div>
	        	<div class="modal-footer">
	          		<button type="button" class="btn btn-success" data-dismiss="modal">Guardar</button>
	        	</div>
	      	</div>
	    </div>
	</div>

	<!--Modal mensaje player-->
	<div class="modal fade" id="modal_mensajePlayer" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                	<h4 class="modal-title"></h4>
                </div>
                <div id="mbd_mensajePlayer" class="modal-body oculto">
                	<h4>Espere un momento...</h4>

                	<div class="progress">
    					<div class="progress-bar progress-bar-striped progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:100%">
    					</div>
  					</div>
                </div>
                <div id="mbd_mensajeErrorPlayer" class="modal-body oculto">
                	<h4>Los archivos flash e imágenes deben llevar un tiempo de duración</h4>
                </div>
                <div id="mbd_mensajeErrorSkin" class="modal-body oculto">
                	<h4>El archivo seleccionado no es una imagen</h4>
                </div>
                <div id="mf_mensajePlayer" class="modal-footer oculto">
                	<button type="button" class="btn btn-danger btn_salirPlayerArchivo"><i class='fa fa-times'></i> Cerrar</button>
            	</div>
            	<div id="mf_mensajeErrorPlayer" class="modal-footer oculto">
                	<button type="button" class="btn btn-danger btn_salirErrorPlayer"><i class='fa fa-times'></i> Cerrar</button>
            	</div>
        	</div>
    	</div>
    </div>

</div>

<script src="webContent/view/player/js/js_player.js"></script>
<script src="webContent/view/player/js/js_skin.js"></script>
