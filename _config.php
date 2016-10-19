<?php
	$active = true;
	$debug = false;

	$sqlhost = "hostname";
	$sqlport = 3306;
	$sqluser = "usrname";
	$sqlpass = "password";
	$sqldb = "database";

	$forum = "/forum/";
	$bans = "/bans/";

	$query = "SELECT ip, port, countrycode, countryname, active, data, players, id, count, display, description, sourceBans, gameME FROM servers WHERE display = 1 ORDER BY ip ASC, port ASC";
	$scanquery = "SELECT last FROM lastscan";

	// Percent
	$warning = "70";
	$danger = "85";

	// Remove Bots?
	$bots = "1";

	// Enable steam connect
	$enableSteam = true;

	// Enable gametracker
	$enableGametracker = true;

	// Enable hlsw
	$enableHLSW = true;
?>
