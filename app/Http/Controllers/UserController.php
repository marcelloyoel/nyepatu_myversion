<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use App\Models\LaundrySepatu;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('homeAdmin', [
            'title' => 'profile',
            'users' => User::all(),
            'javascript'   => 'profile.js'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.profile.create', [
            'title' => 'Create User'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'username' => ['unique:users', 'required'],
            'displayName' => ['required'],
            'address' => ['nullable'],
            'email' => ['unique:users', 'required'],
            'phoneNumber' => ['nullable'],
            'group_id' => ['required'],
            'password' => 'required',
            'picture' => 'image',
        ];

        if ($request->input('group_id') == 2) {
            $rules['laundry-laundrySepatuName'] = ['required'];
            $rules['laundry-laundrySepatuSlug'] = ['required'];
            $rules['laundry-bio'] = ['required'];
            $rules['laundry-address'] = ['required'];
            $rules['laundry-contact'] = ['required'];
            $rules['laundry-picture'] = ['nullable'];
        }

        $validatedData = $request->validate($rules);

        $validatedData['password'] = Hash::make($validatedData['password']);

        if ($request->hasFile('picture')) {
            $uploadedPicture = $request->file('picture');
            $pictureFileName = $uploadedPicture->getClientOriginalName();
            $uploadedPicture->storeAs('images', $pictureFileName, 'public');
            $validatedData['picture'] = $pictureFileName;
        }

        $user = User::create($validatedData);

        if ($user->group_id == 2) {
            $laundryData = [
                'user_id' => $user->id,
                'laundrySepatuName' => $validatedData['laundry-laundrySepatuName'],
                'laundrySepatuSlug' => $validatedData['laundry-laundrySepatuSlug'],
                'bio' => $validatedData['laundry-bio'],
                'Address' => $validatedData['laundry-address'],
                'Contact' => $validatedData['laundry-contact'],
                'distance' => '200 m',
            ];

            if ($request->hasFile('laundry-picture')) {
                $uploadedLaundryPicture = $request->file('laundry-picture');
                $laundryPictureFileName = $uploadedLaundryPicture->getClientOriginalName();
                $uploadedLaundryPicture->storeAs('images', $laundryPictureFileName, 'public');
                $laundryData['picture'] = $laundryPictureFileName;
            }

            LaundrySepatu::create($laundryData);
        }
        User::create($validatedData);
        return redirect('/home')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $profile)
    {
        return $profile;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $profile)
    {
        return view('admin.profile.edit', [
            'user' => $profile,
            'title' => 'Edit',
            'role' => Group::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $profile)
    {
        $uploadedPicture = $request->file('picture');

        $rules = [
            'username' => 'max:255|unique:users,id,' . $profile->id,
            'displayName' => ['required'],
            'email' => ['required'],
            'address' => ['nullable'],
            'status' => ['string'],
            'phoneNumber' => ['string'],
            'group_id' => ['string']
        ];

        if ($uploadedPicture) {
            $rules['picture'] = 'image|mimes:jpeg,png,jpg,gif|max:2048';
            $validatedData = $request->validate($rules);
            // $pictureFileName = $uploadedPicture->getClientOriginalName();
            // $uploadedPicture->storeAs('images', $pictureFileName, 'public');
            // $validatedData['picture'] = $pictureFileName;

            $pictureFileName = $uploadedPicture->hashName(); // Generate a unique filename
            $uploadedPicture->storeAs('public/images', $pictureFileName); // Store in storage/app/public/images
            $validatedData['picture'] = $pictureFileName;
        } else {
            $validatedData = $request->validate($rules);
        }

        User::where('id', $profile->id)->update($validatedData);
        return redirect('/home')->with('update', 'Data berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $profile)
    {
        User::destroy($profile->id);
        return redirect('/home')->with('delete', 'Data berhasil dihapus!');
    }
}
