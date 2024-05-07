<?php
//include('conexao.php');

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

function autenticacao($setor) {
        include('conexao.php');
        $sql = "SELECT * 
        FROM ipinfo.cad_trello_autentica
        WHERE setor = '$setor'";
        
        $dados = array();
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
                $dados = $row;
        }
        return $dados;
}

function busca_instalacao() {
        include('conexao.php');
        //echo "<br>sucedida!";
        

        $sql = "SELECT
                cc.handle AS han_chamado,
                ctt.han_cliente,
                cli.handle AS han_cliente,
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
        WHERE cc.han_chamado_tipo IN (9)
        AND date(ctt.dat_primeira_instalacao) = CURDATE() - INTERVAL 1 DAY;";

        $result = $conn->query($sql);


        return $result;

        //print_r($result);
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

function busca_monitora_atendimento() {
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
        print_r($result);

        return $result;
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
                dat_abertura >= DATE_SUB(CURDATE(), INTERVAL 60 DAY)
                AND han_chamado_tipo NOT IN (33,42,38,9,60,45,31,29,57,36,35,34,46,54,39, 1,25,29,30,32,50,55)
                AND status_chamado(handle) != 'CANCELADO'
                AND han_contrato IS NOT NULL
                GROUP BY 
                han_contrato, han_cliente 
                HAVING 
                num_chamados >= 2 
                ORDER BY 
                han_franquia, han_cliente;";
        
        $result = $conn->query($sql);

        return $result;
}

function busca_csat() {
        include('conexao.php');

        $sql = "SELECT * 
                FROM callcenter.cad_atendimentos ca
                WHERE ca.avaliacao_csat <= 2
                AND ca.dat_fechamento between date_add(NOW(), INTERVAL - 6 minute) AND date_add(NOW(), INTERVAL - 1 MINUTE);";

        $result = $conn->query($sql);

        return $result;
}

function busca_atendimentos($id,$limit) {
        include('conexao.php');
        $sql = "SELECT * FROM callcenter.cad_atendimentos ca
        LEFT JOIN ipinfo.cad_tipo_atendimento cta ON cta.handle = ca.flg_tipo_atendimento
        WHERE han_cliente = $id
        ORDER BY dat_fechamento DESC
        LIMIT $limit;";

        $result = $conn->query($sql);
        
        while ($row = $result->fetch_assoc()) {
                $dados[] = $row;
        }

        return $dados;
}

function busca_cliente($id) {
        include('conexao.php');
        $sql = "SELECT 
                ctt.handle AS han_contrato,
                ctt.han_cliente,
                LPAD(fra.handle, 2, '0') AS han_franquia,
                cli.nom_cliente,
                cli.flg_email_validado,
                cli.nom_email,
                pla.nom_plano,
                fra.nom_sigla,
                ctt.num_mac_roteador,
                ce.nom_modelo AS modelo_roteador,
                fra.nom_franquia,
                pla.tipo_tecnologia,
                CONCAT(end.nom_tipo_logradouro, ' ',end.nom_logradouro, ', ', ctt.num_endereco_entrega, ' - ', end.nom_bairro) as endereco_inst,
                ctt.dat_primeira_instalacao,
                if(ctt.val_plano_scm + ctt.val_plano_sva>0,ctt.val_plano_scm + ctt.val_plano_sva, pla.val_plano_scm + pla.val_plano_sva ) + ctt.val_acrescimo - if(ctt.dat_vencimento_desconto<CURDATE() OR ctt.dat_vencimento_desconto IS NULL,0,ctt.val_desconto) AS valor_total,
                ctt.num_mac_id
                
        FROM ipinfo.cad_contratos ctt
        LEFT JOIN ipinfo.cad_cliente cli ON ctt.han_cliente = cli.handle
        LEFT JOIN ipinfo.cad_franquia fra ON ctt.han_franquia = fra.handle
        LEFT JOIN ipinfo.cad_plano pla ON ctt.han_plano = pla.handle
        LEFT JOIN ipinfo.cad_enderecos end ON end.handle = ctt.han_endereco_entrega
        LEFT JOIN ipinfo.cad_roteador cr ON cr.num_mac = ctt.num_mac_roteador
        LEFT JOIN ipinfo.cad_equipamento ce ON ce.handle = cr.han_equipamento
        WHERE ctt.han_cliente = $id";
        
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
                $dados_cliente = $row;
        }

        //echo '<pre>';print_r($dados_cliente);
        return $dados_cliente;
}

function busca_telefones($id) {
        include('conexao.php');
        $sql = "SELECT * 
        FROM ipinfo.cad_telefones_cliente
        WHERE han_cliente = $id";
        
        $result = $conn->query($sql);
        //$dados_telefone = array();
        while ($row = $result->fetch_assoc()) {
                $dados_telefone['num_telefone'] = $row['num_telefone'];
        }

        return $dados_telefone;
}

