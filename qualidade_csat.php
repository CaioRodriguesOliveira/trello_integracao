<?php
include('database.php');
include('trello.php');

$dados = array();
$busca_nps = busca_nps();
//print_r($busca_nps);

    if(!empty($busca_nps)) {
        //$setor = 'qualidade_nps';
        while($row = $busca_nps->fetch_assoc()) {
            

            //echo '<pre>';print_r($row);
            //echo '<br><pre>';print_r($autenticacao);
            //echo '<pre>';print_r($row);
            $dados['nome_contato'] = $row['nome_informado'];
            if($row['nota'] == 1) {
                $dados['nota'] = '1 - Muito Insatisfeito';
            } else {
                $dados['nota'] = '2 - Insatisfeito';
            }
            
            if ($row['nome_informado'] == '') {
                $dados['nome_informado'] = 'Nome não Informado';
            } else {
                $dados['nome_informado'] = 'Nome Informado';
            }
            $dados['nome_cliente'] = $row['nome_cliente'];
            $dados['franquia'] = $row['franquia'];
            $dados['data'] = $row['data'];
            $dados['fone'] = $row['fone'];
            $dados['handle'] = $row['handle'];
            $dados['motivo'] = $row['motivo'];
            $dados['tipo'] = $row['tipo'];
            $dados['nom_franquia'] = $row['nom_franquia'];
            //$dados['tipo'] = $row['tipo'];
            $dados['han_cliente'] = $row['han_cliente'];
            $dados['email'] = $row['email'];
            $autenticacao = autenticacao('qualidade_nps_chamado');
            if ($dados['tipo'] == 'OS') {
                $setor = 'qualidade_nps_chamado';
                $autenticacao['id_lista'] = '66353cacaf7b6aa8cb86e37a';
                $dados['dados_busca'] = busca_os_nps($dados['handle']);
                //$dados['dados_busca'] = busca_os_nps(900070);
            } else {
                $setor = 'qualidade_nps_atendimento'; 
                $autenticacao['id_lista'] = '66421e394c76117ff4eebc32';
                $dados['dados_busca'] = busca_atendimentos_nps($dados['handle']);
                //$dados['dados_busca'] = busca_atendimentos_nps(32);
            }
            $dados['contrato'] = busca_cliente($dados['han_cliente']);
            $dados['dados_telefone'] = busca_telefones($dados['han_cliente']);
            echo '<pre>';print_r($dados);
            
            adiciona_card($dados,$autenticacao['chave'],$autenticacao['token'],$autenticacao['id_lista'],$setor);
            
        }
    }

    function busca_nps() {
        include('conexao.php');
        $sql = 'SELECT 
        p.data,
        IF(p.pesquisa = 3, "Atendimento", "OS") AS tipo,
        c.han_franquia AS franquia,
        f.nom_franquia,
        p.nome AS nome_informado,
        c.nom_cliente AS nome_cliente,
        p.email,
        p.fone,
        p.nota, 
        p.motivo,  
        IF(p.pesquisa = 3, atend.handle, chama.handle) AS handle,
        IF(p.pesquisa = 3, atend.han_usuario, chama.han_usuario_tecnico) AS usuario,
        au.nom_usuario AS nome_usuario,
        SUBSTRING(p.id, 8, 7) + 0 AS han_cliente  
    FROM cad_cliente c
    INNER JOIN pesquisa_nps_rel p ON c.handle = SUBSTRING(p.id, 8, 7)
    LEFT JOIN callcenter.cad_atendimentos atend ON SUBSTRING(p.id, 1, 7) = atend.handle AND p.pesquisa = 3 
    LEFT JOIN cad_chamado chama ON SUBSTRING(p.id, 1, 7) = chama.handle AND p.pesquisa = 2 
    LEFT JOIN cad_franquia f ON c.han_franquia = f.handle
    LEFT JOIN adm_usuarios au ON IF(p.pesquisa = 3, atend.han_usuario, chama.han_usuario_tecnico) = au.handle
    WHERE 
        p.data BETWEEN DATE_ADD(NOW(), INTERVAL - 6 MINUTE) AND DATE_ADD(NOW(), INTERVAL 1 MINUTE)
        AND p.nota <= 2;
    ';

        $result = $conn->query($sql);

        //print_r($result);

        return $result;

}

?>