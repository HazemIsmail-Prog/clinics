@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('title','Account Group')

@section('content')
    <form action="{{route('account_groups.update',$account_group->id)}}" method="post">
        @csrf
        @method('PUT')
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Edit Account Group - {{$account_group->name}}</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">


                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-primary" for="name">Name</label>
                            <input class="form-control {{$errors->has('name')?'border-danger':''}}" type="text"
                                   id="name"
                                   name="name" value="{{old('name',$account_group->name)}}">
                            @if($errors->has('name'))
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-primary" for="balance_account">Balance Account</label>
                            <select
                                class="custom-select form-control  {{$errors->has('balance_account')?'border-danger':''}}"
                                name="balance_account" id="balance_account">
                                <option value="">---</option>
                                @foreach($accounts->where('is_bank','!=',1) as $account)
                                    <option
                                        value="{{$account->id}}" {{old('balance_account',$account_group->balance_account) == $account->id ? 'selected' : ''}}>{{$account->name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('balance_account'))
                                <span class="text-danger">{{ $errors->first('balance_account') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-primary" for="insurance_account">Insurance Account</label>
                            <select
                                class="custom-select form-control  {{$errors->has('insurance_account')?'border-danger':''}}"
                                name="insurance_account" id="insurance_account">
                                <option value="">---</option>
                                @foreach($accounts->where('is_bank','!=',1) as $account)
                                    <option
                                        value="{{$account->id}}" {{old('insurance_account',$account_group->insurance_account) == $account->id ? 'selected' : ''}}>{{$account->name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('insurance_account'))
                                <span class="text-danger">{{ $errors->first('insurance_account') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-primary" for="accumulated_loss_account">Accumulated Loss Account</label>
                            <select
                                class="custom-select form-control  {{$errors->has('accumulated_loss_account')?'border-danger':''}}"
                                name="accumulated_loss_account" id="accumulated_loss_account">
                                <option value="">---</option>
                                @foreach($accounts->where('is_bank','!=',1) as $account)
                                    <option
                                        value="{{$account->id}}" {{old('accumulated_loss_account',$account_group->accumulated_loss_account) == $account->id ? 'selected' : ''}}>{{$account->name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('accumulated_loss_account'))
                                <span class="text-danger">{{ $errors->first('accumulated_loss_account') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-primary" for="cash_account">Cash Account</label>
                            <select
                                class="custom-select form-control  {{$errors->has('cash_account')?'border-danger':''}}"
                                name="cash_account" id="cash_account">
                                <option value="">---</option>
                                @foreach($accounts->where('is_bank','!=',1) as $account)
                                    <option
                                        value="{{$account->id}}" {{old('cash_account',$account_group->cash_account) == $account->id ? 'selected' : ''}}>{{$account->name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('cash_account'))
                                <span class="text-danger">{{ $errors->first('cash_account') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-primary" for="income_parent_account">Income Account <small>(for close year)</small></label>
                            <select
                                class="custom-select form-control  {{$errors->has('income_parent_account')?'border-danger':''}}"
                                name="income_parent_account" id="income_parent_account">
                                <option value="">---</option>
                                @foreach($parent_accounts as $account)
                                    <option
                                        value="{{$account->id}}" {{old('income_parent_account',$account_group->income_parent_account) == $account->id ? 'selected' : ''}}>{{$account->name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('income_parent_account'))
                                <span class="text-danger">{{ $errors->first('income_parent_account') }}</span>
                            @endif

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-primary" for="expenses_parent_account">Expenses Account <small>(for close year)</small></label>
                            <select
                                class="custom-select form-control  {{$errors->has('expenses_parent_account')?'border-danger':''}}"
                                name="expenses_parent_account" id="expenses_parent_account">
                                <option value="">---</option>
                                @foreach($parent_accounts as $account)
                                    <option
                                        value="{{$account->id}}" {{old('expenses_parent_account',$account_group->expenses_parent_account) == $account->id ? 'selected' : ''}}>{{$account->name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('expenses_parent_account'))
                                <span class="text-danger">{{ $errors->first('expenses_parent_account') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-primary" for="assets_parent_account">Assets Account <small>(for balance sheet)</small></label>
                            <select
                                class="custom-select form-control  {{$errors->has('assets_parent_account')?'border-danger':''}}"
                                name="assets_parent_account" id="assets_parent_account">
                                <option value="">---</option>
                                @foreach($parent_accounts as $account)
                                    <option
                                        value="{{$account->id}}" {{old('assets_parent_account',$account_group->assets_parent_account) == $account->id ? 'selected' : ''}}>{{$account->name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('assets_parent_account'))
                                <span class="text-danger">{{ $errors->first('assets_parent_account') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-primary" for="liabilities_parent_account">Liabilities Account <small>(for balance sheet)</small></label>
                            <select
                                class="custom-select form-control  {{$errors->has('liabilities_parent_account')?'border-danger':''}}"
                                name="liabilities_parent_account" id="liabilities_parent_account">
                                <option value="">---</option>
                                @foreach($parent_accounts as $account)
                                    <option
                                        value="{{$account->id}}" {{old('liabilities_parent_account',$account_group->liabilities_parent_account) == $account->id ? 'selected' : ''}}>{{$account->name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('liabilities_parent_account'))
                                <span class="text-danger">{{ $errors->first('liabilities_parent_account') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="text-primary" for="equity_parent_account">Equity Account <small>(for balance sheet)</small></label>
                            <select
                                class="custom-select form-control  {{$errors->has('equity_parent_account')?'border-danger':''}}"
                                name="equity_parent_account" id="equity_parent_account">
                                <option value="">---</option>
                                @foreach($parent_accounts as $account)
                                    <option
                                        value="{{$account->id}}" {{old('equity_parent_account',$account_group->equity_parent_account) == $account->id ? 'selected' : ''}}>{{$account->name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('equity_parent_account'))
                                <span class="text-danger">{{ $errors->first('equity_parent_account') }}</span>
                            @endif
                        </div>
                    </div>


                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-primary" for="bank_account">Bank Account</label>
                            <select
                                class="custom-select form-control  {{$errors->has('bank_account')?'border-danger':''}}"
                                name="bank_account" id="bank_account">
                                <option value="">---</option>
                                @foreach($accounts->where('is_bank',1) as $account)
                                    <option
                                        value="{{$account->id}}" {{old('bank_account',$account_group->bank_account) == $account->id ? 'selected' : ''}}>{{$account->name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('bank_account'))
                                <span class="text-danger">{{ $errors->first('bank_account') }}</span>
                            @endif

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-primary" for="bankcharge_account">Bank Charge Account</label>
                            <select
                                class="custom-select form-control  {{$errors->has('bankcharge_account')?'border-danger':''}}"
                                name="bankcharge_account" id="bankcharge_account">
                                <option value="">---</option>
                                @foreach($accounts->where('is_bank','!=',1) as $account)
                                    <option
                                        value="{{$account->id}}" {{old('bankcharge_account',$account_group->bankcharge_account) == $account->id ? 'selected' : ''}}>{{$account->name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('bankcharge_account'))
                                <span class="text-danger">{{ $errors->first('bankcharge_account') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2 align-self-end">
                        <div class="form-group">
                            <label class="text-primary" for="knet_ratio">K-Net Ratio</label>
                            <input class="form-control {{$errors->has('knet_ratio')?'border-danger':''}}"
                                   type="number"
                                   min="0"
                                   step="0.001"
                                   id="knet_ratio"
                                   name="knet_ratio" value="{{old('knet_ratio',$account_group->knet_ratio)}}">
                            @if($errors->has('knet_ratio'))
                                <span class="text-danger">{{ $errors->first('knet_ratio') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2 align-self-end">
                        <div class="form-group">
                            <label class="text-primary" for="knet_bankcharge_ratio">K-Net Bank Charge Ratio</label>
                            <input class="form-control {{$errors->has('knet_bankcharge_ratio')?'border-danger':''}}"
                                   type="number"
                                   min="0"
                                   step="0.001"
                                   id="knet_bankcharge_ratio"
                                   name="knet_bankcharge_ratio" value="{{old('knet_bankcharge_ratio',$account_group->knet_bankcharge_ratio)}}">
                            @if($errors->has('knet_bankcharge_ratio'))
                                <span class="text-danger">{{ $errors->first('knet_bankcharge_ratio') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2 align-self-end">
                        <div class="form-group">
                            <label class="text-primary" for="visa_ratio">Visa Ratio</label>
                            <input class="form-control {{$errors->has('visa_ratio')?'border-danger':''}}"
                                   type="number"
                                   min="0"
                                   step="0.001"
                                   id="visa_ratio"
                                   name="visa_ratio" value="{{old('visa_ratio',$account_group->visa_ratio)}}">
                            @if($errors->has('visa_ratio'))
                                <span class="text-danger">{{ $errors->first('visa_ratio') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2 align-self-end">
                        <div class="form-group">
                            <label class="text-primary" for="visa_bankcharge_ratio">Visa Bank Charge Ratio</label>
                            <input class="form-control {{$errors->has('visa_bankcharge_ratio')?'border-danger':''}}"
                                   type="number"
                                   min="0"
                                   step="0.001"
                                   id="visa_bankcharge_ratio"
                                   name="visa_bankcharge_ratio" value="{{old('visa_bankcharge_ratio',$account_group->visa_bankcharge_ratio)}}">
                            @if($errors->has('visa_bankcharge_ratio'))
                                <span class="text-danger">{{ $errors->first('visa_bankcharge_ratio') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2 align-self-end">
                        <div class="form-group">
                            <label class="text-primary" for="master_ratio">Master Card Ratio</label>
                            <input class="form-control {{$errors->has('master_ratio')?'border-danger':''}}"
                                   type="number"
                                   min="0"
                                   step="0.001"
                                   id="master_ratio"
                                   name="master_ratio" value="{{old('master_ratio',$account_group->master_ratio)}}">
                            @if($errors->has('master_ratio'))
                                <span class="text-danger">{{ $errors->first('master_ratio') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2 align-self-end">
                        <div class="form-group">
                            <label class="text-primary" for="master_bankcharge_ratio">Master Card Bank Charge Ratio</label>
                            <input class="form-control {{$errors->has('master_bankcharge_ratio')?'border-danger':''}}"
                                   type="number"
                                   min="0"
                                   step="0.001"
                                   id="master_bankcharge_ratio"
                                   name="master_bankcharge_ratio" value="{{old('master_bankcharge_ratio',$account_group->master_bankcharge_ratio)}}">
                            @if($errors->has('master_bankcharge_ratio'))
                                <span class="text-danger">{{ $errors->first('master_bankcharge_ratio') }}</span>
                            @endif
                        </div>
                    </div>
                </div>




            </div>
            <div class="card-footer">
                <div class="text-center">
                    <button type="submit" class="btn btn-sm text-primary">Update</button>
                    <a href="{{route('account_groups.index')}}" class="btn btn-sm">Cancel</a>
                </div>
            </div>
        </div>

    </form>


@endsection