function busca_atendimentos_nps($id) {
        include('conexao.php');
        $sql = "SELECT * FROM callcenter.cad_atendimentos ca
        LEFT JOIN ipinfo.cad_tipo_atendimento cta ON cta.handle = ca.flg_tipo_atendimento
        WHERE ca.handle = $id
        ORDER BY dat_fechamento DESC;";

        $result = $conn->query($sql);
        
        while ($row = $result->fetch_assoc()) {
                $dados[] = $row;
        }

        return $dados;
}

function busca_os_nps($id) {
        include('conexao.php');

        $sql2 = "SELECT 
        cad_chamado.handle,
        cad_chamado.flg_tipo_atendimento,
        DATE_FORMAT(dat_abertura,'%d/%m/%Y %H:%i:%s') as dat_abertura,
        t.nom_tipo_chamado,
        cli.nom_cliente,
        u.nom_usuario as tecnico,
        ipinfo.status_chamado(cad_chamado.handle) as status,
        cad_chamado.han_cliente,
        cad_chamado.dsc_chamado,
        cad_chamado.han_chamado_tipo,
        cad_chamado.flg_prioridade
        FROM ipinfo.cad_chamado
        LEFT JOIN ipinfo.cad_chamado_tipo t ON cad_chamado.han_chamado_tipo = t.handle
        LEFT JOIN ipinfo.cad_cliente cli ON cad_chamado.han_cliente = cli.handle
        LEFT JOIN ipinfo.adm_usuarios u ON cad_chamado.han_usuario_tecnico = u.handle
        WHERE t.flg_tipo = 'A' AND ipinfo.status_chamado(cad_chamado.handle) != 'CANCELADO'
        AND cad_chamado.handle = $id
        ORDER BY cad_chamado.dat_abertura DESC, status, cad_chamado.flg_prioridade DESC;";

        $result_os = $conn->query($sql2);

        
        while ($row2 = $result_os->fetch_assoc()) {
                $dados_os[] = $row2;
        }
        return $dados_os;
}

function busca_os($id, $limit) {
        include('conexao.php');

        $sql2 = "SELECT 
        cad_chamado.handle,
        cad_chamado.flg_tipo_atendimento,
        DATE_FORMAT(dat_abertura,'%d/%m/%Y %H:%i:%s') as dat_abertura,
        t.nom_tipo_chamado,
        cli.nom_cliente,
        u.nom_usuario as tecnico,
        ipinfo.status_chamado(cad_chamado.handle) as status,
        cad_chamado.han_cliente,
        cad_chamado.dsc_chamado,
        cad_chamado.han_chamado_tipo,
        cad_chamado.flg_prioridade
        FROM ipinfo.cad_chamado
        LEFT JOIN ipinfo.cad_chamado_tipo t ON cad_chamado.han_chamado_tipo = t.handle
        LEFT JOIN ipinfo.cad_cliente cli ON cad_chamado.han_cliente = cli.handle
        LEFT JOIN ipinfo.adm_usuarios u ON cad_chamado.han_usuario_tecnico = u.handle
        WHERE cad_chamado.han_cliente = '$id' AND t.flg_tipo = 'A' AND ipinfo.status_chamado(cad_chamado.handle) != 'CANCELADO'
        ORDER BY cad_chamado.dat_abertura DESC, status, cad_chamado.flg_prioridade DESC LIMIT $limit;";

        $result_os = $conn->query($sql2);

        $dados_os = array();
        while ($row2 = $result_os->fetch_assoc()) {
                $dados_os[] = $row2;
        }
        return $dados_os;
}

function busca_csat_voz(){
	include('conexao.php');
	$sql="SELECT * FROM pesquisa WHERE p2 <= 2 AND p2 <> 0 and DATA between date_add(NOW(), INTERVAL - 60000 minute) AND date_add(NOW(), INTERVAL - 1 MINUTE); ";
    $result = $conn2->query($sql);

    return $result;
}

function busca_atendimentos_csatv($fone,$data) {
        include('conexao.php');
		
		if ($fone[0] === "0") {
			$fone = substr($fone, 1);
		}
		
        $sql = "SELECT * FROM callcenter.cad_atendimentos ca
        LEFT JOIN ipinfo.cad_tipo_atendimento cta ON cta.handle = ca.flg_tipo_atendimento
        WHERE date(dat_fechamento)=DATE('$data') and  REPLACE(REPLACE(REPLACE(REPLACE(ca.num_telefone,'(',''),')',''),'-',''),'-','') like concat('%',REPLACE(REPLACE(REPLACE(REPLACE($fone,'(',''),')',''),'-',''),'-',''),'%')
        ORDER BY dat_fechamento DESC;";

        #echo $sql;	
        $result = $conn->query($sql);
        
        while ($row = $result->fetch_assoc()) {
                $dados[] = $row;
        }

        #print_r($dados);
        return $dados;
}

?>