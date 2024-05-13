<?php


function descricao_qualidade_os($dados) {
    //echo '<br><br>DESCRIÇÃO DE OS<br><pre>';print_r($dados);
    $contrato = $dados['contrato'];
    //echo '<pre>';print_r($dados);

    if ($contrato['tipo_tecnologia'] == 'R') {
        $contrato['tipo_tecnologia'] = 'Rádio';
    } elseif ($contrato['tipo_tecnologia'] == 'F') {
        $contrato['tipo_tecnologia'] = 'Fibra';
    } else {
        $contrato['tipo_tecnologia'] = 'LTE 4G';
    }

    // Cria descrição do card

    $desc_card = '';
    $desc_card .= '%0A** ------------------------------- Informações do Cliente -------------------------------**%0A';

    $desc_card .= '**- Dados do Cliente -**%0A';
    $desc_card .= '%0A**Telefone de Contato**: ';
    foreach($dados['dados_telefone'] as $telefone){
        if(count($dados['dados_telefone']) > 1){
            $desc_card .= $telefone['num_telefone'].' - ';
        }else{
        $desc_card .= $telefone;
        }
    }
    
    if($contrato['flg_email_validado'] == 'S'){
        $email_validado = 'Email Validado';
    }else{
        $email_validado = 'Email não Validado';
    }

    if($contrato['nom_email'] == '') {
        $contrato['nom_email'] = 'Não informado';
    }
    
    $desc_card .= '%0A**Cliente**: '.$contrato['nom_cliente'];
    $desc_card .= '%0A**Email**: '.$contrato['nom_email'].' - '.$email_validado;
    $desc_card .= '%0A';
    $desc_card .= '%0A**- Dados do Contrato -**%0A';
    $desc_card .= '%0A**Contrato**: '.$contrato['han_contrato'];
    $desc_card .= '%0A**Franquia**: '.$contrato['han_franquia']. ' - '.$contrato['nom_franquia'];
    $desc_card .= '%0A**Plano**: '.$contrato['nom_plano'];
    $desc_card .= '%0A**Data Primeira Instalação**: '.date('d/m/Y', strtotime($contrato['dat_primeira_instalacao']));
    $desc_card .= '%0A**Endereço de Instalação**: '.$contrato['endereco_inst'];
    $desc_card .= '%0A**Valor Plano**: R$ '.$contrato['valor_total'];
    $desc_card .= '%0A';
    $desc_card .= '%0A**- Dados Técnicos -**%0A';
    $desc_card .= '%0A**Tipo de Tecnologia**: '.$contrato['tipo_tecnologia'];
    $desc_card .= '%0A**MAC ONU**: '.$contrato['num_mac_id'];
    $desc_card .= '%0A**MAC Roteador**: '.$contrato['num_mac_roteador'];
    $desc_card .= '%0A**Modelo Roteador**: '.$contrato['modelo_roteador'];
    $desc_card .= '%0A';



    $desc_card .= '%0A** ------------------------------------ Últimas OS ----------------------------------------**%0A';
    foreach($dados['dados_os'] as $os) {
        // [1] Baixa, [2] Normal, [3] Alta, [4] Extrema, [5] Dedicado
        if ($os['flg_prioridade'] == 1) {
                $os['flg_prioridade'] = 'Baixa';
            } else if ($os['flg_prioridade'] == 2) {
                $os['flg_prioridade'] = 'Normal';
            } else if ($os['flg_prioridade'] == 3) {
                $os['flg_prioridade'] = 'Alta';
            } else if ($os['flg_prioridade'] == 4) {
                $os['flg_prioridade'] = 'Extrema';
            } else if ($os['flg_prioridade'] == 5) {
                $os['flg_prioridade'] = 'Dedicado';
            } else {
                $os['flg_prioridade'] = 'Não informado';
        }
        $desc_card .= '%0A**OS**: '.$os['handle'];
        $desc_card .= '%0A**Data de Abertura**: '.date('d/m/Y', strtotime($os['dat_abertura']));
        $desc_card .= '%0A**Tipo de Chamado**: '.$os['nom_tipo_chamado'];
        $desc_card .= '%0A**Técnico Responsável**: '.$os['tecnico'];
        $desc_card .= '%0A**Status**: '.$os['status'];
        $desc_card .= '%0A**Prioridade**: '.$os['flg_prioridade'];
        $desc_card .= '%0A**Descrição**: '.$os['dsc_chamado'];
        $desc_card .= '%0A';
        
    } 

    $desc_card = urlencode($desc_card);
    $desc_card = str_replace('%250A', '%0A', $desc_card);

    return $desc_card;

}

