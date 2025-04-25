<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\item;

class itemController extends Controller
{
    public function index() {

        $data = [
            'title' => 'Item',
            'url_json' => url('items/get_data'),
            'url' => url('/items'),
        ];
        return view('item', $data);


    }

    public function getData()
    {
        return response()->json([
            'status' => true,
            'data' => item::all(),
            'message' => 'data berhasil ditemukan',
        ])->header('Content-Type', 'application/json')->setStatusCode(200);
    }

    public function storeData(Request $request)

    {
        $data = $request->only(['item_name', 'status']);

        $validator = Validator::make($data, [
            'item_name' => ['required', 'unique:items', 'min:3', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ],422);
        }

        item::create($data);

        return response()->json([
            'status' => true,
            'message' => 'data berhasil ditambahkan',
        ])->header('Content-Type', 'application/json')->setStatusCode(201);
    }

    public function getDataByID($idItem)
    {
        $item = Item::where('id', $idItem)->first();
        if(!$item) {
            return response()->json([
                'status' => false,
                'message' => 'data tidak ditemukan',
            ])->header('Content-Type', 'application/json')->setStatusCode(404);
        }

        return response()->json([
            'status' => true,
            'data' => $item,
            'message' => 'data berhasi; ditemukan',
        ])->header('Content-Type', 'application/json')->setStatusCode(200);
    }
    public function updateData(Request $request, $idItem) // function untuk mengubah data
{
    $item = Item::where('id', $idItem)->first();
    if(!$item) {
        return response()->json([
            'status' => false,
            'message' => 'data tidak ditemukan',
        ])->header('Content-Type', 'application/json')->setStatusCode(404);
    }

    $data = $request->only(['item_name', 'status']);

    $validator = Validator::make($data, [
        'item_name' => ['required', 'min:3', 'max:255', 'unique:items,item_name,' . $item->id],
        'status' => ['required', 'in:1,0'],
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => $validator->errors()
        ], 422);
    }

    $item->update($data);

    return response()->json([
        'status' => true,
        'message' => 'data berhasil diubah',
    ])->header('Content-Type', 'application/json')->setStatusCode(200);
}

public function destroyData($idItem) // function untuk menghapus data
{
    $item = Item::where('id', $idItem)->first();
    if(!$item) {
        return response()->json([
            'status' => false,
            'message' => 'data tidak ditemukan',
        ])->header('Content-Type', 'application/json')->setStatusCode(404);
    }

    $item->delete();

    return response()->json([
        'status' => true,
        'message' => 'data berhasil dihapus',
    ])->header('Content-Type', 'application/json')->setStatusCode(200);
}


}
