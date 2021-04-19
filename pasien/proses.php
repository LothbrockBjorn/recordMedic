<?php
	require_once"../_config/config.php";
	require "../assets/libs/vendor/autoload.php";

	use Ramsey\Uuid\Uuid;
	use Ramsey\Uuid\Exception\UnstatisfiedDependencyException;

	if (isset($_POST['add'])) {
		$uuid = Uuid::uuid4()->toString();
		$identitas = trim(mysqli_real_escape_string($con, $_POST['identitas']));
		$nama = trim(mysqli_real_escape_string($con, $_POST['nama']));
		$jk = trim(mysqli_real_escape_string($con, $_POST['jk']));
		$alamat = trim(mysqli_real_escape_string($con, $_POST['alamat']));
		$telp = trim(mysqli_real_escape_string($con, $_POST['telp']));
		$sql_cek_identitas = mysqli_query($con,"SELECT * FROM tb_pasien WHERE no_identitas ='$identitas'")or die (mysqli_error($con));
		if (mysqli_num_rows($sql_cek_identitas) > 0){
			echo "<script>alert('Nomor identitas sudah di input!');window.location='add.php';</script>";
		}else{
			mysqli_query($con, "INSERT INTO tb_pasien (id_pasien, no_identitas, nama_pasien, jenis_kelamin, alamat, no_telp) VALUES ('$uuid','$identitas','$nama','$jk','$alamat','$telp')")or die(mysqli_error($con));
			echo "<script>window.location='data.php';</script>";
		}
	} elseif (isset($_POST['edit'])) {
		$id = $_POST['id'];
		$identitas = trim(mysqli_real_escape_string($con, $_POST['identitas']));
		$nama = trim(mysqli_real_escape_string($con, $_POST['nama']));
		$jk = trim(mysqli_real_escape_string($con, $_POST['jk']));
		$alamat = trim(mysqli_real_escape_string($con, $_POST['alamat']));
		$telp = trim(mysqli_real_escape_string($con, $_POST['telp']));
		$sql_cek_identitas = mysqli_query($con, "SELECT * FROM tb_pasien WHERE no_identitas ='$identitas' AND id_pasien !='$id'")or die (mysqli_error($con));
		if (mysqli_num_rows($sql_cek_identitas) > 0){
			echo "<script>alert('Nomor identitas sudah di input!');window.location='edit.php?id=$id';</script>";
		}else{
			mysqli_query($con, "UPDATE tb_pasien SET no_identitas = '$identitas', nama_pasien ='$nama', jenis_kelamin ='$jk',
				alamat ='$alamat', no_telp ='$telp' WHERE id_pasien ='$id'")or die(mysqli_error($con));
			echo "<script>window.location='data.php';</script>";
		}
		// upload data exel to directori app
	} elseif (isset($_POST['import'])) {
		$file = $_FILES['file']['name'];
		$ekstensi = explode(".", $file);
		$file_name = "file_".round(microtime(true)).".".end($ekstensi);
		$sumber = $_FILES['file']['tmp_name'];
		$target_dir = "../_file/";
		$target_file = $target_dir.$file_name;
		move_uploaded_file($sumber, $target_file);
		
		$obj = PHPExcel_IOFactory::load($target_file);
		$all_data = $obj->getActiveSheet()->toArray(null, true, true, true);

		$sql = "INSERT INTO tb_pasien (id_pasien, no_identitas, nama_pasien, jenis_kelamin, alamat, no_telp) VALUES"; //sql1
		for ($i=3; $i <= count($all_data); $i++) { 
		$uuid = Uuid::uuid4()->toString();
		$no_id = $all_data[$i]['A'];
		$nama = $all_data[$i]['B'];
		$jk = $all_data[$i]['C'];
		$alamat = $all_data[$i]['D'];
		$telp = $all_data[$i]['E'];	
		$sql .= " ('$uuid', '$no_id', '$nama', '$jk', '$alamat', '$telp'),"; //sql2
		}
		$sql = substr($sql, 0, -1); //menggabungkan script $sql 1 dan $sql 2
		// echo $sql;
		mysqli_query($con, $sql) or die (mysqli_error($con));
		unlink($target_file); //unlink atau hapus file
		echo "<script>window.location='data.php';</script>";
	}
?> 