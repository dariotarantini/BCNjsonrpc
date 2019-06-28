<?php

class BCNCli{
    private $endpoint;
    public function __construct(string $ip, int $port){
        $this->endpoint = 'http://'.$ip.':'.$port.'/json_rpc';
    }
    private function req(string $method, array $data) : array {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            "jsonrpc" => "2.0",
            "id" => "0",
            "method" => $method,
            "params" => $data,
        ]));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json'
        ]);
        $result = json_decode(curl_exec($ch), true);
        if (curl_errno($ch)) throw new Exception("Cannot call JSON-RPC API: ".curl_error($ch));
        curl_close($ch);
        return $result;
    }
    public function __call($name, $arguments) : array{
        return $this->req($name, $arguments[0]);
    }
}
