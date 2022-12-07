<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use League\Flysystem;


use App\Models\invoice_attachment;
use Illuminate\Http\Request;

class InvoiceAttachmentController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validated = $request->validate([

            'file_name' => 'mimes:pdf,jpeg,png,jpg',
    
            ], [
                'file_name.mimes' => 'صيغة المرفق يجب ان تكون   pdf, jpeg , png , jpg',
            ]);
    


        if($request->hasFile('file_name')) {
            $file = $request->file('file_name')->getClientOriginalName() ;
            $path= $request->file('file_name')->storeAs($request->invoice_number , $file , 'publicfile') ; 


            invoice_attachment::create([
            'file_name' => $file ,
            'invoice_number' => $request->invoice_number ,
            'Created_by' => (auth()->user()->name) ,
            'invoice_id' => $request->invoice_id ,
            ]) ;
        }

        return redirect()->back()->with('Add' , 'تم اضافة المرفق بنجاح');
    }

}
