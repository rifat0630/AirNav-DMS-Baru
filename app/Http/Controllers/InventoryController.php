<?php

namespace App\Http\Controllers;


use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Helpers\ActivityLogger;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;



class InventoryController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | LIST BARANG
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {


        $query = Inventory::with('user')
            ->latest();



        if ($request->filled('search')) {


            $search = $request->search;



            $query->where(function($q) use ($search){


                $q->where(
                    'name',
                    'like',
                    "%{$search}%"
                )


                ->orWhere(
                    'code',
                    'like',
                    "%{$search}%"
                );


            });


        }



        $inventories =
            $query->get();




        return view(

            'inventory.index',

            compact('inventories')

        );


    }






    /*
    |--------------------------------------------------------------------------
    | FORM TAMBAH BARANG
    |--------------------------------------------------------------------------
    */

    public function create()
    {

        return view(
            'inventory.create'
        );

    }






    /*
    |--------------------------------------------------------------------------
    | SIMPAN BARANG
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {



        $request->validate([


            'name'
                =>
                'required|string|max:255',


            'code'
                =>
                'nullable|string|max:100|unique:inventories,code',


            'description'
                =>
                'nullable|string',


            'stock'
                =>
                'required|numeric|min:0',


            'unit'
                =>
                'required|string|max:50',


            'photo'
                =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',


        ]);







        $photoPath = null;



        if($request->hasFile('photo')){


            $photoPath =
                $request
                ->file('photo')
                ->store(
                    'inventory',
                    'public'
                );


        }







        $stock =
            $request->stock;





        $status =
            $this->stockStatus(
                $stock
            );








        $inventory =
            Inventory::create([



                'name'
                    =>
                    $request->name,


                'code'
                    =>
                    $request->code,


                'description'
                    =>
                    $request->description,


                'stock'
                    =>
                    $stock,


                'unit'
                    =>
                    $request->unit,


                'photo'
                    =>
                    $photoPath,


                'status'
                    =>
                    $status,


                'user_id'
                    =>
                    Auth::id(),



            ]);









        ActivityLogger::create(


            'inventory',


            $inventory->id,


            'Tambah Barang',


            'Menambahkan barang '
            .$inventory->name
            .' dengan stok awal '
            .$inventory->stock
            .' '
            .$inventory->unit



        );








        return redirect()

            ->route(
                'inventory.index'
            )

            ->with(
                'success',
                'Barang berhasil ditambahkan'
            );



    }








    /*
    |--------------------------------------------------------------------------
    | DETAIL BARANG
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {


        $inventory =
            Inventory::with([
                'movements',
                'user'
            ])
            ->findOrFail($id);




        return view(

            'inventory.show',

            compact('inventory')

        );


    }








    /*
    |--------------------------------------------------------------------------
    | FORM EDIT
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {


        $inventory =
            Inventory::findOrFail($id);




        $this->authorizeOwner(
            $inventory
        );




        return view(

            'inventory.edit',

            compact('inventory')

        );


    }

        /*
    |--------------------------------------------------------------------------
    | UPDATE BARANG
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    )
    {


        $inventory =
            Inventory::findOrFail($id);



        $this->authorizeOwner(
            $inventory
        );





        $request->validate([


            'name'
                =>
                'required|string|max:255',


            'code'
                =>
                'nullable|string|max:100|unique:inventories,code,'.$id,


            'description'
                =>
                'nullable|string',


            'stock'
                =>
                'required|numeric|min:0',


            'unit'
                =>
                'required|string|max:50',


            'photo'
                =>
                'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',


        ]);





        if($request->hasFile('photo')){


            if(
                $inventory->photo &&
                Storage::disk('public')
                ->exists($inventory->photo)
            ){

                Storage::disk('public')
                    ->delete(
                        $inventory->photo
                    );

            }



            $inventory->photo =
                $request
                ->file('photo')
                ->store(
                    'inventory',
                    'public'
                );


        }






        $inventory->update([



            'name'
                =>
                $request->name,


            'code'
                =>
                $request->code,


            'description'
                =>
                $request->description,


            'stock'
                =>
                $request->stock,


            'unit'
                =>
                $request->unit,


            'status'
                =>
                $this->stockStatus(
                    $request->stock
                ),



        ]);







        ActivityLogger::create(


            'inventory',


            $inventory->id,


            'Edit Barang',


            'Mengubah data barang '
            .$inventory->name



        );







        return redirect()

            ->route(
                'inventory.index'
            )

            ->with(
                'success',
                'Data barang berhasil diperbarui'
            );


    }








    /*
    |--------------------------------------------------------------------------
    | HAPUS BARANG
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {


        $inventory =
            Inventory::findOrFail($id);




        $this->authorizeOwner(
            $inventory
        );






        ActivityLogger::create(


            'inventory',


            $inventory->id,


            'Hapus Barang',


            'Menghapus barang '
            .$inventory->name



        );








        if(
            $inventory->photo &&
            Storage::disk('public')
            ->exists($inventory->photo)
        ){

            Storage::disk('public')
                ->delete(
                    $inventory->photo
                );

        }






        $inventory->delete();







        return back()

            ->with(
                'success',
                'Barang berhasil dihapus'
            );


    }








    /*
    |--------------------------------------------------------------------------
    | STOK MASUK
    |--------------------------------------------------------------------------
    */

    public function stockIn(
        Request $request,
        $id
    )
    {


        $inventory =
            Inventory::findOrFail($id);





        $this->authorizeOwner(
            $inventory
        );





        $request->validate([


            'quantity'
                =>
                'required|integer|min:1',


            'note'
                =>
                'nullable|string',


        ]);






        DB::transaction(function()
        use(
            $request,
            $inventory
        ){



            $before =
                $inventory->stock;



            $quantity =
                $request->quantity;



            $after =
                $before + $quantity;






            $inventory->update([


                'stock'
                    =>
                    $after,


                'status'
                    =>
                    $this->stockStatus(
                        $after
                    ),


            ]);






            InventoryMovement::create([


                'inventory_id'
                    =>
                    $inventory->id,


                'user_id'
                    =>
                    Auth::id(),


                'type'
                    =>
                    'masuk',


                'quantity'
                    =>
                    $quantity,


                'stock_before'
                    =>
                    $before,


                'stock_after'
                    =>
                    $after,


                'note'
                    =>
                    $request->note,


            ]);






            ActivityLogger::create(


                'inventory',


                $inventory->id,


                'Stok Masuk',


                'Tambah stok '
                .$quantity
                .' '
                .$inventory->unit
                .' | Sebelum: '
                .$before
                .' | Sesudah: '
                .$after



            );




        });







        return back()

            ->with(
                'success',
                'Stok berhasil ditambahkan'
            );


    }









    /*
    |--------------------------------------------------------------------------
    | STOK KELUAR
    |--------------------------------------------------------------------------
    */

    public function stockOut(
        Request $request,
        $id
    )
    {


        $inventory =
            Inventory::findOrFail($id);




        $this->authorizeOwner(
            $inventory
        );






        $request->validate([


            'quantity'
                =>
                'required|integer|min:1',


            'note'
                =>
                'nullable|string',


        ]);






        if(
            $request->quantity >
            $inventory->stock
        ){

            return back()

                ->with(
                    'error',
                    'Stok tidak mencukupi'
                );

        }







        DB::transaction(function()
        use(
            $request,
            $inventory
        ){



            $before =
                $inventory->stock;



            $quantity =
                $request->quantity;



            $after =
                $before - $quantity;






            $inventory->update([


                'stock'
                    =>
                    $after,


                'status'
                    =>
                    $this->stockStatus(
                        $after
                    ),


            ]);







            InventoryMovement::create([



                'inventory_id'
                    =>
                    $inventory->id,


                'user_id'
                    =>
                    Auth::id(),


                'type'
                    =>
                    'keluar',


                'quantity'
                    =>
                    $quantity,


                'stock_before'
                    =>
                    $before,


                'stock_after'
                    =>
                    $after,


                'note'
                    =>
                    $request->note,


            ]);








            ActivityLogger::create(


                'inventory',


                $inventory->id,


                'Stok Keluar',


                'Kurangi stok '
                .$quantity
                .' '
                .$inventory->unit
                .' | Sebelum: '
                .$before
                .' | Sesudah: '
                .$after



            );



        });







        return back()

            ->with(
                'success',
                'Stok berhasil dikurangi'
            );


    }









    /*
    |--------------------------------------------------------------------------
    | STATUS STOK
    |--------------------------------------------------------------------------
    */

    private function stockStatus($stock)
    {


        if($stock <= 0){

            return 'habis';

        }


        elseif($stock <= 5){

            return 'stok_menipis';

        }


        return 'tersedia';


    }








    /*
    |--------------------------------------------------------------------------
    | CEK PEMILIK
    |--------------------------------------------------------------------------
    */

    private function authorizeOwner(
        Inventory $inventory
    )
    {


        if(
            Auth::user()->role === 'admin'
        ){

            return;

        }





        if(
            (int)$inventory->user_id
            !==
            (int)Auth::id()
        ){

            abort(
                403,
                'Anda tidak memiliki izin mengelola barang ini.'
            );

        }


    }



}