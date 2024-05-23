<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\OrderPickup;
use App\Member;
use App\Branch;

use App\Http\Controllers\Api\PickupController as ApiPickup;

use DB;
use Excel;
use Carbon\Carbon;

class OrderPickupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(
        Request $request, 
        OrderPickup $modelOrderPickup, 
        Member $modelMember,
        ApiPickup $apiPickup
    ) {
        $response = [];
        $pickups = $modelOrderPickup
            ->select('order_pickups.*')
            ->leftJoin('members', 'order_pickups.member_id', 'members.id')
            ->where(function($query) use ($request) {

                if ($request->has('branch_id') && $request->input('branch_id') != '') {
                    $query->where('order_pickups.branch_id', $request->input('branch_id'));
                }

                if ($request->has('sale_id') && $request->input('sale_id') != '') {
                    $query->where('order_pickups.sale_id', $request->input('sale_id'));
                }

                if ($request->has('checked') && $request->input('checked') != '') {
                    $query->where('order_pickups.checked', $request->input('checked'));
                }

                if ($request->has('key') && $request->input('key') != '') {
                    $query->orWhere('members.name', 'like', '%'. $request->input('key') .'%');
                    $query->orWhere('members.email', 'like', '%'. $request->input('key') .'%');
                    $query->orWhere('members.address', 'like', '%'. $request->input('key') .'%');
                }

                if ($request->has('start_date') && $request->input('start_date') != '') {
                    $query->where('order_pickups.created_at', '>=', date('Y-m-d', strtotime($request->input('start_date'))) . ' 00:00:00');
                }
        
                if ($request->has('end_date') && $request->input('end_date') != '') {
                    $query->where('order_pickups.created_at', '<=', date('Y-m-d', strtotime($request->input('end_date'))) . ' 23:59:59');
                }

            })
            ->orderBy('order_pickups.id', 'desc');
        
        if ($request->has('type') && $request->input('type') == 'export') {

            $response['pickups'] = $pickups->get();

            Excel::create('pickups-export-'. time(), function($excel) use ($response) {

                $excel->sheet('sheet1', function($sheet) use ($response) {
            
                    $sheet->loadView('admin.order_pickup.excel', $response);
            
                });
            
            })->download('xls');
        }

        $response['pickups'] = $pickups->paginate(30);
        $response['members'] = array_pluck($modelMember->where('type', 'staff')
            ->orderBy('name', 'asc')
            ->get(), 'name', 'id');
        
        $branches = $apiPickup->remoteMysql
            ->table('branch')
            ->where([
                ['active', 0]
            ])
            ->get();
            
        $response['branches'] = array_pluck($branches, 'branch_name', 'branchid');

        return view('admin.order_pickup.index', $response);
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
    public function show($id, OrderPickup $modelOrderPickup)
    {
        $response = [];

        $order = $modelOrderPickup->find($id);
        $response['order'] = $order;

        return view('admin.order_pickup.view', $response);
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

        $order = OrderPickup::find($id);
        $response['order'] = $order;

        return view('admin.order_pickup.update', $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrderPickup $modelOrderPickup, $id)
    {
        $response = flash_message();

        $order = $modelOrderPickup->find($id);
        $order->checked = ($order->checked) ? 0 : 1;

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
    public function delete(OrderPickup $modelOrderPickup, $id)
    {
        $response = flash_message();

        $pickup = $modelOrderPickup->find($id);
        if ($pickup->delete()) {
            
            $response = flash_message('success', true);
        }

        return redirect()->back()->with('message', $response);
    }
}
