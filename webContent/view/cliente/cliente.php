<div>
	<div id ="div_clientes">
		<ol class="breadcrumb" id="brc_cliente">
	        <li>Cartelería</li>
	    	<li class="active">Clientes</li>
	    	<p class="pull-right"><img src="webContent/img/nuevo_estilo/navegadores_compatibles.png" width="180"></p>
	    </ol>
		<div id="div_btn_cliente">
			<a id="btn_formAddCliente" class="">
				<img src="webContent/img/nuevo_estilo/btn_agrega_usuario.png" alt="Agregar" width="250">
			</a>
		</div><br>
		<div id="tablaCliente" class="table-responsive">
			<table id="tbl-cliente" class="tbl-cliente table table-condensed table-hover table-bordered">
				<thead>
					<tr>
						<th>#</th><th>Nombre</th><th>Clave</th><th>Cambiar clave</th><!--th class="centrado">Eliminar</th-->
					</tr>
				</thead>
				<tbody id="tbd_cliente">
				</tbody>
			</table>
		</div>
		<input id="nroPagina" type="hidden" value="1">
		<input id="cantidadPagina" type="hidden" value="0">
		<ul class="pager" id="pager_cliente">
  			<li class="previous"><a id="btn_anteriorCliente">Anterior</a></li>
  			<li><label class="numeroPagina" id="lbl_pagina"></label></li>
  			<li class="next"><a id="btn_siguienteCliente">Siguiente</a></li>
		</ul>
	</div>

	<!--Formulario Cliente nuevo-->
	<form id="form_addCliente" class="oculto">
		<ol class="breadcrumb">
	        <li>Cartelería</li>
	        <li>Cliente</li>
	    	<li class="active">Agregar cliente</li>
	    	<p class="pull-right"><img src="webContent/img/nuevo_estilo/navegadores_compatibles.png" width="180"></p>
	    </ol>
        <div id="msj_errorCliente" class="alert alert-danger oculto">
            <a id="btn_cerrarErrorCliente" class="close">&times;</a>
            <strong>Error!</strong> Debe completar todos los campos.
        </div>
        <fieldset>
        	<legend>Agregar cliente</legend>
			<div class="form-group">
				<label for="sl_addCliente" class="form-control-label">Clientes: </label>
				<select class="form-control" id="sl_addCliente" required>
				</select>
			</div>
        </fieldset>
        <fieldset>
        	<legend>Clave cliente</legend>
			<div class="form-group">
				<div class="input-group">
					<a class="input-group-addon" id="btn_generaClaveCliente"><i class="glyphicon glyphicon-refresh"></i></a>
                    <input id="txt_claveCliente" type="text" class="form-control" placeholder="Clave" maxlength="8" readonly="true" required>
                </div>
			</div>
        </fieldset>
		<fieldset>
			<legend></legend>
			<div class="form-group centrado">
            	<button type="button" class="btn btn-success" id="btn_addCliente">
            		<span class="glyphicon glyphicon glyphicon-floppy-disk"></span> Guardar
            	</button>
				<button type="button" class="btn btn-danger btn_salirFormCliente">
					<span class="glyphicon glyphicon glyphicon-ban-circle"></span> Cancelar
				</button>
            </div>
		</fieldset>
    </form>


	<!--Formulario Modificar clave cliente-->
	<form id="form_modCliente" class="oculto">
		<ol class="breadcrumb">
	        <li>Cartelería</li>
	        <li>Cliente</li>
	    	<li class="active">Modificar clave cliente</li>
	    	<p class="pull-right"><img src="webContent/img/nuevo_estilo/navegadores_compatibles.png" width="180"></p>
	    </ol>
        <fieldset>
        	<legend id="lg_nombreClienteMod">Nombre cliente</legend>
        	<input type="hidden" id="txt_idClienteMod">
			<div class="form-group">
				<label class="form-control-label">Clave</label>
				<div class="input-group">
					<a class="input-group-addon" id="btn_generaClaveClienteMod"><i class="glyphicon glyphicon-refresh"></i></a>
                    <input id="txt_claveClienteMod" type="text" class="form-control" placeholder="Clave" maxlength="8" readonly="true" required>
                </div>
			</div>
        </fieldset>
		<fieldset>
			<legend></legend>
			<div class="form-group centrado">
            	<button type="button" class="btn btn-success" id="btn_modCliente">
            		<span class="glyphicon glyphicon glyphicon-floppy-disk"></span> Guardar
            	</button>
				<button type="button" class="btn btn-danger btn_salirFormCliente">
					<span class="glyphicon glyphicon glyphicon-ban-circle"></span> Cancelar
				</button>
            </div>
		</fieldset>
    </form>

    <!--Modal mensaje cliente-->
	<div class="modal fade" id="modal_mensajeCliente" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                	<h4 class="modal-title"></h4>
                </div>
                <div id="mbd_mensajeCliente" class="modal-body">
                	<h4>Espere un momento...</h4>

                	<div class="progress">
    					<div class="progress-bar progress-bar-striped progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:100%">
    					</div>
  					</div>
                </div>
                <div id="mf_mensajeCliente" class="modal-footer oculto">
                	<button type="button" id="btn_salirMensajeCliente" class="btn btn-danger btn_salirFormCliente"><i class='fa fa-times'></i> Cerrar</button>
            	</div>
        	</div>
    	</div>
    </div>

</div>

<script src="webContent/view/cliente/js/js_clientes.js"></script>
