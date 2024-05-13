<?php
include('database.php');
include('trello.php');

$dados = array();
$busca_csatv = busca_csat_voz();
echo "<pre>";print_r($busca_csatv);
    if(!empty($busca_csatv)) {
        $setor = 'qualidade_csat_voz';
        $autenticacao = autenticacao($setor);
        while($row = $busca_csatv->fetch_assoc()) {
            //print_r($row);
            
			$dados['avaliado'] = $row['avaliado'];
            if($row['p2'] == 1) {
                $dados['nota'] = '1 - Muito Insatisfeito';
            } else {
                $dados['nota'] = '2 - Insatisfeito';
            }
            
            $dados['data'] = $row['data'];
            $dados['avaliador'] = $row['avaliador'];
            $dados['Solicitação Atendida?'] = ($row['p1'] == 1) ? "Sim" : "Não";
            $dados['Tipo'] = $row['tipo'];
            #$dados['dados_busca'] = busca_atendimentos_csatv($dados['avaliador'],$dados['data'] );
            #$dados['han_cliente'] = $dados['dados_busca']['han_cliente'];
            //print_r($dados['dados_busca']);
            
			
            #$dados['contrato'] = busca_cliente($dados['han_cliente']);
           # $dados['dados_telefone'] = busca_telefones($dados['han_cliente']);
            echo '<br><pre>';print_r($dados);
            adiciona_card($dados,$autenticacao['chave'],$autenticacao['token'],$autenticacao['id_lista'],$setor);
            
        }
    }

    function busca_csat_voz(){
        include('conexao.php');
        $sql="SELECT * FROM pesquisa WHERE p2 <= 2 AND p2 <> 0 and DATA between date_add(NOW(), INTERVAL - 6 minute) AND date_add(NOW(), INTERVAL - 1 MINUTE); ";
        $result = $conn->query($sql);
    
        return $result;
    }

?>