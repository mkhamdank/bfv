@extends('layouts.master')

@section('title', 'VFI')

@section('styles')
    <style>
        #vfi-container {
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
    <section id="vfi-container">
        <div class="row" style="margin: 1%">
            <div class="card">
                <div class="card-header mt-2">
                    <h3>Vendor Final Inspection</h3>
                </div>

                <div class="row mt-2 mb-2">
                    <div class="col-4">
                        <div class="header ms-2 mt-2">
                            <h4>
                                <i class="fas fa-cogs"></i>
                                Process
                            </h4>
                        </div>
                        <a href="#" class="btn btn-lg btn-outline-dark menu-btn">
                            <i class="fas fa-info-circle"></i>
                            <span class="menu-name">
                                Input VFI
                            </span>
                            <span class="auth-name">{{ Auth::user()->name }}</span>
                        </a>

                    </div>

                    <div class="col-4">
                        <div class="header ms-2 mt-2">
                            <h4>
                                <i class="fas fa-tv"></i>
                                Display
                            </h4>
                        </div>
                        <a href="#" class="btn btn-lg btn-outline-dark menu-btn">
                            <i class="fas fa-info-circle"></i>
                            <span class="menu-name">
                                Production NG RATE
                            </span>
                            <span class="auth-name">{{ Auth::user()->name }}</span>
                        </a>
                        <a href="#" class="btn btn-lg btn-outline-dark menu-btn">
                            <i class="fas fa-info-circle"></i>
                            <span class="menu-name">
                                Production Pareto
                            </span>
                            <span class="auth-name">{{ Auth::user()->name }}</span>
                        </a>

                    </div>

                    <div class="col-4">
                        <div class="header ms-2 mt-2">
                            <h4>
                                <i class="fas fa-file-alt"></i>
                                Report
                            </h4>
                        </div>
                        <a href="#" class="btn btn-lg btn-outline-dark menu-btn">
                            <i class="fas fa-copy"></i>
                                <span class="menu-name">
                                    Report Production Check
                                </span>
                            <span class="auth-name">{{ Auth::user()->name }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#side_vfi').addClass('menu-open');
        });

    </script>
@endsection
