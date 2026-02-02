<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('segments', function (Blueprint $table) {
            $table->id();
            $table->decimal('tarif', 8, 2);
            $table->time('duree_estimee');
            $table->float('distance_km');
            $table->foreignId('programme_id')->constrained()->onDelete('cascade');
            $table->foreignId('depart_etape_id')->constrained('etapes');
            $table->foreignId('arrivee_etape_id')->constrained('etapes');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('segments');
    }
};