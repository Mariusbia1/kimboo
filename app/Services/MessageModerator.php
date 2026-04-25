<?php

namespace App\Services;

class MessageModerator
{
    // Patterns de détection
    private static array $patterns = [
        'phone' => [
            '/\b(\+?[\d\s\-\.]{8,15})\b/',           // numéros génériques
            '/\b0[567]\s?\d{2}\s?\d{2}\s?\d{2}\s?\d{2}\b/', // format CI
            '/\b(\+225|00225)\s?\d{2}\s?\d{2}\s?\d{2}\s?\d{2}\b/', // CI international
            '/\b07\d{8}\b/',
            '/\b05\d{8}\b/',
            '/\b01\d{8}\b/',
        ],
        'bank' => [
            '/\b\d{4}[\s\-]?\d{4}[\s\-]?\d{4}[\s\-]?\d{4}\b/', // numéro carte
            '/\bIBAN\s?:?\s?[A-Z]{2}\d{2}[\w\s]{10,30}\b/i',    // IBAN
            '/\bCVV\s?:?\s?\d{3,4}\b/i',                          // CVV
            '/\bcode\s?(secret|pin)\s?:?\s?\d{4,6}\b/i',          // code pin
            '/\bvirement\b/i',
            '/\bwave\s?\d{8,}\b/i',                                // Wave
            '/\borange\s?money\s?\d{8,}\b/i',                      // Orange Money
        ],
        'link' => [
            '/https?:\/\/[^\s]+/',                     // liens http
            '/www\.[a-zA-Z0-9\-]+\.[a-zA-Z]{2,}/',   // www.
            '/[a-zA-Z0-9\-]+\.(com|net|org|fr|ci|bj|sn|cm)\/[^\s]+/', // domaines avec chemin
        ],
    ];

    public static function analyze(string $content): array
    {
        $detected = [];

        foreach (self::$patterns as $type => $patterns) {
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $content, $matches)) {
                    $detected[] = [
                        'type'    => $type,
                        'matched' => $matches[0],
                    ];
                    break; // un seul match par type suffit
                }
            }
        }

        return $detected;
    }

    public static function isSuspect(string $content): bool
    {
        return !empty(self::analyze($content));
    }
}
