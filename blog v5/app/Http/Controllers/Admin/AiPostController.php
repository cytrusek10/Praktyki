<?php

namespace App\Http\Controllers\Admin;

use App\Ai\Agents\BlogPostAgent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AiPostController extends Controller
{
    public function generate(Request $request)
    {
        $request->validate([
            'topic'    => 'required|min:5|max:200',
            'category' => 'required',
        ]);

        try {
            $response = (new BlogPostAgent)->prompt(
                "Napisz post blogowy na temat: \"{$request->topic}\". 
                 Kategoria: {$request->category}.
                 Post powinien mieć około 400-600 słów."
            );

            return response()->json([
                'title'           => $response['title'],
                'excerpt'         => $response['excerpt'],
                'content'         => $response['content'],
                'seo_title'       => $response['seo_title'],
                'seo_description' => $response['seo_description'],
                'suggested_tags'  => $response['suggested_tags'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Błąd generowania: ' . $e->getMessage()], 500);
        }
    }
}