function descricao_qualidade_instalacao($dados) {
    //echo '<pre>';print_r($dados);
    

    if ($dados['tipo_tecnologia'] == 'R') {
        $dados['tipo_tecnologia'] = 'Rádio';
    } elseif ($dados['tipo_tecnologia'] == 'F') {
        $dados['tipo_tecnologia'] = 'Fibra';
    } else {
        $dados['tipo_tecnologia'] = 'LTE 4G';
    }

    if($dados['nom_email'] == '') {
        $dados['nom_email'] = 'Não informado';
    }

    if($dados['flg_email_validado'] == 'S'){
        $email_validado = 'Validado';
    }else{
        $email_validado = 'Não Validado';
    }

    // Cria descrição do card

    $desc_card = '';
    $desc_card .= '%0A** ------------------------------- Informações do Cliente -------------------------------**%0A';

    $desc_card .= '**- Dados do Cliente -**%0A';
    $desc_card .= '%0A**Telefone de Contato**: ';
    foreach($dados['dados_telefone'] as $telefone){
        if(count($dados['dados_telefone']) > 1){
            $desc_card .= $telefone['num_telefone'].' - ';
        }else{
        $desc_card .= $telefone;
        }
    }
    $desc_card .= '%0A**Cliente**: '.$dados['nom_cliente'];
    $desc_card .= '%0A**Email**: '.$dados['nom_email'].' - '.$email_validado;
    $desc_card .= '%0A';
    $desc_card .= '%0A**- Dados do Contrato -**%0A';
    $desc_card .= '%0A**Contrato**: '.$dados['han_contrato'];
    $desc_card .= '%0A**Franquia**: '.$dados['han_franquia']. ' - '.$dados['nom_franquia'];
    $desc_card .= '%0A**Plano**: '.$dados['nom_plano'];
    $desc_card .= '%0A**Data Primeira Instalação**: '.date('d/m/Y', strtotime($dados['dat_primeira_instalacao']));
    $desc_card .= '%0A**Endereço de Instalação**: '.$dados['endereco_inst'];
    $desc_card .= '%0A**Valor Plano**: R$ '.$dados['valor_total'];
    $desc_card .= '%0A';

    $desc_card .= '%0A**- Dados Técnicos -**%0A';
    $desc_card .= '%0A**Tipo de Tecnologia**: '.$dados['tipo_tecnologia'];
    $desc_card .= '%0A**MAC ONU**: '.$dados['num_mac_id'];
    $desc_card .= '%0A**MAC Roteador**: '.$dados['num_mac_roteador'];
    $desc_card .= '%0A**Modelo Roteador**: '.$dados['modelo_roteador'];
    $desc_card .= '%0A';


    $desc_card .= '%0A** ------------------------------------ Últimas OS ----------------------------------------**%0A';
    foreach($dados['dados_os'] as $os) {
        // [1] Baixa, [2] Normal, [3] Alta, [4] Extrema, [5] Dedicado
        if ($os['flg_prioridade'] == 1) {
                $os['flg_prioridade'] = 'Baixa';
            } else if ($os['flg_prioridade'] == 2) {
                $os['flg_prioridade'] = 'Normal';
            } else if ($os['flg_prioridade'] == 3) {
                $os['flg_prioridade'] = 'Alta';
            } else if ($os['flg_prioridade'] == 4) {
                $os['flg_prioridade'] = 'Extrema';
            } else if ($os['flg_prioridade'] == 5) {
                $os['flg_prioridade'] = 'Dedicado';
            } else {
                $os['flg_prioridade'] = 'Não informado';
        }
        $desc_card .= '%0A**OS**: '.$os['handle'];
        $desc_card .= '%0A**Data de Abertura**: '.$os['dat_abertura'];
        $desc_card .= '%0A**Tipo de Chamado**: '.$os['nom_tipo_chamado'];
        $desc_card .= '%0A**Técnico Responsável**: '.$os['tecnico'];
        $desc_card .= '%0A**Status**: '.$os['status'];
        $desc_card .= '%0A**Prioridade**: '.$os['flg_prioridade'];
        $desc_card .= '%0A**Descrição**: '.$os['dsc_chamado'];
        $desc_card .= '%0A';
        
    }   



    $desc_card = urlencode($desc_card);
    $desc_card = str_replace('%250A', '%0A', $desc_card);
    
    
    return $desc_card;
}

