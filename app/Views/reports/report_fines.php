<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
    <title>Laporan Denda Anggota</title>
    <style>
        /* Tambahkan gaya CSS sesuai kebutuhan */
        body {
            font-family: Arial, sans-serif;
        }

        .hr-one {
            margin: 0px;
            margin-bottom: 1px;
            color: #000000;
            height: 2px !important;
            opacity: 1;
        }

        .hr-two {
            margin: 0px;
            color: #000000;
            height: 5px !important;
            opacity: 1;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        .table th {
            background-color: #f2f2f2;
        }

        .footer {
            float: right;
            margin-top: 100px;
        }
    </style>
</head>

<body>
    <table border="0" width="100%">
        <tr>
            <td align="center">
              <img src="https://i.ibb.co.com/CtnbyXW/logodinas.png" alt="Logo" width="60">
            </td>
            <td align="center">
                <b style="font-size: 23px;">PEMERINTAH PROVINSI KALIMANTAN SELATAN</b><br>
                <b style="font-size: 20px;">DINAS PERPUSTAKAAN DAN KEARSIPAN</b><br>
                <p>Alamat: Jl. A. Yani Km. 6,400 No. 6, Pemurus Luar<br>Kec. Banjarmasin Timur., Kota Banjarmasin<br>Kalimantan Selatan 70249</p>
            </td>
        </tr>
        <tr>
            <td colspan="3" align="center">
                <hr class="hr-one">
                <hr class="hr-two">
            </td>
        </tr>
    </table>

    <!-- Main container -->
    <div class="mt-5">
        <!-- Card for the report -->
        <div class="card">
            <div class="card-body">
                <h2 class="card-title fw-bold mb-4 text-center">Laporan Denda Anggota</h2>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Nama Peminjam</th>
                                <th scope="col">Judul Buku</th>
                                <th scope="col">Denda Dibayar</th>
                                <th scope="col">Jumlah Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($fines as $fine) : ?>
                                <tr>
                                    <td><?= $fine['first_name'] . ' ' . $fine['last_name'] ?></td>
                                    <td><?= $fine['title'] ?></td>
                                    <td>Rp<?= $fine['amount_paid'] ?? 0 ?></td>
                                    <td>Rp<?= $fine['fine_amount'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- End of table-responsive -->

                <?php
                $months = [
                    'January' => 'Januari',
                    'February' => 'Februari',
                    'March' => 'Maret',
                    'April' => 'April',
                    'May' => 'Mei',
                    'June' => 'Juni',
                    'July' => 'Juli',
                    'August' => 'Agustus',
                    'September' => 'September',
                    'October' => 'Oktober',
                    'November' => 'November',
                    'December' => 'Desember',
                ];

                $currentMonth = date('F');
                $month = $months[$currentMonth];
                ?>

                <div class="footer">
                    Banjarmasin, <?php echo date('d'); ?> <?php echo $month; ?> <?php echo date('Y'); ?> <br>
                    Kepala Dinas <br>
                    <div class="container text-center mt-3 mb-3">
                        <img src="https://i.ibb.co.com/8cJQMB5/qrcode.png" alt="Qr" width="70">
                    </div>
                    <?= $kepdin['full_name']; ?> <br>
                    NIP. <?= $kepdin['nip']; ?>
                </div>
                
            </div>
        </div>
        <!-- End of card -->
    </div>
    <!-- End of main container -->

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
</body>

</html>