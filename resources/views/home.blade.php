@extends('layouts.app')
   
@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <h3 class="col-12 text-center">User Information</h3>
      <div class="table-responsive">
        <table id="user_table" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Name</th>
              <th>Host Name</th>
            </tr>
          </thead>
        </table>
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
				]
			});
		});
	</script>
@endsection