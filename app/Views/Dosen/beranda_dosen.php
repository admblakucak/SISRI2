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
							<ul class="nav panel-tabs me-3">
								<li><a href="#dosen" class="active" data-bs-toggle="tab">Dosen</a></li>
								<li><a href="#korprodi" data-bs-toggle="tab">Koorprodi</a></li>
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
												<table class="table table-striped table-bordered mb-0 text-sm-nowrap text-lg-nowrap text-xl-nowrap">
													<thead>
														<tr>
															<th class="wd-lg-25p">Nama Mahasiswa</th>
															<th class="wd-lg-25p">Sebagai</th>
															<th class="wd-lg-25p">Status</th>
														</tr>
													</thead>
													<tbody>
														<?php foreach ($data_mhs_bimbingan as $key) { ?>
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
														<table class="table table-striped mg-b-0 text-md-nowrap">
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

																	$jadwal_sidang = $db->query("SELECT waktu_sidang FROM `tb_pendaftar_sidang` WHERE id_pendaftar=" . $key_2['id_pendaftar'])->getResult();
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
														<table class="table table-striped mg-b-0 text-md-nowrap">
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

																	$jadwal_sidang = $db->query("SELECT waktu_sidang FROM `tb_pendaftar_sidang` WHERE id_pendaftar=" . $key_2['id_pendaftar'])->getResult();
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
													<table class="table table-striped table-bordered mb-0 text-sm-nowrap text-lg-nowrap text-xl-nowrap">
														<thead>
															<tr>
																<th class="wd-lg-25p text-center">Angkatan</th>
																<th class="wd-lg-25p text-center">Lulus</th>
																<th class="wd-lg-25p text-center">Jumlah Seminar Proposal</th>
																<th class="wd-lg-25p text-center">Jumlah Sidang Skripsi</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td class="text-center">2016/2017 Gasal</td>
																<td class="text-center">50/100</td>
																<td class="text-center">72/100</td>
																<td class="text-center">62/100</td>

															</tr>
															<tr>
																<td class="text-center">2017/2018 Gasal</td>
																<td class="text-center">54/100</td>
																<td class="text-center">80/100</td>
																<td class="text-center">73/100</td>
															</tr>
															<tr>
																<td class="text-center">2018/2019 Gasal</td>
																<td class="text-center">20/100</td>
																<td class="text-center">63/100</td>
																<td class="text-center">52/100</td>
															</tr>
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
													<table class="table table-striped mg-b-0 text-md-nowrap">
														<thead>
															<tr>
																<th class="wd-lg-25p text-center">NIP</th>
																<th class="wd-lg-25p text-center">Nama Dosen</th>
																<th class="wd-lg-25p text-center">Sebagai Pembimbing</th>
																<th class="wd-lg-25p text-center">Sebagai Penguji</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td class="text-center">197406102008121002</td>
																<td class="text-center">ABDULLAH BASUKI RAHMAT, S.Si., MT.</td>
																<td class="text-center">7/10</td>
																<td class="text-center">10/10</td>
															</tr>
															<tr>
																<td class="text-center">197406102008121002</td>
																<td class="text-center">ABDULLAH BASUKI RAHMAT, S.Si., MT.</td>
																<td class="text-center">7/10</td>
																<td class="text-center">10/10</td>
															</tr>
															<tr>
																<td class="text-center">197406102008121002</td>
																<td class="text-center">ABDULLAH BASUKI RAHMAT, S.Si., MT.</td>
																<td class="text-center">7/10</td>
																<td class="text-center">10/10</td>
															</tr>
															<tr>
																<td class="text-center">197406102008121002</td>
																<td class="text-center">ABDULLAH BASUKI RAHMAT, S.Si., MT.</td>
																<td class="text-center">7/10</td>
																<td class="text-center">10/10</td>
															</tr>
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
													<table class="table table-striped mg-b-0 text-md-nowrap">
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
															<tr>
																<td>Sidang Sempro Maret 2024</td>
																<td>2024-03-04 11:27:00</td>
																<td>2024-03-10 11:27:00</td>
																<td>Proposal</td>
																<td class="text-success">Dibuka</td>
															</tr>
															<tr>
																<td>Sidang Sempro Mei 2023</td>
																<td>2023-05-04 11:27:00</td>
																<td>2023-05-10 11:27:00</td>
																<td>Proposal</td>
																<td class="text-danger">Ditutup</td>
															</tr>
														</tbody>
													</table>
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
		</div>
	</div>
</div>
<?= $this->endSection(); ?>