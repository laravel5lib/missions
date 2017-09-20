<?php namespace App\Filters\v1;

class UserFilter extends Filter
{

    /**
     * Fields that can be sorted.
     *
     * @var array
     */
    public $sortable = [
        'first_name', 'last_name', 'email', 'alt_email', 'zip',
        'country_code', 'state', 'city', 'created_at',
        'updated_at', 'birthday'
    ];

    /**
     * Fields that can be searched.
     *
     * @var array
     */
    public $searchable = [
        'first_name', 'last_name', 'email', 'alt_email', 'city', 'state',
        'phone_one', 'phone_two', 'zip'
    ];

    /**
     * Find by public or private
     *
     * @param $isPublic
     * @return mixed
     */
    public function isPublic($isPublic)
    {
        if (! $isPublic) {
            return $this;
        }

        return $isPublic == 'yes' ?
            $this->where('public', true) :
            $this->where('public', false);
    }

    /**
     * By gender.
     *
     * @param $gender
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function gender($gender)
    {
        if (! $gender) {
            return $this;
        }

        return $this->where('gender', $gender);
    }

    /**
     * By relationship status.
     *
     * @param $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function status($status)
    {
        if (! $status) {
            return $this;
        }

        return $this->where('status', $status);
    }

    /**
     * By country code.
     *
     * @param $countries
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function country($countries)
    {
        if ($countries = []) {
            return $this;
        }

        return $this->whereIn('country_code', $countries);
    }

    /**
     * By url.
     *
     * @param $url
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function url($url)
    {
        if (! $url) {
            return $this;
        }

        return $this->whereHas('slug', function ($slug) use ($url) {
            return $slug->where('url', $url);
        });
    }

    /**
     * By fundraiser url.
     *
     * @param $url
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function fundraiser($url)
    {
        if (! $url) {
            return $this;
        }

        return $this->whereHas('fundraisers', function ($fundraiser) use ($url) {
            $fundraiser->where('url', $url);
        });
    }

    /**
     * Is a rep
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function rep()
    {
        return $this->isRep();
    }

    /**
     * By facilitating.
     *
     * @param $url
     * @return \Illuminate\Database\Eloquent\Builder
     */
    /*public function isFacilitator($isFacilitator)
    {
        if( !$isFacilitator ) return $this;

        return $this->has('facilitating', '>', 1)->orWhereHas('managing', function ($managing) {
            $managing->has('trips', '>', 1);
        });
    }*/
}
