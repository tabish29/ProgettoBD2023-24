USE ESQL;


DROP TRIGGER IF EXISTS cambio_stato_incompletamento_rispostaquesitorispostachiusa;
DROP TRIGGER IF EXISTS cambio_stato_incompletamento_rispostaquesitocodice;
DROP TRIGGER IF EXISTS cambio_stato_incompletamento_rispostaquesitoRC_conUpd;
DROP TRIGGER IF EXISTS cambio_stato_incompletamento_rispostaQC_conUpd;
DROP TRIGGER IF EXISTS cambio_stato_concluso_rispostaquesitoRC_Agg;
DROP TRIGGER IF EXISTS cambio_stato_concluso_rispostaQuesitoC_Agg;
DROP TRIGGER IF EXISTS cambio_stato_concluso_rispostaquesitorispostachiusa;
DROP TRIGGER IF EXISTS cambio_stato_concluso_rispostaquesitocodice;
DROP TRIGGER IF EXISTS testConclusoVisualizzaRisposte;
DROP TRIGGER IF EXISTS incrementaNumRighe;
DROP TRIGGER IF EXISTS decrementaNumRighe;
DROP TRIGGER IF EXISTS AggiornaNumeroRisposteQuesitoAfterInsert;
DROP TRIGGER IF EXISTS AggiornaNumeroRisposteQuesitoAfterDelete;
DROP TRIGGER IF EXISTS AggiornaNumeroRisposteQuesitoCodiceAfterInsert;
DROP TRIGGER IF EXISTS AggiornaNumeroRisposteQuesitoCodiceAfterDelete;
DROP TRIGGER IF EXISTS spaziTitoloTest;

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
    IF (num_risposte_inserite >= 1) THEN
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
    IF (num_risposte_inserite >= 1) THEN
        UPDATE COMPLETAMENTO
        SET Stato = 'InCompletamento'
        WHERE TitoloTest = NEW.TitoloTest AND NumeroProgressivo = NEW.NumeroProgressivoCompletamento;
    END IF;
END;
// DELIMITER ;


DELIMITER //
CREATE TRIGGER cambio_stato_incompletamento_rispostaquesitoRC_conUpd
AFTER UPDATE ON RISPOSTAQUESITORISPOSTACHIUSA  
FOR EACH ROW
BEGIN
    DECLARE num_risposte_inserite INT;

    -- Conta quante risposte sono state inserite per lo studente per il test corrente
    SET num_risposte_inserite = (SELECT COUNT(*) FROM RISPOSTAQUESITORISPOSTACHIUSA
		WHERE (TitoloTest = NEW.TitoloTest AND NumeroProgressivoCompletamento = NEW.NumeroProgressivoCompletamento));

    -- Se il numero di risposte inserite è uguale a 1, cambia lo stato del test in 'InCompletamento'
    IF (num_risposte_inserite >= 1) THEN
        UPDATE COMPLETAMENTO
        SET Stato = 'InCompletamento'
        WHERE TitoloTest = NEW.TitoloTest AND NumeroProgressivo = NEW.NumeroProgressivoCompletamento;
    END IF;
END
// DELIMITER ;



DELIMITER //
CREATE TRIGGER cambio_stato_incompletamento_rispostaQC_conUpd
AFTER UPDATE ON RISPOSTAQUESITOCODICE 
FOR EACH ROW
BEGIN
    DECLARE num_risposte_inserite INT;

    -- Conta quante risposte sono state inserite per lo studente per il test corrente
    SET num_risposte_inserite = (SELECT COUNT(*) FROM RISPOSTAQUESITOCODICE
		WHERE TitoloTest = NEW.TitoloTest AND NumeroProgressivoCompletamento = NEW.NumeroProgressivoCompletamento);

    -- Se il numero di risposte inserite è uguale a 1, cambia lo stato del test in 'InCompletamento'
    IF (num_risposte_inserite >= 1) THEN
        UPDATE COMPLETAMENTO
        SET Stato = 'InCompletamento'
        WHERE TitoloTest = NEW.TitoloTest AND NumeroProgressivo = NEW.NumeroProgressivoCompletamento;
    END IF;
