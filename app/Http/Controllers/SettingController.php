<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Setting;

class SettingController extends Controller
{
    public $setting;

    public function __construct(
        Setting $setting
    ) {
        $this->setting = $setting;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = [];

        $response['settings'] = $this->setting
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.setting.index', $response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.setting.create');
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

        $this->setting->name = $request->input('name');
        $this->setting->description = $request->input('description');
        $this->setting->image = $request->input('image');
        $this->setting->public = $request->input('public') ?? 0;
        $this->setting->exchange_rate = $request->input('exchange_rate');

        if ($this->setting->save()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $settingId)
    {
        $response = [];

        $response['setting'] = $this->setting->find($settingId);

        return view('admin.setting.' . $request->page, $response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $response = [];

        $response['setting'] = $this->setting->find($id);

        return view('admin.setting.update', $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $response = flash_message();

        $setting = $this->setting->find($id);

        $fileName = $setting->hexa_price_list;
        $fileNameRetainer = $setting->hexa_retainer_gallery;

        if ($request->hasFile('hexa_price_list')) {
            $fileName = time() . '.' . $request->file('hexa_price_list')->extension();
            if (!$request->file('hexa_price_list')->storeAs('settings', $fileName)) {

                $message['message'] = 'อัพโหลดไฟล์ไม่ได้.';
                return redirect()->back()->with('message', $message);
            }
        }

        if ($request->hasFile('hexa_retainer_gallery')) {
            $fileNameRetainer = time() . '.' . $request->file('hexa_retainer_gallery')->extension();
            if (!$request->file('hexa_retainer_gallery')->storeAs('settings', $fileNameRetainer)) {

                $message['message'] = 'อัพโหลดไฟล์ไม่ได้.';
                return redirect()->back()->with('message', $message);
            }
        }

        $setting->hexa_price_list = $fileName;
        $setting->hexa_retainer_gallery = $fileNameRetainer;
        $setting->hexa_line = $request->input('hexa_line') ?? $setting->hexa_line;
        $setting->hexa_email = $request->input('hexa_email') ?? $setting->hexa_email;
        $setting->hexa_facebook = $request->input('hexa_facebook') ?? $setting->hexa_facebook;
        $setting->shipping_cover_page = $request->input('shipping_cover_page') ?? $setting->shipping_cover_page;
        $setting->qrcode = $request->input('qrcode') ?? $setting->qrcode;
        $setting->terms = $request->input('terms') ?? $setting->terms;
        $setting->dental_supply_link = $request->input('dental_supply_link') ?? $setting->dental_supply_link;

        if ($setting->save()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Setting  $setting
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = flash_message();

        $setting = $this->setting->find($id);
        if ($setting->delete()) {

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

        $setting = $this->setting->find($id);
        if ($setting->delete()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }
}
