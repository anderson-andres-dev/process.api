<?php

namespace App\Process\Classes;

use App\Process\BD\Connection;
use App\Process\BD\DB;

Class Location
{
    public function __construct()
    {
        $connection = new Connection();
        DB::init($connection);
    }

    public function getPaises(): array
    {
        try {
            $result = DB::SELECT(['nombre', 'iso3'], 'paises');
            return $result;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
