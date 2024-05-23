<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Voucher;

use Excel;

class VoucherController extends Controller
{
    public $voucher;

    public function __construct(
        Voucher $voucher
    ) {
        $this->voucher = $voucher;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = [];

        $response['vouchers'] = $this->voucher
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.voucher.index', $response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.voucher.create');
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

        $this->voucher->name = $request->input('name');
        $this->voucher->description = $request->input('description');
        $this->voucher->image = $request->input('image');
        $this->voucher->public = $request->input('public') ?? 0;
        $this->voucher->exchange_rate = $request->input('exchange_rate');
        $this->voucher->voucher_value = $request->input('voucher_value');
        $this->voucher->voucher_condition = $request->input('voucher_condition');

        if ($this->voucher->save()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function show(Voucher $voucher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $response = [];

        $response['voucher'] = $this->voucher->find($id);

        return view('admin.voucher.update', $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $response = flash_message();

        $voucher = $this->voucher->find($id);
        $voucher->name = $request->input('name');
        $voucher->description = $request->input('description');
        $voucher->image = $request->input('image');
        $voucher->public = $request->input('public') ?? 0;
        $voucher->exchange_rate = $request->input('exchange_rate');
        $voucher->voucher_value = $request->input('voucher_value');
        $voucher->voucher_condition = $request->input('voucher_condition');


        if ($voucher->save()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * 
     * 
     * 
     */
    public function importVouchers(Request $request)
    {
        $response = [];
        $response = flash_message('ไม่สามารถ import ได้ไฟล์ใหญ่เกินไป');

        if ($request->isMethod('post')) {

            try {

                if ($request->has('file')) {

                    if ($request->file('file')->getSize() > 10485760) {

                        return redirect()->back()->with('message', $response);
                    }

                    $path = $request->file('file')->getRealPath();
                    $data = Excel::load($path)->get();
                    if ($data->count()) {
                        
                        $vouchers = [];
                        foreach ($data as $key => $value) {
                            $vouchers[] = [
                                'name' => $value->name,
                                'exchange_rate' => $value->exchange_rate,
                                'description' => $value->description,
                                'created_at' => date('Y-m-d H:i:s'),
                                'public' => 1,
                                'voucher_value' => $value->voucher_value,
                                'voucher_condition' => $value->voucher_condition,
                            ];
                        }

                        $this->voucher->insert($vouchers);
                        $response = flash_message('success['. count($vouchers) .']', true);                        
                    }
                }
                
                return redirect()->back()->with('message', $response);

            } catch (\Exception $e) {
                
                $response = flash_message($e->getMessage());
                return redirect()->back()->with('message', $response);
            }
        }

        return view('admin.voucher.import', $response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Voucher  $voucher
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = flash_message();

        $voucher = $this->voucher->find($id);
        if ($voucher->delete()) {

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

        $voucher = $this->voucher->find($id);
        if ($voucher->delete()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }
}
