<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = Inventory::with('user')
            ->latest();


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where(
                    'name',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'code',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'serial_numbers',
                    'like',
                    '%' . $search . '%'
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $inventories = $query->paginate(10)
            ->withQueryString();


        return view(
            'inventory.index',
            compact('inventories')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('inventory.create');
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate([

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'code' => [
                'required',
                'string',
                'max:100'
            ],

            'stock' => [
                'required',
                'integer',
                'min:0'
            ],

            'unit' => [
                'required',
                'string',
                'max:50'
            ],

            'condition' => [
                'required',
                'in:normal,rusak'
            ],

            'serial_numbers' => [
                'nullable',
                'array'
            ],

            'serial_numbers.*' => [
                'nullable',
                'string',
                'max:255'
            ],

            'photos' => [
                'nullable',
                'array',
                'max:3'
            ],

            'photos.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120'
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | SERIAL NUMBER
        |--------------------------------------------------------------------------
        */

        $serialNumbers = collect(
            $request->input('serial_numbers', [])
        )
        ->map(function ($serial) {

            return trim($serial);

        })
        ->filter(function ($serial) {

            return $serial !== '';

        })
        ->values()
        ->toArray();


        /*
        |--------------------------------------------------------------------------
        | FOTO
        |--------------------------------------------------------------------------
        */

        $photos = [];


        if ($request->hasFile('photos')) {

            foreach ($request->file('photos') as $photo) {

                $path = $photo->store(
                    'inventory',
                    'public'
                );

                $photos[] = $path;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | SIMPAN
        |--------------------------------------------------------------------------
        */

        $inventory = Inventory::create([

            'name' => $request->name,

            'code' => $request->code,

            'stock' => $request->stock,

            'unit' => $request->unit,

            'serial_numbers' => $serialNumbers,

            'photos' => $photos,

            'condition' => $request->condition,

            'user_id' => Auth::id(),

        ]);


        /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOG
        |--------------------------------------------------------------------------
        */

        if (class_exists(ActivityLog::class)) {

            ActivityLog::create([

                'user_id' => Auth::id(),

                'activity' => 'Tambah Barang',

                'description' =>
                    'Menambahkan barang inventory "' .
                    $inventory->name .
                    '"',

                'module' => 'inventory',

            ]);

        }


        return redirect()
            ->route('inventory.index')
            ->with(
                'success',
                'Barang berhasil ditambahkan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */

    public function show(string $id)
    {
        $inventory = Inventory::with('user')
            ->findOrFail($id);


        return view(
            'inventory.show',
            compact('inventory')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(string $id)
    {
        $inventory = Inventory::findOrFail($id);


        return view(
            'inventory.edit',
            compact('inventory')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        string $id
    ) {

        $inventory = Inventory::findOrFail($id);


        $request->validate([

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'code' => [
                'required',
                'string',
                'max:100'
            ],

            'stock' => [
                'required',
                'integer',
                'min:0'
            ],

            'unit' => [
                'required',
                'string',
                'max:50'
            ],

            'condition' => [
                'required',
                'in:normal,rusak'
            ],

            'serial_numbers' => [
                'nullable',
                'array'
            ],

            'serial_numbers.*' => [
                'nullable',
                'string',
                'max:255'
            ],

            'photos' => [
                'nullable',
                'array',
                'max:3'
            ],

            'photos.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120'
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | SERIAL NUMBER
        |--------------------------------------------------------------------------
        */

        $serialNumbers = collect(
            $request->input('serial_numbers', [])
        )
        ->map(function ($serial) {

            return trim($serial);

        })
        ->filter(function ($serial) {

            return $serial !== '';

        })
        ->values()
        ->toArray();


        /*
        |--------------------------------------------------------------------------
        | FOTO LAMA
        |--------------------------------------------------------------------------
        */

        $photos = $inventory->photos ?? [];


        /*
        |--------------------------------------------------------------------------
        | FOTO BARU
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('photos')) {

            /*
            | Hapus foto lama
            */

            foreach ($photos as $oldPhoto) {

                if (
                    $oldPhoto &&
                    Storage::disk('public')
                        ->exists($oldPhoto)
                ) {

                    Storage::disk('public')
                        ->delete($oldPhoto);

                }

            }


            $photos = [];


            /*
            | Upload maksimal 3
            */

            foreach (
                array_slice(
                    $request->file('photos'),
                    0,
                    3
                )
                as $photo
            ) {

                $path = $photo->store(
                    'inventory',
                    'public'
                );

                $photos[] = $path;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $inventory->update([

            'name' => $request->name,

            'code' => $request->code,

            'stock' => $request->stock,

            'unit' => $request->unit,

            'serial_numbers' => $serialNumbers,

            'photos' => $photos,

            'condition' => $request->condition,

        ]);


        /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOG
        |--------------------------------------------------------------------------
        */

        if (class_exists(ActivityLog::class)) {

            ActivityLog::create([

                'user_id' => Auth::id(),

                'activity' => 'Edit Barang',

                'description' =>
                    'Mengubah barang inventory "' .
                    $inventory->name .
                    '"',

                'module' => 'inventory',

            ]);

        }


        return redirect()
            ->route('inventory.index')
            ->with(
                'success',
                'Data barang berhasil diperbarui.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | DESTROY
    |--------------------------------------------------------------------------
    */

    public function destroy(string $id)
    {
        $inventory = Inventory::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | HAPUS FOTO
        |--------------------------------------------------------------------------
        */

        if ($inventory->photos) {

            foreach (
                $inventory->photos
                as $photo
            ) {

                if (
                    Storage::disk('public')
                        ->exists($photo)
                ) {

                    Storage::disk('public')
                        ->delete($photo);

                }

            }

        }


        /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOG
        |--------------------------------------------------------------------------
        */

        if (class_exists(ActivityLog::class)) {

            ActivityLog::create([

                'user_id' => Auth::id(),

                'activity' => 'Hapus Barang',

                'description' =>
                    'Menghapus barang inventory "' .
                    $inventory->name .
                    '"',

                'module' => 'inventory',

            ]);

        }


        $inventory->delete();


        return redirect()
            ->route('inventory.index')
            ->with(
                'success',
                'Barang berhasil dihapus.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | STOCK IN
    |--------------------------------------------------------------------------
    */

    public function stockIn(
        Request $request,
        string $id
    ) {

        $request->validate([

            'quantity' => [
                'required',
                'integer',
                'min:1'
            ]

        ]);


        $inventory = Inventory::findOrFail($id);


        $inventory->increment(
            'stock',
            $request->quantity
        );


        return redirect()
            ->route('inventory.index')
            ->with(
                'success',
                'Stok berhasil ditambahkan.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | STOCK OUT
    |--------------------------------------------------------------------------
    */

    public function stockOut(
        Request $request,
        string $id
    ) {

        $request->validate([

            'quantity' => [
                'required',
                'integer',
                'min:1'
            ]

        ]);


        $inventory = Inventory::findOrFail($id);


        if (
            $request->quantity >
            $inventory->stock
        ) {

            return back()
                ->with(
                    'error',
                    'Stok tidak mencukupi.'
                );

        }


        $inventory->decrement(
            'stock',
            $request->quantity
        );


        return redirect()
            ->route('inventory.index')
            ->with(
                'success',
                'Stok berhasil dikurangi.'
            );
    }
}