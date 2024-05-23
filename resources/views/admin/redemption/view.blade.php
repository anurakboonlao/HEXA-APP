@extends('layout.admin')
@section('content-header')
    <h1> Redemtion Voucher </h1>
@endsection
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                                <div class="coupon">
                                    <div class="container">
                                        <h3>{{ $redemption->voucher->name }}</h3>
                                    </div>
                                    <img src="{{ store_image($redemption->voucher->image) }}" alt="Avatar" style="width:300px;">
                                    <div class="container" style="background-color:white">
                                        <h2><b>บัตรกำนัล {{ $redemption->voucher->name }} มูลค่า {{ $redemption->amount }} บาท</b></h2> 
                                        <p>{{ $redemption->voucher->description }}</p>
                                    </div>
                                    <div class="container">
                                        <p>Code : <span class="promo">{{ $redemption->code }}</span></p>
                                        <p class="expire">Redeem : {{ $redemption->created_at }}</p>
                                        <hr>
                                        <p class="expire">ผู้แลกรับ : {{ (!$redemption->name) ? $redemption->member->name ?? '' : $redemption->name }}</p>
                                        <p class="expire">เบอร์โทรศัพท์ : {{ $redemption->phone }}</p>
                                        <p class="expire">คลินิก / ที่อยู่ : {{ $redemption->clinic }} {{ $redemption->address }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12 text-right">
                                <a href="{{ request()->input('return') }}" class="btn btn-default"><< กลับ</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection