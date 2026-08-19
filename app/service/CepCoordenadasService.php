<?php

class CepCoordenadasService
{
    public static function cepParaCoordenadas($cep) {
        $cep = urlencode($cep);
        $url = "https://nominatim.openstreetmap.org/search?postalcode={$cep}&country=Brazil&format=json&limit=1";

        $opts = [
            "http" => [
                "header" => "User-Agent: MeuApp/1.0 (aptechsolucoesemti@gmail.com)\r\n"
            ]
        ];
        $context = stream_context_create($opts);

        $resposta = file_get_contents($url, false, $context);
        $dados = json_decode($resposta, true);

        if (!empty($dados)) {
            $latitude = $dados[0]['lat'];
            $longitude = $dados[0]['lon'];
            return ['lat' => $latitude, 'lon' => $longitude];
        }

        return null;


    }

    public static function cidadeParaCoordenadas($cidade, $estado) {
        $location = urlencode("{$cidade}, {$estado}, Brasil");
        $url = "https://nominatim.openstreetmap.org/search?q={$location}&format=json&limit=1";
        
        $opts = [
            "http" => [
                "header" => "User-Agent: Integrador TA Decor/1.0 (aptechsolucoesemti@gmail.com)\r\n"
            ]
        ];
        $context = stream_context_create($opts);

        $resposta = file_get_contents($url, false, $context);

        $dados = json_decode($resposta, true);

       if (!empty($dados)) {
            $latitude = $dados[0]['lat'];
            $longitude = $dados[0]['lon'];
            return ['lat' => $latitude, 'lon' => $longitude];
        }

        return null;
     
    }
}
