<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PaymentMethodsSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('payment_methods')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('payment_methods')->insert([
            [
                'payment_method' => 'Efectivo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_method' => 'Transferencia bancaria',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_method' => 'Yape',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_method' => 'Plin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_method' => 'Tarjeta de debito',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_method' => 'Tarjeta de credito',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_method' => 'Deposito BBVA',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_method' => 'Deposito BCP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_method' => 'Deposito Interbank',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_method' => 'Deposito Scotiabank',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_method' => 'Pago en oficina',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_method' => 'POS presencial',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_method' => 'Pago web',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_method' => 'Billetera digital',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_method' => 'Cheque',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_method' => 'Pago corporativo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_method' => 'Cupon de descuento',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_method' => 'Pago mixto',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_method' => 'Financiamiento',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'payment_method' => 'Metodo demo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
