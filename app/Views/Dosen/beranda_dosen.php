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
						<div class="tabs-menu">
							<ul class="nav panel-tabs me-3">
								<li><a href="#dosen" class="active" data-bs-toggle="tab">Dosen</a></li>
								<li><a href="#tab22" data-bs-toggle="tab">Koorprodi</a></li>
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
														<tr>
															<td class="tx-medium tx-inverse">Adam</td>
															<td class="tx-medium tx-inverse">Pembimbing 1</td>
															<td class="tx-medium tx-inverse">Lulus</td>
														</tr>
														<tr>
															<td class="tx-medium tx-inverse">Nofal</td>
															<td class="tx-medium tx-inverse">Pembimbing 2</td>
															<td class="tx-medium tx-inverse">Progres</td>
														</tr>
														<tr>
															<td class="tx-medium tx-inverse">Wasil</td>
															<td class="tx-medium tx-inverse">Pembimbing 1</td>
															<td class="tx-medium tx-inverse">Progres</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="row row-md row-deck">
											<div class="col-md-12 col-lg-8 col-xl-7">
												<div class="card card-dashboard-eight pb-2">
													<h6 class="card-title">Data Penguji</h6><span class="d-block mg-b-10 text-muted tx-12">Informasi data ujian mahasiswa</span>
													<div class="list-group border-top-0">
														<table class="table table-striped mg-b-0 text-md-nowrap">
															<thead>
																<tr>
																	<th class="wd-lg-25p">Nama Mahasiswa</th>
																	<th class="wd-lg-25p">Sebagai</th>
																	<th class="wd-lg-25p">Tanggal Ujian Seminar Proposal</th>
																	<th class="wd-lg-25p">Tanggal Ujian Sidang Skripsi</th>
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td>Irwan</td>
																	<td>Pembimbing 1</td>
																	<td>20/08/2023</td>
																	<td>18/02/2024</td>
																</tr>
																<tr>
																	<td>Dandi</td>
																	<td>Pembimbing 1</td>
																	<td>20/08/2023</td>
																	<td>18/02/2024</td>
																</tr>
																<tr>
																	<td>Nurul</td>
																	<td>Pembimbing 1</td>
																	<td>20/08/2023</td>
																	<td>18/02/2024</td>
																</tr>
																<tr>
																	<td>Fatim</td>
																	<td>Pembimbing 1</td>
																	<td>20/08/2023</td>
																	<td>18/02/2024</td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
											</div>
											<div class="col-xl-5 col-md-12 col-lg-6">
												<div class="card">
													<div class="card-header pb-1">
														<h3 class="card-title mb-2">Data Mahasiswa</h3>
														<p class="tx-12 mb-0 text-muted">Informasi data mahasiswa yang sudah divalidasi dan tidak divalidasi</p>
													</div>
													<div class="card-body p-0 customers mt-">
														<div class="list-group list-lg-group list-group-flush">
															<div class="list-group-item list-group-item-action" href="#">
																<div class="media mt-0">
																	<div class="media-body">
																		<div class="d-flex align-items-center">
																			<table class="table mg-b-0 text-md-nowrap">
																				<thead>
																					<tr>
																						<th></th>
																						<th class="wd-lg-25p">Nama</th>
																						<th class="wd-lg-25p">Sebagai</th>
																						<th class="wd-lg-25p">Status</th>
																					</tr>
																				</thead>
																				<tbody>
																					<tr>
																						<td class="avatar avatar-lg cover-image" data-bs-image-src="assets/img/faces/3.jpg"></td>
																						<td>Rizki Irwan Pratama</td>
																						<td>Pembimbing 1</td>
																						<td>Diterima</td>
																					</tr>
																					<tr>
																						<td class="avatar avatar-lg cover-image" data-bs-image-src="assets/img/faces/3.jpg"></td>
																						<td>wasil</td>
																						<td>Pembimbing 2</td>
																						<td>Ditolak</td>
																					</tr>
																					<tr>
																						<td class="avatar avatar-lg cover-image" data-bs-image-src="assets/img/faces/3.jpg"></td>
																						<td>Irwan</td>
																						<td>Pembimbing 1</td>
																						<td>Menunggu</td>
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
								<div class="tab-pane" id="tab22">
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
																<th class="wd-lg-25p">NIP</th>
																<th class="wd-lg-25p">Nama Dosen</th>
																<th class="wd-lg-25p">Sebagai Pembimbing</th>
																<th class="wd-lg-25p">Sebagai Penguji</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>197406102008121002</td>
																<td>ABDULLAH BASUKI RAHMAT, S.Si., MT.</td>
																<td>7/10</td>
																<td>10/10</td>
															</tr>
															<tr>
																<td>197406102008121002</td>
																<td>ABDULLAH BASUKI RAHMAT, S.Si., MT.</td>
																<td>7/10</td>
																<td>10/10</td>
															</tr>
															<tr>
																<td>197406102008121002</td>
																<td>ABDULLAH BASUKI RAHMAT, S.Si., MT.</td>
																<td>7/10</td>
																<td>10/10</td>
															</tr>
															<tr>
																<td>197406102008121002</td>
																<td>ABDULLAH BASUKI RAHMAT, S.Si., MT.</td>
																<td>7/10</td>
																<td>10/10</td>
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