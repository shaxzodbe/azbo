<?php

namespace App\Http\Controllers;

use App\Color;
use App\ColorTranslation;
use Illuminate\Http\Request;
use App\Product;

class ColorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $sort_search =null;
        $colors = Color::orderBy('name', 'asc');
        if ($request->has('search')){
            $sort_search = $request->search;
            $colors = $colors->where('name', 'like', '%'.$sort_search.'%');
        }
        $colors = $colors->paginate(15);
        return view('backend.product.colors.index', compact('colors', 'sort_search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $color = new Color;
        $color->name = $request->name;
        $color->code = $request->code;
        $color->save();

        $color_translation = ColorTranslation::firstOrNew(['lang' => env('DEFAULT_LANGUAGE'), 'color_id' => $color->id]);
        $color_translation->name = $request->name;
        $color_translation->save();

        flash(translate('Color has been inserted successfully'))->success();
        return redirect()->route('colors.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $lang   = $request->lang;
        $color  = Color::findOrFail($id);
        return view('backend.product.colors.edit', compact('color','lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $color = Color::findOrFail($id);
        $color->name = $request->name;
        $color->code = $request->code;
        $color->save();

        $color_translation = ColorTranslation::firstOrNew(['lang' => $request->lang, 'color_id' => $color->id]);
        $color_translation->name = $request->name;
        $color_translation->save();

        flash(translate('Color has been updated successfully'))->success();
        return back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $color = Color::findOrFail($id);
        Product::where('color_id', $color->id)->delete();
        foreach ($color->color_translations as $color_translation) {
            $color_translation->delete();
        }
        Color::destroy($id);

        flash(translate('Color has been deleted successfully'))->success();
        return redirect()->route('colors.index');

    }
}
