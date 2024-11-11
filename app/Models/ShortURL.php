<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;

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
     * A class that can be used to try and detect the
     * browser and operating system of the visitor.
     *
     * @var Agent
     */
    protected $agent;

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
        'user_id',
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

    protected $appends = ['short_url'];

    public function __construct()
    {
        $this->agent = new Agent();
    }

     /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'code'; // Use 'code' column as the route key
    }

    /**
     * Get the short_url
     */
    protected function shortUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => route('short-url.redirection', ['code' => $this->code]),
        );
    }

    /**
     * Get the deactivated_at
     */
    protected function deactivatedAt(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                // Check if $value is a string and convert it to a Carbon instance
                $carbon = is_string($value) ? Carbon::parse($value) : $value;
                return $carbon ? $carbon->format('Y-m-d') : '';
            }
        );
    }

    /**
     * Get the activated_at
     */
    protected function activatedAt(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                // Check if $value is a string and convert it to a Carbon instance
                $carbon = is_string($value) ? Carbon::parse($value) : $value;
                return $carbon ? $carbon->format('Y-m-d') : '';
            }
        );
    }

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

    public function shouldAllowAccess() {

        // Check if there are any restrictions on this url
        if (! $this->is_active) {
            return false;
        }

        if ($this->single_use && $this->visits()->count()) {
            return false;
        }

        if (! Str::startsWith($this->redirect_url, ['http://', 'https://'])) {
            return false;
        }

        if ($this->activated_at && now()->isBefore($this->activated_at)) {
            return false;
        }

        if ($this->deactivated_at && now()->isAfter($this->deactivated_at)) {
            return false;
        }

        return true;
    }


    /**
     * Record the visit in the database. We record basic
     * information of the visit if tracking even if
     * tracking is not enabled. We do this so that
     * we can check if single-use URLs have been
     * visited before.
     *
     * @param  Request  $request
     * @return ShortURLVisit
     */
    public function recordVisit(Request $request): ShortURLVisit
    {
        $visit = new ShortURLVisit();

        $visit->short_url_id = $this->id;
        $visit->visited_at = now();

        if ($this->track_visits || $this->single_use) {
            $this->trackVisit($request, $visit);
        }

        $visit->save();

        return $visit;
    }

    /**
     * Check which fields should be tracked and then
     * store them if needed. Otherwise, add them
     * as null.
     *
     * @param  Request  $request
     * @param  ShortURLVisit  $visit
     */
    private function trackVisit(Request $request, ShortURLVisit $visit): void
    {
        $visit->ip_address = $request->ip();

        $visit->operating_system = $this->agent->platform();

        $visit->operating_system_version = $this->agent->version($this->agent->platform());

        $visit->browser = $this->agent->browser();

        $visit->browser_version = $this->agent->version($this->agent->browser());

        $visit->referer_url = $request->headers->get('referer');

        $visit->device_type = $this->guessDeviceType();
    }

    /**
     * Guess and return the device type that was used to
     * visit the short URL.
     *
     * @return string
     */
    private function guessDeviceType(): string
    {
        if ($this->agent->isDesktop()) {
            return ShortURLVisit::DEVICE_TYPE_DESKTOP;
        }

        if ($this->agent->isMobile()) {
            return ShortURLVisit::DEVICE_TYPE_MOBILE;
        }

        if ($this->agent->isTablet()) {
            return ShortURLVisit::DEVICE_TYPE_TABLET;
        }

        if ($this->agent->isRobot()) {
            return ShortURLVisit::DEVICE_TYPE_ROBOT;
        }

        return '';
    }

    /**
     * Check the permission to see/update/delete URL
     *
     * @return bool
     */
    public static function hasPermission($shortUrl) {
        return $shortUrl->user_id == auth()->user()->id ?: abort(404);
    }
}
