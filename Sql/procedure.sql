USE ESQL;

DROP PROCEDURE IF EXISTS VisualizzaDocenti;
DROP PROCEDURE IF EXISTS VisualizzaTestDisponibili;
DROP PROCEDURE IF EXISTS VisualizzaQuesitiPerTest;
DROP PROCEDURE IF EXISTS AutenticazioneDocente;
DROP PROCEDURE IF EXISTS AutenticazioneStudente;
DROP PROCEDURE IF EXISTS RegistrazioneDocente;
DROP PROCEDURE IF EXISTS RegistrazioneStudente;
DROP PROCEDURE IF EXISTS CreazioneTabellaEsercizio;
DROP PROCEDURE IF EXISTS ModificaVisualizzazioneRisposte;
DROP PROCEDURE IF EXISTS CreazioneTest;
DROP PROCEDURE IF EXISTS CreazioneQuesitoRispostaChiusa;
DROP PROCEDURE IF EXISTS CreazioneQuesitoCodice;
DROP PROCEDURE IF EXISTS CreazioneCostituzione;
DROP PROCEDURE IF EXISTS InserimentoSoluzione;
DROP PROCEDURE IF EXISTS InserimentoOpzioneRisposta;
DROP PROCEDURE IF EXISTS SetOpzioneRispostaCorretta;
DROP PROCEDURE IF EXISTS InserimentoMessaggioDocente;
DROP PROCEDURE IF EXISTS inserisciRispostaQuesitoCodice;
DROP PROCEDURE IF EXISTS inserisciRispostaQuesitoRispostaChiusa;
DROP PROCEDURE IF EXISTS visualizzaEsitoRisposta;
DROP PROCEDURE IF EXISTS inserisciMessaggioStudente;
DROP PROCEDURE IF EXISTS eliminaTest;
DROP PROCEDURE IF EXISTS eliminaQuesito;
DROP PROCEDURE IF EXISTS verificaPresenzaCollegamento;
DROP PROCEDURE IF EXISTS ricezioneMessaggiStudente;



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
    IN TitoloTestTemp VARCHAR(100)
    )
BEGIN
    -- Seleziona i quesiti corrispondenti al titolo del test specificato
    SELECT * FROM QUESITO WHERE TitoloTest = TitoloTestTemp;
END //
DELIMITER ;


DELIMITER //
CREATE PROCEDURE AutenticazioneDocente (
    IN EmailTemp VARCHAR(100),
    IN PasswordTemp VARCHAR(20),
    OUT AutenticatoTemp BOOLEAN
)
BEGIN
    -- Verifica se l'email esiste nella tabella Utenti e corrisponde alla password fornita
    IF EXISTS (SELECT * FROM DOCENTE WHERE Email = EmailTemp AND PasswordDocente = PasswordTemp) THEN
        SET AutenticatoTemp = TRUE;
    ELSE
        SET AutenticatoTemp = FALSE;
    END IF;
END //
DELIMITER ;


DELIMITER //
CREATE PROCEDURE AutenticazioneStudente (
    IN EmailTemp VARCHAR(100),
    IN PasswordTemp VARCHAR(20),
    OUT AutenticatoTemp BOOLEAN
)
BEGIN
    -- Verifica se l'email esiste nella tabella Utenti e corrisponde alla password fornita
    IF EXISTS (SELECT * FROM STUDENTE WHERE Email = EmailTemp AND PasswordStudente = PasswordTemp) THEN
        SET AutenticatoTemp = TRUE;
    ELSE
        SET AutenticatoTemp = FALSE;
    END IF;
END //
DELIMITER ;


