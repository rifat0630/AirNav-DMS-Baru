<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Document;
use App\Models\Inventory;
use App\Models\Notification;

use Carbon\Carbon;

class CheckSystemNotifications extends Command
{
    protected $signature = 'system:check-notifications';

    protected $description = 'Check document and inventory notifications';

    public function handle()
    {
        /*
        |--------------------------------------------------------------------------
        | CHECK DOCUMENT
        |--------------------------------------------------------------------------
        */

        $documents = Document::with('category')->get();

        foreach ($documents as $document) {

            /*
            |--------------------------------------------------------------------------
            | Gunakan expired_at sebagai tanggal kadaluarsa
            |--------------------------------------------------------------------------
            */

            if (empty($document->expired_at)) {
                continue;
            }

            try {

                $expiredDate = Carbon::parse($document->expired_at);

                $days = now()->startOfDay()->diffInDays(
                    $expiredDate->startOfDay(),
                    false
                );

                /*
                |--------------------------------------------------------------------------
                | Tentukan threshold berdasarkan kategori
                |--------------------------------------------------------------------------
                |
                | Uji Riksa  : H-90
                | ISR        : H-30
                | Kalibrasi  : H-30
                | Lainnya    : H-30
                |
                */

                $categoryName = strtolower(
                    $document->category->name ?? ''
                );

                $threshold = 30;

                // Laporan Uji Riksa & Sertifikasi
                if (str_contains($categoryName, 'uji riksa')) {
                    $threshold = 90;
                }

                // Izin Stasiun Radio (ISR)
                elseif (str_contains($categoryName, 'izin stasiun radio')) {
                    $threshold = 30;
                }

                // Laporan Kalibrasi Fasilitas CNS
                elseif (str_contains($categoryName, 'kalibrasi')) {
                    $threshold = 30;
                }

                /*
                |--------------------------------------------------------------------------
                | DOKUMEN SUDAH EXPIRED
                |--------------------------------------------------------------------------
                */

                if ($days < 0) {

                    Notification::firstOrCreate(
                        [
                            'reference' =>
                                'document_' .
                                $document->id .
                                '_expired'
                        ],
                        [
                            'user_id' =>
                                null,

                            'title' =>
                                'Dokumen Kadaluarsa',

                            'message' =>
                                $document->title .
                                ' sudah melewati masa berlaku',

                            'url' =>
                                route(
                                    'arsip.preview',
                                    $document->id,
                                    false
                                ),

                            'is_read' =>
                                0
                        ]
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | DOKUMEN H-7
                |--------------------------------------------------------------------------
                */

                elseif ($days <= 7) {

                    Notification::firstOrCreate(
                        [
                            'reference' =>
                                'document_' .
                                $document->id .
                                '_critical'
                        ],
                        [
                            'user_id' =>
                                null,

                            'title' =>
                                'Dokumen Segera Berakhir',

                            'message' =>
                                $document->title .
                                ' akan berakhir dalam ' .
                                $days .
                                ' hari',

                            'url' =>
                                route(
                                    'arsip.preview',
                                    $document->id,
                                    false
                                ),

                            'is_read' =>
                                0
                        ]
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | DOKUMEN WARNING
                |--------------------------------------------------------------------------
                */

                elseif ($days <= $threshold) {

                    Notification::firstOrCreate(
                        [
                            'reference' =>
                                'document_' .
                                $document->id .
                                '_warning'
                        ],
                        [
                            'user_id' =>
                                null,

                            'title' =>
                                'Dokumen Akan Berakhir',

                            'message' =>
                                $document->title .
                                ' akan berakhir dalam ' .
                                $days .
                                ' hari',

                            'url' =>
                                route(
                                    'arsip.preview',
                                    $document->id,
                                    false
                                ),

                            'is_read' =>
                                0
                        ]
                    );
                }

            } catch (\Exception $e) {

                continue;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CHECK BARANG RUSAK
        |--------------------------------------------------------------------------
        */

        $damagedItems = Inventory::where(
            'condition',
            'rusak'
        )->get();

        foreach ($damagedItems as $item) {

            Notification::firstOrCreate(
                [
                    'reference' =>
                        'inventory_' .
                        $item->id .
                        '_damage'
                ],
                [
                    'user_id' =>
                        null,

                    'title' =>
                        'Barang Rusak',

                    'message' =>
                        $item->name .
                        ' belum diperbaiki',

                    'url' =>
                        route(
                            'monitoring.index',
                            [],
                            false
                        ),

                    'is_read' =>
                        0
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CHECK STOCK HABIS
        |--------------------------------------------------------------------------
        */

        $emptyStocks = Inventory::where(
            'stock',
            '<=',
            0
        )->get();

        foreach ($emptyStocks as $item) {

            Notification::firstOrCreate(
                [
                    'reference' =>
                        'inventory_' .
                        $item->id .
                        '_empty'
                ],
                [
                    'user_id' =>
                        null,

                    'title' =>
                        'Stok Habis',

                    'message' =>
                        $item->name .
                        ' stok sudah habis',

                    'url' =>
                        route(
                            'monitoring.index',
                            [],
                            false
                        ),

                    'is_read' =>
                        0
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CHECK STOK MENIPIS
        |--------------------------------------------------------------------------
        */

        $lowStocks = Inventory::whereBetween(
            'stock',
            [
                1,
                3
            ]
        )->get();

        foreach ($lowStocks as $item) {

            Notification::firstOrCreate(
                [
                    'reference' =>
                        'inventory_' .
                        $item->id .
                        '_low_stock'
                ],
                [
                    'user_id' =>
                        null,

                    'title' =>
                        'Stok Menipis',

                    'message' =>
                        $item->name .
                        ' tersisa ' .
                        $item->stock .
                        ' unit',

                    'url' =>
                        route(
                            'monitoring.index',
                            [],
                            false
                        ),

                    'is_read' =>
                        0
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | SELESAI
        |--------------------------------------------------------------------------
        */

        $this->info(
            'Notification checking completed.'
        );

        return Command::SUCCESS;
    }
}