<?php
//include('conexao.php');

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
                $dados = $row;
        }
        //print_r($dados);

        #print_r($dados);
        return $dados;
}

?>