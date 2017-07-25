<?php

namespace Bahjaat\Daisycon\Repository;

interface DataImportInterface {

    public function importData($url, $program_id, $feed_id, $custom_categorie);

}