function descricao_qualidade_percepcao($dados) {
    $contrato = $dados['contrato'];

    $desc_card = '';

    if ($contrato['tipo_tecnologia'] == 'R') {
        $contrato['tipo_tecnologia'] = 'Rádio';
    } elseif ($contrato['tipo_tecnologia'] == 'F') {
        $contrato['tipo_tecnologia'] = 'Fibra';
    } else {
        $contrato['tipo_tecnologia'] = 'LTE 4G';
    }

    // Cria descrição do card

    $desc_card = '';
    $desc_card .= '%0A** ------------------------------- Informações do Cliente -------------------------------**%0A';

    $desc_card .= '**- Dados do Cliente -**%0A';
    $desc_card .= '%0A**Telefone de Contato**: ';

    foreach($dados['dados_telefone'] as $telefone){
        if(count($dados['dados_telefone']) > 1){
            $desc_card .= $telefone['num_telefone'].' - ';
        }else{
        $desc_card .= $telefone;
        }
    }
    
    if($contrato['flg_email_validado'] == 'S'){
        $email_validado = 'Email Validado';
    }else{
        $email_validado = 'Email não Validado';
    }

    if($contrato['nom_email'] == '') {
        $contrato['nom_email'] = 'Não informado';
    }
    
    $desc_card .= '%0A**Cliente**: '.$contrato['nom_cliente'];
    $desc_card .= '%0A**Email**: '.$contrato['nom_email'].' - '.$email_validado;
    $desc_card .= '%0A';
    $desc_card .= '%0A**- Dados do Contrato -**%0A';
    $desc_card .= '%0A**Contrato**: '.$contrato['han_contrato'];
    $desc_card .= '%0A**Franquia**: '.$contrato['han_franquia']. ' - '.$contrato['nom_franquia'];
    $desc_card .= '%0A**Plano**: '.$contrato['nom_plano'];
    $desc_card .= '%0A**Data Primeira Instalação**: '.date('d/m/Y', strtotime($contrato['dat_primeira_instalacao']));
    $desc_card .= '%0A**Endereço de Instalação**: '.$contrato['endereco_inst'];
    $desc_card .= '%0A**Valor Plano**: R$ '.$contrato['valor_total'];
    $desc_card .= '%0A';

    $desc_card .= '%0A**- Dados Técnicos -**%0A';
    $desc_card .= '%0A**Tipo de Tecnologia**: '.$contrato['tipo_tecnologia'];
    $desc_card .= '%0A**MAC ONU**: '.$contrato['num_mac_id'];
    $desc_card .= '%0A**MAC Roteador**: '.$contrato['num_mac_roteador'];
    $desc_card .= '%0A**Modelo Roteador**: '.$contrato['modelo_roteador'];
    $desc_card .= '%0A';

    $desc_card .= '%0A** ------------------------------- Últimos Atendimentos -------------------------------**%0A';

   foreach($dados['atendimentos'] as $atendimento) {
    if ($atendimento['avaliacao_csat'] == '1') {
        $atendimento['avaliacao_csat'] = 'Muito Insatisfeito';
    } elseif ($atendimento['avaliacao_csat'] == '2') {
        $atendimento['avaliacao_csat'] = 'Insatisfeito';
    } elseif ($atendimento['avaliacao_csat'] == '3') {
        $atendimento['avaliacao_csat'] = 'Neutro';
    } elseif ($atendimento['avaliacao_csat'] == '4') {
        $atendimento['avaliacao_csat'] = 'Satisfeito';
    } else {
        $atendimento['avaliacao_csat'] = 'Não Avaliado';
    }
    if($atendimento['avaliacao_csat'] == 'Não Avaliado'){
        $atendimento['dsc_csat'] = 'Não Avaliado';
    }
        $desc_card .= '%0A**Avaliação de Percepção**: '.$atendimento['avaliacao_csat'];
        $desc_card .= '%0A**Descrição da Avaliação**: '.$atendimento['dsc_csat'];
        $desc_card .= '%0A';

        $desc_card .= '%0A**Protocolo**: '.$atendimento['protocolo'];
        $desc_card .= '%0A**Data de Fechamento**: '.date('d/m/Y', strtotime($atendimento['dat_fechamento']));
        $desc_card .= '%0A**Tipo de Atendimento**: '.$atendimento['nom_tipo'];
        $desc_card .= '%0A**Sistema de Origem**: '.$atendimento['sistema_origem'];
        $desc_card .= '%0A**Descrição do Atendimento**: '.$atendimento['dsc_atendimento'];
        $desc_card .= '%0A';
        $desc_card .= '%0A**-------------------------------------------------------**%0A';
        $desc_card .= '%0A';
        
    }

    $desc_card = urlencode($desc_card);
    $desc_card = str_replace('%250A', '%0A', $desc_card);

    return $desc_card;

}

