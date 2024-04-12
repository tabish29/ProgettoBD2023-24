USE ESQL;
-- AREA PER I TEST


-- Test inserisciRisposta e visualizzaEsito e inserisciMessaggioStudente

INSERT INTO DOCENTE VALUES("docente@gmail.com","password", "ciao","nano", 1234589, "scienze", "corso");
INSERT INTO DOCENTE VALUES("docente2@gmail.com","password","ciao2","nano2", 12345892, "scienze", "corso");
INSERT INTO STUDENTE VALUES("studente@gmail.com","password", "nano", "ciao", 123456789, 2010, 1234567891234567);
INSERT INTO STUDENTE VALUES("studente2@gmail.com","password", "nano", "ciao", 3333, 2010, 2234567891234567);
INSERT INTO TEST VALUES("provaNr1", '2024-02-07 14:30:00', NULL ,false, "docente@gmail.com");
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Aperto", "provaNr1", "studente@gmail.com", NULL, NULL);
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Aperto", "provaNr1", "studente2@gmail.com", NULL, NULL);
INSERT INTO QUESITO VALUES(1,"provaNr1","Basso", "testo quesito di codice", 3);
INSERT INTO QUESITO VALUES(2,"provaNr1","Basso", "testo quesito a scleta", 3);
INSERT INTO QUESITOCODICE VALUES(1, "provaNr1");
INSERT INTO SOLUZIONE VALUES(1, "provaNr1","SELECT * FROM QUESITO");
INSERT INTO QUESITORISPOSTACHIUSA VALUES(2, "provaNr1");
INSERT INTO OPZIONERISPOSTA VALUES(1,"provaNr1",2,"opzione risposta Corretta",true);
INSERT INTO OPZIONERISPOSTA VALUES(2,"provaNr1",2,"opzione risposta sbagliata",false);
INSERT INTO STUDENTE VALUES("alessia@gmail.com","password", "Alessia", "Di Sabato", 123456789, 2021, "ABCDEFGHILMNOPWR");
INSERT INTO STUDENTE VALUES("tabish@gmail.com","password", "Tabish", "Ghazanfar", 8654678, 2010,"gdhdnbgdtjhjklmk");
INSERT INTO STUDENTE VALUES("lorenzo@gmail.com","password", "Lorenzo", "Maini", 475875983,2010, "llllllllllllllll");
INSERT INTO STUDENTE VALUES("alex@gmail.com","password","Alex", "Ranaulo",35111111,2010,  "aaaaaaaaaaaaaaaa");
INSERT INTO STUDENTE VALUES("davide@gmail.com","password", "Davide", "De Rosa", 1211212,2010,  "dddddddddddddddd");
INSERT INTO TEST VALUES("provaNr2", '2024-02-09 14:30:00', NULL ,false, "docente@gmail.com");
INSERT INTO QUESITO VALUES(4,"provaNr2","Basso", "testo quesito a scleta", 3);
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Aperto", "provaNr1", "alessia@gmail.com", NULL, NULL);
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Aperto", "provaNr2", "alessia@gmail.com", NULL, NULL);
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Aperto", "provaNr1", "tabish@gmail.com", NULL, NULL);
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Aperto", "provaNr2", "tabish@gmail.com", NULL, NULL);
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Aperto", "provaNr1", "lorenzo@gmail.com", NULL, NULL);
INSERT INTO COMPLETAMENTO (TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("provaNr2", "lorenzo@gmail.com", NULL, NULL);


-- fine test

 -- Test messaggio
CALL inserisciMessaggioStudente("studente@gmail.com", "docente@gmail.com", "provaNr1", "titoloMessaggio", "Argomento del messaggio");
CALL InserimentoMessaggioDocente("provaNr1", "Attenzione","Questo è un messaggio importante",null,"docente@gmail.com");
CALL InserimentoMessaggioDocente("testDiProva3", "Eccoci qua","Questo è un messaggio e basta",null,"docente2@gmail.com");
#SELECT * FROM MESSAGGIO;
-- fine test



CALL CreazioneTabellaEsercizio("NomeTabellaProva",NOW(),20,"docente2@gmail.com");
CALL CreazioneTabellaEsercizio("SecondaTabellaProva",NOW(),10,"docente@gmail.com");
SELECT * FROM TABELLADIESERCIZIO;
-- fine test

/* test visualizzarisposte
CALL ModificaVisualizzazioneRisposte("nuovoTitolo3",true);
#SELECT * FROM TEST;
-- fine test
*/

/* test creazione test
CALL CreazioneTest("TestDiProva3", NOW(), null, true, "docente@gmail.com");
#SELECT * FROM TEST;
-- fine test
*/

-- Test creazione quesiti
CALL CreazioneQuesitoRispostaChiusa("TestDiProva3","Medio","Eccoci qua",@nQ1);
CALL CreazioneQuesitoRispostaChiusa("provaNr2","Medio","Descrizione",@nQ2);
CALL CreazioneQuesitoRispostaChiusa("TestDiProva3","Medio","Eccoci qua",@nQ3);
CALL CreazioneQuesitoCodice("TestDiProva3","Alto","Eccoci qua",@nQ4);
CALL CreazioneQuesitoCodice("TestDiProva3","Alto","Eccoci qua di nuovo",@nQ5);

CALL InserimentoSoluzione("provaNr1",1,"SELECT * FROM TEST");
CALL InserimentoSoluzione("provaNr1",8,"Qui va tutto bene sbagliato");
CALL InserimentoSoluzione("provaNr2",10,"Anche qua funziona");
CALL InserimentoSoluzione("TestDiProva3",9,"Anche qua funziona tutto");

CALL InserimentoOpzioneRisposta("provaNr2",2,"Evviva Noi fatto male",true);
CALL InserimentoOpzioneRisposta("provaNr2",6,"Evviva Noi",true);
CALL InserimentoOpzioneRisposta("provaNr2",8,"Completamento di Lollo",false);
-- fine test




-- test inserimento risposte
CALL inserisciRispostaQuesitoRispostaChiusa(3,"ProvaNr1","risposta chiusa",2);
CALL inserisciRispostaQuesitoRispostaChiusa(1, "provaNr1", "opzione risposta sbagliata", 2);
CALL inserisciRispostaQuesitoRispostaChiusa(1, "provaNr1", "opzione risposta Corretta", 2);
CALL inserisciRispostaQuesitoCodice(2, "provaNr1", "rispostaNonCorretta", 1, null);
CALL inserisciRispostaQuesitoCodice(3, "provaNr1", "Qui va tutto bene", 1, true);
CALL inserisciRispostaQuesitoCodice(4, "provaNr2", "Anche qua funziona", 10, false);
CALL inserisciRispostaQuesitoCodice(5, "provaNr1", "rispostaNonCorretta", 1, false);

#CALL visualizzaEsitoRisposta(5, "provaNr1",1,  @esitoRispostaCodice);
#SELECT @esitoRispostaCodice;
 
#CALL visualizzaEsitoRisposta(3, "provaNr1",1,  @esitoRispostaCodice);
#SELECT @esitoRispostaCodice;
 
#CALL visualizzaEsitoRisposta(1, "provaNr1", 2,  @esitoRispostaScelta);
#SELECT @esitoRispostaScelta;


-- TEST VIEW

SELECT * FROM ClassificaQuesitiPerRisposte;
SELECT * FROM classificaTestCompletati;

SELECT * FROM classifica_test_completati;
SELECT * FROM classifica_studenti;
SELECT * FROM classifica_quesiti;



/*

DELIMITER //
UPDATE TEST
SET Titolo="nuovoTitolo3",visualizzaRisposte="0"
WHERE Titolo="TestDiProva3"
//
DELIMITER ;

CALL VisualizzaTestDisponibili();
*/
/*
-- Test classificaTestCompletati
INSERT INTO DOCENTE VALUES("docente@gmail.com","password","ciao","nano", 1234589, "scienze", "corso");
INSERT INTO TEST VALUES("provaNr1", '2024-02-07 14:30:00', NULL ,true, "docente@gmail.com");
INSERT INTO STUDENTE VALUES("alessia@gmail.com","password", "Alessia", "Di Sabato", 123456789, 2021, "ABCDEFGHILMNOPWR");
INSERT INTO STUDENTE VALUES("tabish@gmail.com","password", "Tabish", "Ghazanfar", 8654678, 2010,"gdhdnbgdtjhjklmk");
INSERT INTO STUDENTE VALUES("lorenzo@gmail.com","password", "Lorenzo", "Maini", 475875983,2010, "llllllllllllllll");
INSERT INTO STUDENTE VALUES("alex@gmail.com","password","Alex", "Ranaulo",35111111,2010,  "aaaaaaaaaaaaaaaa");
INSERT INTO STUDENTE VALUES("davide@gmail.com","password", "Davide", "De Rosa", 1211212,2010,  "dddddddddddddddd");
INSERT INTO TEST VALUES("provaNr2", '2024-02-09 14:30:00', NULL ,true, "docente@gmail.com");
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Aperto", "provaNr1", "alessia@gmail.com", NULL, NULL);
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Concluso", "provaNr2", "alessia@gmail.com", NULL, NULL);
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Concluso", "provaNr1", "tabish@gmail.com", NULL, NULL);
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Concluso", "provaNr2", "tabish@gmail.com", NULL, NULL);
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Aperto", "provaNr1", "lorenzo@gmail.com", NULL, NULL;
INSERT INTO COMPLETAMENTO (Stato, TitoloTest, EmailStudente, DataPrimaRisposta, DataUltimaRisposta) VALUES("Aperto", "provaNr2", "lorenzo@gmail.com", NULL, NULL);


-- Fine test
*/

/* 
-- Test per Trigger testConclusoVisualizzaRisposte
UPDATE TEST
SET VisualizzaRisposte = TRUE
WHERE Titolo = 'provaNr1';

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