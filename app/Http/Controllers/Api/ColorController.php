<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\ColorCollection;
use App\Models\Color;

class ColorController extends Controller
{
    public function index()
    {
        dd(Color::all());
        return new ColorCollection(Color::all());
    }
}
