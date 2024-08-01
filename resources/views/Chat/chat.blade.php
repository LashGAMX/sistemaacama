<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emoji Picker</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .container-center {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }
        #sendMessageForm {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.2);
            width: 400px; /* Ajusta el ancho del formulario según tus necesidades */
            box-sizing: border-box;
        }
        .emoji-picker-wrapper {
            position: relative;
        }
        .emoji-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr); /* Mostrar 5 emojis por fila */
            gap: 5px;
            width: 100%;
            max-height: 250px; /* Ajusta la altura máxima */
            overflow-y: auto; /* Agrega scroll si es necesario */
            background-color: #fff;
            border: 1px solid #ccc;
            box-shadow: 0px 4px 8px rgba(0,0,0,0.2);
            position: absolute;
            bottom: 100%; /* Coloca el contenedor de emojis hacia arriba del botón */
            left: 0;
            display: none; /* Ocultar el contenedor por defecto */
            z-index: 1000;
        }
        .emoji-item {
            text-align: center;
            cursor: pointer;
            padding: 5px;
            border-radius: 5px;
        }
        .emoji-item:hover {
            background-color: #f0f0f0;
        }
        .form-control {
            width: calc(100% - 90px); /* Ajustar el ancho para dejar espacio para los botones */
        }
        .btn {
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="container-center">
        <form id="sendMessageForm">
            <div class="mb-3">
                <div id="fileStatusMessage" style="margin-top: 10px; color: green;"></div>
            </div>
            <div class="mensaje emoji-picker-wrapper">
                <input type="text" id="messageContent" class="form-control" placeholder="Escribe tu mensaje aquí...">
                <button class="btn btn-outline-secondary" type="button" id="emojiButton">
                    <i class="fas fa-smile"></i>
                </button>
                <button class="btn" id="enviar" type="submit">
                    <i class="fa fa-paper-plane" aria-hidden="true" style="color: azure"></i>
                </button>
                <!-- Emoji Picker Element -->
                <div id="emojiPicker" class="emoji-grid">
                    <!-- Opciones de emojis se llenarán mediante JavaScript -->
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const emojiButton = document.getElementById('emojiButton');
            const emojiPicker = document.getElementById('emojiPicker');
            const messageContent = document.getElementById('messageContent');

            // Lista de emojis
            const emojis = [
                '😀', '😁', '😂', '😃', '😄',
                '😅', '😆', '😇', '😈', '😉',
                '😊', '😋', '😌', '😍', '😎',
                '😏', '😐', '😑', '😒', '😓',
                '😔', '😕', '😖', '😗', '😘',
                '😙', '😚', '😛', '😜', '😝',
                '😞', '😟', '😠', '😡', '😢',
                '😣', '😤', '😥', '😦', '😧',
                '😨', '😩', '😪', '😫', '😬',
                '😭', '😮', '😯', '😰', '😱',
                '😲', '😳', '😴', '😵', '😶',
                '😷', '🙁', '🙂', '🙃', '🙄',
                '🤐', '🤑', '🤒', '🤓', '🤔',
                '🤕', '🤗', '🤠', '🤢', '🤣',
                '🤤', '🤥', '🤧', '🤨', '🤩',
                '🤪', '🤫', '🤭', '🤮', '🤯',
                '🥰', '🥱', '🥳', '🥴', '🥵',
                '🥶', '🥺', '🧐', '😸', '😹',
                '😺', '😻', '😼', '😽', '😾',
                '😿', '🙀', '🙈', '🙉', '🙊',
                '👿', '💀', '🤬'
                // Agrega más emojis si es necesario
            ];

            // Función para llenar el contenedor de emojis
            function populateEmojiGrid() {
                emojiPicker.innerHTML = '';
                // Crear un elemento para cada emoji
                emojis.forEach(emoji => {
                    const emojiItem = document.createElement('div');
                    emojiItem.className = 'emoji-item';
                    emojiItem.textContent = emoji;
                    emojiItem.addEventListener('click', function() {
                        messageContent.value += emoji;
                        emojiPicker.style.display = 'none'; // Ocultar el picker después de seleccionar un emoji
                    });
                    emojiPicker.appendChild(emojiItem);
                });
            }

            // Llamar a la función para llenar el contenedor de emojis
            populateEmojiGrid();

            // Mostrar el emoji picker al hacer clic en el botón
            emojiButton.addEventListener('click', function () {
                emojiPicker.style.display = emojiPicker.style.display === 'block' ? 'none' : 'block';
            });

            // Manejar clic fuera del emoji picker para ocultarlo
            document.addEventListener('click', function(event) {
                if (!emojiButton.contains(event.target) && !emojiPicker.contains(event.target)) {
                    emojiPicker.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
