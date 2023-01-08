<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['middleware' => 'auth'] , function (){


    Route::get      (  '/'                                , 'DashboardController@index')              ->name('dashboard.index');
    Route::post     (  '/change_clinic'                   , 'DashboardController@change_clinic')      ->name('dashboard.change_clinic');

    Route::get      (  'appointments/r'                   , 'AppointmentsController@reports_index')   ->name('appointments.reports_index');
    Route::get      (  'appointments/r/monthly_stats'     , 'AppointmentsController@monthly_stats')   ->name('appointments.monthly_stats');
    Route::get      (  'appointments/r/monthly_stats_ar'  , 'AppointmentsController@monthly_stats_ar')->name('appointments.monthly_stats_ar');
    Route::get      (  'appointments/r/patients_visit'    , 'AppointmentsController@patients_visit')  ->name('appointments.patients_visit');
    Route::get      (  'appointments/r/app_register'      , 'AppointmentsController@app_register')    ->name('appointments.app_register');

    Route::get      (  'appointments/print'               , 'AppointmentsController@print')           ->name('appointments.print');
    Route::get      (  'appointments/search'              , 'AppointmentsController@search')          ->name('appointments.search');
    Route::get      (  'appointments/ministry_book'       , 'AppointmentsController@ministry_book')   ->name('appointments.ministry_book');
    Route::resource ('appointments'                     , AppointmentsController::class);

    Route::get      (  'collection_statements'            ,'CollectionStatementsController@index')    ->name('collection_statements.index');
    Route::get      (  'collection_statements/show'       ,'CollectionStatementsController@show')     ->name('collection_statements.show');


    Route::get      (  'account_statement'                ,'AccountStatementController@index')        ->name('account_statement.index');
    Route::get      (  'account_statement/show'           ,'AccountStatementController@show')         ->name('account_statement.show');

    Route::get      (  'bank_book'                        ,'BankBookController@index')                ->name('bank_book.index');
    Route::get      (  'bank_book/show'                   ,'BankBookController@show')                 ->name('bank_book.show');




    Route::get      (  'close_year/'                      ,'YearCloseController@index')           ->name('year_close.index');
    Route::post     (  'close_year/'                      ,'YearCloseController@store')           ->name('year_close.store');

    Route::get      (  'trial_balance'                    ,'TrialBalanceController@index')            ->name('trial_balance.index');
    Route::get      (  'trial_balance/show'               ,'TrialBalanceController@show')             ->name('trial_balance.show');

    Route::get      (  'profit_loss'                      ,'ProfitLossController@index')              ->name('profit_loss.index');
    Route::get      (  'profit_loss/show'                 ,'ProfitLossController@show')               ->name('profit_loss.show');


    Route::get      (  'balance_sheet'                    ,'BalanceSheetController@index')            ->name('balance_sheet.index');
    Route::get      (  'balance_sheet/show'               ,'BalanceSheetController@show')             ->name('balance_sheet.show');




    Route::resource ('patients'                         , PatientsController::class);
    Route::get      (  'invoices'                         , 'InvoicesController@index')               ->name('invoices.index');
    Route::get      (  'invoices/{id}/create'             , 'InvoicesController@create')              ->name('invoices.create');
    Route::get      (  'invoices/{id}/edit'               , 'InvoicesController@edit')                ->name('invoices.edit');
    Route::get      (  'invoices/{id}/show'               , 'InvoicesController@show')                ->name('invoices.show');

    Route::get      (  'balances'                         , 'BalancesController@index')               ->name('balances.index');
    Route::get      (  'balance_invoices/{id}/create'     , 'BalancesController@create')              ->name('balances.create');
    Route::get      (  'balance_invoices/{id}/edit'       , 'BalancesController@edit')                ->name('balances.edit');

    Route::get      (  'day_closing'                      , 'DayClosingController@index')             ->name('day_closing.index');
    Route::get      (  'day_closing/print'                , 'DayClosingController@print')             ->name('day_closing.print');

    Route::get      (  'daily_income'                     , 'DailyIncomeController@index')            ->name('daily_income.index');
    Route::get      (  'daily_income/print'               , 'DailyIncomeController@print')            ->name('daily_income.print');

    Route::get      (  'reset_password'                   , 'MyResetPasswordController@index')        ->name('reset_password.index');
    Route::post     (  'reset_password'                   , 'MyResetPasswordController@store')        ->name('reset_password.store');

    Route::resource ('clinics'                          , ClinicsController::class);
    Route::resource ('offers'                           , OffersController::class);
    Route::resource ('jvs'                              , JvsController::class);
    Route::resource ('bps'                              , BpsController::class);
    Route::resource ('brs'                              , BrsController::class);
    Route::resource ('departments'                      , DepartmentsController::class);
    Route::resource ('doctors'                          , DoctorsController::class);
    Route::resource ('nurses'                           , NursesController::class);
    Route::resource ('treatments'                       , TreatmentsController::class);
    Route::resource ('nationalities'                    , NationalitiesController::class);
    Route::resource ('app_departments'                  , AppDepartmentsController::class);
    Route::resource ('app_devices'                      , AppDevicesController::class);
    Route::resource ('app_statuses'                     , AppStatusesController::class);
    Route::resource ('accounts'                         , AccountsController::class);
    Route::resource ('account_groups'                   , AccountGroupsController::class);
    Route::resource ('users'                            , UsersController::class);







//|        | POST      | clinics                 | clinics.store     | App\Http\Controllers\ClinicsController@store                           | web        |
//|        | GET|HEAD  | clinics                 | clinics.index     | App\Http\Controllers\ClinicsController@index                           | web        |
//|        | GET|HEAD  | clinics/create          | clinics.create    | App\Http\Controllers\ClinicsController@create                          | web        |
//|        | PUT|PATCH | clinics/{clinic}         | clinics.update    | App\Http\Controllers\ClinicsController@update                          | web        |
//|        | GET|HEAD  | clinics/{clinic}         | clinics.show      | App\Http\Controllers\ClinicsController@show                            | web        |
//|        | DELETE    | clinics/{clinic}         | clinics.destroy   | App\Http\Controllers\ClinicsController@destroy                         | web        |
//|        | GET|HEAD  | clinics/{clinic}/edit    | clinics.edit      | App\Http\Controllers\ClinicsController@edit

});


