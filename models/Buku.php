<?php

class Buku {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        return $this->conn->query("SELECT * FROM buku");
    }

    public function getById($id) {
        return $this->conn->query("SELECT * FROM buku WHERE id='$id'");
    }

    public function create($nama, $jenis) {
        return $this->conn->query("INSERT INTO buku(nama_buku, jenis_buku) 
                VALUES('$nama', '$jenis')");
    }

    public function update($id, $nama, $jenis) {
        return $this->conn->query("UPDATE buku SET nama='$nama', 
jurusan='$jenis' WHERE id='$id'");
    }

    public function delete($id) {
        return $this->conn->query("DELETE FROM buku WHERE id='$id'");
    }
}
?>