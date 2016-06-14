<?php

namespace App\Http\Controllers\v1;

use App\Http\Requests;
use App\Models\v1\Visa;
use Dingo\Api\Contract\Http\Request;
use App\Http\Requests\v1\VisaRequest;
use App\Http\Transformers\v1\VisaTransformer;

class VisasController extends Controller
{
    /**
     * @var Visa
     */
    private $visa;

    /**
     * VisasController constructor.
     *
     * @param Visa $visa
     */
    public function __construct(Visa $visa)
    {
        $this->visa = $visa;

        $this->middleware('api.auth');
        $this->middleware('jwt.refresh');
    }

    /**
     * Get all visas.
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $visas = $this->visa;

        if($request->has('user_id'))
            $visas = $visas->where('user_id', $request->get('user_id'));

        $visas = $visas->paginate(25);

        return $this->response->paginator($visas, new VisaTransformer);
    }

    /**
     * Get the specified visa.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $visa = $this->visa->findOrFail($id);

        return $this->response->item($visa, new VisaTransformer);
    }

    /**
     * Create a new visa and save in storage.
     *
     * @param VisaRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(VisaRequest $request)
    {
        $visa = $this->visa->create($request->all());

        $location = url('/visas/' . $visa->id);

        return $this->response->created($location);
    }

    /**
     * Update the specified visa in storage.
     *
     * @param VisaRequest $request
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function update(VisaRequest $request, $id)
    {
        $visa = $this->visa->findOrFail($id);

        $visa->update($request->all());

        return $this->response->item($visa, new VisaTransformer);
    }

    /**
     * Remove the specified visa from storage.
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $visa = $this->visa->findOrFail($id);

        $visa->delete();

        return $this->response->noContent();
    }
}
