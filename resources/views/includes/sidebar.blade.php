<!-- Sidebar -->
<ul class="noprint navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
{{--    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">--}}
{{--        <div class="sidebar-brand-text mx-3">Clinic Management System</div>--}}
{{--    </a>--}}

<!-- Divider -->
{{--    <hr class="sidebar-divider my-0">--}}

<!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
        <a class="nav-link" href="{{route('dashboard.index')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

@can('Menus_Reception')

    <!-- Heading -->
        <div class="sidebar-heading">
            Reception
        </div>

        @can('Appointments_Access')
            <li class="nav-item {{ request()->is('appointments') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('appointments.index')}}">
                    <i class="far fa-calendar-alt"></i>
                    <span>Appointments</span></a>
            </li>
        @endcan

        @can('Patients_Access')
            <li class="nav-item {{ request()->is('patients*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('patients.index')}}">
                    <i class="fas fa-users"></i>
                    <span>Patients</span></a>
            </li>
        @endcan

        @can('Invoices_Access')
            <li class="nav-item {{ request()->is('invoices*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('invoices.index')}}">
                    <i class="fas fa-file-invoice"></i>
                    <span>Billing History</span></a>
            </li>
        @endcan

        @can('Balances_Access')
            <li class="nav-item {{ request()->is('balances*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('balances.index')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Balance History</span></a>
            </li>
        @endcan

        @can('Reports_DayClosing')
            <li class="nav-item {{ request()->is('day_closing*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('day_closing.index')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Day Closing</span></a>
            </li>
        @endcan

    <!-- Divider -->
        <hr class="sidebar-divider">

    @endcan

    @can('Menus_Reports')

    <!-- Heading -->
        <div class="sidebar-heading">
            Reports
        </div>

        @can('Reports_DailyIncome')
            <li class="nav-item {{ request()->is('daily_income*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('daily_income.index')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Daily Income</span></a>
            </li>
        @endcan

        @can('Reports_Appointments')
            <li class="nav-item {{ request()->is('appointments/r') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('appointments.reports_index')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Appointments</span></a>
            </li>
        @endcan

        @can('Reports_CollectionStatements')
            <li class="nav-item {{ request()->is('collection_statements*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('collection_statements.index')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Collection Statements</span></a>
            </li>
        @endcan

    <!-- Divider -->
        <hr class="sidebar-divider">
    @endcan

    @can('Menus_Accounts')

    <!-- Heading -->
        <div class="sidebar-heading">
            Accounts
        </div>

        @can('JournalVouchers_Access')
            <li class="nav-item {{ request()->is('jvs*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('jvs.index')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Journal Vouchers</span></a>
            </li>
        @endcan

        @can('BankPayments_Access')
            <li class="nav-item {{ request()->is('bps*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('bps.index')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Bank Payments</span></a>
            </li>
        @endcan

        @can('BankReceipts_Access')
            <li class="nav-item {{ request()->is('brs*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('brs.index')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Bank Receipts</span></a>
            </li>
        @endcan


        @can('Reports_Accounts')
        <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item {{ request()->is('account_statement*') || request()->is('trial_balance*') || request()->is('bank_book*') || request()->is('balance_sheet*') || request()->is('profit_loss*') ? 'active' : '' }}">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#accounts"
                   aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Reports</span>
                </a>
                <div id="accounts"
                     class="collapse {{ request()->is('account_statement*') || request()->is('trial_balance*') || request()->is('bank_book*') || request()->is('balance_sheet*') || request()->is('profit_loss*') ? 'show' : '' }}"
                     aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item {{request()->is('account_statement*') ? 'active' : '' }}"
                           href="{{route('account_statement.index')}}">Account Statement</a>
                        <a class="collapse-item {{request()->is('trial_balance*') ? 'active' : '' }}"
                           href="{{route('trial_balance.index')}}">Trial Balance</a>
                        <a class="collapse-item {{request()->is('bank_book*') ? 'active' : '' }}"
                           href="{{route('bank_book.index')}}">Bank Book</a>
                        <a class="collapse-item {{request()->is('balance_sheet*') ? 'active' : '' }}"
                           href="{{route('balance_sheet.index')}}">Balance Sheet</a>
                        <a class="collapse-item {{request()->is('profit_loss*') ? 'active' : '' }}"
                           href="{{route('profit_loss.index')}}">Profit & Loss</a>
                    </div>
                </div>
            </li>

        @endcan


    <!-- Divider -->
        <hr class="sidebar-divider">

    @endcan

    @can('Menus_Settings')
    <!-- Heading -->
        <div class="sidebar-heading">
            Settings
        </div>

        @can('AccountGroups_Access')
            @if (auth()->id() == 1)
                <li class="nav-item {{ request()->is('account_groups*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{route('account_groups.index')}}">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Account Group</span></a>
                </li>
            @endif
        @endcan

        @can('Clinics_Access')
            <li class="nav-item {{ request()->is('clinics*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('clinics.index')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Clinics</span></a>
            </li>
        @endcan

        @can('Users_Access')
            <li class="nav-item {{ request()->is('users*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('users.index')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Users</span></a>
            </li>
        @endcan

        @can('Offers_Access')
            <li class="nav-item {{ request()->is('offers*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('offers.index')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Offers</span></a>
            </li>
        @endcan

        @can('Departments_Access')
            <li class="nav-item {{ request()->is('departments*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('departments.index')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Departments</span></a>
            </li>
        @endcan

        @can('Doctors_Access')
            <li class="nav-item {{ request()->is('doctors*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('doctors.index')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Doctors</span></a>
            </li>
        @endcan

        @can('Nurses_Access')
            <li class="nav-item {{ request()->is('nurses*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('nurses.index')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Nurses</span></a>
            </li>
        @endcan

        @can('Treatments_Access')
            <li class="nav-item {{ request()->is('treatments*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('treatments.index')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Treatments</span></a>
            </li>
        @endcan

        @can('Nationalities_Access')

            <li class="nav-item {{ request()->is('nationalities*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('nationalities.index')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Nationalities</span></a>
            </li>
        @endcan

        @can('AppDepartments_Access')
            <li class="nav-item {{ request()->is('app_departments*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('app_departments.index')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Appointment Departments</span></a>
            </li>
        @endcan

        @can('AppDevices_Access')
            <li class="nav-item {{ request()->is('app_devices*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('app_devices.index')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Appointment Devices</span></a>
            </li>
        @endcan

        @can('AppStatuses_Access')
            <li class="nav-item {{ request()->is('app_statuses*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('app_statuses.index')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Appointment Status</span></a>
            </li>
        @endcan

        @can('Constants_Access')
            <li class="nav-item">
                <a class="nav-link" href="index.html">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Mode of Payment</span></a>
            </li>
        @endcan

        @can('Accounts_Access')
            <li class="nav-item {{ request()->is('accounts*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('accounts.index')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Accounts</span></a>
            </li>
        @endcan

        @if(auth()->id() == 1)
            <li class="nav-item {{ request()->is('close_year*') ? 'active' : '' }}">
                <a class="nav-link" href="{{route('year_close.index')}}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Year Close</span></a>
            </li>
    @endif


    <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

@endcan

<!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


</ul>
<!-- End of Sidebar -->
