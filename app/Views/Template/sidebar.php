	<!-- Page -->
	<div class="page">

		<!-- main-sidebar -->
		<div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
		<aside class="app-sidebar sidebar-scroll">
			<div class="main-sidebar-header active">
				<a class="desktop-logo logo-light active" href="#"><img src="<?= base_url() ?>/assets/img/brand/logosisri.png" class="main-logo" alt="logo"></a>
				<a class="desktop-logo logo-dark active" href="#"><img src="<?= base_url() ?>/assets/img/brand/logosisri.png" class="main-logo dark-theme" alt="logo"></a>
				<a class="logo-icon mobile-logo icon-light active" href="#"><img src="<?= base_url() ?>/assets/img/brand/logosisri.png" class="logo-icon" alt="logo"></a>
				<a class="logo-icon mobile-logo icon-dark active" href="#"><img src="<?= base_url() ?>/assets/img/brand/logosisri.png" class="logo-icon dark-theme" alt="logo"></a>
			</div>
			<div class="main-sidemenu">
				<div class="app-sidebar__user clearfix">
					<div class="dropdown user-pro-body">
						<div class="">
							<img alt="user-img" class="avatar avatar-xl brround" src="<?= base_url() ?>/image/<?= session()->get('ses_image') ?>"><span class="avatar-status profile-status bg-green"></span>
						</div>
						<div class="user-info">
							<?php
							$namaunit = $db->query("SELECT * FROM tb_unit WHERE idunit='" . session()->get('ses_idunit') . "'")->getResult()[0]->namaunit;
							?>
							<h4 class="fw-semibold mt-3 mb-0"><?= session()->get('ses_nama'); ?></h4>
							<span class="mb-0 text-muted"><?= session()->get('ses_id'); ?></span><br>
							<span class="mb-0 text-muted"><?= $namaunit ?></span>
						</div>
					</div>
				</div>
				<ul class="side-menu">
					<?php if (session()->get('ses_login') == 'mahasiswa') {; ?>
						<li class="side-item side-item-category">MAHASISWA</li>
						<li class="slide">
							<a class="side-menu__item" href="<?= base_url() ?>beranda_mahasiswa"><svg style="width:24px;height:24px" viewBox="0 0 24 24">
									<path fill="currentColor" d="M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z" />
								</svg> &nbsp; &nbsp;<span class="side-menu__label">Beranda</span></a>
						</li>
						<li class="slide">
							<a class="side-menu__item" href="<?= base_url() ?>ajukan_topik_mahasiswa"><svg style="width:24px;height:24px" viewBox="0 0 24 24">
									<path fill="currentColor" d="M14 2H6C4.89 2 4 2.89 4 4V20C4 21.11 4.89 22 6 22H13.81C13.28 21.09 13 20.05 13 19C13 18.67 13.03 18.33 13.08 18H6V16H13.81C14.27 15.2 14.91 14.5 15.68 14H6V12H18V13.08C18.33 13.03 18.67 13 19 13S19.67 13.03 20 13.08V8L14 2M13 9V3.5L18.5 9H13M18 15V18H15V20H18V23H20V20H23V18H20V15H18Z" />
								</svg> &nbsp; &nbsp;<span class="side-menu__label">Ajukan Topik</span></a>
						</li>
						<?php
						$pem1 = $db->query("SELECT * from tb_pengajuan_pembimbing where nim='" . session()->get('ses_id') . "' AND status_pengajuan='diterima' AND sebagai='1'")->getResult();
						$pem2 = $db->query("SELECT * from tb_pengajuan_pembimbing where nim='" . session()->get('ses_id') . "' AND status_pengajuan='diterima' AND sebagai='2'")->getResult();
						$penguji1 = $db->query("SELECT * from tb_penguji where nim='" . session()->get('ses_id') . "' AND `status`='aktif' AND sebagai='1'")->getResult();
						$penguji2 = $db->query("SELECT * from tb_penguji where nim='" . session()->get('ses_id') . "' AND `status`='aktif' AND sebagai='2'")->getResult();
						$penguji3 = $db->query("SELECT * from tb_penguji where nim='" . session()->get('ses_id') . "' AND `status`='aktif' AND sebagai='3'")->getResult();
						if (count($pem1) != 0 && count($pem2) != 0) {
						?>
							<li class="slide">
								<a class="side-menu__item" data-bs-toggle="slide" href="#"><svg style="width:24px;height:24px" viewBox="0 0 24 24">
										<path fill="currentColor" d="M13,9H18.5L13,3.5V9M6,2H14L20,8V20A2,2 0 0,1 18,22H6C4.89,22 4,21.1 4,20V4C4,2.89 4.89,2 6,2M15,18V16H6V18H15M18,14V12H6V14H18Z" />
									</svg> &nbsp; &nbsp;<span class="side-menu__label">Proposal</span><i class="angle fe fe-chevron-down"></i></a>
								<ul class="slide-menu">
									<li><a class="slide-item" href="<?= base_url() ?>bimbingan_proposal/<?= $pem1[0]->nip ?>">Bimbingan Proposal</a></li>
									<li><a class="slide-item" href="<?= base_url() ?>daftar_seminar">Daftar Seminar</a></li>
									<li><a class="slide-item" href="<?= base_url() ?>berkas_mhs_proposal">Berkas Seminar</a></li>
									<?php
									if ($penguji1 != NULL) {
									?>
										<li><a class="slide-item" href="<?= base_url() ?>bimbingan_revisi_proposal/<?= $penguji1[0]->nip ?>">Revisi Proposal</a></li>
									<?php
									} elseif ($penguji2 != NULL) {
									?>
										<li><a class="slide-item" href="<?= base_url() ?>bimbingan_revisi_proposal/<?= $penguji2[0]->nip ?>">Revisi Proposal</a></li>
									<?php
									} elseif ($penguji3 != NULL) {
									?>
										<li><a class="slide-item" href="<?= base_url() ?>bimbingan_revisi_proposal/<?= $penguji3[0]->nip ?>">Revisi Proposal</a></li>
									<?php } ?>
								</ul>
							</li>
							<?php
							$total_acc_dosen_penguji = $db->query("SELECT * FROM `tb_acc_revisi` WHERE `nim` ='" . session()->get('ses_id') . "' AND jenis_sidang = 'seminar proposal'")->getResult();
							// dd(count($total_acc_dosen_penguji));

							if (count($total_acc_dosen_penguji) == 3) {
							?>
								<li class=" slide">
									<a class="side-menu__item" data-bs-toggle="slide" href="#"><svg style="width:24px;height:24px" viewBox="0 0 24 24">
											<path fill="currentColor" d="M4 4V22H20V24H4C2.9 24 2 23.1 2 22V4H4M15 7H20.5L15 1.5V7M8 0H16L22 6V18C22 19.11 21.11 20 20 20H8C6.89 20 6 19.1 6 18V2C6 .89 6.89 0 8 0M17 16V14H8V16H17M20 12V10H8V12H20Z" />
										</svg> &nbsp; &nbsp;<span class="side-menu__label">Skripsi</span><i class="angle fe fe-chevron-down"></i></a>
									<ul class="slide-menu">
										<li><a class="slide-item" href="<?= base_url() ?>bimbingan_skripsi/<?= $pem1[0]->nip ?>">Bimbingan Skripsi</a></li>
										<li><a class=" slide-item" href="<?= base_url() ?>daftar_sidang">Daftar Sidang</a></li>
										<li><a class="slide-item" href="<?= base_url() ?>berkas_mhs_skripsi">Berkas Sidang</a></li>
										<?php
										if ($penguji1 != NULL) {
										?>
											<li><a class="slide-item" href="<?= base_url() ?>bimbingan_revisi_skripsi/<?= $penguji1[0]->nip ?>">Revisi Skripsi</a></li>
										<?php
										} elseif ($penguji2 != NULL) {
										?>
											<li><a class="slide-item" href="<?= base_url() ?>bimbingan_revisi_skripsi/<?= $penguji2[0]->nip ?>">Revisi Skripsi</a></li>
										<?php
										} elseif ($penguji3 != NULL) {
										?>
											<li><a class="slide-item" href="<?= base_url() ?>bimbingan_revisi_skripsi/<?= $penguji3[0]->nip ?>">Revisi Skripsi</a></li>
										<?php } ?>
									</ul>
								</li>
							<?php }
							?>
							<?php
							$cek_status_sidang = $db->query("SELECT * FROM tb_pendaftar_sidang a LEFT JOIN tb_jadwal_sidang b ON a.`id_jadwal`=b.`id_jadwal` WHERE b.`jenis_sidang`='sidang skripsi' AND a.`nim`='" . session()->get('ses_id') . "'")->getResult();
							if (count($cek_status_sidang) > 0) {
								// 	dd('benar');
								// }
								// dd("SELECT * FROM tb_pendaftar_sidang a LEFT JOIN tb_jadwal_sidang b ON a.`id_jadwal`=b.`id_jadwal` WHERE b.`jenis_sidang`='sidang skripsi' AND a.`nim`='" . session()->get('ses_id') . "'");
							?>
								<li class="slide">
									<a class="side-menu__item" data-bs-toggle="slide" href="<?= base_url() ?>informasi_nilai"><svg style="width:24px;height:24px" viewBox="0 0 24 24">
											<path fill="currentColor" d="M6 9H8V15H6V9M16 9H18V15H16V9M21 3C22.1 3 23 3.9 23 5V19C23 20.1 22.1 21 21 21H3C1.9 21 1 20.1 1 19V5C1 3.9 1.9 3 3 3H21M5 7C4.4 7 4 7.4 4 8V16C4 16.6 4.4 17 5 17H9C9.6 17 10 16.6 10 16V8C10 7.4 9.6 7 9 7H5M15 7C14.4 7 14 7.4 14 8V16C14 16.6 14.4 17 15 17H19C19.6 17 20 16.6 20 16V8C20 7.4 19.6 7 19 7H15M12 11C12.6 11 13 10.6 13 10C13 9.4 12.6 9 12 9C11.4 9 11 9.4 11 10C11 10.6 11.4 11 12 11M12 15C12.6 15 13 14.6 13 14C13 13.4 12.6 13 12 13C11.4 13 11 13.4 11 14C11 14.6 11.4 15 12 15Z" />

										</svg> &nbsp; &nbsp;<span class="side-menu__label">Informasi Nilai</span>
									</a>
									<!-- <ul class="slide-menu">
										<li><a class="slide-item" href="<?= base_url() ?>input_nilai_bimbingan">Input Nilai Pembimbing (Bimbingan & Ujian)</a></li>
										<li><a class="slide-item" href="<?= base_url() ?>input_nilai_skripsi">Input Nilai Penguji (Ujian)</a></li>
									</ul> -->
								</li>
						<?php
							}
						}
						?>
					<?php } elseif (session()->get('ses_login') == 'dosen' || session()->get('ses_login') == 'korprodi') {; ?>
						<li class=" side-item side-item-category">Dosen</li>
						<li class="slide">
							<a class="side-menu__item" href="<?= base_url() ?>beranda_dosen"><svg style="width:24px;height:24px" viewBox="0 0 24 24">
									<path fill="currentColor" d="M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z" />
								</svg> &nbsp; &nbsp;<span class="side-menu__label">Beranda</span></a>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-bs-toggle="slide" href="#">
								<svg style="width:24px;height:24px" viewBox="0 0 24 24">
									<path fill="currentColor" d="M13,9H18.5L13,3.5V9M6,2H14L20,8V20A2,2 0 0,1 18,22H6C4.89,22 4,21.1 4,20V4C4,2.89 4.89,2 6,2M15,18V16H6V18H15M18,14V12H6V14H18Z" />
								</svg> &nbsp; &nbsp;
								<span class="side-menu__label">Proposal</span><i class="angle fe fe-chevron-down"></i>
							</a>
							<ul class="slide-menu">
								<li><a class="slide-item" href="<?= base_url() ?>validasi_usulan">Validasi Usulan</a></li>
								<li><a class="slide-item" href="<?= base_url() ?>data_mahasiswa_bimbingan_proposal">Bimbingan Proposal</a></li>
								<li><a class="slide-item" href="<?= base_url() ?>validasi_daftar_seminar">Validasi Daftar Seminar</a></li>
								<li><a class="slide-item" href="<?= base_url() ?>berita_acara_seminar">Berita Acara Seminar</a></li>
								<li><a class="slide-item" href="<?= base_url() ?>data_mahasiswa_bimbingan_revisi_proposal">Validasi Revisi Pasca Seminar</a></li>
							</ul>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-bs-toggle="slide" href="#">
								<svg style="width:24px;height:24px" viewBox="0 0 24 24">
									<path fill="currentColor" d="M4 4V22H20V24H4C2.9 24 2 23.1 2 22V4H4M15 7H20.5L15 1.5V7M8 0H16L22 6V18C22 19.11 21.11 20 20 20H8C6.89 20 6 19.1 6 18V2C6 .89 6.89 0 8 0M17 16V14H8V16H17M20 12V10H8V12H20Z" />
								</svg> &nbsp; &nbsp;
								<span class="side-menu__label">Skripsi</span><i class="angle fe fe-chevron-down"></i>
							</a>
							<ul class="slide-menu">
								<li><a class="slide-item" href="<?= base_url() ?>data_mahasiswa_bimbingan_skripsi">Bimbingan Skripsi</a></li>
								<li><a class="slide-item" href="<?= base_url() ?>validasi_daftar_sidang">Validasi Daftar Sidang</a></li>
								<li><a class="slide-item" href="<?= base_url() ?>berita_acara_skripsi">Berita Acara Sidang</a></li>
								<li><a class="slide-item" href="<?= base_url() ?>data_mahasiswa_bimbingan_revisi_skripsi">Validasi Revisi Pasca Sidang</a></li>
							</ul>
						</li>
						<li class="slide">
							<a class="side-menu__item" data-bs-toggle="slide" href="#"><svg style="width:24px;height:24px" viewBox="0 0 24 24">
									<path fill="currentColor" d="M6 9H8V15H6V9M16 9H18V15H16V9M21 3C22.1 3 23 3.9 23 5V19C23 20.1 22.1 21 21 21H3C1.9 21 1 20.1 1 19V5C1 3.9 1.9 3 3 3H21M5 7C4.4 7 4 7.4 4 8V16C4 16.6 4.4 17 5 17H9C9.6 17 10 16.6 10 16V8C10 7.4 9.6 7 9 7H5M15 7C14.4 7 14 7.4 14 8V16C14 16.6 14.4 17 15 17H19C19.6 17 20 16.6 20 16V8C20 7.4 19.6 7 19 7H15M12 11C12.6 11 13 10.6 13 10C13 9.4 12.6 9 12 9C11.4 9 11 9.4 11 10C11 10.6 11.4 11 12 11M12 15C12.6 15 13 14.6 13 14C13 13.4 12.6 13 12 13C11.4 13 11 13.4 11 14C11 14.6 11.4 15 12 15Z" />

								</svg> &nbsp; &nbsp;<span class="side-menu__label">Nilai</span><i class="angle fe fe-chevron-down"></i></a>
							<ul class="slide-menu">
								<li><a class="slide-item" href="<?= base_url() ?>input_nilai_bimbingan">Input Nilai Pembimbing (Bimbingan & Ujian)</a></li>
								<li><a class="slide-item" href="<?= base_url() ?>input_nilai_skripsi">Input Nilai Penguji (Ujian)</a></li>
							</ul>
						</li>
						<?php if (session()->get('ses_login') == 'korprodi') {; ?>
							<li class="side-item side-item-category">KOORDINATOR PRODI</li>
							<li class="slide">
								<a class="side-menu__item" href="<?= base_url() ?>data_dosen_koorprodi">
									<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
										<path d="M0 0h24v24H0V0z" fill="none" />
										<path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3" />
										<path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z" />
									</svg>
									<span class="side-menu__label">Data Dosen</span>
								</a>
							</li>
							<li class="slide">
								<a class="side-menu__item" href="<?= base_url() ?>bidang_minat">
									<svg style="width:24px;height:24px" viewBox="0 0 24 24">
										<path fill="currentColor" d="M14 2H6C4.89 2 4 2.89 4 4V20C4 21.11 4.89 22 6 22H13.81C13.28 21.09 13 20.05 13 19C13 18.67 13.03 18.33 13.08 18H6V16H13.81C14.27 15.2 14.91 14.5 15.68 14H6V12H18V13.08C18.33 13.03 18.67 13 19 13S19.67 13.03 20 13.08V8L14 2M13 9V3.5L18.5 9H13M18 15V18H15V20H18V23H20V20H23V18H20V15H18Z" />
									</svg> &nbsp; &nbsp;
									<span class="side-menu__label">Manajemen Bidang Minat</span>
								</a>
							</li>
							<li class="slide">
								<a class="side-menu__item" href="<?= base_url() ?>validasi_daftar_seminar_koor">
									<svg style="width:24px;height:24px" viewBox="0 0 24 24">
										<path fill="currentColor" d="M23.5 17L18.5 22L15 18.5L16.5 17L18.5 19L22 15.5L23.5 17M6 2C4.89 2 4 2.89 4 4V20C4 21.11 4.89 22 6 22H13.81C13.28 21.09 13 20.05 13 19C13 18.67 13.03 18.33 13.08 18H6V16H13.81C14.27 15.2 14.91 14.5 15.68 14H6V12H18V13.08C18.33 13.03 18.67 13 19 13C19.34 13 19.67 13.03 20 13.08V8L14 2M13 3.5L18.5 9H13Z" />
									</svg> &nbsp; &nbsp;
									<span class="side-menu__label">Validasi Perizinan Sidang</span>
								</a>
							</li>
							<li class="slide">
								<a class="side-menu__item" href="<?= base_url() ?>penjadwalan_sidang">
									<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
										<path d="M0 0h24v24H0V0z" fill="none" />
										<path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3" />
										<path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z" />
									</svg>
									<span class="side-menu__label">Penjadwalan Sidang</span>
								</a>
							</li>
							<!-- <li class="slide">
								<a class="side-menu__item" href="<?= base_url() ?>beranda_koorprodi">
									<svg style="width:24px;height:24px" viewBox="0 0 24 24">
										<path fill="currentColor" d="M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z" />
									</svg> &nbsp; &nbsp;
									<span class="side-menu__label">Beranda</span>
								</a>
							</li> -->

							<!-- <li class="slide">
								<a class="side-menu__item" data-bs-toggle="slide" href="#">
									<svg style="width:24px;height:24px" viewBox="0 0 24 24">
										<path fill="currentColor" d="M13,9H18.5L13,3.5V9M6,2H14L20,8V20A2,2 0 0,1 18,22H6C4.89,22 4,21.1 4,20V4C4,2.89 4.89,2 6,2M15,18V16H6V18H15M18,14V12H6V14H18Z" />
									</svg> &nbsp; &nbsp;
									<span class="side-menu__label">Proposal</span><i class="angle fe fe-chevron-down"></i>
								</a>
								<ul class="slide-menu">
									<li><a class="slide-item" href="<?= base_url() ?>atur_jadwal_pendaftaran_seminar">Atur Jadwal Pendaftaran</a></li>
									<li><a class="slide-item" href="<?= base_url() ?>atur_pengujidan_jadwal_seminar">Atur Penguji dan Jadwal Seminar</a></li>
									<li><a class="slide-item" href="<?= base_url() ?>histori_sempro">Histori Seminar</a></li>
								</ul>
							</li>
							<li class="slide">
								<a class="side-menu__item" data-bs-toggle="slide" href="#">
									<svg style="width:24px;height:24px" viewBox="0 0 24 24">
										<path fill="currentColor" d="M4 4V22H20V24H4C2.9 24 2 23.1 2 22V4H4M15 7H20.5L15 1.5V7M8 0H16L22 6V18C22 19.11 21.11 20 20 20H8C6.89 20 6 19.1 6 18V2C6 .89 6.89 0 8 0M17 16V14H8V16H17M20 12V10H8V12H20Z" />
									</svg> &nbsp; &nbsp;
									<span class="side-menu__label">Skripsi</span><i class="angle fe fe-chevron-down"></i>
								</a>
								<ul class="slide-menu">
									<li><a class="slide-item" href="<?= base_url() ?>atur_jadwal_pendaftaran">Atur Jadwal Pendaftaran</a></li>
									<li><a class="slide-item" href="<?= base_url() ?>atur_jadwal_sidang_skripsi">Atur Jadwal Sidang</a></li>
									<li><a class="slide-item" href="<?= base_url() ?>histori_sidang_skripsi">Histori Sidang</a></li>
								</ul>
							</li> -->
							<li class="slide">
								<a class="side-menu__item" href="<?= base_url() ?>daftar_nilai">
									<svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
										<path d="M0 0h24v24H0V0z" fill="none" />
										<path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3" />
										<path d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z" />
									</svg>
									<span class="side-menu__label">Daftar Nilai</span>
								</a>
							</li>
						<?php }
					} elseif (session()->get('ses_login') == 'admin') { ?>
						<li class="side-item side-item-category">ADMIN</li>
						<li class="slide">
							<a class="side-menu__item" href="<?= base_url() ?>beranda_admin">
								<svg style="width:24px;height:24px" viewBox="0 0 24 24">
									<path fill="currentColor" d="M10,20V14H14V20H19V12H22L12,3L2,12H5V20H10Z" />
								</svg> &nbsp; &nbsp;
								<span class="side-menu__label">Beranda</span>
							</a>
						</li>
						<li class="slide">
							<a class="side-menu__item" href="<?= base_url() ?>data_mahasiswa">
								<svg style="width:24px;height:24px" viewBox="0 0 24 24">
									<path fill="currentColor" d="M23.5 17L18.5 22L15 18.5L16.5 17L18.5 19L22 15.5L23.5 17M6 2C4.89 2 4 2.89 4 4V20C4 21.11 4.89 22 6 22H13.81C13.28 21.09 13 20.05 13 19C13 18.67 13.03 18.33 13.08 18H6V16H13.81C14.27 15.2 14.91 14.5 15.68 14H6V12H18V13.08C18.33 13.03 18.67 13 19 13C19.34 13 19.67 13.03 20 13.08V8L14 2M13 3.5L18.5 9H13Z" />
								</svg> &nbsp; &nbsp;
								<span class="side-menu__label">Data Mahasiswa</span>
							</a>
						</li>
						<li class="slide">
							<a class="side-menu__item" href="<?= base_url() ?>data_dosen">
								<svg style="width:24px;height:24px" viewBox="0 0 24 24">
									<path fill="currentColor" d="M23.5 17L18.5 22L15 18.5L16.5 17L18.5 19L22 15.5L23.5 17M6 2C4.89 2 4 2.89 4 4V20C4 21.11 4.89 22 6 22H13.81C13.28 21.09 13 20.05 13 19C13 18.67 13.03 18.33 13.08 18H6V16H13.81C14.27 15.2 14.91 14.5 15.68 14H6V12H18V13.08C18.33 13.03 18.67 13 19 13C19.34 13 19.67 13.03 20 13.08V8L14 2M13 3.5L18.5 9H13Z" />
								</svg> &nbsp; &nbsp;
								<span class="side-menu__label">Data Dosen</span>
							</a>
						</li>
						<li class="slide">
							<a class="side-menu__item" href="<?= base_url() ?>data_korprodi">
								<svg style="width:24px;height:24px" viewBox="0 0 24 24">
									<path fill="currentColor" d="M23.5 17L18.5 22L15 18.5L16.5 17L18.5 19L22 15.5L23.5 17M6 2C4.89 2 4 2.89 4 4V20C4 21.11 4.89 22 6 22H13.81C13.28 21.09 13 20.05 13 19C13 18.67 13.03 18.33 13.08 18H6V16H13.81C14.27 15.2 14.91 14.5 15.68 14H6V12H18V13.08C18.33 13.03 18.67 13 19 13C19.34 13 19.67 13.03 20 13.08V8L14 2M13 3.5L18.5 9H13Z" />
								</svg> &nbsp; &nbsp;
								<span class="side-menu__label">Data Koordinator Prodi</span>
							</a>
						</li>
						<li class="slide">
							<a class="side-menu__item" href="<?= base_url() ?>data_akun_khusus">
								<svg style="width:24px;height:24px" viewBox="0 0 24 24">
									<path fill="currentColor" d="M23.5 17L18.5 22L15 18.5L16.5 17L18.5 19L22 15.5L23.5 17M6 2C4.89 2 4 2.89 4 4V20C4 21.11 4.89 22 6 22H13.81C13.28 21.09 13 20.05 13 19C13 18.67 13.03 18.33 13.08 18H6V16H13.81C14.27 15.2 14.91 14.5 15.68 14H6V12H18V13.08C18.33 13.03 18.67 13 19 13C19.34 13 19.67 13.03 20 13.08V8L14 2M13 3.5L18.5 9H13Z" />
								</svg> &nbsp; &nbsp;
								<span class="side-menu__label">Data Akun Khusus</span>
							</a>
						</li>
						<li class="slide">
							<a class="side-menu__item" href="<?= base_url() ?>data_unit">
								<svg style="width:24px;height:24px" viewBox="0 0 24 24">
									<path fill="currentColor" d="M23.5 17L18.5 22L15 18.5L16.5 17L18.5 19L22 15.5L23.5 17M6 2C4.89 2 4 2.89 4 4V20C4 21.11 4.89 22 6 22H13.81C13.28 21.09 13 20.05 13 19C13 18.67 13.03 18.33 13.08 18H6V16H13.81C14.27 15.2 14.91 14.5 15.68 14H6V12H18V13.08C18.33 13.03 18.67 13 19 13C19.34 13 19.67 13.03 20 13.08V8L14 2M13 3.5L18.5 9H13Z" />
								</svg> &nbsp; &nbsp;
								<span class="side-menu__label">Data Unit</span>
							</a>
						</li>

						<li class="slide">
							<a class="side-menu__item" href="<?= base_url() ?>data_periode">
								<svg style="width:24px;height:24px" viewBox="0 0 24 24">
									<path fill="currentColor" d="M23.5 17L18.5 22L15 18.5L16.5 17L18.5 19L22 15.5L23.5 17M6 2C4.89 2 4 2.89 4 4V20C4 21.11 4.89 22 6 22H13.81C13.28 21.09 13 20.05 13 19C13 18.67 13.03 18.33 13.08 18H6V16H13.81C14.27 15.2 14.91 14.5 15.68 14H6V12H18V13.08C18.33 13.03 18.67 13 19 13C19.34 13 19.67 13.03 20 13.08V8L14 2M13 3.5L18.5 9H13Z" />
								</svg> &nbsp; &nbsp;
								<span class="side-menu__label">Data Periode</span>
							</a>
						</li>
						<?php };
					$nip_dekan = $db->query("SELECT * FROM tb_dekan WHERE nip='" . session()->get('ses_id') . "'")->getResult();
					if (!empty($nip_dekan)) {
						if (session()->get('ses_id') == $nip_dekan[0]->nip) {
							$id = password_hash(session()->get('ses_id'), PASSWORD_DEFAULT);
						?>
							<li class="side-item side-item-category">Fitur Khusus</li>
							<li class="slide">
								<a class="side-menu__item" data-bs-toggle="slide" href="<?= base_url() ?>loginkhusus/<?= $id ?>"><svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
										<path d="M0 0h24v24H0V0z" fill="none" />
										<path d="M6 20h12V10H6v10zm6-7c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2z" opacity=".3" />
										<path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM9 6c0-1.66 1.34-3 3-3s3 1.34 3 3v2H9V6zm9 14H6V10h12v10zm-6-3c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2z" />
									</svg><span class="side-menu__label">Login Khusus</span><i class="angle fe fe-chevron-down"></i></a>
							</li>
					<?php }
					} ?>
				</ul>
			</div>
		</aside>
		<!-- main-sidebar -->

		<!-- main-content -->
		<div class="main-content app-content">