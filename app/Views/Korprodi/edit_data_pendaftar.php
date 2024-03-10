<?php

use CodeIgniter\Images\Image;
?>
<?= $this->extend('Template/content') ?>

<?= $this->section('content') ?>

<div class="" id="modalupdate<?= $id_pendaftar ?>">
  <div class="modal-dialog" role="document">
    <div class="modal-content modal-content-demo">
      <div class="modal-header">
        <h3>Edit data pendaftar sidang</h3>
        <!-- <h6 class="modal-title">Edit Jadwal Sidang</h6><button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button> -->
      </div>
      <form action="<?= base_url() ?>data_pendaftar" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="id_pendaftar" value="<?= $id_pendaftar ?>" />
        <input type="hidden" name="id_jadwal" value="<?= $id_jadwal ?>" />
        <input type="hidden" name="nim" value="<?= $nim ?>" />
        <input type="hidden" name="jenis_sidang" value="<?= $data_jadwal ?>" />
        <div class="modal-body">
          <input type="hidden" name="idunit" value="<?= $idunit ?>">
          <div class="form-group">
            <label for="exampleInputTutup">Waktu Sidang</label>
            <div class="row row-sm">
              <div class="input-group col-md-12">
                <div class="input-group-text">
                  <div class="input-group-text">
                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                  </div>
                </div>
                <input name='waktu_sidang' class="form-control" id="datepickeropen<?= $id_pendaftar ?>" value="<?= $waktu_sidang ?>" type="text">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="exampleInputPeriode">Ruang Sidang</label>
            <input type="teks" class="form-control" id="exampleInput" value="<?= $ruang_sidang ?>" name="ruang_sidang">
          </div>
          <?php if ($data_jadwal == 'seminar proposal' || $data_jadwal == 'sidang skripsi') { ?>

            <div class="form-group">
              <label for="exampleInputJenis Sidang">Penguji 1</label>
              <div class="row row-sm">
                <select class="form-control select select2" name="nip_p1" id="<?= $id_pendaftar ?>">
                  <?php
                  $cek = $db->query("SELECT * FROM tb_penguji where sebagai='1' and id_pendaftar='$id_pendaftar'")->getResult();
                  ?>
                  <option <?= $cek == NULL ? 'selected' : '' ?> disabled> Pilih Penguji 1
                  </option>
                  <?php
                  foreach ($data_dosen_prodi as $key1) {
                    if ($key1->nip != $pem1 && $key1->nip != $pem2) {
                  ?>
                      <option <?= $key1->nip == $penguji_1 ? 'selected' : '' ?> value="<?= $key1->nip ?>">
                        <?= $key1->nip . ' - ' . $key1->gelardepan . ' ' . $key1->nama . ', ' . $key1->gelarbelakang ?>
                      </option>
                  <?php }
                  } ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="exampleInputJenis Sidang">Penguji 2</label>
              <div class="row row-sm">
                <select class="form-control select select2" name="nip_p2">
                  <?php
                  $cek = $db->query("SELECT * FROM tb_penguji where sebagai='2' and id_pendaftar='$id_pendaftar'")->getResult();
                  ?>
                  <option <?= $cek == NULL ? 'selected' : '' ?> disabled> Pilih Penguji 2
                  </option>
                  <?php
                  foreach ($data_dosen_f as $key1) {
                    if ($key1->nip != $pem1 && $key1->nip != $pem2) {
                  ?>
                      <option <?= $key1->nip == $penguji_2 ? 'selected' : '' ?> value="<?= $key1->nip ?>">
                        <?= $key1->nip . ' - ' . $key1->gelardepan . ' ' . $key1->nama . ', ' . $key1->gelarbelakang ?>
                      </option>
                  <?php }
                  } ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="exampleInputJenis Sidang">Penguji 3</label>
              <div class="row row-sm">
                <select class="form-control select select2" name="nip_p3">
                  <?php
                  $cek = $db->query("SELECT * FROM tb_penguji where sebagai='3' and id_pendaftar='$id_pendaftar'")->getResult();
                  ?>
                  <option <?= $cek == NULL ? 'selected' : '' ?> disabled> Pilih Penguji 3
                  </option>
                  <?php
                  foreach ($data_dosen_fakultas as $key1) {
                    if ($key1->nip != $pem1 && $key1->nip != $pem2) {
                  ?>
                      <option <?= $key1->nip == $penguji_3 ? 'selected' : '' ?> value="<?= $key1->nip ?>">
                        <?= $key1->nip . ' - ' . $key1->gelardepan . ' ' . $key1->nama . ', ' . $key1->gelarbelakang ?>
                      </option>
                  <?php }
                  } ?>
                </select>

              </div>
            </div>
          <?php } ?>
        </div>
        <script type="text/javascript">
          $(function() {
            $('#datepickeropen<?= $id_pendaftar ?>').datetimepicker();
            $('#datepickerexpire<?= $id_pendaftar ?>').datetimepicker();
          });
        </script>
        <div class="modal-footer">
          <input type="hidden" name="update" value="update" />
          <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button" onClick="history_back()">Kembali</button>
          <button class="btn ripple btn-primary" type="submit">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function history_back() {
    window.history.back();
  }
</script>


<?= $this->endSection(); ?>