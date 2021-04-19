<?php
	require_once"../_config/config.php";
	require "../assets/libs/vendor/autoload.php";

	use Ramsey\Uuid\Uuid;
	use Ramsey\Uuid\Exception\UnstatisfiedDependencyException;

	if (isset($_POST['add'])) {
		$uuid = Uuid::uuid4()->toString();
		$nama = trim(mysqli_real_escape_string($con, $_POST['nama']));
		$ket = trim(mysqli_real_escape_string($con, $_POST['ket']));
		mysqli_query($con, "INSERT INTO tb_obat (id_obat, nama_obat, ket_obat) VALUES ('$uuid','$nama','$ket')")or die(mysqli_error($con));
		echo "<script>window.location='data.php';</script>";
	} elseif (isset($_POST['edit'])) {
		$id = $_POST['id'];
		$nama = trim(mysqli_real_escape_string($con, $_POST['nama']));
		$ket = trim(mysqli_real_escape_string($con, $_POST['ket']));
		mysqli_query($con, "UPDATE tb_obat SET nama_obat = '$nama' ,ket_obat ='$ket' WHERE id_obat = '$id'") or die(mysqli_error($con));
		echo "<script>window.location='data.php';</script>";
	}
?> 