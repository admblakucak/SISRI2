<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <!-- Title -->
    <title> SISRI - <?= $title; ?></title>
    <style type="text/css">
        p {
            margin: 5px 0 0 0;
        }

        p.footer {
            text-align: right;
            font-size: 11px;
            border-top: 1px solid #D0D0D0;
            line-height: 32px;
            padding: 0 10px 0 10px;
            margin: 20px 0 0 0;
            display: block;
        }

        .bold {
            font-weight: bold;
        }

        #footer {
            clear: both;
            position: relative;
            height: 40px;
            margin-top: -40px;
        }

        @media print {
            .table_footer {
                display: table-footer-group;
                position: fixed;
                bottom: 0;
            }
        }
    </style>
    <link href="<?= base_url() ?>/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="font-size: 12px">
    <table width="100%" style="border: 1px solid black;border-collapse: collapse;">
        <tr>
            <td style="border: 1px solid black;text-align:center;padding: 10px;" rowspan="2">
                <img src="https://upload.wikimedia.org/wikipedia/commons/f/f1/UTM_DIKBUDRISTEK.png" style="width: 100px;">
            </td>
            <td style="text-align:center;font-size: 18px;border: 1px solid black;"><b>HASIL PELAKSANAAN SIDANG</b></td>
            <td style="border: 1px solid black;padding: 5px;text-align:center;" colspan="2">Periode : <b><?= date('d-m-Y', strtotime($date1)) ?></b> hingga <b><?= date('d-m-Y', strtotime($date2)) ?></b> </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:center;font-size: 18px;border: 1px solid black;"><b>DATA DOSEN <?= strtoupper($sebagai) ?> SKRIPSI <?= strtoupper($namaunit) ?></b></td>
            <td style="border: 1px solid black;padding: 5px;text-align:center;">Tanggal : <b><?= date('d-m-Y') ?></b></td>
        </tr>
    </table>
    <table style="border: 1px solid black;border-collapse: collapse;font-size: 11px" rules="cols" width="100%">
        <thead>
            <tr>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>No.</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Mahasiswa/NPM</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Judul Skripsi</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Dosen <?= $sebagai ?></span></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($tb_dosen as $key) {
                $data_mhs = $db->query("SELECT * FROM tb_nilai a LEFT JOIN tb_mahasiswa b ON a.`nim`=b.`nim` WHERE nip='$key->nip' AND (create_at BETWEEN '$date1' AND ('$date2'+ INTERVAL 1 DAY))")->getResult();
            ?>

                <?php
                foreach ($data_mhs as $key2) {
                    $mhs = $db->query("SELECT * FROM tb_mahasiswa WHERE nim = '" . $key2->nim . "'")->getResult();
                    $judul = $db->query("SELECT * FROM tb_pengajuan_topik WHERE nim = '" . $key2->nim . "'")->getResult();
                    $pem1 = $db->query("SELECT a.*,b.nama,b.gelardepan,b.gelarbelakang FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.nip=b.nip WHERE a.nim = '" . $key2->nim . "' AND a.sebagai='1' AND a.status_pengajuan='diterima'")->getResult();
                    $pem2 = $db->query("SELECT a.*,b.nama,b.gelardepan,b.gelarbelakang FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.nip=b.nip WHERE a.nim = '" . $key2->nim . "' AND a.sebagai='2' AND a.status_pengajuan='diterima'")->getResult();
                    $penguji1  = $db->query("SELECT a.*, b.nama,b.gelardepan,b.gelarbelakang  FROM tb_penguji a LEFT JOIN tb_dosen b ON a.nip=b.nip WHERE a.nim='" . $key2->nim  . "' AND a.status='aktif' AND a.jenis_sidang = 'sidang skripsi' AND a.sebagai=1")->getResult();
                    if (empty($penguji1)) {
                        $penguji1  = $db->query("SELECT a.*, b.nama,b.gelardepan,b.gelarbelakang  FROM tb_penguji a LEFT JOIN tb_dosen b ON a.nip=b.nip WHERE a.nim='" . $key2->nim  . "' AND a.status='aktif' AND a.jenis_sidang = '' AND a.sebagai=1")->getResult();
                    }
                    $penguji2  = $db->query("SELECT a.*, b.nama,b.gelardepan,b.gelarbelakang  FROM tb_penguji a LEFT JOIN tb_dosen b ON a.nip=b.nip WHERE a.nim='" . $key2->nim  . "' AND a.status='aktif' AND a.jenis_sidang = 'sidang skripsi' AND a.sebagai=2")->getResult();
                    if (empty($penguji2)) {
                        $penguji2  = $db->query("SELECT a.*, b.nama,b.gelardepan,b.gelarbelakang  FROM tb_penguji a LEFT JOIN tb_dosen b ON a.nip=b.nip WHERE a.nim='" . $key2->nim  . "' AND a.status='aktif' AND a.jenis_sidang = '' AND a.sebagai=2")->getResult();
                    }
                    $penguji3  = $db->query("SELECT a.*, b.nama,b.gelardepan,b.gelarbelakang  FROM tb_penguji a LEFT JOIN tb_dosen b ON a.nip=b.nip WHERE a.nim='" . $key2->nim  . "' AND a.status='aktif' AND a.jenis_sidang = 'sidang skripsi' AND a.sebagai=3")->getResult();
                    if (empty($penguji3)) {
                        $penguji3  = $db->query("SELECT a.*, b.nama,b.gelardepan,b.gelarbelakang  FROM tb_penguji a LEFT JOIN tb_dosen b ON a.nip=b.nip WHERE a.nim='" . $key2->nim  . "' AND a.status='aktif' AND a.jenis_sidang = '' AND a.sebagai=3")->getResult();
                    }
                ?>
                    <tr>
                        <td style="text-align: center; vertical-align: middle;border: 1px solid black; border-bottom: none; "><?= $no ?></td>
                        <td style="text-align: left; vertical-align: middle;border: 1px solid black; border-bottom: none; "><?= $key2->nama ?> <br> <?= $key2->nim ?> </td>
                        <td style="text-align: center; vertical-align: middle;border: 1px solid black; border-bottom: none; "><?= strtoupper($judul[0]->judul_topik) ?></td>
                        <td style="text-align: left; vertical-align: middle;border: 1px solid black; border-bottom: none; ">
                            <ol type="1">
                                <?php if ($sebagai == 'pembimbing') { ?>
                                    <li><?= $pem1[0]->gelardepan . ' ' . $pem1[0]->nama . ', ' . $pem1[0]->gelarbelakang; ?></li>
                                    <li><?= $pem2[0]->gelardepan . ' ' . $pem2[0]->nama . ', ' . $pem2[0]->gelarbelakang; ?></li>
                                <?php } else { ?>
                                    <li><?= !empty($penguji1) ?  $penguji1[0]->gelardepan . ' ' . $penguji1[0]->nama . ', ' . $penguji1[0]->gelarbelakang : '' ?></li>
                                    <li><?= !empty($penguji2) ? $penguji2[0]->gelardepan . ' ' . $penguji2[0]->nama . ', ' . $penguji2[0]->gelarbelakang : '' ?></li>
                                    <li><?= !empty($penguji3) ? $penguji3[0]->gelardepan . ' ' . $penguji3[0]->nama . ', ' . $penguji3[0]->gelarbelakang : '' ?></li>
                                <?php } ?>
                            </ol>
                        </td>
                    </tr>
            <?php
                    $no++;
                }
            }
            ?>
        </tbody>
    </table>
    <p class="footer">
    <table width='100%'>
        <tr>
            <td align="center" valign='top'>
                <?= $qr_link ?><br>SCAN ME
            </td>
            <td align="right" valign='top'>
                <small>Fakultas Teknik - Universitas Trunojoyo Madura</small>
            </td>
        </tr>
    </table>
    </p>
    <script>
        function print() {
            window.print();
        }
    </script>
</body>

</html>