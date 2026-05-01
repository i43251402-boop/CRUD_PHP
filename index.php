<?php

include_once "controllers/BukuController.php";

$controller = new BukuController();

if(isset($_GET['hapus'])) {

    $controller->model->delete($_GET['hapus']);

    header("Location: index.php");
}

include_once "views/list.php";

?>