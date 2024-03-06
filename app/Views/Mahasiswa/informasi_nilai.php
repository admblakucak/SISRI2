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
                    <tr>
                      <td scope="row">1</td>
                      <td style="text-align: center; vertical-align: middle;"><?= session()->get('ses_nama') ?></td>
                      <td style="text-align: center; vertical-align: middle;"><?= $data_judul ?></td>
                      <div class="d-flex justify-content-center col-sm-10 col-md-8 col-lg-6">
                        <?php
                        // dd($data_nilai);
                        foreach ($data_nilai as $key) {

                        ?>
                          <td style="font-size: 16px; vertical-align: middle;" class=" <?= empty($key->nilai_ujian) ? "text-danger" : "text-success" ?> text-center"><?= empty($key->nilai_ujian) ? "Belum <br> Dinilai" : $key->nilai_ujian ?></td>
                        <?php } ?>

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