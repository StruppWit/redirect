<?php

	$F = file('/var/www/redirects.drk-witten.de/data/redirects.txt',FILE_IGNORE_NEW_LINES);


	list($suchstring,$params) = explode('?', strtolower($_SERVER['REQUEST_URI']));
	if (substr($suchstring,-1) == "/") {
		$suchstring = substr($suchstring,0,-1);
		// mail("jens@struppek.de","test",$suchstring);
	}


	if (array_search($suchstring,$F)===FALSE) {
		$url = "https://www.drkwitten.de";
		$url .= "?pk_campaign=drk-wit-fallback";
	} else {
		$url = $F[array_search($suchstring,$F)+1];
		$url .= "?pk_campaign=drk-wit";
	}

	$url .= "&pk_kwd=" . $suchstring;

	if (substr($suchstring,0,6)=='fbclid') {
		$url .= "?pk_source=FB";
	}

// mail("jens@struppek.de","DEBUG",$suchstring.'     ==>     '.$url.'   ###     '.$params );

	header('Location: '.$url);


?>
