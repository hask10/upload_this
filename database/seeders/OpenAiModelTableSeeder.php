<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OpenAiModelTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('open_ai_models')->delete();

        \DB::table('open_ai_models')->insert(array(
            array('id' => '1', 'name' => 'Ada (The Fastest but Simplest)', 'key' => 'text-ada-001', 'created_at' => NULL, 'updated_at' => NULL, 'deleted_at' => NULL),
            array('id' => '2', 'name' => 'Babbage (Average)', 'key' => 'text-babbage-001', 'created_at' => NULL, 'updated_at' => NULL, 'deleted_at' => NULL),
            array('id' => '3', 'name' => 'Curie (Good)', 'key' => 'text-curie-001', 'created_at' => NULL, 'updated_at' => NULL, 'deleted_at' => NULL),
            array('id' => '4', 'name' => 'Davinci (Powerful but Most Expensive)', 'key' => 'text-davinci-001', 'created_at' => NULL, 'updated_at' => NULL, 'deleted_at' => NULL),
            array('id' => '5', 'name' => 'ChatGPT 3.5', 'key' => 'gpt-3.5-turbo', 'created_at' => NULL, 'updated_at' => NULL, 'deleted_at' => NULL),
            array('id' => '6', 'name' => 'ChatGPT 4 (Beta)', 'key' => 'gpt-4', 'created_at' => NULL, 'updated_at' => NULL, 'deleted_at' => NULL)
        ));
    }
}
