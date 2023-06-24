<div>
	<div id ="div_log">
		<ol class="breadcrumb">
	        <li>Cartelería</li>
	    	<li class="active">Log</li>
	    	<p class="pull-right"><img src="webContent/img/nuevo_estilo/navegadores_compatibles.png" width="180"></p>
	    </ol>

		<select id="sl_clientes" class="form-control"></select><br>

		<div id="tablaLog" class="table-responsive">
			<table id="tbl-log" class="tbl-log table table-condensed table-hover table-bordered">
				<thead>
					<tr>
						<th>#</th><th>Evento</th><th>Usuario</th><th>Fecha</th>
					</tr>
				</thead>
				<tbody id="tbd_log">
				</tbody>
			</table>
		</div>
		<input id="nroPagina" type="hidden" value="1">
		<input id="cantidadPagina" type="hidden" value="0">
		<ul class="pager">
  			<li class="previous"><a id="btn_anteriorLog">Anterior</a></li>
  			<li><label class="numeroPagina" id="lbl_pagina"></label></li>
  			<li class="next"><a id="btn_siguienteLog">Siguiente</a></li>
		</ul>
	</div>
</div>

<script src="webContent/view/log/js/js_log.js"></script>
