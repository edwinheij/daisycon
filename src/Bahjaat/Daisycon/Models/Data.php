<?php
namespace Bahjaat\Daisycon\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Bahjaat\Daisycon\Helper\DaisyconHelper;
use Config;
use Str;

class Data extends \Eloquent
{
    protected $fillable = []; // see constructor
    // protected $guarded = ['link'];
    // protected $table = 'data';

    // protected $data;

    public function __construct(array $attributes = array())
    {
        // $db_fields_to_import = DaisyconHelper::db_fields_to_import();
        // $custom_db_fields_to_import = Config::get('daisycon.custom_db_fields_to_import');

        $this->fillable(array_merge(DaisyconHelper::getDatabaseFields(), array('program_id', 'feed_id', 'custom_categorie')));
        parent::__construct($attributes);
    }

    public static function dataVoorbereiden($data)
    {
        $data = parent::fixTransportationType($data);
        $data = parent::fixBoardingType($data);
        $data = parent::fixLandcodes($data);
        $data = parent::fixAccommodationName($data);
        $data = parent::fixStars($data);
        $data = parent::fixDescription($data);
        $data = parent::fixPositions($data);
        $data = parent::fixDuration($data);

        // Slug aan het einde laten staan
        foreach (DaisyconHelper::getDatabaseFields() as $fieldname) {
            if (str_contains($fieldname, 'slug_')) {
                $originalFieldName = str_replace('slug_', '', $fieldname);
                if (!empty($data->$originalFieldName)) $data->$fieldname = Str::slug($data->$originalFieldName);
            }

            // Encoding aanpassen
            $data->$fieldname = html_entity_decode(($data->$fieldname), ENT_QUOTES, "utf-8"); // nog testen

            // Trim alle velden
            $data->$fieldname = trim($data->$fieldname);

            // Specialchars aanpassen
            $regex = '/http:\/\/.*/';
            if (isset($data->$fieldname) && preg_match($regex, $data->$fieldname)) {
                $data->$fieldname = htmlspecialchars_decode(urldecode($data->$fieldname)); // sneller dan html_entity_decode @ http://stackoverflow.com/questions/11723641/how-to-decode-the-amp-from-a-url-so-that-header-works-urldecode-not-working

                // Brakke utm_ arguments vervangen in url's
                $regex = '/(&utm_\w+=%\w+%)&/';
                if (preg_match_all($regex, $data->$fieldname, $matches)) {
                    $data->$fieldname = str_replace($matches[1], '', $data->$fieldname);
                }
            }
        }
        return $data;
    }

    public static function boot()
    {
        parent::boot();

//        static::updating(function($data)
//        {
//            $data = static::dataVoorbereiden($data);
//        });
//
//        static::creating(function($data)
//        {
//            $data = static::dataVoorbereiden($data);
//        });

        static::saving(function ($data) {
            $data = static::dataVoorbereiden($data);
        });


    }

    public function fixTransportationType($data)
    {
        $transpArr = array(
            'EV' => 'Eigen vervoer',
            'BU' => 'Bus',
            'VL' => 'Vliegtuig',
            'flight' => 'Vliegtuig',
            'own' => 'Eigen vervoer',
            'eigen vervoer' => 'Eigen vervoer'
        );
        // dd($data);
        if (isset($data->transportation_type)) {
            if (array_key_exists($data->transportation_type, $transpArr)) {
                $data->transportation_type = $transpArr[$data->transportation_type];
            }
        }
        return $data;
    }

    public function fixBoardingType($data)
    {
        $boardArr = array(
            'LG' => 'Logies',
            'LO' => 'Logies en ontbijt',
            'AI' => 'All inclusive',
            'HP' => 'Halfpension',
            'VP' => 'Volpension'
        );
        if (isset($data->board_type)) {
            if (array_key_exists($data->board_type, $boardArr)) {
                $data->board_type = $boardArr[$data->board_type];
            }
        }
        $data->board_type = ucfirst($data->board_type); // logies >> Logies
        return $data;
    }

