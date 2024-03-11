<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
    <meta name="Author" content="Spruko Technologies Private Limited">
    <meta name="Keywords" content="admin,admin dashboard,admin dashboard template,admin panel template,admin template,admin theme,bootstrap 4 admin template,bootstrap 4 dashboard,bootstrap admin,bootstrap admin dashboard,bootstrap admin panel,bootstrap admin template,bootstrap admin theme,bootstrap dashboard,bootstrap form template,bootstrap panel,bootstrap ui kit,dashboard bootstrap 4,dashboard design,dashboard html,dashboard template,dashboard ui kit,envato templates,flat ui,html,html and css templates,html dashboard template,html5,jquery html,premium,premium quality,sidebar bootstrap 4,template admin bootstrap 4" />

    <!-- Title -->
    <title> SISRI - Login</title>

    <!-- Favicon -->
    <link rel="icon" href="<?= base_url() ?>/assets/img/brand/Sisri.png" type="image/x-icon" />

    <!-- Icons css -->
    <link href="<?= base_url() ?>/assets/css/icons.css" rel="stylesheet">

    <!-- Bootstrap css -->
    <link href="<?= base_url() ?>/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!--  Right-sidemenu css -->
    <link href="<?= base_url() ?>/assets/plugins/sidebar/sidebar.css" rel="stylesheet">

    <!-- P-scroll bar css-->
    <link href="<?= base_url() ?>/assets/plugins/perfect-scrollbar/p-scrollbar.css" rel="stylesheet" />

    <!-- Sidemenu css -->
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/sidemenu.css">

    <!--- Style css --->
    <link href="<?= base_url() ?>/assets/css/style.css" rel="stylesheet">
    <link href="<?= base_url() ?>/assets/css/boxed.css" rel="stylesheet">
    <link href="<?= base_url() ?>/assets/css/dark-boxed.css" rel="stylesheet">

    <!--- Dark-mode css --->
    <link href="<?= base_url() ?>/assets/css/style-dark.css" rel="stylesheet">

    <!---Skinmodes css-->
    <link href="<?= base_url() ?>/assets/css/skin-modes.css" rel="stylesheet" />

    <!--- Animations css-->
    <link href="<?= base_url() ?>/assets/css/animate.css" rel="stylesheet">

    <style>
        .btn-md {
            padding: 10px 16px;
            font-size: 15px;
            line-height: 23px
        }

        .btn {
            border-radius: 2px;
            text-transform: capitalize;
            font-size: 15px;
            padding: 10px 19px;
            cursor: pointer
        }

        .btn-google {
            color: #545454;
            background-color: #ffffff;
            box-shadow: 0 1px 2px 1px #ddd;
        }

        .btn-modif {
            color: #545454;
            background-color: #3B71CA;
            box-shadow: 0 1px 2px 1px #ddd;
        }
    </style>

</head>

<div class="container" oncontextmenu="return false">
    <div class="row mt-4">
        <div class="col">
            <div class="card pb-4">
                <section class="vh-90">
                    <div class="container h-custom">
                        <div class="row">
                            <div class="col-md-9 col-lg-4 col-xl-5 pb-2">
                                <img src="<?= base_url() ?>/assets/img/logosisri.png" alt="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-lg-6 col-xl-6 col-xxl-6 pb-2">
                                <a href=""><img src="<?= base_url() ?>/assets/img/ft.png" class="img-fluid" alt="Sample image"></a>
                            </div>
                            <div class="col-md-10 col-lg-9 col-xl-4 offset-xl-1 pt-4">
                                <div class="row mb-4">
                                    <div class="col-12 pb-2">
                                        <a href="https://siakad.trunojoyo.ac.id/"><button class="btn btn-outline btn-modif btn-light btn-block text-white" type="button"><b>SIAKAD UTM</b></button></a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <form action="<?= base_url() ?>proses_login" method="POST" enctype="multipart/form-data">
                                            <?= csrf_field() ?>
                                            <h3 class="text-secondary"><b>SELAMAT DATANG</b><br></h3>
                                            <p class="text-secondary" style="text-align: justify;"><b>SISRI</b> <a class="font-weight-light">merupakan sistem informasi manejemen skripsi fakultas teknik yang bertujuan memudahkan mahasiswa dan dosen pembimbing dalam melaksanakan prosedur skripsi. Sistem ini memungkinkan mahasiswa untuk melakukan pengajuan judul, bimbingan proposal, pendaftaran seminar proposal, bimbingan pasca seminar proposal dan pendaftaran sidang. Sistem ini juga memungkinkan dosen untuk melakukan validasi usulan, proses bimbingan, revisi pasca seminar dan sidang, validasi pendaftaran seminar dan sidang skripsi serta input nilai skripsi.</a></p>
                                            <br>
                                            <!-- <div class="form-group mb-4">
                                                <label>Username</label>
                                                <input class="form-control" placeholder="Masukkan NIM / NIP / Email Anda" type="text" name="username">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label>Password</label>
                                                <input class="form-control" placeholder="Masukkan Password Anda" type="password" name="password">
                                            </div>
                                            <div class="text-left text-lg-start pb-3">
                                                <button type="submit" class="btn btn-modif btn-light btn-outline text-white" style="padding-left: 2.5rem; padding-right: 2.5rem;"><b>Login</b></button>
                                            </div> -->
                                            <?php
                                            session()->getFlashdata('message');
                                            ?>
                                            <div class="row row-xs">
                                                <div class="col-sm-7 mg-t-10 mg-sm-t-0">
                                                    <a class="btn btn-primary btn-google btn-block btn-outline" href="<?= base_url() ?>redirect"><img src="https://img.icons8.com/color/16/000000/google-logo.png"> Sign In with Google</a>
                                                </div>
                                                <!-- <div class="col-sm-5">
                                                </div>
                                                <div class="col-sm-5">
                                                    <p><a href="/password">Lupa Password?</a></p>
                                                </div> -->
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="col-auto">
                            <span>Copyright © 2022 <a href="http://teknik.trunojoyo.ac.id/">FT-UTM</a>.
                                All rights reserved.</span>
                        </div>
                </section>
            </div>
        </div>
    </div>
</div>