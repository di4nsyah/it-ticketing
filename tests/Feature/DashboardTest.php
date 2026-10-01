<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private function ticket(User $user, Category $category, string $status): Ticket
    {
        return Ticket::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Tiket',
            'description' => 'Deskripsi.',
            'priority' => 'medium',
            'status' => $status,
        ]);
    }

    public function test_karyawan_hanya_melihat_statistik_ticket_miliknya(): void
    {
        $category = Category::create(['name' => 'Hardware']);
        $karyawan = User::factory()->create(['role' => 'karyawan']);
        $seorangLain = User::factory()->create(['role' => 'karyawan']);

        $this->ticket($karyawan, $category, 'open');
        $this->ticket($karyawan, $category, 'done');
        $this->ticket($seorangLain, $category, 'open');
        $this->ticket($seorangLain, $category, 'open');
        $this->ticket($seorangLain, $category, 'open');

        $this->actingAs($karyawan)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('total', 2)
            ->assertViewHas('counts', fn ($counts) => $counts->get('open') === 1
                && $counts->get('done') === 1
                && ! $counts->has('in_progress'));
    }

    public function test_teknisi_melihat_statistik_seluruh_ticket(): void
    {
        $category = Category::create(['name' => 'Hardware']);
        $teknisi = User::factory()->create(['role' => 'teknisi']);
        $karyawan = User::factory()->create(['role' => 'karyawan']);

        $this->ticket($karyawan, $category, 'open');
        $this->ticket($karyawan, $category, 'in_progress');
        $this->ticket($karyawan, $category, 'in_progress');

        $this->actingAs($teknisi)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('total', 3)
            ->assertViewHas('counts', fn ($counts) => $counts->get('open') === 1
                && $counts->get('in_progress') === 2);
    }
}