function descricao_qualidade_nps($dados) {
    $contrato = $dados['contrato'];

    $desc_card = '';
    $desc_card .= '%0A** ------------------------------- Pesquisa CSAT -------------------------------**%0A';

    $desc_card .= '**- Dados do Cliente -**%0A';
    $desc_card .= '%0A**Telefone de Contato**: ';
    foreach($dados['dados_telefone'] as $telefone){
        if(count($dados['dados_telefone']) > 1){
            $desc_card .= $telefone['num_telefone'].' - ';
        }else{
        $desc_card .= $telefone;
        }
    }
    
    if($contrato['flg_email_validado'] == 'S'){
        $email_validado = 'Email Validado';
    }else{
        $email_validado = 'Email não Validado';
    }

    if($contrato['nom_email'] == '') {
        $contrato['nom_email'] = 'Não informado';
    }
    
    $desc_card .= '%0A**Cliente**: '.$dados['nome_cliente'];
    $desc_card .= '%0A**Email**: '.$contrato['nom_email'];
    $desc_card .= '%0A**Franquia**: '.$dados['franquia']. ' - '.$dados['nom_franquia'];
    $desc_card .= '%0A';

    $desc_card .= '%0A**- Dados do Contato -**%0A';
    $desc_card .= '%0A**Nome:**: '.$dados['nome_contato'];
    $desc_card .= '%0A**Telefone:**: '.$dados['fone'];
    $desc_card .= '%0A**Email:**: '.$dados['email'];
    $desc_card .= '%0A';

    $desc_card .= '%0A**- Dados da Avaliação -**%0A';
    $desc_card .= '%0A**Nota (de 1 à 5)**: '.$dados['nota'];
    $desc_card .= '%0A**Data da Avaliação**: '.date('d/m/Y H:i:s',strtotime($dados['data']));
    $desc_card .= '%0A**Motivo da Avaliação**: '.$dados['motivo'];
    $desc_card .= '%0A**Tipo de Avaliação**: '.$dados['tipo'].' - '.$dados['handle'];
    $desc_card .= '%0A';

    if($dados['tipo'] == 'OS'){
        foreach($dados['dados_busca'] as $os) {
            $desc_card .= '%0A** ------------------------------------ OS Avaliada ----------------------------------------**%0A';
            // [1] Baixa, [2] Normal, [3] Alta, [4] Extrema, [5] Dedicado
            if ($os['flg_prioridade'] == 1) {
                    $os['flg_prioridade'] = 'Baixa';
                } else if ($os['flg_prioridade'] == 2) {
                    $os['flg_prioridade'] = 'Normal';
                } else if ($os['flg_prioridade'] == 3) {
                    $os['flg_prioridade'] = 'Alta';
                } else if ($os['flg_prioridade'] == 4) {
                    $os['flg_prioridade'] = 'Extrema';
                } else if ($os['flg_prioridade'] == 5) {
                    $os['flg_prioridade'] = 'Dedicado';
                } else {
                    $os['flg_prioridade'] = 'Não informado';
            }
            $desc_card .= '%0A**OS**: '.$os['handle'];
            $desc_card .= '%0A**Data de Abertura**: '.date('d/m/Y', strtotime($os['dat_abertura']));
            $desc_card .= '%0A**Tipo de Chamado**: '.$os['nom_tipo_chamado'];
            $desc_card .= '%0A**Técnico Responsável**: '.$os['tecnico'];
            $desc_card .= '%0A**Status**: '.$os['status'];
            $desc_card .= '%0A**Prioridade**: '.$os['flg_prioridade'];
            $desc_card .= '%0A**Descrição**: '.$os['dsc_chamado'];
            $desc_card .= '%0A';
            
        }
    }else{
        foreach($dados['dados_busca'] as $atendimento) {
                $desc_card .= '%0A** ------------------------------- Atendimento Avaliado -------------------------------**%0A';
                $desc_card .= '%0A';
    
                $desc_card .= '%0A**Protocolo**: '.$atendimento['protocolo'];
                $desc_card .= '%0A**Data de Fechamento**: '.date('d/m/Y H:i:s',strtotime($atendimento['dat_fechamento']));
                $desc_card .= '%0A**Tipo de Atendimento**: '.$atendimento['nom_tipo'];
                $desc_card .= '%0A**Sistema de Origem**: '.$atendimento['sistema_origem'];
                $desc_card .= '%0A**Descrição do Atendimento**: '.$atendimento['dsc_atendimento'];
                $desc_card .= '%0A';
                $desc_card .= '%0A**-------------------------------------------------------**%0A';
                $desc_card .= '%0A';
                
            }
    }



    $desc_card = urlencode($desc_card);
    $desc_card = str_replace('%250A', '%0A', $desc_card);

    return $desc_card;

}

