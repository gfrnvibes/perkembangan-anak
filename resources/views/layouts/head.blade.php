@yield('css')
<!-- Bootstrap Css -->
<link href="{{ URL::asset('/assets/css/bootstrap.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{ URL::asset('/assets/css/icons.css')}}" id="icons-style" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{ URL::asset('/assets/css/app.css')}}" id="app-style" rel="stylesheet" type="text/css" />
{{-- Bootstrap Icon --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<script src="{{ asset('assets/libs/chart.js/chart.min.js') }}"></script>
@stack('scripts')