<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Promotion;

class PromotionController extends Controller
{
    public $product;
    public $promotion;

    public function __construct(
        Promotion $promotion,
        Product $product
    ) {
        $this->product = $product;
        $this->promotion = $promotion;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = [];

        $response['promotions'] = $this->promotion
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.promotion.index', $response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $response = [];
        $response['products'] = $this->product->getDataToSelect();

        return view('admin.promotion.create', $response);
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

        $this->promotion->product_id = $request->input('product_id');
        $this->promotion->image = $request->input('image');
        $this->promotion->text = $request->input('text');
        $this->promotion->small_text = $request->input('small_text');
        $this->promotion->url = $request->input('url');
        $this->promotion->sort = 0;
        $this->promotion->public = $request->input('public') ?? 0;
        $this->promotion->type = $request->input('type');

        if ($this->promotion->save()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function show(Promotion $promotion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $response = [];

        $response['promotion'] = $this->promotion->find($id);
        $response['products'] = $this->product->getDataToSelect();

        return view('admin.promotion.update', $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $response = flash_message();

        $promotion = $this->promotion->find($id);
        $promotion->product_id = $request->input('product_id');
        $promotion->image = $request->input('image');
        $promotion->text = $request->input('text');
        $promotion->small_text = $request->input('small_text');
        $promotion->url = $request->input('url');
        $promotion->public = $request->input('public') ?? 0;
        $promotion->type = $request->input('type');

        if ($promotion->save()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Promotion  $promotion
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = flash_message();

        $promotion = $this->promotion->find($id);
        if ($promotion->delete()) {

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

        $promotion = $this->promotion->find($id);
        if ($promotion->delete()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }
}
