<?php
$link_list='?hal=diagnosa_ds';

if(isset($_POST['submit'])){
	$gejala='';
	if(isset($_POST['gejala_ds'])){
		$gejala=$_POST['gejala_ds'];
	}
	if(empty($gejala)){
		$error='Silahkan pilih gelaja terlebih dahulu.';
	}else{
		$_SESSION['GEJALA_DS']=$gejala;
		exit("<script>location.href='?hal=hasil_ds';</script>");

	}
}
if(isset($_POST['reset'])){
	if(isset($_SESSION['GEJALA_DS'])){
		unset($_SESSION['GEJALA_DS']);
	}
	exit("<script>location.href='?hal=diagnosa_ds';</script>");

}

$list_gejala='';
$no=0;
$q=mysql_query("select * from gejala order by kode");
if(mysql_num_rows($q) > 0){
	while($h=mysql_fetch_array($q)){
		if(isset($_SESSION['GEJALA_DS'])){
			if(in_array($h['id_gejala'],$_SESSION['GEJALA_DS'])){$c='checked';}else{$c='';}
		}else{
			$c='';
		}
		$no++;
		$list_gejala.='
		  <tr>
			<td style="text-align:center;" width="30"><input name="gejala_ds[]" type="checkbox" class="checkboxes" '.$c.' value="'.$h['id_gejala'].'" /></td>
			<td style="text-align:center;" width="30">'.$no.'</td>
			<td>'.$h['nama'].'</td>
		  </tr>
		';
	}
}


?>

<h3 class="p2">Diagnosa Penyakit</h3>
<div style="clear:both;height:20px;"></div>
<form action="" name="" method="post" enctype="multipart/form-data">
<?php
if(!empty($error)){
	echo '
	   <div class="alert alert-error ">
		  '.$error.'
	   </div>
	';
}
?>

<p>Silahkan pilih gelaja-gejala yang anda alami.</p>
<table class="table table-striped table-hover table-bordered">
	<thead>
	  <tr>
		<td style="text-align:center;" width="30"><input type="checkbox" id="ckbCheckAll" /></td>
		<td style="text-align:center;" width="30">NO</td>
		<td style="text-align:center;">NAMA GEJALA</td>
	  </tr>
	</thead>
	<tbody>
		<?php echo $list_gejala;?>
	</tbody>
</table>
<center>
<button type="submit" name="reset" class="btn"> Reset</button>
<button type="submit" name="submit" class="btn"><i class="icon-ok"></i> Submit Diagnosa</button>
</center>

</form>

<script>
jQuery(document).ready(function() {
	$("#ckbCheckAll").click(function () {
		if($(this).prop("checked")==true){
			$(".checkboxes").attr("checked",true);
			$(".checkboxes").parent("span").attr("class","checked");
		}else{
			$(".checkboxes").attr("checked",false);
			$(".checkboxes").parent("span").attr("class","");
		}
    });
})
</script>
