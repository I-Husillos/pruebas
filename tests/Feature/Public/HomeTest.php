<?php

test('homepage is accessible', function () {
    $this->seed([
        \Database\Seeders\MarketsSeeder::class,
        \Database\Seeders\LanguagesSeeder::class,
    ]);

    $response = $this->get('/es/es');

    $response->assertStatus(200);
});
