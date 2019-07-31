<?php
    session_start();

    $iplogfile = '/var/www/html/php/log.txt';
    $ipaddress = $_SERVER['REMOTE_ADDR'];
    $timestamp = date('d/m/Y H:i:s');
    $fp = fopen($iplogfile, 'a+') or die("can't open file");
    chmod($iplogfile, 0777);
    fwrite($fp, '['.$timestamp.']: '.'   logout          '.$_SESSION['user_id'].' '.$ipaddress.' '. "\n");
    fclose($fp);

    session_destroy();
?>
<meta http-equiv="refresh" content="0;url=../index.php" />
