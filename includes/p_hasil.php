<?php

$link_list='?hal=hasil';

if(!isset($_SESSION['GEJALA'])){
	exit("<script>location.href='?hal=diagnosa';</script>");
}
$gejala=$_SESSION['GEJALA'];
$penyakit=array();
$cf=array();

# PROSES PERHITUNGAN CF
#tahap pengambilan data gejala
$q=mysql_query("select * from penyakit order by kode");
if(mysql_num_rows($q) > 0){
	while($h=mysql_fetch_array($q)){
		$id=$h['id_penyakit'];
		$penyakit[$id]=array($h['kode'],$h['nama']);
		$mb_lama=0;$md_lama=0;$mb_baru=0;$md_baru=0;$mb_sementara=0;$md_sementara=0;
		$gejala_ke=0;

		$qq=mysql_query("select * from pengetahuan where id_penyakit='".$id."' order by id_pengetahuan");
		while($hh=mysql_fetch_array($qq)){
			if(in_array($hh['id_gejala'],$gejala)){
				$gejala_ke++;
				if($gejala_ke==1){
					$mb_lama=0;$md_lama=0;
					$mb_baru=$hh['mb'];
					$md_baru=$hh['md'];
					$cf_old= $mb_baru * $md_baru;

					#echo $gejala_ke.'<pre> cf old = ';print_r ($cf_old);echo '</pre>';
				}else if ($gejala_ke == 2){
					$mb_baru=$hh['mb'];
					$md_baru=$hh['md'];
					$cf_baru=$mb_baru * $md_baru;
					$cf_co=$cf_old + ($cf_baru * (1-$cf_old));

					#echo  $gejala_ke.'<pre>cf baru ';print_r ($cf_baru);echo '<br>';
					#echo 'cf old = ';print_r ($cf_old);echo '<br>';
					#echo 'cf co = ';print_r ($cf_co);echo '</pre>';

				} else {
					$cf_old=$cf_co;
					$mb_baru=$hh['mb'];
					$md_baru=$hh['md'];
					$cf_baru=$mb_baru * $md_baru;
					$cf_co=$cf_old + ($cf_baru * (1-$cf_old));
					#echo $gejala_ke.'<pre>cf baru ';print_r ($cf_baru);echo '<br>';
					#echo 'cf old ';print_r ($cf_old);echo '<br>';
					#echo 'cf co ';print_r ($cf_co);echo '</pre>';
				}

			}
		}
		if($gejala_ke>0){
			$nilai=round($cf_co,3);
			$nilai_penyakit[$id]=$nilai;
			$cf[]=array($nilai,$id);
		}
	}
}
# URUTKAN NILAI
sort($cf);

$nama_penyakit='';
$daftar='';
$no=0;
for($i=count($cf)-1;$i>=0;$i--){
	if($nama_penyakit==''){$nama_penyakit=$penyakit[$cf[$i][1]][1];}
	$no++;
	$daftar.='
	  <tr>
		<td style="text-align:center;">'.$no.'</td>
		<td>'.$penyakit[$cf[$i][1]][0].'</td>
		<td>'.$penyakit[$cf[$i][1]][1].'</td>
		<td style="text-align:center;">'.($cf[$i][0]*100).' %</td>
		<td style="text-align:center;">'.$no.'</td>
	  </tr>
	';
}

$list_gejala='';
$no=0;
$q=mysql_query("select * from gejala order by kode");
if(mysql_num_rows($q) > 0){
	while($h=mysql_fetch_array($q)){
		if(isset($_SESSION['GEJALA'])){
			if(in_array($h['id_gejala'],$_SESSION['GEJALA'])){
				$no++;
				$list_gejala.='
				  <tr>
					<td valign="top" width="30">'.$no.'</td>
					<td valign="top" width="70">'.$h['kode'].'</td>
					<td valign="top">'.$h['nama'].'</td>
				  </tr>
				';
			}
		}
	}
}

?>
<h3 class="p2">Hasil Diagnosa</h3>
<div style="clear:both;height:20px;"></div>
<p>Gejala-gejala yang anda alami :</p>
<table class="table table-striped table-hover table-bordered">
	<tbody>
		<?php echo $list_gejala;?>
	</tbody>
</table>
<p>Data Analisa</p>
<table class="table table-striped table-hover table-bordered">
	<thead>
		<tr>
			<th style="text-align:center;" width="30">NO</th>
			<th style="text-align:center;" width="100">KODE</th>
			<th style="text-align:center;">NAMA PENYAKIT</th>
			<th style="text-align:center;" width="70">CF</th>
			<th style="text-align:center;" width="70">RANK</th>
		</tr>
	</thead>
	<tbody>
		<?php echo $daftar;?>
	</tbody>
</table>
<table class="table table-bordered">
	<tbody>
	  <tr>
		<td width="150"><strong>Nama Penyakit</strong></td>
		<td><strong><?php echo strtoupper($nama_penyakit);?></strong></td>
	  </tr>
	</tbody>
</table>
<center>
<a href="?hal=diagnosa" class="btn btn-primary">Pilih Gejala/ Kembali</a>
</center>
<br><br><br>
