<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Forum</title>

      <link rel="stylesheet" href="css/bootstrap/css/bootstrap.css" type="text/css" />
      <link href="css/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
      <link href="css/bootstrap/css/style.css" rel="stylesheet" type="text/css"/>
      <link href="css/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css"/>
      <script src="css/bootstrap/js/jquery.js"></script>
      <script src="css/bootstrap/js/bootstrap.min.js"></script>
      	<script src="js/jquery.js"></script>	
	<script src="js/mediaelement-and-player.min.js"></script>	
	<link rel="stylesheet" href="js/mediaelementplayer.css" />
  </head>

  <body>
    <div class="container">
			<div class="row well content" style="width: 100%; float: left;">
			
<!-- ecouter tous  -->
<!-- fin ecouter tous -->


				<h3>Studio</h3>
				
	
			</div>
			<div class="row well"> 
			<?php
							$mp3s = array();
							$dirname = '../audioMP3/upload/';
							$dir = opendir($dirname); 
							while($file = readdir($dir)) {
								if($file != '.' && $file != '..' && !is_dir($dirname.$file))
								{
									$element = pathinfo($file);
									if ($element['extension']=="mp3"){
										if($file != "test.mp3")
											echo $file."\n";
										// array_push($mp3s, $file);
									}
								}
							}
			?>
			</div>
			<script>
				$('audio').mediaelementplayer();
			</script>
    </div>
  </body>
</html>