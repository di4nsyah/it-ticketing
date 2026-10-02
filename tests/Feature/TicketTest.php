<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    private function category(): Category
    {
        return Category::create(['name' => 'Hardware']);
    }

    private function ticketFor(User $user, Category $category, array $extra = []): Ticket
    {
        return Ticket::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Printer tidak bisa ngeprint',
            'description' => 'Kertas keluar tapi kosong.',
            'priority' => 'high',
            'status' => 'open',
            ...$extra,
        ]);
    }

    public function test_karyawan_dapat_membuat_ticket(): void
    {
        $karyawan = User::factory()->create(['role' => 'karyawan']);
        $category = $this->category();

        $response = $this->actingAs($karyawan)->post(route('tickets.store'), [
            'title' => 'Wifi putus',
            'description' => 'Tidak bisa konek sejak pagi.',
            'category_id' => $category->id,
            'priority' => 'medium',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('tickets.index'));

        $this->assertDatabaseHas('tickets', [
            'user_id' => $karyawan->id,
            'title' => 'Wifi putus',
            'priority' => 'medium',
            'status' => 'open',
        ]);
    }

    public function test_teknisi_tidak_bisa_membuat_ticket(): void
    {
        $teknisi = User::factory()->create(['role' => 'teknisi']);

        $this->actingAs($teknisi)
            ->get(route('tickets.create'))
            ->assertForbidden();
    }

    public function test_karyawan_hanya_melihat_ticket_miliknya(): void
    {
        $category = $this->category();
        $karyawan = User::factory()->create(['role' => 'karyawan']);
        $seorangLain = User::factory()->create(['role' => 'karyawan']);

        $this->ticketFor($karyawan, $category, ['title' => 'Tiket milik saya']);
        $this->ticketFor($seorangLain, $category, ['title' => 'Tiket milik orang lain']);

        $this->actingAs($karyawan)
            ->get(route('tickets.index'))
            ->assertOk()
            ->assertSee('Tiket milik saya')
            ->assertDontSee('Tiket milik orang lain');
    }

    public function test_teknisi_melihat_semua_ticket(): void
    {
        $category = $this->category();
        $teknisi = User::factory()->create(['role' => 'teknisi']);
        $karyawan = User::factory()->create(['role' => 'karyawan']);

        $this->ticketFor($karyawan, $category, ['title' => 'Tiket karyawan']);

        $this->actingAs($teknisi)
            ->get(route('tickets.index'))
            ->assertOk()
            ->assertSee('Tiket karyawan')
            ->assertSee($karyawan->name);
    }

    public function test_karyawan_dapat_membatalkan_ticket_miliknya(): void
    {
        $category = $this->category();
        $karyawan = User::factory()->create(['role' => 'karyawan']);
        $ticket = $this->ticketFor($karyawan, $category);

        $this->actingAs($karyawan)
            ->patch(route('tickets.cancel', $ticket))
            ->assertRedirect(route('tickets.index'));

        $ticket->refresh();

        $this->assertSame('cancelled', $ticket->status);
        $this->assertSame('Cancelled', $ticket->statusLabel());
    }

    public function test_karyawan_tidak_bisa_membatalkan_ticket_orang_lain(): void
    {
        $category = $this->category();
        $karyawan = User::factory()->create(['role' => 'karyawan']);
        $pemilik = User::factory()->create(['role' => 'karyawan']);
        $ticket = $this->ticketFor($pemilik, $category);

        $this->actingAs($karyawan)
            ->patch(route('tickets.cancel', $ticket))
            ->assertForbidden();

        $this->assertSame('open', $ticket->fresh()->status);
    }
}
