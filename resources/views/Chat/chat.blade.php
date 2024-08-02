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
            width: 400px;
            box-sizing: border-box;
            position: relative;
        }
        .emoji-picker-wrapper {
            position: relative;
        }
        .emoji-picker {
            display: grid;
            grid-template-columns: repeat(5, 1fr); /* 5 columnas */
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
        .emoji-row {
            display: grid;
            grid-template-columns: repeat(5, 1fr); /* 5 emojis por fila */
        }
        .emoji-item {
            text-align: center;
            cursor: pointer;
            padding: 10px;
            border-radius: 5px;
            user-select: none;
        }
        .emoji-item.selected {
            background-color: #e0e0e0; /* Color de fondo para el emoji seleccionado */
        }
        .form-control {
            width: calc(100% - 90px);
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
                <div id="emojiPicker" class="emoji-picker">
                    <!-- Opciones de emojis se llenarán mediante JavaScript -->
                </div>
            </div>
        </form>
    </div>

   
</body>
</html>
