# Tâches  4106 - 4194

## Version 01

### Coté Operateur

- (ok - 4106) Configuration des préfixes valable de l’opérateur
  - crée table / operateur : id , prefixe , libelle
- (ok - 4106)- Création de types d'opérations 
  - table type_mouvement : id , libelle 
- (ok - 4106) creation bareme et modification
  - table barème : montant_min , montant_max, valeur_frais

  
- (ok - 4106) Situation gain via les différents frais
  - à l'aide de la table transaction on peut la cumul des frais

-  (ok - 4106) Situation des comptes clients
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
  (ok- 4194)- faire un dépot
  - faire un retrait
  (ok-4194) - voir les historique
    -  table transaction , id-user, id 
  

  V2

  ### cote operateur
  - (ok- 4106)configuration autre prefixe
    -ajout nouveaux lignes dans la table :id, prefixe, libelle
  -  (ok- 4106) Configuration % en plus de commissions pour les transferts vers les autres opérateurs 
    -creation table commissions avec: id, libelle, pourcentage
    -fonction pour appliquer % aux frais (autres operateurs seulement) 
    -ajout dans une table transaction autre operateur
  - (ok- 4106) Sur la page “Situation gain via les différents frais” , séparer opérateur et autres opérateurs
    -autres tableau affichage des frais grace a la table transaction autre operateur
  -Situation des montants à envoyer à chaque opérateur
    -montant transferee/ %


  ### cote client
  - (ok - 4106) Option inclure frais de retrait lors de l’envoi
    -dans retrait (bouton)(condition)
    - frais dans transfert
  -  (ok - 4106) Envoi multiple vers plusieurs numéros ( divisé le montant pour chaque numéro)
    -ajout champ dans transfert
    -fonction calcul montant pour chaque numero
    -envoie du montant vers tout les numeros

  