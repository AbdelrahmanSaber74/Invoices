<?php

namespace App\Http\Controllers;

use App\Models\invoiceSoft;
use Illuminate\Http\Request;
use App\Models\invoices;

class InvoiceSoftController extends Controller
{
 
    public function index()
    {
        $invoices = invoices::onlyTrashed()->get();
        return view('invoices.invoice_soft' , compact('invoices'))  ;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\invoiceSoft  $invoiceSoft
     * @return \Illuminate\Http\Response
     */
    public function destroy(request $request )
    {
        $id =  $request->invoice_id;
        $invoice = invoices::withTrashed()->where('id' , $id)->forceDelete();
        return redirect()->back()->with('forcedelete' , "تم حذف الفاتورة نهائيا بنجاح");


    }



    public function restore(Request $request)
    {
        $id = $request->invoice_id ;
        $invoices = invoices::withTrashed()->where('id', $id)->restore();
        return redirect()->back()->with('restore' , "تم استرجاع الفاتورة بنجاح");

    }

}
