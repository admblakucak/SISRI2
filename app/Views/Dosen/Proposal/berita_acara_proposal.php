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
                                <h4 class="card-title mg-b-0">Berita Acara Seminar Proposal</h4>
                                <i class="mdi mdi-dots-horizontal text-gray"></i>
                            </div>
                        </div>
                        <p class="tx-12 tx-gray-500 mb-2">Daftar Berita Acara</a></p>
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
                                                    <li><a href="#pembimbing" class="nav-link active" data-bs-toggle="tab">Pembimbing</a></li>
                                                    <li><a href="#penguji" class="nav-link" data-bs-toggle="tab">Penguji</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="panel-body tabs-menu-body main-content-body-right border">
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="pembimbing">
                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-striped mg-b-0 text-md-nowrap" id="validasitable1">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="text-align: center; vertical-align: middle;"> <span> No. </span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Mahasiswa</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Judul</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Waktu Sidang</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Ruang Sidang</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Hasil Sidang</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Anda Sebagai</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Aksi</span></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $no = 1;
                                                                        foreach ($data_mhs_bimbingan as $key1) {
                                                                            $judul = $db->query("SELECT * FROM tb_pengajuan_topik WHERE nim='" . $key1->nim . "'")->getResult();
                                                                            $sidang = $db->query("SELECT * FROM tb_pendaftar_sidang a LEFT JOIN tb_jadwal_sidang b ON a.`id_jadwal`=b.`id_jadwal` WHERE b.`jenis_sidang`='seminar proposal' AND a.`nim`='" . $key1->nim . "'")->getResult();
                                                                            $berita_acara = $db->query("SELECT * FROM tb_berita_acara WHERE nim='" . $key1->nim . "' AND nip='" . session()->get('ses_id') . "' AND sebagai='pembimbing $key1->sebagai' AND status='ditandatangani' AND jenis_sidang='proposal'")->getResult();

                                                                        ?>
                                                                            <?php if (empty($sidang)) {
                                                                                continue;
                                                                            } ?>
                                                                            <tr>
                                                                                <td></td>
                                                                                <td><?= $key1->nim . ' - ' . $key1->nama ?></td>
                                                                                <td>
                                                                                    <?php
                                                                                    // $judul[0]->judul_topik 
                                                                                    if (!empty($judul)) {
                                                                                        echo $judul[0]->judul_topik;
                                                                                    } else {
                                                                                        echo "";
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td><?= !empty($sidang) ? $sidang[0]->waktu_sidang : "" ?></td>
                                                                                <td><?= !empty($sidang) ? $sidang[0]->ruang_sidang : "" ?></td>
                                                                                <td>
                                                                                    <?php
                                                                                    if (!empty($sidang)) {
                                                                                        if ($sidang[0]->hasil_sidang == '1') {
                                                                                            echo "Disetujui tanpa perbaikan";
                                                                                        } elseif ($sidang[0]->hasil_sidang == '2') {
                                                                                            echo "Disetujui dengan perbaikan";
                                                                                        } elseif ($sidang[0]->hasil_sidang == '3') {
                                                                                            echo "Tidak disetujui/mengulang";
                                                                                        } else {
                                                                                            echo "-";
                                                                                        }
                                                                                    } else {
                                                                                        echo '-';
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td><?= 'Pembimbing ' . $key1->sebagai ?></td>
                                                                                <td class="text-center">
                                                                                    <?php
                                                                                    if (empty($berita_acara)) {
                                                                                    ?>
                                                                                        <a class="btn btn-success btn-sm" data-bs-target="#modalpembimbing<?= $no ?>" data-bs-toggle="modal" href=""><i class="las la-check"></i></a>
                                                                                        <?php } else {
                                                                                        if ('pembimbing ' . $key1->sebagai == 'pembimbing 1') { ?>
                                                                                            <a class="btn btn-success btn-sm" data-bs-target="#modalpembimbing<?= $no ?>" data-bs-toggle="modal" href="">Edit Hasil Seminar</a>
                                                                                    <?php } else {
                                                                                            echo "<span class='text-success ms-2'>Telah Ditandatangani</span>";
                                                                                        }
                                                                                    } ?>
                                                                                    <a href="<?= base_url() ?>/berita_acara_proposal_download_file/proposal/<?= $sidang[0]->id_pendaftar ?>" class="btn btn-primary btn-sm mt-2 ml-2"><i class="las la-download"> Proposal</i></a>

                                                                                </td>
                                                                            </tr>
                                                                            <div class="modal" id="modalpembimbing<?= $no ?>">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content modal-content-demo">
                                                                                        <div class="modal-header">
                                                                                            <h6 class="modal-title">Menginputkan Nilai</h6><button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                                                                                        </div>
                                                                                        <form action="<?= base_url() ?>tandatangani_proposal" method="POST" enctype="multipart/form-data">
                                                                                            <?= csrf_field() ?>
                                                                                            <div class="modal-body">
                                                                                                <input type="hidden" name="nim" value="<?= $key1->nim ?>">
                                                                                                <input type="hidden" name="sebagai" value="pembimbing <?= $key1->sebagai ?>">
                                                                                                <input type="hidden" name="id_pendaftar" value="<?= !empty($sidang) ? $sidang[0]->id_pendaftar : '' ?>">
                                                                                                <div class="form-group">
                                                                                                    <label for="exampleInputEmail1">Anda Sebagai : <b>Pembimbing <?= $key1->sebagai ?></b></label>
                                                                                                </div>
                                                                                                <div class="form-group">
                                                                                                    <label for="exampleInputEmail1">Status Ujian Skripsi</label>
                                                                                                    <label class="rdiobox mb-1">
                                                                                                        <input <?= !empty($sidang) ? ($sidang[0]->hasil_sidang == '1' ? "checked" : '') : '' ?> <?= $key1->sebagai != '1' ? "disabled" : '' ?> type="radio" name="status" value="1"><span>Disetujui tanpa perbaikan</span>
                                                                                                    </label>
                                                                                                    <label class="rdiobox mb-1">
                                                                                                        <input <?= !empty($sidang) ? ($sidang[0]->hasil_sidang == '2' ? "checked" : '') : '' ?> <?= $key1->sebagai != '1' ? "disabled" : '' ?> type="radio" name="status" value="2"><span>Disetujui dengan perbaikan</span>
                                                                                                    </label>
                                                                                                    <label class="rdiobox">
                                                                                                        <input <?= !empty($sidang) ? ($sidang[0]->hasil_sidang == '3' ? "checked" : '') : '' ?> <?= $key1->sebagai != '1' ? "disabled" : '' ?> type="radio" name="status" value="3"><span>Tidak disetujui/mengulang</span>
                                                                                                    </label>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <?php
                                                                                                if (!empty($berita_acara)) {
                                                                                                    if ('pembimbing ' . $key1->sebagai == 'pembimbing 1') { ?>
                                                                                                        <button class="btn ripple btn-primary" type="submit">Simpan</button>
                                                                                                <?php } else {
                                                                                                        echo '<button class="btn ripple btn-primary" type="submit">Tandatangani</button>';
                                                                                                    }
                                                                                                } else {
                                                                                                    echo ' <button class="btn ripple btn-primary" type="submit">Tandatangani</button>';
                                                                                                } ?>
                                                                                                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">Keluar</button>
                                                                                            </div>
                                                                                        </form>
                                                                                        <?php
                                                                                        $no++;

                                                                                        ?>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        <?php
                                                                        } ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="penguji">
                                                    <div class="row">
                                                        <div class="col-xl-12">
                                                            <div class="table-responsive">
                                                                <table class="table table-striped mg-b-0 text-md-nowrap" id="validasitable10">
                                                                    <thead>
                                                                        <tr>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>No. </span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Mahasiswa</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Judul</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Waktu Sidang</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Ruang Sidang</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Hasil Sidang</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Anda Sebagai</span></th>
                                                                            <th style="text-align: center; vertical-align: middle;"><span>Aksi</span></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $no = 1;
                                                                        foreach ($data_mhs_uji as $key2) {
                                                                            $judul = $db->query("SELECT * FROM tb_pengajuan_topik WHERE nim='" . $key2->nim . "'")->getResult();
                                                                            $sidang = $db->query("SELECT * FROM tb_pendaftar_sidang a LEFT JOIN tb_jadwal_sidang b ON a.`id_jadwal`=b.`id_jadwal` WHERE b.`jenis_sidang`='seminar proposal' AND a.`nim`='" . $key2->nim . "' ORDER BY create_at DESC LIMIT 1")->getResult();
                                                                            $berita_acara = $db->query("SELECT * FROM tb_berita_acara WHERE nim='" . $key2->nim . "' AND nip='" . session()->get('ses_id') . "' AND sebagai='penguji $key2->sebagai' AND status='ditandatangani' AND jenis_sidang='proposal'")->getResult();
                                                                        ?>
                                                                            <?php if (empty($sidang)) {
                                                                                continue;
                                                                            } ?>
                                                                            <tr>
                                                                                <td></td>
                                                                                <td><?= $key2->nim . ' - ' . $key2->nama ?></td>
                                                                                <td>
                                                                                    <?php
                                                                                    // $judul[0]->judul_topik 
                                                                                    if (!empty($judul)) {
                                                                                        echo $judul[0]->judul_topik;
                                                                                    } else {
                                                                                        echo "";
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td><?= !empty($sidang) ? $sidang[0]->waktu_sidang : "" ?></td>
                                                                                <td><?= !empty($sidang) ? $sidang[0]->ruang_sidang : "" ?></td>
                                                                                <td>
                                                                                    <?php
                                                                                    if (!empty($sidang)) {
                                                                                        if ($sidang[0]->hasil_sidang == '1') {
                                                                                            echo "Disetujui tanpa perbaikan";
                                                                                        } elseif ($sidang[0]->hasil_sidang == '2') {
                                                                                            echo "Disetujui dengan perbaikan";
                                                                                        } elseif ($sidang[0]->hasil_sidang == '3') {
                                                                                            echo "Tidak disetujui/mengulang";
                                                                                        } else {
                                                                                            echo "-";
                                                                                        }
                                                                                    } else {
                                                                                        echo '-';
                                                                                    }
                                                                                    ?>
                                                                                </td>
                                                                                <td><?= 'Penguji ' . $key2->sebagai ?></td>
                                                                                <td>
                                                                                    <?php
                                                                                    if (empty($berita_acara)) {
                                                                                    ?>
                                                                                        <a class="btn btn-success btn-sm" data-bs-target="#modalpenguji<?= $no ?>" data-bs-toggle="modal" href=""><i class="las la-check"></i></a>
                                                                                    <?php } else {
                                                                                        echo "<span class='text-success ms-2'>Telah Ditandatangani</span>";
                                                                                    } ?>
                                                                                    <a href="<?= base_url() ?>/berita_acara_proposal_download_file/proposal/<?= $sidang[0]->id_pendaftar ?>" class="btn btn-primary btn-sm mt-2 ml-2"><i class="las la-download"> Proposal</i></a>
                                                                                    <?php
                                                                                    ?>
                                                                                </td>
                                                                            </tr>


                                                                            <div class="modal" id="modalpenguji<?= $no ?>">
                                                                                <div class="modal-dialog" role="document">
                                                                                    <div class="modal-content modal-content-demo">
                                                                                        <div class="modal-header">
                                                                                            <h6 class="modal-title">Tanda Tangan Berita Acara</h6><button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                                                                                        </div>
                                                                                        <form action="<?= base_url() ?>tandatangani_proposal" method="POST" enctype="multipart/form-data">
                                                                                            <?= csrf_field() ?>
                                                                                            <div class="modal-body">
                                                                                                <input type="hidden" name="nim" value="<?= $key2->nim ?>">
                                                                                                <input type="hidden" name="sebagai" value="penguji <?= $key2->sebagai ?>">
                                                                                                <input type="hidden" name="id_pendaftar" value="<?= !empty($sidang) ? $sidang[0]->id_pendaftar : '' ?>">
                                                                                                <div class="form-group">
                                                                                                    <label for="exampleInputEmail1">Anda Sebagai : <b>Penguji <?= $key2->sebagai ?></b></label>
                                                                                                </div>
                                                                                                <div class="form-group">
                                                                                                    <label for="exampleInputEmail1">Status Ujian Skripsi</label>
                                                                                                    <label class="rdiobox mb-1">
                                                                                                        <input <?= !empty($sidang) ? ($sidang[0]->hasil_sidang == '1' ? "checked" : '') : '' ?> disabled type="radio" name="status" value="1"><span>Disetujui tanpa perbaikan</span>
                                                                                                    </label>
                                                                                                    <label class="rdiobox mb-1">
                                                                                                        <input <?= !empty($sidang) ? ($sidang[0]->hasil_sidang == '2' ? "checked" : '') : '' ?> disabled type="radio" name="status" value="2"><span>Disetujui dengan perbaikan</span>
                                                                                                    </label>
                                                                                                    <label class="rdiobox">
                                                                                                        <input <?= !empty($sidang) ? ($sidang[0]->hasil_sidang == '3' ? "checked" : '') : '' ?> disabled type="radio" name="status" value="3"><span>Tidak disetujui/mengulang</span>
                                                                                                    </label>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="modal-footer">
                                                                                                <button class="btn ripple btn-primary" type="submit">Tandatangani</button>
                                                                                                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">Keluar</button>
                                                                                            </div>
                                                                                        </form>
                                                                                        <?php $no++; ?>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        <?php
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