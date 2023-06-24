<div>
	<div id ="div_usuarios">
		<ol class="breadcrumb">
	        <li>Cartelería</li>
	    	<li class="active">Usuarios</li>
	    	<p class="pull-right"><img src="webContent/img/nuevo_estilo/navegadores_compatibles.png" width="180"></p>
	    </ol>
		<div>
			<a id="btn_formAddUsuario" class="">
				<img src="webContent/img/nuevo_estilo/btn_agrega_usuario.png" alt="Agregar" width="250">
			</a>
		</div><br>
		<div id="tablaUsuario" class="table-responsive">
			<table id="tbl-usuario" class="tbl-usuario table table-condensed table-hover table-bordered">
				<thead>
					<tr>
						<th>#</th><th>Login</th><th>Nombre</th><th>Perfil</th><th>Modificar</th><th>Eliminar</th>
					</tr>
				</thead>
				<tbody id="tbd_usuario">
				</tbody>
			</table>
		</div>
		<input id="nroPagina" type="hidden" value="1">
		<input id="cantidadPagina" type="hidden" value="0">
		<ul class="pager">
  			<li class="previous"><a id="btn_anteriorUsuario">Anterior</a></li>
  			<li><label class="numeroPagina" id="lbl_pagina"></label></li>
  			<li class="next"><a id="btn_siguienteUsuario">Siguiente</a></li>
		</ul>
	</div>

	<!--Formulario Usuario nuevo-->
	<form id="form_addUsuario" class="oculto">
		<ol class="breadcrumb">
	        <li>Cartelería</li>
	        <li>Usuario</li>
	    	<li class="active">Agregar usuario</li>
	    	<p class="pull-right"><img src="webContent/img/nuevo_estilo/navegadores_compatibles.png" width="180"></p>
	    </ol>
        <div id="msj_errorUsuario" class="alert alert-danger oculto">
            <a id="btn_cerrarErrorUsuario" class="close">&times;</a>
            <strong>Error!</strong> Debe completar todos los campos.
        </div>
        <div id="msj_errorClaveUsuario" class="alert alert-danger oculto">
            <a id="btn_cerrarErrorClaveUsuario" class="close">&times;</a>
            <strong>Error!</strong> Las claves no coinciden.
        </div>
        <fieldset>
        	<legend>Agregar Usuario</legend>
			<div class="form-group">
				<label for="txt_loginUsuario" class="form-control-label">Login: </label>
				<input id="txt_loginUsuario" type="text" class="form-control" placeholder="Login" maxlength="16" required>
			</div>
			<div class="form-group">
				<label for="txt_nombreUsuario" class="form-control-label">Nombre: </label>
				<input id="txt_nombreUsuario" type="text" class="form-control" placeholder="Nombre" maxlength="30" required>
			</div>
			<div class="form-group">
				<label for="txt_apellidoUsuario" class="form-control-label">Apellido: </label>
				<input id="txt_apellidoUsuario" type="text" class="form-control" placeholder="Apellido" maxlength="50" required>
			</div>
			<div class="form-group">
				<label for="sl_perfilUsuario" class="form-control-label">Perfil: </label>
				<select id="sl_perfilUsuario" class="form-control" required>
					<option value="">--Seleccione--</option>
					<option value="1">Administrador</option>
					<option value="2">Ejecutivo</option>
				</select>
			</div>
			<div class="form-group">
				<label for="txt_claveUsuario" class="form-control-label">Clave: </label>
				<input id="txt_claveUsuario" type="password" class="form-control" placeholder="Clave" maxlength="16" required>
			</div>
			<div class="form-group">
				<label for="txt_confirmaClave" class="form-control-label">Confirma clave: </label>
				<input id="txt_confirmaClave" type="password" class="form-control" placeholder="Confirma clave" maxlength="16" required>
			</div>
        </fieldset>
		<fieldset>
			<legend></legend>
			<div class="form-group centrado">
            	<button type="button" class="btn btn-success" id="btn_addUsuario">
            		<span class="glyphicon glyphicon glyphicon-floppy-disk"></span> Guardar
            	</button>
				<button type="button" class="btn btn-danger btn_salirFormUsuario">
					<span class="glyphicon glyphicon glyphicon-ban-circle"></span> Cancelar
				</button>
            </div>
		</fieldset>
    </form>

    <!--Formulario Modificar Usuario-->
	<form id="form_modUsuario" class="oculto">
		<ol class="breadcrumb">
	        <li>Cartelería</li>
	        <li>Usuario</li>
	    	<li class="active">Modificar usuario</li>
	    	<p class="pull-right"><img src="webContent/img/nuevo_estilo/navegadores_compatibles.png" width="180"></p>
	    </ol>
        <div id="msj_errorUsuarioMod" class="alert alert-danger oculto">
            <a id="btn_cerrarErrorUsuarioMod" class="close">&times;</a>
            <strong>Error!</strong> Debe completar todos los campos.
        </div>
        <div id="msj_errorClaveUsuarioMod" class="alert alert-danger oculto">
            <a id="btn_cerrarErrorClaveUsuarioMod" class="close">&times;</a>
            <strong>Error!</strong> Las claves no coinciden.
        </div>
        <fieldset>
        	<legend>Modificar Usuario</legend>
			<div class="form-group">
				<input type="hidden" id="txt_idUsuarioMod">
				<label for="txt_loginUsuarioMod" class="form-control-label">Login: </label>
				<input id="txt_loginUsuarioMod" type="text" class="form-control" placeholder="Login" maxlength="16" required>
			</div>
			<div class="form-group">
				<label for="txt_nombreUsuarioMod" class="form-control-label">Nombre: </label>
				<input id="txt_nombreUsuarioMod" type="text" class="form-control" placeholder="Nombre" maxlength="30" required>
			</div>
			<div class="form-group">
				<label for="txt_apellidoUsuarioMod" class="form-control-label">Apellido: </label>
				<input id="txt_apellidoUsuarioMod" type="text" class="form-control" placeholder="Apellido" maxlength="50" required>
			</div>
			<div class="form-group">
				<label for="sl_perfilUsuarioMod" class="form-control-label">Perfil: </label>
				<select id="sl_perfilUsuarioMod" class="form-control" required>
					<option value="">--Seleccione--</option>
					<option value="1">Administrador</option>
					<option value="2">Ejecutivo</option>
				</select>
			</div>
			<div class="form-group">
				<label for="txt_claveUsuarioMod" class="form-control-label">Clave: </label>
				<input id="txt_claveUsuarioMod" type="password" class="form-control" placeholder="Clave" maxlength="16">
			</div>
			<div class="form-group">
				<label for="txt_confirmaClaveMod" class="form-control-label">Confirma clave: </label>
				<input id="txt_confirmaClaveMod" type="password" class="form-control" placeholder="Confirma clave" maxlength="16">
			</div>
        </fieldset>
		<fieldset>
			<legend></legend>
			<div class="form-group centrado">
            	<button type="button" class="btn btn-success" id="btn_modUsuario">
            		<span class="glyphicon glyphicon glyphicon-floppy-disk"></span> Guardar
            	</button>
				<button type="button" class="btn btn-danger btn_salirFormUsuario">
					<span class="glyphicon glyphicon glyphicon-ban-circle"></span> Cancelar
				</button>
            </div>
		</fieldset>
    </form>

    <!--Modal mensaje usuario-->
	<div class="modal fade" id="modal_mensajeUsuario" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                	<h4 class="modal-title"></h4>
                </div>
                <div id="mbd_mensajeUsuario" class="modal-body">
                	<h4>Espere un momento...</h4>

                	<div class="progress">
    					<div class="progress-bar progress-bar-striped progress-bar-success" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width:100%">
    					</div>
  					</div>
                </div>
                <div id="mf_mensajeUsuario" class="modal-footer oculto">
                	<button type="button" class="btn btn-danger btn_salirFormUsuario"><i class='fa fa-times'></i> Cerrar</button>
            	</div>
        	</div>
    	</div>
    </div>

</div>

<script src="webContent/view/usuarios/js/js_usuarios.js"></script>
