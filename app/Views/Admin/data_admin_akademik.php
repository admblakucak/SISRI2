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
                        <h4 class="card-title mg-b-0">Data Admin Akademik</h4>
                    </div>
                    <p class="tx-12 tx-gray-500 mb-2">Data Admin Akademik</p>
                    <div class="row">
                        <div class="col-xl-12">
                            <?= session()->getFlashdata('message'); ?>
                            <a class="btn btn-primary  btn-sm mt-1" data-bs-target="#modaladd" id="" data-bs-toggle="modal" href="#">Tambah Admin Akademik</a>
                            <div class="modal" id="modaladd">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content modal-content-demo">
                                        <form action="<?= base_url() ?>add_admin_akademik" method="POST" enctype="multipart/form-data">
                                            <?= csrf_field() ?>
                                            <div class="modal-header">
                                                <h6 class="modal-title">Tambah Admin Akademik</h6><button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <label for="">NIP</label>
                                                <input class="form-control" id="inputFirstName" type="text" name="nip" required>
                                                <small class="text-danger">*</small>
                                                <label for="" class="mt-2">Gelar Depan</label>
                                                <small class="text-danger">( Contoh : Dr. )</small>
                                                <input class="form-control" id="inputFirstName" type="text" name="gelar_depan">
                                                <label for="" class="mt-2">Nama</label>
                                                <input class="form-control" id="inputFirstName" type="text" name="nama" required>
                                                <label for="" class="mt-2">Gelar Bekalang</label>
                                                <small class="text-danger">( Contoh : S.H., M.H.)</small>
                                                <input class="form-control" id="inputFirstName" type="text" name="gelar_belakang">
                                                <label for="" class="mt-2">Jenis Kelamin</label>
                                                <select class="form-control select" name="jenis_kelamin">
                                                    <option value='L'>Laki-Laki</option>
                                                    <option value='P'>Perempuan</option>
                                                </select>
                                                <label for="" class="mt-2">Program Studi</label>
                                                <select class="form-control select" name="idunit">
                                                    <?php
                                                    foreach ($data_prodi as $keyp) { ?>
                                                        <option value='<?= $keyp->idunit ?>'><?= $keyp->namaunit ?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                                <label for="" class="mt-2">E-mail</label>
                                                <small class="text-danger">( Wajib menggunakan email universitas dan terlah terdaftar )</small>
                                                <input class="form-control" id="inputFirstName" type="text" name="email"" required>
                                            </div>
                                            <div class=" modal-footer">
                                                <button class="btn ripple btn-primary" type="submit">Tambah</button>
                                                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">Keluar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="table-responsive">
                                <table class="table text-md-nowrap" id="validasitable1">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center; vertical-align: middle;"><span>No.</span></th>
                                            <th style="text-align: center; vertical-align: middle;"><span>FAKULTAS</span></th>
                                            <th style="text-align: center; vertical-align: middle;"><span>JURUSAN</span></th>
                                            <th style="text-align: center; vertical-align: middle;"><span>PRODI</span></th>
                                            <th style="text-align: center; vertical-align: middle;"><span>Admin Akademik</span></th>
                                            <th style="text-align: center; vertical-align: middle;"><span>AKSI</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($data as $key) {
                                        ?>
                                            <tr>
                                                <td style="text-align: center; vertical-align: middle;"><span><?= $no ?></span></td>
                                                <td style="text-align: center; vertical-align: middle;"><span><?= strstr($key->fakultas, " ");  ?></span> </td>
                                                <td style="text-align: center; vertical-align: middle;"><span><?= strstr($key->jurusan, " ") ?></span></td>
                                                <td style="text-align: center; vertical-align: middle;"><span><?= $key->prodi ?></span></td>
                                                <td style="text-align: center; vertical-align: middle;"><span><?= $key->gelar_depan . " " . $key->nama . ", " . $key->gelar_belakang ?></td>
                                                <td style="text-align: center; vertical-align: middle; vertical-align: middle;">
                                                    <input type="hidden" name="id_bimbingan" value="" />
                                                    <div class="btn-group">
                                                        <a class="btn btn-danger btn-sm" data-bs-target="#modaldelete<?= $key->id_admin_akademik ?>" id="" data-bs-toggle="modal" href="#">Hapus</a>
                                                    </div>
                                                </td>
                                            </tr>


                                            <div class="modal" id="modaldelete<?= $key->id_admin_akademik ?>">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content modal-content-demo">

                                                        <form action="<?= base_url() ?>delete_admin_akademik" method="POST" enctype="multipart/form-data">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="id_admin_akademik" value="<?= $key->id_admin_akademik ?>">
                                                            <div class="modal-header">
                                                                <h6 class="modal-title">Hapus Data Admin Akademik</h6><button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Apakah Anda Yakin Ingin Menghapus <b><?= $key->gelar_depan . " " . $key->nama . ", " . $key->gelar_belakang ?></b> Sebagai Admin Akademik <b><?= $key->prodi ?></b> ?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn ripple btn-danger" type="submit">Hapus</button>
                                                                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">Keluar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php
                                            $no++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-3"></div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>