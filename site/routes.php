<?php

	if (!isset($_GET['page']) || $_GET['page'] == "" || $_GET['page'] == "home"){
		$affiche = "menu.php";
	}
	else {
		switch ($_GET['page']) {
			case "europeN":
				$affiche = "pageMap/europeN.php";
				break;
			default:
				$affiche = "lostinspace.php";
		}
	}