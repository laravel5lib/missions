<?php

namespace App\Models\v1;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Models\v1\Slug;
use Conner\Tagging\Taggable;
use App\Models\v1\Reservation;
use EloquentFilter\Filterable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Presenters\FundraiserPresenter;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Fundraiser extends Model implements HasMedia
{
    use Filterable, Taggable, SoftDeletes, FundraiserPresenter, HasMediaTrait;

    /**
     * Boot the Uuid trait for the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Uuid::uuid4();
        });
    }

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fundraisers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'started_at', 'ended_at', 'goal_amount', 'description',
        'sponsor_id', 'sponsor_type', 'url', 'type', 'public', 'show_donors',
        'created_at', 'updated_at', 'short_desc', 'featured_image_id', 'fund_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'started_at', 'ended_at', 'created_at', 'updated_at', 'deleted_at'
    ];

    protected $casts = ['public' => 'boolean'];

    protected $appends = ['featured_image'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('optimized')
             ->optimize()
             ->fit(Manipulations::FIT_MAX, 800, 600)
             ->nonQueued();
    }

    /**
     * Get the fundraiser's sponsor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function sponsor()
    {
        return $this->morphTo();
    }

    /**
     * Get the fund the fundraiser belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function fund()
    {
        return $this->belongsTo(Fund::class)->withTrashed();
    }

    /**
     * Get the fundraiser's slug.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function slug()
    {
        return $this->morphOne(Slug::class, 'slugable');
    }

    /**
     * Get all the fundraiser's stories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function stories()
    {
        return $this->morphToMany(Story::class, 'publication', 'published_stories', 'published_id')
            ->withPivot('published_at')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get the donations made through the fundraiser.
     *
     * @return mixed
     */
    public function donations()
    {
        return $this->fund->donations();
    }

    /**
     * Get all the transactions related to the fundraiser.
     *
     * @return mixed
     */
    public function transactions()
    {
        return $this->fund->transactions;
    }

    /**
     * Get the donors who gave through the fundraiser.
     *
     * @return mixed
     */
    public function donors()
    {
        return $this->fund->donors();
    }

    /**
     * Get the scheduled reminders of the fundraiser.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function reminders()
    {
        return $this->morphMany(Reminder::class, 'remindable');
    }

    /**
     * Get the fundraiser's total amount raised.
     *
     * @return integer
     */
    public function raised()
    {
        $amount = $this->transactions()->sum('amount'); // in cents

        return $amount ? (int) $amount : 0;
    }

    /**
     * Get the fundraiser's total amount raised in dollars.
     *
     * @return string
     */
    public function raisedAsDollars()
    {
        return number_format(($this->raised() / 100), 2, '.', '');
    }

    /**
     * Set the fundraiser's goal amount in cents.
     *
     * @param integet $value
     */
    public function setGoalAmountAttribute($value)
    {
        $this->attributes['goal_amount'] = $value*100; // convert to cents
    }

    /**
     * Get the goal amount for the fundraiser.
     * @param  float $value
     * @return float
     */
    public function getGoalAmountAttribute($value)
    {
        // temporary fix
        if ($this->fund && $this->fund->fundable instanceOf Reservation) {
            return $this->fund->fundable->getTotalCost();
        }

        return $value;
    }

    /**
     * Get the fundraiser's goal amount in dollars.
     *
     * @return string
     */
    public function goalAmountAsDollars()
    {
        return number_format(($this->goal_amount / 100), 2, '.', '');
    }

    public function getUrlAttribute($value)
    {
        return $this->slug ? $this->slug->url : ( $value ?: 'fundraisers/'.$this->uuid);
    }

    public function getFeaturedImageAttribute()
    {
        $featuredImage = $this->getMedia('featured');
        
        return isset($featuredImage[0]) 
            ? $featuredImage[0]->getUrl('optimized') 
            : null;
    }

    /**
     * Get the fundraiser's percentage raised.
     *
     * @return integer
     */
    public function getPercentRaised()
    {
        // check for 0 values first,
        // because division by zero is not possible
        if ($this->raised() === 0 or $this->goal_amount === 0) {
            return 0;
        }

        return (int) round(($this->raised()/$this->goal_amount) * 100);
    }

    /**
     * Get the Fundraiser's Status.
     *
     * @return string
     */
    public function getStatus()
    {
        if ($this->ended_at->isPast()) {
            return 'closed';
        }

        if ($this->getPercentRaised() >= 100) {
            $this->ended_at = Carbon::now();

            return 'closed';
        }

        return 'open';
    }

    /**
     * Check if fundraiser is closed.
     *
     * @return boolean
     */
    public function isClosed()
    {
        if ($this->getStatus() == 'closed') {
            return true;
        }

        return false;
    }

    /**
     * Check if fundraiser is closed.
     *
     * @return boolean
     */
    public function isOpen()
    {
        if ($this->getStatus() == 'open') {
            return true;
        }

        return false;
    }

    /**
     * Get public fundraisers
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopePublic($query)
    {
        return $query->where('public', true);
    }

    /**
     * Close Fundraiser
     */
    public function close()
    {
        $this->delete();
    }

    /**
     * Open a closed fundraiser.
     */
    public function open()
    {
        $this->restore();
    }

    // public function setDescriptionAttribute($value)
    // {
    //     $this->attributes['description'] = json_encode($value);
    // }

    // public function getDescriptionAttribute($value)
    // {
    //     return is_string($value) ? $value : json_decode($value);
    // }

    public function getPublicAttribute()
    {   
        return !$this->description ? false : $this->attributes['public'];
    }
}
