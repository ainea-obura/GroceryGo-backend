<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::getAllCategory();
        // return $category;
        return view('admin.category.index')->with('categories', $category);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parent_cats = Category::get();
        return view('admin.category.create')->with('parent_cats', $parent_cats);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'title' => 'string|required',
            //'summary' => 'string|nullable',
            //'photo'=>'string|nullable',
            'status' => 'required|in:active,inactive',
        ]);
        $data = $request->all();
        $slug = Str::slug($request->title);
        $count = Category::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . date('ymdis') . '-' . rand(0, 999);
        }
        $data['slug'] = $slug;
        $status = Category::create($data);
        if ($status) {
            request()->session()->flash('success', 'Category successfully added');
        } else {
            request()->session()->flash('error', 'Error occurred, Please try again!');
        }
        return redirect()->route('category.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //$parent_cats=Category::where('is_parent',1)->get();
        $category = Category::findOrFail($id);
        return view('admin.category.edit')->with('category', $category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return $request->all();
        $category = Category::findOrFail($id);
        $this->validate($request, [
            'title' => 'string|required',
            //'summary'=>'string|nullable',
            'status' => 'required|in:active,inactive',
        ]);
        $data = $request->all();
        $status = $category->fill($data)->save();
        if ($status) {
            request()->session()->flash('success', 'Category successfully updated');
        } else {
            request()->session()->flash('error', 'Error occurred, Please try again!');
        }
        return redirect()->route('category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $status = $category->delete();

        if ($status) {
            request()->session()->flash('success', 'Category successfully deleted');
        } else {
            request()->session()->flash('error', 'Error while deleting category');
        }
        return redirect()->route('category.index');
    }

    // public function productCat(Request $request){
    //     $products=Category::getProductByCat($request->slug);
    //     // return $request->slug;
    //     $recent_products=Product::where('status','active')->orderBy('id','DESC')->limit(3)->get();

    //     if(request()->is('e-shop.loc/product-grids')){
    //         return view('frontend.pages.product-grids')->with('products',$products->products)->with('recent_products',$recent_products);
    //     }
    //     else{
    //         return view('frontend.pages.product-lists')->with('products',$products->products)->with('recent_products',$recent_products);
    //     }

    // }

    public function productCat(Request $request)
    {
        $products = Category::getProductByCat($request->slug);

        $data = [
            'products' => $products->products,
        ];

        return response()->json($data);
    }

    public function display()
    {
        //return Brand::all();
        return response()->json([
            'categories' =>  Category::orderBy('created_at', 'desc')->get()
        ],200);
    }
    
}