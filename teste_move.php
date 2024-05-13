<?php
$id_card = '663d30e348d998bc22c01350';
$id_list = '662ba4494bb7bdb7dec8aba8';
$key = '82dfca12f57642dc8ff7c2756257157d';
$token = 'ATTA7a46e863bde7d1fb67c32686597d8db8445ce1bfebc7fc9c43953a2d2af9c42133F3B906';

$url = "https://api.trello.com/1/cards/".$id_card."?idList=".$id_list."&key=".$key."&token=".$token;

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);

$ch_move = curl_init($url);
$response_move = curl_exec($ch_move);
$httpCode_move = curl_getinfo($ch_move, CURLINFO_HTTP_CODE);
if ($httpCode_move == 200) {
    echo "Card movido para a lista de destino com sucesso.\n";

    $url_get_card = "https://api.trello.com/1/cards/$id_card?key=$key&token=$token";
    $ch_get_card = curl_init($url_get_card);
    curl_setopt($ch_get_card, CURLOPT_RETURNTRANSFER, true);
    $response_get_card = curl_exec($ch_get_card);
    $card_data = json_decode($response_get_card, true);
    $card_name = $card_data['name'];

    echo "Nome do card: $card_name\n";
} else {
    echo "Erro ao mover o card: $response_move";
}

curl_close($ch_move);


?>