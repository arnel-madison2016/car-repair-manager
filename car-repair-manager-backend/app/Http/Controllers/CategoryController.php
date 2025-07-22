<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\User;
use Spatie\Permission\Traits\HasRoles;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {

        $categories = Category::all();

        return response()->json($categories);
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
                $request->validate([
                    'name' => 'required|string|max:255|unique:category_services,name',
                    'cout_forfaitaire' => 'required|decimal:2',
                ]);

                // storing the datas in to db
                $category = Category::create(attributes: [
                    'name' => $request->name,
                    'cout_forfaitaire' => $request->cout_forfaitaire,
                    'details' => $request->details
                ]);

                return response()->json([
                    'status' => 201,
                    'category' => $category
                ]);                
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {

        // show specific category
        $category = Category::find($id);

        return response()->json($category);
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
                $request->validate([
                    'name' => 'required|string|max:255|unique:category_services,name',
                    'cout_forfaitaire' => 'required|decimal:2',
                ]);

                $category = Category::find($id);

                // record found ?
                if($category){

                    // update record
                    $category->update([
                        'name' => $request->name,
                        'cout_forfaitaire' => $request->cout_forfaitaire,
                        'details' => $request->details
                    ]);

                    return response()->json([
                        'status' => 200,
                        'category' => $category
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

                $category = Category::find($id);
                $label = $category->name;
                
                if($category){

                    $category->delete();

                    return response()->json([
                        'status' => 201,
                        'message' => ucfirst($label) .' has successfully being deleted.'
                    ]);
                }                
            }
        }
    }
}
