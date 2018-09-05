<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp;

class HomeController extends Controller
{
    public $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('web');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Validate input
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validator($request->all())->validate();
        $longUrl = $request->get('long_url');
	        if ($this->validateUrlFormat($longUrl) == false) {
                return redirect()->back()->withErrors(['Wrong format']);
	        }

            if (!$this->checkUrlExists($longUrl)) {
                return redirect()->back()->withErrors(['URL is not exists']);

            }

        $url = $this->create($longUrl);
            return redirect($this->redirectTo)->with(['url' => $url]);
    }

    /**
     * Get a validator for an incoming request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'long_url' => 'required|string|max:255',
        ]);
    }


    /**
     * Create a new short URL
     *
     * @param $longUrl
     * @return mixed
     */
    protected function create($longUrl)
    {
        return ShortUrl::make($longUrl);
    }

    /**
     * Redirect to the original URL
     *
     * @param $shortUrl
     */
    public function goToOriginal($shortUrl)
    {
        $url = ShortUrl::where('short_url', $shortUrl)->first();
        header("Location: $url->long_url", true, 301);
    }

    /**
     * Check original URL
     *
     * @param $longUrl
     * @return bool
     */
    public function checkUrlExists($longUrl)
    {
        $client = new GuzzleHttp\Client();
        $request = $client->get($longUrl, ['http_errors' => false]);
        return ($request->getStatusCode() != 404);
    }

    /**
     * Validate original URL
     *
     * @param $url
     * @return mixed
     */
    protected function validateUrlFormat($url) {
        return filter_var($url, FILTER_VALIDATE_URL,
            FILTER_FLAG_HOST_REQUIRED);
    }
}
