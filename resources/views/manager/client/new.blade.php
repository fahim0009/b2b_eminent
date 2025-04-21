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
              <h3 class="card-title">New Client</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body" id="allClientContainer">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>Sl</th>
                  {{-- <th>Client ID</th> --}}
                  <th>Passport Name</th>
                  <th>Passport Number</th>
                  <th>Assign</th>
                  <th>Status</th>
                  <th>Mofa Request</th>
                </tr>
                </thead>
                <tbody>

                  @php
                      $count1 = $count;                     
                  @endphp

                  @foreach ($data as $key => $data)
                  <tr>
                    <td style="text-align: center">{{ ($count1) }} </td>
                    <td style="text-align: center"><a href="{{route('admin.clientDetails', $data->id)}}">{{$data->passport_name}}</a></td>
                    <td style="text-align: center">{{$data->passport_number}}</td>
                    <td style="text-align: center">
                      @if ($data->assign == 1)
                          Assigned
                      @else
                          Not Assigned
                      @endif
                    </td>
                    
                    <td style="text-align: center">
                      <div class="btn-group">
                        <button type="button" class="btn btn-secondary"><span id="stsval{{$data->id}}"> @if ($data->status == 0) New
                          @elseif($data->status == 1) Processing @elseif($data->status == 2) Complete @else Decline @endif</span>
                        </button>
                          
                      </div>
                    </td>
                    <td style="text-align: center">
                      @if ($data->mofa_request == 1)
                          Request Send
                      @else
                        <span class="btn btn-secondary btn-xs mofa-btn" style="cursor: pointer;" data-id="{{ $data->id }}" data-agent-id="{{ Auth::user()->id }}" data-rl-id="">Mofa Request</span>
                      @endif

                      <span class="btn btn-success btn-xs view-btn" style="cursor: pointer;" data-id="{{ $data->id }}" data-agent-id="{{ Auth::user()->id }}">All Request</span>
                    </td> 


                    
                  </tr>
                  
                  @php
                      $count1 = $count1 - 1;
                  @endphp
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
    <!-- /.container-fluid -->
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