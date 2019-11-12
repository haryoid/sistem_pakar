<?php
$page='';
$str_hal='';
if(isset($_GET['hal'])){
	$page=$_GET['hal'];
	$str_hal=$_GET['hal'];
}
switch($page){
	case 'data_penyakit':$page="include 'includes/p_penyakit.php';";break;
	case 'update_penyakit':$page="include 'includes/p_penyakit_update.php';";break;
	case 'data_gejala':$page="include 'includes/p_gejala.php';";break;
	case 'update_gejala':$page="include 'includes/p_gejala_update.php';";break;
	case 'data_pengetahuan':$page="include 'includes/p_pengetahuan.php';";break;
	case 'update_pengetahuan':$page="include 'includes/p_pengetahuan_update.php';";break;
	case 'diagnosa':$page="include 'includes/p_diagnosa.php';";break;
	case 'diagnosa_ds':$page="include 'includes/p_diagnosa_ds.php';";break;
	case 'dst':$page="include 'includes/p_dst.php';";break;
	case 'hasil':$page="include 'includes/p_hasil.php';";break;
	case 'ubah_password':
		$page="include 'includes/p_ubah_password.php';";
		break;

	default:
		$page="include 'includes/p_home.php';";
		break;
}
$CONTENT_["main"]=$page;

?>
