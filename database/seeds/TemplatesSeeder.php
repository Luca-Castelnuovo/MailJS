<?php

use CQ\DB\Seeder;

class TemplatesSeeder extends Seeder
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

        for ($i = 0; $i < 5; ++$i) {
            $data[] = [
                'id' => $faker->uuid,
                'user_id' => $faker->uuid,
                'user_variant' => 'Free',
                'key_id' => $faker->uuid,
                'name' => $faker->userName,
                'captcha_key' => $faker->md5,
                'email_to' => $faker->email,
                'email_replyTo' => $faker->email,
                'email_cc' => $faker->email,
                'email_bcc' => $faker->email,
                'email_fromName' => $faker->name,
                'email_subject' => $faker->sentence,
                'email_content' => $faker->paragraph,
                'updated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
            ];
        }

        $this->table('templates')->insert($data)->saveData();
    }
}
