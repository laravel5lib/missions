<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\v1\Representative;
use App\Http\Controllers\Controller;
use App\Http\Transformers\v1\RepresentativeTransformer;

class RepresentativeAvatarController extends Controller
{
    public function update($id, Request $request)
    {
        $this->validate($request, [
            'path' => 'required|url',
        ]);

        $rep = Representative::findOrFail($id);
        $rep->avatar_url = $request->get('path');
        $rep->save();

        return $this->response->item($rep, new RepresentativeTransformer);
    }
}
