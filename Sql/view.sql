USE ESQL;

DROP VIEW IF EXISTS ClassificaQuesitiPerRisposte;
DROP VIEW IF EXISTS classificaTestCompletati;
DROP VIEW IF EXISTS classifica_test_completati;
DROP VIEW IF EXISTS classifica_studenti;
DROP VIEW IF EXISTS classifica_quesiti;

-- VIEW


CREATE VIEW ClassificaQuesitiPerRisposte AS
SELECT  QUESITO.NumeroProgressivo,QUESITO.TitoloTest,COUNT(RC.NumeroProgressivoCompletamento) + COUNT(RCC.NumeroProgressivoCompletamento) AS NumeroTotaleRisposte
FROM QUESITO 
JOIN RISPOSTAQUESITORISPOSTACHIUSA AS RC ON QUESITO.NumeroProgressivo = RC.NumeroProgressivoQuesito AND QUESITO.TitoloTest = RC.TitoloTest
JOIN RISPOSTAQUESITOCODICE AS RCC ON QUESITO.NumeroProgressivo = RCC.NumeroProgressivoQuesito AND QUESITO.TitoloTest = RCC.TitoloTest
GROUP BY QUESITO.NumeroProgressivo, QUESITO.TitoloTest
ORDER BY NumeroTotaleRisposte DESC;


        

CREATE VIEW classifica_test_completati AS
	SELECT s.CodiceAlfaNumerico, COUNT(*) AS numero_test_completati
	FROM STUDENTE AS s LEFT JOIN COMPLETAMENTO AS c ON s.Email = c.EmailStudente
	WHERE c.Stato = 'Concluso'
	GROUP BY s.CodiceAlfaNumerico;
        

# QUI BISOGNA LAVORARCI, VANNO UNITE LE DUE, NON POSSONO ESSERE SEPARATE
CREATE VIEW classifica_studenti AS
	SELECT s.CodiceAlfaNumerico, 
    ROUND((COUNT(CASE WHEN rc.Esito = TRUE THEN 1 ELSE 0 END) / COUNT(rc.NumeroProgressivoCompletamento)) * 100, 2) AS percentuale_risposte_chiuse_corrette,
    ROUND((COUNT(CASE WHEN rc.Esito = TRUE THEN 1 ELSE 0 END) / COUNT(rcc.NumeroProgressivoCompletamento)) * 100, 2) AS percentuale_risposte_codice_corrette
	FROM STUDENTE AS s 
    LEFT JOIN REALIZZAZIONE AS rz ON s.Email = rz.EmailStudente
	LEFT JOIN RISPOSTAQUESITORISPOSTACHIUSA AS rc ON rz.NumeroProgressivoCompletamento = rc.NumeroProgressivoCompletamento
    LEFT JOIN RISPOSTAQUESITORISPOSTACHIUSA AS rcc ON rz.NumeroProgressivoCompletamento = rcc.NumeroProgressivoCompletamento
	GROUP BY s.CodiceAlfaNumerico;
        

CREATE VIEW classifica_quesiti AS
	SELECT q.NumeroProgressivo, q.TitoloTest, COUNT(r.NumeroProgressivoCompletamento) AS num_risposte_codice_inserite, 
		COUNT(rc.NumeroProgressivoCompletamento) AS num_risposte_chiuse_inserite
	FROM QUESITO AS q
	LEFT JOIN RISPOSTAQUESITOCODICE AS r ON q.NumeroProgressivo = r.NumeroProgressivoQuesito
    LEFT JOIN RISPOSTAQUESITORISPOSTACHIUSA AS rc ON q.NumeroProgressivo = rc.NumeroProgressivoQuesito
	GROUP BY q.NumeroProgressivo, q.TitoloTest;