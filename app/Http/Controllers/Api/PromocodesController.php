<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use App\Facades\Promocodes;
use App\Models\v1\Promocode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Transformers\v1\PromocodeTransformer;

class PromocodesController extends Controller
{   
    private $promocode;

    function __construct(Promocode $promocode)
    {
        $this->promocode = $promocode;
    }

    public function index(Request $request)
    {
        $codes = $this->promocode
                      ->filter($request->all())
                      ->paginate($request->get('per_page', 10));

        return $this->response->paginator($codes, new PromocodeTransformer);
    }

    public function show($id)
    {
        $code = $this->promocode->findOrFail($id);

        return $this->response->item($code, new PromocodeTransformer);
    }

    public function store(PromocodeRequest $request)
    {
        $codes = Promocodes::output($request->get('quantity', 1));

        $promocodes = collect([]);

        $codes->each(function($code) {
            $promocode = $this->promocode->create([
                'code' => $code,
                'promotional_id' => $request->get('promotional_id')
            ]);

            $promocodes->push($promocode);
        });

        return $this->response->collection($promocodes, new PromocodeTransformer);
    }

    public function destroy($id)
    {
        $code = $this->promocode->findOrFail($id);

        $code->delete();

        return $this->response->noContent();
    }

    public function restore($id)
    {
        $code = $this->promocode->withTrashed()->findOrFail($id);

        $code->restore();

        return $this->response->item($code, new PromocodeTransformer);
    }
}
