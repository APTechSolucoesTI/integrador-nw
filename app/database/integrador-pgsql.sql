CREATE TABLE aguardo_produto( 
      system_unit_id integer   NOT NULL  , 
      system_users_id integer   NOT NULL  , 
      id  SERIAL    NOT NULL  , 
      repres_id integer   NOT NULL  , 
      ap_item_id integer   NOT NULL  , 
      quantidade float   , 
      status char  (1)     DEFAULT 'A', 
      cod_clifor char  (7)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_cidade( 
      id  SERIAL    NOT NULL  , 
      nome varchar  (255)   , 
      estado_id integer   , 
      cod_cidade varchar  (5)   , 
      latitude float   , 
      longitude float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_estado( 
      id  SERIAL    NOT NULL  , 
      cod_estado varchar  (10)   , 
      nome varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_familia_comercial( 
      system_unit_id integer   NOT NULL  , 
      id  SERIAL    NOT NULL  , 
      descricao varchar  (255)   NOT NULL  , 
      cod_fmcomercial char  (8)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_familia_industrial( 
      system_unit_id integer   NOT NULL  , 
      id  SERIAL    NOT NULL  , 
      descricao varchar  (255)   NOT NULL  , 
      cod_fmindustrial char  (8)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_grupo_cliente( 
      id integer   NOT NULL  , 
      cod_grpcliente varchar  (10)   , 
      descricao varchar  (255)   , 
      pontuacao integer   , 
      valor_inicial float   , 
      valor_final float   , 
      system_unit_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_grupo_estoque( 
      system_unit_id integer   NOT NULL  , 
      id  SERIAL    NOT NULL  , 
      cod_grupoestoque varchar  (8)   NOT NULL  , 
      descricao varchar  (255)   NOT NULL  , 
      sequencia_exibicao integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_item( 
      system_unit_id integer   NOT NULL  , 
      id  SERIAL    NOT NULL  , 
      cod_item char  (10)   , 
      cod_clifor char  (7)   , 
      subgrupo_estoque_id integer   , 
      grupo_estoque_id integer   , 
      familia_comercial_id integer   , 
      familia_industrial_id integer   , 
      codigo char  (20)   , 
      descricao varchar  (100)   , 
      cod_unidade char  (6)   , 
      ativo char  (1)   , 
      ap_fm_industrial_id_teste char  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_representante( 
      system_unit_id integer   NOT NULL  , 
      id  SERIAL    NOT NULL  , 
      cod_repres char  (7)   , 
      razao varchar  (255)   , 
      fantasia varchar  (255)   , 
      ativo char  (1)   , 
      system_users_id integer   , 
      email varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_subgrupo_estoque( 
      system_unit_id integer   NOT NULL  , 
      id  SERIAL    NOT NULL  , 
      grupo_estoque_id integer   , 
      cod_subgrupoestoque char  (8)   NOT NULL  , 
      cod_grupoestoque varchar  (15)   , 
      descricao varchar  (255)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_tabela_preco( 
      id  SERIAL    NOT NULL  , 
      cod_tabelapreco char  (20)   , 
      descricao varchar  (100)   , 
      ativo char  (1)   , 
      system_unit_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE campanha( 
      system_unit_id integer   NOT NULL  , 
      ap_tabela_preco_id integer   NOT NULL  , 
      campanha_tipo_id integer   NOT NULL  , 
      id  SERIAL    NOT NULL  , 
      descricao varchar  (255)   , 
      data_inicial timestamp   , 
      data_final timestamp   , 
      status integer     DEFAULT 1, 
      valor_unitario_min float   , 
      min float   , 
      qtde float   , 
      painel integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE campanha_fechamento( 
      id  SERIAL    NOT NULL  , 
      premio float   , 
      campanha_id integer   NOT NULL  , 
      ap_representante_id integer   NOT NULL  , 
      alcancou_quantidade char   , 
      alcancou_ranking char   , 
      quantidade_total float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE campanha_item( 
      id  SERIAL    NOT NULL  , 
      campanha_id integer   NOT NULL  , 
      cod_item text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE campanha_premio( 
      campanha_id integer   NOT NULL  , 
      comparador_id integer   NOT NULL  , 
      id  SERIAL    NOT NULL  , 
      dado_0 float   , 
      dado_1 float   , 
      premio varchar  (255)   NOT NULL  , 
      tipo_valor integer   , 
      alvo_valor char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE campanha_repres( 
      id  SERIAL    NOT NULL  , 
      campanha_id integer   NOT NULL  , 
      repres_id integer   NOT NULL  , 
      quantidade float   , 
      valor float   , 
      premio float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE campanha_tabela_preco( 
      id  SERIAL    NOT NULL  , 
      ap_tabela_preco_id integer   NOT NULL  , 
      campanha_id integer   NOT NULL  , 
      fazparte char   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE campanha_tipo( 
      id  SERIAL    NOT NULL  , 
      nome varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cep_coordenadas( 
      id  SERIAL    NOT NULL  , 
      cep varchar  (10)   , 
      latitude float   , 
      longitude float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE comparador( 
      id  SERIAL    NOT NULL  , 
      descricao varchar  (255)   NOT NULL  , 
      operador varchar  (255)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credito_cliente( 
      id  SERIAL    NOT NULL  , 
      cod_clifor varchar  (7)   , 
      valor_total float   , 
      media_por_pedido float   , 
      razao_clifor varchar  (255)   , 
      periodo_inicio timestamp   , 
      periodo_fim timestamp   , 
      quantidade_notas integer   , 
      data_processamento timestamp     DEFAULT now(), 
      historico_cli_id integer   , 
      credito_cliente_cadastro_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credito_cliente_cadastro( 
      id  SERIAL    , 
      descricao text   NOT NULL  , 
      periodo_inicio timestamp   NOT NULL  , 
      periodo_fim timestamp   NOT NULL  , 
      data_criacao timestamp     DEFAULT now(), 
      status integer     DEFAULT 1, 
 PRIMARY KEY (id)) ; 

CREATE TABLE grupo( 
      system_unit_id integer   NOT NULL  , 
      id  SERIAL    NOT NULL  , 
      cod_grupoestoque char  (8)   NOT NULL  , 
      descricao varchar  (255)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE historico_cli_repres( 
      system_unit_id integer   NOT NULL  , 
      id  SERIAL    NOT NULL  , 
      ano integer   , 
      mes integer   , 
      cod_clifor char  (7)   , 
      razao_clifor varchar  (255)   , 
      dt_cadastro timestamp   , 
      ap_cidade_id integer   , 
      grupo_id integer   , 
      repres_id integer   , 
      ativo char  (1)   , 
      agente_regular_anp varchar  (10)   , 
      tipo varchar  (255)   , 
      prospeccao char  (1)   , 
      cod_estado char  (2)   , 
      filial char  (1)   , 
      cod_principal varchar  (7)   , 
      tipo_pessoa char  (1)   , 
      rota text   , 
      grupo_empresarial char  (1)   , 
      latitude float   , 
      longitude float   , 
      cep varchar  (10)   , 
      dt_atualizacao timestamp   , 
      dt_change timestamp   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE historico_venda( 
      meta_id integer   , 
      system_unit_id integer   NOT NULL  , 
      id  SERIAL    NOT NULL  , 
      data_emissao timestamp   , 
      data_cadastro timestamp   , 
      nro char  (10)   , 
      tipo_pessoa char  (1)   , 
      cod_clifor char  (10)   , 
      razao varchar  (255)   , 
      agente text   , 
      repres_id integer   , 
      grupo_cliente_id integer   , 
      cidade_id integer   , 
      estado_id integer   , 
      rota text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE historico_venda_item( 
      empresa varchar  (255)   , 
      venda_id integer   NOT NULL  , 
      id  SERIAL    NOT NULL  , 
      cod_item text   , 
      descricao varchar  (255)   , 
      grupo_estoque_id integer   , 
      subgrupo_estoque_id integer   , 
      familia_comercial_id integer   , 
      familia_industrial_id integer   , 
      quantidade float   , 
      valor_unitario float   , 
      valor_mercadoria float   , 
      perc_desconto float   , 
      valor_desconto float   , 
      valor_total float   , 
      sequencia integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE log_crontab( 
      system_unit_id integer   NOT NULL  , 
      id  SERIAL    NOT NULL  , 
      classe text   NOT NULL  , 
      metodo text   , 
      data_hora timestamp   , 
      status integer   , 
      mensagem text   , 
      observacao text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta( 
      system_unit_id integer   NOT NULL  , 
      id  SERIAL    NOT NULL  , 
      ano integer   , 
      mes integer   , 
      data_inicial timestamp   , 
      data_final timestamp   , 
      data_abertura timestamp   , 
      data_entrega timestamp   , 
      data_me timestamp   , 
      dias_uteis integer   , 
      feriados integer   , 
      dias_disponiveis integer   , 
      status integer     DEFAULT 1, 
      mes_ano varchar  (255)   , 
      limite_import integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_fechamento( 
      id  SERIAL    NOT NULL  , 
      meta_id integer   NOT NULL  , 
      meta_repres_id integer   NOT NULL  , 
      repres_id integer   NOT NULL  , 
      faturamento_total float   , 
      faturamento_cortina float   , 
      faturamento_mostruario float   , 
      faturamento_prospeccao float   , 
      faturamento_reativacao float   , 
      faturamento_prosp_reat float   , 
      percentual_import float   , 
      percentual_persianas float   , 
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
      premio_cortina float   , 
      premio_mostruario float   , 
      premio_prospeccao float   , 
      premio_reativacao float   , 
      premio_site float   , 
      premio_prosp_reat float   , 
      comissao float   , 
      bonus_meta float   , 
      bonus_mini float   , 
      bonus_mini_pers float   , 
      bonus_mini_import float   , 
      st float   , 
      premio_estrategico float   , 
      site_porcentagem float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_feriado( 
      id  SERIAL    NOT NULL  , 
      meta_id integer   , 
      data_feriado date   , 
      descricao varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_import( 
      system_unit_id integer   NOT NULL  , 
      id  SERIAL    NOT NULL  , 
      descricao varchar  (255)   , 
      data_inicial timestamp   , 
      data_final timestamp   , 
      status integer     DEFAULT 1, 
      painel integer   , 
      ano integer   , 
      mes integer   , 
      ap_tabela_preco_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_import_fechamento( 
      id  SERIAL    NOT NULL  , 
      meta_import_repres_id integer   , 
      qtd_total float   , 
      meta_import_id integer   NOT NULL  , 
      ap_representante_id integer   NOT NULL  , 
      percentual_qtd float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_import_item( 
      id  SERIAL    NOT NULL  , 
      meta_import_id integer   NOT NULL  , 
      cod_item text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_import_repres( 
      id  SERIAL    NOT NULL  , 
      qtd float   , 
      fantasia varchar  (255)   , 
      ap_representante_id integer   , 
      meta_import_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_import_tabela_preco( 
      id  SERIAL    NOT NULL  , 
      fazparte char  (1)   , 
      ap_tabela_preco_id integer   NOT NULL  , 
      meta_import_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_repres( 
      id  SERIAL    NOT NULL  , 
      meta_id integer   , 
      repres_id integer   , 
      fantasia varchar  (255)   , 
      valor_meta float   , 
      valor_super_meta float   , 
      valor_moc float   , 
      valor_cortina float   , 
      valor_mostruario float   , 
      valor_prospeccao float   , 
      valor_reativacao float   , 
      valor_prosp_reat float   , 
      perc_site float   , 
      perc_aprov_carteira float   , 
      perc_cliente_abaixo float   , 
      perc_fora_estado float   , 
      perc_import float   , 
      perc_persianas float   , 
      valor_import float   , 
      valor_persianas float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_trimestral( 
      id  SERIAL    NOT NULL  , 
      descricao varchar  (255)   NOT NULL  , 
      data_inicial date   NOT NULL  , 
      data_final date   , 
      status integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_trimestral_repres( 
      id  SERIAL    NOT NULL  , 
      meta_trimestral_id integer   NOT NULL  , 
      ap_representante_id integer   NOT NULL  , 
      valor float   NOT NULL  , 
      pontuacao_inicial float   , 
      pontuacao_alvo float   , 
      pontuacao_atual float   , 
      fantasia varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE mini_meta( 
      system_unit_id integer   NOT NULL  , 
      id  SERIAL    NOT NULL  , 
      mini_meta_tipo_id integer   NOT NULL  , 
      ap_tabela_preco_id integer   NOT NULL  , 
      painel integer   , 
      descricao varchar  (255)   , 
      ano integer   , 
      mes integer   , 
      data_inicial timestamp   , 
      data_final timestamp   , 
      status integer     DEFAULT 1, 
      min float   , 
      qtde float   , 
      mes_ano varchar  (255)   , 
      valor_unitario_min float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE mini_meta_fechamento( 
      id  SERIAL    NOT NULL  , 
      mini_meta_id integer   NOT NULL  , 
      ap_representante_id integer   NOT NULL  , 
      alcancou_st char   , 
      quantidade_st float   , 
      premio_st float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE mini_meta_item( 
      id  SERIAL    NOT NULL  , 
      mini_meta_id integer   NOT NULL  , 
      cod_item text   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE minimeta_tabela_preco( 
      id  SERIAL    NOT NULL  , 
      ap_tabela_preco_id integer   NOT NULL  , 
      mini_meta_id integer   NOT NULL  , 
      fazparte char   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE mini_meta_tipo( 
      id  SERIAL    NOT NULL  , 
      nome varchar  (255)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE planejamento_import( 
      id  SERIAL    NOT NULL  , 
      qtd integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE planejamento_import_dias( 
      id  SERIAL    NOT NULL  , 
      planejamento_import_id integer   NOT NULL  , 
      dia integer   , 
      valido char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE planejamento_import_excecao( 
      id  SERIAL    NOT NULL  , 
      data date   , 
      planejamento_import_id integer   NOT NULL  , 
      qtd integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE preferencia_sistema( 
      id  SERIAL    NOT NULL  , 
      system_users_id integer   NOT NULL  , 
      zoom integer   NOT NULL    DEFAULT 100, 
      data_criacao timestamp   , 
      criacao_user_id integer   , 
      data_modificacao timestamp   , 
      modificacao_user_id integer   , 
      menu_fixado integer   NOT NULL    DEFAULT 0, 
 PRIMARY KEY (id)) ; 

CREATE TABLE premio( 
      id  SERIAL    NOT NULL  , 
      ano integer   NOT NULL  , 
      mes integer   NOT NULL  , 
      tipo_premio_id integer   NOT NULL  , 
      obs text   , 
      restricao char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE premio_regra( 
      id  SERIAL    NOT NULL  , 
      premio_id integer   NOT NULL  , 
      comparador_id integer   NOT NULL  , 
      dado_0 float   NOT NULL  , 
      dado_1 float   , 
      bonus float   NOT NULL  , 
      tipo_valor integer   NOT NULL  , 
      alvo_valor char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE regra_prospeccao( 
      system_unit_id integer   NOT NULL  , 
      id  SERIAL    NOT NULL  , 
      ano integer   , 
      mes integer   , 
      estado_op integer   , 
      filial_op integer   , 
      estado varchar  (255)   , 
      filial varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE restricao_premio( 
      id  SERIAL    NOT NULL  , 
      premio_id integer   NOT NULL  , 
      ap_grupo_estoque_id integer   NOT NULL  , 
      ap_subgrupo_estoque_id integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_group( 
      id integer   NOT NULL  , 
      name text   NOT NULL  , 
      uuid varchar  (36)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_group_program( 
      id integer   NOT NULL  , 
      system_group_id integer   NOT NULL  , 
      system_program_id integer   NOT NULL  , 
      actions text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_preference( 
      id varchar  (255)   NOT NULL  , 
      preference text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_program( 
      id integer   NOT NULL  , 
      name text   NOT NULL  , 
      controller text   NOT NULL  , 
      actions text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_unit( 
      id integer   NOT NULL  , 
      name text   NOT NULL  , 
      connection_name text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_user_group( 
      id integer   NOT NULL  , 
      system_user_id integer   NOT NULL  , 
      system_group_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_user_program( 
      id integer   NOT NULL  , 
      system_user_id integer   NOT NULL  , 
      system_program_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_users( 
      system_unit_id integer   , 
      id integer   NOT NULL  , 
      name text   NOT NULL  , 
      login text   NOT NULL  , 
      password text   NOT NULL  , 
      email text   , 
      frontpage_id integer   , 
      active char  (1)   , 
      accepted_term_policy_at text   , 
      accepted_term_policy char  (1)   , 
      two_factor_enabled char  (1)     DEFAULT 'N', 
      two_factor_type varchar  (100)   , 
      two_factor_secret varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_user_unit( 
      id integer   NOT NULL  , 
      system_user_id integer   NOT NULL  , 
      system_unit_id integer   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_premio( 
      id  SERIAL    NOT NULL  , 
      descricao varchar  (255)   NOT NULL  , 
      coluna varchar  (255)   , 
      seq integer   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE trimestre_cliente_atual( 
      id  SERIAL    NOT NULL  , 
      meta_trimestral_id integer   NOT NULL  , 
      data_insercao timestamp   NOT NULL  , 
      cod_clifor char  (7)   NOT NULL  , 
      razao_social varchar  (255)   , 
      dt_cadastro timestamp   , 
      grupo_id integer   , 
      repres_id integer   , 
      ativo char  (1)   , 
      reativacao varchar  (10)   , 
      tipo_pessoa char  (1)   , 
      filial char  (1)   , 
      cod_principal char  (7)   , 
      rota varchar  (255)   , 
      cidade_id integer   , 
      estado_id integer   , 
      mes_inicial integer   , 
      valor_total float   , 
      valor_mostruario float   , 
      valor_media float   , 
      valor_grupo float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE trimestre_cliente_inicial( 
      id  SERIAL    NOT NULL  , 
      meta_trimestral_id integer   NOT NULL  , 
      data_insercao timestamp   NOT NULL  , 
      cod_clifor char  (7)   NOT NULL  , 
      razao_social varchar  (255)   , 
      dt_cadastro timestamp   , 
      grupo_id integer   , 
      repres_id integer   , 
      ativo char  (1)   , 
      reativacao varchar  (10)   , 
      tipo_pessoa char  (1)   , 
      filial char  (1)   , 
      cod_principal char  (7)   , 
      rota varchar  (255)   , 
      cidade_id integer   , 
      estado_id integer   , 
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
 
 CREATE index idx_aguardo_produto_ap_item_id on aguardo_produto(ap_item_id); 
CREATE index idx_aguardo_produto_system_users_id on aguardo_produto(system_users_id); 
CREATE index idx_aguardo_produto_repres_id on aguardo_produto(repres_id); 
CREATE index idx_aguardo_produto_system_unit_id on aguardo_produto(system_unit_id); 
CREATE index idx_ap_cidade_estado_id on ap_cidade(estado_id); 
CREATE index idx_ap_familia_comercial_system_unit_id on ap_familia_comercial(system_unit_id); 
CREATE index idx_ap_familia_industrial_system_unit_id on ap_familia_industrial(system_unit_id); 
CREATE index idx_ap_grupo_cliente_system_unit_id on ap_grupo_cliente(system_unit_id); 
CREATE index idx_ap_grupo_estoque_system_unit_id on ap_grupo_estoque(system_unit_id); 
CREATE index idx_ap_item_subgrupo_estoque_id on ap_item(subgrupo_estoque_id); 
CREATE index idx_ap_item_grupo_estoque_id on ap_item(grupo_estoque_id); 
CREATE index idx_ap_item_familia_comercial_id on ap_item(familia_comercial_id); 
CREATE index idx_ap_item_familia_industrial_id on ap_item(familia_industrial_id); 
CREATE index idx_ap_item_system_unit_id on ap_item(system_unit_id); 
CREATE index idx_ap_representante_system_users_id on ap_representante(system_users_id); 
CREATE index idx_ap_representante_system_unit_id on ap_representante(system_unit_id); 
CREATE index idx_ap_subgrupo_estoque_grupo_estoque_id on ap_subgrupo_estoque(grupo_estoque_id); 
CREATE index idx_ap_subgrupo_estoque_system_unit_id on ap_subgrupo_estoque(system_unit_id); 
CREATE index idx_ap_tabela_preco_system_unit_id on ap_tabela_preco(system_unit_id); 
CREATE index idx_campanha_ap_tabela_preco_id on campanha(ap_tabela_preco_id); 
CREATE index idx_campanha_campanha_tipo_id on campanha(campanha_tipo_id); 
CREATE index idx_campanha_system_unit_id on campanha(system_unit_id); 
CREATE index idx_campanha_fechamento_campanha_id on campanha_fechamento(campanha_id); 
CREATE index idx_campanha_fechamento_ap_representante_id on campanha_fechamento(ap_representante_id); 
CREATE index idx_campanha_item_campanha_id on campanha_item(campanha_id); 
CREATE index idx_campanha_premio_comparador_id on campanha_premio(comparador_id); 
CREATE index idx_campanha_premio_campanha_id on campanha_premio(campanha_id); 
CREATE index idx_campanha_repres_campanha_id on campanha_repres(campanha_id); 
CREATE index idx_campanha_repres_repres_id on campanha_repres(repres_id); 
CREATE index idx_campanha_tabela_preco_ap_tabela_preco_id on campanha_tabela_preco(ap_tabela_preco_id); 
CREATE index idx_campanha_tabela_preco_campanha_id on campanha_tabela_preco(campanha_id); 
CREATE index idx_credito_cliente_historico_cli_id on credito_cliente(historico_cli_id); 
CREATE index idx_credito_cliente_credito_cliente_cadastro_id on credito_cliente(credito_cliente_cadastro_id); 
CREATE index idx_grupo_system_unit_id on grupo(system_unit_id); 
CREATE index idx_historico_cli_repres_grupo_id on historico_cli_repres(grupo_id); 
CREATE index idx_historico_cli_repres_repres_id on historico_cli_repres(repres_id); 
CREATE index idx_historico_cli_repres_system_unit_id on historico_cli_repres(system_unit_id); 
CREATE index idx_historico_cli_repres_ap_cidade_id on historico_cli_repres(ap_cidade_id); 
CREATE index idx_historico_venda_system_unit_id on historico_venda(system_unit_id); 
CREATE index idx_historico_venda_cidade_id on historico_venda(cidade_id); 
CREATE index idx_historico_venda_grupo_cliente_id on historico_venda(grupo_cliente_id); 
CREATE index idx_historico_venda_repres_id on historico_venda(repres_id); 
CREATE index idx_historico_venda_meta_id on historico_venda(meta_id); 
CREATE index idx_historico_venda_estado_id on historico_venda(estado_id); 
CREATE index idx_historico_venda_item_venda_id on historico_venda_item(venda_id); 
CREATE index idx_historico_venda_item_grupo_estoque_id on historico_venda_item(grupo_estoque_id); 
CREATE index idx_historico_venda_item_subgrupo_estoque_id on historico_venda_item(subgrupo_estoque_id); 
CREATE index idx_historico_venda_item_familia_industrial_id on historico_venda_item(familia_industrial_id); 
CREATE index idx_historico_venda_item_familia_comercial_id on historico_venda_item(familia_comercial_id); 
CREATE index idx_log_crontab_system_unit_id on log_crontab(system_unit_id); 
CREATE index idx_meta_system_unit_id on meta(system_unit_id); 
CREATE index idx_meta_fechamento_repres_id on meta_fechamento(repres_id); 
CREATE index idx_meta_fechamento_meta_id on meta_fechamento(meta_id); 
CREATE index idx_meta_fechamento_meta_repres_id on meta_fechamento(meta_repres_id); 
CREATE index idx_meta_feriado_meta_id on meta_feriado(meta_id); 
CREATE index idx_meta_import_ap_tabela_preco_id on meta_import(ap_tabela_preco_id); 
CREATE index idx_meta_import_fechamento_meta_import_repres_id on meta_import_fechamento(meta_import_repres_id); 
CREATE index idx_meta_import_fechamento_meta_import_id on meta_import_fechamento(meta_import_id); 
CREATE index idx_meta_import_fechamento_ap_representante_id on meta_import_fechamento(ap_representante_id); 
CREATE index idx_meta_import_item_meta_import_id on meta_import_item(meta_import_id); 
CREATE index idx_meta_import_repres_ap_representante_id on meta_import_repres(ap_representante_id); 
CREATE index idx_meta_import_repres_meta_import_id on meta_import_repres(meta_import_id); 
CREATE index idx_meta_import_tabela_preco_ap_tabela_preco_id on meta_import_tabela_preco(ap_tabela_preco_id); 
CREATE index idx_meta_import_tabela_preco_meta_import_id on meta_import_tabela_preco(meta_import_id); 
CREATE index idx_meta_repres_repres_id on meta_repres(repres_id); 
CREATE index idx_meta_repres_meta_id on meta_repres(meta_id); 
CREATE index idx_meta_trimestral_repres_meta_trimestral_id on meta_trimestral_repres(meta_trimestral_id); 
CREATE index idx_meta_trimestral_repres_ap_representante_id on meta_trimestral_repres(ap_representante_id); 
CREATE index idx_mini_meta_mini_meta_tipo_id on mini_meta(mini_meta_tipo_id); 
CREATE index idx_mini_meta_system_unit_id on mini_meta(system_unit_id); 
CREATE index idx_mini_meta_ap_tabela_preco_id on mini_meta(ap_tabela_preco_id); 
CREATE index idx_mini_meta_fechamento_mini_meta_id on mini_meta_fechamento(mini_meta_id); 
CREATE index idx_mini_meta_fechamento_ap_representante_id on mini_meta_fechamento(ap_representante_id); 
CREATE index idx_mini_meta_item_mini_meta_id on mini_meta_item(mini_meta_id); 
CREATE index idx_minimeta_tabela_preco_ap_tabela_preco_id on minimeta_tabela_preco(ap_tabela_preco_id); 
CREATE index idx_minimeta_tabela_preco_mini_meta_id on minimeta_tabela_preco(mini_meta_id); 
CREATE index idx_planejamento_import_dias_planejamento_import_id on planejamento_import_dias(planejamento_import_id); 
CREATE index idx_planejamento_import_excecao_planejamento_import_id on planejamento_import_excecao(planejamento_import_id); 
CREATE index idx_preferencia_sistema_system_users_id on preferencia_sistema(system_users_id); 
CREATE index idx_premio_tipo_premio_id on premio(tipo_premio_id); 
CREATE index idx_premio_regra_premio_id on premio_regra(premio_id); 
CREATE index idx_premio_regra_comparador_id on premio_regra(comparador_id); 
CREATE index idx_regra_prospeccao_system_unit_id on regra_prospeccao(system_unit_id); 
CREATE index idx_restricao_premio_premio_id on restricao_premio(premio_id); 
CREATE index idx_restricao_premio_ap_grupo_estoque_id on restricao_premio(ap_grupo_estoque_id); 
CREATE index idx_restricao_premio_ap_subgrupo_estoque_id on restricao_premio(ap_subgrupo_estoque_id); 
CREATE index idx_system_group_program_system_group_id on system_group_program(system_group_id); 
CREATE index idx_system_group_program_system_program_id on system_group_program(system_program_id); 
CREATE index idx_system_user_group_system_group_id on system_user_group(system_group_id); 
CREATE index idx_system_user_group_system_user_id on system_user_group(system_user_id); 
CREATE index idx_system_user_program_system_program_id on system_user_program(system_program_id); 
CREATE index idx_system_user_program_system_user_id on system_user_program(system_user_id); 
CREATE index idx_system_users_system_unit_id on system_users(system_unit_id); 
CREATE index idx_system_users_frontpage_id on system_users(frontpage_id); 
CREATE index idx_system_user_unit_system_user_id on system_user_unit(system_user_id); 
CREATE index idx_system_user_unit_system_unit_id on system_user_unit(system_unit_id); 
CREATE index idx_trimestre_cliente_atual_meta_trimestral_id on trimestre_cliente_atual(meta_trimestral_id); 
CREATE index idx_trimestre_cliente_atual_grupo_id on trimestre_cliente_atual(grupo_id); 
CREATE index idx_trimestre_cliente_atual_repres_id on trimestre_cliente_atual(repres_id); 
CREATE index idx_trimestre_cliente_atual_cidade_id on trimestre_cliente_atual(cidade_id); 
CREATE index idx_trimestre_cliente_atual_estado_id on trimestre_cliente_atual(estado_id); 
CREATE index idx_trimestre_cliente_inicial_meta_trimestral_id on trimestre_cliente_inicial(meta_trimestral_id); 
CREATE index idx_trimestre_cliente_inicial_grupo_id on trimestre_cliente_inicial(grupo_id); 
CREATE index idx_trimestre_cliente_inicial_repres_id on trimestre_cliente_inicial(repres_id); 
CREATE index idx_trimestre_cliente_inicial_cidade_id on trimestre_cliente_inicial(cidade_id); 
CREATE index idx_trimestre_cliente_inicial_estado_id on trimestre_cliente_inicial(estado_id); 
