<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pelaksanaan Sidang</title>
</head>

<body>
    <?php
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=Hasil Pelaksanaan Sidang.xls");
    ?>
    <table style="border: 1px solid black;border-collapse: collapse;font-size: 11px" rules="cols" width="100%" id="excel">
        <thead>
            <tr>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span> &nbsp;&nbsp;&nbsp; NIM &nbsp;&nbsp;&nbsp;</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Prodi</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Kategori Kegiatan</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Judul Kegiatan</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>&nbsp;NIP Pembimbing 1&nbsp;</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Nama Pembimbing 1</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>&nbsp;NIP Pembimbing 2&nbsp;</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Nama Pembimbing 2</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>&nbsp;NIP Ketua Penguji&nbsp;</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Nama Penguji 1</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>&nbsp;NIP Penguji 2&nbsp;</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Nama Penguji 2</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>&nbsp;NIP Penguji 3&nbsp;</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Nama Penguji 3</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>No. SK Tugas</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Tgl SK Tugas</span></th>
                <?php if ($sidang == 'skripsi') {
                ?>
                    <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Tanggal Mulai</span></th>
                    <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Tanggal Selesai</span></th>
                    <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>SMT Lulus</span></th>
                    <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Tanggal Yudisium</span></th>
                <?php } ?>
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
                    if (empty($penguji2)) {
                    }
                    $prodi = $db->query("SELECT * FROM tb_unit where idunit='" . $key2->idunit . "'")->getResult()[0]->namaunit;
                    $waktu_sempro = $db->query("SELECT a.jenis_sidang, b.waktu_sidang FROM `tb_jadwal_sidang` a LEFT JOIN tb_pendaftar_sidang b ON a.id_jadwal=b.id_jadwal WHERE b.nim = '" . $key2->nim . "' AND a.jenis_sidang = 'seminar proposal' AND b.waktu_sidang IS NOT NULL ORDER BY `b`.`waktu_sidang` DESC")->getResult();
                    if (empty($waktu_sempro)) {
                        $waktu_sempro_str = 'Jadwal Sempro Belum Ditentukan';
                    } else {
                        $waktu_sempro_str = $waktu_sempro[0]->waktu_sidang;
                    }
                    $waktu_sidang_skripsi = $db->query("SELECT a.jenis_sidang, b.waktu_sidang FROM `tb_jadwal_sidang` a LEFT JOIN tb_pendaftar_sidang b ON a.id_jadwal=b.id_jadwal WHERE b.nim = '" . $key2->nim . "' AND a.jenis_sidang = 'sidang skripsi' AND b.waktu_sidang IS NOT NULL ORDER BY `b`.`waktu_sidang` DESC")->getResult();
                    if (empty($waktu_sidang_skripsi)) {
                        $waktu_sidang_skripsi_str = 'Jadwal Sidang Skripsi Belum Ditentukan';
                    } else {
                        $waktu_sidang_skripsi_str = $waktu_sidang_skripsi[0]->waktu_sidang;
                    }

                ?>
                    <tr>
                        <td style="text-align: left; vertical-align: middle;border: 1px solid black; border-bottom: none; ">="<?= $key2->nim ?>"</td>
                        <td style="text-align: left; vertical-align: middle;border: 1px solid black; border-bottom: none; "><?= strtoupper($prodi) ?> </td>
                        <td style="text-align: left; vertical-align: middle;border: 1px solid black; border-bottom: none; "><?= $sidang == 'skripsi' ? 'SIDANG SKRIPSI' : 'SEMINAR PROPOSAL' ?> </td>
                        <td style="text-align: center; vertical-align: middle;border: 1px solid black; border-bottom: none; "><?= strtoupper($judul[0]->judul_topik) ?></td>
                        <td style="text-align: center; vertical-align: middle;border: 1px solid black; border-bottom: none; ">="<?= empty($pem1) ? '' : $pem1[0]->nip ?>"</td>
                        <td style="text-align: center; vertical-align: middle;border: 1px solid black; border-bottom: none; "><?= empty($pem1) ? '' : $pem1[0]->gelardepan . ' ' .  $pem1[0]->nama . ' ' . $pem1[0]->gelarbelakang ?></td>
                        <td style="text-align: center; vertical-align: middle;border: 1px solid black; border-bottom: none; ">="<?= empty($pem2) ? '' : $pem2[0]->nip ?>"</td>
                        <td style="text-align: center; vertical-align: middle;border: 1px solid black; border-bottom: none; "><?= empty($pem2) ? '' : $pem2[0]->gelardepan . ' ' .  $pem2[0]->nama . ' ' . $pem2[0]->gelarbelakang ?></td>
                        <td style="text-align: center; vertical-align: middle;border: 1px solid black; border-bottom: none; ">="<?= !empty($penguji1) ? $penguji1[0]->nip : '' ?>"</td>
                        <td style="text-align: center; vertical-align: middle;border: 1px solid black; border-bottom: none; "><?= !empty($penguji1) ? $penguji1[0]->gelardepan . ' ' .  $penguji1[0]->nama . ' ' . $penguji1[0]->gelarbelakang : '' ?></td>
                        <td style="text-align: center; vertical-align: middle;border: 1px solid black; border-bottom: none; ">="<?= !empty($penguji2) ? $penguji2[0]->nip : '' ?>"</td>
                        <td style="text-align: center; vertical-align: middle;border: 1px solid black; border-bottom: none; "><?= !empty($penguji2) ? $penguji2[0]->gelardepan . ' ' .  $penguji2[0]->nama . ' ' . $penguji2[0]->gelarbelakang : '' ?></td>
                        <td style="text-align: center; vertical-align: middle;border: 1px solid black; border-bottom: none; ">="<?= !empty($penguji3) ? $penguji3[0]->nip : '' ?>"</td>
                        <td style="text-align: center; vertical-align: middle;border: 1px solid black; border-bottom: none; "><?= !empty($penguji3) ? $penguji3[0]->gelardepan . ' ' .  $penguji3[0]->nama . ' ' . $penguji3[0]->gelarbelakang : '' ?></td>
                        <td style="text-align: center; vertical-align: middle;border: 1px solid black; border-bottom: none; "></td>
                        <td style="text-align: center; vertical-align: middle;border: 1px solid black; border-bottom: none; "></td>
                        <?php if ($sidang == 'skripsi') {
                        ?>
                            <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><span><?= $waktu_sempro_str  ?></span></td>
                            <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><span><?= $waktu_sidang_skripsi_str ?></span></td>
                            <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><span></span></td>
                            <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><span></span></td>
                        <?php } ?>
                    </tr>
            <?php
                    $no++;
                }
            }
            ?>
        </tbody>
    </table>
    <!-- <script src="<?= base_url(); ?>/assets/plugins/jquery/jquery.min.js"></script> -->
    <!-- <script src="<?= base_url(); ?>/assets/js/jquery.tableToExcel.js"></script> -->
    <!-- <script src="https://cdn.jsdelivr.net/gh/rainabba/jquery-table2excel@1.1.0/dist/jquery.table2excel.min.js"></script> -->
    <!-- use version 0.20.1 -->
    <!-- <script lang="javascript" src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
    <script>
        (function() {
            /* find the table element in the page */
            var tbl = document.getElementById('excel');
            /* create a workbook */
            var wb = XLSX.utils.table_to_book(tbl);
            /* export to file */
            XLSX.writeFile(wb, "SheetJSTable.xlsx", {
                width: '50'
            });
        })();
    </script> -->
</body>

</html>