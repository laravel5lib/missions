<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\TransportRequest;
use App\Http\Transformers\v1\TransportTransformer;
use App\Models\v1\Transport;
use Illuminate\Http\Request;

use App\Http\Requests;

class TransportsController extends Controller
{

    /**
     * @var Transport
     */
    private $transport;

    /**
     * TransportsController constructor.
     * @param Transport $transport
     */
    public function __construct(Transport $transport)
    {
        $this->transport = $transport;
        $this->middleware('api.auth');
        $this->middleware('jwt.refresh');
    }

    /**
     * Get a list of transports
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function index(Request $request)
    {
        $transports = $this->transport->filter($request->all())
                        ->paginate($request->get('per_page', 10));

        return $this->response->paginator($transports, new TransportTransformer);
    }

    /**
     * Create a new transport
     *
     * @param TransportRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function store(TransportRequest $request)
    {
        $transport = $this->transport->create($request->all());

        return $this->response->item($transport, new TransportTransformer);
    }

    /**
     * Get the specified transport
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function show($id)
    {
        $transport = $this->transport->findOrFail($id);

        return $this->response->item($transport, new TransportTransformer);
    }

    /**
     * Update the specified transport
     *
     * @param $id
     * @param TransportRequest $request
     * @return \Dingo\Api\Http\Response
     */
    public function update($id, TransportRequest $request)
    {
        $transport = $this->transport->findOrFail($id);

        $transport->update($request->all());

        return $this->response->item($transport, new TransportTransformer);
    }

    /**
     * Delete the specified transport
     *
     * @param $id
     * @return \Dingo\Api\Http\Response
     */
    public function destroy($id)
    {
        $transport = $this->transport->findOrFail($id);

        $transport->delete();

        return $this->response->noContent();
    }
}
