<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class ShortURL
 * @property int $id
 * @property string $code
 * @property string $redirect_url
 * @property bool $single_use
 * @property bool $track_visits
 * @property int $clicks
 * @property bool $is_active
 * @property Carbon $activated_at
 * @property Carbon|null $deactivated_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ShortURL extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'short_urls';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'code',
        'redirect_url',
        'single_use',
        'track_visits',
        'clicks',
        'is_active',
        'activated_at',
        'deactivated_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @deprecated This field is no longer used in Laravel 10 and above.
     *             It will be removed in a future release.
     *
     * @var array
     */
    protected $dates = [
        'activated_at',
        'deactivated_at',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'single_use' => 'boolean',
        'track_visits' => 'boolean',
        'is_active' => 'boolean',
        'activated_at' => 'datetime',
        'deactivated_at' => 'datetime',
    ];

    /**
     * A short URL can be visited many times.
     *
     * @return HasMany<ShortURLVisit>
     */
    public function visits(): HasMany
    {
        return $this->hasMany(ShortURLVisit::class, 'short_url_id');
    }

    /**
     * A helper method that can be used for finding
     * a ShortURL model with the given URL code.
     *
     * @param  string  $URLcode
     * @return ShortURL|null
     */
    public static function findByCode(string $URLcode): ?self
    {
        return self::where('code', $URLcode)->first();
    }

    /**
     * A helper method that can be used for finding
     * all of the ShortURL models with the given
     * redirect URL.
     *
     * @param  string  $redirectURL
     * @return Collection<int, ShortURL>
     */
    public static function findByRedirectURL(string $redirectURL): Collection
    {
        return self::where('redirect_url', $redirectURL)->get();
    }

    /**
     * A helper method to determine whether if tracking
     * is currently enabled for the short URL.
     *
     * @return bool
     */
    public function trackingEnabled(): bool
    {
        return $this->track_visits;
    }
}
