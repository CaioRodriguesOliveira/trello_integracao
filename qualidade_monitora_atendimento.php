<?php 
include('database.php');
include('trello.php');

$busca_monitora_atendimento = busca_monitora_atendimento();



if (!empty($busca_monitora_atendimento)) {
    $setor = 'qualidade_monitora_atendimento';
    $autenticacao = autenticacao($setor);
    while($row1 = $busca_monitora_atendimento->fetch_assoc()) {
        $dados['han_cliente'] = $row1['han_cliente'];
        $dados['num_atendimentos'] = $row1['num_atendimentos'];
        $dados['mais_antiga'] = $row1['mais_antiga'];
        $dados['mais_recente'] = $row1['mais_recente'];
        $dados['media_dias'] = $row1['media_dias'];
        $dados['dados_telefone'] = busca_telefones($dados['han_cliente']);
        //$dados['atendimentos'] = busca_atendimentos($dados['han_cliente'], $dados['num_atendimentos']);
        $dados['atendimentos'] = busca_atendimentos($dados['han_cliente'], 5);
        //$dados['dados_os'] = busca_os($dados['han_cliente'], $dados['num_atendimentos']);
        $dados['dados_os'] = busca_os($dados['han_cliente'], 5);
        $dados['contrato'] = busca_cliente($dados['han_cliente']);
        //echo '<pre>';print_r($dados);
        
        //echo '<pre>';print_r($autenticacao);die();
        adiciona_card($dados, $autenticacao['chave'], $autenticacao['token'], $autenticacao['id_lista'],$setor);
    }
}

function busca_monitora_atendimento() {
    include('conexao.php');
    $sql = "SELECT  
    han_cliente, 
    num_contrato, 
    COUNT(handle) AS num_atendimentos, 
    DATE(MIN(dat_fechamento)) mais_antiga, 
    DATE(MAX(dat_fechamento)) mais_recente, 
    ROUND(AVG(DATEDIFF(CURDATE(), dat_fechamento)),1) media_dias 
    FROM 
        callcenter.cad_atendimentos
    WHERE 
        DATE(dat_fechamento) BETWEEN DATE_SUB(CURDATE(), INTERVAL 60 DAY) AND CURDATE() - INTERVAL 1 day
        AND num_contrato IS NOT NULL
        
    GROUP BY 
        num_contrato, han_cliente 
    HAVING 
        num_chamados >= 2
        AND mais_recente = CURDATE() - INTERVAL 1 day

    ORDER BY 
        han_cliente;";

    $result = $conn->query($sql);
    print_r($result);

    return $result;
}

?>