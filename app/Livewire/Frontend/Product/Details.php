<?php

namespace App\Livewire\Frontend\Product;

use App\Models\Cart;
use App\Models\Product;
use Livewire\Component;
use App\Models\Wishlist;
use Livewire\Attributes\On;
use App\Models\ProductStock;
use App\Models\ProductReview;
use App\Livewire\Frontend\Inc\Header;
use App\Models\Category;
use Livewire\Attributes\Title;

class Details extends Component
{
    public $product;

    public $comment;

    public $rating;

    public $selectedVariations = [];

    public $totalPrice = 0;

    public $related_products;

    public $variationImg = null;

    public function mount($slug)
    {
        $this->product = Product::with('categories.category', 'stocks', 'reviews.user')->where('slug', $slug)->publish()->first();
        if(!$this->product != null ){
            $category = Category::whereSlug($slug)->first();
            return $category != null ? redirect(route('shop',['category' => $slug])) : abort(404);
        }
        $this->getTotalPrice();
        $this->related_products = Product::whereHas('categories', function ($query) {
            $query->whereIn('category_id', $this->product->categories->pluck('category_id'));
        })->inRandomOrder()->publish()->limit(20)->get();
    }

    public function addToWishlist($product_id)
    {
        $product = Product::find($product_id);

        $wishlist = Wishlist::MyWishlists()->where('product_id', $product_id)->first();

        if ($product !== null && $wishlist == null) {
            Wishlist::updateOrCreate([
                'product_id' => $product_id,
                'user_id' => auth()->id(),
                'guest_id' => guestID(),
            ]);

            $this->dispatch('wishlist-changed')->to(Header::class);

            $this->dispatch('alert', [
                'type' => 'success',
                'message' => 'Product added to wishlist!',
            ]);

            return;
        }

        $this->dispatch('alert', [
            'type' => 'error',
            'message' => 'Product already exists to wishlist!',
        ]);
    }

    public function addToCart($redirect = false)
    {
        $product = $this->product;

        if ($product->variant_product) {
            if (count(json_decode($product->attributes)) == count($this->selectedVariations)) {

                $price = $this->totalPrice;
                $tax = $product->tax_type == 'percent' ? ($price * $product->tax) / 100 : $product->tax;

                Cart::updateOrCreate([
                    'product_id' => $product->id,
                    'user_id' => auth()->id(),
                    'guest_id' => guestID(),
                ],[
                    'quantity' => 1,
                    'variation' => json_encode($this->selectedVariations),
                    'price' => $price,
                    'shipping_type' => 'home_delivery',
                    'tax' => $tax,
                ]);
            }

            $this->dispatch('alert', [
                'type' => 'warning',
                'message' => 'Please choose all variation!',
            ]);

        } else {

            $tax = 0;
            $price = $product->discountPrice(false);

            if ($product->tax_type == 'percent') {
                $tax += ($price * $product->tax) / 100;
            } elseif ($product->tax_type == 'amount') {
                $tax += $product->tax;
            }

            Cart::updateOrCreate([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'guest_id' => guestID(),
            ],[
                'quantity' => 1,
                'price' => $price,
                'shipping_type' => 'home_delivery',
                'tax' => $tax,
            ]);

        }

        $this->dispatch('cart-changed');

        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Product added to your cart!',
        ]);

        if($redirect){
            return $this->redirect(route('cart'));
        }
    }

    public function changeRating($rating)
    {
        $this->rating = $rating;
    }

    public function addVariation($variation, $value)
    {
        $this->selectedVariations[$variation] = str_replace(' ', '', trim($value));
        $this->dispatch('getTotalPrice')->self();
    }

    #[On('getTotalPrice')]
    public function getTotalPrice()
    {
        $variation = ProductStock::where('product_id', $this->product->id)->where('variant', implode('-', $this->selectedVariations))->first();
        if ($this->product->variant_product) {
            $this->totalPrice = $variation->price ?? 0;
            $this->variationImg = $variation->image ?? null;

            return;
        }

        $this->totalPrice = $this->product->discountPrice(false);
    }

    public function addReview()
    {
        $this->validate([
            'rating' => 'required',
            'comment' => 'required',
        ]);

        $review = new ProductReview();
        $review->product_id = $this->product->id;
        $review->user_id = auth()->id();
        $review->comment = $this->comment;
        $review->rating = $this->rating;
        $review->save();

        $this->reset([
            'rating',
            'comment',
        ]);

        $this->dispatch('alert', [
            'type' => 'success',
            'message' => 'Review has been added!',
        ]);

    }

    public function render()
    {
        return view('livewire.frontend.product.details')->title($this->product->name);
    }
}