END;
// DELIMITER ;



DELIMITER //
CREATE TRIGGER cambio_stato_concluso_rispostaquesitoRC_Agg
AFTER UPDATE ON RISPOSTAQUESITORISPOSTACHIUSA 
FOR EACH ROW
BEGIN
    DECLARE num_quesiti_totali INT;
    DECLARE num_risposte_inserite_RC INT;
    DECLARE num_risposte_inserite_codice INT;
    DECLARE num_risposte_inserite INT;
    DECLARE num_risposte_corrette_RC INT;
    DECLARE num_risposte_corrette_codice INT;
    DECLARE num_risposte_corrette INT;
    DECLARE num_progressivo_completamento INT;
    
    -- Ottieni il numero progressivo di completamento
    SET num_progressivo_completamento = NEW.NumeroProgressivoCompletamento;

    
    -- Conta il numero totale di quesiti per il test
    SET num_quesiti_totali = (SELECT COUNT(*) FROM QUESITO
		WHERE TitoloTest = NEW.TitoloTest);

    -- Conta il numero di risposte inserite per il test
    SET num_risposte_inserite_RC = (SELECT COUNT(*) FROM RISPOSTAQUESITORISPOSTACHIUSA
		WHERE NumeroProgressivoCompletamento = num_progressivo_completamento);

    SET num_risposte_inserite_codice = (SELECT COUNT(*) FROM RISPOSTAQUESITOCODICE
        WHERE NumeroProgressivoCompletamento = num_progressivo_completamento);
    
    SET num_risposte_inserite = num_risposte_inserite_codice + num_risposte_inserite_RC;

    -- Conta il numero di risposte corrette per il test
    SET num_risposte_corrette_RC = (SELECT COUNT(*) FROM RISPOSTAQUESITORISPOSTACHIUSA
		WHERE NumeroProgressivoCompletamento = num_progressivo_completamento AND Esito = TRUE);

    -- Conta il numero di risposte corrette per il test
    SET num_risposte_corrette_codice = (SELECT COUNT(*) FROM RISPOSTAQUESITOCODICE
		WHERE NumeroProgressivoCompletamento = num_progressivo_completamento AND Esito = TRUE);

    SET num_risposte_corrette = num_risposte_corrette_codice + num_risposte_corrette_RC;

    -- Se tutte le risposte sono state inserite e hanno esito True, il test diventa Concluso
    IF (num_risposte_inserite = num_quesiti_totali AND num_risposte_corrette = num_quesiti_totali) THEN
        UPDATE COMPLETAMENTO
        SET Stato = 'Concluso'
        WHERE NumeroProgressivo = num_progressivo_completamento;
    END IF;
END;
// DELIMITER ;



