<?php

namespace App\Services;

use App\Models\Document;
use App\Models\Inventory;
use App\Models\Notification;
use Carbon\Carbon;


class NotificationService
{


    public function generate()
    {


        $this->checkDocuments();


        $this->checkInventory();


    }




    private function checkDocuments()
    {


        $documents = Document::all();



        foreach($documents as $document)
        {


            if(!$document->tanggal_berlaku)
            {
                continue;
            }



            preg_match(
                '/s\/d\s(.+)/',
                $document->tanggal_berlaku,
                $matches
            );



            if(!isset($matches[1]))
            {
                continue;
            }



            try {


                $expired =
                Carbon::parse($matches[1]);



                $days =
                now()->diffInDays(
                    $expired,
                    false
                );



                if($days < 0)
                {


                    Notification::firstOrCreate([

                        'title'=>'Dokumen Kadaluarsa',

                        'message'=>
                        $document->title.
                        ' sudah melewati masa berlaku',

                        'type'=>'danger'

                    ]);


                }


                elseif($days <= 30)
                {


                    Notification::firstOrCreate([

                        'title'=>'Dokumen Hampir Habis',

                        'message'=>
                        $document->title.
                        ' akan habis dalam '.
                        $days.
                        ' hari',

                        'type'=>'warning'

                    ]);


                }


            }
            catch(\Exception $e)
            {

            }


        }


    }






    private function checkInventory()
    {


        $items =
        Inventory::all();



        foreach($items as $item)
        {


            if(
                strtolower($item->condition ?? '')
                ==
                'rusak'
            )
            {


                Notification::firstOrCreate([

                    'title'=>'Barang Rusak',

                    'message'=>
                    $item->name.
                    ' belum diperbaiki',

                    'type'=>'danger'

                ]);


            }


        }


    }



}