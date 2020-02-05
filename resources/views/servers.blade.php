@extends('layouts.app')
   
@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <h3 class="col-12 text-center">Server Management</h3>
      <div class="col-12 text-right mb-2">
        <button type="button" id="btn-create" class="btn btn-success btn-sm">Create Server</button>
      </div>
      <div class="table-responsive">
        <table id="server_table" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Name</th>
              <th>Domain</th>
              <th>IP Address</th>
              <th>User Name</th>
              <th>Password</th>
              <th>Action</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
  
  <div id="formModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add New Server</h4>
          <button type="button" class="close" data-dismiss="modal">
            &times;
          </button>
        </div>
        <div class="modal-body">
          <span id="form_result"></span>
          <form method="post" id="sample_form" class="form-horizontal">
            @csrf
            <div class="form-group">
              <label class="control-label" >Name : </label>
              <input type="text" name="name" id="name" class="form-control" />
            </div>
            <div class="form-group">
              <label class="control-label">Domain : </label>
              <input type="text" name="host_name" id="host_name" class="form-control" />
            </div>
            <div class="form-group">
              <label class="control-label">IP Address : </label>
              <input type="text" name="ip_address" id="ip_address" class="form-control" />
            </div>
            <div class="form-group">
              <label class="control-label">User Name: </label>
              <input type="text" name="user_name" id="user_name" class="form-control" />
            </div>
            <div class="form-group" id="div-password">
              <label class="control-label">Password : </label>
              <input type="text" name="password" id="password" class="form-control" />
            </div>
            <div class="form-group">
              <input type="hidden" name="action" id="action" value="Add" />
              <input type="hidden" name="hidden_id" id="hidden_id" />
              <input type="submit" name="action_button" id="action_button" class="btn btn-info" value="Add" />
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div id="confirmModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Confirmation</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <h4 class="text-center" style="margin:0;">Are you sure you want to remove this data?</h4>
        </div>
        <div class="modal-footer">
          <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">OK</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
  <script>
    $(document).ready(function(){
    
      $('#server_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ route('servers.index') }}",
        },
        columns: [
          {
            data: 'name',
            name: 'name'
          },
          {
            data: 'host_name',
            name: 'host_name'
          },
          {
            data: 'ip_address',
            name: 'ip_address'
          },
          {
            data: 'user_name',
            name: 'user_name'
          },
          {
            data: 'password',
            name: 'password'
          },
          {
            data: 'action',
            name: 'action',
            orderable: false
          }
        ]
      });
    
      $('#btn-create').click(function(){
        $('#formModal .modal-title').text('Add New Server');
        $('#action_button').val('Add');
        $('#action').val('Add');
        $('#form_result').html('');
      
        $('#formModal').modal('show');
      });
      
      $('#sample_form').on('submit', function(event){
        event.preventDefault();
        var action_url = '';
      
        if($('#action').val() == 'Add')
        {
          action_url = "{{ route('servers.store') }}";
        }
      
        if($('#action').val() == 'Edit')
        {
          action_url = "{{ route('servers.update') }}";
        }
      
        $.ajax({
          url: action_url,
          method:"POST",
          data:$(this).serialize(),
          dataType:"json",
          success:function(data)
          {
            var html = '';
            if(data.errors)
            {
              html = '<div class="alert alert-danger">';
              for(var count = 0; count < data.errors.length; count++)
              {
                html += '<p>' + data.errors[count] + '</p>';
              }
              html += '</div>';
            }
            if(data.success)
            {
              html = '<div class="alert alert-success">' + data.success + '</div>';
              $('#sample_form')[0].reset();
              $('#server_table').DataTable().ajax.reload();
              $('#formModal').modal('hide');
            }
            $('#form_result').html(html);
          },
          error:function(err){
            console.log(err)
            $('#formModal').modal('hide');
          }
        });
      });
      
      $(document).on('click', '.edit', function(){
        var id = $(this).attr('id');
        $('#form_result').html('');
        $.ajax({
          url :"/servers/"+id+"/edit",
          dataType:"json",
          success:function(data)
          {
            $('#name').val(data.result.name);
            $('#host_name').val(data.result.host_name);
            $('#ip_address').val(data.result.ip_address);
            $('#user_name').val(data.result.user_name);
            $('#password').val(data.result.password);
            $('#hidden_id').val(id);
            $('#formModal .modal-title').text('Edit Server');
            $('#action_button').val('Edit');
            $('#action').val('Edit');
            $('#formModal').modal('show');
          }
        })
      });
      
      var server_id;
      
      $(document).on('click', '.delete', function(){
        server_id = $(this).attr('id');
        $('#confirmModal').modal('show');
      });
      
      $('#ok_button').click(function(){
        $.ajax({
          url:"/servers/destroy/"+server_id,
          beforeSend:function(){
            $('#ok_button').text('Deleting...');
          },
          success:function(data)
          {
            setTimeout(function(){
              $('#confirmModal').modal('hide');
              $('#server_table').DataTable().ajax.reload();
              alert('Data Deleted');
            }, 1000);
          }
        })
      });
    
    });
  </script>
   
@endsection