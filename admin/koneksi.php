<?php
$koneksi = mysqli_connect("localhost", "root", "", "smsr_jaya");


if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
