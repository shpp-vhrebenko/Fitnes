
@section('footer-scripts')
<script src="{{asset('js/lib/popper.min.js') }}"></script>
<script  src="{{asset('js/lib/bootstrap.min.js') }}"></script>
@show

@section('footer-assets')
  <!-- include summernote css/js -->
<link href="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">

<link href="{{ asset('css/datepicker.min.css') }}" rel="stylesheet">
<script src="{{ asset('js/datepicker.min.js') }}"></script>

<script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.js"></script>
<script src="{{ asset('js/lib/summernote_acordion.js') }}"></script>
<style>
  .dropdown:hover>.dropdown-menu {
    display: block;
  }
</style>

<script>
  $(document).ready(function() {
    $('.summernote').summernote({
      height: 200,
      toolbar: [        
        ['style', ['bold', 'italic', 'underline']],
        ['para', ['style']],
        ['insert', ['link', 'picture', 'video']],
        ['misc', ['accordion', 'codeview']],
      ],
      disableResizeEditor: true
    });
    $('[data-toggle="datepicker"]').datepicker({      
      monthsShort: [ "Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],
      format: 'yyyy-mm-dd'
    });
    $('[data-toggle="tooltip"]').tooltip();
  });
</script>
@show

@section('footer-modal')
@show
