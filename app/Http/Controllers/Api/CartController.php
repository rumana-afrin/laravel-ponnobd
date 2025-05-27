<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index($id)
    {
        $product = Product::find($id);
        $upload = DB::table('uploads')->where('id', $product->thumbnail_img)->first();

        if ($upload) {
            $product->thumbnail_img = url($upload->file_name);
        }
        $photoData = json_decode($product->photos, true);
        if (!empty($photoData)) {
            foreach ($photoData as $key => $photo) {
                $photo_id = $photo['id'];
                $photoFile = DB::table('uploads')->where('id', $photo_id)->first();

                if ($photoFile) {
                    $photoData[$key]['url'] = url($photoFile->file_name);
                }
            }
        }

        $product->photos = $photoData;
        return response()->json($product);
    }
}
