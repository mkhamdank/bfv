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

        .menu-name {}

        .auth-name {
            font-weight: 600;
            color: #BA241C;
        }
    </style>
@stop

@section('content')
@section('content')
    <section id="user-container">
        <div class="row" style="margin: 1%">
            <div class="card">
                <div class="card-header mt-2">
                    <h3>Add new user</h3>
                </div>

                {{-- <div class="row mt-2 mb-2">
                    <div class="header">
                        <div class="btn-group float-right">                                                        
                        </div>
                    </div>
                </div> --}}

                <div class="row mt-2 mb-2" style="padding: 0">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-1" style="padding: 0">
                                <label for="username" class="col-form-label float-right"><i class="fas fa-user"></i></label>
                            </div>
                            <div class="col-11">
                                <input type="text" name="username" id="username" class="form-control" placeholder="Username">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-1" style="padding: 0">
                                {{-- <label for="name" class="col-form-label float-right"><i class="fas fa-user"></i></label> --}}
                            </div>
                            <div class="col-11">
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-1" style="padding: 0">
                                <label for="email" class="col-form-label float-right"><i class="fas fa-envelope"></i></label>
                            </div>
                            <div class="col-11">
                                <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-1" style="padding: 0">
                                <label for="password" class="col-form-label float-right"><i class="fas fa-key"></i></label>
                            </div>
                            <div class="col-11">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="row">
                            <div class="col-1" style="padding: 0">
                                <label for="permission" class="col-form-label float-right"><i class="fas fa-user-cog"></i></label>
                            </div>
                            <div class="col-11">
                                <div class="card" style="padding: 2%">
                                    <span>Select Permission</span>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="selectAllCheck">
                                        <label class="form-check-label" for="selectAllCheck">
                                            Select All
                                        </label>
                                    </div>
                                    <div id="permission">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="row mt-2 mb-2">
                    <div class="col-12">
                        <button class="btn btn-success float-right" id="btn-save-user">Save</button>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection


@section('scripts')

    <script>        

        $(document).ready(function() {

            getPermission();

            $('#selectAllCheck').click(function() {
                $('#permission input:checkbox').not(this).prop('checked', this.checked);
            });            

            $('#btn-save-user').click(function() {                
                var username = $('#username').val();
                var name = $('#name').val();
                var email = $('#email').val();
                var password = $('#password').val();
                var permission = [];                
            
                $('#permission input:checked').each(function() {                    
                    permission.push($(this).val());
                });                            

                if (username == '' || name == '' || email == '' || password == '' || permission.length == 0) {
                    alert('Please fill all fields');
                    console.log(permission);
                    return false;
                }
                
                $.post("{{ route('admin.user.store') }}", {
                    _token: "{{ csrf_token() }}",
                    username: username,
                    name: name,
                    email: email,
                    password: password,
                    permission: permission
                }, function(data) {
                    console.log(data);
                    if (data.status == 'success') {
                        window.location.href = "{{ route('admin.user.index') }}";
                    }
                });
                
            });
        });

        function getPermission() {
            $.ajax({
                url: "{{ route('admin.user.getPermissions') }}",
                type: 'GET',
                success: function(data) {                                    
                    var html = '';
                    $.each(data.permissions, function(key, value) {
                        html += '<div class="form-check">';
                        html += '<input class="form-check-input" type="checkbox" value="' + value.id + '" id="' + value.name + '">';
                        html += '<label class="form-check-label" for="' + value.name + '">';
                        html += value.name;
                        html += '</label>';
                        html += '</div>';
                    });
                    $('#permission').append(html);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }



    </script>

@endsection
