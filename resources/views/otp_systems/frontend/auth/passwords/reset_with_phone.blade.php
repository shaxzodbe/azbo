@extends('frontend.layouts.app')

@section('content')

<section class="gry-bg py-5">
<div class="container">
    <div class="row">



<div class="col-xxl-4 col-xl-5 col-lg-6 col-md-8 mx-auto">
    <div class="cls-content-sm card">
        
            <div class="card-body">
                <h1 class="h3">{{ __('Reset Password') }}</h1>
                <p class="pad-btm">{{__('Enter your phone, code and new password and confirm password.')}} </p>
                <form method="POST" action="{{ route('password.update.phone') }}">
                    @csrf
    
                    <div class="form-group">
                        <input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="phone" value="{{ $email ?? old('email') }}" placeholder="Phone" required autofocus>
    
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
    
                    <div class="form-group">
                        <input id="email" type="text" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="code" value="{{ $email ?? old('email') }}" placeholder="Code" required autofocus>
    
                        @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
    
                    <div class="form-group">
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="New Password" required>
    
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
    
                    <div class="form-group">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
                    </div>
    
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </form>
            </div>
        
    </div>
</div>


</div>
</div>

</section>
@endsection
