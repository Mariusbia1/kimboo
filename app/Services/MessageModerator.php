<?php

namespace App\Services;

class MessageModerator
{
    // Patterns de détection (les numéros de téléphone sont tolérés sur Kimboo)
    private static array $patterns = [
        'bank' => [
            '/\b\d{4}[\s\-]\d{4}[\s\-]\d{4}[\s\-]\d{4}\b/', // 4x4 chiffres carte (avec séparateurs)
            '/\b(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|3[47][0-9]{13}|6(?:011|5[0-9]{2})[0-9]{12})\b/', // Cartes Visa, Mastercard, Amex
            '/\b\d{16}\b/',                                       // 16 chiffres continus (carte bancaire)
            '/\bIBAN\s?:?\s?[A-Z]{2}\d{2}[\w\s]{10,34}\b/i',    // IBAN explicite
            '/\b[A-Z]{2}\d{2}\s?\d{4}\s?\d{4}\s?\d{4}\s?\d{4}\s?\d{2,4}\b/i', // Format IBAN
            '/\b(CVV|CVC)\s?:?\s?\d{3,4}\b/i',                   // CVV / CVC
            '/\bRIB\s?:?\s?\d{5}[\s\-]?\d{5}[\s\-]?[\w\d]{11}[\s\-]?\d{2}\b/i', // Format RIB
            '/\bcode\s?(secret|pin)\s?:?\s?\d{4,6}\b/i',          // code pin / code secret
            '/\bcarte\s+(bancaire|de\s+cr[ée]dit|de\s+paiement|bleue)\b/i', // mentions de cartes
            '/\bnum[ée]ro\s+de\s+carte\b/i',                      // numéro de carte
            '/\bcoordonn[ée]es\s+bancaires\b/i',                  // coordonnées bancaires
            '/\bcompte\s+bancaire\b/i',                           // compte bancaire
            '/\bvirement\s+bancaire\b/i',                         // virement bancaire
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
