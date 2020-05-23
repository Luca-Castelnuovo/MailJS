<?php

use CQ\DB\Seeder;

class HistorySeeder extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $faker = self::faker();
        $data = [];

        for ($i = 0; $i < 5; $i++) {
            $data[] = [
                'template_id'     => $faker->randomDigit,
                'template_owner'  => $faker->uuid,
                'template_params' => '{"name":"123","email":"test@gmail.com"}',
                'origin'          => $faker->domainName,
                'user_ip'         => $faker->ipv4,
                'created_at'      => date('Y-m-d H:i:s'),
            ];
        }

        $this->table('history')->insert($data)->saveData();
    }
}
