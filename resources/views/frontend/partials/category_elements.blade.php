<div class="card-columns">

    @php
        $ids = \App\Utility\CategoryUtility::get_immediate_children_ids($category->id);
        $categories = \App\Category::whereIn('id', $ids)
            ->with(['childrenCategories' => function ($query) {
                $query->withCount('products')
                    ->with('category_translations');
            }, 'childrenCategories.childrenCategories' => function ($query) {
                $query->withCount('products')->with('category_translations');
            }, 'category_translations'])
            ->withCount('products')
            ->get();

        foreach ($categories as $category) {
            foreach($category->childrenCategories as $cat) {
                $cat->products_count += $cat->childrenCategories->sum('products_count');
            }
            $category->products_count += $category->childrenCategories->sum('products_count');
        }
    @endphp

    {{-- @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($category->id) as $key => $first_level_id) --}}
    @foreach($categories as $category)
        @if($category->products_count)
        <div class="card shadow-none border-0">
            <ul class="list-unstyled mb-3">
                <li class="fw-600 border-bottom pb-2 mb-3">
                    @php
                        $tr = $category->category_translations->where('lang', app()->getLocale())->first();
                        $category_name = $tr ? $tr->name : $category->name;
                    @endphp
                    <a class="text-reset" href="{{ route('products.category', $category->slug) }}"> {{ $category_name }} <!-- ({{$category->products_count}}) --> </a>
                </li>

                @foreach($category->childrenCategories as $cat)
                    @if($cat->products_count && $cat->products->count())
                        <li class="mb-2">
                            <a class="text-reset" href="{{ route('products.category',$cat->slug) }}">{{ $cat->getTranslation('name') }} <!-- ({{$cat->products_count}}) --> </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
        @endif
    @endforeach
</div>
