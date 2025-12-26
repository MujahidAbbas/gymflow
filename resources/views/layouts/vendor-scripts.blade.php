{{-- jQuery - loaded first as other scripts may depend on it --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="{{ URL::asset('build/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ URL::asset('build/js/plugins.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/datatables-bootstrap5.js')}}"></script>
<script src="{{ URL::asset('build/js/pages/tables-datatables-advanced.js')}}"></script>
{{-- SweetAlert2 - correct path --}}
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js')}}"></script>
{{-- Flatpickr and Choices.js - loaded with absolute URLs --}}
<script src="{{ URL::asset('build/libs/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{ URL::asset('build/libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>
<script src="{{ asset('js/custom.js')}}"></script>
@yield('script')
@stack('scripts')
@yield('script-bottom')

