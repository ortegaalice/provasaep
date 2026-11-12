-- Ortega Clinic | PostgreSQL
-- Execute este arquivo em um banco vazio chamado ortega_clinic.

CREATE TABLE IF NOT EXISTS usuarios (
    id BIGSERIAL PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    perfil VARCHAR(30) NOT NULL DEFAULT 'funcionario',
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS tutores (
    id BIGSERIAL PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    cpf_criptografado TEXT NOT NULL,
    telefone VARCHAR(30) NOT NULL,
    email VARCHAR(150) NOT NULL,
    criado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS pets (
    id BIGSERIAL PRIMARY KEY,
    tutor_id BIGINT NOT NULL REFERENCES tutores(id) ON DELETE RESTRICT,
    nome VARCHAR(100) NOT NULL,
    especie VARCHAR(60) NOT NULL,
    raca VARCHAR(100),
    data_nascimento DATE,
    cadastrado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS agendamentos (
    id BIGSERIAL PRIMARY KEY,
    tutor_id BIGINT NOT NULL REFERENCES tutores(id) ON DELETE RESTRICT,
    pet_id BIGINT NOT NULL REFERENCES pets(id) ON DELETE RESTRICT,
    usuario_id BIGINT NOT NULL REFERENCES usuarios(id) ON DELETE RESTRICT,
    data_agendamento DATE NOT NULL,
    hora_agendamento TIME NOT NULL,
    servico VARCHAR(120) NOT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'Agendado',
    observacao TEXT
);

CREATE INDEX IF NOT EXISTS idx_tutores_nome ON tutores(nome);
CREATE INDEX IF NOT EXISTS idx_agendamentos_data ON agendamentos(data_agendamento);

-- Usuários de demonstração. Senha de todos: Admin123!
INSERT INTO usuarios (nome,email,senha_hash,perfil) VALUES
('Administrador','admin@ortegaclinic.com','$2y$12$4wxLzkjqg8oj/6vrbF8Yiuj5fLuipMQjXa5NG9jSqYZhNtjc4CKXK','admin'),
('Marina Costa','marina@ortegaclinic.com','$2y$12$4wxLzkjqg8oj/6vrbF8Yiuj5fLuipMQjXa5NG9jSqYZhNtjc4CKXK','funcionario'),
('Rafael Lima','rafael@ortegaclinic.com','$2y$12$4wxLzkjqg8oj/6vrbF8Yiuj5fLuipMQjXa5NG9jSqYZhNtjc4CKXK','funcionario')
ON CONFLICT (email) DO NOTHING;

-- CPFs abaixo são dados fictícios de demonstração e não devem ser usados em produção.
-- A aplicação criptografa os CPFs cadastrados pela interface.
INSERT INTO tutores (nome,cpf_criptografado,telefone,email) VALUES
('Ana Souza','DADO_DEMO_NAO_UTILIZAR','(19) 99999-1001','ana@email.com'),
('Bruno Martins','DADO_DEMO_NAO_UTILIZAR','(19) 99999-1002','bruno@email.com'),
('Carla Oliveira','DADO_DEMO_NAO_UTILIZAR','(19) 99999-1003','carla@email.com')
ON CONFLICT DO NOTHING;

-- Para permitir a demonstração das listagens, os registros de pets e agendamentos
-- são incluídos somente se os tutores de demonstração estiverem presentes.
INSERT INTO pets (tutor_id,nome,especie,raca,data_nascimento)
SELECT t.id, v.nome, v.especie, v.raca, v.nascimento::date
FROM (VALUES
 ('Ana Souza','Luna','Cachorro','Golden Retriever','2021-04-12'),
 ('Bruno Martins','Mingau','Gato','SRD','2022-09-03'),
 ('Carla Oliveira','Thor','Cachorro','Labrador','2020-01-20')
) AS v(tutor,nome,especie,raca,nascimento)
JOIN tutores t ON t.nome=v.tutor
WHERE NOT EXISTS (SELECT 1 FROM pets p WHERE p.nome=v.nome AND p.tutor_id=t.id);

INSERT INTO agendamentos (tutor_id,pet_id,usuario_id,data_agendamento,hora_agendamento,servico,status,observacao)
SELECT p.tutor_id,p.id,u.id,CURRENT_DATE + 1, '09:00','Consulta veterinária','Agendado','Atendimento de demonstração'
FROM pets p CROSS JOIN (SELECT id FROM usuarios WHERE email='admin@ortegaclinic.com' LIMIT 1) u
WHERE p.nome='Luna' AND NOT EXISTS (SELECT 1 FROM agendamentos a WHERE a.pet_id=p.id);
INSERT INTO agendamentos (tutor_id,pet_id,usuario_id,data_agendamento,hora_agendamento,servico,status)
SELECT p.tutor_id,p.id,u.id,CURRENT_DATE + 2, '10:30','Vacinação','Agendado'
FROM pets p CROSS JOIN (SELECT id FROM usuarios WHERE email='admin@ortegaclinic.com' LIMIT 1) u
WHERE p.nome='Mingau' AND NOT EXISTS (SELECT 1 FROM agendamentos a WHERE a.pet_id=p.id);
INSERT INTO agendamentos (tutor_id,pet_id,usuario_id,data_agendamento,hora_agendamento,servico,status)
SELECT p.tutor_id,p.id,u.id,CURRENT_DATE + 3, '14:00','Retorno','Agendado'
FROM pets p CROSS JOIN (SELECT id FROM usuarios WHERE email='admin@ortegaclinic.com' LIMIT 1) u
WHERE p.nome='Thor' AND NOT EXISTS (SELECT 1 FROM agendamentos a WHERE a.pet_id=p.id);
