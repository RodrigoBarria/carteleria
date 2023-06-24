<div>
	<div id ="div_ayuda">
		<ol class="breadcrumb">
	        <li>Cartelería</li>
	    	<li class="active">Ayuda</li>
	    	<p class="pull-right"><img src="webContent/img/nuevo_estilo/navegadores_compatibles.png" width="180"></p>
	    </ol>


		<div class="panel panel-default">
  			<div class="panel-heading cursor" data-toggle="collapse" data-target="#pnl_clienteCarteleria">
  				Agregar cliente a la cartelería <span class="glyphicon glyphicon-chevron-down pull-right"></span>
  			</div>
  			<div class="panel-body collapse" id="pnl_clienteCarteleria">
  				<ul>
  					<li>
  						<p>
  							Presione la opción "Clientes" en el menú de navegación.
  						</p>
  					</li>
  					<li>
  						<p>
  							Ya en el menú de "Clientes" presione el botón <label class="btn btn-info btn-xs"><span class="glyphicon glyphicon-plus"></span> Agregar</label>
  							para abrir el formulario que nos permitira crear una nueva cuenta de cliente.
  						</p>
  					</li>
  					<li>
  						<p>
  							Seleccione un cliente desde la lista, el sistema generará una clave aleatoria
  							la cual podra ser cambiada presionando el botón <span class="glyphicon glyphicon-refresh"></span>
  							o luego desde el menú de "Clientes", presione el botón <label class="btn btn-success btn-xs"><span class="glyphicon glyphicon-floppy-disk"></span> Guardar</label>
  							para crear la cuenta o el botón <label class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-ban-circle"></span> Cancelar</label> para anular el proceso.
  						</p>
  					</li>
  				</ul>
  			</div>
		</div>

		<div class="panel panel-default">
  			<div class="panel-heading cursor" data-toggle="collapse" data-target="#pnl_clienteFtp">
  				Crear usuario FTP para "Cliente Cartelería Ingelan" <span class="glyphicon glyphicon-chevron-down pull-right"></span>
  			</div>
  			<div class="panel-body collapse" id="pnl_clienteFtp">
  				<ul>
  					<li>
  						<p>
  							Para crear un usuario FTP deberemos trabajar desde la terminal de comandos, escribiremos la siguiente instrucción:
  						</p>
						<p>
							<div class="well well-sm">
								sudo useradd -g ftp -d /var/www/carteleria/src/upload/[idCliente]/ -c "cliente_ftp_[idCliente]" cliente_ftp_[idCliente]
							</div>
							Donde:
						</p>
						<p>
							<ul>
								<li>
									<strong>-g ftp</strong> agrega el usuario al grupo FTP.
								</li>
								<li>
									<strong>-d /var/www/carteleria/src/upload/[idCliente]/</strong> es la ruta del repositorio de archivos del cliente.
								</li>
								<li>
									<strong>[idCliente]</strong> debe ser el id generado al agregar un nuevo cliente a la plataforma de cartelería.
								</li>
								<li>
									<strong>-c "cliente_ftp_[idCliente]" cliente_ftp_[idCliente]</strong> es la descripción y nombre del usuario.
								</li>
							</ul>
						</p>
  					</li>
  					<li>
  						<p>
  							Ahora se debe crear la contraseña del usuario, la cual debe ser la misma que generó la plataforma de cartelería,
  							para esto escribiremos la siguiente instrucción:
  							<div class="well well-sm">
								sudo passwd cliente_ftp_[idCliente]
							</div>
							Se nos pedirá escribir la clave dos veces, al hacerlo tendremos la contraseña creada.
  						</p>
  					</li>
  					<li>
  						<p>
  							Por último debemos agregar el nuevo cliente a la lista de usuarios existentes, para esto escribiremos la siguiente instrucción:
  							<div class="well well-sm">
								echo "cliente_ftp_[idCliente]" >> /etc/vsftpd.chroot_list
							</div>
  						</p>
  					</li>
  				</ul>
  			</div>
		</div>

	</div>

</div>

<script src="webContent/view/ayuda/js/js_ayuda.js"></script>
