DROP DATABASE IF EXISTS ESQL;
CREATE DATABASE IF NOT EXISTS ESQL;
USE ESQL;

CREATE TABLE DOCENTE (
	Email VARCHAR(100) PRIMARY KEY,
    PasswordDocente VARCHAR(20) NOT NULL,
    Nome VARCHAR (50) NOT NULL,
    Cognome VARCHAR (50) NOT NULL,
    RecapitoTelefonicoDocente INT,
    NomeDipartimento VARCHAR(100),
    NomeCorso VARCHAR(100)
    
) ENGINE = INNODB;

CREATE TABLE STUDENTE (
	Email VARCHAR(100) PRIMARY KEY,
    PasswordStudente VARCHAR(20) NOT NULL,
    Nome VARCHAR (50) NOT NULL,
    Cognome VARCHAR (50) NOT NULL,
    RecapitoTelefonicoStudente INT,
    AnnoImmatricolazione INT,
    CodiceAlfaNumerico CHAR(16)
    
) ENGINE = INNODB;

CREATE TABLE TEST (
	Titolo VARCHAR(100) PRIMARY KEY,
    DataCreazione DATETIME,
    Foto VARCHAR(255),											
    VisualizzaRisposte BOOLEAN,
    EmailDocente VARCHAR(100) NOT NULL,
    
    FOREIGN KEY(EmailDocente) REFERENCES DOCENTE(Email) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE MESSAGGIO (
	Id INT auto_increment,
    TitoloTest VARCHAR(100) NOT NULL,
    TitoloMessaggio VARCHAR(100),
    CampoTesto VARCHAR(500),
    Data DATETIME,
    
    PRIMARY KEY(Id, TitoloTest),
    
    FOREIGN KEY(TitoloTest) REFERENCES TEST(Titolo) ON DELETE CASCADE
    
) ENGINE = INNODB;

CREATE TABLE RICEZIONESTUDENTE (
	Id INT NOT NULL,
	TitoloTest VARCHAR(100) NOT NULL,
    EmailStudenteDestinatario VARCHAR(100) NOT NULL,
    
    PRIMARY KEY(Id, TitoloTest, EmailStudenteDestinatario),
    
    FOREIGN KEY(Id) REFERENCES MESSAGGIO(Id) ON DELETE CASCADE,
	FOREIGN KEY(TitoloTest) REFERENCES TEST(Titolo) ON DELETE CASCADE,
    FOREIGN KEY(EmailStudenteDestinatario) REFERENCES STUDENTE(Email) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE INVIOSTUDENTE (
	Id INT NOT NULL,
	TitoloTest VARCHAR(100) NOT NULL,
    EmailStudenteMittente VARCHAR(100) NOT NULL,

	PRIMARY KEY(Id, TitoloTest, EmailStudenteMittente),
    
    FOREIGN KEY(Id) REFERENCES MESSAGGIO(Id) ON DELETE CASCADE,
	FOREIGN KEY(TitoloTest) REFERENCES TEST(Titolo) ON DELETE CASCADE,
    FOREIGN KEY(EmailStudenteMittente) REFERENCES STUDENTE(Email) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE RICEZIONEDOCENTE (
	Id INT NOT NULL,
	TitoloTest VARCHAR(100) NOT NULL,
    EmailDocenteDestinatario VARCHAR(100) NOT NULL,
    
    PRIMARY KEY(Id, TitoloTest, EmailDocenteDestinatario),
    
    FOREIGN KEY(Id) REFERENCES MESSAGGIO(Id) ON DELETE CASCADE,
	FOREIGN KEY(TitoloTest) REFERENCES TEST(Titolo) ON DELETE CASCADE,
    FOREIGN KEY(EmailDocenteDestinatario) REFERENCES DOCENTE(Email) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE INVIODOCENTE (
	Id INT NOT NULL,
	TitoloTest VARCHAR(100) NOT NULL,
    EmailDocenteMittente VARCHAR(100) NOT NULL,

	PRIMARY KEY(Id, TitoloTest, EmailDocenteMittente),
    
    FOREIGN KEY(Id) REFERENCES MESSAGGIO(Id) ON DELETE CASCADE,
	FOREIGN KEY(TitoloTest) REFERENCES TEST(Titolo) ON DELETE CASCADE,
    FOREIGN KEY(EmailDocenteMittente) REFERENCES DOCENTE(Email) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE COMPLETAMENTO (
	NumeroProgressivo INT auto_increment,
	Stato ENUM("Aperto","InCompletamento","Concluso") NOT NULL, 
	TitoloTest VARCHAR(100)NOT NULL,
    EmailStudente VARCHAR(100)NOT NULL,
    DataPrimaRisposta DATETIME,
    DataUltimaRisposta DATETIME,
    
    PRIMARY KEY(NumeroProgressivo),
    
	FOREIGN KEY(TitoloTest) REFERENCES TEST(Titolo) ON DELETE CASCADE,
    FOREIGN KEY(EmailStudente) REFERENCES STUDENTE(Email) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE TABELLADIESERCIZIO (
	Nome VARCHAR(20) PRIMARY KEY,
    DataCreazione DATETIME,
    num_righe INT,
    EmailDocente VARCHAR(100),
    FOREIGN KEY(EmailDocente) REFERENCES DOCENTE(Email) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE RIGA(
	Testo VARCHAR(255),
    NomeTabella VARCHAR(20),
    
    PRIMARY KEY(Testo,NomeTabella),
    FOREIGN KEY(NomeTabella) REFERENCES TABELLADIESERCIZIO(Nome) ON DELETE CASCADE
);

CREATE TABLE ATTRIBUTO (
	NomeTabella VARCHAR(20) NOT NULL,
    NomeAttributo VARCHAR(100) NOT NULL,
    Tipo VARCHAR(30) NOT NULL,
    chiavePrimaria BOOLEAN,
    
    PRIMARY KEY(NomeAttributo, NomeTabella),
    
    FOREIGN KEY(NomeTabella) REFERENCES TABELLADIESERCIZIO(Nome) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE VINCOLODIINTEGRITA  (
    NomeTabellaUno VARCHAR(20) NOT NULL,
    NomeAttributoUno VARCHAR(100) NOT NULL,
    NomeTabellaDue VARCHAR(20) NOT NULL,
    NomeAttributoDue VARCHAR(100) NOT NULL,
    
    PRIMARY KEY(NomeTabellaUno, NomeAttributoUno, NomeTabellaDue, NomeAttributoDue),
    
    FOREIGN KEY(NomeTabellaUno) REFERENCES TABELLADIESERCIZIO(Nome) ON DELETE CASCADE,
    FOREIGN KEY(NomeAttributoUno) REFERENCES ATTRIBUTO(NomeAttributo) ON DELETE CASCADE,
    FOREIGN KEY(NomeTabellaDue) REFERENCES TABELLADIESERCIZIO(Nome) ON DELETE CASCADE,
    FOREIGN KEY(NomeAttributoDue) REFERENCES ATTRIBUTO(NomeAttributo) ON DELETE CASCADE

)  ENGINE=INNODB;

CREATE TABLE QUESITO (
	NumeroProgressivo INT auto_increment,
    TitoloTest VARCHAR(100) NOT NULL,
    LivelloDifficolta ENUM("Basso","Medio","Alto"),
    Descrizione VARCHAR(255),
    NumeroRisposte INT,
    
    PRIMARY KEY(NumeroProgressivo,TitoloTest),
    
    FOREIGN KEY(TitoloTest) REFERENCES TEST(Titolo) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE QUESITORISPOSTACHIUSA (
	NumeroProgressivo INT NOT NULL,
    TitoloTest VARCHAR(100) NOT NULL,
    
	PRIMARY KEY(NumeroProgressivo, TitoloTest),
    
    FOREIGN KEY(TitoloTest, NumeroProgressivo) REFERENCES QUESITO(TitoloTest, NumeroProgressivo) ON DELETE CASCADE
) ENGINE = INNODB;

CREATE TABLE QUESITOCODICE (
	NumeroProgressivo INT NOT NULL,
    TitoloTest VARCHAR(100) NOT NULL,
    
    PRIMARY KEY(TitoloTest, NumeroProgressivo),
    
   FOREIGN KEY(TitoloTest,NumeroProgressivo) REFERENCES QUESITO(TitoloTest,NumeroProgressivo) ON DELETE CASCADE
) ENGINE = INNODB;

CREATE TABLE SOLUZIONE (
	NumeroProgressivo INT NOT NULL,
    TitoloTest VARCHAR(100) NOT NULL,
    TestoSoluzione VARCHAR(500),
    
	PRIMARY KEY(TitoloTest, NumeroProgressivo, TestoSoluzione),
    
   FOREIGN KEY(TitoloTest, NumeroProgressivo) REFERENCES QUESITOCODICE(TitoloTest,NumeroProgressivo) ON DELETE CASCADE
) ENGINE = INNODB;

CREATE TABLE RISPOSTAQUESITORISPOSTACHIUSA  (
    NumeroProgressivoCompletamento INT NOT NULL,
    TitoloTest VARCHAR(100) NOT NULL,
    OpzioneScelta VARCHAR(200),
    NumeroProgressivoQuesito INT,
    Esito BOOLEAN,
    
    PRIMARY KEY (NumeroProgressivoCompletamento, TitoloTest, NumeroProgressivoQuesito),
    
    FOREIGN KEY(NumeroProgressivoCompletamento) REFERENCES COMPLETAMENTO(NumeroProgressivo) ON DELETE CASCADE,
    FOREIGN KEY(TitoloTest, NumeroProgressivoQuesito) REFERENCES QUESITORISPOSTACHIUSA(TitoloTest, NumeroProgressivo) ON DELETE CASCADE
    
)  ENGINE=INNODB;

CREATE TABLE RISPOSTAQUESITOCODICE  (
    NumeroProgressivoCompletamento INT NOT NULL,
    TitoloTest VARCHAR(100) NOT NULL,
    Testo VARCHAR(500),
    NumeroProgressivoQuesito INT,
    Esito BOOLEAN,
    
    PRIMARY KEY (NumeroProgressivoCompletamento, TitoloTest, NumeroProgressivoQuesito),
    
    FOREIGN KEY(NumeroProgressivoCompletamento) REFERENCES COMPLETAMENTO(NumeroProgressivo) ON DELETE CASCADE,
    FOREIGN KEY(TitoloTest, NumeroProgressivoQuesito) REFERENCES QUESITOCODICE(TitoloTest,NumeroProgressivo) ON DELETE CASCADE

)  ENGINE=INNODB;

CREATE TABLE OPZIONERISPOSTA (
	NumeroProgressivoOpzione INT auto_increment,
    TitoloTest VARCHAR(100) NOT NULL,
    NumeroProgressivoQuesito INT NOT NULL,
    CampoTesto VARCHAR(200),
    RispostaCorretta BOOLEAN,
    
    PRIMARY KEY (NumeroProgressivoOpzione, NumeroProgressivoQuesito, TitoloTest),
    
    FOREIGN KEY(TitoloTest,NumeroProgressivoQuesito) REFERENCES QUESITORISPOSTACHIUSA(TitoloTest,NumeroProgressivo) ON DELETE CASCADE    
)  ENGINE=INNODB;

CREATE TABLE COSTITUZIONE (
    TitoloTest VARCHAR(100) NOT NULL,
    NumeroProgressivoQuesito INT NOT NULL,
    NomeTabella VARCHAR(20) NOT NULL,
    
    PRIMARY KEY(TitoloTest, NumeroProgressivoQuesito, NomeTabella),
    
    FOREIGN KEY(TitoloTest,NumeroProgressivoQuesito) REFERENCES QUESITO(TitoloTest,NumeroProgressivo) ON DELETE CASCADE,
    FOREIGN KEY(NomeTabella) REFERENCES TABELLADIESERCIZIO(Nome) ON DELETE CASCADE
    
)  ENGINE=INNODB;