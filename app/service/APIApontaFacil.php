<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $url = "https://app.apontafacil.com.br/api/v1/integration/get_registers";

    // Verifica se os parâmetros existem
    if (isset($_GET['data_inicial']) && isset($_GET['data_final'])) {
        $dataInicial = $_GET['data_inicial'];
        $dataFinal = $_GET['data_final'];
    }else{

    }
    
    // Dados que serão enviados via POST
    $dados = [
        'app_key' => '145ae12f71c729fa5da8f35231d2041a30c7ff3f',
        'app_secret' => '515cc34bd30644a0b4dfdaf914f817fd',
        'data_inicial' => $dataInicial,
        'data_final' => $dataFinal
    ];

    // Inicializa o cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $dados);
/*
    // Executa a requisição
    $resposta = curl_exec($ch);

    echo "<pre>";
    var_dump($resposta);
    echo "</pre>";
/*

    // Trata erro, se houver
    if (curl_errno($ch)) {
        http_response_code(500);
        echo json_encode(['erro' => curl_error($ch)]);
    } else {
        header('Content-Type: application/json');
        echo $resposta;
    }

    curl_close($ch);
    */
} else {
    http_response_code(405); // Método não permitido
    echo json_encode(['mensagem' => 'Use GET para acessar esta API']);
}