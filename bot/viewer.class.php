<?php
class viewer {
    private $database;
    public  $sourcequery;

    function __construct() {
        $this->database = new mysqli("hostname","username","password","database", 3306);
        if ($this->database->connect_errno) {
            exit();
        }
        $this->sourcequery = new SourceQuery( );
    }

    function __destruct () {
        $this->database->close( );
        $this->sourcequery->Disconnect( );
    }

    public function query($query) {
        $query = $this->database->query($query);
        if(!$query)
            printf("Errormessage: %s\n", $this->database->error);
        else
            return $query;
    }
	
	
    public function saveSource($id, $data, $players) {
        if(is_array($data)) {
            $data 	    = $this->database->real_escape_string(serialize($data));
            $players    = $this->database->real_escape_string(serialize($players));
            $time = time();

            $this->database->query("UPDATE `servers` SET `data` = '$data', `players` = '$players', `lastscan` = '$time' `lastSuccessScan` = UNIX_TIMESTAMP() WHERE `id`='$id'");
        }
        else {
            $this->database->query("UPDATE `servers` SET `data` = 'Offline', `lastscan` = '$time' WHERE `id`='$id'");
        }
    }


    public function updateGlobalScan() {
	$time = time();
        $this->database->query("UPDATE `lastscan` SET `last` = '$time'");
    }
}
