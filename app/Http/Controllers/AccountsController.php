<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Gate;

class AccountsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('Accounts_Access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $parentAccounts = Account::
        loggedAccountGroup()
            ->with(['childAccounts' => function ($q) {
                $q->with(['childAccounts' => function ($q) {
                    $q->with('childAccounts');
                }]);
            }])
            ->where('account_id', null)->get();
        return view('pages.accounts.index', compact('parentAccounts'));
    }

    public function create()
    {
        abort_if(Gate::denies('Accounts_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $accounts = Account::loggedAccountGroup()->where('level', '<', 4)->get();
        return view('pages.accounts.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('Accounts_Create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $validator = \Validator::make(request()->all(), [
            'name' => [
                'required',
                Rule::unique('accounts')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name)->where('account_group_id', auth()->user()->clinic->account_group_id);
                })
            ],
            'account_id' => 'required',
            'type' => 'required_without:account_id',

        ], [
            'type.required_without' => 'Account type is required for parent accounts',
        ]);
        $validator->validate();

        $parent_account = $request->account_id ? Account::find($request->account_id) : null;

        $data = [
            'name' => $request->name,
            'type' => $request->account_id ? $parent_account->type : $request->type,
            'is_bank' => $request->is_bank ? 1 : 0,
            'account_id' => $request->account_id ?? null,
            'root_account' => $request->account_id ? $parent_account->root_account : null,
            'account_group_id' => auth()->user()->clinic->account_group_id,
            'level' => $request->account_id ? Account::find($request->account_id)->level + 1 : 1,
        ];


        $account = Account::create($data);
        if ($account->account_id == null) {
            $account->update(['root_account' => $account->id]);
        }

        session()->flash('success', 'Account Added Successfully');
        return redirect()->route('accounts.index');
    }

    public function edit($id)
    {
        abort_if(Gate::denies('Accounts_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $account = Account::loggedAccountGroup()->findOrFail($id);
        $accounts = Account::loggedAccountGroup()->where('level', '<', 4)->where('id', '!=', $id)->get();
        if ($account) {
            return view('pages.accounts.edit', compact('account', 'accounts'));
        }
    }

    public function update(Request $request, $id)
    {
        abort_if(Gate::denies('Accounts_Update'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $validator = \Validator::make(request()->all(), [
            'name' => [
                'required',
                Rule::unique('accounts')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name)->where('account_group_id', auth()->user()->clinic->account_group_id);
                })->ignore($id)
            ],
            'account_id' => 'required',
            'type' => 'required_without:account_id',
        ], [
            'type.required_without' => 'Account type is required for parent accounts',
        ]);


        $validator->validate();
        $account = Account::loggedAccountGroup()->findOrFail($id);
        $parent_account = $request->account_id ? Account::find($request->account_id) : null;
        $data = [
            'name' => $request->name,
            'type' => $request->account_id ? $parent_account->type : $request->type,
            'is_bank' => $request->is_bank ? 1 : 0,
            'account_id' => $request->account_id ?? null,
            'root_account' => $request->account_id ? $parent_account->root_account : $account->id,
            'account_group_id' => auth()->user()->clinic->account_group_id,
            'level' => $request->account_id ? $parent_account->level + 1 : 1,
        ];


        $account->update($data);
        session()->flash('success', 'Account Updated Successfully');
        return redirect()->route('accounts.index');
    }

    public function destroy($id)
    {
        abort_if(Gate::denies('Accounts_Delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

//        $account = Account::loggedAccountGroup()->findOrFail($id);
//        if($account->voucher_details->count() == 0 && $account->childAccounts->count() == 0)
//        {
//            $account->childAccounts()->delete();
//            $account->delete();
//            session()->flash('success','Account Deleted Successfully');
//            return back();
//        }
    }
}
