<?php
if(isset($_SESSION['GEJALA_DS'])){
	if(count($_POST['GEJALA_DS'])<2){
		echo "Pilih minimal 2 gejala";
	}else{
		$sql = "SELECT GROUP_CONCAT(b.kode), a.mb
			FROM pengetahuan a
			JOIN penyakit b ON a.id_penyakit=b.id
			WHERE a.id_gejala IN(".implode(',',$_POST['GEJALA_DS']).")
			GROUP BY a.id_gejala";
		$result=$db->query($sql);
		$evidence=array();
		while($row=$result->fetch_row()){
			$evidence[]=$row;
		}
		//-- masukkan kode perhitungannya di sini
    //--- menentukan environement
$sql="SELECT GROUP_CONCAT(kode) FROM penyakit";
$result=$db->query($sql);
$row=$resul->fecth_row();
$fod=$row[0];

//--- menentukan nilai densitas
$densitas_baru=array();
while(!empty($evidence)){
	$densitas1[0]=array_shift($evidence);
	$densitas1[1]=array($fod,1-$densitas1[0][1]);
	$densitas2=array();
	if(empty($densitas_baru)){
		$densitas2[0]=array_shift($evidence);
	}else{
		foreach($densitas_baru as $k=>$r){
			if($k!="&theta;"){
				$densitas2[]=array($k,$r);
			}
		}
	}
	$theta=1;
	foreach($densitas2 as $d) $theta-=$d[1];
	$densitas2[]=array($fod,$theta);
	$m=count($densitas2);
	$densitas_baru=array();
	for($y=0;$y<$m;$y++){
		for($x=0;$x<2;$x++){
			if(!($y==$m-1 && $x==1)){
				$v=explode(',',$densitas1[$x][0]);
				$w=explode(',',$densitas2[$y][0]);
				sort($v);
				sort($w);
				$vw=array_intersect($v,$w);
				if(empty($vw)){
					$k="&theta;";
				}else{
					$k=implode(',',$vw);
				}
				if(!isset($densitas_baru[$k])){
					$densitas_baru[$k]=$densitas1[$x][1]*$densitas2[$y][1];
				}else{
					$densitas_baru[$k]+=$densitas1[$x][1]*$densitas2[$y][1];
				}
			}
		}
	}
	foreach($densitas_baru as $k=>$d){
		if($k!="&theta;"){
			$densitas_baru[$k]=$d/(1-(isset($densitas_baru["&theta;"])?$densitas_baru["&theta;"]:0));
		}
	}
	print_r($densitas_baru);
}

//--- perangkingan
unset($densitas_baru["&theta;"]);
arsort($densitas_baru);
print_r($densitas_baru);


//--- menampilkan hasil akhir
$codes=array_keys($densitas_baru);
$sql="SELECT GROUP_CONCAT(nama)
	FROM penyakit
	WHERE kode IN('{$codes[0]}')";
$result=$db->query($sql);
$row=$result->fetch_row();
echo "Terdeteksi penyakit <b>{$row[0]}</b> dengan derajat kepercayaan ".round($densitas_baru[$codes[0]]*100,2)."%";

	}
}
