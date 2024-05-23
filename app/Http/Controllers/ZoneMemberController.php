<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ZoneMember;

class ZoneMemberController extends Controller
{
    public $zoneMember;

    public function __construct(
        ZoneMember $zoneMember
    ) {
        $this->zoneMember = $zoneMember;
    }
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = flash_message();

        $this->zoneMember->zone_id = $request->input('zone_id');
        $this->zoneMember->member_id = $request->input('member_id');
        $this->zoneMember->created_at = date('Y-m-d H:i:s');
        $this->zoneMember->updated_at = date('Y-m-d H:i:s');

        if ($this->zoneMember->save()) {
            
            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * 
     * 
     */
    public function delete($id)
    {
        $response = flash_message();

        $zoneMember = $this->zoneMember->find($id);

        if ($zoneMember->delete()) {
            
            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }
}
