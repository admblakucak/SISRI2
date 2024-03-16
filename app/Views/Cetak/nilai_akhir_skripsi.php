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
      <td style="text-align:center;font-size: 18px;border: 1px solid black;"><b>FORM</b></td>
      <td style="border: 1px solid black;text-align:center;padding: 5px;" colspan="2">No. Dokumen : <b>NAS.SS.03</b></td>
    </tr>
    <tr>
      <td colspan="2" style="text-align:center;font-size: 18px;border: 1px solid black;"><b>NILAI AKHIR SKRIPSI</b></td>
      <td style="border: 1px solid black;padding: 5px;text-align:center;">Tanggal Cetak : <b><?= date('d-m-Y') ?></b></td>
    </tr>
  </table>
  <?php
  function tgl_indo($tanggal)
  {
    $bulan = array(
      1 =>   'Januari',
      'Februari',
      'Maret',
      'April',
      'Mei',
      'Juni',
      'Juli',
      'Agustus',
      'September',
      'Oktober',
      'November',
      'Desember'
    );
    $pecahkan = explode('-', $tanggal);

    // variabel pecahkan 0 = tanggal
    // variabel pecahkan 1 = bulan
    // variabel pecahkan 2 = tahun

    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
  }
  function getHari($date)
  {
    $datetime = DateTime::createFromFormat('Y-m-d', $date);
    $day = $datetime->format('l');
    switch ($day) {
      case 'Sunday':
        $hari = 'Minggu';
        break;
      case 'Monday':
        $hari = 'Senin';
        break;
      case 'Tuesday':
        $hari = 'Selasa';
        break;
      case 'Wednesday':
        $hari = 'Rabu';
        break;
      case 'Thursday':
        $hari = 'Kamis';
        break;
      case 'Friday':
        $hari = 'Jum\'at';
        break;
      case 'Saturday':
        $hari = 'Sabtu';
        break;
      default:
        $hari = 'Tidak ada';
        break;
    }
    return $hari;
  }
  $id_pendaftar = $db->query("SELECT * FROM tb_pendaftar_sidang a LEFT JOIN tb_jadwal_sidang b ON a.`id_jadwal`=b.`id_jadwal` WHERE b.`jenis_sidang`='sidang skripsi' AND nim='$nim' ORDER BY create_at DESC LIMIT 1")->getResult()[0]->id_pendaftar;
  $jadwal_sidang = $db->query("SELECT * FROM tb_pendaftar_sidang WHERE id_pendaftar='$id_pendaftar'")->getResult();
  ?>

  <br>
  <p><b>Pada,</b></p>
  <table>
    <tr>
      <th align="left"> Hari / Tanggal </th>
      <td> : <?= $id_pendaftar != NULL ? getHari(date('Y-m-d', strtotime($jadwal_sidang[0]->waktu_sidang))) . ', ' . tgl_indo(date('Y-m-d', strtotime($jadwal_sidang[0]->waktu_sidang))) : '' ?></td>
    </tr>
    <tr>
      <th align="left"> Pukul </th>
      <td> : <?= $id_pendaftar != NULL ? date('H:i:s', strtotime($jadwal_sidang[0]->waktu_sidang)) . ' WIB' : '' ?></td>
    </tr>
    <tr>
      <th align="left"> Tempat :</th>
      <td> : <?= $id_pendaftar != NULL ? $jadwal_sidang[0]->ruang_sidang : '' ?></td>
    </tr>
  </table>
  <br>
  <p><b>Telah dilaksanakan Sidang Skripsi oleh,</b></p>
  <table>
    <tr>
      <th align="left"> Nama </th>
      <td> : <?= ucwords(strtolower($nama)) ?></td>
    </tr>
    <tr>
      <th align="left"> NIM / NRP </th>
      <td> : <?= $nim ?></td>
    </tr>
    <tr>
      <th align="left"> Program Studi </th>
      <td> : <?= ucwords(strtolower($nm_prodi)) ?></td>
    </tr>
    <tr>
      <th align="left"> Jurusan </th>
      <td> : <?= ucwords(strtolower($nm_jurusan)) ?></td>
    </tr>
    <tr>
      <th align="left"> Fakultas </th>
      <td> : <?= ucwords(strtolower($nm_fakultas)) ?></td>
    </tr>
    <tr>
      <th align="left"> Judul Skripsi </th>
      <td> : <?= ucwords(strtolower($judul_skripsi)) ?></td>
    </tr>
    <tr>
      <th align="left"> Rekapitulasi Nilai :</th>
    </tr>
  </table>

  <table width="100%" style="margin-top: 5px;">
    <thead>
      <tr>
        <th style="text-align: center; vertical-align: middle; border: 1px solid black; "><span>Pembimbing 1</span></th>
        <th style="text-align: center; vertical-align: middle; border: 1px solid black; "><span>Pembimbing 2</span></th>
        <th style="text-align: center; vertical-align: middle; border: 1px solid black; "><span>Penguji 1</span></th>
        <th style="text-align: center; vertical-align: middle; border: 1px solid black; "><span>Penguji 2</span></th>
        <th style="text-align: center; vertical-align: middle; border: 1px solid black; "><span>Penguji 3</span></th>
        <th style="text-align: center; vertical-align: middle; border: 1px solid black; "><span>Nilai Akhir Angka</span></th>
        <th style="text-align: center; vertical-align: middle; border: 1px solid black; "><span>Nilai Akhir Huruf</span></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <?php
        foreach ($data_nilai as $key) { ?>
          <td style="vertical-align: middle; border: 1px solid black; text-align:center;"><?= empty($key->nilai_ujian) ? "Belum <br> Dinilai" : $key->nilai_ujian ?></td>
        <?php } ?>

        <td style="font-weight: bold vertical-align: middle; border: 1px solid black; text-align:center; "><?= $total_nilai ?></td>
        <td style="font-weight: bold; vertical-align: middle; border: 1px solid black; text-align:center; "><?= $grade ?></td>

      </tr>
    </tbody>
  </table>
  <br>
  <p><b>Status Ujian :</b></p>
  <input type="checkbox" onclick="return false;" <?= $id_pendaftar != NULL ? $jadwal_sidang[0]->hasil_sidang == 1 ? 'checked' : '' : '' ?>> Lulus tanpa perbaikan
  <br><input type="checkbox" onclick="return false;" <?= $id_pendaftar != NULL ? $jadwal_sidang[0]->hasil_sidang == 2 ? 'checked' : '' : '' ?>> Lulus dengan perbaikan
  <br><input type="checkbox" onclick="return false;" <?= $id_pendaftar != NULL ? $jadwal_sidang[0]->hasil_sidang == 3 ? 'checked' : '' : '' ?>> Tidak lulus/mengulang
  <br>
  <br>
  <p>
  <table width="100%" style="border: 1px solid black;border-collapse: collapse;">
    <tr>
      <td align="center" width="50%" style="border: 1px solid black;text-align:center;">
        Diketahui oleh<br>Pembimbing <?= $dosen_pembimbing_1[0]->sebagai ?><br><?= $qr_pembimbing_1 ?><br><u><?= $dosen_pembimbing_1[0]->gelardepan . ' ' . ucwords(strtolower($dosen_pembimbing_1[0]->nama)) . ', ' . $dosen_pembimbing_1[0]->gelarbelakang ?></u><br>NIP. <?= $dosen_pembimbing_1[0]->nip ?>
      </td>
      <td align="center" width="50%" style="border: 1px solid black;text-align:center;">
        Diketahui oleh<br>Pembimbing <?= $dosen_pembimbing_2[0]->sebagai ?><br><?= $qr_pembimbing_1 ?><br><u><?= $dosen_pembimbing_2[0]->gelardepan . ' ' . ucwords(strtolower($dosen_pembimbing_2[0]->nama)) . ', ' . $dosen_pembimbing_2[0]->gelarbelakang ?></u><br>NIP. <?= $dosen_pembimbing_2[0]->nip ?>
      </td>
    </tr>
  </table>
  <br />
  <table width="100%" style="border: 1px solid black;border-collapse: collapse;">
    <tr>
      <td align="center" width="33%" style="border: 1px solid black;text-align:center;">
        Diketahui oleh<br>Penguji <?= $penguji_1[0]->sebagai ?><br><?= $qr_penguji_1 ?><br><u><?= $penguji_1[0]->gelardepan . ' ' . ucwords(strtolower($penguji_1[0]->nama)) . ', ' . $penguji_1[0]->gelarbelakang ?></u><br>NIP. <?= $penguji_1[0]->nip ?>

      </td>
      <td align="center" width="34%" style="border: 1px solid black;text-align:center;">
        Diketahui oleh<br>Penguji <?= $penguji_2[0]->sebagai ?><br><?= $qr_penguji_2 ?><br><u><?= $penguji_2[0]->gelardepan . ' ' . ucwords(strtolower($penguji_2[0]->nama)) . ', ' . $penguji_2[0]->gelarbelakang ?></u><br>NIP. <?= $penguji_2[0]->nip ?>

      </td>
      <td align="center" width="33%" style="border: 1px solid black;text-align:center;">
        Diketahui oleh<br>Penguji <?= $penguji_3[0]->sebagai ?><br><?= $qr_penguji_3 ?><br><u><?= $penguji_3[0]->gelardepan . ' ' . ucwords(strtolower($penguji_3[0]->nama)) . ', ' . $penguji_3[0]->gelarbelakang ?></u><br>NIP. <?= $penguji_3[0]->nip ?>
      </td>
    </tr>
  </table>
  </p>
  <br><br><br>
  <p align='left' style="font-size: 10px">
    <b>
      Catatan :
      <ol style="font-size: 10px">
        <li>KARTU INI DIBERIKAN KE KOORDINATOR PROGRAM STUDI SETELAH SEMUA PEMBIMBING & PENGUJI MELAKUKAN TANDA TANGAN</li>
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