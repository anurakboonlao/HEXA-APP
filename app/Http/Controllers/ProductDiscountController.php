<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductDiscount;

class ProductDiscountController extends Controller
{
    public $productDiscount;

    public function __construct(
        ProductDiscount $productDiscount
    ) {
        $this->productDiscount = $productDiscount;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = [];

        $response['productDiscounts'] = $this->productDiscount
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.productDiscount.index', $response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.productDiscount.create');
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

        $this->productDiscount->product_id = $request->input('product_id');
        $this->productDiscount->qty = $request->input('qty');
        $this->productDiscount->price = $request->input('price');

        if ($this->productDiscount->save()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductDiscount  $productDiscount
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $productDiscountId)
    {
        $response = [];

        $response['productDiscount'] = $this->productDiscount->find($productDiscountId);

        return view('admin.productDiscount.' . $request->page, $response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductDiscount  $productDiscount
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $response = [];

        $response['productDiscount'] = $this->productDiscount->find($id);

        return view('admin.productDiscount.update', $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductDiscount  $productDiscount
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $response = flash_message();

        $productDiscount = $this->productDiscount->find($id);

        $fileName = $productDiscount->hexa_price_list;

        if ($request->hasFile('hexa_price_list')) {
            $fileName = time() . '.' . $request->file('hexa_price_list')->extension();
            if (!$request->file('hexa_price_list')->storeAs('productDiscounts', $fileName)) {

                $message['message'] = 'อัพโหลดไฟล์ไม่ได้.';
                return redirect()->back()->with('message', $message);
            }
        }

        $productDiscount->hexa_price_list = $fileName;
        $productDiscount->hexa_line = $request->input('hexa_line') ?? $productDiscount->hexa_line;
        $productDiscount->hexa_email = $request->input('hexa_email') ?? $productDiscount->hexa_email;
        $productDiscount->hexa_facebook = $request->input('hexa_facebook') ?? $productDiscount->hexa_facebook;
        $productDiscount->shipping_cover_page = $request->input('shipping_cover_page') ?? $productDiscount->shipping_cover_page;
        $productDiscount->terms = $request->input('terms') ?? $productDiscount->terms;

        if ($productDiscount->save()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductDiscount  $productDiscount
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = flash_message();

        $productDiscount = $this->productDiscount->find($id);
        if ($productDiscount->delete()) {

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

        $productDiscount = $this->productDiscount->find($id);
        if ($productDiscount->delete()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }
}
