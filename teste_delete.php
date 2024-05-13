<?php


$url = "https://api.trello.com/1/cards/663d2fd06f00570cd9db0b4f?key=82dfca12f57642dc8ff7c2756257157d&token=ATTA7a46e863bde7d1fb67c32686597d8db8445ce1bfebc7fc9c43953a2d2af9c42133F3B906";

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);


?>