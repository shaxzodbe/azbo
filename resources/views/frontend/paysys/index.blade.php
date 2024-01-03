@extends('frontend.layouts.app')

@section('content')

    <form action="{{ $url }}" method="POST" id='paysys-pay' style="display: none;">
        
        @forelse ($params as $name => $p)
            <input type="text" name="{{ $name }}" value="{{ $p }}" >
        @empty
        @endforelse
        <input type="hidden" name="_token" value="{!!csrf_token()!!}">

        <!-- <input type="submit" class="btn btn-danger" value="pay"> -->
    </form>

@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function(){
            $('#paysys-pay').submit()
        });
    </script>
@endsection