<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Category;

class CategoriesController extends Controller
{
    public function show($id)
    {
        $category = Category::find($id);
        return view('categories.index', compact('category'));
    }
}
