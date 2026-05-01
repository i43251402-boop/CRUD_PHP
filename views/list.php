<?php
include_once "controllers/BukuController.php";

$controller = new BukuController();
$data = $controller->model->getAll();
?>

<h2>Data Buku</h2>

<a href="views/tambah.php">Tambah Data</a>
<br><br>

<table border="1" cellpadding="10">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Jenis</th>
        <th>Aksi</th>
    </tr>

    <?php
    $no = 1;
    while ($row = $data->fetch_assoc()) {
    ?>
    <tr>
        <td><?= $no++; ?></td>
        <td><?= $row['nama_buku']; ?></td>
        <td><?= $row['jenis_buku']; ?></td>
        <td>
            <a href="views/edit.php?id=<?= $row['id_buku']; ?>">Edit</a>
            <a href="index.php?hapus=<?= $row['id_buku']; ?>">Hapus</a>
        </td>
    </tr>
    <?php } ?>
</table>