function descricao_monitora_atendimentos($dados) {
    $contrato = $dados['contrato'];

    //echo '<br><br>DESCRIÇÃO DE OS<br><pre>';print_r($dados);
    $contrato = $dados['contrato'];
    //echo '<pre>';print_r($dados);

    if ($contrato['tipo_tecnologia'] == 'R') {
        $contrato['tipo_tecnologia'] = 'Rádio';
    } elseif ($contrato['tipo_tecnologia'] == 'F') {
        $contrato['tipo_tecnologia'] = 'Fibra';
    } else {
        $contrato['tipo_tecnologia'] = 'LTE 4G';
    }

    // Cria descrição do card

    $desc_card = '';
    $desc_card .= '%0A** ------------------------------- Informações do Cliente -------------------------------**%0A';

    $desc_card .= '**- Dados do Cliente -**%0A';
    $desc_card .= '%0A**Telefone de Contato**: ';
    foreach($dados['dados_telefone'] as $telefone){
        if(count($dados['dados_telefone']) > 1){
            $desc_card .= $telefone['num_telefone'].' - ';
        }else{
        $desc_card .= $telefone;
        }
    }
    
    if($contrato['flg_email_validado'] == 'S'){
        $email_validado = 'Email Validado';
    }else{
        $email_validado = 'Email não Validado';
    }

    if($contrato['nom_email'] == '') {
        $contrato['nom_email'] = 'Não informado';
    }
    
    $desc_card .= '%0A**Cliente**: '.$contrato['nom_cliente'];
    $desc_card .= '%0A**Email**: '.$contrato['nom_email'].' - '.$email_validado;
    $desc_card .= '%0A';
    $desc_card .= '%0A**- Dados do Contrato -**%0A';
    $desc_card .= '%0A**Contrato**: '.$contrato['han_contrato'];
    $desc_card .= '%0A**Franquia**: '.$contrato['han_franquia']. ' - '.$contrato['nom_franquia'];
    $desc_card .= '%0A**Plano**: '.$contrato['nom_plano'];

    $desc_card .= '%0A**Endereço de Instalação**: '.$contrato['endereco_inst'];
    $desc_card .= '%0A**Valor Plano**: R$ '.$contrato['valor_total'];
    $desc_card .= '%0A';
    $desc_card .= '%0A**- Dados Técnicos -**%0A';
    $desc_card .= '%0A**Tipo de Tecnologia**: '.$contrato['tipo_tecnologia'];
    $desc_card .= '%0A**MAC ONU**: '.$contrato['num_mac_id'];
    $desc_card .= '%0A**MAC Roteador**: '.$contrato['num_mac_roteador'];
    $desc_card .= '%0A**Modelo Roteador**: '.$contrato['modelo_roteador'];
    $desc_card .= '%0A';
    $desc_card .= '%0A** ------------------------------- Últimos Atendimentos -------------------------------**%0A';
    foreach($dados['atendimentos'] as $atendimento) {
            $desc_card .= '%0A';
            $desc_card .= '%0A**Protocolo**: '.$atendimento['protocolo'];
            $desc_card .= '%0A**Data de Fechamento**: '.date('d/m/Y H:i:s',strtotime($atendimento['dat_fechamento']));
            $desc_card .= '%0A**Tipo de Atendimento**: '.$atendimento['nom_tipo'];
            $desc_card .= '%0A**Sistema de Origem**: '.$atendimento['sistema_origem'];
            $desc_card .= '%0A**Descrição do Atendimento**: '.$atendimento['dsc_atendimento'];
            $desc_card .= '%0A';
            $desc_card .= '%0A**-------------------------------------------------------**%0A';
            $desc_card .= '%0A';
            
        }

    $desc_card .= '%0A** ------------------------------------ Últimas OS ----------------------------------------**%0A';
    foreach($dados['dados_os'] as $os) {
        // [1] Baixa, [2] Normal, [3] Alta, [4] Extrema, [5] Dedicado
        if ($os['flg_prioridade'] == 1) {
                $os['flg_prioridade'] = 'Baixa';
            } else if ($os['flg_prioridade'] == 2) {
                $os['flg_prioridade'] = 'Normal';
            } else if ($os['flg_prioridade'] == 3) {
                $os['flg_prioridade'] = 'Alta';
            } else if ($os['flg_prioridade'] == 4) {
                $os['flg_prioridade'] = 'Extrema';
            } else if ($os['flg_prioridade'] == 5) {
                $os['flg_prioridade'] = 'Dedicado';
            } else {
                $os['flg_prioridade'] = 'Não informado';
        }
        $desc_card .= '%0A**OS**: '.$os['handle'];
        $desc_card .= '%0A**Data de Abertura**: '.date('d/m/Y', strtotime($os['dat_abertura']));
        $desc_card .= '%0A**Tipo de Chamado**: '.$os['nom_tipo_chamado'];
        $desc_card .= '%0A**Técnico Responsável**: '.$os['tecnico'];
        $desc_card .= '%0A**Status**: '.$os['status'];
        $desc_card .= '%0A**Prioridade**: '.$os['flg_prioridade'];
        $desc_card .= '%0A**Descrição**: '.$os['dsc_chamado'];
        $desc_card .= '%0A';
        
    } 

    $desc_card = urlencode($desc_card);
    $desc_card = str_replace('%250A', '%0A', $desc_card);

    return $desc_card;

    
}

