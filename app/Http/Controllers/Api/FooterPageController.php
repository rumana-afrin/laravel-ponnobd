<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class FooterPageController extends Controller
{
    public function footerMenuPage($slug){
        $data = Page::where('slug', $slug)->first();
        if(!$data){
            return response()->json(['message' => 'Page not found'], 404);
        }
        return response()->json($data);
    }
    public function contactUs(){
        return response()->json([
            'message' => 'Contact us page content'
        ], 200);
    }
}
