<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    | Menampilkan semua barang
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Inventory::with('user')
            ->latest();

        /*
        |--------------------------------------------------------------------------
        | Pencarian
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

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


        $inventories = $query->get();


        return view(
            'inventory.index',
            compact('inventories')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    | Form tambah barang
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
    | STORE
    |--------------------------------------------------------------------------
    | Simpan barang baru
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'name'
                => 'required|string|max:255',

            'code'
                => 'nullable|string|max:100|unique:inventories,code',

            'description'
                => 'nullable|string',

            'stock'
                => 'required|numeric|min:0',

            'unit'
                => 'required|string|max:50',

            'photo'
                => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Upload foto
        |--------------------------------------------------------------------------
        */

        $photoPath = null;


        if ($request->hasFile('photo')) {

            $photoPath =
                $request
                    ->file('photo')
                    ->store(
                        'inventory',
                        'public'
                    );
        }


        /*
        |--------------------------------------------------------------------------
        | Tentukan status stok
        |--------------------------------------------------------------------------
        */

        $stock = $request->stock;


        if ($stock <= 0) {

            $status = 'habis';

        } elseif ($stock <= 5) {

            $status = 'stok_menipis';

        } else {

            $status = 'tersedia';
        }


        /*
        |--------------------------------------------------------------------------
        | Simpan barang
        |--------------------------------------------------------------------------
        */

        Inventory::create([

            'name'
                => $request->name,

            'code'
                => $request->code,

            'description'
                => $request->description,

            'stock'
                => $stock,

            'unit'
                => $request->unit,

            'photo'
                => $photoPath,

            'status'
                => $status,

            'user_id'
                => Auth::id(),

        ]);


        return redirect()
            ->route('inventory.index')
            ->with(
                'success',
                'Barang berhasil ditambahkan'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    | Detail barang + riwayat stok
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $inventory = Inventory::with([
            'user',
            'movements.user'
        ])->findOrFail($id);


        return view(
            'inventory.show',
            compact('inventory')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    | Hanya pemilik atau admin
    |--------------------------------------------------------------------------
    */

    public function edit($id)
    {
        $inventory = Inventory::findOrFail($id);


        $this->authorizeInventoryOwner(
            $inventory
        );


        return view(
            'inventory.edit',
            compact('inventory')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    | Hanya pemilik atau admin
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        $id
    ) {

        $inventory = Inventory::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Cek hak akses
        |--------------------------------------------------------------------------
        */

        $this->authorizeInventoryOwner(
            $inventory
        );


        /*
        |--------------------------------------------------------------------------
        | Validasi
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'name'
                => 'required|string|max:255',

            'code'
                => 'nullable|string|max:100|unique:inventories,code,' . $id,

            'description'
                => 'nullable|string',

            'stock'
                => 'required|numeric|min:0',

            'unit'
                => 'required|string|max:50',

            'photo'
                => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

        ]);


        /*
        |--------------------------------------------------------------------------
        | Upload foto baru
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('photo')) {

            if (
                $inventory->photo &&
                Storage::disk('public')->exists(
                    $inventory->photo
                )
            ) {

                Storage::disk('public')->delete(
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


        /*
        |--------------------------------------------------------------------------
        | Status stok
        |--------------------------------------------------------------------------
        */

        $stock = $request->stock;


        if ($stock <= 0) {

            $status = 'habis';

        } elseif ($stock <= 5) {

            $status = 'stok_menipis';

        } else {

            $status = 'tersedia';
        }


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        $inventory->update([

            'name'
                => $request->name,

            'code'
                => $request->code,

            'description'
                => $request->description,

            'stock'
                => $stock,

            'unit'
                => $request->unit,

            'status'
                => $status,

            'photo'
                => $inventory->photo,

        ]);


        return redirect()
            ->route('inventory.index')
            ->with(
                'success',
                'Data barang berhasil diperbarui'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    | Hanya pemilik atau admin
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {
        $inventory =
            Inventory::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Cek hak akses
        |--------------------------------------------------------------------------
        */

        $this->authorizeInventoryOwner(
            $inventory
        );


        /*
        |--------------------------------------------------------------------------
        | Hapus foto
        |--------------------------------------------------------------------------
        */

        if (
            $inventory->photo &&
            Storage::disk('public')->exists(
                $inventory->photo
            )
        ) {

            Storage::disk('public')->delete(
                $inventory->photo
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Hapus riwayat pergerakan stok
        |--------------------------------------------------------------------------
        */

        InventoryMovement::where(
            'inventory_id',
            $inventory->id
        )->delete();


        /*
        |--------------------------------------------------------------------------
        | Hapus barang
        |--------------------------------------------------------------------------
        */

        $inventory->delete();


        return redirect()
            ->route('inventory.index')
            ->with(
                'success',
                'Barang berhasil dihapus'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | STOCK IN
    |--------------------------------------------------------------------------
    | Tambah stok
    |--------------------------------------------------------------------------
    */

    public function stockIn(
        Request $request,
        $id
    ) {

        $inventory =
            Inventory::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Cek hak akses
        |--------------------------------------------------------------------------
        */

        $this->authorizeInventoryOwner(
            $inventory
        );


        /*
        |--------------------------------------------------------------------------
        | Validasi
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'quantity'
                => 'required|numeric|min:0.01',

            'description'
                => 'nullable|string|max:1000',

        ]);


        DB::transaction(function () use (
            $request,
            $inventory
        ) {

            $before =
                $inventory->stock;


            $quantity =
                $request->quantity;


            $after =
                $before + $quantity;


            /*
            |--------------------------------------------------------------------------
            | Update stok
            |--------------------------------------------------------------------------
            */

            $inventory->stock =
                $after;


            /*
            |--------------------------------------------------------------------------
            | Update status
            |--------------------------------------------------------------------------
            */

            if ($after <= 0) {

                $inventory->status =
                    'habis';

            } elseif ($after <= 5) {

                $inventory->status =
                    'stok_menipis';

            } else {

                $inventory->status =
                    'tersedia';
            }


            $inventory->save();


            /*
            |--------------------------------------------------------------------------
            | Simpan riwayat
            |--------------------------------------------------------------------------
            */

            InventoryMovement::create([

                'inventory_id'
                    => $inventory->id,

                'user_id'
                    => Auth::id(),

                'type'
                    => 'masuk',

                'quantity'
                    => $quantity,

                'stock_before'
                    => $before,

                'stock_after'
                    => $after,

                'description'
                    => $request->description,

            ]);
        });


        return redirect()
            ->route(
                'inventory.show',
                $inventory->id
            )
            ->with(
                'success',
                'Stok berhasil ditambahkan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | STOCK OUT
    |--------------------------------------------------------------------------
    | Kurangi stok
    |--------------------------------------------------------------------------
    */

    public function stockOut(
        Request $request,
        $id
    ) {

        $inventory =
            Inventory::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Cek hak akses
        |--------------------------------------------------------------------------
        */

        $this->authorizeInventoryOwner(
            $inventory
        );


        /*
        |--------------------------------------------------------------------------
        | Validasi
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'quantity'
                => 'required|numeric|min:0.01',

            'description'
                => 'nullable|string|max:1000',

        ]);


        $quantity =
            $request->quantity;


        /*
        |--------------------------------------------------------------------------
        | Jangan sampai stok minus
        |--------------------------------------------------------------------------
        */

        if (
            $quantity >
            $inventory->stock
        ) {

            return back()
                ->with(
                    'error',
                    'Stok tidak mencukupi. Stok tersedia: '
                    . $inventory->stock
                    . ' '
                    . $inventory->unit
                );
        }


        DB::transaction(function () use (
            $request,
            $inventory,
            $quantity
        ) {

            $before =
                $inventory->stock;


            $after =
                $before - $quantity;


            /*
            |--------------------------------------------------------------------------
            | Update stok
            |--------------------------------------------------------------------------
            */

            $inventory->stock =
                $after;


            /*
            |--------------------------------------------------------------------------
            | Update status
            |--------------------------------------------------------------------------
            */

            if ($after <= 0) {

                $inventory->status =
                    'habis';

            } elseif ($after <= 5) {

                $inventory->status =
                    'stok_menipis';

            } else {

                $inventory->status =
                    'tersedia';
            }


            $inventory->save();


            /*
            |--------------------------------------------------------------------------
            | Simpan riwayat
            |--------------------------------------------------------------------------
            */

            InventoryMovement::create([

                'inventory_id'
                    => $inventory->id,

                'user_id'
                    => Auth::id(),

                'type'
                    => 'keluar',

                'quantity'
                    => $quantity,

                'stock_before'
                    => $before,

                'stock_after'
                    => $after,

                'description'
                    => $request->description,

            ]);
        });


        return redirect()
            ->route(
                'inventory.show',
                $inventory->id
            )
            ->with(
                'success',
                'Stok berhasil dikurangi.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | AUTHORIZE OWNER
    |--------------------------------------------------------------------------
    | Keamanan utama Inventory
    |--------------------------------------------------------------------------
    */

    private function authorizeInventoryOwner(
        Inventory $inventory
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Admin boleh mengelola semua barang
        |--------------------------------------------------------------------------
        */

        if (
            Auth::user()->role === 'admin'
        ) {

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | User biasa hanya boleh mengelola
        | barang yang dibuat olehnya
        |--------------------------------------------------------------------------
        */

        if (
            (int) $inventory->user_id !==
            (int) Auth::id()
        ) {

            abort(
                403,
                'Anda tidak memiliki izin untuk mengelola barang ini.'
            );
        }
    }
}