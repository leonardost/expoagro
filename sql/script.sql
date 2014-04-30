--CREATE DATABASE expoagro;

--DROP TABLE produtor_produto;
--DROP TABLE produto;
--DROP TABLE produtor;
--DROP TABLE pontuacao_premiacao;
--DROP TABLE premiacao;
--DROP TABLE pontuacao;
--DROP TABLE categoria;

CREATE TABLE categoria (
	id SERIAL,
	nome TEXT NOT NULL
);
ALTER TABLE categoria ADD CONSTRAINT categoria_pk PRIMARY KEY (id);
ALTER TABLE categoria ADD CONSTRAINT categoria_uq UNIQUE (nome);

CREATE TABLE pontuacao (
	id SERIAL,
	colocacao CHAR(1) NOT NULL,
	pontos INTEGER NOT NULL,
	id_categoria INTEGER NOT NULL
);
ALTER TABLE pontuacao ADD CONSTRAINT pontuacao_pk PRIMARY KEY (id);
ALTER TABLE pontuacao ADD CONSTRAINT pontuacao_uq UNIQUE (id_categoria, 
	colocacao);
ALTER TABLE pontuacao ADD CONSTRAINT pontuacao_fk FOREIGN KEY 
	(id_categoria) REFERENCES categoria (id) ON UPDATE CASCADE ON DELETE
	CASCADE;

CREATE TABLE premiacao (
	id SERIAL,
	premio TEXT NOT NULL,
	valor NUMERIC
);
ALTER TABLE premiacao ADD CONSTRAINT premiacao_pk PRIMARY KEY (id);
ALTER TABLE premiacao ADD CONSTRAINT premiacao_uq UNIQUE (premio);

CREATE TABLE pontuacao_premiacao (
	id_pontuacao INTEGER,
	id_premiacao INTEGER
);
ALTER TABLE pontuacao_premiacao ADD CONSTRAINT pontuacao_premiacao_pk 
	PRIMARY KEY (id_pontuacao, id_premiacao);
ALTER TABLE pontuacao_premiacao ADD CONSTRAINT pontuacao_premiacao_fk1
	FOREIGN KEY (id_pontuacao) REFERENCES pontuacao (id) ON UPDATE
	CASCADE ON DELETE CASCADE;
ALTER TABLE pontuacao_premiacao ADD CONSTRAINT pontuacao_premiacao_fk2
	FOREIGN KEY (id_premiacao) REFERENCES premiacao (id) ON UPDATE
	CASCADE ON DELETE CASCADE;

CREATE TABLE produtor (
    id SERIAL,
    nome TEXT NOT NULL,
    endereco TEXT
);
ALTER TABLE produtor ADD CONSTRAINT produtor_pk PRIMARY KEY (id);
ALTER TABLE produtor ADD CONSTRAINT produtor_uq UNIQUE (nome);

CREATE TABLE produto (
    id SERIAL,
    nome TEXT NOT NULL,
    id_categoria INTEGER
);
ALTER TABLE produto ADD CONSTRAINT produto_pk PRIMARY KEY (id);
ALTER TABLE produto ADD CONSTRAINT produto_uq UNIQUE (nome);
ALTER TABLE produto ADD CONSTRAINT produto_fk FOREIGN KEY (id_categoria) 
	REFERENCES categoria (id) ON UPDATE CASCADE ON DELETE SET NULL;

CREATE TABLE produtor_produto (
    id_produtor INTEGER,
    id_produto INTEGER,
    classificacao CHAR(1)
);
ALTER TABLE produtor_produto ADD CONSTRAINT produtor_produto_pk PRIMARY 
	KEY (id_produtor, id_produto);
ALTER TABLE produtor_produto ADD CONSTRAINT produtor_produto_fk1 FOREIGN
	KEY (id_produtor) REFERENCES produtor (id);
ALTER TABLE produtor_produto ADD CONSTRAINT produtor_produto_fk2 FOREIGN
	KEY (id_produto) REFERENCES produto (id);	
