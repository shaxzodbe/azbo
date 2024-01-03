    @extends('backend.layouts.app')

    @section('content')

    <div class="row">

        <!-- PAYSYS -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6 ">{{translate('PaySys Credential')}}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('payment_method.update') }}" method="POST">
                        <input type="hidden" name="payment_method" value="paysys">
                        @csrf
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="PAYSYS_VENDOR_ID">
                            <div class="col-md-4">
                                <label class="col-from-label">{{translate('PaySys Vendor ID')}}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="PAYSYS_VENDOR_ID" value="{{  env('PAYSYS_VENDOR_ID') }}" placeholder="{{ translate('Paysys Vendor Id') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="PAYSYS_SECRET_KEY">
                            <div class="col-md-4">
                                <label class="col-from-label">{{translate('PaySys Secret Key')}}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="PAYSYS_SECRET_KEY" value="{{  env('PAYSYS_SECRET_KEY') }}" placeholder="{{ translate('Paysys Secret Key') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="col-from-label">{{translate('PaySys Sandbox Mode')}}</label>
                            </div>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" name="paysys_sandbox" type="checkbox" @if (\App\BusinessSetting::where('type', 'paysys_sandbox')->first() && \App\BusinessSetting::where('type', 'paysys_sandbox')->first()->value == 1)
                                        checked
                                    @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- END PAYSYS -->

        <!-- PAYCOM -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6 ">{{translate('PAYCOM Credential')}}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('payment_method.update') }}" method="POST">
                        <input type="hidden" name="payment_method" value="paycom">
                        @csrf
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="PAYCOM_VENDOR_ID">
                            <div class="col-md-4">
                                <label class="col-from-label">{{translate('PAYCOM VENDOR ID')}}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="PAYCOM_VENDOR_ID" value="{{  env('PAYCOM_VENDOR_ID') }}" placeholder="{{ translate('PAYCOM VENDOR ID') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="PAYCOM_CHECKOUT_KEY">
                            <div class="col-md-4">
                                <label class="col-from-label">{{translate('PAYCOM CHECKOUT KEY')}}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="PAYCOM_CHECKOUT_KEY" value="{{  env('PAYCOM_CHECKOUT_KEY') }}" placeholder="{{ translate('PAYCOM CHECKOUT KEY') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="PAYCOM_TEST_KEY">
                            <div class="col-md-4">
                                <label class="col-from-label">{{translate('PAYCOM TEST KEY')}}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" name="PAYCOM_TEST_KEY" value="{{  env('PAYCOM_TEST_KEY') }}" placeholder="{{ translate('PAYCOM TEST KEY') }}" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="col-from-label">{{translate('PAYCOM Sandbox Mode')}}</label>
                            </div>
                            <div class="col-md-8">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input value="1" name="paycom_sandbox" type="checkbox" @if (\App\BusinessSetting::where('type', 'paycom_sandbox')->first() && \App\BusinessSetting::where('type', 'paycom_sandbox')->first()->value == 1)
                                        checked
                                    @endif>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- END PAYCOM -->


    </div>

    <div class="row">
        <div class="col-md-6">
        <!-- monthly payment -->
        <div class="card">
          <div class="card-header">
            <h5 class="mb-0 h6">Monthly payment</h5>
          </div>
          <div class="card-body">
            <form class="form-horizontal" action="{{ route('monthly_payment.update') }}" method="POST">
              @csrf
              <input type="hidden" name="type" value="alif_bank_fee">
            <div class="form-group row">
              <div class="col-md-4"> 
                <label class="col-form-label">
                  Bank fee  (%)
                </label>
              </div>
              <div class="col-md-8">
                <input type="number"  value="<?= (\App\BusinessSetting::where('type', 'alif_bank_fee')->first()->value) ? \App\BusinessSetting::where('type', 'alif_bank_fee')->first()->value : 0 ?>"
                  name="alif_bank_fee" class="form-control" required>
              </div>
            </div>
            
            <div class="form-group mb-0 text-right">
                <button type="submit" class="btn btn-sm btn-primary">Save</button>
            </div>
            </form>
          </div>
        </div>

        <!-- end monthly payment -->
      </div>
    
    
      <div class="col-md-6">
        <div class="card">
          <div class="card-header">
            <h5 class="mb-0 h6">Monthly payment</h5>
            
          </div>
          <div class="card-body">
            <form action="{{ route('monthly_payment.update') }}" method="POST">
              @csrf
              
              <input type="hidden"  name="type" value="alif_instalments">

              <?php $instalments = json_decode(\App\BusinessSetting::where('type', 'alif_instalments')->first()->value) ?>
              @foreach ($instalments as $instalment)
              <div class="form-group row">
                <label class="col-md-3 col-form-label"> {{ $instalment->label}}</label>

                <div class="col-md-6">
                    
                  <input type="number" value="{{ (int) $instalment->value }}"
                    placeholder="enter number" name="{{ $instalment->name  }}" class="form-control" required>
                </div>
                <div class="col-md-1">
                  <label class="aiz-switch aiz-switch-success mb-0">
                    <input value="1" type="checkbox" name="{{ $instalment->name_active }}" @if($instalment->active) checked @endif>
                    <span></span>
                  </label>
                </div>
              </div>
              @endforeach
              <div class="form-group mb-0 text-right">
                <button type="submit" class="btn btn-sm btn-primary">Save</button>
              </div>
          </form >
          </div>
        </div>
      </div>
    </div>
@endsection
