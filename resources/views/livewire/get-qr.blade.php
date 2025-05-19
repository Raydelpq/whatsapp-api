<div class="flex justify-center items-center min-h-screen">
    <div class="grid grid-cols-1 gap-4 justify-items-center">
        <div class="hidden" id="waiting-text">
            <p class="text-2xl animate-pulse">Esperando QR...</p>
        </div>
        <div class="flex flex-col items-center">
            <!--<div>Vincular por Código: <span id="code" style="color: red"></span></div>-->
            <div class="hidden bg-gray-700 text-white px-2 py-1 text-sm" id="qr-timer"></div>
            <div class="relative hidden w-64 h-64 border-black mt-2 transition-opacity duration-500 ease-in-out opacity-0" id="qr-container"></div>
        </div>
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

    // Mostrar texto de espera
    document.getElementById('waiting-text').style.display = 'block';

    let qrTimerInterval; // Variable para almacenar el intervalo del temporizador

    // Recibir QR
    channel.bind('{{ $canal }}', function(data) {
      document.getElementById('waiting-text').style.display = 'none';
      //console.log(data);

      const pairingCode = data.pairingCode;
      document.getElementById('code').innerHTML = pairingCode;
      generar(data.message_qr);
      iniciarContador();
    });

    // Cargando
    channel.bind('{{ $canal }}.LOAD', function(data) {
      mostrarInformacion('Cargando', "Se está vinculando a la API...Espere",true);
    });

    // Autenticado
    channel.bind('{{ $canal }}.AUTH', function(data) {
      mostrarInformacion('Autenticando', "El cliente ya fue autenticado",true);
    });

    // Ready
    channel.bind('{{ $canal }}.READY', function(data) {
      mostrarInformacion('Listo', "Ya se ha sincronizado");
    });

    const generar = (value) => {
        const contenedorQR = document.getElementById('qr-container');
        const contenedorMensaje = document.getElementById('message-container');
        const qrTimer = document.getElementById('qr-timer');
        contenedorQR.innerHTML = '';
        contenedorQR.style.display = 'flex';
        contenedorQR.classList.remove('opacity-0');
        contenedorQR.classList.add('opacity-100');
        qrTimer.style.display = 'block';
        contenedorMensaje.style.display = 'none';
        const QR = new QRCode(contenedorQR);
        QR.makeCode(value);
    }

    const mostrarInformacion = (titulo, mensaje, mostrarSpinner) => {
        const contenedorQR = document.getElementById('qr-container');
        const contenedorMensaje = document.getElementById('message-container');
        const qrTimer = document.getElementById('qr-timer');
        const spinner = document.getElementById('spinner');
        const textoMensaje = document.getElementById('message');

        contenedorQR.style.display = 'none';
        qrTimer.style.display = 'none';

        contenedorMensaje.style.display = 'flex';

        if (mostrarSpinner) {
            spinner.style.display = 'block';

        } else {
            spinner.style.display = 'none';
        }
        textoMensaje.innerHTML = `${titulo}: ${mensaje}`;
    }

    const iniciarContador = () => {
        const qrTimer = document.getElementById('qr-timer');
        let segundos = 0;
        qrTimer.style.display = 'block';

        // Limpiar cualquier temporizador anterior
        if (qrTimerInterval) {
            clearInterval(qrTimerInterval);
        }

        // Iniciar un nuevo temporizador
        qrTimerInterval = setInterval(() => {
            segundos += 1;
            qrTimer.innerHTML = `Tiempo de vida: ${segundos} segundos`;
        }, 1000);
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
