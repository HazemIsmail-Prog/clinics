@extends('layouts.master')

@section('links')
    <link rel="stylesheet" href="{{asset('assets\custom\css\forms.css')}}">
@endsection

@section('title','Accounts')

@section('content')
    <form action="{{route('accounts.update',$account->id)}}" method="post">
        @csrf
        @method('PUT')
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Edit Account - {{$account->name}}</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body">



                <div class="form-group">
                    <label class="text-primary" for="name">Account Name</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name"
                           value="{{ old('name', $account->name) }}">
                    @if($errors->has('name'))
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label class="text-primary" for="type">Account Type</label>
                    <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type"
                            id="type">
                        <option value="">Select Account Type</option>
                        <option value="Debit" {{ $account->type == 'Debit' ? 'selected' : '' }}>Debit</option>
                        <option value="Credit" {{ $account->type == 'Credit' ? 'selected' : '' }}>Credit</option>
                    </select>
                    @if($errors->has('type'))
                        <span class="text-danger">{{ $errors->first('type') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="account_id">Parent Account</label>
                    <select class="form-control select2 {{ $errors->has('account') ? 'is-invalid' : '' }}" name="account_id"
                            id="account_id">
                        <option value="">Select Parent Account (Optional)</option>
                        @foreach($accounts as  $parent_account)
                            <option
                                value="{{ $parent_account->id }}"
                                {{ ($parent_account->id == $account->account_id) ? 'selected' : '' }}>
                                {{ $parent_account->id }} - {{ $parent_account->name }}
                            </option>
                        @endforeach
                    </select>
                    @if($errors->has('account'))
                        <span class="text-danger">{{ $errors->first('account') }}</span>
                    @endif
                </div>

                <div class="form-group">

                    <div class="form-check">
                        <input class="form-check-input" {{$account->is_bank == 1 ? 'checked' : ''}} name="is_bank" type="checkbox" value="1"
                               id="defaultCheck1">
                        <label class="form-check-label text-primary" for="defaultCheck1">
                            Is Bank
                        </label>
                    </div>

                </div>

            </div>
            <div class="card-footer">
                <div class="text-center">
                    <button type="submit" class="btn btn-sm text-primary">Update</button>
                    <a href="{{route('accounts.index')}}" class="btn btn-sm">Cancel</a>
                </div>
            </div>
        </div>
    </form>
@endsection




























