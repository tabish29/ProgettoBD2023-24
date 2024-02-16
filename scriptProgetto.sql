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
    Foto BLOB,											# METTIAMO BLOB
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
    
	PRIMARY KEY(TitoloTest, NumeroProgressivo),
    
   FOREIGN KEY(TitoloTest) REFERENCES QUESITO(TitoloTest) ON DELETE CASCADE,
   FOREIGN KEY(NumeroProgressivo) REFERENCES QUESITO(NumeroProgressivo) ON DELETE CASCADE

) ENGINE = INNODB;

CREATE TABLE SOLUZIONE (
	NumeroProgressivo INT,
    TitoloTest VARCHAR(20) NOT NULL,
    TestoSoluzione VARCHAR(40),
    
	PRIMARY KEY(TitoloTest, NumeroProgressivo, TestoSoluzione),
    
   FOREIGN KEY(TitoloTest) REFERENCES QUESITOCODICE(TitoloTest) ON DELETE CASCADE,
   FOREIGN KEY(NumeroProgressivo) REFERENCES QUESITOCODICE(NumeroProgressivo) ON DELETE CASCADE

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

    # Conta quante risposte sono state inserite per lo studente
    SELECT COUNT(*) INTO num_risposte_inserite
    FROM RISPOSTA
    WHERE TitoloTest = NEW.TitoloTest AND EmailStudente = NEW.EmailStudente;

    # Se il numero di risposte inserite è uguale a 1, cambia lo stato del test in 'InCompletamento'
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

    # Conta quante risposte sono state inserite per lo studente
    SELECT COUNT(*) INTO num_risposte_inserite
    FROM RISPOSTA
    WHERE TitoloTest = NEW.TitoloTest AND EmailStudente = NEW.EmailStudente;

    # Se il numero di risposte inserite è uguale a 1, cambia lo stato del test in 'InCompletamento'
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

    # Conta il numero totale di quesiti per il test
    SELECT COUNT(*) INTO num_quesiti_totali
    FROM QUESITO
    WHERE TitoloTest = NEW.TitoloTest;

    # Conta il numero di risposte inserite per il test e lo studente
    SELECT COUNT(*) INTO num_risposte_inserite
    FROM RISPOSTA
    WHERE TitoloTest = NEW.TitoloTest AND EmailStudente = NEW.EmailStudente;

    # Conta il numero di risposte corrette per lo studente
    SELECT COUNT(*) INTO num_risposte_corrette
    FROM RISPOSTA
    WHERE TitoloTest = NEW.TitoloTest AND EmailStudente = NEW.EmailStudente AND Esito = TRUE;

    # Se tutte le risposte sono state inserite e hanno esito True, il test diventa Concluso
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

    # Conta il numero totale di quesiti per il test
    SELECT COUNT(*) INTO num_quesiti_totali
    FROM QUESITO
    WHERE TitoloTest = NEW.TitoloTest;

    # Conta il numero di risposte inserite per il test e lo studente
    SELECT COUNT(*) INTO num_risposte_inserite
    FROM RISPOSTA
    WHERE TitoloTest = NEW.TitoloTest AND EmailStudente = NEW.EmailStudente;

    # Conta il numero di risposte corrette per lo studente
    SELECT COUNT(*) INTO num_risposte_corrette
    FROM RISPOSTA
    WHERE TitoloTest = NEW.TitoloTest AND EmailStudente = NEW.EmailStudente AND Esito = TRUE;

    # Se tutte le risposte sono state inserite e hanno esito True, il test diventa Concluso
    IF num_risposte_inserite = num_quesiti_totali AND num_risposte_corrette = num_quesiti_totali THEN
        UPDATE COMPLETAMENTO
        SET Stato = 'Concluso'
        WHERE TitoloTest = NEW.TitoloTest AND EmailStudente = NEW.EmailStudente;
    END IF;
END//
DELIMITER ;





DELIMITER //
CREATE PROCEDURE VisualizzaTestDisponibili ()
BEGIN
    # Seleziona tutti i test presenti nella tabella Test
    SELECT * FROM Test;
END //
DELIMITER ;





DELIMITER //
CREATE PROCEDURE VisualizzaQuesitiPerTest (
    IN p_TitoloTest VARCHAR(20)
)
BEGIN
    # Seleziona i quesiti corrispondenti al titolo del test specificato
    SELECT * FROM Quesiti WHERE TitoloTest = p_TitoloTest;
END //
DELIMITER ;





DELIMITER //
CREATE PROCEDURE AutenticazioneDocente (
    IN p_Email VARCHAR(40),
    OUT p_Autenticato BOOLEAN
)
BEGIN
    # Verifica se l'email esiste nella tabella Utenti e corrisponde alla password fornita
    IF EXISTS (SELECT * FROM Docente WHERE Email = p_Email) THEN
        SET p_Autenticato = TRUE;
    ELSE
        SET p_Autenticato = FALSE;
    END IF;
END //
DELIMITER ;




DELIMITER //
CREATE PROCEDURE AutenticazioneStudente (
    IN p_Email VARCHAR(40),
    OUT p_Autenticato BOOLEAN
)
BEGIN
    # Verifica se l'email esiste nella tabella Utenti e corrisponde alla password fornita
    IF EXISTS (SELECT * FROM Studente WHERE Email = p_Email) THEN
        SET p_Autenticato = TRUE;
    ELSE
        SET p_Autenticato = FALSE;
    END IF;
END //
DELIMITER ;




#Da mettere gli altri attributi della tabella docente
DELIMITER //
CREATE PROCEDURE RegistrazioneDocente (
    IN p_Email VARCHAR(40)
)
BEGIN
    # Verifica se l'email non esiste già nella tabella Docente
    IF NOT EXISTS (SELECT * FROM Docente WHERE Email = p_Email) THEN
        # Inserisce l'utente nella tabella Utenti
        INSERT INTO Docente (Email) VALUES (p_Email);
    END IF;
END //
DELIMITER ;




#Da mettere gli altri attributi della tabella Studente
DELIMITER //
CREATE PROCEDURE RegistrazioneStudente (
    IN p_Email VARCHAR(40)
)
BEGIN
    # Verifica se l'email non esiste già nella tabella Utenti
    IF NOT EXISTS (SELECT * FROM Studente WHERE Email = p_Email) THEN
        # Inserisce l'utente nella tabella Utenti
        INSERT INTO Studente (Email) VALUES (p_Email);
    END IF;
END //
DELIMITER ;



*/

# TRIGGER

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

//DELIMITER ;




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
    
	
	# Controlla se è una risposta a quesito chiuso
    SELECT COUNT(*) INTO numRispostaChiusa
    FROM QUESITORISPOSTACHIUSA 
    WHERE NumeroProgressivo = numeroQuesitoTemp;
    
	IF numRispostaChiusa = 1 THEN
        SET tipoRispostaChiusa = TRUE;
    ELSE
        SET tipoRispostaChiusa = FALSE;
    END IF;
    
	# Controlla se è una risposta a quesito aperto
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
		SELECT TestoSoluzione INTO rispostaCorretta
		FROM QUESITOCODICE, SOLUZIONE
		WHERE (QUESITOCODICE.NumeroProgressivo = SOLUZIONE.NumeroProgressivo) AND (SOLUZIONE.NumeroProgressivo = numeroQuesitoTemp);
        
        IF (valoreRispostaTemp = rispostaCorretta) THEN
			SET esitoRisposta = TRUE;
		END IF;
		
        INSERT INTO RISPOSTAQUESITOCODICE(StatoCompletamento, TitoloTest,EmailStudente,Testo, NumeroProgressivoQuesito,Esito) VALUES (statoCompletamentoTemp, titoloTestTemp, emailStudenteTemp, valoreRispostaTemp, numeroQuesitoTemp, esitoRisposta);
    END IF;
    
END
// DELIMITER ;



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
    
    # salvo l'ID del messaggio -> potrebbe esserci un errore in quanto i campi per la ricerca noon sono univoci
    SELECT Id INTO IDMess
    FROM MESSAGGIO
    WHERE (TitoloTest=titoloTestTemp) AND (TitoloMessaggio = titoloMess) AND (testoMess = CampoTesto);
    
    # Invio del messaggio a tutti i docenti
    INSERT INTO RICEZIONEDOCENTE VALUES (IDMess, titoloTestTemp, emailDocenteTemp);
    
    # Aggiornamento tabella INVIOSTUDENTE
    INSERT INTO INVIOSTUDENTE VALUES(IDMess, titoloTestTemp, emailStudenteTemp);

END
// DELIMITER ;











# solo per docente
DELIMITER //
CREATE PROCEDURE CreazioneTabellaEsercizio (
    IN nomeTabella VARCHAR(20),
    IN dataCreazione DATETIME,
    IN numRighe INT,
    IN emailDocente VARCHAR(40)
)
BEGIN

# controllo che la tabella non esista già e che esista il docente
DECLARE tabellaNonEsistente INT DEFAULT 0;
DECLARE docenteEsistente INT DEFAULT 0;
SET tabellaNonEsistente = ( SELECT COUNT(*) FROM TABELLADIESERCIZIO WHERE (nomeTabella=TABELLADIESERCIZIO.Nome) );
SET docenteEsistente = ( SELECT COUNT(*) FROM DOCENTE WHERE (emailDocente = DOCENTE.Email) );

# se non esiste la tabella ed esiste il docente la inserisco
IF (TabellaNonEsistente = 0 AND docenteEsistente=1) THEN 
INSERT INTO TABELLADIESERCIZIO VALUES(NomeTabella, dataCreazione, numRighe, emailDocente);
END IF;

END
// DELIMITER ;




DELIMITER //
CREATE PROCEDURE ModificaVisualizzazioneRisposte (
    IN TitoloTest_t VARCHAR(50),
    IN Valore_t BOOLEAN
)
BEGIN
    # Imposta il campo VisualizzaRisposte al valore specificato per il test specificato
    UPDATE Test SET VisualizzaRisposte = Valore_t WHERE Titolo = TitoloTest_t;
END 
// DELIMITER ;




# solo per docente 
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
    
# se il docente esiste, e il test non esiste, inserisce i dati
IF (docenteEsistente = 1 AND TestNonEsistente = 0) THEN
	INSERT INTO TEST VALUES (TitoloTest, DataCreazione, Foto, VisualizzaRisposte, EmailDocente);
END IF;

END 
// DELIMITER ;


# solo per docente
// DELIMITER 
CREATE PROCEDURE CreazioneQuesitoRispostaChiusa (
    IN TitoloTest_t VARCHAR(20),
    IN LivelloDifficolta_t ENUM("Basso","Medio","Alto"),
    IN Descrizione_t VARCHAR(50),
    IN NumeroRisposte_t INT
)
BEGIN

DECLARE TestEsistente INT DEFAULT 0;
SET TestEsistente = (SELECT COUNT(*) FROM TEST WHERE (TitoloTest=TEST.Titolo));

IF (TestEsistente = 1) THEN
INSERT INTO QUESITO(TitoloTest, LivelloDifficolta, Descrizione, NumeroRisposte) 
VALUES (TitoloTest_t, LivelloDifficolta_t, Descrizione_t, NumeroRisposte_t);
INSERT INTO QUESITORISPOSTACHIUSA(TitoloTest) VALUES (TitoloTest_t);
END IF;

END 
// DELIMITER ;



# solo per docente
// DELIMITER 
CREATE PROCEDURE CreazioneQuesitoCodice (
    IN TitoloTest_t VARCHAR(20),
    IN LivelloDifficolta_t ENUM("Basso","Medio","Alto"),
    IN Descrizione_t VARCHAR(50),
    IN NumeroRisposte_t INT
)
BEGIN

DECLARE TestEsistente INT DEFAULT 0;
SET TestEsistente = (SELECT COUNT(*) FROM TEST WHERE (TitoloTest=TEST.Titolo));

IF (TestEsistente = 1) THEN
INSERT INTO QUESITO(TitoloTest, LivelloDifficolta, Descrizione, NumeroRisposte) 
VALUES (TitoloTest_t, LivelloDifficolta_t, Descrizione_t, NumeroRisposte_t);
INSERT INTO QUESITOCODICE(TitoloTest) VALUES (TitoloTest_t);
END IF;

END 
// DELIMITER ;



# solo per docente
// DELIMITER 
CREATE PROCEDURE InserimentoSoluzione (
    IN TitoloTest_t VARCHAR(20),
    IN TestoSoluzione_t VARCHAR(40)
)
BEGIN

DECLARE TestEsistente INT DEFAULT 0;
SET TestEsistente = (SELECT COUNT(*) FROM TEST WHERE (TitoloTest=TEST.Titolo));

IF (TestEsistente = 1) THEN
INSERT INTO SOLUZIONE(TitoloTest, TestoSoluzione) VALUES (TitoloTest_t, TestoSoluzione_t);
END IF;

END 
// DELIMITER ;




# solo per docente
// DELIMITER 
CREATE PROCEDURE InserimentoOpzioneRisposta (
    IN TitoloTest_t VARCHAR(20),
    IN NumeroProgressivoQuesito_t INT,
    IN NumeroProgressivoOpzione_t INT,
    IN CampoTesto_t VARCHAR(2000),
)
BEGIN

DECLARE TestEsistente INT DEFAULT 0;
DECLARE ProgressivoQuesitoEsistente INT DEFAULT 0;
DECLARE ProgressiviETestEsistenti INT DEFAULT 0;
SET TestEsistente = (SELECT COUNT(*) FROM TEST WHERE (TitoloTest=TEST.Titolo));
SET ProgressivoQuesitoEsistente = (SELECT COUNT(*) FROM QUESITORISPOSTACHIUSA WHERE (NumeroProgressivoQuesito_t=NumeroProgressivo));
SET ProgressivoETestEsistenti = (SELECT COUNT(*) INTO ProgressiviETestEsistenti FROM OPZIONERISPOSTA WHERE TitoloTest = TitoloTest_t 
									AND NumeroProgressivoQuesito = NumeroProgressivoQuesito_t 
                                    AND NumeroProgressivoOpzione = NumeroProgressivoOpzione_t;);

IF (TestEsistente = 1 AND ProgressivoQuesitoEsistente = 1 AND ProgressivoETestEsistenti = 0) THEN
INSERT INTO OPZIONERISPOSTA(TitoloTest, NumeroProgressivoQuesito, NumeroProgressivoOpzione, CampoTesto) 
VALUES (TitoloTest_t, NumeroProgressivoQuesito_t, NumeroProgressivoOpzione_t, CampoTesto_t);
END IF;

END 
// DELIMITER ;




# solo per docente
// DELIMITER 
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
SET TestEsistente = (SELECT COUNT(*) FROM TEST WHERE (TitoloTest=TEST.Titolo));
SET DocenteEsistente = ( SELECT COUNT(*) FROM DOCENTE WHERE (EmailDocente=DOCENTE.Email));

IF (TestEsistente = 1 AND DocenteEsistente = 1) THEN

# Inserisce il messaggio nella tabella MESSAGGIO
INSERT INTO MESSAGGIO (TitoloTest, TitoloMessaggio, CampoTesto, Data) VALUES (TitoloTest_t, TitoloMessaggio_t, CampoTesto_t, Data_t);

# Ottiene l'ID del messaggio appena inserito
SELECT LAST_INSERT_ID() INTO IdMessaggio;

# Inserisce il messaggio nella tabella INVIODOCENTE per ogni docente
INSERT INTO INVIODOCENTE (Id, TitoloTest, EmailDocenteMittente) VALUES (IdMessaggio, TitoloTest_t, EmailDocenteMittente_t)

# DA AGGIUNGERE TUTTE LE RICEZIONI DEGLI STUDENTI -> COME FACCIO A METTERLI TUTTI ?

END IF;

END
// DELIMITER ;



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





# AREA PER I TEST


/*
# Test inserisciRisposta e visualizzaEsito e inserisciMessaggioStudente

INSERT INTO DOCENTE VALUES("docente@gmail.com","ciao","nano", 1234589, "scienze", "corso");
INSERT INTO STUDENTE VALUES("studente@gmail.com", "nano", "ciao", 123456789, 2010, 1234567891234567);
INSERT INTO STUDENTE VALUES("studente2@gmail.com", "nano", "ciao", 3333, 2010, 2234567891234567);
INSERT INTO TEST VALUES("provaNr1", '2024-02-07 14:30:00', NULL ,true, "docente@gmail.com");
INSERT INTO COMPLETAMENTO VALUES("Aperto", "provaNr1", "studente@gmail.com", NULL, NULL);
INSERT INTO COMPLETAMENTO VALUES("Aperto", "provaNr1", "studente2@gmail.com", NULL, NULL);
INSERT INTO QUESITO VALUES(1,"provaNr1","Basso", "testo quesito di codice", 3);
INSERT INTO QUESITO VALUES(2,"provaNr1","Basso", "testo quesito a scleta", 3);
INSERT INTO QUESITOCODICE VALUES(1, "provaNr1");
INSERT INTO SOLUZIONE VALUES(1, "provaNr1","rispostaCorretta");
INSERT INTO QUESITORISPOSTACHIUSA VALUES(2, "provaNr1");
INSERT INTO OPZIONERISPOSTA VALUES("provaNr1",2,2,"rispostaCorretta");
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

CALL inserisciRisposta("Aperto","provaNr1","studente@gmail.com", "rispostaCorretta", 2);
CALL inserisciRisposta("Aperto","provaNr1","studente2@gmail.com", "rispostaNonCorretta", 1);

CALL visualizzaEsitoRisposta('Aperto', 'provaNr1', 'studente@gmail.com', 2, @esitoRispostaScelta);
SELECT @esitoRispostaScelta;

CALL visualizzaEsitoRisposta('Aperto', 'provaNr1', 'studente@gmail.com', 1, @esitoRispostaCodice);
SELECT @esitoRispostaCodice;

CALL inserisciMessaggioStudente("studente@gmail.com", "docente@gmail.com", "provaNr1", "titoloMessaggio", "Argomento del messaggio");

# Fine test
*/



# Test classificaTestCompletati
INSERT INTO DOCENTE VALUES("docente@gmail.com","ciao","nano", 1234589, "scienze", "corso");
INSERT INTO TEST VALUES("provaNr1", '2024-02-07 14:30:00', NULL ,true, "docente@gmail.com");
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


#Fine test


/*
# Test per Trigger testConclusoVisualizzaRisposte
UPDATE TEST
SET VisualizzaRisposte = TRUE
WHERE Titolo = 'provaNr2';

# Fine Test
*/

/*
# Test per Trigger incrementaNumRighe
INSERT INTO TABELLADIESERCIZIO VALUES ("TabellaNR1",NOW(), 0, 'docente@gmail.com');
INSERT INTO RIGA VALUES("primariga","TabellaNR1");
INSERT INTO RIGA VALUES("secondariga","TabellaNR1");
INSERT INTO RIGA VALUES("terzariga","TabellaNR1");

INSERT INTO TABELLADIESERCIZIO VALUES ("TabellaNR2",NOW(), 0, 'docente@gmail.com');
INSERT INTO RIGA VALUES("primariga","TabellaNR2");
INSERT INTO RIGA VALUES("secondariga","TabellaNR2");
# Fine Test
*/



