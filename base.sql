PRAGMA foreign_keys = ON;

DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS solde_user;
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
    id_receiver INTEGER NOT NULL,
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

