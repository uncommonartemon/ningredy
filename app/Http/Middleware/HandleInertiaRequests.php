<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\Settings;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
       return array_merge(parent::share($request), [
            'searchProducts' => fn () => $request->session()->get('searchProducts', null),
            'cart' => fn () => $request->session()->get('cart', []),
            'data' => fn () => $request->session()->get('data', []),
            // 'styles' => [
            //     'dark3' => '#1F1F1F',
            //     'dark2' => '#232323',
            //     'dark1' => '#292929',
            //     'light' => '#D9E9CF', 
            //     'width' => '350px',
            //     'height' => '400px',
            //     'borderRadius' => '20px',
            //     'dark' => '#2E2E2E',
            //     'darkBlue' => '#16161B',
            //     'darkBlue2' => '#1D1D25', 
            //     'darkBlue3' => '#303046',
            //     'turq' => '#80ffdb',
            //     'turq2' => '#1FAF94', 
            //     'turq3' => '#64dfdf',
            //     'turq4' => '#56cfe1',
            //     'maxWidth' => '1400px'
            // ],
            'styles' => Settings::first()->styles ?? [],
        ]);
    }
}
