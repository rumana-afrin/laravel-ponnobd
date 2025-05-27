<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Settings;
use App\Models\Uploads;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FooterController extends Controller
{
   public function index()
   {
      $settings = Settings::whereIn(
         'key',
         [
            'widget_title_one',
            'widget_one_title',
            'widget_one_link',
            'widget_title_two',
            'widget_two_title',
            'widget_two_link',
            'footer_showroom_description',
            'footer_showroom_title',
            'footer_showroom_phone_one',
            'footer_showroom_phone_two',
            'fb_link',
            'yt_link',
            'linkedin_link',
            'insta_link',
            'ceo_speech_title',
            'ceo_description',
            'about_title',
            'about_description',
            'service_title',
            'service_description',

            'location_title',
            'location_description',
            'location_phone',
            'contact_title',
            'contact_description',

            'headoffice_title',
            'headoffice_address',
            'headoffice_email',
            'headoffice_phone',

            'site_meta_image',
            'site_meta_keywords',
            'site_meta_description',
            'site_meta_title',
            'site_icon',
            'header_logo',
         ]
      )->pluck('value', 'key');

      $footer_menu_one = isset($settings['widget_one_title']) ? json_decode($settings['widget_one_title'], true) : [];
      $menu_title_one = $settings['widget_title_one'] ?? null;

      $footer_menu_two = isset($settings['widget_two_title']) ? json_decode($settings['widget_two_title'], true) : [];
      $footer_menu_two_link = isset($settings['widget_two_link']) ? json_decode($settings['widget_two_link'], true) : [];
      $menu_title_two = $settings['widget_title_two'] ?? null;

      $location_title = isset($settings['location_title']) ? json_decode($settings['location_title'], true) : [];
      $location_description = isset($settings['location_description']) ? json_decode($settings['location_description'], true) : [];
      $location_phone = isset($settings['location_phone']) ? json_decode($settings['location_phone'], true) : [];

      $footer_showroom_description = isset($settings['footer_showroom_description']) ? json_decode($settings['footer_showroom_description'], true) : [];
      $footer_showroom_phone_one = isset($settings['footer_showroom_phone_one']) ? json_decode($settings['footer_showroom_phone_one'], true) : [];
      $showroom_title = isset($settings['footer_showroom_title']) ? json_decode($settings['footer_showroom_title'], true) : [];

      $about_description = isset($settings['about_description']) ? json_decode($settings['about_description'], true) : [];
      $service_title = isset($settings['service_title']) ? json_decode($settings['service_title'], true) : [];
      $service_description = isset($settings['service_description']) ? json_decode($settings['service_description'], true) : [];

      $slug_from_url = [];
      foreach ($footer_menu_two_link as $link) {
          $parsed_url = parse_url($link);
                    if (isset($parsed_url['scheme']) && isset($parsed_url['host'])) {
              $base_domain = $parsed_url['scheme'] . '://' . $parsed_url['host'];
              $slug_from_url[] = str_replace($base_domain, '', $link);
          } else {
              $slug_from_url[] = $link;
          }
      }

      $fb_link = $settings['fb_link'] ?? null;
      $yt_link = $settings['yt_link'] ?? null;
      $linkedin_link = $settings['linkedin_link'] ?? null;
      $insta_link = $settings['insta_link'] ?? null;
      $ceo_speech_title = $settings['ceo_speech_title'] ?? null;
      $ceo_description = $settings['ceo_description'] ?? null;
      $about_title = $settings['about_title'] ?? null;
      $about_description = $settings['about_description'] ?? null;
      $headoffice_title = $settings['headoffice_title'] ?? null;
      $headoffice_address = $settings['headoffice_address'] ?? null;
      $headoffice_email = $settings['headoffice_email'] ?? null;
      $headoffice_phone = $settings['headoffice_phone'] ?? null;
      $site_meta_keywords = $settings['site_meta_keywords'] ?? null;
      $site_meta_description = $settings['site_meta_description'] ?? null;
      $site_meta_title = $settings['site_meta_title'] ?? null;

      //header

      $fetchImages = function ($id) {
         $upload = DB::table('uploads')->where('id', $id)->first();
         if ($upload) {
             $image = url($upload->file_name);
             return $image;
         }
     };
      
      $site_meta_image =$fetchImages($settings['site_meta_image'] ?? null);
      $site_icon =$fetchImages($settings['site_icon'] ?? null);
      $header_logo =$fetchImages($settings['header_logo'] ?? null);
 
      if (empty($footer_menu_one) || empty($menu_title_one) || empty($footer_menu_two) || empty($menu_title_two) || empty($footer_showroom_description) || empty($footer_showroom_phone_one) || empty($showroom_title) || empty($fb_link) || empty($yt_link) || empty($linkedin_link) || empty($insta_link)) {

         return response()->json([]);
      }
      //  Log::info([$pageData]);
      return response()->json([
         'footer_menu_one' => $footer_menu_one,
         'menu_title_one' => $menu_title_one,
         'footer_menu_two' => $footer_menu_two,
         'menu_title_two' => $menu_title_two,
         'footer_showroom_description' => $footer_showroom_description,
         'footer_showroom_phone_one' => $footer_showroom_phone_one,
         'showroom_title' => $showroom_title,
         'app_name' => config('app.name'),
         'fb_link' => $fb_link,
         'yt_link' => $yt_link,
         'linkedin_link' => $linkedin_link,
         'insta_link' => $insta_link,
         'ceo_speech_title' => $ceo_speech_title,
         'ceo_description' => $ceo_description,
         'about_title' => $about_title,
         'about_description' => $about_description,
         'service_title' => $service_title,
         'service_description' => $service_description,
         'footer_menu_two_link' => $footer_menu_two_link,
         'slug_from_url' => $slug_from_url,
         'location_title' => $location_title,
         'location_description' => $location_description,
         'location_phone' => $location_phone,
         'headoffice_address' => $headoffice_address,
         'headoffice_title' => $headoffice_title,
         'headoffice_email' => $headoffice_email,
         'headoffice_phone' => $headoffice_phone,

         'site_meta_image' => $site_meta_image,
         'site_meta_keywords' => $site_meta_keywords,
         'site_meta_description' => $site_meta_description,
         'site_meta_title' => $site_meta_title,
         'site_icon' => $site_icon,
         'header_logo' => $header_logo,
      ]);
   }

}
