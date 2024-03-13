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
            <h4 class="card-title mg-b-0">Data Dosen</h4>
          </div>
          <p class="tx-12 tx-gray-500 mb-2">Data dosen <?= $nama_prodi ?> yang telah mengakses SISRI.</a></p>
          <?= session()->getFlashdata('message'); ?>
          <div class="row mt-3">
            <div class="col-xl-12">
              <div class="table-responsive">
                <table class="table text-md-nowrap" id="validasitable2">
                  <thead>
                    <tr>
                      <th style="text-align: center; vertical-align: middle;"><span>No.</span></th>
                      <th style="text-align: center; vertical-align: middle;"><span>NIP</span></th>
                      <th style="text-align: center; vertical-align: middle;"><span>NIDN</span></th>
                      <th style="text-align: center; vertical-align: middle;"><span>Nama</span></th>
                      <th style="text-align: center; vertical-align: middle;"><span>Jenis Kelamin</span></th>
                      <th style="text-align: center; vertical-align: middle;"><span>Kuota Bimbingan</span></th>
                      <!-- <th style="text-align: center; vertical-align: middle;"><span>Aksi</span></th> -->
                    </tr>
                  </thead>
                  <tbody>
                    <?php $no = 1;
                    foreach ($data as $key) :
                      $jumlah_bimbingan1 = $db->query("SELECT * FROM tb_pengajuan_pembimbing where nip='$key->nip' AND sebagai='1' AND status_pengajuan='diterima'")->getResult();
                      $jumlah_bimbingan2 = $db->query("SELECT * FROM tb_pengajuan_pembimbing where nip='$key->nip' AND sebagai='2' AND status_pengajuan='diterima'")->getResult();
                      $jumlah_bimbinganselesai1 = $db->query("SELECT * FROM `tb_nilai` WHERE nip='$key->nip' AND sebagai='pembimbing 1' AND nilai_ujian is NOT null AND nilai_bimbingan is not null")->getResult();
                      $jumlah_bimbinganselesai2 = $db->query("SELECT * FROM `tb_nilai` WHERE nip='$key->nip' AND sebagai='pembimbing 2' AND nilai_ujian is NOT null AND nilai_bimbingan is not null")->getResult();
                      $db->query("UPDATE tb_jumlah_pembimbing SET jumlah='" . (count($jumlah_bimbingan1) - count($jumlah_bimbinganselesai1)) . "' WHERE nip='$key->nip' AND sebagai='pembimbing 1' ");
                      $db->query("UPDATE tb_jumlah_pembimbing SET jumlah='" . (count($jumlah_bimbingan2) - count($jumlah_bimbinganselesai2)) . "' WHERE nip='$key->nip' AND sebagai='pembimbing 2' ");

                      $kuota_p1 = $db->query("SELECT * FROM tb_jumlah_pembimbing WHERE nip='$key->nip' AND sebagai='pembimbing 1'")->getResult();
                      $kuota_p2 = $db->query("SELECT * FROM tb_jumlah_pembimbing WHERE nip='$key->nip' AND sebagai='pembimbing 2'")->getResult();
                    ?>
                      <tr>
                        <td><?= $no; ?></td>
                        <td><?= $key->nip; ?></td>
                        <td><?= $key->nidn; ?></td>
                        <td><?= $key->gelardepan . ' ' . $key->nama . ', ' . $key->gelarbelakang; ?></td>
                        <td><?= $key->jk; ?></td>
                        <td>
                          Pembimbing 1 : <?= $kuota_p1 != NULL ? $kuota_p1[0]->jumlah . '/' . $kuota_p1[0]->kuota : "0/10" ?>
                          <br>
                          Pembimbing 2 : <?= $kuota_p2 != NULL ? $kuota_p2[0]->jumlah . '/' . $kuota_p2[0]->kuota : "0/10" ?>
                        </td>
                 
                      </tr>
                      <div class="modal" id="modalupdate<?= $key->nip ?>">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content modal-content-demo">
                            <div class="modal-header">
                              <h6 class="modal-title">Update Data Dosen</h6><button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <form action="<?= base_url() ?>update_kuota_dosen" method="POST" enctype="multipart/form-data">
                              <?= csrf_field() ?>
                              <input type="hidden" name="nip" value="<?= $key->nip ?>" />
                              <div class="modal-body">
                                <label for="">Kuota pembimbing 1</label>
                                <div class="input-group">
                                  <input class="form-control form-control-sm" name='p1' type="text" value=" <?= $kuota_p1 != NULL ? $kuota_p1[0]->kuota : "10" ?>">
                                </div>
                                <label for="" class="mt-2">Kuota Pembimbing 2</label>
                                <div class="input-group">
                                  <input class="form-control form-control-sm" name='p2' type="text" value=" <?= $kuota_p2 != NULL ? $kuota_p2[0]->kuota : "10" ?>">
                                </div>
                              </div>
                              <div class="modal-footer">
                                <button class="btn ripple btn-warning" type="submit">Update</button>
                                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">Keluar</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    <?php $no++;
                    endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-md-9 col-lg-9 col-xl-9">
              <label class="form-label"><b>Cetak Data Dosen</b></label>
              <p class="tx-12 tx-gray-500 pt-0">Data dosen yang akan dicetak adalah menurut jumlah mahasiswa yang telah melakukan sidang skripsi menurut periode tertentu.</p>
            </div>
            <div class="col-md-3 col-lg-3 col-xl-3">
              <div class="btn-list">
                <a aria-controls="multiCollapseExample" aria-expanded="false" class="btn ripple btn-light plus float-right" href=".multi-collapse" data-bs-toggle="collapse" role="button">OPEN</a>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="collapse <?= session()->getFlashdata('message') != NULL ? 'show' : ''  ?> multi-collapse" id="multiCollapseExample">
                <form action="<?= base_url() ?>direct_hasil_dosen" method="POST" enctype="multipart/form-data">
                  <?= csrf_field() ?>
                  <div class="row row-sm">
                    <input class="form-control" name="idunit" type="hidden">
                    <div class="col-3">
                      <div class="form-group">
                        <label class="form-label">Tanggal Mulai</label>
                        <input class="form-control" name="start" type="date">
                      </div>
                    </div>
                    <div class="col-3">
                      <div class="form-group">
                        <label class="form-label">Tanggal Akhir</label>
                        <input class="form-control" name="end" type="date">
                      </div>
                    </div>
                    <div class="col-3">
                      <div class="form-group">
                        <label class="form-label">Jenis File</label>
                        <select class="form-control select2" name="jenis_file">
                          <option value="pdf" selected>PDF</option>
                          <option value="excel">Excel</option>
                        </select>
                      </div>
                    </div>

                    <div class="col-3"><button class="btn btn-success pd-x-20 mt-4" type="submit"><i class="fa fa-print"></i></button>
                      <a aria-controls="multiCollapseExample" aria-expanded="false" class="btn ripple btn-light pd-x-20 mt-4" href=".multi-collapse" data-bs-toggle="collapse" role="button" class="col-md-10 col-lg-9 col-xl-2 offset-xl-10 pt-4">Tutup</a>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
          <hr>
        </div>

      </div>
    </div>
  </div>
</div>

<?= $this->endSection(); ?>