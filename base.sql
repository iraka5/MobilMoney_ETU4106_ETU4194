PRAGMA foreign_keys = ON;

DROP VIEW  IF EXISTS vue_bareme_operations;
DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTs  solde_user;
DROP TABLE IF EXISTS baremeFrais;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS type_operation;
DROP TABLE IF EXISTS prefixe;
DROP TABLE IF EXISTS operateurs;

CREATE TABLE operateurs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle TEXT NOT NULL
);

CREATE TABLE prefixe (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefixe TEXT NOT NULL UNIQUE,
    id_operateur INTEGER NOT NULL,
    FOREIGN KEY (id_operateur) REFERENCES operateurs(id)
);

CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    id_prefixe INTEGER NOT NULL,
    numero TEXT NOT NULL UNIQUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_prefixe) REFERENCES prefixe(id)
);

CREATE TABLE type_operation (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle TEXT NOT NULL
);

CREATE TABLE baremeFrais (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_type_operation INTEGER NOT NULL,
    montant_max REAL NOT NULL,
    montant_min REAL NOT NULL,
    montant_frais REAL NOT NULL,
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id)
);

CREATE TABLE transactions (
    id_transaction INTEGER PRIMARY KEY AUTOINCREMENT,
    id_sender INTEGER NOT NULL,
    id_receiver INTEGER,
    receiver_numero TEXT,
    montant REAL NOT NULL,
    frais REAL DEFAULT 0,
    statut TEXT NOT NULL,
    id_type_operation INTEGER NOT NULL,
    date_transaction DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_sender) REFERENCES users(id),
    FOREIGN KEY (id_receiver) REFERENCES users(id),
    FOREIGN KEY (id_type_operation) REFERENCES type_operation(id)
);

CREATE TABLE solde_user (
    id_user INTEGER PRIMARY KEY,
    solde REAL NOT NULL DEFAULT 0.0,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id)
);

INSERT INTO "operateurs" ("libelle") VALUES ('Orange');  
INSERT INTO "operateurs" ("libelle") VALUES ('Airtel');     
INSERT INTO "operateurs" ("libelle") VALUES ('YAS');    

INSERT INTO "prefixe" ("prefixe", "id_operateur") VALUES ('32', 1);
INSERT INTO "prefixe" ("prefixe", "id_operateur") VALUES ('33', 2);
INSERT INTO "prefixe" ("prefixe", "id_operateur") VALUES ('34', 3);

INSERT INTO "type_operation" ("libelle") VALUES ('Dépôt');    
INSERT INTO "type_operation" ("libelle") VALUES ('Retrait');   
INSERT INTO "type_operation" ("libelle") VALUES ('Transfert'); 

