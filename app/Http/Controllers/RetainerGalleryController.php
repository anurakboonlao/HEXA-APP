<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RetainerGallery;

class RetainerGalleryController extends Controller
{
    public $retainerGallery;

    public function __construct(
        RetainerGallery $retainerGallery
    ) {
        $this->retainerGallery = $retainerGallery;
    }

    /**
     * View index 
     * 
     */
    public function index()
    {
        $response = [];

        $response['retainer_galleries'] = $this->retainerGallery->orderBy('id', 'desc')->get();

        return view('admin.retainer_gallery.index', $response);
    }

    /**
     * View create 
     * 
     */
    public function create()
    {
        $response = [];

        return view('admin.retainer_gallery.create', $response);
    }

    /**
     * Get request and save 
     * 
     */
    public function store(Request $request)
    {
        $response = flash_message();

        $this->retainerGallery->name = $request->input('name');
        $this->retainerGallery->image = $request->input('image');
        $this->retainerGallery->url = $request->input('url');
        $this->retainerGallery->sort = 0;
        $this->retainerGallery->public = $request->input('public') ?? 0;

        if ($this->retainerGallery->save()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * Get retainer id response to view update
     * 
     */
    public function edit($id)
    {
        $response = [];

        $response['retainer_gallery'] = $this->retainerGallery->find($id);

        return view('admin.retainer_gallery.update', $response);
    }

    /**
     * Get request and update retainer data
     * 
     */
    public function update(Request $request, $id)
    {
        $response = flash_message();

        $retainerGallery = $this->retainerGallery->find($id);
        $retainerGallery->name = $request->input('name');
        $retainerGallery->image = $request->input('image');
        $retainerGallery->url = $request->input('url');
        $retainerGallery->sort = $retainerGallery->sort;
        $retainerGallery->public = $request->input('public') ?? 0;

        if ($retainerGallery->save()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * Get retainer id to delete
     * 
     */
    public function delete($id)
    {
        $response = flash_message();

        $retainerGallery = $this->retainerGallery->find($id);

        if ($retainerGallery->delete()) {

            $response = flash_message("ลบข้อมูลสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }
}
