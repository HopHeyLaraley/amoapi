<?php

class AmoApi{
    private $access_token;
    private $domain;

    public function __construct($access_token, $domain) {
        $this->access_token = $access_token;
        $this->domain = $domain;
    }

    private function request($method, $link, $data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-oAuth-client/1.0');
        curl_setopt($curl, CURLOPT_URL, $link);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->access_token,
            'Content-Type: application/json'
        ]);
        if($method === "post"){
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);

        $out = curl_exec($curl);
        curl_close($curl);

        return json_decode($out, true);
    }

    public function get($url){
        $link = "https://{$this->domain}{$url}";
        return $this->request("get", $link);
    }

    public function post($url, $data){
        $link = "https://{$this->domain}{$url}";
        return $this->request("post", $link, $data);
    }
}

?>