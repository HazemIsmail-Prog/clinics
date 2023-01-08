<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\AccountGroup;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class AccountGroupsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('AccountGroups_Access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $account_group = AccountGroup::findOrFail(auth()->user()->clinic->account_group_id);
        return view('pages.account_groups.index', compact('account_group'));
    }

    public function edit($id)
    {
        abort_if(Gate::denies('AccountGroups_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        abort_if(auth()->id() > 1 , Response::HTTP_FORBIDDEN, '403 Forbidden');

        $account_group = AccountGroup::findOrFail(auth()->user()->clinic->account_group_id);
        $accounts = Account::loggedAccountGroup()->doesnthave('childAccounts')->orderBy('name')->get();
        $parent_accounts = Account::loggedAccountGroup()->doesnthave('parentAccounts')->orderBy('name')->get();
        return view('pages.account_groups.edit', compact('account_group', 'accounts', 'parent_accounts'));
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('AccountGroups_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        abort_if(auth()->id() > 1 , Response::HTTP_FORBIDDEN, '403 Forbidden');

        $validator = \Validator::make(request()->all(), [
            'name' => 'required',
            'balance_account' => 'required',
            'insurance_account' => 'required',
            'accumulated_loss_account' => 'required',
            'bank_account' => 'required',
            'knet_ratio' => 'required',
            'knet_bankcharge_ratio' => 'required',
            'visa_ratio' => 'required',
            'visa_bankcharge_ratio' => 'required',
            'master_ratio' => 'required',
            'master_bankcharge_ratio' => 'required',
            'cash_account' => 'required',
            'bankcharge_account' => 'required',
            'income_parent_account' => 'required',
            'expenses_parent_account' => 'required',
            'assets_parent_account' => 'required',
            'liabilities_parent_account' => 'required',
            'equity_parent_account' => 'required',
        ]);
        $validator->validate();
        $account_group = AccountGroup::findOrFail(auth()->user()->clinic->account_group_id);
        $account_group->update($request->all());
        session()->flash('success', 'Account Group Updated Successfully');
        return redirect()->route('account_groups.index');
    }
}
