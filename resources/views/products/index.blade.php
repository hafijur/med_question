@extends('layouts.app')

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="" method="get" class="card-header">
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    <input type="text" value="{{ request()->input('title') }}" name="title" placeholder="Product Title"
                        class="form-control">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="" class="form-control">
                        @foreach ($variants as $product_variants)
                            <optgroup label="{{ $product_variants->title }}">
                                @foreach ($product_variants->variants->unique('variant') as $variant)
                                    <option value="{{ $variant->id }}"
                                        {{ $variant->id == request('variant') ? 'selected' : '' }}>
                                        {{ $variant->variant }}</option>
                                @endforeach

                            </optgroup>
                        @endforeach

                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" value="{{ request()->input('price_from') }}" name="price_from"
                            aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" value="{{ request()->input('price_to') }}" name="price_to"
                            aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" value="{{ request()->input('date') }}" name="date" placeholder="Date"
                        class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th style="width:300px">Description</th>
                            <th>Variant</th>
                            <th width="150px">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>1</td>
                                <td>{{ $product->title }} <br> Created at : 25-Aug-2020</td>
                                <td>{{ $product->description }}</td>
                                <td>
                                    <dl class="row mb-0" style="height: 80px; overflow: hidden" id="variant">
                                        @foreach ($product->producs_variant_price as $item)
                                            <dt class="col-sm-3 pb-0">

                                                {{ $item->variant_1->variant ?? '' }}/{{ $item->variant_2->variant ?? '' }}/{{ $item->variant_3->variant ?? '' }}
                                                <br />


                                            </dt>
                                            <dd class="col-sm-9">
                                                <dl class="row mb-0">
                                                    <dt class="col-sm-4 pb-0">Price :
                                                        {{ number_format($item->price, 2) }}
                                                    </dt>
                                                    <dd class="col-sm-8 pb-0">InStock :
                                                        {{ number_format($item->stock, 2) }}</dd>
                                                </dl>
                                            </dd>
                                        @endforeach
                                    </dl>
                                    <button onclick="$('#variant').toggleClass('h-auto')" class="btn btn-sm btn-link">Show
                                        more</button>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('product.edit', 1) }}" class="btn btn-success">Edit</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach


                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <p>Showing {{ $products->firstItem() }} to {{ $products->lastItem() }}
                        of total {{ $products->total() }} </p>
                </div>
                <div class="col-md-2">
                    {!! $products->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
