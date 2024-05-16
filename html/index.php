<?php

	//Datei mit den Weiterleitungen einlesen. Format: 1. Zeile Alias, 2. Zeile Weiterleitungsziel usw.
	$F = file('/var/www/redirects.drk-witten.de/data/redirects.txt',FILE_IGNORE_NEW_LINES);

	//Wurden Parameter mit der URI übergeben? Dann am ? splitten in suchstring un Parammeter, sonst ist du URI der Suchstring 
	if (str_contains($_SERVER['REQUEST_URI'])) {
		list($suchstring,$params) = explode('?', strtolower($_SERVER['REQUEST_URI']));
	} else {
		$suchstring = strtolower($_SERVER['REQUEST_URI']));
	}

	// Wenn vorhanden / am Ende entfernen zur Vereinheitlichung
	if (substr($suchstring,-1) == "/") {
		$suchstring = substr($suchstring,0,-1);
		// mail("jens@struppek.de","test",$suchstring);
	}


	//In den Aliasen aus der Datei nach dem Alias in der URL suchen.  
	if (array_search($suchstring,$F)===FALSE) {
		// Wenn kein Weiterleitungsziel gefunden wurde, Startseite als Ziel definieren.
		$url = "https://www.drkwitten.de";
		// Weiterleitungsziel um ein Parameter für Statistiktool ergänzen (Kampagne: drk-wit-fallback)
		$url .= "?pk_campaign=drk-wit-fallback";
	} else {
		// Wenn ein Weiterleitungsziel gefunden wurde, dies als Weiterleitungsziel für diesen Aufruf definieren.
		$url = $F[array_search($suchstring,$F)+1];
		// Weiterleitungsziel um ein Parameter für Statistiktool ergänzen (Kampagne: drk-wit-fallback)
		$url .= "?pk_campaign=drk-wit";
	}

	// Weiterleitungsziel um ein Parameter für Statistiktool ergänzen (Kampagnen-Keyword: aufgerufener Alias)
	$url .= "&pk_kwd=" . $suchstring;

	// Prüfen ob der Parameter fbclid übergeben wurde. Dieser wird immer von Facebook mitgegeben, wenn ein Link dort gepostet wird.
	if (substr($params,0,6)=='fbclid') {
		// Weiterleitungsziel um ein Parameter für Statistiktool ergänzen (Herkunft: FB)
		$url .= "?pk_source=FB";
	}

// mail("jens@struppek.de","DEBUG",$suchstring.'     ==>     '.$url.'   ###     '.$params );

	header('Location: '.$url);


?>
