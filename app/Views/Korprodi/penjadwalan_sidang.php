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
                        <h4 class="card-title mg-b-0">Penjadwalan Sidang</h4>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>
                    <p class="tx-12 tx-gray-500 mb-2">Mengatur jadwal pendaftaran Seminar Proposal dan Sidang Skripsi</p>
                    <div class="row mt-4">
                    </div>
                    <?= session()->getFlashdata('message') . "<br>"; ?>
                    <form action="<?= base_url() ?>add_jadwal_sidang" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="idunit" value="<?= $idunit ?>">
                        <div class="form-group">
                            <label for="exampleInputPeriode">Periode</label>
                            <input type="teks" class="form-control" id="exampleInput" placeholder="Isikan Periode Sidang" name="periode">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputTutup">Dibuka Pada</label>
                            <div class="row row-sm">
                                <div class="input-group col-md-12">
                                    <div class="input-group-text">
                                        <div class="input-group-text">
                                            <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                        </div>
                                    </div>
                                    <input name='open' class="form-control" id="datetimepicker1" type="text" placeholder="<?= date('d F Y H:i:s') ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputTutup">Tutup Pada</label>
                            <div class="row row-sm">
                                <div class="input-group col-md-12">
                                    <div class="input-group-text">
                                        <div class="input-group-text">
                                            <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                        </div>
                                    </div>
                                    <input name='expire' class="form-control" id="datetimepicker2" type="text" placeholder="<?= date('d F Y H:i:s') ?>">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputJenis Sidang">Jenis Sidang</label>
                            <div class="row row-sm">
                                <select class="form-control select2" name="jenis_sidang">
                                    <option selected disabled> Pilih Sidang
                                    </option>
                                    <option value="seminar proposal">
                                        Seminar Proposal
                                    </option>
                                    <option value="sidang skripsi">
                                        Sidang Skripsi
                                    </option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3 mb-0">Tambah</button>
                    </form>
                </div>
                <div class="row mt-3"></div>
            </div>
        </div>
    </div>


    <div class="row mt-3">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mg-b-0">Data Jadwal Sidang</h4>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>
                    <p class="tx-12 tx-gray-500 mb-2">Data jadwal seminar proposal dan sidang skripsi</a></p>
                </div>

                <div class="panel panel-primary tabs-style-2">
                    <div class="tab-menu-heading">
                        <div class="tabs-menu1">
                            <ul class="nav panel-tabs main-nav-line">
                                <li><a href="#sempro" class="nav-link active" data-bs-toggle="tab">Seminar Proposal</a></li>
                                <li><a href="#sidang_skripsi" class="nav-link" data-bs-toggle="tab">Sidang Skripsi</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="panel-body tabs-menu-body main-content-body-right border">
                        <div class="tab-content">
                            <div class="tab-pane active" id="sempro">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped mg-b-0 text-md-nowrap" id="validasitable1">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align: center; vertical-align: middle;"><span>No.</span></th>
                                                        <th style="text-align: center; vertical-align: middle;"><span>Periode Sidang</span></th>
                                                        <th style="text-align: center; vertical-align: middle;"><span>Dibuka Pada</span></th>
                                                        <th style="text-align: center; vertical-align: middle;"><span>Ditutup Pada</span></th>
                                                        <th style="text-align: center; vertical-align: middle;"><span>Jumlah Pendaftar</span></th>
                                                        <th style="text-align: center; vertical-align: middle;"><span>Jenis Sidang</span></th>
                                                        <th style="text-align: center; vertical-align: middle;"><span>Status</span></th>
                                                        <th style="text-align: center; vertical-align: middle;"><span>Aksi</span></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    date_default_timezone_set("Asia/Jakarta");
                                                    $no = 1;
                                                    foreach ($data_jadwal as $key) {

                                                        if ($key->jenis_sidang != 'seminar proposal') {
                                                            continue;
                                                        }
                                                    ?>
                                                        <tr>
                                                            <th scope="row"><?= $no ?></th>
                                                            <td><?= $key->periode ?></td>
                                                            <td><?= $key->open ?></td>
                                                            <td><?= $key->expire ?></td>
                                                            <td>
                                                                <?php
                                                                $jumlah = $db->query("SELECT * FROM tb_pendaftar_sidang WHERE id_jadwal=" . $key->id_jadwal)->getResult();
                                                                echo $jumlah != NULL ? count($jumlah) : 0;
                                                                ?>
                                                            </td>
                                                            <td><?= $key->jenis_sidang == 'seminar proposal' ? 'PROPOSAL' : 'SKRIPSI' ?></td>
                                                            <td>
                                                                <?php
                                                                if (time() < strtotime($key->open)) {
                                                                    echo "<a class='text-secondary'>Belum Dibuka</a>";
                                                                } elseif (time() > strtotime($key->expire)) {
                                                                    echo "<a class='text-danger'>Ditutup</a>";
                                                                } else {
                                                                    echo "<a class='text-success'>Dibuka</a>";
                                                                } ?>
                                                            </td>
                                                            <td>
                                                                <input type="hidden" name="id_bimbingan" value="" />
                                                                <div class="btn-group">
                                                                    <form action="<?= base_url() ?>data_pendaftar" method="POST" enctype="multipart/form-data">
                                                                        <?= csrf_field() ?>
                                                                        <input type="hidden" name="id_jadwal" value="<?= $key->id_jadwal ?>" />
                                                                        <button class="btn btn-primary btn-sm" type='submit'><i class="fa fa-search"></i></button>
                                                                    </form>
                                                                    <!-- <form action="<?= base_url() ?>data_pendaftar" method="POST" enctype="multipart/form-data">
                                                                        <?= csrf_field() ?>
                                                                        <input type="hidden" name="id_jadwal" value="<?= $key->id_jadwal ?>" />
                                                                        <button class="btn btn-primary btn-sm" type='submit'><i class="fa fa-envelope"></i></button>
                                                                    </form> -->
                                                                    <a class="btn btn-warning btn-sm" data-bs-target="#modalupdate<?= $key->id_jadwal ?>" data-bs-toggle="modal" href="#"><i class="las la-pen"></i></a>
                                                                    <a class="btn btn-danger btn-sm" data-bs-target="#modaldel<?= $key->id_jadwal ?>" data-bs-toggle="modal" href="#"><i class="las la-trash"></i></a>
                                                                </div>
                                                            </td>
                                                            <div class="modal" id="modaldel<?= $key->id_jadwal ?>">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content modal-content-demo">
                                                                        <div class="modal-header">
                                                                            <h6 class="modal-title">Hapus Jadwal Sidang</h6><button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                                                                        </div>
                                                                        <form action="<?= base_url() ?>del_jadwal_sidang" method="POST" enctype="multipart/form-data">
                                                                            <?= csrf_field() ?>
                                                                            <input type="hidden" name="id_jadwal" value="<?= $key->id_jadwal ?>" />
                                                                            <div class="modal-body">
                                                                                Apakah anda yakin ingin menghapus jadwal <b><?= $key->periode ?></b> ?
                                                                                <p class="mt-3"></p>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button class="btn ripple btn-danger" type="submit">Hapus</button>
                                                                                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">Keluar</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal" id="modalupdate<?= $key->id_jadwal ?>">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content modal-content-demo">
                                                                        <div class="modal-header">
                                                                            <h6 class="modal-title">Edit Jadwal Sidang</h6><button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                                                                        </div>
                                                                        <form action="<?= base_url() ?>upd_jadwal_sidang" method="POST" enctype="multipart/form-data">
                                                                            <?= csrf_field() ?>
                                                                            <input type="hidden" name="id_jadwal" value="<?= $key->id_jadwal ?>" />
                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="idunit" value="<?= $idunit ?>">
                                                                                <div class="form-group">
                                                                                    <label for="exampleInputPeriode">Periode</label>
                                                                                    <input type="teks" class="form-control" id="exampleInput" value="<?= $key->periode ?>" name="periode">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="exampleInputTutup">Dibuka Pada</label>
                                                                                    <div class="row row-sm">
                                                                                        <div class="input-group col-md-12">
                                                                                            <div class="input-group-text">
                                                                                                <div class="input-group-text">
                                                                                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                            <input name='open' class="form-control" id="datepickeropen<?= $key->id_jadwal ?>" value="<?= $key->open ?>" type="text">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="exampleInputTutup">Tutup Pada</label>
                                                                                    <div class="row row-sm">
                                                                                        <div class="input-group col-md-12">
                                                                                            <div class="input-group-text">
                                                                                                <div class="input-group-text">
                                                                                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                            <input name='expire' class="form-control" id="datepickerexpire<?= $key->id_jadwal ?>" type="text" value="<?= $key->expire ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="exampleInputJenis Sidang">Jenis Sidang</label>
                                                                                    <div class="row row-sm">
                                                                                        <select class="form-control select" name="jenis_sidang">
                                                                                            <option disabled> Pilih Sidang
                                                                                            </option>
                                                                                            <option <?= $key->jenis_sidang == 'seminar proposal' ? 'selected' : '' ?> value="seminar proposal">
                                                                                                Seminar Proposal
                                                                                            </option>
                                                                                            <option <?= $key->jenis_sidang == 'sidang skripsi' ? 'selected' : '' ?> value="sidang skripsi">
                                                                                                Sidang Skripsi
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <script type="text/javascript">
                                                                                $(function() {
                                                                                    $('#datepickeropen<?= $key->id_jadwal ?>').datetimepicker();
                                                                                    $('#datepickerexpire<?= $key->id_jadwal ?>').datetimepicker();
                                                                                });
                                                                            </script>
                                                                            <div class="modal-footer">
                                                                                <button class="btn ripple btn-primary" type="submit">Update</button>
                                                                                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">Keluar</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </tr>
                                                    <?php $no++;
                                                    } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane" id="sidang_skripsi">
                                <div class="row">
                                    <div class="col-xl-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped mg-b-0 text-md-nowrap" id="validasitable2">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align: center; vertical-align: middle;"><span>No.</span></th>
                                                        <th style="text-align: center; vertical-align: middle;"><span>Periode Sidang</span></th>
                                                        <th style="text-align: center; vertical-align: middle;"><span>Dibuka Pada</span></th>
                                                        <th style="text-align: center; vertical-align: middle;"><span>Ditutup Pada</span></th>
                                                        <th style="text-align: center; vertical-align: middle;"><span>Jumlah Pendaftar</span></th>
                                                        <th style="text-align: center; vertical-align: middle;"><span>Jenis Sidang</span></th>
                                                        <th style="text-align: center; vertical-align: middle;"><span>Status</span></th>
                                                        <th style="text-align: center; vertical-align: middle;"><span>Aksi</span></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    date_default_timezone_set("Asia/Jakarta");
                                                    $no = 1;
                                                    foreach ($data_jadwal as $key) {
                                                        if ($key->jenis_sidang == 'seminar proposal') {
                                                            continue;
                                                        }
                                                    ?>
                                                        <tr>
                                                            <th scope="row"><?= $no ?></th>
                                                            <td><?= $key->periode ?></td>
                                                            <td><?= $key->open ?></td>
                                                            <td><?= $key->expire ?></td>
                                                            <td>
                                                                <?php
                                                                $jumlah = $db->query("SELECT * FROM tb_pendaftar_sidang WHERE id_jadwal=" . $key->id_jadwal)->getResult();
                                                                echo $jumlah != NULL ? count($jumlah) : 0;
                                                                ?>
                                                            </td>
                                                            <td><?= $key->jenis_sidang == 'seminar proposal' ? 'PROPOSAL' : 'SKRIPSI' ?></td>
                                                            <td>
                                                                <?php
                                                                if (time() < strtotime($key->open)) {
                                                                    echo "<a class='text-secondary'>Belum Dibuka</a>";
                                                                } elseif (time() > strtotime($key->expire)) {
                                                                    echo "<a class='text-danger'>Ditutup</a>";
                                                                } else {
                                                                    echo "<a class='text-success'>Dibuka</a>";
                                                                } ?>
                                                            </td>
                                                            <td>
                                                                <input type="hidden" name="id_bimbingan" value="" />
                                                                <div class="btn-group">
                                                                    <form action="<?= base_url() ?>data_pendaftar" method="POST" enctype="multipart/form-data">
                                                                        <?= csrf_field() ?>
                                                                        <input type="hidden" name="id_jadwal" value="<?= $key->id_jadwal ?>" />
                                                                        <button class="btn btn-primary btn-sm" type='submit'><i class="fa fa-search"></i></button>
                                                                    </form>
                                                                    <!-- <form action="<?= base_url() ?>data_pendaftar" method="POST" enctype="multipart/form-data">
                                                                        <?= csrf_field() ?>
                                                                        <input type="hidden" name="id_jadwal" value="<?= $key->id_jadwal ?>" />
                                                                        <button class="btn btn-primary btn-sm" type='submit'><i class="fa fa-envelope"></i></button>
                                                                    </form> -->
                                                                    <a class="btn btn-warning btn-sm" data-bs-target="#modalupdate<?= $key->id_jadwal ?>" data-bs-toggle="modal" href="#"><i class="las la-pen"></i></a>
                                                                    <a class="btn btn-danger btn-sm" data-bs-target="#modaldel<?= $key->id_jadwal ?>" data-bs-toggle="modal" href="#"><i class="las la-trash"></i></a>
                                                                </div>
                                                            </td>
                                                            <div class="modal" id="modaldel<?= $key->id_jadwal ?>">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content modal-content-demo">
                                                                        <div class="modal-header">
                                                                            <h6 class="modal-title">Hapus Jadwal Sidang</h6><button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                                                                        </div>
                                                                        <form action="<?= base_url() ?>del_jadwal_sidang" method="POST" enctype="multipart/form-data">
                                                                            <?= csrf_field() ?>
                                                                            <input type="hidden" name="id_jadwal" value="<?= $key->id_jadwal ?>" />
                                                                            <div class="modal-body">
                                                                                Apakah anda yakin ingin menghapus jadwal <b><?= $key->periode ?></b> ?
                                                                                <p class="mt-3"></p>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button class="btn ripple btn-danger" type="submit">Hapus</button>
                                                                                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">Keluar</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal" id="modalupdate<?= $key->id_jadwal ?>">
                                                                <div class="modal-dialog" role="document">
                                                                    <div class="modal-content modal-content-demo">
                                                                        <div class="modal-header">
                                                                            <h6 class="modal-title">Edit Jadwal Sidang</h6><button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                                                                        </div>
                                                                        <form action="<?= base_url() ?>upd_jadwal_sidang" method="POST" enctype="multipart/form-data">
                                                                            <?= csrf_field() ?>
                                                                            <input type="hidden" name="id_jadwal" value="<?= $key->id_jadwal ?>" />
                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="idunit" value="<?= $idunit ?>">
                                                                                <div class="form-group">
                                                                                    <label for="exampleInputPeriode">Periode</label>
                                                                                    <input type="teks" class="form-control" id="exampleInput" value="<?= $key->periode ?>" name="periode">
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="exampleInputTutup">Dibuka Pada</label>
                                                                                    <div class="row row-sm">
                                                                                        <div class="input-group col-md-12">
                                                                                            <div class="input-group-text">
                                                                                                <div class="input-group-text">
                                                                                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                            <input name='open' class="form-control" id="datepickeropen<?= $key->id_jadwal ?>" value="<?= $key->open ?>" type="text">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="exampleInputTutup">Tutup Pada</label>
                                                                                    <div class="row row-sm">
                                                                                        <div class="input-group col-md-12">
                                                                                            <div class="input-group-text">
                                                                                                <div class="input-group-text">
                                                                                                    <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                                                                </div>
                                                                                            </div>
                                                                                            <input name='expire' class="form-control" id="datepickerexpire<?= $key->id_jadwal ?>" type="text" value="<?= $key->expire ?>">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label for="exampleInputJenis Sidang">Jenis Sidang</label>
                                                                                    <div class="row row-sm">
                                                                                        <select class="form-control select" name="jenis_sidang">
                                                                                            <option disabled> Pilih Sidang
                                                                                            </option>
                                                                                            <option <?= $key->jenis_sidang == 'seminar proposal' ? 'selected' : '' ?> value="seminar proposal">
                                                                                                Seminar Proposal
                                                                                            </option>
                                                                                            <option <?= $key->jenis_sidang == 'sidang skripsi' ? 'selected' : '' ?> value="sidang skripsi">
                                                                                                Sidang Skripsi
                                                                                            </option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <script type="text/javascript">
                                                                                $(function() {
                                                                                    $('#datepickeropen<?= $key->id_jadwal ?>').datetimepicker();
                                                                                    $('#datepickerexpire<?= $key->id_jadwal ?>').datetimepicker();
                                                                                });
                                                                            </script>
                                                                            <div class="modal-footer">
                                                                                <button class="btn ripple btn-primary" type="submit">Update</button>
                                                                                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">Keluar</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
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
                <!-- <div class="row mt-3">
                    <div class="col">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped mg-b-0 text-md-nowrap" id="validasitable1">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center; vertical-align: middle;"><span>No.</span></th>
                                            <th style="text-align: center; vertical-align: middle;"><span>Periode Sidang</span></th>
                                            <th style="text-align: center; vertical-align: middle;"><span>Dibuka Pada</span></th>
                                            <th style="text-align: center; vertical-align: middle;"><span>Ditutup Pada</span></th>
                                            <th style="text-align: center; vertical-align: middle;"><span>Jumlah Pendaftar</span></th>
                                            <th style="text-align: center; vertical-align: middle;"><span>Jenis Sidang</span></th>
                                            <th style="text-align: center; vertical-align: middle;"><span>Status</span></th>
                                            <th style="text-align: center; vertical-align: middle;"><span>Aksi</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        date_default_timezone_set("Asia/Jakarta");
                                        $no = 1;
                                        foreach ($data_jadwal as $key) {
                                        ?>
                                            <tr>
                                                <th scope="row"><?= $no ?></th>
                                                <td><?= $key->periode ?></td>
                                                <td><?= $key->open ?></td>
                                                <td><?= $key->expire ?></td>
                                                <td>
                                                    <?php
                                                    $jumlah = $db->query("SELECT * FROM tb_pendaftar_sidang WHERE id_jadwal=" . $key->id_jadwal)->getResult();
                                                    echo $jumlah != NULL ? count($jumlah) : 0;
                                                    ?>
                                                </td>
                                                <td><?= $key->jenis_sidang == 'seminar proposal' ? 'PROPOSAL' : 'SKRIPSI' ?></td>
                                                <td>
                                                    <?php
                                                    if (time() < strtotime($key->open)) {
                                                        echo "<a class='text-secondary'>Belum Dibuka</a>";
                                                    } elseif (time() > strtotime($key->expire)) {
                                                        echo "<a class='text-danger'>Ditutup</a>";
                                                    } else {
                                                        echo "<a class='text-success'>Dibuka</a>";
                                                    } ?>
                                                </td>
                                                <td>
                                                    <input type="hidden" name="id_bimbingan" value="" />
                                                    <div class="btn-group">
                                                        <form action="<?= base_url() ?>data_pendaftar" method="POST" enctype="multipart/form-data">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="id_jadwal" value="<?= $key->id_jadwal ?>" />
                                                            <button class="btn btn-primary btn-sm" type='submit'><i class="fa fa-search"></i></button>
                                                        </form>
                                                        <form action="<?= base_url() ?>data_pendaftar" method="POST" enctype="multipart/form-data">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" name="id_jadwal" value="<?= $key->id_jadwal ?>" />
                                                            <button class="btn btn-primary btn-sm" type='submit'><i class="fa fa-envelope"></i></button>
                                                        </form>
                                                        <a class="btn btn-warning btn-sm" data-bs-target="#modalupdate<?= $key->id_jadwal ?>" data-bs-toggle="modal" href="#"><i class="las la-pen"></i></a>
                                                        <a class="btn btn-danger btn-sm" data-bs-target="#modaldel<?= $key->id_jadwal ?>" data-bs-toggle="modal" href="#"><i class="las la-trash"></i></a>
                                                    </div>
                                                </td>
                                                <div class="modal" id="modaldel<?= $key->id_jadwal ?>">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content modal-content-demo">
                                                            <div class="modal-header">
                                                                <h6 class="modal-title">Hapus Jadwal Sidang</h6><button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                                                            </div>
                                                            <form action="<?= base_url() ?>del_jadwal_sidang" method="POST" enctype="multipart/form-data">
                                                                <?= csrf_field() ?>
                                                                <input type="hidden" name="id_jadwal" value="<?= $key->id_jadwal ?>" />
                                                                <div class="modal-body">
                                                                    Apakah anda yakin ingin menghapus jadwal <b><?= $key->periode ?></b> ?
                                                                    <p class="mt-3"></p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button class="btn ripple btn-danger" type="submit">Hapus</button>
                                                                    <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">Keluar</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal" id="modalupdate<?= $key->id_jadwal ?>">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content modal-content-demo">
                                                            <div class="modal-header">
                                                                <h6 class="modal-title">Edit Jadwal Sidang</h6><button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                                                            </div>
                                                            <form action="<?= base_url() ?>upd_jadwal_sidang" method="POST" enctype="multipart/form-data">
                                                                <?= csrf_field() ?>
                                                                <input type="hidden" name="id_jadwal" value="<?= $key->id_jadwal ?>" />
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="idunit" value="<?= $idunit ?>">
                                                                    <div class="form-group">
                                                                        <label for="exampleInputPeriode">Periode</label>
                                                                        <input type="teks" class="form-control" id="exampleInput" value="<?= $key->periode ?>" name="periode">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="exampleInputTutup">Dibuka Pada</label>
                                                                        <div class="row row-sm">
                                                                            <div class="input-group col-md-12">
                                                                                <div class="input-group-text">
                                                                                    <div class="input-group-text">
                                                                                        <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                                                    </div>
                                                                                </div>
                                                                                <input name='open' class="form-control" id="datepickeropen<?= $key->id_jadwal ?>" value="<?= $key->open ?>" type="text">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="exampleInputTutup">Tutup Pada</label>
                                                                        <div class="row row-sm">
                                                                            <div class="input-group col-md-12">
                                                                                <div class="input-group-text">
                                                                                    <div class="input-group-text">
                                                                                        <i class="typcn typcn-calendar-outline tx-24 lh--9 op-6"></i>
                                                                                    </div>
                                                                                </div>
                                                                                <input name='expire' class="form-control" id="datepickerexpire<?= $key->id_jadwal ?>" type="text" value="<?= $key->expire ?>">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="exampleInputJenis Sidang">Jenis Sidang</label>
                                                                        <div class="row row-sm">
                                                                            <select class="form-control select" name="jenis_sidang">
                                                                                <option disabled> Pilih Sidang
                                                                                </option>
                                                                                <option <?= $key->jenis_sidang == 'seminar proposal' ? 'selected' : '' ?> value="seminar proposal">
                                                                                    Seminar Proposal
                                                                                </option>
                                                                                <option <?= $key->jenis_sidang == 'sidang skripsi' ? 'selected' : '' ?> value="sidang skripsi">
                                                                                    Sidang Skripsi
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <script type="text/javascript">
                                                                    $(function() {
                                                                        $('#datepickeropen<?= $key->id_jadwal ?>').datetimepicker();
                                                                        $('#datepickerexpire<?= $key->id_jadwal ?>').datetimepicker();
                                                                    });
                                                                </script>
                                                                <div class="modal-footer">
                                                                    <button class="btn ripple btn-primary" type="submit">Update</button>
                                                                    <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">Keluar</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </tr>
                                        <?php $no++;
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>



</div>

<?= $this->endSection(); ?>