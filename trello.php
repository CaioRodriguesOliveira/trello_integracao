<?php
include('controlador.php');

function adiciona_card($dados,$key,$token,$idList,$setor) {
    switch ($setor) {
        case 'qualidade_instalacao':
            $titulo_card = urlencode($dados['han_chamado'].' - '.$dados['nom_cliente']. ' - Franquia: '. $dados['nom_sigla']);
            $desc_card = descricao_qualidade_instalacao($dados);
            break;
        case 'qualidade_monitora_os':
            $titulo_card = urlencode($dados['contrato']['nom_cliente']. ' - Franquia: '. $dados['han_franquia'].' - Qnt OS Abertas: '.$dados['num_chamados']);
            $desc_card = descricao_qualidade_os($dados);
            break;
        case 'qualidade_percepcao':
            //echo'<pre>';print_r($dados);
            $titulo_card = urlencode($dados['contrato']['nom_cliente']. ' - Franquia: '. $dados['contrato']['han_franquia']);
            $desc_card = descricao_qualidade_percepcao($dados);
            break;
        case 'qualidade_percepcao_2':
            //echo'<pre>';print_r($dados);
            $titulo_card = urlencode($dados['contrato']['nom_cliente']. ' - Franquia: '. $dados['contrato']['han_franquia']);
            $desc_card = descricao_qualidade_percepcao($dados);
            break;
        case 'qualidade_nps_chamado':
            $titulo_card = urlencode($dados['nome_cliente']. ' - ' .$dados['nome_informado']);
            $desc_card = descricao_qualidade_nps($dados);
            //echo '<pre>';print_r($dados);
            break;
        case 'qualidade_nps_atendimento':
            $titulo_card = urlencode($dados['nome_cliente']. ' - ' .$dados['nome_informado']);
            $desc_card = descricao_qualidade_nps($dados);
            //echo '<pre>';print_r($dados);
            break;
        case 'qualidade_monitora_atendimento':
            $titulo_card = urlencode($dados['contrato']['nom_cliente']. ' - Franquia: '. $dados['contrato']['han_franquia'].' - Qnt Atendimentos : '.$dados['num_atendimentos']);
            $desc_card = descricao_monitora_atendimentos($dados);
            break;
        case 'qualidade_refidelizacao':
            $titulo_card = urlencode($dados['han_chamado'].' - '.$dados['nom_cliente']. ' - Franquia: '. $dados['nom_sigla']);
            $desc_card = descricao_qualidade_refidelizacao($dados);
            break;
        case 'qualidade_csat_voz':
            $titulo_card = urlencode($dados['avaliador']. ' - Nota: '. $dados['nota']);
            $desc_card = descricao_qualidade_csat_voz($dados);
            break;
    }
	
    $url = "https://api.trello.com/1/cards?key=".$key."&token=".$token."&name=".$titulo_card."&desc=".$desc_card."&idList=".$idList."";
	
	$curl = curl_init();

	curl_setopt_array($curl, array(
	  CURLOPT_URL => $url,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => '',
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 0,
	  CURLOPT_FOLLOWLOCATION => true,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_CUSTOMREQUEST => 'POST',
	  
	));

	$response = curl_exec($curl);

    // Verifica se houve algum erro na requisição
    if(curl_error($curl)) {
        echo '<br>Erro ao executar a requisição: ' . curl_error($curl);
    } else {
        // Exibe a resposta da API do Trello (ID do novo card)
        //echo '<br>ID do novo card: ' . $response;
    }
    // Fecha a sessão cURL

    curl_close($curl);
}
/*
function adicionar_card_monitora_os($dados, $qualidade_os = null) {
    //include('controlador.php');
    if ($qualidade_os == null) {
        $titulo_card = urlencode($dados['han_cliente']. ' - Franquia: '. $dados['han_franquia']);
        //$desc_card = 'teste';
        //echo '<pre>';print_r($dados);die();
        $desc_card = descricao_qualidade_os($dados);


        $url = "https://api.trello.com/1/cards?key=".$key."&token=".$token."&name=".$titulo_card."&desc=".$desc_card."&idList=".$idList."";
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        if(curl_error($curl)) {
            echo '<br>Erro ao executar a requisição: ' . curl_error($curl);
        } else {
            //echo '<br>ID do novo card: ' . $response;
        }
        curl_close($curl);
    }
}



/*

//echo '<pre>';print_r($dados['nom_cliente']);
    //print_r($busca_instalacao);

    // QUALIDADE -----------------------------

    //token Qualidade
    //ATTA5ea2d1ef93ed9d3593a1039bc36a09f58b0454e3508cc1e0d756062257cf7f00E36713D1

    //key Qualidade
    //9dfc52cc0a0826e7262aeafd98db744c
    
    //idList Qualidade 
    //662fdd21a22e3b216b1283e4
    // ---------------------------------------
    
    //token ZAPI Sistemas
    //ATTA7a46e863bde7d1fb67c32686597d8db8445ce1bfebc7fc9c43953a2d2af9c42133F3B906
    
    //key ZAPI Sistemas
    //82dfca12f57642dc8ff7c2756257157d
    
    //id trello teste
    //662ba4494bb7bdb7dec8aba6
    //-----------------------------------------

    //echo '<pre>';print_r($url);
    
    */



?>
