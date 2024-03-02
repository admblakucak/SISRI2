<?php


use CodeIgniter\Images\Image;
?>
<?= $this->extend('Template/content') ?>

<?= $this->section('content') ?>

<link rel="stylesheet" href="https://cdn.datatables.net/2.0.1/css/dataTables.dataTables.css">

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

                        foreach ($data_nilai as $key) { ?>
                          <td style="font-size: 16px; vertical-align: middle;" class=" <?= empty($key->nilai_ujian) ? "text-danger" : "text-success" ?> text-center"><?= empty($key->nilai_ujian) ? "Belum <br> Dinilai" : "Sudah <br>Dinilai" ?></td>
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

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.0.1/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.0/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>




<?= $this->endSection(); ?>