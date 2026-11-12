# Ortega Clinic - Sistema de Gestão de Clínica Veterinária

**Tecnologias**: PHP 8.5, PostgreSQL, PDO

**Arquitetura**: MVC Artesanal, Autoload PSR-4 via composer

## Apresentação do projeto e visão geral

Este documento apresenta a resolução integral para o **sistema de gestão de clínica veterinária** - SGVC 

- 


## Entregas

### 1. Identificação do projeto
- **Nome do Sistema**: Ortega Clinic - Sistema de gestão de clínica veterinaria 
- **Público-Alvo**: Veterinarios, Recepcionistas e Gestores da Clínica
- **Problema**: O controle manual dos agendamentos e dos registros de tutores e pets tem
ocasionado perda de consultas, duplicidade de informações e falhas no armazenamento de dados, ausência de mecanismos adequeados de proteção dos dados pessoais dos tutores, como CPF, pode compremeter a segurança dessas informações e resultar no descumprimento das disposições da LGPD

### 2. Requisitos Funcionais (RF)
- **RF-01**: Autenticação do usuário com sessão e expiração => o sistema deve autenticar o usuário com email e senha (hash de criptografia). Exibir alerta em caso de erro. Sessão deve expirar automaticamente após 15 minutos de inatividade. 
- **RF-02**: Logout => encerramento da sessão, destruindo os dados do usuário (cookies)
- **RF-03**: Painel principal (DASHBOARD) => tela inicial após o login, deve exibir o nome do usuário logado
- **RF-04**: Cadastro de tutores (LGPD): Cadastrar informações dos tutores criptografando dados sensíveis (CPF)
- **RF-05**: Busca e listagem de tutores => Disponibilizar um campo de pesquisa para localizar tutores por meio de um termo informado pelo usuário, atualizando a listagem conforme os resultados encontrados
- **RF-06**: Edição de tutores => Permitir a alteração das informações de tutores já cadastrados, salvando as modificações no banco de dados 
- **RF-07**: Cadastro de pets => Permitir o cadastro de novos pets, obrigatoriamente vínculados a um tutor previamente cadastrado. 
- **RF-08**: Listagem de pets => Exibir os pets cadastrados no sistema, apresentando suas informações e respectivos tutores.
- **RF-09**: Cadastro e listagem de agendamentos => Permitir o cadastro de agendamentos e apresentar a listafem organizada por dara, incluindo as informações correspodentes ao tutor e ao pet
- **RF-10**: Atualização de agendamentos => Permitir a atualização da data de um agendamento já cadastrado, salvando as alterações no banco de dados 

## 2.1 Requisitos Não funcionais (RNF) 
- **RNF-01**: Arquiteura MVC => Organizar o código utilizando o padrão Model-View-Controller, separando as responsabilidades da aplicação.
- **RNF-02**: Autoload PSR-4 => utilizar o composer para realizar o carregamento automático das classes
- **RNF-03**: Segurança de dados => Implementar mecanismos de proteção, incluindo criptografia do CPF e hash de senhas.
- **RNF-04**: Banco de dados relacional => Utilizar PostgreSQL para armazenar e relacionar as informações do sistema 
- **RNF-05**: Prevenção contra vunerabilidades => Utilizar consultas preparadas com PDO e mecanismos de proteção contra SQL Injection, XSS e CSRF.
- **RNF-06**: Gerenciamento de sessão => garantir o controle de acesso por meio de sessões autenticadas e expiração por inatividade 
- **RNF-07**: Manutenibilidade => Desenvolver o código de maneira organizada, facilitando futuras correções, atualizações e implementações. 

## 3. Diagrama Entidade-Relacionamento (DER)

### Entidades do sistema 

