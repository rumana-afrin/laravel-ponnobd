@extends('backend.layouts.app')

@section('title')
Orders
@endsection
@push('js')
<script>
        $('#update_delivery_status').on('change', function(){
            var order_id = {{ $order->id }};
            var status = $('#update_delivery_status').val();
            $.post('{{ route('order.update.status') }}', {
                _token:'{{ @csrf_token() }}',
                order_id:order_id,
                status:status
            }, function(data){
                window.location.reload();
            });
        });

        $('#update_payment_status').on('change', function(){
            var order_id = {{ $order->id }};
            var status = $('#update_payment_status').val();
            $.post('{{ route('order.update.payment.status') }}', {_token:'{{ @csrf_token() }}',order_id:order_id,status:status}, function(data){
                window.location.reload();
            });
        });
</script>
@endpush
@section('content')
<div class="card">
    <div class="vstack gap-4">
        <div class="card border">
            <!-- Card header -->
            <div class="card-header border-bottom">
                <h4 class="card-header-title">Order Summary</h4>
            </div>
            <!-- Card body START -->
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col text-md-left text-center"> </div>

                    <div class="col-md-3 ml-auto">
                        <label for="update_payment_status">Payment Status</label>
                        <select class="form-select" id="update_payment_status" @disabled(!auth()->user()->can('orders_payment_status_change'))>
                            <option value="unpaid" @selected($order->payment_status == 'unpaid')> Unpaid </option>
                            <option value="paid" @selected($order->payment_status == 'paid')> Paid </option>
                        </select>
                    </div>
                    <div class="col-md-3 ml-auto">
                        <label for="update_delivery_status">Delivery Status</label>
                        <select class="form-select" id="update_delivery_status" @disabled(!auth()->user()->can('orders_delivery_status_change'))>
                            <option value="pending" @selected($order->status == 'pending')> Pending </option>
                            <option value="processing" @selected($order->status == 'processing')> Processing </option>
                            <option value="on_hold" @selected($order->status == 'on_hold')> On Hold </option>
                            <option value="completed" @selected($order->status == 'completed')> Completed </option>
                            <option value="cancelled" @selected($order->status == 'cancelled')> Cancelled </option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6 table-responsive">
                        <table class="table-bordered table ">
                            <tbody>
                                <tr>
                                    <th class="fw-600">Order Code::</th>
                                    <td>{{ $order->code }}</td>
                                </tr>
                                <tr>
                                    <th class="fw-600">Customer:</th>
                                    <td>{{ $order->user->name ?? 'Guest User' }}</td>
                                </tr>
                                <tr>
                                    <th class="fw-600">Email:</th>
                                    <td>{{ $order->user->email ?? 'Guest User' }}</td>
                                </tr>
                                <tr>
                                    <th class="fw-600">Shipping address:</th>
                                    <td>
                                        @php
                                            $shipping = is_array($order->shipping) ? $order->shipping : json_decode($order->shipping);
                                            $billing = is_array($order->billing) ? $order->billing : json_decode($order->billing);
                                        @endphp
                                        @foreach ($shipping as $key => $ship)
                                        <b>{{ ucwords(str_replace('_',' ',$key)) }}</b> : {{ $ship }} <br>
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <th class="fw-600">Billing address:</th>
                                    <td>
                                        @foreach ($billing as $key => $ship)
                                        <b>{{ ucwords(str_replace('_',' ',$key)) }}</b> : {{ $ship }} <br>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6 table-responsive">
                        <table class="table-bordered table">
                            <tbody>
                                <tr>
                                    <th class="fw-600">Order date:</th>
                                    <td>{{ $order->created_at->format('d M Y h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th class="fw-600">Order status:</th>
                                    <td>{{ ucwords(str_replace('_',' ',$order->status)) }}</td>
                                </tr>
                                <tr>
                                    <th class="fw-600">Total order amount:</th>
                                    <td>{{ formatPrice($order->total) }}</td>
                                </tr>
                                <tr>
                                    <th class="fw-600">Shipping method:</th>
                                    <td>{{ ucwords(str_replace('_',' ',$order->detail->first()?->shipping_type)) }}</td>
                                </tr>
                                <tr>
                                    <th class="fw-600">Payment method:</th>
                                    <td>{{ ucwords(str_replace('_',' ',$order->payment_type)) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-main text-bold">Order Note:</th>
                                    <td class="text-truncate">{{ $order->note }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Card body END -->
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card rounded-0 shadow-none border mt-2 mb-4">
                    <div class="card-header border-bottom-0">
                        <h5 class="fs-16 fw-700 text-dark mb-0">Order Details</h5>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered">
                            <thead class="text-gray">
                                <tr class="footable-header">
                                    <th>Image</th>
                                    <th>Product</th>
                                    <th>Variation</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            @php
                                $subtotal = 0;
                            @endphp
                            <tbody class="fs-14">
                                @foreach ($order->detail as $detail)
                                @php
                                    $variants = json_decode($detail->variation);
                                    $product = $detail->product;
                                @endphp
                                <tr>
                                    <td>
                                        <img height="50" src="{{ uploadedFile($product?->thumbnail_img) }}" alt="">
                                    </td>
                                    <td>
                                        <a @if($product) href="{{ route('product.details',$product?->slug) }}" target="_blank" @endif>{{ $product?->name ?? 'N/A' }}</a>
                                    </td>

                                    <td>
                                        @if(!is_null($variants))
                                            @foreach ($variants as $variant => $value)
                                                <span><b>{{ $variant }} : </b>{{ $value }}</span> <br>
                                            @endforeach
                                        @else
                                        ---
                                        @endif
                                    </td>
                                    <td> {{ $detail->quantity }} </td>
                                    <td>{{ formatPrice($detail->price*$detail->quantity) }}</td>
                                </tr>
                                @php
                                    $subtotal += $detail->price*$detail->quantity;
                                @endphp
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Order Ammount -->
            <div class="col-md-12">
                <div class="card rounded-0 shadow-none border mt-2">
                    <div class="card-header border-bottom-0">
                        <b class="fs-16 fw-700 text-dark">Order Amount</b>
                    </div>
                    <div class="card-body pb-0">
                        <table class="table-bordered table">
                            <tbody>
                                <tr>
                                    <td class="w-50 fw-600">Subtotal</td>
                                    <td class="text-right"> <span class="strong-600">{{ formatPrice($subtotal) }}</span> </td>
                                </tr>
                                {{-- <tr>
                                    <td class="w-50 fw-600">Shipping</td>
                                    <td class="text-right"> <span class="text-italic">$0.000</span> </td>
                                </tr>
                                <tr>
                                    <td class="w-50 fw-600">Tax</td>
                                    <td class="text-right"> <span class="text-italic">$0.000</span> </td>
                                </tr>
                                <tr>
                                    <td class="w-50 fw-600">Coupon</td>
                                    <td class="text-right"> <span class="text-italic">$0.000</span> </td>
                                </tr> --}}
                                <tr>
                                    <td class="w-50 fw-600">Total</td>
                                    <td class="text-right"> <strong>{{ formatPrice($subtotal) }}</strong> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
