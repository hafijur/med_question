<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {

        $variants = Variant::with('variants')->get();
        $products = $this->productQuery($request)->paginate(2);
        return view('products.index', compact("products", 'variants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        $product_variant_prices = ProductVariantPrice::all();
        return view('products.create', compact('variants', 'product_variant_prices'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        DB::transaction(function () use($request) {
            // insert Product
            $product = Product::create($request->all());

            // Now inserting product variants
            foreach ($request->product_variant as $variant) {

                foreach ($variant['tags'] as $tag) {
                    $productVariant = new ProductVariant();
                    $productVariant->variant = $tag;
                    $productVariant->variant_id = $variant['option'];
                    $productVariant->product_id = $product['id'];
                    $productVariant->save();
                }
            }

            // Now inserting Product Variant Price
            foreach ($request->product_variant_prices as $pvp) {
                $variants = explode('/', $pvp['title']);                
                $variants = (count($variants) > 2 ? array_slice($variants,0,3) : $variants);
                $product_variants = ProductVariant::whereIn('variant', $variants  )->get();
                $product_variant_price = new ProductVariantPrice();
                $product_variant_price->product_variant_one = count($variants) > 0 ?  $product_variants[0]['id'] : null;
                $product_variant_price->product_variant_two = count($variants) > 1 ?  $product_variants[1]['id'] : null;
                $product_variant_price->product_variant_three = count($variants) > 2 ?  $product_variants[2]['id'] : null;
                $product_variant_price->price = $pvp['price'];
                $product_variant_price->stock = $pvp['stock'];
                $product_variant_price->product_id = $product->id;
                $product_variant_price->save();
            }
            return response()->json($request->product_variant_prices);
        });


        return response()->json($request->all());
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $variants = Variant::all();
        return view('products.edit', compact('variants'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }

    private function productQuery($request)
    {

        extract($request->all());
        $products = Product::with('producs_variant_price');
        if (isset($title)) {
            $products->where('title', 'LIKE', "%{$title}%");
        }
        if (isset($date)) {
            $products->whereDate('created_at', $date);
        }
        if (isset($price_from) && isset($price_to)) {
            $products->whereHas('producs_variant_price', function ($q) use ($price_from, $price_to) {
                $q->whereBetween('price', [$price_from, $price_to]);
            });
        }

        return $products;
    }
}
