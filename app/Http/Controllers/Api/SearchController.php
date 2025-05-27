<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Attribute;


class SearchController extends Controller
{

   
    public function search(Request $request)
    {
        $limit = $request->input('limit', 12);
        $sort = $request->input('sort', 'popularity');
        $query = $request->query('query');
        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');

        $allProductQuery = Product::where('name', 'like', "%{$query}%")->whereBetween('unit_price', [$min_price, $max_price]);
     
        $allProductQuery->when($sort, function ($query) use ($sort) {
            $query->when($sort == 'popularity', fn($q) => $q->topSell());
            $query->when($sort == 'latest', fn($q) => $q->latest());
            $query->when($sort == 'oldest', fn($q) => $q->oldest());
            $query->when($sort == 'low to high', fn($q) => $q->orderByPrice());
            $query->when($sort == 'high to low', fn($q) => $q->orderByPrice('desc'));
        });

        $allProducts = $allProductQuery->with('brand')->get();

        $products = $allProductQuery->with('brand')->paginate($limit);

        foreach ($products as &$product) {
            $upload = DB::table('uploads')->where('id', $product->thumbnail_img)->first();
            if ($upload) {
                $product->thumbnail_img = url($upload->file_name);
            }
            $photoData = json_decode($product->photos, true);
            if (!empty($photoData)) {
                foreach ($photoData as $key => $photo) {
                    if (isset($photo['id'])) {
                        $photoFile = DB::table('uploads')->where('id', $photo['id'])->first();
                        if ($photoFile) {
                            $photoData[$key]['url'] = url($photoFile->file_name);
                        }
                    }
                }
            }

            $product->photos = $photoData;
        }
        $attributes = [];
        foreach ($allProducts as $product) {
            if (!is_array($product->attributes) && !is_object($product->attributes)) {
                continue;
            }

            foreach ($product->attributes as $attrId => $attrValue) {
                if (!isset($attributes[$attrId])) {
                    $attributes[$attrId] = [];
                }

                if (!in_array($attrValue, $attributes[$attrId])) {
                    $attributes[$attrId][] = $attrValue;
                }
            }
        }
        foreach ($attributes as $key => &$values) {
            $values = array_merge(...$values);
        }

        $attributeModels = Attribute::whereIn('id', array_keys($attributes))->get();

        return response()->json([
            'products' => $products,
            'attributeModels' => $attributeModels,
            'attributes' => $attributes,
        ]);
    }
}
