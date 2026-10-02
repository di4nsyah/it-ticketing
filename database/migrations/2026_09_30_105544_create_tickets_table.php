<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * MVC database: migration ini yg bikin tabel tickets ada
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            // dua-duanya foreign key, nunjuk ke baris di tabel lain
            // tickets.user_id -> users.id, tickets.category_id -> categories.id
            // cascadeOnDelete: user dihapus, ticket-nya ikut terhapus
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained();

            $table->string('title');
            $table->text('description');
            $table->string('priority')->default('low');

            // status nggak ada di form, TicketController@store yg nulis 'open'
            $table->string('status')->default('open');

            // bikin created_at & updated_at otomatis, kepake buat tanggal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};