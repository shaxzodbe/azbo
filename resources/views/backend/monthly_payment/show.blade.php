@extends('backend.layouts.app')

@section('content')
<div class="row">
<div class="col-md-8">
  <div class="card">
    <div class="card-header">
      <h2>{{ translate('Customer') }} </h2>

      @php $user =  $application->user @endphp 
    </div>
    <div class="card-body">
      <table class="table aiz-table mb-0">
        <tr>
          <td class="font-weight-bold" style="width: 150px;">{{ translate('Customer Name') }}</td> 
          <td>{{ $user->name }}</td>
        </tr>
        <tr>
          <td class="font-weight-bold">{{ translate('Card') }}</td>
          <td>{{ $user->plastic_serial_number }} , <span class="font-weight-bold">{{ translate('Exiry date') }}:</span> {{ $user->expiry_date }}</td>
        </tr>

        <tr>
          <td class="font-weight-bold"> </td>
          <td>{{ $user->connected_phone_number }} </td>
        </tr>
        <tr>
          <td class="font-weight-bold">{{ translate('Relative Phones Numbers') }}:</td>
         <td>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>{{ translate('phone') }}</th>
                <th>{{ translate('Name') }}</th>
                <th>{{ translate('Owner') }}</th>
              </tr>
            </thead>

            <tr>
              <td>{{ $user->relative_phone_number1 }} </td>
              <td>{{ $user->relative_phone1_name }}</td>
              <td>{{ $user->relative_phone1_owner }}</td>
            </tr>

            <tr>
              <td>{{ $user->relative_phone_number2 }} </td>
              <td>{{ $user->relative_phone2_name }}</td>
              <td>{{ $user->relative_phone2_owner }}</td>
            </tr>

            <tr>
              <td>{{ $user->relative_phone_number3 }} </td>
              <td>{{ $user->relative_phone3_name }}</td>
              <td>{{ $user->relative_phone3_owner }}</td>
            </tr>
          </table>
         </td>
        
        </tr>

        <tr>
          <td class="font-weight-bold">{{ translate('Place Work Name') }}</td>
          <td>{{ $user->work_place_name }}</td>
        </tr>

        <tr>
          <td class="font-weight-bold">{{ translate('Salary Month') }} </td>
          <td>{{ $user->month_salary }}</td>
        </tr>
        <tr>
          <td class="font-weight-bold"> </td>
          <td>{{ $user->amount_salary }}</td>
        </tr>

        <tr>
          <td class="font-weight-bold">{{ translate('Is Paying Credit') }}: </td>
          <td>{{ $user->is_paying_credit ? 'Yes' : 'No'}} @if($user->is_paying_credit) - {{ translate('Amount Credit') }}: {{ $user->amount_credit }} @endif</td>
        </tr>

        <tr>
          <td class="font-weight-bold">{{ translate('Passport Image') }}:</td>
          <td><img src="{{ uploaded_asset($user->passport_image) }}" alt="" class="img-fluid"></td>
        </tr>

        <tr>
          <td class="font-weight-bold">{{ translate('Photo with Passport') }}</td>
          <td><img src="{{ uploaded_asset($user->photo_with_passport) }}" alt="" class="img-fluid"></td>
        </tr>
      </table>
    </div>
  </div>
</div>
<div class="col-md-4">
  <div class="card">
    <div class="card-header">
      <h3>Status</h3>
    </div>
    <div class="card-body">
      <div class="form-group">
        <select id="update_app_status" class="form-control">
          <option value="pending" @if($application->status == 'pending') selected @endif>{{ translate('Pending') }}</option>
          <option value="approved" @if($application->status == 'approved') selected @endif>{{ translate('Approved') }}</option>
          <option value="disapproved" @if($application->status == 'disapproved') selected @endif>{{ translate('Disaproved') }}</option>
        </select>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header"><h2>{{ translate('Product') }}</h2></div>
    <img class="card-img-top" src="{{ uploaded_asset($application->product->thumbnail_img) }}" alt="Card image cap">
    <div class="card-body">
      <h5>{{ $application->product->name }} @if($application->variant) - {{ $application->variant }} @endif</h5>
      
      {{ translate('Monthly price') }}: {{single_price($application->price)}}
    </div>
  </div>

  <div class="card">
    <div class="card-header"><h3>{{ translate('Installment') }}</h3></div>
    <div class="card-body">
      @php $details =  json_decode($application->details); @endphp
      
      {{ translate('Period') }}: {{ $details->name}}
      <br>
      {{ translate('Profit') }}: {{ $details->profit}}
      <br>
      {{ translate('Percent') }}: {{ $details->percent}}
    </div>
  </div>
</div>
</div>

@endsection 

@section('script')

  <script>
    $('#update_app_status').on('change', function(){
      var application_id = {{ $application->id }};
      var status = $('#update_app_status').val();
      $.post('{{ route('monthly_payment.update_status') }}', {_token:'{{ @csrf_token() }}',application_id:application_id,status:status}, function(data){
          AIZ.plugins.notify('success', '{{ translate('Payment status has been updated') }}');
      });
    });
  </script>
@endsection 