<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {

        $profile = Auth::user()->profile;

        return $profile;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

        if(Auth::check()){

            // validate datas
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg|max:2048',
                'phone' => 'required',
                'adresse' => 'required',
                'country' => 'required|string|min:5',
                'city' => 'required|string|min:5',
                'postal_code' => 'required|string|min:2',
                'employment_held' => 'required|string|min:8',
            ]);

            // manage image
            if($request->hasFile('image')){

                // get image details
                $file = $request->file('image');
                $fileName = rand(100, 9999) . time() . '_' . $file->getClientOriginalName();

                // path where to store image "storage/app/public/images/profiles"
                $filePath = $file->storeAs('images/profiles', $fileName, 'public');
            }

            // First get connected user with Auth
            $user = Auth::user();

            // storing the datas in to db
            $profile = Profile::create([
                'user_id' => $user->id,
                'phone' => $request->phone,
                'adresse' => $request->adresse,
                'country' => $request->country,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'employment_held' => $request->employment_held,
                'url_picture' => $filePath,
                'skill' => $request->skill,
                'url_photo' => $fileName,
            ]);

            return [
                'status' => 201,
                'profile' => $profile
            ];

        }else{

            // unauthenticated user
            return [
                'status' => 401,
                'message' => 'You should first logg-in.',
            ];
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {

        // connected user
        if (Auth::check()) {

            // get connected user datas
            $user = Auth::user();

            // get profile datas
            $profile = $user->profile;

            return [
                'status' => 200,
                'profile' => $profile
            ];

        } else {

            return [
                'status' => 401,
                'message' => 'You should first logg-in.'
            ];
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {

        if(Auth::check()){

            // get connected user profile
            $profile = Profile::find($id);

            // initialize the path to store images locally
            $filePath = '';

            // validate datas
            $request->validate([
                'image' => 'image|mimes:jpeg,png,jpg|max:2048',
                'country' => 'required|string|min:5',
                'city' => 'required|string|min:8',
                'postal_code' => 'required|string|min:5',
                'employment_held' => 'required|string',
            ]);

            // manage images
            if($request->hasFile('image')){

                if($profile){
                    // delete the old image in path: /public/images/photos
                    Storage::disk('public')->delete($profile->url_picture);
                }            

                // get image details
                $file = $request->file('image');
                $fileName = rand(100, 9999) . time() . '_' . $file->getClientOriginalName();

                // path where to store image "storage/app/public/images/profiles"
                $filePath = $file->storeAs('images/profiles', $fileName, 'public');
            }

            // storing the datas in to db
            $profile->update([
                'user_id' => $profile->user->id,
                'phone' => $request->phone,
                'email' => $request->email,
                'adresse' => $request->adresse,
                'country' => $request->country,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'employment_held' => $request->employment_held,
                'url_picture' => $filePath
            ]);

            return [
                'status' => 201,
                'profile' => $profile
            ];

        }else{

            // unauthenticated user
            return response()->json([
                'status' => 401,
                'message' => 'You should first logg-in.',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {

        if(Auth::check()){
            
            // get connected user profile's datas
            $profile = Profile::find($id);

           // delete the old image in path: /public/images/profiles
            Storage::disk('public')->delete($profile->url_picture);

            // delete the record in the database
            $profile->delete();

            return [
                'status' => 201,
                'message' => 'Profile successfully deleted.'
            ];

        }else{

            // unauthenticated user
            return [
                'status' => 401,
                'message' => 'You should first logg-in to deleted item.',
            ];
        }        
    }
}
