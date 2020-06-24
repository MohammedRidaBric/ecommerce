<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainCategoriesRequest;
use App\Models\MainCategory;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class MainCategoriesController extends Controller
{
    public function index()
    {
        $defualt_lang = get_default_lang();
        $maincategories = MainCategory::where('translation_lang', $defualt_lang)->selection()->paginate(PAGINATION_COUNT);
        return view('admin.maincategories.index', compact('maincategories'));
    }

    public function create()
    {
        return view('admin.maincategories.create');
    }

    public function  store(MainCategoriesRequest $request)
    {
        try {
            // Convirt Request  in to Array
            $main_categorie = collect($request->category);
            // get cat for default lang
            $filter = $main_categorie->filter(function ($val, $key) {

                return $val['abbr'] == get_default_lang();
            });
            $file = '';
            $default_category = array_values($filter->all())[0];
            // start spy  if has Error in database
            DB::beginTransaction();
            if ($request->has('photo')) {
                $file = SaveImage('maincategories', $request->photo);
            }
            //insert cat of Default lang
            $id_default_category = MainCategory::insertGetId([
                'translation_lang' => $default_category['abbr'],
                'translation_of' => 0,
                'name' => $default_category['name'],
                'slug' => $default_category['name'],
                'photo' => $file,
            ]);
            //get other cat and Insert in DB
            $categories = $main_categorie->filter(function ($val, $key) {
                return $val['abbr'] != get_default_lang();
            });
            if (isset($categories) && $categories->count()) {
                $categoriesArray = [];
                foreach ($categories as $category) {
                    $categoriesArray[] = [
                        'translation_lang' => $category['abbr'],
                        'translation_of' => $id_default_category,
                        'name' => $category['name'],
                        'slug' => $category['name'],
                        'photo' => $file,
                    ];
                }
                MainCategory::insert($categoriesArray);
            }
            DB::commit();
            // end spy if has Error in Database  and redirect
            return redirect()->route('admin.maincategories')->with(['success' => 'تم الحفظ بنجاح']);
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }

    public function edit($id)
    {
        try {
            $mainCategory = MainCategory::select()->find($id);
            if (!$mainCategory)
                return redirect()->route('admin.maincategories')->with(['error' => 'هذه اللغة غير موجوده']);

            return view('admin.maincategories.edit', compact('mainCategory'));
        } catch (Exception $ex) {
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }

    public function update(MainCategoriesRequest $request, $id)
    {
        try {
            // Convirt Request  in to Array
            $main_categorie = collect($request->category);
            // get cat for default lang
            $filter = $main_categorie->filter(function ($val, $key) {

                return $val['abbr'] == get_default_lang();
            });
            $file = '';
            $default_category = array_values($filter->all())[0];
            // start spy  if has Error in database
            DB::beginTransaction();
            if ($request->has('photo')) {
                $file = SaveImage('maincategories', $request->photo);
            }
            //insert cat of Default lang
            $id_default_category = MainCategory::insertGetId([
                'translation_lang' => $default_category['abbr'],
                'translation_of' => 0,
                'name' => $default_category['name'],
                'slug' => $default_category['name'],
                'photo' => $file,
            ]);
            //get other cat and Insert in DB
            $categories = $main_categorie->filter(function ($val, $key) {
                return $val['abbr'] != get_default_lang();
            });
            if (isset($categories) && $categories->count()) {
                $categoriesArray = [];
                foreach ($categories as $category) {
                    $categoriesArray[] = [
                        'translation_lang' => $category['abbr'],
                        'translation_of' => $id_default_category,
                        'name' => $category['name'],
                        'slug' => $category['name'],
                        'photo' => $file,
                    ];
                }
                MainCategory::insert($categoriesArray);
            }
            DB::commit();
            // end spy if has Error in Database  and redirect
            return redirect()->route('admin.maincategories')->with(['success' => 'تم الحفظ بنجاح']);
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }
}
