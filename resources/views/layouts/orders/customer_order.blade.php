@extends('layouts.pages.page')

@section('title') Orders @stop

@section('content')
    <h1>Order Summary</h1>
    <div class="well">
        <table class="table">
            <thead>
            <tr>
                <th>#</th>
                <th>Customer name</th>
                <th>Customer Last Name</th>
                <th>Customer email</th>
                <th>Customer document number</th>
                <th>Product</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @if (isset($order))
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->customer_last_name }}</td>
                    <td>{{ $order->customer_email }}</td>
                    <td>{{ $order->customer_document_number}}</td>
                    <td>{{ $order->getProductName() }}</td>
                    <td>{{$order->amount}} COP</td>
                    <td><a href="#" class="btn btn-primary"><i class="icon-pencil"></i>{{$order->status}}</a></td>
                    <td>
                        @if(@Auth::user()->orderHasProducts())
                        <a href="{{route('pay_order')}}" class="btn btn-primary"><i class="icon-pencil"></i>Pay</a>
                        @endif
                    </td>
                </tr>
            @else
                <p>No order</p>
            @endif
            </tbody>
        </table>
    </div>
@stop
