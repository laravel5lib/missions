<?php namespace App\Filters\v1;

class ReferralFilter extends Filter
{
    use Manageable;

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
    public $sortable = [
        'applicant_name', 'attention_to', 'recipient_email',
        'sent_at', 'responded_at', 'created_at',
        'updated_at', 'type'
    ];

    /**
     * Default searchable fields.
     *
     * @var array
     */
    public $searchable = [
        'applicant_name', 'attention_to', 'recipient_email', 'referrer->name'
    ];

    /**
     * By status.
     *
     * @param $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function status($status)
    {
        if (! in_array($status, ['draft', 'sent', 'received'])) {
            return $this;
        }

        return $this->{$status}();
    }

    /**
     * By type.
     *
     * @param $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function type($type)
    {
        return $this->where('type', $type);
    }

    /**
     * By user id.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function user($id)
    {
        if (! key_exists('manager', $this->input)) {
            return $this->where('user_id', $id);
        }

        return null;
    }
}
