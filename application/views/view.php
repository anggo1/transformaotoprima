<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Export Excel Plus Filter Tanggal</title>

	<!-- Include file bootstrap.min.css -->
	<link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" />

	<!-- Load file jquery -->
	<script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
</head>

<body style="padding: 0 20px;">
	<h1>Data Siswa</h1>
	<hr>
	<a href="<?php echo base_url("index.php/siswa/export"); ?>" class="btn btn-primary">
		Export ke Excel
	</a><br><br>

	<div class="table-responsive">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>NIS</th>
					<th>Nama</th>
					<th>Jenis Kelamin</th>
					<th>Alamat</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if (!empty($siswa)) { // Jika data pada database tidak sama dengan empty (alias ada datanya)
					foreach ($siswa as $data) { // Lakukan looping pada variabel siswa dari controller
						echo "<tr>";
						echo "<td>" . $data->nama_owner . "</td>";
						echo "<td>" . $data->alamat . "</td>";
						echo "<td>" . $data->nama_aplikasi . "</td>";
						echo "<td>" . $data->status . "</td>";
						echo "</tr>";
					}
				} else { // Jika data tidak ada
					echo "<tr><td colspan='4'>Data tidak ada</td></tr>";
				}
				?>
			</tbody>
		</table>
	</div>
</body>

</html>