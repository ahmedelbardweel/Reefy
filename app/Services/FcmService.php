<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FcmService
{
    /**
     * Send a push notification to a specific FCM token using HTTP v1 API.
     * 
     * @param string $token The recipient's FCM token.
     * @param string $title The notification title.
     * @param string $body The notification message body.
     * @param array $data Optional custom data to send with the notification.
     * @return bool
     */
    public static function sendPushNotification($token, $title, $body, $data = [])
    {
        if (!$token) {
            return false;
        }

        try {
            $accessToken = self::getAccessToken();
            
            if (!$accessToken) {
                Log::error('FCM: Failed to get access token');
                return false;
            }

            // Get project ID from credentials file usually, or config. 
            // We decoded it in getAccessToken, lets re-read or optimize later. 
            // For now, re-reading for safety/simplicity in this context.
            $credentialsPath = storage_path('app/firebase_credentials.json');
            if (!file_exists($credentialsPath)) {
                Log::error('FCM: Credentials file not found at ' . $credentialsPath);
                return false;
            }
            $serviceAccount = json_decode(file_get_contents($credentialsPath), true);
            $projectId = $serviceAccount['project_id'];

            $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

            $payload = [
                'message' => [
                    'token' => $token,
                    'notification' => [
                        'title' => $title,
                        'body' => $body,
                    ],
                    'data' => $data, // Data must be strings in v1
                ],
            ];

            $response = Http::withToken($accessToken)
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($url, $payload);

            if ($response->successful()) {
                Log::info('FCM notification sent successfully to token: ' . $token);
                return true;
            } else {
                Log::error('FCM notification failed: ' . $response->body());
                return false;
            }

        } catch (\Exception $e) {
            Log::error('FCM execution error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate OAuth2 Access Token manually without extra dependencies.
     */
    private static function getAccessToken()
    {
        $credentialsPath = storage_path('app/firebase_credentials.json');
        
        if (!file_exists($credentialsPath)) {
            Log::error('FCM: Credentials file missing.');
            return null;
        }

        $serviceAccount = json_decode(file_get_contents($credentialsPath), true);
        
        $now = time();
        $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $payload = json_encode([
            'iss' => $serviceAccount['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now,
        ]);

        $base64Header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64Payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        $signatureInput = $base64Header . "." . $base64Payload;
        $signature = '';
        
        if (!openssl_sign($signatureInput, $signature, $serviceAccount['private_key'], 'SHA256')) {
            Log::error('FCM: OpenSSL sign failed.');
            return null;
        }

        $base64Signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        $jwt = $signatureInput . "." . $base64Signature;

        // Exchange JWT for Access Token
        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        if ($response->successful()) {
            return $response->json()['access_token'];
        }

        Log::error('FCM: Auth failed: ' . $response->body());
        return null;
    }
}
