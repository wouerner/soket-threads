<?php

$line = true;
while ($line) {

    $fp = stream_socket_client("tcp://localhost:4444", $errno, $errstr, 30);
    stream_set_blocking ( $fp, false );

    echo  "-------------------\n";
    echo "Escreva: \n";

    $line = trim(fread(STDIN, 1024));

    fwrite($fp, base64_encode($line));
    //sleep(2);
    //echo   stream_get_contents($fp);

    fclose($fp);
}
