<?php

namespace App\Http\Controllers;

use App\Models\section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = section::all();
        return view('sections.sections' , compact('sections')) ;
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

        $validated = $request->validate([
            'section_name' => 'required|unique:sections|max:255',
            'description' => 'required',
        ], [
            'section_name.required' => 'يرجي ادخال اسم القسم'  ,
            'section_name.unique' => 'خطأ , اسم القسم مسجل مسبقا'  ,
            'description.required' => 'يرجي ادخال البيان'  ,
        ]);

        section::create([
            'section_name' => $request->section_name ,
            'description' => $request->description ,
            'Created_by' => (auth()->user()->name) ,

        ]);

        return redirect()->back()->with('Add' , "تم اضافة القسم بنجاح") ;

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\section  $section
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request )
    {
        
        $id = $request->id;

        $validated = $request->validate([
            'section_name' => 'required|max:255|unique:sections,section_name,'.$id,
            'description' => 'required',
        ], [
            'section_name.required' => 'يرجي ادخال اسم القسم'  ,
            'section_name.unique' => 'خطأ , اسم القسم مسجل مسبقا'  ,
            'description.required' => 'يرجي ادخال البيان'  ,
        ]);


        $update = section::find($id);

        $update->update([
            'section_name' => $request->section_name,
            'description' => $request->description,

        ]);

        return redirect()->back()->with('edit' , "تم تعديل القسم بنجاح") ;


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\section  $section
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request )
    {
        $id =$request->id;
        section::destroy($id);
        return redirect()->back()->with('delete' , "تم حذف القسم بنجاح") ;

    }
}
