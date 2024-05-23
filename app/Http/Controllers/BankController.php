<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Bank;

class BankController extends Controller
{
    public $product;
    public $bank;

    public function __construct(
        Bank $bank,
        Product $product
    ) {
        $this->product = $product;
        $this->bank = $bank;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = [];

        $response['banks'] = $this->bank
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.bank.index', $response);
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

        return view('admin.bank.create', $response);
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

        $this->bank->name = $request->input('name');
        $this->bank->image = $request->input('image');
        $this->bank->status = $request->input('status') ?? 0;

        if ($this->bank->save()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function show(Bank $bank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $response = [];

        $response['bank'] = $this->bank->find($id);
        $response['products'] = $this->product->getDataToSelect();

        return view('admin.bank.update', $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $response = flash_message();

        $bank = $this->bank->find($id);
        
        $bank->name = $request->input('name');
        $bank->image = $request->input('image');
        $bank->status = $request->input('status') ?? 0;

        if ($bank->save()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = flash_message();

        $bank = $this->bank->find($id);
        if ($bank->delete()) {

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

        $bank = $this->bank->find($id);
        if ($bank->delete()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }
}
