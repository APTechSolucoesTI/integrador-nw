CREATE TABLE aguardo_produto( 
      system_unit_id number(10)    NOT NULL , 
      system_users_id number(10)    NOT NULL , 
      id number(10)    NOT NULL , 
      repres_id number(10)    NOT NULL , 
      ap_item_id number(10)    NOT NULL , 
      quantidade binary_double   , 
      status char  (1)    DEFAULT 'A' , 
      cod_clifor char  (7)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_cidade( 
      id number(10)    NOT NULL , 
      nome varchar  (255)   , 
      estado_id number(10)   , 
      cod_cidade varchar  (5)   , 
      latitude binary_double   , 
      longitude binary_double   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_estado( 
      id number(10)    NOT NULL , 
      cod_estado varchar  (10)   , 
      nome varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_familia_comercial( 
      system_unit_id number(10)    NOT NULL , 
      id number(10)    NOT NULL , 
      descricao varchar  (255)    NOT NULL , 
      cod_fmcomercial char  (8)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_familia_industrial( 
      system_unit_id number(10)    NOT NULL , 
      id number(10)    NOT NULL , 
      descricao varchar  (255)    NOT NULL , 
      cod_fmindustrial char  (8)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_grupo_cliente( 
      id number(10)    NOT NULL , 
      cod_grpcliente varchar  (10)   , 
      descricao varchar  (255)   , 
      pontuacao number(10)   , 
      valor_inicial binary_double   , 
      valor_final binary_double   , 
      system_unit_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_grupo_estoque( 
      system_unit_id number(10)    NOT NULL , 
      id number(10)    NOT NULL , 
      cod_grupoestoque varchar  (8)    NOT NULL , 
      descricao varchar  (255)    NOT NULL , 
      sequencia_exibicao number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_item( 
      system_unit_id number(10)    NOT NULL , 
      id number(10)    NOT NULL , 
      cod_item char  (10)   , 
      cod_clifor char  (7)   , 
      subgrupo_estoque_id number(10)   , 
      grupo_estoque_id number(10)   , 
      familia_comercial_id number(10)   , 
      familia_industrial_id number(10)   , 
      codigo char  (20)   , 
      descricao varchar  (100)   , 
      cod_unidade char  (6)   , 
      ativo char  (1)   , 
      ap_fm_industrial_id_teste char  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_representante( 
      system_unit_id number(10)    NOT NULL , 
      id number(10)    NOT NULL , 
      cod_repres char  (7)   , 
      razao varchar  (255)   , 
      fantasia varchar  (255)   , 
      ativo char  (1)   , 
      system_users_id number(10)   , 
      email varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_subgrupo_estoque( 
      system_unit_id number(10)    NOT NULL , 
      id number(10)    NOT NULL , 
      grupo_estoque_id number(10)   , 
      cod_subgrupoestoque char  (8)    NOT NULL , 
      cod_grupoestoque varchar  (15)   , 
      descricao varchar  (255)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_tabela_preco( 
      id number(10)    NOT NULL , 
      cod_tabelapreco char  (20)   , 
      descricao varchar  (100)   , 
      ativo char  (1)   , 
      system_unit_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE campanha( 
      system_unit_id number(10)    NOT NULL , 
      ap_tabela_preco_id number(10)    NOT NULL , 
      campanha_tipo_id number(10)    NOT NULL , 
      id number(10)    NOT NULL , 
      descricao varchar  (255)   , 
      data_inicial timestamp(0)   , 
      data_final timestamp(0)   , 
      status number(10)    DEFAULT 1 , 
      valor_unitario_min binary_double  (15,2)   , 
      min binary_double   , 
      qtde binary_double   , 
      painel number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE campanha_fechamento( 
      id number(10)    NOT NULL , 
      premio binary_double   , 
      campanha_id number(10)    NOT NULL , 
      ap_representante_id number(10)    NOT NULL , 
      alcancou_quantidade char   , 
      alcancou_ranking char   , 
      quantidade_total binary_double   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE campanha_item( 
      id number(10)    NOT NULL , 
      campanha_id number(10)    NOT NULL , 
      cod_item varchar(3000)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE campanha_premio( 
      campanha_id number(10)    NOT NULL , 
      comparador_id number(10)    NOT NULL , 
      id number(10)    NOT NULL , 
      dado_0 binary_double   , 
      dado_1 binary_double   , 
      premio varchar  (255)    NOT NULL , 
      tipo_valor number(10)   , 
      alvo_valor char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE campanha_repres( 
      id number(10)    NOT NULL , 
      campanha_id number(10)    NOT NULL , 
      repres_id number(10)    NOT NULL , 
      quantidade binary_double   , 
      valor binary_double   , 
      premio binary_double   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE campanha_tabela_preco( 
      id number(10)    NOT NULL , 
      ap_tabela_preco_id number(10)    NOT NULL , 
      campanha_id number(10)    NOT NULL , 
      fazparte char   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE campanha_tipo( 
      id number(10)    NOT NULL , 
      nome varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cep_coordenadas( 
      id number(10)    NOT NULL , 
      cep varchar  (10)   , 
      latitude binary_double   , 
      longitude binary_double   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE comparador( 
      id number(10)    NOT NULL , 
      descricao varchar  (255)    NOT NULL , 
      operador varchar  (255)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credito_cliente( 
      id number(10)    NOT NULL , 
      cod_clifor varchar  (7)   , 
      valor_total binary_double  (15,2)   , 
      media_por_pedido binary_double  (15,2)   , 
      razao_clifor varchar  (255)   , 
      periodo_inicio timestamp(0)   , 
      periodo_fim timestamp(0)   , 
      quantidade_notas number(10)   , 
      data_processamento timestamp(0)    DEFAULT now() , 
      historico_cli_id number(10)   , 
      credito_cliente_cadastro_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credito_cliente_cadastro( 
      id number(10)   , 
      descricao varchar(3000)    NOT NULL , 
      periodo_inicio timestamp(0)    NOT NULL , 
      periodo_fim timestamp(0)    NOT NULL , 
      data_criacao timestamp(0)    DEFAULT now() , 
      status number(10)    DEFAULT 1 , 
 PRIMARY KEY (id)) ; 

CREATE TABLE grupo( 
      system_unit_id number(10)    NOT NULL , 
      id number(10)    NOT NULL , 
      cod_grupoestoque char  (8)    NOT NULL , 
      descricao varchar  (255)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE historico_cli_repres( 
      system_unit_id number(10)    NOT NULL , 
      id number(10)    NOT NULL , 
      ano number(10)   , 
      mes number(10)   , 
      cod_clifor char  (7)   , 
      razao_clifor varchar  (255)   , 
      dt_cadastro timestamp(0)   , 
      ap_cidade_id number(10)   , 
      grupo_id number(10)   , 
      repres_id number(10)   , 
      ativo char  (1)   , 
      agente_regular_anp varchar  (10)   , 
      tipo varchar  (255)   , 
      prospeccao char  (1)   , 
      cod_estado char  (2)   , 
      filial char  (1)   , 
      cod_principal varchar  (7)   , 
      tipo_pessoa char  (1)   , 
      rota varchar(3000)   , 
      grupo_empresarial char  (1)   , 
      latitude binary_double   , 
      longitude binary_double   , 
      cep varchar  (10)   , 
      dt_atualizacao timestamp(0)   , 
      dt_change timestamp(0)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE historico_venda( 
      meta_id number(10)   , 
      system_unit_id number(10)    NOT NULL , 
      id number(10)    NOT NULL , 
      data_emissao timestamp(0)   , 
      data_cadastro timestamp(0)   , 
      nro char  (10)   , 
      tipo_pessoa char  (1)   , 
      cod_clifor char  (10)   , 
      razao varchar  (255)   , 
      agente varchar(3000)   , 
      repres_id number(10)   , 
      grupo_cliente_id number(10)   , 
      cidade_id number(10)   , 
      estado_id number(10)   , 
      rota varchar(3000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE historico_venda_item( 
      empresa varchar  (255)   , 
      venda_id number(10)    NOT NULL , 
      id number(10)    NOT NULL , 
      cod_item varchar(3000)   , 
      descricao varchar  (255)   , 
      grupo_estoque_id number(10)   , 
      subgrupo_estoque_id number(10)   , 
      familia_comercial_id number(10)   , 
      familia_industrial_id number(10)   , 
      quantidade binary_double   , 
      valor_unitario binary_double   , 
      valor_mercadoria binary_double   , 
      perc_desconto binary_double   , 
      valor_desconto binary_double   , 
      valor_total binary_double   , 
      sequencia number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE log_crontab( 
      system_unit_id number(10)    NOT NULL , 
      id number(10)    NOT NULL , 
      classe varchar(3000)    NOT NULL , 
      metodo varchar(3000)   , 
      data_hora timestamp(0)   , 
      status number(10)   , 
      mensagem varchar(3000)   , 
      observacao varchar(3000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta( 
      system_unit_id number(10)    NOT NULL , 
      id number(10)    NOT NULL , 
      ano number(10)   , 
      mes number(10)   , 
      data_inicial timestamp(0)   , 
      data_final timestamp(0)   , 
      data_abertura timestamp(0)   , 
      data_entrega timestamp(0)   , 
      data_me timestamp(0)   , 
      dias_uteis number(10)   , 
      feriados number(10)   , 
      dias_disponiveis number(10)   , 
      status number(10)    DEFAULT 1 , 
      mes_ano varchar  (255)   , 
      limite_import number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_fechamento( 
      id number(10)    NOT NULL , 
      meta_id number(10)    NOT NULL , 
      meta_repres_id number(10)    NOT NULL , 
      repres_id number(10)    NOT NULL , 
      faturamento_total binary_double   , 
      faturamento_cortina binary_double   , 
      faturamento_mostruario binary_double   , 
      faturamento_prospeccao binary_double   , 
      faturamento_reativacao binary_double   , 
      faturamento_prosp_reat binary_double   , 
      percentual_import binary_double   , 
      percentual_persianas binary_double   , 
      alcancou_meta char  (1)   , 
      alcancou_super_meta char  (1)   , 
      alcancou_import char  (1)   , 
      alcancou_persianas char  (1)   , 
      alcancou_cortina_pronta char  (1)   , 
      alcancou_mostruario char  (1)   , 
      alcancou_prospeccao char  (1)   , 
      alcancou_reativacao char  (1)   , 
      alcancou_prosp_reat char  (1)   , 
      alcancou_site char  (1)   , 
      premio_cortina binary_double   , 
      premio_mostruario binary_double   , 
      premio_prospeccao binary_double   , 
      premio_reativacao binary_double   , 
      premio_site binary_double   , 
      premio_prosp_reat binary_double   , 
      comissao binary_double   , 
      bonus_meta binary_double   , 
      bonus_mini binary_double   , 
      bonus_mini_pers binary_double   , 
      bonus_mini_import binary_double   , 
      st binary_double   , 
      premio_estrategico binary_double   , 
      site_porcentagem binary_double   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_feriado( 
      id number(10)    NOT NULL , 
      meta_id number(10)   , 
      data_feriado date   , 
      descricao varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_import( 
      system_unit_id number(10)    NOT NULL , 
      id number(10)    NOT NULL , 
      descricao varchar  (255)   , 
      data_inicial timestamp(0)   , 
      data_final timestamp(0)   , 
      status number(10)    DEFAULT 1 , 
      painel number(10)   , 
      ano number(10)   , 
      mes number(10)   , 
      ap_tabela_preco_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_import_fechamento( 
      id number(10)    NOT NULL , 
      meta_import_repres_id number(10)   , 
      qtd_total binary_double  (15,2)   , 
      meta_import_id number(10)    NOT NULL , 
      ap_representante_id number(10)    NOT NULL , 
      percentual_qtd binary_double  (15,2)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_import_item( 
      id number(10)    NOT NULL , 
      meta_import_id number(10)    NOT NULL , 
      cod_item varchar(3000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_import_repres( 
      id number(10)    NOT NULL , 
      qtd binary_double  (15,2)   , 
      fantasia varchar  (255)   , 
      ap_representante_id number(10)   , 
      meta_import_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_import_tabela_preco( 
      id number(10)    NOT NULL , 
      fazparte char  (1)   , 
      ap_tabela_preco_id number(10)    NOT NULL , 
      meta_import_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_repres( 
      id number(10)    NOT NULL , 
      meta_id number(10)   , 
      repres_id number(10)   , 
      fantasia varchar  (255)   , 
      valor_meta binary_double  (15,2)   , 
      valor_super_meta binary_double  (15,2)   , 
      valor_moc binary_double  (15,2)   , 
      valor_cortina binary_double  (15,2)   , 
      valor_mostruario binary_double  (15,2)   , 
      valor_prospeccao binary_double  (15,2)   , 
      valor_reativacao binary_double  (15,2)   , 
      valor_prosp_reat binary_double   , 
      perc_site binary_double  (15,2)   , 
      perc_aprov_carteira binary_double  (15,2)   , 
      perc_cliente_abaixo binary_double  (15,2)   , 
      perc_fora_estado binary_double  (15,2)   , 
      perc_import binary_double   , 
      perc_persianas binary_double   , 
      valor_import binary_double   , 
      valor_persianas binary_double   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_trimestral( 
      id number(10)    NOT NULL , 
      descricao varchar  (255)    NOT NULL , 
      data_inicial date    NOT NULL , 
      data_final date   , 
      status number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_trimestral_repres( 
      id number(10)    NOT NULL , 
      meta_trimestral_id number(10)    NOT NULL , 
      ap_representante_id number(10)    NOT NULL , 
      valor binary_double    NOT NULL , 
      pontuacao_inicial binary_double   , 
      pontuacao_alvo binary_double   , 
      pontuacao_atual binary_double   , 
      fantasia varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE mini_meta( 
      system_unit_id number(10)    NOT NULL , 
      id number(10)    NOT NULL , 
      mini_meta_tipo_id number(10)    NOT NULL , 
      ap_tabela_preco_id number(10)    NOT NULL , 
      painel number(10)   , 
      descricao varchar  (255)   , 
      ano number(10)   , 
      mes number(10)   , 
      data_inicial timestamp(0)   , 
      data_final timestamp(0)   , 
      status number(10)    DEFAULT 1 , 
      min binary_double   , 
      qtde binary_double   , 
      mes_ano varchar  (255)   , 
      valor_unitario_min binary_double  (15,2)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE mini_meta_fechamento( 
      id number(10)    NOT NULL , 
      mini_meta_id number(10)    NOT NULL , 
      ap_representante_id number(10)    NOT NULL , 
      alcancou_st char   , 
      quantidade_st binary_double   , 
      premio_st binary_double   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE mini_meta_item( 
      id number(10)    NOT NULL , 
      mini_meta_id number(10)    NOT NULL , 
      cod_item varchar(3000)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE minimeta_tabela_preco( 
      id number(10)    NOT NULL , 
      ap_tabela_preco_id number(10)    NOT NULL , 
      mini_meta_id number(10)    NOT NULL , 
      fazparte char   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE mini_meta_tipo( 
      id number(10)    NOT NULL , 
      nome varchar  (255)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE planejamento_import( 
      id number(10)    NOT NULL , 
      qtd number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE planejamento_import_dias( 
      id number(10)    NOT NULL , 
      planejamento_import_id number(10)    NOT NULL , 
      dia number(10)   , 
      valido char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE planejamento_import_excecao( 
      id number(10)    NOT NULL , 
      data date   , 
      planejamento_import_id number(10)    NOT NULL , 
      qtd number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE preferencia_sistema( 
      id number(10)    NOT NULL , 
      system_users_id number(10)    NOT NULL , 
      zoom number(10)    DEFAULT 100  NOT NULL , 
      data_criacao timestamp(0)   , 
      criacao_user_id number(10)   , 
      data_modificacao timestamp(0)   , 
      modificacao_user_id number(10)   , 
      menu_fixado number(10)    DEFAULT 0  NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE premio( 
      id number(10)    NOT NULL , 
      ano number(10)    NOT NULL , 
      mes number(10)    NOT NULL , 
      tipo_premio_id number(10)    NOT NULL , 
      obs varchar(3000)   , 
      restricao char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE premio_regra( 
      id number(10)    NOT NULL , 
      premio_id number(10)    NOT NULL , 
      comparador_id number(10)    NOT NULL , 
      dado_0 binary_double    NOT NULL , 
      dado_1 binary_double   , 
      bonus binary_double    NOT NULL , 
      tipo_valor number(10)    NOT NULL , 
      alvo_valor char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE regra_prospeccao( 
      system_unit_id number(10)    NOT NULL , 
      id number(10)    NOT NULL , 
      ano number(10)   , 
      mes number(10)   , 
      estado_op number(10)   , 
      filial_op number(10)   , 
      estado varchar  (255)   , 
      filial varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE restricao_premio( 
      id number(10)    NOT NULL , 
      premio_id number(10)    NOT NULL , 
      ap_grupo_estoque_id number(10)    NOT NULL , 
      ap_subgrupo_estoque_id number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_group( 
      id number(10)    NOT NULL , 
      name varchar(3000)    NOT NULL , 
      uuid varchar  (36)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_group_program( 
      id number(10)    NOT NULL , 
      system_group_id number(10)    NOT NULL , 
      system_program_id number(10)    NOT NULL , 
      actions varchar(3000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_preference( 
      id varchar  (255)    NOT NULL , 
      preference varchar(3000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_program( 
      id number(10)    NOT NULL , 
      name varchar(3000)    NOT NULL , 
      controller varchar(3000)    NOT NULL , 
      actions varchar(3000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_unit( 
      id number(10)    NOT NULL , 
      name varchar(3000)    NOT NULL , 
      connection_name varchar(3000)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_user_group( 
      id number(10)    NOT NULL , 
      system_user_id number(10)    NOT NULL , 
      system_group_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_user_program( 
      id number(10)    NOT NULL , 
      system_user_id number(10)    NOT NULL , 
      system_program_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_users( 
      system_unit_id number(10)   , 
      id number(10)    NOT NULL , 
      name varchar(3000)    NOT NULL , 
      login varchar(3000)    NOT NULL , 
      password varchar(3000)    NOT NULL , 
      email varchar(3000)   , 
      frontpage_id number(10)   , 
      active char  (1)   , 
      accepted_term_policy_at varchar(3000)   , 
      accepted_term_policy char  (1)   , 
      two_factor_enabled char  (1)    DEFAULT 'N' , 
      two_factor_type varchar  (100)   , 
      two_factor_secret varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_user_unit( 
      id number(10)    NOT NULL , 
      system_user_id number(10)    NOT NULL , 
      system_unit_id number(10)    NOT NULL , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_premio( 
      id number(10)    NOT NULL , 
      descricao varchar  (255)    NOT NULL , 
      coluna varchar  (255)   , 
      seq number(10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE trimestre_cliente_atual( 
      id number(10)    NOT NULL , 
      meta_trimestral_id number(10)    NOT NULL , 
      data_insercao timestamp(0)    NOT NULL , 
      cod_clifor char  (7)    NOT NULL , 
      razao_social varchar  (255)   , 
      dt_cadastro timestamp(0)   , 
      grupo_id number(10)   , 
      repres_id number(10)   , 
      ativo char  (1)   , 
      reativacao varchar  (10)   , 
      tipo_pessoa char  (1)   , 
      filial char  (1)   , 
      cod_principal char  (7)   , 
      rota varchar  (255)   , 
      cidade_id number(10)   , 
      estado_id number(10)   , 
      mes_inicial number(10)   , 
      valor_total binary_double   , 
      valor_mostruario binary_double   , 
      valor_media binary_double   , 
      valor_grupo binary_double   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE trimestre_cliente_inicial( 
      id number(10)    NOT NULL , 
      meta_trimestral_id number(10)    NOT NULL , 
      data_insercao timestamp(0)    NOT NULL , 
      cod_clifor char  (7)    NOT NULL , 
      razao_social varchar  (255)   , 
      dt_cadastro timestamp(0)   , 
      grupo_id number(10)   , 
      repres_id number(10)   , 
      ativo char  (1)   , 
      reativacao varchar  (10)   , 
      tipo_pessoa char  (1)   , 
      filial char  (1)   , 
      cod_principal char  (7)   , 
      rota varchar  (255)   , 
      cidade_id number(10)   , 
      estado_id number(10)   , 
 PRIMARY KEY (id)) ; 

 
  
 ALTER TABLE aguardo_produto ADD CONSTRAINT fk_aguardo_produto_4 FOREIGN KEY (ap_item_id) references ap_item(id); 
ALTER TABLE aguardo_produto ADD CONSTRAINT fk_aguardando_produto_2 FOREIGN KEY (system_users_id) references system_users(id); 
ALTER TABLE aguardo_produto ADD CONSTRAINT fk_aguardo_produto_3 FOREIGN KEY (repres_id) references ap_representante(id); 
ALTER TABLE aguardo_produto ADD CONSTRAINT fk_aguardo_produto_4 FOREIGN KEY (system_unit_id) references system_unit(id); 
ALTER TABLE ap_cidade ADD CONSTRAINT fk_cidade_1 FOREIGN KEY (estado_id) references ap_estado(id); 
ALTER TABLE ap_familia_comercial ADD CONSTRAINT fk_ap_familia_comercial_1 FOREIGN KEY (system_unit_id) references system_unit(id); 
ALTER TABLE ap_familia_industrial ADD CONSTRAINT fk_ap_familia_industrial_1 FOREIGN KEY (system_unit_id) references system_unit(id); 
ALTER TABLE ap_grupo_cliente ADD CONSTRAINT fk_ap_grupo_cliente_1 FOREIGN KEY (system_unit_id) references system_unit(id); 
ALTER TABLE ap_grupo_estoque ADD CONSTRAINT fk_ap_grupo_estoque_1 FOREIGN KEY (system_unit_id) references system_unit(id); 
ALTER TABLE ap_item ADD CONSTRAINT fk_ap_item_1 FOREIGN KEY (subgrupo_estoque_id) references ap_subgrupo_estoque(id); 
ALTER TABLE ap_item ADD CONSTRAINT fk_ap_item_2 FOREIGN KEY (grupo_estoque_id) references ap_grupo_estoque(id); 
ALTER TABLE ap_item ADD CONSTRAINT fk_ap_item_3 FOREIGN KEY (familia_comercial_id) references ap_familia_comercial(id); 
ALTER TABLE ap_item ADD CONSTRAINT fk_ap_item_4 FOREIGN KEY (familia_industrial_id) references ap_familia_industrial(id); 
ALTER TABLE ap_item ADD CONSTRAINT fk_ap_item_5 FOREIGN KEY (system_unit_id) references system_unit(id); 
ALTER TABLE ap_representante ADD CONSTRAINT fk_ap_representante_2 FOREIGN KEY (system_users_id) references system_users(id); 
ALTER TABLE ap_representante ADD CONSTRAINT fk_ap_representante_2 FOREIGN KEY (system_unit_id) references system_unit(id); 
ALTER TABLE ap_subgrupo_estoque ADD CONSTRAINT fk_ap_subgrupo_estoque_1 FOREIGN KEY (grupo_estoque_id) references ap_grupo_estoque(id); 
ALTER TABLE ap_subgrupo_estoque ADD CONSTRAINT fk_ap_subgrupo_estoque_2 FOREIGN KEY (system_unit_id) references system_unit(id); 
ALTER TABLE ap_tabela_preco ADD CONSTRAINT fk_ap_tabela_preco_1 FOREIGN KEY (system_unit_id) references system_unit(id); 
ALTER TABLE campanha ADD CONSTRAINT fk_campanha_2 FOREIGN KEY (ap_tabela_preco_id) references ap_tabela_preco(id); 
ALTER TABLE campanha ADD CONSTRAINT fk_campanha_3 FOREIGN KEY (campanha_tipo_id) references campanha_tipo(id); 
ALTER TABLE campanha ADD CONSTRAINT fk_campanha_3 FOREIGN KEY (system_unit_id) references system_unit(id); 
ALTER TABLE campanha_fechamento ADD CONSTRAINT fk_campanha_fechamento_1 FOREIGN KEY (campanha_id) references campanha(id); 
ALTER TABLE campanha_fechamento ADD CONSTRAINT fk_campanha_fechamento_2 FOREIGN KEY (ap_representante_id) references ap_representante(id); 
ALTER TABLE campanha_item ADD CONSTRAINT fk_mini_meta_item_clone_72701688900d636b41_1 FOREIGN KEY (campanha_id) references campanha(id); 
ALTER TABLE campanha_premio ADD CONSTRAINT fk_campanha_premio_1 FOREIGN KEY (comparador_id) references comparador(id); 
ALTER TABLE campanha_premio ADD CONSTRAINT fk_campanha_premio_2 FOREIGN KEY (campanha_id) references campanha(id); 
ALTER TABLE campanha_repres ADD CONSTRAINT fk_campanha_repres_1 FOREIGN KEY (campanha_id) references campanha(id); 
ALTER TABLE campanha_repres ADD CONSTRAINT fk_campanha_repres_2 FOREIGN KEY (repres_id) references ap_representante(id); 
ALTER TABLE campanha_tabela_preco ADD CONSTRAINT fk_campanha_tabela_preco_1 FOREIGN KEY (ap_tabela_preco_id) references ap_tabela_preco(id); 
ALTER TABLE campanha_tabela_preco ADD CONSTRAINT fk_campanha_tabela_preco_2 FOREIGN KEY (campanha_id) references campanha(id); 
ALTER TABLE credito_cliente ADD CONSTRAINT fk_credito_cliente_1 FOREIGN KEY (historico_cli_id) references historico_cli_repres(id); 
ALTER TABLE credito_cliente ADD CONSTRAINT fk_credito_cliente_2 FOREIGN KEY (credito_cliente_cadastro_id) references credito_cliente_cadastro(id); 
ALTER TABLE grupo ADD CONSTRAINT fk_grupo_1 FOREIGN KEY (system_unit_id) references system_unit(id); 
ALTER TABLE historico_cli_repres ADD CONSTRAINT fk_historico_cli_repres_2 FOREIGN KEY (grupo_id) references ap_grupo_cliente(id); 
ALTER TABLE historico_cli_repres ADD CONSTRAINT fk_historico_cli_repres_3 FOREIGN KEY (repres_id) references ap_representante(id); 
ALTER TABLE historico_cli_repres ADD CONSTRAINT fk_historico_cli_repres_3 FOREIGN KEY (system_unit_id) references system_unit(id); 
ALTER TABLE historico_cli_repres ADD CONSTRAINT fk_historico_cli_repres_4 FOREIGN KEY (ap_cidade_id) references ap_cidade(id); 
ALTER TABLE historico_venda ADD CONSTRAINT fk_venda_6 FOREIGN KEY (system_unit_id) references system_unit(id); 
ALTER TABLE historico_venda ADD CONSTRAINT fk_venda_1 FOREIGN KEY (cidade_id) references ap_cidade(id); 
ALTER TABLE historico_venda ADD CONSTRAINT fk_venda_2 FOREIGN KEY (grupo_cliente_id) references ap_grupo_cliente(id); 
ALTER TABLE historico_venda ADD CONSTRAINT fk_venda_3 FOREIGN KEY (repres_id) references ap_representante(id); 
ALTER TABLE historico_venda ADD CONSTRAINT fk_historico_venda_5 FOREIGN KEY (meta_id) references meta(id); 
ALTER TABLE historico_venda ADD CONSTRAINT fk_historico_venda_6 FOREIGN KEY (estado_id) references ap_estado(id); 
ALTER TABLE historico_venda_item ADD CONSTRAINT fk_venda_item_1 FOREIGN KEY (venda_id) references historico_venda(id); 
ALTER TABLE historico_venda_item ADD CONSTRAINT fk_venda_item_2 FOREIGN KEY (grupo_estoque_id) references ap_grupo_estoque(id); 
ALTER TABLE historico_venda_item ADD CONSTRAINT fk_venda_item_3 FOREIGN KEY (subgrupo_estoque_id) references ap_subgrupo_estoque(id); 
ALTER TABLE historico_venda_item ADD CONSTRAINT fk_venda_item_4 FOREIGN KEY (familia_industrial_id) references ap_familia_industrial(id); 
ALTER TABLE historico_venda_item ADD CONSTRAINT fk_venda_item_5 FOREIGN KEY (familia_comercial_id) references ap_familia_comercial(id); 
ALTER TABLE log_crontab ADD CONSTRAINT fk_log_crontab_1 FOREIGN KEY (system_unit_id) references system_unit(id); 
ALTER TABLE meta ADD CONSTRAINT fk_meta_1 FOREIGN KEY (system_unit_id) references system_unit(id); 
ALTER TABLE meta_fechamento ADD CONSTRAINT fk_meta_fechamento_3 FOREIGN KEY (repres_id) references ap_representante(id); 
ALTER TABLE meta_fechamento ADD CONSTRAINT fk_meta_fechamento_1 FOREIGN KEY (meta_id) references meta(id); 
ALTER TABLE meta_fechamento ADD CONSTRAINT fk_meta_fechamento_2 FOREIGN KEY (meta_repres_id) references meta_repres(id); 
ALTER TABLE meta_feriado ADD CONSTRAINT fk_metas_feriados_1 FOREIGN KEY (meta_id) references meta(id); 
ALTER TABLE meta_import ADD CONSTRAINT fk_meta_import_1 FOREIGN KEY (ap_tabela_preco_id) references ap_tabela_preco(id); 
ALTER TABLE meta_import_fechamento ADD CONSTRAINT fk_meta_import_fechamento_1 FOREIGN KEY (meta_import_repres_id) references meta_import_repres(id); 
ALTER TABLE meta_import_fechamento ADD CONSTRAINT fk_meta_import_fechamento_2 FOREIGN KEY (meta_import_id) references meta_import(id); 
ALTER TABLE meta_import_fechamento ADD CONSTRAINT fk_meta_import_fechamento_3 FOREIGN KEY (ap_representante_id) references ap_representante(id); 
ALTER TABLE meta_import_item ADD CONSTRAINT fk_meta_import_item_1 FOREIGN KEY (meta_import_id) references meta_import(id); 
ALTER TABLE meta_import_repres ADD CONSTRAINT fk_metas_import_repres_1 FOREIGN KEY (ap_representante_id) references ap_representante(id); 
ALTER TABLE meta_import_repres ADD CONSTRAINT fk_metas_import_repres_2 FOREIGN KEY (meta_import_id) references meta_import(id); 
ALTER TABLE meta_import_tabela_preco ADD CONSTRAINT fk_meta_import_tabela_preco_1 FOREIGN KEY (ap_tabela_preco_id) references ap_tabela_preco(id); 
ALTER TABLE meta_import_tabela_preco ADD CONSTRAINT fk_meta_import_tabela_preco_2 FOREIGN KEY (meta_import_id) references meta_import(id); 
ALTER TABLE meta_repres ADD CONSTRAINT fk_meta_item_2 FOREIGN KEY (repres_id) references ap_representante(id); 
ALTER TABLE meta_repres ADD CONSTRAINT fk_metas_itens_1 FOREIGN KEY (meta_id) references meta(id); 
ALTER TABLE meta_trimestral_repres ADD CONSTRAINT fk_meta_trimestral_repres_1 FOREIGN KEY (meta_trimestral_id) references meta_trimestral(id); 
ALTER TABLE meta_trimestral_repres ADD CONSTRAINT fk_meta_trimestral_repres_2 FOREIGN KEY (ap_representante_id) references ap_representante(id); 
ALTER TABLE mini_meta ADD CONSTRAINT fk_mini_meta_2 FOREIGN KEY (mini_meta_tipo_id) references mini_meta_tipo(id); 
ALTER TABLE mini_meta ADD CONSTRAINT fk_mini_meta_3 FOREIGN KEY (system_unit_id) references system_unit(id); 
ALTER TABLE mini_meta ADD CONSTRAINT fk_mini_meta_3 FOREIGN KEY (ap_tabela_preco_id) references ap_tabela_preco(id); 
ALTER TABLE mini_meta_fechamento ADD CONSTRAINT fk_mini_meta_fechamento_1 FOREIGN KEY (mini_meta_id) references mini_meta(id); 
ALTER TABLE mini_meta_fechamento ADD CONSTRAINT fk_mini_meta_fechamento_2 FOREIGN KEY (ap_representante_id) references ap_representante(id); 
ALTER TABLE mini_meta_item ADD CONSTRAINT fk_mini_meta_item_1 FOREIGN KEY (mini_meta_id) references mini_meta(id); 
ALTER TABLE minimeta_tabela_preco ADD CONSTRAINT fk_minimeta_tabela_preco_1 FOREIGN KEY (ap_tabela_preco_id) references ap_tabela_preco(id); 
ALTER TABLE minimeta_tabela_preco ADD CONSTRAINT fk_minimeta_tabela_preco_2 FOREIGN KEY (mini_meta_id) references mini_meta(id); 
ALTER TABLE planejamento_import_dias ADD CONSTRAINT fk_planejamento_import_dias_1 FOREIGN KEY (planejamento_import_id) references planejamento_import(id); 
ALTER TABLE planejamento_import_excecao ADD CONSTRAINT fk_planejamento_import_excecao_1 FOREIGN KEY (planejamento_import_id) references planejamento_import(id); 
ALTER TABLE preferencia_sistema ADD CONSTRAINT fk_preferencia_sistema_1 FOREIGN KEY (system_users_id) references system_users(id); 
ALTER TABLE premio ADD CONSTRAINT fk_premio_teste_1 FOREIGN KEY (tipo_premio_id) references tipo_premio(id); 
ALTER TABLE premio_regra ADD CONSTRAINT fk_regra_2 FOREIGN KEY (premio_id) references premio(id); 
ALTER TABLE premio_regra ADD CONSTRAINT fk_regra_1 FOREIGN KEY (comparador_id) references comparador(id); 
ALTER TABLE regra_prospeccao ADD CONSTRAINT fk_regra_prospeccao_1 FOREIGN KEY (system_unit_id) references system_unit(id); 
ALTER TABLE restricao_premio ADD CONSTRAINT fk_restricoes_persianas_1 FOREIGN KEY (premio_id) references premio(id); 
ALTER TABLE restricao_premio ADD CONSTRAINT fk_restricoes_persianas_2 FOREIGN KEY (ap_grupo_estoque_id) references ap_grupo_estoque(id); 
ALTER TABLE restricao_premio ADD CONSTRAINT fk_restricoes_persianas_3 FOREIGN KEY (ap_subgrupo_estoque_id) references ap_subgrupo_estoque(id); 
ALTER TABLE system_group_program ADD CONSTRAINT fk_system_group_program_2 FOREIGN KEY (system_group_id) references system_group(id); 
ALTER TABLE system_group_program ADD CONSTRAINT fk_system_group_program_1 FOREIGN KEY (system_program_id) references system_program(id); 
ALTER TABLE system_user_group ADD CONSTRAINT fk_system_user_group_1 FOREIGN KEY (system_group_id) references system_group(id); 
ALTER TABLE system_user_group ADD CONSTRAINT fk_system_user_group_2 FOREIGN KEY (system_user_id) references system_users(id); 
ALTER TABLE system_user_program ADD CONSTRAINT fk_system_user_program_1 FOREIGN KEY (system_program_id) references system_program(id); 
ALTER TABLE system_user_program ADD CONSTRAINT fk_system_user_program_2 FOREIGN KEY (system_user_id) references system_users(id); 
ALTER TABLE system_users ADD CONSTRAINT fk_system_user_1 FOREIGN KEY (system_unit_id) references system_unit(id); 
ALTER TABLE system_users ADD CONSTRAINT fk_system_user_2 FOREIGN KEY (frontpage_id) references system_program(id); 
ALTER TABLE system_user_unit ADD CONSTRAINT fk_system_user_unit_1 FOREIGN KEY (system_user_id) references system_users(id); 
ALTER TABLE system_user_unit ADD CONSTRAINT fk_system_user_unit_2 FOREIGN KEY (system_unit_id) references system_unit(id); 
ALTER TABLE trimestre_cliente_atual ADD CONSTRAINT fk_trimestre_cliente_atual_1 FOREIGN KEY (meta_trimestral_id) references meta_trimestral(id); 
ALTER TABLE trimestre_cliente_atual ADD CONSTRAINT fk_trimestre_cliente_atual_2 FOREIGN KEY (grupo_id) references ap_grupo_cliente(id); 
ALTER TABLE trimestre_cliente_atual ADD CONSTRAINT fk_trimestre_cliente_atual_3 FOREIGN KEY (repres_id) references ap_representante(id); 
ALTER TABLE trimestre_cliente_atual ADD CONSTRAINT fk_trimestre_cliente_atual_4 FOREIGN KEY (cidade_id) references ap_cidade(id); 
ALTER TABLE trimestre_cliente_atual ADD CONSTRAINT fk_trimestre_cliente_atual_5 FOREIGN KEY (estado_id) references ap_estado(id); 
ALTER TABLE trimestre_cliente_inicial ADD CONSTRAINT fk_trimestre_cliente_inicial_1 FOREIGN KEY (meta_trimestral_id) references meta_trimestral(id); 
ALTER TABLE trimestre_cliente_inicial ADD CONSTRAINT fk_trimestre_cliente_inicial_2 FOREIGN KEY (grupo_id) references ap_grupo_cliente(id); 
ALTER TABLE trimestre_cliente_inicial ADD CONSTRAINT fk_trimestre_cliente_inicial_3 FOREIGN KEY (repres_id) references ap_representante(id); 
ALTER TABLE trimestre_cliente_inicial ADD CONSTRAINT fk_trimestre_cliente_inicial_4 FOREIGN KEY (cidade_id) references ap_cidade(id); 
ALTER TABLE trimestre_cliente_inicial ADD CONSTRAINT fk_trimestre_cliente_inicial_5 FOREIGN KEY (estado_id) references ap_estado(id); 
 CREATE SEQUENCE aguardo_produto_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER aguardo_produto_id_seq_tr 

BEFORE INSERT ON aguardo_produto FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT aguardo_produto_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE ap_cidade_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER ap_cidade_id_seq_tr 

BEFORE INSERT ON ap_cidade FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT ap_cidade_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE ap_estado_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER ap_estado_id_seq_tr 

BEFORE INSERT ON ap_estado FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT ap_estado_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE ap_familia_comercial_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER ap_familia_comercial_id_seq_tr 

BEFORE INSERT ON ap_familia_comercial FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT ap_familia_comercial_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE ap_familia_industrial_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER ap_familia_industrial_id_seq_tr 

BEFORE INSERT ON ap_familia_industrial FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT ap_familia_industrial_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE ap_grupo_estoque_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER ap_grupo_estoque_id_seq_tr 

BEFORE INSERT ON ap_grupo_estoque FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT ap_grupo_estoque_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE ap_item_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER ap_item_id_seq_tr 

BEFORE INSERT ON ap_item FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT ap_item_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE ap_representante_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER ap_representante_id_seq_tr 

BEFORE INSERT ON ap_representante FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT ap_representante_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE ap_subgrupo_estoque_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER ap_subgrupo_estoque_id_seq_tr 

BEFORE INSERT ON ap_subgrupo_estoque FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT ap_subgrupo_estoque_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE ap_tabela_preco_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER ap_tabela_preco_id_seq_tr 

BEFORE INSERT ON ap_tabela_preco FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT ap_tabela_preco_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE campanha_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER campanha_id_seq_tr 

BEFORE INSERT ON campanha FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT campanha_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE campanha_fechamento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER campanha_fechamento_id_seq_tr 

BEFORE INSERT ON campanha_fechamento FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT campanha_fechamento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE campanha_item_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER campanha_item_id_seq_tr 

BEFORE INSERT ON campanha_item FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT campanha_item_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE campanha_premio_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER campanha_premio_id_seq_tr 

BEFORE INSERT ON campanha_premio FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT campanha_premio_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE campanha_repres_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER campanha_repres_id_seq_tr 

BEFORE INSERT ON campanha_repres FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT campanha_repres_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE campanha_tabela_preco_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER campanha_tabela_preco_id_seq_tr 

BEFORE INSERT ON campanha_tabela_preco FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT campanha_tabela_preco_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE campanha_tipo_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER campanha_tipo_id_seq_tr 

BEFORE INSERT ON campanha_tipo FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT campanha_tipo_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE cep_coordenadas_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER cep_coordenadas_id_seq_tr 

BEFORE INSERT ON cep_coordenadas FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT cep_coordenadas_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE comparador_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER comparador_id_seq_tr 

BEFORE INSERT ON comparador FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT comparador_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE credito_cliente_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER credito_cliente_id_seq_tr 

BEFORE INSERT ON credito_cliente FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT credito_cliente_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE credito_cliente_cadastro_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER credito_cliente_cadastro_id_seq_tr 

BEFORE INSERT ON credito_cliente_cadastro FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT credito_cliente_cadastro_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE grupo_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER grupo_id_seq_tr 

BEFORE INSERT ON grupo FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT grupo_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE historico_cli_repres_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER historico_cli_repres_id_seq_tr 

BEFORE INSERT ON historico_cli_repres FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT historico_cli_repres_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE historico_venda_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER historico_venda_id_seq_tr 

BEFORE INSERT ON historico_venda FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT historico_venda_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE historico_venda_item_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER historico_venda_item_id_seq_tr 

BEFORE INSERT ON historico_venda_item FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT historico_venda_item_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE log_crontab_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER log_crontab_id_seq_tr 

BEFORE INSERT ON log_crontab FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT log_crontab_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE meta_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER meta_id_seq_tr 

BEFORE INSERT ON meta FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT meta_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE meta_fechamento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER meta_fechamento_id_seq_tr 

BEFORE INSERT ON meta_fechamento FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT meta_fechamento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE meta_feriado_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER meta_feriado_id_seq_tr 

BEFORE INSERT ON meta_feriado FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT meta_feriado_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE meta_import_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER meta_import_id_seq_tr 

BEFORE INSERT ON meta_import FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT meta_import_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE meta_import_fechamento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER meta_import_fechamento_id_seq_tr 

BEFORE INSERT ON meta_import_fechamento FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT meta_import_fechamento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE meta_import_item_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER meta_import_item_id_seq_tr 

BEFORE INSERT ON meta_import_item FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT meta_import_item_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE meta_import_repres_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER meta_import_repres_id_seq_tr 

BEFORE INSERT ON meta_import_repres FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT meta_import_repres_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE meta_import_tabela_preco_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER meta_import_tabela_preco_id_seq_tr 

BEFORE INSERT ON meta_import_tabela_preco FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT meta_import_tabela_preco_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE meta_repres_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER meta_repres_id_seq_tr 

BEFORE INSERT ON meta_repres FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT meta_repres_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE meta_trimestral_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER meta_trimestral_id_seq_tr 

BEFORE INSERT ON meta_trimestral FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT meta_trimestral_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE meta_trimestral_repres_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER meta_trimestral_repres_id_seq_tr 

BEFORE INSERT ON meta_trimestral_repres FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT meta_trimestral_repres_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE mini_meta_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER mini_meta_id_seq_tr 

BEFORE INSERT ON mini_meta FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT mini_meta_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE mini_meta_fechamento_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER mini_meta_fechamento_id_seq_tr 

BEFORE INSERT ON mini_meta_fechamento FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT mini_meta_fechamento_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE mini_meta_item_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER mini_meta_item_id_seq_tr 

BEFORE INSERT ON mini_meta_item FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT mini_meta_item_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE minimeta_tabela_preco_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER minimeta_tabela_preco_id_seq_tr 

BEFORE INSERT ON minimeta_tabela_preco FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT minimeta_tabela_preco_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE mini_meta_tipo_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER mini_meta_tipo_id_seq_tr 

BEFORE INSERT ON mini_meta_tipo FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT mini_meta_tipo_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE planejamento_import_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER planejamento_import_id_seq_tr 

BEFORE INSERT ON planejamento_import FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT planejamento_import_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE planejamento_import_dias_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER planejamento_import_dias_id_seq_tr 

BEFORE INSERT ON planejamento_import_dias FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT planejamento_import_dias_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE planejamento_import_excecao_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER planejamento_import_excecao_id_seq_tr 

BEFORE INSERT ON planejamento_import_excecao FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT planejamento_import_excecao_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE preferencia_sistema_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER preferencia_sistema_id_seq_tr 

BEFORE INSERT ON preferencia_sistema FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT preferencia_sistema_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE premio_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER premio_id_seq_tr 

BEFORE INSERT ON premio FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT premio_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE premio_regra_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER premio_regra_id_seq_tr 

BEFORE INSERT ON premio_regra FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT premio_regra_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE regra_prospeccao_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER regra_prospeccao_id_seq_tr 

BEFORE INSERT ON regra_prospeccao FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT regra_prospeccao_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE restricao_premio_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER restricao_premio_id_seq_tr 

BEFORE INSERT ON restricao_premio FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT restricao_premio_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE tipo_premio_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER tipo_premio_id_seq_tr 

BEFORE INSERT ON tipo_premio FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT tipo_premio_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE trimestre_cliente_atual_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER trimestre_cliente_atual_id_seq_tr 

BEFORE INSERT ON trimestre_cliente_atual FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT trimestre_cliente_atual_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
CREATE SEQUENCE trimestre_cliente_inicial_id_seq START WITH 1 INCREMENT BY 1; 

CREATE OR REPLACE TRIGGER trimestre_cliente_inicial_id_seq_tr 

BEFORE INSERT ON trimestre_cliente_inicial FOR EACH ROW 

    WHEN 

        (NEW.id IS NULL) 

    BEGIN 

        SELECT trimestre_cliente_inicial_id_seq.NEXTVAL INTO :NEW.id FROM DUAL; 

END;
 