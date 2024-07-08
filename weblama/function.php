<?php
$koneksi = mysqli_connect("localhost", "root", "", "dbrestoran");
// Funtion Register
function register_akun()
{
    global $koneksi;
    $username = htmlspecialchars($_POST["username"]);
    $password = md5(htmlspecialchars($_POST["password"]));
    $konfirmasi_password = md5(htmlspecialchars($_POST["konfirmasi-password"]));
    $cek_username = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM `user` WHERE username = '$username'"));
    if ($cek_username != null) {
        echo "<script> alert('Username sudah ada!'); </script>";
        return -1;
    } else if ($password != $konfirmasi_password) {
        echo "<script> alert('Password Tidak Sesuai!'); </script>";
        return -1;
    }
    mysqli_query($koneksi, "INSERT INTO `user` VALUES ('', '$username', '$password')");
    return mysqli_affected_rows($koneksi);
}

// Function Login
function login_akun()
{
    global $koneksi;
    $username = htmlspecialchars($_POST["username"]);
    $password = md5(htmlspecialchars($_POST["password"]));
    $query = "SELECT * FROM `users` WHERE username = '$username' AND `password` = '$password'";
    $result = mysqli_query($koneksi, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user != null) {
        $_SESSION["user"] = [
            "username" => $username,
            "role" => $user['role']
        ];
        return true; // Login successful
    } else {
        return false; // Login failed
    }
}




// Function Select Data
function ambil_data($query)
{
    global $koneksi;
    $db = [];
    $sql_query = mysqli_query($koneksi, $query);
    while ($q = mysqli_fetch_assoc($sql_query)) {
        array_push($db, $q);
    }
    return $db;
}



// Function Tambah Data

function tambah_data_menu()
{
    global $koneksi;
    $nama = htmlspecialchars($_POST["nama"]);
    $harga = (int) htmlspecialchars($_POST["harga"]);
    $gambar = htmlspecialchars($_FILES["gambar"]["name"]);
    $kategori = htmlspecialchars($_POST["kategori"]);
    $status = htmlspecialchars($_POST["status"]);

    // Generate Kode Menu
    $kode_menu = "MN" . ambil_data("SELECT MAX(SUBSTR(kode_menu, 3)) AS kode FROM menu")[0]["kode"] + 1;

    // cek format gambar
    $format_gambar = ["jpg", "jpeg", "png", "gif"];
    $cek_gambar = explode(".", $gambar);
    $cek_gambar = strtolower(end($cek_gambar));
    if (!in_array($cek_gambar, $format_gambar)) {
        echo "<script> alert('File yang diupload bukan merupakan image!'); </script>";
        return -1;
    }

    // upload file
    $nama_gambar = uniqid() . ".$cek_gambar";
    move_uploaded_file($_FILES["gambar"]["tmp_name"], "src/img/$nama_gambar");

    // eksekusi query insert
    $id_menu = ambil_data("SELECT MAX(SUBSTR(kode_menu, 3)) AS kode FROM menu")[0]["kode"] + 1;
    mysqli_query($koneksi, "INSERT INTO menu,VALUES ($id_menu, '$kode_menu', '$nama', $harga, '$nama_gambar', '$kategori', '$status') ");
    return mysqli_affected_rows($koneksi);
}

// Function Edit Data Menu
function edit_data_menu()
{

    global $koneksi;
    $id_menu = $_POST["id_menu"];
    $nama = htmlspecialchars($_POST["nama"]);
    $harga = (int) htmlspecialchars($_POST["harga"]);
    $gambar = htmlspecialchars($_FILES["gambar"]["name"]);
    $kategori = htmlspecialchars($_POST["kategori"]);
    $status = htmlspecialchars($_POST["status"]);
    $kode_menu = htmlspecialchars($_POST["kode_menu"]);

    // cek format gambar
    $format_gambar = ["jpg", "jpeg", "png", "gif"];
    $cek_gambar = explode(".", $gambar);
    $cek_gambar = strtolower(end($cek_gambar));

    if (!in_array($cek_gambar, $format_gambar) && strlen($gambar) != 0) {
        echo "<script> alert('File yang diupload bukan merupakan image!'); </script>";
        return -1;
    }

    // cek jika admin mengupload gambar yang baru
    $gambar_lama = $_POST["gambar-lama"];
    if (strlen($gambar) == 0) {
        $gambar = $gambar_lama;
    } else if ($gambar != $gambar_lama && strlen($gambar) != 0) {
        move_uploaded_file($_FILES["gambar"]["tmp_name"], "src/img/$gambar");
        unlink("src/img/$gambar_lama");
    }

    // eksekusi query update
    mysqli_query($koneksi, "UPDATE menu SET kode_menu = '$kode_menu', nama = '$nama', harga = $harga, gambar = '$gambar', kategori = '$kategori', `status` = '$status' WHERE id_menu = $id_menu ");
    return mysqli_affected_rows($koneksi);
}


