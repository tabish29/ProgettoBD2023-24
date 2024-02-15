# elimino se esiste, poi ricreo il database 
DROP DATABASE IF EXISTS ESQL;
CREATE DATABASE IF NOT EXISTS ESQL;
USE ESQL;

# Creo le tabelle
CREATE TABLE DOCENTE (
	Email VARCHAR(40) PRIMARY KEY,
    Nome VARCHAR (20) NOT NULL,
    Cognome VARCHAR (20) NOT NULL,
    RecapitoTelefonicoDocente INT,
    NomeDipartimento VARCHAR(20),
    NomeCorso VARCHAR(20)
    
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
    Foto VARCHAR(20),											# HELP COME METTIAMO LA FOTO
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
    
    FOREIGN KEY(TitoloTest) REFERENCES TEST(Titolo) ON DELETE CASCADE 				# BOH IO NON SO HO MESSO CASCADE
    
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
	Stato ENUM("Aperto","InCompletamento","Concluso") NOT NULL,  #non credo sia corretto mettere not null,non credo che sia necessario avere questo attributo come chiave primaria(dopo crea casino con le tabelle risposta)
	TitoloTest VARCHAR(20) NOT NULL,
    EmailStudente VARCHAR(40) NOT NULL,
    DataPrimaRisposta DATETIME,
    DataUltimaRisposta DATETIME,
    
    PRIMARY KEY(Stato, TitoloTest, EmailStudente),
    
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


CREATE TABLE ATTRIBUTO (
	NomeTabella VARCHAR(20) NOT NULL,
    NomeAttributo VARCHAR(20) NOT NULL,
    Tipo VARCHAR(20) NOT NULL,
    
    PRIMARY KEY(NomeAttributo, NomeTabella), #Nome attributo va messo nella prima posizione se no dà problema di indicizzazione
    
    FOREIGN KEY(NomeTabella) REFERENCES TABELLADIESERCIZIO(Nome) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE VINCOLODIINTEGRITA (
	NomeTabella VARCHAR(20) NOT NULL,
    NomeAttributo VARCHAR(20) NOT NULL,
    EmailDocente VARCHAR(40),
    
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
	NumeroProgressivo INT,
    TitoloTest VARCHAR(20) NOT NULL,
    
	PRIMARY KEY(NumeroProgressivo, TitoloTest),
    
    FOREIGN KEY(TitoloTest) REFERENCES QUESITO(TitoloTest) ON DELETE CASCADE,
	FOREIGN KEY(NumeroProgressivo) REFERENCES QUESITO(NumeroProgressivo) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE QUESITOCODICE (
	NumeroProgressivo INT,
    TitoloTest VARCHAR(20) NOT NULL,
    Soluzione VARCHAR(40),
    
	PRIMARY KEY(TitoloTest, NumeroProgressivo),
    
   FOREIGN KEY(TitoloTest) REFERENCES QUESITO(TitoloTest) ON DELETE CASCADE,
   FOREIGN KEY(NumeroProgressivo) REFERENCES QUESITO(NumeroProgressivo) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE RISPOSTAQUESITORISPOSTACHIUSA  (
    StatoCompletamento ENUM("Aperto","InCompletamento","Concluso") NOT NULL,
    TitoloTest VARCHAR(20) NOT NULL,
    EmailStudente VARCHAR(40) NOT NULL,
    OpzioneScelta VARCHAR(20),
    NumeroProgressivoQuesito INT,
    Esito BOOLEAN,
    
    PRIMARY KEY (StatoCompletamento , TitoloTest , EmailStudente),
    
    FOREIGN KEY(StatoCompletamento) REFERENCES COMPLETAMENTO(Stato) ON DELETE CASCADE,
    FOREIGN KEY(TitoloTest) REFERENCES COMPLETAMENTO(TitoloTest) ON DELETE CASCADE,
    FOREIGN KEY(EmailStudente) REFERENCES COMPLETAMENTO(EmailStudente) ON DELETE CASCADE,
	FOREIGN KEY(NumeroProgressivoQuesito) REFERENCES QUESITORISPOSTACHIUSA(NumeroProgressivo) ON DELETE CASCADE
    
)  ENGINE=INNODB;

CREATE TABLE RISPOSTAQUESITOCODICE  (
    StatoCompletamento ENUM("Aperto","InCompletamento","Concluso") NOT NULL,
    TitoloTest VARCHAR(20) NOT NULL,
    EmailStudente VARCHAR(40) NOT NULL,
    Testo VARCHAR(100),
    NumeroProgressivoQuesito INT,
    Esito BOOLEAN,
    
    PRIMARY KEY (StatoCompletamento , TitoloTest , EmailStudente),
    
    FOREIGN KEY(StatoCompletamento) REFERENCES COMPLETAMENTO(Stato) ON DELETE CASCADE,
    FOREIGN KEY(TitoloTest) REFERENCES COMPLETAMENTO(TitoloTest) ON DELETE CASCADE,
    FOREIGN KEY(EmailStudente) REFERENCES COMPLETAMENTO(EmailStudente) ON DELETE CASCADE,
    FOREIGN KEY(NumeroProgressivoQuesito) REFERENCES QUESITOCODICE(NumeroProgressivo) ON DELETE CASCADE
    
)  ENGINE=INNODB;

CREATE TABLE OPZIONERISPOSTA  (
    TitoloTest VARCHAR(20) NOT NULL,
    NumeroProgressivoQuesito INT NOT NULL,
    NumeroProgressivoOpzione INT NOT NULL,
    CampoTesto VARCHAR(2000),
    
    PRIMARY KEY (NumeroProgressivoQuesito, TitoloTest, NumeroProgressivoOpzione),
    
    FOREIGN KEY(TitoloTest) REFERENCES QUESITORISPOSTACHIUSA(TitoloTest) ON DELETE CASCADE,
    FOREIGN KEY(NumeroProgressivoQuesito) REFERENCES QUESITORISPOSTACHIUSA(NumeroProgressivo) ON DELETE CASCADE
    
)  ENGINE=INNODB;

CREATE TABLE COSTITUZIONE  (
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
    TitoloTest VARCHAR(20) NOT NULL,
    StatoCompletamento ENUM("Aperto","InCompletamento","Concluso") NOT NULL,
    
    PRIMARY KEY (EmailStudente, TitoloTest, StatoCompletamento),
    
    FOREIGN KEY(EmailStudente) REFERENCES STUDENTE(Email) ON DELETE CASCADE,
    FOREIGN KEY(TitoloTest) REFERENCES COMPLETAMENTO(TitoloTest) ON DELETE CASCADE,
    FOREIGN KEY(StatoCompletamento) REFERENCES COMPLETAMENTO(Stato) ON DELETE CASCADE

) ENGINE = INNODB;




/*
DELIMITER //
CREATE TRIGGER cambio_stato_incompletamento_rispostaquesitorispostachiusa
AFTER INSERT ON RISPOSTAQUESITORISPOSTACHIUSA  
FOR EACH ROW
BEGIN
    DECLARE num_risposte_inserite INT;

    -- Conta quante risposte sono state inserite per lo studente
    SELECT COUNT(*) INTO num_risposte_inserite
    FROM RISPOSTA
    WHERE TitoloTest = NEW.TitoloTest AND EmailStudente = NEW.EmailStudente;

    -- Se il numero di risposte inserite è uguale a 1, cambia lo stato del test in 'InCompletamento'
    IF num_risposte_inserite = 1 THEN
        UPDATE COMPLETAMENTO
        SET Stato = "InCompletamento"
        WHERE TitoloTest = NEW.TitoloTest AND EmailStudente = NEW.EmailStudente;
    END IF;
END//
DELIMITER ;

DELIMITER //
CREATE TRIGGER cambio_stato_incompletamento_rispostaquesitocodice
AFTER INSERT ON RISPOSTAQUESITOCODICE
FOR EACH ROW
BEGIN
    DECLARE num_risposte_inserite INT;

    -- Conta quante risposte sono state inserite per lo studente
    SELECT COUNT(*) INTO num_risposte_inserite
    FROM RISPOSTA
    WHERE TitoloTest = NEW.TitoloTest AND EmailStudente = NEW.EmailStudente;

    -- Se il numero di risposte inserite è uguale a 1, cambia lo stato del test in 'InCompletamento'
    IF num_risposte_inserite = 1 THEN
        UPDATE COMPLETAMENTO
        SET Stato = "InCompletamento"
        WHERE TitoloTest = NEW.TitoloTest AND EmailStudente = NEW.EmailStudente;
    END IF;
END//
DELIMITER ;


DELIMITER //
CREATE TRIGGER cambio_stato_test_rispostaquesitorispostachiusa
AFTER INSERT ON RISPOSTAQUESITORISPOSTACHIUSA
FOR EACH ROW
BEGIN
    DECLARE num_quesiti_totali INT;
    DECLARE num_risposte_inserite INT;
    DECLARE num_risposte_corrette INT;

    -- Conta il numero totale di quesiti per il test
    SELECT COUNT(*) INTO num_quesiti_totali
    FROM QUESITO
    WHERE TitoloTest = NEW.TitoloTest;

    -- Conta il numero di risposte inserite per il test e lo studente
    SELECT COUNT(*) INTO num_risposte_inserite
    FROM RISPOSTA
    WHERE TitoloTest = NEW.TitoloTest AND EmailStudente = NEW.EmailStudente;

    -- Conta il numero di risposte corrette per lo studente
    SELECT COUNT(*) INTO num_risposte_corrette
    FROM RISPOSTA
    WHERE TitoloTest = NEW.TitoloTest AND EmailStudente = NEW.EmailStudente AND Esito = TRUE;

    -- Se tutte le risposte sono state inserite e hanno esito True, il test diventa Concluso
    IF num_risposte_inserite = num_quesiti_totali AND num_risposte_corrette = num_quesiti_totali THEN
        UPDATE COMPLETAMENTO
        SET Stato = 'Concluso'
        WHERE TitoloTest = NEW.TitoloTest AND EmailStudente = NEW.EmailStudente;
    END IF;
END//
DELIMITER ;


DELIMITER //
CREATE TRIGGER cambio_stato_test_rispostaquesitocodice
AFTER INSERT ON RISPOSTAQUESITOCODICE
FOR EACH ROW
BEGIN
    DECLARE num_quesiti_totali INT;
    DECLARE num_risposte_inserite INT;
    DECLARE num_risposte_corrette INT;

    -- Conta il numero totale di quesiti per il test
    SELECT COUNT(*) INTO num_quesiti_totali
    FROM QUESITO
    WHERE TitoloTest = NEW.TitoloTest;

    -- Conta il numero di risposte inserite per il test e lo studente
    SELECT COUNT(*) INTO num_risposte_inserite
    FROM RISPOSTA
    WHERE TitoloTest = NEW.TitoloTest AND EmailStudente = NEW.EmailStudente;

    -- Conta il numero di risposte corrette per lo studente
    SELECT COUNT(*) INTO num_risposte_corrette
    FROM RISPOSTA
    WHERE TitoloTest = NEW.TitoloTest AND EmailStudente = NEW.EmailStudente AND Esito = TRUE;

    -- Se tutte le risposte sono state inserite e hanno esito True, il test diventa Concluso
    IF num_risposte_inserite = num_quesiti_totali AND num_risposte_corrette = num_quesiti_totali THEN
        UPDATE COMPLETAMENTO
        SET Stato = 'Concluso'
        WHERE TitoloTest = NEW.TitoloTest AND EmailStudente = NEW.EmailStudente;
    END IF;
END//
DELIMITER ;


DELIMITER //
CREATE TRIGGER cambio_stato_test_concluso
AFTER UPDATE ON TEST
FOR EACH ROW
BEGIN
    -- Verifica se il campo VisualizzaRisposte è stato impostato a True
    IF NEW.VisualizzaRisposte = TRUE THEN
        -- Aggiorna lo stato del test a 'Concluso' per tutti gli studenti
        UPDATE COMPLETAMENTO
        SET Stato = 'Concluso'
        WHERE TitoloTest = NEW.Titolo;
    END IF;
END//
DELIMITER ;


DELIMITER //
CREATE PROCEDURE VisualizzaTestDisponibili ()
BEGIN
    -- Seleziona tutti i test presenti nella tabella Test
    SELECT * FROM Test;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE VisualizzaQuesitiPerTest (
    IN p_TitoloTest VARCHAR(20)
)
BEGIN
    -- Seleziona i quesiti corrispondenti al titolo del test specificato
    SELECT * FROM Quesiti WHERE TitoloTest = p_TitoloTest;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE Autenticazione (
    IN p_Email VARCHAR(40),
    OUT p_Autenticato BOOLEAN
)
BEGIN
    -- Verifica se l'email esiste nella tabella Utenti e corrisponde alla password fornita
    IF EXISTS (SELECT * FROM Utenti WHERE Email = p_Email) THEN
        SET p_Autenticato = TRUE;
    ELSE
        SET p_Autenticato = FALSE;
    END IF;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE Registrazione (
    IN p_Email VARCHAR(40)
)
BEGIN
    -- Verifica se l'email non esiste già nella tabella Utenti
    IF NOT EXISTS (SELECT * FROM Utenti WHERE Email = p_Email) THEN
        -- Inserisce l'utente nella tabella Utenti
        INSERT INTO Utenti (Email) VALUES (p_Email);
    END IF;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE CreazioneTest (
    IN p_TitoloTest VARCHAR(50),
    IN p_DescrizioneTest TEXT,
    IN p_EmailDocente VARCHAR(40),
    OUT p_TestCreato BOOLEAN
)
BEGIN
    DECLARE docente_esiste BOOLEAN;

    -- Verifica se il docente esiste nella tabella Docenti
    SELECT EXISTS(SELECT 1 FROM Docenti WHERE Email = p_EmailDocente) INTO docente_esiste;

    -- Se il docente esiste, crea il nuovo test
    IF docente_esiste THEN
        INSERT INTO Test (Titolo, Descrizione, EmailDocente) VALUES (p_TitoloTest, p_DescrizioneTest, p_EmailDocente);
        SET p_TestCreato = TRUE;
    ELSE
        SET p_TestCreato = FALSE;
    END IF;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE SettaVisualizzazioneRisposte (
    IN p_TitoloTest VARCHAR(50),
    IN p_Valore BOOLEAN
)
BEGIN
    -- Imposta il campo VisualizzaRisposte al valore specificato per il test specificato
    UPDATE Test SET VisualizzaRisposte = p_Valore WHERE Titolo = p_TitoloTest;
END //
DELIMITER ;

DELIMITER //

CREATE PROCEDURE InserisciMessaggioDocente(
    IN p_TitoloTest VARCHAR(20),
    IN p_TitoloMessaggio VARCHAR(20),
    IN p_CampoTesto VARCHAR(60),
    IN p_Data DATETIME
)
BEGIN
    -- Inserimento del messaggio
    INSERT INTO MESSAGGIO (TitoloTest, TitoloMessaggio, CampoTesto, Data)
    VALUES (p_TitoloTest, p_TitoloMessaggio, p_CampoTesto, p_Data);
    
    -- Invio del messaggio a tutti gli studenti
    INSERT INTO RICEZIONESTUDENTE (TitoloTest, TitoloMessaggio, CampoTesto, Data)
    VALUES (p_TitoloTest, p_TitoloMessaggio, p_CampoTesto, p_Data);
END//

DELIMITER ;

*/
DELIMITER //

CREATE PROCEDURE inserisciRisposta(
    IN statoCompletamentoTemp ENUM('Aperto','InCompletamento','Concluso'),
    IN titoloTestTemp VARCHAR(20),
    IN emailStudenteTemp VARCHAR(40),
    IN valoreRispostaTemp VARCHAR(20),
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
    FROM QUESITORISPOSTACHIUSA 
    WHERE NumeroProgressivo = numeroQuesitoTemp;
    
	IF numRispostaChiusa = 1 THEN
        SET tipoRispostaChiusa = TRUE;
    ELSE
        SET tipoRispostaChiusa = FALSE;
    END IF;
    
	-- Controlla se è una risposta a quesito aperto
    SELECT COUNT(*) INTO numRispostaAperta
    FROM QUESITOCODICE
    WHERE NumeroProgressivo = numeroQuesitoTemp;

    IF numRispostaAperta = 1 THEN
        SET tipoRispostaAperta = TRUE;
    ELSE
        SET tipoRispostaAperta = FALSE;
    END IF;
    
    SET esitoRisposta = FALSE;
    
    IF tipoRispostaChiusa THEN
		SELECT CampoTesto INTO RispostaCorretta
		FROM OPZIONERISPOSTA
		WHERE OPZIONERISPOSTA.NumeroProgressivoQuesito = numeroQuesitoTemp;
        
        IF (valoreRispostaTemp = rispostaCorretta) THEN
			SET esitoRisposta = TRUE;
		END IF;
		
        INSERT INTO RISPOSTAQUESITORISPOSTACHIUSA(StatoCompletamento,TitoloTest,EmailStudente, OpzioneScelta, NumeroProgressivoQuesito,Esito) VALUES (statoCompletamentoTemp, titoloTestTemp, emailStudenteTemp, valoreRispostaTemp, numeroQuesitoTemp, esitoRisposta);
    END IF;
    
    IF tipoRispostaAperta THEN
		SELECT Soluzione INTO rispostaCorretta
		FROM QUESITOCODICE
		WHERE NumeroProgressivo = numeroQuesitoTemp;
        
        IF (valoreRispostaTemp = rispostaCorretta) THEN
			SET esitoRisposta = TRUE;
		END IF;
		
        INSERT INTO RISPOSTAQUESITOCODICE(StatoCompletamento, TitoloTest,EmailStudente,Testo, NumeroProgressivoQuesito,Esito) VALUES (statoCompletamentoTemp, titoloTestTemp, emailStudenteTemp, valoreRispostaTemp, numeroQuesitoTemp, esitoRisposta);
    END IF;
    
END//

DELIMITER ;



DELIMITER //
CREATE PROCEDURE visualizzaEsitoRisposta(
	IN statoTemp ENUM("Aperto","InCompletamento","Concluso"),
    IN titoloTestTemp VARCHAR(20),
    IN emailTemp VARCHAR(40),
    IN numQuesito INT,
    OUT esitoRisposta BOOLEAN
)
BEGIN
	
    IF(EXISTS(SELECT * FROM RISPOSTAQUESITORISPOSTACHIUSA WHERE (numQuesito = NumeroProgressivoQuesito))) THEN
		(SELECT esito INTO esitoRisposta
		FROM RISPOSTAQUESITORISPOSTACHIUSA
		WHERE (numQuesito = NumeroProgressivoQuesito));
    END IF;
    
    IF(EXISTS(SELECT * FROM RISPOSTAQUESITOCODICE WHERE (numQuesito = NumeroProgressivoQuesito))) THEN
		(SELECT esito INTO esitoRisposta
		FROM RISPOSTAQUESITOCODICE
		WHERE (numQuesito = NumeroProgressivoQuesito));
    END IF;

END//

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
END//

DELIMITER ;


-- Test inserisciRisposta e visualizzaEsito e inserisciMessaggioStudente
INSERT INTO DOCENTE VALUES("docente@gmail.com","ciao","nano", 1234589, "scienze", "corso");
INSERT INTO STUDENTE VALUES("studente@gmail.com", "nano", "ciao", 123456789, 2010, 1234567891234567);
INSERT INTO TEST VALUES("provaNr1", '2024-02-07 14:30:00', NULL ,true, "docente@gmail.com");
INSERT INTO COMPLETAMENTO VALUES("Aperto", "provaNr1", "studente@gmail.com", NULL, NULL);
INSERT INTO QUESITO VALUES(1,"provaNr1","Basso", "testo quesito di codice", 3);
INSERT INTO QUESITO VALUES(2,"provaNr1","Basso", "testo quesito a scleta", 3);
INSERT INTO QUESITOCODICE VALUES(1, "provaNr1","rispostaCorretta");
INSERT INTO QUESITORISPOSTACHIUSA VALUES(2, "provaNr1");
INSERT INTO OPZIONERISPOSTA VALUES("provaNr1",2,2,"rispostaCorretta");

-- CALL inserisciRisposta("Aperto","provaNr1","studente@gmail.com", "rispostaCorretta", 2);
-- CALL inserisciRisposta("Aperto","provaNr1","studente@gmail.com", "rispostaSbagliata", 1);

-- CALL visualizzaEsitoRisposta('Aperto', 'provaNr1', 'studente@gmail.com', 2, @esitoRispostaScelta);
-- SELECT @esitoRispostaScelta;

-- CALL visualizzaEsitoRisposta('Aperto', 'provaNr1', 'studente@gmail.com', 1, @esitoRispostaCodice);
-- SELECT @esitoRispostaCodice;

-- CALL inserisciMessaggioStudente("studente@gmail.com", "docente@gmail.com", "provaNr1", "titoloMessaggio", "Argomento del messaggio");

-- Fine test 



-- Test classificaTestCompletati
INSERT INTO STUDENTE VALUES("alessia@gmail.com", "Alessia", "Di Sabato", 123456789, 2021, "ABCDEFGHILMNOPWR");
INSERT INTO STUDENTE VALUES("tabish@gmail.com", "Tabish", "Ghazanfar", 8654678, 2010,"gdhdnbgdtjhjklmk");
INSERT INTO STUDENTE VALUES("lorenzo@gmail.com", "Lorenzo", "Maini", 475875983,2010, "llllllllllllllll");
INSERT INTO STUDENTE VALUES("alex@gmail.com","Alex", "Ranaulo",35111111,2010,  "aaaaaaaaaaaaaaaa");
INSERT INTO STUDENTE VALUES("davide@gmail.com", "Davide", "De Rosa", 1211212,2010,  "dddddddddddddddd");
INSERT INTO TEST VALUES("provaNr2", '2024-02-09 14:30:00', NULL ,true, "docente@gmail.com");
INSERT INTO COMPLETAMENTO VALUES("Aperto", "provaNr1", "alessia@gmail.com", NOW(), NOW());
INSERT INTO COMPLETAMENTO VALUES("Concluso", "provaNr2", "alessia@gmail.com", NOW(), NOW());
INSERT INTO COMPLETAMENTO VALUES("Concluso", "provaNr1", "tabish@gmail.com", NOW(), NOW());
INSERT INTO COMPLETAMENTO VALUES("Concluso", "provaNr2", "tabish@gmail.com", NOW(), NOW());
INSERT INTO COMPLETAMENTO VALUES("Aperto", "provaNr1", "lorenzo@gmail.com", NOW(), NOW());
INSERT INTO COMPLETAMENTO VALUES("Aperto", "provaNr2", "lorenzo@gmail.com", NOW(), NOW());

-- Fine Test




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






/*
 #Test(da cancellare)
DELIMITER //

CREATE PROCEDURE inserisci_dati(
    IN p_TitoloTest VARCHAR(20),
    IN p_EmailStudente VARCHAR(40),
    IN p_NomeStudente VARCHAR(20),
    IN p_CognomeStudente VARCHAR(20),
    IN p_RecapitoTelefonicoStudente INT,
    IN p_AnnoImmatricolazione INT,
    IN p_CodiceAlfaNumericoStudente CHAR(16),
    IN p_EmailDocente VARCHAR(40),
    IN p_NomeDocente VARCHAR(20),
    IN p_CognomeDocente VARCHAR(20),
    IN p_RecapitoTelefonicoDocente INT,
    IN p_NomeDipartimentoDocente VARCHAR(20),
    IN p_NomeCorsoDocente VARCHAR(20)
)
BEGIN
    -- Inserimento del docente
    INSERT INTO DOCENTE (Email, Nome, Cognome, RecapitoTelefonicoDocente, NomeDipartimento, NomeCorso)
    VALUES (p_EmailDocente, p_NomeDocente, p_CognomeDocente, p_RecapitoTelefonicoDocente, p_NomeDipartimentoDocente, p_NomeCorsoDocente);

    -- Inserimento dello studente
    INSERT INTO STUDENTE (Email, Nome, Cognome, RecapitoTelefonicoStudente, AnnoImmatricolazione, CodiceAlfaNumerico)
    VALUES (p_EmailStudente, p_NomeStudente, p_CognomeStudente, p_RecapitoTelefonicoStudente, p_AnnoImmatricolazione, p_CodiceAlfaNumericoStudente);

    -- Inserimento del test
    INSERT INTO TEST (Titolo, DataCreazione, VisualizzaRisposte, EmailDocente)
    VALUES (p_TitoloTest, NOW(), FALSE, p_EmailDocente);

    -- Inserimento del completamento
    INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente)
    VALUES ("Aperto", p_TitoloTest, p_EmailStudente);
END//
DELIMITER ;

CALL inserisci_dati(
    'Titolo del Test',
    'email@studente.com',
    'Nome Studente',
    'Cognome Studente',
    1234567890, -- Recapito telefonico studente
    2022, -- Anno immatricolazione studente
    'ABCDE1234567890', -- Codice alfanumerico studente
    'docente2@email.com',
    'Nome Docente',
    'Cognome Docente',
    987654321, -- Recapito telefonico docente
    'Nome Dipartimento',
    'Nome Corso'
);


#Test per il terzo trigger da implementare
SELECT * FROM TEST WHERE Titolo = 'Titolo del Test';
SELECT * FROM COMPLETAMENTO WHERE TitoloTest = 'Titolo del Test';
UPDATE TEST SET VisualizzaRisposte = True WHERE Titolo = 'Titolo del Test';
SELECT * FROM TEST WHERE Titolo = 'Titolo del Test';
SELECT * FROM COMPLETAMENTO WHERE TitoloTest = 'Titolo del Test';

#Test per il secondo trigger(devo inserire dei dati nella tabella Quesito)

#test per la procedure SettaVisualizzazioneRisposte
CALL SettaVisualizzazioneRisposte('Titolo del Test', True);
SELECT VisualizzaRisposte FROM Test WHERE Titolo = 'Titolo del Test';*/
