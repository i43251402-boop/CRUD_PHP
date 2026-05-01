<?php
include_once "../controllers/BukuController.php";

$controller = new BukuController();

$id = $_GET['id'];

$data = $controller->model->getById($id);
$row = $data->fetch_assoc();

if (isset($_POST['update'])) {

    $controller->model->update(
        $id,
        $_POST['nama'],
        $_POST['jenis']
    );

    header("Location: ../index.php");
}
?>

<h2>Edit Data Buku</h2>

<form method="POST">
    Nama:
    <input type="text" name="nama" value="<?= $row['nama']; ?>">
    <br><br>

    Jurusan:
    <input type="text" name="jenis" value="<?= $row['jenis']; ?>">
    <br><br>

    <button type="submit" name="update">Update</button>
</form>