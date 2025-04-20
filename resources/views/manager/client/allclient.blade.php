@extends('manager.layouts.master')

@section('content')

    <!-- Main content -->
    <section class="content" id="contentContainer">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <!-- /.card -->

              <div class="card">
                <div class="card-header">
                  <h2 class="card-title"></h2>
                  <h3 class="card-title">All Data</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                  <table class="table table-bordered table-striped mt-4 mb-5">
                    <thead>
                    <tr>
                      <th>Processing</th>
                      <th>Complete</th>
                      <th>Decline</th>
                      <th>Receive Amount</th>
                      <th>Discount Amount</th>
                      <th>Others Bill</th>
                      <th>Due Amount</th>
                      <th>Total Received</th>
                    </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td style="text-align: center">{{ $processing }}</td>
                        <td style="text-align: center">{{$completed}}</td>
                        <td style="text-align: center">{{$decline}}</td>
                        <td style="text-align: center">{{$rcvamntForProcessing}}</td>
                        <td style="text-align: center">{{$totalPkgDiscountAmnt}}</td>
                        <td style="text-align: center">{{$totaServiceamt}}</td>
                        <td style="text-align: center">{{$dueForvisa}}</td>
                        <td style="text-align: center">{{$ttlVisanSrvcRcv}}</td>
                      </tr>
                    
                    </tbody>
                  </table>

                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->


    <!-- Main content -->
