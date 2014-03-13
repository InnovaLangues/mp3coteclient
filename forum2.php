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
			<div class="modal hide fade" id="infos">
			  <div class="modal-header"> <a class="close" data-dismiss="modal">×</a>
			    <h3>Enregistrement</h3>
			  </div>
			  <div class="modal-body">
			  	<!doctype html>
<html>
<head>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Audio Recorder</title>
    <script src="http://cwilso.github.io/AudioContext-MonkeyPatch/AudioContextMonkeyPatch.js"></script>
	<script src="js/audiodisplay.js"></script>
	<script src="js/recorder.js"></script>
	<style>
	body { 
		display: flex;
		flex-direction: column;
		height: 100vh;
		width: 100%;
		margin: 0 0;
	}
	canvas {
		display: inline-block; 
		background: #202020;
		width: 90%;
		height: 25%;
		box-shadow: 0px 0px 10px blue;
	}
	</style>
</head>
<body>
	<div id="viz">
	<table class="table table-striped table-bordered">
		<tr>
			<td id="recordAudio">
				<!-- <audio id="preview" controls></audio> -->
			</td>
			<td>
				<canvas id="analyser" width="300" height="150"></canvas>
			</td>
			<td>
				<canvas id="wavedisplay" width="300" height="150"></canvas>
			</td>
		</tr>
	</table>
	</div>
	<div id="controls">
	<table>
		<tr>
			<td>
				<img id="record" src="img/mic128.png" onclick="toggleRecording(this);">
			</td>
			<td>
				<button class="btn btn-danger" style="position: static; float: right;" onclick="saveAudio();">Ecouter enregistrement</button>
			</td>
			<td>
				<button class="btn btn-danger" style="position: static; float: right;" onclick="Deposer();">Deposer enregistrement</button>
			</td>
			<td>
					<div id="chargement2">

					</div>
			</td>
		</tr>
		<tr>
			<td>
				<div id="chargement">

				</div>
			</td>
			<td>
				<span id="compt"></span>
			</td>
			<td>
				<span id="transfert"></span>
			</td>
		</tr>
	</table>
	</div>
</body>
</html>
			  </div>
			  <div class="modal-footer"> <a class="btn btn-info" data-dismiss="modal">Fermer</a> </div>
			</div>
<!-- ecouter tous  -->
			<div class="modal hide fade" id="infos2">
			  <div class="modal-header"> <a class="close" data-dismiss="modal">×</a>
			    <h3>Tout écouter</h3>
			  </div>
			  <div class="modal-body">
			  		<!-- debut -->
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
										array_push($mp3s, $file);
									}
								}
							}
							// echo " ";
							$content = joinmp3s($mp3s);
							// header('Content-Type: audio/x-mp3');
							// // echo $content;
							// // binary to mp3
							file_put_contents("upload/test.mp3", $content);
							function joinmp3s($mp3s){
							    $fields = join('/',array( 'H8ChunkID', 'VChunkSize', 'H8Format',
							                              'H8Subchunk1ID', 'VSubchunk1Size',
							                              'vAudioFormat', 'vNumChannels', 'VSampleRate',
							                              'VByteRate', 'vBlockAlign', 'vBitsPerSample' ));
							    $data = '';
							    foreach($mp3s as $mp3){
							        $fp     = fopen("upload/".$mp3,'rb');
							        $header = fread($fp,36);
							        $info   = unpack($fields,$header);
							        // read optional extra stuff
							        if($info['Subchunk1Size'] > 16){
							            $header .= fread($fp,($info['Subchunk1Size']-16));
							        }
							        // read SubChunk2ID
							        $header .= fread($fp,4);
							        // read Subchunk2Size
							        $size  = unpack('vsize',fread($fp, 4));
							        $size  = $size['size'];
							        // read data
							        $data .= fread($fp,20000000);
							    }
							    return $header.pack('V',strlen($data)).$data;
							}
					?>
						<audio src="../audioMP3/upload/test.mp3" controls></audio>

			  		<!-- fin -->
			  </div>
			  <div class="modal-footer"> <a class="btn btn-info" data-dismiss="modal">Fermer</a> </div>
			</div>
