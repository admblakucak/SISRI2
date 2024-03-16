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
</head>

<body style="font-size: 12px">
    <table width="100%" style="border: 1px solid black;border-collapse: collapse;">
        <tr>

            <td style="border: 1px solid black;text-align:center;padding: 10px;" rowspan="2">
                <img src="https://upload.wikimedia.org/wikipedia/commons/f/f1/UTM_DIKBUDRISTEK.png" style="width: 100px;">
            </td>
            <td style="text-align:center;font-size: 18px;border: 1px solid black;"><b>FORM</b></td>
            <td style="border: 1px solid black;text-align:center;padding: 5px;" colspan="2">No. Dokumen : <b>F.S.JMF.05</b></td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:center;font-size: 18px;border: 1px solid black;"><b>MONITORING KEGIATAN PEMBIMBINGAN</b></td>
            <td style="border: 1px solid black;padding: 5px;text-align:center;">Tanggal : <b><?= date('d-m-Y') ?></b></td>
        </tr>
    </table>

    <p>
    <table>
        <tr>
            <th align="left"> Nama </th>
            <td> : <?= $nama ?></td>
        </tr>
        <tr>
            <th align="left"> NIM / NRP </th>
            <td> : <?= $nim ?></td>
        </tr>
        <tr>
            <th align="left"> Judul Skripsi </th>
            <td> : <?= $judul_skripsi ?></td>
        </tr>
    </table>
    </p>

    <p>
    <table style="border: 1px solid black;border-collapse: collapse;font-size: 11px" rules="cols" width="100%">
        <tr style="margin: 5px">
            <th style="border: 1px solid black;">No</th>
            <th style="border: 1px solid black;">Tanggal</th>
            <th style="border: 1px solid black;">Topik Bimbingan</th>
            <th style="border: 1px solid black;">Detail Bimbingan</th>
            <!-- <th style="border: 1px solid black;">Respon</th> -->
        </tr>
        <?php
        $no = 1;
        foreach ($data as $key) {
        ?>
            <tr style="margin: 5px">
                <td><?= $no ?></td>
                <td><?= $key->create_at ?></td>
                <td><?= $key->pokok_bimbingan ?></td>
                <td><?= $key->keterangan ?></td>
                <!-- <td><?= $key->status_baca ?></td> -->
            </tr>
        <?php } ?>
    </table>
    </p>
    <br>
    <p align='center'>
        <b>
            Menyetujui untuk dilanjutkan ke Seminar Proposal Skripsi,
            <br />(JANGAN DITANDATANGANI APABILA SKRIPSI MAHASISWA BELUM LAYAK UNTUK SEMINAR)
        </b>
    </p>
    <p>
    <table width="100%">
        <tr>
            <td align="center" width="50%"></td>
            <td align="center">Diketahui oleh<br>Pembimbing <?= $pembimbing ?><br><?= $qr ?><br><u><?= $nama_pembimbing->gelardepan . ' ' . ucwords(strtolower($nama_pembimbing->nama)) . ', ' . $nama_pembimbing->gelarbelakang ?></u><br>NIP. <?= $nip ?></td>
        </tr>
    </table>
    </p>
    <br><br><br>
    <p align='left' style="font-size: 10px">
        <b>
            Catatan :
            <ol style="font-size: 10px">
                <li>Minimal kegiatan monitoring pada Pembimbing I sebanyak 3 kali dan Pembimbing II sebanyak 3 kali (akumulasi 6 kali kegiatan monitoring)</li>
                <!-- <li>Form ini harus DITUNJUKKAN ke Koordinator Skripsi pada saat MENDAFTAR SEMINAR USULAN SKRIPSI</li> -->
                <!-- <li>Sudah memprogram Skripsi di KRS, menunjukkan KRS pada saat mendaftar</li> -->
            </ol>
        </b>
    </p>
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