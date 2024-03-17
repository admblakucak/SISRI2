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
      <td style="text-align:center;font-size: 18px;border: 1px solid black;"><b>RIWAYAT BIMBINGAN</b></td>
      <td style="border: 1px solid black;padding: 5px;text-align:center;" colspan="2">Periode : <b><?= date('d-m-Y', strtotime($date1)) ?></b> hingga <b><?= date('d-m-Y', strtotime($date2)) ?></b> </td>
    </tr>
    <tr>
      <td colspan="2" style="text-align:center;font-size: 18px;border: 1px solid black;"><b>DATA NILAI MAHASISWA <?= strtoupper($namaunit) ?></b></td>
      <td style="border: 1px solid black;padding: 5px;text-align:center;">Tanggal : <b><?= date('d-m-Y') ?></b></td>
    </tr>
  </table>
  <table style=" border: 1px solid black; border-collapse: collapse;font-size: 11px" rules="cols" width="100%">
    <thead>
      <tr>
        <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Nama </span></th>
        <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Judul</span></th>
        <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Pembimbing 1</span></th>
        <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Pembimbing 2</span></th>
        <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Penguji 1</span></th>
        <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Penguji 2</span></th>
        <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Penguji 3</span></th>
        <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Tanggal Sidang</span></th>
        <th style="text-align: center; vertical-align: middle;border: 1px solid black;"><span>Keterangan Lulus</span></th>
      </tr>
    </thead>
    <tbody id='show_data2'>
      <?php
      $no = 1;
      foreach ($data_mhs_bimbingan as $key) {
        // $pem1 = $db->query("SELECT a.nim,a.nip,a.sebagai, b.nama,b.gelardepan,b.gelarbelakang, c.nilai_bimbingan, c.nilai_ujian FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.nip=b.nip LEFT JOIN tb_nilai c ON a.nip = c.nip WHERE a.status_pengajuan = 'diterima' AND a.nim = '" . $key['nim'] . "' AND a.sebagai ='1' ORDER BY `c`.`nilai_bimbingan` DESC")->getResult();
        // $pem1 = $db->query("SELECT a.nim,a.nip,a.sebagai, b.nama,b.gelardepan,b.gelarbelakang, c.nilai_bimbingan, c.nilai_ujian FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.nip=b.nip LEFT JOIN tb_nilai c ON a.nip = c.nip WHERE a.status_pengajuan = 'diterima' AND a.nim = '" . '180441100116' . "' AND a.sebagai ='1' ORDER BY `c`.`nilai_bimbingan` DESC")->getResult();
        // $pem2 = $db->query("SELECT a.nim,a.nip,a.sebagai, b.nama,b.gelardepan,b.gelarbelakang, c.nilai_bimbingan, c.nilai_ujian FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.nip=b.nip LEFT JOIN tb_nilai c ON a.nip = c.nip WHERE a.status_pengajuan = 'diterima' AND a.nim = '" . $key['nim'] . "' AND a.sebagai ='2' ORDER BY `c`.`nilai_bimbingan` DESC")->getResult();

        $sidang = $db->query("SELECT a.*, b.jenis_sidang FROM `tb_pendaftar_sidang` a LEFT JOIN tb_jadwal_sidang b ON a.id_jadwal=b.id_jadwal WHERE b.jenis_sidang = 'sidang skripsi' AND a.nim = '" . $key['nim'] . "' AND (a.waktu_sidang BETWEEN '$date1' AND ('$date2'+ INTERVAL 1 DAY))  ")->getResult();
        if (empty($sidang) || empty($sidang[0]->waktu_sidang) ) {
          continue;
        }

        $pem1 = $db->query("SELECT a.nip,b.nama,b.gelardepan,b.gelarbelakang from tb_pengajuan_pembimbing a left join tb_dosen b on a.nip=b.nip where a.nim = '" . $key['nim'] . "' AND a.status_pengajuan = 'diterima' AND sebagai = 1")->getResult();
        $pem2 = $db->query("SELECT a.nip,b.nama,b.gelardepan,b.gelarbelakang from tb_pengajuan_pembimbing a left join tb_dosen b on a.nip=b.nip where a.nim = '" . $key['nim'] . "' AND a.status_pengajuan = 'diterima' AND sebagai = 2")->getResult();
        $penguji1 = $db->query("SELECT a.nip,b.nama,b.gelardepan,b.gelarbelakang from tb_penguji a left join tb_dosen b on a.nip=b.nip where a.nim = '" . $key['nim'] . "' AND a.status = 'aktif' AND sebagai = 1 AND a.jenis_sidang = 'sidang skripsi'")->getResult();
        if (empty($penguji1)) {
          $penguji1 = $db->query("SELECT a.nip,b.nama,b.gelardepan,b.gelarbelakang from tb_penguji a left join tb_dosen b on a.nip=b.nip where a.nim = '" . $key['nim'] . "' AND a.status = 'aktif' AND sebagai = 1 AND a.jenis_sidang = ''")->getResult();
        }
        $penguji2 = $db->query("SELECT a.nip,b.nama,b.gelardepan,b.gelarbelakang from tb_penguji a left join tb_dosen b on a.nip=b.nip where a.nim = '" . $key['nim'] . "' AND a.status = 'aktif' AND sebagai = 2 AND a.jenis_sidang = 'sidang skripsi'")->getResult();
        if (empty($penguji2)) {
          $penguji2 = $db->query("SELECT a.nip,b.nama,b.gelardepan,b.gelarbelakang from tb_penguji a left join tb_dosen b on a.nip=b.nip where a.nim = '" . $key['nim'] . "' AND a.status = 'aktif' AND sebagai = 2 AND a.jenis_sidang = ''")->getResult();
        }
        $penguji3 = $db->query("SELECT a.nip,b.nama,b.gelardepan,b.gelarbelakang from tb_penguji a left join tb_dosen b on a.nip=b.nip where a.nim = '" . $key['nim'] . "' AND a.status = 'aktif' AND sebagai = 3 AND a.jenis_sidang = 'sidang skripsi'")->getResult();
        if (empty($penguji3)) {
          $penguji3 = $db->query("SELECT a.nip,b.nama,b.gelardepan,b.gelarbelakang from tb_penguji a left join tb_dosen b on a.nip=b.nip where a.nim = '" . $key['nim'] . "' AND a.status = 'aktif' AND sebagai = 3 AND a.jenis_sidang = ''")->getResult();
        }

        $nilai_pem1 = $db->query("SELECT * FROM `tb_nilai`  WHERE nim = '" . $key['nim'] . "' AND sebagai = 'pembimbing 1'")->getResult();
        $nilai_pem2 = $db->query("SELECT * FROM `tb_nilai`  WHERE nim = '" . $key['nim'] . "' AND sebagai = 'pembimbing 2'")->getResult();
        $nilai_penguji1 = $db->query("SELECT * FROM `tb_nilai`  WHERE nim = '" . $key['nim'] . "' AND sebagai = 'penguji 1'")->getResult();
        $nilai_penguji2 = $db->query("SELECT * FROM `tb_nilai`  WHERE nim = '" . $key['nim'] . "' AND sebagai = 'penguji 2'")->getResult();
        $nilai_penguji3 = $db->query("SELECT * FROM `tb_nilai`  WHERE nim = '" . $key['nim'] . "' AND sebagai = 'penguji 3'")->getResult();
        if (!empty($penguji1[0]->nip)) {
          $nilai_penguji1 = $db->query("SELECT * FROM `tb_nilai`  WHERE nim = '" . $key['nim'] . "' AND sebagai = 'penguji 1' AND nip='" . $penguji1[0]->nip . "'")->getResult();
        }
        if (!empty($penguji2[0]->nip)) {
          $nilai_penguji2 = $db->query("SELECT * FROM `tb_nilai`  WHERE nim = '" . $key['nim'] . "' AND sebagai = 'penguji 2' AND nip='" . $penguji2[0]->nip . "'")->getResult();
        }
        if (!empty($penguji3[0]->nip)) {
          $nilai_penguji3 = $db->query("SELECT * FROM `tb_nilai`  WHERE nim = '" . $key['nim'] . "' AND sebagai = 'penguji 3' AND nip='" . $penguji3[0]->nip . "'")->getResult();
        }

        $judul = $db->query("SELECT * FROM tb_pengajuan_topik WHERE nim='" . $key['nim'] . "'")->getResult();
        $acc_dosen_penguji_1 = $db->query("SELECT * FROM `tb_acc_revisi` WHERE `nim` ='" . $key['nim'] . "' AND jenis_sidang = 'seminar proposal' AND sebagai ='penguji 1'")->getResult();
        $acc_dosen_penguji_2 = $db->query("SELECT * FROM `tb_acc_revisi` WHERE `nim` ='" . $key['nim'] . "' AND jenis_sidang = 'seminar proposal' AND sebagai ='penguji 2'")->getResult();
        $acc_dosen_penguji_3 = $db->query("SELECT * FROM `tb_acc_revisi` WHERE `nim` ='" . $key['nim'] . "' AND jenis_sidang = 'seminar proposal' AND sebagai ='penguji 3'")->getResult();
        $keterangan_lulus = 'belum lulus';
        $jadwal_sidang = $db->query("SELECT a.*,b.jenis_sidang FROM `tb_pendaftar_sidang` a LEFT JOIN tb_jadwal_sidang b ON a.id_jadwal=b.id_jadwal WHERE nim ='" . $key['nim'] . "' AND (hasil_sidang <3 or hasil_sidang IS NULL)  AND b.jenis_sidang = 'sidang skripsi'")->getResult();

        if ($sebagai == 'pembimbing') {
          if (empty($acc_dosen_penguji_1) || empty($acc_dosen_penguji_2) || empty($acc_dosen_penguji_3)) {
            continue;
          }
        }

        $status_acc_revisi = $db->query("SELECT * FROM tb_acc_revisi WHERE nim ='" . $key['nim'] . "' AND nip='" . session()->get('ses_id') . "' AND jenis_sidang='skripsi'")->getResult();
        // if (empty($status_acc_revisi)) {
        //   continue;
        // }
        if (!empty($nilai_pem1[0]->nilai_bimbingan) && !empty($nilai_pem2[0]->nilai_bimbingan) && !empty($nilai_penguji1[0]->nilai_ujian) && !empty($nilai_penguji2[0]->nilai_ujian) && !empty($nilai_penguji3[0]->nilai_ujian)) {
          $keterangan_lulus = 'Sudah Lulus';
          $db->query("UPDATE tb_pengajuan_pembimbing SET pesan = 'Sudah Lulus' WHERE nim='" . $key['nim'] . "'");
        } else {
          $keterangan_lulus = 'belum lulus';
          $db->query("UPDATE tb_pengajuan_pembimbing SET pesan = NULL WHERE nim='" . $key['nim'] . "'");
        }
        // if (count($total_acc_dosen_penguji) != 3) {
        //   continue;
        // }
      ?>
        <tr>
          <td style="text-align: center; vertical-align: middle;border: 1px solid black;">
            <?= $key['nim'] . ' - ' . $key['nama_mhs']; ?>
          </td>
          <td scope="row" style="text-align: center; vertical-align: middle;border: 1px solid black;"><?= $judul[0]->judul_topik ?></td>
          <td scope="row" style="text-align: center; vertical-align: middle;border: 1px solid black;">
            <?= $pem1[0] != NULL ? $pem1[0]->gelardepan . ' ' . $pem1[0]->nama . ', ' . $pem1[0]->gelarbelakang : '' ?>
            <br>
            <?php
            if (!empty($nilai_pem1[0]->nilai_bimbingan)) {
              echo " <br> <span class='text-success'>Sudah Dinilai</span>";
            } else {
              echo "<br> <span class='text-danger'>Belum Dinilai</span>";
            }
            ?>
          </td>
          <td scope="row" style="text-align: center; vertical-align: middle;border: 1px solid black;">
            <?= $pem2 != NULL ? $pem2[0]->gelardepan . ' ' . $pem2[0]->nama . ', ' . $pem2[0]->gelarbelakang : '' ?>
            <br>
            <?php
            if (!empty($nilai_pem2[0]->nilai_bimbingan)) {
              echo " <br> <span class='text-success'>Sudah Dinilai</span>";
            } else {
              echo "<br> <span class='text-danger'>Belum Dinilai</span>";
            }
            ?>
          </td>
          <td scope="row" style="text-align: center; vertical-align: middle;border: 1px solid black;">
            <?= $penguji1[0] != NULL ? $penguji1[0]->gelardepan . ' ' . $penguji1[0]->nama . ', ' . $penguji1[0]->gelarbelakang : '' ?>
            <br>
            <?php
            if (!empty($nilai_penguji1[0]->nilai_ujian)) {
              echo " <br> <span class='text-success'>Sudah Dinilai</span>";
            } else {
              echo "<br> <span class='text-danger'>Belum Dinilai</span>";
            }
            ?>
          </td>
          <td scope="row" style="text-align: center; vertical-align: middle;border: 1px solid black;">
            <?= $penguji2[0] != NULL ? $penguji2[0]->gelardepan . ' ' . $penguji2[0]->nama . ', ' . $penguji2[0]->gelarbelakang : '' ?>
            <br>
            <?php
            if (!empty($nilai_penguji2[0]->nilai_ujian)) {
              echo " <br> <span class='text-success'>Sudah Dinilai</span>";
            } else {
              echo "<br> <span class='text-danger'>Belum Dinilai</span>";
            }
            ?>
          </td>
          <td scope="row" style="text-align: center; vertical-align: middle;border: 1px solid black;">
            <?= $penguji3[0] != NULL ? $penguji3[0]->gelardepan . ' ' . $penguji3[0]->nama . ', ' . $penguji3[0]->gelarbelakang : '' ?>
            <br>
            <?php
            if (!empty($nilai_penguji3[0]->nilai_ujian)) {
              echo " <br> <span class='text-success'>Sudah Dinilai</span>";
            } else {
              echo "<br> <span class='text-danger'>Belum Dinilai</span>";
            }
            ?>
          </td>
          <td style="text-align: center; vertical-align: middle;border: 1px solid black;"><?= !empty($jadwal_sidang) && !empty($jadwal_sidang[0]->waktu_sidang)  ?  $jadwal_sidang[0]->waktu_sidang : 'null' ?> </td>
          <td class="<?= $keterangan_lulus == 'Sudah Lulus' ? 'text-success' : 'text-danger' ?>" style="text-align: center; vertical-align: middle;border: 1px solid black;"><?= $keterangan_lulus ?></td>
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
  <script>
    function print() {
      window.print();
    }
  </script>
</body>

</html>