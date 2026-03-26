<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            [
                'key' => 'site_name',
                'value' => 'CMS Headless',
                'type' => 'string',
                'group' => 'general',
                'label' => 'Nombre del Sitio',
                'description' => 'Nombre que aparece en el título del sitio y login',
            ],
        ];

        foreach ($defaults as $setting) {
            Setting::firstOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
