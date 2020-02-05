@extends('layouts.app')
   
@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <h3 class="col-12 text-center">User Management</h3>
      <div class="col-12 text-right mb-2">
        <button type="button" id="btn-create" class="btn btn-success btn-sm">Create User</button>
      </div>
      <div class="table-responsive">
        <table id="user_table" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Name</th>
              <th>Host Name</th>
              <th>IP Address</th>
              <th>Email</th>
              {{-- <th>Password</th> --}}
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
          <h4 class="modal-title">Add New User</h4>
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
              <label class="control-label">Host Name : </label>
              <input type="text" name="hostname" id="hostname" class="form-control" />
            </div>
            <div class="form-group">
              <label class="control-label">IP Address : </label>
              <input type="text" name="ip_address" id="ip_address" class="form-control" />
            </div>
            <div class="form-group">
              <label class="control-label">Email : </label>
              <input type="email" name="email" id="email" class="form-control" />
            </div>
            <div class="form-group" id="div-password">
              <label class="control-label">Password : </label>
              <input type="password" name="password" id="password" class="form-control" />
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
    
      $('#user_table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
          url: "{{ route('users.index') }}",
        },
        columns: [
          {
            data: 'name',
            name: 'name'
          },
          {
            data: 'hostname',
            name: 'hostname'
          },
          {
            data: 'ip_address',
            name: 'ip_address'
          },
          {
            data: 'email',
            name: 'email'
          },
          // {
          //   data: 'password',
          //   name: 'password'
          // },
          {
            data: 'action',
            name: 'action',
            orderable: false
          }
        ]
      });
    
      $('#btn-create').click(function(){
        $('.modal-title').text('Add New User');
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
          action_url = "{{ route('users.store') }}";
        }
      
        if($('#action').val() == 'Edit')
        {
          action_url = "{{ route('users.update') }}";
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
              $('#user_table').DataTable().ajax.reload();
            }
            $('#form_result').html(html);
          },
          error:function(err){
            console.log(err)
          }
        });
      });
      
      $(document).on('click', '.edit', function(){
        var id = $(this).attr('id');
        $('#form_result').html('');
        $.ajax({
          url :"/users/"+id+"/edit",
          dataType:"json",
          success:function(data)
          {
            $('#name').val(data.result.name);
            $('#hostname').val(data.result.hostname);
            $('#ip_address').val(data.result.ip_address);
            $('#email').val(data.result.email);
            $('#password').val('');
            $('#hidden_id').val(id);
            $('.modal-title').text('Edit Record');
            $('#action_button').val('Edit');
            $('#action').val('Edit');
            $('#formModal').modal('show');
          }
        })
      });
      
      var user_id;
      
      $(document).on('click', '.delete', function(){
        user_id = $(this).attr('id');
        $('#confirmModal').modal('show');
      });
      
      $('#ok_button').click(function(){
        $.ajax({
          url:"/users/destroy/"+user_id,
          beforeSend:function(){
            $('#ok_button').text('Deleting...');
          },
          success:function(data)
          {
            setTimeout(function(){
              $('#confirmModal').modal('hide');
              $('#user_table').DataTable().ajax.reload();
              alert('Data Deleted');
            }, 1000);
          }
        })
      });
    
    });
  </script>
   
@endsection