<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Motivations extends Model
{
    protected $fillable = [
        'text',
    ];

    private static $motivations = [
        'Неважно, как медленно ты продвигаешься. Главное — ты не останавливаешься.',
        'Подтянутая женщина всегда добивается того, чего не может добиться женщина в халате.',
        'Если меня что-то и двигает вперед, то только моя слабость, которую я ненавижу и превращаю в мою силу',
        'Мотивация 4',
        'Мотивация 5',
        'Мотивация 6',
        'Мотивация 7',
        'Мотивация 8',
        'Мотивация 9',
        'Мотивация 10',
    ];

    private static $settings = [
    	'period_motivation' => 10,
    	'interval' => 300000,
    ];

    public static function getMotivations()
    {
        return self::$motivations;
    }

    public static function getMotivationsSettings()
    {
        return self::$settings;
    }
}
