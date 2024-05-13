<?php
include('database.php');
include('trello.php');

$busca_refidelizacao = busca_refidelizacao();

echo '<pre>';print_r($busca_refidelizacao);
if (!empty($busca_refidelizacao)) {
    $setor = 'qualidade_refidelizacao';
    $autenticacao = autenticacao($setor);
    while($row = $busca_refidelizacao->fetch_assoc()) {
        //echo '<pre>';print_r($row);
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
        $dados['dat_abertura'] = $row['dat_abertura'];
        $dados['dados_telefone'] = busca_telefones($dados['han_cliente']);
        $dados['num_mac_id'] = $row['num_mac_id'];
        $dados['nom_plano'] = $row['nom_plano'];
        $dados['dados_os'] = busca_os($dados['han_cliente'], 3);
        $dados['tipo_tecnologia'] = $row['tipo_tecnologia'];
        echo '<pre>';print_r($dados);
        //echo '<pre>';print_r($autenticacao);die();
        adiciona_card($dados, $autenticacao['chave'], $autenticacao['token'], $autenticacao['id_lista'],$setor);
    }
}

function busca_refidelizacao() {
    include('conexao.php');
    //echo "<br>sucedida!";
    

    $sql = "SELECT
            cc.handle AS han_chamado,
            ctt.han_cliente,
            cli.nom_cliente,
            cli.flg_email_validado,
            cli.nom_email,
            LPAD(fra.handle, 2, '0') AS han_franquia,
            cc.han_contrato,
            pla.nom_plano,
            fra.nom_sigla,
            ctt.num_mac_roteador,
            ce.nom_modelo AS modelo_roteador,
            fra.nom_franquia,
            pla.tipo_tecnologia,
            CONCAT(end.nom_tipo_logradouro, ' ',end.nom_logradouro, ', ', ctt.num_endereco_entrega, ' - ', end.nom_bairro) as endereco_inst,
            ctt.dat_primeira_instalacao,
            if(ctt.val_plano_scm + ctt.val_plano_sva>0,ctt.val_plano_scm + ctt.val_plano_sva, pla.val_plano_scm + pla.val_plano_sva ) + ctt.val_acrescimo - if(ctt.dat_vencimento_desconto<CURDATE() OR ctt.dat_vencimento_desconto IS NULL,0,ctt.val_desconto) AS valor_total,
            cc.dat_fim_execucao,
            ctt.num_mac_id

    FROM ipinfo.cad_chamado cc
    LEFT JOIN ipinfo.cad_contratos ctt ON ctt.handle = cc.han_contrato
    LEFT JOIN ipinfo.cad_cliente cli ON cli.handle = ctt.han_cliente
    LEFT JOIN ipinfo.cad_franquia fra ON fra.handle = cli.han_franquia
    LEFT JOIN ipinfo.cad_plano pla ON pla.handle = ctt.han_plano
    LEFT JOIN ipinfo.cad_enderecos end ON end.handle = ctt.han_endereco_entrega
    LEFT JOIN ipinfo.cad_roteador cr ON cr.num_mac = ctt.num_mac_roteador
    LEFT JOIN ipinfo.cad_equipamento ce ON ce.handle = cr.han_equipamento
    WHERE cc.han_chamado_tipo IN (54)
    AND date(cc.dat_abertura) = CURDATE() - INTERVAL 1 DAY;";

    $result = $conn->query($sql);


    return $result;

    

    
}

?>