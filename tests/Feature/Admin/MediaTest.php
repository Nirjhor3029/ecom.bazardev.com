<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Modules\Catalog\Models\AttributeSet;
use App\Modules\Catalog\Models\Product;
use App\Modules\Core\Models\Media;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->seed(RoleAndPermissionSeeder::class);
        $this->admin = User::factory()->create();
        $this->admin->assignRole('Super Admin');
    }

    protected function bigTestImage(): UploadedFile
    {
        $gd = imagecreatetruecolor(2400, 1800);
        imagefill($gd, 0, 0, imagecolorallocate($gd, 200, 150, 60));
        $path = tempnam(sys_get_temp_dir(), 'img').'.jpg';
        imagejpeg($gd, $path, 95);
        imagedestroy($gd);

        return new UploadedFile($path, 'big.jpg', 'image/jpeg', null, true);
    }

    public function test_large_image_is_resized_and_compressed_on_upload(): void
    {
        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/admin/media', ['file' => $this->bigTestImage()])
            ->assertCreated();

        $response->assertJsonPath('data.width', 1600); // scaled down from 2400
        Storage::disk('public')->assertExists(Media::first()->path);
    }

    public function test_non_image_files_are_stored_without_processing(): void
    {
        $file = UploadedFile::fake()->create('notes.txt', 10, 'text/plain');

        $response = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/admin/media', ['file' => $file])
            ->assertCreated();

        $response->assertJsonPath('data.width', null)->assertJsonPath('data.kind', 'other');
    }

    public function test_deleting_media_in_use_requires_force(): void
    {
        $set = AttributeSet::query()->create(['code' => 'honey', 'name' => 'Honey']);
        $product = Product::query()->create(['attribute_set_id' => $set->id, 'name' => ['bn' => 'x'], 'slug' => 'x', 'type' => 'simple']);

        $upload = $this->actingAs($this->admin, 'sanctum')
            ->postJson('/api/v1/admin/media', ['file' => UploadedFile::fake()->image('p.jpg', 100, 100)])
            ->json('data');

        $this->actingAs($this->admin, 'sanctum')
            ->postJson("/api/v1/admin/products/{$product->id}/media", ['media_id' => $upload['id']])
            ->assertCreated();

        $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/v1/admin/media/{$upload['id']}")
            ->assertStatus(409)->assertJsonPath('error.code', 'MEDIA_IN_USE');

        $this->actingAs($this->admin, 'sanctum')
            ->deleteJson("/api/v1/admin/media/{$upload['id']}?force=1")
            ->assertStatus(204);

        $this->assertDatabaseMissing('media', ['id' => $upload['id']]);
    }
}
