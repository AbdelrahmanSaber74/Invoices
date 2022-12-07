<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use App\Models\invoice_details;
use Illuminate\Http\Request;
use App\Models\invoices;
use App\Models\invoice_attachment ;

class InvoiceDetailsController extends Controller
{

     function invoicedetails($id)
    {
        $invoices = invoices::find($id);
        $invoice_details = invoice_details::all()->where('invoice_id' , $id);
        $invoice_attachment = invoice_attachment::all()->where('invoice_id' , $id);

        return view('invoices.invoicedetails' , compact('invoices' , 'invoice_details' ,'invoice_attachment' ,)) ;
    }



}
