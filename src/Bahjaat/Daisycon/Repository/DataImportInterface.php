<?php
/**
 * User: Edwin Heij
 * Date: 30-1-2015
 * Time: 21:48
 */

namespace Bahjaat\Daisycon\Repository;


interface DataImportInterface {
    public function importData($url, $program_id, $feed_id, $custom_categorie);
}