<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ProductCategory;

class ProductCategoryController extends Controller
{
    public $category;

    public function __construct(
        ProductCategory $category
    ) {
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = [];

        $response['categories'] = $this->category
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.category.index', $response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.category.create');
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

        $this->category->name = $request->input('name');
        $this->category->sort = $request->input('sort') ?? 0;
        $this->category->public = $request->input('public') ?? 0;

        if ($this->category->save()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function show($categoryId)
    {
        $category = $this->category->find($categoryId);
        
        $categories = [];
        foreach ($category->categories as $key => $value) {
            $categories[] = [
                'id' => $value->id,
                'name' => $value->name
            ];
        }

        return response()->json($categories);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $response = [];

        $response['category'] = $this->category->find($id);

        return view('admin.category.update', $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $response = flash_message();

        $category = $this->category->find($id);

        $category->name = $request->input('name');
        $category->sort = $request->input('sort');
        $category->public = $request->input('public') ?? 0;

        if ($category->save()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = flash_message();

        $category = $this->category->find($id);
        if ($category->delete()) {

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

        $category = $this->category->find($id);
        if ($category->delete()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }
}
