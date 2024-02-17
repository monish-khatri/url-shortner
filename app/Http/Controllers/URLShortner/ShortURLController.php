<?php

namespace App\Http\Controllers\URLShortner;

use App\Http\Controllers\Controller;
use App\Models\ShortURL;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ShortURLController extends Controller
{
    /**
     * Display the list of short URLs.
     */
    public function index(): Response
    {
        return Inertia::render('ShortURL/Index', [
            'shortUrls' => ShortURL::all(),
        ]);
    }

    /**
     * Display the form to create a new short URL.
     */
    public function create(): Response
    {
        return Inertia::render('ShortURL/Create', [
            'status' => session('status'),
        ]);
    }

    /**
     * Store a new short URL.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        return Redirect::route('short-url.index');
    }

    /**
     * Redirect Short URL to the original URL.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function show($code): RedirectResponse
    {
        $shortUrl = ShortURL::findByCode($code);
        if(! $shortUrl->is_active) {
          return Redirect::route('short-url.index');
        }
        return Redirect::to($shortUrl->redirect_url);
    }
}
