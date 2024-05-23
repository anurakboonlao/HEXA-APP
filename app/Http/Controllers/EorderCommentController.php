<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\EorderComment;
use App\Member;
use App\Branch;

use DB;

use Carbon\Carbon;

use Excel;

class EorderCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(
        Request $request,
        EorderComment $modelEorderComment,
        Member $modelMember
    ) {
        $response = [];
        $eorderComments = $modelEorderComment
            ->where(function($query) use ($request) {

                if ($request->has('key') && $request->input('key') != '') {
                    $query->orWhere('id', 'like', '%'. $request->input('key') .'%');
                }

                if ($request->has('start_date') && $request->input('start_date') != '') {
                    $query->where('updated_at', '>=', date('Y-m-d', strtotime($request->input('start_date'))) . ' 00:00:00');
                }
        
                if ($request->has('end_date') && $request->input('end_date') != '') {
                    $query->where('updated_at', '<=', date('Y-m-d', strtotime($request->input('end_date'))) . ' 23:59:59');
                }

            })
            ->orderBy('created_at', 'desc');
        
        if ($request->has('type') && $request->input('type') == 'export') {

            $response['comments'] = $eorderComments->get();

            Excel::create('feedback-export-'. time(), function($excel) use ($response) {

                $excel->sheet('sheet1', function($sheet) use ($response) {
            
                    $sheet->loadView('admin.comment.excel', $response);
            
                });
            
            })->download('xls');
        }

        $response['comments'] = $eorderComments->paginate(30);

        return view('admin.comment.index', $response);
    }
}
