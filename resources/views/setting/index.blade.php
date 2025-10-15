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
        <div class="row justify-content-center my-4">
            <div class="col-lg-10">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-gradient-primary text-white d-flex align-items-center" style="background: linear-gradient(90deg, #43cea2 0%, #185a9d 100%); border-radius: 8px 8px 0 0;">
                        <h3 class="mb-0"><i class="fa fa-cog me-2"></i>Setting Whatsapp</h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle mb-0" style="border-radius: 8px; overflow: hidden;">
                                <thead style="background: linear-gradient(90deg, #43cea2 0%, #185a9d 100%); color: #fff;">
                                    <tr>
                                        <th class="text-center" style="font-size: 18px; font-weight: 600;">Whatsapp No</th>
                                        <th class="text-center" style="font-size: 18px; font-weight: 600;">Info</th>
                                        <th class="text-center" style="font-size: 18px; font-weight: 600;">Status</th>
                                        <th class="text-center" style="font-size: 18px; font-weight: 600;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($whatsapp as $wa)
                                    <tr>
                                        <td class="text-center" style="font-size: 16px;">{{ $wa->values }}</td>
                                        <td class="text-center" style="font-size: 16px;">{{ $wa->information }}</td>
                                        <td class="text-center">
                                            @if ($wa->remark == 1)
                                                <span class="badge bg-success px-3 py-2" style="font-size: 15px; border-radius: 8px;">Active</span>
                                            @else
                                                <span class="badge bg-secondary px-3 py-2" style="font-size: 15px; border-radius: 8px;">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($wa->remark == 1)
                                                <button onclick="changeWhatsapp({{$wa->id}}, 0)" class="btn btn-danger btn-sm rounded-pill fw-bold">
                                                    <i class="fa fa-times me-1"></i>Deactivate
                                                </button>
                                            @else
                                                <button onclick="changeWhatsapp({{$wa->id}}, 1)" class="btn btn-success btn-sm rounded-pill fw-bold">
                                                    <i class="fa fa-check me-1"></i>Activate
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">No Whatsapp settings found.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
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
            
        });

        function changeWhatsapp(id, remark) {
            if (confirm('Apakah Anda yakin?')) {
                $('#loading').show();
                var data = {
                    _token: "{{ csrf_token() }}",
                    id: id,
                    remark: remark
                }

                $.post('{{route("admin.settings.changeWhatsapp")}}', data, function(result, status, xhr) {
                    if (result.status) {
                        $('#loading').hide();
                        location.reload();
                    } else {
                        $('#loading').hide();
                        openErrorGritter('Error!', result.message);
                        return false;
                    }
                });
            }
        }
        

    </script>
@endsection
