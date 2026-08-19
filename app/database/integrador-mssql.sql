CREATE TABLE aguardo_produto( 
      system_unit_id int   NOT NULL  , 
      system_users_id int   NOT NULL  , 
      id  INT IDENTITY    NOT NULL  , 
      repres_id int   NOT NULL  , 
      ap_item_id int   NOT NULL  , 
      quantidade float   , 
      status char  (1)     DEFAULT 'A', 
      cod_clifor char  (7)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_cidade( 
      id  INT IDENTITY    NOT NULL  , 
      nome varchar  (255)   , 
      estado_id int   , 
      cod_cidade varchar  (5)   , 
      latitude float   , 
      longitude float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_estado( 
      id  INT IDENTITY    NOT NULL  , 
      cod_estado varchar  (10)   , 
      nome varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_familia_comercial( 
      system_unit_id int   NOT NULL  , 
      id  INT IDENTITY    NOT NULL  , 
      descricao varchar  (255)   NOT NULL  , 
      cod_fmcomercial char  (8)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_familia_industrial( 
      system_unit_id int   NOT NULL  , 
      id  INT IDENTITY    NOT NULL  , 
      descricao varchar  (255)   NOT NULL  , 
      cod_fmindustrial char  (8)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_grupo_cliente( 
      id int   NOT NULL  , 
      cod_grpcliente varchar  (10)   , 
      descricao varchar  (255)   , 
      pontuacao int   , 
      valor_inicial float   , 
      valor_final float   , 
      system_unit_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_grupo_estoque( 
      system_unit_id int   NOT NULL  , 
      id  INT IDENTITY    NOT NULL  , 
      cod_grupoestoque varchar  (8)   NOT NULL  , 
      descricao varchar  (255)   NOT NULL  , 
      sequencia_exibicao int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_item( 
      system_unit_id int   NOT NULL  , 
      id  INT IDENTITY    NOT NULL  , 
      cod_item char  (10)   , 
      cod_clifor char  (7)   , 
      subgrupo_estoque_id int   , 
      grupo_estoque_id int   , 
      familia_comercial_id int   , 
      familia_industrial_id int   , 
      codigo char  (20)   , 
      descricao varchar  (100)   , 
      cod_unidade char  (6)   , 
      ativo char  (1)   , 
      ap_fm_industrial_id_teste char  (10)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_representante( 
      system_unit_id int   NOT NULL  , 
      id  INT IDENTITY    NOT NULL  , 
      cod_repres char  (7)   , 
      razao varchar  (255)   , 
      fantasia varchar  (255)   , 
      ativo char  (1)   , 
      system_users_id int   , 
      email varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_subgrupo_estoque( 
      system_unit_id int   NOT NULL  , 
      id  INT IDENTITY    NOT NULL  , 
      grupo_estoque_id int   , 
      cod_subgrupoestoque char  (8)   NOT NULL  , 
      cod_grupoestoque varchar  (15)   , 
      descricao varchar  (255)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_tabela_preco( 
      id  INT IDENTITY    NOT NULL  , 
      cod_tabelapreco char  (20)   , 
      descricao varchar  (100)   , 
      ativo char  (1)   , 
      system_unit_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE campanha( 
      system_unit_id int   NOT NULL  , 
      ap_tabela_preco_id int   NOT NULL  , 
      campanha_tipo_id int   NOT NULL  , 
      id  INT IDENTITY    NOT NULL  , 
      descricao varchar  (255)   , 
      data_inicial datetime2   , 
      data_final datetime2   , 
      status int     DEFAULT 1, 
      valor_unitario_min float  (15,2)   , 
      min float   , 
      qtde float   , 
      painel int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE campanha_fechamento( 
      id  INT IDENTITY    NOT NULL  , 
      premio float   , 
      campanha_id int   NOT NULL  , 
      ap_representante_id int   NOT NULL  , 
      alcancou_quantidade char   , 
      alcancou_ranking char   , 
      quantidade_total float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE campanha_item( 
      id  INT IDENTITY    NOT NULL  , 
      campanha_id int   NOT NULL  , 
      cod_item nvarchar(max)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE campanha_premio( 
      campanha_id int   NOT NULL  , 
      comparador_id int   NOT NULL  , 
      id  INT IDENTITY    NOT NULL  , 
      dado_0 float   , 
      dado_1 float   , 
      premio varchar  (255)   NOT NULL  , 
      tipo_valor int   , 
      alvo_valor char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE campanha_repres( 
      id  INT IDENTITY    NOT NULL  , 
      campanha_id int   NOT NULL  , 
      repres_id int   NOT NULL  , 
      quantidade float   , 
      valor float   , 
      premio float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE campanha_tabela_preco( 
      id  INT IDENTITY    NOT NULL  , 
      ap_tabela_preco_id int   NOT NULL  , 
      campanha_id int   NOT NULL  , 
      fazparte char   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE campanha_tipo( 
      id  INT IDENTITY    NOT NULL  , 
      nome varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cep_coordenadas( 
      id  INT IDENTITY    NOT NULL  , 
      cep varchar  (10)   , 
      latitude float   , 
      longitude float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE comparador( 
      id  INT IDENTITY    NOT NULL  , 
      descricao varchar  (255)   NOT NULL  , 
      operador varchar  (255)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credito_cliente( 
      id  INT IDENTITY    NOT NULL  , 
      cod_clifor varchar  (7)   , 
      valor_total float  (15,2)   , 
      media_por_pedido float  (15,2)   , 
      razao_clifor varchar  (255)   , 
      periodo_inicio datetime2   , 
      periodo_fim datetime2   , 
      quantidade_notas int   , 
      data_processamento datetime2     DEFAULT now(), 
      historico_cli_id int   , 
      credito_cliente_cadastro_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credito_cliente_cadastro( 
      id  INT IDENTITY    , 
      descricao nvarchar(max)   NOT NULL  , 
      periodo_inicio datetime2   NOT NULL  , 
      periodo_fim datetime2   NOT NULL  , 
      data_criacao datetime2     DEFAULT now(), 
      status int     DEFAULT 1, 
 PRIMARY KEY (id)) ; 

CREATE TABLE grupo( 
      system_unit_id int   NOT NULL  , 
      id  INT IDENTITY    NOT NULL  , 
      cod_grupoestoque char  (8)   NOT NULL  , 
      descricao varchar  (255)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE historico_cli_repres( 
      system_unit_id int   NOT NULL  , 
      id  INT IDENTITY    NOT NULL  , 
      ano int   , 
      mes int   , 
      cod_clifor char  (7)   , 
      razao_clifor varchar  (255)   , 
      dt_cadastro datetime2   , 
      ap_cidade_id int   , 
      grupo_id int   , 
      repres_id int   , 
      ativo char  (1)   , 
      agente_regular_anp varchar  (10)   , 
      tipo varchar  (255)   , 
      prospeccao char  (1)   , 
      cod_estado char  (2)   , 
      filial char  (1)   , 
      cod_principal varchar  (7)   , 
      tipo_pessoa char  (1)   , 
      rota nvarchar(max)   , 
      grupo_empresarial char  (1)   , 
      latitude float   , 
      longitude float   , 
      cep varchar  (10)   , 
      dt_atualizacao datetime2   , 
      dt_change datetime2   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE historico_venda( 
      meta_id int   , 
      system_unit_id int   NOT NULL  , 
      id  INT IDENTITY    NOT NULL  , 
      data_emissao datetime2   , 
      data_cadastro datetime2   , 
      nro char  (10)   , 
      tipo_pessoa char  (1)   , 
      cod_clifor char  (10)   , 
      razao varchar  (255)   , 
      agente nvarchar(max)   , 
      repres_id int   , 
      grupo_cliente_id int   , 
      cidade_id int   , 
      estado_id int   , 
      rota nvarchar(max)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE historico_venda_item( 
      empresa varchar  (255)   , 
      venda_id int   NOT NULL  , 
      id  INT IDENTITY    NOT NULL  , 
      cod_item nvarchar(max)   , 
      descricao varchar  (255)   , 
      grupo_estoque_id int   , 
      subgrupo_estoque_id int   , 
      familia_comercial_id int   , 
      familia_industrial_id int   , 
      quantidade float   , 
      valor_unitario float   , 
      valor_mercadoria float   , 
      perc_desconto float   , 
      valor_desconto float   , 
      valor_total float   , 
      sequencia int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE log_crontab( 
      system_unit_id int   NOT NULL  , 
      id  INT IDENTITY    NOT NULL  , 
      classe nvarchar(max)   NOT NULL  , 
      metodo nvarchar(max)   , 
      data_hora datetime2   , 
      status int   , 
      mensagem nvarchar(max)   , 
      observacao nvarchar(max)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta( 
      system_unit_id int   NOT NULL  , 
      id  INT IDENTITY    NOT NULL  , 
      ano int   , 
      mes int   , 
      data_inicial datetime2   , 
      data_final datetime2   , 
      data_abertura datetime2   , 
      data_entrega datetime2   , 
      data_me datetime2   , 
      dias_uteis int   , 
      feriados int   , 
      dias_disponiveis int   , 
      status int     DEFAULT 1, 
      mes_ano varchar  (255)   , 
      limite_import int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_fechamento( 
      id  INT IDENTITY    NOT NULL  , 
      meta_id int   NOT NULL  , 
      meta_repres_id int   NOT NULL  , 
      repres_id int   NOT NULL  , 
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
      id  INT IDENTITY    NOT NULL  , 
      meta_id int   , 
      data_feriado date   , 
      descricao varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_import( 
      system_unit_id int   NOT NULL  , 
      id  INT IDENTITY    NOT NULL  , 
      descricao varchar  (255)   , 
      data_inicial datetime2   , 
      data_final datetime2   , 
      status int     DEFAULT 1, 
      painel int   , 
      ano int   , 
      mes int   , 
      ap_tabela_preco_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_import_fechamento( 
      id  INT IDENTITY    NOT NULL  , 
      meta_import_repres_id int   , 
      qtd_total float  (15,2)   , 
      meta_import_id int   NOT NULL  , 
      ap_representante_id int   NOT NULL  , 
      percentual_qtd float  (15,2)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_import_item( 
      id  INT IDENTITY    NOT NULL  , 
      meta_import_id int   NOT NULL  , 
      cod_item nvarchar(max)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_import_repres( 
      id  INT IDENTITY    NOT NULL  , 
      qtd float  (15,2)   , 
      fantasia varchar  (255)   , 
      ap_representante_id int   , 
      meta_import_id int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_import_tabela_preco( 
      id  INT IDENTITY    NOT NULL  , 
      fazparte char  (1)   , 
      ap_tabela_preco_id int   NOT NULL  , 
      meta_import_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_repres( 
      id  INT IDENTITY    NOT NULL  , 
      meta_id int   , 
      repres_id int   , 
      fantasia varchar  (255)   , 
      valor_meta float  (15,2)   , 
      valor_super_meta float  (15,2)   , 
      valor_moc float  (15,2)   , 
      valor_cortina float  (15,2)   , 
      valor_mostruario float  (15,2)   , 
      valor_prospeccao float  (15,2)   , 
      valor_reativacao float  (15,2)   , 
      valor_prosp_reat float   , 
      perc_site float  (15,2)   , 
      perc_aprov_carteira float  (15,2)   , 
      perc_cliente_abaixo float  (15,2)   , 
      perc_fora_estado float  (15,2)   , 
      perc_import float   , 
      perc_persianas float   , 
      valor_import float   , 
      valor_persianas float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_trimestral( 
      id  INT IDENTITY    NOT NULL  , 
      descricao varchar  (255)   NOT NULL  , 
      data_inicial date   NOT NULL  , 
      data_final date   , 
      status int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_trimestral_repres( 
      id  INT IDENTITY    NOT NULL  , 
      meta_trimestral_id int   NOT NULL  , 
      ap_representante_id int   NOT NULL  , 
      valor float   NOT NULL  , 
      pontuacao_inicial float   , 
      pontuacao_alvo float   , 
      pontuacao_atual float   , 
      fantasia varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE mini_meta( 
      system_unit_id int   NOT NULL  , 
      id  INT IDENTITY    NOT NULL  , 
      mini_meta_tipo_id int   NOT NULL  , 
      ap_tabela_preco_id int   NOT NULL  , 
      painel int   , 
      descricao varchar  (255)   , 
      ano int   , 
      mes int   , 
      data_inicial datetime2   , 
      data_final datetime2   , 
      status int     DEFAULT 1, 
      min float   , 
      qtde float   , 
      mes_ano varchar  (255)   , 
      valor_unitario_min float  (15,2)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE mini_meta_fechamento( 
      id  INT IDENTITY    NOT NULL  , 
      mini_meta_id int   NOT NULL  , 
      ap_representante_id int   NOT NULL  , 
      alcancou_st char   , 
      quantidade_st float   , 
      premio_st float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE mini_meta_item( 
      id  INT IDENTITY    NOT NULL  , 
      mini_meta_id int   NOT NULL  , 
      cod_item nvarchar(max)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE minimeta_tabela_preco( 
      id  INT IDENTITY    NOT NULL  , 
      ap_tabela_preco_id int   NOT NULL  , 
      mini_meta_id int   NOT NULL  , 
      fazparte char   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE mini_meta_tipo( 
      id  INT IDENTITY    NOT NULL  , 
      nome varchar  (255)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE planejamento_import( 
      id  INT IDENTITY    NOT NULL  , 
      qtd int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE planejamento_import_dias( 
      id  INT IDENTITY    NOT NULL  , 
      planejamento_import_id int   NOT NULL  , 
      dia int   , 
      valido char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE planejamento_import_excecao( 
      id  INT IDENTITY    NOT NULL  , 
      data date   , 
      planejamento_import_id int   NOT NULL  , 
      qtd int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE preferencia_sistema( 
      id  INT IDENTITY    NOT NULL  , 
      system_users_id int   NOT NULL  , 
      zoom int   NOT NULL    DEFAULT 100, 
      data_criacao datetime2   , 
      criacao_user_id int   , 
      data_modificacao datetime2   , 
      modificacao_user_id int   , 
      menu_fixado int   NOT NULL    DEFAULT 0, 
 PRIMARY KEY (id)) ; 

CREATE TABLE premio( 
      id  INT IDENTITY    NOT NULL  , 
      ano int   NOT NULL  , 
      mes int   NOT NULL  , 
      tipo_premio_id int   NOT NULL  , 
      obs nvarchar(max)   , 
      restricao char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE premio_regra( 
      id  INT IDENTITY    NOT NULL  , 
      premio_id int   NOT NULL  , 
      comparador_id int   NOT NULL  , 
      dado_0 float   NOT NULL  , 
      dado_1 float   , 
      bonus float   NOT NULL  , 
      tipo_valor int   NOT NULL  , 
      alvo_valor char  (1)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE regra_prospeccao( 
      system_unit_id int   NOT NULL  , 
      id  INT IDENTITY    NOT NULL  , 
      ano int   , 
      mes int   , 
      estado_op int   , 
      filial_op int   , 
      estado varchar  (255)   , 
      filial varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE restricao_premio( 
      id  INT IDENTITY    NOT NULL  , 
      premio_id int   NOT NULL  , 
      ap_grupo_estoque_id int   NOT NULL  , 
      ap_subgrupo_estoque_id int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_group( 
      id int   NOT NULL  , 
      name nvarchar(max)   NOT NULL  , 
      uuid varchar  (36)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_group_program( 
      id int   NOT NULL  , 
      system_group_id int   NOT NULL  , 
      system_program_id int   NOT NULL  , 
      actions nvarchar(max)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_preference( 
      id varchar  (255)   NOT NULL  , 
      preference nvarchar(max)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_program( 
      id int   NOT NULL  , 
      name nvarchar(max)   NOT NULL  , 
      controller nvarchar(max)   NOT NULL  , 
      actions nvarchar(max)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_unit( 
      id int   NOT NULL  , 
      name nvarchar(max)   NOT NULL  , 
      connection_name nvarchar(max)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_user_group( 
      id int   NOT NULL  , 
      system_user_id int   NOT NULL  , 
      system_group_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_user_program( 
      id int   NOT NULL  , 
      system_user_id int   NOT NULL  , 
      system_program_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_users( 
      system_unit_id int   , 
      id int   NOT NULL  , 
      name nvarchar(max)   NOT NULL  , 
      login nvarchar(max)   NOT NULL  , 
      password nvarchar(max)   NOT NULL  , 
      email nvarchar(max)   , 
      frontpage_id int   , 
      active char  (1)   , 
      accepted_term_policy_at nvarchar(max)   , 
      accepted_term_policy char  (1)   , 
      two_factor_enabled char  (1)     DEFAULT 'N', 
      two_factor_type varchar  (100)   , 
      two_factor_secret varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_user_unit( 
      id int   NOT NULL  , 
      system_user_id int   NOT NULL  , 
      system_unit_id int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE tipo_premio( 
      id  INT IDENTITY    NOT NULL  , 
      descricao varchar  (255)   NOT NULL  , 
      coluna varchar  (255)   , 
      seq int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE trimestre_cliente_atual( 
      id  INT IDENTITY    NOT NULL  , 
      meta_trimestral_id int   NOT NULL  , 
      data_insercao datetime2   NOT NULL  , 
      cod_clifor char  (7)   NOT NULL  , 
      razao_social varchar  (255)   , 
      dt_cadastro datetime2   , 
      grupo_id int   , 
      repres_id int   , 
      ativo char  (1)   , 
      reativacao varchar  (10)   , 
      tipo_pessoa char  (1)   , 
      filial char  (1)   , 
      cod_principal char  (7)   , 
      rota varchar  (255)   , 
      cidade_id int   , 
      estado_id int   , 
      mes_inicial int   , 
      valor_total float   , 
      valor_mostruario float   , 
      valor_media float   , 
      valor_grupo float   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE trimestre_cliente_inicial( 
      id  INT IDENTITY    NOT NULL  , 
      meta_trimestral_id int   NOT NULL  , 
      data_insercao datetime2   NOT NULL  , 
      cod_clifor char  (7)   NOT NULL  , 
      razao_social varchar  (255)   , 
      dt_cadastro datetime2   , 
      grupo_id int   , 
      repres_id int   , 
      ativo char  (1)   , 
      reativacao varchar  (10)   , 
      tipo_pessoa char  (1)   , 
      filial char  (1)   , 
      cod_principal char  (7)   , 
      rota varchar  (255)   , 
      cidade_id int   , 
      estado_id int   , 
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
