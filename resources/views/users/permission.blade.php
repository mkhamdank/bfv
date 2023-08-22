@extends('layouts.master')

@section('styles')    
        
@stop

@section('content')
<section id="permission-container">
    <div class="row" style="margin: 1%">
        <div class="card">
            <div class="card-header mt-2">
                <h3>Permission Controller</h3>
            </div>

            <div class="row mt-2 mb-2">
                <div class="header">
                    <div class="btn-group float-right">                            
                        {{-- <button class="btn btn-outline-primary"><i class="fas fa-sync-alt"></i> Refresh</button>                             --}}
                        <button class="btn btn-outline-success" data-toggle="modal" data-target="#default-modal" onclick="modalAddPermission()"><i class="fas fa-user-plus"></i> Add Permission</button>
                    </div>
                </div>
            </div>

            <div class="row mt-2 mb-2" style="padding: 2%">
                <table id="table-permission" class="table table-bordered table-hover">
                    <tr>
                        <th>id</th>
                        <th>permission</th>
                        <th>guard</th>                        
                        <th>remark</th>
                        <th>Actions</th>
                    </tr>
                    <tbody id="body-table-permission">
                        @php
                            $id = 1;
                        @endphp
                        @foreach ($permissions as $permission)
                        <tr>
                            <td>{{ $id++ }}</td>
                            <td>{{ $permission->name }}</td>
                            <td>{{ $permission->guard_name }}</td>
                            <td>{{ $permission->remark }}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-outline-danger"><i class="fas fa-trash"></i> Delete</button>
                                </div>
                            </td>                            
                        @endforeach
                    </tbody>
                </table>                    
            </div>
            
        </div>
    </div>    
    @include('../components/default-modal');

</section>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        
    });

    function modalAddPermission() {        
        
        $('#default-modal-label-title').html('');
        $('#default-modal-label-body').html('');
        $('#default-modal-label-footer').html('');


        $('#default-modal-label-title').html('Add Permission');
        let body = '';
        body += '<div class="form-group">';
        body += '<label for="permission">Permission</label>';
        body += '<input type="text" class="form-control" id="permission" placeholder="Enter Permission">';
        body += '</div>';        
        body += '<div class="form-group">';
        body += '<label for="remark">Remark</label>';        
        body += '<select class="form-control" id="remark">';
        body += '<option value="">Select Remark</option>';
        body += '<option value="mis">MIS</option>';
        body += '<option value="ympi">YMPI</option>';
        body += '<option value="vendor">Vendor</option>';
        body += '</select>';
        body += '</div>';        

        $('#default-modal-label-body').html(body);
        $('#default-modal-label-footer').html('<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button><button type="button" class="btn btn-outline-primary" onclick="addPermission()">Save</button>');        

    }

    function addPermission() {
        let permission = $('#permission').val();
        let remark = $('#remark').val();
        let data = {
            name: permission,
            remark: remark,
            _token: "{{ csrf_token() }}"
        }
        $.post("{{ route('admin.permission.store') }}", data, function(data) {
            if (data.status == 'success') {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);                
            }
        });
    }




</script>
@endsection


