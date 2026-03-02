<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Item;
use App\Models\ChatRoom;
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

        // 取引中（チャットルームがある商品）
        $tradingChatRooms = ChatRoom::with(['item'])
            ->where(function ($q) use ($user) {
                $q->where('buyer_id', $user->id)
                  ->orWhere('seller_id', $user->id);
            })
        // 未読数（相手が送った & read_at が null）
        ->withCount(['messages as unread_count' => function ($q) use ($user) {
            $q->where('user_id', '!=', $user->id)   // 相手が送った
              ->whereNull('read_at');              // 未読だけ
        }])
            ->latest()
            ->get();

        // タブに出す合計未読数
        $tradingUnreadTotal = $tradingChatRooms->sum('unread_count');

        // 受け取った評価（ratee_id = 自分）
        $ratingCount = Rating::where('ratee_id', $user->id)->count();
        $ratingAvg = Rating::where('ratee_id', $user->id)->avg('rating');
        $ratingAvgRounded = $ratingAvg ? (int) round($ratingAvg) : 0;

        return view('mypage.index', [
            'user' => $user,
            'listedItems' => $listedItems,       // 自分が出品した商品（user_id）
            'purchasedItems' => $purchasedItems, // 自分が購入した商品（buyer_id）
            'tradingChatRooms' => $tradingChatRooms,
            'tradingUnreadTotal' => $tradingUnreadTotal,
            'ratingCount' => $ratingCount,
            'ratingAvgRounded' => $ratingAvgRounded,
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

public function index()
{
    $user = Auth::user();

    // 受け取った評価（ratee_id = 自分）
    $ratingCount = Rating::where('ratee_id', $user->id)->count();

    // 平均（小数が出たら四捨五入 → ★用に整数にする）
    $ratingAvg = Rating::where('ratee_id', $user->id)->avg('rating'); // nullの可能性あり
    $ratingAvgRounded = $ratingAvg ? (int) round($ratingAvg) : null;

    return view('mypage.index', compact('user', 'ratingCount', 'ratingAvgRounded'));
}

}
