<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\api\Controller;

class AuthController extends Controller
{
    public function index(){
        return parent::respError('400');
    }
}
