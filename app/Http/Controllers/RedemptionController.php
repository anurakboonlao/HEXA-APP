<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Redemption;
use App\Member;
use App\Voucher;

use App\Http\Controllers\Api\PickupController as ApiPickup;

use DB;

use Carbon\Carbon;

use Excel;

class RedemptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(
        Request $request, 
        Redemption $modelRedemption, 
        Member $modelMember,
        Voucher $modelVoucher
    ) {
        $response = [];
        $redemptions = $modelRedemption
            ->where(function($query) use ($request) {

                if ($request->has('member_id') && $request->input('member_id') != '') {
                    $query->where('redemptions.member_id', $request->input('member_id'));
                }

                if ($request->has('voucher_id') && $request->input('voucher_id') != '') {
                    $query->where('redemptions.voucher_id', $request->input('voucher_id'));
                }

                if ($request->has('approved') && $request->input('approved') != '') {
                    $query->where('redemptions.approved', $request->input('approved'));
                }

                if ($request->has('start_date') && $request->input('start_date') != '') {
                    $query->where('redemptions.created_at', '>=', date('Y-m-d', strtotime($request->input('start_date'))) . ' 00:00:00');
                }
        
                if ($request->has('end_date') && $request->input('end_date') != '') {
                    $query->where('redemptions.created_at', '<=', date('Y-m-d', strtotime($request->input('end_date'))) . ' 23:59:59');
                }

            })
            ->orderBy('redemptions.id', 'desc')
            ->paginate(30);
        
        $response['redemptions'] = $redemptions;
        $response['members'] = array_pluck($modelMember->where('type', '!=', 'staff')
            ->orderBy('name', 'asc')
            ->get(), 'name', 'id');

        $response['vouchers'] = array_pluck($modelVoucher
            ->orderBy('name', 'asc')
            ->get(), 'name', 'id');

        return view('admin.redemption.index', $response);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function export(
        Request $request, 
        Redemption $modelRedemption, 
        Member $modelMember,
        Voucher $modelVoucher
    ) {
        $response = [];
        $redemptions = $modelRedemption
            ->where(function($query) use ($request) {

                if ($request->has('member_id') && $request->input('member_id') != '') {
                    $query->where('redemptions.member_id', $request->input('member_id'));
                }

                if ($request->has('voucher_id') && $request->input('voucher_id') != '') {
                    $query->where('redemptions.voucher_id', $request->input('voucher_id'));
                }

                if ($request->has('approved') && $request->input('approved') != '') {
                    $query->where('redemptions.approved', $request->input('approved'));
                }

                if ($request->has('start_date') && $request->input('start_date') != '') {
                    $query->where('redemptions.created_at', '>=', date('Y-m-d', strtotime($request->input('start_date'))) . ' 00:00:00');
                }
        
                if ($request->has('end_date') && $request->input('end_date') != '') {
                    $query->where('redemptions.created_at', '<=', date('Y-m-d', strtotime($request->input('end_date'))) . ' 23:59:59');
                }

            })
            ->orderBy('redemptions.id', 'desc')
            ->get();
        
        $response['redemptions'] = $redemptions;

        Excel::create('report hexa reward', function($excel) use ($response) {

            $excel->sheet('sheet1', function($sheet) use ($response) {
        
                $sheet->loadView('admin.redemption.excel', $response);
        
            });
        
        })->download('xls');
        //return view('admin.redemption.excel', $response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Redemption $modelRedemption)
    {
        $response = [];

        $redemption = $modelRedemption->find($id);
        $response['redemption'] = $redemption;

        return view('admin.redemption.view', $response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $response = [];

        $order = Redemption::find($id);
        $response['order'] = $order;

        return view('admin.redemption.update', $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Redemption $modelRedemption, $id)
    {
        $response = flash_message();

        $order = $modelRedemption->find($id);
        $order->status = ($order->status) ? 0 : 1;

        if ($order->save()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * 
     * 
     */
    public function delete(Redemption $modelRedemption, $id)
    {
        $response = flash_message();

        $pickup = $modelRedemption->find($id);
        if ($pickup->delete()) {
            
            $response = flash_message('success', true);
        }

        return redirect()->back()->with('message', $response);
    }
}
