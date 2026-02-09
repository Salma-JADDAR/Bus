<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. MODIFIER LA TABLE RESERVATIONS EXISTANTE
        Schema::table('reservations', function (Blueprint $table) {
            // Ajouter toutes les nouvelles colonnes nécessaires
            $table->string('numero_reservation')->unique()->after('id');
            $table->date('date_voyage')->after('date_reservation');
            $table->integer('nb_passagers')->default(1)->after('statut');
            $table->decimal('prix_total', 10, 2)->default(0)->after('nb_passagers');
            $table->decimal('prix_base', 10, 2)->default(0)->after('prix_total');
            $table->decimal('prix_options', 10, 2)->default(0)->after('prix_base');
            $table->boolean('has_assurance')->default(false)->after('prix_options');
            $table->boolean('has_snackbox')->default(false)->after('has_assurance');
            $table->boolean('has_seat_premium')->default(false)->after('has_snackbox');
            $table->enum('methode_paiement', ['en ligne', 'agence', 'autre'])->nullable()->after('has_seat_premium');
            $table->string('reference_paiement')->nullable()->after('methode_paiement');
            $table->string('qr_code_path')->nullable()->after('reference_paiement');
            $table->boolean('email_envoye')->default(false)->after('qr_code_path');
            $table->text('notes')->nullable()->after('email_envoye');
            
            // Changer le type de statut pour qu'il corresponde
            $table->enum('statut', ['en attente', 'confirmée', 'annulée', 'terminée'])
                ->default('en attente')
                ->change();
        });

        // 2. CRÉER LA TABLE PASSAGERS
        Schema::create('passagers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->string('nom_complet');
            $table->string('cin', 20)->nullable();
            $table->date('date_naissance')->nullable();
            $table->enum('type_passager', ['adulte', 'enfant'])->default('adulte');
            $table->integer('seat_number')->nullable();
            $table->boolean('has_assurance')->default(false);
            $table->boolean('has_snackbox')->default(false);
            $table->boolean('has_seat_premium')->default(false);
            $table->text('besoins_speciaux')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone', 20)->nullable();
            $table->timestamps();
            
            $table->index(['reservation_id', 'cin']);
        });

        // 3. CRÉER LA TABLE RESERVATION_OPTIONS
        Schema::create('reservation_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained()->onDelete('cascade');
            $table->foreignId('passager_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('option_type');
            $table->string('option_name');
            $table->text('option_description')->nullable();
            $table->decimal('prix', 8, 2);
            $table->integer('quantite')->default(1);
            $table->timestamps();
            
            $table->index(['reservation_id', 'option_type']);
        });
    }

    public function down(): void
    {
        // Supprimer d'abord les tables dépendantes
        Schema::dropIfExists('reservation_options');
        Schema::dropIfExists('passagers');
        
        // Retirer les colonnes ajoutées à reservations
        Schema::table('reservations', function (Blueprint $table) {
            $columns = [
                'numero_reservation',
                'date_voyage',
                'nb_passagers',
                'prix_total',
                'prix_base',
                'prix_options',
                'has_assurance',
                'has_snackbox',
                'has_seat_premium',
                'methode_paiement',
                'reference_paiement',
                'qr_code_path',
                'email_envoye',
                'notes',
            ];
            
            foreach ($columns as $column) {
                $table->dropColumn($column);
            }
            
            // Restaurer le type de statut original
            $table->string('statut')->change();
        });
    }
};