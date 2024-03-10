<?php

use CodeIgniter\Images\Image;
?>
<?= $this->extend('Template/content') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
  <div class="row mt-3">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header pb-0">
          <div class="d-flex justify-content-between">
            <h4 class="card-title mg-b-0">Riwayat Bimbingan</h4>
            <i class="mdi mdi-dots-horizontal text-gray"></i>
          </div>
          <p class="tx-12 tx-gray-500 mb-2">Daftar Riwayat Bimbingan Mahasiswa</a></p>
        </div>
        <div class="row">
          <div class="col-xl-12">
            <div class="card-body">
              <div class="text-wrap">
                <div class="example">
                  <div class="panel panel-primary tabs-style-2">
                    <div class="tab-menu-heading">
                      <div class="tabs-menu1">
                        <ul class="nav panel-tabs main-nav-line">
                          <li><a href="#pembimbing" class="nav-link active" data-bs-toggle="tab">Pembimbing</a></li>
                          <li><a href="#penguji" class="nav-link" data-bs-toggle="tab">Penguji</a></li>
                        </ul>
                      </div>
                    </div>
                    <div class="panel-body tabs-menu-body main-content-body-right border">
                      <div class="tab-content">
                        <div class="tab-pane active" id="pembimbing">
                          <div class="row">
                            <div class="col-xl-12">
                              <div class="table-responsive">
                                <table class="table table-striped mg-b-0 text-md-nowrap" id="validasitable1">
                                  <thead>
                                    <tr>
                                      <th style="text-align: center; vertical-align: middle;"><span>&nbsp;</span></th>
                                      <th style="text-align: center; vertical-align: middle;"><span>Nama </span></th>
                                      <th style="text-align: center; vertical-align: middle;"><span>Judul</span></th>
                                      <th style="text-align: center; vertical-align: middle;"><span>Pembimbing 1</span></th>
                                      <th style="text-align: center; vertical-align: middle;"><span>Pembimbing 2</span></th>
                                      <th style="text-align: center; vertical-align: middle;"><span>Penguji 1</span></th>
                                      <th style="text-align: center; vertical-align: middle;"><span>Penguji 2</span></th>
                                      <th style="text-align: center; vertical-align: middle;"><span>Penguji 3</span></th>
                                      <th style="text-align: center; vertical-align: middle;"><span>Keterangan Lulus</span></th>
                                    </tr>
                                  </thead>
                                  <tbody id='show_data2'>
                                    <?php
                                    $no = 1;
                                    // dd($data_mhs_bimbingan);
                                    foreach ($data_mhs_bimbingan as $key) {
                                      // $pem1 = $db->query("SELECT a.nim,a.nip,a.sebagai, b.nama,b.gelardepan,b.gelarbelakang, c.nilai_bimbingan, c.nilai_ujian FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.nip=b.nip LEFT JOIN tb_nilai c ON a.nip = c.nip WHERE a.status_pengajuan = 'diterima' AND a.nim = '" . $key['nim'] . "' AND a.sebagai ='1' ORDER BY `c`.`nilai_bimbingan` DESC")->getResult();
                                      // $pem1 = $db->query("SELECT a.nim,a.nip,a.sebagai, b.nama,b.gelardepan,b.gelarbelakang, c.nilai_bimbingan, c.nilai_ujian FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.nip=b.nip LEFT JOIN tb_nilai c ON a.nip = c.nip WHERE a.status_pengajuan = 'diterima' AND a.nim = '" . '180441100116' . "' AND a.sebagai ='1' ORDER BY `c`.`nilai_bimbingan` DESC")->getResult();
                                      // $pem2 = $db->query("SELECT a.nim,a.nip,a.sebagai, b.nama,b.gelardepan,b.gelarbelakang, c.nilai_bimbingan, c.nilai_ujian FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.nip=b.nip LEFT JOIN tb_nilai c ON a.nip = c.nip WHERE a.status_pengajuan = 'diterima' AND a.nim = '" . $key['nim'] . "' AND a.sebagai ='2' ORDER BY `c`.`nilai_bimbingan` DESC")->getResult();


                                      $pem1 = $db->query("SELECT a.nip,b.nama,b.gelardepan,b.gelarbelakang from tb_pengajuan_pembimbing a left join tb_dosen b on a.nip=b.nip where a.nim = '" . $key['nim'] . "' AND a.status_pengajuan = 'diterima' AND sebagai = 1")->getResult();
                                      $pem2 = $db->query("SELECT a.nip,b.nama,b.gelardepan,b.gelarbelakang from tb_pengajuan_pembimbing a left join tb_dosen b on a.nip=b.nip where a.nim = '" . $key['nim'] . "' AND a.status_pengajuan = 'diterima' AND sebagai = 2")->getResult();
                                      $penguji1 = $db->query("SELECT a.nip,b.nama,b.gelardepan,b.gelarbelakang from tb_penguji a left join tb_dosen b on a.nip=b.nip where a.nim = '" . $key['nim'] . "' AND a.status = 'aktif' AND sebagai = 1")->getResult();
                                      $penguji2 = $db->query("SELECT a.nip,b.nama,b.gelardepan,b.gelarbelakang from tb_penguji a left join tb_dosen b on a.nip=b.nip where a.nim = '" . $key['nim'] . "' AND a.status = 'aktif' AND sebagai = 2")->getResult();
                                      $penguji3 = $db->query("SELECT a.nip,b.nama,b.gelardepan,b.gelarbelakang from tb_penguji a left join tb_dosen b on a.nip=b.nip where a.nim = '" . $key['nim'] . "' AND a.status = 'aktif' AND sebagai = 3")->getResult();

                                      $nilai_pem1 = $db->query("SELECT * FROM `tb_nilai`  WHERE nim = '" . $key['nim'] . "' AND sebagai = 'pembimbing 1'")->getResult();
                                      $nilai_pem2 = $db->query("SELECT * FROM `tb_nilai`  WHERE nim = '" . $key['nim'] . "' AND sebagai = 'pembimbing 2'")->getResult();
                                      $nilai_penguji1 = $db->query("SELECT * FROM `tb_nilai`  WHERE nim = '" . $key['nim'] . "' AND sebagai = 'penguji 1'")->getResult();
                                      $nilai_penguji2 = $db->query("SELECT * FROM `tb_nilai`  WHERE nim = '" . $key['nim'] . "' AND sebagai = 'penguji 2'")->getResult();
                                      $nilai_penguji3 = $db->query("SELECT * FROM `tb_nilai`  WHERE nim = '" . $key['nim'] . "' AND sebagai = 'penguji 3'")->getResult();

                                      $judul = $db->query("SELECT * FROM tb_pengajuan_topik WHERE nim='" . $key['nim'] . "'")->getResult();
                                      $acc_dosen_penguji_1 = $db->query("SELECT * FROM `tb_acc_revisi` WHERE `nim` ='" . $key['nim'] . "' AND jenis_sidang = 'seminar proposal' AND sebagai ='penguji 1'")->getResult();
                                      $acc_dosen_penguji_2 = $db->query("SELECT * FROM `tb_acc_revisi` WHERE `nim` ='" . $key['nim'] . "' AND jenis_sidang = 'seminar proposal' AND sebagai ='penguji 2'")->getResult();
                                      $acc_dosen_penguji_3 = $db->query("SELECT * FROM `tb_acc_revisi` WHERE `nim` ='" . $key['nim'] . "' AND jenis_sidang = 'seminar proposal' AND sebagai ='penguji 3'")->getResult();
                                      $keterangan_lulus = 'belum lulus';
                                      // dd($total_acc_dosen_penguji);
                                      if (empty($acc_dosen_penguji_1) || empty($acc_dosen_penguji_2) || empty($acc_dosen_penguji_3)) {

                                        continue;
                                      }

                                      $status_acc_revisi = $db->query("SELECT * FROM tb_acc_revisi WHERE nim ='" . $key['nim'] . "' AND nip='" . session()->get('ses_id') . "' AND jenis_sidang='skripsi'")->getResult();
                                      // if (empty($status_acc_revisi)) {
                                      //   // dd($status_acc_revisi);
                                      //   continue;
                                      // }
                                      // dd("SELECT * FROM tb_acc_revisi WHERE nim ='" . $key['nim'] . "' AND nip='" . session()->get('ses_id') . "' AND jenis_sidang='skripsi'");
                                      if (!empty($nilai_pem1[0]->nilai_bimbingan) && !empty($nilai_pem2[0]->nilai_bimbingan) && !empty($nilai_penguji1[0]->nilai_ujian) && !empty($nilai_penguji2[0]->nilai_ujian) && !empty($nilai_penguji3[0]->nilai_ujian)) {
                                        // dd($nilai_pem2[0]->nilai_bimbingan);
                                        $keterangan_lulus = 'Sudah Lulus';
                                        $db->query("UPDATE tb_pengajuan_pembimbing SET pesan = 'Sudah Lulus' WHERE nim='" . $key['nim'] . "'");
                                      }
                                      // if (count($total_acc_dosen_penguji) != 3) {
                                      //   continue;
                                      // }
                                    ?>
                                      <tr>
                                        <td>
                                        </td>
                                        <td>
                                          <?= $key['nim'] . ' - ' . $key['nama_mhs']; ?>
                                        </td>
                                        <td scope="row"><?= $judul[0]->judul_topik ?></td>
                                        <td scope="row">
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
                                        <td scope="row">
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
                                        <td scope="row">
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
                                        <td scope="row">
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
                                        <td scope="row">
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
                                        <td class="<?= $keterangan_lulus == 'Sudah Lulus' ? 'text-success' : 'text-danger' ?>"><?= $keterangan_lulus ?></td>
                                      </tr>
                                    <?php $no++;
                                    } ?>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="tab-pane" id="penguji">
                          <div class="row">
                            <div class="col-xl-12">
                              <div class="table-responsive">
                                <table class="table table-striped mg-b-0 text-md-nowrap" id="validasitable2">
                                  <thead>
                                    <tr>
                                      <th style="text-align: center; vertical-align: middle;"><span>&nbsp;</span></th>
                                      <th style="text-align: center; vertical-align: middle;"><span>Nama </span></th>
                                      <th style="text-align: center; vertical-align: middle;"><span>Judul</span></th>
                                      <th style="text-align: center; vertical-align: middle;"><span>Pembimbing 1</span></th>
                                      <th style="text-align: center; vertical-align: middle;"><span>Pembimbing 2</span></th>
                                      <th style="text-align: center; vertical-align: middle;"><span>Penguji 1</span></th>
                                      <th style="text-align: center; vertical-align: middle;"><span>Penguji 2</span></th>
                                      <th style="text-align: center; vertical-align: middle;"><span>Penguji 3</span></th>
                                      <th style="text-align: center; vertical-align: middle;"><span>Tanggal Sidang</span></th>
                                      <!-- <th style="text-align: center; vertical-align: middle;"><span>Jenis Sidang</span></th> -->
                                      <th style="text-align: center; vertical-align: middle;"><span>Keterangan Lulus</span></th>
                                    </tr>
                                  </thead>
                                  <tbody id='show_data3'>
                                    <?php
                                    $no = 1;
                                    // dd($data_mhs_bimbingan);
                                    foreach ($data_mhs_uji as $key) {
                                      // $pem1 = $db->query("SELECT a.nim,a.nip,a.sebagai, b.nama,b.gelardepan,b.gelarbelakang, c.nilai_bimbingan, c.nilai_ujian FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.nip=b.nip LEFT JOIN tb_nilai c ON a.nip = c.nip WHERE a.status_pengajuan = 'diterima' AND a.nim = '" . $key['nim'] . "' AND a.sebagai ='1' ORDER BY `c`.`nilai_bimbingan` DESC")->getResult();
                                      // $pem1 = $db->query("SELECT a.nim,a.nip,a.sebagai, b.nama,b.gelardepan,b.gelarbelakang, c.nilai_bimbingan, c.nilai_ujian FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.nip=b.nip LEFT JOIN tb_nilai c ON a.nip = c.nip WHERE a.status_pengajuan = 'diterima' AND a.nim = '" . '180441100116' . "' AND a.sebagai ='1' ORDER BY `c`.`nilai_bimbingan` DESC")->getResult();
                                      // $pem2 = $db->query("SELECT a.nim,a.nip,a.sebagai, b.nama,b.gelardepan,b.gelarbelakang, c.nilai_bimbingan, c.nilai_ujian FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.nip=b.nip LEFT JOIN tb_nilai c ON a.nip = c.nip WHERE a.status_pengajuan = 'diterima' AND a.nim = '" . $key['nim'] . "' AND a.sebagai ='2' ORDER BY `c`.`nilai_bimbingan` DESC")->getResult();


                                      $pem1 = $db->query("SELECT a.nip,b.nama,b.gelardepan,b.gelarbelakang from tb_pengajuan_pembimbing a left join tb_dosen b on a.nip=b.nip where a.nim = '" . $key['nim'] . "' AND a.status_pengajuan = 'diterima' AND sebagai = 1")->getResult();
                                      $pem2 = $db->query("SELECT a.nip,b.nama,b.gelardepan,b.gelarbelakang from tb_pengajuan_pembimbing a left join tb_dosen b on a.nip=b.nip where a.nim = '" . $key['nim'] . "' AND a.status_pengajuan = 'diterima' AND sebagai = 2")->getResult();
                                      $penguji1 = $db->query("SELECT a.nip,b.nama,b.gelardepan,b.gelarbelakang from tb_penguji a left join tb_dosen b on a.nip=b.nip where a.nim = '" . $key['nim'] . "' AND a.status = 'aktif' AND sebagai = 1")->getResult();
                                      $penguji2 = $db->query("SELECT a.nip,b.nama,b.gelardepan,b.gelarbelakang from tb_penguji a left join tb_dosen b on a.nip=b.nip where a.nim = '" . $key['nim'] . "' AND a.status = 'aktif' AND sebagai = 2")->getResult();
                                      $penguji3 = $db->query("SELECT a.nip,b.nama,b.gelardepan,b.gelarbelakang from tb_penguji a left join tb_dosen b on a.nip=b.nip where a.nim = '" . $key['nim'] . "' AND a.status = 'aktif' AND sebagai = 3")->getResult();

                                      $nilai_pem1 = $db->query("SELECT * FROM `tb_nilai`  WHERE nim = '" . $key['nim'] . "' AND sebagai = 'pembimbing 1'")->getResult();
                                      $nilai_pem2 = $db->query("SELECT * FROM `tb_nilai`  WHERE nim = '" . $key['nim'] . "' AND sebagai = 'pembimbing 2'")->getResult();
                                      $nilai_penguji1 = $db->query("SELECT * FROM `tb_nilai`  WHERE nim = '" . $key['nim'] . "' AND sebagai = 'penguji 1'")->getResult();
                                      $nilai_penguji2 = $db->query("SELECT * FROM `tb_nilai`  WHERE nim = '" . $key['nim'] . "' AND sebagai = 'penguji 2'")->getResult();
                                      $nilai_penguji3 = $db->query("SELECT * FROM `tb_nilai`  WHERE nim = '" . $key['nim'] . "' AND sebagai = 'penguji 3'")->getResult();

                                      $judul = $db->query("SELECT * FROM tb_pengajuan_topik WHERE nim='" . $key['nim'] . "'")->getResult();
                                      $acc_dosen_penguji_1 = $db->query("SELECT * FROM `tb_acc_revisi` WHERE `nim` ='" . $key['nim'] . "' AND jenis_sidang = 'seminar proposal' AND sebagai ='penguji 1'")->getResult();
                                      $acc_dosen_penguji_2 = $db->query("SELECT * FROM `tb_acc_revisi` WHERE `nim` ='" . $key['nim'] . "' AND jenis_sidang = 'seminar proposal' AND sebagai ='penguji 2'")->getResult();
                                      $acc_dosen_penguji_3 = $db->query("SELECT * FROM `tb_acc_revisi` WHERE `nim` ='" . $key['nim'] . "' AND jenis_sidang = 'seminar proposal' AND sebagai ='penguji 3'")->getResult();
                                      $keterangan_lulus = 'belum lulus';
                                      $jadwal_sidang = $db->query("SELECT a.*,b.jenis_sidang FROM `tb_pendaftar_sidang` a LEFT JOIN tb_jadwal_sidang b ON a.id_jadwal=b.id_jadwal WHERE nim ='180411100075' AND (hasil_sidang <3 or hasil_sidang IS NULL)  AND b.jenis_sidang = 'sidang skripsi'")->getResult();

                                      if (empty($acc_dosen_penguji_1) || empty($acc_dosen_penguji_2) || empty($acc_dosen_penguji_3)) {

                                        continue;
                                      }

                                      $status_acc_revisi = $db->query("SELECT * FROM tb_acc_revisi WHERE nim ='" . $key['nim'] . "' AND nip='" . session()->get('ses_id') . "' AND jenis_sidang='skripsi'")->getResult();
                                      if (empty($status_acc_revisi)) {
                                        // dd($status_acc_revisi);
                                        continue;
                                      }
                                      // dd("SELECT * FROM tb_acc_revisi WHERE nim ='" . $key['nim'] . "' AND nip='" . session()->get('ses_id') . "' AND jenis_sidang='skripsi'");
                                      if (!empty($nilai_pem1[0]->nilai_bimbingan) && !empty($nilai_pem2[0]->nilai_bimbingan) && !empty($nilai_penguji1[0]->nilai_ujian) && !empty($nilai_penguji2[0]->nilai_ujian) && !empty($nilai_penguji3[0]->nilai_ujian)) {
                                        // dd($nilai_pem2[0]->nilai_bimbingan);
                                        $keterangan_lulus = 'Sudah Lulus';
                                        $db->query("UPDATE tb_pengajuan_pembimbing SET pesan = 'Sudah Lulus' WHERE nim='" . $key['nim'] . "'");
                                      }
                                      // if (count($total_acc_dosen_penguji) != 3) {
                                      //   continue;
                                      // }
                                    ?>
                                      <tr>
                                        <td>
                                        </td>
                                        <td>
                                          <?= $key['nim'] . ' - ' . $key['nama_mhs']; ?>
                                        </td>
                                        <td scope="row"><?= $judul[0]->judul_topik ?></td>
                                        <td scope="row">
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
                                        <td scope="row">
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
                                        <td scope="row">
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
                                        <td scope="row">
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
                                        <td scope="row">
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
                                        <td><?= !empty($jadwal_sidang) && !empty($jadwal_sidang[0]->waktu_sidang)  ?  $jadwal_sidang[0]->waktu_sidang : 'null' ?> </td>
                                        <td class="<?= $keterangan_lulus == 'Sudah Lulus' ? 'text-success' : 'text-danger' ?>"><?= $keterangan_lulus ?></td>
                                      </tr>
                                    <?php $no++;
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
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?= $this->endSection(); ?>