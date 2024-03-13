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
                        <h6 class="card-title mb-0">Berkas Seminar Proposal</h6>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>
                    <p class="tx-12 tx-gray-500 mb-2">Silahkan unduh berkas sesuai dengan kebutuhan anda.</p>
                </div>
                <div class="card-body">
                    <div class="row mt-3">
                        <div class="col-md-8 col-lg-8 col-xl-8">
                            <label class="form-label">Berkas Bimbingan Proposal</label>
                            <p class="tx-12 tx-gray-500 pt-0"></p>
                        </div>
                        <div class="col-md-4 col-lg-4 col-xl-4">
                            <div class="btn-list">
                                <a aria-controls="multiCollapseExample1" aria-expanded="false" class="btn ripple btn-light plus float-right" href=".multi-collapse" data-bs-toggle="collapse" role="button">Detail Berkas</a>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="collapse multi-collapse" id="multiCollapseExample1">
                                <form action="<?= base_url() ?>update_pass" method="POST" enctype="multipart/form-data">
                                    <?= csrf_field() ?>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="border-0">Pembimbing 1</th>
                                                <th class="border-0">Pembimbing 2</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="border-0"><?= $dosen_pembimbing_1[0]->gelardepan . " " . $dosen_pembimbing_1[0]->nama . ", " . $dosen_pembimbing_1[0]->gelarbelakang ?></td>
                                                <td class="border-0"><?= $dosen_pembimbing_2[0]->gelardepan . " " . $dosen_pembimbing_2[0]->nama . ", " . $dosen_pembimbing_2[0]->gelarbelakang ?></td>
                                            </tr>
                                            <tr>
                                                <td class="border-0">
                                                    <a href="<?= base_url() ?>form_bimbingan_proposal/<?= session()->get('ses_id') ?>/<?= $dosen_pembimbing_1[0]->nip ?>"><button class="btn btn-primary btn-sm" type="button">Unduh</button></a>
                                                </td>
                                                <td class="border-0">
                                                    <a href="<?= base_url() ?>form_bimbingan_proposal/<?= session()->get('ses_id') ?>/<?= $dosen_pembimbing_2[0]->nip ?>"><button class="btn btn-primary btn-sm" type="button">Unduh</button></a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <?php
                    $datadaftarseminar = $db->query("SELECT * FROM tb_pendaftar_sidang a LEFT JOIN tb_jadwal_sidang b ON a.`id_jadwal`=b.`id_jadwal` WHERE b.`jenis_sidang`='seminar proposal' AND a.`nim`='" . session()->get('ses_id') . "'")->getResult();
                    if (!empty($datadaftarseminar)) {
                    ?>
                        <div class="row mt-4">
                            <div class="col-md-8 col-lg-8 col-xl-8">
                                <label class="form-label">Berkas Berita Acara Seminar Proposal</label>
                                <p class="tx-12 tx-gray-500 pt-0">Berkas Berita Acara Pembimbing 1, 2 dan Penguji 1, 2 dan 3</p>
                            </div>
                            <?php
                            if (empty($penguji_1) && empty($penguji_2) && empty($penguji_2)) { ?>
                                <div class="col-md-4 col-lg-4 col-xl-4">
                                    <span class="text-danger ms-2">Penguji Belum Ditentukan</span>
                                </div>
                            <?php } else {
                            ?>
                                <div class="col-md-4 col-lg-4 col-xl-4">
                                    <div class="btn-list">
                                        <a aria-controls="multiCollapseExample2" aria-expanded="false" class="btn ripple btn-light plus float-right" href=".multi-collapse2" data-bs-toggle="collapse" role="button">Detail Berkas</a>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-12">
                                        <div class="collapse multi-collapse2" id="multiCollapseExample2">
                                            <?php
                                            function tgl_indo($tanggal)
                                            {
                                                $bulan = array(
                                                    1 =>   'Januari',
                                                    'Februari',
                                                    'Maret',
                                                    'April',
                                                    'Mei',
                                                    'Juni',
                                                    'Juli',
                                                    'Agustus',
                                                    'September',
                                                    'Oktober',
                                                    'November',
                                                    'Desember'
                                                );
                                                $pecahkan = explode('-', $tanggal);

                                                // variabel pecahkan 0 = tanggal
                                                // variabel pecahkan 1 = bulan
                                                // variabel pecahkan 2 = tahun

                                                return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
                                            }
                                            function getHari($date)
                                            {
                                                $datetime = DateTime::createFromFormat('Y-m-d', $date);
                                                $day = $datetime->format('l');
                                                switch ($day) {
                                                    case 'Sunday':
                                                        $hari = 'Minggu';
                                                        break;
                                                    case 'Monday':
                                                        $hari = 'Senin';
                                                        break;
                                                    case 'Tuesday':
                                                        $hari = 'Selasa';
                                                        break;
                                                    case 'Wednesday':
                                                        $hari = 'Rabu';
                                                        break;
                                                    case 'Thursday':
                                                        $hari = 'Kamis';
                                                        break;
                                                    case 'Friday':
                                                        $hari = 'Jum\'at';
                                                        break;
                                                    case 'Saturday':
                                                        $hari = 'Sabtu';
                                                        break;
                                                    default:
                                                        $hari = 'Tidak ada';
                                                        break;
                                                }
                                                return $hari;
                                            }
                                            $id_pendaftar = $db->query("SELECT * FROM tb_pendaftar_sidang a LEFT JOIN tb_jadwal_sidang b ON a.`id_jadwal`=b.`id_jadwal` WHERE b.`jenis_sidang`='seminar proposal' AND nim='" . session()->get('ses_id') . "' ORDER BY create_at DESC LIMIT 1")->getResult()[0]->id_pendaftar;
                                            $jadwal_sidang = $db->query("SELECT * FROM tb_pendaftar_sidang WHERE id_pendaftar='$id_pendaftar'")->getResult();
                                            ?>
                                            <p class="tx-12 tx-gray-500 pt-0 mb-0">Dilaksanakan Pada :</p>
                                            <div class="row col-md-12 col-lg-6 col-xl-6">
                                                <div class="col">
                                                    Hari / Tanggal
                                                </div>
                                                <div class="col">
                                                    : <?= $id_pendaftar != NULL && $jadwal_sidang[0]->waktu_sidang != '0000-00-00 00:00:00' && $jadwal_sidang[0]->waktu_sidang != NULL ? getHari(date('Y-m-d', strtotime($jadwal_sidang[0]->waktu_sidang))) . ', ' . tgl_indo(date('Y-m-d', strtotime($jadwal_sidang[0]->waktu_sidang))) : '' ?>
                                                </div>
                                            </div>
                                            <div class="row col-md-12 col-lg-6 col-xl-6">
                                                <div class="col">
                                                    Pukul
                                                </div>
                                                <div class="col">
                                                    : <?= $id_pendaftar != NULL && $jadwal_sidang[0]->waktu_sidang != '0000-00-00 00:00:00' && $jadwal_sidang[0]->waktu_sidang != NULL ? date('H:i:s', strtotime($jadwal_sidang[0]->waktu_sidang)) . ' WIB' : '' ?>
                                                </div>
                                            </div>
                                            <div class="row  col-md-12 col-lg-6 col-xl-6">
                                                <div class="col">
                                                    Tempat
                                                </div>
                                                <div class="col">
                                                    : <?= $id_pendaftar != NULL && $jadwal_sidang[0]->ruang_sidang != NULL ? $jadwal_sidang[0]->ruang_sidang : '' ?>
                                                </div>
                                            </div>
                                            <p class="tx-12 tx-gray-500 pt-1 mb-0">Lulus tanpa perbaikan, lulus dengan perbaikan dan Tidak lulus/mengulang</p>
                                            <div class="form-group mb-0 justify-content-end">
                                                <div class="checkbox">
                                                    <div class="custom-checkbox custom-control">
                                                        <input type="checkbox" onclick="return false;" <?= $id_pendaftar != NULL ? $jadwal_sidang[0]->hasil_sidang == 1 ? 'checked' : '' : '' ?> data-checkboxes="mygroup" class="custom-control-input" id="checkbox-2">
                                                        <label for="checkbox-2" class="custom-control-label mt-1">Lulus tanpa perbaikan</label>
                                                    </div>
                                                    <div class="custom-checkbox custom-control">
                                                        <input type="checkbox" onclick="return false;" <?= $id_pendaftar != NULL ? $jadwal_sidang[0]->hasil_sidang == 2 ? 'checked' : '' : '' ?> data-checkboxes="mygroup" class="custom-control-input" id="checkbox-3">
                                                        <label for="checkbox-3" class="custom-control-label mt-1">Lulus dengan perbaikan</label>
                                                    </div>
                                                    <div class="custom-checkbox custom-control">
                                                        <input type="checkbox" onclick="return false;" <?= $id_pendaftar != NULL ? $jadwal_sidang[0]->hasil_sidang == 3 ? 'checked' : '' : '' ?> data-checkboxes="mygroup" class="custom-control-input" id="checkbox-4">
                                                        <label for="checkbox-4" class="custom-control-label mt-1">Tidak Lulus/mengulang</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row mt-3">
                                                <div class="col-xl-12">
                                                    <table class="table">
                                                        <tr>
                                                            <th style="text-align: center; vertical-align: middle;"><span>Pembimbing 1</span></th>
                                                            <th style="text-align: center; vertical-align: middle;"><span>Pembimbing 2</span></th>
                                                            <th style="text-align: center; vertical-align: middle;"><span>Penguji 1</span></th>
                                                            <th style="text-align: center; vertical-align: middle;"><span>Penguji 2</span></th>
                                                            <th style="text-align: center; vertical-align: middle;"><span>Penguji 3</span></th>
                                                        </tr>
                                                        <tr>
                                                            <?php
                                                            $sts_penguji_1 = $db->query("SELECT * FROM tb_berita_acara WHERE id_pendaftar = '$id_pendaftar' AND sebagai='penguji 1' AND nim='" . session()->get('ses_id') . "' AND jenis_sidang='proposal'")->getResult();
                                                            $sts_penguji_2 = $db->query("SELECT * FROM tb_berita_acara WHERE id_pendaftar = '$id_pendaftar' AND sebagai='penguji 2' AND nim='" . session()->get('ses_id') . "' AND jenis_sidang='proposal'")->getResult();
                                                            $sts_penguji_3 = $db->query("SELECT * FROM tb_berita_acara WHERE id_pendaftar = '$id_pendaftar' AND sebagai='penguji 3' AND nim='" . session()->get('ses_id') . "' AND jenis_sidang='proposal'")->getResult();
                                                            $sts_pembimbing_1 = $db->query("SELECT * FROM tb_berita_acara WHERE id_pendaftar = '$id_pendaftar' AND sebagai='pembimbing 1' AND nim='" . session()->get('ses_id') . "' AND jenis_sidang='proposal'")->getResult();
                                                            $sts_pembimbing_2 = $db->query("SELECT * FROM tb_berita_acara WHERE id_pendaftar = '$id_pendaftar' AND sebagai='pembimbing 2' AND nim='" . session()->get('ses_id') . "' AND jenis_sidang='proposal'")->getResult();
                                                            $true = '<span class="text-success ms-2">Ditandatangani</span>';
                                                            $false = '<span class="text-danger ms-2">Belum Ditandatangani</span>';

                                                            // dd($id_pendaftar);
                                                            ?>
                                                            <td style="text-align: center; vertical-align: middle;"><?= $dosen_pembimbing_1[0]->gelardepan . " " . $dosen_pembimbing_1[0]->nama . ", " . $dosen_pembimbing_1[0]->gelarbelakang ?><p class="mb-0 tx-11 text-muted">
                                                                    <?= !empty($sts_pembimbing_1) ? $sts_pembimbing_1[0]->status == 'ditandatangani' ? $true : $false : $false ?>
                                                                </p>
                                                            </td>
                                                            <td style="text-align: center; vertical-align: middle;"><?= $dosen_pembimbing_2[0]->gelardepan . " " . $dosen_pembimbing_2[0]->nama . ", " . $dosen_pembimbing_2[0]->gelarbelakang ?><p class="mb-0 tx-11 text-muted">
                                                                    <?= !empty($sts_pembimbing_2) ? $sts_pembimbing_2[0]->status == 'ditandatangani' ? $true : $false : $false ?>
                                                                </p>
                                                            </td>
                                                            <td style="text-align: center; vertical-align: middle;"><?= !empty($penguji_1) ? $penguji_1[0]->gelardepan . " " . $penguji_1[0]->nama . ", " . $penguji_1[0]->gelarbelakang : '' ?><p class="mb-0 tx-11 text-muted">
                                                                    <?= !empty($sts_penguji_1) ? $sts_penguji_1[0]->status == 'ditandatangani' ? $true : $false : $false ?>
                                                                </p>
                                                            </td>
                                                            <td style="text-align: center; vertical-align: middle;"><?= !empty($penguji_2) ? $penguji_2[0]->gelardepan . " " . $penguji_2[0]->nama . ", " . $penguji_2[0]->gelarbelakang : '' ?><p class="mb-0 tx-11 text-muted">
                                                                    <?= !empty($sts_penguji_2) ? $sts_penguji_2[0]->status == 'ditandatangani' ? $true : $false : $false ?>
                                                                </p>
                                                            </td>
                                                            <td style="text-align: center; vertical-align: middle;"><?= !empty($penguji_3) ? $penguji_3[0]->gelardepan . " " . $penguji_3[0]->nama . ", " . $penguji_3[0]->gelarbelakang : '' ?><p class="mb-0 tx-11 text-muted">
                                                                    <?= !empty($sts_penguji_3) ? $sts_penguji_3[0]->status == 'ditandatangani' ? $true : $false : $false ?>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <p>
                                                <a href="<?= base_url() ?>berita_acara_proposal/<?= session()->get('ses_id') ?>/<?= $id_pendaftar ?>"><button class="btn btn-primary" type="button">Unduh</button></a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>