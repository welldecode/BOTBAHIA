<?php

class Cotacao {
    const BASE_URL = 'https://economia.awesomeapi.com.br/json/last/';
    
    public function get($resource) {
        $endpoint = self::BASE_URL . $resource;

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $endpoint,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'GET'
        ]);
        
        $response =  curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true);
    }

    public function consultarCotacao($moedaA, $moedaB) {
        $resource = $moedaA . '-' . $moedaB;
        return $this->get($resource);
    }
}
