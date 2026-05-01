<?php

include_once dirname(__DIR__) . "/config/Database.php";
include_once dirname(__DIR__) . "/models/Buku.php";

class BukuController {

    public $model;

    public function __construct() {
        $database = new Database();
        $db = $database->connect();

        $this->model = new Buku($db);
    }
}
?>