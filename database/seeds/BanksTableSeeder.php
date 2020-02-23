<?php

use App\Banks\Bank;
use Illuminate\Database\Seeder;

class BanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contents = Storage::get('fints_institute.csv');
        $rows = explode("\r\n", $contents);
        foreach ($rows as $key => $row) {
            $columns = explode(';', $row);
            if (! $columns[0] || ! is_numeric($columns[0]) || ! $columns[23])
            {
                continue;
            }

            Bank::create([
                'id' => $columns[0],
                'blz' => (int) $columns[1],
                'name' => $columns[2],
                'city' => $columns[3],
                'url' => $columns[23],
            ]);
        }
    }
}
