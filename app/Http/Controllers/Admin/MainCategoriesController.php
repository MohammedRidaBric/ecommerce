<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainCategoriesRequest;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class MainCategoriesController extends Controller
{
    public function index()
    {
        $defualt_lang = get_default_lang();
        $maincategories = MainCategory::where('translation_lang',$defualt_lang)->selection()->paginate(PAGINATION_COUNT);
        return view('admin.maincategories.index', compact('maincategories'));
    }

    public function create()
    {
        return view('admin.maincategories.create');
    }

    public function  store(MainCategoriesRequest $request){

        return $request;

    }

    public function edit($id)
    {

        $maincategory = MainCategory::select()->find($id);
        if (!$maincategory) {
            return redirect()->route('admin.maincategories')->with(['error' => 'هذه اللغة غير موجوده']);
        }

        return view('admin.maincategories.edit', compact('maincategory'));
    }
}
