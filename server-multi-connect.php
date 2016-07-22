<?php

echo "Iniciando o server!\n";

$socket = stream_socket_server("tcp://0.0.0.0:4444", $errno, $errstr);
stream_set_blocking ( $socket, false );

$data = '';
$chat = 'Chats:';

$clients = [];
while (true) {

    $reads = $clients;
    $reads[] = $socket;

    stream_select($reads, $writes, $ex, 30000);

    if (in_array($socket, $reads)) {
        $client = stream_socket_accept($socket);

        if ($client) {
            $name = stream_socket_get_name($client, false);
            echo '[Server] Connected: ', $name . PHP_EOL;
            $clients[] = $client;
        }

        unset($reads[array_search($socket, $reads)]);
    }

    foreach ($reads as $sock) {
        $data = base64_decode(fread($sock, 128));

        if ($data == 'oi') {

            fwrite($sock, 'Comando oi' . PHP_EOL);
            unset($clients[array_search($sock, $clients)]);
            fclose($sock);
            echo 'client desconnect'.PHP_EOL;
            continue;
        }

        if (!$data) {
            fwrite($sock, 'teste'.$chat . PHP_EOL);
            unset($clients[array_search($sock, $clients)]);
            fclose($sock);
            echo 'client desconnect'.PHP_EOL;
            continue;
        }

        $chat .= " \n[Client]  " . $data;
        echo $chat . " \n";
        unset($clients[array_search($sock, $clients)]);
        fwrite($sock, $chat . PHP_EOL);

        fclose($sock);
    }
}

fclose($socket);
echo '[Server] Fechando o server!/n';
