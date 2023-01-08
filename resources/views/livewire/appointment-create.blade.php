<div class="card shadow">
    <div class="bg-light border-primary border" wire:loading
         style="position: fixed;top: 70px;right: 10px;padding: 10px 20px;border-radius: 10px;z-index: 1000;">
        <h6 class="m-0 font-weight-bold text-primary">
            <div class="spinner-border small"></div>
            Loading ...
        </h6>
    </div>
    <!-- Card Header - Dropdown -->
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">{{$action == 'create' ? 'New Appointment' : 'Edit Appointment'}}</h6>
        @if($action == 'edit')
            <div class="text-right">
                <div class="text-xs">Created at
                    <strong class="text-primary">{{date('d-m-Y h:i a',strtotime($created_at))}}</strong> by
                    <strong class="text-primary">{{$created_by}}</strong></div>
                <div class="text-xs">Updated at
                    <strong class="text-primary">{{date('d-m-Y h:i a',strtotime($updated_at))}}</strong> by
                    <strong class="text-primary">{{$updated_by}}</strong>
                </div>
            </div>
        @endif
    </div>
    <!-- Card Body -->
    <div class="card-body">


        <div class="row">
            <div class="col-md-6 align-self-end" style="position: relative">

                <div class="row">
                    <div class="col-6">
                        @if(($is_new && $action=='edit')|| $action=='create')
                            <div class="form-group">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input wire:click="new_patient" type="radio" id="new" name="is_new"
                                           {{$is_new == true ? 'checked' : ''}}
                                           class="custom-control-input">
                                    <label class="custom-control-label text-primary" for="new">New</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input wire:click="old_patient" type="radio" id="old" name="is_new"
                                           {{$is_new == false ? 'checked' : ''}}
                                           class="custom-control-input">
                                    <label class="custom-control-label text-primary" for="old">Has File</label>
                                </div>
                            </div>
                        @endif
                    </div>
                    @if(!$is_new)
                        <div class="col-6">
                            <input
                                wire:model="search"
                                type="text"
                                class="form-control mb-2"
                                placeholder="Patient Search ..."
                            >
                        </div>
                        @if(!empty($search))
                            <div class="border border-primary"
                                 style="
                             background: #ffffff;
                             top: 50px;
                             left: 0;
                             min-width: 100%;
                             padding: 10px;
                             position: absolute;
                             z-index: 5;
                             border-radius: 5px;"
                            >
                                <table class="table table-striped table-sm mb-0">
                                    <thead>
                                    <tr>
                                        <th class="text-center">File #</th>
                                        <th class="text-left">Name</th>
                                        <th class="text-center">Mobile</th>
                                        <th class="text-center">Civil ID</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($patients as $patient)
                                        <tr wire:click.prevent="select_patient({{$patient->id}})"
                                            style="cursor: pointer">
                                            <td class="text-center patient_file_no">{{$patient->file_no}}</td>
                                            <td class=" patient_name">{{$patient->name}}
                                                <span class="badge badge-pill badge-danger">{{$patient->status}}</span>
                                                @if($patient->balances->sum('amount') > 0)
                                                    <span class="blink_me badge badge-pill badge-danger">{{$patient->balances->sum('amount')}} K.D</span>
                                                @endif
                                            </td>
                                            <td class="text-center patient_mobile">{{$patient->mobile}}</td>
                                            <td class="text-center patient_civil_id">{{$patient->civil_id}}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">no record found</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endif
                </div>

                <div class="card mb-3" style="min-height: 361px">
                    <div class="card-header text-primary">Pateint Data</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="patient_file_no" class="text-primary required">File No.</label>
                                    <input
                                        wire:model="patient_file_no"
                                        class="form-control
                            {{ $errors->has('patient_file_no') ? 'is-invalid' : '' }}"
                                        type="text"
                                        id="patient_file_no"
                                        name="patient_file_no"
                                        placeholder="File No."
                                        readonly
                                    >
                                    @if($errors->has('patient_file_no'))
                                        <span class="text-danger small">{{ $errors->first('patient_file_no') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="required text-primary">Gender</label>
                                    <select wire:model="gender" class="custom-select form-control "
                                            name="gender">
                                        <option value="0">Female</option>
                                        <option value="1">Male</option>
                                    </select>
                                    <input hidden wire:model="gender" type="text" name="gender">
                                    @if($errors->has('patient'))
                                        <span class="text-danger small">{{ $errors->first('patient') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="form-group">
                                    <label class="required text-primary"
                                           for="name">Name</label>
                                    <input
                                        wire:model="name"
                                        class="form-control
                            {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                        type="text"
                                        name="name"
                                        id="name"
                                    >
                                    @if($errors->has('name'))
                                        <span class="text-danger small">{{ $errors->first('name') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="form-group">
                                    <label class="required text-primary"
                                           for="nationality_id">Nationality</label>
                                    <select
                                        wire:model="nationality_id"
                                        class="custom-select form-control  {{ $errors->has('nationality_id') ? 'is-invalid' : '' }}"
                                        name="nationality_id" id="nationality_id">
                                        <option value="">---</option>
                                        @foreach($nationalities as $nationality)
                                            <option value="{{$nationality->id}}">{{$nationality->name}}</option>
                                        @endforeach
                                    </select>
                                    <input hidden wire:model="nationality_id" type="text" name="nationality_id">
                                    @if($errors->has('nationality_id'))
                                        <span class="text-danger small">{{ $errors->first('nationality_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="required text-primary"
                                           for="mobile">Mobile</label>
                                    <input
                                        wire:model="mobile"
                                        class="form-control {{ $errors->has('mobile') ? 'is-invalid' : '' }}"
                                        type="text" name="mobile" id="mobile" value="{{ old('mobile', '') }}"
                                    >
                                    @if($errors->has('mobile'))
                                        <span class="text-danger small">{{ $errors->first('mobile') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="required text-primary"
                                           for="civil">Civil ID</label>
                                    <input
                                        wire:model="civil_id"
                                        class="form-control {{ $errors->has('civil_id') ? 'is-invalid' : '' }}"
                                        type="text" name="civil_id" id="civil_id"
                                    >
                                    @if($errors->has('civil_id'))
                                        <span class="text-danger small">{{ $errors->first('civil_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if($show_source)
                            <div class="form-group">
                                <label class="text-primary"
                                       for="source">Source</label>
                                <select
                                    wire:model="source"
                                    class="custom-select form-control  {{ $errors->has('source') ? 'is-invalid' : '' }}"
                                    name="source" id="source">
                                    <option value="">---</option>
                                    @foreach(config('global.patient_sources') as $source)
                                        <option
                                            value="{{$source}}">{{$source}}</option>
                                    @endforeach
                                </select>
                                <input hidden wire:model="source" type="text" name="source">
                                @if($errors->has('source'))
                                    <span class="text-danger small">{{ $errors->first('source') }}</span>
                                @endif
                            </div>
                        @endif
                        @can('Patients_Update')
                            @if($patient_data_changed)
                                <button wire:click="change_patient_data"
                                        class="btn btn-outline-danger btn-block btn-sm">
                                    Change
                                    Patient Data
                                </button>
                            @endif
                        @endcan
                        @can('Patients_Create')
                            @if($is_new && $action == 'edit')
                                <button wire:click="save_new_to_patients_list"
                                        class="btn btn-outline-danger btn-block btn-sm">
                                    Add to Patients List & Save Appointment
                                </button>
                            @endif
                        @endcan
                    </div>
                </div>


            </div>

            <div class="col-md-6 align-self-end">
                @if($action == 'edit' && $patient_id > 0)
                    @can("Invoices_Create")
                        <div class="row">
                            <div class="col-12 text-right">
                                <div class="form-group">
                                    <a href="{{route('invoices.create',$patient_id)}}"
                                       class="btn btn-primary btn-sm" title="New Invoice">
                                        Create Invoice
                                    </a>
                                </div>
                            </div>

                        </div>
                    @endcan
                @endif


                <div class="card mb-3" style="min-height: 361px">
                    <div class="card-header text-primary d-flex justify-content-between">
                        <div>Appointment Data</div>
                        <div>Duration : <span>{{$duration}}</span> Mins</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required text-primary"
                                           for="date">Date</label>
                                    <input wire:model="date"
                                           class="form-control date {{ $errors->has('date') ? 'is-invalid' : '' }}"
                                           type="date" name="date" id="date"
                                    >
                                    @if($errors->has('date'))
                                        <span class="text-danger small">{{ $errors->first('date') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required text-primary"
                                           for="start">Start</label>
                                    <div class="input-group">
                                        <input wire:model="start"
                                               class="form-control {{ $errors->has('start') ? 'is-invalid' : '' }}"
                                               type="time" name="start" id="start" readonly>
                                        <button wire:click="start_dec" class="btn btn-sm btn-outline-primary">-
                                        </button>
                                        <button wire:click="start_inc" class="btn btn-sm btn-outline-primary">+
                                        </button>
                                    </div>

                                    @if($errors->has('start'))
                                        <span class="text-danger small">{{ $errors->first('start') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required text-primary"
                                           for="end">End</label>
                                    <div class="input-group">
                                        <input wire:model="end"
                                               class="form-control {{ $errors->has('end') ? 'is-invalid' : '' }}"
                                               type="time" name="end" id="end" readonly>
                                        <button wire:click="end_dec" class="btn btn-sm btn-outline-primary">-
                                        </button>
                                        <button wire:click="end_inc" class="btn btn-sm btn-outline-primary">+
                                        </button>
                                    </div>

                                    @if($errors->has('end'))
                                        <span class="text-danger small">{{ $errors->first('end') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required text-primary"
                                           for="device_id">Device</label>
                                    <select wire:model="device_id"
                                            class="custom-select form-control {{ $errors->has('device_is_busy') ? 'is-invalid' : '' }}"
                                            id="device_id">
                                        @foreach($app_devices as $app_device)
                                            <option
                                                value="{{$app_device->id}}">{{$app_device->name}}</option>
                                        @endforeach
                                    </select>
                                    <input hidden wire:model="device_id" type="text" name="app_device_id">
                                    @if($errors->has('device_is_busy'))
                                        <span class="text-danger small">{{ $errors->first('device_is_busy') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-primary" for="nurse_id">Nurse</label>
                                    <select wire:model="nurse_id"
                                            class="custom-select form-control  {{ $errors->has('nurse_is_busy') ? 'is-invalid' : '' }}"
                                            name="nurse_id" id="nurse_id">
                                        <option value="">---</option>
                                        @foreach($nurses as $nurse)
                                            <option value="{{$nurse->id}}">{{$nurse->name}}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('nurse_is_busy'))
                                        <span class="text-danger small">{{ $errors->first('nurse_is_busy') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="required text-primary"
                                           for="status_id">Status</label>
                                    <select
                                        wire:model="status_id"
                                        class="custom-select form-control  {{ $errors->has('status') ? 'is-invalid' : '' }}"
                                        name="status_id" id="status_id">
                                        @foreach($statuses as $status)
                                            <option value="{{$status->id}}">{{$status->name}}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('status'))
                                        <span class="text-danger small">{{ $errors->first('status') }}</span>
                                    @endif
                                </div>
                            </div>


                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="required text-primary"
                                           for="notes">Notes</label>
                                    <input
                                        wire:model="notes"
                                        class="form-control
                            {{ $errors->has('notes') ? 'is-invalid' : '' }}"
                                        type="text"
                                        name="notes"
                                        id="notes"
                                    >
                                    @if($errors->has('notes'))
                                        <span class="text-danger small">{{ $errors->first('notes') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <div class="card-footer text-center">
        <button wire:click="save_appointment" type="submit" class="btn btn-sm text-primary">Save</button>
        @can('Appointments_Delete')
            @if($action == 'edit')
                <button wire:click="delete_confirmation"
                        class="btn btn-sm btn-outline-danger">Delete
                </button>
            @endif
        @endcan
        <a class="btn btn-sm" href="{{route('appointments.index',[
            'date'=> $date,
            'app_department' => $app_department,
            ])}}">Cancel</a>
    </div>

</div>

<script>
    window.addEventListener('delete', event => {
        if (confirm('Delete This Appointment\nAre you sure?')) {
        @this.delete_appointment();
        }
    });

    window.addEventListener('patient_data_updated', event => {
        alert('Patient Data Updated Successfully');
    });

</script>



