<?php
include('database.php');
include('trello.php');

$dados = array();
$busca_nps = busca_nps();
//print_r($busca_nps);

    if(!empty($busca_nps)) {
        //$setor = 'qualidade_nps';
        while($row = $busca_nps->fetch_assoc()) {
            echo '<pre>';print_r($row);
            if($row['tipo'] == 'OS') {
                $setor = 'qualidade_nps_chamado';
                $autenticacao = autenticacao($setor);
            } else {
                $setor = 'qualidade_nps_atendimento';
                $autenticacao = autenticacao($setor);
            }
            echo '<br><pre>';print_r($autenticacao);
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
            $dados['data'] = $row['data'];
            $dados['fone'] = $row['fone'];
            $dados['handle'] = $row['handle'];
            $dados['motivo'] = $row['motivo'];
            $dados['tipo'] = $row['tipo'];
            $dados['nom_franquia'] = $row['nom_franquia'];
            //$dados['tipo'] = $row['tipo'];
            $dados['han_cliente'] = $row['han_cliente'];
            $dados['email'] = $row['email'];
            switch ($dados['tipo']) {
                case 'OS':
                    $dados['dados_busca'] = busca_os_nps(900070);
                    //$dados['dados_busca'] = busca_os_nps($dados['handle']);
                    break;
                case 'Atendimento':
                    $dados['dados_busca'] = busca_atendimentos_nps(100000);
                    //$dados['dados_busca'] = busca_atendimentos_nps($dados['handle']);
                    break;
            }
            $dados['contrato'] = busca_cliente($dados['han_cliente']);
            $dados['dados_telefone'] = busca_telefones($dados['han_cliente']);
            
            //adiciona_card($dados,$autenticacao['chave'],$autenticacao['token'],$autenticacao['id_lista'],$setor);
            
        }
    }

?>