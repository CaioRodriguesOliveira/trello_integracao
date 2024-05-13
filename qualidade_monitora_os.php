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

function busca_monitora_os() {
    include('conexao.php');
    $sql = "SELECT 
    LPAD(han_franquia, 2, '0') AS han_franquia, 
    han_cliente, 
    han_contrato, 
    COUNT(handle) AS num_chamados, 
    DATE(MIN(dat_abertura)) mais_antiga, 
    DATE(MAX(dat_abertura)) mais_recente, 
    ROUND(AVG(DATEDIFF(CURDATE(), dat_abertura)),1) media_dias 
FROM 
    cad_chamado
WHERE 
    DATE(dat_abertura) BETWEEN DATE_SUB(CURDATE(), INTERVAL 60 DAY) AND CURDATE() - INTERVAL 1 day
    AND han_chamado_tipo NOT IN (33,42,38,9,60,45,31,29,57,36,35,34,46,54,39, 1,25,29,30,32,50,55)
    AND dat_cancelamento IS NULL 
    AND han_contrato IS NOT NULL
    
GROUP BY 
    han_contrato, han_cliente 
HAVING 
    num_chamados >= 2
    AND mais_recente = CURDATE() - INTERVAL 1 day

ORDER BY 
    han_franquia, han_cliente;";
    
    $result = $conn->query($sql);

    return $result;
}

?>