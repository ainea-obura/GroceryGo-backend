<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Image;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::getAllProduct();
        return view('admin.product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::all();
        $category = Category::get();
        return view('admin.product.create', compact('products'))->with('categories', $category);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'summary' => 'string|required',
            'description' => 'string|nullable',
            'cat_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'status' => 'required|in:active,inactive',
        ]);
        $slug = Str::slug($request->title);
        $count = Product::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . date('ymdis') . '-' . rand(0, 999);
        }
        $data['slug'] = $slug;
        if ($request->hasfile('thumbnail')) {
            $fileName = time() . $request->file('thumbnail')->getClientOriginalName();
            $path = $request->file('thumbnail')->storeAs('thumbnail', $fileName, 'public');
            $data["thumbnail"] = '/storage/' . $path;

        }

        $new_product = Product::create($data);
        if ($request->has('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = $data['title'] . '-image-' . time() . rand(1, 1000) . '.' . $image->extension();
                $image->move(public_path('product_images'), $imageName);
                Image::create([
                    'product_id' => $new_product->id,
                    'image' => $imageName
                ]);
            }
        }
        //return back()->with('success','Added');
        return redirect()->route('products.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    // public function show(Product $product)
    // {
    //     //
    // }


    public function images($id)
    {
        $product = Product::find($id);
        if (!$product)
            abort(404);
        $images = $product->images;
        return view('admin.product.images', compact('product', 'images'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $product=Product::findOrFail($id);
        // $category=Category::get();
        // $items=Product::where('id',$id)->get();
        // return $items;
        // return view('admin.product.edit')->with('product',$product)
        //             ->with('categories',$category)->with('items',$items);
        //$brand=Brand::get();
        $product = Product::findOrFail($id);
        $category = Category::get();
        $items = Product::where('id', $id)->get();
        //return $items;
        return view('admin.product.edit')->with('product', $product)->with('categories', $category)->with('items', $items);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'title' => 'required',
            'summary' => 'string|required',
            'description' => 'string|nullable',
            'cat_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'status' => 'required|in:active,inactive',
        ]);

        $slug = Str::slug($request->title);
        $count = Product::where('slug', $slug)->where('id', '!=', $product->id)->count();
        if ($count > 0) {
            $slug = $slug . '-' . date('ymdis') . '-' . rand(0, 999);
        }

        $data['slug'] = $slug;

        if ($request->hasfile('thumbnail')) {
            $fileName = time() . $request->file('thumbnail')->getClientOriginalName();
            $path = $request->file('thumbnail')->storeAs('thumbnail', $fileName, 'public');
            $data["thumbnail"] = '/storage/' . $path;
        }

        $product->update($data);

        if ($request->has('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = $data['title'] . '-image-' . time() . rand(1, 1000) . '.' . $image->extension();
                $image->move(public_path('product_images'), $imageName);
                Image::create([
                    'product_id' => $product->id,
                    'image' => $imageName
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }
    // public function update(Request $request, $id)
    // {
    //     $product=Product::findOrFail($id);
    //     $this->validate($request,[
    //         'title'=>'required',
    //         'summary'=>'string|required',
    //         'description'=>'string|nullable',
    //         'cat_id'=>'required|exists:categories,id',
    //         'price'=>'required|numeric',
    //         'discount'=>'nullable|numeric',
    //         'status'=>'required|in:active,inactive',
    //     ]);

    //     $data=$request->all();
    //     //return $data;
    //     $status=$product->fill($data)->save();
    //     if($status){
    //         request()->session()->flash('success','Product Successfully updated');
    //     }
    //     else{
    //         request()->session()->flash('error','Please try again!!');
    //     }
    //     return redirect()->route('products.index');
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $status = $product->delete();

        if ($status) {
            request()->session()->flash('success', 'Product successfully deleted');
        } else {
            request()->session()->flash('error', 'Error while deleting product');
        }
        return redirect()->route('products.index');
    }

    public function display()
    {
        //return Car::all()->with('images');

        return response([
            'product' => Product::orderBy('created_at', 'desc')->with('images')->get()
        ], 200);
    }

    public function show($id)
    {
        //return Product::find($id);
        return response()->json([
            'product' => Product::where('id', $id)->with('images')->get()
        ], 200);
    }

}