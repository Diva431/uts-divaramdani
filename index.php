<!-- Fitur Dark Mode (JavaScript & CSS) -->
<script>
    function toggleDarkMode() {
        document.body.classList.toggle("dark-mode");
        localStorage.setItem("darkMode", document.body.classList.contains("dark-mode") ? "enabled" : "disabled");
    }

    window.onload = function() {
        if (localStorage.getItem("darkMode") === "enabled") {
            document.body.classList.add("dark-mode");
        }
    };
</script>
<style>
    body.dark-mode {
        background: #121212;
        color: white;
    }
    .dark-mode .card, .dark-mode .table {
        background: #1e1e1e;
        color: white;
    }
    .dark-mode .btn-primary {
        background: #bb86fc;
    }
</style>

<button onclick="toggleDarkMode()" class="btn btn-dark">🌙 Dark Mode</button>


<!--  Cek Session Login & Navigasi -->
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<a href="export.php" class="btn btn-success">Export ke Excel</a>

<a href="logout.php" class="btn btn-danger">Logout</a>

<link rel="stylesheet" href="style.css">

<!-- KONEKSI KE DATABASE -->
<?php
$host       = "localhost";
$user       = "root";
$pass       = "";
$db         = "akademik";

$koneksi    = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}

$NIM        = "";
$NAMA       = "";
$KELAS      = "";
$PRODI      = "";
$sukses     = "";
$error      = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

//DELETE DATA

if($op == 'delete'){
    $ID         = $_GET['ID'];
    $sql1       = "delete from mahasiswa where id = '$ID'";
    $q1         = mysqli_query($koneksi,$sql1);
    if($q1){
        $sukses = "Berhasil hapus data";
    }else{
        $error  = "Gagal melakukan delete data";
    }
}

//EDIT DATA

if ($op == 'edit') {
    $ID         = $_GET['ID'];
    $sql1       = "select * from mahasiswa where id = '$ID'";
    $q1         = mysqli_query($koneksi, $sql1);
    $r1         = mysqli_fetch_array($q1);
    $NIM        = $r1['NIM'];
    $NAMA       = $r1['NAMA'];
    $KELAS     = $r1['KELAS'];
    $PRODI      = $r1['PRODI'];

    if ($NIM == '') {
        $error = "Data tidak ditemukan";
    }
}

// INSERT DATA

if (isset($_POST['simpan'])) { // Untuk Create atau Insert Data
    // Sanitize input untuk menghindari SQL Injection
    $NIM    = mysqli_real_escape_string($koneksi, $_POST['NIM']);
    $NAMA   = mysqli_real_escape_string($koneksi, $_POST['NAMA']);
    $KELAS  = mysqli_real_escape_string($koneksi, $_POST['KELAS']);
    $PRODI  = mysqli_real_escape_string($koneksi, $_POST['PRODI']);

    if ($NIM && $NAMA && $KELAS && $PRODI) {
        // Cek apakah NIM sudah ada
        $sql_check = "SELECT 1 FROM mahasiswa WHERE NIM = '$NIM' LIMIT 1";
        $q_check = mysqli_query($koneksi, $sql_check);

        if (mysqli_num_rows($q_check) > 0) {
            $error = "NIM sudah terdaftar, silakan gunakan NIM lain.";
        } else {
            // Lakukan INSERT jika NIM belum ada
            $sql1   = "INSERT INTO mahasiswa (NIM, NAMA, KELAS, PRODI) VALUES ('$NIM', '$NAMA', '$KELAS', '$PRODI')";
            $q1     = mysqli_query($koneksi, $sql1);
            
            if ($q1) {
                $sukses = "Berhasil memasukkan data baru";
            } else {
                $error = "Gagal memasukkan data: " . mysqli_error($koneksi); // Tampilkan error SQL jika gagal
            }
        }
    } else {
        $error = "Silakan masukkan semua data!";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mahasiswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 800px
        }

        .card {
            margin-top: 10px;
        }
    </style>
</head>

<!-- UNTUK MEMASUKAN DATA -->

<body>
    <div class="mx-auto">
        <div class="card">
            <div class="card-header">
                Create / Edit Data
            </div>
            <div class="card-body">
                <?php
                if ($error) {
                ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error ?>
                    </div>
                <?php
                    header("refresh:1;url=index.php");// : 1 detik
                }
                ?>
                <?php
                if ($sukses) {
                ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $sukses ?>
                    </div>
                <?php
                    header("refresh:1;url=index.php");
                }

// FORM INPUT DATA

                ?>
                <form action="" method="POST">
                    <div class="mb-3 row">
                        <label for="NIM" class="col-sm-2 col-form-label">NIM</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="NIM" NAME="NIM" value="<?php echo $NIM ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="NAMA" class="col-sm-2 col-form-label">NAMA</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nama" NAME="NAMA" value="<?php echo $NAMA ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="KELAS" class="col-sm-2 col-form-label">KELAS</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="ALAMAT" NAME="KELAS" value="<?php echo $KELAS ?>">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="PRODI" class="col-sm-2 col-form-label">PRODI</label>
                        <div class="col-sm-10">
                            <select class="form-control" NAME="PRODI" id="PRODI">
                                <option value="">- Pilih prodi -</option>
                                <option value="Rekayasa Keamanan Siber" <?php if ($PRODI == "Rekayasa Keamanan Siber") echo "selected" ?>>Rekayasa Keamanan Siber</option>
                                <option value="Teknik Informatika" <?php if ($PRODI == "Teknik Informatika") echo "selected" ?>>Teknik Informatika</option>
                                <option value="Teknik Rekayasa Multimedia" <?php if ($PRODI == "Teknik Rekayasa Multimedia") echo "selected" ?>>Teknik Rekayasa Multimedia</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>

<!-- UNTUK MENGELUARKAN DATA -->

        <div class="card">
            <div class="card-header text-white bg-secondary">
                Data Mahasiswa
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">NIM</th>
                            <th scope="col">NAMA</th>
                            <th scope="col">KELAS</th>
                            <th scope="col">PRODI</th>
                            <th scope="col">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

//TABEL DATA MAHASISWA

                        $sql2   = "select * from mahasiswa order by id desc";
                        $q2     = mysqli_query($koneksi, $sql2);
                        $urut   = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $ID         = $r2['ID'];
                            $NIM        = $r2['NIM'];
                            $NAMA       = $r2['NAMA'];
                            $KELAS     = $r2['KELAS'];
                            $PRODI      = $r2['PRODI'];

                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td scope="row"><?php echo $NIM ?></td>
                                <td scope="row"><?php echo $NAMA?></td>
                                <td scope="row"><?php echo $KELAS ?></td>
                                <td scope="row"><?php echo $PRODI ?></td>
                                <td scope="row">
                                    <a href="index.php?op=edit&ID=<?php echo $ID?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                    <a href="index.php?op=delete&ID=<?php echo $ID?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">Delete</button></a>            
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>
</body>

</html>