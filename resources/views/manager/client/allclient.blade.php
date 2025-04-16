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
          <div class="card-body">
            <div class="tab-content" id="custom-tabs-one-tabContent">
              <div class="tab-pane fade active show" id="custom-tabs-one-home" role="tabpanel" aria-labelledby="custom-tabs-one-home-tab">
                

                <table id="example1" class="table table-bordered table-striped mt-4">
                  <thead>
                  <tr>
                    <th>Sl</th>
                    <th>Passport Name</th>
                    <th>Passport Number</th>
                    <th>Package Cost</th>
                    <th>Received Amount</th>
                    <th>Status</th>
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

    $(function() {
      $('.stsBtn').click(function() {
        var url = "{{URL::to('/admin/change-client-status')}}";
          var id = $(this).data('id');
          var status = $(this).attr('value');
          $.ajax({
              type: "GET",
              dataType: "json",
              url: url,
              data: {'status': status, 'id': id},
              success: function(d){
                if (d.status == 303) {
                        $(function() {
                          var Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                          });
                          Toast.fire({
                            icon: 'warning',
                            title: d.message
                          });
                        });
                    }else if(d.status == 300){
                      
                      $("#stsval"+d.id).html(d.stsval);
                      $(function() {
                          var Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                          });
                          Toast.fire({
                            icon: 'success',
                            title: d.message
                          });
                        });
                    }
                },
                error: function (d) {
                    console.log(d);
                }
          });
      })
    })

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
      var url = "{{URL::to('/admin/client')}}";
      var upurl = "{{URL::to('/admin/client-update')}}";
      // console.log(url);
      $("#addBtn").click(function(){
      //   alert("#addBtn");
            $(this).prop('disabled', true);
          if($(this).val() == 'Create') {

              var passport_image = $('#passport_image').prop('files')[0];
              if(typeof passport_image === 'undefined'){
                  passport_image = 'null';
              }
              var client_image = $('#client_image').prop('files')[0];
              if(typeof client_image === 'undefined'){
                client_image = 'null';
              }

              var form_data = new FormData();
              form_data.append('passport_image', passport_image);
              form_data.append('client_image', client_image);
              form_data.append("clientid", $("#clientid").val());
              form_data.append("passport_number", $("#passport_number").val());
              form_data.append("passport_name", $("#passport_name").val());
              form_data.append("passport_rcv_date", $("#passport_rcv_date").val());
              form_data.append("country", $("#country").val());
              form_data.append("user_id", $("#user_id").val());
              form_data.append("package_cost", $("#package_cost").val());
              form_data.append("description", $("#description").val());



              $.ajax({
                url: url,
                method: "POST",
                contentType: false,
                processData: false,
                data:form_data,
                success: function (d) {
                    if (d.status == 303) {
                        $(".ermsg").html(d.message);
                        $("#addBtn").prop('disabled', false);
                    }else if(d.status == 300){

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
                },
                error: function (d) {
                    console.log(d);
                }
            });
          }
          //create  end
          //Update
          if($(this).val() == 'Update'){
              var passport_image = $('#passport_image').prop('files')[0];
              if(typeof passport_image === 'undefined'){
                  passport_image = 'null';
              }
              var client_image = $('#client_image').prop('files')[0];
              if(typeof client_image === 'undefined'){
                client_image = 'null';
              }
              
              var form_data = new FormData();
              form_data.append('passport_image', passport_image);
              form_data.append('client_image', client_image);
              form_data.append("clientid", $("#clientid").val());
              form_data.append("passport_number", $("#passport_number").val());
              form_data.append("passport_name", $("#passport_name").val());
              form_data.append("passport_rcv_date", $("#passport_rcv_date").val());
              form_data.append("country", $("#country").val());
              form_data.append("user_id", $("#user_id").val());
              form_data.append("package_cost", $("#package_cost").val());
              form_data.append("description", $("#description").val());
              form_data.append("codeid", $("#codeid").val());
              
              $.ajax({
                  url:upurl,
                  type: "POST",
                  dataType: 'json',
                  contentType: false,
                  processData: false,
                  data:form_data,
                  success: function(d){
                      console.log(d);
                      if (d.status == 303) {
                          $(".ermsg").html(d.message);
                          $("#addBtn").prop('disabled', false);
                          pagetop();
                      }else if(d.status == 300){
                        $(function() {
                          var Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                          });
                          Toast.fire({
                            icon: 'success',
                            title: 'Data updated successfully.'
                          });
                        });
                          window.setTimeout(function(){location.reload()},2000)
                      }
                  },
                  error:function(d){
                      console.log(d);
                  }
              });
          }
          //Update
      });
      //Edit
      // $("#contentContainer").on('click','#EditBtn', function(){
      //     //alert("btn work");
      //     codeid = $(this).attr('rid');
      //     //console.log($codeid);
      //     info_url = url + '/'+codeid+'/edit';
      //     //console.log($info_url);
      //     $.get(info_url,{},function(d){
      //         populateForm(d);
      //         pagetop();
      //     });
      // });
      //Edit  end
      //Delete
      $("#contentContainer").on('click','#deleteBtn', function(){
            if(!confirm('Sure?')) return;
            codeid = $(this).attr('rid');
            info_url = url + '/'+codeid;
            $.ajax({
                url:info_url,
                method: "GET",
                type: "DELETE",
                data:{
                },
                success: function(d){
                    if(d.success) {
                        alert(d.message);
                        location.reload();
                    }
                },
                error:function(d){
                    console.log(d);
                }
            });
        });
        //Delete 
        
      function clearform(){
          $('#createThisForm')[0].reset();
          $("#addBtn").val('Create');
      }



  });
</script>
@endsection