DELIMITER //
CREATE TRIGGER cambio_stato_concluso_rispostaQuesitoC_Agg
AFTER UPDATE ON RISPOSTAQUESITOCODICE
FOR EACH ROW
BEGIN
    DECLARE num_quesiti_totali INT;
    DECLARE num_risposte_inserite_RC INT;
    DECLARE num_risposte_inserite_codice INT;
    DECLARE num_risposte_inserite INT;
    DECLARE num_risposte_corrette_RC INT;
    DECLARE num_risposte_corrette_codice INT;
    DECLARE num_risposte_corrette INT;
    DECLARE num_progressivo_completamento INT;
    
    -- Ottiene il numero progressivo di completamento
    SET num_progressivo_completamento = NEW.NumeroProgressivoCompletamento;

    -- Conta il numero totale di quesiti per il test
    SET num_quesiti_totali = (SELECT COUNT(*) FROM QUESITO
		WHERE TitoloTest = NEW.TitoloTest);

    -- Conta il numero di risposte inserite per il test
    SET num_risposte_inserite_RC = (SELECT COUNT(*) FROM RISPOSTAQUESITORISPOSTACHIUSA
		WHERE NumeroProgressivoCompletamento = num_progressivo_completamento);

    SET num_risposte_inserite_codice = (SELECT COUNT(*) FROM RISPOSTAQUESITOCODICE
        WHERE NumeroProgressivoCompletamento = num_progressivo_completamento);
    
    SET num_risposte_inserite = num_risposte_inserite_codice + num_risposte_inserite_RC;

    -- Conta il numero di risposte corrette per il test
    SET num_risposte_corrette_RC = (SELECT COUNT(*) FROM RISPOSTAQUESITORISPOSTACHIUSA
		WHERE NumeroProgressivoCompletamento = num_progressivo_completamento AND Esito = TRUE);

    -- Conta il numero di risposte corrette per il test
    SET num_risposte_corrette_codice = (SELECT COUNT(*) FROM RISPOSTAQUESITOCODICE
		WHERE NumeroProgressivoCompletamento = num_progressivo_completamento AND Esito = TRUE);

    SET num_risposte_corrette = num_risposte_corrette_codice + num_risposte_corrette_RC;

    -- Se tutte le risposte sono state inserite e hanno esito True, il test diventa Concluso
    IF (num_risposte_inserite = num_quesiti_totali AND num_risposte_corrette = num_quesiti_totali) THEN
        UPDATE COMPLETAMENTO
        SET Stato = 'Concluso'
        WHERE NumeroProgressivo = num_progressivo_completamento;
    END IF;
END;
// DELIMITER ;

DELIMITER //
CREATE TRIGGER cambio_stato_concluso_rispostaquesitorispostachiusa
AFTER INSERT ON RISPOSTAQUESITORISPOSTACHIUSA 
FOR EACH ROW
BEGIN
    DECLARE num_quesiti_totali INT;
    DECLARE num_risposte_inserite_RC INT;
    DECLARE num_risposte_inserite_codice INT;
    DECLARE num_risposte_inserite INT;
    DECLARE num_risposte_corrette_RC INT;
    DECLARE num_risposte_corrette_codice INT;
    DECLARE num_risposte_corrette INT;
    DECLARE num_progressivo_completamento INT;
    
    -- Ottiene il numero progressivo di completamento
    SET num_progressivo_completamento = NEW.NumeroProgressivoCompletamento;

    
    -- Conta il numero totale di quesiti per il test
    SET num_quesiti_totali = (SELECT COUNT(*) FROM QUESITO
		WHERE TitoloTest = NEW.TitoloTest);

    -- Conta il numero di risposte inserite per il test
    SET num_risposte_inserite_RC = (SELECT COUNT(*) FROM RISPOSTAQUESITORISPOSTACHIUSA
		WHERE NumeroProgressivoCompletamento = num_progressivo_completamento);

    SET num_risposte_inserite_codice = (SELECT COUNT(*) FROM RISPOSTAQUESITOCODICE
        WHERE NumeroProgressivoCompletamento = num_progressivo_completamento);
    
    SET num_risposte_inserite = num_risposte_inserite_codice + num_risposte_inserite_RC;

    -- Conta il numero di risposte corrette per il test
    SET num_risposte_corrette_RC = (SELECT COUNT(*) FROM RISPOSTAQUESITORISPOSTACHIUSA
		WHERE NumeroProgressivoCompletamento = num_progressivo_completamento AND Esito = TRUE);

    -- Conta il numero di risposte corrette per il test
    SET num_risposte_corrette_codice = (SELECT COUNT(*) FROM RISPOSTAQUESITOCODICE
		WHERE NumeroProgressivoCompletamento = num_progressivo_completamento AND Esito = TRUE);

    SET num_risposte_corrette = num_risposte_corrette_codice + num_risposte_corrette_RC;

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
    DECLARE num_risposte_inserite_RC INT;
    DECLARE num_risposte_inserite_codice INT;
    DECLARE num_risposte_inserite INT;
    DECLARE num_risposte_corrette_RC INT;
    DECLARE num_risposte_corrette_codice INT;
    DECLARE num_risposte_corrette INT;
    DECLARE num_progressivo_completamento INT;
    
    -- Ottieni il numero progressivo di completamento
    SET num_progressivo_completamento = NEW.NumeroProgressivoCompletamento;

    -- Conta il numero totale di quesiti per il test
    SET num_quesiti_totali = (SELECT COUNT(*) FROM QUESITO
		WHERE TitoloTest = NEW.TitoloTest);

    -- Conta il numero di risposte inserite per il test
    SET num_risposte_inserite_RC = (SELECT COUNT(*) FROM RISPOSTAQUESITORISPOSTACHIUSA
		WHERE NumeroProgressivoCompletamento = num_progressivo_completamento);

    SET num_risposte_inserite_codice = (SELECT COUNT(*) FROM RISPOSTAQUESITOCODICE
        WHERE NumeroProgressivoCompletamento = num_progressivo_completamento);
    
    SET num_risposte_inserite = num_risposte_inserite_codice + num_risposte_inserite_RC;

    -- Conta il numero di risposte corrette per il test
    SET num_risposte_corrette_RC = (SELECT COUNT(*) FROM RISPOSTAQUESITORISPOSTACHIUSA
		WHERE NumeroProgressivoCompletamento = num_progressivo_completamento AND Esito = TRUE);

    -- Conta il numero di risposte corrette per il test
    SET num_risposte_corrette_codice = (SELECT COUNT(*) FROM RISPOSTAQUESITOCODICE
		WHERE NumeroProgressivoCompletamento = num_progressivo_completamento AND Esito = TRUE);

    SET num_risposte_corrette = num_risposte_corrette_codice + num_risposte_corrette_RC;

    -- Se tutte le risposte sono state inserite e hanno esito True, il test diventa Concluso
    IF (num_risposte_inserite = num_quesiti_totali AND num_risposte_corrette = num_quesiti_totali) THEN
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
AFTER INSERT ON RIGA
FOR EACH ROW
BEGIN
    UPDATE TABELLADIESERCIZIO
    SET num_righe = num_righe + 1
    WHERE Nome = NEW.NomeTabella;
