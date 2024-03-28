-- elimino se esiste, poi ricreo il database 
DROP DATABASE IF EXISTS ESQL;
CREATE DATABASE IF NOT EXISTS ESQL;
USE ESQL;
-- Creo le tabelle
CREATE TABLE DOCENTE (
	Email VARCHAR(40) PRIMARY KEY,
    Nome VARCHAR (50) NOT NULL,
    Cognome VARCHAR (50) NOT NULL,
    RecapitoTelefonicoDocente INT,
    NomeDipartimento VARCHAR(100),
    NomeCorso VARCHAR(100)
    
) ENGINE = INNODB;

CREATE TABLE STUDENTE (
	Email VARCHAR(40) PRIMARY KEY,
    Nome VARCHAR (20) NOT NULL,
    Cognome VARCHAR (20) NOT NULL,
    RecapitoTelefonicoStudente INT,
    AnnoImmatricolazione INT,
    CodiceAlfaNumerico CHAR(16)
    
) ENGINE = INNODB;

CREATE TABLE TEST (
	Titolo VARCHAR(20) PRIMARY KEY,
    DataCreazione DATETIME,
    Foto BLOB,											-- METTIAMO BLOB
    VisualizzaRisposte BOOLEAN,
    EmailDocente VARCHAR(40),
    
    FOREIGN KEY(EmailDocente) REFERENCES DOCENTE(Email) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE MESSAGGIO (
	Id INT auto_increment,
    TitoloTest VARCHAR(20) NOT NULL,
    TitoloMessaggio VARCHAR(20),
    CampoTesto VARCHAR(60),
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
    TestoSoluzione VARCHAR(40),
    
	PRIMARY KEY(TitoloTest, NumeroProgressivo, TestoSoluzione),
    
   FOREIGN KEY(TitoloTest) REFERENCES QUESITOCODICE(TitoloTest) ON DELETE CASCADE,
   FOREIGN KEY(NumeroProgressivo) REFERENCES QUESITOCODICE(NumeroProgressivo) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE RISPOSTAQUESITORISPOSTACHIUSA  (
    NumeroProgressivoCompletamento INT NOT NULL,
    TitoloTest VARCHAR(20) NOT NULL,
    OpzioneScelta VARCHAR(20),
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
    Testo VARCHAR(100),
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







-- PROCEDURE PER TUTTI GLI UTENTI
DELIMITER //
CREATE PROCEDURE VisualizzaDocenti ()
BEGIN
    SELECT * FROM DOCENTE;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE VisualizzaTestDisponibili ()
BEGIN
    SELECT * FROM TEST;
END //
DELIMITER ;


DELIMITER //
CREATE PROCEDURE VisualizzaQuesitiPerTest (
    IN TitoloTestTemp VARCHAR(20)
    )
BEGIN
    -- Seleziona i quesiti corrispondenti al titolo del test specificato
    SELECT * FROM QUESITO WHERE TitoloTest = TitoloTestTemp;
END //
DELIMITER ;


DELIMITER //
CREATE PROCEDURE AutenticazioneDocente (
    IN EmailTemp VARCHAR(40),
    OUT AutenticatoTemp BOOLEAN
)
BEGIN
    -- Verifica se l'email esiste nella tabella Utenti e corrisponde alla password fornita
    IF EXISTS (SELECT * FROM DOCENTE WHERE Email = EmailTemp) THEN
        SET AutenticatoTemp = TRUE;
    ELSE
        SET AutenticatoTemp = FALSE;
    END IF;
END //
DELIMITER ;


DELIMITER //
CREATE PROCEDURE AutenticazioneStudente (
    IN EmailTemp VARCHAR(40),
    OUT AutenticatoTemp BOOLEAN
)
BEGIN
    -- Verifica se l'email esiste nella tabella Utenti e corrisponde alla password fornita
    IF EXISTS (SELECT * FROM STUDENTE WHERE Email = EmailTemp) THEN
        SET AutenticatoTemp = TRUE;
    ELSE
        SET AutenticatoTemp = FALSE;
    END IF;
END //
DELIMITER ;


DELIMITER //
CREATE PROCEDURE RegistrazioneDocente (
    IN EmailTemp VARCHAR(40),
    IN Nome VARCHAR (50),
    IN Cognome VARCHAR (50),
    IN RecapitoTelefonicoDocente INT,
    IN NomeDipartimento VARCHAR(100),
    IN NomeCorso VARCHAR(100)
)
BEGIN
    -- Verifica se l'email non esiste già nella tabella Docente
    IF NOT EXISTS (SELECT * FROM Docente WHERE Email = EmailTemp) THEN
        -- Inserisce l'utente nella tabella Utenti
        INSERT INTO Docente (Email,Nome,Cognome,RecapitoTelefonicoDocente,NomeDipartimento,NomeCorso) VALUES (EmailTemp,Nome,Cognome,RecapitoTelefonicoDocente,NomeDipartimento,NomeCorso);
    ELSE
     -- Se l'email esiste già, restituisci un messaggio di errore
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "L\'email inserita è già presente nella tabella Docente";
    END IF;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE RegistrazioneStudente (
    IN EmailTemp VARCHAR(40),
    IN Nome VARCHAR (20),
    IN Cognome VARCHAR (20),
    IN RecapitoTelefonicoStudente INT,
    IN AnnoImmatricolazione INT,
    IN CodiceAlfaNumerico CHAR(16)
)
BEGIN
    -- Verifica se l'email non esiste già nella tabella Utenti
    IF NOT EXISTS (SELECT * FROM Studente WHERE Email = EmailTemp) THEN
        -- Inserisce l'utente nella tabella Utenti
        INSERT INTO STUDENTE (Email,Nome,Cognome,RecapitoTelefonicoStudente,AnnoImmatricolazione,CodiceAlfaNumerico) VALUES (EmailTemp,Nome,Cognome,RecapitoTelefonicoStudente,AnnoImmatricolazione,CodiceAlfaNumerico);
    ELSE
     -- Se l'email esiste già, restituisci un messaggio di errore
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "L\'email inserita è già presente nella tabella Studente";
    END IF;
END //
DELIMITER ;



-- PROCEDURE PER I DOCENTI

-- OK
DELIMITER //
CREATE PROCEDURE CreazioneTabellaEsercizio (
    IN nomeTabella VARCHAR(20),
    IN dataCreazione DATETIME,
    IN numRighe INT,
    IN emailDocente VARCHAR(40)
)
BEGIN
-- controllo che la tabella non esista già e che esista il docente
DECLARE tabellaNonEsistente INT DEFAULT 0;
DECLARE docenteEsistente INT DEFAULT 0;
SET tabellaNonEsistente = ( SELECT COUNT(*) FROM TABELLADIESERCIZIO WHERE (nomeTabella=TABELLADIESERCIZIO.Nome) );
SET docenteEsistente = ( SELECT COUNT(*) FROM DOCENTE WHERE (emailDocente = DOCENTE.Email) );

-- se non esiste la tabella ed esiste il docente la inserisco
IF (TabellaNonEsistente = 0 AND docenteEsistente=1) THEN 
INSERT INTO TABELLADIESERCIZIO VALUES(NomeTabella, dataCreazione, numRighe, emailDocente);
END IF;

END
// DELIMITER ;



-- OK
DELIMITER //
CREATE PROCEDURE ModificaVisualizzazioneRisposte (
    IN TitoloTestTemp VARCHAR(50),
    IN ValoreTemp BOOLEAN
)
BEGIN
    -- Imposta il campo VisualizzaRisposte al valore specificato per il test specificato
    UPDATE Test SET VisualizzaRisposte = ValoreTemp WHERE Titolo = TitoloTestTemp;
END 
// DELIMITER ;



-- OK
DELIMITER //
CREATE PROCEDURE CreazioneTest (
    IN TitoloTest VARCHAR(50),
    IN DataCreazione datetime,
    IN Foto BLOB,
    IN VisualizzaRisposte BOOLEAN,
    IN EmailDocente VARCHAR(40)
)
BEGIN
    DECLARE docenteEsistente INT DEFAULT 0;
    DECLARE TestNonEsistente INT DEFAULT 0;
    SET docenteEsistente = ( SELECT COUNT(*) FROM DOCENTE WHERE (EmailDocente=DOCENTE.Email));
	SET TestNonEsistente = ( SELECT COUNT(*) FROM Test WHERE (TitoloTest=TEST.Titolo));
-- se il docente esiste, e il test non esiste, inserisce i dati
IF (docenteEsistente = 1 AND TestNonEsistente = 0) THEN
	INSERT INTO TEST VALUES (TitoloTest, DataCreazione, Foto, VisualizzaRisposte, EmailDocente);
END IF;
END 
// DELIMITER ;


-- OK
DELIMITER //
CREATE PROCEDURE CreazioneQuesitoRispostaChiusa (
    IN TitoloTestTemp VARCHAR(20),
    IN LivelloDifficoltaTemp ENUM("Basso","Medio","Alto"),
    IN DescrizioneTemp VARCHAR(50),
    IN NumeroRisposteTemp INT,
    OUT numProgressivo INT
)
BEGIN
    DECLARE TestEsistente INT DEFAULT 0;
    DECLARE UltimoNumeroProgressivo INT;
    SET TestEsistente = (SELECT COUNT(*) FROM TEST WHERE Titolo = TitoloTestTemp);
    IF (TestEsistente = 1) THEN
        INSERT INTO QUESITO(TitoloTest, LivelloDifficolta, Descrizione, NumeroRisposte) 
        VALUES (TitoloTestTemp, LivelloDifficoltaTemp, DescrizioneTemp, NumeroRisposteTemp);

        SET UltimoNumeroProgressivo = (SELECT MAX(NumeroProgressivo) FROM QUESITO WHERE TitoloTest = TitoloTestTemp);
        INSERT INTO QUESITORISPOSTACHIUSA(NumeroProgressivo, TitoloTest) VALUES (UltimoNumeroProgressivo, TitoloTestTemp);
        
        SET numProgressivo = UltimoNumeroProgressivo;
    END IF;
    
END //
DELIMITER ;



-- OK
DELIMITER //
CREATE PROCEDURE CreazioneQuesitoCodice (
    IN TitoloTestTemp VARCHAR(20),
    IN LivelloDifficoltaTemp ENUM("Basso","Medio","Alto"),
    IN DescrizioneTemp VARCHAR(50),
    IN NumeroRisposteTemp INT,
    OUT numProgressivo INT
)
BEGIN
	DECLARE UltimoNumeroProgressivo INT;
	DECLARE TestEsistente INT DEFAULT 0;
	SET TestEsistente = (SELECT COUNT(*) FROM TEST WHERE (TitoloTestTemp = Titolo));

	IF (TestEsistente = 1) THEN
		INSERT INTO QUESITO(TitoloTest, LivelloDifficolta, Descrizione, NumeroRisposte) 
		VALUES (TitoloTestTemp, LivelloDifficoltaTemp, DescrizioneTemp, NumeroRisposteTemp);
        
        SET UltimoNumeroProgressivo = (SELECT MAX(NumeroProgressivo) FROM QUESITO WHERE TitoloTest = TitoloTestTemp);
		INSERT INTO QUESITOCODICE(TitoloTest,NumeroProgressivo) VALUES (TitoloTestTemp, UltimoNumeroProgressivo);
        
        SET numProgressivo = UltimoNumeroProgressivo;
	END IF;
    
END 
// DELIMITER ;


# Faccio mettere in input il progressivo al docente, dovrà essere visibile nel programma
-- OK
DELIMITER //
CREATE PROCEDURE InserimentoSoluzione (
    IN TitoloTestTemp VARCHAR(20),
    IN NumeroProgressivoTemp INT,
    IN TestoSoluzioneTemp VARCHAR(40)
)
BEGIN
    DECLARE ProgressivoETestEsistenti INT DEFAULT 0;
    SET ProgressivoETestEsistenti = (SELECT COUNT(*) FROM QUESITOCODICE 
    WHERE (NumeroProgressivo=NumeroProgressivoTemp AND TitoloTestTemp=TitoloTest));

	IF (ProgressivoETestEsistenti=1) THEN
	INSERT INTO SOLUZIONE(NumeroProgressivo, TitoloTest, TestoSoluzione) 
    VALUES (NumeroProgressivoTemp, TitoloTestTemp, TestoSoluzioneTemp);
	END IF;
END 
// DELIMITER ;



# qui ho reso auto_increment il progressivo e come prima faccio inserire il progressivo
-- OK
DELIMITER //
CREATE PROCEDURE InserimentoOpzioneRisposta (
    IN TitoloTestTemp VARCHAR(20),
    IN NumeroProgressivoQuesitoTemp INT,
    IN CampoTestoTemp VARCHAR(2000)
)
BEGIN
    DECLARE ProgressivoQuesitoETestEsistente INT DEFAULT 0;

	SET ProgressivoQuesitoETestEsistente = (SELECT COUNT(*) FROM QUESITORISPOSTACHIUSA 
    WHERE (TitoloTestTemp=TitoloTest AND NumeroProgressivo = NumeroProgressivoQuesitoTemp));

    IF (ProgressivoQuesitoETestEsistente = 1) THEN
        INSERT INTO OPZIONERISPOSTA(TitoloTest, NumeroProgressivoQuesito, CampoTesto) 
        VALUES (TitoloTestTemp, NumeroProgressivoQuesitoTemp, CampoTestoTemp);
    END IF;
END //
DELIMITER ;



-- OK
DELIMITER //
CREATE PROCEDURE InserimentoMessaggioDocente(
    IN TitoloTest_t VARCHAR(20),
    IN TitoloMessaggio_t VARCHAR(20),
    IN CampoTesto_t VARCHAR(60),
    IN Data_t DATETIME,
    IN EmailDocenteMittente_t VARCHAR(40)
)
BEGIN
    DECLARE TestEsistente INT DEFAULT 0;
    DECLARE DocenteEsistente INT DEFAULT 0;
    DECLARE IdMessaggio INT;
    DECLARE done INT DEFAULT FALSE;
    DECLARE student_email VARCHAR(40);
    DECLARE student_cursor CURSOR FOR SELECT Email FROM STUDENTE;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    -- mi assicuro che esistano il test e il docente
    SET TestEsistente = (SELECT COUNT(*) FROM TEST WHERE Titolo = TitoloTest_t);
    SET DocenteEsistente = (SELECT COUNT(*) FROM DOCENTE WHERE Email = EmailDocenteMittente_t);

    IF (TestEsistente = 1 AND DocenteEsistente = 1) THEN
        -- Inserisce il messaggio nella tabella MESSAGGIO
        INSERT INTO MESSAGGIO (TitoloTest, TitoloMessaggio, CampoTesto, Data) VALUES (TitoloTest_t, TitoloMessaggio_t, CampoTesto_t, Data_t);

        -- Ottiene l'ID del messaggio appena inserito
        SET IdMessaggio = LAST_INSERT_ID();

        -- Inserisce il messaggio nella tabella INVIODOCENTE per ogni docente
        INSERT INTO INVIODOCENTE (Id, TitoloTest, EmailDocenteMittente) VALUES (IdMessaggio, TitoloTest_t, EmailDocenteMittente_t);        

        OPEN student_cursor;

        -- Ciclo per inserire il messaggio nella tabella RICEZIONESTUDENTE per ogni studente
        message_loop: LOOP
            FETCH student_cursor INTO student_email;
            IF done THEN
                LEAVE message_loop;
            END IF;
            -- Inserisce il messaggio nella tabella RICEZIONESTUDENTE per lo studente corrente
            INSERT INTO RICEZIONESTUDENTE (Id, TitoloTest, EmailStudenteDestinatario) VALUES (IdMessaggio, TitoloTest_t, student_email);
        END LOOP;

        CLOSE student_cursor;

    END IF;
END //
DELIMITER ;




-- PROCEDURE PER GLI STUDENTI

DELIMITER //
CREATE PROCEDURE inserisciRisposta(
    IN idCompletamentoTemp INT,
    IN TitoloTestTemp VARCHAR(20),
    IN valoreRispostaTemp VARCHAR(2000),
    IN numeroQuesitoTemp INT
)
BEGIN

    DECLARE tipoRispostaChiusa BOOLEAN;
    DECLARE tipoRispostaAperta BOOLEAN;
    DECLARE numRispostaChiusa INT;
    DECLARE numRispostaAperta INT;
    DECLARE esitoRisposta BOOLEAN;
    DECLARE rispostaCorretta VARCHAR(40);
    
	
	-- Controlla se è una risposta a quesito chiuso
    SELECT COUNT(*) INTO numRispostaChiusa
    FROM QUESITORISPOSTACHIUSA  AS QC
    WHERE (QC.NumeroProgressivo = numeroQuesitoTemp) AND (QC.TitoloTest IN (SELECT C1.TitoloTest
                                                                      FROM COMPLETAMENTO AS C1
                                                                      WHERE (idCompletamentoTemp = C1.NumeroProgressivo)));
    
	IF numRispostaChiusa = 1 THEN
        SET tipoRispostaChiusa = TRUE;
    ELSE
        SET tipoRispostaChiusa = FALSE;
    END IF;
    
	-- Controlla se è una risposta a quesito aperto
    SELECT COUNT(*) INTO numRispostaAperta
    FROM QUESITOCODICE AS QC
    WHERE (QC.NumeroProgressivo = numeroQuesitoTemp) AND (QC.TitoloTest IN (SELECT C1.TitoloTest
                                                                      FROM COMPLETAMENTO AS C1
                                                                      WHERE (idCompletamentoTemp = C1.NumeroProgressivo)));

    IF numRispostaAperta = 1 THEN
        SET tipoRispostaAperta = TRUE;
    ELSE
        SET tipoRispostaAperta = FALSE;
    END IF;
    
    SET esitoRisposta = FALSE;
    
    IF tipoRispostaChiusa THEN
		SELECT CampoTesto INTO rispostaCorretta
		FROM OPZIONERISPOSTA AS OP
		WHERE (OP.RispostaCorretta = TRUE) AND (OP.NumeroProgressivoQuesito = numeroQuesitoTemp) AND (OP.TitoloTest IN (SELECT C1.TitoloTest
                                                                                    FROM COMPLETAMENTO AS C1
                                                                                    WHERE (idCompletamentoTemp = C1.NumeroProgressivo)));
                        
        IF (valoreRispostaTemp = rispostaCorretta) THEN
			SET esitoRisposta = TRUE;
		END IF;
		
        INSERT INTO RISPOSTAQUESITORISPOSTACHIUSA(NumeroProgressivoCompletamento, TitoloTest, OpzioneScelta, NumeroProgressivoQuesito,Esito) VALUES (idCompletamentoTemp, TitoloTestTemp, valoreRispostaTemp, numeroQuesitoTemp, esitoRisposta);
    END IF;
    
    IF tipoRispostaAperta THEN
		SELECT TestoSoluzione INTO rispostaCorretta
		FROM QUESITOCODICE AS QC, SOLUZIONE
		WHERE (QC.NumeroProgressivo = SOLUZIONE.NumeroProgressivo) AND (SOLUZIONE.NumeroProgressivo = numeroQuesitoTemp) AND (QC.TitoloTest IN (SELECT C1.TitoloTest
                                                                                                                                        FROM COMPLETAMENTO AS C1
                                                                                                                                        WHERE (idCompletamentoTemp = C1.NumeroProgressivo)));
        
        IF (valoreRispostaTemp = rispostaCorretta) THEN
			SET esitoRisposta = TRUE;
		END IF;
		
        INSERT INTO RISPOSTAQUESITOCODICE(NumeroProgressivoCompletamento, TitoloTest, Testo, NumeroProgressivoQuesito,Esito) VALUES (idCompletamentoTemp, TitoloTestTemp, valoreRispostaTemp, numeroQuesitoTemp, esitoRisposta);
    END IF;
    
END
// DELIMITER ;



DELIMITER //
CREATE PROCEDURE visualizzaEsitoRisposta(
    IN idCompletamentoTemp INT,
    IN TitoloTestTemp VARCHAR(30),
    IN numQuesito INT,
    OUT esitoRisposta BOOLEAN
)
BEGIN
    DECLARE esitoTemp BOOLEAN;

    -- Verifica se esiste una risposta per il quesito di tipo "Risposta Chiusa"
    IF EXISTS (
        SELECT 1 
        FROM RISPOSTAQUESITORISPOSTACHIUSA AS RC 
        WHERE RC.NumeroProgressivoQuesito = numQuesito AND RC.TitoloTest = TitoloTestTemp
    ) THEN
        SELECT esito INTO esitoTemp
        FROM RISPOSTAQUESITORISPOSTACHIUSA AS RC
        WHERE RC.NumeroProgressivoQuesito = numQuesito AND RC.TitoloTest = TitoloTestTemp
        LIMIT 1; -- Assicura che venga restituita una sola riga
    END IF;

    -- Verifica se esiste una risposta per il quesito di tipo "Codice"
    IF EXISTS (
        SELECT 1 
        FROM RISPOSTAQUESITOCODICE AS QC 
        WHERE QC.NumeroProgressivoQuesito = numQuesito AND QC.TitoloTest = TitoloTestTemp
    ) THEN
        SELECT esito INTO esitoTemp
        FROM RISPOSTAQUESITOCODICE AS QC
        WHERE QC.NumeroProgressivoQuesito = numQuesito AND QC.TitoloTest = TitoloTestTemp
        LIMIT 1; -- Assicura che venga restituita una sola riga
    END IF;

    -- Imposta il valore di esitoRisposta in base al valore di esitoTemp
    IF esitoTemp IS NOT NULL THEN
        SET esitoRisposta = esitoTemp;
    ELSE
        SET esitoRisposta = NULL;
    END IF;

END
//
DELIMITER ;




DELIMITER //
CREATE PROCEDURE inserisciMessaggioStudente(
	IN emailStudenteTemp VARCHAR(40),
    IN emailDocenteTemp VARCHAR(40),
    IN titoloTestTemp VARCHAR(20),
    IN titoloMess VARCHAR(20),
    IN testoMess VARCHAR(60)
)
BEGIN
	DECLARE IDMess INT;
    
    INSERT INTO MESSAGGIO (TitoloTest, TitoloMessaggio, CampoTesto, Data)
    VALUES (titoloTestTemp, titoloMess, testoMess, NOW());
    
    -- salvo l'ID del messaggio -> potrebbe esserci un errore in quanto i campi per la ricerca noon sono univoci
    SELECT Id INTO IDMess
    FROM MESSAGGIO
    WHERE (TitoloTest=titoloTestTemp) AND (TitoloMessaggio = titoloMess) AND (testoMess = CampoTesto);
    
    -- Invio del messaggio a tutti i docenti
    INSERT INTO RICEZIONEDOCENTE VALUES (IDMess, titoloTestTemp, emailDocenteTemp);
    
    -- Aggiornamento tabella INVIOSTUDENTE
    INSERT INTO INVIOSTUDENTE VALUES(IDMess, titoloTestTemp, emailStudenteTemp);

END
// DELIMITER ;








-- TRIGGER








DELIMITER //
CREATE TRIGGER cambio_stato_incompletamento_rispostaquesitorispostachiusa
AFTER INSERT ON RISPOSTAQUESITORISPOSTACHIUSA  
FOR EACH ROW
BEGIN
    DECLARE num_risposte_inserite INT;

    -- Conta quante risposte sono state inserite per lo studente per il test corrente
    SET num_risposte_inserite = (SELECT COUNT(*) FROM RISPOSTAQUESITORISPOSTACHIUSA
		WHERE (TitoloTest = NEW.TitoloTest AND NumeroProgressivoCompletamento = NEW.NumeroProgressivoCompletamento));

    -- Se il numero di risposte inserite è uguale a 1, cambia lo stato del test in 'InCompletamento'
    IF (num_risposte_inserite = 1) THEN
        UPDATE COMPLETAMENTO
        SET Stato = 'InCompletamento'
        WHERE TitoloTest = NEW.TitoloTest AND NumeroProgressivo = NEW.NumeroProgressivoCompletamento;
    END IF;
END
// DELIMITER ;



DELIMITER //
CREATE TRIGGER cambio_stato_incompletamento_rispostaquesitocodice
AFTER INSERT ON RISPOSTAQUESITOCODICE
FOR EACH ROW
BEGIN
    DECLARE num_risposte_inserite INT;

    -- Conta quante risposte sono state inserite per lo studente per il test corrente
    SET num_risposte_inserite = (SELECT COUNT(*) FROM RISPOSTAQUESITOCODICE
		WHERE TitoloTest = NEW.TitoloTest AND NumeroProgressivoCompletamento = NEW.NumeroProgressivoCompletamento);

    -- Se il numero di risposte inserite è uguale a 1, cambia lo stato del test in 'InCompletamento'
    IF (num_risposte_inserite = 1) THEN
        UPDATE COMPLETAMENTO
        SET Stato = 'InCompletamento'
        WHERE TitoloTest = NEW.TitoloTest AND NumeroProgressivo = NEW.NumeroProgressivoCompletamento;
    END IF;
END;
// DELIMITER ;



DELIMITER //
CREATE TRIGGER cambio_stato_concluso_rispostaquesitorispostachiusa
AFTER INSERT ON RISPOSTAQUESITORISPOSTACHIUSA
FOR EACH ROW
BEGIN
    DECLARE num_quesiti_totali INT;
    DECLARE num_risposte_inserite INT;
    DECLARE num_risposte_corrette INT;
    DECLARE num_progressivo_completamento INT;
    
    -- Ottieni il numero progressivo di completamento
    SET num_progressivo_completamento = (SELECT NumeroProgressivoCompletamento FROM RISPOSTAQUESITORISPOSTACHIUSA
		WHERE TitoloTest = NEW.TitoloTest AND NumeroProgressivoCompletamento = NEW.NumeroProgressivoCompletamento
		LIMIT 1);
    
    -- Conta il numero totale di quesiti per il test
    SET num_quesiti_totali = (SELECT COUNT(*) FROM QUESITORISPOSTACHIUSA
		WHERE TitoloTest = NEW.TitoloTest);

    -- Conta il numero di risposte inserite per il test
    SET num_risposte_inserite = (SELECT COUNT(*) FROM RISPOSTAQUESITORISPOSTACHIUSA
		WHERE NumeroProgressivoCompletamento = num_progressivo_completamento);

    -- Conta il numero di risposte corrette per il test
    SET num_risposte_corrette = (SELECT COUNT(*) FROM RISPOSTAQUESITORISPOSTACHIUSA
		WHERE NumeroProgressivoCompletamento = num_progressivo_completamento AND Esito = TRUE);

    -- Se tutte le risposte sono state inserite e hanno esito True, il test diventa Concluso
    IF (num_risposte_inserite = num_quesiti_totali AND num_risposte_corrette = num_quesiti_totali) THEN
        UPDATE COMPLETAMENTO
        SET Stato = 'Concluso'
        WHERE NumeroProgressivo = num_progressivo_completamento;
    END IF;
END;
// DELIMITER ;



DELIMITER //
CREATE TRIGGER cambio_stato_concluso_rispostaquesitocodice
AFTER INSERT ON RISPOSTAQUESITOCODICE
FOR EACH ROW
BEGIN
    DECLARE num_quesiti_totali INT;
    DECLARE num_risposte_inserite INT;
    DECLARE num_risposte_corrette INT;
    DECLARE num_progressivo_completamento INT;
    
    -- Ottieni il numero progressivo di completamento
    SET num_progressivo_completamento = (SELECT NumeroProgressivoCompletamento FROM RISPOSTAQUESITOCODICE
		WHERE TitoloTest = NEW.TitoloTest AND NumeroProgressivoCompletamento = NEW.NumeroProgressivoCompletamento
		LIMIT 1);
    
    -- Conta il numero totale di quesiti per il test
    SET num_quesiti_totali = (SELECT COUNT(*) FROM QUESITOCODICE
		WHERE TitoloTest = NEW.TitoloTest);

    -- Conta il numero di risposte inserite per il test
    SET num_risposte_inserite = (SELECT COUNT(*) FROM RISPOSTAQUESITOCODICE
		WHERE NumeroProgressivoCompletamento = num_progressivo_completamento);

    -- Conta il numero di risposte corrette per il test
    SET num_risposte_corrette = (SELECT COUNT(*) FROM RISPOSTAQUESITOCODICE
		WHERE NumeroProgressivoCompletamento = num_progressivo_completamento AND Esito = TRUE);

    -- Se tutte le risposte sono state inserite e hanno esito True, il test diventa Concluso
    IF num_risposte_inserite = num_quesiti_totali AND num_risposte_corrette = num_quesiti_totali THEN
        UPDATE COMPLETAMENTO
        SET Stato = 'Concluso'
        WHERE NumeroProgressivo = num_progressivo_completamento;
    END IF;
END;
// DELIMITER ;




DELIMITER //
CREATE TRIGGER testConclusoVisualizzaRisposte
AFTER UPDATE ON TEST
FOR EACH ROW
BEGIN
    IF NEW.VisualizzaRisposte = TRUE THEN
        UPDATE COMPLETAMENTO
        SET Stato = 'Concluso'
        WHERE TitoloTest = NEW.Titolo;
    END IF;
END
// DELIMITER ;


DELIMITER //
CREATE TRIGGER incrementaNumRighe
BEFORE INSERT ON RIGA
FOR EACH ROW
BEGIN
    UPDATE TABELLADIESERCIZIO
    SET num_righe = num_righe + 1
    WHERE Nome = NEW.NomeTabella;
END 
//
DELIMITER ;





DELIMITER //
-- TRIGGER PER CAMBIARE L'ATTRIBUTO NUMERORISPOSTE DELLA TAVELLA QUESITO(IN RISPOSTA CI DOVREBBE ESSERE ANCHE IL TITOLO DEL TEST DATO CHE SOLO IL NUMERO PROGRESSIVO DEL QUESITO NON è SUFFICIENTE PER INDENTIFICARLO DALLA TABELLA RIPSOSTA )
CREATE TRIGGER AggiornaNumeroRisposteQuesitoAfterInsert
AFTER INSERT ON RISPOSTAQUESITORISPOSTACHIUSA
FOR EACH ROW
BEGIN
    UPDATE QUESITO
    SET NumeroRisposte = NumeroRisposte + 1
    WHERE NumeroProgressivo = NEW.NumeroProgressivoQuesito;
END;
//
DELIMITER ;

DELIMITER //
CREATE TRIGGER AggiornaNumeroRisposteQuesitoAfterDelete
AFTER DELETE ON RISPOSTAQUESITORISPOSTACHIUSA
FOR EACH ROW
BEGIN
    UPDATE QUESITO
    SET NumeroRisposte = NumeroRisposte - 1
    WHERE NumeroProgressivo = OLD.NumeroProgressivoQuesito;
END;
//
DELIMITER ;

DELIMITER //
CREATE TRIGGER AggiornaNumeroRisposteQuesitoCodiceAfterInsert
AFTER INSERT ON RISPOSTAQUESITOCODICE
FOR EACH ROW
BEGIN
    UPDATE QUESITO
    SET NumeroRisposte = NumeroRisposte + 1
    WHERE NumeroProgressivo = NEW.NumeroProgressivoQuesito;
END;
//
DELIMITER ;

DELIMITER //
CREATE TRIGGER AggiornaNumeroRisposteQuesitoCodiceAfterDelete
AFTER DELETE ON RISPOSTAQUESITOCODICE
FOR EACH ROW
BEGIN
    UPDATE QUESITO
    SET NumeroRisposte = NumeroRisposte - 1
    WHERE NumeroProgressivo = OLD.NumeroProgressivoQuesito;
END;
//
DELIMITER ;

CREATE VIEW ClassificaQuesitiPerRisposte AS
SELECT  QUESITO.NumeroProgressivo,QUESITO.TitoloTest,COUNT(RC.NumeroProgressivoCompletamento) + COUNT(RCC.NumeroProgressivoCompletamento) AS NumeroTotaleRisposte
FROM QUESITO 
JOIN RISPOSTAQUESITORISPOSTACHIUSA AS RC ON QUESITO.NumeroProgressivo = RC.NumeroProgressivoQuesito AND QUESITO.TitoloTest = RC.TitoloTest
JOIN RISPOSTAQUESITOCODICE AS RCC ON QUESITO.NumeroProgressivo = RCC.NumeroProgressivoQuesito AND QUESITO.TitoloTest = RCC.TitoloTest
GROUP BY QUESITO.NumeroProgressivo, QUESITO.TitoloTest
ORDER BY NumeroTotaleRisposte DESC;

CREATE VIEW classificaTestCompletati(codiceStudente,testSvolti) AS
	SELECT
		CodiceAlfaNumerico,
		COUNT(*) AS num_test_completati
	FROM STUDENTE, COMPLETAMENTO AS C1, COMPLETAMENTO AS C2
	WHERE
		(Email = C1.EmailStudente) AND (Email = C2.EmailStudente) AND (C1.TitoloTest <> C2.TitoloTest) AND (C1.Stato = "Concluso")
	GROUP BY
		STUDENTE.CodiceAlfaNumerico
	ORDER BY
		num_test_completati DESC;


-- AREA PER I TEST

-- Test inserisciRisposta e visualizzaEsito e inserisciMessaggioStudente

INSERT INTO DOCENTE VALUES("docente@gmail.com","ciao","nano", 1234589, "scienze", "corso");
INSERT INTO DOCENTE VALUES("docente2@gmail.com","ciao2","nano2", 12345892, "scienze", "corso");
INSERT INTO STUDENTE VALUES("studente@gmail.com", "nano", "ciao", 123456789, 2010, 1234567891234567);
INSERT INTO STUDENTE VALUES("studente2@gmail.com", "nano", "ciao", 3333, 2010, 2234567891234567);
INSERT INTO TEST VALUES("provaNr1", '2024-02-07 14:30:00', NULL ,true, "docente@gmail.com");
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Aperto", "provaNr1", "studente@gmail.com", NULL, NULL);
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Aperto", "provaNr1", "studente2@gmail.com", NULL, NULL);
INSERT INTO QUESITO VALUES(1,"provaNr1","Basso", "testo quesito di codice", 3);
INSERT INTO QUESITO VALUES(2,"provaNr1","Basso", "testo quesito a scleta", 3);
INSERT INTO QUESITOCODICE VALUES(1, "provaNr1");
INSERT INTO SOLUZIONE VALUES(1, "provaNr1","soluzione risposta Corretta");
INSERT INTO QUESITORISPOSTACHIUSA VALUES(2, "provaNr1");
INSERT INTO OPZIONERISPOSTA VALUES(1,"provaNr1",2,"opzione risposta Corretta",true);
INSERT INTO OPZIONERISPOSTA VALUES(2,"provaNr1",2,"opzione risposta sbagliata",false);
INSERT INTO STUDENTE VALUES("alessia@gmail.com", "Alessia", "Di Sabato", 123456789, 2021, "ABCDEFGHILMNOPWR");
INSERT INTO STUDENTE VALUES("tabish@gmail.com", "Tabish", "Ghazanfar", 8654678, 2010,"gdhdnbgdtjhjklmk");
INSERT INTO STUDENTE VALUES("lorenzo@gmail.com", "Lorenzo", "Maini", 475875983,2010, "llllllllllllllll");
INSERT INTO STUDENTE VALUES("alex@gmail.com","Alex", "Ranaulo",35111111,2010,  "aaaaaaaaaaaaaaaa");
INSERT INTO STUDENTE VALUES("davide@gmail.com", "Davide", "De Rosa", 1211212,2010,  "dddddddddddddddd");
INSERT INTO TEST VALUES("provaNr2", '2024-02-09 14:30:00', NULL ,true, "docente@gmail.com");
INSERT INTO QUESITO VALUES(4,"provaNr2","Basso", "testo quesito a scleta", 3);
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Aperto", "provaNr1", "alessia@gmail.com", NOW(), NOW());
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Aperto", "provaNr2", "alessia@gmail.com", NOW(), NOW());
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Aperto", "provaNr1", "tabish@gmail.com", NOW(), NOW());
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Aperto", "provaNr2", "tabish@gmail.com", NOW(), NOW());
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Aperto", "provaNr1", "lorenzo@gmail.com", NOW(), NOW());
INSERT INTO COMPLETAMENTO (TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("provaNr2", "lorenzo@gmail.com", NOW(), NOW());

CALL inserisciRisposta(1, "provaNr1", "opzione risposta Corretta", 2);
CALL inserisciRisposta(2, "provaNr1", "rispostaNonCorretta", 1);
CALL inserisciRisposta(3, "provaNr1", "rispostaNonCorretta", 1);
CALL inserisciRisposta(4, "provaNr2", "rispostaNonCorretta", 1);

 CALL visualizzaEsitoRisposta(1, "provaNr1", 2,  @esitoRispostaScelta);
 SELECT @esitoRispostaScelta;

 CALL visualizzaEsitoRisposta(5, "provaNr1",1,  @esitoRispostaCodice);
 SELECT @esitoRispostaCodice;

CALL inserisciMessaggioStudente("studente@gmail.com", "docente@gmail.com", "provaNr1", "titoloMessaggio", "Argomento del messaggio");
CALL InserimentoMessaggioDocente("provaNr1", "Attenzione","Questo è un messaggio importante",null,"docente@gmail.com");
CALL InserimentoMessaggioDocente("testDiProva3", "Eccoci qua","Questo è un messaggio e basta",null,"docente2@gmail.com");
#SELECT * FROM MESSAGGIO;

CALL CreazioneTabellaEsercizio("NomeTabellaProva",NOW(),20,"docente2@gmail.com");
#SELECT * FROM TABELLADIESERCIZIO;

CALL ModificaVisualizzazioneRisposte("nuovoTitolo3",true);
#SELECT * FROM TEST;

CALL CreazioneTest("TestDiProva3", NOW(), null, true, "docente@gmail.com");
#SELECT * FROM TEST;

CALL CreazioneQuesitoRispostaChiusa("TestDiProva3","Medio","Eccoci qua",40,@nQ1);
CALL CreazioneQuesitoRispostaChiusa("provaNr2","Medio","Descrizione",5,@nQ2);
CALL CreazioneQuesitoRispostaChiusa("TestDiProva3","Medio","Eccoci qua",40,@nQ3);
CALL CreazioneQuesitoCodice("TestDiProva3","Alto","Eccoci qua",10,@nQ4);
CALL CreazioneQuesitoCodice("TestDiProva3","Alto","Eccoci qua di nuovo",20,@nQ5);

SELECT @nQ1;
SELECT @nQ2;
SELECT @nQ3;
SELECT @nQ4;
SELECT @nQ5;

CALL InserimentoSoluzione("provaNr1",1,"Qui va tutto bene");
CALL InserimentoSoluzione("provaNr1",8,"Qui va tutto bene sbagliato");
CALL InserimentoSoluzione("TestDiProva3",8,"Anche qua funziona");
CALL InserimentoSoluzione("TestDiProva3",9,"Anche qua funziona tutto");

CALL InserimentoOpzioneRisposta("provaNr2",2,"Evviva Noi fatto male");
CALL InserimentoOpzioneRisposta("provaNr2",6,"Evviva Noi");
CALL InserimentoOpzioneRisposta("provaNr2",8,"Completamento di Lollo");

CALL InserisciRisposta(3,"ProvaNr1","risposta chiusa",2);

SELECT * FROM QUESITO;
SELECT * FROM QUESITORISPOSTACHIUSA;
SELECT * FROM OPZIONERISPOSTA;
SELECT * FROM RISPOSTAQUESITORISPOSTACHIUSA;
SELECT * FROM QUESITOCODICE;
SELECT * FROM SOLUZIONE;
SELECT * FROM RISPOSTAQUESITOCODICE;
SELECT * FROM COMPLETAMENTO;






-- Fine test
/*

DELIMITER //
UPDATE TEST
SET Titolo="nuovoTitolo3",visualizzaRisposte="0"
WHERE Titolo="TestDiProva3"
//
DELIMITER ;

CALL VisualizzaTestDisponibili();

/*
-- Test classificaTestCompletati
INSERT INTO DOCENTE VALUES("docente@gmail.com","ciao","nano", 1234589, "scienze", "corso");
INSERT INTO TEST VALUES("provaNr1", '2024-02-07 14:30:00', NULL ,true, "docente@gmail.com");
INSERT INTO STUDENTE VALUES("alessia@gmail.com", "Alessia", "Di Sabato", 123456789, 2021, "ABCDEFGHILMNOPWR");
INSERT INTO STUDENTE VALUES("tabish@gmail.com", "Tabish", "Ghazanfar", 8654678, 2010,"gdhdnbgdtjhjklmk");
INSERT INTO STUDENTE VALUES("lorenzo@gmail.com", "Lorenzo", "Maini", 475875983,2010, "llllllllllllllll");
INSERT INTO STUDENTE VALUES("alex@gmail.com","Alex", "Ranaulo",35111111,2010,  "aaaaaaaaaaaaaaaa");
INSERT INTO STUDENTE VALUES("davide@gmail.com", "Davide", "De Rosa", 1211212,2010,  "dddddddddddddddd");
INSERT INTO TEST VALUES("provaNr2", '2024-02-09 14:30:00', NULL ,true, "docente@gmail.com");
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Aperto", "provaNr1", "alessia@gmail.com", NOW(), NOW());
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Concluso", "provaNr2", "alessia@gmail.com", NOW(), NOW());
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Concluso", "provaNr1", "tabish@gmail.com", NOW(), NOW());
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Concluso", "provaNr2", "tabish@gmail.com", NOW(), NOW());
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Aperto", "provaNr1", "lorenzo@gmail.com", NOW(), NOW());
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Aperto", "provaNr2", "lorenzo@gmail.com", NOW(), NOW());


-- Fine test
*/

/*
-- Test per Trigger testConclusoVisualizzaRisposte
UPDATE TEST
SET VisualizzaRisposte = TRUE
WHERE Titolo = 'provaNr2';

-- Fine Test
*/

/*
-- Test per Trigger incrementaNumRighe
INSERT INTO TABELLADIESERCIZIO VALUES ("TabellaNR1",NOW(), 0, 'docente@gmail.com');
INSERT INTO RIGA VALUES("primariga","TabellaNR1");
INSERT INTO RIGA VALUES("secondariga","TabellaNR1");
INSERT INTO RIGA VALUES("terzariga","TabellaNR1");

INSERT INTO TABELLADIESERCIZIO VALUES ("TabellaNR2",NOW(), 0, 'docente@gmail.com');
INSERT INTO RIGA VALUES("primariga","TabellaNR2");
INSERT INTO RIGA VALUES("secondariga","TabellaNR2");
-- Fine Test
*/