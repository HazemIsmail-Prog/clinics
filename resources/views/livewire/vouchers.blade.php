<div class="card shadow mb-4">
    <div class="bg-light border-primary border" wire:loading style="position: fixed;top: 70px;right: 10px;padding: 10px 20px;border-radius: 10px;z-index: 1000;">
        <h6 class="m-0 font-weight-bold text-primary"><div class="spinner-border small"></div> Loading ...</h6>
    </div>
    <!-- Card Header - Dropdown -->
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">
            {{$action == 'create' ? 'Add New ' : 'Edit '}}
            @switch($voucher_type)
                @case('jv')
                Journal Voucher
                @break
                @case('bp')
                Bank Payment
                @break
                @case('br')
                Bank Receipt
                @break
            @endswitch
        </h6>
    </div>

    <!-- Card Body -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="date" class="text-primary">Date</label>
                    <input wire:model="date" type="date" id="date" class="form-control">
                </div>
            </div>
            @if($action == 'create')
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="copy" class="text-primary">Copy</label>
                        <div class="input-group">
                            <input wire:model="copy_from" type="number" id="copy" class="form-control"
                                   placeholder="{{$placeholder}}">
                            <button wire:click="copy_voucher" class="btn btn-outline-primary btn-sm">Copy</button>
                        </div>
                        @if($no_voucher_message)
                            <span class="text-danger">Voucher No. {{$copy_from}} not found</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                        <tr class="bg-primary text-white">
                            <th class="text-center">Account</th>
                            <th class="text-center">Cost Center</th>
                            <th class="text-center">Narration</th>
                            <th class="text-center">Debit</th>
                            <th class="text-center">Credit</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $index =>$item)
                            <tr>
                                <td>
                                    <select style="min-width: 200px;"
                                            wire:model="items.{{$index}}.account_id"
                                            name="items[{{$index}}][account_id]"
                                            class="form-control custom-select bg-transparent {{ $errors->has('items.'.$index.'.account_id') ? 'is-invalid' : '' }}">
                                        <option disabled value="">---</option>
                                        @if($voucher_type == 'jv')
                                            @foreach($accounts as $account)
                                                <option
                                                    value="{{$account->id}}">{{$account->name}}</option>
                                            @endforeach
                                        @else
                                            @if($index == 0)
                                                @foreach($accounts->where('is_bank',1) as $account)
                                                    <option
                                                        value="{{$account->id}}">{{$account->name}}</option>
                                                @endforeach
                                            @else
                                                @foreach($accounts->where('is_bank','!=',1) as $account)
                                                    <option
                                                        value="{{$account->id}}">{{$account->name}}</option>
                                                @endforeach
                                            @endif
                                        @endif
                                    </select>
{{--                                    @if($errors->has('items.'.$index.'.account_id'))--}}
{{--                                        <div--}}
{{--                                            class="text-danger text-center">{{ $errors->first('items.'.$index.'.account_id') }}</div>--}}
{{--                                    @endif--}}
                                </td>
                                <td>
                                    <select style="min-width: 150px;"
                                            wire:model="items.{{$index}}.doctor_id"
                                            name="items[{{$index}}][doctor_id]"
                                            class="form-control custom-select bg-transparent {{ $errors->has('items.'.$index.'.doctor_id') ? 'is-invalid' : '' }}">
                                        <option value="">---</option>
                                        @foreach($doctors as $doctor)
                                            <option
                                                value="{{$doctor->id}}">{{$doctor->name}}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td>
                                <textarea
                                    wire:model="items.{{$index}}.narration"
                                    style="min-width: 350px;"
                                    class="form-control bg-transparent"
                                    name="items[{{$index}}][narration]"
                                    rows="1">

                                </textarea>
                                    @if($errors->has('items.'.$index.'.narration'))
                                        <span
                                            class="text-danger">{{ $errors->first('items.'.$index.'.narration') }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($voucher_type == 'jv' || ($voucher_type == 'bp' && $index > 0) || ($voucher_type == 'br' && $index == 0))
                                        <input style="min-width: 150px;" wire:model="items.{{$index}}.debit"
                                               type="number"
                                               step="0.001" min="0"
                                               class="text-center form-control bg-transparent {{ $errors->has('items.'.$index.'.debit') ? 'is-invalid' : '' }}"
                                               name="items[{{$index}}][debit]">
{{--                                        @if($errors->has('items.'.$index.'.debit'))--}}
{{--                                            <span--}}
{{--                                                class="text-danger">{{ $errors->first('items.'.$index.'.debit') }}</span>--}}
{{--                                        @endif--}}
                                    @endif

                                </td>
                                <td class="text-center">
                                    @if($voucher_type == 'jv' || ($voucher_type == 'bp' && $index == 0) || ($voucher_type == 'br' && $index > 0))
                                        <input style="min-width: 150px;" wire:model="items.{{$index}}.credit"
                                               type="number"
                                               step="0.001" min="0"
                                               class="text-center form-control bg-transparent {{ $errors->has('items.'.$index.'.credit') ? 'is-invalid' : '' }}"
                                               name="items[{{$index}}][credit]">
{{--                                        @if($errors->has('items.'.$index.'.credit'))--}}
{{--                                            <span--}}
{{--                                                class="text-danger">{{ $errors->first('items.'.$index.'.credit') }}</span>--}}
{{--                                        @endif--}}
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button wire:click="delete_row({{$index}})"
                                            class="text-center btn btn-sm text-danger {{$index < 2 ? 'd-none' : ''}}"><i
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
                        <tr>
                            <td colspan="3" class="text-right">Total : </td>
                            <td class="text-right">{{number_format($total_debit,3)}}</td>
                            <td class="text-right">{{number_format($total_credit,3)}}</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right">Difference : </td>
                            <td class="text-right {{$total_difference == 0 ? 'text-success' : 'text-danger'}}">
                                <div>{{number_format($total_difference,3)}}</div>
                                @if($errors->has('total_difference'))
                                    <span class="text-danger">{{ $errors->first('total_difference') }}</span>
                                @endif
                            </td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-center">
        @if($action == 'create')
            <button wire:click="save_voucher" class="btn btn-sm text-primary">Save</button>

            @switch($voucher_type)
                @case('jv')
                <a href="{{route('jvs.index')}}" class="btn btn-sm">Cancel</a>
                @break
                @case('bp')
                <a href="{{route('bps.index')}}" class="btn btn-sm">Cancel</a>
                @break
                @case('br')
                <a href="{{route('brs.index')}}" class="btn btn-sm">Cancel</a>
                @break
            @endswitch

        @endif

        @if($action == 'edit')
            <button wire:click="update_voucher" class="btn btn-sm text-primary">Update</button>
                @switch($voucher_type)
                    @case('jv')
                    <a href="{{route('jvs.index')}}" class="btn btn-sm">Cancel</a>
                    @break
                    @case('bp')
                    <a href="{{route('bps.index')}}" class="btn btn-sm">Cancel</a>
                    @break
                    @case('br')
                    <a href="{{route('brs.index')}}" class="btn btn-sm">Cancel</a>
                    @break
                @endswitch
        @endif
    </div>

</div>