INSERT INTO "baremeFrais" ("id_type_operation", "montant_min", "montant_max", "montant_frais") 
VALUES (1, 0.0, 1000000.0, 0.0);
INSERT INTO "baremeFrais" ("id_type_operation", "montant_min", "montant_max", "montant_frais") 
VALUES (2, 100.0, 1000.0, 50.0);
INSERT INTO "baremeFrais" ("id_type_operation", "montant_min", "montant_max", "montant_frais") 
VALUES (2, 1001.0, 5000.0, 50.0);
INSERT INTO "baremeFrais" ("id_type_operation", "montant_min", "montant_max", "montant_frais") 
VALUES (2, 5001.0, 10000.0, 100.0);
INSERT INTO "baremeFrais" ("id_type_operation", "montant_min", "montant_max", "montant_frais") 
VALUES (2, 10001.0, 25000.0, 200.0);
INSERT INTO "baremeFrais" ("id_type_operation", "montant_min", "montant_max", "montant_frais") 
VALUES (2, 25001.0, 50000.0, 400.0);
INSERT INTO "baremeFrais" ("id_type_operation", "montant_min", "montant_max", "montant_frais") 
VALUES (2, 5001.0, 100000.0, 800.0);
INSERT INTO "baremeFrais" ("id_type_operation", "montant_min", "montant_max", "montant_frais") 
VALUES (2, 100001.0, 250000.0, 1500.0);
INSERT INTO "baremeFrais" ("id_type_operation", "montant_min", "montant_max", "montant_frais") 
VALUES (2, 250001.0, 500000.0, 1500.0);
INSERT INTO "baremeFrais" ("id_type_operation", "montant_min", "montant_max", "montant_frais") 
VALUES (2, 500001.0, 1000000.0, 2500.0);
INSERT INTO "baremeFrais" ("id_type_operation", "montant_min", "montant_max", "montant_frais") 
VALUES (2, 1000001.0, 2000000.0, 3000.0);
INSERT INTO "baremeFrais" ("id_type_operation", "montant_min", "montant_max", "montant_frais") 
VALUES (3, 100.0, 1000.0, 50.0);
INSERT INTO "baremeFrais" ("id_type_operation", "montant_min", "montant_max", "montant_frais") 
VALUES (3, 1001.0, 5000.0, 50.0);
INSERT INTO "baremeFrais" ("id_type_operation", "montant_min", "montant_max", "montant_frais") 
VALUES (3, 5001.0, 10000.0, 100.0);
INSERT INTO "baremeFrais" ("id_type_operation", "montant_min", "montant_max", "montant_frais") 
VALUES (3, 10001.0, 25000.0, 200.0);
INSERT INTO "baremeFrais" ("id_type_operation", "montant_min", "montant_max", "montant_frais") 
VALUES (3, 25001.0, 50000.0, 400.0);
INSERT INTO "baremeFrais" ("id_type_operation", "montant_min", "montant_max", "montant_frais") 
VALUES (3, 5001.0, 100000.0, 800.0);
INSERT INTO "baremeFrais" ("id_type_operation", "montant_min", "montant_max", "montant_frais") 
VALUES (3, 100001.0, 250000.0, 1500.0);
INSERT INTO "baremeFrais" ("id_type_operation", "montant_min", "montant_max", "montant_frais") 
VALUES (3, 250001.0, 500000.0, 1500.0);
INSERT INTO "baremeFrais" ("id_type_operation", "montant_min", "montant_max", "montant_frais") 
VALUES (3, 500001.0, 1000000.0, 2500.0);
INSERT INTO "baremeFrais" ("id_type_operation", "montant_min", "montant_max", "montant_frais") 
VALUES (3, 1000001.0, 2000000.0, 3000.0);



CREATE VIEW IF NOT EXISTS "vue_bareme_operations" AS
SELECT 
    b.id AS id_bareme,
    t.libelle AS type_operation,
    b.montant_min,
    b.montant_max,
    b.montant_frais
FROM 
    "baremeFrais" b
JOIN 
    "type_operation" t ON b.id_type_operation = t.id;


CREATE TABLE commissions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_operateur INTEGER NOT NULL UNIQUE,
    pourcentage REAL NOT NULL,
    FOREIGN KEY (id_operateur) REFERENCES operateurs(id)
);


INSERT INTO commissions (libelle, pourcentage)
VALUES ('Orange', 5.0);


CREATE TABLE transaction_autre_operateur (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    id_user_source INTEGER NOT NULL,
    numero_dest TEXT NOT NULL,
    id_operateur_dest INTEGER NOT NULL,
    montant REAL NOT NULL,
    frais REAL NOT NULL DEFAULT 0,
    commission REAL NOT NULL DEFAULT 0,
    date_cree DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user_source) REFERENCES users(id),
    FOREIGN KEY (id_operateur_dest) REFERENCES operateurs(id)
);

INSERT INTO commissions (id_operateur, pourcentage) VALUES (1, 5.0); 
INSERT INTO commissions (id_operateur, pourcentage) VALUES (2, 5.0); 
INSERT INTO commissions (id_operateur, pourcentage) VALUES (3, 5.0); 


SELECT 
    'Autres opérateurs' AS libelle,
    COUNT(*) AS nb_transactions,
    SUM(frais) AS total_frais,
    SUM(frais) - SUM(montant * (c.pourcentage/100)) AS gain_net
FROM transaction_autre_operateur t
JOIN commissions c ON c.libelle = 'Autres-opérateurs';


SELECT o.libelle AS operateur,
       COUNT(*) AS nb_transactions,
       SUM(t.montant) AS montant_total,
       SUM(t.montant * (c.pourcentage/100)) AS montant_a_envoyer
FROM transaction_autre_operateur t
JOIN users u ON u.id = t.id_user_dest
JOIN prefixe p ON p.id = u.id_prefixe
JOIN operateurs o ON o.id = p.id_operateur
JOIN commissions c ON c.libelle = o.libelle   
GROUP BY o.libelle;