DELIMITER //
CREATE PROCEDURE RegistrazioneDocente (
    IN EmailTemp VARCHAR(100),
    IN PasswordTemp VARCHAR(20),
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
        INSERT INTO Docente (Email,PasswordDocente,Nome,Cognome,RecapitoTelefonicoDocente,NomeDipartimento,NomeCorso) VALUES (EmailTemp,PasswordTemp,Nome,Cognome,RecapitoTelefonicoDocente,NomeDipartimento,NomeCorso);
    ELSE
     -- Se l'email esiste già, restituisci un messaggio di errore
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "L\'email inserita è già presente nella tabella Docente";
    END IF;
END //
DELIMITER ;

DELIMITER //
CREATE PROCEDURE RegistrazioneStudente (
    IN EmailTemp VARCHAR(100),
    IN PasswordTemp VARCHAR(20),
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
        INSERT INTO STUDENTE (Email,PasswordStudente,Nome,Cognome,RecapitoTelefonicoStudente,AnnoImmatricolazione,CodiceAlfaNumerico) VALUES (EmailTemp,PasswordTemp,Nome,Cognome,RecapitoTelefonicoStudente,AnnoImmatricolazione,CodiceAlfaNumerico);
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
    IN emailDocente VARCHAR(100)
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
    IN TitoloTestTemp VARCHAR(100),
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
    IN TitoloTest VARCHAR(100),
    IN DataCreazione datetime,
    IN Foto VARCHAR(100),
    IN VisualizzaRisposte BOOLEAN,
    IN EmailDocente VARCHAR(100)
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
    IN TitoloTestTemp VARCHAR(100),
    IN LivelloDifficoltaTemp ENUM("Basso","Medio","Alto"),
    IN DescrizioneTemp VARCHAR(255),
    OUT numProgressivo INT
)
BEGIN
    DECLARE TestEsistente INT DEFAULT 0;
    DECLARE UltimoNumeroProgressivo INT;
    SET TestEsistente = (SELECT COUNT(*) FROM TEST WHERE Titolo = TitoloTestTemp);
    IF (TestEsistente = 1) THEN
        INSERT INTO QUESITO(TitoloTest, LivelloDifficolta, Descrizione, NumeroRisposte) 
        VALUES (TitoloTestTemp, LivelloDifficoltaTemp, DescrizioneTemp, 0);

        SET UltimoNumeroProgressivo = (SELECT MAX(NumeroProgressivo) FROM QUESITO WHERE TitoloTest = TitoloTestTemp);
        INSERT INTO QUESITORISPOSTACHIUSA(NumeroProgressivo, TitoloTest) VALUES (UltimoNumeroProgressivo, TitoloTestTemp);
        
        SET numProgressivo = UltimoNumeroProgressivo;
    END IF;
    
END //
DELIMITER ;



-- OK
DELIMITER //
CREATE PROCEDURE CreazioneQuesitoCodice (
    IN TitoloTestTemp VARCHAR(100),
    IN LivelloDifficoltaTemp ENUM("Basso","Medio","Alto"),
    IN DescrizioneTemp VARCHAR(255),
    OUT numProgressivo INT
)
BEGIN
	DECLARE UltimoNumeroProgressivo INT;
	DECLARE TestEsistente INT DEFAULT 0;
	SET TestEsistente = (SELECT COUNT(*) FROM TEST WHERE (TitoloTestTemp = Titolo));

	IF (TestEsistente = 1) THEN
		INSERT INTO QUESITO(TitoloTest, LivelloDifficolta, Descrizione, NumeroRisposte) 
		VALUES (TitoloTestTemp, LivelloDifficoltaTemp, DescrizioneTemp, 0);
        
        SET UltimoNumeroProgressivo = (SELECT MAX(NumeroProgressivo) FROM QUESITO WHERE TitoloTest = TitoloTestTemp);
		INSERT INTO QUESITOCODICE(TitoloTest,NumeroProgressivo) VALUES (TitoloTestTemp, UltimoNumeroProgressivo);
        
        SET numProgressivo = UltimoNumeroProgressivo;
	END IF;
    
END 
// DELIMITER ;

DELIMITER //
CREATE PROCEDURE CreazioneCostituzione(
    IN numero_temp_progressivoQuesito INT,
    IN titoloTest_temp VARCHAR(100),
    IN nome_temp_tabellaEsercizio VARCHAR(40)
)
BEGIN
	DECLARE TabellaEsistente INT DEFAULT 0;
    DECLARE QuesitoEsistente INT DEFAULT 0;
    SET TabellaEsistente = (SELECT COUNT(*) FROM TABELLADIESERCIZIO WHERE (nome=nome_temp_tabellaEsercizio));
    SET QuesitoEsistente = (SELECT COUNT(*) FROM QUESITO WHERE (numeroProgressivo=numero_temp_progressivoQuesito) 
															AND (titoloTest=titoloTest_temp));
	IF (TabellaEsistente = 1 AND QuesitoEsistente = 1) THEN
		INSERT INTO COSTITUZIONE(TitoloTest, NumeroProgressivoQuesito, NomeTabella) 
        VALUES (titoloTest_temp, numero_temp_progressivoQuesito, nome_temp_tabellaEsercizio);
    END IF;
END
// DELIMITER ;
# Faccio mettere in input il progressivo al docente, dovrà essere visibile nel programma
-- OK
DELIMITER //
CREATE PROCEDURE InserimentoSoluzione (
    IN TitoloTestTemp VARCHAR(100),
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
    IN TitoloTestTemp VARCHAR(100),
    IN NumeroProgressivoQuesitoTemp INT,
    IN CampoTestoTemp VARCHAR(2000),
    IN RispostaCorrettaTemp BOOLEAN
)
BEGIN
    DECLARE ProgressivoQuesitoETestEsistente INT DEFAULT 0;

	SET ProgressivoQuesitoETestEsistente = (SELECT COUNT(*) FROM QUESITORISPOSTACHIUSA 
    WHERE (TitoloTestTemp=TitoloTest AND NumeroProgressivo = NumeroProgressivoQuesitoTemp));

    IF (ProgressivoQuesitoETestEsistente = 1) THEN
        INSERT INTO OPZIONERISPOSTA(TitoloTest, NumeroProgressivoQuesito, CampoTesto, RispostaCorretta) 
        VALUES (TitoloTestTemp, NumeroProgressivoQuesitoTemp, CampoTestoTemp, RispostaCorrettaTemp);
    END IF;
END //
DELIMITER ;



DELIMITER //
CREATE PROCEDURE SetOpzioneRispostaCorretta (
    IN TitoloTestTemp VARCHAR(100),
    IN NumeroProgressivoQuesitoTemp INT,
    IN CampoTestoTemp VARCHAR(40)
)
BEGIN
	UPDATE OPZIONERISPOSTA
    SET rispostaCorretta = true
    WHERE ((TitoloTestTemp=TitoloTest) AND (NumeroProgressivoQuesitoTemp=NumeroProgressivoQuesito)
		AND (CampoTestoTemp=CampoTesto));
    
END 
// DELIMITER ;




-- OK
DELIMITER //
CREATE PROCEDURE InserimentoMessaggioDocente(
    IN TitoloTest_t VARCHAR(100),
    IN TitoloMessaggio_t VARCHAR(20),
    IN CampoTesto_t VARCHAR(500),
    IN Data_t DATETIME,
    IN EmailDocenteMittente_t VARCHAR(100)
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
DELIMITER //


# aggiungere parte di controllo
CREATE PROCEDURE inserisciRispostaQuesitoCodice(
    IN idCompletamentoTemp INT,
    IN TitoloTestTemp VARCHAR(100),
    IN valoreRispostaTemp VARCHAR(2000),
    IN numeroQuesitoTemp INT,
    IN esitoRisposta BOOLEAN
)
BEGIN

    DECLARE numRispostaAperta INT;
    DECLARE num_risposte INT;
    

	-- Controlla se esiste il quesito
    SELECT COUNT(*) INTO numRispostaAperta
    FROM QUESITOCODICE AS QC
    WHERE (QC.NumeroProgressivo = numeroQuesitoTemp) AND (QC.TitoloTest IN (SELECT C1.TitoloTest
                                                                      FROM COMPLETAMENTO AS C1
                                                                      WHERE (idCompletamentoTemp = C1.NumeroProgressivo)));

    

    IF numRispostaAperta = 1 THEN
		
        


        -- Controllo se è già presente una risposta al quesito
        SELECT COUNT(*) INTO num_risposte
        FROM RISPOSTAQUESITOCODICE
        WHERE NumeroProgressivoCompletamento = idCompletamentoTemp AND TitoloTest = TitoloTestTemp AND NumeroProgressivoQuesito = numeroQuesitoTemp;
        
       

        IF (num_risposte = 0) THEN
            INSERT INTO RISPOSTAQUESITOCODICE(NumeroProgressivoCompletamento, TitoloTest, Testo, NumeroProgressivoQuesito, Esito) 
            VALUES (idCompletamentoTemp, TitoloTestTemp, valoreRispostaTemp, numeroQuesitoTemp, esitoRisposta);
            
            
        ELSE 
            UPDATE RISPOSTAQUESITOCODICE
            SET Testo = valoreRispostaTemp, Esito = esitoRisposta
            WHERE NumeroProgressivoCompletamento = idCompletamentoTemp
            AND TitoloTest = TitoloTestTemp
            AND NumeroProgressivoQuesito = numeroQuesitoTemp;
            
            
        END IF;

    END IF;
   
END//

DELIMITER ;


DELIMITER //

CREATE PROCEDURE inserisciRispostaQuesitoRispostaChiusa(
    IN idCompletamentoTemp INT,
    IN TitoloTestTemp VARCHAR(100),
    IN valoreRispostaTemp VARCHAR(2000),
    IN numeroQuesitoTemp INT
)
BEGIN
    DECLARE numRispostaChiusa INT;
    DECLARE esitoRisposta BOOLEAN;
    DECLARE rispostaCorretta VARCHAR(40);
    DECLARE num_risposte INT;
    

    
    
    -- Controlla se esiste la risposta
    SELECT COUNT(*) INTO numRispostaChiusa
    FROM QUESITORISPOSTACHIUSA AS QC
    WHERE (QC.NumeroProgressivo = numeroQuesitoTemp) AND (QC.TitoloTest IN (SELECT C1.TitoloTest
                                                                      FROM COMPLETAMENTO AS C1
                                                                      WHERE (idCompletamentoTemp = C1.NumeroProgressivo)));
    
    
    
     IF (numRispostaChiusa = 1) THEN
        SET esitoRisposta = FALSE;
	

        SELECT CampoTesto INTO rispostaCorretta
        FROM OPZIONERISPOSTA AS OP
        WHERE (OP.RispostaCorretta = TRUE) AND (OP.NumeroProgressivoQuesito = numeroQuesitoTemp) AND (OP.TitoloTest IN (SELECT C1.TitoloTest
                                                                                    FROM COMPLETAMENTO AS C1
                                                                                    WHERE (idCompletamentoTemp = C1.NumeroProgressivo)));
                        
        IF (valoreRispostaTemp = rispostaCorretta) THEN
            SET esitoRisposta = TRUE;
        END IF;
        
        
        
        -- Controllo se è già presente una risposta al quesito
        SELECT COUNT(*) INTO num_risposte
        FROM RISPOSTAQUESITORISPOSTACHIUSA
        WHERE NumeroProgressivoCompletamento = idCompletamentoTemp AND TitoloTest = TitoloTestTemp AND NumeroProgressivoQuesito = numeroQuesitoTemp;
        
        
        
        IF (num_risposte = 0) THEN
            INSERT INTO RISPOSTAQUESITORISPOSTACHIUSA(NumeroProgressivoCompletamento, TitoloTest, OpzioneScelta, NumeroProgressivoQuesito, Esito) 
            VALUES (idCompletamentoTemp, TitoloTestTemp, valoreRispostaTemp, numeroQuesitoTemp, esitoRisposta);
            
            
        ELSE 
            UPDATE RISPOSTAQUESITORISPOSTACHIUSA
            SET OpzioneScelta = valoreRispostaTemp, Esito = esitoRisposta
            WHERE NumeroProgressivoCompletamento = idCompletamentoTemp
            AND TitoloTest = TitoloTestTemp
            AND NumeroProgressivoQuesito = numeroQuesitoTemp;
            
            
        END IF;

      END IF;   
END

//
DELIMITER ;


DELIMITER //

CREATE PROCEDURE visualizzaEsitoRisposta(
    IN idCompletamentoTemp INT,
    IN TitoloTestTemp VARCHAR(100),
    IN numQuesito INT,
    OUT esitoRisposta BOOLEAN
)
BEGIN
    DECLARE esitoTemp BOOLEAN;

    

    -- Verifica se esiste una risposta per il quesito di tipo "Risposta Chiusa"
    IF EXISTS (
        SELECT 1 
        FROM RISPOSTAQUESITORISPOSTACHIUSA AS RC 
        WHERE RC.NumeroProgressivoQuesito = numQuesito AND RC.TitoloTest = TitoloTestTemp AND RC.NumeroProgressivoCompletamento = idCompletamentoTemp
    ) THEN
        

        SELECT esito INTO esitoTemp
        FROM RISPOSTAQUESITORISPOSTACHIUSA AS RC
        WHERE RC.NumeroProgressivoQuesito = numQuesito AND RC.TitoloTest = TitoloTestTemp AND  RC.NumeroProgressivoCompletamento = idCompletamentoTemp
        LIMIT 1; -- Assicura che venga restituita una sola riga

        
    END IF;

    -- Verifica se esiste una risposta per il quesito di tipo "Codice"
    IF EXISTS (
        SELECT 1 
        FROM RISPOSTAQUESITOCODICE AS QC 
        WHERE QC.NumeroProgressivoQuesito = numQuesito AND QC.TitoloTest = TitoloTestTemp AND QC.NumeroProgressivoCompletamento = idCompletamentoTemp
    ) THEN
        

        SELECT esito INTO esitoTemp
        FROM RISPOSTAQUESITOCODICE AS QC
        WHERE QC.NumeroProgressivoQuesito = numQuesito AND QC.TitoloTest = TitoloTestTemp AND  QC.NumeroProgressivoCompletamento = idCompletamentoTemp
        LIMIT 1; -- Assicura che venga restituita una sola riga

        
    END IF;

    -- Imposta il valore di esitoRisposta in base al valore di esitoTemp
    IF esitoTemp IS NOT NULL THEN
        SET esitoRisposta = esitoTemp;
    ELSE
        SET esitoRisposta = NULL;
    END IF;

END//

DELIMITER ;





DELIMITER //
CREATE PROCEDURE inserisciMessaggioStudente(
	IN emailStudenteTemp VARCHAR(100),
    IN emailDocenteTemp VARCHAR(100),
    IN titoloTestTemp VARCHAR(100),
    IN titoloMess VARCHAR(20),
    IN testoMess VARCHAR(500)
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

DELIMITER //
CREATE PROCEDURE eliminaTest(
    IN titoloTest VARCHAR(100)
)
BEGIN
    DELETE FROM TEST WHERE Titolo = titoloTest;
END
// DELIMITER ;

DELIMITER //
CREATE PROCEDURE eliminaQuesito(
    IN titoloTestTemp VARCHAR(20),
    IN numeroProgressivoTemp INT
)
BEGIN
    DELETE FROM QUESITO WHERE TitoloTest = titoloTestTemp AND NumeroProgressivo = numeroProgressivoTemp;
END
// DELIMITER ;

DELIMITER //
CREATE PROCEDURE verificaPresenzaCollegamento(
    IN titoloTestTemp VARCHAR(100),
    IN numeroProgressivoTemp INT,
    OUT presente BOOLEAN
)
BEGIN
    SELECT COUNT(*) INTO presente FROM COSTITUZIONE WHERE TitoloTest = titoloTestTemp AND NumeroProgressivoQuesito = numeroProgressivoTemp;
END
// DELIMITER ;

DELIMITER //
CREATE PROCEDURE ricezioneMessaggiStudente(
    IN emailStudenteTemp VARCHAR(100)
)
BEGIN
    SELECT * 
    FROM messaggio as M, ricezionestudente as S
    WHERE (M.Id = S.Id) AND (emailStudenteTemp = S.EmailStudenteDestinatario) AND (M.TitoloTest=S.TitoloTest);
END
// DELIMITER ;