@extends('manager.layouts.master')

@section('content')



    <!-- Main content -->
    <section class="content">
      
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card card-secondary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  @if ($data->client_image)
                    <img class="profile-user-img img-fluid img-circle"
                  src="{{asset('images/client/'.$data->client_image)}}"
                  alt="User profile picture" style="height: 200px; width:auto">
                  @else
                  <img class="profile-user-img img-fluid img-circle" src="{{asset('default.png')}}" alt="User profile picture" style="height: 200px; width:auto">                     
                  @endif
                </div>

                <h3 class="profile-username text-center">{{$data->passport_name}}</h3>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->
            <div class="card card-secondary">
              <div class="card-header">
                <h3 class="card-title">About Me</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">

                <strong><i class="fas fa-book mr-1"></i> Agent Details</strong>
                <p class="text-muted">
                  {{$data->user->name}} {{$data->user->surname}}<br>
                  {{$data->user->email}} <br>
                  {{$data->user->phone}} <br>
                  
                </p>
                <input type="hidden" id="agent_id" value="{{$data->user_id}}">
                <hr>
                {{-- <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>
                <p class="text-muted">Malibu, California</p>
                <hr> --}}

                <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>

                <p class="text-muted">{{$data->description}}</p>

                <hr>
                <strong><i class="far fa-file-alt mr-1"></i> Total Received</strong>
                <p class="text-muted">{{$data->total_rcv}}</p>
                <hr>
                <strong><i class="far fa-file-alt mr-1"></i> Due Amount</strong>
                <p class="text-muted">{{$data->due_amount}}</p>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
          <div class="col-md-9">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Client Details</a></li>
                  <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Documents</a></li>
                  <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Transaction</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="activity">
                    <!-- Post -->
                    <form class="form-horizontal">
                      @csrf
                      <div class="row">
                        <div class="col-sm-12">
                            <label>Client ID</label>
                            <div class="ermsg"></div>
                            <input type="number" class="form-control" id="clientid" name="clientid" value="{{$data->clientid}}" readonly="readonly">
                        </div>
                      </div>


                      <div class="row">
                        <div class="col-sm-6">
                            <label>Name <small>(Passport Name)</small></label>
                            <input type="text" class="form-control" value="{{$data->passport_name}}" id="passport_name" name="passport_name" readonly="readonly">
                            <input type="hidden" name="codeid" id="codeid" value="{{$data->id}}">
                        </div>
                        <div class="col-sm-6">
                            <label>Passport Number</label>
                            <input type="text" id="passport_number" name="passport_number" class="form-control" value="{{$data->passport_number}}" readonly="readonly">
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-6">
                            <label>Passport Receive Date</label>
                            <input type="date" class="form-control" id="passport_rcv_date" name="passport_rcv_date" value="{{$data->passport_rcv_date}}" readonly="readonly">
                        </div>
                        <div class="col-sm-6">
                            <label>Flight Date/Delivery Date</label>
                            <input type="date" class="form-control" id="flight_date" name="flight_date" value="{{$data->flight_date}}" readonly="readonly">
                        </div>
    
                        

                      </div>

                      <div class="row">
                        <div class="col-sm-6">
                            <label>Package Cost</label>
                            <input type="number" class="form-control" id="package_cost" name="package_cost"  value="{{$data->package_cost}}" readonly="readonly">
                        </div>

                        <div class="col-sm-6">
                          <label>Visa Expired  Date</label>
                          <input type="date" class="form-control" id="visa_exp_date" name="visa_exp_date" value="{{$data->visa_exp_date}}" readonly="readonly">
                        </div>
                        
                        
                      </div>


                      <div class="row">
                        <div class="col-sm-6">
                            <label>Country</label>
                            <select class="form-control" id="country" name="country" disabled>
                              <option value="">Select</option>
                              @foreach ($countries as $country)
                                <option value="{{$country->id}}"@if ($country->id == $data->country_id) selected @endif >{{$country->type_name}}</option>
                              @endforeach
                            </select>
                        </div>

                        
                        <div class="col-sm-6">
                            <label>Agents</label>
                            <select name="user_id" id="user_id" class="form-control" disabled>
                              <option value="">Select</option>
                              @foreach ($agents as $item)
                              <option value="{{$item->id}}" @if ($item->id == $data->user_id) selected @endif>{{$item->name}} {{$item->surname}}</option>
                              @endforeach
                            </select>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-12">
                            <label>Description</label>
                            <textarea name="description" id="description" cols="30" rows="2" class="form-control" readonly="readonly">{{$data->description}}</textarea>
                        </div>
                      </div>

                      

                    </form>
                    <!-- /.post -->
                  </div>
                  <!-- /.tab-pane -->
                  <div class="tab-pane" id="timeline">
                    
                    <!-- Post -->
                    <div class="post">
                      <!-- /.user-block -->
                      
                      <div class="user-block">
                        <span class="username">
                          <h3>Client Image</h3>
                        </span>
                      </div>

                      <div class="row mb-3">
                        <div class="col-sm-6">
                          @if ($data->client_image)
                            <img class="img-fluid" src="{{asset('images/client/'.$data->client_image)}}" alt="Photo">
                          @else
                              
                            <img src="{{ asset('assets/common/loader.gif') }}" class="img-fluid" alt="User Image">
                          @endif
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                          <div class="row">
                            <div class="col-sm-12">
                              <a href="{{ route('client_image.download',$data->id)}}" class="btn btn-secondary">Download</a>
                            </div>
                          </div>
                          <!-- /.row -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->

                    </div>
                    <!-- /.post -->

                    <!-- Post -->
                    <div class="post">
                      <!-- /.user-block -->
                      
                      <div class="user-block">
                        <span class="username">
                          <h3>Passport Image</h3>
                        </span>
                      </div>

                      <div class="row mb-3">
                        <div class="col-sm-6">
                          @if ($data->passport_image)
                            <img class="img-fluid" src="{{asset('images/client/passport/'.$data->passport_image)}}" alt="Photo">
                          @else
                              
                            <img src="{{ asset('assets/common/loader.gif') }}" class="img-fluid" alt="User Image">
                          @endif
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                          <div class="row">
                            <div class="col-sm-12">
                              <a href="{{ route('passport_image.download',$data->id)}}" class="btn btn-secondary">Download</a>
                            </div>
                          </div>
                          <!-- /.row -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->

                    </div>
                    <!-- /.post -->

                    <!-- Post -->
                    <div class="post">
                      <!-- /.user-block -->
                      
                      <div class="user-block">
                        <span class="username">
                          <h3>Visa Image</h3>
                        </span>
                      </div>

                      <div class="row mb-3">
                        <div class="col-sm-6">

                          @if ($data->visa)
                          @php
                              $foo = \File::extension($data->visa);
                          @endphp
                              
                          @if ($foo == "pdf")
                            <div class="row justify-content-center">
                              <iframe src="{{asset('images/client/visa/'.$data->visa)}}" width="100%" height="600">
                                <a href="{{asset('images/client/visa/'.$data->visa)}}">Download PDF</a>
                              </iframe>
                            </div>
                          @else
                              <img class="img-fluid" src="{{asset('images/client/visa/'.$data->visa)}}" alt="Photo">
                          @endif

                          
                        
                          @else
                            <img src="{{ asset('assets/common/loader.gif') }}" class="img-fluid" alt="User Image">
                          @endif
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                          <div class="row">
                            <div class="col-sm-12">
                              <a href="{{ route('visa_image.download',$data->id)}}" class="btn btn-secondary">Download</a>
                            </div>
                          </div>
                          <!-- /.row -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->

                    </div>
                    <!-- /.post -->

                    <!-- Post -->
                    <div class="post">
                      <!-- /.user-block -->
                      
                      <div class="user-block">
                        <span class="username">
                          <h3>Manpower Image</h3>
                        </span>
                      </div>

                      <div class="row mb-3">
                        <div class="col-sm-6">

                          @if ($data->manpower_image)

                          @php
                              $chkmp = \File::extension($data->manpower_image);
                          @endphp

                          @if ($chkmp == "pdf")
                            <div class="row justify-content-center">
                              <iframe src="{{asset('images/client/manpower/'.$data->manpower_image)}}" width="100%" height="600">
                                <a href="{{asset('images/client/manpower/'.$data->manpower_image)}}">Download PDF</a>
                              </iframe>
                            </div>
                          @else
                              <img class="img-fluid" src="{{asset('images/client/manpower/'.$data->manpower_image)}}" alt="Photo">
                          @endif

                          @else
                          <img src="{{ asset('assets/common/loader.gif') }}" class="img-fluid" alt="User Image">
                          @endif
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                          <div class="row">
                            <div class="col-sm-12">
                              <a href="{{ route('manpower_image.download',$data->id)}}" class="btn btn-secondary">Download</a>
                            </div>
                          </div>
                          <!-- /.row -->
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->

                    </div>
                    <!-- /.post -->

                  </div>
                  <!-- /.tab-pane -->

                  <div class="tab-pane" id="settings">
                <!-- Image loader -->
                <div id='loading' style='display:none ;'>
                    <img src="{{ asset('assets/common/loader.gif') }}" id="loading-image" alt="Loading..." />
                </div>
                <!-- Image loader -->
                    {{-- <div class="row">
                          <h3>Money Received</h3>
                    </div>
                    <div class="tranermsg"></div>

                    <form class="form-horizontal">

                      <div class="row">
                      <div class="col-sm-4">
                            <label>Date</label>
                            <input type="date" class="form-control" id="date" name="date">
                            <input type="hidden" class="form-control" id="tran_id" name="tran_id">
                            <input type="hidden" class="form-control" id="client_name" name="client_name" value="{{$data->passport_name}}">
                            <input type="hidden" class="form-control" id="client_passport" name="client_passport" value="{{$data->passport_number}}">
                        </div>
                        <div class="col-sm-4">
                              <label>Transaction Type</label>
                              <select class="form-control" id="tran_type" name="tran_type">
                                <option value="">Select</option>               
                                  <option value="package_received">Package Received</option>
                                  <option value="package_adon">Package Ad-On</option>
                                  <option value="package_discount">Package Discount</option>
                              </select>
                          </div>
                      </div>

                      <div class="row">
                      <div class="col-sm-4">
                            <label>Transaction method</label>
                            <select class="form-control" id="account_id" name="account_id">
                              <option value="">Select</option>
                              @foreach ($accounts as $method)
                                <option value="{{$method->id}}">{{$method->name}}</option>
                              @endforeach
                            </select>
                        </div>

                        <div class="col-sm-4">
                            <label>Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount">
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-sm-8">
                            <label>Note</label>
                            <input type="text" class="form-control" id="note" name="note">
                        </div>
                      </div>
                      
                      
                      <div class="form-group row rcptBtn">
                        <div class="col-sm-12 mt-2">
                          <button type="button" id="rcptBtn" class="btn btn-success">Save</button>
                        </div>
                      </div>
                      <div class="form-group row rcptUpBtn" style="display: none">
                        <div class="col-sm-12 mt-2">
                          <button type="button" id="rcptUpBtn" class="btn btn-success">Update</button>
                          <button type="button" id="rcptCloseBtn" class="btn btn-warning">Close</button>
                        </div>
                      </div>
                    </form> --}}

                    <div class="row">
                          <h3>Receive History</h3>
                    </div>

                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-12">
                          <!-- /.card -->
                
                          <div class="card">
                            <div class="card-header">
                              <h3 class="card-title">All Data</h3>
                            </div>
                            <!-- /.card-header -->

                  <!--get total balance -->
                  <?php
                    $tbalance = 0;
                    ?> 
                    @forelse ($trans as $sdata)
                            
                    @if(($sdata->tran_type == 'package_sales') || ($sdata->tran_type == 'package_adon'))
                    <?php $tbalance = $tbalance + $sdata->bdt_amount;?>
                    @elseif(($sdata->tran_type == 'package_received') || ($sdata->tran_type == 'package_discount'))
                    <?php $tbalance = $tbalance - $sdata->bdt_amount;?>
                    @endif
     
                    @empty
                    @endforelse

                            <div class="card-body" id="rcvContainer">
                              <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                  <th>Sl</th>
                                  <th>Date</th>
                                  <th>Transaction Method</th>
                                  <th>Dr.</th>
                                  <th>Cr.</th>
                                  <th>Balance</th>
                                </tr>
                                </thead>
                                <tbody>
                                  @foreach ($trans as $key => $tran)
                                  <tr>
                                    <td style="text-align: center">{{ $key + 1 }}</td>
                                    <td style="text-align: center">{{$tran->date}}</td>
                                    <td style="text-align: center">@if(isset($tran->account_id)){{$tran->account->short_name}}@else {{$tran->ref}} @endif @if(isset($tran->note))({{$tran->note}})@endif</td>
                                    <!-- <td style="text-align: center">{{$tran->bdt_amount}}</td> -->

                                    @if(($tran->tran_type == 'package_received') || ($tran->tran_type == 'package_discount'))

                                    <td style="text-align: center">{{$tran->bdt_amount}}</td>
                                    <td style="text-align: center"></td>
                                    <td style="text-align: center">{{$tbalance}}</td>
                                    <?php $tbalance = $tbalance + $tran->bdt_amount;?>

                                    @elseif(($tran->tran_type == 'package_sales') || ($tran->tran_type == 'package_adon'))

                                    <td style="text-align: center"></td>
                                    <td style="text-align: center">{{$tran->bdt_amount}}</td>
                                    <td style="text-align: center">{{$tbalance}}</td>
                                    <?php $tbalance = $tbalance - $tran->bdt_amount;?>

                                    @endif

                                  </tr>
                                  @endforeach
                                
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

                  </div>
                  
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
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
  </script>

<script>
  $(document).ready(function () {
    
      //header for csrf-token is must in laravel
      $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
      //
      
          




      
  });
</script>
@endsection