<section class="content" id="newBtnSection">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 col-sm-12">
        <div class="card card-secondary card-tabs">
          <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="custom-tabs-one-home-tab" data-toggle="pill" href="#custom-tabs-one-home" role="tab" aria-controls="custom-tabs-one-home" aria-selected="false">All Client</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="custom-tabs-one-profile-tab" data-toggle="pill" href="#custom-tabs-one-profile" role="tab" aria-controls="custom-tabs-one-profile" aria-selected="false">VISA Transaction</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="custom-tabs-one-messages-tab" data-toggle="pill" href="#custom-tabs-one-messages" role="tab" aria-controls="custom-tabs-one-messages" aria-selected="false">Okala Transaction</a>
              </li>
              <!-- <li class="nav-item">
                <a class="nav-link " id="custom-tabs-one-settings-tab" data-toggle="pill" href="#custom-tabs-one-settings" role="tab" aria-controls="custom-tabs-one-settings" aria-selected="true">Tab 2</a>
              </li> -->
            </ul>
          </div>
          <div class="card-body" id="allClientContainer">
            <div class="tab-content" id="custom-tabs-one-tabContent">
              <div class="tab-pane fade active show" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                
                <div class="errormessage"></div>

                <table id="example1" class="table table-bordered table-striped mt-4">
                  <thead>
                  <tr>
                    <th>Sl</th>
                    <th>Passport Name</th>
                    <th>Passport Number</th>
                    <th>Package Cost</th>
                    <th>Received Amount</th>
                    <th>Status</th>
                    <th>Mofa</th>
                    <th>Mofa Request</th>
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($datas as $key => $data)
                    <tr>
                      <td style="text-align: center">{{ $key + 1 }}</td>
                      <td style="text-align: center"><a href="{{route('admin.clientDetails', $data->id)}}">{{$data->passport_name}}</a></td>
                      <td style="text-align: center">{{$data->passport_number}}</td>
                      <td style="text-align: center">{{$data->total_package}}</td>
                      <td style="text-align: center">{{$data->total_received}}</td>
                      <td style="text-align: center">
                        @if ($data->status == 0) New
                        @elseif($data->status == 1)Processing
                        @elseif($data->status == 2) Complete @else Decline @endif
                      </td>  
                      <td style="text-align: center">{{$data->mofa}}</td>  
                      <td style="text-align: center">
                        @if ($data->mofa_request == 1)
                            Request Send
                        @else
                          <span class="btn btn-secondary btn-xs mofa-btn" style="cursor: pointer;" data-id="{{ $data->id }}" data-agent-id="{{ Auth::user()->id }}" data-rl-id="">Mofa Request</span>
                        @endif

                        <span class="btn btn-success btn-xs view-btn" style="cursor: pointer;" data-id="{{ $data->id }}" data-agent-id="{{ Auth::user()->id }}">All Request</span>
                      </td>                
                    </tr>
                    @endforeach 
                  
                  </tbody>
                </table>


              </div>
              <div class="tab-pane fade" id="custom-tabs-one-profile" role="tabpanel" aria-labelledby="custom-tabs-one-profile-tab">

                <!-- visa and others transaction start  -->

                <form method="GET" action="{{ route('admin.agentClient', $id) }}">
                  <div class="row">
                    <div class="col-sm-3">
                      <label>From Date</label>
                      <input type="date" class="form-control" name="from_date" value="{{ request()->get('from_date') }}">
                    </div>
                    <div class="col-sm-3">
                      <label>To Date</label>
                      <input type="date" class="form-control" name="to_date" value="{{ request()->get('to_date') }}">
                    </div>
                  </div>
                  <div class="row mt-3">
                    <div class="col-sm-12">
                      <button type="submit" class="btn btn-secondary">Search</button>
                    </div>
                  </div>
                </form>
                
                <!--get total balance -->
                  <?php
                    $tbalance = 0;
                  ?> 
                  @forelse ($clientTransactions as $sdata)
                          
                    @if(($sdata->tran_type == 'package_sales') || ($sdata->tran_type == 'service_sales') || ($sdata->tran_type == 'package_adon') || ($sdata->tran_type == 'service_adon'))
                    <?php $tbalance = $tbalance + $sdata->bdt_amount;?>
                    @elseif(($sdata->tran_type == 'package_received') || ($sdata->tran_type == 'service_received') || ($sdata->tran_type == 'package_discount') || ($sdata->tran_type == 'service_discount'))
                    <?php $tbalance = $tbalance - $sdata->bdt_amount;?>
                    @endif
  
                  @empty
                  @endforelse

                  <!-- /.card-header -->
                    <div class="row">
                    <div class="col-sm-12 text-center">
                      <h2>Transaction</h2>
                    </div>
                    </div>

                <table id="example2" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Sl</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Received</th>
                    <th>Bill</th>
                    <th>Balance</th>                  
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($clientTransactions as $key => $tran)
                    <tr>
               
  
                      @if(($tran->tran_type == 'package_received') || ($tran->tran_type == 'service_received') || ($tran->tran_type == 'package_discount') || ($tran->tran_type == 'service_discount'))
                      <td style="text-align: center">{{ $key + 1 }}</td>
                      <td style="text-align: center">{{$tran->date}}</td>
                      <td style="text-align: center">{{$tran->ref}}  @if(isset($tran->note)){{$tran->note}}@endif</td>
                      <td style="text-align: center">{{$tran->bdt_amount}}</td>
                      <td style="text-align: center"></td>
                      <td style="text-align: center">{{$tbalance}}</td>
                      <?php $tbalance = $tbalance + $tran->bdt_amount;?>
  
                      @elseif(($tran->tran_type == 'package_sales') || ($tran->tran_type == 'service_sales') || ($tran->tran_type == 'package_adon') || ($tran->tran_type == 'service_adon'))
                      <td style="text-align: center">{{ $key + 1 }}</td>
                      <td style="text-align: center">{{$tran->date}}</td>
                      <td style="text-align: center">{{$tran->ref}}  @if(isset($tran->note)){{$tran->note}}@endif</td>
                      <td style="text-align: center"></td>
                      <td style="text-align: center">{{$tran->bdt_amount}}</td>
                      <td style="text-align: center">{{$tbalance}}</td>
                      <?php $tbalance = $tbalance - $tran->bdt_amount;?>
  
                      @endif
  
                    </tr>
                    @endforeach
                  
                  </tbody>
                </table>
                 <!-- End visa and others transaction End  -->
              </div>


              <div class="tab-pane fade" id="custom-tabs-one-messages" role="tabpanel" aria-labelledby="custom-tabs-one-messages-tab">
                Okala transactions  
                <!-- Start visa and others transaction Start  -->

                <!--get total okala balance -->
                <?php
                    $tokala_bal = 0;
                ?> 
                  @forelse ($clientTransactions as $okala_data)
                          
                    @if(($okala_data->tran_type == 'okala_sales') || ($okala_data->tran_type == 'okalasales_adon'))
                    <?php $tokala_bal = $tokala_bal + $okala_data->bdt_amount;?>
                    @elseif(($okala_data->tran_type == 'okala_received') || ($okala_data->tran_type == 'okalasales_discount'))
                    <?php $tokala_bal = $tokala_bal - $okala_data->bdt_amount;?>
                    @endif
  
                  @empty
                  @endforelse

                    <!-- /.card-header -->
                    <div class="row">
                    <div class="col-sm-12 text-center">
                      <h2>Transaction</h2>
                    </div>
                    </div>

                <table id="example2" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Sl</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Dr.</th>
                    <th>Cr.</th>
                    <th>Balance</th>                  
                  </tr>
                  </thead>
                  <tbody>
                    @foreach ($clientTransactions as $okala_key => $okala_tran)
                    <tr>

  
                      @if(($okala_tran->tran_type == 'okala_received') || ($okala_tran->tran_type == 'okalasales_discount'))
                      <td style="text-align: center">{{ $okala_key + 1 }}</td>
                      <td style="text-align: center">{{$okala_tran->date}}</td>
                      <td style="text-align: center">{{$okala_tran->ref}}  @if(isset($okala_tran->note)){{$okala_tran->note}}@endif</td>
  
                      <td style="text-align: center">{{$okala_tran->bdt_amount}}</td>
                      <td style="text-align: center"></td>
                      <td style="text-align: center">{{$tokala_bal}}</td>
                      <?php $tokala_bal = $tokala_bal + $okala_tran->bdt_amount;?>
  
                      @elseif(($okala_tran->tran_type == 'okala_sales') || ($okala_tran->tran_type == 'okalasales_adon'))
                      <td style="text-align: center">{{ $okala_key + 1 }}</td>
                      <td style="text-align: center">{{$okala_tran->date}}</td>
                      <td style="text-align: center">{{$okala_tran->ref}}  @if(isset($okala_tran->note)){{$okala_tran->note}}@endif</td>
  
                      <td style="text-align: center"></td>
                      <td style="text-align: center">{{$okala_tran->bdt_amount}}</td>
                      <td style="text-align: center">{{$tokala_bal}}</td>
                      <?php $tokala_bal = $tokala_bal - $okala_tran->bdt_amount;?>
  
                      @endif
  
                    </tr>
                    @endforeach
                  
                  </tbody>
                </table>

                <!-- End visa and others transaction End  -->
              </div>

              <!-- 
              <div class="tab-pane fade" id="custom-tabs-one-settings" role="tabpanel" aria-labelledby="custom-tabs-one-settings-tab">
                coming soon
              </div> -->


            </div>
          </div>
          <!-- /.card -->
        </div>
      </div>
      
    </div>
  </div>
