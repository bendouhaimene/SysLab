<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Test;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        $tests = [
            ['name' => 'NFS (CBC)',          'category' => 'Hematology',   'price' => 850,  'unit' => '×10⁹/L', 'normal_min' => 4.5,  'normal_max' => 11.0],
            ['name' => 'Glycémie',           'category' => 'Biochemistry', 'price' => 400,  'unit' => 'mmol/L', 'normal_min' => 3.9,  'normal_max' => 5.5],
            ['name' => 'Créatinine',         'category' => 'Biochemistry', 'price' => 500,  'unit' => 'µmol/L', 'normal_min' => 62.0, 'normal_max' => 115.0],
            ['name' => 'TSH',                'category' => 'Immunology',   'price' => 1200, 'unit' => 'mIU/L',  'normal_min' => 0.4,  'normal_max' => 4.0],
            ['name' => 'Cholestérol Total',  'category' => 'Biochemistry', 'price' => 450,  'unit' => 'mmol/L', 'normal_min' => 0.0,  'normal_max' => 5.2],
            ['name' => 'ECBU',               'category' => 'Microbiology', 'price' => 750,  'unit' => null,     'normal_min' => null, 'normal_max' => null],
            ['name' => 'Transaminases (TGO)','category' => 'Biochemistry', 'price' => 600,  'unit' => 'U/L',    'normal_min' => 5.0,  'normal_max' => 40.0],
            ['name' => 'Transaminases (TGP)','category' => 'Biochemistry', 'price' => 600,  'unit' => 'U/L',    'normal_min' => 7.0,  'normal_max' => 56.0],
            ['name' => 'Groupage Sanguin',   'category' => 'Hematology',   'price' => 300,  'unit' => null,     'normal_min' => null, 'normal_max' => null],
            ['name' => 'CRP',                'category' => 'Immunology',   'price' => 700,  'unit' => 'mg/L',   'normal_min' => 0.0,  'normal_max' => 5.0],
        ];

        foreach ($tests as $test) {
            Test::create($test);
        }
    }
}