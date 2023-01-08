@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('title','Accounts')

@section('content')
    <form method="POST" action="{{ route("accounts.store") }}">
        @csrf
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Add Account</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">


                <div class="form-group">
                    <label class="text-primary" for="name">Account Name</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                           id="name"
                           value="{{ old('name') }}">
                    @if($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label class="text-primary" for="type">Account Type</label>
                    <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type"
                            id="type">
                        <option value="">Select Account Type</option>
                        <option value="Debit" {{ old('type') == 'Debit' ? 'selected' : '' }}>Debit</option>
                        <option value="Credit" {{ old('type') == 'Credit' ? 'selected' : '' }}>Credit</option>
                    </select>
                    @if($errors->has('type'))
                        <span class="text-danger">{{ $errors->first('type') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label class="text-primary" for="account_id">Parent Account</label>
                    <select class="form-control {{ $errors->has('account_id') ? 'is-invalid' : '' }}" name="account_id"
                            id="account_id">
                        <option value="">Select Parent Account (Optional)</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}"
                                    data-type="{{$account->type}}" {{ old('account_id') == $account->id ? 'selected' : '' }}>{{$account->id}}
                                - {{ $account->name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('account_id'))
                        <span class="text-danger">{{ $errors->first('account_id') }}</span>
                    @endif
                </div>

                <div class="form-group">

                    <div class="form-check">
                        <input class="form-check-input" name="is_bank" type="checkbox" value="1"
                               id="defaultCheck1">
                        <label class="form-check-label text-primary" for="defaultCheck1">
                            Is Bank
                        </label>
                    </div>

                </div>

            </div>
            <div class="card-footer">
                <div class="text-center">
                    <button type="submit" class="btn btn-sm text-primary">Save</button>
                    <a href="{{route('accounts.index')}}" class="btn btn-sm">Cancel</a>
                </div>
            </div>
        </div>
    </form>
@endsection
