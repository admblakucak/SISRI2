<?php


use CodeIgniter\Images\Image;
?>
<?= $this->extend('Template/content') ?>

<?= $this->section('content') ?>


<div class="container-fluid">
  <div class="row mt-3">
    <div class="col-cl-12">
      <div class="card">
        <div class="card-header pb-0">
          <div class="d-flex justify-content-between">
            <div class="card-title mg-b-0">Informasi <?= $tipe == 'sudah_dinilai'?'sudah dinilai': 'belum dinilai'?></div>
            <i class="mdi mdi-dots-horizontal text-gray"></i>
          </div>
          <!-- <p class="tx-12 tx-gray-500 mb-2">Daftar Nilai</p> -->
        </div>


        <div class="row mt-3">
          <div class="col">
            <div class="card-body">
              <form class="form-inline mb-4" action="<?= base_url() ?>export_nilai" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="form-group mb-2 ml-4" style="width: 20%;">
                  <select class="form-control select2" name="id_periode">
                    <option selected disabled>Semua Angkatan</option>
                    <?php
                    foreach ($data_periode as $key) {
                      if (substr($key->idperiode, 4) == 1) {
                        echo "<option value='$key->idperiode'>" . substr($key->idperiode, 0, -1) . "</option>";
                      }
                    }
                    ?>
                  </select>
                </div>
                <div class="form-group mb-2 mx-2" style="width: 30%;">
                  <select class="form-control  select2" name="id_jadwal">
                    <option selected disabled>Semua Periode Sidang</option>
                    <?php
                    foreach ($data_jadwal as $key) {
                      echo "<option value='$key->id_jadwal'>" . $key->periode . "</option>";
                    }
                    ?>
                  </select>
                </div>
                <div class="form-group mb-2 mx-2" style="width: 30%;">
                  <select class="form-control  select2" name="jenis_file">
                    <option selected value="pdf">PDF</option>
                    <option value="excel">EXCEL</option>
                  </select>
                </div>
                <button type="submit" class="btn btn-primary mb-2"><i class="fa fa-print"></i></button>
              </form>
              <hr>
              <div class="table-responsive">

                <a href="<?= base_url() ?>export_sudah_nilai_pdf/<?= $tipe ?>/<?= session()->get('ses_idunit') ?>"><button class="btn btn-primary mb-3" type="button">Cetak</button></a>
                <table class="table table-striped mg-b-0 text-md-nowrap" id="validasitable3">
                  <thead>
                    <tr>
                      <th style="text-align: center; vertical-align: middle;"><span>No.</span></th>
                      <th style="text-align: center; vertical-align: middle;" class="col-sm-7 col-md-6 col-lg-2"><span>Nama</span></th>
                      <th style="text-align: center; vertical-align: middle;" class="col-sm-7 col-md-6 col-lg-4"><span>Judul Skripsi</span></th>
                      <th style="text-align: center; vertical-align: middle;"><span>Pembimbing 1</span></th>
                      <th style="text-align: center; vertical-align: middle;"><span>Pembimbing 2</span></th>
                      <th style="text-align: center; vertical-align: middle;"><span>Penguji 1</span></th>
                      <th style="text-align: center; vertical-align: middle;"><span>Penguji 2</span></th>
                      <th style="text-align: center; vertical-align: middle;"><span>Penguji 3</span></th>
                      <th style="text-align: center; vertical-align: middle;"><span>Nilai Akhir</span></th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $no = 1;
                    foreach ($data_mhs as $key) {
                      $nama_mahasiswa = $db->query("SELECT nama from tb_mahasiswa where nim ='" . $key->id . "' ")->getResult();
                      $judul = $db->query("SELECT * FROM tb_pengajuan_topik WHERE nim = '" . $key->id . "'")->getResult();
                      $pembimbing1 = $db->query("SELECT * FROM tb_nilai WHERE nim = '" . $key->id . "' AND sebagai='pembimbing 1'")->getResult();
                      $pembimbing2 = $db->query("SELECT * FROM tb_nilai WHERE nim = '" . $key->id . "' AND sebagai='pembimbing 2'")->getResult();
                      $penguji1 = $db->query("SELECT * FROM tb_nilai WHERE nim = '" . $key->id . "' AND sebagai='penguji 1'")->getResult();
                      $penguji2 = $db->query("SELECT * FROM tb_nilai WHERE nim = '" . $key->id . "' AND sebagai='penguji 2'")->getResult();
                      $penguji3 = $db->query("SELECT * FROM tb_nilai WHERE nim = '" . $key->id . "' AND sebagai='penguji 3'")->getResult();
                      if (empty($judul[0]->judul_topik) || empty($nama_mahasiswa[0]->nama)) {
                        continue;
                      }

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
                      if ($tipe == 'sudah_dinilai') {
                        if (empty($pembimbing1[0]->nilai_bimbingan) || empty($pembimbing2[0]->nilai_bimbingan) || empty($penguji1[0]->nilai_ujian) || empty($penguji2[0]->nilai_ujian) || empty($penguji3[0]->nilai_ujian)) {
                          continue;
                        }
                      } else {
                        if (!empty($pembimbing1[0]->nilai_bimbingan) && !empty($pembimbing2[0]->nilai_bimbingan) && !empty($penguji1[0]->nilai_ujian) && !empty($penguji2[0]->nilai_ujian) && !empty($penguji3[0]->nilai_ujian)) {
                          continue;
                        }
                      }
                    ?>
                      <tr>
                        <td scope="row"><?= $no ?></td>
                        <td style="text-align: center; vertical-align: middle;"><?= !empty($nama_mahasiswa[0]->nama) ? $nama_mahasiswa[0]->nama : "" ?></td>
                        <td style="text-align: center; vertical-align: middle;"><?= $judul[0]->judul_topik ?></td>
                        <td style="font-size: 16px; vertical-align: middle;" class=" <?= empty($pembimbing1[0]->nilai_bimbingan) ? "text-danger" : "text-success" ?> text-center"><?= empty($pembimbing1[0]->nilai_bimbingan) ? "Belum <br> Dinilai" : $pembimbing1[0]->nilai_bimbingan ?></td>
                        <td style="font-size: 16px; vertical-align: middle;" class=" <?= empty($pembimbing2[0]->nilai_bimbingan) ? "text-danger" : "text-success" ?> text-center"><?= empty($pembimbing2[0]->nilai_bimbingan) ? "Belum <br> Dinilai" : $pembimbing2[0]->nilai_bimbingan ?></td>
                        <td style="font-size: 16px; vertical-align: middle;" class=" <?= empty($penguji1[0]->nilai_ujian) ? "text-danger" : "text-success" ?> text-center"><?= empty($penguji1[0]->nilai_ujian) ? "Belum <br> Dinilai" : $penguji1[0]->nilai_ujian ?></td>
                        <td style="font-size: 16px; vertical-align: middle;" class=" <?= empty($penguji2[0]->nilai_ujian) ? "text-danger" : "text-success" ?> text-center"><?= empty($penguji2[0]->nilai_ujian) ? "Belum <br> Dinilai" : $penguji2[0]->nilai_ujian ?></td>
                        <td style="font-size: 16px; vertical-align: middle;" class=" <?= empty($penguji3[0]->nilai_ujian) ? "text-danger" : "text-success" ?> text-center"><?= empty($penguji3[0]->nilai_ujian) ? "Belum <br> Dinilai" : $penguji3[0]->nilai_ujian ?></td>
                        <td><?= $grade ?></td>
                      </tr>
                    <?php
                      $no++;
                    } ?>

                  </tbody>
                </table>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>







<?= $this->endSection(); ?>