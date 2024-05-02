<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <!-- Title -->
  <title> SISRI - <?= $title; ?></title>

  <style type="text/css">
    .textclass {
      font-weight: bold;
      mso-number-format: "\@";
      text-align: right
    }
  </style>
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
  <?php
  header("Content-type: application/vnd-ms-excel");
  header("Content-Disposition: attachment; filename=Data Pendaftar.xls");
  ?>
</head>

<body style="font-size: 12px">
  <table style="border: 1px solid black;border-collapse: collapse;font-size: 11px" rules="cols" width="100%" id="excel">
    <thead>
      <tr>
        <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>No.</span></th>
        <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Mahasiswa</span></th>
        <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Judul</span></th>
        <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Pembimbing 1</span></th>
        <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Pembimbing 2</span></th>
        <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Penguji 1</span></th>
        <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Penguji 2</span></th>
        <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Penguji 3</span></th>
        <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Waktu Sidang</span></th>
        <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Ruang Sidang</span></th>
        <?php
        if ($data_jadwal[0]->jenis_sidang == 'sidang skripsi') {
        ?>
          <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Selisih Sidang Seminar ke Sidang Skripsi</span></th>
        <?php } ?>
      </tr>
    </thead>
    <tbody>
      <?php
      date_default_timezone_set("Asia/Jakarta");
      $no = 1;
      foreach ($data_pendaftar as $key) {
        $mhs = $db->query("SELECT * FROM tb_mahasiswa WHERE nim='$key->nim'")->getResult();
        $judul = $db->query("SELECT * FROM tb_pengajuan_topik WHERE nim='$key->nim'")->getResult();
        $pem1 = $db->query("SELECT * FROM tb_pengajuan_pembimbing a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$key->nim' AND a.sebagai='1' AND a.status_pengajuan='diterima'")->getResult();
        $pem2 = $db->query("SELECT * FROM tb_pengajuan_pembimbing a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$key->nim' AND a.sebagai='2' AND a.status_pengajuan='diterima'")->getResult();
        $penguji1 = $db->query("SELECT * FROM tb_penguji a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$key->nim' AND a.sebagai='1' AND a.id_pendaftar='$key->id_pendaftar'")->getResult();
        $penguji2 = $db->query("SELECT * FROM tb_penguji a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$key->nim' AND a.sebagai='2' AND a.id_pendaftar='$key->id_pendaftar'")->getResult();
        $penguji3 = $db->query("SELECT * FROM tb_penguji a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$key->nim' AND a.sebagai='3' AND a.id_pendaftar='$key->id_pendaftar'")->getResult();
        if ($data_jadwal[0]->jenis_sidang == 'sidang skripsi') {
          $penguji1 = $db->query("SELECT * FROM tb_penguji a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$key->nim' AND a.sebagai='1' AND a.`status`='aktif'")->getResult();
          $penguji2 = $db->query("SELECT * FROM tb_penguji a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$key->nim' AND a.sebagai='2' AND a.`status`='aktif'")->getResult();
          $penguji3 = $db->query("SELECT * FROM tb_penguji a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$key->nim' AND a.sebagai='3' AND a.`status`='aktif'")->getResult();
        }
        if ($jenis == 'semua') { ?>
          <tr style="margin: 5px">
            <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><?= $no ?></td>
            <td style="text-align: left; vertical-align: middle;border: 1px solid black;"><?= $key->nim . ' - ' . $mhs[0]->nama ?></td>
            <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><?= $judul[0]->judul_topik ?></td>
            <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><?= $pem1[0]->gelardepan . ' ' . $pem1[0]->nama . ', ' . $pem1[0]->gelarbelakang ?></td>
            <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><?= $pem2[0]->gelardepan . ' ' . $pem2[0]->nama . ', ' . $pem2[0]->gelarbelakang ?></td>
            <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><?= $penguji1 != NULL ? $penguji1[0]->gelardepan . ' ' . $penguji1[0]->nama . ', ' . $penguji1[0]->gelarbelakang : '' ?></td>
            <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><?= $penguji2 != NULL ? $penguji2[0]->gelardepan . ' ' . $penguji2[0]->nama . ', ' . $penguji2[0]->gelarbelakang : '' ?></td>
            <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><?= $penguji3 != NULL ? $penguji3[0]->gelardepan . ' ' . $penguji3[0]->nama . ', ' . $penguji3[0]->gelarbelakang : '' ?></td>
            <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><?= $key->waktu_sidang ?></td>
            <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><?= $key->ruang_sidang ?></td>
            <?php
            if ($data_jadwal[0]->jenis_sidang == 'sidang skripsi') {
              $d_s = $db->query("SELECT * FROM tb_pendaftar_sidang a LEFT JOIN tb_jadwal_sidang b ON a.`id_jadwal`=b.`id_jadwal` WHERE a.`nim`='" . $key->nim . "' AND b.`jenis_sidang`='seminar proposal' ORDER BY a.`create_at` DESC")->getResult();
              $d_s = date_create(date('Y-m-d', strtotime($d_s[0]->waktu_sidang)));
              $d_now = date_create(date('Y-m-d'));
              $selisih = date_diff($d_s, $d_now);
            ?>
              <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><?= $selisih->y . " tahun, " . $selisih->m . " bulan, " . $selisih->d . " hari" ?></td>
            <?php } ?>
          </tr>
          <?php
          $no++;
        } else {
          if ($penguji1 != NULL && $penguji2 != NULL && $penguji3 != NULL && ($key->waktu_sidang != '' or $key->waktu_sidang != NULL) && ($key->ruang_sidang != '' or $key->ruang_sidang != NULL)) {

          ?>
            <tr style="margin: 5px">
              <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><?= $no ?></td>
              <td style="text-align: left; vertical-align: middle;border: 1px solid black;"><?= $key->nim . ' - ' . $mhs[0]->nama ?></td>
              <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><?= $judul[0]->judul_topik ?></td>
              <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><?= $pem1[0]->gelardepan . ' ' . $pem1[0]->nama . ', ' . $pem1[0]->gelarbelakang ?></td>
              <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><?= $pem2[0]->gelardepan . ' ' . $pem2[0]->nama . ', ' . $pem2[0]->gelarbelakang ?></td>
              <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><?= $penguji1 != NULL ? $penguji1[0]->gelardepan . ' ' . $penguji1[0]->nama . ', ' . $penguji1[0]->gelarbelakang : '' ?></td>
              <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><?= $penguji2 != NULL ? $penguji2[0]->gelardepan . ' ' . $penguji2[0]->nama . ', ' . $penguji2[0]->gelarbelakang : '' ?></td>
              <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><?= $penguji3 != NULL ? $penguji3[0]->gelardepan . ' ' . $penguji3[0]->nama . ', ' . $penguji3[0]->gelarbelakang : '' ?></td>
              <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><?= $key->waktu_sidang ?></td>
              <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><?= $key->ruang_sidang ?></td>
              <?php
              if ($data_jadwal[0]->jenis_sidang == 'sidang skripsi') {
                $d_s = $db->query("SELECT * FROM tb_pendaftar_sidang a LEFT JOIN tb_jadwal_sidang b ON a.`id_jadwal`=b.`id_jadwal` WHERE a.`nim`='" . $key->nim . "' AND b.`jenis_sidang`='seminar proposal' ORDER BY a.`create_at` DESC")->getResult();
                $d_s = date_create(date('Y-m-d', strtotime($d_s[0]->waktu_sidang)));
                $d_now = date_create(date('Y-m-d'));
                $selisih = date_diff($d_s, $d_now);
              ?>
                <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><?= $selisih->y . " tahun, " . $selisih->m . " bulan, " . $selisih->d . " hari" ?></td>
              <?php } ?>
            </tr>
      <?php $no++;
          }
        }
      } ?>
    </tbody>
  </table>



</body>

</html>