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
                <form action="<?= base_url() ?>tambah_bimbingan_revisi_proposal" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <input type="hidden" name="pembimbing" value="<?= $how ?>">
                        <div class="form-group">
                            <label for="">Pokok Bimbingan</label>
                            <input type="teks" name="pokok_bimbingan" class="form-control" id="exampleInput" placeholder="Contoh : Bimbingan BAB I">
                        </div>
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
        <div class="col-xl-4 col-lg-5 col-sm-5">
            <div class="card">
                <div class="main-content-left main-content-left-chat">
                    <div class="main-chat-list" id="ChatList">
                        <!-- <?php
                                foreach ($dosen_pembimbing as $key2) {
                                    $notif = $db->query("SELECT *, COUNT( * ) AS total FROM tb_bimbingan WHERE status_baca='belum dibaca' AND `from`='$key2->nip' AND `to`='" . session()->get('ses_id') . "' AND kategori_bimbingan=2 GROUP BY `from`")->getResult();
                                ?>
                            <a href="<?= base_url() ?>bimbingan_revisi_proposal/<?= $key2->nip ?>">
                                <?php if ($notif != NULL) { ?>
                                    <div class="media new">
                                    <?php } else {
                                    ?>
                                        <div class="media <?= $key2->nip == $how ? 'selected' : '' ?>">
                                        <?php } ?>
                                        <div class="main-img-user">
                                            <img alt="" src="<?= base_url() ?>/image/<?= $key2->image != NULL ? $key2->image : 'Profile_Default.png' ?>">
                                            <?php if ($notif != NULL) { ?>
                                                <span><?= $notif[0]->total ?></span>
                                            <?php } ?>
                                        </div>
                                        <div class="media-body">
                                            <div class="media-contact-name">
                                                <span><?php
                                                        if ($key2->nip == $how) {
                                                            $image_dosen = $key2->image != NULL ? $key2->image : 'Profile_Default.png';
                                                            $pembimbing = "Pembimbing $key2->sebagai";
                                                            $nama_dosen = "$key2->gelardepan $key2->nama, $key2->gelarbelakang";
                                                        }
                                                        // echo 'Pembimbing ' . $key2->sebagai . ' - ' . $key2->gelardepan . ' ' . $key2->nama . ', ' . $key2->gelarbelakang;
                                                        ?></span>
                                            </div>
                                        </div>
                                        </div>
                            </a>
                        <?php }
                        ?> -->
                        <?php
                        foreach ($dosen_penguji as $key2) {
                            $notif = $db->query("SELECT *, COUNT( * ) AS total FROM tb_bimbingan WHERE status_baca='belum dibaca' AND `from`='$key2->nip' AND `to`='" . session()->get('ses_id') . "' GROUP BY `from`")->getResult();
                        ?>
                            <a href="<?= base_url() ?>bimbingan_revisi_proposal/<?= $key2->nip ?>">
                                <?php if ($notif != NULL) { ?>
                                    <div class="media new">
                                    <?php } else {
                                    ?>
                                        <div class="media <?= $key2->nip == $how ? 'selected' : '' ?>">
                                        <?php } ?>
                                        <div class="main-img-user">
                                            <img alt="" src="<?= base_url() ?>/image/<?= $key2->image != NULL ? $key2->image : 'Profile_Default.png' ?>">
                                            <?php if ($notif != NULL) { ?>
                                                <span><?= $notif[0]->total ?></span>
                                            <?php } ?>
                                        </div>
                                        <div class="media-body">
                                            <div class="media-contact-name">
                                                <span><?php
                                                        if ($key2->nip == $how) {
                                                            $image_dosen = $key2->image != NULL ? $key2->image : 'Profile_Default.png';
                                                            $pembimbing = "Penguji $key2->sebagai";
                                                            $nama_dosen = "$key2->gelardepan $key2->nama, $key2->gelarbelakang";
                                                        }
                                                        echo 'Penguji ' . $key2->sebagai . ' - ' . $key2->gelardepan . ' ' . $key2->nama . ', ' . $key2->gelarbelakang;
                                                        ?></span>
                                            </div>
                                            <?php
                                            $status_acc_revisi = $db->query("SELECT * FROM tb_acc_revisi WHERE nim='" . session()->get('ses_id') . "' AND nip='" . $key2->nip . "' AND jenis_sidang='seminar proposal'")->getResult();
                                            if ($status_acc_revisi == NULL) {
                                                echo "<p class='text-danger'>Revisi Belum di ACC</p>";
                                            } else {
                                                echo "<p class='text-success'>Revisi Telah di ACC</p>";
                                            }
                                            ?>
                                        </div>
                                        </div>
                            </a>
                        <?php }
                        ?>
                    </div><!-- main-chat-list -->
                </div>
            </div>
        </div>
        <div class="col-xl-8 col-lg-7 col-sm-7">
            <?= session()->getFlashdata('message') != NULL ? session()->getFlashdata('message') . "<br>" : "" ?>
            <div class="card">
                <!-- <a class="main-header-arrow" href="" id="ChatBodyHide"><i class="icon ion-md-arrow-back"></i></a> -->
                <div class="main-content-body-show main-content-body-chat-show">
                    <div class="main-chat-header">
                        <div class="main-img-user"><img alt="" src="<?= base_url() ?>/image/<?= $image_dosen ?>"></div>
                        <div class="main-chat-msg-name">
                            <h6><?= $nama_dosen ?></h6><small><?= $pembimbing ?></small>
                        </div>
                    </div><!-- main-chat-header -->
                    <div class="main-chat-body" id="ChatBody">
                        <div class="content-inner">
                            <script>
                                $(document).ready(function() {
                                    setInterval(function() {
                                        $.get("<?= base_url() . "reload_m_bimbingan_revisi_proposal/$how" ?>", function(result) {
                                            $('#latestData').html(result);
                                        });
                                    }, 1000);
                                });
                            </script>
                            <div id="latestData"></div>
                            <div class="modal" id="modaldel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content modal-content-demo">
                                        <div class="modal-header">
                                            <h6 class="modal-title">Hapus Bimbingan</h6><button aria-label="Close" class="close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <form action="<?= base_url() ?>hapus_bimbingan_revisi_proposal" method="POST" enctype="multipart/form-data">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id_bimbingan" value="" class="id_bimbingan" />
                                            <input type="hidden" name="nip" value="" class="nip">
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
                        </div>
                    </div>
                </div>
                <div class="main-chat-footer">
                    <form action="<?= base_url() ?>tambah_revisi_bimbingan_proposal" method="POST" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <a class="main-msg-send" data-bs-target="#modaladd" data-bs-toggle="modal" href=""><i class="far fa-paper-plane"></i></a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- row -->
</div>
<?= $this->endSection(); ?>