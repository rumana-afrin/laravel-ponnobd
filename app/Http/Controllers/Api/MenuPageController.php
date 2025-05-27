<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\CategoryMenu;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MenuPageController extends Controller
{
    public function topMenu()
    {
        // $menu = CategoryMenu::all();
        $menu = CategoryMenu::whereNull('parent_id')->with('submenus')->get();

        return response()->json([
            'menu_name' => $menu,
        ]);
    }

    public function menuPage($slug, Request $request)
    {
         Log::info("Query Params:", $request->all());
         
        $limit = $request->input('limit', 12);
        $min_price = $request->input('min_price');
        $max_price = $request->input('max_price');
        $sort = $request->input('sort', 'popularity');

        $category = Category::where('slug', $slug)->firstOrFail();
        $category_id = $category->id;

        $allProductQuery = Product::when($category_id, function ($query) use ($category_id) {
            $query->whereHas('categories', function ($categories) use ($category_id) {
                $categories->where('category_id', $category_id);
            });
        })->whereBetween('unit_price', [$min_price, $max_price]);

        $queryProduct =  $allProductQuery->when($sort, function ($query) use ($sort) {
            $query->when($sort == 'popularity', fn($q) => $q->topSell());
            $query->when($sort == 'latest', fn($q) => $q->latest());
            $query->when($sort == 'oldest', fn($q) => $q->oldest());
            $query->when($sort == 'low to high', fn($q) => $q->orderByPrice());
            $query->when($sort == 'high to low', fn($q) => $q->orderByPrice('desc'));
        });
        $allProducts = $queryProduct->with('brand')->get();
        $paginatedProducts = $allProductQuery->with('brand')->paginate($limit);

        foreach ($paginatedProducts as &$product) {
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
        Log::info($attributes);

        return response()->json([
            'products' => $paginatedProducts,
            'category' => $category,
            'attributeModels' => $attributeModels,
            'attributes' => $attributes,
        ]);
    }

    public function sortProduct($slug, Request $request)
    {
        $limit = $request->input('limit', 12);
        $sort = $request->input('sort', 'popularity');
        $category = Category::where('slug', $slug)->first();
        $category_id = $category->id;

        $allProductQuery = Product::whereHas('categories', function ($query) use ($category_id) {
            $query->where('category_id', $category_id);
        });

        Log::info('Request Data:', $request->all());

        $allProductQuery->when($sort, function ($query) use ($sort) {
            $query->when($sort == 'popularity', fn($q) => $q->topSell());
            $query->when($sort == 'latest', fn($q) => $q->latest());
            $query->when($sort == 'oldest', fn($q) => $q->oldest());
            $query->when($sort == 'low to high', fn($q) => $q->orderByPrice());
            $query->when($sort == 'high to low', fn($q) => $q->orderByPrice('desc'));
        });

        $allProducts = $allProductQuery->get();

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
            'category' => $category,
            'attributeModels' => $attributeModels,
            'attributes' => $attributes,
        ]);
    }
}
