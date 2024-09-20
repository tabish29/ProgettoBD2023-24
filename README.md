# Piattaforma ESQL

## Descrizione del Progetto

La piattaforma **ESQL** la piattaforma è ispirata al tool **Moodle SQL**.

L'obiettivo della piattaforma è fornire un ambiente interattivo per i docenti e gli studenti, facilitando la creazione e la gestione di test legati all'insegnamento di SQL e al modello relazionale.

## Funzionalità principali

### Docenti
- **Creazione di tabelle**: I docenti possono creare tabelle, definendo struttura e contenuti.
- **Popolamento delle tabelle**: Le tabelle create possono essere popolate con dati per le esercitazioni e i test.
- **Creazione di test**: È possibile creare test con domande a risposta chiusa o basate su sketch di codice SQL.

### Studenti
- **Registrazione**: Gli studenti possono registrarsi alla piattaforma per effettuare test del corso.
- **Visione dei test disponibili**: Gli studenti hanno accesso ai test creati dai docenti.
- **Completamento dei test**: Gli studenti possono completare i test direttamente sulla piattaforma.
- **Visione dei risultati**: Dopo aver completato un test, gli studenti possono consultare i risultati e l'esito delle loro risposte.
- **Comunicazione con i docenti**: Gli studenti possono inviare messaggi diretti ai docenti attraverso la piattaforma.


## Istruzioni per avviare il progetto

### Prerequisiti

Assicurarsi di avere installati i seguenti strumenti:
- **[XAMPP](https://www.apachefriends.org/index.html)** o **[MAMP](https://www.mamp.info/en/downloads/)** per l'ambiente Apache e PHP.
- **[MYSQL SERVER](https://dev.mysql.com/downloads/mysql/)** e **[MYSQL WORKBENCH](https://dev.mysql.com/downloads/workbench/)** per il database SQL
- **[MongoDB](https://www.mongodb.com/try/download/community-kubernetes-operator)** per il database NoSQL.

### Passaggi per l'avvio

1. **Scaricare la repository**  
   Assicurati di scaricare la repository e posizionarla nella cartella `htdocs` di **XAMPP** o **MAMP**. 

   - Per **XAMPP**, la cartella si trova tipicamente in `C:\xampp\htdocs\`.
   - Per **MAMP**, la cartella si trova in `/Applications/MAMP/htdocs/`.

2. **Configurare la connessione a MongoDB**  
   Nel file `connessione.php` all'interno del progetto, inserire i dati di connessione per MongoDB e MYSQL.

3. **Eseguire le query SQL**  
   Nella cartella `SQL` del progetto, sono presenti gli script SQL da eseguire per configurare il database relazionale. 
   Seguire il seguente ordine di eseguizione:
   - `table.sql`: per creare le tabelle del database.
   - `procedure.sql`: per creare le procedure SQL.
   - `trigger.sql`: per configurare i trigger necessari.
   - `view.sql`: per creare le viste.

   Questi script si possono eseguire attraverso **phpMyAdmin** o direttamente dal terminale MySQL.

4. **Avviare Apache e MySQL**  
   A seconda dell'ambiente scelto (**XAMPP** o **MAMP**), aprire il pannello di controllo e avviare i servizi **Apache**.
   Una volta completata la configurazione, si può accedere all'applicazione nel browser utilizzando il seguente link:
      - Per **XAMPP**: [http://localhost/ProgettoBD2023-24](http://localhost/ProgettoBD2023-24)
      - Per **MAMP**: [http://localhost:8888/ProgettoBD2023-24](http://localhost:8888/ProgettoBD2023-24)

