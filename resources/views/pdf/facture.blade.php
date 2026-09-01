<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Facture - KivuPort</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; border-bottom: 2px solid #1e3a8a; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; color: #1e3a8a; }
        .details { margin: 20px 0; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background: #f2f2f2; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #6b7280; }
        .qrcode { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>⚓ KivuPort.com</h1>
        <p>Facture de réservation</p>
    </div>

    <div class="details">
        <p><strong>🔖 N° réservation :</strong> {{ $reservation->id }}</p>
        <p><strong>👤 Client :</strong> {{ $reservation->client->prenom }} {{ $reservation->client->nom }}</p>
        <p><strong>📧 Email :</strong> {{ $reservation->client->email }}</p>
        <p><strong>📅 Date de réservation :</strong> {{ $reservation->date_reservation->format('d/m/Y H:i') }}</p>
        <p><strong>🚢 Voyage :</strong> {{ $reservation->voyage->code_voyage }}</p>
        <p><strong>📅 Date d'embarquement :</strong> {{ \Carbon\Carbon::parse($reservation->date_embarquement)->format('d/m/Y H:i') }}</p>
        <p><strong>🏠 Pavillon :</strong> {{ $reservation->pavillon->nom ?? 'N/A' }} ({{ $reservation->pavillon->classe ?? 'N/A' }})</p>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $reservation->type_reservation }}</td>
                <td>{{ $reservation->nombre_cargaison ?? 1 }}</td>
                <td>{{ number_format($reservation->prix_total, 0, ',', ' ') }} CDF</td>
                <td>{{ number_format($reservation->prix_total, 0, ',', ' ') }} CDF</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Total</strong></td>
                <td><strong>{{ number_format($reservation->prix_total, 0, ',', ' ') }} CDF</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="qrcode">
        <p>Scannez ce code pour voir votre réservation :</p>
        {!! QrCode::size(150)->generate(route('client.reservations.show', $reservation->id)) !!}
    </div>

    <div class="footer">
        <p>© 2025 KivuPort.com - Tous droits réservés</p>
        <p>Merci de votre confiance !</p>
    </div>
</body>
</html>