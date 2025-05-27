<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DisplaySection;
use Illuminate\Support\Facades\DB;
use App\Models\Settings;
use App\Models\Uploads;
use Illuminate\Support\Facades\Log;



class LandingPageController extends Controller
{
    public function landingPage()
    {
        // 🟦 1. Fetch Sections and Products
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

        // 🟦 2. Fetch Gallery Settings & Footer Content
        $settings = Settings::whereIn('key', ['home_galleries_image', 'cat_title', 'cat_icon', 'footer_content'])->pluck('value', 'key');

        $homeGalleries = isset($settings['home_galleries_image']) ? json_decode($settings['home_galleries_image'], true) : [];
        $cat_icon = isset($settings['cat_icon']) ? json_decode($settings['cat_icon'], true) : [];
        $catTitle = isset($settings['cat_title']) ? json_decode($settings['cat_title'], true) : [];
        $footer_content = $settings['footer_content'] ?? null;

        $fetchImages = function ($ids) {
            return Uploads::whereIn('id', $ids)->get()->map(fn($upload) => [
                'file_url' => asset($upload->file_name),
            ]);
        };

        $upload_Galleries = $fetchImages($homeGalleries);
        $catIcons = $fetchImages($cat_icon);

        // 🟦 3. Fetch Meta Settings
        $metaSettings = Settings::whereIn(
            'key',
            ['site_meta_image', 'site_meta_keywords', 'site_meta_description', 'site_meta_title']
        )->pluck('value', 'key');

        $site_meta_image = null;
        if (!empty($metaSettings['site_meta_image'])) {
            $upload = DB::table('uploads')->where('id', $metaSettings['site_meta_image'])->first();
            if ($upload) {
                $site_meta_image = url($upload->file_name);
            }
        }

        // ✅ Final Combined JSON Response
        return response()->json([
            'sections' => $groupedProducts,
            'galleries' => $upload_Galleries,
            'cat_icon' => $catIcons,
            'description' => $catTitle,
            'footer_content' => $footer_content,
            'meta' => [
                'site_meta_image' => $site_meta_image,
                'site_meta_keywords' => $metaSettings['site_meta_keywords'] ?? null,
                'site_meta_description' => $metaSettings['site_meta_description'] ?? null,
                'site_meta_title' => $metaSettings['site_meta_title'] ?? null,
            ]
        ]);
    }
}