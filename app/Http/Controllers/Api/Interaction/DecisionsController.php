<?php

namespace App\Http\Controllers\v1\Interaction;

use App\Http\Controllers\v1\Controller;

use App\Http\Requests;
use App\Http\Requests\v1\Interaction\DecisionRequest;
use App\Http\Transformers\v1\Interaction\DecisionTransformer;
use App\Models\v1\Interaction\Decision;
use Dingo\Api\Contract\Http\Request;

class DecisionsController extends Controller
{

    /**
     * @var Decision
     */
    private $decision;

    /**
     * ContactsController constructor.
     * @param Decision $decision
     */
    public function __construct(Decision $decision)
    {
        $this->decision = $decision;

        $this->middleware('api.auth');
        $this->middleware('jwt.refresh');
    }

    public function index(Request $request)
    {
        $decisions = $this->decision
                          ->filter($request->all())
                          ->paginate($request->get('per_page', 10));

        return $this->response->paginator($decisions, new DecisionTransformer);
    }

    public function store(DecisionRequest $request)
    {
        $decision = $this->decision->create($request->all());

        $location = url('/interactions/decisions/' . $decision->id);

        return $this->response->created($location);
    }

    public function show($id)
    {
        $decision = $this->decision->findOrFail($id);

        return $this->response->item($decision, new DecisionTransformer);
    }

    public function update(DecisionRequest $request, $id)
    {
        $decision = $this->decision->findOrFail($id);

        $decision->update($request->all());

        return $this->response->item($decision, new DecisionTransformer);
    }

    public function destroy($id)
    {
        $decision = $this->decision->findOrFail($id);

        $decision->delete();

        return $this->response->noContent();
    }
}
