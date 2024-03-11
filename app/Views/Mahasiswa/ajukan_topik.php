<?php

use CodeIgniter\Images\Image;
?>
<?= $this->extend('Template/content') ?>

<?= $this->section('content') ?>

<div class="container-fluid mt-3">

    <!-- row opened-->

    <div class="card custom-card">
        <div class="row row-sm row-deck">
            <div class="col-lg-12 col-md-12">
                <div class="card-body ht-100p">
                    <div>
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title mg-b-0">Ajukan Topik</h4>
                            <i class="mdi mdi-dots-horizontal text-gray"></i>
                        </div>
                        <p class="tx-12 tx-gray-500 mb-2">Ajukan topik skripsi.</p>
                    </div>
                    <?= session()->getFlashdata('message_ajukan_topik') . "<br>"; ?>
                    <?php
                    $cek_status_sidang_skripsi = $db->query("SELECT * FROM tb_pendaftar_sidang a LEFT JOIN tb_jadwal_sidang b ON a.`id_jadwal`=b.`id_jadwal` WHERE b.`jenis_sidang`='sidang skripsi' AND a.`nim`='" . session()->get('ses_id') . "'")->getResult();
                    ?>
                    <div class="">
                        <form action="<?= base_url() ?>proses_ajukan_topik" method="POST" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="mb-4">
                                <label for="">Bidang Minat</label>
                                <select class="form-control select2" name="topik">
                                    <option selected disabled>Pilih Bidang Minat
                                    </option>

                                    <?php foreach ($topik as $key) { ?>
                                        <option value="<?= $key->idtopik ?>" <?php if ($data_pengajuan_topik[0]->id_topik == $key->idtopik) {
                                                                                    echo "selected";
                                                                                } else {
                                                                                    // if ($stsp1 > 0 || $stsp2 > 0) {
                                                                                    //     echo "disabled";
                                                                                    // }
                                                                                    if (count($cek_status_sidang_skripsi) > 0) {
                                                                                        echo "disabled";
                                                                                    }
                                                                                } ?>>
                                            <?= $key->nama ?>
                                        </option>
                                    <?php
                                    } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Judul</label>
                                <input type="teks" <?php
                                                    // if ($stsp1 > 0 || $stsp2 > 0) {
                                                    //     echo "readonly";
                                                    // }
                                                    if (count($cek_status_sidang_skripsi) > 0) {
                                                        echo "readonly";
                                                    }
                                                    ?> name="judul_topik" class="form-control" id="exampleInput" placeholder="Isikan Judul Skripsi Anda" value=<?php if (!empty($data_pengajuan_topik[0]->judul_topik)) {
                                                                                                                                                                    echo "'" . $data_pengajuan_topik[0]->judul_topik . "'";
                                                                                                                                                                } ?>>
                            </div>
                            <div class="row">
                                <div class="col-sm-7 col-md-6 col-lg-4">
                                    <label for="">Upload Berkas Proposal Skripsi</label>
                                    <div class="input-group file-browser">
                                        <input type="text" class="form-control border-right-0 browse-file" placeholder="Upload Proposal Minimal Bab 1" name="ket_berkas" <?php if (!empty($data_pengajuan_topik[0]->berkas)) {
                                                                                                                                                                                echo "value='" . $data_pengajuan_topik[0]->berkas . "'";
                                                                                                                                                                            } ?> readonly>
                                        <label class="input-group-btn">
                                            <span class="btn btn-default">
                                                Browse <input type="file" name="berkas" class="d-none" multiple>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $validasi_daftar_sidang = $db->query("SELECT * FROM tb_pendaftar_sidang WHERE nim='" . session()->get('ses_id') . "'")->getResult();
                            if (!empty($validasi_daftar_sidang)) { ?>
                                <button type="submit" class="btn btn-primary mt-3 mb-0" disabled>Update</button>

                            <?php } else { ?>

                                <button type="submit" class="btn btn-primary mt-3 mb-0">Update</button>
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if (!empty($data_pengajuan_topik[0]->judul_topik)) { ?>
        <div class="row row-sm row-deck">
            <div class="col-lg-6 col-md-6">
                <div class="card custom-card">
                    <div class="card-body ht-100p">
                        <div>
                            <div class="d-flex justify-content-between">
                                <h4 class="card-title mg-b-0">Ajukan Pembimbing 1</h4>
                                <i class="mdi mdi-dots-horizontal text-gray"></i>
                            </div>
                            <p class="tx-12 tx-gray-500 mb-2">Anda tidak dapat mengajukan dosen pembimbing 1 berulang kali kecuali pengajuan telah ditolak.</p>
                            <?= session()->getFlashdata('message_pem1'); ?>
                            <div class="container">
                                <div class="row">
                                    <div class="col-9">
                                        <form action="<?= base_url() ?>ajukan_dospem_1" method="POST" enctype="multipart/form-data">
                                            <?= csrf_field() ?>
                                            <div class="mt-2">
                                                <select class="form-control select2" name="nip">
                                                    <option label="Pilih Pembimbing 1" selected disabled>Pilih Pembimbing 1
                                                    </option>
                                                    <?php
                                                    foreach ($dosen_p1 as $key1) {
        
                                                    ?>
                                                        <option value="<?= $key1->nip_dos ?>">
                                                            <?= $key1->namaunit . ' - ' . $key1->nip_dos . ' - ' . $key1->gelardepan . ' ' . $key1->nama . ', ' . $key1->gelarbelakang ?>
                                                            <?php
                                                            if (count($jumlah_p1) > 0) {
                                                                if ($jumlah_p1[0]->jumlah >= $jumlah_p1[0]->kuota) {
                                                                    echo '<a class="text-danger"> - (Penuh)</a>';
                                                                } else {
                                                                    echo '<a class="text-success"> - (' . $jumlah_p1[0]->jumlah . ' Bimbingan)</a>';
                                                                }
                                                            } else {
                                                                echo '<a class="text-success"> - (0 Bimbingan)</a>';
                                                            }
                                                            ?>
                                                        </option>
                                                    <?php
                                                    } ?>
                                                </select>
                                            </div>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn mt-2 btn-main-primary btn-block <?php if ($ststbl1 > 0) {
                                                                                                                echo " disabled";
                                                                                                            } ?>">Ajukan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0 customers mt-1">
                            <div class="list-group list-lg-group list-group-flush">
                                <?php foreach ($pengajuan_pem1 as $pem1) { ?>
                                    <div class="list-group-item list-group-item-action br-t-1" href="#" data-bs-container="body" data-bs-toggle="popover" data-bs-popover-color="default" data-bs-placement="right" title="" data-bs-content="<?= $pem1->pesan ?>" data-bs-original-title="PESAN">
                                        <div class="media mt-0">
                                            <div class="media-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="mt-0">
                                                        <h5 class="mb-1 tx-15"><?= $pem1->gelardepan . ' ' . $pem1->nama . ', ' . $pem1->gelarbelakang ?></h5>
                                                        <p class="mb-0 tx-13 text-muted"><?= $pem1->namaunit ?></p>
                                                    </div>
                                                    <span class="ms-auto fs-16 mt-2">
                                                        <?php if ($pem1->status_pengajuan == 'diterima') { ?>
                                                            <span class="text-success ms-2">Diterima</span>
                                                        <?php
                                                        } elseif ($pem1->status_pengajuan == 'menunggu') { ?>
                                                            <span class="text-warning ms-2">Menunggu</span> |
                                                            <a href="<?= base_url() ?>batal_ajukan_dospem_1/<?= $pem1->id_pengajuan_pembimbing ?>" class="btn btn-danger btn-sm"> Batalkan</a>
                                                        <?php
                                                        } else { ?>
                                                            <span class="text-danger ms-2">Ditolak</span>
                                                        <?php
                                                        } ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="card custom-card">
                    <div class="card-body ht-100p">
                        <div>
                            <div class="d-flex justify-content-between">
                                <h4 class="card-title mg-b-0">Ajukan Pembimbing 2</h4>
                                <i class="mdi mdi-dots-horizontal text-gray"></i>
                            </div>
                            <p class="tx-12 tx-gray-500 mb-2">Anda tidak dapat mengajukan dosen pembimbing 2 berulang kali kecuali pengajuan telah ditolak.</p>
                            <?= session()->getFlashdata('message_pem2'); ?>
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-9">
                                        <form action="<?= base_url() ?>ajukan_dospem_2" method="POST" enctype="multipart/form-data">
                                            <?= csrf_field() ?>
                                            <div class="mt-2">
                                                <select class="form-control select2" name="nip">
                                                    <option label="Pilih Pembimbing 1" selected disabled>Pilih Pembimbing 2
                                                    </option>
                                                    <?php foreach ($dosen_p2 as $key2) {
                                                        $jumlah_p2 = $db->query("SELECT * FROM tb_jumlah_pembimbing WHERE nip='$key2->nip_dos' AND sebagai='pembimbing 2'")->getResult();
                                                    ?>
                                                        <option value="<?= $key2->nip_dos ?>">
                                                            <?= $key2->namaunit . ' - ' . $key2->nip_dos . ' - ' . $key2->gelardepan . ' ' . $key2->nama . ', ' . $key2->gelarbelakang ?>
                                                            <?php
                                                            if (count($jumlah_p2) > 0) {
                                                                if ($jumlah_p2[0]->jumlah >= $jumlah_p2[0]->kuota) {
                                                                    echo '<a class="text-danger"> - (Penuh)</a>';
                                                                } else {
                                                                    echo '<a class="text-success"> - (' . $jumlah_p2[0]->jumlah . ' Bimbingan)</a>';
                                                                }
                                                            } else {
                                                                echo '<a class="text-success"> - (0 Bimbingan)</a>';
                                                            }
                                                            ?>
                                                        </option>
                                                    <?php
                                                    } ?>
                                                </select>
                                            </div>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn mt-2 btn-main-primary btn-block <?php if ($ststbl2 > 0) {
                                                                                                                echo " disabled";
                                                                                                            } ?>">Ajukan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0 customers mt-1">
                            <div class="list-group list-lg-group list-group-flush">
                                <?php foreach ($pengajuan_pem2 as $pem2) { ?>
                                    <div class="list-group-item list-group-item-action br-t-1" href="#" data-bs-container="body" data-bs-toggle="popover" data-bs-popover-color="default" data-bs-placement="left" title="" data-bs-content="<?= $pem2->pesan ?>" data-bs-original-title="PESAN">
                                        <div class="media mt-0">
                                            <div class="media-body">
                                                <div class="d-flex align-items-center">
                                                    <div class="mt-0">
                                                        <h5 class="mb-1 tx-15"><?= $pem2->gelardepan . ' ' . $pem2->nama . ', ' . $pem2->gelarbelakang ?></h5>
                                                        <p class="mb-0 tx-13 text-muted"><?= $pem2->namaunit ?></p>
                                                    </div>
                                                    <span class="ms-auto fs-16 mt-2">
                                                        <?php if ($pem2->status_pengajuan == 'diterima') { ?>
                                                            <span class="text-success ms-2">Diterima</span>
                                                        <?php
                                                        } elseif ($pem2->status_pengajuan == 'menunggu') { ?>
                                                            <span class="text-warning ms-2">Menunggu</span>
                                                            <a href="<?= base_url() ?>batal_ajukan_dospem_2/<?= $pem2->id_pengajuan_pembimbing ?>" class="btn btn-danger btn-sm"> Batalkan</a>
                                                        <?php
                                                        } else { ?>
                                                            <span class="text-danger ms-2">Ditolak</span>
                                                        <?php
                                                        } ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<?= $this->endSection(); ?>