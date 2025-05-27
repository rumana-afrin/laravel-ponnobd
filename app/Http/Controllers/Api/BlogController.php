<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = BlogCategory::with('posts')->get();
    
        foreach ($blogs as &$category) {
            foreach ($category->posts as &$blog) {
                $upload = DB::table('uploads')->where('id', $blog->thumbnail)->first();
                if ($upload) {
                    $blog->thumbnail = url($upload->file_name);
                }
            }
        }
    
        return response()->json([
            'status' => 'success',
            'blog' => $blogs,
        ]);
    }
    public function blogDetails($slug)
{
    $blog = Blog::where('slug', $slug)->first();

    if ($blog) {
        $upload = DB::table('uploads')->where('id', $blog->thumbnail)->first();
        if ($upload) {
            $blog->thumbnail = url($upload->file_name);
        }
        $blog->description = preg_replace_callback(
            '/<img[^>]+src=[\'"]([^\'"]+)[\'"][^>]*>/i',
            function ($matches) {
                $src = $matches[1];
        
                $src = str_replace(['../../../', '../../', '../', 'public/'], '', $src); 
                        $absoluteUrl = url($src);
                return str_replace($matches[1], $absoluteUrl, $matches[0]);
            },
            $blog->description
        );
        
    }

    return response()->json([
        'status' => 'success',
        'blog' => $blog,
    ]);
}

    

}
