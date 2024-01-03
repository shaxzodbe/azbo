@extends('frontend.layouts.app')

@section('content')
    <section class="gry-bg py-4">
        <div class="profile">
            <div class="container">
                <div class="row">
                    <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-8 mx-auto">
                        <div class="card">
                            <div class="text-center pt-4">
                                <img src="{{static_asset('assets/img/logo-intend.png')}}" alt="topilmadi">
                            </div>
                            <div class="px-4 py-3 py-lg-4">
                                <div class="">
                                    @if ($message = \Illuminate\Support\Facades\Session::get('error'))
                                        <div class="alert alert-danger alert-block">
                                            <button type="button" class="close" data-dismiss="alert">×</button>
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @endif
                                    <form id="reg-form" class="form-default" role="form"
                                          action="{{ route('post.intent_auth') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{$id}}">
                                        <div class="form-group phone-form-group mb-3">
                                            <input type="tel" id="phone-code"
                                                   class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                                   value="{{ old('phone') }}" placeholder="" name="phone"
                                                   autocomplete="off">
                                        </div>
                                        <input type="hidden" name="country_code" value="">
                                        <div class="form-group">
                                            <input type="password"
                                                   class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                                   placeholder="{{  translate('Password') }}" name="password">
                                            @if ($errors->has('password'))
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $errors->first('password') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        <div class="form-group">
                                            <input type="password" class="form-control"
                                                   placeholder="{{  translate('Confirm Password') }}"
                                                   name="password_confirmation">
                                        </div>

                                        <div class="mb-4">
                                            <button type="submit"
                                                    class="btn btn-primary btn-block fw-600">{{ __('Kirish') }}</button>
                                        </div>
                                    </form>
                                        <div class="text-center">
                                            <p class="text-muted mb-0">Intend {{ translate('Dont have an account?')}}</p>
                                            <a href="{{ url('https://reg.intend.uz/login?back_uri=https://azbo.loc/intent-auth&l=uz') }}">{{ translate('Register Now')}}</a>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection


@section('script')
    <script type="text/javascript">
        var isPhoneShown = true,
            countryData = window.intlTelInputGlobals.getCountryData(),
            input = document.querySelector("#phone-code");

        for (var i = 0; i < countryData.length; i++) {
            var country = countryData[i];
            if (country.iso2 == 'bd') {
                country.dialCode = '88';
            }
        }

        var iti = intlTelInput(input, {
            separateDialCode: true,
            utilsScript: "{{ static_asset('assets/js/intlTelutils.js') }}?1590403638580",
            onlyCountries: @php echo json_encode(\App\Country::where('status', 1)->pluck('code')->toArray()) @endphp,
            customPlaceholder: function (selectedCountryPlaceholder, selectedCountryData) {
                if (selectedCountryData.iso2 == 'bd') {
                    return "01xxxxxxxxx";
                }
                return selectedCountryPlaceholder;
            }
        });

        var country = iti.getSelectedCountryData();
        $('input[name=country_code]').val(country.dialCode);

        input.addEventListener("countrychange", function (e) {
            // var currentMask = e.currentTarget.placeholder;

            var country = iti.getSelectedCountryData();
            $('input[name=country_code]').val(country.dialCode);

        });

        function toggleEmailPhone(el) {
            if (isPhoneShown) {
                $('.phone-form-group').addClass('d-none');
                $('.email-form-group').removeClass('d-none');
                isPhoneShown = false;
                $(el).html('{{ translate('Use Phone Instead') }}');
            } else {
                $('.phone-form-group').removeClass('d-none');
                $('.email-form-group').addClass('d-none');
                isPhoneShown = true;
                $(el).html('{{ translate('Use Email Instead') }}');
            }
        }
    </script>
@endsection