    public function fixLandcodes($data)
    {
        // $lc = $this->landcodes;
        $fields = array(
            'country_of_destination',
            'country_of_origin'
        );

        foreach ($fields as $field) {
            if (isset($data->$field) && strlen($data->$field) == 2) {
                try {
                    $cc = Countrycode::where('countrycode', $data->$field)->remember(60)->firstOrFail();
                    if (!empty($cc->country)) $data->$field = $cc->country;
                } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $exception) {
                    \Log::error($data->$field . ' niet in Countrycode tabel gevonden');
                }
            }
        }
        return $data;
    }

    public function fixAccommodationName($data)
    {
        if ((!isset($data->accommodation_name) || empty($data->accommodation_name)) && (isset($data->title) && !empty($data->title))) {
            $data->accommodation_name = $data->title;
        }
        return $data;
    }

    function fixStars($data)
    {
        if (isset($data->stars)) {
            if (!preg_match('/^[0-9]$/', $data->stars)) {
                //$this->update($row->id, array('stars' => $m[0]));
                if (preg_match('/[\d]/', $data->stars, $m)) {
                    //var_dump($m);
                    $data->stars = $m[0];
                }
            }
            if ((int)$data->stars == 0) {
                $data->stars = '';
            }
        }
        return $data;
    }

    public function fixDescription($data)
    {
        if (isset($data->description)) {
            // Init newDesc
            $newDesc = trim($data->description);

            // Lege desc opvullen met title
            if (strlen($newDesc) <= 0) {
                $newDesc = trim($data->title);
                //die ($newDesc);
            }

            // Laat niet '...' zien aan 't einde. Zoek in laatste zin 'punt' of 'komma' en sluit daar netjes af.
            $posKomma = $posPunt = 0;
            if (substr($newDesc, -3) == '...') {
                $posKomma = strrpos(substr($newDesc, 0, -3), ',');
                $posPunt = strrpos(substr($newDesc, 0, -3), '.');
                if ($posKomma > $posPunt) {
                    $newDesc = substr($newDesc, 0, $posKomma) . '.';
                } elseif ($posKomma < $posPunt) {
                    $newDesc = substr($newDesc, 0, ($posPunt + 1));
                }
            }

            // Sluit zin netjes af.
            if (preg_match('/[^\.\?\!]$/', $newDesc)) {
                $newDesc .= '.';
            }

            // Strip tags
            $newDesc = strip_tags($newDesc);

            // Strip meer ...
            // inkoopagenten strippen
            /*
            TEST-DATA
            Appelscha." - Jack de Jong, inkoopagent Zeeland.
            Appelscha." - Jack de Jong, inkoopagent Zeeland
            Appelscha." - Jack de Jong, inkoopagent
            Appelscha."- Jack de Jong, inkoopagent
            et een huifkarrentocht!”– Jack, inkoopagent.
             (gelieve vóór aankomst te bespreken)." Jack de Jong – inkoopagent.
            omfort.’- Margreet Huizinga, inkoopagent.
            asdfasdf' - Ingrid, inkoopagent Zeeland.
            older.” - Ingrid, inkoopagent Zeeland.
            */
            $newDesc = preg_replace("/([\”\'\’\"\”])\s?[-–]?\s?[a-zA-Z -']+[, -–]+\sinkoopagent([a-zA-Z -']+)?.?/mi", '$1', $newDesc);

            $data->description = $newDesc;
        }
        return $data;
    }

    public function fixPositions($data)
    {
        if (isset($data->latitude)) {
            switch ($data->latitude) {
                case '0.00000':
                case '':
                    $data->latitude = '';
            }
        }
        if (isset($data->longitude)) {
            switch ($data->longitude) {
                case '0.00000':
                case '':
                    $data->longitude = '';
            }
        }
        return $data;
    }

    public function fixDuration($data)
    {
        if (isset($data->duration) && !empty($data->duration) && $data->duration <= 0) {
            if (isset($data->departure_date) && isset($data->end_date)) {
                $start = new \DateTime($data->departure_date);
                $einde = new \DateTime($data->end_date);
                $diff = $start->diff($einde);
                //echo $diff->format('%R'); // use for point out relation: smaller/greater
                //echo $diff->days;
                $data->duration = ($diff->days + 1);
            } else {
                $data->duration = '';
            }
        }
        return $data;
    }
}