<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortUrl extends Model
{

    protected $table = 'urls';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'short_url', 'long_url'
    ];

    /**
     * Make new short URL or return existed short URL for requested original URL
     * @param $longUrl
     * @return mixed
     */
    public static function make($longUrl)
    {
        $existed = self::where('long_url', $longUrl)->first();

        if (is_null($existed)) {
            do {
                $shortUrl = str_random(6);
            } while (self::checkExists($shortUrl));

            return self::create(['short_url' => $shortUrl, 'long_url' => $longUrl]);
        }
        return $existed;
    }

    /**
     * Check short URL in database
     *
     * @param $shortUrl
     * @return bool
     */
    public static function checkExists($shortUrl)
    {
        if (is_null(self::select('id')
            ->where('short_url', $shortUrl)
            ->first())) {
            return false;
        }
        return true;
    }
}
