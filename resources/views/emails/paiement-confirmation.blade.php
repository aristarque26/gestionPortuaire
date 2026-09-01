<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paiement reçu - KivuPort</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1e3a8a; color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #f9fafb; padding: 20px; border-radius: 0 0 10px 10px; }
        .details { background: white; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .footer { text-align: center; font-size: 12px; color: #6b7280; margin-top: 20px; }
        h1 { margin: 0; font-size: 24px; }
        .btn { display: inline-block; background: #2563eb; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 5px; }
        .btn-pay { background: #10b981; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚓ KivuPort.com</h1>
            <p>✅ Paiement confirmé</p>
        </div>
        <div class="content">
            <h2>Merci {{ $reservation->client->prenom }} {{ $reservation->client->nom }} !</h2>
            <p>Nous vous confirmons la réception de votre paiement pour la réservation <strong>n°{{ $reservation->id }}</strong>.</p>
            
            <div class="details">
                <p><strong>💰 Montant réglé :</strong> {{ number_format($reservation->prix_total, 0, ',', ' ') }} FCFA</p>
                <p><strong>🚢 Voyage :</strong> {{ $reservation->voyage->code_voyage }}</p>
                <p><strong>📅 Date d'embarquement :</strong> {{ \Carbon\Carbon::parse($reservation->date_embarquement)->format('d/m/Y H:i') }}</p>
                <p><strong>💳 Moyen de paiement :</strong> Maisha Pay</p>
            </div>
            
            <p>Vous pouvez télécharger votre facture ci-dessous ou la retrouver en pièce jointe :</p>
            <p style="text-align: center;">
                <a href="{{ route('client.reservation.facture', $reservation->id) }}" class="btn">📄 Télécharger la facture</a>
            </p>
            
            <p>Bon voyage avec KivuPort.com ! ⚓</p>
        </div>
        <div class="footer">
            <p>© 2025 KivuPort.com - Gestion portuaire</p>
        </div>
    </div>
</body>
</html>