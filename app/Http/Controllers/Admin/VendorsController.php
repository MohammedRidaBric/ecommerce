<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRequest;
use App\Models\MainCategory;
use App\Models\Vendor;
use App\Notifications\VendorCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Mockery\Exception;

class VendorsController extends Controller
{
    public function index()
    {
        $vendors = Vendor::selection()->with(['categories' => function ($q) {
            $q->select('id', 'name', 'slug');
        }])->paginate(PAGINATION_COUNT);

        return view('admin.vendors.index', compact('vendors'));
    }

    public function create()
    {
        $categories = MainCategory::where('translation_of', 0)->active()->get();
        return view('admin.vendors.create', compact('categories'));
    }

    public function store(VendorRequest $request)
    {
        try {
            // start spy  if has Error in database
            DB::beginTransaction();
            $file = '';
            if ($request->has('logo')) {
                $file = SaveImage('vendors', $request->logo);
            }
            if ($request->has('active')) $request->request->add(['active' => 1]);
            else $request->request->add(['active' => 0]);
            $Vendor = Vendor::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'email' => $request->email,
                'password' => $request->password,
                'active' => $request->active,
                'logo' => $file,
                'category_id' => $request->category_id
            ]);
            Notification::send($Vendor, new VendorCreated($Vendor));
            DB::commit();
            // end spy  if has Error in database
            return redirect()->route('admin.vendors')->with(['success' => 'تم الحفظ بنجاح']);

        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);

        }
    }

    public function edit($id)
    {
        try {
            $vendor = Vendor::Selection()->find($id);
            if (!$vendor) return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود او ربما يكون محذوفا ']);
            $categories = MainCategory::where('translation_of', 0)->active()->get();
            return view('admin.vendors.edit', compact('categories', 'vendor'));

        } catch (Exception $ex) {
            return redirect()->route('admin.vendors.index')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $vendor = Vendor::Selection()->find($id);
            if (!$vendor) return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود او ربما يكون محذوفا ']);
            // start spy  if has Error in database
            DB::beginTransaction();
            $file = $vendor->logo;
            $password = "";
            if ($request->has('logo')) $file = SaveImage('vendors', $request->logo);
            if ($request->has('password')) $password = $request->password;
            if ($request->has('active')) $request->request->add(['active' => 1]);
            else $request->request->add(['active' => 0]);
            $vendor->update([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'email' => $request->email,
                'password' => $password,
                'active' => $request->active,
                'logo' => $file,
                'category_id' => $request->category_id
            ]);
            DB::commit();
            // end spy  if has Error in database
            return redirect()->route('admin.vendors')->with(['success' => 'تم الحفظ بنجاح']);


        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->route('admin.vendors.index')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);

        }
    }

    public function status(Request $request)
    {
        try {

        } catch (Exception $ex) {
            return redirect()->route('admin.vendors.index')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);

        }
    }

}
