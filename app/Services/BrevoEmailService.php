<?php

namespace App\Services;

class BrevoEmailService
{
    /**
     * Envoyer un email simple (sans pièce jointe)
     */
    public static function send($to, $subject, $htmlContent)
    {
        $apiKey = env('BREVO_API_KEY');
        $fromEmail = env('MAIL_FROM_ADDRESS');
        $fromName = env('MAIL_FROM_NAME');

        $data = [
            'sender' => ['email' => $fromEmail, 'name' => $fromName],
            'to' => [['email' => $to]],
            'subject' => $subject,
            'htmlContent' => $htmlContent
        ];

        $ch = curl_init('https://api.brevo.com/v3/smtp/email');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'api-key: ' . $apiKey,
            'content-type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 201) {
            return ['success' => true, 'message' => 'Email envoyé'];
        } else {
            return ['success' => false, 'message' => $response];
        }
    }

    /**
     * Envoyer un email avec une pièce jointe (PDF)
     */
    public static function sendWithAttachment($to, $subject, $htmlContent, $pdfContent, $pdfFileName = 'facture.pdf')
    {
        $apiKey = env('BREVO_API_KEY');
        $fromEmail = env('MAIL_FROM_ADDRESS');
        $fromName = env('MAIL_FROM_NAME');

        // Encoder le PDF en base64
        $pdfBase64 = base64_encode($pdfContent);

        $data = [
            'sender' => ['email' => $fromEmail, 'name' => $fromName],
            'to' => [['email' => $to]],
            'subject' => $subject,
            'htmlContent' => $htmlContent,
            'attachment' => [
                [
                    'content' => $pdfBase64,
                    'name'    => $pdfFileName
                ]
            ]
        ];

        $ch = curl_init('https://api.brevo.com/v3/smtp/email');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'api-key: ' . $apiKey,
            'content-type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 201) {
            return ['success' => true, 'message' => 'Email avec pièce jointe envoyé'];
        } else {
            return ['success' => false, 'message' => $response];
        }
    }
}