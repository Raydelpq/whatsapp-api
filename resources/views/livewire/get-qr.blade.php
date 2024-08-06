<div>
    <div class="w-24 h-24 border-black" id="qr-container"></div>
</div>
@push('code-js')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    const pusher = new Pusher('{{ $pusherKey }}', {
      cluster: 'us2'
    });

    const channel = pusher.subscribe('public');
    channel.bind('{{ $canal }}', function(data) {
      generar(data.message_qr);
    });

    const generar = (value) => {
        const contenedorQR = document.getElementById('qr-container');
        contenedorQR.innerHTML = '';
        const QR = new QRCode(contenedorQR);
        QR.makeCode(value);
    }
</script>
@endpush