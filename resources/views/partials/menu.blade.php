<aside class="main-sidebar sidebar-dark-primary " style="min-height: 917px; overflow-y: auto;">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">
            {{ config('app.name', 'LMS') }}
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @if (auth()->user()->is_superadmin)
                    <li>
                        <select class="searchable-field form-control">

                        </select>
                    </li>
                    <li class="mt-2 mb-2">
                        <select class="form-control mt-2" multiple name="__global_clients_filter"
                            id="__global_clients_filter">
                            @foreach ($__global_clients_drpdwn as $id => $name)
                                <option value="{{ $id }}" @if (!empty($__global_clients_filter) && in_array($id, $__global_clients_filter)) selected @endif>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </li>
                @endif
                @if (!(auth()->user()->is_channel_partner || auth()->user()->is_channel_partner_manager))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}"
                            href="{{ route('admin.home') }}">
                            <i class="fas fa-fw fa-tachometer-alt nav-icon">
                            </i>
                            <p>
                                {{ trans('global.dashboard') }}
                            </p>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->is_admissionteam || auth()->user()->is_superadmin || auth()->user()->is_client)
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.admission.index') ? 'active' : '' }}"
                            href="{{ route('admin.admission.index') }}">
                            <i class="far fa-newspaper nav-icon"></i>
                            </i>
                            <p>
                                Admission followup
                            </p>
                        </a>
                    </li>
                @endif
                {{-- @if (auth()->user()->is_admissionteam || auth()->user()->is_superadmin)
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}"
                    href="{{ route('admin.home') }}">
                    <i class="far fa-newspaper nav-icon"></i>
                    </i>
                    <p>
                       Admission SiteVisit
                    </p>
                </a>
            </li>
        @endif --}}
                @if (auth()->user()->is_superadmin || auth()->user()->is_client)
                    <!-- <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.webhook.incoming.list') ? 'active' : '' }}" href="{{ route('admin.webhook.incoming.list') }}">
                            <i class="fas fa-satellite-dish nav-icon fa-fw"></i>
                            <p>
                                {{ trans('messages.webhook') }}
                            </p>
                        </a>
                    </li> -->
                    <li
                        class="nav-item has-treeview {{ request()->is('admin/documents*') ? 'menu-open' : '' }} {{ request()->routeIs('admin.get.documents.log') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/documents*') ? 'active' : '' }} {{ request()->routeIs('admin.get.documents.log') ? 'active' : '' }}"
                            href="#">
                            <i class=" fa-fw nav-icon fas fa-folder-open"></i>
                            <p>
                                {{ trans('messages.documents_management') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon ml-3"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.documents.*') ? 'active' : '' }}"
                                    href="{{ route('admin.documents.index') }}">
                                    <i class="fas fa-question-circle nav-icon fa-fw"></i>
                                    <p>
                                        {{ trans('messages.documents') }}
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('admin.get.documents.log') ? 'active' : '' }}"
                                    href="{{ route('admin.get.documents.log') }}">
                                    <i class="fas fa-history nav-icon fa-fw"></i>
                                    <p>
                                        {{ trans('messages.document_logs') }}
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (auth()->user()->is_superadmin || auth()->user()->is_channel_partner_manager || auth()->user()->is_client)
                    <li
                        class="nav-item has-treeview {{ request()->is('admin/permissions*') ? 'menu-open' : '' }} {{ request()->is('admin/roles*') ? 'menu-open' : '' }} {{ request()->is('admin/users*') ? 'menu-open' : '' }} {{ request()->is('admin/audit-logs*') ? 'menu-open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/permissions*') ? 'active' : '' }} {{ request()->is('admin/roles*') ? 'active' : '' }} {{ request()->is('admin/users*') ? 'active' : '' }} {{ request()->is('admin/audit-logs*') ? 'active' : '' }}"
                            href="#">
                            <i class="fa-fw nav-icon fas fa-users">

                            </i>
                            <p>
                                {{ trans('cruds.userManagement.title') }}
                                <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.users.index') }}"
                                    class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                                    <i class="fa-fw nav-icon fas fa-user">

                                    </i>
                                    <p>
                                        {{ trans('cruds.user.title') }}
                                    </p>
                                </a>
                            </li>
                            @if (auth()->user()->is_superadmin || auth()->user()->is_client)
                                <li class="nav-item">
                                    <a href="{{ route('admin.audit-logs.index') }}"
                                        class="nav-link {{ request()->is('admin/audit-logs') || request()->is('admin/audit-logs/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-file-alt">
                                        </i>
                                        <p>
                                            {{ trans('cruds.auditLog.title') }}
                                        </p>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                    @if (auth()->user()->is_superadmin || auth()->user()->is_client)
                        <li class="nav-item has-treeview {{ request()->is('admin/clients*') ? 'menu-open' : '' }}">
                            <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/clients*') ? 'active' : '' }}"
                                href="#">
                                <i class="fa-fw nav-icon fas fa-briefcase">
                                </i>
                                <p>
                                    {{ trans('cruds.clientManagement.title') }}
                                    <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.clients.index') }}"
                                        class="nav-link {{ request()->is('admin/clients') || request()->is('admin/clients/*') ? 'active' : '' }}">
                                        <i class="fa-fw nav-icon fas fa-briefcase">

                                        </i>
                                        <p>
                                            {{ trans('cruds.client.title') }}
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item has-treeview {{ request()->is('admin/agencies*') ? 'menu-open' : '' }}">
                            <a class="nav-link nav-dropdown-toggle {{ request()->is('admin/agencies*') ? 'active' : '' }}"
                                href="#">
                                <i class="fa-fw nav-icon fas fa-tv">

                                </i>
                                <p>
                                    {{ trans('cruds.agencyManagement.title') }}
                                    <i class="right fa fa-fw fa-angle-left nav-icon"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.agencies.index') }}"
                                        class="nav-link {{ request()->is('admin/agencies') || request()->is('admin/agencies/*') ? 'active' : '' }}">
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
                @endif
                @if (auth()->user()->is_superadmin || auth()->user()->is_client)
                    <li class="nav-item">
                        <a href="{{ route('admin.projects.index') }}"
                            class="nav-link {{ request()->is('admin/projects') || request()->is('admin/projects/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-project-diagram">

                            </i>
                            <p>
                                {{ trans('cruds.project.title') }}
                            </p>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->is_superadmin || auth()->user()->is_presales || auth()->user()->is_client)
                    <li class="nav-item">
                        <a href="{{ route('admin.followups.index') }}"
                            class="nav-link {{ request()->is('admin/followups') || request()->is('admin/followups/*') ? 'active' : '' }}">
                            <i class="far fa-newspaper nav-icon"></i>
                            <p>Lead FollowUp</p>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->is_superadmin ||
                        auth()->user()->is_presales ||
                        auth()->user()->is_frontoffice ||
                        auth()->user()->is_client)
                    <li class="nav-item">
                        <a href="{{ route('admin.sitevisit.index') }}"
                            class="nav-link {{ request()->is('admin/sitevisit') || request()->is('admin/sitevisit/*') ? 'active' : '' }}">
                            <i class="fas fa-map-marker-alt nav-icon">

                            </i>
                            <p>
                                Site Visit
                            </p>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->is_superadmin || auth()->user()->is_client)
                    <li class="nav-item">
                        <a href="{{ route('admin.callog.index') }}"
                            class="nav-link {{ request()->is('admin/callog') || request()->is('admin/callog/*') ? 'active' : '' }}">
                            <i class="fas fa-phone fa-flip-horizontal nav-icon"></i>

                            </i>
                            <p>
                                Lead Call log
                            </p>
                        </a>
                    </li>
                @endif
                {{-- @if (!(auth()->user()->is_channel_partner || auth()->user()->is_agency || auth()->user()->is_channel_partner_manager))
                    <li class="nav-item">
                        <a href="{{ route("admin.stages.index") }}" class="nav-link {{ request()->is("admin/stages") || request()->is("admin/stages/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon fasfa-project-diagram">


                            </i>
                            <p>
                                {{ trans('cruds.stages.title') }}
                            </p>
                        </a>
                    </li>
                @endif --}}
                @if (auth()->user()->is_superadmin || auth()->user()->is_client)
                    <li class="nav-item">
                        <a href="{{ route('admin.campaigns.index') }}"
                            class="nav-link {{ request()->is('admin/campaigns') || request()->is('admin/campaigns/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-bullhorn">

                            </i>
                            <p>
                                {{ trans('cruds.campaign.title') }}
                            </p>
                        </a>
                    </li>
                @endif
                {{-- @if (auth()->user()->is_superadmin)
                    <li class="nav-item">
                        <a href="{{ route("admin.sources.index") }}" class="nav-link {{ request()->is("admin/sources") || request()->is("admin/sources/*") ? "active" : "" }}">
                            <i class="fa-fw nav-icon far fa-dot-circle"></i>
                            <p>
                                {{ trans('cruds.field.title') }}
                            </p>
                        </a>
                    </li>
                @endif --}}
                @if (auth()->user()->is_superadmin || auth()->user()->is_client)
                    <li class="nav-item">
                        <a href="{{ route('admin.sources.index') }}"
                            class="nav-link {{ request()->is('admin/sources') || request()->is('admin/sources/*') ? 'active' : '' }}">
                            <i class="fa-fw nav-icon fas fa-external-link-alt"></i>
                            <p>
                                {{ trans('cruds.source.title') }}
                            </p>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->is_superadmin || auth()->user()->is_client)
                <li class="nav-item">
                    <a href="{{ route('admin.promo.index') }}"
                        class="nav-link {{ request()->is('admin/promo') || request()->is('admin/promo/*') ? 'active' : '' }}">
                        <i class="fa-fw nav-icon fas fa-external-link-alt"></i>
                        <p>
                           Promo
                        </p>
                    </a>
                </li>
            @endif
                @if (auth()->user()->is_superadmin || auth()->user()->is_client)
                    <li class="nav-item">
                        <a href="{{ route('admin.parent-stages.index') }}"
                            class="nav-link {{ request()->is('admin/parent-stages') || request()->is('admin/parent-stages/*') ? 'active' : '' }}">
                            <i class="far fa-dot-circle nav-icon "></i>
                            <p>
                                {{ trans('cruds.stages.title') }}
                            </p>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->is_superadmin || auth()->user()->is_client)
                    <li class="nav-item">
                        <a href="{{ route('admin.notenotinterested.index') }}"
                            class="nav-link {{ request()->is('admin/notenotinterested') || request()->is('admin/notenotinterested/*') ? 'active' : '' }}">
                            <i class="far fa-sticky-note nav-icon"></i>
                            <p>
                                Note for Not Interested </p>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('admin.leads.index', ['view' => 'kanban']) }}" id="lead_menu_link"
                        class="nav-link {{ request()->is('admin/leads') || request()->is('admin/leads/*') ? 'active' : '' }}">
                        <i class="fa-fw nav-icon fas fa-handshake">
                        </i>
                        <p>
                            {{ trans('cruds.lead.title') }}
                        </p>
                    </a>
                </li>

                @if (auth()->user()->is_client ||
                        auth()->user()->is_frontoffice ||
                        auth()->user()->is_superadmin ||
                        auth()->user()->is_client)
                    <li class="nav-item">
                        <a href="{{ route('admin.walkinform.index') }}"
                            class="nav-link {{ request()->is('admin/walkinform') || request()->is('admin/walkinform/*') ? 'active' : '' }}">
                            {{-- <i class='fas fa-walking' style='font-size:20px'></i> --}}
                            <i class="fa-fw nav-icon fas fa-walking" style='font-size:22px'>
                            </i>
                            <p>
                                Walkin Form
                            </p>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->is_superadmin)
                    <li class="nav-item">
                        <a href="{{ route('admin.systemCalendar') }}"
                            class="nav-link {{ request()->is('admin/system-calendar') || request()->is('admin/system-calendar/*') ? 'active' : '' }}">
                            <i class="fas fa-fw fa-calendar nav-icon">
                            </i>
                            <p>
                                {{ trans('global.systemCalendar') }}
                            </p>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->is_superadmin || auth()->user()->is_frontoffice || auth()->user()->is_client)
                    <li class="nav-item">
                        <a href="{{ route('admin.applications.index') }}"
                            class="nav-link {{ request()->is('admin/applications') || request()->is('admin/applications/*') ? 'active' : '' }}">
                            <i class="	fas fa-receipt nav-icon"></i>
                            </i>
                            <p>
                                Application Purchased
                            </p>
                        </a>
                    </li>
                @endif
                @if (auth()->user()->is_superadmin || auth()->user()->is_admissionteam )
                <li class="nav-item">
                    <a href="{{ route('admin.application.index') }}"
                        class="nav-link {{ request()->is('admin/application') || request()->is('admin/application/*') ? 'active' : '' }}">
                        <i class="	fas fa-receipt nav-icon"></i>
                        </i>
                        <p>
                            App not Purchased
                        </p>
                    </a>
                </li>
            @endif
                @if (auth()->user()->is_admissionteam || auth()->user()->is_superadmin || auth()->user()->is_client)
                    <li class="nav-item">
                        <a href="{{ route('admin.admitted.index') }}"
                            class="nav-link {{ request()->is('admin/admitted') || request()->is('admin/admitted/*') ? 'active' : '' }}">
                            <i class="fas fa fa-check nav-icon"></i>
                            </i>
                            <p>
                                Admitted
                            </p>
                        </a>
                    </li>
                @endif
                @if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'active' : '' }}"
                            href="{{ route('profile.password.edit') }}">
                            <i class="fas fa-user nav-icon"></i>
                            <p>
                                {{ trans('messages.profile') }}
                            </p>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="#" class="nav-link"
                        onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
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
