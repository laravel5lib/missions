<?php namespace App\Filters\v1;

use App\Models\v1\Fundraiser;
use Illuminate\Support\Facades\DB;

class StoryFilter extends Filter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relatedModel => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    /**
     * Fields that can be sorted.
     *
     * @var array
     */
    public $sortable = ['title', 'content', 'created_at', 'updated_at'];

    /**
     * Fields that can be searched.
     *
     * @var array
     */
    public $searchable = ['title', 'content'];

    /**
     * By group.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function group($id)
    {
        return $this->whereHas('groups', function ($group) use ($id) {
            $group->where('id', $id);
        });
    }

    /**
     * By user.
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function user($id)
    {
        return $this->whereHas('users', function ($user) use ($id) {
            $user->where('id', $id);
        });
    }

    /**
     * By fundraiser.
     *
     * @param $uuid
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function fundraiser($uuid)
    {
        $id = Fundraiser::whereUuid($uuid)->first()->id;

        return $this->whereHas('fundraisers', function ($fundraiser) use ($id) {
            $fundraiser->where('id', $id);
        });

        // $storyIds = DB::table('stories')->join('published_stories', 'stories.id', '=', 'published_stories.story_id')
        //             ->join('fundraisers', 'published_stories.publication_id', '=', 'fundraisers.uuid')
        //             ->where('fundraisers.uuid', $id)
        //             ->pluck('stories.id')
        //             ->toArray();

        // return $this->whereIn('stories.id', $storyIds);
    }
}
