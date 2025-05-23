<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\Slider;
use App\Models\Settings;

class MainController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $attributes = Attribute::all();
        $slider = Slider::all();

        $release_count = $request->input('release', 10);
        $release_products = Product::orderBy('created_at', 'desc')->take($release_count)->get();

        $showCase = collect();
        $settings = Settings::first();
        $settingsData = $settings->styles['showcase'] ?? [];
        if (count($settingsData) == 0) {
            $showCase = Product::inRandomOrder()->take(10)->get();
        } else { $showCase = Product::whereIn('id', $settingsData)->get(); }

        $discount_count = $request->input('discount', 10);
        $discount_products = Product::orderByRaw('(COALESCE(discount, 0) / price) DESC')->take($discount_count)->get();

        return Inertia::render('Main/HomePage', [
            'categories' => $categories, 
            'slider' => $slider,
            'release' => $release_products,
            'showCase' => $showCase,
            'discounts' => $discount_products,
            'attributes' => $attributes
        ]);   
    }

    public function productsByCategory(Category $category, Request $request) {
        $attributes = Attribute::all();

        $products = $category->products();
    
        // Фильтрация по атрибутам
        if ($request->has('filter_attributes')) {
            $filterAttributes = $request->input('filter_attributes');

            $products->where(function ($query) use ($filterAttributes) {
                foreach ($filterAttributes as $filterKey => $filterValues) {
                    $query->where(function ($subQuery) use ($filterKey, $filterValues) {
                        foreach ($filterValues as $filterValue) {
                            $jsonCondition = json_encode([$filterKey => $filterValue]);
                            $subQuery->orWhereRaw("JSON_CONTAINS(`attributes`, ?)", [$jsonCondition]);
                        }
                    });
                }
            });
        }

        // filter_updated
        if ($request->has('filter_updated')) {
            $filterUpdated = $request->input('filter_updated');
            foreach ($filterUpdated as $column => $values) {
                if (!empty($values)) {
                    $products->whereIn($column, $values);
                }
            }
        }

        // filter by price
        if ($request->has('price')) {
            $priceFilter = $request->input('price');
            if (!empty($priceFilter['from']) && !empty($priceFilter['to'])) {
                $products->where(function ($query) use ($priceFilter) {
                    $query->whereBetween('price', [$priceFilter['from'], $priceFilter['to']])
                        ->orWhereBetween('discount', [$priceFilter['from'], $priceFilter['to']]);
                });
            } elseif (!empty($priceFilter['from'])) {
                $products->where(function ($query) use ($priceFilter) {
                    $query->where('price', '>=', $priceFilter['from'])
                        ->orWhere('discount', '>=', $priceFilter['from']);
                });
            } elseif (!empty($priceFilter['to'])) {
                $products->where(function ($query) use ($priceFilter) {
                    $query->where('price', '<=', $priceFilter['to'])
                        ->orWhere('discount', '<=', $priceFilter['to']);
                });
            } elseif ($priceFilter === 'discount') {
                $products->whereNotNull('discount');
            } else {
                $products->where(function ($query) use ($priceFilter) {
                    $query->where('price', $priceFilter)->orWhere('discount', $priceFilter);
                });
            }
        }
        // sorting
        $sortBy = $request->input('sortBy', ''); 
        switch ($sortBy) {
            case 'price+':
                $products->orderByRaw('COALESCE(discount, price) ASC');
                break;
            case 'price-':
                $products->orderByRaw('COALESCE(discount, price) DESC');
                break;
            case 'discount':
                $products->orderByRaw('price - discount DESC');
                break;
            case 'title+':
                $products->orderBy('title', 'ASC');
                break;
            case 'title-':
                $products->orderBy('title', 'DESC');
                break;
            case 'created':
            case '':
            default:
                $products->orderBy('created_at', 'DESC');
                break;
        }
    
        $totalProducts = $products->count();
        session()->put('data.total_products', $totalProducts);

        $productsPaginate = $products->paginate(4, ['*'], 'page', $request->input('page', 1));
    
        $productData = $category->products()->get(['brand', 'type', 'country', 'price', 'discount', 'used']);
        $brands = $productData->pluck('brand')->unique();
        $types = $productData->pluck('type')->unique();
        $countries = $productData->pluck('country')->unique();
        $prices = $productData->map(function ($product) {
            return $product->discount ? $product->discount : $product->price;
        })->unique();
        //dd($category);
        return Inertia::render('Main/ProductsByCategory', [
            'category' => $category,
            'products' => $productsPaginate,
            'attributes' => $attributes,
            'filter' => [
                'brand' => $brands,
                'type' => $types,
                'country' => $countries,
                'price' => $prices,
            ],
        ]);
    }

    public function productsBySearch(Request $request)
    {
        $request->session()->forget('searchProducts');
        $search = $request->input('search', '');
        $brand = $request->input('brand', '');
        $type = $request->input('type', '');
        $attributes = $request->input('attributes', []);
        
        // Запрос продуктов с жадной загрузкой категорий
        $products = Product::query();
    
        if ($search) {
            $products->where('title', 'like', "%{$search}%");
        }
        if ($brand) {
            $products->where('brand', 'like', "%{$brand}%");
        }
        if ($type) {
            $products->where('type', 'like', "%{$type}%");
        }
    
        // Загружаем продукты с их категориями
        $products = $products->with('categories')->limit(20)->get();
    
        // Сохраняем сгруппированные продукты в сессии
        session(['searchProducts' => $products]);
    
    }

    public function productById(Request $request) {
        $product = Product::with('categories')->find($request->id);
        if (!$product) {
            //dd( Settings::first()->settings['loading_dark']);
            $product = [
              'title' => [
                'en' => 'title'
              ],
              'price' => 'price',
              'discount' => 'discount',
              'categories'=> [['name_en'=> 'category']],
              'type'=> 'type',
              'brand'=> 'brand',
              'images' => null
            ];
        }
        //dd($product);
        return Inertia::render('Main/ProductById',[
            'product' => $product,
            
        ]);
    }

    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
