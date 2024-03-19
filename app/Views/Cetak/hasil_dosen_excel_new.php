<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pelaksanaan Sidang</title>
    <style type="text/css">
        .textclass {
            font-weight: bold;
            mso-number-format: "\@";
            text-align: right
        }
    </style>
</head>

<body>
    <?php
    // header("Content-type: application/vnd-ms-excel");
    // header("Content-Disposition: attachment; filename=Hasil Pelaksanaan Sidang.xls");
    ?>
    <table id="excel">
        <thead>
            <tr>
                <th><span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; NIM &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></th>
                <th><span>Prodi</span></th>
                <th><span>Kategori Kegiatan</span></th>
                <th><span>Judul Kegiatan</span></th>
                <th><span>&nbsp;&nbsp;&nbsp;NIP Pembimbing 1&nbsp;&nbsp;&nbsp;</span></th>
                <th><span>Nama Pembimbing 1</span></th>
                <th><span>&nbsp;&nbsp;&nbsp;NIP Pembimbing 2&nbsp;&nbsp;&nbsp;</span></th>
                <th><span>Nama Pembimbing 2</span></th>
                <th><span>&nbsp;&nbsp;&nbsp;NIP Ketua Penguji&nbsp;&nbsp;&nbsp;</span></th>
                <th><span>Nama Penguji 1</span></th>
                <th><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NIP Penguji 2&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></th>
                <th><span>Nama Penguji 2</span></th>
                <th><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NIP Penguji 3&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></th>
                <th><span>Nama Penguji 3</span></th>
                <th><span>No. SK Tugas</span></th>
                <th><span>Tgl SK Tugas</span></th>
                <?php if ($sidang == 'skripsi') {
                ?>
                    <th><span>Tanggal Mulai</span></th>
                    <th><span>Tanggal Selesai</span></th>
                    <th><span>SMT Lulus</span></th>
                    <th><span>Tanggal Yudisium</span></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $data_nim = [];
            foreach ($tb_dosen as $key) {
                $data_mhs = $db->query("SELECT * FROM tb_nilai a LEFT JOIN tb_mahasiswa b ON a.`nim`=b.`nim` WHERE nip='$key->nip' AND (create_at BETWEEN '$date1' AND ('$date2'+ INTERVAL 1 DAY))")->getResult();
            ?>

                <?php
                foreach ($data_mhs as $key2) {
                    if ($sidang == 'sempro') {
                        $cek_sidang = $db->query("SELECT a.nim,b.jenis_sidang FROM `tb_pendaftar_sidang` a LEFT JOIN tb_jadwal_sidang b on a.id_jadwal=b.id_jadwal WHERE a.nim = '" . $key2->nim . "' AND b.jenis_sidang = 'seminar proposal'")->getResult();
                        $penguji1  = $db->query("SELECT a.*, b.nama,b.gelardepan,b.gelarbelakang  FROM tb_penguji a LEFT JOIN tb_dosen b ON a.nip=b.nip WHERE a.nim='" . $key2->nim  . "' AND a.status='aktif' AND a.jenis_sidang = '' AND a.sebagai=1")->getResult();
                        $penguji2  = $db->query("SELECT a.*, b.nama,b.gelardepan,b.gelarbelakang  FROM tb_penguji a LEFT JOIN tb_dosen b ON a.nip=b.nip WHERE a.nim='" . $key2->nim  . "' AND a.status='aktif' AND a.jenis_sidang = '' AND a.sebagai=2")->getResult();
                        $penguji3  = $db->query("SELECT a.*, b.nama,b.gelardepan,b.gelarbelakang  FROM tb_penguji a LEFT JOIN tb_dosen b ON a.nip=b.nip WHERE a.nim='" . $key2->nim  . "' AND a.status='aktif' AND a.jenis_sidang = '' AND a.sebagai=3")->getResult();
                    } else {
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
                        $cek_sidang = $db->query("SELECT a.nim,b.jenis_sidang FROM `tb_pendaftar_sidang` a LEFT JOIN tb_jadwal_sidang b on a.id_jadwal=b.id_jadwal WHERE a.nim = '" . $key2->nim . "' AND b.jenis_sidang = 'sidang skripsi'")->getResult();
                    }
                    if (empty($cek_sidang)) {
                        continue;
                    }
                    if (array_search($key2->nim, $data_nim) !== false) {
                        continue;
                    } else {
                        array_push($data_nim, $key2->nim);
                    }
                    $mhs = $db->query("SELECT * FROM tb_mahasiswa WHERE nim = '" . $key2->nim . "'")->getResult();
                    $judul = $db->query("SELECT * FROM tb_pengajuan_topik WHERE nim = '" . $key2->nim . "'")->getResult();
                    $pem1 = $db->query("SELECT a.*,b.nama,b.gelardepan,b.gelarbelakang FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.nip=b.nip WHERE a.nim = '" . $key2->nim . "' AND a.sebagai='1' AND a.status_pengajuan='diterima'")->getResult();
                    $pem2 = $db->query("SELECT a.*,b.nama,b.gelardepan,b.gelarbelakang FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.nip=b.nip WHERE a.nim = '" . $key2->nim . "' AND a.sebagai='2' AND a.status_pengajuan='diterima'")->getResult();
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
                    <tr style="width: 100px;">
                        <td style="mso-number-format:" @"" class="textclass">="<?= $key2->nim ?>"</td>
                        <td style="mso-number-format:" @""><?= strtoupper($prodi) ?> </td>
                        <td style="mso-number-format:" @""><?= $sidang == 'skripsi' ? 'SIDANG SKRIPSI' : 'SEMINAR PROPOSAL' ?> </td>
                        <td style="width: max-content;"><?= strtoupper($judul[0]->judul_topik) ?></td>
                        <td>="<?= empty($pem1) ? '' : $pem1[0]->nip ?>"</td>
                        <td><?= empty($pem1) ? '' : $pem1[0]->gelardepan . ' ' .  $pem1[0]->nama . ' ' . $pem1[0]->gelarbelakang ?></td>
                        <td>="<?= empty($pem2) ? '' : $pem2[0]->nip ?>"</td>
                        <td><?= empty($pem2) ? '' : $pem2[0]->gelardepan . ' ' .  $pem2[0]->nama . ' ' . $pem2[0]->gelarbelakang ?></td>
                        <td>="<?= !empty($penguji1) ? $penguji1[0]->nip : '' ?>"</td>
                        <td><?= !empty($penguji1) ? $penguji1[0]->gelardepan . ' ' .  $penguji1[0]->nama . ' ' . $penguji1[0]->gelarbelakang : '' ?></td>
                        <td>="<?= !empty($penguji2) ? $penguji2[0]->nip : '' ?>"</td>
                        <td><?= !empty($penguji2) ? $penguji2[0]->gelardepan . ' ' .  $penguji2[0]->nama . ' ' . $penguji2[0]->gelarbelakang : '' ?></td>
                        <td>="<?= !empty($penguji3) ? $penguji3[0]->nip : '' ?>"</td>
                        <td><?= !empty($penguji3) ? $penguji3[0]->gelardepan . ' ' .  $penguji3[0]->nama . ' ' . $penguji3[0]->gelarbelakang : '' ?></td>
                        <td></td>
                        <td></td>
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
    <a id="dlink" style="display:none;"></a>

    <script>
        (function() {
            var tableToExcel = (function() {
                var uri = 'data:application/vnd.ms-excel;base64,',
                    template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
                    base64 = function(s) {
                        return window.btoa(unescape(encodeURIComponent(s)))
                    },
                    format = function(s, c) {
                        return s.replace(/{(\w+)}/g, function(m, p) {
                            return c[p];
                        })
                    }
                return function(table, name, filename) {
                    if (!table.nodeType) table = document.getElementById(table)
                    var ctx = {
                        worksheet: name || 'Worksheet',
                        table: table.innerHTML
                    }

                    document.getElementById("dlink").href = uri + base64(format(template, ctx));
                    document.getElementById("dlink").download = filename;
                    document.getElementById("dlink").traget = "_blank";
                    document.getElementById("dlink").click();
                }
            })();
            tableToExcel('excel', '<?= $sidang == 'sempro' ? 'SEMINAR PROPOSAL' : 'SIDANG SKRIPSI' ?>', 'Data Skripsi' + '.xls')
            history.back()
        })()
    </script>

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