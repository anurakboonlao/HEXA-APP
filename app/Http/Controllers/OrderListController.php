<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\ProductSize;
use App\ProductStock;

use App\Order;
use App\OrderList;

class OrderListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $response = flash_message('ไม่สามารถทำรายการได้ กรุณาลองใหม่');
        
        $orderList = OrderList::find($id);

        $productStock = ProductStock::where([
            ['product_id', $orderList->product_id],
            ['product_size_id', $orderList->product_size_id]
        ])->first();

        $productStock->qty = ($productStock->qty + $orderList->qty);
        $productStock->save();

        $orderList->delete();
        
        $response = flash_message('ลบสินค้าที่ต้องการสำเร็จ', true);
        return redirect()->back()->with('message', $response);
    }
}
