<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sentinel;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;

class LoginController extends Controller
{
  public function login()
  {
    return view('authentication.login');
  }

  public function postLogin(Request $request)
  {
    try {
      $rememberMe = false;
      if (isset($request->remember_me)) {
        $rememberMe = true;
      }

      if (Sentinel::authenticate($request->all(), $rememberMe)) {
        $slug = Sentinel::getUser()->roles->first()->slug;
        if ($slug == 'admin') {
          return redirect('/earnings');
          return response()->json(['redirect'=>'/earning']);
        } elseif ($slug == 'manager') {
          return response()->json(['redirect'=>'/tasks']);
        }
      } else {
        return response()->json(['error' => 'Wrong Credentials'], 500);
      }
    } catch (ThrottlingException $e) {
      $delay = $e->getDelay();
      return response()->json([
        'error' => "You are banned for $delay seconds."
      ], 500);
    } catch (NotActivatedException $e) {
      return response()->json([
        'error' => 'Your account is not activated.'
      ], 500);
    }
  }

  public function logout()
  {
    Sentinel::logout();
    return redirect('/login');
  }
}
