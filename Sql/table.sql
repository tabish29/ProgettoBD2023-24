-- elimino se esiste, poi ricreo il database 
DROP DATABASE IF EXISTS ESQL;
CREATE DATABASE IF NOT EXISTS ESQL;
USE ESQL;
-- Creo le tabelle
CREATE TABLE DOCENTE (
	Email VARCHAR(40) PRIMARY KEY,
    PasswordDocente VARCHAR(20) NOT NULL,
    Nome VARCHAR (50) NOT NULL,
    Cognome VARCHAR (50) NOT NULL,
    RecapitoTelefonicoDocente INT,
    NomeDipartimento VARCHAR(100),
    NomeCorso VARCHAR(100)
    
) ENGINE = INNODB;

CREATE TABLE STUDENTE (
	Email VARCHAR(40) PRIMARY KEY,
    PasswordStudente VARCHAR(20) NOT NULL,
    Nome VARCHAR (20) NOT NULL,
    Cognome VARCHAR (20) NOT NULL,
    RecapitoTelefonicoStudente INT,
    AnnoImmatricolazione INT,
    CodiceAlfaNumerico CHAR(16)
    
) ENGINE = INNODB;

CREATE TABLE TEST (
	Titolo VARCHAR(20) PRIMARY KEY,
    DataCreazione DATETIME,
    Foto VARCHAR(100),											
    VisualizzaRisposte BOOLEAN,
    EmailDocente VARCHAR(40) NOT NULL,
    
    FOREIGN KEY(EmailDocente) REFERENCES DOCENTE(Email) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE MESSAGGIO (
	Id INT auto_increment,
    TitoloTest VARCHAR(20) NOT NULL,
    TitoloMessaggio VARCHAR(20),
    CampoTesto VARCHAR(500),
    Data DATETIME,
    
    PRIMARY KEY(Id, TitoloTest),
    
    FOREIGN KEY(TitoloTest) REFERENCES TEST(Titolo) ON DELETE CASCADE 				-- BOH IO NON SO HO MESSO CASCADE
    
) ENGINE = INNODB;

CREATE TABLE RICEZIONESTUDENTE (
	Id INT NOT NULL,
	TitoloTest VARCHAR(20) NOT NULL,
    EmailStudenteDestinatario VARCHAR(40) NOT NULL,
    
    PRIMARY KEY(Id, TitoloTest, EmailStudenteDestinatario),
    
    FOREIGN KEY(Id) REFERENCES MESSAGGIO(Id) ON DELETE CASCADE,
	FOREIGN KEY(TitoloTest) REFERENCES TEST(Titolo) ON DELETE CASCADE,
    FOREIGN KEY(EmailStudenteDestinatario) REFERENCES STUDENTE(Email) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE INVIOSTUDENTE (
	Id INT NOT NULL,
	TitoloTest VARCHAR(20) NOT NULL,
    EmailStudenteMittente VARCHAR(40) NOT NULL,

	PRIMARY KEY(Id, TitoloTest, EmailStudenteMittente),
    
    FOREIGN KEY(Id) REFERENCES MESSAGGIO(Id) ON DELETE CASCADE,
	FOREIGN KEY(TitoloTest) REFERENCES TEST(Titolo) ON DELETE CASCADE,
    FOREIGN KEY(EmailStudenteMittente) REFERENCES STUDENTE(Email) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE RICEZIONEDOCENTE (
	Id INT NOT NULL,
	TitoloTest VARCHAR(20) NOT NULL,
    EmailDocenteDestinatario VARCHAR(40) NOT NULL,
    
    PRIMARY KEY(Id, TitoloTest, EmailDocenteDestinatario),
    
    FOREIGN KEY(Id) REFERENCES MESSAGGIO(Id) ON DELETE CASCADE,
	FOREIGN KEY(TitoloTest) REFERENCES TEST(Titolo) ON DELETE CASCADE,
    FOREIGN KEY(EmailDocenteDestinatario) REFERENCES DOCENTE(Email) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE INVIODOCENTE (
	Id INT NOT NULL,
	TitoloTest VARCHAR(20) NOT NULL,
    EmailDocenteMittente VARCHAR(40) NOT NULL,

	PRIMARY KEY(Id, TitoloTest, EmailDocenteMittente),
    
    FOREIGN KEY(Id) REFERENCES MESSAGGIO(Id) ON DELETE CASCADE,
	FOREIGN KEY(TitoloTest) REFERENCES TEST(Titolo) ON DELETE CASCADE,
    FOREIGN KEY(EmailDocenteMittente) REFERENCES DOCENTE(Email) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE COMPLETAMENTO (
	NumeroProgressivo INT auto_increment,
	Stato ENUM("Aperto","InCompletamento","Concluso") NOT NULL,  -- non credo sia corretto mettere not null,non credo che sia necessario avere questo attributo come chiave primaria(dopo crea casino con le tabelle risposta)
	TitoloTest VARCHAR(20) NOT NULL,
    EmailStudente VARCHAR(40) NOT NULL,
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
    EmailDocente VARCHAR(40),
    
    FOREIGN KEY(EmailDocente) REFERENCES DOCENTE(Email) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE RIGA(
	Testo VARCHAR(100),
    NomeTabella VARCHAR(20),
    
    PRIMARY KEY(Testo,NomeTabella),
    FOREIGN KEY(NomeTabella) REFERENCES TABELLADIESERCIZIO(Nome) ON DELETE CASCADE
);

CREATE TABLE ATTRIBUTO (
	NomeTabella VARCHAR(20) NOT NULL,
    NomeAttributo VARCHAR(20) NOT NULL,
    Tipo VARCHAR(20) NOT NULL,
    
    PRIMARY KEY(NomeAttributo, NomeTabella), -- Nome attributo va messo nella prima posizione se no dà problema di indicizzazione
    
    FOREIGN KEY(NomeTabella) REFERENCES TABELLADIESERCIZIO(Nome) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE VINCOLODIINTEGRITA (
	NomeTabella VARCHAR(20) NOT NULL,
    NomeAttributo VARCHAR(20) NOT NULL,
    EmailDocente VARCHAR(40),
    -- Referenziata e referente
    PRIMARY KEY(NomeTabella, NomeAttributo),
    
    FOREIGN KEY(NomeTabella) REFERENCES ATTRIBUTO(NomeTabella) ON DELETE CASCADE,
    FOREIGN KEY(NomeAttributo) REFERENCES ATTRIBUTO(NomeAttributo) ON DELETE CASCADE,
    FOREIGN KEY(EmailDocente) REFERENCES DOCENTE(Email) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE QUESITO (
	NumeroProgressivo INT auto_increment,
    TitoloTest VARCHAR(20) NOT NULL,
    LivelloDifficolta ENUM("Basso","Medio","Alto"),
    Descrizione VARCHAR(50),
    NumeroRisposte INT,
    
    PRIMARY KEY(NumeroProgressivo,TitoloTest),
    
    FOREIGN KEY(TitoloTest) REFERENCES TEST(Titolo) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE QUESITORISPOSTACHIUSA (
	NumeroProgressivo INT NOT NULL,
    TitoloTest VARCHAR(20) NOT NULL,
    
	PRIMARY KEY(NumeroProgressivo, TitoloTest),
    
    FOREIGN KEY(TitoloTest) REFERENCES QUESITO(TitoloTest) ON DELETE CASCADE,
	FOREIGN KEY(NumeroProgressivo) REFERENCES QUESITO(NumeroProgressivo) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE QUESITOCODICE (
	NumeroProgressivo INT NOT NULL,
    TitoloTest VARCHAR(20) NOT NULL,
    
    PRIMARY KEY(TitoloTest, NumeroProgressivo),
    
   FOREIGN KEY(TitoloTest) REFERENCES QUESITO(TitoloTest) ON DELETE CASCADE,
   FOREIGN KEY(NumeroProgressivo) REFERENCES QUESITO(NumeroProgressivo) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE SOLUZIONE (
	NumeroProgressivo INT NOT NULL,
    TitoloTest VARCHAR(20) NOT NULL,
    TestoSoluzione VARCHAR(500),
    
	PRIMARY KEY(TitoloTest, NumeroProgressivo, TestoSoluzione),
    
   FOREIGN KEY(TitoloTest) REFERENCES QUESITOCODICE(TitoloTest) ON DELETE CASCADE,
   FOREIGN KEY(NumeroProgressivo) REFERENCES QUESITOCODICE(NumeroProgressivo) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE RISPOSTAQUESITORISPOSTACHIUSA  (
    NumeroProgressivoCompletamento INT NOT NULL,
    TitoloTest VARCHAR(20) NOT NULL,
    OpzioneScelta VARCHAR(2000),
    NumeroProgressivoQuesito INT,
    Esito BOOLEAN,
    
    PRIMARY KEY (NumeroProgressivoCompletamento),
    
    FOREIGN KEY(NumeroProgressivoCompletamento) REFERENCES COMPLETAMENTO(NumeroProgressivo) ON DELETE CASCADE,
	FOREIGN KEY(NumeroProgressivoQuesito) REFERENCES QUESITORISPOSTACHIUSA(NumeroProgressivo) ON DELETE CASCADE,
    FOREIGN KEY(TitoloTest) REFERENCES QUESITORISPOSTACHIUSA(TitoloTest) ON DELETE CASCADE
)  ENGINE=INNODB;

CREATE TABLE RISPOSTAQUESITOCODICE  (
    NumeroProgressivoCompletamento INT NOT NULL,
    TitoloTest VARCHAR(20) NOT NULL,
    Testo VARCHAR(2000),
    NumeroProgressivoQuesito INT,
    Esito BOOLEAN,
    
    PRIMARY KEY (NumeroProgressivoCompletamento),
    
    FOREIGN KEY(NumeroProgressivoCompletamento) REFERENCES COMPLETAMENTO(NumeroProgressivo) ON DELETE CASCADE,
    FOREIGN KEY(NumeroProgressivoQuesito) REFERENCES QUESITOCODICE(NumeroProgressivo) ON DELETE CASCADE,
    FOREIGN KEY(TitoloTest) REFERENCES QUESITOCODICE(TitoloTest) ON DELETE CASCADE
)  ENGINE=INNODB;

CREATE TABLE OPZIONERISPOSTA (
	NumeroProgressivoOpzione INT auto_increment,
    TitoloTest VARCHAR(20) NOT NULL,
    NumeroProgressivoQuesito INT NOT NULL,
    CampoTesto VARCHAR(2000),
    RispostaCorretta BOOLEAN,
    
    PRIMARY KEY (NumeroProgressivoOpzione, NumeroProgressivoQuesito, TitoloTest),
    
    FOREIGN KEY(TitoloTest) REFERENCES QUESITORISPOSTACHIUSA(TitoloTest) ON DELETE CASCADE,
    FOREIGN KEY(NumeroProgressivoQuesito) REFERENCES QUESITORISPOSTACHIUSA(NumeroProgressivo) ON DELETE CASCADE
    
)  ENGINE=INNODB;

CREATE TABLE COSTITUZIONE (
    TitoloTest VARCHAR(20) NOT NULL,
    NumeroProgressivoQuesito INT NOT NULL,
    NomeTabella VARCHAR(20) NOT NULL,
    
    PRIMARY KEY(TitoloTest, NumeroProgressivoQuesito, NomeTabella),
    
    FOREIGN KEY(TitoloTest) REFERENCES QUESITO(TitoloTest) ON DELETE CASCADE,
    FOREIGN KEY(NumeroProgressivoQuesito) REFERENCES QUESITO(NumeroProgressivo) ON DELETE CASCADE,
    FOREIGN KEY(NomeTabella) REFERENCES TABELLADIESERCIZIO(Nome) ON DELETE CASCADE
    
)  ENGINE=INNODB;

CREATE TABLE APPARTENENZA  (
    NomeTabellaUno VARCHAR(20) NOT NULL,
    NomeAttributoUno VARCHAR(20) NOT NULL,
    NomeTabellaDue VARCHAR(20) NOT NULL,
    NomeAttributoDue VARCHAR(20) NOT NULL,
    EmailDocente VARCHAR(40),
    
    PRIMARY KEY(NomeTabellaUno, NomeAttributoUno, NomeTabellaDue, NomeAttributoDue),
    
    FOREIGN KEY(NomeTabellaUno) REFERENCES TABELLADIESERCIZIO(Nome) ON DELETE CASCADE,
    FOREIGN KEY(NomeAttributoUno) REFERENCES ATTRIBUTO(NomeAttributo) ON DELETE CASCADE,
    FOREIGN KEY(NomeTabellaDue) REFERENCES TABELLADIESERCIZIO(Nome) ON DELETE CASCADE,
    FOREIGN KEY(NomeAttributoDue) REFERENCES ATTRIBUTO(NomeAttributo) ON DELETE CASCADE,
	FOREIGN KEY(EmailDocente) REFERENCES DOCENTE(Email) ON DELETE CASCADE
    
)  ENGINE=INNODB;

CREATE TABLE REALIZZAZIONE (
	EmailStudente VARCHAR(40) NOT NULL,
    NumeroProgressivoCompletamento INT NOT NULL,
    
    PRIMARY KEY(EmailStudente, NumeroProgressivoCompletamento),
    FOREIGN KEY(EmailStudente) REFERENCES STUDENTE(Email) ON DELETE CASCADE,
    FOREIGN KEY(NumeroProgressivoCompletamento) REFERENCES COMPLETAMENTO(NumeroProgressivo) ON DELETE CASCADE

) ENGINE = INNODB;