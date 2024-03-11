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
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="d-flex justify-content-between">
                                <h4 class="card-title mg-b-0">Daftar Usulan Topik</h4>
                                <i class="mdi mdi-dots-horizontal text-gray"></i>
                            </div>
                        </div>
                        <p class="tx-12 tx-gray-500 mb-2">Daftar Usulan Topik dari Mahasiswa</a></p>
                    </div>
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
                                                    <li><a href="#menunggu" class="nav-link active" data-bs-toggle="tab">Menunggu</a></li>
                                                    <li><a href="#diterima" class="nav-link" data-bs-toggle="tab">Diterima</a></li>
                                                    <li><a href="#ditolak" class="nav-link" data-bs-toggle="tab">Ditolak</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="panel-body tabs-menu-body main-content-body-right border">
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="menunggu">
                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            Kuota Dosen Pembimbing 1 : Terisi <?= count($jumlah_pembimbing_p1) == 0 ? '0 dari 10' : $jumlah_pembimbing_p1[0]->jumlah . ' dari ' . $jumlah_pembimbing_p1[0]->kuota ?>
                                                            <br>
                                                            Kuota Dosen Pembimbing 2 : Terisi <?= count($jumlah_pembimbing_p2) == 0 ? '0 dari 10' : $jumlah_pembimbing_p2[0]->jumlah . ' dari ' . $jumlah_pembimbing_p2[0]->kuota ?>
                                                            <br><br>
                                                            <div class="table-responsive">
                                                                <table class="table table-striped mg-b-0 text-md-nowrap" id="validasitable1">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>No. </span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Mahasiswa</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Topik</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Judul</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Pembimbing 1</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Pembimbing 2</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Permintaan Sebagai</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Aksi</span></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $no = 1;
                                                                        foreach ($data_menunggu as $key) {
                                                                            $ketdospem1 = $db->query("SELECT * FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.nip=b.nip where nim='" . $key->nim . "' AND a.status_pengajuan='diterima' AND a.sebagai='1'")->getResultArray();
                                                                            $ketdospem2 = $db->query("SELECT * FROM tb_pengajuan_pembimbing a LEFT JOIN tb_dosen b ON a.nip=b.nip where nim='" . $key->nim . "' AND a.status_pengajuan='diterima' AND a.sebagai='2'")->getResultArray();
                                                                            $id_pengajuan = $key->id_pengajuan_pembimbing;
                                                                        ?>
                                                                            <tr>
                                                                                <td><?= $no; ?></td>
                                                                                <td><?= $key->nim . ' - ' . $key->nama_mhs; ?></td>
                                                                                <td><?= $key->nama_topik; ?></td>
                                                                                <td><?= $key->judul_topik; ?></td>
                                                                                <td><?php
                                                                                    if (count($ketdospem1) > 0) {
                                                                                        echo $ketdospem1[0]['gelardepan'] . ' ' . $ketdospem1[0]['nama'] . ', ' . $ketdospem1[0]['gelarbelakang'];
                                                                                    } else {
                                                                                        echo '-';
                                                                                    }
                                                                                    ?></td>
                                                                                <td><?php
                                                                                    if (count($ketdospem2) > 0) {
                                                                                        echo $ketdospem2[0]['gelardepan'] . ' ' . $ketdospem2[0]['nama'] . ', ' . $ketdospem2[0]['gelarbelakang'];
                                                                                    } else {
                                                                                        echo '-';
                                                                                    }
                                                                                    ?></td>
                                                                                <td><?= 'Pembimbing ' . $key->sebagai; ?></td>
                                                                                <td>
                                                                                    <a href="<?= base_url() ?>setujui_validasi_usulan/<?= $id_pengajuan ?>" class="btn btn-success btn-sm"><i class="las la-check"></i></a>
                                                                                    <a href="#" data-bs-target="#modaltolak<?= $no ?>" data-bs-toggle="modal" href="" class="btn btn-sm btn-dark"><i class="las la-times"></i></a>
                                                                                    <?php if ($key->berkas == '') {
                                                                                        echo " ";
                                                                                    } else { ?>
                                                                                        <a href="<?= base_url() ?>download_proposal/menunggu/<?= $id_pengajuan ?>" class="btn btn-primary btn-sm"><i class="las la-download"> Proposal</i></a>
                                                                                    <?php } ?>
                                                                                </td>
                                                                            </tr>
                                                                            <div class="modal" id="modaltolak<?= $no ?>">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content modal-content-demo">
                                                                                        <form action="<?= base_url() ?>tolak_validasi_usulan" method="POST" enctype="multipart/form-data">
                                                                                            <?= csrf_field() ?>
                                                                                            <input type="hidden" name="id_pengajuan" value="<?= $id_pengajuan ?>">
                                                                                            <input type="hidden" name="berkas" value="<?= $key->berkas ?>">
                                                                                            <div class="modal-header">
                                                                                                <h6 class="modal-title">Tolak</h6><button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                                                                                            </div>
                                                                                            <div class="modal-body">
                                                                                                <div class="row">
                                                                                                    <div class="col-5">
                                                                                                        NIM
                                                                                                    </div>
                                                                                                    <div class="col-1">
                                                                                                        :
                                                                                                    </div>
                                                                                                    <div class="col-auto">
                                                                                                        <?= $key->nim ?>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="row">
                                                                                                    <div class="col-5">
                                                                                                        Nama Mahasiswa
                                                                                                    </div>
                                                                                                    <div class="col-1">
                                                                                                        :
                                                                                                    </div>
                                                                                                    <div class="col-auto">
                                                                                                        <?= $key->nama_mhs ?>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="row">
                                                                                                    <div class="col-5">
                                                                                                        Topik
                                                                                                    </div>
                                                                                                    <div class="col-1">
                                                                                                        :
                                                                                                    </div>
                                                                                                    <div class="col-auto">
                                                                                                        <?= $key->nama_topik ?>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="row">
                                                                                                    <div class="col-5">
                                                                                                        Judul
                                                                                                    </div>
                                                                                                    <div class="col-1">
                                                                                                        :
                                                                                                    </div>
                                                                                                    <div class="col-auto">
                                                                                                        <?= $key->judul_topik ?>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="row">
                                                                                                    <div class="col-5">
                                                                                                        Sebagai Pembimbing
                                                                                                    </div>
                                                                                                    <div class="col-1">
                                                                                                        :
                                                                                                    </div>
                                                                                                    <div class="col-auto">
                                                                                                        <?= $key->sebagai ?>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="row mt-3">
                                                                                                    <div class="col-lg">
                                                                                                        <textarea class="form-control" name="pesan" placeholder="Pesan penolakan (opsional)" rows="3"></textarea>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button class="btn ripple btn-danger" type="submit">Tolak</button>
                                                                                                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">Tutup</button>
                                                                                            </div>
                                                                                        </form>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        <?php $no++;
                                                                        } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="diterima">
                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-striped mg-b-0 text-md-nowrap" id="validasitable2">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>No. </span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Mahasiswa</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Topik</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Judul</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Sebagai</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Diterima Pada</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Aksi</span></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $no = 1;
                                                                        foreach ($data_diterima as $key) {
                                                                        ?>
                                                                            <tr>
                                                                                <td><?= $no; ?></td>
                                                                                <td><?= $key->nim . ' - ' . $key->nama; ?></td>
                                                                                <td><?= $key->nama_topik; ?></td>
                                                                                <td><?= $key->judul_topik; ?></td>
                                                                                <td>Pembimbing <?= $key->sebagai; ?></td>
                                                                                <td><?= $key->agree_at; ?></td>
                                                                                <td>
                                                                                    <?php if ($key->berkas == '') {
                                                                                        echo " ";
                                                                                    } else { ?>
                                                                                        <a href="<?= base_url() ?>download_proposal/terima_pengajuan/<?= $key->id_pengajuan_pembimbing ?>" class="btn btn-primary btn-sm"><i class="las la-download"> Proposal</i></a>
                                                                                    <?php } ?>
                                                                                </td>
                                                                            </tr>
                                                                        <?php $no++;
                                                                        } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="ditolak">
                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-striped mg-b-0 text-md-nowrap" id="validasitable3">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>No. </span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Mahasiswa</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Topik</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Judul</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Sebagai</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Ditolak Pada</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Pesan Penolakan</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Aksi</span></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $no = 1;
                                                                        foreach ($data_ditolak as $key) {
                                                                        ?>
                                                                            <tr>
                                                                                <td><?= $no; ?></td>
                                                                                <td><?= $key->nim . ' - ' . $key->nama; ?></td>
                                                                                <td><?= $key->nama_topik; ?></td>
                                                                                <td><?= $key->judul; ?></td>
                                                                                <td>Pembimbing <?= $key->sebagai; ?></td>
                                                                                <td><?= $key->reject_at; ?></td>
                                                                                <td><?= $key->pesan; ?></td>
                                                                                <td>
                                                                                    <?php if ($key->berkas == '') {
                                                                                        echo " ";
                                                                                    } else { ?>
                                                                                        <a href="<?= base_url() ?>download_proposal/tolak_pengajuan/<?= $key->id_penolakan_pengajuan_pembimbing ?>" class="btn btn-primary btn-sm"><i class="las la-download"> Proposal</i></a>
                                                                                    <?php } ?>
                                                                                </td>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>