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
	SELECT
		NumeroProgressivoQuesito,
		COUNT(*) AS numRisposteInserite
	FROM
		(SELECT NumeroProgressivoQuesito FROM RISPOSTAQUESITORISPOSTACHIUSA
		UNION ALL
		SELECT NumeroProgressivoQuesito FROM RISPOSTAQUESITOCODICE) AS Risposte
	GROUP BY
		NumeroProgressivoQuesito
	ORDER BY
		numRisposteInserite DESC;
