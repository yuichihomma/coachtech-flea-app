<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function store(Item $item)
    {
        Auth::user()->likedItems()->toggle($item->id);

        return back();
    }
}

