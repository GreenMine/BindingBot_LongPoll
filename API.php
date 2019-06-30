<?php

class API
{

    private $table_name;
    private $TABLE;

    private $vkid;
    private $player;
    private $dbinfo;

    public function __construct(\mysqli $connect, $table_name)
    {
        $this->connect = $connect;
        $this->table_name = $table_name;
        $this->exec();
    }

    private function exec() {
        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
            exit();
        }
        $this->connect->set_charset('utf8');
        $check = mysqli_fetch_row($this->connect->query('CHECK TABLE '. $this->table_name));
        if($check[2] == 'Error') {
            exit('База данных не создана. Сначала надо запустить MINECRAFT сервер.'. PHP_EOL .'Полный ответ MySql: '. $check[3]);
        }
    }

    public function setId($vkid): bool {
        $this->dbinfo = $this->getInfo('vkid', $vkid);
        return true;
    }

    public function setPlayer($player): bool {
        $this->dbinfo = $this->getInfo('player', $player);
        return true;
    }

    public function getDBInfo(): array {
        return $this->dbinfo;
    }

    public function bindingAccount($nick, $vkid, $first_name, $last_name, $sub = 0, $state = 1) {
        $this->connect->query("UPDATE $this->table_name SET vkid='$vkid', VKFirstName='$first_name', VKLastName='$last_name', State='$state', sub='$sub' WHERE Player='$nick'");
    }

    public function getInfo($name, $data) {
        return mysqli_fetch_row($this->connect->query("SELECT * FROM ".$this->table_name." WHERE ". $name ." = '$data'"));
    }

    public function updateSubscribe($sub) {
        $this->connect->query("UPDATE $this->table_name SET sub='$sub' WHERE vkid='$this->vkid'");
        return true;
    }

    public function checkBinding(): bool {
        if(!empty($this->getInfo('vkid', $this->vkid))) {
            return true;
        }
        return false;
    }

}