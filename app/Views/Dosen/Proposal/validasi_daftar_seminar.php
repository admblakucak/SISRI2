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
                        <h4 class="card-title mg-b-0">Persetujuan Daftar Seminar Proposal</h4>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>
                    <p class="tx-12 tx-gray-500 mb-2">Permintaan izin mahasiswa untuk mendaftar seminar proposal</a></p>
                </div>
                <div class="col-xl-12 pt-3">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="tab-pane" id="pem2">
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
                                                    // dd($data_mhs_bimbingan);
                                                    foreach ($data_mhs_bimbingan as $key) {
                                                        $pem1 = $db->query("SELECT * FROM tb_pengajuan_pembimbing a left join tb_dosen b on a.nip=b.nip WHERE a.nim='" . $key['nim'] . "' AND a.sebagai='1' AND a.status_pengajuan='diterima'")->getResult();
                                                        $pem2 = $db->query("SELECT * FROM tb_pengajuan_pembimbing a left join tb_dosen b on a.nip=b.nip WHERE a.nim='" . $key['nim'] . "' AND a.sebagai='2' AND a.status_pengajuan='diterima'")->getResult();
                                                        $judul = $db->query("SELECT * FROM tb_pengajuan_topik WHERE nim='" . $key['nim'] . "'")->getResult();
                                                        $sts_pem1 = $db->query("SELECT * FROM tb_perizinan_sidang WHERE nim='" . $key['nim'] . "' AND jenis_sidang='seminar proposal' AND izin_sebagai='pembimbing 1'")->getResult();
                                                        $sts_pem2 = $db->query("SELECT * FROM tb_perizinan_sidang WHERE nim='" . $key['nim'] . "' AND jenis_sidang='seminar proposal' AND izin_sebagai='pembimbing 2'")->getResult();
                                                        $sts_koor = $db->query("SELECT * FROM tb_perizinan_sidang WHERE nim='" . $key['nim'] . "' AND jenis_sidang='seminar proposal' AND izin_sebagai='koordinator'")->getResult();
                                                        // dd($sts_koor[0]->nip == session()->get('ses_id'));
                                                        if ($sts_koor || $sts_pem1 || $sts_pem2 != null) {
                                                            if (isset($sts_koor[0]->nip) && $sts_koor[0] == session()->get('ses_id')) {
                                                                if ($sts_koor[0]->status == "disetujui") {
                                                                    continue;
                                                                }
                                                            } elseif ($sts_pem1[0]->nip == session()->get('ses_id')) {
                                                                if ($sts_pem1[0]->status == "disetujui") {
                                                                    continue;
                                                                }
                                                            } elseif ($sts_pem2[0]->nip == session()->get('ses_id')) {
                                                                if ($sts_pem2[0]->status == "disetujui") {
                                                                    continue;
                                                                }
                                                            }
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
                                                                        <form action="<?= base_url() ?>proses_validasi_daftar_seminar" method="POST" enctype="multipart/form-data">
                                                                            <?= csrf_field() ?>
                                                                            <input type="hidden" name="nim" value="<?= $key['nim'] ?>">
                                                                            <input type="hidden" name="status" value="disetujui">
                                                                            <button class="btn btn-success btn-sm" type="submit"><i class="las la-check"></i></button>
                                                                        </form>
                                                                        <form action="<?= base_url() ?>proses_validasi_daftar_seminar" method="POST" enctype="multipart/form-data">
                                                                            <?= csrf_field() ?>
                                                                            <input type="hidden" name="nim" value="<?= $key['nim'] ?>">
                                                                            <input type="hidden" name="status" value="menunggu">
                                                                            <button class="btn btn-warning btn-sm" type="submit"><i class="las la-hourglass-half"></i></i></button>
                                                                        </form>
                                                                        <form action="<?= base_url() ?>proses_validasi_daftar_seminar" method="POST" enctype="multipart/form-data">
                                                                            <?= csrf_field() ?>
                                                                            <input type="hidden" name="nim" value="<?= $key['nim'] ?>">
                                                                            <input type="hidden" name="status" value="ditolak">
                                                                            <button class="btn btn-danger btn-sm" type="submit"><i class="las la-times"></i></button>
                                                                        </form>
                                                                    </div>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                    <?php $no++;
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

<?= $this->endSection(); ?>