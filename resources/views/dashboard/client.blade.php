
@extends('layouts.master')
@section('content')
    {{-- message --}}
    {!! Toastr::message() !!}
    <!-- Page Wrapper -->
    <div class="page-wrapper">

        <!-- Page Content -->
        <div class="content container-fluid">
        
            <!-- Page Header -->
            <div class="page-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="page-title">Clients</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Clients</li>
                        </ul>
                    </div>
                    <div class="col-auto float-right ml-auto">
                        <a href="{{ route('create/estimate/page') }}" class="btn add-btn"><i class="fa fa-plus"></i> Create Client</a>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            
           
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0">
                        <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                            <tbody>
                                    @foreach ($client as $client)
                                    <tr>
                                        <td>
                                            <h2 class="table-avatar">
                                                <a href="#" class="avatar"><img alt="" src="{{ $client->avatar }}"></a>
                                                <a href="">{{ $client->name }} <span>{{ $client->role
                                                        }}</span></a>
                                            </h2>
                                        </td>
                                        <td>{{ $client->email }}</td>
                                        <td>
                                            <div class="dropdown action-label">
                                                <a class="btn btn-white btn-sm btn-rounded dropdown-toggle" href="#"
                                                    data-toggle="dropdown" aria-expanded="false"> <i
                                                        class="fa fa-dot-circle-o text-{{ $client->status === 'Active' ? 'success' : 'danger' }}"></i>
                                                    {{ $client->status }} </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('status_form_active_{{ $client->id }}').submit();">
                    <i class="fa fa-dot-circle-o text-success"></i> Active
                </a>
                <form id="status_form_active_{{ $client->id }}" action="{{ route('update_status', ['status' => 'active']) }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="id" value="{{ $client->id }}">
                    <!-- Add any other hidden inputs you need -->
                </form>
                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('status_form_inactive_{{ $client->id }}').submit();">
                    <i class="fa fa-dot-circle-o text-danger"></i> Inactive
                </a>
                <form id="status_form_inactive_{{ $client->id }}" action="{{ route('update_status', ['status' => 'inactive']) }}" method="POST" style="display: none;">
                    @csrf
                    <input type="hidden" name="id" value="{{ $client->id }}">
                    <!-- Add any other hidden inputs you need -->
                </form>
            </div>
        </div>
    </td>
    <td class="text-right">
        <div class="dropdown dropdown-action">
            <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <i class="material-icons">more_vert</i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item edit-client" href="#" data-toggle="modal" data-target="#editClientModal" data-clientid="{{ $client->id }}" data-clientname="{{ $client->name }}" data-clientemail="{{ $client->email }}" data-clientstatus="{{ $client->status }}">
                    <i class="fa fa-pencil m-r-5"></i> Edit
                </a>
                <!-- <a class="dropdown-item edit-client" href="#" data-toggle="modal" data-target="#deleteClientModal" data-clientid="{{ $client->id }}">
    <i class="fa fa-pencil m-r-5"></i> Delete
</a>
            </div> -->
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
        <div class="modal fade" id="editClientModal" tabindex="-1" role="dialog"
                aria-labelledby="editClientModalLabel" aria-hidden="true">

                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editClientModalLabel">Edit Client</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editClientForm" method="POST" action="{{ route('/home/client/edit') }}">
                                @csrf
                                <input type="hidden" id="clientId" name="clientId">
                                <div class="form-group">
                                    <label for="clientName">Name</label>
                                    <input type="text" class="form-control" id="clientName" name="clientName">
                                </div>
                                <div class="form-group">
                                    <label for="clientEmail">Email</label>
                                    <input type="email" class="form-control" id="clientEmail" name="clientEmail">
                                </div>
                                <div class="form-group">
                                    <label for="clientStatus">Status</label>
                                    <select class="form-control" id="clientStatus" name="clientStatus">
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" id="saveClientChanges">Save
                                        Changes</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        <!-- /Page Content -->
        
        <!-- Delete Estimate Modal -->
        <div class="modal fade" id="deleteClientModal" tabindex="-1" role="dialog"
    aria-labelledby="editClientModalLabel" aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteClientModalLabel">Edit Client</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editClientForm" method="POST" action="{{ route('/home/client/delete') }}">
                    @csrf
                    <input type="hidden" id="clientId" name="clientId">
                   
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="saveClientChanges">Delete
                            Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
        <!-- /Delete Estimate Modal -->
    
    </div>
    <!-- /Page Wrapper -->
 
    @section('script')
         {{-- delete model --}}
         <script>
             $(document).ready(function () {
        // Edit client button click event
        $('.edit-client').click(function () {
            var clientId = $(this).data('clientid');
            var clientName = $(this).data('clientname');
            var clientEmail = $(this).data('clientemail');
            var clientStatus = $(this).data('clientstatus');

            $('#clientId').val(clientId);
            $('#clientName').val(clientName);
            $('#clientEmail').val(clientEmail);
            $('#clientStatus').val(clientStatus);
        });
        $('.delete-client').click(function () {
            var clientId = $(this).data('clientid');
            console.log(clientId);
            $('#clientId').val(clientId);
            $('#deleteClientModal').modal('show');
        });
        
    });
    
        </script>
    @endsection
@endsection
