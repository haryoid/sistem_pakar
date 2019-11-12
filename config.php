<?php
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'cf';

$web_host='http://localhost/sistem_pakar';

$link=mysql_connect($db_host,$db_user,$db_password) or die('Koneksi ke server database gagal.');
mysql_select_db($db_name,$link) or die('Database tidak ditemukan.');

?>
