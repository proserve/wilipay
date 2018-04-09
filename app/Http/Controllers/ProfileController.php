<?php

namespace App\Http\Controllers;

use App\Profile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    function editProfile(Request $request)
    {
        $user = Auth::user();
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:32',
            'last_name' => 'required|string|max:32',
            'birthday' => 'required|date',
            'gender' => ['required', Rule::in(['male', 'female'])]
        ]);
        $validatedData['birthday'] = Carbon::parse($validatedData['birthday']);
        $profile = $user->profile ? $user->profile : new Profile;
        $profile->fill($validatedData);
        $profile->user_id = $user->id;
        $profile->save();
        return response('', 201);
    }

    function editAddress(Request $request)
    {
        $user = Auth::user();
        $validatedData = $request->validate([
            'country' => 'required|string|max:4|min:2',
            'city' => 'required|string',
            'postal_code' => 'required|string',
            'region' => 'required|string',
            'street' => 'required|string',
        ]);
        $profile = $user->profile;
        if(!$profile) abort(400, 'You don\'t have a profile yet');
        $profile->fill($validatedData);
        $profile->save();
        return response('', 201);
    }

    function editUserAvatar(Request $request)
    {
        $user = Auth::user();
        $request->validate(['avatar' => 'required|image|dimensions:max_width=400,max_height=400']);
        $disk = Storage::disk('gcs');
        $file = $request->file('avatar');

        $fileName = $user->id . '/avatar.'. ($file->getClientOriginalExtension() || '.png');
        try {
            $disk->put($fileName, file_get_contents($file->getRealPath()));
        } catch (\Google\Cloud\Core\Exception\GoogleException $e) {
            abort(400, 'Error occur while adding your avatar');
        }
        $disk->setVisibility($fileName, 'public');
        $profile = $user->profile;
        if(!$profile){
            abort(400, 'Wooops! you don\'t have a profile yet, you should create your profile first');
        }
        $profile->avatar_url = $disk->url($fileName);
        $profile->save();
        return response(null, 201);
    }
}