<!-- fin ecouter tous -->


				<h3>Forum</h3>
				
				<!-- Liste des sujets -->
				<?php if (!isset($_GET['sujet'])) {?>
				<button class="btn btn-danger" style="position: static; float: right;">Créer un nouveau sujet</button>
				<br /><br />
				<table class="table table-striped table-bordered">
					<tr>
						<th style="width: 70%; text-align: center;">Sujet et description</th>
						<th style="width: 20%; text-align: center;">Date</th>
						<th style="width: 10%; text-align: center;">Messages</th>
					</tr>

					<tr>
						<td><a href="?sujet=1"><strong>Titre du sujet 1</strong> </a><br /> Description du sujet en question</td>
						<td style="text-align: center; vertical-align: middle;">Sujet créé le <span style="color: #1290D0;">30/01/2014</span><br /> <span
							style="font-size: small;">(il y a 2 jours)</span>
						</td>
						<td style="text-align: center; vertical-align: middle;">42</td>
					</tr>
					<tr>
						<td><a href="?sujet=1"><strong>Titre du sujet 1</strong> </a><br /> Description du sujet en question</td>
						<td style="text-align: center; vertical-align: middle;">Sujet créé le <span style="color: #1290D0;">30/01/2014</span><br /> <span
							style="font-size: small;">(il y a 2 jours)</span>
						</td>
						<td style="text-align: center; vertical-align: middle;">42</td>
					</tr>
					<tr>
						<td><a href="?sujet=1"><strong>Titre du sujet 1</strong> </a><br /> Description du sujet en question</td>
						<td style="text-align: center; vertical-align: middle;">Sujet créé le <span style="color: #1290D0;">30/01/2014</span><br /> <span
							style="font-size: small;">(il y a 2 jours)</span>
						</td>
						<td style="text-align: center; vertical-align: middle;">42</td>
					</tr>
					<tr>
						<td><a href="?sujet=1"><strong>Titre du sujet 1</strong> </a><br /> Description du sujet en question</td>
						<td style="text-align: center; vertical-align: middle;">Sujet créé le <span style="color: #1290D0;">30/01/2014</span><br /> <span
							style="font-size: small;">(il y a 2 jours)</span>
						</td>
						<td style="text-align: center; vertical-align: middle;">42</td>
					</tr>
					<tr>
						<td><a href="?sujet=1"><strong>Titre du sujet 1</strong> </a><br /> Description du sujet en question</td>
						<td style="text-align: center; vertical-align: middle;">Sujet créé le <span style="color: #1290D0;">30/01/2014</span><br /> <span
							style="font-size: small;">(il y a 2 jours)</span>
						</td>
						<td style="text-align: center; vertical-align: middle;">42</td>
					</tr>
				</table>
				
				<!-- Liste des messages d'un sujet -->
				<?php } else{?>
				<h4>Titre du sujet</h4>
				<br />
				<table class="table table-striped table-bordered">
					<tr>
						<th style="width: 20%; text-align: center;">Profil</th>
						<th style="width: 80%; text-align: center;">Message</th>
					</tr>
					<tr>
						<td style="background: #F5F7FF;">
							<span style="font-weight: bold; color: #1290D0;">Prénom N.</span><br />
							<span style="font-size: small;">apprenant</span><br />
							<span style="font-size: small;">25 ans</span><br />
						</td>
						<td>
							<span style="font-size: x-small;">Le 30/01/2014 à 12:30</span><br />
							<audio src="../audioMP3/upload/112665653.mp3" controls></audio></span><br />
							Contenu d'un sujet principal.<br />
						</td>
					</tr>
				</table>
				<br />
				<table>
					<tr>
						<td>
							<form action="forum2.php" method="post">
								<input type="submit" class="btn btn-danger" name="ajout" style="position: static; float: right;" value="Retour"/>
							</form>
						</td>
						<td>
							<a class="btn btn-danger" data-toggle="modal" href="#" data-target="#infos">S'enregistrer</a> 
						</td>
						<td>
							<a class="btn btn-danger" data-toggle="modal" href="#" data-target="#infos2">Tout écouter</a> 
						</td>
					</tr>
				</table>
				<h4>42 réponses</h4><br />
				<table class="table table-striped table-bordered">
					<tr>
						<th style="width: 20%; text-align: center;">Profil</th>
						<th style="width: 80%; text-align: center;">Message</th>
					</tr>
					<?php
						$dirname = '../audioMP3/upload/';
						$dir = opendir($dirname); 

						while($file = readdir($dir)) {
							if($file != '.' && $file != '..' && !is_dir($dirname.$file))
							{
								$element = pathinfo($file);
								if ($element['extension']=="mp3"){
									if($file != "test.mp3"){
									?>
										<tr>
											<td>
												<span style="font-weight: bold; color: #1290D0;">Prénom N.</span><br />
												<span style="font-size: small;">apprenant</span><br />
												<span style="font-size: small;">25 ans</span><br />
											</td>
											<td>
												<span style="font-size: x-small;">Le 30/01/2014 à 12:30</span><br />
												<audio src="<?php echo $dirname.'/'.$file; ?>" controls></audio></span><br />
												Contenu d'une réponse au sujet.<br />
											</td>
										</tr>
									<?php
									}
								}

							}
						}

						closedir($dir);
 					?>
				</table>
				<?php } ?>
			</div>
			<script>
				$('audio').mediaelementplayer();
			</script>
				<script src="js/main.js"></script>
    </div>
  </body>
</html>