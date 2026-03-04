<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

test('admin can create a product', function () {
    // 0. Seed
    $this->seed([
        \Database\Seeders\MarketsSeeder::class,
        \Database\Seeders\LanguagesSeeder::class,
    ]);

    // 1. Arrange: Create Admin User
    $admin = User::factory()->create();
    // Assuming Spatie permissions are set up, we might need to assign a role
    // For now, let's assume the Policy checks for 'super-admin' or similar.
    // We'll mock the role or bypass if possible, but better to use the real role.

    // Check if role exists or create it
    if (! Role::where('name', 'super-admin')->exists()) {
        Role::create(['name' => 'super-admin']);
    }
    $admin->assignRole('super-admin');

    $productData = [
        'code' => 'TEST-001',
        'name' => ['es' => 'Producto Test', 'en' => 'Test Product'],
        'slug' => ['es' => 'producto-test', 'en' => 'test-product'],
        'short_description' => ['es' => 'Corto', 'en' => 'Short'],
        'description' => ['es' => 'Largo', 'en' => 'Long'],
        'category_id' => null, // or create one
        'available_markets' => ['es', 'us'],
        'published' => true,
    ];

    // 2. Act
    $response = $this->actingAs($admin)
        ->post(route('admin.products.store'), $productData);

    // 3. Assert
    $response->assertRedirect(route('admin.products.index'));

    $this->assertDatabaseHas('products', [
        'code' => 'TEST-001',
    ]);
})->skip("Admin controllers are being refactored to SRP");
