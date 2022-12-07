<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\invoice_details;
use App\Models\invoice_attachment;
use app\Models\User;
use App\Models\invoices;
use App\Models\section;
use App\Notifications\InvoicePaid;
use Illuminate\Http\Request;
use League\CommonMark\Delimiter\Delimiter;
use Illuminate\Support\Facades\Notification;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoiceExport;

class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sections = section::all();
        $invoices = invoices::all();
        return view('invoices.invoices' , compact('invoices' , 'sections'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sections = section::all() ;
        return view('invoices.add_invoice' , compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        invoices::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'due_date' => $request->Due_date,
            'section_id' => $request->Section,
            'product' => $request->product,
            'Amount_collection' => $request->Amount_collection,
            'Amount_commission' => $request->Amount_Commission,
            'discount' => $request->Discount,
            'value_vat' => $request->Value_VAT,
            'Rate_Vat' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'value_status' => '2',
            'note' => $request->note ,
        ]) ;

        $invoice_id = invoices::latest()->first()->id;

        invoice_details::create([
            'invoice_id' => $invoice_id ,
            'invoice_number' => $request->invoice_number ,
            'product' => $request->product ,
            'section_id' => $request->Section,
            'Status' => 'غير مدفوعة',
            'value_status' => '2',
            'note' => $request->note ,
            'user' => (auth()->user()->name) ,
        ]) ;



        if($request->hasFile('pic')) {

            $file = $request->file('pic')->getClientOriginalName() ;
            $path= $request->file('pic')->storeAs($request->invoice_number , $file , 'publicfile') ; 


            invoice_attachment::create([
            'file_name' => $file ,
            'invoice_number' => $request->invoice_number ,
            'Created_by' => (auth()->user()->name) ,
            'invoice_id' => $invoice_id ,
            ]) ;
        }




        return redirect()->back()->with('Add' , 'تم اضافة الفاتورة بنجاح');
        

    } 

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = invoices::where('id' , $id)->first();
        
        return view('invoices.status_update' , compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoice = invoices::find($id);
        $sections = section::all();
        return view('invoices.invoices_edit' , compact('invoice' , 'sections')) ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $id = $request->invoice_id;
        $updata = invoices::find($id);
        $updata->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'due_date' => $request->Due_date,
            'product' => $request->product,
            'Total' => $request->Total,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_commission' => $request->Amount_Commission,
            'discount' => $request->Discount,
            'value_vat' => $request->Value_VAT,
            'Rate_Vat' => $request->Rate_VAT,
            'note' => $request->note,
            
        ]);


        $details = invoice_details::where('invoice_id' , $id)->first();
        $details->update([

            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'section_id' => $request->Section,
            'note' => $request->note,
            'user' => (auth()->user()->name) ,
        ]);

        return redirect()->back()->with('edit' , 'تم تعديل الفاتورة بنجاح');



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\invoices  $invoices
     * @return \Illuminate\Http\Response
     */
    public function destroy(request $request)
    {
        $invoice_id = $request->invoice_id;
        $invoice = invoices::where('id' , $invoice_id)->forceDelete();
        return redirect()->back()->with('delete' , "تم حذف الفاتورة نهائيا بنجاح");

    }


    public function archive(request $request)
    {
        $invoice_id = $request->invoice_id;
        $invoice = invoices::where('id' , $invoice_id)->delete();
        return redirect()->back()->with('archive' , "تم ارشفة الفاتورة بنجاح");

    }



    public function updateStatus(request $request)
    {

        $invoice_id = $request->invoice_id ;
        $section = invoice_details::where('invoice_id' , $invoice_id)->pluck('section_id')->first();
        $product = invoice_details::where('invoice_id' , $invoice_id)->pluck('product')->first();

        if($request->value_status == 1) {

            $details = invoice_details::create([

                'invoice_id' => $invoice_id ,
                'invoice_number' => $request->invoice_number ,
                'product' => $product ,
                'section_id' => $section ,
                'Status' => 'مدفوعة' ,
                'Value_Status' => $request->value_status ,
                'Payment_Date' => $request->Payment_Date ,
                'user' => (auth()->user()->name) ,

            ]) ;

            $update = invoices::find($invoice_id) ;
            $update->update([
                'Status' => 'مدفوعة',
                'Value_Status' => $request->value_status ,
                'Payment_Date' => $request->Payment_Date ,

            ]) ;

            return redirect()->back()->with('edit' , 'تم تعديل حالة الفاتورة بنجاح');
        }



       else if ($request->value_status == 3) {
        $details = invoice_details::create([
            'invoice_id' => $invoice_id ,
            'invoice_number' => $request->invoice_number ,
            'product' => $product ,
            'section_id' => $section ,
            'Status' => ' مدفوعة جزئيا' ,
            'Value_Status' => $request->value_status ,
            'Payment_Date' => $request->Payment_Date ,
            'user' => (auth()->user()->name) ,
        ]) ;

        $update = invoices::find($invoice_id) ;
        $update->update([
            'Status' => ' مدفوعة جزئيا' ,
            'Value_Status' => $request->value_status ,
            'Payment_Date' => $request->Payment_Date ,

        ]) ;

        return redirect()->back()->with('edit' , 'تم تعديل حالة الفاتورة بنجاح');

        }
    }

   
    public function invoice_paid (){
        $invoice_paid = invoices::where('value_status' , '1')->get() ;
        return view('invoices.invoice_paid' , compact('invoice_paid')) ;

    }

    
    public function invoice_unpaid () {
        $invoice_unpaid = invoices::where('value_status' , '2')->get() ;
        return view('invoices.invoice_unpaid' , compact('invoice_unpaid')) ;
    }
    

    public function invoice_partial () {
        $invoice_partial = invoices::where('value_status' , '3')->get() ;
        return view('invoices.invoice_partial' , compact('invoice_partial')) ;
    }


    public function getproducts($id)
    {
        $products = DB::table("products")->where("section_id", $id)->pluck("Product_name", "id");
        return json_encode($products);
    }


    public function print($id) {

        $invoice = invoices::find($id);
        return view('invoices.print' , compact('invoice'));
            
    }


    public function export() 
    {
        return Excel::download(new InvoiceExport, 'invoice.xlsx');
    }







}
