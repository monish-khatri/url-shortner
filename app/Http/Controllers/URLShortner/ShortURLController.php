<?php

namespace App\Http\Controllers\URLShortner;

use App\Http\Controllers\Controller;
use App\Http\Requests\URLShortner\CreateRequest;
use App\Http\Resources\ShortURLResource;
use App\Http\Resources\ShortURLVisitResource;
use App\Models\ShortURL;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Response as HttpResponse;

class ShortURLController extends Controller
{
    /**
     * Display the list of short URLs.
     */
    public function index(): Response
    {
        return Inertia::render('ShortURL/Index', [
            'shortUrls' => ShortURLResource::collection(ShortURL::latest()->get()),
        ]);
    }

    /**
     * Display the form to create a new short URL.
     */
    public function create(): Response
    {
        return Inertia::render('ShortURL/Create');
    }

    /**
     * Store a new short URL.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(CreateRequest $request): Response
    {
        $shortUrl = ShortURL::create($request->validated());

        $status = false;
        $message = "Cannot create Short URL. Please try again!";
        if($shortUrl) {
            $status = true;
            $message = "Short URL created successfully!";
        }
        return Inertia::render('ShortURL/Create', [
            'status' => $status,
            'message' => $message,
        ]);
    }

    /**
     * Store a new short URL.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function show(ShortURL $shortUrl): Response
    {
        return Inertia::render('ShortURL/Show', [
            'shortUrl' => new ShortURLResource($shortUrl),
            'visits' => ShortURLVisitResource::collection($shortUrl->visits()->latest()->get()),
        ]);
    }

    /**
     * Redirect Short URL to the original URL.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function redirectToOriginalLink(Request $request, $code): RedirectResponse
    {
        $shortUrl = ShortURL::findByCode($code);
        if(!$shortUrl->shouldAllowAccess()) {
          abort(HttpResponse::HTTP_FORBIDDEN, message: 'URL does not exist or expired');
        }
        $shortUrl->recordVisit($request);

        return Redirect::to($shortUrl->redirect_url);
    }
}
