<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Dictionnaire des valeurs par défaut pour l'ensemble des paramètres
     */
    public static function defaults(): array
    {
        return [
            // Général & Coordonnées
            'site_name'             => 'Kimboo',
            'site_tagline'          => 'Cours particuliers & soutien scolaire d\'excellence',
            'contact_email'         => 'contact@kimboo.net',
            'contact_phone'         => '+225 07 00 00 00 00',
            'contact_whatsapp'      => '+225 07 00 00 00 00',
            'contact_address'       => 'Abidjan, Côte d\'Ivoire',
            'contact_hours'         => 'Lun - Sam : 08h00 - 19h00',
            'site_currency'         => 'FCFA',

            // Page d'Accueil & Contenu
            'hero_title'            => 'Trouvez le professeur idéal en Côte d’Ivoire',
            'hero_subtitle'         => 'Des cours particuliers à domicile ou en ligne avec les meilleurs enseignants sélectionnés pour votre réussite.',
            'hero_cta_text'         => 'Trouver un professeur',
            'banner_active'         => '0',
            'banner_text'           => 'Rentrée scolaire : réservez vos cours particuliers dès aujourd\'hui !',
            'home_teachers_title'   => 'Nos professeurs particuliers en vedette',
            'home_teachers_subtitle'=> 'Découvrez les meilleurs enseignants vérifiés et disponibles près de chez vous',
            'become_teacher_title'  => 'Vous êtes enseignant ? Rejoignez Kimboo et donnez des cours particuliers',

            // Réseaux sociaux
            'facebook'              => 'https://facebook.com/kimboo',
            'instagram'             => 'https://instagram.com/kimboocotedivoire',
            'tiktok'                => 'https://tiktok.com/@kimboo.cte.divoir',
            'whatsapp'              => '',
            'youtube'               => '',
            'linkedin'              => '',
            'twitter'               => '',

            // Tarifs & Plateforme
            'commission_percent'    => '0',
            'min_hourly_rate'       => '2000',
            'max_hourly_rate'       => '70000',
            'default_search_radius' => '15',

            // SEO & Référencement Google
            'seo_title'             => 'Kimboo — Cours particuliers & soutien scolaire en Côte d\'Ivoire',
            'seo_description'       => 'Kimboo cours particuliers et soutien scolaire en Cote d’ivoire. Trouvez le professeur qu’il vous faut.',
            'seo_keywords'          => 'cours particuliers, soutien scolaire, abidjan, prof particuliers, cote d\'ivoire, maths, anglais',
        ];
    }

    /**
     * Récupère un paramètre avec mise en cache
     */
    public static function get(string $key, ?string $default = null): string
    {
        $defaultValue = $default ?? (static::defaults()[$key] ?? '');

        return Cache::rememberForever('site_setting_' . $key, function () use ($key, $defaultValue) {
            $val = static::where('key', $key)->value('value');
            if ($val !== null && $val !== '') {
                return $val;
            }
            return $defaultValue;
        });
    }

    /**
     * Définit un paramètre et invalide le cache
     */
    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value ?? '']);
        Cache::forget('site_setting_' . $key);
        Cache::forget('site_settings_all');
    }

    /**
     * Récupère tous les paramètres avec leurs valeurs par défaut
     */
    public static function allSettings(): array
    {
        return Cache::rememberForever('site_settings_all', function () {
            $stored = static::pluck('value', 'key')->toArray();
            $defaults = static::defaults();

            $result = [];
            foreach ($defaults as $key => $defaultVal) {
                $result[$key] = (isset($stored[$key]) && $stored[$key] !== '') ? $stored[$key] : $defaultVal;
            }

            // Clés personnalisées supplémentaires si présentes en BDD
            foreach ($stored as $key => $val) {
                if (!array_key_exists($key, $result)) {
                    $result[$key] = $val;
                }
            }

            return $result;
        });
    }
}
