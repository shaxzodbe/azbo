@extends('frontend.layouts.app')

@section('css')


@endsection
@section('content')
<section class="pt-5 mb-4">
  <div class="container  pb-4 pt-md-4 min-vh-80">
    <div class="row">

      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h4>{{ translate('Personal Information') }}</h4>
          </div>
          <div class="card-body">

            <form action="{{route('storeapp')}}" method="POST">
              @csrf
              <div class="form-group row">
                <label for="plastic_serial_number"
                  class="col-md-3 col-form-label font-weight-bold ">{{ translate('Plastic Serial Number:') }} </label>
                <div class="col-md-9">
                  <input type="text" name="plastic_serial_number" id="plastic_serial_number" class="form-control"
                    value="{{ Auth::user()->plastic_serial_number ?? null }}">
                </div>
              </div>

              <div class="form-group row">
                <label for="expiry_date"
                  class="col-md-3 col-form-label font-weight-bold">{{ translate('Expiry Date')}}:</label>
                <div class="col-md-9">
                  <input type="text" name="expiry_date" id="expiry_date" class="form-control" placeholder="MM/YY"
                    value="{{ Auth::user()->expiry_date ?? null}}">
                </div>
              </div>

              <div class="form-group row">
                <label for="connected_phone_number"
                  class="col-md-3 col-form-label font-weight-bold">{{ translate('Connected Phone Number To Plastic Card')}}:</label>
                <div class="col-md-9">
                  <input type="text" name="connected_phone_number" id="connected_phone_number" class="form-control"
                    value="{{ Auth::user()->connected_phone_number ?? null }}">
                </div>
              </div>

              <div class="form-group row ">
                <label for="relative_phone_number1" class="col-md-3 col-form-label font-weight-bold">
                  {{ translate('Nearset Relative Phone Number')}} 1:</label>

                <div class="col-md-9">
                  <div class="row">
                    <div class="col-sm-3">
                      <input type="text"  name="relative_phone_number1" class="form-control" placeholder="+998"
                        required>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group row">
                        <label for="relative_phone1_name"
                          class="col-sm-4 col-form-label pr-0 font-weight-bold">{{ translate('Name')}}:</label>
                        <div class="col-sm-8">
                          <input type="text" name="relative_phone1_name" id="relative_phone1_name" class="form-control"
                            value="{{ Auth::user()->relative_phone1_name ?? null }}">
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
                            class="form-control" value="{{ Auth::user()->relative_phone1_owner ?? null }}">
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
                        value="{{ Auth::user()->relative_phone_number2 ?? null}}">
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group row">
                        <label for="relative_phone2_name"
                          class="col-sm-4 col-form-label pr-0 font-weight-bold">{{ translate('Name') }}:</label>
                        <div class="col-sm-8">
                          <input type="text" name="relative_phone2_name" id="relative_phone2_name" class="form-control"
                            value="{{ Auth::user()->relative_phone2_name  ?? null}}">
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
                            value="{{ Auth::user()->relative_phone2_owner ?? null }}">
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
                        value="{{ Auth::user()->relative_phone_number3 ?? null }}">

                    </div>
                    <div class="col-sm-4">
                      <div class="form-group row">
                        <label for="relative_phone3_name"
                          class="col-sm-4 col-form-label pr-0 font-weight-bold">{{ translate('Name')}}:</label>
                        <div class="col-sm-8">
                          <input type="text" name="relative_phone3_name" id="relative_phone3_name" class="form-control"
                            value="{{ Auth::user()->relative_phone3_name ?? null }}">
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-5">
                      <div class="form-group row">
                        <label for="relative_phone3_owner" class="col-sm-4 col-form-label pr-0 font-weight-bold">
                          {{ translate('Who is he/she for you') }}:</label>
                        <div class="col-sm-8">
                          <input type="text" name="relative_phone3_owner" class="form-control"
                            value="{{ Auth::user()->relative_phone3_owner ?? null }}">
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
                        value="{{ Auth::user()->work_place_name ?? null }}">
                    </div>
                    <div class="col-sm-7">
                      <div class="form-group row">
                        <label for="month_salary"
                          class="col-sm-3 col-form-label pr-0 font-weight-bold">{{ translate('You Salary Per Months')}}:</label>
                        <div class="col-sm-9">
                          <input type="number" name="month_salary" id="month_salary" class="form-control"
                            value="{{ Auth::user()->month_salary ?? null}}">
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
                    value="{{ Auth::user()->amount_salary ?? null }}">
                </div>
              </div>

              <div class="form-group row">
                <label for="amount_credit" class="col-md-3 col-form-label font-weight-bold">
                  {{ translate('Are You Paying for Other Credit') }}?:
                </label>

                <div class="col-md-1" style="margin-top: auto; margin-bottom: auto;">
                  <label class="aiz-switch aiz-switch-success mb-0">
                    <input type="checkbox" name="is_paying_credit" onchange="amount_credit_changed(this)"
                      @if(isset(Auth::user()->is_paying_credit)) checked @endif>
                    <span class="slider round"></span>
                  </label>
                </div>
                <div class="col-md-8">
                  <input type="text" name="amount_credit" id="amount_credit" class="form-control"
                    placeholder="Fill amount here" value="{{ Auth::user()->amount_credit ?? null }}"
                    @if(!isset(Auth::user()->is_paying_credit) ) disabled @endif>
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
                    <input type="hidden" name="passport_image" value="{{ Auth::user()->passport_image ?? null }}"
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
                    <input type="hidden" name="photo_with_passport" value="{{ Auth::user()->photo_with_passport ?? null }}"
                      class="selected-files">
                  </div>
                  <div class="file-preview box sm">
                  </div>
                </div>
              </div>

              <button type="submit" class="btn btn-primary" style="border-radius: 0px;">{{ translate('Save') }}</button>
            </form>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection

@section('script')

<script>
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
