@extends('backend.layouts.app')

@section('content')

<div class="card">

  <div class="card-body">
    <table class="table aiz-table mb-0">
      <thead>
          <tr>
              <th>#</th>
              <th>{{ translate('Payment Code') }}</th>
              <th> Product Owner </th>
              <th>{{ translate('Customer Name') }}</th>
              <th data-breakpoints="md">{{ translate('Prouct Name') }} </th>
              <th data-breakpoints="md">{{ translate('Quantity') }}</th>
              <th data-breakpoints="md">{{ translate('Amount') }}</th>
              <th data-breakpoints="md">{{ translate('Period') }}</th>
              <th data-breakpoints="md">{{ translate('Status') }}</th>
            
              <th class="text-right" width="15%">{{translate('options')}}</th>
          </tr>
      </thead>
      <tbody>

        @foreach ($applications as $key => $app)
            @if($app->user) 
            <tr>
              <td>{{ $key + 1 }} </td>
              <td>{{ $app->code }} </td>
              <td>{{ $app->product ? $app->product->user->name : '-' }}</td>
              <td>{{ $app->user->name }}</td>
              <td>{{ isset($app->product) ? $app->product->name : '' }} @if($app->variant) - {{ get_variant_string($app->variant) }} @endif</td>
              <td>{{ $app->quantity }} </td>
              <td>{{ single_price($app->price) }}</td>
             
              <td>{{ json_decode($app->details)->name }}</td>
             
              <td style="text-transform: capitalize"> 
              
                @if($app->status == 'pending')
                  <span class="badge badge-inline badge-primary">{{ translate($app->status) }}</span> 
                @elseif($app->status == 'approved') 
                  <span class="badge badge-inline badge-success">{{ translate($app->status) }}</span> 
                @elseif($app->status == 'disapproved')
                  <span class="badge badge-inline badge-danger">{{ translate($app->status) }}</span> 
                @else 
                @endif 
                </td>
              
              <td class="text-right">
                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('monthly_payment.show', encrypt($app->id))}}" title="{{ translate('View') }}">
                  <i class="las la-eye"></i>
                </a>


                <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('monthly_payment.customer.edit', $app->user->id)}}" title="{{ translate('Edit') }}">
                  <i class="las la-edit"></i>
              </a>
              </td>
            
            </tr>
            @endif
        @endforeach
        <tr>
          <td></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
@endsection 