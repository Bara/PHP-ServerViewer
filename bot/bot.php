#!/usr/bin/php
<?php
    require 'SourceQuery/SourceQuery.class.php';
    require 'viewer.class.php';

    $viewer = new viewer( );

    $result = $viewer->query("SELECT `id`, `ip`, `port`, `active` FROM `servers` ORDER BY `id`");
    while ($row = $result->fetch_object())
    {
        $server = array(
            "id"            => $row->id,
            "ip"            => $row->ip,
            "port"          => $row->port,
            "active"        => $row->active
        );

        try {
            if($server['active'] == 1) {
		$viewer->sourcequery->Connect($server['ip'], $server['port'], 3, 1);

		$data    = $viewer->sourcequery->GetInfo();
		$players = $viewer->sourcequery->GetPlayers();

		$viewer->saveSource($server['id'], $data, $players);

		echo $server['ip'] . ":" . $server['port'] . "\n";
            }
        }
        catch( Exception $e )
        {
            echo $e->getMessage( );
        }

        $viewer->updateGlobalScan();

    }
    $result->close();
