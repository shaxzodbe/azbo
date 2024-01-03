@extends('backend.layouts.app')

@section('content')
<div class="card">
  <div class="card-header">
    <h3>Customer #{{ $user->id }}: {{ $user->name }} </h3>
  </div>
  <div class="card-body">
    <form action="{{ route('monthly_payment.customer.update') }}" method="POST">
      @csrf
      <input type="hidden" name="user_id" value="{{ encrypt($user->id)}}">
      <div class="form-group row">
                <label for="plastic_serial_number"
                  class="col-md-3 col-form-label font-weight-bold ">{{ translate('Plastic Serial Number:') }} </label>
                <div class="col-md-9">
                  <input type="text" name="plastic_serial_number" id="plastic_serial_number" class="form-control"
                    value="{{ $user->plastic_serial_number }}">
                </div>
              </div>

              <div class="form-group row">
                <label for="expiry_date"
                  class="col-md-3 col-form-label font-weight-bold">{{ translate('Expiry Date')}}:</label>
                <div class="col-md-9">
                  <input type="text" name="expiry_date" id="expiry_date" class="form-control" placeholder="MM/YY"
                    value="{{ $user->expiry_date }}">
                </div>
              </div>

              <div class="form-group row">
                <label for="connected_phone_number"
                  class="col-md-3 col-form-label font-weight-bold">{{ translate('Connected Phone Number To Plastic Card')}}:</label>
                <div class="col-md-9">
                  <input type="text" name="connected_phone_number" id="connected_phone_number" class="form-control"
                    value="{{ $user->connected_phone_number }}">
                </div>
              </div>

              <div class="form-group row ">
                <label for="relative_phone_number1" class="col-md-3 col-form-label font-weight-bold">
                  {{ translate('Nearset Relative Phone Number')}} 1:</label>

                <div class="col-md-9">
                  <div class="row">
                    <div class="col-sm-3">
                      <input type="text" name="relative_phone_number1" class="form-control"
                        value="{{ $user->relative_phone_number1 }}">
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group row">
                        <label for="relative_phone1_name"
                          class="col-sm-4 col-form-label pr-0 font-weight-bold">{{ translate('Name')}}:</label>
                        <div class="col-sm-8">
                          <input type="text" name="relative_phone1_name" id="relative_phone1_name" class="form-control"
                            value="{{ $user->relative_phone1_name }}">
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-5">
                      <div class="form-group row">
                        <label for="relative_phone1_owner"
                          class="col-sm-4 col-form-label pr-0 font-weight-bold font-weight-bold">
                          {{ translate('Who is he/she for you')}}:</label>
                        <div class="col-sm-8">
                          <input type="text" name="relative_phone1_owner" id="relative_phone1_owner"
                            class="form-control" value="{{ $user->relative_phone1_owner }}">
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
              </div>


              <div class="form-group row">
                <label for="relative_phone_number2"
                  class="col-md-3 col-form-label font-weight-bold">
                  {{ translate('Nearset Relative Phone Number')}}2:
                </label>
                <div class="col-md-9">

                  <div class="row">
                    <div class="col-sm-3">
                      <input type="text" name="relative_phone_number2" id="relative_phone_number2" class="form-control"
                        value="{{ $user->relative_phone_number2 }}">
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group row">
                        <label for="relative_phone2_name"
                          class="col-sm-4 col-form-label pr-0 font-weight-bold">{{ translate('Name') }}:</label>
                        <div class="col-sm-8">
                          <input type="text" name="relative_phone2_name" id="relative_phone2_name" class="form-control"
                            value="{{ $user->relative_phone2_name }}">
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-5">
                      <div class="form-group row">
                        <label for="relative_phone2_owner" id="relative_phone2_owner"
                          class="col-sm-4 col-form-label pr-0 font-weight-bold">
                          {{ translate('Who is he/she for you')}}:
                        </label>
                        <div class="col-sm-8">
                          <input type="text" name="relative_phone2_owner" class="form-control"
                            value="{{ $user->relative_phone2_owner }}">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label for="relative_phone_number3"
                  class="col-md-3 col-form-label font-weight-bold">
                  {{ translate('Nearset Relative Phone Number')}} 3:
                </label>
                <div class="col-md-9">

                  <div class="row">
                    <div class="col-sm-3">
                      <input type="text" name="relative_phone_number3" id="relative_phone_number3" class="form-control"
                        value="{{ $user->relative_phone_number3 }}">

                    </div>
                    <div class="col-sm-4">
                      <div class="form-group row">
                        <label for="relative_phone3_name"
                          class="col-sm-4 col-form-label pr-0 font-weight-bold">{{ translate('Name')}}:</label>
                        <div class="col-sm-8">
                          <input type="text" name="relative_phone3_name" id="relative_phone3_name" class="form-control"
                            value="{{ $user->relative_phone3_name }}">
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-5">
                      <div class="form-group row">
                        <label for="relative_phone3_owner" class="col-sm-4 col-form-label pr-0 font-weight-bold">
                          {{ translate('Who is he/she for you') }}:</label>
                        <div class="col-sm-8">
                          <input type="text" name="relative_phone3_owner" class="form-control"
                            value="{{ $user->relative_phone3_owner }}">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


              <div class="form-group row">
                <label for="work_place_name"
                  class="col-md-3 col-form-label font-weight-bold">{{ translate('Where Do You Work') }}?
                  ({{ translate('Name Of Place')}}):</label>
                <div class="col-md-9">
                  <div class="row">
                    <div class="col-sm-5">
                      <input type="text" name="work_place_name" id="work_place_name" class="form-control"
                        value="{{ $user->work_place_name }}">
                    </div>
                    <div class="col-sm-7">
                      <div class="form-group row">
                        <label for="month_salary"
                          class="col-sm-3 col-form-label pr-0 font-weight-bold">{{ translate('You Salary Per Months')}}:</label>
                        <div class="col-sm-9">
                          <input type="number" name="month_salary" id="month_salary" class="form-control"
                            value="{{ $user->month_salary }}">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <label for="amount_salary"
                  class="col-md-3 col-form-label font-weight-bold">{{ translate('Amount Salary Into Card')}}:</label>
                <div class="col-md-9">
                  <input type="number" name="amount_salary" id="amount_salary" class="form-control"
                    value="{{ $user->amount_salary }}">
                </div>
              </div>

              <div class="form-group row">
                <label for="amount_credit" class="col-md-3 col-form-label font-weight-bold">
                  {{ translate('Are You Paying for Other Credit') }}?:
                </label>

                <div class="col-md-1" style="margin-top: auto; margin-bottom: auto;">
                  <label class="aiz-switch aiz-switch-success mb-0">
                    <input type="checkbox" name="is_paying_credit" onchange="amount_credit_changed(this)"
                      @if($user->is_paying_credit) checked @endif>
                    <span class="slider round"></span>
                  </label>
                </div>
                <div class="col-md-8">
                  <input type="text" name="amount_credit" id="amount_credit" class="form-control"
                    placeholder="Fill amount here" value="{{ $user->amount_credit }}"
                    @if(!$user->is_paying_credit) disabled @endif>
                </div>
              </div>



              <div class="form-group row">
                <label for="passport_image"
                  class="col-md-3 col-form-label font-weight-bold">{{ translate('Passport Scan Copy')}}:</label>
                <div class="col-md-9">
                  <div class="input-group" data-toggle="aizuploader" data-type="image">
                    <div class="input-group-prepend">
                      <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                    </div>
                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                    <input type="hidden" name="passport_image" value="{{ $user->passport_image }}"
                      class="selected-files">
                  </div>
                  <div class="file-preview box sm">
                  </div>
                </div>
              </div>

              <div class="form-group row">
                <label for="photo_with_passport" class="col-md-3 col-form-label font-weight-bold">
                  {{ translate('Your Photo With Passport') }}:</label>
                <div class="col-md-9">
                  <div class="input-group" data-toggle="aizuploader" data-type="image">
                    <div class="input-group-prepend">
                      <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                    </div>
                    <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                    <input type="hidden" name="photo_with_passport" value="{{ $user->photo_with_passport }}"
                      class="selected-files">
                  </div>
                  <div class="file-preview box sm">
                  </div>
                </div>
              </div>

      <button type="submit" class="btn btn-primary" style="border-radius: 0px;">{{ translate('Save') }}</button>

      <button type="button" class="btn btn-warning" style="border-radius: 0px;" onclick="returnBack()">{{ translate('Back') }}</button>
    </form>
  </div>
</div>
@endsection 

@section('script')
<script>
 function returnBack() {
   document.location.replace('{{ route('monthly_payment.index') }}')
 }

 function amount_credit_changed(el) {
   if(!el.checked) {
     var amount_credit_input = document.getElementById('amount_credit')
     amount_credit_input.disabled = true; 
     amount_credit_input.value = 0; 
     
   } else {
    var amount_credit_input = document.getElementById('amount_credit')
     amount_credit_input.disabled = false; 
   }
 }

 
</script>
@endsection 