</section>
<!-- /.content -->


<div class="modal fade" id="rcvModal" tabindex="-1" role="dialog" aria-labelledby="rcvModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="rcvModalLabel">Mofa Request</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <form id="rcvForm">
              <div class="modal-body">
                  <div class="permsg"></div>
                
                  <div class="form-group">
                      <label for="note">Note</label>
                      <textarea class="form-control" id="note" name="note" rows="3"></textarea>
                  </div>

              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-warning">Request Send</button>
              </div>
          </form>
      </div>
  </div>
</div>

<div class="modal fade" id="tranModal" tabindex="-1" role="dialog" aria-labelledby="tranModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title" id="tranModalLabel">All mofa request</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>


          <div class="modal-body">
            
            <table id="trantable" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Note</th>
                </tr>
                </thead>
                <tbody>

                </tbody>
                
            </table>

          </div>
        
          
      </div>
  </div>
</div>



@endsection
@section('script')
<script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
    });



  </script>

<script>
  $(document).ready(function () {
      $("#addThisFormContainer").hide();
      $("#newBtn").click(function(){
          clearform();
          $("#newBtn").hide(100);
          $("#addThisFormContainer").show(300);

      });
      $("#FormCloseBtn").click(function(){
          $("#addThisFormContainer").hide(200);
          $("#newBtn").show(100);
          clearform();
      });
      //header for csrf-token is must in laravel
      $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
      //

      // receive 
      $("#allClientContainer").on('click', '.mofa-btn', function () {
          var client_id = $(this).data('id');
          var agentId = $(this).data('agent-id');

          
          $('#rcvModal').modal('show');
          $('#rcvForm').off('submit').on('submit', function (event) {
              event.preventDefault();

              var form_data = new FormData();
              form_data.append("client_id", client_id);
              form_data.append("agentId", agentId);
              form_data.append("note", $("#note").val());



              $.ajax({
                  url: '{{ URL::to('/manager/client-mofa-request') }}',
                  method: 'POST',
                  data:form_data,
                  contentType: false,
                  processData: false,
                  // dataType: 'json',
                  success: function (response) {
                    if (response.status == 303) {
                      $(function() {
                          var Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                          });
                          Toast.fire({
                            icon: 'error',
                            title: 'Deactive client.'
                          });
                        });
                        $(".errormessage").html(response.message);
                    }else if(response.status == 300){

                      $(function() {
                          var Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                          });
                          Toast.fire({
                            icon: 'success',
                            title: 'Data create successfully.'
                          });
                        });
                      window.setTimeout(function(){location.reload()},2000)
                    }
                    
                      $('#rcvModal').modal('hide');

                  },
                  error: function (xhr) {
                      console.log(xhr.responseText);
                  }
              });
          });
      });

      $('#rcvModal').on('hidden.bs.modal', function () {
          $('#note').val('');
      });
      // receive end 


      $("#allClientContainer").on('click', '.view-btn', function () {
          var id = $(this).data('id');
          var agentId = $(this).data('agent-id');
          $('#tranModal').modal('show');
          
              var form_data = new FormData();
              form_data.append("client_id", id);
              form_data.append("agentId", agentId);

              $.ajax({
                  url: '{{ URL::to('/manager/get-mofa-request') }}',
                  method: 'POST',
                  data:form_data,
                  contentType: false,
                  processData: false,
                  // dataType: 'json',
                  success: function (response) {
                    console.log(response);
                      $('#trantable tbody').html(response.data);
                  },
                  error: function (xhr) {
                      console.log(xhr.responseText);
                  }
              });
      });
      

      function clearform(){
          $('#createThisForm')[0].reset();
          $("#addBtn").val('Create');
      }



  });
</script>
@endsection