END
//
DELIMITER ;

DELIMITER //
CREATE TRIGGER decrementaNumRighe 
AFTER DELETE ON RIGA
FOR EACH ROW
    -- Aggiorna il conteggio delle righe nella tabella TABELLADIESERCIZIO
    UPDATE TABELLADIESERCIZIO
    SET num_righe = num_righe - 1
    WHERE Nome = OLD.NomeTabella;
//
DELIMITER ;


DELIMITER //
CREATE TRIGGER AggiornaNumeroRisposteQuesitoAfterInsert
AFTER INSERT ON RISPOSTAQUESITORISPOSTACHIUSA
FOR EACH ROW
BEGIN
    UPDATE QUESITO
    SET NumeroRisposte = NumeroRisposte + 1
    WHERE NumeroProgressivo = NEW.NumeroProgressivoQuesito AND TitoloTest = NEW.TitoloTest;
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
    WHERE NumeroProgressivo = OLD.NumeroProgressivoQuesito AND TitoloTest = OLD.TitoloTest;
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
    WHERE NumeroProgressivo = NEW.NumeroProgressivoQuesito AND TitoloTest = NEW.TitoloTest;
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
    WHERE NumeroProgressivo = OLD.NumeroProgressivoQuesito AND TitoloTest = OLD.TitoloTest;
END;
//
DELIMITER ;


DELIMITER //

CREATE TRIGGER spaziTitoloTest
BEFORE INSERT ON TEST FOR EACH ROW
BEGIN
    DECLARE titoloModificato VARCHAR(100);
    SET titoloModificato = TRIM(NEW.Titolo); -- Rimuove spazi vuoti all'inizio e alla fine
    SET titoloModificato = REPLACE(titoloModificato, ' ', '_'); -- Sostituisce gli spazi vuoti con '_'
    SET NEW.Titolo = titoloModificato; -- Aggiorna il titolo nel nuovo valore pulito
END;

//

DELIMITER ;
