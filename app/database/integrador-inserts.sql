INSERT INTO campanha_tipo (id,nome) VALUES (1,'Quantidade'); 

INSERT INTO campanha_tipo (id,nome) VALUES (2,'Ranking'); 

INSERT INTO comparador (id,descricao,operador) VALUES (1,'Maior','>'); 

INSERT INTO comparador (id,descricao,operador) VALUES (2,'Menor','<'); 

INSERT INTO comparador (id,descricao,operador) VALUES (3,'Maior ou Igual','>='); 

INSERT INTO comparador (id,descricao,operador) VALUES (4,'Menor ou Igual','<='); 

INSERT INTO comparador (id,descricao,operador) VALUES (5,'Igual','='); 

INSERT INTO comparador (id,descricao,operador) VALUES (6,'Está entre','BETWEEN'); 

INSERT INTO mini_meta_tipo (id,nome) VALUES (1,'Quantidade'); 

INSERT INTO mini_meta_tipo (id,nome) VALUES (2,'Valor'); 

INSERT INTO preferencia_sistema (id,system_users_id,zoom,data_criacao,criacao_user_id,data_modificacao,modificacao_user_id,menu_fixado) VALUES (01,1,100,null,null,null,null,0); 

INSERT INTO premio_regra (id,premio_id,comparador_id,dado_0,dado_1,bonus,tipo_valor,alvo_valor) VALUES (1,1,6,5.00,14.99,5.00,1,'2'); 

INSERT INTO premio_regra (id,premio_id,comparador_id,dado_0,dado_1,bonus,tipo_valor,alvo_valor) VALUES (2,1,6,10.00,49.99,10.00,1,'2'); 

INSERT INTO premio_regra (id,premio_id,comparador_id,dado_0,dado_1,bonus,tipo_valor,alvo_valor) VALUES (3,1,3,50.00,null,1000.00,2,'1'); 

INSERT INTO regra_prospeccao (system_unit_id,id,ano,mes,estado_op,filial_op,estado,filial) VALUES (1,1,2024,04,2,2,null,null); 

INSERT INTO system_group (id,name,uuid) VALUES (1,'Admin',null); 

INSERT INTO system_group (id,name,uuid) VALUES (2,'Standard',null); 

INSERT INTO system_unit (id,name,connection_name) VALUES (1,'Matriz','matriz'); 

INSERT INTO system_unit (id,name,connection_name) VALUES (2,'Teste','teste'); 

INSERT INTO system_user_group (id,system_user_id,system_group_id) VALUES (1,1,1); 

INSERT INTO system_user_group (id,system_user_id,system_group_id) VALUES (2,2,2); 

INSERT INTO system_users (system_unit_id,id,name,login,password,email,frontpage_id,active,accepted_term_policy_at,accepted_term_policy,two_factor_enabled,two_factor_type,two_factor_secret) VALUES (1,1,'Administrator','admin','21232f297a57a5a743894a0e4a801fc3','admin@admin.net',null,'Y','','',null,null,null); 

INSERT INTO system_users (system_unit_id,id,name,login,password,email,frontpage_id,active,accepted_term_policy_at,accepted_term_policy,two_factor_enabled,two_factor_type,two_factor_secret) VALUES (1,2,'User','user','ee11cbb19052e40b07aac0ca060c23ee','user@user.net',null,'Y','','',null,null,null); 

INSERT INTO system_user_unit (id,system_user_id,system_unit_id) VALUES (1,1,1); 

INSERT INTO system_user_unit (id,system_user_id,system_unit_id) VALUES (2,1,2); 

INSERT INTO tipo_premio (id,descricao,coluna,seq) VALUES (1,'Cortina','valor_cortina',1); 

INSERT INTO tipo_premio (id,descricao,coluna,seq) VALUES (2,'Super Meta','valor_super_meta',1); 
