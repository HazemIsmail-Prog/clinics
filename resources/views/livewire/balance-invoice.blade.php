<div class="card shadow mb-4">
    <div class="bg-light border-primary border" wire:loading style="position: fixed;top: 70px;right: 10px;padding: 10px 20px;border-radius: 10px;z-index: 1000;">
        <h6 class="m-0 font-weight-bold text-primary"><div class="spinner-border small"></div> Loading ...</h6>
    </div>
    <!-- Card Header - Dropdown -->
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">{{$action == 'create' ? 'Balance Payment' : 'Edit Balance Payment'}}</h6>
        <span
            class="m-0 font-weight-bold text-primary text-right">For Invoice No. {{$ref_invoice->invoice_no}}</span>
    </div>
    <!-- Card Body -->
    <div class="card-body">


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

                        {{-- <table class="table table-borderless">
                            <tr>
                                <td class="text-center">
                                    <img style="width: 40px;" src="{{asset('assets\img\cash.png')}}" alt="Cash">
                                </td>
                                <td>
                                    <input wire:model="cash" type="number" name="cash" id="cash" placeholder="Cash"
                                           class="form-control bg-transparent  {{ $errors->has('total_paid') ? 'is-invalid' : '' }}"
                                           step="0.001"></td>
                                <td>
                                    <button wire:click="all_cash" tabindex="-1" class="btn btn-outline-primary btn-sm">Pay All
                                    </button>
                                </td>

                            </tr>
                            <tr>
                                <td class="text-center">
                                    <img style="width: 40px;" src="{{asset('assets\img\knet.png')}}" alt="K-Net">
                                </td>
                                <td>
                                    <input wire:model="knet" type="number" name="knet" id="knet" placeholder="K-Net"
                                           class="form-control bg-transparent {{ $errors->has('total_paid') ? 'is-invalid' : '' }}"
                                           step="0.001">
                                </td>
                                <td>
                                    <button wire:click="all_knet" tabindex="-1" class="btn btn-outline-primary btn-sm">Pay All
                                    </button>
                                </td>
                            </tr>

                            <tr>
                                <td class="text-center">
                                    <img style="width: 40px;" src="{{asset('assets\img\visa.png')}}" alt="">
                                </td>
                                <td>
                                    <input wire:model="visa" type="number" name="visa" id="visa" placeholder="VISA"
                                           class="form-control bg-transparent {{ $errors->has('total_paid') ? 'is-invalid' : '' }}"
                                           step="0.001">
                                </td>
                                <td>
                                    <button wire:click="all_visa" tabindex="-1" class="btn btn-outline-primary btn-sm">Pay All
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
                                    <button wire:click="all_master" tabindex="-1" class="btn btn-outline-primary btn-sm">Pay All
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
                                    <button wire:click="all_knet_link" tabindex="-1"
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
                                    <button wire:click="all_credit_link" tabindex="-1"
                                            class="btn btn-outline-primary btn-sm">Pay All
                                    </button>
                                </td>
                            </tr>
                        </table> --}}

                        <div class="col-12">
                            <div class="form-group">
                                <label class="text-primary" for="notes">Notes</label>
                                <input wire:model="notes" class="form-control bg-transparent" type="text" id="notes"
                                       placeholder="notes ..." name="notes">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>


    </div>

    <div class="card-footer text-center">
        @if($action == 'create')
            <button wire:click="balance_confirmation" class="btn btn-sm text-primary">Save</button>
            <a href="{{route('balances.index')}}" class="btn btn-sm">Cancel</a>

        @endif

        @if($action == 'edit')
            <button wire:click="balance_confirmation" class="btn btn-sm text-primary">Update</button>
            <a href="{{route('invoices.index')}}" class="btn btn-sm">Cancel</a>
        @endif

    </div>

</div>


<script>
    window.addEventListener('balance', event => {
        if (confirm('Continue with Balance\nAre you sure?')) {
        @this.balance_confirmed();
        }
    });
</script>



