<?php

namespace App\Http\View\Composers;

use App\BusinessSetting;
use App\Category;
use Illuminate\View\View;

class FrontPageComposer
{
    private $frontCategories;
    private $businessSettings;

    public function __construct()
    {
        $this->loadBusinessSetting();
        $this->loadFrontCategories();
    }

    public function loadBusinessSetting()
    {

        // $this->businessSettings = BusinessSetting::all()->keyBy('type');

        $this->businessSettings = cache()->remember('businessSettings', 3600, function () {
            return $this->businessSettings = BusinessSetting::whereIn('type', [
                'home_slider_images', 'home_slider_links',
                'home_banner1_images', 'home_banner1_links',
                'home_banner2_images', 'home_banner2_links',
                'home_banner3_images', 'home_banner3_links',
                'top10_categories', 'top10_brands',
                'system_default_currency',
                'vendor_system_activation',
                'decimal_separator',
                'no_of_descimal',
                'google_analytics',
                'facebook_pixel',
                'top10_categories',
                'top10_brands'
            ])->get()->pluck('value', 'type');;
        });

        

        // dd($this->businessSettings, $this->businessSettings['home_slider_images']);

    }
    public function loadFrontCategories()
    {
        //cache()->forget('frontCategories');

        $this->frontCategories = cache()->remember('frontCategories', 3600, function () {
            return Category::where('level', 0)
                ->with(['childrenCategories.childrenCategories' => function ($query) {
                    $query->withCount('products');
                }, 'childrenCategories' => function ($query) {
                    $query->withCount('products');
                }])
                ->with('category_translations')
                ->withCount('products')
                ->orderBy('sort_order', 'asc')
                ->take(11)
                ->get();
        });

        foreach ($this->frontCategories as $category) {
            foreach ($category->childrenCategories as $cat) {
                $cat->products_count += $cat->childrenCategories->sum('products_count');
            }

            $category->products_count += $category->childrenCategories->sum('products_count');
        }

        $this->frontCategories = $this->frontCategories->filter(function ($value, $key) {
            return $value->products_count > 0;
        });
    }

    public function compose(View $view)
    {

        $view->with('frontCategories', $this->frontCategories);
        $view->with('businessSettings', $this->businessSettings);
    }
}
