<?php

use CodeIgniter\Images\Image;
?>
<?= $this->extend('Template/content') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row mt-3">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 grid-margin">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mg-b-0">Data Bimbingan</h4>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>
                    <p class="tx-12 tx-gray-500 mb-2">Data Bimbingan Revisi Skripsi Mahasiswa</p>
                </div>
                <div class="card-body">
                    <div class="table-responsive border-top userlist-table">
                        <div class="row mt-3"></div>
                        <div class="table-responsive userlist-table">
                            <table class="table card-table table-vcenter text-nowrap mb-0" id="validasitable2">
                                <thead>
                                    <tr>
                                        <th><span>&nbsp;</span></th>
                                        <th><span>Nim</span></th>
                                        <th><span>Nama</span></th>
                                        <th><span>Jenis Kelamin</span></th>
                                        <th><span>Prodi</span></th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 0;
                                    foreach ($data_mhs_bimbingan_baru as $key) {
                                    ?>
                                        <tr>
                                            <td>
                                                <img alt="avatar" class="rounded-circle avatar-md me-2" src="<?= base_url() ?>image/<?= $key['image'] ?>">
                                            </td>
                                            <td>
                                                <?= $key['nim']; ?>
                                            </td>
                                            <td>
                                                <?= $key['nama_mhs']; ?>
                                            </td>
                                            <td>
                                                <?php if ($key['jk'] == 'P') {
                                                    echo 'Perempuan';
                                                } else {
                                                    echo 'Laki-Laki';
                                                } ?>
                                            </td>
                                            <td>
                                                <?= $key['namaunit']; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?= base_url() ?>bimbingan_revisi_skripsi_dosen/<?= $key['nim'] ?>" class="btn btn-primary btn-sm">Riwayat Bimbingan</a>
                                                    <?php
                                                    $jumlah = $key['sum_pemberitahuan'];
                                                    if ($jumlah > 0) {
                                                        echo "<a class='btn btn-danger btn-sm'>$jumlah</a>";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                    <?php
                                    foreach ($data_mhs_bimbingan as $key) {
                                        // $status_acc_revisi = $db->query("SELECT * FROM tb_acc_revisi WHERE nim ='" . $key['nim'] . "' AND nip='" . session()->get('ses_id') . "' AND jenis_sidang='skripsi'")->getResult();
                                        // if ($status_acc_revisi) {
                                        //     continue;
                                        // }
                                    ?>
                                        <tr>
                                            <td>
                                                <img alt="avatar" class="rounded-circle avatar-md me-2" src="<?= base_url() ?>image/<?= $key['image'] ?>">
                                            </td>
                                            <td>
                                                <?= $key['nim']; ?>
                                            </td>
                                            <td>
                                                <?= $key['nama_mhs']; ?>
                                            </td>
                                            <td>
                                                <?php if ($key['jk'] == 'P') {
                                                    echo 'Perempuan';
                                                } else {
                                                    echo 'Laki-Laki';
                                                } ?>
                                            </td>
                                            <td>
                                                <?= $key['namaunit']; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="<?= base_url() ?>bimbingan_revisi_skripsi_dosen/<?= $key['nim'] ?>" class="btn btn-primary btn-sm">Riwayat Bimbingan</a>
                                                    <?php
                                                    $jumlah = $key['sum_pemberitahuan'];
                                                    if ($jumlah > 0) {
                                                        echo "<a class='btn btn-danger btn-sm'>$jumlah</a>";
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php
                                        $no++;
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
<?= $this->endSection(); ?>