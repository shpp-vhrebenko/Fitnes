
@section('footer-scripts')
<script src="{{asset('js/lib/popper.min.js') }}"></script>
<script  src="{{asset('js/lib/bootstrap.min.js') }}"></script>
@show

@section('footer-assets')
  <!-- include summernote css/js -->
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">
<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>

<link href="{{ asset('css/datepicker.min.css') }}" rel="stylesheet">
<script src="{{ asset('js/datepicker.min.js') }}"></script>
<script src="{{ asset('js/lib/bootstrap.min.js') }}"></script>

<style>
  .dropdown:hover>.dropdown-menu {
    display: block;
  }
</style>

<script>
  $(document).ready(function() {
    $('.summernote').summernote({
      height: 200
    });
    $('[data-toggle="datepicker"]').datepicker({
      format: 'yyyy-mm-dd'
    });
  });
</script>
@show

@section('footer-modal')
@show
