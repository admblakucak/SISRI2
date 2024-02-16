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
					<div class="breadcrumb-header justify-content-between">
						<div class="left-content">
							<h2 class="main-content-title tx-24 mg-b-1">Beranda Dosen</h2>
							<p class="mg-b-0">Selamat Datang di Beranda Dosen</p>
						</div>
					</div>
				</div>
				<div class="col-md-12 col-lg-8 col-xl-12">
					<div class="card card-table-two">
						<div class="d-flex justify-content-between">
							<h4 class="card-title mb-1">Informasi Mahasiswa Bimbingan</h4>
							<i class="mdi mdi-dots-horizontal text-gray"></i>
						</div>
						<span class="tx-12 tx-muted mb-3 ">Tabel informasi mahasiswa melakukan bimbingan</span>
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
										<td>Adam</td>
										<td class="tx-medium tx-inverse">Pembimbing 1</td>
										<td class="tx-medium tx-inverse">Lulus</td>
									</tr>
									<tr>
										<td>Nofal</td>
										<td class="tx-medium tx-inverse">Pembimbing 2</td>
										<td class="tx-medium tx-inverse">Progres</td>
									</tr>
									<tr>
										<td>Wasil</td>
										<td class="tx-medium tx-inverse">Pembimbing 1</td>
										<td class="tx-medium tx-inverse">Progres</td>
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
<?= $this->endSection(); ?>