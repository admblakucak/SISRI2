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
            <div class="card-title mg-b-0">Informasi Nilai</div>
            <i class="mdi mdi-dots-horizontal text-gray"></i>
          </div>
          <!-- <p class="tx-12 tx-gray-500 mb-2">Daftar Nilai</p> -->
        </div>
        <div class="row mt-3">
          <div class="col">
            <div class="card-body">
              <hr>
              <div class="table-responsive">

                <a href="<?= base_url() ?>nilai_akhir_skripsi/<?= session()->get('ses_id') ?>/<?= session()->get('ses_idunit') ?>"><button class="btn btn-primary mb-3" type="button">Cetak</button></a>
                <table class="table table-striped mg-b-0 text-md-nowrap" id="validasitable2">
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
                    $id_pendaftar = $db->query("SELECT * FROM tb_pendaftar_sidang a LEFT JOIN tb_jadwal_sidang b ON a.`id_jadwal`=b.`id_jadwal` WHERE b.`jenis_sidang`='sidang skripsi' AND nim='" . session()->get('ses_id') . "' ORDER BY create_at DESC LIMIT 1")->getResult()[0]->id_pendaftar;
                    $jadwal_sidang = $db->query("SELECT * FROM tb_pendaftar_sidang WHERE id_pendaftar='$id_pendaftar' AND hasil_sidang < 3")->getResult();
                    // dd($jadwal_sidang);
                    if (!empty($jadwal_sidang)) {
                      $d_now = new Datetime();
                      $d_sidang = new Datetime($jadwal_sidang[0]->waktu_sidang);
                      if ($d_now > $d_sidang) {
                        $selisih = date_diff($d_now, $d_sidang);
                        // karena $d_now utc+00 sedangkan $d_sidang utc+7
                        if ($selisih->h >= 17) {
                          $cek_nilai = $db->query("SELECT * FROM tb_nilai where nim ='" . session()->get('ses_id') . "' ORDER BY `tb_nilai`.`sebagai` ASC")->getResult();
                          if (count($cek_nilai) <= 0) {
                            dd(count($cek_nilai));
                            $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian) VALUES ('" . session()->get('ses_id') . "','" . $penguji1[0]->nip . "','penguji 1','80')");
                            $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian) VALUES ('" . session()->get('ses_id') . "','" . $penguji2[0]->nip . "','penguji 2','80')");
                            $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian) VALUES ('" . session()->get('ses_id') . "','" . $penguji3[0]->nip . "','penguji 3','80')");
                          } else {
                            $cek_nilai_penguji1 = $db->query("SELECT * FROM tb_nilai where nim ='" . session()->get('ses_id') . "' AND nip ='" . $penguji1[0]->nip . "' AND sebagai = 'penguji 1' ")->getResult();
                            if (empty($cek_nilai_penguji1) || empty($cek_nilai_penguji1[0]->nilai_ujian)) {
                              $db->query("UPDATE `tb_nilai` SET nilai_ujian='80' where id_nilai='" . $cek_nilai_penguji1[0]->id_nilai . "'");
                            }
                            $cek_nilai_penguji2 = $db->query("SELECT * FROM tb_nilai where nim ='" . session()->get('ses_id') . "' AND nip ='" . $penguji2[0]->nip . "' AND sebagai = 'penguji 2' ")->getResult();
                            if (empty($cek_nilai_penguji2) || empty($cek_nilai_penguji2[0]->nilai_ujian)) {
                              $db->query("UPDATE `tb_nilai` SET nilai_ujian='80' where id_nilai='" . $cek_nilai_penguji2[0]->id_nilai . "'");
                              // $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian) VALUES ('" . session()->get('ses_id') . "','" . $penguji2[0]->nip . "','penguji 2','80')");
                            }
                            $cek_nilai_penguji3 = $db->query("SELECT * FROM tb_nilai where nim ='" . session()->get('ses_id') . "' AND nip ='" . $penguji3[0]->nip . "' AND sebagai = 'penguji 3' ")->getResult();
                            if (empty($cek_nilai_penguji3) || empty($cek_nilai_penguji3[0]->nilai_ujian)) {
                              $db->query("UPDATE `tb_nilai` SET nilai_ujian='80' where id_nilai='" . $cek_nilai_penguji3[0]->id_nilai . "'");
                              // $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian) VALUES ('" . session()->get('ses_id') . "','" . $penguji3[0]->nip . "','penguji 3','80')");
                            }
                            $cek_nilai_pembimbing1 =  $db->query("SELECT * FROM tb_nilai where nim ='" . session()->get('ses_id') . "' AND sebagai = 'pembimbing 1' ")->getResult();
                            if (empty($cek_nilai_pembimbing1) || empty($cek_nilai_pembimbing1[0]->nilai_bimbingan) || empty($cek_nilai_pembimbing1[0]->nilai_ujian)) {
                              $db->query("UPDATE `tb_nilai` SET nilai_ujian='80', nilai_bimbingan='80' where id_nilai='" . $cek_nilai_pembimbing1[0]->id_nilai . "'");
                              // $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian,nilai_bimbingan) VALUES ('" . session()->get('ses_id') . "','" . $dosen_pembimbing[0]->nip . "','pembimbing 1','80','80')");
                            }
                            $cek_nilai_pembimbing2 =  $db->query("SELECT * FROM tb_nilai where nim ='" . session()->get('ses_id') . "' AND sebagai = 'pembimbing 2' ")->getResult();
                            if (empty($cek_nilai_pembimbing2)  || empty($cek_nilai_pembimbing2[0]->nilai_bimbingan) || empty($cek_nilai_pembimbing2[0]->nilai_ujian)) {
                              $db->query("UPDATE `tb_nilai` SET nilai_ujian='80', nilai_bimbingan='80' where id_nilai='" . $cek_nilai_pembimbing2[0]->id_nilai . "'");
                              // $db->query("INSERT INTO tb_nilai (nim,nip,sebagai,nilai_ujian,nilai_bimbingan) VALUES ('" . session()->get('ses_id') . "','" . $dosen_pembimbing[1]->nip . "','pembimbing 2','80','80')");
                            }
                          }
                        }
                      }
                    }
                    ?>
                    <tr>
                      <td scope="row">1</td>
                      <td style="text-align: center; vertical-align: middle;"><?= session()->get('ses_nama') ?></td>
                      <td style="text-align: center; vertical-align: middle;"><?= $data_judul ?></td>
                      <div class="d-flex justify-content-center col-sm-10 col-md-8 col-lg-6">
                        <td style="font-size: 16px; vertical-align: middle;" class=" <?= empty($nilai_pem1[0]->nilai_ujian) ? "text-danger" : "text-success" ?> text-center"><?= empty($nilai_pem1[0]->nilai_ujian) ? "Belum <br> Dinilai" : $nilai_pem1[0]->nilai_ujian ?></td>
                        <td style="font-size: 16px; vertical-align: middle;" class=" <?= empty($nilai_pem2[0]->nilai_ujian) ? "text-danger" : "text-success" ?> text-center"><?= empty($nilai_pem2[0]->nilai_ujian) ? "Belum <br> Dinilai" : $nilai_pem2[0]->nilai_ujian ?></td>
                        <td style="font-size: 16px; vertical-align: middle;" class=" <?= empty($nilai_penguji1[0]->nilai_ujian) ? "text-danger" : "text-success" ?> text-center"><?= empty($nilai_penguji1[0]->nilai_ujian) ? "Belum <br> Dinilai" : $nilai_penguji1[0]->nilai_ujian ?></td>
                        <td style="font-size: 16px; vertical-align: middle;" class=" <?= empty($nilai_penguji2[0]->nilai_ujian) ? "text-danger" : "text-success" ?> text-center"><?= empty($nilai_penguji2[0]->nilai_ujian) ? "Belum <br> Dinilai" : $nilai_penguji2[0]->nilai_ujian ?></td>
                        <td style="font-size: 16px; vertical-align: middle;" class=" <?= empty($nilai_penguji3[0]->nilai_ujian) ? "text-danger" : "text-success" ?> text-center"><?= empty($nilai_penguji3[0]->nilai_ujian) ? "Belum <br> Dinilai" : $nilai_penguji3[0]->nilai_ujian ?></td>
                        <?php
                        if (!empty($nilai_pem1[0]->nilai_bimbingan) && !empty($nilai_pem2[0]->nilai_bimbingan) && !empty($nilai_penguji1[0]->nilai_ujian) && !empty($nilai_penguji2[0]->nilai_ujian) && !empty($nilai_penguji3[0]->nilai_ujian)) {
                          $keterangan_lulus = 'Sudah Lulus';
                          $db->query("UPDATE tb_pengajuan_pembimbing SET pesan = 'Sudah Lulus' WHERE nim='" . session()->get('ses_id') . "'");
                        }
                        ?>
                        <td style="font-size: 18px; vertical-align: middle;" class=" text-center"><?= $grade ?></td>

                      </div>
                    </tr>
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