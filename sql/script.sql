/* 
Script para criação do banco de dados no PostgreSQL.
*/

--CREATE DATABASE expoagro;

DROP TABLE IF EXISTS produto_produtor;
DROP TABLE IF EXISTS produto;
DROP TABLE IF EXISTS produtor;
DROP TABLE IF EXISTS pontuacao_premiacao;
DROP TABLE IF EXISTS premiacao;
DROP TABLE IF EXISTS pontuacao;
DROP TABLE IF EXISTS categoria;

CREATE TABLE categoria (
    id SERIAL,
    nome TEXT NOT NULL
);
ALTER TABLE categoria ADD CONSTRAINT pk_categoria PRIMARY KEY (id);
ALTER TABLE categoria ADD CONSTRAINT uq_categoria_nome UNIQUE (nome);

CREATE TABLE pontuacao (
    id SERIAL,
    colocacao CHAR(1) NOT NULL,
    pontos INTEGER NOT NULL,
    categoria INTEGER NOT NULL
);
ALTER TABLE pontuacao ADD CONSTRAINT pk_pontuacao PRIMARY KEY (id);
ALTER TABLE pontuacao ADD CONSTRAINT uq_pontuacao_catcolocacao UNIQUE 
    (categoria, colocacao);
ALTER TABLE pontuacao ADD CONSTRAINT fk_pontuacao_categoria
    FOREIGN KEY (categoria) REFERENCES categoria (id)
    ON UPDATE CASCADE ON DELETE CASCADE;

CREATE TABLE premiacao (
    id SERIAL,
    premio TEXT NOT NULL,
    valor NUMERIC
);
ALTER TABLE premiacao ADD CONSTRAINT pk_premiacao PRIMARY KEY (id);
ALTER TABLE premiacao ADD CONSTRAINT uq_premiacao_premio UNIQUE (premio);

CREATE TABLE pontuacao_premiacao (
    pontuacao INTEGER,
    premiacao INTEGER,
    quantidade INTEGER
);
ALTER TABLE pontuacao_premiacao ADD CONSTRAINT pk_pontpremiacao PRIMARY KEY
    (pontuacao, premiacao);
ALTER TABLE pontuacao_premiacao ADD CONSTRAINT fk_pontpremiacao_pontuacao
    FOREIGN KEY (pontuacao) REFERENCES pontuacao (id)
    ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE pontuacao_premiacao ADD CONSTRAINT fk_pontpremiacao_premiacao
    FOREIGN KEY (premiacao) REFERENCES premiacao (id);

CREATE TABLE produtor (
    id SERIAL,
    nome TEXT NOT NULL,
    endereco TEXT
);
ALTER TABLE produtor ADD CONSTRAINT pk_produtor PRIMARY KEY (id);
ALTER TABLE produtor ADD CONSTRAINT uq_produtor_nome UNIQUE (nome);

CREATE TABLE produto (
    id SERIAL,
    nome TEXT NOT NULL,
    categoria INTEGER
);
ALTER TABLE produto ADD CONSTRAINT pk_produto PRIMARY KEY (id);
ALTER TABLE produto ADD CONSTRAINT uq_produto_nome UNIQUE (nome);
ALTER TABLE produto ADD CONSTRAINT fk_produto_categoria
    FOREIGN KEY (categoria) REFERENCES categoria (id)
    ON UPDATE CASCADE ON DELETE SET NULL;

CREATE TABLE produto_produtor (
    produto INTEGER,
    produtor INTEGER,
    classificacao CHAR(1)
);
ALTER TABLE produto_produtor ADD CONSTRAINT pk_prodprodutor PRIMARY KEY
    (produto, produtor);
ALTER TABLE produto_produtor ADD CONSTRAINT fk_prodprodutor_produto
    FOREIGN KEY (produto) REFERENCES produto (id);
ALTER TABLE produto_produtor ADD CONSTRAINT fk_prodprodutor_produtor
    FOREIGN KEY (produtor) REFERENCES produtor (id);	
