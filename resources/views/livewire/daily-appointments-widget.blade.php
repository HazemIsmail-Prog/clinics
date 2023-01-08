<div>
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div
            class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary w-50">
                <i class="far fa-calendar-alt"></i>
                 Appointments</h6>
            <div wire:loading>
                <h6 class="m-0 font-weight-bold text-primary"><div class="spinner-border small"></div></h6>
            </div>
            <input wire:model="current_date" type="date" class="form-control text-center w-50">
        </div>
        <!-- Card Body -->
        <div class="card-body">

            <div class="card mb-2">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary w-50">

                    Statuses
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-borderless table-sm mb-0">
                        @forelse($statuses as $status)
                            <tr>
                                <td>{{$status->status->name}}</td>
                                <td>{{$status->total}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">No appointments for selected date</td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </div>


            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary w-50">

                        Devices
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-borderless table-sm mb-0">
                        @forelse($devices as $device)
                            <tr>
                                <td>{{$device->app_device->name}}</td>
                                <td>{{$device->total}}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">No appointments for selected date</td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </div>



        </div>
    </div>
</div>
