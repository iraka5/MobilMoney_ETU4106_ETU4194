# Tâches  4106 - 4194

## Version 01

### Coté Operateur

- (ok - 4106) Configuration des préfixes valable de l’opérateur
  - crée table / operateur : id , prefixe , libelle
- (ok - 4106)- Création de types d'opérations 
  - table type_mouvement : id , libelle 
- (ok - 4106) creation bareme et modification
  - table barème : montant_min , montant_max, valeur_frais

  
- Situation gain via les différents frais
  - à l'aide de la table transaction on peut la cumul des frais

- Situation des comptes clients
  - tous les informations de la table user, transcations (id_user)

### Coté Client 

 - (ok- 4194) Login automatique avec le numéro de téléphone
  - formulaire, input : numero , email , mdp
  - table utilisateur : numero , email , mdp , id , id-solde, id-préfixe
  - fonction verifier si le numero est valide
  - afficher les erreurs ( javascript)
  - inserer le numero dans la base si elle n' existe pas
  - se connecter si le numero + email  existe déjà
  

- Operations
  (ok- 4194) - voir le solde
    - depuis la table solde
- faire un dépot
  - faire un retrait
  - voir les historique
    -  table transaction , id-user, id 
  

  configuration de la basesqlite