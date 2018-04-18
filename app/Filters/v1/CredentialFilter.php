<?php namespace App\Filters\v1;

use App\Models\v1\User;

class CredentialFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    /**
     * Default sortable fields.
     *
     * @var array
     */
    public $sortable = ['applicant_name', 'created_at', 'updated_at', 'expired_at'];

    /**
     * Default searchable fields.
     *
     * @var array
     */
    public $searchable = ['applicant_name', 'content'];

    /**
     * Filter by user id.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function user($id)
    {
        if (! key_exists('manager', $this->input)) {
            return $this->where('holder_id', $id)
                        ->where('holder_type', 'users');
        }

        return null;
    }

    /**
     * By reservation id
     *
     * @param  String $id
     * @return Builder
     */
    public function reservation($id)
    {
        return $this->where('holder_id', $id)
                    ->where('holder_type', 'reservations');
    }

    /**
     * By type
     *
     * @param  String $type
     * @return Builder
     */
    public function type($type)
    {
        return $this->where('type', $type);
    }

    /**
     * By status
     *
     * @param  String $status
     * @return Response
     */
    public function status($status)
    {
        if (! in_array($status, ['expired', 'active'])) {
            return $this;
        }

        return $this->{$status}();
    }

    public function manager($user_id)
    {
        $user = User::findOrFail($user_id);

        $facilitated_users = $user->facilitating->flatMap(function ($trip) {
            return $trip->reservations->pluck('user_id');
        });

        $managed_users = $user->managing->flatMap(function ($group) {
            return $group->trips->flatMap(function ($trip) {
                return $trip->reservations->pluck('user_id');
            });
        });

        $users = $facilitated_users->merge($managed_users)->unique()->all();

        if (key_exists('user', $this->input)) {
            $users = array_prepend($users, $this->input['user']);
        }

        return $this->whereIn('holder_id', $users)->where('holder_type', 'users');
    }
}
