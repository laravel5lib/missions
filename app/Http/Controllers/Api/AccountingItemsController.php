<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Models\v1\AccountingItem;
use App\Http\Controllers\Controller;

class AccountingItemsController extends Controller
{
    protected $item;

    function __construct(AccountingItem $item)
    {
        $this->item = $item;
    }

    public function index()
    {
        $items = $this->item->all();

        return ['data' => $items];
    }

    public function show($id)
    {
        $item = $this->item->findOrFail($id);

        return ['data' => $item];
    }
}
