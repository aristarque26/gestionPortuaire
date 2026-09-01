# Routes de l'application Laravel

## Routes publiques

- `/`: Page d'accueil
- `/login`, `/register`: Authentification
- `/auth/google`, `/auth/google/callback`: Connexion Google
- `/trouver-reservations`, `/verifier-code`: Fonctionnalité de recherche de réservation
- `/api/verifier-disponibilite-pavillon`: API pour vérifier la disponibilité d'un pavillon

## Routes protégées

### Dashboard Admin

- `/admin/dashboard`: Tableau de bord admin
- `/admin/*`: Gestion des utilisateurs, bateaux, ports, quais, voyages, pavillons, clients, réservations, paiements, trajets
- `/admin/export-reservations`, `/admin/export`: Export Excel des réservations
- `/admin/conceder*`, `/admin/appartenir*`, `/admin/contiendra*`, `/admin/reserve*`: Gestion des tables associatives
- `/admin/settings*`: Paramètres admin (général, notifications, sécurité, facturation, apparence, avance)

### Dashboard Client

- `/client/dashboard`: Tableau de bord client
- `/client/voyages`, `/client/voyages/{id}`: Gestion des voyages
- `/client/reservations`: Gestion des réservations
- `/client/reservation/{id}/paiement`, `/client/reservations/{id}/paiement`: Paiement des réservations
- `/client/reservation/{id}/facture`: Téléchargement de la facture (PDF)
- `/client/paiements`: Gestion des paiements
- `/client/profil`, `/client/settings`: Gestion du profil et des paramètres client
- `/client/settings/password`: Gestion du mot de passe

## Authentification

- Login, registration, password reset via email
- Verification d'email
- Confirmation de mot de passe
- Déconnexion

## API

- `/api/verifier-disponibilite-pavillon`: Vérifie la disponibilité d'un pavillon en fonction du voyage et du poids de cargaison

## Autres

- `require base_path('routes/auth.php');`: Inclusion des routes d'authentification
