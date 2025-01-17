<?php

namespace App\Http\Controllers;

use App\Models\Blogpost;
use DateTime;
use Illuminate\Contracts\Support\Renderable;

class BlogController extends Controller
{
    public function all(): Renderable {
        $blogposts = Blogpost::where("published_at", "<", new DateTime())->latest('published_at')->simplePaginate(5);

        return view('blog', ['blogposts' => $blogposts, "page" => "home"]);
    }

    public function show(string $slug): Renderable {
        $blogposts = Blogpost::where("slug", $slug)->simplePaginate(1);

        if ($blogposts->count() == 0) {
            abort(404);
        }

        return view('blog', ['blogposts' => $blogposts, "page" => "single"]);
    }

    public function category(string $cat): Renderable {
        $blogposts = Blogpost::where("category", $cat)->orderBy('published_at', 'desc')->simplePaginate(5);

        if ($blogposts->count() == 0) {
            abort(404);
        }

        return view('blog', ['blogposts' => $blogposts, "category" => $cat, "page" => "category"]);
    }
}
