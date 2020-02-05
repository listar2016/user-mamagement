@extends('layouts.app')
   
@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <h3 class="col-12 text-center">Users</h3>
      <div class="table-responsive">
        <table id="user_table" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>User Name</th>
              <th>Domains</th>
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
					url: "{{ route('hostnames') }}",
				},
				columns: [
					{
						data: 'user_name',
						name: 'user_name'
					},
					{
						data: 'hostnames',
						name: 'hostnames'
					},
				]
			});
		});
	</script>
@endsection