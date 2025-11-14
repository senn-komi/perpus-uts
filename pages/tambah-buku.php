<?php
//proteksi agar file tidak dapat diakses langsung
if(!defined('MY_APP')) {
    die('Akses langsung tidak diperbolehkan!');
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $penerbit = $_POST['penerbit'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $stok = $_POST['stok'];

    $cover_buku_name = null;
    if (!empty($_FILES['cover_buku']['name'])) {
        $target_dir = __DIR__. "/uploads/buku/";
        if (!file_exists($target_dir)) {
        }

        $target_file = $target_dir . basename($_FILES['cover_buku']['name']);

        if(move_uploaded_file($_FILES["cover_buku"]["tmp_name"], $target_file)) {
            $cover_buku_name = basename($_FILES['cover_buku']['name']);
            echo "<script>alert('Berhasil Menambahkan Buku!'); </script>";
        } else {
            echo "<script>alert('Upload gagal! coba periksa izin folder uploads/buku'); </script>";
        }
    }

    $sql = "INSERT INTO buku (judul, penulis, penerbit, tahun_terbit, stok, cover_buku ) VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("sssiis", $judul, $penulis, $penerbit, $tahun_terbit, $stok, $cover_buku_name);
        if ($stmt->execute()) {
            $id_buku = $stmt->insert_id;
            if(!empty($_POST['kategori'])) {
                foreach($_POST['kategori'] as $id_kategori) {
                    $mysqli->query("INSERT INTO buku_kategori (id_buku, id_kategori) VALUES ($id_buku, $id_kategori)");
                }
           }
        
            $pesan = "Buku Berhasil di Tambahkan";
        } else {
           $pesan_error = "Gagal Menambahkan Buku";       
        }
        $stmt->close();
    }
}

?>

<div class="container-fluid px-4">
    <h1 class="mt-4">Buku</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Tambah Buku</li>
    </ol>

    <?php if(!empty($pesan)) : ?>
        <div class="alert alert-success" role="alert">
            <?php echo $pesan; ?>
        </div>
    <?php endif; ?>

    <?php if(!empty($pesan_error)) : ?>
        <div class="alert alert-success" role="alert">
            <?php echo $pesan_error; ?>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-body">
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Buku</label>
                    <input type="text" name="judul" class="form-control" id="judul" required>
                </div> 

                <div class="mb-3">
                    <label for="kategori" class="form-label">Pilih Kategori</label><br>
                    

                        <?php
                        $sql_kategori = "SELECT * FROM kategori ORDER BY nama_kategori ASC";
                        $result_kategori = $mysqli->query($sql_kategori);
                        ?>

                        <?php while ($kat = $result_kategori->fetch_assoc()) : ?>
                            <label class="me-3">

                                <input type="checkbox" name="kategori[]" value="<?php echo $kat['id_kategori'] ?>"><?php echo $kat['nama_kategori'] ?></input>                       
                        
                            </label>
                    <?php endwhile;
                    $mysqli->close(); ?>
                </div>

                <div class="mb-3">
                    <label for="penulis" class="form-label">Penulis</label>
                    <input type="text" class="form-control" id="penulis" name="penulis" required>
                </div>
                <div class="mb-3">
                    <label for="penerbit" class="form-label">Penerbit</label>
                    <input type="text" class="form-control" id="penerbit" name="penerbit" required>
                </div>
                <div class="mb-3">
                    <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                    <input type="text" class="form-control" id="tahun_terbit" name="tahun_terbit" required>
                </div>
                <div class="mb-3">
                    <label for="stok" class="form-label">Stok</label>
                    <input type="text" class="form-control" id="stok" name="stok" required>
                </div>
                <div class="mb-3">
                    <label for="cover_buku" class="form-label">Upload Cover</label>
                    <input type="file" class="form-control" id="cover_buku" name="cover_buku" >
                </div>            

             <button type="submit" class="btn btn-primary">Simpan</button>
             <a href="index.php?hal=daftar-buku" class="btn btn-danger">Kembali</a>
        </form> 
        </div>
    </div>
</div