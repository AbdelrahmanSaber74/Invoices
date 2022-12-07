<?php

namespace App\Http\Controllers;

use App\Models\product;
use App\Models\section;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        $products = product::all();
        $sections = section::all();
        return view('products.products' , compact('sections' , 'products')) ;
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'Product_name' => 'required|unique:products|max:255' ,
            'description' => 'required',
        ] , [

            'Product_name.unique' => 'خطأ اسم المنتج مسجل مسبقا' , 
            'Product_name.required' => 'يرجي ادخال اسم المنتج' , 
            'description.required' => 'يرجي ادخال البيان ' , 

        ]);
            

        $product = product::create([
            'Product_name' => $request->Product_name,
            'section_id' => $request->section_id,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('Add' , 'تم اضافة المنتج بنجاح') ;



    }


   
    public function update(Request $request)
    {

        $section_id =section::where('section_name' , $request->section_name)->first()->id;
        $id = $request->pro_id ;
        
        $validated = $request->validate([
            'Product_name' => 'required|max:255|unique:products,Product_name,'.$id,
            'description' => 'required',
        ] , [

            'Product_name.unique' => 'خطأ اسم المنتج مسجل مسبقا' , 
            'Product_name.required' => 'يرجي ادخال اسم المنتج' , 
            'description.required' => 'يرجي ادخال البيان ' , 

        ]);

        $updata = product::find($id);
        $updata->update([
        
        'Product_name' => $request->Product_name ,
        'section_id' => $section_id ,
        'description' => $request->description ,


        ]);
        return redirect()->back()->with('edit' , "تم تعديل القسم بنجاح") ;


            
    }

    public function destroy(Request $request)
    {
        $id = $request->pro_id ;
        product::destroy($id);
        return redirect()->back()->with('delete' , "تم حذف المنتج بنجاح") ;

    }
}
