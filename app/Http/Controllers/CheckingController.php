<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Checking;
use App\Member;
use App\Branch;

use App\Http\Controllers\Api\PickupController as ApiPickup;

use DB;
use Excel;
use Carbon\Carbon;

class CheckingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(
        Request $request,
        Checking $modelChecking,
        Member $modelMember,
        ApiPickup $apiPickup
    ) {
        $response = [];
        $checkings = $modelChecking
            ->select('checkings.*')
            ->leftJoin('members', 'checkings.member_id', 'members.id')
            ->where(function($query) use ($request) {

                if ($request->has('member_id') && $request->input('member_id') != '') {
                    $query->where('checkings.member_id', $request->input('member_id'));
                }

                if ($request->has('key') && $request->input('key') != '') {
                    $query->orWhere('members.name', 'like', '%'. $request->input('key') .'%');
                    $query->orWhere('members.email', 'like', '%'. $request->input('key') .'%');
                    $query->orWhere('members.address', 'like', '%'. $request->input('key') .'%');
                }

                if ($request->has('start_date') && $request->input('start_date') != '') {
                    $query->where('checkings.created_at', '>=', date('Y-m-d', strtotime($request->input('start_date'))) . ' 00:00:00');
                }
        
                if ($request->has('end_date') && $request->input('end_date') != '') {
                    $query->where('checkings.created_at', '<=', date('Y-m-d', strtotime($request->input('end_date'))) . ' 23:59:59');
                }

            })
            ->orderBy('checkings.id', 'desc');
        
        if ($request->has('type') && $request->input('type') == 'export') {

            $response['checkings'] = $checkings->get();

            Excel::create('checkings-export-'. time(), function($excel) use ($response) {

                $excel->sheet('sheet1', function($sheet) use ($response) {
            
                    $sheet->loadView('admin.checking.excel', $response);
            
                });
            
            })->download('xls');
        }
        
        $response['checkings'] = $checkings->paginate(30);
        $response['members'] = array_pluck($modelMember->where('type', 'staff')
            ->orderBy('name', 'asc')
            ->get(), 'name', 'id');

        return view('admin.checking.index', $response);
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
    public function delete(Checking $modelChecking, $id)
    {
        $response = flash_message();

        $checking = $modelChecking->find($id);
        if ($checking->delete()) {
            
            $response = flash_message('success', true);
        }

        return redirect()->back()->with('message', $response['message']);
    }
}
