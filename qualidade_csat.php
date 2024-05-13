<?php
include('database.php');
include('trello.php');


$busca_csat = busca_avaliacao_percepcao();
print_r($busca_csat);


if (!empty($busca_csat)) {
    while ($row = $busca_csat->fetch_assoc()) {   
    
        $dados['handle'] = $row['handle'];
        $dados['han_cliente'] = $row['han_cliente'];
        $dados['protocolo'] = $row['protocolo'];
        $dados['num_contrato'] = $row['num_contrato'];
        $dados['dat_fechamento'] = $row['dat_fechamento'];
        $dados['sistema_origem'] = $row['sistema_origem'];
        $dados['dsc_atendimento'] = $row['dsc_atendimento'];
        $dados['avaliacao_csat'] = $row['avaliacao_csat'];
        
        if ($dados['avaliacao_csat'] == '1') {
            $dados['avaliacao_csat'] = 'Muito Insatisfeito';
        } elseif ($dados['avaliacao_csat'] == '2') {
            $dados['avaliacao_csat'] = 'Insatisfeito';
        } elseif ($dados['avaliacao_csat'] == '3') {
            $dados['avaliacao_csat'] = 'Neutro';
        } elseif ($dados['avaliacao_csat'] == '4') {
            $dados['avaliacao_csat'] = 'Satisfeito';
        } else {
            $dados['avaliacao_csat'] = 'Não Avaliado';
        }
        
        $dados['dsc_csat'] = $row['dsc_csat'];
        $dados['dados_telefone'] = busca_telefones($dados['han_cliente']);
        $dados['contrato'] = busca_cliente($dados['han_cliente']);
        $dados['atendimentos'] = busca_atendimentos($dados['han_cliente'], 5);
        if ($dados['avaliacao_csat'] == 'Insatisfeito') {
            $setor = 'qualidade_percepcao_2';
        } else 
            $setor = 'qualidade_percepcao';
            $autenticacao = autenticacao($setor);
        //echo '<pre>';print_r($dados);
        adiciona_card($dados, $autenticacao['chave'], $autenticacao['token'], $autenticacao['id_lista'],$setor);

    }
}

function busca_avaliacao_percepcao() {
    include('conexao.php');

    $sql = "SELECT * 
            FROM callcenter.cad_atendimentos ca
            WHERE ca.avaliacao_csat <= 2
            AND ca.dat_fechamento between date_add(NOW(), INTERVAL - 6 minute) AND date_add(NOW(), INTERVAL - 1 MINUTE);";

    $result = $conn->query($sql);

    return $result;
}

?>