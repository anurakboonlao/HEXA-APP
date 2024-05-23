<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\ProductCategory;
use DB;

use Excel;

class ProductController extends Controller
{
    public $product;
    public $category;

    public function __construct(
        Product $product,
        ProductCategory $category
    ) {
        $this->product = $product;
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
        $response['products'] = $this->product->orderBy('id', 'desc')->get();

        return view('admin.product.index', $response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $response = [];
        $response['categories'] = $this->category->getListToSelect();

        return view('admin.product.create', $response);
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
        
        try {
         
            $this->product->category_id = $request->input('category_id');
            $this->product->image = $request->input('image') ?? "";
            $this->product->name = $request->input('name');
            $this->product->details = $request->input('details');
            $this->product->description = $request->input('description');
            $this->product->price = $request->input('price');
            $this->product->sizes = $request->input('sizes');
            $this->product->colors = $request->input('colors');
            $this->product->public = $request->input('public') ?? 0;
            $this->product->recommended = $request->input('recommended') ?? 0;
            $this->product->sort = $request->input('sort') ?? 0;
            
            if ($this->product->save()) {
                
                $response = flash_message("ทำรายการสำเร็จ", true);
                return redirect('admin/product/'. $this->product->id .'/edit')->with('message', $response);
            }

        } catch (\Exception $e) {
            
            $response = flash_message($e->getMessage());            
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $response = [];
        
        $response['categories'] = $this->category->getListToSelect();

        $product = $this->product->find($id);
        $response['product'] = $product;

        return view('admin.product.update', $response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $response = flash_message();

        try {
        
            $product = $this->product->find($id);
            $product->category_id = $request->input('category_id');
            $product->image = $request->input('image') ?? "";
            $product->name = $request->input('name');
            $product->details = $request->input('details');
            $product->description = $request->input('description');
            $product->price = $request->input('price');
            $product->sizes = $request->input('sizes');
            $product->colors = $request->input('colors');
            $product->public = $request->input('public') ?? 0;
            $product->recommended = $request->input('recommended') ?? $product->sort;
            $product->sort = $request->input('sort') ?? $product->sort;

            if ($product->save()) {

                $response = flash_message("ทำรายการสำเร็จ", true);
            }
        
        } catch (\Exception $e) {
            
            $response = flash_message($e->getMessage());            
        }

        return redirect()->back()->with('message', $response);
    }

    /**
     * 
     * 
     * 
     */
    public function importProducts(Request $request)
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
                        
                        $products = [];
                        foreach ($data as $key => $value) {
                            $products[] = [
                                'name' => $value->name,
                                'details' => $value->details,
                                'price' => $value->price,
                                'sizes' => $value->sizes,
                                'colors' => $value->colors,
                                'description' => $value->description,
                                'public' => $value->public ?? 1,
                                'created_at' => date('Y-m-d H:i:s')
                            ];
                        }

                        $this->product->insert($products);
                        $response = flash_message('success['. count($products) .']', true);                        
                    }
                }
                
                return redirect()->back()->with('message', $response);

            } catch (\Exception $e) {
                
                $response = flash_message($e->getMessage());
                return redirect()->back()->with('message', $response);
            }
        }

        return view('admin.product.import', $response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $response = flash_message();

        $product = $this->product->find($id);
        if ($product->delete()) {

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

        $product = $this->product->find($id);
        if ($product->delete()) {

            $response = flash_message("ทำรายการสำเร็จ", true);
        }

        return redirect()->back()->with('message', $response);
    }
}