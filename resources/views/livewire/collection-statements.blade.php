<div>
    <div class="bg-light border-primary border" wire:loading style="position: fixed;top: 70px;right: 10px;padding: 10px 20px;border-radius: 10px;z-index: 1000;">
        <h6 class="m-0 font-weight-bold text-primary"><div class="spinner-border small"></div> Loading ...</h6>
    </div>
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Collection Statements</h6>
            <div class="d-flex flex-row noprint">
                <select wire:model="main_filter" class="form-control custom-select noprint">
                    <option value="users">Users Wise</option>
                    <option value="users_doctors">Users Doctors Wise</option>
                    <option value="departments">Departments Wise</option>
                    <option value="doctors">Doctors Wise</option>
                    <option value="treatments">Treatment Wise</option>
                    <option value="nurses">Nurses Wise</option>
                    <option value="times">Time Wise</option>
                    <option value="revenues">Revenue Wise</option>
                </select>

                <select wire:model="view_filter" class="form-control custom-select noprint">
                    <option value="detailed">Detailed View</option>
                    <option value="daily">Daily View</option>
                    <option value="monthly">Monthly View</option>
                </select>
            </div>


        </div>
        <!-- Card Body -->
        <div class="card-body">

            <div class="row noprint">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="text-primary" for="start_date">Start Date</label>
                        <input wire:model.debounce.2s="start_date" class="form-control" type="date" id="start_date">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="text-primary" for="end_date">End Date</label>
                        <input wire:model="end_date" class="form-control" type="date" id="end_date">
                    </div>
                </div>

                @if($main_filter == 'users' || $main_filter == 'users_doctors')

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="text-primary" for="users">Users</label>
                            <select wire:model="user_filter" class="form-control custom-select" id="users" multiple>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                @endif


                @if($main_filter == 'departments')

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="text-primary" for="departments">Departments</label>
                            <select wire:model="department_filter" class="form-control custom-select" id="departments"
                                    multiple>
                                @foreach($departments as $department)
                                    <option value="{{$department->id}}">{{$department->name}}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                @endif


                @if($main_filter == 'doctors' || $main_filter == 'users_doctors')

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="text-primary" for="doctors">Doctors</label>
                            <select wire:model="doctor_filter" class="form-control custom-select" id="doctors" multiple>
                                @foreach($doctors as $doctor)
                                    <option value="{{$doctor->id}}">{{$doctor->name}}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                @endif


                @if($main_filter == 'nurses')

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="text-primary" for="nurses">Nurses</label>
                            <select wire:model="nurse_filter" class="form-control custom-select" id="nurses" multiple>
                                @foreach($nurses as $nurse)
                                    <option value="{{$nurse->id}}">{{$nurse->name}}</option>
                                @endforeach

                            </select>
                        </div>
                    </div>

                @endif



                @if($main_filter == 'times')

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="text-primary" for="start_time">Start Time</label>
                            <input class="form-control" type="date" id="start_time">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="text-primary" for="end_time">End Time</label>
                            <input class="form-control" type="date" id="end_time">
                        </div>
                    </div>

                @endif

            </div>
        </div>
        <div class="card-footer text-center">
            <a class="btn btn-sm text-primary" target="_blank" href="{{route('collection_statements.show',[
                'filter'=>$filter,
                'start_date'=>$start_date,
                'end_date'=>$end_date,
                'main_filter' => $main_filter,
                'view_filter' => $view_filter,
                ])}}"
            >
                View Report
            </a>
        </div>
    </div>
</div>
