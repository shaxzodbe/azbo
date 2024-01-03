@extends('frontend.layouts.app')

@section('content')
<section class="pt-5 mb-4">
  <div class="container  pb-4 pt-md-4 min-vh-80">
    <div class="row">

      <div class="col-md-8 offset-md-2">
        <div class="card">
          <div class="card-header">
            <h4>{{ translate('Order Code:') }} <span class="text-danger">#{{ $data['code'] }}</span> </h4>
            
          </div>
          <div class="card-body">
            <h5>
              {{ translate('Thank you, you request has been placed successfully') }} !
            </h5>
            <p>{{ translate('Our guys will contact you as soon as possible to confirm the order.')}}</p>
          </div>
        </div>
      </div>
    </div>
</section>
@endsection
