# Galaxy Swiss Bourdin

![Galaxy Swiss Bourdin](https://imgur.com/yTMdlhK.png)

Projet Personnel Encadré de deuxième année de BTS SIO

## Installation

### Installation local (exemple: WAMPS):
1. Configurer wamps afin de diriger le le virtual host vers le dossier public.
2. Exécuter le fichier `gsb_restore.sql` suivis de `preRealease.sql` dans `/resources`
3. Exécuter le fichier `password_hash.php` dans `/bin/gendatas`.
4. Suivre "Installer les composants"

### Installation sur serveur Linux
1. Exécuter le script `Install-Gsb.sh` en tant que simple utilisateur avec des droits de sudo sur le serveur.

### Installer les composants:
Executez la commande ``composer install --no-dev`` dans l'invite de commande à la racine du dossier

Changer les identifiants de base de données: Dans le fichier ``config/config.php`` changer les valeurs des variables de la classe

## Documentation

Documentation ouvrable dans le fichier `/docs/index.html`

Date de la dernière génération de documentation : 11/04/2024

Lancer la mise à jour/regénération de la documentation avec la commande suivante (à la racine du projet) : 
``bash Docit.bash``

## Composants
* [TCPDF](https://tcpdf.org) - Génération d'un document PDF

## Avancée

- [x] Tache 1 : Validation de fiche de frais
- [x] Tache 2 : Suivi du paiement des fiches de frais
- [x] Tache 3 : Production de la documentation (dernière version : 23/12/2017)
- [x] Tache 4 : Gestion du refus de certains frais hors forfait
- [x] Tache 5 : Sécurisation des mots de passe stockés. Hashage utilisé : SHA-512
- [x] Tache 6 : Gestion plus fine de l'indeminisation kilométrique
- [x] Tache 7 : Génération d'un état de frais au format PDF
- [x] Tache 8 : Davantage d'écologie dans l'application

# Tester la prod
Note: Avoir accées au serveur 10.10.2.165
1. Configurer son fichier host et ajouter : `10.10.2.165 <nomchoisi>`
2. se connecter à http://\<nomchoisi>

# Information de test

Visiteur : user : `p.ayot` - mdp : `xiej3uuY0`

Utilisateur comptable : user : `l.bruel` - mdp : `mdp`

## Auteurs

* [Bruel Lucas](https://github.com/Lurius-Kitsune)
* [Marco Clin](https://github.com/M3rwin)
