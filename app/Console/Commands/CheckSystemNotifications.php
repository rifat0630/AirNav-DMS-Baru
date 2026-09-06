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

        $documents = Document::all();


        foreach ($documents as $document) {

            if (empty($document->tanggal_berlaku)) {
                continue;
            }


            try {

                preg_match(
                    '/s\/d\s(.+)/',
                    $document->tanggal_berlaku,
                    $matches
                );


                if (!isset($matches[1])) {
                    continue;
                }


                $expiredDate = Carbon::parse(
                    trim($matches[1])
                );


                $days = now()->diffInDays(
                    $expiredDate,
                    false
                );


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
                            'title' =>
                                'Dokumen Kadaluarsa',

                            'message' =>
                                $document->title .
                                ' sudah melewati masa berlaku',

                            'type' =>
                                'danger',

                            'url' =>
                                route(
                                    'documents.show',
                                    $document->id
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
                            'title' =>
                                'Dokumen Segera Berakhir',

                            'message' =>
                                $document->title .
                                ' akan berakhir dalam ' .
                                $days .
                                ' hari',

                            'type' =>
                                'danger',

                            'url' =>
                                route(
                                    'documents.show',
                                    $document->id
                                ),

                            'is_read' =>
                                0
                        ]

                    );

                }


                /*
                |--------------------------------------------------------------------------
                | DOKUMEN H-30
                |--------------------------------------------------------------------------
                */

                elseif ($days <= 30) {

                    Notification::firstOrCreate(

                        [
                            'reference' =>
                                'document_' .
                                $document->id .
                                '_warning'
                        ],

                        [
                            'title' =>
                                'Dokumen Akan Berakhir',

                            'message' =>
                                $document->title .
                                ' akan berakhir dalam ' .
                                $days .
                                ' hari',

                            'type' =>
                                'warning',

                            'url' =>
                                route(
                                    'documents.show',
                                    $document->id
                                ),

                            'is_read' =>
                                0
                        ]

                    );

                }

            }

            catch (\Exception $e) {

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
                    'title' =>
                        'Barang Rusak',

                    'message' =>
                        $item->name .
                        ' belum diperbaiki',

                    'type' =>
                        'danger',

                    'url' =>
                        route(
                            'inventory.show',
                            $item->id
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
                    'title' =>
                        'Stok Habis',

                    'message' =>
                        $item->name .
                        ' stok sudah habis',

                    'type' =>
                        'danger',

                    'url' =>
                        route(
                            'inventory.show',
                            $item->id
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
                    'title' =>
                        'Stok Menipis',

                    'message' =>
                        $item->name .
                        ' tersisa ' .
                        $item->stock .
                        ' unit',

                    'type' =>
                        'warning',

                    'url' =>
                        route(
                            'inventory.show',
                            $item->id
                        ),

                    'is_read' =>
                        0
                ]

            );

        }



        $this->info(
            'Notification checking completed.'
        );


        return Command::SUCCESS;

    }

}