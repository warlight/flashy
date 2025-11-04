<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BotUserAgentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bots = ['bot', 'telegram', 'twitterbot', 'curl', 'wget', 'python-requests', 'aiohttp', 'httpclient', 'scrapy', 'java', 'okhttp'];
        $data = array_map( fn ($bot) => ['bot_user_agent_part' => strtolower($bot), 'created_at' => now()], $bots);
        DB::table('bot_user_agents')->insert($data);
    }
}
