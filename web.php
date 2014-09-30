<?php

require_once __DIR__.'/simple_html_dom.php';

// pdo
$dbh = null;
$dsn = 'mysql:dbname=web;host=127.0.0.1';
$user = '';
$password = '';

try {
    $dbh = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}


// sql
//$sql = 'SELECT * FROM rules WHERE id = 6';
$sql = 'SELECT * FROM rules';
foreach ($dbh->query($sql) as $row) {

    // Create DOM from URL
    $html = file_get_html($row['url']);
    // target
    $price = $html->find($row['rule'], 0)->plaintext;
    $price = preg_replace('/[^0-9]+/', '', $price);
//var_dump($price);exit;

    // query
 
    $urlhh = str_replace('/', '-', parse_url($row['url'], PHP_URL_PATH));

    $sh_cmd = 'curl -F number='.$price;
    $sh_cmd.= ' -F datetime="'.date('Y-m-d H:i:s').'"';
    $sh_cmd.= ' http://example.com/hrf//api/'.$row['name'];
    $sh_cmd.= '/'.$row['site'].'/'.$urlhh;
//echo $sh_cmd;exit;

    // post
    shell_exec($sh_cmd);
}
