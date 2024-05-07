<?php
include('database.php');
include('trello.php');

$busca_monitora_os = busca_monitora_os();

if (!empty($busca_monitora_os)) {
    $setor = 'qualidade_monitora_os';
    $autenticacao = autenticacao($setor);
    
    while ($row1 = $busca_monitora_os->fetch_assoc()) { 
        $dados_monitoramento['han_cliente'] = $row1['han_cliente'];
        $dados_monitoramento['han_contrato'] = $row1['han_contrato'];
        $dados_monitoramento['han_franquia'] = $row1['han_franquia'];
        $dados_monitoramento['num_chamados'] = $row1['num_chamados'];
        $dados_monitoramento['mais_antiga'] = $row1['mais_antiga'];
        $dados_monitoramento['mais_recente'] = $row1['mais_recente'];
        $dados_monitoramento['media_dias'] = $row1['media_dias'];
        $dados_monitoramento['dados_telefone'] = busca_telefones($dados_monitoramento['han_cliente']);
        $dados_monitoramento['dados_os'] = busca_os($dados_monitoramento['han_cliente'], $dados_monitoramento['num_chamados']);
        $dados_monitoramento['contrato'] = busca_cliente($dados_monitoramento['han_cliente']);
        adiciona_card($dados_monitoramento, $autenticacao['chave'], $autenticacao['token'], $autenticacao['id_lista'],$setor);
        //echo '<pre>';print_r($dados_monitoramento);
    }
}

?>