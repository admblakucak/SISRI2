<style>
    .cs {
        color: red;
    }
</style>
<?php

use CodeIgniter\Images\Image;
?>

<?= $this->extend('Template/content') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row mt-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <h6 class="card-title mb-0">Beranda Mahasiswa</h6>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>
                    <p class="tx-12 tx-gray-500 mb-2">Tahap Mahasiswa dalam Melakukan Skripsi</p>
                </div>
                <!-- <div class="row mt-3">
                    <div class="vtimeline">
                        <div class="timeline-wrapper timeline-wrapper-primary">
                            <div class="timeline-badge success"><i class="las la-folder-open"></i> </div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h6 class="timeline-title">Ajukan Topik dan Dosen Pembimbing</h6>
                                </div>
                                <div class="timeline-body">
                                    <table>
                                        <tr>
                                            <td>Topik</td>
                                            <td>:</td>
                                            <td>peramalan</td>
                                        </tr>
                                        <tr>
                                            <td>Judul</td>
                                            <td>:</td>
                                            <td>produksi sangkar burung</td>
                                        </tr>
                                        <tr>
                                            <td>Pembimbing 1</td>
                                            <td>:</td>
                                            <td>tajuddin</td>
                                            <td>
                                                <p class="cs">Menunggu</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Pembimbing 2</td>
                                            <td>:</td>
                                            <td>Amin</td>
                                            <td>
                                                <p class="cs">Menunggu</p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="timeline-footer d-flex align-items-center flex-wrap">
                                    <span class="ms-auto"><i class="fe fe-calendar text-muted me-1"></i>19
                                        Oct 2019</span>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-wrapper timeline-inverted timeline-wrapper-secondary">
                            <div class="timeline-badge"><i class="las la-file"></i></div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h6 class="timeline-title">Bimbingan Proposal</h6>
                                </div>
                                <div class="timeline-body">
                                    <div class="row row-sm main-content-app mb-4">
                                        <div class="col-xl-4 col-lg-8 col-sm-8">
                                            <div class="card">
                                                <div class="main-content-left main-content-left-chat">
                                                    <div class="main-chat-list" id="ChatList">
                                                        <div class="media new">
                                                            <div class="main-img-user online">
                                                                <img alt="" src="../../assets/img/faces/5.jpg">
                                                            </div>
                                                            <div class="media-body">
                                                                <div class="media-contact-name">
                                                                    <span>Dr. Budi dwi Satoto, S.Kom., MT.</span>
                                                                </div>
                                                                <p>Pembimbing 1</p>
                                                            </div>
                                                        </div>
                                                        <div class="media new">
                                                            <div class="main-img-user online">
                                                                <img alt="" src="../../assets/img/faces/5.jpg">
                                                            </div>
                                                            <div class="media-body">
                                                                <div class="media-contact-name">
                                                                    <span>Ach Dafid, ST., MT.</span>
                                                                </div>
                                                                <p>Pembimbing 2</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-8 col-lg-7">
                                            <div class="card">
                                                <a class="main-header-arrow" href="" id="ChatBodyHide"><i class="icon ion-md-arrow-back"></i></a>
                                                <div class="main-content-body main-content-body-chat">
                                                    <div class="main-chat-header">
                                                        <div class="main-img-user"><img alt="" src="../../assets/img/faces/9.jpg"></div>
                                                        <div class="main-chat-msg-name">
                                                            <h6>Dr. Budi Dwi Satoto, S.Kom., MT.</h6><small>Pembimbing 1</small>
                                                        </div>
                                                    </div>
                                                    <div class="main-chat-body" id="ChatBody">
                                                        <div class="content-inner">
                                                            <label class="main-chat-time"></label>
                                                            <div class="media flex-row-reverse">
                                                                <div class="main-img-user online"><img alt="" src="../../assets/img/faces/9.jpg"></div>
                                                                <div class="media-body">
                                                                    <div class="main-msg-wrapper right">
                                                                        Mohon maaf bapak untuk jadwal bimbingan minggu depan hari apa bapak?
                                                                    </div>
                                                                    <div>
                                                                        <span>9:48 am</span> <a href=""><i class="icon ion-android-more-horizontal"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="media">
                                                                <div class="main-img-user online"><img alt="" src="../../assets/img/faces/6.jpg"></div>
                                                                <div class="media-body">
                                                                    <div class="main-msg-wrapper left">
                                                                        Hari Selasa ya jam 8
                                                                    </div>
                                                                    <div>
                                                                        <span>9:32 am</span> <a href=""><i class="icon ion-android-more-horizontal"></i></a>
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
                                <div class="timeline-footer d-flex align-items-center flex-wrap">
                                    <i class="fe fe-heart  text-muted me-1"></i>
                                    <span>25</span>
                                    <span class="ms-auto"><i class="fe fe-calendar text-muted me-1"></i>10th
                                        Oct 2019</span>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-wrapper timeline-wrapper-info">
                            <div class="timeline-badge"><i class="las la-user-check"></i></div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h6 class="timeline-title">Status Perizinan Mengikuti Sidang</h6>
                                </div>
                                <div class="timeline-body">
                                    <table>
                                        <tr>
                                            <td>Pembimbing 1</td>
                                            <td class="row mt-3">
                                                <p class="csDI">Dizinkan</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Pembimbing 2</td>
                                            <td class="row mt-3">
                                                <p class="csDI">Dizinkan</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Koorprodi</td>
                                            <td class="row mt-3">
                                                <p class="csDI">Dizinkan</p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Jadwal daftar seminar</td>
                                            <td class="row mt-3">
                                                <p class="csDI">Dibuka</p>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="timeline-footer d-flex align-items-center flex-wrap">
                                    <i class="fe fe-heart  text-muted me-1"></i>
                                    <span>19</span>
                                    <span class="ms-auto"><i class="fe fe-calendar text-muted me-1"></i>5th
                                        Oct 2019</span>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-wrapper timeline-inverted timeline-wrapper-danger">
                            <div class="timeline-badge success"><i class="las la-calendar-alt"></i></div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h6 class="timeline-title">Jadwal Pelaksanaan Seminar Proposal</h6>
                                </div>
                                <div class="timeline-body">
                                    <table class="table">
                                        <tr>
                                            <th>Penguji 1</th>
                                            <th>Penguji 2</th>
                                            <th>Penguji 3</th>
                                            <th>Tanggal</th>
                                            <th>Jam</th>
                                            <th>Ruangan</th>
                                        </tr>
                                        <tr>
                                            <td>Dr. Budi Dwi Satoto, S.Kom., MT.</td>
                                            <td>Ach. Dafid, ST., MT.</td>
                                            <td>Khozaimi, S.Kom., M.Kom.</td>
                                            <td>20/09/2022</td>
                                            <td>09:45 wib</td>
                                            <td>F03</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="timeline-footer d-flex align-items-center flex-wrap">
                                    <i class="fe fe-heart  text-muted me-1"></i>
                                    <span>19</span>
                                    <span class="ms-auto"><i class="fe fe-calendar text-muted me-1"></i>27th
                                        Sep 2017</span>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-wrapper timeline-wrapper-success">
                            <div class="timeline-badge"><i class="las la-clipboard"></i></div>
                            <div class="timeline-panel">
                                <div class="timeline-heading">
                                    <h6 class="timeline-title">Revisi dan Status Revisi</h6>
                                </div>
                                <div class="timeline-body">
                                    <div class="row row-sm main-content-app mb-4">
                                        <div class="col-xl-4 col-lg-8 col-sm-8">
                                            <div class="card">
                                                <div class="main-content-left main-content-left-chat">
                                                    <div class="main-chat-list" id="ChatList">
                                                        <div class="media new">
                                                            <div class="main-img-user online">
                                                                <img alt="" src="../../assets/img/faces/5.jpg">
                                                            </div>
                                                            <div class="media-body">
                                                                <div class="media-contact-name">
                                                                    <span>Dr. Budi dwi Satoto, S.Kom., MT.</span>
                                                                </div>
                                                                <p>Pembimbing 1</p>
                                                            </div>
                                                        </div>
                                                        <div class="media new">
                                                            <div class="main-img-user online">
                                                                <img alt="" src="../../assets/img/faces/5.jpg">
                                                            </div>
                                                            <div class="media-body">
                                                                <div class="media-contact-name">
                                                                    <span>Ach Dafid, ST., MT.</span>
                                                                </div>
                                                                <p>Pembimbing 2</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-8 col-lg-7">
                                            <div class="card">
                                                <a class="main-header-arrow" href="" id="ChatBodyHide"><i class="icon ion-md-arrow-back"></i></a>
                                                <div class="main-content-body main-content-body-chat">
                                                    <div class="main-chat-header">
                                                        <div class="main-img-user"><img alt="" src="../../assets/img/faces/9.jpg"></div>
                                                        <div class="main-chat-msg-name">
                                                            <h6>Dr. Budi Dwi Satoto, S.Kom., MT.</h6><small>Pembimbing 1</small>
                                                        </div>
                                                    </div>
                                                    <div class="main-chat-body" id="ChatBody">
                                                        <div class="content-inner">
                                                            <label class="main-chat-time"></label>
                                                            <div class="media flex-row-reverse">
                                                                <div class="main-img-user online"><img alt="" src="../../assets/img/faces/9.jpg"></div>
                                                                <div class="media-body">
                                                                    <div class="main-msg-wrapper right">
                                                                        Mohon maaf bapak untuk jadwal bimbingan minggu depan hari apa bapak?
                                                                    </div>
                                                                    <div>
                                                                        <span>9:48 am</span> <a href=""><i class="icon ion-android-more-horizontal"></i></a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="media">
                                                                <div class="main-img-user online"><img alt="" src="../../assets/img/faces/6.jpg"></div>
                                                                <div class="media-body">
                                                                    <div class="main-msg-wrapper left">
                                                                        Hari Selasa ya jam 8
                                                                    </div>
                                                                    <div>
                                                                        <span>9:32 am</span> <a href=""><i class="icon ion-android-more-horizontal"></i></a>
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
                                <div class="timeline-footer d-flex align-items-center flex-wrap">
                                    <i class="fe fe-heart  text-muted me-1"></i>
                                    <span>25</span>
                                    <span class="ms-auto"><i class="fe fe-calendar text-muted me-1"></i>25th
                                        Sep 2017</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>