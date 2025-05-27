<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Livewire\Frontend\Product\Details;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Mail\OrderPlacedMail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\State;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

class OrderController extends Controller
{
    #[Locked]
    public $carts;

    public $check_terms;

    public $password;

    public $create_account;

    public $name;

    public $address;

    public $phone;

    public $notes;

    protected $rules = [
        'name' => 'required',
        'address' => 'required',
        'phone' => 'required',
        'check_terms' => 'required'
    ];
    public function store(Request $request)
    {
        // Log::info($request->all());
        if ($this->create_account) {
            $this->rules['phone'] = 'unique:users';
            $this->rules['password'] = 'required|min:6';
        }

        try {
            DB::beginTransaction();

            $shippingAddress = [
                'full_name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'shipping_type' => $request->delivery,
            ];

            if ($this->create_account) {
                $generateUsername = $this->name;

                $user = User::create([
                    'name' => $this->name,
                    'phone' => $this->phone,
                    'password' => bcrypt($this->password),
                    'user_type' => 'customer',
                    'username' => User::whereUsername($generateUsername)->first() ? strtolower($generateUsername . Str::random(4)) : strtolower($generateUsername),
                ]);

                Auth::login($user);
            }

            $price = 0;
            $order = new Order();
            $order->user_id = auth()->id();
            $order->guest_id = guestID();
            $order->note = $request->note;
            $order->code =  date('YHis') . rand(10, 99);
            $order->shipping = json_encode($shippingAddress);
            $order->billing = json_encode($shippingAddress);
            $order->payment_type = 'cash_on_delivery';
            $order->ip_address = request()->ip();
            $order->user_agent =  request()->userAgent();
            $order->status = 'pending';
            $order->total = $request->subTotal;
            $order->save();

            $dilevery = $request->delivery;

            if ($order) {
                $totalOrder = $request->orders;

                $details = [];
                foreach ($totalOrder as $item) {
                    $product = Product::find($item['id']);
                    $product->increment('num_of_sale');
                    $details[] = [
                        'order_id' => $order->id,
                        'seller_id' => $product?->user_id || null,
                        'product_id' => $item['id'],
                        'created_via' => 'laravel',
                        'price' => $item['price'],
                        'variation' => $item['variation'] ??  null,
                        'quantity' => $item['quantity'],
                        'shipping_type' => $item['delivery'],
                        'status' => 'pending',
                    ];
                }

                OrderDetails::insert($details);
            }
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Order placed successfully!',
                'order_id' => $order->id,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Sorry, something went wrong!',
            ]);
        }
    }
    public function orderSummary($id)
    {
        $data = Order::with(['detail.product'])->where('id', $id)->first();

        foreach ($data->detail as $orderDetail) {
            if ($orderDetail->product->thumbnail_img) {
                $upload = DB::table('uploads')->where('id', $orderDetail->product->thumbnail_img)->first();
                if ($upload) {
                    $orderDetail->product->thumbnail_url = url($upload->file_name);
                }
            }
        }
        Log::info($data->detail);
        
        return response()->json([
            'orderDetails' => $data,
        ]);
    }
}
