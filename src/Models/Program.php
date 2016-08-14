<?php

namespace Bahjaat\Daisycon\Models;

class Program extends \Eloquent
{
    protected $fillable =
        [
            'program_id',
            'advertiser_id',
            'url',
            'program_logo',
            'name',
            'display_url',
            'description',
            'productfeed',
            'subscribed_media_ids',
            'category_name',
            'status',
            'startdate',
            'enddate'
        ];

    // public function activeProgram()
    //    {
    //        return $this->belongsTo('Bahjaat\Daisycon\Models\ActiveProgram', 'program_id', 'program_id');
    //    }
    public function feeds()
    {
        // return $this->belongsTo('Bahjaat\Daisycon\Models\Feed', 'program_id', 'program_id');
        return $this->hasMany(Feed::class, 'program_id', 'program_id');
    }

}

// php artisan generate:migration create_programs_table --fields="program_id:integer:unsigned, advertiser_id:integer:unsigned, url:string:nullable, program_logo:string, name:string, description:string, productfeed:string, subscribed_media_ids:string, category_name:string, status:string, startdate:date, enddate:date" --path="workbench\bahjaat\daisycon\src\migrations"
// php artisan generate:migration add_field_to_programs_table --fields="display_url:string:nullable" --path="workbench\bahjaat\daisycon\src\database\migrations"
// php artisan generate:migration remove_fields_from_programs_table --fields="program_logo:string:nullable, subscribed_media_ids:string:nullable, category_name:string:nullable" --path="workbench\bahjaat\daisycon\src\database\migrations"

// php artisan migrate --path="workbench/bahjaat/daisycon/src/database/migrations/"