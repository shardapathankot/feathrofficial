<?php

    require_once('../../../../wp-load.php');

	$vollstart_Ticket = vollstart_Ticket::Instance($_SERVER["REQUEST_URI"]);
	$vollstart_Ticket->initFilterAndActions();
	$vollstart_Ticket->renderPage();

?>