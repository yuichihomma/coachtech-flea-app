<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as CheckoutSession;
use App\Models\Item;
use App\Http\Requests\AddressRequest;

class PurchaseController extends Controller
{
    /**
     * 購入画面表示
     */
    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = Auth::user();

        return view('purchase.show', compact('item', 'user'));
    }

    /**
     * 購入処理（Stripe Checkoutへ遷移）
     */
    public function store(Request $request, $item_id)
    {
        $request->validate([
            'payment_method' => 'required|in:convenience,card',
        ],
        [
        'payment_method.required' => '支払い方法を選択してください。',
        'payment_method.in' => '正しい支払い方法を選択してください。',
    ]
        );

        $item = Item::findOrFail($item_id);

        // 二重購入防止
        if ($item->is_sold) {
            return redirect('/')
                ->with('error', 'すでに購入されています');
        }

        // Stripe APIキー設定
        Stripe::setApiKey(config('services.stripe.secret'));

        // 支払い方法分岐
        $paymentMethods = $request->payment_method === 'card'
            ? ['card']
            : ['konbini'];

        // Stripe Checkout セッション作成
        $session = CheckoutSession::create([
            'payment_method_types' => $paymentMethods,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',

            // 決済成功・キャンセル時の戻り先
            'success_url' => route('purchase.success', $item->id),
            'cancel_url' => route('purchase.show', $item->id),
        ]);

        // Stripe決済画面へリダイレクト
        return redirect($session->url);
    }

    public function success($item_id)
    {
        $item = Item::findOrFail($item_id);

        $item->is_sold = true;
        $item->buyer_id = Auth::id();
        $item->save();

        return redirect()->route('items.index')
            ->with('success', '購入が完了しました');
    }


    /**
 * 配送先変更画面
 */
public function address($item_id)
{
    $user = Auth::user();

    return view('purchase.address', compact('user', 'item_id'));
}
    /**
     * 配送先変更処理
     */

    public function updateAddress(AddressRequest $request, $item_id)
{
    $user = Auth::user();

    $user->update([
        'postcode' => $request->postcode,
        'address'     => $request->address,
        'building'    => $request->building,
    ]);

    return redirect()->route('purchase.show', $item_id);
}

}
