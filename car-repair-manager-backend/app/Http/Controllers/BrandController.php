<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class BrandController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {

        $brand = Brand::with('manufacturers')->get();

        return response()->json($brand);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

        // current user is connected ?
        if(Auth::check()){
            
            // get datas
            $session_infos = Auth::user();
            $user = User::find($session_infos->id);

            // check if it's admin or manager role ?
            if ($user->hasAnyRole(['admin', 'manager'])) {

                // validate datas
                $validated = $request->validate([
                    'name' => 'required|string|max:255|unique:brands,name',
                    'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                ]);

                // manage images
                if($request->hasFile('image')){

                    // get image details
                    $file = $request->file('image');
                    $name = rand(100, 9999) . time() . '_' . $file->getClientOriginalName();
                    $fileName = Str::slug($name);

                    // path where to store image "storage/app/public/images/brands"
                    $filePath = $file->storeAs('images/brands', $fileName, 'public');
                }
                
                // storing the datas in to db
                $brand = Brand::create([
                    'name' => $request->name,
                    'manufacturer_id' => $request->manufacturer_id,
                    'url-picture' => $filePath,
                ]);

                return response()->json([
                    'status' => 201,
                    'brand' => $brand
                ]);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {

        $brand = Brand::with('manufacturer')->find($id);

        return response()->json($brand);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {

        // current user is connected ?
        if(Auth::check()){
            
            // get datas
            $session_infos = Auth::user();
            $user = User::find($session_infos->id);

            // check if it's admin or manager role ?
            if ($user->hasAnyRole(['admin', 'manager'])) {

                // validate datas
                $validated = $request->validate([
                    'name' => 'required|string|max:255|unique:brands,name',
                    'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                ]);
                
                // found the record to update
                $brand = Brand::find($id);

                // manage images
                if($request->hasFile('image')){

                    if($brand){
                        // delete the old image in path: /public/images/manufacturers
                        Storage::disk('public')->delete($brand->url_picture);
                    }            

                    // get image details
                    $file = $request->file('image');
                    $name = rand(100, 9999) . time() . '_' . $file->getClientOriginalName();
                    $fileName = Str::slug($name);

                    // path where to store image "storage/app/public/images/brands"
                    $filePath = $file->storeAs('images/brands', $fileName, 'public');
                }

                // update record
                if($brand){            
                    $brand->update([
                        'name' => $request->name,
                        'url_picture' => $filePath,
                        'manufacturer_id' => $request->manufacturer_id
                    ]);

                    return response()->json([
                        'status' => 200,
                        'brand' => $brand
                    ]);
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {

        // current user is connected ?
        if(Auth::check()){
            
            // get datas
            $session_infos = Auth::user();
            $user = User::find($session_infos->id);

            // check if it's admin or manager role ?
            if ($user->hasRole('admin')) {

                // found the record to delete
                $brand = Brand::find($id);                

                if($brand){
                    // delete the old image in path: /public/images/brands
                    Storage::disk('public')->delete($brand->url_picture);

                    $label = $brand->name;

                    // deletion
                    $brand->delete();

                    return response()->json([
                        'status' => 201,
                        'message' => ucfirst($label) . ' has successfully being deleted.'
                    ]);
                }
            }
        }
    }
}
