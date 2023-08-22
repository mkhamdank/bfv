@extends('layouts.master')

@section('title', 'Users')

@section('styles')
    <style>
        #user-container {
            color: #333333;
        }

        .menu-btn {
            width: 100%;
            margin: 1% 0;
        }

        .menu-name {

        }

        .auth-name {
            font-weight: 600;
            color: #BA241C;
        }

    </style>
@stop

@section('content')
    <section id="user-container">
        <div class="row" style="margin: 1%">
            <div class="card">
                <div class="card-header mt-2">
                    <h3>Users Controller</h3>
                </div>

                <div class="row mt-2 mb-2">
                    <div class="header">
                        <div class="btn-group float-right">                            
                            {{-- <button class="btn btn-outline-primary"><i class="fas fa-sync-alt"></i> Refresh</button>                             --}}
                            <a href="{{ route('admin.user.create') }}" class="btn btn-outline-success"><i class="fas fa-user-plus"></i> Add User</a>                            
                        </div>
                    </div>
                </div>

                <div class="row mt-2 mb-2" style="padding: 2%">
                    <table id="table-users" class="table table-bordered table-hover">
                        <tr>
                            <th>Username</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Permission</th>                                                        
                            <th>Actions</th>
                        </tr>
                        <tbody id="body-table-users">
                        </tbody>                            
                    </table>                    
                </div>
                
            </div>
        </div>    
    </section>
    {{-- @include('../components/default-modal'); --}}
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#side_users').addClass('menu-open');

            getUsers();
            // getPermissions();
        });

        const permissions = [];
        const users = [];

        function getUsers() {
            permissions.splice(0, permissions.length);
            $.get("{{ route('admin.user.getUsers') }}", function(data) {
                for (var i = 0; i < data.users.length; i++) {
                    users.push(data.users[i]);                    
                }
                renderUsers();
            });
        }

        function renderUsers(){            
            $('#body-table-users').empty();
            var html = '';
            for (var i = 0; i < users.length; i++) {
                html += '<tr>';
                html += '<td>' + users[i].username + '</td>';
                html += '<td>' + users[i].name + '</td>';
                html += '<td>' + users[i].email + '</td>';
                html += '<td>';
                for (var j = 0; j < users[i].permissions.length; j++) {
                    html += '<span class="badge badge-primary" style="margin:1px;">' + users[i].permissions[j].name + '</span>';
                }
                html += '</td>';
                html += '<td>';
                html += '<a href="{{ route('admin.user.edit', '') }}/' + users[i].username + '" class="btn btn-outline-primary btn-sm"><i class="fas fa-edit"></i></a>';
                html += '<button class="btn btn-outline-danger btn-sm" onclick="deleteUser(' + users[i].id + ')"><i class="fas fa-trash"></i></button>';
                html += '</td>';
                html += '</tr>';
            }

            $('#body-table-users').append(html);
        }

        function getPermissions() {
            $.get("{{ route('admin.user.getPermissions') }}", function(data) {                                
                try {
                    for (var i = 0; i < data.permissions.length; i++) {
                        permissions.push(data.permissions[i]);
                    }
                } catch (error) {
                    console.log(error);
                }                
            });
        }

        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                $.ajax({
                    url: "{{ route('admin.user.delete', '') }}/" + id,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function(data) {
                        if (data.success) {
                            alert(data.message);
                            getUsers();
                        } else {
                            alert(data.message);
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }            
        }
        

    </script>
@endsection
