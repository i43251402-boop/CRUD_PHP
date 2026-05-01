<?php
include_once "../controllers/BukuController.php";

$controller = new BukuController();

if(isset($_POST['simpan'])) {

    $controller->model->create($_POST['nama'], $_POST['jenis']);

    header("Location: ../index.php");
}
?>

<h2>Tambah Data Buku</h2>

<form method="POST">

Nama :
<input type="text" name="nama">
<br><br>

Jenis :
<input type="text" name="jenis">
<br><br>

<button type="submit" name="simpan">Simpan</button>

</form>