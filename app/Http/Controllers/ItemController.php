<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function addItem(Request $request) {
        // Validate request
        $data =$request->all();
        $rules = [
            'item_name' => 'required',
            'size' => 'required',
            'color' => 'required',
            'category' => 'required',
        ];
        $valid = Validator::make($data, $rules);
        if (count($valid->errors())) {
            return response([
                'status' => 'failed',
                'errors' => $valid->errors()
            ],422);
        }

        // Create new item
        $item = Item::create([
            'user_id' => Auth::user()->id, // Fetch logged-in user ID
            'item_name' => $request->item_name,
            'size' => $request->size,
            'color' => $request->color,
            'category' => $request->category,
        ]);
        // Return JSON response
        return response()->json([
            'message' => 'Item added successfully',
            'item' => $item
        ], 201);
    }
    public function fetchItem() {
        $data =Item::all();
        return response()->json(
            [
                'items' => $data,
                'message'=>'Fetched successfully',
            ]
        );
    }
    public function DeleteItem($id) {
        $item = Item::find($id); // Corrected `$id`

        if (!$item) {
            return response()->json([
                'message' => 'Item not found',
            ], 404);
        }

        $item->delete();

        return response()->json([
            'message' => 'Deleted successfully',
        ]);
    }

}
