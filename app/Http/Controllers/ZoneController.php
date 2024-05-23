<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Zone;
use App\ZoneMember;
use App\Member;

class ZoneController extends Controller
{
    public $zone;
    public $zoneMember;
    public $member;

    public function __construct(
        Zone $zone,
        ZoneMember $zoneMember,
        Member $member
    ) {
        $this->zone = $zone;
        $this->zoneMember = $zoneMember;
        $this->member = $member;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = [];

        $response['zones'] = $this->zone
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.zone.index', $response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.zone.create');
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

        $request->validate([
            'name' => 'required'
        ]);

        $this->zone->name = $request->input('name');
        $this->zone->description = $request->input('description') ?? 0;

        if ($this->zone->save()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function show($zoneId)
    {
        $zone = $this->zone->find($zoneId);
        
        $zones = [];
        foreach ($zone->zones as $key => $value) {
            $zones[] = [
                'id' => $value->id,
                'name' => $value->name
            ];
        }

        return response()->json($zones);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $response = [];

        $response['zone'] = $this->zone->find($id);

        return view('admin.zone.update', $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $response = flash_message();

        $zone = $this->zone->find($id);

        $zone->name = $request->input('name');
        $zone->description = $request->input('description');

        if ($zone->save()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = flash_message();

        $zone = $this->zone->find($id);
        if ($zone->delete()) {

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

        $zone = $this->zone->find($id);
        if ($zone->delete()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * 
     * 
     */
    public function addMember(Request $request)
    {
        $this->zoneMember->where('member_id', $request->input('member_id'))->delete();
        $this->zoneMember->zone_id = $request->input('zone_id');
        $this->zoneMember->member_id = $request->input('member_id');
        $this->zoneMember->save();

        $member = $this->member->find($this->zoneMember->member_id);
        if ($member) {

            $member->zone_id = $this->zoneMember->zone_id;
            $member->save();
        }

        $response['zoneMember'] = $this->zoneMember->find($this->zoneMember->id);

        return view('admin.zone.member_ele', $response);
    }

    /**
     * 
     * 
     */
    public function deleteMember($id)
    {
        $response = flash_message("ทำรายการสำเร็จ", true);

        $zoneMember = $this->zoneMember->find($id);
        $zoneMember->delete();

        $member = $this->member->find($zoneMember->member_id);
        if ($member) {

            $member->zone_id = 0;
            $member->save();
        }

        return response()->json($response);
    }
}
