<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SiteTopController extends Controller
{
  public function top()
  {
    return view('top/top');
  }
}
