USE ESQL;

DROP VIEW IF EXISTS classifica_test_completati;
DROP VIEW IF EXISTS classifica_risposte_corrette;
DROP VIEW IF EXISTS classifica_quesiti;

-- VIEW
-- view nr 1
CREATE VIEW classifica_test_completati AS
	SELECT s.CodiceAlfaNumerico, COUNT(*) AS numero_test_completati
	FROM STUDENTE AS s LEFT JOIN COMPLETAMENTO AS c ON s.Email = c.EmailStudente
	WHERE c.Stato = 'Concluso'
	GROUP BY s.CodiceAlfaNumerico;
        

-- view nr 2
CREATE VIEW classifica_risposte_corrette AS
	SELECT
		C.EmailStudente AS codiceStudente,
		IFNULL(((SUM(RQC.Esito) + SUM(RCC.Esito)) / (COUNT(RQC.NumeroProgressivoCompletamento) + COUNT(RCC.NumeroProgressivoCompletamento))) * 100, 0) AS percentualeRisposteCorrette
	FROM
		COMPLETAMENTO C
	LEFT JOIN
		RISPOSTAQUESITORISPOSTACHIUSA AS RQC ON C.NumeroProgressivo = RQC.NumeroProgressivoCompletamento
	LEFT JOIN
		RISPOSTAQUESITOCODICE AS RCC ON C.NumeroProgressivo = RCC.NumeroProgressivoCompletamento
	GROUP BY
		C.EmailStudente
	ORDER BY
		percentualeRisposteCorrette DESC;



-- view nr 3
CREATE VIEW classifica_quesiti AS
	SELECT q.NumeroProgressivo, q.TitoloTest, COUNT(r.NumeroProgressivoCompletamento) AS num_risposte_codice_inserite, 
		COUNT(rc.NumeroProgressivoCompletamento) AS num_risposte_chiuse_inserite
	FROM QUESITO AS q
	LEFT JOIN RISPOSTAQUESITOCODICE AS r ON q.NumeroProgressivo = r.NumeroProgressivoQuesito
    LEFT JOIN RISPOSTAQUESITORISPOSTACHIUSA AS rc ON q.NumeroProgressivo = rc.NumeroProgressivoQuesito
	GROUP BY q.NumeroProgressivo, q.TitoloTest;