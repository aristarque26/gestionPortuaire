<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Confirmation de réservation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1e3a8a; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9fafb; padding: 20px; border-radius: 0 0 10px 10px; }
        .details { background: white; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .footer { text-align: center; font-size: 12px; color: #6b7280; margin-top: 20px; }
        h1 { margin: 0; font-size: 24px; }
        .btn { display: inline-block; background: #2563eb; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚓ KivuPort.com</h1>
            <p>Confirmation de réservation</p>
        </div>
        <div class="content">
            <h2>Merci, {{ $reservation->client->prenom }} !</h2>
            <p>Votre réservation a bien été prise en compte. Voici les détails :</p>
            
            <div class="details">
                <p><strong>🔖 N° réservation :</strong> #{{ $reservation->id }}</p>
                <p><strong>🚢 Voyage :</strong> {{ $reservation->voyage->code_voyage }}</p>
                <p><strong>📅 Date d'embarquement :</strong> {{ $reservation->date_embarquement->format('d/m/Y H:i') }}</p>
                <p><strong>🏠 Pavillon :</strong> {{ $reservation->pavillon->nom ?? 'N/A' }} ({{ $reservation->pavillon->classe ?? 'N/A' }})</p>
                <p><strong>💰 Prix total :</strong> {{ number_format($reservation->prix_total, 0, ',', ' ') }} FCFA</p>
                <p><strong>📌 Statut :</strong> {{ $reservation->statut }}</p>
            </div>
            
            <p>Vous pouvez suivre votre réservation en cliquant sur le lien ci-dessous :</p>
            <p style="text-align: center;">
                <a href="{{ route('client.reservations.show', $reservation->id) }}" class="btn">Voir ma réservation</a>
            </p>
            
            <p>Vous recevrez un email dès que l'administrateur aura confirmé votre réservation.</p>
            <p>Merci de votre confiance !</p>
            <p>L'équipe KivuPort.com</p>
        </div>
        <div class="footer">
            <p>© 2025 KivuPort.com - Gestion portuaire</p>
        </div>
    </div>
</body>
</html>