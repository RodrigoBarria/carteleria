<?php session_start(); ?>
<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Cartelería</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="webContent/css/bootstrap.min.css">
        <link rel="stylesheet" href="webContent/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="webContent/css/main.css">
        <link rel="stylesheet" href="webContent/css/archivos.css">
    </head>
    <body>

        <?php
            if(!isset($_SESSION["idCliente"])){
                require("webContent/view/login/login.php");
            }else if(isset($_SESSION["idCliente"]) && !isset($_SESSION["idUsuario"])){
                require("webContent/view/loginUsuario/loginUsuario.php");
            }else{
        ?>

        <!--Menú de navegación-->
        <div class="navbar navbar-personalizado navbar-fixed-top" role="navigation">
            <input type="hidden" id="idCliente" value="<?php echo $_SESSION["idCliente"] ?>">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar app-bar"></span>
                        <span class="icon-bar app-bar"></span>
                        <span class="icon-bar app-bar"></span>
                    </button>
                    <a href="" class="navbar-brand link-personalizado">
                        <img src="webContent/img/nuevo_estilo/logo_publik_blanco.png" alt="Publik">
                    </a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-personalizado">
                        <li><a id="btn_player"><span class="glyphicon glyphicon-expand"></span> Players</a></li>
                        <li><a id="btn_archivo"><span class="glyphicon glyphicon-file"></span> Archivos</a></li>

                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-download"></span> Descargas
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu navbar-personalizado">
                                <li><a id="btn_archivo" href="webContent/app/ClienteCarteleriaIngelan.msi"><span class="glyphicon glyphicon-bookmark"></span> Cliente Cartelería Ingelan</a></li>
                            </ul>
                        </li>

                        <?php if($_SESSION["idPerfilUsuario"]==1){?>
                            <li><a id="btn_usuario"><span class="glyphicon glyphicon-user"></span> Usuarios</a></li>
                        <?php }?>

                        <?php if($_SESSION["idPerfilCliente"]==1 && $_SESSION["idPerfilUsuario"]==1){?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-cog"></span> Opciones
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu navbar-personalizado">
                                    <li><a id="btn_clientes"><span class="glyphicon glyphicon-briefcase"></span> Clientes</a></li>
                                    <li><a id="btn_log"><span class="glyphicon glyphicon-list"></span> Logs</a></li>
                                    <li><a id="btn_ayuda"><span class="glyphicon glyphicon-question-sign"></span> Ayuda</a></li>
                                </ul>
                            </li>
                        <?php }?>

                    </ul>

                    <ul class="nav navbar-nav navbar-right navbar-personalizado">
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <span class="glyphicon glyphicon-user"></span>
                                    <strong class="oculta-texto">
                                        <label style="display: inline !important;">
                                            <?php echo $_SESSION["loginUsuario"]; ?>
                                        </label>
                                    </strong>

                                <span class="glyphicon glyphicon-chevron-down"></span>
                            </a>
                            <ul class="dropdown-menu navbar-personalizado">
                                <li>
                                    <div class="navbar-usuario">
                                        <div class="row">
                                            <div class="col-lg-8">
                                                <p class="text-left">
                                                    <span class="glyphicon glyphicon-user"></span>
                                                    <strong class="oculta-texto">
                                                        <label style="display: inline !important;">
                                                            <?php echo $_SESSION["nombreUsuario"]." ".$_SESSION["apellidousuario"]; ?>
                                                        </label>
                                                    </strong>
                                                </p>
                                                <p class="text-left small"><span class="glyphicon glyphicon-calendar"></span> <span class="sp_tiempo"></span></p>
                                                <p class="text-left"><a class="btn_logout logout hidden-xs"><span class="glyphicon glyphicon-log-out"></span> Salir</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right navbar-personalizado hidden-sm hidden-md hidden-lg">
                        <li><a class="btn_logout"><span class="glyphicon glyphicon-log-out"></span> Salir</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!--Contenido-->
        <div class="container">
            <div id="view" class="col-sm-12">
               <?php require_once("webContent/view/archivosPlayer/archivosPlayer.php");?>
            </div>
            <div><a href="#" class="scroll-to-top"><span class="glyphicon glyphicon-circle-arrow-up"></span><span class="sr-only">Ir arriba</span></a></div>
        </div>

        <?php }?>

        <script src="webContent/js/jquery-3.2.1.min.js"></script>
        <script src="webContent/js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
        <script src="webContent/js/jquery.ui.touch-punch.min.js"></script>
        <script src="webContent/js/main.js"></script>
        <script src="webContent/view/login/js/js_login.js"></script>
        <script src="webContent/view/loginUsuario/js/js_loginUsuario.js"></script>
        <script src="webContent/view/archivosPlayer/js/js_archivosPlayer.js"></script>
        <script src="webContent/js/bootstrap.min.js"></script>
    </body>
</html>
