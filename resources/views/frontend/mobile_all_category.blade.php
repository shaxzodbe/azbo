@extends('frontend.layouts.app')

@section('css')
    <style>
        .aiz-main-wrapper {
            background-color: #fff;
        }

        .main {
            height: 100%;
        }

        .main {
            display: -ms-flexbox;
            display: flex;
            padding-bottom: 54px;
        }

        .main-top {
            padding-top: 56px;
        }


        ._2LERv {
            -ms-flex-negative: 0;
            flex-shrink: 0;
            width: 90px;
            background: #fbfbfb;
            height: calc(100vh) !important;
            overflow: auto;
            -webkit-overflow-scrolling: touch;
            cursor: pointer;
        }

        ._2LERv::-webkit-scrollbar {
            display: none;
        }

        .ENvVF {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            -ms-flex-align: center;
            align-items: center;
            -ms-flex-pack: center;
            justify-content: center;
            height: 80px;
            padding: 0 8px;
        }

        .ENvVF._3TE6M:before {
            content: "";
            position: absolute;
            left: 0;
            width: 4px;
            height: 100%;
            background: #000;
        }

        ._2oIsS {
            width: 32px;
            height: 32px;
        }

        ._1O_jv {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            width: 100%;
            margin-top: 4px;
            font-size: 10px;
            text-align: center;
            color: #3a3e4a;
            word-break: break-word;
            overflow: hidden;
        }

        /** content  */

        .Ghz0I {
            position: relative;
            -ms-flex-positive: 1;
            flex-grow: 1;
            padding: 0 16px;
            height: calc(100vh);
            overflow: auto;
            -webkit-overflow-scrolling: touch;
        }

        .Ghz0I::-webkit-scrollbar {
            display: none;
        }

        .-Aob2 {
            display: block;
            height: 20px;
            margin: 16px 0;
            font-size: 14px;
            font-weight: 400;
        }

        .P_K5d {
            font-size: 2rem;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-wrap: wrap;
            flex-wrap: wrap;
        }

        ._2Eqko,
        .P_K5d {
            margin-bottom: 8px;
        }

        ._2Eqko {
            position: relative;
            margin-right: 8px;
            width: calc(33.33333% - 5.33333px);
        }

        ._2Eqko:nth-child(3n) {
            margin-right: 0;
        }

        ._1kuAi {
            position: relative;
            padding-bottom: 100%;
            height: 0;
            overflow: hidden;
        }

        ._1kuAi:after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            bottom: 0;
            border-radius: 4px;
            background: rgba(0, 0, 0, 0.04);
        }

        .pjhmq {
            position: absolute;
            top: 50%;
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
            width: 100%;
            border-radius: 4px;
        }

        ._1q55l,
        ._19y2g .pjhmq {
            height: 12.6667vw;
            padding: 8px;
        }

        .EMxoF {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            margin-top: 2px;
            min-height: 30px;
            text-align: center;
            font-size: 10px;
            overflow: hidden;
        }

        ._2yabe {
            -ms-flex-positive: 1;
            flex-grow: 1;
        }

        .CSMfl {
            -ms-flex-negative: 0;
            flex-shrink: 0;
        }

        .icon-svg {
            width: 1em;
            height: 1em;
            vertical-align: -0.15em;
            fill: currentColor;
            overflow: hidden;
        }

        .ao-nav {
            display: none; 
        }

    </style>
@endsection
@section('content')
    <div class="main">
        <!-- nav sidebar -->
        <nav class="_2LERv">
            @foreach ($categories as $key => $category)
                @php
                    $products_count = $category->products->count();
                    foreach ($category->categories as $cat_) {
                        $products_count += $cat_->products->count();
                    
                        foreach ($cat_->categories as $c) {
                            $products_count += $c->products->count();
                        }
                    }
                @endphp

                @if ($products_count)
                    <a href="/categories/{{ $category->id }}" class="ENvVF @if ($category->id == $k) _3TE6M @endif">
                        <img class="_2oIsS" src="{{ uploaded_asset($category->icon) }}" alt="">
                        <span class="_1O_jv">{{ $category->getTranslation('name') }}</span>
                    </a>
                @endif
            @endforeach
        </nav>

        <!-- main content -->
        <div class="Ghz0I">
            @foreach ($categories as $category)
                @if ($category->id == $k)
                    @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($category->id) as $key => $first_level_id)
                        @php
                            $sub_category = \App\Category::find($first_level_id);
                            
                            $p_count = $sub_category->products->count();
                            foreach ($sub_category->categories as $c) {
                                $p_count += $c->products->count();
                            }
                        @endphp

                        @if ($p_count)
                            <h3 class="-Aob2" data-toggle="collapse" href="#cat_{{ $sub_category->id }}">
                                <span class="_2yabe">
                                    {{ $sub_category->getTranslation('name') }}
                                    
                                </span>
                                <svg class="icon-svg CSMfl" style="font-size: 12px;">
                                    <use xlink:href="#icon-icArrowDown"></use>
                                </svg>
                            </h3>
                            <div class="P_K5d collapse @if ($key == 0) show @endif" id="cat_{{ $sub_category->id }}">
                                @foreach (\App\Utility\CategoryUtility::get_immediate_children_ids($first_level_id) as $key => $second_level_id)

                                    @php
                                        $sub_item_cat = \App\Category::find($second_level_id);
                                        
                                    @endphp

                                    @if ($sub_item_cat->products->count())
                                        <a href="{{ route('products.category', $sub_item_cat->slug) }}"
                                            class="_2Eqko">

                                            <div class="_1kuAi">
                                                <img src="{{ uploaded_asset($sub_item_cat->banner) }}" alt=""
                                                    class="pjhmq product-img">
                                            </div>

                                            <p class="EMxoF">
                                                {{ \App\Category::find($second_level_id)->getTranslation('name') }} </p>
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                @endif
            @endforeach

        </div>

    </div>
@endsection
