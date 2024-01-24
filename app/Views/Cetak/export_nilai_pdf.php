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
    </style>
    <link href="<?= base_url() ?>/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="font-size: 12px">
    <table width="100%" style="border: 1px solid black;border-collapse: collapse;">
        <tr>
            <td style="border: 1px solid black;text-align:center;padding: 10px;" rowspan="2">
                <img src="https://upload.wikimedia.org/wikipedia/commons/f/f1/UTM_DIKBUDRISTEK.png" style="width: 100px;">
            </td>
            <td style="text-align:center;font-size: 18px;border: 1px solid black;" colspan="3"><b>NILAI MAHASIWA</b></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:center;font-size: 18px;border: 1px solid black;"><b>DATA DOSEN <?= strtoupper($namaunit) ?></b></td>
            <td style="border: 1px solid black;padding: 5px;text-align:center;">Tanggal : <b><?= date('d-m-Y') ?></b></td>
        </tr>
    </table>
    <table style="border: 1px solid black;border-collapse: collapse;font-size: 11px" rules="cols" width="100%">
        <thead>
            <tr>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>No.</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>NIM</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Nama</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Judul Skripsi</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Nilai Bimbingan</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Nilai Ujian Skripsi</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Nilai Akhir</span></th>
                <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Grade</span></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($data_mhs as $key) {
                $mhs = $db->query("SELECT * FROM tb_mahasiswa WHERE nim = '" . $key->id . "'")->getResult();
                $judul = $db->query("SELECT * FROM tb_pengajuan_topik WHERE nim = '" . $key->id . "'")->getResult();
                $nilai = $db->query("SELECT * FROM tb_mahasiswa WHERE nim = '" . $key->id . "'")->getResult();
                $pembimbing1 = $db->query("SELECT * FROM tb_nilai WHERE nim = '" . $key->id . "' AND sebagai='pembimbing 1'")->getResult();
                $pembimbing2 = $db->query("SELECT * FROM tb_nilai WHERE nim = '" . $key->id . "' AND sebagai='pembimbing 2'")->getResult();
                $penguji1 = $db->query("SELECT * FROM tb_nilai WHERE nim = '" . $key->id . "' AND sebagai='penguji 1'")->getResult();
                $penguji2 = $db->query("SELECT * FROM tb_nilai WHERE nim = '" . $key->id . "' AND sebagai='penguji 2'")->getResult();
                $penguji3 = $db->query("SELECT * FROM tb_nilai WHERE nim = '" . $key->id . "' AND sebagai='penguji 3'")->getResult();
                if (!empty($pembimbing1)) {
                    $nb_pembimbing1 = $pembimbing1[0]->nilai_bimbingan == NULL ? 0 : $pembimbing1[0]->nilai_bimbingan;
                    $ns_pembimbing1 = $pembimbing1[0]->nilai_ujian == NULL ? 0 : $pembimbing1[0]->nilai_ujian;
                } else {
                    $nb_pembimbing1 = 0;
                    $ns_pembimbing1 = 0;
                }
                if (!empty($pembimbing2)) {
                    $nb_pembimbing2 = $pembimbing2[0]->nilai_bimbingan == NULL ? 0 : $pembimbing2[0]->nilai_bimbingan;
                    $ns_pembimbing2 = $pembimbing2[0]->nilai_ujian == NULL ? 0 : $pembimbing2[0]->nilai_ujian;
                } else {
                    $nb_pembimbing2 = 0;
                    $ns_pembimbing2 = 0;
                }
                if (!empty($penguji1)) {
                    $ns_penguji1 = $penguji1[0]->nilai_ujian == NULL ? 0 : $penguji1[0]->nilai_ujian;
                } else {
                    $ns_penguji1 = 0;
                }
                if (!empty($penguji2)) {
                    $ns_penguji2 = $penguji2[0]->nilai_ujian == NULL ? 0 : $penguji2[0]->nilai_ujian;
                } else {
                    $ns_penguji2 = 0;
                }
                if (!empty($penguji3)) {
                    $ns_penguji3 = $penguji3[0]->nilai_ujian == NULL ? 0 : $penguji3[0]->nilai_ujian;
                } else {
                    $ns_penguji3 = 0;
                }
                $nb = (($nb_pembimbing1 + $nb_pembimbing2) / 2) * (60 / 100);
                $ns = (($ns_pembimbing1 + $ns_pembimbing2 + $ns_penguji1 + $ns_penguji2 + $ns_penguji3) / 5) * (40 / 100);
                $total = $nb + $ns;
                $grade = "E";
                $sidang = $db->query("SELECT * FROM tb_pendaftar_sidang a LEFT JOIN tb_jadwal_sidang b ON a.`id_jadwal`=b.`id_jadwal` WHERE a.`nim`='" . $key->id . "' AND b.`jenis_sidang`='sidang skripsi' ORDER BY create_at DESC LIMIT 1")->getResult();
                if (!empty($sidang)) {
                    if ($total >= 80) {
                        $grade = "A";
                    } elseif ($total >= 75 && $total < 80) {
                        $grade = "B+";
                    } elseif ($total >= 70 && $total < 75) {
                        $grade = "B";
                    } elseif ($total >= 65 && $total < 70) {
                        $grade = "C+";
                    } elseif ($total >= 60 && $total < 65) {
                        $grade = "C";
                    } elseif ($total >= 55 && $total < 60) {
                        $grade = "D+";
                    } elseif ($total >= 50 && $total < 55) {
                        $grade = "D";
                    } else {
                        $grade = "E";
                    }
                };
                // else {
                //     $grade = "<span class='text-danger ms-2'>Belum Mendaftar Sidang Skripsi</span>";
                // }
            ?>
                <tr>
                    <th scope="row"><?= $no ?></th>
                    <td style="text-align: center; vertical-align: middle; border: 1px solid black;"><?= $key->id ?></td>
                    <td style="text-align: left; vertical-align: middle; border: 1px solid black;">
                        <?php
                        // $mhs[0]->nama 
                        if (!empty($mhs)) {
                            echo $mhs[0]->nama;
                        } else {
                            echo "";
                        }
                        ?>
                    </td>
                    <td style="text-align: center; vertical-align: middle; border: 1px solid black;">
                        <?php
                        // $judul[0]->judul_topik
                        if (!empty($judul)) {
                            echo $judul[0]->judul_topik;
                        } else {
                            echo "";
                        }
                        ?>
                    </td>
                    <td style="text-align: center; vertical-align: middle; border: 1px solid black;"><?= $nb ?></td>
                    <td style="text-align: center; vertical-align: middle; border: 1px solid black;"><?= $ns ?></td>
                    <td style="text-align: center; vertical-align: middle; border: 1px solid black;"><?= $total ?></td>
                    <td style="text-align: center; vertical-align: middle; border: 1px solid black;"><?= $grade ?></td>
                </tr>
            <?php $no++;
            } ?>
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
</body>

</html>