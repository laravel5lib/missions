<?php
namespace App;

use App\Models\v1\Group;
use Dingo\Api\Contract\Http\Request;

class GroupPromoHandler
{
    protected $group;

    function __construct(Group $group)
    {
        $this->group = $group;
    }

    /**
     * @param Request $request
     * @return object
     */
    public function create($request)
    {
        $group = $this->group->findOrFail($request->get('promoteable_id'));

        $promos = $group->promote(
            $request->get('name'),
            $request->get('qty', 1),
            $request->get('reward'),
            $request->get('expires_at'),
            $request->get('affiliates')
        );

        return $promos;
    }

}