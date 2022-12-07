
@extends('layouts.master')
@section('title')
	الفواتير
@endsection
@section('css')
<!-- Internal Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
<!--Internal   Notify -->
<link href="{{URL::asset('assets/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
@endsection
@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الفواتير</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/قائمة الفواتير </span>
						</div>

							
					</div>
				</div>
				<!-- breadcrumb -->
@endsection

@section('content')
			<div class="row">
				
					@if (session()->has('Error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>{{ session()->get('Error') }}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@if (session()->has('delete'))
		<script>
			window.onload = function(){
				notif({
					msg:'تم حذف الفاتورة بنجاح' ,
					type : 'success' ,
				})
			}
		</script>
@endif

@if (session()->has('archive'))
		<script>
			window.onload = function(){
				notif({
					msg:'تم ارشفة الفاتورة بنجاح' ,
					type : 'success' ,
				})
			}
		</script>
@endif


        <!-- delete -->
        <div class="modal fade" id="modaldemo9" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">حذف الفاتورة</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="invoices/destroy" method="post">
                        @method('DELETE')
                        @csrf
                        <div class="modal-body">
                            <p>هل انت متاكد من عملية الحذف نهائيا ؟</p><br>
                            <input type="hidden" name="invoice_id" id="invoice_id" value="">

                            <input class="form-control" name="invoice_number" id="invoice_number" type="text" readonly>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                            <button type="submit" class="btn btn-danger">تاكيد</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- delete -->

        <!-- Archive -->

        <div class="modal fade" id="modelarchive" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">ارشفة الفاتورة</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="archive" method="post">
                        @method('GET')
                        @csrf
                        <div class="modal-body">
                            <p>هل انت متاكد من ارشفة الفاتورة ؟</p><br>
                            <input type="hidden" name="invoice_id" id="invoice_id" value="">
                            <input class="form-control" name="invoice_number" id="invoice_number" type="text" readonly>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">الغاء</button>
                            <button type="submit" class="btn btn-success">تاكيد</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


		
        <!-- Archive -->




		<!-- End Modal effects-->




					<div class="col-xl-12">
						<div class="card mg-b-20">

									<div class="card-header pb-0">

										@can('اضافة فاتورة')
											<a href="invoices/create" class="modal-effect btn btn-sm btn-primary" style="color:white"><i
													class="fas fa-plus"></i>&nbsp; اضافة فاتورة</a>
										@endcan

										@can('تصدير EXCEL')
											<a class="modal-effect btn btn-sm btn-primary" href="export"
												style="color:white"><i class="fas fa-file-download"></i>&nbsp;تصدير اكسيل</a>
										@endcan

									</div>
					

							<div class="card-body">
								<div class="table-responsive">
									<table id="example1" class="table key-buttons text-md-nowrap">
										<thead>
											<tr>
												<th class="border-bottom-0">#</th>
												<th class="border-bottom-0">رقم الفاتورة</th>
												<th class="border-bottom-0">تاريخ الفاتورة</th>
												<th class="border-bottom-0">تاريخ الاستحقاق</th>
												<th class="border-bottom-0">المنتج</th>
												<th class="border-bottom-0">القسم</th>
												<th class="border-bottom-0">الخصم</th>
												<th class="border-bottom-0">نسبة الضريبة</th>
												<th class="border-bottom-0">قيمة الضريبة</th>
												<th class="border-bottom-0">الاجمالي</th>
												<th class="border-bottom-0">الحالة</th>
												<th class="border-bottom-0">ملاحظات</th>
												<th class="border-bottom-0">العمليات</th>

											</tr>
										</thead>
										<tbody>

											@foreach ($invoices as $invoice)
											<tr>
												<td>{{$invoice->id}}</td>
												<td>{{$invoice->invoice_number}}</td>
												<td>{{$invoice->invoice_Date}}</td>
												<td>{{$invoice->due_date}}</td>
												<td>{{$invoice->product}}</td>
												<td>

													<a href="{{route('invoicedetails' , $invoice->id)}}">
														{{$invoice->sections->section_name}}
													</a>
												</td>
												<td>{{$invoice->discount }}</td>
												<td>{{$invoice->Rate_Vat }}</td>
												<td>{{$invoice->value_vat }}</td>
												<td>{{$invoice->Total }}</td>
												<td>
													
											
												@if ($invoice->value_status == 1)
												<span class="text-success"> {{$invoice->Status}} </span> 

												@elseif ($invoice->value_status == 2)
												<span class="text-danger"> {{$invoice->Status}} </span> 
												 
												@else 
												<span class="text-warning"> {{$invoice->Status}} </span> 

												@endif
												
									
												
												</td>
												<td>{{$invoice->note }}</td>
												<td>

													<div class="dropdown">
														<button aria-expanded="false" aria-haspopup="true" class="btn ripple btn-primary"
														data-toggle="dropdown" id="dropdownMenuButton" type="button"> العمليات  <i class="fas fa-caret-down ml-1"></i></button>
														<div  class="dropdown-menu tx-13">

														@can('تعديل الفاتورة')
															<a class="dropdown-item" href="{{route('invoices.edit' , $invoice->id)}}">تعديل الفاتورة</a>
														@endcan

															@can('حذف الفاتورة')
																<button class="dropdown-item" data-invoice_id="{{ $invoice->id }}"
																data-invoice_number="{{$invoice->invoice_number }}" data-toggle="modal"
                                                                data-target="#modaldemo9">حذف</button>
															@endcan

															@can('تغير حالة الدفع')
																<a class="dropdown-item" href="{{route('invoices.show' , $invoice->id)}}">تعديل حالة الدفع</a>
															@endcan


															@can('ارشفة الفاتورة')
																<button class="dropdown-item" data-invoice_id="{{ $invoice->id }}"
																	data-invoice_number="{{$invoice->invoice_number }}" data-toggle="modal"
																	data-target="#modelarchive">نقل الي الارشيف</button>
															@endcan

															@can('طباعةالفاتورة')
																<a class="dropdown-item" href="{{route('print' , $invoice->id)}}">طباعة الفاتورة'</a>
															@endcan

														</div>
													</div>
													


												</td>
											</tr>

											@endforeach


										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<!--/div-->

					<!--div-->

				</div>


				<!-- row closed -->
			</div>
			<!-- Container closed -->
		</div>
		<!-- main-content closed -->
@endsection
@section('js')
<!-- Internal Data tables -->
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>
<!--Internal  Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>

<!--Internal  Notify js -->
<script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>
<script src="{{URL::asset('assets/plugins/notify/js/notifit-custom.js')}}"></script>
<!--Internal  Notify js -->


<script>

        $('#modaldemo9').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var invoice_id = button.data('invoice_id')

            var invoice_number = button.data('invoice_number')
            var modal = $(this)
            modal.find('.modal-body #invoice_id').val(invoice_id);
					
            modal.find('.modal-body #invoice_number').val(invoice_number);
        })


        $('#modelarchive').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget)
            var invoice_id = button.data('invoice_id')

            var invoice_number = button.data('invoice_number')
            var modal = $(this)
            modal.find('.modal-body #invoice_id').val(invoice_id);
					
            modal.find('.modal-body #invoice_number').val(invoice_number);
        })



</script>

<!--Internal  Notify js -->
<script src="{{URL::asset('assets/plugins/notify/js/notifIt.js')}}"></script>
<script src="{{URL::asset('assets/plugins/notify/js/notifit-custom.js')}}"></script>
<!--Internal  Notify js -->

@endsection 