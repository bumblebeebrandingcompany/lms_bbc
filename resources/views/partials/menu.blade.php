<aside class="main-sidebar sidebar-dark-primary elevation-4" style="min-height: 917px;">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">
            {{config('app.name', 'LMS')}}
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @if(auth()->user()->is_superadmin)
                    <li>
                        <select class="searchable-field form-control">

                        </select>
                    </li>
                    <li class="mt-2 mb-2">
                        <select class="form-control mt-2" multiple name="__global_clients_filter" id="__global_clients_filter">
                            @foreach($__global_clients_drpdwn as $id => $name)
                                <option value="{{$id}}"
                                    @if(!empty($__global_clients_filter) && in_array($id, $__global_clients_filter))
                                        selected
                                    @endif>
                                    {{$name}}
                                </option>
                            @endforeach
                        </select>
                    </li>
                @endif
                @if(!auth()->user()->is_channel_partner)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs("admin.home") ? "active" : "" }}" href="{{ route("admin.home") }}">
                            <i class="fas fa-fw fa-tachometer-alt nav-icon">
                            </i>
                            <p>
                                {{ trans('global.dashboard') }}
                            </p>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->is_superadmin)
                    <li class="nav-item has-treeview {{ request()->is("admin/permissions*") ? "menu-open" : "" }} {{ request()->is("admin/roles*") ? "menu-open" : "" }} {{ request()->is("admin/users*") ? "menu-open" : "" }} {{ request()->is("admin/audit-logs*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/permissions*") ? "active" : "" }} {{ request()->is("admin/roles*") ? "active" : "" }} {{ request()->is("admin/users*") ? "active" : "" }} {{ request()->is("admin/audit-logs*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon fas fa-users">

                            </i>
                            <p>
                                {{ trans('cruds.userManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route("admin.users.index") }}" class="nav-link {{ request()->is("admin/users") || request()->is("admin/users/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-user">

                                    </i>
                                    <p>
                                        {{ trans('cruds.user.title') }}
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route("admin.audit-logs.index") }}" class="nav-link {{ request()->is("admin/audit-logs") || request()->is("admin/audit-logs/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-file-alt">

                                    </i>
                                    <p>
                                        {{ trans('cruds.auditLog.title') }}
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item has-treeview {{ request()->is("admin/clients*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/clients*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon fas fa-briefcase">

                            </i>
                            <p>
                                {{ trans('cruds.clientManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route("admin.clients.index") }}" class="nav-link {{ request()->is("admin/clients") || request()->is("admin/clients/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-briefcase">

                                    </i>
                                    <p>
                                        {{ trans('cruds.client.title') }}
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item has-treeview {{ request()->is("admin/agencies*") ? "menu-open" : "" }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is("admin/agencies*") ? "active" : "" }}" href="#">
                            <i class="fa-fw nav-icon fas fa-tv">

                            </i>
                            <p>
                                {{ trans('cruds.agencyManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route("admin.agencies.index") }}" class="nav-link {{ request()->is("admin/agencies") || request()->is("admin/agencies/*") ? "active" : "" }}">
                                    <i class="fa-fw nav-icon fas fa-tv">

                                    </i>
                                    <p>
                                        {{ trans('cruds.agency.title') }}
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if(!(auth()->user()->is_channel_partner || auth()->user()->is_agency))
                    <li class="nav-item">
                        <a href="{{ route("admin.projects.index") }}" class="nav-link {{ request()->is("admin/projects") || request()->is("admin/projects/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-project-diagram">

                            </i>
                            <p>
                                {{ trans('cruds.project.title') }}
                            </p>
                        </a>
                    </li>
                @endif
                @if(!auth()->user()->is_channel_partner)
                    <li class="nav-item">
                        <a href="{{ route("admin.campaigns.index") }}" class="nav-link {{ request()->is("admin/campaigns") || request()->is("admin/campaigns/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-bullhorn">

                            </i>
                            <p>
                                {{ trans('cruds.campaign.title') }}
                            </p>
                        </a>
                    </li>
                @endif
                @if(auth()->user()->is_superadmin)
                    <li class="nav-item">
                        <a href="{{ route("admin.sources.index") }}" class="nav-link {{ request()->is("admin/sources") || request()->is("admin/sources/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fas fa-external-link-alt"></i>
                            <p>
                                {{ trans('cruds.source.title') }}
                            </p>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route("admin.leads.index") }}" class="nav-link {{ request()->is("admin/leads") || request()->is("admin/leads/*") ? "active" : "" }}">
                        <i class="fa-fw nav-icon fas fa-handshake">

                        </i>
                        <p>
                            {{ trans('cruds.lead.title') }}
                        </p>
                    </a>
                </li>
                @if(!auth()->user()->is_channel_partner)
                    <li class="nav-item">
                        <a href="{{ route("admin.systemCalendar") }}" class="nav-link {{ request()->is("admin/system-calendar") || request()->is("admin/system-calendar/*") ? "active" : "" }}">
                            <i class="fas fa-fw fa-calendar nav-icon">

                            </i>
                            <p>
                                {{ trans('global.systemCalendar') }}
                            </p>
                        </a>
                    </li>
                @endif
                @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'active' : '' }}" href="{{ route('profile.password.edit') }}">
                            <i class="fas fa-user nav-icon"></i>
                            <p>
                                {{ trans('messages.profile') }}
                            </p>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <p>
                            <i class="fas fa-fw fa-sign-out-alt nav-icon">

                            </i>
                            <p>{{ trans('global.logout') }}</p>
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>