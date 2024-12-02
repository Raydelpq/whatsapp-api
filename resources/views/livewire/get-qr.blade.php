<div>
    <div class="flex justify-center items-center min-h-screen">
        <div class="w-64 h-64 border-black" id="qr-container"></div>
        <div class="hidden justify-center items-center min-h-screen" id="message-container">
            <div id="spinner" class="hidden">
                <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291a7.953 7.953 0 01-2-5.291H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <div id="message" class="text-center text-2xl"></div>
        </div>
    </div>
</div>
@push('code-js')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    const pusher = new Pusher('{{ $pusherKey }}', {
      cluster: 'us2'
    });

    const channel = pusher.subscribe('public');

    // Recibir QR
    channel.bind('{{ $canal }}', function(data) {
      generar(data.message_qr);
    });

    // Autenticado
    channel.bind('{{ $canal }}.AUTH', function(data) {
      mostrarInformacion('Autenticado', data.message, true);
    });

    // Ready
    channel.bind('{{ $canal }}.READY', function(data) {
      mostrarInformacion('Listo', data.message, false);
    });

    const generar = (value) => {
        const contenedorQR = document.getElementById('qr-container');
        const contenedorMensaje = document.getElementById('message-container');
        contenedorQR.innerHTML = '';
        contenedorQR.style.display = 'flex';
        contenedorMensaje.style.display = 'none';
        const QR = new QRCode(contenedorQR, {
            width: contenedorQR.offsetWidth,
            height: contenedorQR.offsetHeight
        });
        QR.makeCode(value);
    }

    const mostrarInformacion = (titulo, mensaje, mostrarSpinner) => {
        const contenedorQR = document.getElementById('qr-container');
        const contenedorMensaje = document.getElementById('message-container');
        const spinner = document.getElementById('spinner');
        const textoMensaje = document.getElementById('message');

        contenedorQR.style.display = 'none';
        contenedorMensaje.style.display = 'flex';

        if (mostrarSpinner) {
            spinner.style.display = 'block';
            textoMensaje.innerHTML = '';
        } else {
            spinner.style.display = 'none';
            textoMensaje.innerHTML = `${titulo}: ${mensaje}`;
        }
    }

    // Ajustar el tamaño del QR según el tamaño de la ventana
    window.addEventListener('resize', () => {
        const contenedorQR = document.getElementById('qr-container');
        const value = contenedorQR.innerText; // Obtener el valor actual del QR
        if (value) {
            generar(value); // Regenerar el QR con el nuevo tamaño
        }
    });
</script>
@endpush

