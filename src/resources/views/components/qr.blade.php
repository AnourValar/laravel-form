@props(['value', 'title' => null, 'style' => 'light'])

@if ($value)
  <div class="alert alert-{{ $style }} alert-dismissible fade show alert-blocks fade-in" role="alert">
    <div>
        {!! $title !!}
        <div id="qrcode-{{ sha1($value) }}"></div>
    </div>
    <button
      type="button"
      class="close"
      data-dismiss="alert"
      aria-label="Close"
    ><span aria-hidden="true">&times;</span></button>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
  <script>
  new QRCode(document.getElementById("qrcode-{{ sha1($value) }}"), {
      text: @json($value, JSON_UNESCAPED_UNICODE),
      width: 256,
      height: 256
  });
  </script>
@endif
