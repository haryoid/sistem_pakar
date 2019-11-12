<?php

$link_list='?hal=data_pengetahuan';
$link_update='?hal=update_pengetahuan';

$daftar='';$no=0;
$q="select * from pengetahuan order by id_pengetahuan";
$q=mysql_query($q);
if(mysql_num_rows($q) > 0){
	while($h=mysql_fetch_array($q)){
		$no++;
		$id=$h['id_pengetahuan'];
		$allow_del=true;
		//if(mysql_num_rows(mysql_query("select * from pengetahuan where id_gejala='".$id."' limit 0,1"))>0){$allow_del=false;}
		if($allow_del){
			$disabled='';
			$delete_link='DeleteConfirm(\''.$link_update.'&amp;id='.$id.'&amp;action=delete\');';
		}else{
			$disabled='disabled';
			$delete_link='';
		}
		$qq=mysql_query("select * from penyakit where id_penyakit='".$h['id_penyakit']."'");
		$hh=mysql_fetch_array($qq);
		$penyakit=$hh['nama'];
		$qq=mysql_query("select * from gejala where id_gejala='".$h['id_gejala']."'");
		$hh=mysql_fetch_array($qq);
		$gejala=$hh['nama'];


		$daftar.='
		  <tr>
			<td style="text-align:center;">'.$no.'</td>
			<td>'.$penyakit.'</td>
			<td>'.$gejala.'</td>
			<td style="text-align:center;">'.$h['mb'].'</td>
			<td style="text-align:center;">'.$h['md'].'</td>
			<td style="text-align:center;">
			<a href="'.$link_update.'&amp;id='.$id.'&amp;action=edit" class="btn btn-primary">edit</a>
			<a href="#" onclick="'.$delete_link.'return(false);" class="btn btn-danger '.$disabled.'">delete</a>
			</td>
		  </tr>
		';
	}
}


?>
<script language="javascript">
function DeleteConfirm(url){
	if (confirm("Anda yakin akan menghapus data ini ?")){
		window.location.href=url;
	}
}
</script>

<h3 class="p2">Data Basis Pengetahuan</h3>
<div style="clear:both;height:20px;"></div>
<a href="<?php echo $link_update;?>" class="btn btn-primary" style="float:right"><i class="icon-plus"></i> Tambah data basis pengetahuan</a><br /><br />
<table class="table table-striped table-hover table-bordered">
	<thead>
		<tr>
			<th style="text-align:center;">NO</th>
			<th style="text-align:center;">NAMA PENYAKIT</th>
			<th style="text-align:center;">NAMA GEJALA</th>
			<th style="text-align:center;">CF pakar</th>
			<th style="text-align:center;">CF user</th>
			<th align="right">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php echo $daftar;?>
	</tbody>
</table>
