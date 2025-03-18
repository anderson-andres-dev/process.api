<?php

namespace App\Process\Classes;

use App\Process\BD\Connection;
use App\Process\BD\DB;

class Location
{
    public function __construct()
    {
        $connection = new Connection();
        DB::init($connection);
    }

    public function getPaises(): array
    {
        try {
            $result = DB::SELECT(['id', 'nombre'], 'paises');
            return $result;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getProvincias(int $id_pais): array
    {
        try {
            $result = DB::SELECT(['id','nombre'], 'provincias', [
                'id_pais' => ['=', $id_pais]
            ]);
            return $result;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function getCantones(int $id_provincia): array
    {
        try {
            $result = DB::SELECT(['id','nombre'], 'cantones', [
                'id_provincia' => ['=', $id_provincia]
            ]);
            return $result;
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
