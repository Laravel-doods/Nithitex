@php
    $seller_url = '';
    if (Auth::check()) {
        if (Auth::user()->userrole_id == 2) {
            $seller_url = 'seller/';
        }
    }
@endphp
@php
    $main_categories_list = App\Models\MainCategory::orderBy('main_category_name', 'ASC')->get();
@endphp

<div class="sidebar-wrapper sidebar-wrapper-mrg-right">
    <div class="sidebar-widget shop-sidebar-border mb-35 pt-40">
        <h4 class="sidebar-widget-title">Categories </h4>
        <div class="shop-catigory">
            <ul class="main-categories">
                @foreach ($main_categories_list as $category_view)
                    <li class="main-category">
                        
                        <div class="maincategory-name">
                            @if ($category_view->categories->count() > 0)
                                <span class="toggle-arrow"><i class="fas fa-angle-right" style="color:#535050"></i></span> 
                            @endif
                            {{ $category_view->main_category_name }}
                        </div>
                        <ul class="categorieses px-3 pt-2">
                            @foreach ($category_view->categories->sortBy('category_name') as $category)
                                <li>
                                    <a
                                        href="{{ url(($seller_url == '' ? '' : $seller_url) . 'category/product/' . $category->id) }}">
                                        <span class="text-muted">{{ $category->category_name }}</span>
                                        
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @php
        $tags = App\Models\Product::where('tags', '!=', null)->groupBy('tags')->select('tags')->limit('8')->get();
        $tagscount = App\Models\Product::where('tags', '!=', null)->get()->count();
    @endphp
    @if ($tagscount > 0)
        <div class="sidebar-widget shop-sidebar-border pt-40">
            <h4 class="sidebar-widget-title">Popular Tags</h4>
            <div class="tag-wrap sidebar-widget-tag">
                @foreach ($tags as $product_tag)
                    <a
                        href="{{ url(($seller_url == '' ? '' : $seller_url) . 'product/tag/' . $product_tag->tags) }}">{{ str_replace(',', ' ', $product_tag->tags) }}</a>
                @endforeach
            </div>
        </div>
    @endif
</div>
