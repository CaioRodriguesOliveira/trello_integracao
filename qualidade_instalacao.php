<?php
include('database.php');
include('trello.php');

$busca_instalacao = busca_instalacao();


if (!empty($busca_instalacao)) {
    $setor = 'qualidade_instalacao';
    $autenticacao = autenticacao($setor);
    while($row = $busca_instalacao->fetch_assoc()) {
        $dados['han_cliente'] = $row['han_cliente'];
        $dados['han_contrato'] = $row['han_contrato'];
        $dados['han_chamado'] = $row['han_chamado'];
        $dados['nom_cliente'] = $row['nom_cliente'];
        $dados['nom_email'] = $row['nom_email'];
        $dados['flg_email_validado'] = $row['flg_email_validado'];
        $dados['han_franquia'] = $row['han_franquia'];
        $dados['nom_franquia'] = $row['nom_franquia'];
        $dados['nom_sigla'] = $row['nom_sigla'];
        $dados['valor_total'] = $row['valor_total'];
        $dados['endereco_inst'] = $row['endereco_inst'];
        $dados['num_mac_roteador'] = $row['num_mac_roteador'];
        $dados['modelo_roteador'] = $row['modelo_roteador'];
        $dados['dat_primeira_instalacao'] = $row['dat_primeira_instalacao'];
        $dados['dados_telefone'] = busca_telefones($dados['han_cliente']);
        $dados['num_mac_id'] = $row['num_mac_id'];
        $dados['nom_plano'] = $row['nom_plano'];
        $dados['dados_os'] = busca_os($dados['han_cliente'], 3);
        $dados['tipo_tecnologia'] = $row['tipo_tecnologia'];
        //echo '<pre>';print_r($dados);
        //echo '<pre>';print_r($autenticacao);die();
        adiciona_card($dados, $autenticacao['chave'], $autenticacao['token'], $autenticacao['id_lista'],$setor);
    }
}
/*
$busca_monitora_os = busca_monitora_os();

if (!empty($busca_monitora_os)) {
    $setor = 'teste_monitora_os';
    $autenticacao = autenticacao($setor);
    $ultimo_han_cliente = null; 
    
    while ($row1 = $busca_monitora_os->fetch_assoc()) {

        if ($row1['han_cliente'] != $ultimo_han_cliente) {
            
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

            $ultimo_han_cliente = $row1['han_cliente'];
        }
        
    }
}

*/
?>