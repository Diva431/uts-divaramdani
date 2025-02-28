<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=data_mahasiswa.xls");

$koneksi = mysqli_connect("localhost", "root", "", "akademik");
$sql = "SELECT * FROM mahasiswa";
$result = mysqli_query($koneksi, $sql);

echo "ID\tNIM\tNAMA\tKELAS\tPRODI\n";
while ($row = mysqli_fetch_assoc($result)) {
    echo "{$row['ID']}\t{$row['NIM']}\t{$row['NAMA']}\t{$row['KELAS']}\t{$row['PRODI']}\n";
}
?>
