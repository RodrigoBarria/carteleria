<div>
	<div id ="div_carteleria">
		<ol class="breadcrumb">
	        <li>Cartelería</li>
	    	<li class="active">Distribución de archivos</li>
	    	<p class="pull-right"><img src="webContent/img/nuevo_estilo/navegadores_compatibles.png" width="180"></p>
	    </ol>

		<div class="col-sm-7"></div>
		<div class="form-group col-sm-5">
			<a class="pull-right btn_salirArchivoPlayer">
				<img src="webContent/img/nuevo_estilo/btn_cancelar.png" alt="Cancelar" width="110">
				<!-- <span class="glyphicon glyphicon glyphicon-ban-circle"></span> Cancelar -->
			</a>
			<span class="pull-right">&nbsp;</span>
			<a class="pull-right" id="btn_archivoPlayer">
				<img src="webContent/img/nuevo_estilo/btn_guardar.png" alt="Guardar" width="110">
        		<!-- <span class="glyphicon glyphicon glyphicon-floppy-disk"></span> Guardar -->
        	</a>
        </div>

		<ul id="tab_archivosPlayer" class="nav nav-tabs">
        	<li class="active"><a data-toggle="tab" href="#tab_player" id="btn_tabPlayer">Players</a></li>
        	<li><a data-toggle="tab" href="#tab_archivos" id="btn_tabTransArchivo">Archivos</a></li>
    	</ul><br>

		<div class="tab-content">
			<!--Players-->
			<div id="tab_player" class="panel-group tab-pane fade in active">
				<div id="contenedor_players" class="col-sm-4"></div>
				<div id="contenedor_archivos" class="col-sm-8 scroll"></div>
			</div>

			<div id="tab_archivos" class="tab-pane fade">
				<!--Archivos-->
				<div class="div_transferencia">
					<!--Archivos para players-->
					<div class="col-sm-6">
						<h4>Arrastre aquí sus archivos
							<button type="button" id="popperPrincipal" class="btn btn-default popper pull-right" data-toggle="popover">
								<span class="glyphicon glyphicon-volume-up"><span>
							</button>

							<div class="oculto" id="popper-content-principal">
								<label class="pull-left">Volumen:</label>
								<span class="pull-right" id="sp_volumenPrincipal">50%</span>
								<input type="range" id="rg_volumenPrincipal" class="rg_volumenPrincipal" value="50">
							</div>
						</h4>
						<div class="scroll" id="contenedor_archivoPlayer">
							<div id="div_archivoPlayer" class="connectedSortable">
							</div>
						</div>
					</div>

					<!--Todos los archivos-->
					<div class="col-sm-6">
						<h4>Archivos</h4>
						<div class="scroll">
							<div id="div_archivos" class="connectedSortable">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!--Modal mensaje archivo player-->
	<div class="modal fade" id="modal_mensajeArchivoPlayer" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                	<h4 class="modal-title"></h4>
                </div>
                <div id="mbd_mensajeArchivoPlayer" class="modal-body oculto">
                	<h4>Espere un momento...</h4>

                	<div class="progress">
    					<div class="progress-bar progress-bar-striped progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:100%">
    					</div>
  					</div>
                </div>
                <div id="mbd_mensajeErrorArchivoPlayer" class="modal-body oculto">
                </div>
                <div id="mf_mensajeArchivoPlayer" class="modal-footer oculto">
                	<button type="button" class="btn btn-danger btn_salirArchivoPlayer"><i class='fa fa-times'></i> Cerrar</button>
            	</div>
            	<div id="mf_mensajeErrorArchivoPlayer" class="modal-footer oculto">
                	<button type="button" class="btn btn-danger btn_salirErrorArchivoPlayer"><i class='fa fa-times'></i> Cerrar</button>
            	</div>
        	</div>
    	</div>
    </div>
</div>
