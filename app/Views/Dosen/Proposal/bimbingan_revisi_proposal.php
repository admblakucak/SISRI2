<?php

use CodeIgniter\Images\Image;
?>
<?= $this->extend('Template/content') ?>

<?= $this->section('content') ?>
<div class="container-fluid">
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">Bimbingan</h4><span class="text-muted mt-1 tx-13 ms-2 mb-0">/ Revisi Proposal</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
    <div class="modal" id="modaladd">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">Masukkan Dokumen Bimbingan Proposal</h6><button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <form action="<?= base_url() ?>tambah_bimbingan_revisi_proposal_dosen" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <input type="hidden" name="nim" value="<?= $nim ?>">
                        <div class="form-group">
                            <label for="">Upload File <small>(.pdf, .doc, .docx, .ppt, .pptx, .xls, .xlsx, .csv, .jpg, .jpeg, .png, .zip)</small></label>
                            <div class="input-group file-browser">
                                <input type="text" class="form-control border-right-0 browse-file" placeholder="-" name="ket_berkas" readonly>
                                <label class="input-group-btn">
                                    <span class="btn btn-default">
                                        Browse <input type="file" name="berkas" class="d-none" multiple>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="">Keterangan/Pesan</label>
                            <textarea name="keterangan" class="ckeditor" id="ckeditor" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn ripple btn-primary" type="submit">Kirim</button>
                        <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">Keluar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- row -->
    <div class="row row-sm main-content-app mb-4">
        <div class="col-xl-auto col-lg-auto col-sm-auto">
            <div class="card">
                <div class="main-content-left main-content-left-chat">
                    <div class="main-chat-list" id="ChatList">
                        <div class="row p-3">
                            <div class="col-auto">
                                <a><b>Anda Sebagai</b> : <?= $sebagai ?></a>
                            </div>
                            <?php if ($sebagai == 'Penguji 1' || $sebagai == 'Penguji 2' || $sebagai == 'Penguji 3') { ?>
                                <div class="col-auto">
                                    <?php if ($status_acc_revisi == NULL) { ?>
                                        <!-- Button trigger modal -->
                                        <button class="btn btn-sm btn-warning" type="button" data-toggle="modal" data-target="#exampleModal" data-bs-target="#modalConfirm" data-bs-toggle="modal">ACC REVISI</button>
                                        <div class="modal" id="modalConfirm">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content modal-content-demo">
                                                    <div class="modal-header">
                                                        <h6 class="modal-title">ACC Revisi</h6><button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                                                    </div>


                                                    <div class="modal-body">
                                                        Apakah anda yakin ingin ACC Revisi mahasiswa atas nama <b><?= $data_mhs->nama ?></b>?
                                                        <p class="mt-3"></p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form action="<?= base_url() ?>acc_revisi_proposal_dosen" method="POST" enctype="multipart/form-data">
                                                            <?= csrf_field() ?>
                                                            <input type="hidden" value="<?= $nim ?>" name="nim">
                                                            <input type="hidden" value="<?= $sebagai ?>" name="sebagai">
                                                            <button class="btn ripple btn-success" type="submit">ACC</button>
                                                            <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <span class='text-success ms-3'>Revisi Telah di ACC</span>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12 col-lg-12 col-sm-12">
            <?= session()->getFlashdata('message') != NULL ? session()->getFlashdata('message') . "<br>" : "" ?>
            <div class="card">
                <div class="main-content-body-show main-content-body-chat-show">
                    <div class="main-chat-header">
                        <div class="main-img-user"><img alt="" src="<?= base_url() ?>/image/<?= $data_mhs->image != NULL ? $data_mhs->image : 'Profile_Default.png' ?>"></div>
                        <div class="main-chat-msg-name">
                            <h6><?= $data_mhs->nama ?></h6><small><?= $nim ?></small>
                        </div>
                    </div><!-- main-chat-header -->
                    <div class="main-chat-body" id="ChatBody">
                        <div class="content-inner">
                            <script>
                                $(document).ready(function() {
                                    setInterval(function() {
                                        $.get("<?= base_url() . "reload_m_bimbingan_revisi_proposal_dosen/$nim" ?>", function(result) {
                                            $('#latestData').html(result);
                                        });
                                    }, 1000);
                                });
                            </script>
                            <div id="latestData">
                            </div>
                            <div class="modal" id="modaldel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content modal-content-demo">
                                        <div class="modal-header">
                                            <h6 class="modal-title">Hapus Bimbingan</h6><button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <form action="<?= base_url() ?>hapus_bimbingan_revisi_proposal_dosen" method="POST" enctype="multipart/form-data">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id_bimbingan" value="" class="id_bimbingan" />
                                            <input type="hidden" name="nim" value="" class="nim">
                                            <div class="modal-body">
                                                Apakah anda yakin ingin menghapus <b><a class="pokok_bimbingan"></a></b> ini ?
                                                <p class="mt-3 keterangan"></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn ripple btn-danger" type="submit">Hapus</button>
                                                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">Keluar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal" id="modalrev">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content modal-content-demo">
                                        <div class="modal-header">
                                            <h6 class="modal-title">Masukkan Dokumen Bimbingan Proposal</h6><button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <form action="<?= base_url() ?>tambah_bimbingan_revisi_proposal_dosen" method="POST" enctype="multipart/form-data">
                                            <?= csrf_field() ?>
                                            <div class="modal-body">
                                                <input type="hidden" name="nim" value="" class="nim">
                                                <input type="hidden" name="id_bimbingan" value="" class="id_bimbingan">
                                                <div class="form-group">
                                                    <label for="">Pokok Bimbingan</label>
                                                    <input type="teks" name="pokok_bimbingan" class="form-control pokok_bimbingan" readonly id="exampleInput" value="">
                                                </div>
                                                <!-- <div class="form-group"> -->
                                                <!-- <label for="">Upload File <small>(.pdf, .doc, .docx, .ppt, .pptx, .xls, .xlsx, .csv, .jpg, .jpeg, .png, .zip)</small></label> -->
                                                <!-- <div class="input-group file-browser"> -->
                                                <input type="hidden" class="form-control border-right-0 browse-file" placeholder="-" name="ket_berkas" readonly>
                                                <!-- <label class="input-group-btn"> -->
                                                <!-- <span class="btn btn-default"> -->
                                                <!-- Browse  -->
                                                <input type="file" name="berkas" class="d-none" multiple>
                                                <!-- </span> -->
                                                <!-- </label> -->
                                                <!-- </div> -->
                                                <!-- </div> -->
                                                <div class="form-group">
                                                    <label for="">Keterangan/Pesan</label>
                                                    <textarea name="keterangan" class="ckeditor keterangan" id="ckeditor" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn ripple btn-primary" type="submit">Kirim</button>
                                                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">Keluar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="main-chat-footer">
                    <a class="main-msg-send" data-bs-target="#modaladd" data-bs-toggle="modal" href=""><i class="far fa-paper-plane"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!-- row -->
</div>
<?= $this->endSection(); ?>