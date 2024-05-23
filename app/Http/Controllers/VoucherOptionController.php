<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VoucherOption;

class VoucherOptionController extends Controller
{
    public $voucherOption;

    public function __construct(
        VoucherOption $voucherOption
    ) {
        $this->voucherOption = $voucherOption;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = [];

        $response['voucher_options'] = $this->voucherOption
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.vouche_option.index', $response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.vouche_option.create');
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

        $this->voucherOption->voucher_id = $request->input('voucher_id');
        $this->voucherOption->redeem_point = $request->input('redeem_point');

        if ($this->voucherOption->save()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VoucherOption  $voucherOption
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $voucherOptionId)
    {
        $response = [];

        $response['voucherOption'] = $this->voucherOption->find($voucherOptionId);

        return view('admin.vouche_option.' . $request->page, $response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\VoucherOption  $voucherOption
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $response = [];

        $response['voucherOption'] = $this->voucherOption->find($id);

        return view('admin.vouche_option.update', $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VoucherOption  $voucherOption
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VoucherOption  $voucherOption
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = flash_message();

        $voucherOption = $this->voucherOption->find($id);
        if ($voucherOption->delete()) {

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

        $voucherOption = $this->voucherOption->find($id);
        if ($voucherOption->delete()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }
}
