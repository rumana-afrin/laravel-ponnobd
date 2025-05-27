<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\DisplaySection;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index()
    {
        $sections = DisplaySection::with('products.product')->get();

        $groupedProducts = $sections->map(function ($section) {
            return [
                'section_name' => $section->name,
                'section_id' => $section->id,
                'products' => $section->products->pluck('product')->filter()->values(),
            ];
        });

        foreach ($groupedProducts as &$section) {
            foreach ($section['products'] as &$product) {
                $upload = DB::table('uploads')->where('id', $product->thumbnail_img)->first();

                if ($upload) {
                    $product->thumbnail_img = url($upload->file_name);
                }

                $photoData = json_decode($product->photos, true);
                if (!empty($photoData)) {
                    foreach ($photoData as $key => $photo) {
                        if (isset($photo['id'])) {
                            $photo_id = $photo['id'];
                            $photoFile = DB::table('uploads')->where('id', $photo_id)->first();

                            if ($photoFile) {
                                $photoData[$key]['url'] = url($photoFile->file_name);
                            }
                        }
                    }
                }
                $product->photos = $photoData;
            }
        }
        return response()->json($groupedProducts);
    }

    public function stock($slug, $stock)
    {
        Log::info([
            'stock' => $stock,
        ]);
        
        $category = Category::where('slug', $slug)->first();

        if (!$category) {
            return response()->json(['message' => 'Category not found.'], 404);
        }

        $category_id = $category->id;

        $query = Product::whereHas('categories', function ($categories) use ($category_id) {
            $categories->where('category_id', $category_id);
        });

        if ($stock === 'in_stock') {
            $query->where('current_stock', '>', 0);
        } elseif ($stock === 'out_stock') {
            $query->where('current_stock', '<=', 0);
        } else {
            return response()->json([
                'message' => 'Invalid stock availability value.',
            ], 400);
        }

        $products = $query->with('brand')->get();

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
      
        return response()->json([
            'products' => $products,
            'category' => $category,
            'category_name' => $category->name
        ]);
    }
}
