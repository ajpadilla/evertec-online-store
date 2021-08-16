@extends('layouts.pages.page')

@section('title') Pay Order@stop

@section('content')

    <h1>Order Detail</h1>

    <div class="row">
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div id="login-alert" class="alert alert-danger col-sm-12">{{ $error }}</div>
            @endforeach
        @endif
    </div>

    <section class="invoice">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="fa fa-globe"></i> Trust point Co.
                    <small class="pull-right">Date: {{ $order->created_at }}</small>
                </h2>
            </div><!-- /.col -->
        </div>
        <!-- info row -->
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
                From
                <address>
                    <strong>
                    </strong>
                </address>
            </div><!-- /.col -->
            <div class="col-sm-4 invoice-col">
                To
                <address>
                    <strong>{{$order->customer_name}} {{ $order->customer_last_name  }}</strong>
                    <br>
                    Phone:
                    {{$order->customer_mobile}}
                    <br>
                    Email:{{$order->customer_email}}
                </address>
            </div><!-- /.col -->
            <div class="col-sm-4 invoice-col">
                <b>Invoice #{{ $order->id }}</b><br>
                <br>
                <b>Order ID:</b> {{ $order->id }}<br>
                <b>Payment Due:</b> {{$order->created_at->addDays(1)}}<br>
                <b>Order Status:</b> <a href="#" class="btn btn-primary"><i class="icon-pencil"></i>{{$order->status}}</a><br>
            </div><!-- /.col -->
        </div><!-- /.row -->

        <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Qty</th>
                        <th>Product</th>
                        <th>Price</th>
                    </tr>
                    </thead>
                    <tbody>


                    <tr>
                        <td>{{ $order->getTotalProducts() }}</td>
                        <td>{{ $order->getProductName() }}</td>
                        <td>{{ $order->getProductPrice() }}</td>
                    </tr>
                    </tbody>
                </table>
            </div><!-- /.col -->
        </div><!-- /.row -->

        <div class="row">
            <!-- accepted payments column -->
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th>Total:</th>
                            <td>{{ $order->amount }} COP</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->

        @if($order->hasProducts())
            <!-- this row will not appear when printing -->
                <div class="row no-print">
                    <div class="col-xs-12">
                        @if($order->getFirstPaymentAttemptState()== 'INITIAL')
                            <a href="{{ route('process_pay') }}" class="btn btn-success"><i class="fa fa-money"></i> Pay</a>
                        @elseif($order->getFirstPaymentAttemptState() == 'PENDING')
                            <a href="{{ url($order->getFirstPaymentAttemptUrlProcess()) }}" class="btn btn-success"><i class="fa fa-money"></i> Process Pay</a>
                        @elseif($order->getFirstPaymentAttemptState() == 'REJECTED' || $order->getFirstPaymentAttemptState() == 'FAILED')
                            <a href="{{ route('process_pay') }}" class="btn btn-success"><i class="fa fa-money"></i>Retry Pay</a>
                        @endif
                    </div>
                </div>
        @endif
    </section>
@stop
