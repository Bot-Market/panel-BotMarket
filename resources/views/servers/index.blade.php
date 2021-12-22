@extends('layouts.main')

@section('content')
    <!-- CONTENT HEADER -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ __('Servers') }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a class="text-muted"
                                href="{{ route('servers.index') }}">{{ __('Servers') }}</a>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <!-- END CONTENT HEADER -->

    <!-- MAIN CONTENT -->
    <section class="content">
        <div class="container-fluid">

            <!-- CUSTOM CONTENT -->
            <div class="d-flex justify-content-end mb-3">
                <a @if (Auth::user()->Servers->count() >= Auth::user()->server_limit)
                    disabled="disabled" title="Server limit reached!"
                    @endif href="{{ route('servers.create') }}"
                    class="btn
                    @if (Auth::user()->Servers->count() >= Auth::user()->server_limit) disabled
                    @endif btn-primary"><i
                        class="fa fa-plus mr-2"></i>
                    {{ __('Create Server') }}
                </a>
            </div>

            <div class="row">
                @foreach ($servers as $server)

                    <div class="col-xl-3 col-lg-5 col-md-6 col-sm-6 col-xs-12 card pr-0 pl-0" style="max-width: 350px">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mt-1">{{ $server->name }}
                                </h5>
                                <div class="card-tools mt-1">
                                    <div class="dropdown no-arrow">
                                        <a href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-white-50"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                            aria-labelledby="dropdownMenuLink">
                                            @if (!empty(env('PHPMYADMIN_URL')))
                                                <a href="{{ env('PHPMYADMIN_URL', 'http://localhost') }}"
                                                    class="dropdown-item text-info" target="__blank"><i title="manage"
                                                        class="fas fa-database mr-2"></i><span>{{ __('Database') }}</span></a>
                                            @endif
                                            <div class="dropdown-divider"></div>
                                            <span class="dropdown-item"><i title="Created at"
                                                    class="fas fa-sync-alt mr-2"></i><span>{{ $server->created_at->isoFormat('LL') }}</span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="container mt-1">
                                <div class="row mb-3">
                                    <div class="col my-auto">{{ __('Status') }}:</div>
                                    <div class="col-7 my-auto">
                                        <i
                                            class="fas {{ $server->isSuspended() ? 'text-danger' : 'text-success' }} fa-circle mr-2"></i>
                                        {{ $server->isSuspended() ? 'Suspended' : 'Active' }}
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-5">
                                        {{ __('Location') }}:
                                    </div>
                                    <div class="col-7">
                                        <span class="">{{ $server->location }}</span>
                                    </div>

                                </div>
                                <div class="row mb-2">
                                    <div class="col-5 ">
                                        {{ __('Software') }}:
                                    </div>
                                    <div class="col-7 text-wrap">
                                        <span>{{ $server->nest }}</span>
                                    </div>

                                </div>
                                <div class="row mb-2">
                                    <div class="col-5 ">
                                        {{ __('Specification') }}:
                                    </div>
                                    <div class="col-7 text-wrap">
                                        <span>{{ $server->egg }}</span>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-5 ">
                                        {{ __('Resource plan') }}:
                                    </div>
                                    <div class="col-7 text-wrap">
                                        <span>{{ $server->resourceplanName }}</span>
                                    </div>

                                </div>
                                <div class="row mb-2">
                                    <div class="col-4">
                                        {{ __('Price') }}:
                                        <span class="text-muted">
                                            ({{ CREDITS_DISPLAY_NAME }})
                                        </span>
                                    </div>
                                    <div class="col-8">
                                        <div class="row">
                                            <div class="col-6  text-center">
                                                <div class="text-muted">{{ __('per Hour') }}</div>
                                                <span>
                                                    {{ number_format($server->product->getHourlyPrice(), 2, '.', '') }}
                                                </span>
                                            </div>
                                            <div class="col-6  text-center">
                                                <div class="text-muted">{{ __('per Month') }}
                                                </div>
                                                <span>
                                                    {{ $server->product->getHourlyPrice() * 24 * 30 }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a href="{{ env('PTERODACTYL_URL', 'http://localhost') }}/server/{{ $server->identifier }}"
                                target="__blank"
                                class="btn btn-info mx-3 w-100 align-items-center justify-content-center d-flex">
                                <i class="fas fa-tools mr-2"></i>
                                <span>{{ __('Manage') }}</span>
                            </a>
                            <button onclick="handleServerDelete('{{ $server->id }}');" target="__blank"
                                class="btn btn-danger mx-3 w-100 align-items-center justify-content-center d-flex">
                                <i class="fas fa-trash mr-2"></i>
                                <span>{{ __('Delete') }}</span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- END CUSTOM CONTENT -->
        </div>
    </section>
    <!-- END CONTENT -->

    <script>
        const handleSubmit = () => {
            return confirm('Are you sure you want to delete this server?');
        }

        const handleServerDelete = (serverId) => {
            if (handleSubmit()) {
                fetch("{{ route('servers.destroy', '') }}" + '/' + serverId, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }).then(() => {
                    window.location.reload();
                });
            }
        }
    </script>
@endsection
