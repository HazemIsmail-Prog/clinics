<div class="card shadow mb-4">
    <div class="bg-light border-primary border" wire:loading style="position: fixed;top: 70px;right: 10px;padding: 10px 20px;border-radius: 10px;z-index: 1000;">
        <h6 class="m-0 font-weight-bold text-primary"><div class="spinner-border small"></div> Loading ...</h6>
    </div>
    <!-- Card Header - Dropdown -->
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">{{$action == 'create' ? 'New Invoice' : 'Edit Invoice - '.$invoice->invoice_no}}</h6>
        <div class="m-0 font-weight-bold text-primary text-right">
            <div>
                {{$patient->file_no .' - ' . $patient->name}}
            </div>
            @if($action == 'edit')
                <div>
                    <input wire:model.debouce.1s="changed_file_no" type="number" class="form-control" id="file_no"
                           placeholder="Change Patient (file no...)">
                    @if($file_no_not_found)
                        <span class="text-danger">file no. {{$changed_file_no}} not found</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
    <!-- Card Body -->
    <div class="card-body">

        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <select wire:model="department_id" name="department_id" id="department_id"
                            class="form-control bg-transparent custom-select {{ $errors->has('department_id') ? 'is-invalid' : '' }}">
                        <option value="">-- Choose Department --</option>
                        @foreach($departments as $department)
                            <option value="{{$department->id}}">{{$department->name}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('department_id'))
                        <span class="text-danger">{{ $errors->first('department_id') }}</span>
                    @endif
                </div>
            </div>
        </div>

        @if($department_id)

            <div class="row">
                <div class="col-md-6">
                    @if($doctors)
                        <div class="form-group">
                            <select wire:model="doctor_id" name="doctor_id" id="doctor_id"
                                    class="form-control bg-transparent custom-select {{ $errors->has('doctor_id') ? 'is-invalid' : '' }}">
                                <option value="">-- Choose Doctor --</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{$doctor->id}}">{{$doctor->name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('doctor_id'))
                                <span class="text-danger">{{ $errors->first('doctor_id') }}</span>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="col-md-6">
                    @if($nurses)
                        <div class="form-group">
                            <select wire:model="nurse_id" name="nurse_id" id="nurse_id"
                                    class="form-control bg-transparent custom-select">
                                <option value="">-- Choose Nurse --</option>
                                @foreach($nurses as $nurse)
                                    <option value="{{$nurse->id}}">{{$nurse->name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('nurse_id'))
                                <span class="text-danger">{{ $errors->first('nurse_id') }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>


            <div class="card shadow mb-3">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Treatments</h6>
                </div>

                <div class="card-body">


                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <thead>
                                    <tr class="bg-primary text-white">
                                        <th>Treatment</th>
                                        <th class="text-center">Unit Price</th>
                                        <th class="text-center">Qty</th>
                                        @can('Invoices_Discount')
                                            <th class="text-center">Discount</th>
                                        @endcan

                                        <th class="text-center">Total</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($items as $index =>$item)
                                        <tr>
                                            <td>
                                                <select style="min-width: 250px;"
                                                        wire:model="items.{{$index}}.treatment_id"
                                                        name="items[{{$index}}][treatment_id]"
                                                        class="form-control custom-select bg-transparent {{ $errors->has('items.'.$index.'.treatment_id') ? 'is-invalid' : '' }}">
                                                    <option disabled value="">---</option>
                                                    @foreach($treatments as $treatment)
                                                        <option
                                                            value="{{$treatment->id}}">{{$treatment->name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="text-center">
                                                <input style="min-width: 60px;" wire:model="items.{{$index}}.price"
                                                       type="text"
                                                       disabled class="text-center border-0 bg-transparent"
                                                       name="items[{{$index}}][price]">
                                                @if($errors->has('price'))
                                                    <span class="text-danger">{{ $errors->first('price') }}</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <input style="min-width: 60px;" wire:model="items.{{$index}}.qty"
                                                       type="number"
                                                       step="1" min="1"
                                                       class="text-center form-control bg-transparent {{ $errors->has('items.'.$index.'.qty') ? 'is-invalid' : '' }}"
                                                       name="items[{{$index}}][qty]">
                                                @if($errors->has('items.'.$index.'.qty'))
                                                    <span
                                                        class="text-danger">{{ $errors->first('items.'.$index.'.qty') }}</span>
                                                @endif
                                            </td>
                                            @can('Invoices_Discount')
                                                <td class="text-center">


                                                    <div class="input-group" style="min-width: 150px;">
                                                        <input
                                                            style="width: 50%;"
                                                            wire:model="items.{{$index}}.discount"
                                                            type="number" step="0.001"
                                                            class="text-center form-control bg-transparent {{ $errors->has('items.'.$index.'.discount') ? 'is-invalid' : '' }} {{ $errors->has('items.'.$index.'.discount') ? 'is-invalid' : '' }}"
                                                            name="items[{{$index}}][discount]">


                                                        <select
                                                            wire:model="items.{{$index}}.discount_type"
                                                            name="items[{{$index}}][discount_type]"
                                                            style="padding-right: 0px;padding-left: 15px;"
                                                            class="form-control input-group-append custom-select bg-transparent"
                                                        >
                                                            <option selected value="fixed">KD</option>
                                                            <option value="percentage">%</option>
                                                        </select>
                                                        @if($errors->has('items.'.$index.'.discount'))
                                                            <span
                                                                class="text-danger">{{ $errors->first('items.'.$index.'.discount') }}</span>
                                                        @endif
                                                    </div>


                                                </td>
                                            @endcan
                                            <td class="text-center">
                                                <input style="min-width: 60px;" wire:model="items.{{$index}}.total"
                                                       type="text"
                                                       disabled
                                                       class="text-center border-0 bg-transparent {{ $errors->has('items.'.$index.'.total') ? 'is-invalid' : '' }}"
                                                       name="items[{{$index}}][total]">
                                                @if($errors->has('items.'.$index.'.total'))
                                                    <div
                                                        class="text-danger">{{ $errors->first('items.'.$index.'.total') }}</div>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button wire:click="delete_row({{$index}})"
                                                        class="text-center btn btn-sm text-danger {{$index == 0 ? 'd-none' : ''}}">
                                                    <i
                                                        class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="text-center" colspan="6">
                                            <button wire:click="add_row" class="btn btn-sm text-success">Add</button>
                                            <input wire:model="rows_number"
                                                   style="width: 50px;background: transparent;text-align: center;border: 0"
                                                   type="number" step="1" min="1" value="1">
                                            Row(s)
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Details</h6>
                    <div>
                        <h6 class="m-0 font-weight-bold text-primary">Total Amount
                            : {{number_format($total_amount, 3)}}
                            KWD</h6>
                        @if($balance>0)
                            <h6 class="m-0 font-weight-bold text-danger">Balance
                                : {{number_format($balance, 3)}}
                                KWD</h6>
                        @endif
                    </div>


                </div>

                <div class="card-body">

                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="form-check">
                                <input wire:model="is_insurance" class="form-check-input" name="is_bank" type="checkbox"
                                       value="1"
                                       id="defaultCheck1">
                                <label class="form-check-label text-primary" for="defaultCheck1">
                                    Insurance
                                </label>
                            </div>
                        </div>
                    </div>


                    @if(!$is_insurance)

                        @if($errors->has('difference'))
                            <div class="alert alert-danger" role="alert">
                                {{ $errors->first('difference') }}
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6 mx-auto">

                                <table class="table table-borderless">
                                    <tr>
                                        <td class="text-center">
                                            <img style="width: 40px;" src="{{asset('assets\img\cash.png')}}"
                                                 alt="Cash">
                                        </td>
                                        <td>
                                            <input wire:model="cash" type="number" name="cash" id="cash"
                                                   placeholder="Cash"
                                                   class="form-control bg-transparent  {{ $errors->has('total_paid') ? 'is-invalid' : '' }}"
                                                   step="0.001"></td>
                                        <td>
                                            <button wire:click="pay_all('cash')" tabindex="-1"
                                                    class="btn btn-outline-primary btn-sm">Pay All
                                            </button>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td class="text-center">
                                            <img style="width: 40px;" src="{{asset('assets\img\knet.png')}}"
                                                 alt="K-Net">
                                        </td>
                                        <td>
                                            <input wire:model="knet" type="number" name="knet" id="knet"
                                                   placeholder="K-Net"
                                                   class="form-control bg-transparent {{ $errors->has('total_paid') ? 'is-invalid' : '' }}"
                                                   step="0.001">
                                        </td>
                                        <td>
                                            <button wire:click="pay_all('knet')" tabindex="-1"
                                                    class="btn btn-outline-primary btn-sm">Pay All
                                            </button>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-center">
                                            <img style="width: 40px;" src="{{asset('assets\img\visa.png')}}" alt="">
                                        </td>
                                        <td>
                                            <input wire:model="visa" type="number" name="visa" id="visa"
                                                   placeholder="VISA"
                                                   class="form-control bg-transparent {{ $errors->has('total_paid') ? 'is-invalid' : '' }}"
                                                   step="0.001">
                                        </td>
                                        <td>
                                            <button wire:click="pay_all('visa')" tabindex="-1"
                                                    class="btn btn-outline-primary btn-sm">Pay All
                                            </button>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-center">
                                            <img style="width: 40px;" src="{{asset('assets\img\master.png')}}" alt="">
                                        </td>
                                        <td>
                                            <input wire:model="master" type="number" name="master" id="master"
                                                   placeholder="Master Card"
                                                   class="form-control bg-transparent {{ $errors->has('total_paid') ? 'is-invalid' : '' }}"
                                                   step="0.001">
                                        </td>
                                        <td>
                                            <button wire:click="pay_all('master')" tabindex="-1"
                                                    class="btn btn-outline-primary btn-sm">Pay All
                                            </button>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-center">
                                            K-Net Link
                                        </td>
                                        <td>
                                            <input wire:model="knet_link" type="number" name="knet_link" id="knet_link"
                                                   placeholder="K-Net Link"
                                                   class="form-control bg-transparent {{ $errors->has('total_paid') ? 'is-invalid' : '' }}"
                                                   step="0.001">
                                        </td>
                                        <td>
                                            <button wire:click="pay_all('knet_link')" tabindex="-1"
                                                    class="btn btn-outline-primary btn-sm">Pay All
                                            </button>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-center">
                                            Credit Card Link
                                        </td>
                                        <td>
                                            <input wire:model="credit_link" type="number" name="credit_link" id="credit_link"
                                                   placeholder="Credit Card Link"
                                                   class="form-control bg-transparent {{ $errors->has('total_paid') ? 'is-invalid' : '' }}"
                                                   step="0.001">
                                        </td>
                                        <td>
                                            <button wire:click="pay_all('credit_link')" tabindex="-1"
                                                    class="btn btn-outline-primary btn-sm">Pay All
                                            </button>
                                        </td>
                                    </tr>
                                </table>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="text-primary" for="notes">Notes</label>
                                        <input wire:model="notes" class="form-control bg-transparent" type="text"
                                               id="notes"
                                               placeholder="notes ..." name="notes">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif


                </div>

            </div>


        @endif


    </div>

    <div class="card-footer text-center">
        @if($action == 'create')
            <button wire:click="balance_confirmation" class="btn btn-sm text-primary">Save</button>
        @endif

        @if($action == 'edit')
            <button wire:click="balance_confirmation" class="btn btn-sm text-primary">Update</button>
        @endif

        @switch($action)

            @case('create')
            <a href="{{route('patients.index')}}" class="btn btn-sm">Cancel</a>

            @break

            @case('edit')
            <a href="{{route('invoices.index')}}" class="btn btn-sm">Cancel</a>

            @break

        @endswitch


    </div>

</div>


<script>
    window.addEventListener('balance', event => {
        if (confirm('Continue with Balance\nAre you sure?')) {
        @this.balance_confirmed();
        }
    });
</script>




