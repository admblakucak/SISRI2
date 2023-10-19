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
                        <h4 class="card-title mg-b-0">Data Pendaftar Sidang</h4>
                        <div>
                            <i class="mdi mdi-dots-horizontal text-gray"></i>
                            <!-- <a class="btn btn-primary btn-sm" href="<?= base_url() ?>cetak_pendaftar/<?= $id_jadwal ?>/semua"><i class="fa fa-print"></i> Semua</a>
                            <a class="btn btn-success btn-sm" href="<?= base_url() ?>cetak_pendaftar/<?= $id_jadwal ?>/terjadwal"><i class="fa fa-print"></i> Terjadwal</a> -->
                        </div>
                    </div>
                    <p class="tx-12 tx-gray-500 mb-2">Data pendaftar seminar proposal dan sidang skripsi</a></p>
                    <hr>
                    <?= session()->getFlashdata('message') ?>
                    <form action="<?= base_url() ?>direct_cetak_pendaftar" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="row row-sm">
                            <input class="form-control" name="idunit" type="hidden">
                            <input class="form-control" name="id_jadwal" type="hidden" value="<?= $id_jadwal ?>">
                            <div class="col-3">
                                <div class="form-group">
                                    <label class="form-label">Tanggal Mulai Waktu Sidang</label>
                                    <input class="form-control" name="start" type="date">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label class="form-label">Tanggal Akhir Waktu Sidang</label>
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
                            </div>
                        </div>
                    </form>
                    <hr>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped mg-b-0 text-md-nowrap" id="validasitable1">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center; vertical-align: middle;"><span>No.</span></th>
                                            <th style="text-align: center; vertical-align: middle;"><span>Mahasiswa</span></th>
                                            <th style="text-align: center; vertical-align: middle;"><span>Judul</span></th>
                                            <th style="text-align: center; vertical-align: middle;"><span>Pembimbing 1</span></th>
                                            <th style="text-align: center; vertical-align: middle;"><span>Pembimbing 2</span></th>
                                            <th style="text-align: center; vertical-align: middle;"><span>Mendaftar Pada</span></th>
                                            <th style="text-align: center; vertical-align: middle;"><span>Penguji 1</span></th>
                                            <th style="text-align: center; vertical-align: middle;"><span>Penguji 2</span></th>
                                            <th style="text-align: center; vertical-align: middle;"><span>Penguji 3</span></th>
                                            <th style="text-align: center; vertical-align: middle;"><span>Waktu Sidang</span></th>
                                            <th style="text-align: center; vertical-align: middle;"><span>Ruang Sidang</span></th>
                                            <?php
                                            if ($data_jadwal[0]->jenis_sidang == 'sidang skripsi') {
                                            ?>
                                                <th style="text-align: center; vertical-align: middle;"><span>Selisih Sidang Seminar ke Sidang Skripsi</span></th>
                                            <?php } ?>
                                            <th style="text-align: center; vertical-align: middle;"><span>Aksi</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        date_default_timezone_set("Asia/Jakarta");
                                        $no = 1;
                                        foreach ($data_pendaftar as $key) {
                                            $mhs = $db->query("SELECT * FROM tb_mahasiswa WHERE nim='$key->nim'")->getResult();
                                            $judul = $db->query("SELECT * FROM tb_pengajuan_topik WHERE nim='$key->nim'")->getResult();
                                            $pem1 = $db->query("SELECT * FROM tb_pengajuan_pembimbing a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$key->nim' AND a.sebagai='1' AND a.status_pengajuan='diterima'")->getResult();
                                            $pem2 = $db->query("SELECT * FROM tb_pengajuan_pembimbing a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$key->nim' AND a.sebagai='2' AND a.status_pengajuan='diterima'")->getResult();
                                            $penguji1 = $db->query("SELECT * FROM tb_penguji a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$key->nim' AND a.sebagai='1' AND a.id_pendaftar='$key->id_pendaftar'")->getResult();
                                            $penguji2 = $db->query("SELECT * FROM tb_penguji a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$key->nim' AND a.sebagai='2' AND a.id_pendaftar='$key->id_pendaftar'")->getResult();
                                            $penguji3 = $db->query("SELECT * FROM tb_penguji a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$key->nim' AND a.sebagai='3' AND a.id_pendaftar='$key->id_pendaftar'")->getResult();
                                            $penguji_1 = '';
                                            $penguji_2 = '';
                                            $penguji_3 = '';
                                            if ($data_jadwal[0]->jenis_sidang == 'sidang skripsi') {
                                                $penguji1 = $db->query("SELECT * FROM tb_penguji a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$key->nim' AND a.sebagai='1' AND a.`status`='aktif'")->getResult();
                                                $penguji2 = $db->query("SELECT * FROM tb_penguji a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$key->nim' AND a.sebagai='2' AND a.`status`='aktif'")->getResult();
                                                $penguji3 = $db->query("SELECT * FROM tb_penguji a left join tb_dosen b on a.nip=b.nip WHERE a.nim='$key->nim' AND a.sebagai='3' AND a.`status`='aktif'")->getResult();
                                            }
                                            if ($penguji1 != NULL) {
                                                $penguji_1 = $penguji1[0]->nip;
                                            }
                                            if ($penguji2 != NULL) {
                                                $penguji_2 = $penguji2[0]->nip;
                                            }
                                            if ($penguji3 != NULL) {
                                                $penguji_3 = $penguji3[0]->nip;
                                            }
                                        ?>
                                            <tr>
                                                <th scope="row"><?= $no ?></th>
                                                <td><?= $key->nim . ' - ' . $mhs[0]->nama ?></td>
                                                <td><?= $judul[0]->judul_topik ?></td>
                                                <td><?= $pem1[0]->gelardepan . ' ' . $pem1[0]->nama . ', ' . $pem1[0]->gelarbelakang ?></td>
                                                <td><?= $pem2[0]->gelardepan . ' ' . $pem2[0]->nama . ', ' . $pem2[0]->gelarbelakang ?></td>
                                                <td><?= $key->create_at ?></td>
                                                <td><?= $penguji1 != NULL ? $penguji1[0]->gelardepan . ' ' . $penguji1[0]->nama . ', ' . $penguji1[0]->gelarbelakang : '' ?></td>
                                                <td><?= $penguji2 != NULL ? $penguji2[0]->gelardepan . ' ' . $penguji2[0]->nama . ', ' . $penguji2[0]->gelarbelakang : '' ?></td>
                                                <td><?= $penguji3 != NULL ? $penguji3[0]->gelardepan . ' ' . $penguji3[0]->nama . ', ' . $penguji3[0]->gelarbelakang : '' ?></td>
                                                <td><?= $key->waktu_sidang ?></td>
                                                <td><?= $key->ruang_sidang ?></td>
                                                <?php
                                                if ($data_jadwal[0]->jenis_sidang == 'sidang skripsi') {
                                                    $d_s = $db->query("SELECT * FROM tb_pendaftar_sidang a LEFT JOIN tb_jadwal_sidang b ON a.`id_jadwal`=b.`id_jadwal` WHERE a.`nim`='" . $key->nim . "' AND b.`jenis_sidang`='seminar proposal' ORDER BY a.`create_at` DESC")->getResult();
                                                    if (isset($d_s[0])) {
                                                        if ($d_s[0]->waktu_sidang == "0000-00-00 00:00:00") {
                                                            $d_s = date_create(date('Y-m-d', strtotime($d_s[0]->create_at)));
                                                        } else {
                                                            $d_s = date_create(date('Y-m-d', strtotime($d_s[0]->waktu_sidang)));
                                                        }
                                                        $d_now = date_create(date('Y-m-d'));
                                                        $selisih = date_diff($d_s, $d_now);
                                                        echo "<td>" . $selisih->y . " tahun, " . $selisih->m . " bulan, " . $selisih->d . " hari</td>";
                                                    } else {
                                                        echo "<td>- tahun, - bulan, - hari</td>";
                                                    }
                                                }
                                                ?>
                                                <td>
                                                    <a class="btn btn-warning btn-sm" data-bs-target="#modalupdate<?= $key->id_pendaftar ?>" data-bs-toggle="modal" href="#"><i class="las la-pen">Setting</i></a>
                                                </td>
                                                <div class="modal" id="modalupdate<?= $key->id_pendaftar ?>">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content modal-content-demo">
                                                            <div class="modal-header">
                                                                <h6 class="modal-title">Edit Jadwal Sidang</h6><button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                                                            </div>
                                                            <form action="<?= base_url() ?>data_pendaftar" method="POST" enctype="multipart/form-data">
                                                                <?= csrf_field() ?>
                                                                <input type="hidden" name="id_pendaftar" value="<?= $key->id_pendaftar ?>" />
                                                                <input type="hidden" name="id_jadwal" value="<?= $id_jadwal ?>" />
                                                                <input type="hidden" name="nim" value="<?= $key->nim ?>" />
                                                                <input type="hidden" name="jenis_sidang" value="<?= $data_jadwal[0]->jenis_sidang ?>" />
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
                                                                                <input name='waktu_sidang' class="form-control" id="datepickeropen<?= $key->id_pendaftar ?>" value="<?= $key->waktu_sidang ?>" type="text">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label for="exampleInputPeriode">Ruang Sidang</label>
                                                                        <input type="teks" class="form-control" id="exampleInput" value="<?= $key->ruang_sidang ?>" name="ruang_sidang">
                                                                    </div>
                                                                    <?php if ($data_jadwal[0]->jenis_sidang == 'seminar proposal') { ?>
                                                                        <div class="form-group">
                                                                            <label for="exampleInputJenis Sidang">Penguji 1</label>
                                                                            <div class="row row-sm">
                                                                                <select class="form-control select" name="nip_p1">
                                                                                    <?php
                                                                                    $cek = $db->query("SELECT * FROM tb_penguji where sebagai='1' and id_pendaftar='$key->id_pendaftar'")->getResult();
                                                                                    ?>
                                                                                    <option <?= $cek == NULL ? 'selected' : '' ?> disabled> Pilih Penguji 1
                                                                                    </option>
                                                                                    <?php
                                                                                    foreach ($data_dosen_f as $key1) {
                                                                                        if ($key1->nip != $pem1[0]->nip && $key1->nip != $pem2[0]->nip) {
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
                                                                                <select class="form-control select" name="nip_p2">
                                                                                    <?php
                                                                                    $cek = $db->query("SELECT * FROM tb_penguji where sebagai='2' and id_pendaftar='$key->id_pendaftar'")->getResult();
                                                                                    ?>
                                                                                    <option <?= $cek == NULL ? 'selected' : '' ?> disabled> Pilih Penguji 2
                                                                                    </option>
                                                                                    <?php
                                                                                    foreach ($data_dosen_f as $key1) {
                                                                                        if ($key1->nip != $pem1[0]->nip && $key1->nip != $pem2[0]->nip) {
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
                                                                                <select class="form-control select" name="nip_p3">
                                                                                    <?php
                                                                                    $cek = $db->query("SELECT * FROM tb_penguji where sebagai='3' and id_pendaftar='$key->id_pendaftar'")->getResult();
                                                                                    ?>
                                                                                    <option <?= $cek == NULL ? 'selected' : '' ?> disabled> Pilih Penguji 3
                                                                                    </option>
                                                                                    <?php
                                                                                    foreach ($data_dosen_f as $key1) {
                                                                                        if ($key1->nip != $pem1[0]->nip && $key1->nip != $pem2[0]->nip) {
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
                                                                        $('#datepickeropen<?= $key->id_pendaftar ?>').datetimepicker();
                                                                        $('#datepickerexpire<?= $key->id_pendaftar ?>').datetimepicker();
                                                                    });
                                                                </script>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name='update' value="update">
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
</div>
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
<?= $this->endSection(); ?>