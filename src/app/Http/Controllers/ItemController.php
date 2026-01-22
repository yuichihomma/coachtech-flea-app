<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ExhibitionRequest;

class ItemController extends Controller
{
    /**
     * 商品一覧（おすすめ / マイリスト）
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab');         // 'mylist' or null
        $keyword = $request->query('keyword'); // 検索ワード

        /*
        |--------------------------------------------------------------------------
        | マイリスト
        |--------------------------------------------------------------------------
        */
        if ($tab === 'mylist') {

            // 未ログイン時は空
            if (!Auth::check()) {
                $items = collect();
            } else {
                $query = Auth::user()->likedItems();

                // 検索（部分一致）
                if (!empty($keyword)) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                }

                $items = $query->get();
            }

        /*
        |--------------------------------------------------------------------------
        | おすすめ（商品一覧）
        |--------------------------------------------------------------------------
        */
        } else {
            $query = Item::query();

            // ログイン中は「自分が出品した商品」を除外
            if (Auth::check()) {
                $query->where('user_id', '!=', Auth::id());
            }

            // 検索（部分一致）
            if (!empty($keyword)) {
                $query->where('name', 'like', '%' . $keyword . '%');
            }

            $items = $query->get();
        }

        return view('items.index', compact('items', 'tab'));
    }

    /**
     * 商品詳細
     */
    public function show($item_id)
    {
        $item = Item::with([
                'comments.user', // コメント + 投稿者
                'categories',    // カテゴリ
            ])
            ->withCount([
                'comments', // コメント数
                'likes',    // いいね数
            ])
            ->findOrFail($item_id);

        // いいね済みかどうか
        $isLiked = false;
        if (Auth::check()) {
            $isLiked = Auth::user()
                ->likedItems()
                ->where('item_id', $item->id)
                ->exists();
        }

        return view('items.show', compact('item', 'isLiked'));
    }

    /**
     * 出品画面
     */
    public function create()
    {
        return view('items.sell');
    }

    /**
     * 出品処理
     */
    public function store(ExhibitionRequest $request)
    {

        $path = $request->file('image')->store('items', 'public');

        $item = Item::create([
            'user_id'     => Auth::id(),
            'name'        => $request->name,
            'brand'       => $request->brand,
            'description' => $request->description,
            'price'       => $request->price,
            'condition'   => $request->condition,
            'img_url'     => $path,
        ]);

        if ($request->filled('category_ids')) {
            $item->categories()->sync($request->category_ids);
        }

        return redirect()->route('items.show', $item->id);
}

}
