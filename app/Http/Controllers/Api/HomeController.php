<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Models\Uploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        $settings = Settings::whereIn('key', ['home_galleries_image', 'cat_title','cat_icon','footer_content'])->pluck('value', 'key');
    
        $homeGalleries = isset($settings['home_galleries_image']) ? json_decode($settings['home_galleries_image'], true) : [];
        $cat_icon = isset($settings['cat_icon']) ? json_decode($settings['cat_icon'], true) : [];
        $catTitle = isset($settings['cat_title']) ? json_decode($settings['cat_title'], true) : [];
        $footer_content = $settings['footer_content'] ?? null;

        if (empty($homeGalleries) || empty($catTitle) || empty($cat_icon)) {
            return response()->json([]);
        }
    
        $fetchImages = function ($ids) {
            return Uploads::whereIn('id', $ids)->get()->map(fn($upload) => [
                'file_url' => asset($upload->file_name),
            ]);
        };
       
        $upload_Galleries = $fetchImages($homeGalleries);
        $catIcons = $fetchImages($cat_icon);
        
        Log::info($upload_Galleries);

        return response()->json([
            'galleries' => $upload_Galleries,
            'cat_icon' => $catIcons,
            'description' => $catTitle,
            'footer_content' => $footer_content,
        ]);
    }
}
