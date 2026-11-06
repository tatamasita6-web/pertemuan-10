
<?php
require 'koneksi.php';

$sql = 'SELECT p.id_produk, p.nama_produk, p.harga, p.stok,
               k.nama_kategori
        FROM produk AS p
        INNER JOIN kategori AS k ON k.id_kategori = p.id_kategori
        ORDER BY p.id_produk';

$hasil = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Produk</title>

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
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .header h1 {
            color: #1e3a8a;
            font-size: 26px;
            margin-bottom: 8px;
        }

        .header p {
            color: #64748b;
            font-size: 14px;
        }

        .btn-tambah {
            background: #2563eb;
            color: white;
            text-decoration: none;
            padding: 11px 18px;
            border-radius: 7px;
            font-size: 14px;
            font-weight: bold;
        }

        .btn-tambah:hover {
            background: #1d4ed8;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #eff6ff;
            color: #1e40af;
            padding: 14px;
            text-align: left;
            font-size: 14px;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }

        tr:hover td {
            background: #f8fafc;
        }

        .harga {
            font-weight: bold;
            color: #15803d;
        }

        .stok {
            background: #dbeafe;
            color: #1d4ed8;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .aksi {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-edit {
            background: #f59e0b;
            color: white;
            text-decoration: none;
            padding: 7px 12px;
            border-radius: 5px;
            font-size: 12px;
        }

        .btn-hapus {
            background: #ef4444;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
        }

        .btn-edit:hover {
            background: #d97706;
        }

        .btn-hapus:hover {
            background: #dc2626;
        }

        .kosong {
            text-align: center;
            padding: 30px;
            color: #64748b;
        }

        .footer {
            margin-top: 20px;
            color: #94a3b8;
            font-size: 12px;
            text-align: center;
        }

        @media (max-width: 600px) {
            .container {
                padding: 18px;
            }

            .header {
                align-items: flex-start;
                flex-direction: column;
            }

            .header h1 {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>

<div class="container">

    <div class="header">
        <div>
            <h1>Data Produk</h1>
            <p>Kelola data produk dan stok barang kamu.</p>
        </div>

        <a href="tambah.php" class="btn-tambah">
            + Tambah Produk
        </a>
    </div>

    <div class="table-wrapper">
        <table>
            <tr>
                <th>No.</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>

            <?php if (mysqli_num_rows($hasil) > 0): ?>
                <?php $no = 1; ?>

                <?php while ($baris = mysqli_fetch_assoc($hasil)): ?>
                <tr>
                    <td><?= $no++ ?></td>

                    <td>
                        <strong><?= e($baris['nama_produk']) ?></strong>
                    </td>

                    <td><?= e($baris['nama_kategori']) ?></td>

                    <td class="harga">
                        Rp <?= e(number_format((float) $baris['harga'], 0, ',', '.')) ?>
                    </td>

                    <td>
                        <span class="stok">
                            <?= e($baris['stok']) ?> unit
                        </span>
                    </td>

                    <td>
                        <div class="aksi">
                            <a class="btn-edit"
                               href="edit.php?id=<?= e($baris['id_produk']) ?>">
                                Edit
                            </a>

                            <form method="post" action="hapus.php"
                                  onsubmit="return confirm('Yakin ingin menghapus produk ini?')">

                                <input type="hidden" name="id"
                                       value="<?= e($baris['id_produk']) ?>">

                                <button type="submit" class="btn-hapus">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>

            <?php else: ?>
                <tr>
                    <td colspan="6" class="kosong">
                        Belum ada data produk.
                    </td>
                </tr>
            <?php endif; ?>

        </table>
    </div>
</div>

</body>
</html>
