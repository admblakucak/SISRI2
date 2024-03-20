<?php

use CodeIgniter\Images\Image;
?>
<?= $this->extend('Template/content') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
	<div class="breadcrumb-header justify-content-between">
		<div class="left-content">
			<h2 class="main-content-title tx-24 mg-b-1">Beranda Dosen</h2>
			<p class="mg-b-0">Selamat Datang di Beranda Dosen</p>
		</div>
	</div>
</div>
<div class="text-wrap">
	<div class="example">
		<div class="d-md-flex flex-row">
			<div class="">
				<div class="panel panel-primary tabs-style-4">
					<div class="tab-menu-heading">
						<div class="tabs menu">
							<ul class="nav panel-tabs container row" style="width: 400px;">
								<li class="col-6"><a href="#dosen" class="active" data-bs-toggle="tab">Dosen</a></li>
								<?php if (session()->get('ses_login') == 'korprodi') { ?>
									<li class="col-6"><a href="#korprodi" data-bs-toggle="tab">Koorprodi</a></li>
								<?php } ?>
							</ul>
						</div>
					</div>
					<div class="tabs-style-4 ">
						<div class="panel-body tabs-menu-body">
							<div class="tab-content">
								<div class="tab-pane active" id="dosen">
									<div class="col-md-12 col-lg-8 col-xl-12">
										<div class="card card-table-two">
											<div class="d-flex justify-content-between">
												<h4 class="card-title mb-1">Informasi Mahasiswa Bimbingan</h4>
												<i class="mdi mdi-dots-horizontal text-gray"></i>
											</div>
											<span class="d-block mg-b-10 text-muted tx-12">Informasi data mahasiswa melakukan bimbingan</span>
											<div class="table-responsive country-table">
												<table class="table table-striped table-bordered mb-0 text-sm-nowrap text-lg-nowrap text-xl-nowrap" id="validasitable2">
													<thead>
														<tr>
															<th class="wd-lg-25p">Nama Mahasiswa</th>
															<th class="wd-lg-25p">Sebagai</th>
															<th class="wd-lg-25p">Status</th>
														</tr>
													</thead>
													<tbody>
														<?php foreach ($data_mhs_bimbingan as $key) {
															if (empty($key->nama)) {
																continue;
															}
														?>
															<tr>
																<td class="tx-medium tx-inverse"><?= $key->nama ?></td>
																<td class="tx-medium tx-inverse">Pembimbing <?= $key->sebagai ?></td>
																<td class="tx-medium tx-inverse"><?= empty($key->pesan) ? 'Progress' : 'Sudah Lulus' ?></td>
															</tr>
														<?php }  ?>
														<!-- <tr>
															<td class="tx-medium tx-inverse">Nofal</td>
															<td class="tx-medium tx-inverse">Pembimbing 2</td>
															<td class="tx-medium tx-inverse">Progres</td>
														</tr>
														<tr>
															<td class="tx-medium tx-inverse">Wasil</td>
															<td class="tx-medium tx-inverse">Pembimbing 1</td>
															<td class="tx-medium tx-inverse">Progres</td> -->
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="row row-md row-deck">
											<div class="col-md-12 col-lg-6 col-xl-6">
												<div class="card card-dashboard-eight pb-2">
													<h6 class="card-title">Data Penguji Seminar Proposal</h6><span class="d-block mg-b-10 text-muted tx-12">Informasi data ujian mahasiswa</span>
													<div class="list-group border-top-0">
														<table class="table table-striped mg-b-0 text-md-nowrap" id="validasitable3">
															<thead>
																<tr>
																	<th class="wd-lg-25p">Nama Mahasiswa</th>
																	<th class="wd-lg-25p">Sebagai</th>
																	<th class="wd-lg-25p">Tanggal Ujian Seminar Proposal</th>
																</tr>
															</thead>
															<tbody>

																<?php foreach ($data_mhs_uji as $key_2) {
																	if ($key_2['jenis_sidang'] == 'sidang skripsi') {
																		continue;
																	}
																	
																	if (!empty($key_2['id_pendaftar'])) {
																		$jadwal_sidang = $db->query("SELECT waktu_sidang FROM `tb_pendaftar_sidang` WHERE id_pendaftar='" . $key_2['id_pendaftar'] . "'")->getResult();
																	} else {
																		$jadwal_sidang = null;
																	}

																?>
																	<tr>
																		<td><?= $key_2['nama_mhs'] ?></td>
																		<td>Penguji <?= $key_2['sebagai'] ?></td>
																		<td class="text-left"><?= empty($jadwal_sidang) ? 'belum mendaftar' : $jadwal_sidang[0]->waktu_sidang ?></td>
																	</tr>
																<?php } ?>

															</tbody>
														</table>
													</div>
												</div>
											</div>
											<div class="col-md-12 col-lg-6 col-xl-6">
												<div class="card card-dashboard-eight pb-2">
													<h6 class="card-title">Data Penguji Sidang Skripsi</h6><span class="d-block mg-b-10 text-muted tx-12">Informasi data ujian mahasiswa</span>
													<div class="list-group border-top-0">
														<table class="table table-striped mg-b-0 text-md-nowrap" id="validasitable4">
															<thead>
																<tr>
																	<th class="wd-lg-25p">Nama Mahasiswa</th>
																	<th class="wd-lg-25p">Sebagai</th>
																	<th class="wd-lg-25p">Tanggal Ujian Sidang Skripsi</th>
																</tr>
															</thead>
															<tbody>

																<?php foreach ($data_mhs_uji as $key_2) {
																	if ($key_2['jenis_sidang'] == '') {
																		continue;
																	}

																	if (!empty($key_2['id_pendaftar'])) {
																		$jadwal_sidang = $db->query("SELECT waktu_sidang FROM `tb_pendaftar_sidang` WHERE id_pendaftar='" . $key_2['id_pendaftar'] . "'")->getResult();
																	} else {
																		$jadwal_sidang = null;
																	}

																?>
																	<tr>
																		<td><?= $key_2['nama_mhs'] ?></td>
																		<td>Penguji <?= $key_2['sebagai'] ?></td>
																		<td class="text-left"><?= empty($jadwal_sidang) ? 'belum mendaftar' : $jadwal_sidang[0]->waktu_sidang ?></td>
																	</tr>
																<?php } ?>

															</tbody>
														</table>
													</div>
												</div>
											</div>

										</div>
									</div>
								</div>
								<?php if (session()->get('ses_login') == 'korprodi') { ?>
									<div class="tab-pane" id="korprodi">
										<div class="row row-md row-deck">
											<div class="col-md-12 col-lg-12 col-xl-5">
												<div class="card card-table-two">
													<div class="d-flex justify-content-between">
														<h4 class="card-title mb-1">Data Angkatan</h4>
														<i class="mdi mdi-dots-horizontal text-gray"></i>
													</div>
													<span class="d-block mg-b-10 text-muted tx-12">Informasi data mahasiswa yang sudah melakukan seminar proposal dan sidanng skripsi</span>
													<div class="table-responsive country-table">
														<table class="table table-striped table-bordered mb-0 text-sm-nowrap text-lg-nowrap text-xl-nowrap" id="validasitable5">
															<thead>
																<tr>
																	<th class="wd-lg-25p text-center">Angkatan</th>
																	<!-- <th class="wd-lg-25p text-center">Lulus</th> -->
																	<th class="wd-lg-25p text-center">Jumlah Seminar Proposal</th>
																	<th class="wd-lg-25p text-center">Jumlah Sidang Skripsi</th>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($data_periode as $key) {
																	$jumlah_sempro = $db->query("SELECT COUNT(a.nim) as jumlah FROM `tb_mahasiswa` a LEFT JOIN tb_pendaftar_sidang b ON a.nim=b.nim LEFT JOIN tb_jadwal_sidang c ON b.id_jadwal=c.id_jadwal WHERE a.idperiode = '" . $key->idperiode . "' AND b.id_jadwal IS NOT NULL AND c.jenis_sidang='seminar proposal' AND a.idunit='$idunit' ")->getResult();
																	$jumlah_sidang_skripsi = $db->query("SELECT COUNT(a.nim) as jumlah FROM `tb_mahasiswa` a LEFT JOIN tb_pendaftar_sidang b ON a.nim=b.nim LEFT JOIN tb_jadwal_sidang c ON b.id_jadwal=c.id_jadwal WHERE a.idperiode = '" . $key->idperiode . "' AND b.id_jadwal IS NOT NULL AND c.jenis_sidang='sidang skripsi' AND a.idunit='$idunit' ")->getResult();
																	$periode = substr($key->namaperiode, 0, 4);
																?>
																	<tr>
																		<td class="text-center"><?= $periode ?></td>
																		<!-- <td class="text-center">50/100</td> -->
																		<td class="text-center"><?= $jumlah_sempro[0]->jumlah ?></td>
																		<td class="text-center"><?= $jumlah_sidang_skripsi[0]->jumlah ?> </td>
																	</tr>
																<?php } ?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
											<div class="col-md-12 col-lg-8 col-xl-7">
												<div class="card card-dashboard-eight pb-2">
													<h6 class="card-title">Data Dosen</h6>
													<span class="d-block mg-b-10 text-muted tx-12">Informasi data jumlah dosen sebagai pembimbing dan penguji</span>
													<div class="list-group border-top-0">
														<table class="table table-striped mg-b-0 text-md-nowrap" id="validasitable6">
															<thead>
																<tr>
																	<th class="wd-lg-25p text-center">NIP</th>
																	<th class="wd-lg-25p text-center">Nama Dosen</th>
																	<th class="wd-lg-25p text-center">Sebagai Pembimbing</th>
																	<th class="wd-lg-25p text-center">Sebagai Penguji</th>
																</tr>
															</thead>
															<tbody>
																<?php
																foreach ($data_dosen as $key) {
																	$jumlah_bimbingan1 = $db->query("SELECT * FROM tb_pengajuan_pembimbing where nip='$key->nip' AND sebagai='1' AND status_pengajuan='diterima'")->getResult();
																	$jumlah_bimbingan2 = $db->query("SELECT * FROM tb_pengajuan_pembimbing where nip='$key->nip' AND sebagai='2' AND status_pengajuan='diterima'")->getResult();
																	$jumlah_bimbinganselesai1 = $db->query("SELECT * FROM `tb_nilai` WHERE nip='$key->nip' AND sebagai='pembimbing 1' AND nilai_ujian is NOT null AND nilai_bimbingan is not null")->getResult();
																	$jumlah_bimbinganselesai2 = $db->query("SELECT * FROM `tb_nilai` WHERE nip='$key->nip' AND sebagai='pembimbing 2' AND nilai_ujian is NOT null AND nilai_bimbingan is not null")->getResult();
																	$db->query("UPDATE tb_jumlah_pembimbing SET jumlah='" . (count($jumlah_bimbingan1) - count($jumlah_bimbinganselesai1)) . "' WHERE nip='$key->nip' AND sebagai='pembimbing 1' ");
																	$db->query("UPDATE tb_jumlah_pembimbing SET jumlah='" . (count($jumlah_bimbingan2) - count($jumlah_bimbinganselesai2)) . "' WHERE nip='$key->nip' AND sebagai='pembimbing 2' ");
																	$kuota_p1 = $db->query("SELECT * FROM tb_jumlah_pembimbing WHERE nip='$key->nip' AND sebagai='pembimbing 1'")->getResult();
																	$kuota_p2 = $db->query("SELECT * FROM tb_jumlah_pembimbing WHERE nip='$key->nip' AND sebagai='pembimbing 2'")->getResult();
																	$jumlah_penguji = $db->query("SELECT count(*) as jumlah FROM tb_penguji WHERE nip='$key->nip' AND status='aktif'")->getResult();

																?>
																	<tr>
																		<td class="text-center align-middle"> <?= $key->nip ?> </td>
																		<td class="text-center align-middle"> <?= $key->nama ?> </td>
																		<td class="text-center align-middle">
																			Pembimbing 1 : <?= $kuota_p1 != NULL ? $kuota_p1[0]->jumlah . '/' . $kuota_p1[0]->kuota : "0/10" ?>
																			<br>
																			Pembimbing 2 : <?= $kuota_p2 != NULL ? $kuota_p2[0]->jumlah . '/' . $kuota_p2[0]->kuota : "0/10" ?>
																		</td>
																		<td class="text-center align-middle"><?= $jumlah_penguji[0]->jumlah ?></td>
																	</tr>
																<?php } ?>



															</tbody>
														</table>
													</div>
												</div>
											</div>
											<div class="col-md-12 col-lg-8 col-xl-12">
												<div class="card card-dashboard-eight pb-2">
													<h6 class="card-title">Jadwal Sidang</h6>
													<span class="d-block mg-b-10 text-muted tx-12">Informasi jadwal seminar proposal dan sidang skripsi</span>
													<div class="list-group border-top-0">
														<table class="table table-striped mg-b-0 text-md-nowrap" id="validasitable7">
															<thead>
																<tr>
																	<th class="wd-lg-25p">Periode Sidang</th>
																	<th class="wd-lg-25p">Dibuka Pada</th>
																	<th class="wd-lg-25p">Ditutup Pada</th>
																	<th class="wd-lg-25p">Jenis Sidang</th>
																	<th class="wd-lg-25p">Status</th>
																</tr>
															</thead>
															<tbody>
																<?php foreach ($data_jadwal as $key) { ?>

																	<tr>
																		<td><?= $key->periode ?></td>
																		<td><?= $key->open ?></td>
																		<td><?= $key->expire ?></td>
																		<td><?= $key->jenis_sidang ?></td>
																		<td class="text-success">Dibuka</td>
																	<?php } ?>
																	</tr>
															</tbody>
														</table>
													</div>
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
	</div>
</div>
<?= $this->endSection(); ?>