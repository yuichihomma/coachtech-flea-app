<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;


class MypageController extends Controller
{
    public function show(Request $request)
    {
        $user = Auth::user();
        $tab = $request->query('tab', 'sell'); 

        // 出品した商品（自分が出品）
        $listedItems = Item::where('user_id', $user->id)->get();

        // 購入した商品
        $purchasedItems = Item::where('buyer_id', $user->id)->get();


    return view('mypage.index', [
        'user' => $user,
        'listedItems' => $listedItems,       // 自分が出品した商品（user_id）
        'purchasedItems' => $purchasedItems, // 自分が購入した商品（buyer_id）
        'tab' => $tab,                        // 'sell' or 'buy'
]);


    }
    public function edit()
    {
        return view('mypage.edit');
    }
    public function update(ProfileRequest $request)
{

    $user = Auth::user();

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('profiles', 'public');
        $user->image = $path;
    }

    $user->update([
        'name'              => $request->name,
        'postcode'          => $request->postcode,
        'address'           => $request->address,
        'building'          => $request->building,
        'profile_completed' => true,
    ]);

    return redirect()->route('mypage.show');

}

}