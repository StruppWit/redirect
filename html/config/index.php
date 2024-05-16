<?php
	$configFileName = '/var/www/redirects.drk-witten.de/data/redirects.txt';

	function show_redirect($von,$nach,$titel="") {

		if ($titel=="") $titel=$von;
		echo '
			<div class="card" style="margin:10px;">
  				<div class="card-header">
				    '.$titel.'
				</div>
				<div class="card-body">

					<label for="von-url">aufgerufene Adresse</label>
					<div class="input-group mb-3">
					  <div class="input-group-prepend">
					      <span class="input-group-text" id="von-addon">http(s)://www.drk-witten.de</span>
					  </div>
					  <input type="text" class="form-control" id="von-url" aria-describedby="von-addon" value="'.$von.'" name="URLS[]">
					</div>

					<label for="nach-url">weiterleiten an</label>
					<div class="input-group mb-3">
					  <div class="input-group-prepend">
					      <span class="input-group-text" id="nach-addon">&gt;&gt;&gt;</span>
					  </div>
					  <input type="text" class="form-control" id="von-url" aria-describedby="nach-addon" value="'.$nach.'" name="URLS[]">
					</div>


				</div>
			</div>
';
	}

	function test($pretext) {
			echo '
				<div class="card text-white bg-info">
					<div class="card-header">Header</div>
					<div class="card-body">
					    <p class="card-text">
					    	<pre class=".pre-scrollable"><code>
					    		'.$pretext.'
							</code></pre>
					    </p>
					</div>
				</div>
			';

	}

	function gespeichert() {
		echo '

			<div class="alert alert-success alert-dismissible fade show" role="alert">
				  Die Weiterleitungen wurden gespeichert!
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
					    <span aria-hidden="true">&times;</span>
				  </button>
			</div>

		';
	}

?><!doctype html>
<html lang="de">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Redirects f&uuml;r www.drk-witten.de</title>
  </head>
  <body>

	<nav class="navbar navbar-dark bg-dark">
  		<span class="navbar-brand mb-0 h1">Weiterleitungen f&uuml;r www.drk-witten.de</span>
	</nav>


<?php
		if ($_SERVER['REQUEST_METHOD'] == "POST") {
			$URLS=$_POST['URLS'];
			$anz=count($URLS);

			if ($anz>0) {

				$anz=count($URLS);
				for($i=0;$i < $anz;$i++) {
					if (trim($URLS[$i]) == "") {
						if ($i % 2 == 0) {
							if (trim($URLS[$i+1]) == "") {
								unset($URLS[$i]);
								unset($URLS[$i+1]);
								$i++;
							} else {
								$URLS[$i]=uniqid("/");
							}
						} else {
							$URLS[$i]="https://www.drkwitten.de";
						}
					}
				}
				//test(print_r($URLS,true));
				file_put_contents($configFileName,implode(PHP_EOL,$URLS));
				gespeichert();
			}
		}


		echo '<form action="'.$_SERVER['PHP_SELF'].'" method="POST">';
		$F = file($configFileName,FILE_IGNORE_NEW_LINES);
		while (count($F)>1) {
			show_redirect(array_shift($F),array_shift($F));
		}

		show_redirect("","","<b>Neue Weiterleitung anlegen</b>");


		echo '<button type="submit" class="btn btn-dark" style="margin:10px;">Speichern</button>';
		echo '</form>';
?>

				<div class="card text-white bg-info"  style="margin:10px;">
					<div class="card-header">Information</div>
					<div class="card-body">
					    <p class="card-text">
					    	Zum l&ouml;schen von Weiterleitungen bitte beide Felder leeren und dann speichern.
					    </p>
					    <p class="card-text">
					    	Da f&uuml;r die Umleitung immer beide Werte ben&ouml;tigt werden, wird eine leere &quot;aufgerufene Adresse&quot; durch eine zuf&auml;llige Zahlen und Buchstabenkombination ersetzt
					    	und ein leeres &quot;weiterleiten an&quot; Feld wird immer auf die DRK Witten Startseite weitergeleitet.
					    </p>
					</div>
				</div>
				<p>&nbsp;</p>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
