<?php

namespace App\Http\Controllers\Api;

use App\Models\v1\Representative;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\RepresentativeRequest;
use App\Http\Transformers\v1\RepresentativeTransformer;

class RepresentativeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $representatives = Representative::filter(request()->all())
            ->orderBy('name', 'asc')
            ->paginate(request()->get('per_page', 10));

        return $this->response->paginator($representatives, new RepresentativeTransformer);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RepresentativeRequest $request)
    {
        $rep = Representative::create([
            'name' => $request->get('name'),
            'phone' => $request->get('phone'),
            'ext' => $request->get('ext'),
            'email' => $request->get('email')
        ]);

        return $this->response->item($rep, new RepresentativeTransformer);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Represenative  $Represenative
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $rep = Representative::findOrFail($id);

        return $this->response->item($rep, new RepresentativeTransformer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Represenative  $Represenative
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rep = Representative::findOrFail($id);

        $rep->update([
            'name' => $request->get('name', $rep->name),
            'email' => $request->get('email', $rep->email),
            'phone' => $request->get('phone', $rep->phone),
            'ext' => $request->get('ext', $rep->ext)
        ]);

        return $this->response->item($rep, new RepresentativeTransformer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Represenative  $Represenative
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rep = Representative::findOrFail($id);

        $rep->delete();

        return $this->response->noContent();
    }
}
