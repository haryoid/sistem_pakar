<?php
//-- database configurations
$dbhost='localhost';
$dbuser='root';
$dbpass='';
$dbname='cf';
//-- database connections
$db=new mysqli($dbhost,$dbuser,$dbpass,$dbname);
//-- halt and show error message if connection fail
if ($db->connect_error) {
    die('Connect Error ('.$db->connect_errno.')'.$db->connect_error);
}
?>
<h3 class="p2">Diagnosa Penyakit</h3>
<div style="clear:both;height:20px;"></div>
<form method="post">
<!-- menampilkan daftar gejala-->

<p>Silahkan pilih gelaja-gejala yang anda alami.</p>
<table class="table table-striped table-hover table-bordered">
	<thead>
	  <tr>
		<td style="text-align:center;" width="30"><input type="checkbox" id="ckbCheckAll" /></td>
		<td style="text-align:center;" width="30">KODE</td>
		<td style="text-align:center;">NAMA GEJALA</td>
	  </tr>
	</thead>
	<tbody>
<?php
$sqli="SELECT * FROM gejala";
$result=$db->query($sqli);
while($row=$result->fetch_object()){
    echo "<tr><td><input type='checkbox' name='evidence[]' value='{$row->id_gejala}'".(isset($_POST['evidence'])?(in_array($row->id_gejala,$_POST['evidence'])?" checked":""):"")
        ."></td><td>{$row->kode}</td><td>{$row->nama}</td></tr>";

}
?>
</tbody>
</table>

<center>
<button type="submit" name="reset" class="btn btn-danger"> Reset</button>
<button type="submit" name="submit" class="btn btn-primary" value="proses"><i class="icon-ok"></i> Submit Diagnosa</button>
</center>

</form>
<pre>
<?php
    //-- Mengambil Nilai Belief Gejala Yang dipilih
if(isset($_POST['evidence'])){
    if(count($_POST['evidence'])<2){
        echo "Pilih minimal 2 gejala";
    }else{
        $sql = "SELECT GROUP_CONCAT(b.kode), a.mb
            FROM pengetahuan a
            JOIN penyakit b ON a.id_penyakit=b.id_penyakit
            WHERE a.gejala IN(".implode(',',$_POST['evidence']).")
            GROUP BY a.id_gejala";
        $result=$db->query($sql);
        $evidence=array();
        while($row=$result->fetch_row()){
            $evidence[]=$row;
        }
        //--- menentukan environement
        $sql="SELECT GROUP_CONCAT(kode) FROM penyakit";
        $result=$db->query($sql);
        $row=$result->fetch_row();
        $fod=$row[0];

        //--- menentukan nilai densitas
        echo "== MENENTUKAN NILAI DENSITAS ==\n";
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
        echo "== PERANGKINGAN ==\n";
        unset($densitas_baru["&theta;"]);
        arsort($densitas_baru);
        print_r($densitas_baru);

        //--- menampilkan hasil akhir
        echo "== HASIL AKHIR ==\n";
        $codes=array_keys($densitas_baru);
        $final_codes=explode(',',$codes[0]);
        $sql="SELECT GROUP_CONCAT(nama)
        FROM penyakit
        WHERE kode IN('".implode("','",$final_codes)."')";
        $result=$db->query($sql);
        $row=$result->fetch_row();
        echo "Terdeteksi penyakit <b>{$row[0]}</b> dengan derajat kepercayaan ".round($densitas_baru[$codes[0]]*100,2)."%";
    }
}
