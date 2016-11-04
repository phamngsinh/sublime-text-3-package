<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\User;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AjaxAuthController extends Controller
{


    /**
     * Store a newly created resource in storage.
     * Validate Auth
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
        ]);
    }

    /**
     * Validate password via ajax
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function postValidate(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);

        // test input password against the existing one

        if (!Hash::check($request->get('old_password'), $user->getAuthPassword())) {
            return "false";    
        }
        return "true";
    }
}