function descricao_qualidade_refidelizacao($dados) {
    //echo '<pre>';print_r($dados);
    

    if ($dados['tipo_tecnologia'] == 'R') {
        $dados['tipo_tecnologia'] = 'Rádio';
    } elseif ($dados['tipo_tecnologia'] == 'F') {
        $dados['tipo_tecnologia'] = 'Fibra';
    } else {
        $dados['tipo_tecnologia'] = 'LTE 4G';
    }

    if($dados['nom_email'] == '') {
        $dados['nom_email'] = 'Não informado';
    }

    if($dados['flg_email_validado'] == 'S'){
        $email_validado = 'Validado';
    }else{
        $email_validado = 'Não Validado';
    }

    // Cria descrição do card

    $desc_card = '';
    $desc_card .= '%0A** ------------------------------- Informações do Cliente -------------------------------**%0A';

    $desc_card .= '**- Dados do Cliente -**%0A';
    $desc_card .= '%0A**Telefone de Contato**: ';
    foreach($dados['dados_telefone'] as $telefone){
        if(count($dados['dados_telefone']) > 1){
            $desc_card .= $telefone['num_telefone'].' - ';
        }else{
        $desc_card .= $telefone;
        }
    }
    $desc_card .= '%0A**Cliente**: '.$dados['nom_cliente'];
    $desc_card .= '%0A**Email**: '.$dados['nom_email'].' - '.$email_validado;
    $desc_card .= '%0A';
    $desc_card .= '%0A**- Dados do Contrato -**%0A';
    $desc_card .= '%0A**Contrato**: '.$dados['han_contrato'];
    $desc_card .= '%0A**Franquia**: '.$dados['han_franquia']. ' - '.$dados['nom_franquia'];
    $desc_card .= '%0A**Plano**: '.$dados['nom_plano'];
    $desc_card .= '%0A**Data Primeira Instalação**: '.date('d/m/Y', strtotime($dados['dat_primeira_instalacao']));
    $desc_card .= '%0A**Data da Refidelização**: '.date('d/m/Y', strtotime($dados['dat_abertura']));
    $desc_card .= '%0A**Endereço de Instalação**: '.$dados['endereco_inst'];
    $desc_card .= '%0A**Valor Plano**: R$ '.$dados['valor_total'];
    $desc_card .= '%0A';

    $desc_card .= '%0A**- Dados Técnicos -**%0A';
    $desc_card .= '%0A**Tipo de Tecnologia**: '.$dados['tipo_tecnologia'];
    $desc_card .= '%0A**MAC ONU**: '.$dados['num_mac_id'];
    $desc_card .= '%0A**MAC Roteador**: '.$dados['num_mac_roteador'];
    $desc_card .= '%0A**Modelo Roteador**: '.$dados['modelo_roteador'];
    $desc_card .= '%0A';


    $desc_card .= '%0A** ------------------------------------ Últimas OS ----------------------------------------**%0A';
    foreach($dados['dados_os'] as $os) {
        // [1] Baixa, [2] Normal, [3] Alta, [4] Extrema, [5] Dedicado
        if ($os['flg_prioridade'] == 1) {
                $os['flg_prioridade'] = 'Baixa';
            } else if ($os['flg_prioridade'] == 2) {
                $os['flg_prioridade'] = 'Normal';
            } else if ($os['flg_prioridade'] == 3) {
                $os['flg_prioridade'] = 'Alta';
            } else if ($os['flg_prioridade'] == 4) {
                $os['flg_prioridade'] = 'Extrema';
            } else if ($os['flg_prioridade'] == 5) {
                $os['flg_prioridade'] = 'Dedicado';
            } else {
                $os['flg_prioridade'] = 'Não informado';
        }
        $desc_card .= '%0A**OS**: '.$os['handle'];
        $desc_card .= '%0A**Data de Abertura**: '.date('d/m/Y', strtotime($os['dat_abertura']));
        $desc_card .= '%0A**Tipo de Chamado**: '.$os['nom_tipo_chamado'];
        $desc_card .= '%0A**Técnico Responsável**: '.$os['tecnico'];
        $desc_card .= '%0A**Status**: '.$os['status'];
        $desc_card .= '%0A**Prioridade**: '.$os['flg_prioridade'];
        $desc_card .= '%0A**Descrição**: '.$os['dsc_chamado'];
        $desc_card .= '%0A';
        
    }   



    $desc_card = urlencode($desc_card);
    $desc_card = str_replace('%250A', '%0A', $desc_card);
    
    
    return $desc_card;
}

function descricao_qualidade_csat_voz($dados){
    


    $desc_card = '';


    // Cria descrição do card

    $desc_card = '';
    $desc_card .= '%0A** ------------------------------- Informações da Avaliação ----------------------------**%0A';
    $desc_card .= '%0A**Avaliador**: '.$dados['avaliador'];
    $desc_card .= '%0A**Nota**: '.$dados['nota'];
    $desc_card .= '%0A**Data da Avaliação**: '.date('d/m/Y H:i:s',strtotime($dados['data']));
    $desc_card .= '%0A**Solicitação Atendida?**: '.$dados['Solicitação Atendida?'];

    
    $desc_card .= '%0A';
            
        
        $desc_card = urlencode($desc_card);
        $desc_card = str_replace('%250A', '%0A', $desc_card);
        
        
        return $desc_card;
        
}

?>