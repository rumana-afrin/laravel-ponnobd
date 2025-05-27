<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Settings;
use App\Models\Uploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MetaDataController extends Controller
{

   public function metaHome()
   {
      $settings = Settings::whereIn(
         'key',
         [
            'site_meta_image',
            'site_meta_keywords',
            'site_meta_description',
            'site_meta_title',
         ]
      )->pluck('value', 'key');

      $fetchImages = function ($id) {
         $upload = DB::table('uploads')->where('id', $id)->first();
         if ($upload) {
            $image = url($upload->file_name);
            return $image;
         }
      };

      $site_meta_keywords = $settings['site_meta_keywords'] ?? null;
      $site_meta_description = $settings['site_meta_description'] ?? null;
      $site_meta_title = $settings['site_meta_title'] ?? null;
      $site_meta_image = $fetchImages($settings['site_meta_image'] ?? null);

      // Log::info($site_meta_image);

      return response()->json([
         'site_meta_image' => $site_meta_image,
         'site_meta_keywords' => $site_meta_keywords,
         'site_meta_description' => $site_meta_description,
         'site_meta_title' => $site_meta_title,
      ]);
   }
   public function categoryMetaDate($slug)
   {
      $category = Category::where('slug', $slug)->first();
      $settings = Settings::whereIn(
         'key',
         [
            'site_meta_image',
         ]
      )->pluck('value', 'key');

       $fetchImages = function ($id) {
         $upload = DB::table('uploads')->where('id', $id)->first();
         if ($upload) {
            $image = url($upload->file_name);
            return $image;
         }
      };
      $site_meta_image = $fetchImages($settings['site_meta_image'] ?? null);

      return response()->json([
         'meta_title' => $category->meta_title,
         'meta_keywords' => $category->meta_keywords,
         'meta_description' => $category->meta_description,
         'meta_img' => $site_meta_image,
      ]);
   }
   public function productMetaDate($slug)
   {

      $product = Product::where('slug', $slug)->first();

      $upload = DB::table('uploads')->where('id', $product->meta_img)->first();

      if ($upload) {
          $product->meta_img = url($upload->file_name);
      }

      return response()->json([
         'meta_title' => $product->meta_title,
         'meta_img' => $product->meta_img,
         'meta_description' => $product->meta_description,
      ]);
   }
   public function aboutMetaDate()
   {
      $settings = Settings::whereIn(
         'key',
         [
            'site_meta_image',
            'site_meta_keywords',
            'site_meta_description',
            'site_meta_title',
            'about_description',
            'about_title'
         ]
      )->pluck('value', 'key');

      $fetchImages = function ($id) {
         $upload = DB::table('uploads')->where('id', $id)->first();
         if ($upload) {
            $image = url($upload->file_name);
            return $image;
         }
      };

      $site_meta_keywords = $settings['site_meta_keywords'] ?? null;
      $site_meta_description = $settings['site_meta_description'] ?? null;
      $site_meta_title = $settings['site_meta_title'] ?? null;
      $about_description = $settings['about_description'] ?? null;
      $about_title = $settings['about_title'] ?? null;
      $site_meta_image = $fetchImages($settings['site_meta_image'] ?? null);

      return response()->json([
         'site_meta_image' => $site_meta_image,
         'site_meta_keywords' => $site_meta_keywords,
         'site_meta_description' => $site_meta_description,
         'site_meta_title' => $site_meta_title,
         'about_description' => $about_description,
         'about_title' => $about_title,
      ]);
   }

}
