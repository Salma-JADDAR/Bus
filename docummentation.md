# Documentation du projet – Plateforme de réservation de bus (inspirée de marKoub.ma)

---

## A. Analyse du domaine (Étude de marKoub.ma)

### 1. Présentation générale du domaine

Le domaine étudié est celui de la **réservation de voyages en bus en ligne**. Une plateforme comme *marKoub.ma* permet aux utilisateurs de rechercher des trajets, consulter les horaires, réserver des places et gérer leurs billets sans se déplacer.

---

### 2. Processus de réservation (côté utilisateur)

Le processus de réservation se déroule généralement comme suit :

1. L'utilisateur accède à la plateforme
2. Il recherche un voyage (ville de départ, ville d'arrivée, date)
3. Le système affiche la liste des voyages disponibles
4. L'utilisateur sélectionne un voyage
5. Il choisit le nombre de places
6. Il s'authentifie ou crée un compte
7. Il confirme la réservation
8. Le système génère une réservation avec un statut (en attente / confirmée)

---

### 3. Entités principales identifiées

À partir de l’analyse du domaine et du MCD réalisé, les entités principales identifiées dans le système de réservation sont les suivantes :

**Utilisateur**
Représente toute personne utilisant la plateforme. Un utilisateur peut être un client ou un administrateur. Il peut effectuer des réservations, payer, et laisser des avis.

Attributs clés : id, nom, email, password, rôle, téléphone, created_at.

---

**Client**
Spécialisation de l’utilisateur. Le client est celui qui effectue les réservations, choisit des sièges et donne des avis sur les voyages.

---

**Administrateur**
Spécialisation de l’utilisateur. Il est responsable de la gestion des données du système (bus, routes, villes, voyages).

---

**Réservation**
Représente l’action de réserver un ou plusieurs sièges pour un voyage donné. Elle relie le client, le voyage, le paiement et le ticket.

Attributs clés : id_reservation, date_reservation.

---

**Paiement**
Permet d’enregistrer le paiement effectué pour une réservation. Chaque réservation est associée à un seul paiement.

Attributs clés : id_paiement, montant, méthode, statut, date_paiement.

---

**Ticket**
Document généré après la confirmation de la réservation. Il sert de preuve de réservation pour le client.

Attributs clés : id_ticket, date_emission.

---

**Voyage**
Représente un trajet planifié à une date précise. Un voyage utilise un bus, suit une route et possède un tarif.

Attributs clés : id_voyage, date_depart.

---

**Route**
Définit un itinéraire composé de plusieurs villes (arrêts). Elle sert de base pour programmer les voyages.

Attributs clés : id_trajet, nom_trajet.

---

**Ville**
Représente une ville traversée par une route. Elle intervient comme point de départ, d’arrêt ou d’arrivée.

Attributs clés : id_ville, nom_ville.

---

**Bus**
Moyen de transport utilisé pour effectuer les voyages. Chaque bus possède une capacité et plusieurs sièges.

Attributs clés : id_bus, nom_bus, capacite.

---

**Siège**
Représente une place dans un bus. Un siège peut être libre ou occupé lors d’une réservation.

Attributs clés : id_siege, numero, statut.

---

**Tarif**
Définit le prix appliqué à un voyage entre deux villes ou arrêts.

Attributs clés : id_tarif, montant.

---

**Avis**
Permet aux clients de donner une note et un commentaire sur un voyage effectué.

Attributs clés : commentaire, dateAvis.

---

### 4. Flux d'administration observé

Le rôle de l'administrateur comprend :

1. Gestion des voyages (ajout, modification, suppression)
2. Gestion des bus
3. Gestion des villes
4. Consultation des réservations
5. Gestion des utilisateurs

---

## B. Proposition d'architecture

### 1. Schéma de base de données (MCD / ERD)

Le Modèle Conceptuel de Données (MCD) est construit en cohérence totale avec le diagramme de classes réalisé. Il représente les entités, leurs attributs ainsi que les associations et cardinalités du système de réservation.

#### Entités principales

**UTILISATEUR**

* id (PK)
* nom
* email
* password
* rôle
* created_at
* telephone
* codeAdmin

**RESERVATION**

* id_reservation (PK)
* date_reservation

**PAIEMENT**

* id_paiement (PK)
* montant
* methode
* statut
* date_paiement

**TICKET**

* id_ticket (PK)
* date_emission

**SIEGE**

* id_siege (PK)
* numero
* statut

**BUS**

* id_bus (PK)
* nom_bus
* capacite

**VOYAGE**

* id_voyage (PK)
* date_depart

**ROUTE**

* id_trajet (PK)
* nom_trajet

**VILLE**

* id_ville (PK)
* nom_ville

**TARIF**

* id_tarif (PK)
* montant

**AVIS**

* commentaire
* dateAvis

---

#### Associations et cardinalités

* **RESERVER** : Utilisateur (0,n) — Réservation (1,1)
* **PAYER** : Réservation (1,1) — Paiement (1,1)
* **GENERER** : Réservation (1,1) — Ticket (1,1)
* **OCCUPER** : Réservation (1,n) — Siège (1,1)
* **UTILISER** : Voyage (1,1) — Bus (0,n)
* **PROGRAMMER** : Route (0,n) — Voyage (1,1)
* **CONCERNER** : Réservation (1,1) — Voyage (1,1)
* **PASSER_PAR** : Route (1,n) — Ville (1,n)

  * Attributs : ordre, heure_arrivee
* **TARIFIER** : Voyage (1,1) — Tarif (1,1)
* **COMPOSER** : Bus (1,n) — Siège (1,1)
* **AVIS** : Utilisateur (0,n) — Voyage (0,n)

---

### 2. Fonctionnalités MVP (Minimum Viable Product)

Fonctionnalités essentielles pour la première version :

* Authentification (inscription / connexion)
* Recherche de voyages
* Consultation des détails d'un voyage
* Réservation de places
* Consultation des réservations utilisateur
* Interface administrateur basique

---

### 3. Diagramme de cas d'utilisation

#### Acteurs :

* Utilisateur
* Administrateur

#### Cas d'utilisation – Utilisateur :

* Rechercher un voyage
* Consulter les voyages disponibles
* Réserver un voyage
* Consulter mes réservations

#### Cas d'utilisation – Administrateur :

* Gérer les voyages
* Gérer les bus
* Gérer les villes
* Consulter les réservations

---

### 4. Diagramme de classes

Le diagramme de classes proposé est aligné avec le modèle que nous avons conçu et implémenté.

#### Classe Utilisateur

* Attributs : id:int, nom:String, email:String, password:String, createdAt:Date
* Méthodes : login(email:String, password:String):boolean, logout():void

#### Classe Admin (hérite de Utilisateur)

* Attributs : adminCode:String
* Méthodes : ajouterBus(bus:Bus):void, ajouterVille(ville:Ville):void, creerRoute(route:Route):void, programmerVoyage(voyage:Voyage):void

#### Classe Client (hérite de Utilisateur)

* Attributs : telephone:int
* Méthodes : creerReservation(voyage:Voyage, sieges:List<Siege>):Reservation, consulterReservations():List<Reservation>, donnerAvis(voyage:Voyage, commentaire:String, note:int):Avis

#### Classe Reservation

* Attributs : id:int, client:Client, voyage:Voyage, sieges:List<Siege>, paiement:Paiement, ticket:Ticket, dateReservation:Date
* Méthodes : calculerTotal():double, genererTicket():Ticket

#### Classe Paiement

* Attributs : id:int, montant:double, modePaiement:String, statut:String, datePaiement:Date
* Méthodes : effectuerPaiement():boolean

#### Classe Ticket

* Attributs : id:int, codeQR:String, dateEmission:Date
* Méthodes : genererTicket():String, imprimer():void

#### Classe Voyage

* Attributs : id:int, route:Route, bus:Bus, dateDepart:LocalDate
* Méthodes : getArrets():List<Arret>, calculerPrix(arretDepart:Arret, arretArrivee:Arret):double

#### Classe Route

* Attributs : id:int, ordre:int, arrets:List<Arret>
* Méthodes : getArrets():List<Arret>, ajouterArret(arret:Arret):void

#### Classe Arret

* Attributs : id:int, ville:Ville, ordre:int, heureArrivee:LocalTime
* Méthodes : getVille():Ville, getOrdre():int

#### Classe Ville

* Attributs : id:int, nom:String
* Méthodes : getNom():String

#### Classe Bus

* Attributs : id:int, matricule:String, capacite:int, sieges:List<Siege>
* Méthodes : getSieges():List<Siege>, getSiegesDisponibles():List<Siege>

#### Classe Siege

* Attributs : id:int, numero:int, disponible:boolean
* Méthodes : estDisponible():boolean, reserver():void

#### Classe Tarif

* Attributs : id:int, route:Route, depart:Arret, arrivee:Arret, montant:double
* Méthodes : getMontant():double

#### Classe Avis

* Attributs : id:int, client:Client, voyage:Voyage, note:int, commentaire:String, dateAvis:Date
* Méthodes : publier():boolean, modifier(nouvelleNote:int, nouveauCommentaire:String):boolean

---

**Ce diagramme de classes correspond exactement au modèle conceptuel réalisé pour le projet.**
