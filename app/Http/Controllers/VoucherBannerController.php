<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VoucherBanner;

class VoucherBannerController extends Controller
{
    public $voucherBanner;

    public function __construct(
        VoucherBanner $voucherBanner
    ) {
        $this->voucherBanner = $voucherBanner;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = [];

        $response['voucher_banners'] = $this->voucherBanner
            ->orderBy('sort', 'asc')
            ->get();

        return view('admin.voucher_banner.index', $response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.voucher_banner.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = flash_message();

        $this->voucherBanner->name = $request->input('name');
        $this->voucherBanner->url = $request->input('url');
        $this->voucherBanner->sort = $request->input('sort');
        $this->voucherBanner->image = $request->input('image');
        $this->voucherBanner->public = $request->input('public') ?? 0;

        if ($this->voucherBanner->save()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VoucherBanner  $voucherBanner
     * @return \Illuminate\Http\Response
     */
    public function show(Voucher $voucher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\VoucherBanner  $voucherBanner
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $response = [];

        $response['voucher_banner'] = $this->voucherBanner->find($id);

        return view('admin.voucher_banner.update', $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VoucherBanner  $voucherBanner
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $response = flash_message();

        $voucherBanner = $this->voucherBanner->find($id);
        $voucherBanner->name = $request->input('name');
        $voucherBanner->url = $request->input('url');
        $voucherBanner->sort = $request->input('sort');
        $voucherBanner->image = $request->input('image');
        $voucherBanner->public = $request->input('public') ?? 0;


        if ($voucherBanner->save()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * 
     * 
     * 
     */
    public function delete($id)
    {
        $response = flash_message();

        $voucherBanner = $this->voucherBanner->find($id);
        if ($voucherBanner->delete()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }
}
