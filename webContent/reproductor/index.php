<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Reproductor</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/reproductor.css">
    </head>
    <body>

		<div id="div_player">
			<div class="center">
				<!--Videos-->
				<video id="videoPlayer" autoplay class="oculto" width="100%">
					 <source src="" type="video/mp4">
				</video>
				
				<!--Imagenes-->
				<img src="" id="imgPlayer" class="oculto img-responsive">

				<!--Flash-->
				<object id="flashPlayer">
				</object>

				
			</div>
			<div class="banner oculto">
				<marquee id="txt_banner"></marquee>
			</div>
		</div>
		
    	<script src="../js/jquery-3.2.1.min.js"></script>
		<script src="js/js_reproductor.js"></script>
    </body>
</html>