<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\ChangeControl;
use App\Services\ChangeControlService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChangeControlTest extends TestCase
{
    use RefreshDatabase;

    protected $service;
    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        \Illuminate\Support\Facades\Notification::fake();
        $this->seed(\Database\Seeders\RolesAndPermissionsSeeder::class);
        $this->service = new ChangeControlService();
        $this->admin = User::factory()->create();
        $this->admin->assignRole('super-admin');
        $this->actingAs($this->admin);
    }

    public function test_it_creates_change_request_for_product_update()
    {
        $product = Product::create(['name' => ['es' => 'Original'], 'code' => '123', 'slug' => ['es' => 'orig']]);

        $newData = ['name' => ['es' => 'Updated'], 'code' => '123'];

        $changeControl = $this->service->createChangeRequest($product, $newData, 'update');

        $this->assertDatabaseHas('change_controls', [
            'id' => $changeControl->id,
            'changeable_id' => $product->id,
            'status' => ChangeControl::STATUS_PENDING,
            'type' => 'update',
        ]);

        // Product should NOT be updated yet
        $this->assertEquals(['es' => 'Original'], $product->fresh()->name);
    }

    public function test_approval_applies_changes()
    {
        $product = Product::create(['name' => ['es' => 'Original'], 'code' => '123', 'slug' => ['es' => 'orig']]);

        $newData = ['name' => ['es' => 'Updated Name']];

        $changeControl = $this->service->createChangeRequest($product, $newData, 'update');

        $this->service->approve($changeControl);

        $this->assertEquals(ChangeControl::STATUS_APPROVED, $changeControl->fresh()->status);
        $this->assertEquals(['es' => 'Updated Name'], $product->fresh()->name);
    }

    public function test_create_request_creates_model_on_approval()
    {
        // ChangeControl for creation (Product doesn't exist yet)
        $data = [
            'name' => ['es' => 'New Product'],
            'code' => 'NEW001',
            'slug' => ['es' => 'new-product'],
            'published' => true
        ];

        // Pass empty model instance
        $changeControl = $this->service->createChangeRequest(new Product(), $data, 'create');

        $this->assertEquals('create', $changeControl->type);
        $this->assertNull($changeControl->changeable_id);

        $this->service->approve($changeControl);

        $this->assertDatabaseHas('products', ['code' => 'NEW001']);
        $product = Product::where('code', 'NEW001')->first();

        // Check if change control was linked back (optional feature I implemented)
        // In my service: $changeControl->update(['changeable_id' => $model->id]);
        $this->assertEquals($product->id, $changeControl->fresh()->changeable_id);
    }
}