- **Usuários:** armazenam as informações de acesso dos profissionais da clínica
- **Tutores:** armazenam os dados pessoais dos responsáveis pelos animais 
- **Pets:** armazenam as informações dos animais cadastrados, vinculando cada pet a um tutor
- **Agendamento:** armazenam os dados das consultas e serviços agendados, relacionando tutores, pets e usuários responsáveis pelo registro

### Diagrama 
```mermaid
erDiagram 
    usuarios {
        int id PK
        varchar nome
        varchar email UK
        varchar senha_hash
        varchar perfil
        timestamp criado_em
    }

    tutores {
        int id PK
        varchar nome
        varchar cpf_criptografado
        varchar telefone
        varchar email
        timestamp criado_em
    }

    pets {
        int id PK
        int tutor_id FK
        varchar nome
        varchar especie
        varchar raca
        date data_nascimento
        timestamp cadastrado_em
    }

    agendamentos {
        int id PK
        int tutor_id FK
        int pet_id FK
        int usuario_id FK
        date data_agendamento
        time hora_agendamento
        varchar servico
        varchar status
        text observacao
    }

    usuarios ||--o{ agendamentos: registra}
    tutores ||--o{ pets: possui}
    tutores ||--o{ agendamentos: responsavel}
    pets ||--o{ agendamentos: participa}
```
### 4. Relacionamentos

- Um tutor pode possuir vários pets (1:N).
- Um tutor pode possuir vários agendamentos (1:N).
- Um pet pode estar associado a vários agendamentos (1:N).
- Um usuário pode registrar vários agendamentos (1:N).

---

## 5. Banco de Dados - Script de Criação e População

O banco de dados foi desenvolvido utilizando PostgreSQL, reponsável pelo armazenamento e gerenciamento das informações da aplicação

O arquivo `schame.sql` contém os comandos necessários para a criação e população das tabelas.

### Estrutura do banco de dados 

- `usuarios`: informações dos usuários e credenciais de acesso
- `tutores`: dados pessoais dos responsáveis pelos pets
- `pets`: informações dos animais cadastrados 
- `agendamentos`: registros de consultas e servições agendados. 

O script também deve contemplar:

- Definição das chaves primárias (PK).
- Definição das chaves estrangeiras (FK).
- Restrições de unicidade e integridade dos dados.
- Inserção de pelo menos três registros em cada tabela criada, conforme solicitado no desafio SAEP.

---

## 6. Construção da aplicação 

A aplicação foi estruturada utilizando o padrão arquitetural MVC, com o objetivo de separar as responsabilidades e facilitar a manutenção do código.

### Tecnologias utilizadas
| Tecnologia | Finalidade |
|---|---|
| PHP 8.5 | Desenvolvimento da aplicação e regras de negócio. |
| PostgreSQL | Armazenamento e gerenciamento dos dados. | 
| PDO | Comunicação segura entre PHP e banco de dados. | 
| Composer | Gerenciamento de dependências e autoload PSR-4. |

### Organização da arquitetura MVC

- **Models:** responsáveis pela comunicação com o banco de dados e manipulação das informações.
- **Views:** responsáveis pela interface apresentada ao usuário. 
- **Controllers:** responsáveis por receber as solicitações, executar as regras de negócio e controlar o fluxo da aplicação.

---

## 7. Segurança e Proteção de dados 

A segurança é um dos pontos fundamentais da Ortega Clinic, principalmente por envolver informações pessoais dos tutores.

Foram consideradas as seguintes medidas de proteção:

- **LGPD:** proteção das informações pessoais armazenadas no sistema.
- **Criptografia do CPF:** armazenamento do CPF de forma criptografada no banco de dados.
- **Hash de senhas:** utilização de mecanismos de hash para proteger as credenciais dos usários.
- **PDO:** utilização de consultas preparadas para reduzir riscos de SQL Injection. 
- **Proteção contra CSRF:** utilização de mecanismos de validação das solicitações. 
- **Controle de sessão:** expiração automática após 15 minutos de inatividade e encerramento seguro do acesso