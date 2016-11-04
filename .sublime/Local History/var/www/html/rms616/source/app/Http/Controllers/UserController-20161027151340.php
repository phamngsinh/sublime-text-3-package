<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ChangePasswordRequest;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('login');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::with(['subscriptionPackages'])->findOrFail(Auth::user()->id);

        return view('users.index')->with('user', $user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('users.show');
    }

    /**
     * Show the form for editing the specified resource.
     * Account setting
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        $countries =   Cache::remember('countries', 60, function() {
            return  Country::all();
        });

        return view('users.edit')->with('user',$user)->with('countries',$countries->lists('long_name','id'));
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail(Auth::user()->id);
        $user->name = $request->get('name');
        $user->email_reminder = $request->get('email_reminder');
        $user->type_customized = $request->get('type_customized');
        $user->save();
     return redirect(route('users.edit',$id))->with('success', trans('messages.data_saved'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @return mixed
     */
    public function change()
    {
        return view('users.password');
    }

    /**
     * @param ChangePasswordRequest $request
     * @return mixed
     */
    public function postChange(ChangePasswordRequest $request)
    {

        // Grab the current user
        $user = User::findOrFail(Auth::user()->id);

        // test input password against the existing one

        if (Hash::check(Input::get('old_password'), $user->getAuthPassword())) {
            $user->password = Hash::make(Input::get('password'));

            if ($user->save()) {
                return redirect()->route('users.edit', Auth::user()->id)
                    ->with('success', trans('messages.change_password_success'));
            }
        } else {
            return redirect()->route('changePassword')->with('message', trans('messages.old_password_message'));
        }
        return redirect()->route('users.show', Auth::user()->id);

    }


   
}
