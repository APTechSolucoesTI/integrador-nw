PRAGMA foreign_keys=OFF; 

CREATE TABLE aguardo_produto( 
      system_unit_id int   NOT NULL  , 
      system_users_id int   NOT NULL  , 
      id  INTEGER    NOT NULL  , 
      repres_id int   NOT NULL  , 
      ap_item_id int   NOT NULL  , 
      quantidade double   , 
      status char  (1)     DEFAULT 'A', 
      cod_clifor char  (7)   , 
 PRIMARY KEY (id),
FOREIGN KEY(ap_item_id) REFERENCES ap_item(id),
FOREIGN KEY(system_users_id) REFERENCES system_users(id),
FOREIGN KEY(repres_id) REFERENCES ap_representante(id),
FOREIGN KEY(system_unit_id) REFERENCES system_unit(id)) ; 

CREATE TABLE ap_cidade( 
      id  INTEGER    NOT NULL  , 
      nome varchar  (255)   , 
      estado_id int   , 
      cod_cidade varchar  (5)   , 
      latitude double   , 
      longitude double   , 
 PRIMARY KEY (id),
FOREIGN KEY(estado_id) REFERENCES ap_estado(id)) ; 

CREATE TABLE ap_estado( 
      id  INTEGER    NOT NULL  , 
      cod_estado varchar  (10)   , 
      nome varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE ap_familia_comercial( 
      system_unit_id int   NOT NULL  , 
      id  INTEGER    NOT NULL  , 
      descricao varchar  (255)   NOT NULL  , 
      cod_fmcomercial char  (8)   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(system_unit_id) REFERENCES system_unit(id)) ; 

CREATE TABLE ap_familia_industrial( 
      system_unit_id int   NOT NULL  , 
      id  INTEGER    NOT NULL  , 
      descricao varchar  (255)   NOT NULL  , 
      cod_fmindustrial char  (8)   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(system_unit_id) REFERENCES system_unit(id)) ; 

CREATE TABLE ap_grupo_cliente( 
      id int   NOT NULL  , 
      cod_grpcliente varchar  (10)   , 
      descricao varchar  (255)   , 
      pontuacao int   , 
      valor_inicial double   , 
      valor_final double   , 
      system_unit_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(system_unit_id) REFERENCES system_unit(id)) ; 

CREATE TABLE ap_grupo_estoque( 
      system_unit_id int   NOT NULL  , 
      id  INTEGER    NOT NULL  , 
      cod_grupoestoque varchar  (8)   NOT NULL  , 
      descricao varchar  (255)   NOT NULL  , 
      sequencia_exibicao int   , 
 PRIMARY KEY (id),
FOREIGN KEY(system_unit_id) REFERENCES system_unit(id)) ; 

CREATE TABLE ap_item( 
      system_unit_id int   NOT NULL  , 
      id  INTEGER    NOT NULL  , 
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
 PRIMARY KEY (id),
FOREIGN KEY(subgrupo_estoque_id) REFERENCES ap_subgrupo_estoque(id),
FOREIGN KEY(grupo_estoque_id) REFERENCES ap_grupo_estoque(id),
FOREIGN KEY(familia_comercial_id) REFERENCES ap_familia_comercial(id),
FOREIGN KEY(familia_industrial_id) REFERENCES ap_familia_industrial(id),
FOREIGN KEY(system_unit_id) REFERENCES system_unit(id)) ; 

CREATE TABLE ap_representante( 
      system_unit_id int   NOT NULL  , 
      id  INTEGER    NOT NULL  , 
      cod_repres char  (7)   , 
      razao varchar  (255)   , 
      fantasia varchar  (255)   , 
      ativo char  (1)   , 
      system_users_id int   , 
      email varchar  (255)   , 
 PRIMARY KEY (id),
FOREIGN KEY(system_users_id) REFERENCES system_users(id),
FOREIGN KEY(system_unit_id) REFERENCES system_unit(id)) ; 

CREATE TABLE ap_subgrupo_estoque( 
      system_unit_id int   NOT NULL  , 
      id  INTEGER    NOT NULL  , 
      grupo_estoque_id int   , 
      cod_subgrupoestoque char  (8)   NOT NULL  , 
      cod_grupoestoque varchar  (15)   , 
      descricao varchar  (255)   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(grupo_estoque_id) REFERENCES ap_grupo_estoque(id),
FOREIGN KEY(system_unit_id) REFERENCES system_unit(id)) ; 

CREATE TABLE ap_tabela_preco( 
      id  INTEGER    NOT NULL  , 
      cod_tabelapreco char  (20)   , 
      descricao varchar  (100)   , 
      ativo char  (1)   , 
      system_unit_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(system_unit_id) REFERENCES system_unit(id)) ; 

CREATE TABLE campanha( 
      system_unit_id int   NOT NULL  , 
      ap_tabela_preco_id int   NOT NULL  , 
      campanha_tipo_id int   NOT NULL  , 
      id  INTEGER    NOT NULL  , 
      descricao varchar  (255)   , 
      data_inicial datetime   , 
      data_final datetime   , 
      status int     DEFAULT 1, 
      valor_unitario_min double  (15,2)   , 
      min double   , 
      qtde double   , 
      painel int   , 
 PRIMARY KEY (id),
FOREIGN KEY(ap_tabela_preco_id) REFERENCES ap_tabela_preco(id),
FOREIGN KEY(campanha_tipo_id) REFERENCES campanha_tipo(id),
FOREIGN KEY(system_unit_id) REFERENCES system_unit(id)) ; 

CREATE TABLE campanha_fechamento( 
      id  INTEGER    NOT NULL  , 
      premio double   , 
      campanha_id int   NOT NULL  , 
      ap_representante_id int   NOT NULL  , 
      alcancou_quantidade char   , 
      alcancou_ranking char   , 
      quantidade_total double   , 
 PRIMARY KEY (id),
FOREIGN KEY(campanha_id) REFERENCES campanha(id),
FOREIGN KEY(ap_representante_id) REFERENCES ap_representante(id)) ; 

CREATE TABLE campanha_item( 
      id  INTEGER    NOT NULL  , 
      campanha_id int   NOT NULL  , 
      cod_item text   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(campanha_id) REFERENCES campanha(id)) ; 

CREATE TABLE campanha_premio( 
      campanha_id int   NOT NULL  , 
      comparador_id int   NOT NULL  , 
      id  INTEGER    NOT NULL  , 
      dado_0 double   , 
      dado_1 double   , 
      premio varchar  (255)   NOT NULL  , 
      tipo_valor int   , 
      alvo_valor char  (1)   , 
 PRIMARY KEY (id),
FOREIGN KEY(comparador_id) REFERENCES comparador(id),
FOREIGN KEY(campanha_id) REFERENCES campanha(id)) ; 

CREATE TABLE campanha_repres( 
      id  INTEGER    NOT NULL  , 
      campanha_id int   NOT NULL  , 
      repres_id int   NOT NULL  , 
      quantidade double   , 
      valor double   , 
      premio double   , 
 PRIMARY KEY (id),
FOREIGN KEY(campanha_id) REFERENCES campanha(id),
FOREIGN KEY(repres_id) REFERENCES ap_representante(id)) ; 

CREATE TABLE campanha_tabela_preco( 
      id  INTEGER    NOT NULL  , 
      ap_tabela_preco_id int   NOT NULL  , 
      campanha_id int   NOT NULL  , 
      fazparte char   , 
 PRIMARY KEY (id),
FOREIGN KEY(ap_tabela_preco_id) REFERENCES ap_tabela_preco(id),
FOREIGN KEY(campanha_id) REFERENCES campanha(id)) ; 

CREATE TABLE campanha_tipo( 
      id  INTEGER    NOT NULL  , 
      nome varchar  (255)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE cep_coordenadas( 
      id  INTEGER    NOT NULL  , 
      cep varchar  (10)   , 
      latitude double   , 
      longitude double   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE comparador( 
      id  INTEGER    NOT NULL  , 
      descricao varchar  (255)   NOT NULL  , 
      operador varchar  (255)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE credito_cliente( 
      id  INTEGER    NOT NULL  , 
      cod_clifor varchar  (7)   , 
      valor_total double  (15,2)   , 
      media_por_pedido double  (15,2)   , 
      razao_clifor varchar  (255)   , 
      periodo_inicio datetime   , 
      periodo_fim datetime   , 
      quantidade_notas int   , 
      data_processamento datetime   , 
      historico_cli_id int   , 
      credito_cliente_cadastro_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(historico_cli_id) REFERENCES historico_cli_repres(id),
FOREIGN KEY(credito_cliente_cadastro_id) REFERENCES credito_cliente_cadastro(id)) ; 

CREATE TABLE credito_cliente_cadastro( 
      id  INTEGER    , 
      descricao text   NOT NULL  , 
      periodo_inicio datetime   NOT NULL  , 
      periodo_fim datetime   NOT NULL  , 
      data_criacao datetime   , 
      status int     DEFAULT 1, 
 PRIMARY KEY (id)) ; 

CREATE TABLE grupo( 
      system_unit_id int   NOT NULL  , 
      id  INTEGER    NOT NULL  , 
      cod_grupoestoque char  (8)   NOT NULL  , 
      descricao varchar  (255)   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(system_unit_id) REFERENCES system_unit(id)) ; 

CREATE TABLE historico_cli_repres( 
      system_unit_id int   NOT NULL  , 
      id  INTEGER    NOT NULL  , 
      ano int   , 
      mes int   , 
      cod_clifor char  (7)   , 
      razao_clifor varchar  (255)   , 
      dt_cadastro datetime   , 
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
      rota text   , 
      grupo_empresarial char  (1)   , 
      latitude double   , 
      longitude double   , 
      cep varchar  (10)   , 
      dt_atualizacao datetime   , 
      dt_change datetime   , 
 PRIMARY KEY (id),
FOREIGN KEY(grupo_id) REFERENCES ap_grupo_cliente(id),
FOREIGN KEY(repres_id) REFERENCES ap_representante(id),
FOREIGN KEY(system_unit_id) REFERENCES system_unit(id),
FOREIGN KEY(ap_cidade_id) REFERENCES ap_cidade(id)) ; 

CREATE TABLE historico_venda( 
      meta_id int   , 
      system_unit_id int   NOT NULL  , 
      id  INTEGER    NOT NULL  , 
      data_emissao datetime   , 
      data_cadastro datetime   , 
      nro char  (10)   , 
      tipo_pessoa char  (1)   , 
      cod_clifor char  (10)   , 
      razao varchar  (255)   , 
      agente text   , 
      repres_id int   , 
      grupo_cliente_id int   , 
      cidade_id int   , 
      estado_id int   , 
      rota text   , 
 PRIMARY KEY (id),
FOREIGN KEY(system_unit_id) REFERENCES system_unit(id),
FOREIGN KEY(cidade_id) REFERENCES ap_cidade(id),
FOREIGN KEY(grupo_cliente_id) REFERENCES ap_grupo_cliente(id),
FOREIGN KEY(repres_id) REFERENCES ap_representante(id),
FOREIGN KEY(meta_id) REFERENCES meta(id),
FOREIGN KEY(estado_id) REFERENCES ap_estado(id)) ; 

CREATE TABLE historico_venda_item( 
      empresa varchar  (255)   , 
      venda_id int   NOT NULL  , 
      id  INTEGER    NOT NULL  , 
      cod_item text   , 
      descricao varchar  (255)   , 
      grupo_estoque_id int   , 
      subgrupo_estoque_id int   , 
      familia_comercial_id int   , 
      familia_industrial_id int   , 
      quantidade double   , 
      valor_unitario double   , 
      valor_mercadoria double   , 
      perc_desconto double   , 
      valor_desconto double   , 
      valor_total double   , 
      sequencia int   , 
 PRIMARY KEY (id),
FOREIGN KEY(venda_id) REFERENCES historico_venda(id),
FOREIGN KEY(grupo_estoque_id) REFERENCES ap_grupo_estoque(id),
FOREIGN KEY(subgrupo_estoque_id) REFERENCES ap_subgrupo_estoque(id),
FOREIGN KEY(familia_industrial_id) REFERENCES ap_familia_industrial(id),
FOREIGN KEY(familia_comercial_id) REFERENCES ap_familia_comercial(id)) ; 

CREATE TABLE log_crontab( 
      system_unit_id int   NOT NULL  , 
      id  INTEGER    NOT NULL  , 
      classe text   NOT NULL  , 
      metodo text   , 
      data_hora datetime   , 
      status int   , 
      mensagem text   , 
      observacao text   , 
 PRIMARY KEY (id),
FOREIGN KEY(system_unit_id) REFERENCES system_unit(id)) ; 

CREATE TABLE meta( 
      system_unit_id int   NOT NULL  , 
      id  INTEGER    NOT NULL  , 
      ano int   , 
      mes int   , 
      data_inicial datetime   , 
      data_final datetime   , 
      data_abertura datetime   , 
      data_entrega datetime   , 
      data_me datetime   , 
      dias_uteis int   , 
      feriados int   , 
      dias_disponiveis int   , 
      status int     DEFAULT 1, 
      mes_ano varchar  (255)   , 
      limite_import int   , 
 PRIMARY KEY (id),
FOREIGN KEY(system_unit_id) REFERENCES system_unit(id)) ; 

CREATE TABLE meta_fechamento( 
      id  INTEGER    NOT NULL  , 
      meta_id int   NOT NULL  , 
      meta_repres_id int   NOT NULL  , 
      repres_id int   NOT NULL  , 
      faturamento_total double   , 
      faturamento_cortina double   , 
      faturamento_mostruario double   , 
      faturamento_prospeccao double   , 
      faturamento_reativacao double   , 
      faturamento_prosp_reat double   , 
      percentual_import double   , 
      percentual_persianas double   , 
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
      premio_cortina double   , 
      premio_mostruario double   , 
      premio_prospeccao double   , 
      premio_reativacao double   , 
      premio_site double   , 
      premio_prosp_reat double   , 
      comissao double   , 
      bonus_meta double   , 
      bonus_mini double   , 
      bonus_mini_pers double   , 
      bonus_mini_import double   , 
      st double   , 
      premio_estrategico double   , 
      site_porcentagem double   , 
 PRIMARY KEY (id),
FOREIGN KEY(repres_id) REFERENCES ap_representante(id),
FOREIGN KEY(meta_id) REFERENCES meta(id),
FOREIGN KEY(meta_repres_id) REFERENCES meta_repres(id)) ; 

CREATE TABLE meta_feriado( 
      id  INTEGER    NOT NULL  , 
      meta_id int   , 
      data_feriado date   , 
      descricao varchar  (255)   , 
 PRIMARY KEY (id),
FOREIGN KEY(meta_id) REFERENCES meta(id)) ; 

CREATE TABLE meta_import( 
      system_unit_id int   NOT NULL  , 
      id  INTEGER    NOT NULL  , 
      descricao varchar  (255)   , 
      data_inicial datetime   , 
      data_final datetime   , 
      status int     DEFAULT 1, 
      painel int   , 
      ano int   , 
      mes int   , 
      ap_tabela_preco_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(ap_tabela_preco_id) REFERENCES ap_tabela_preco(id)) ; 

CREATE TABLE meta_import_fechamento( 
      id  INTEGER    NOT NULL  , 
      meta_import_repres_id int   , 
      qtd_total double  (15,2)   , 
      meta_import_id int   NOT NULL  , 
      ap_representante_id int   NOT NULL  , 
      percentual_qtd double  (15,2)   , 
 PRIMARY KEY (id),
FOREIGN KEY(meta_import_repres_id) REFERENCES meta_import_repres(id),
FOREIGN KEY(meta_import_id) REFERENCES meta_import(id),
FOREIGN KEY(ap_representante_id) REFERENCES ap_representante(id)) ; 

CREATE TABLE meta_import_item( 
      id  INTEGER    NOT NULL  , 
      meta_import_id int   NOT NULL  , 
      cod_item text   , 
 PRIMARY KEY (id),
FOREIGN KEY(meta_import_id) REFERENCES meta_import(id)) ; 

CREATE TABLE meta_import_repres( 
      id  INTEGER    NOT NULL  , 
      qtd double  (15,2)   , 
      fantasia varchar  (255)   , 
      ap_representante_id int   , 
      meta_import_id int   , 
 PRIMARY KEY (id),
FOREIGN KEY(ap_representante_id) REFERENCES ap_representante(id),
FOREIGN KEY(meta_import_id) REFERENCES meta_import(id)) ; 

CREATE TABLE meta_import_tabela_preco( 
      id  INTEGER    NOT NULL  , 
      fazparte char  (1)   , 
      ap_tabela_preco_id int   NOT NULL  , 
      meta_import_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(ap_tabela_preco_id) REFERENCES ap_tabela_preco(id),
FOREIGN KEY(meta_import_id) REFERENCES meta_import(id)) ; 

CREATE TABLE meta_repres( 
      id  INTEGER    NOT NULL  , 
      meta_id int   , 
      repres_id int   , 
      fantasia varchar  (255)   , 
      valor_meta double  (15,2)   , 
      valor_super_meta double  (15,2)   , 
      valor_moc double  (15,2)   , 
      valor_cortina double  (15,2)   , 
      valor_mostruario double  (15,2)   , 
      valor_prospeccao double  (15,2)   , 
      valor_reativacao double  (15,2)   , 
      valor_prosp_reat double   , 
      perc_site double  (15,2)   , 
      perc_aprov_carteira double  (15,2)   , 
      perc_cliente_abaixo double  (15,2)   , 
      perc_fora_estado double  (15,2)   , 
      perc_import double   , 
      perc_persianas double   , 
      valor_import double   , 
      valor_persianas double   , 
 PRIMARY KEY (id),
FOREIGN KEY(repres_id) REFERENCES ap_representante(id),
FOREIGN KEY(meta_id) REFERENCES meta(id)) ; 

CREATE TABLE meta_trimestral( 
      id  INTEGER    NOT NULL  , 
      descricao varchar  (255)   NOT NULL  , 
      data_inicial date   NOT NULL  , 
      data_final date   , 
      status int   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE meta_trimestral_repres( 
      id  INTEGER    NOT NULL  , 
      meta_trimestral_id int   NOT NULL  , 
      ap_representante_id int   NOT NULL  , 
      valor double   NOT NULL  , 
      pontuacao_inicial double   , 
      pontuacao_alvo double   , 
      pontuacao_atual double   , 
      fantasia varchar  (255)   , 
 PRIMARY KEY (id),
FOREIGN KEY(meta_trimestral_id) REFERENCES meta_trimestral(id),
FOREIGN KEY(ap_representante_id) REFERENCES ap_representante(id)) ; 

CREATE TABLE mini_meta( 
      system_unit_id int   NOT NULL  , 
      id  INTEGER    NOT NULL  , 
      mini_meta_tipo_id int   NOT NULL  , 
      ap_tabela_preco_id int   NOT NULL  , 
      painel int   , 
      descricao varchar  (255)   , 
      ano int   , 
      mes int   , 
      data_inicial datetime   , 
      data_final datetime   , 
      status int     DEFAULT 1, 
      min double   , 
      qtde double   , 
      mes_ano varchar  (255)   , 
      valor_unitario_min double  (15,2)   , 
 PRIMARY KEY (id),
FOREIGN KEY(mini_meta_tipo_id) REFERENCES mini_meta_tipo(id),
FOREIGN KEY(system_unit_id) REFERENCES system_unit(id),
FOREIGN KEY(ap_tabela_preco_id) REFERENCES ap_tabela_preco(id)) ; 

CREATE TABLE mini_meta_fechamento( 
      id  INTEGER    NOT NULL  , 
      mini_meta_id int   NOT NULL  , 
      ap_representante_id int   NOT NULL  , 
      alcancou_st char   , 
      quantidade_st double   , 
      premio_st double   , 
 PRIMARY KEY (id),
FOREIGN KEY(mini_meta_id) REFERENCES mini_meta(id),
FOREIGN KEY(ap_representante_id) REFERENCES ap_representante(id)) ; 

CREATE TABLE mini_meta_item( 
      id  INTEGER    NOT NULL  , 
      mini_meta_id int   NOT NULL  , 
      cod_item text   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(mini_meta_id) REFERENCES mini_meta(id)) ; 

CREATE TABLE minimeta_tabela_preco( 
      id  INTEGER    NOT NULL  , 
      ap_tabela_preco_id int   NOT NULL  , 
      mini_meta_id int   NOT NULL  , 
      fazparte char   , 
 PRIMARY KEY (id),
FOREIGN KEY(ap_tabela_preco_id) REFERENCES ap_tabela_preco(id),
FOREIGN KEY(mini_meta_id) REFERENCES mini_meta(id)) ; 

CREATE TABLE mini_meta_tipo( 
      id  INTEGER    NOT NULL  , 
      nome varchar  (255)   NOT NULL  , 
 PRIMARY KEY (id)) ; 

CREATE TABLE persiana_agrupamento( 
      id  INTEGER    , 
      nome varchar  (30)   NOT NULL  , 
      qtd int   NOT NULL  , 
      data_inicio date   , 
      data_fim date   , 
      ativo char  (1)   NOT NULL    DEFAULT 'S', 
      eficiencia_operacional double   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE persiana_agrupamento_dias( 
      id  INTEGER    NOT NULL  , 
      dia int   , 
      valido char  (1)   , 
      persiana_agrupamento_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(persiana_agrupamento_id) REFERENCES persiana_agrupamento(id)) ; 

CREATE TABLE persiana_agrupamento_excecao( 
      id  INTEGER    NOT NULL  , 
      persiana_agrupamento_id int   NOT NULL  , 
      data date   , 
      qtd int   , 
 PRIMARY KEY (id),
FOREIGN KEY(persiana_agrupamento_id) REFERENCES persiana_agrupamento(id)) ; 

CREATE TABLE persiana_agrupamento_grupo( 
      id  INTEGER    , 
      persiana_agrupamento_id int   NOT NULL  , 
      ap_grupo_estoque_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(ap_grupo_estoque_id) REFERENCES ap_grupo_estoque(id),
FOREIGN KEY(persiana_agrupamento_id) REFERENCES persiana_agrupamento(id)) ; 

CREATE TABLE planejamento_import( 
      id  INTEGER    NOT NULL  , 
      qtd int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE planejamento_import_dias( 
      id  INTEGER    NOT NULL  , 
      planejamento_import_id int   NOT NULL  , 
      dia int   , 
      valido char  (1)   , 
 PRIMARY KEY (id),
FOREIGN KEY(planejamento_import_id) REFERENCES planejamento_import(id)) ; 

CREATE TABLE planejamento_import_excecao( 
      id  INTEGER    NOT NULL  , 
      data date   , 
      planejamento_import_id int   NOT NULL  , 
      qtd int   , 
 PRIMARY KEY (id),
FOREIGN KEY(planejamento_import_id) REFERENCES planejamento_import(id)) ; 

CREATE TABLE preferencia_sistema( 
      id  INTEGER    NOT NULL  , 
      system_users_id int   NOT NULL  , 
      zoom int   NOT NULL    DEFAULT 100, 
      data_criacao datetime   , 
      criacao_user_id int   , 
      data_modificacao datetime   , 
      modificacao_user_id int   , 
      menu_fixado int   NOT NULL    DEFAULT 0, 
 PRIMARY KEY (id),
FOREIGN KEY(system_users_id) REFERENCES system_users(id)) ; 

CREATE TABLE premio( 
      id  INTEGER    NOT NULL  , 
      ano int   NOT NULL  , 
      mes int   NOT NULL  , 
      tipo_premio_id int   NOT NULL  , 
      obs text   , 
      restricao char  (1)   , 
 PRIMARY KEY (id),
FOREIGN KEY(tipo_premio_id) REFERENCES tipo_premio(id)) ; 

CREATE TABLE premio_regra( 
      id  INTEGER    NOT NULL  , 
      premio_id int   NOT NULL  , 
      comparador_id int   NOT NULL  , 
      dado_0 double   NOT NULL  , 
      dado_1 double   , 
      bonus double   NOT NULL  , 
      tipo_valor int   NOT NULL  , 
      alvo_valor char  (1)   , 
 PRIMARY KEY (id),
FOREIGN KEY(premio_id) REFERENCES premio(id),
FOREIGN KEY(comparador_id) REFERENCES comparador(id)) ; 

CREATE TABLE regra_prospeccao( 
      system_unit_id int   NOT NULL  , 
      id  INTEGER    NOT NULL  , 
      ano int   , 
      mes int   , 
      estado_op int   , 
      filial_op int   , 
      estado varchar  (255)   , 
      filial varchar  (255)   , 
 PRIMARY KEY (id),
FOREIGN KEY(system_unit_id) REFERENCES system_unit(id)) ; 

CREATE TABLE restricao_premio( 
      id  INTEGER    NOT NULL  , 
      premio_id int   NOT NULL  , 
      ap_grupo_estoque_id int   NOT NULL  , 
      ap_subgrupo_estoque_id int   , 
 PRIMARY KEY (id),
FOREIGN KEY(premio_id) REFERENCES premio(id),
FOREIGN KEY(ap_grupo_estoque_id) REFERENCES ap_grupo_estoque(id),
FOREIGN KEY(ap_subgrupo_estoque_id) REFERENCES ap_subgrupo_estoque(id)) ; 

CREATE TABLE system_group( 
      id int   NOT NULL  , 
      name text   NOT NULL  , 
      uuid varchar  (36)   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_group_program( 
      id int   NOT NULL  , 
      system_group_id int   NOT NULL  , 
      system_program_id int   NOT NULL  , 
      actions text   , 
 PRIMARY KEY (id),
FOREIGN KEY(system_group_id) REFERENCES system_group(id),
FOREIGN KEY(system_program_id) REFERENCES system_program(id)) ; 

CREATE TABLE system_preference( 
      id varchar  (255)   NOT NULL  , 
      preference text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_program( 
      id int   NOT NULL  , 
      name text   NOT NULL  , 
      controller text   NOT NULL  , 
      actions text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_unit( 
      id int   NOT NULL  , 
      name text   NOT NULL  , 
      connection_name text   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE system_user_group( 
      id int   NOT NULL  , 
      system_user_id int   NOT NULL  , 
      system_group_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(system_group_id) REFERENCES system_group(id),
FOREIGN KEY(system_user_id) REFERENCES system_users(id)) ; 

CREATE TABLE system_user_program( 
      id int   NOT NULL  , 
      system_user_id int   NOT NULL  , 
      system_program_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(system_program_id) REFERENCES system_program(id),
FOREIGN KEY(system_user_id) REFERENCES system_users(id)) ; 

CREATE TABLE system_users( 
      system_unit_id int   , 
      id int   NOT NULL  , 
      name text   NOT NULL  , 
      login text   NOT NULL  , 
      password text   NOT NULL  , 
      email text   , 
      frontpage_id int   , 
      active char  (1)   , 
      accepted_term_policy_at text   , 
      accepted_term_policy char  (1)   , 
      two_factor_enabled char  (1)     DEFAULT 'N', 
      two_factor_type varchar  (100)   , 
      two_factor_secret varchar  (255)   , 
 PRIMARY KEY (id),
FOREIGN KEY(system_unit_id) REFERENCES system_unit(id),
FOREIGN KEY(frontpage_id) REFERENCES system_program(id)) ; 

CREATE TABLE system_user_unit( 
      id int   NOT NULL  , 
      system_user_id int   NOT NULL  , 
      system_unit_id int   NOT NULL  , 
 PRIMARY KEY (id),
FOREIGN KEY(system_user_id) REFERENCES system_users(id),
FOREIGN KEY(system_unit_id) REFERENCES system_unit(id)) ; 

CREATE TABLE tipo_premio( 
      id  INTEGER    NOT NULL  , 
      descricao varchar  (255)   NOT NULL  , 
      coluna varchar  (255)   , 
      seq int   , 
 PRIMARY KEY (id)) ; 

CREATE TABLE trimestre_cliente_atual( 
      id  INTEGER    NOT NULL  , 
      meta_trimestral_id int   NOT NULL  , 
      data_insercao datetime   NOT NULL  , 
      cod_clifor char  (7)   NOT NULL  , 
      razao_social varchar  (255)   , 
      dt_cadastro datetime   , 
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
      valor_total double   , 
      valor_mostruario double   , 
      valor_media double   , 
      valor_grupo double   , 
 PRIMARY KEY (id),
FOREIGN KEY(meta_trimestral_id) REFERENCES meta_trimestral(id),
FOREIGN KEY(grupo_id) REFERENCES ap_grupo_cliente(id),
FOREIGN KEY(repres_id) REFERENCES ap_representante(id),
FOREIGN KEY(cidade_id) REFERENCES ap_cidade(id),
FOREIGN KEY(estado_id) REFERENCES ap_estado(id)) ; 

CREATE TABLE trimestre_cliente_inicial( 
      id  INTEGER    NOT NULL  , 
      meta_trimestral_id int   NOT NULL  , 
      data_insercao datetime   NOT NULL  , 
      cod_clifor char  (7)   NOT NULL  , 
      razao_social varchar  (255)   , 
      dt_cadastro datetime   , 
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
 PRIMARY KEY (id),
FOREIGN KEY(meta_trimestral_id) REFERENCES meta_trimestral(id),
FOREIGN KEY(grupo_id) REFERENCES ap_grupo_cliente(id),
FOREIGN KEY(repres_id) REFERENCES ap_representante(id),
FOREIGN KEY(cidade_id) REFERENCES ap_cidade(id),
FOREIGN KEY(estado_id) REFERENCES ap_estado(id)) ; 

 
 CREATE UNIQUE INDEX unique_idx_persiana_agrupamento_nome ON persiana_agrupamento(nome);
 