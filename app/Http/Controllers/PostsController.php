<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Sentinel;

class PostsController extends Controller
{
  public function store(Request $request)
  {
    $user = Sentinel::getUser();
    // if ($user->hasAccess(['posts.create', 'posts.update'])) All permission have to be set to true
    // if ($user->hasAnyAccess(['posts.create', 'posts.update'])) Only one of the permissions needs to be set to true
    // if ($user->hasAccess(['posts.*'])) All permission have to be set to true
    if ($user->hasAccess('posts.create')) {
      return $request->all();
    }
    abort(403, 'Unauthorized action');
  }
}
