<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\invoices;
class HomeController extends Controller
{
    
public function index () {

$total_invoices = invoices::all()->count();
$invoice_paid = invoices::where('value_status' , 1)->count();
$invoice_unpaid = invoices::where('value_status' , 2)->count();
$invoice_partily_paid = invoices::where('value_status' , 3)->count();

if ($total_invoices != 0 ){


$percentage_paid = number_format( ($invoice_paid / $total_invoices ) * 100  , 2) ;
$percentage_unpaid = number_format( ($invoice_unpaid / $total_invoices ) * 100  , 2) ;
$percentage_partily_paid = number_format( ($invoice_partily_paid / $total_invoices ) * 100  , 2) ;



$chartjs = app()->chartjs
        ->name('pieChartTest')
        ->type('pie')
        ->size(['width' => 500, 'height' => 300])
        ->labels(['الفواتير الغير مدفوعة', 'الفواتير المدفوعة' , 'الفواتير المدفوعة جزئيا'])
        ->datasets([
            [
                'backgroundColor' => ['#f94f6c', '#049868' ,'#f76c2f'],
                'hoverBackgroundColor' => ['#f94f6c', '#049868' , '#f76c2f'],
                'data' => [$percentage_unpaid, $percentage_paid , $percentage_partily_paid]
            ]
        ])
        ->options([]);

        return view('index', compact('chartjs'));


            }


    }

}
