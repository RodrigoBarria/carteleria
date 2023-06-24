<nav class="navbar navbar-default navbar-fixed-top navbar-login">
    <div class="container-fluid navbar-login">
        <div class="col-sm-9">
        </div>
        <div class="navbar-header col-lg-3">
            <a class="navbar-brand" href=""><img src="webContent/img/logo_ingelan.png" alt="ingelan"></a>
        </div>
    </div>
</nav>
<br><br><br>
<div class="container" id="div_loginUsuario">
    <div class="col-sm-2 hidden-xs col-md-4 col-lg-4"></div>
    <div class="col-sm-8 col-xs-12 col-md-4 col-lg-4">
        <form>
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <div class="tituloLogin">
                                <img src="webContent/img/nuevo_estilo/logo_publik.png" alt="" width="100%">
                                <strong class="textoLogin">
                                    <?php echo $_SESSION["nombreCliente"]; ?>
                                </strong>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user glyphicon-user-personal"></i></span>
                                <input id="txtLoginUsuario" type="text" class="form-control txt-login" placeholder="Login" maxlength="16" required>
                            </div><br>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input id="txtClaveUsuario" type="password" class="form-control txt-login" placeholder="Clave" maxlength="16" required>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="td_ingresaUsuario">
                            <a id="btnIngresarUsuario">
                                <img src="webContent/img/nuevo_estilo/btn_ingresar.png" alt="" width="35%">
                            </a><br><br>
                            <span id="msjErrorLoginUsuario" class="msjError"></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>
    </div>
</div>
