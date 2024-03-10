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
                        <h4 class="card-title mg-b-0">Persetujuan Daftar Seminar Proposal & Sidang Skripsi</h4>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>
                    <p class="tx-12 tx-gray-500 mb-2">Permintaan izin mahasiswa untuk mendaftar seminar proposal dan sidang skripsi</a></p>
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
                                                    <li><a href="#sempro" class="nav-link active" data-bs-toggle="tab">Seminar Proposal</a></li>
                                                    <li><a href="#semproValidasi" class="nav-link" data-bs-toggle="tab">Seminar Tervalidasi</a></li>
                                                    <li><a href="#sidang" class="nav-link" data-bs-toggle="tab">Sidang Skripsi</a></li>
                                                    <li><a href="#sidangValidasi" class="nav-link" data-bs-toggle="tab">Sidang Tervalidasi</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="panel-body tabs-menu-body main-content-body-right border">
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="sempro">
                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-striped mg-b-0 text-md-nowrap" id="validasitable2">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>&nbsp;</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Nama</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Judul</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Pembimbing 1</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Pembimbing 2</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Koordinator Prodi</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Aksi</span></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id='show_data2'>
                                                                        <?php
                                                                        $no = 1;
                                                                        foreach ($data_mhs_bimbingan as $key) {
                                                                            if ($key['jenis_sidang'] == 'seminar proposal') {
                                                                                $pem1 = $db->query("SELECT * FROM tb_pengajuan_pembimbing a left join tb_dosen b on a.nip=b.nip WHERE a.nim='" . $key['nim'] . "' AND a.sebagai='1' AND a.status_pengajuan='diterima'")->getResult();
                                                                                $pem2 = $db->query("SELECT * FROM tb_pengajuan_pembimbing a left join tb_dosen b on a.nip=b.nip WHERE a.nim='" . $key['nim'] . "' AND a.sebagai='2' AND a.status_pengajuan='diterima'")->getResult();
                                                                                $judul = $db->query("SELECT * FROM tb_pengajuan_topik WHERE nim='" . $key['nim'] . "'")->getResult();
                                                                                $sts_pem1 = $db->query("SELECT * FROM tb_perizinan_sidang WHERE nim='" . $key['nim'] . "' AND jenis_sidang='seminar proposal' AND izin_sebagai='pembimbing 1'")->getResult();
                                                                                $sts_pem2 = $db->query("SELECT * FROM tb_perizinan_sidang WHERE nim='" . $key['nim'] . "' AND jenis_sidang='seminar proposal' AND izin_sebagai='pembimbing 2'")->getResult();
                                                                                $sts_koor = $db->query("SELECT * FROM tb_perizinan_sidang WHERE nim='" . $key['nim'] . "' AND jenis_sidang='seminar proposal' AND izin_sebagai='koordinator'")->getResult();

                                                                                if ($sts_koor[0]->status == 'disetujui') {
                                                                                    continue;
                                                                                }

                                                                        ?>
                                                                                <tr>
                                                                                    <td>
                                                                                        <img alt="avatar" class="rounded-circle avatar-md me-2" src="<?= base_url() ?>/image/<?= $key['image'] ?>">
                                                                                    </td>
                                                                                    <td>
                                                                                        <?= $key['nim'] . ' - ' . $key['nama_mhs']; ?>
                                                                                    </td>
                                                                                    <td scope="row"><?= $judul[0]->judul_topik ?></td>
                                                                                    <td scope="row">
                                                                                        <?= $pem1 != NULL ? $pem1[0]->gelardepan . ' ' . $pem1[0]->nama . ', ' . $pem1[0]->gelarbelakang : '' ?>
                                                                                        <?php
                                                                                        if ($sts_pem1 != NULL) {
                                                                                            if ($sts_pem1[0]->status == 'disetujui') {
                                                                                                echo "<span class='text-success ms-3'>Disetujui</span>";
                                                                                            } elseif ($sts_pem1[0]->status == 'ditolak') {
                                                                                                echo "<span class='text-danger ms-3'>Ditolak</span>";
                                                                                            } else {
                                                                                                echo "<span class='text-warning ms-3'>Menunggu Disetuji</span>";
                                                                                            }
                                                                                        } else {
                                                                                            echo "<span class='text-danger ms-3'>Belum Melakukan Perizinan</span>";
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td scope="row">
                                                                                        <?= $pem2 != NULL ? $pem2[0]->gelardepan . ' ' . $pem2[0]->nama . ', ' . $pem2[0]->gelarbelakang : '' ?>
                                                                                        <?php
                                                                                        if ($sts_pem2 != NULL) {
                                                                                            if ($sts_pem2[0]->status == 'disetujui') {
                                                                                                echo "<span class='text-success ms-3'>Disetujui</span>";
                                                                                            } elseif ($sts_pem2[0]->status == 'ditolak') {
                                                                                                echo "<span class='text-danger ms-3'>Ditolak</span>";
                                                                                            } else {
                                                                                                echo "<span class='text-warning ms-3'>Menunggu Disetuji</span>";
                                                                                            }
                                                                                        } else {
                                                                                            echo "<span class='text-danger ms-3'>Belum Melakukan Perizinan</span>";
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td scope="row">
                                                                                        <?php
                                                                                        if ($sts_koor != NULL) {
                                                                                            if ($sts_koor[0]->status == 'disetujui') {
                                                                                                echo "<span class='text-success ms-3'>Disetujui</span>";
                                                                                            } elseif ($sts_koor[0]->status == 'ditolak') {
                                                                                                echo "<span class='text-danger ms-3'>Ditolak</span>";
                                                                                            } else {
                                                                                                echo "<span class='text-warning ms-3'>Menunggu Disetuji</span>";
                                                                                            }
                                                                                        } else {
                                                                                            echo "<span class='text-danger ms-3'>Belum Melakukan Perizinan</span>";
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td style="text-align: center; vertical-align: middle;">
                                                                                        <?php
                                                                                        $cek = $db->query("SELECT * FROM tb_perizinan_sidang WHERE nim='" . $key['nim'] . "' AND nip='" . session()->get('ses_id') . "' AND jenis_sidang='seminar proposal'")->getResult();
                                                                                        if ($cek != NULL) {
                                                                                        ?>
                                                                                            <div class="btn-group">
                                                                                                <form action="<?= base_url() ?>proses_validasi_daftar_seminar_koor" method="POST" enctype="multipart/form-data">
                                                                                                    <?= csrf_field() ?>
                                                                                                    <input type="hidden" name="id_perizinan_sidang" value="<?= $key['id_perizinan_sidang'] ?>">
                                                                                                    <input type="hidden" name="nim" value="<?= $key['nim'] ?>">
                                                                                                    <input type="hidden" name="status" value="disetujui">
                                                                                                    <input type="hidden" name="jenis_sidang" value="seminar proposal">
                                                                                                    <button class="btn btn-success btn-sm" type="submit"><i class="las la-check"></i></button>
                                                                                                </form>
                                                                                                <form action="<?= base_url() ?>proses_validasi_daftar_seminar_koor" method="POST" enctype="multipart/form-data">
                                                                                                    <?= csrf_field() ?>
                                                                                                    <input type="hidden" name="id_perizinan_sidang" value="<?= $key['id_perizinan_sidang'] ?>">
                                                                                                    <input type="hidden" name="nim" value="<?= $key['nim'] ?>">
                                                                                                    <input type="hidden" name="status" value="menunggu">
                                                                                                    <input type="hidden" name="jenis_sidang" value="seminar proposal">
                                                                                                    <button class="btn btn-warning btn-sm" type="submit"><i class="las la-hourglass-half"></i></i></button>
                                                                                                </form>
                                                                                                <form action="<?= base_url() ?>proses_validasi_daftar_seminar_koor" method="POST" enctype="multipart/form-data">
                                                                                                    <?= csrf_field() ?>
                                                                                                    <input type="hidden" name="id_perizinan_sidang" value="<?= $key['id_perizinan_sidang'] ?>">
                                                                                                    <input type="hidden" name="nim" value="<?= $key['nim'] ?>">
                                                                                                    <input type="hidden" name="status" value="ditolak">
                                                                                                    <input type="hidden" name="jenis_sidang" value="seminar proposal">
                                                                                                    <button class="btn btn-danger btn-sm" type="submit"><i class="las la-times"></i></button>
                                                                                                </form>
                                                                                            </div>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                </tr>
                                                                        <?php $no++;
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="semproValidasi">
                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-striped mg-b-0 text-md-nowrap" id="validasitable3">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>&nbsp;</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Nama</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Judul</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Pembimbing 1</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Pembimbing 2</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Koordinator Prodi</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Aksi</span></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id='show_data2'>
                                                                        <?php
                                                                        $no = 1;
                                                                        foreach ($data_mhs_bimbingan as $key) {
                                                                            if ($key['jenis_sidang'] == 'seminar proposal') {
                                                                                $pem1 = $db->query("SELECT * FROM tb_pengajuan_pembimbing a left join tb_dosen b on a.nip=b.nip WHERE a.nim='" . $key['nim'] . "' AND a.sebagai='1' AND a.status_pengajuan='diterima'")->getResult();
                                                                                $pem2 = $db->query("SELECT * FROM tb_pengajuan_pembimbing a left join tb_dosen b on a.nip=b.nip WHERE a.nim='" . $key['nim'] . "' AND a.sebagai='2' AND a.status_pengajuan='diterima'")->getResult();
                                                                                $judul = $db->query("SELECT * FROM tb_pengajuan_topik WHERE nim='" . $key['nim'] . "'")->getResult();
                                                                                $sts_pem1 = $db->query("SELECT * FROM tb_perizinan_sidang WHERE nim='" . $key['nim'] . "' AND jenis_sidang='seminar proposal' AND izin_sebagai='pembimbing 1'")->getResult();
                                                                                $sts_pem2 = $db->query("SELECT * FROM tb_perizinan_sidang WHERE nim='" . $key['nim'] . "' AND jenis_sidang='seminar proposal' AND izin_sebagai='pembimbing 2'")->getResult();
                                                                                $sts_koor = $db->query("SELECT * FROM tb_perizinan_sidang WHERE nim='" . $key['nim'] . "' AND jenis_sidang='seminar proposal' AND izin_sebagai='koordinator'")->getResult();
                                                                                if ($sts_koor[0]->status != 'disetujui') {
                                                                                    continue;
                                                                                }
                                                                        ?>
                                                                                <tr>
                                                                                    <td>
                                                                                        <img alt="avatar" class="rounded-circle avatar-md me-2" src="<?= base_url() ?>/image/<?= $key['image'] ?>">
                                                                                    </td>
                                                                                    <td>
                                                                                        <?= $key['nim'] . ' - ' . $key['nama_mhs']; ?>
                                                                                    </td>
                                                                                    <td scope="row"><?= $judul[0]->judul_topik ?></td>
                                                                                    <td scope="row">
                                                                                        <?= $pem1 != NULL ? $pem1[0]->gelardepan . ' ' . $pem1[0]->nama . ', ' . $pem1[0]->gelarbelakang : '' ?>
                                                                                        <?php
                                                                                        if ($sts_pem1 != NULL) {
                                                                                            if ($sts_pem1[0]->status == 'disetujui') {
                                                                                                echo "<span class='text-success ms-3'>Disetujui</span>";
                                                                                            } elseif ($sts_pem1[0]->status == 'ditolak') {
                                                                                                echo "<span class='text-danger ms-3'>Ditolak</span>";
                                                                                            } else {
                                                                                                echo "<span class='text-warning ms-3'>Menunggu Disetuji</span>";
                                                                                            }
                                                                                        } else {
                                                                                            echo "<span class='text-danger ms-3'>Belum Melakukan Perizinan</span>";
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td scope="row">
                                                                                        <?= $pem2 != NULL ? $pem2[0]->gelardepan . ' ' . $pem2[0]->nama . ', ' . $pem2[0]->gelarbelakang : '' ?>
                                                                                        <?php
                                                                                        if ($sts_pem2 != NULL) {
                                                                                            if ($sts_pem2[0]->status == 'disetujui') {
                                                                                                echo "<span class='text-success ms-3'>Disetujui</span>";
                                                                                            } elseif ($sts_pem2[0]->status == 'ditolak') {
                                                                                                echo "<span class='text-danger ms-3'>Ditolak</span>";
                                                                                            } else {
                                                                                                echo "<span class='text-warning ms-3'>Menunggu Disetuji</span>";
                                                                                            }
                                                                                        } else {
                                                                                            echo "<span class='text-danger ms-3'>Belum Melakukan Perizinan</span>";
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td scope="row">
                                                                                        <?php
                                                                                        if ($sts_koor != NULL) {
                                                                                            if ($sts_koor[0]->status == 'disetujui') {
                                                                                                echo "<span class='text-success ms-3'>Disetujui</span>";
                                                                                            } elseif ($sts_koor[0]->status == 'ditolak') {
                                                                                                echo "<span class='text-danger ms-3'>Ditolak</span>";
                                                                                            } else {
                                                                                                echo "<span class='text-warning ms-3'>Menunggu Disetuji</span>";
                                                                                            }
                                                                                        } else {
                                                                                            echo "<span class='text-danger ms-3'>Belum Melakukan Perizinan</span>";
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td style="text-align: center; vertical-align: middle;">
                                                                                        <?php
                                                                                        $cek = $db->query("SELECT * FROM tb_perizinan_sidang WHERE nim='" . $key['nim'] . "' AND nip='" . session()->get('ses_id') . "' AND jenis_sidang='seminar proposal'")->getResult();
                                                                                        if ($cek != NULL) {
                                                                                        ?>
                                                                                            <div class="btn-group">
                                                                                                <form action="<?= base_url() ?>proses_validasi_daftar_seminar_koor" method="POST" enctype="multipart/form-data">
                                                                                                    <?= csrf_field() ?>
                                                                                                    <input type="hidden" name="id_perizinan_sidang" value="<?= $key['id_perizinan_sidang'] ?>">
                                                                                                    <input type="hidden" name="nim" value="<?= $key['nim'] ?>">
                                                                                                    <input type="hidden" name="status" value="disetujui">
                                                                                                    <input type="hidden" name="jenis_sidang" value="seminar proposal">
                                                                                                    <button class="btn btn-success btn-sm" type="submit"><i class="las la-check"></i></button>
                                                                                                </form>
                                                                                                <form action="<?= base_url() ?>proses_validasi_daftar_seminar_koor" method="POST" enctype="multipart/form-data">
                                                                                                    <?= csrf_field() ?>
                                                                                                    <input type="hidden" name="id_perizinan_sidang" value="<?= $key['id_perizinan_sidang'] ?>">
                                                                                                    <input type="hidden" name="nim" value="<?= $key['nim'] ?>">
                                                                                                    <input type="hidden" name="status" value="menunggu">
                                                                                                    <input type="hidden" name="jenis_sidang" value="seminar proposal">
                                                                                                    <button class="btn btn-warning btn-sm" type="submit"><i class="las la-hourglass-half"></i></i></button>
                                                                                                </form>
                                                                                                <form action="<?= base_url() ?>proses_validasi_daftar_seminar_koor" method="POST" enctype="multipart/form-data">
                                                                                                    <?= csrf_field() ?>
                                                                                                    <input type="hidden" name="id_perizinan_sidang" value="<?= $key['id_perizinan_sidang'] ?>">
                                                                                                    <input type="hidden" name="nim" value="<?= $key['nim'] ?>">
                                                                                                    <input type="hidden" name="status" value="ditolak">
                                                                                                    <input type="hidden" name="jenis_sidang" value="seminar proposal">
                                                                                                    <button class="btn btn-danger btn-sm" type="submit"><i class="las la-times"></i></button>
                                                                                                </form>
                                                                                            </div>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                </tr>
                                                                        <?php $no++;
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="sidang">
                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-striped mg-b-0 text-md-nowrap" id="validasitable4">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>&nbsp;</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Nama</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Judul</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Pembimbing 1</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Pembimbing 2</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Koordinator Prodi</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Jarak Seminar ke Sidang</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Aksi</span></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id='show_data2'>
                                                                        <?php
                                                                        $no = 1;
                                                                        foreach ($data_mhs_bimbingan as $key2) {
                                                                            if ($key2['jenis_sidang'] == 'skripsi') {
                                                                                $pem1 = $db->query("SELECT * FROM tb_pengajuan_pembimbing a left join tb_dosen b on a.nip=b.nip WHERE a.nim='" . $key2['nim'] . "' AND a.sebagai='1' AND a.status_pengajuan='diterima'")->getResult();
                                                                                $pem2 = $db->query("SELECT * FROM tb_pengajuan_pembimbing a left join tb_dosen b on a.nip=b.nip WHERE a.nim='" . $key2['nim'] . "' AND a.sebagai='2' AND a.status_pengajuan='diterima'")->getResult();
                                                                                $judul = $db->query("SELECT * FROM tb_pengajuan_topik WHERE nim='" . $key2['nim'] . "'")->getResult();
                                                                                $sts_pem1 = $db->query("SELECT * FROM tb_perizinan_sidang WHERE nim='" . $key2['nim'] . "' AND jenis_sidang='skripsi' AND izin_sebagai='pembimbing 1'")->getResult();
                                                                                $sts_pem2 = $db->query("SELECT * FROM tb_perizinan_sidang WHERE nim='" . $key2['nim'] . "' AND jenis_sidang='skripsi' AND izin_sebagai='pembimbing 2'")->getResult();
                                                                                $sts_koor = $db->query("SELECT * FROM tb_perizinan_sidang WHERE nim='" . $key2['nim'] . "' AND jenis_sidang='skripsi' AND izin_sebagai='koordinator'")->getResult();
                                                                                $waktu_sidang_proposal = $db->query("SELECT a.nim, a.waktu_sidang, b.jenis_sidang FROM `tb_pendaftar_sidang` a JOIN tb_jadwal_sidang b ON a.id_jadwal=b.id_jadwal WHERE a.nim ='" . $key['nim'] . "' AND b.jenis_sidang = 'seminar proposal' AND a.waktu_sidang IS NOT NULL")->getResult();
                                                                                $waktu_sidang_skripsi = $db->query("SELECT a.nim, a.waktu_sidang, b.jenis_sidang FROM `tb_pendaftar_sidang` a JOIN tb_jadwal_sidang b ON a.id_jadwal=b.id_jadwal WHERE a.nim ='" . $key['nim'] . "' AND b.jenis_sidang = 'sidang skripsi' ")->getResult();
                                                                                if (!empty($waktu_sidang_proposal[0]->waktu_sidang) && !empty($waktu_sidang_skripsi[0]->waktu_sidang)) {
                                                                                    $date_time_sidang_skripsi = new DateTime($waktu_sidang_skripsi[0]->waktu_sidang);
                                                                                    $date_time_sidang_proposal = new DateTime($waktu_sidang_proposal[0]->waktu_sidang);
                                                                                    // $interval = $date_time_sidang_proposal->diff($date_time_sidang_skripsi)->format('%y tahun %m bulan %d hari');
                                                                                    $interval = $date_time_sidang_proposal->diff($date_time_sidang_skripsi);
                                                                                    if ($interval->y > 0) {
                                                                                        $interval = $interval->format('%y tahun %m bulan %d hari');
                                                                                    } elseif ($interval->m > 0) {
                                                                                        $interval = $interval->format('%m bulan %d hari');
                                                                                    } else {
                                                                                        $interval = $interval->format('%d hari');
                                                                                    }
                                                                                } else {
                                                                                    $interval = 'null';
                                                                                }

                                                                                if ($sts_koor[0]->status == 'disetujui') {
                                                                                    continue;
                                                                                }
                                                                        ?>
                                                                                <tr>
                                                                                    <td>
                                                                                        <img alt="avatar" class="rounded-circle avatar-md me-2" src="<?= base_url() ?>/image/<?= $key2['image'] ?>">
                                                                                    </td>
                                                                                    <td>
                                                                                        <?= $key2['nim'] . ' - ' . $key2['nama_mhs']; ?>
                                                                                    </td>
                                                                                    <td scope="row"><?= $judul[0]->judul_topik ?></td>
                                                                                    <td scope="row">
                                                                                        <?= $pem1 != NULL ? $pem1[0]->gelardepan . ' ' . $pem1[0]->nama . ', ' . $pem1[0]->gelarbelakang : '' ?>
                                                                                        <?php
                                                                                        if ($sts_pem1 != NULL) {
                                                                                            if ($sts_pem1[0]->status == 'disetujui') {
                                                                                                echo "<span class='text-success ms-3'>Disetujui</span>";
                                                                                            } elseif ($sts_pem1[0]->status == 'ditolak') {
                                                                                                echo "<span class='text-danger ms-3'>Ditolak</span>";
                                                                                            } else {
                                                                                                echo "<span class='text-warning ms-3'>Menunggu Disetuji</span>";
                                                                                            }
                                                                                        } else {
                                                                                            echo "<span class='text-danger ms-3'>Belum Melakukan Perizinan</span>";
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td scope="row">
                                                                                        <?= $pem2 != NULL ? $pem2[0]->gelardepan . ' ' . $pem2[0]->nama . ', ' . $pem2[0]->gelarbelakang : '' ?>
                                                                                        <?php
                                                                                        if ($sts_pem2 != NULL) {
                                                                                            if ($sts_pem2[0]->status == 'disetujui') {
                                                                                                echo "<span class='text-success ms-3'>Disetujui</span>";
                                                                                            } elseif ($sts_pem2[0]->status == 'ditolak') {
                                                                                                echo "<span class='text-danger ms-3'>Ditolak</span>";
                                                                                            } else {
                                                                                                echo "<span class='text-warning ms-3'>Menunggu Disetuji</span>";
                                                                                            }
                                                                                        } else {
                                                                                            echo "<span class='text-danger ms-3'>Belum Melakukan Perizinan</span>";
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td scope="row">
                                                                                        <?php
                                                                                        if ($sts_koor != NULL) {
                                                                                            if ($sts_koor[0]->status == 'disetujui') {
                                                                                                echo "<span class='text-success ms-3'>Disetujui</span>";
                                                                                            } elseif ($sts_koor[0]->status == 'ditolak') {
                                                                                                echo "<span class='text-danger ms-3'>Ditolak</span>";
                                                                                            } else {
                                                                                                echo "<span class='text-warning ms-3'>Menunggu Disetuji</span>";
                                                                                            }
                                                                                        } else {
                                                                                            echo "<span class='text-danger ms-3'>Belum Melakukan Perizinan</span>";
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td style="text-align: center; vertical-align: middle;"><?= $interval ?></td>
                                                                                    <td style="text-align: center; vertical-align: middle;">
                                                                                        <?php
                                                                                        $cek = $db->query("SELECT * FROM tb_perizinan_sidang WHERE nim='" . $key2['nim'] . "' AND nip='" . session()->get('ses_id') . "' AND jenis_sidang='seminar proposal'")->getResult();
                                                                                        if ($cek != NULL) {
                                                                                        ?>
                                                                                            <div class="btn-group">
                                                                                                <form action="<?= base_url() ?>proses_validasi_daftar_seminar_koor" method="POST" enctype="multipart/form-data">
                                                                                                    <?= csrf_field() ?>
                                                                                                    <input type="hidden" name="id_perizinan_sidang" value="<?= $key2['id_perizinan_sidang'] ?>">
                                                                                                    <input type="hidden" name="nim" value="<?= $key2['nim'] ?>">
                                                                                                    <input type="hidden" name="status" value="disetujui">
                                                                                                    <input type="hidden" name="jenis_sidang" value="skripsi">
                                                                                                    <button class="btn btn-success btn-sm" type="submit"><i class="las la-check"></i></button>
                                                                                                </form>
                                                                                                <form action="<?= base_url() ?>proses_validasi_daftar_seminar_koor" method="POST" enctype="multipart/form-data">
                                                                                                    <?= csrf_field() ?>
                                                                                                    <input type="hidden" name="id_perizinan_sidang" value="<?= $key2['id_perizinan_sidang'] ?>">
                                                                                                    <input type="hidden" name="nim" value="<?= $key2['nim'] ?>">
                                                                                                    <input type="hidden" name="status" value="menunggu">
                                                                                                    <input type="hidden" name="jenis_sidang" value="skripsi">
                                                                                                    <button class="btn btn-warning btn-sm" type="submit"><i class="las la-hourglass-half"></i></i></button>
                                                                                                </form>
                                                                                                <form action="<?= base_url() ?>proses_validasi_daftar_seminar_koor" method="POST" enctype="multipart/form-data">
                                                                                                    <?= csrf_field() ?>
                                                                                                    <input type="hidden" name="id_perizinan_sidang" value="<?= $key2['id_perizinan_sidang'] ?>">
                                                                                                    <input type="hidden" name="nim" value="<?= $key2['nim'] ?>">
                                                                                                    <input type="hidden" name="status" value="ditolak">
                                                                                                    <input type="hidden" name="jenis_sidang" value="skripsi">
                                                                                                    <button class="btn btn-danger btn-sm" type="submit"><i class="las la-times"></i></button>
                                                                                                </form>
                                                                                            </div>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                </tr>
                                                                        <?php $no++;
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="sidangValidasi">
                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-striped mg-b-0 text-md-nowrap" id="validasitable5">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>&nbsp;</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Nama</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Judul</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Pembimbing 1</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Pembimbing 2</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Koordinator Prodi</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Jarak Seminar ke Sidang</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Aksi</span></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody id='show_data2'>
                                                                        <?php
                                                                        $no = 1;
                                                                        foreach ($data_mhs_bimbingan as $key2) {
                                                                            if ($key2['jenis_sidang'] == 'skripsi') {
                                                                                $pem1 = $db->query("SELECT * FROM tb_pengajuan_pembimbing a left join tb_dosen b on a.nip=b.nip WHERE a.nim='" . $key2['nim'] . "' AND a.sebagai='1' AND a.status_pengajuan='diterima'")->getResult();
                                                                                $pem2 = $db->query("SELECT * FROM tb_pengajuan_pembimbing a left join tb_dosen b on a.nip=b.nip WHERE a.nim='" . $key2['nim'] . "' AND a.sebagai='2' AND a.status_pengajuan='diterima'")->getResult();
                                                                                $judul = $db->query("SELECT * FROM tb_pengajuan_topik WHERE nim='" . $key2['nim'] . "'")->getResult();
                                                                                $sts_pem1 = $db->query("SELECT * FROM tb_perizinan_sidang WHERE nim='" . $key2['nim'] . "' AND jenis_sidang='skripsi' AND izin_sebagai='pembimbing 1'")->getResult();
                                                                                $sts_pem2 = $db->query("SELECT * FROM tb_perizinan_sidang WHERE nim='" . $key2['nim'] . "' AND jenis_sidang='skripsi' AND izin_sebagai='pembimbing 2'")->getResult();
                                                                                $sts_koor = $db->query("SELECT * FROM tb_perizinan_sidang WHERE nim='" . $key2['nim'] . "' AND jenis_sidang='skripsi' AND izin_sebagai='koordinator'")->getResult();
                                                                                $waktu_sidang_proposal = $db->query("SELECT a.nim, a.waktu_sidang, b.jenis_sidang FROM `tb_pendaftar_sidang` a JOIN tb_jadwal_sidang b ON a.id_jadwal=b.id_jadwal WHERE a.nim ='" . $key2['nim'] . "' AND b.jenis_sidang = 'seminar proposal' AND a.waktu_sidang IS NOT NULL")->getResult();
                                                                                $waktu_sidang_skripsi = $db->query("SELECT a.nim, a.waktu_sidang, b.jenis_sidang FROM `tb_pendaftar_sidang` a JOIN tb_jadwal_sidang b ON a.id_jadwal=b.id_jadwal WHERE a.nim ='" . $key2['nim'] . "' AND b.jenis_sidang = 'sidang skripsi'AND a.waktu_sidang IS NOT NULL ")->getResult();
                                                                                // dd($waktu_sidang_skripsi[0]->waktu_sidang);
                                                                                if (!empty($waktu_sidang_proposal[0]->waktu_sidang) && !empty($waktu_sidang_skripsi[0]->waktu_sidang)) {
                                                                                    $date_time_sidang_skripsi = new DateTime($waktu_sidang_skripsi[0]->waktu_sidang);
                                                                                    $date_time_sidang_proposal = new DateTime($waktu_sidang_proposal[0]->waktu_sidang);
                                                                                    // $interval = $date_time_sidang_proposal->diff($date_time_sidang_skripsi)->format('%y tahun %m bulan %d hari');
                                                                                    $interval = $date_time_sidang_proposal->diff($date_time_sidang_skripsi);
                                                                                    if ($interval->y > 0) {
                                                                                        $interval = $interval->format('%y tahun %m bulan %d hari');
                                                                                    } elseif ($interval->m > 0) {
                                                                                        $interval = $interval->format('%m bulan %d hari');
                                                                                    } else {
                                                                                        $interval = $interval->format('%d hari');
                                                                                    }
                                                                                } else {
                                                                                    if (empty($date_time_sidang_proposal) || empty($date_time_sidang_skripsi)) {
                                                                                        $interval = 'kosong';
                                                                                    } elseif (!empty($date_time_sidang_proposal)) {
                                                                                        $interval = 'sidang skripsi kosong';
                                                                                    } elseif (!empty($date_time_sidang_skripsi)) {
                                                                                        $interval = 'sidang skripsi kosong';
                                                                                    }
                                                                                }
                                                                                if ($sts_koor[0]->status != 'disetujui') {
                                                                                    continue;
                                                                                }
                                                                        ?>
                                                                                <tr>
                                                                                    <td>
                                                                                        <img alt="avatar" class="rounded-circle avatar-md me-2" src="<?= base_url() ?>/image/<?= $key2['image'] ?>">
                                                                                    </td>
                                                                                    <td>
                                                                                        <?= $key2['nim'] . ' - ' . $key2['nama_mhs']; ?>
                                                                                    </td>
                                                                                    <td scope="row"><?= $judul[0]->judul_topik ?></td>
                                                                                    <td scope="row">
                                                                                        <?= $pem1 != NULL ? $pem1[0]->gelardepan . ' ' . $pem1[0]->nama . ', ' . $pem1[0]->gelarbelakang : '' ?>
                                                                                        <?php
                                                                                        if ($sts_pem1 != NULL) {
                                                                                            if ($sts_pem1[0]->status == 'disetujui') {
                                                                                                echo "<span class='text-success ms-3'>Disetujui</span>";
                                                                                            } elseif ($sts_pem1[0]->status == 'ditolak') {
                                                                                                echo "<span class='text-danger ms-3'>Ditolak</span>";
                                                                                            } else {
                                                                                                echo "<span class='text-warning ms-3'>Menunggu Disetuji</span>";
                                                                                            }
                                                                                        } else {
                                                                                            echo "<span class='text-danger ms-3'>Belum Melakukan Perizinan</span>";
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td scope="row">
                                                                                        <?= $pem2 != NULL ? $pem2[0]->gelardepan . ' ' . $pem2[0]->nama . ', ' . $pem2[0]->gelarbelakang : '' ?>
                                                                                        <?php
                                                                                        if ($sts_pem2 != NULL) {
                                                                                            if ($sts_pem2[0]->status == 'disetujui') {
                                                                                                echo "<span class='text-success ms-3'>Disetujui</span>";
                                                                                            } elseif ($sts_pem2[0]->status == 'ditolak') {
                                                                                                echo "<span class='text-danger ms-3'>Ditolak</span>";
                                                                                            } else {
                                                                                                echo "<span class='text-warning ms-3'>Menunggu Disetuji</span>";
                                                                                            }
                                                                                        } else {
                                                                                            echo "<span class='text-danger ms-3'>Belum Melakukan Perizinan</span>";
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td scope="row">
                                                                                        <?php
                                                                                        if ($sts_koor != NULL) {
                                                                                            if ($sts_koor[0]->status == 'disetujui') {
                                                                                                echo "<span class='text-success ms-3'>Disetujui</span>";
                                                                                            } elseif ($sts_koor[0]->status == 'ditolak') {
                                                                                                echo "<span class='text-danger ms-3'>Ditolak</span>";
                                                                                            } else {
                                                                                                echo "<span class='text-warning ms-3'>Menunggu Disetuji</span>";
                                                                                            }
                                                                                        } else {
                                                                                            echo "<span class='text-danger ms-3'>Belum Melakukan Perizinan</span>";
                                                                                        }
                                                                                        ?>
                                                                                    </td>
                                                                                    <td style="text-align: center; vertical-align: middle;"><?= $interval ?></td>

                                                                                    <td style="text-align: center; vertical-align: middle;">
                                                                                        <?php
                                                                                        $cek = $db->query("SELECT * FROM tb_perizinan_sidang WHERE nim='" . $key2['nim'] . "' AND nip='" . session()->get('ses_id') . "' AND jenis_sidang='seminar proposal'")->getResult();
                                                                                        if ($cek != NULL) {
                                                                                        ?>
                                                                                            <div class="btn-group">
                                                                                                <form action="<?= base_url() ?>proses_validasi_daftar_seminar_koor" method="POST" enctype="multipart/form-data">
                                                                                                    <?= csrf_field() ?>
                                                                                                    <input type="hidden" name="id_perizinan_sidang" value="<?= $key2['id_perizinan_sidang'] ?>">
                                                                                                    <input type="hidden" name="nim" value="<?= $key2['nim'] ?>">
                                                                                                    <input type="hidden" name="status" value="disetujui">
                                                                                                    <input type="hidden" name="jenis_sidang" value="skripsi">
                                                                                                    <button class="btn btn-success btn-sm" type="submit"><i class="las la-check"></i></button>
                                                                                                </form>
                                                                                                <form action="<?= base_url() ?>proses_validasi_daftar_seminar_koor" method="POST" enctype="multipart/form-data">
                                                                                                    <?= csrf_field() ?>
                                                                                                    <input type="hidden" name="id_perizinan_sidang" value="<?= $key2['id_perizinan_sidang'] ?>">
                                                                                                    <input type="hidden" name="nim" value="<?= $key2['nim'] ?>">
                                                                                                    <input type="hidden" name="status" value="menunggu">
                                                                                                    <input type="hidden" name="jenis_sidang" value="skripsi">
                                                                                                    <button class="btn btn-warning btn-sm" type="submit"><i class="las la-hourglass-half"></i></i></button>
                                                                                                </form>
                                                                                                <form action="<?= base_url() ?>proses_validasi_daftar_seminar_koor" method="POST" enctype="multipart/form-data">
                                                                                                    <?= csrf_field() ?>
                                                                                                    <input type="hidden" name="id_perizinan_sidang" value="<?= $key2['id_perizinan_sidang'] ?>">
                                                                                                    <input type="hidden" name="nim" value="<?= $key2['nim'] ?>">
                                                                                                    <input type="hidden" name="status" value="ditolak">
                                                                                                    <input type="hidden" name="jenis_sidang" value="skripsi">
                                                                                                    <button class="btn btn-danger btn-sm" type="submit"><i class="las la-times"></i></button>
                                                                                                </form>
                                                                                            </div>
                                                                                        <?php } ?>
                                                                                    </td>
                                                                                </tr>
                                                                        <?php $no++;
                                                                            }
                                                                        }
                                                                        ?>
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
</div>

<?= $this->endSection(); ?>