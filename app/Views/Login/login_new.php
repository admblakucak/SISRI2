<!doctype html>
<html>

<head>
  <meta charset='utf-8'>
  <meta name='viewport' content='width=device-width, initial-scale=1'>
  <title> SISRI - Login</title>
  <link href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' rel='stylesheet'>
  <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet'>
  <!-- Favicon -->
  <link rel="icon" href="<?= base_url() ?>/assets/img/brand/Sisri.png" type="image/x-icon" />
  <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>

  <style>
    ::-webkit-scrollbar {
      width: 8px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
      background: #888;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
      background: #555;
    }

    body {
      color: #000;
      overflow-x: hidden;
      height: 100%;
      background-color: #B0BEC5;
      background-repeat: no-repeat;
    }

    .card0 {
      box-shadow: 0px 4px 8px 0px #757575;
      border-radius: 0px;
    }

    .card2 {
      margin: 0px 40px;
    }

    .logo {
      width: 370px;
      height: 100px;
      margin-top: 20px;
      margin-left: 35px;
    }

    .image {
      width: 360px;
      height: 280px;
    }

    .border-line {
      border-right: 1px solid #EEEEEE;
    }

    .facebook {
      background-color: #3b5998;
      color: #fff;
      font-size: 18px;
      padding-top: 5px;
      border-radius: 50%;
      width: 35px;
      height: 35px;
      cursor: pointer;
    }

    .twitter {
      background-color: #1DA1F2;
      color: #fff;
      font-size: 18px;
      padding-top: 5px;
      border-radius: 50%;
      width: 35px;
      height: 35px;
      cursor: pointer;
    }

    .linkedin {
      background-color: #2867B2;
      color: #fff;
      font-size: 18px;
      padding-top: 5px;
      border-radius: 50%;
      width: 35px;
      height: 35px;
      cursor: pointer;
    }

    .line {
      height: 1px;
      width: 45%;
      background-color: #E0E0E0;
      margin-top: 10px;
    }

    .or {
      width: 10%;
      font-weight: bold;
    }

    .text-sm {
      font-size: 14px !important;
    }

    ::placeholder {
      color: #BDBDBD;
      opacity: 1;
      font-weight: 300
    }

    :-ms-input-placeholder {
      color: #BDBDBD;
      font-weight: 300
    }

    ::-ms-input-placeholder {
      color: #BDBDBD;
      font-weight: 300
    }

    input,
    textarea {
      padding: 10px 12px 10px 12px;
      border: 1px solid lightgrey;
      border-radius: 2px;
      margin-bottom: 5px;
      margin-top: 2px;
      width: 100%;
      box-sizing: border-box;
      color: #2C3E50;
      font-size: 14px;
      letter-spacing: 1px;
    }

    .icon_sosmed,
    .text-white.mt-5 {
      transition: transform .1s
    }

    .icon_sosmed:hover {
      text-decoration: none;
      color: white;
      transform: scale(1.3);
      cursor: pointer;
    }

    .text-white.mt-5:hover {
      transform: scale(0.95);
      cursor: pointer;
    }


    input:focus,
    textarea:focus {
      -moz-box-shadow: none !important;
      -webkit-box-shadow: none !important;
      box-shadow: none !important;
      border: 1px solid #304FFE;
      outline-width: 0;
    }

    button:focus {
      -moz-box-shadow: none !important;
      -webkit-box-shadow: none !important;
      box-shadow: none !important;
      outline-width: 0;
    }

    a {
      color: inherit;
      cursor: pointer;
    }

    .btn-blue {
      background-color: #1A237E;
      width: 150px;
      color: #fff;
      border-radius: 2px;
    }

    .btn-blue:hover {
      background-color: #000;
      cursor: pointer;
    }

    .bg-blue {
      color: #fff;
      background-color: #1A237E;
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

    @media screen and (max-width: 991px) {
      .logo {
        margin-left: 0px;
      }

      .image {
        width: 300px;
        height: 220px;
      }

      .border-line {
        border-right: none;
      }

      .card2 {
        border-top: 1px solid #EEEEEE !important;
        margin: 0px 15px;
      }
    }
  </style>
</head>

<body className='snippet-body'>
  <div class="container-fluid px-1 px-md-5 px-lg-1 px-xl-5 py-5 mx-auto">
    <div class="card card0 border-0">
      <div class="row d-flex">
        <div class="col-lg-6">
          <div class="card1 pb-5">
            <div class="row">
              <img src="<?= base_url() ?>/assets/img/logosisri.png" class="logo img-fluid">
            </div>
            <div class="row px-3 justify-content-center mt-4 mb-5 border-line">
              <img src="https://i.imgur.com/uNGdWHi.png" class="image img-fluid">
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="col-6 pb-2 ml-0">
            <a href="https://siakad.trunojoyo.ac.id/"><button class="btn btn-outline btn-modif btn-light btn-block text-white mt-5" type="button" style="background-color: blue;"><b>SIAKAD UTM</b></button></a>
          </div>
          <div class="col-12 pb-2 ml-0">
            <form action="<?= base_url() ?>proses_login" method="POST" enctype="multipart/form-data">
              <?= csrf_field() ?>
              <h3 class="text-secondary"><b>SELAMAT DATANG</b><br></h3>
              <p class="text-secondary w-100" style="text-align: justify;"><b>SISRI</b> <a class="font-weight-light">merupakan sistem informasi manejemen skripsi fakultas teknik yang bertujuan memudahkan mahasiswa dan dosen pembimbing dalam melaksanakan prosedur skripsi. Sistem ini memungkinkan mahasiswa untuk melakukan pengajuan judul, bimbingan proposal, pendaftaran seminar proposal, bimbingan pasca seminar proposal dan pendaftaran sidang. Sistem ini juga memungkinkan dosen untuk melakukan validasi usulan, proses bimbingan, revisi pasca seminar dan sidang, validasi pendaftaran seminar dan sidang skripsi serta input nilai skripsi.</a></p>
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
              // session()->getFlashdata('message');
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
          <!-- <div class="col-md-10 col-lg-9 col-xl-4 offset-xl-1 pt-4">
            <div class="row mb-4">
              <div class="col-12 pb-2">
                <a href="https://siakad.trunojoyo.ac.id/"><button class="btn btn-outline btn-modif btn-light btn-block text-whiteb" type="button" style="background-color: blue;"><b>SIAKAD UTM</b></button></a>
              </div>
            </div>
            <div class="row">
              <div class="col-12">

              </div>
            </div>
          </div> -->
        </div>
      </div>

      <div class="bg-blue py-4">
        <div class="row px-3">
          <small class="ml-1 ml-sm-5 mb-2"> <span>Copyright © 2022 <a href="http://teknik.trunojoyo.ac.id/">FT-UTM SISRI V.2.0</a>.
              All rights reserved.</span></small>
          <div class="social-contact ml-4 ml-sm-auto">
            <a target="_blank" href="https://www.facebook.com/universitastrunojoyomadura/?locale=id_ID" class="icon_sosmed fa fa-facebook mr-4 text-sm"></a>
            <a target="_blank" href="https://www.trunojoyo.ac.id/" class="icon_sosmed fa fa-google-plus mr-4 text-sm"></a>
            <a target="_blank" href="https://www.linkedin.com/school/universitas-trunojoyo-madura/" class="icon_sosmed fa fa-linkedin mr-4 text-sm"></a>
            <a target="_blank" href="https://twitter.com/UTM_Official" class="icon_sosmed fa fa-twitter mr-4 mr-sm-5 text-sm"></a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script script src="<?= base_url(); ?>/assets/login/bootstrap.bundle.min.js"></script>

  <script type='text/javascript'>
    var myLink = document.querySelector('a[href="#"]');
    myLink.addEventListener('click', function(e) {
      e.preventDefault();
    });
  </script>

</body>

</html>