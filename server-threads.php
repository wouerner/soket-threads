<?php

class WorkerThreads extends \Thread
{
    private $workerId;
    private $socket;

    public function __construct($id, $socket = null)
    {
        $this->workerId = $id;
        $this->socket = $socket;
    }

    public function run()
    {
        echo "Worker {$this->workerId} ran" . PHP_EOL;

        fwrite($this->socket, 'In proccess' . PHP_EOL);
        sleep(rand(0, 10));
    }
}

echo "Iniciando o server!\n";

$socket = stream_socket_server("tcp://0.0.0.0:4444", $errno, $errstr);
stream_set_blocking ( $socket, false );

$data = '';

$result = '';

while ($conn = stream_socket_accept($socket)) {

    $result = base64_decode(fread($conn, 13421772));
    echo 'recebido:' . $result . "\n";

    //$data .= '[server] : ' . $result . "\n";

    //fwrite($conn, $data . PHP_EOL);

    $workers = new WorkerThreads(1,$conn, $result);
    $workers->start();
    $workers->join();

    fclose($conn);
}

fclose($socket);
echo 'Fechando o server!/n';
