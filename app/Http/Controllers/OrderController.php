<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Order;
use App\OrderList;

use App\Payment;
use App\Product;
use App\ProductSize;
use App\ProductStock;
use App\OrderProduct;

use DB;
use Excel;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Order $modelOrder)
    {
        $response = [];
        $orders = $modelOrder
            ->where(function($query) use ($request) {

                if ($request->has('status') && $request->input('status') != '') {
                    $query->where('status', ($request->input('status') == 'new') ? 0 : 1);
                }

                if ($request->has('key') && $request->input('key') != '') {
                    $query->orWhere('email', 'like', '%'. $request->input('key') .'%');
                    $query->orWhere('address', 'like', '%'. $request->input('key') .'%');
                    $query->orWhere('id', 'like', '%'. $request->input('key') .'%');
                }

                if ($request->has('start_date') && $request->input('start_date') != '') {
                    $query->where('created_at', '>=', date('Y-m-d', strtotime($request->input('start_date'))) . ' 00:00:00');
                }
        
                if ($request->has('end_date') && $request->input('end_date') != '') {
                    $query->where('created_at', '<=', date('Y-m-d', strtotime($request->input('end_date'))) . ' 23:59:59');
                }

            })
            ->orderBy('id', 'desc');
        
        if ($request->has('type') && $request->input('type') == 'export') {

            $response['orders'] = $orders->get();

            Excel::create('orders-export-'. time(), function($excel) use ($response) {

                $excel->sheet('sheet1', function($sheet) use ($response) {
            
                    $sheet->loadView('admin.order.excel', $response);
            
                });
            
            })->download('xls');
        }
        
        $response['orders'] = $orders->paginate(30);

        return view('admin.order.index', $response);
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
    public function show($id, Order $modelOrder)
    {
        $response = [];

        $order = $modelOrder->find($id);
        $response['order'] = $order;

        return view('admin.order.view', $response);
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

        $order = Order::find($id);
        $response['order'] = $order;

        return view('admin.order.update', $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $modelOrder, $id)
    {
        $response = flash_message();

        $order = $modelOrder->find($id);
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
    public function delete(Order $modelOrder, OrderProduct $modelOrderProduct, $id)
    {
        $response = flash_message();
        $order = $modelOrder->find($id);
        if ($order->delete()) {

            $modelOrderProduct->where('order_id', $id)->delete();
            $response = flash_message('success', true);
        }

        return redirect()->back()->with('message', $response);
    }
}
