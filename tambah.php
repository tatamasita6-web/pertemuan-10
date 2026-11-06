
<?php
require 'koneksi.php';

$kategori = mysqli_query(
    $koneksi,
    'SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori'
);

$error = '';

$nama = trim($_POST['nama_produk'] ?? '');
$hargaInput = $_POST['harga'] ?? '';
$stokInput = $_POST['stok'] ?? '';
$kategoriInput = $_POST['id_kategori'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $harga = filter_var($hargaInput, FILTER_VALIDATE_FLOAT);
    $stok = filter_var($stokInput, FILTER_VALIDATE_INT);
    $idKategori = filter_var($kategoriInput, FILTER_VALIDATE_INT);

    if (
        $nama === '' ||
        $harga === false || $harga <= 0 ||
        $stok === false || $stok < 0 ||
        $idKategori === false || $idKategori < 1
    ) {
        $error = 'Isi semua data dengan benar. Harga harus positif dan stok tidak boleh negatif.';
    } else {

        $cek = mysqli_prepare(
            $koneksi,
            'SELECT COUNT(*) FROM kategori WHERE id_kategori = ?'
        );

        mysqli_stmt_bind_param($cek, 'i', $idKategori);
        mysqli_stmt_execute($cek);
        mysqli_stmt_bind_result($cek, $jumlahKategori);
        mysqli_stmt_fetch($cek);
        mysqli_stmt_close($cek);

        if ((int) $jumlahKategori === 0) {
            $error = 'Kategori tidak ditemukan.';
        } else {

            $sql = 'INSERT INTO produk
                    (id_kategori, nama_produk, harga, stok)
                    VALUES (?, ?, ?, ?)';

            $stmt = mysqli_prepare($koneksi, $sql);

            mysqli_stmt_bind_param(
                $stmt,
                'isdi',
                $idKategori,
                $nama,
                $harga,
                $stok
            );

            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            header('Location: index.php');
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f1f5f9;
            padding: 40px 20px;
            color: #334155;
        }

        .container {
            max-width: 550px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        }

        h1 {
            color: #1e3a8a;
            font-size: 25px;
            margin-bottom: 8px;
        }

        .deskripsi {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #334155;
        }

        input, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font-size: 14px;
            outline: none;
        }

        input:focus, select:focus {
            border-color: #2563eb;
        }

        .error {
            background: #fee2e2;
            color: #b91c1c;
            padding: 12px;
            border-radius: 7px;
            margin-bottom: 18px;
            font-size: 13px;
        }

        .tombol {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn-simpan {
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 7px;
            cursor: pointer;
            font-weight: bold;
            flex: 1;
        }

        .btn-kembali {
            background: #e2e8f0;
            color: #334155;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 7px;
            text-align: center;
        }

        .btn-simpan:hover {
            background: #1d4ed8;
        }

        .btn-kembali:hover {
            background: #cbd5e1;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Tambah Produk</h1>
    <p class="deskripsi">Silakan isi formulir untuk menambahkan produk baru.</p>

    <?php if ($error !== ''): ?>
        <div class="error">
            <?= e($error) ?>
        </div>
    <?php endif; ?>

    <form method="post">

        <div class="form-group">
            <label>Nama Produk</label>
            <input
                type="text"
                name="nama_produk"
                placeholder="Masukkan nama produk"
                value="<?= e($nama) ?>"
                required
            >
        </div>

        <div class="form-group">
            <label>Harga Produk (Rp)</label>
            <input
                type="number"
                name="harga"
                placeholder="Contoh: 4000"
                min="0.01"
                step="0.01"
                value="<?= e($hargaInput) ?>"
                required
            >
        </div>

        <div class="form-group">
            <label>Stok Produk</label>
            <input
                type="number"
                name="stok"
                placeholder="Masukkan jumlah stok"
                min="0"
                step="1"
                value="<?= e($stokInput) ?>"
                required
            >
        </div>

        <div class="form-group">
            <label>Kategori Produk</label>

            <select name="id_kategori" required>
                <option value="">Pilih kategori</option>

                <?php while ($item = mysqli_fetch_assoc($kategori)): ?>

                    <?php
                    $terpilih = (string) $kategoriInput ===
                                (string) $item['id_kategori'];
                    ?>

                    <option
                        value="<?= e($item['id_kategori']) ?>"
                        <?= $terpilih ? 'selected' : '' ?>
                    >
                        <?= e($item['nama_kategori']) ?>
                    </option>

                <?php endwhile; ?>
            </select>
        </div>

        <div class="tombol">
            <button type="submit" class="btn-simpan">
                Simpan Produk
            </button>

            <a href="index.php" class="btn-kembali">
                Kembali
            </a>
        </div>

    </form>

</div>

</body>
</html>
