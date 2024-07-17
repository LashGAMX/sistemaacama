<!DOCTYPE html>
<html lang="en">
<head>
  <title>Chat ejemplo</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{ voyager_asset('css/mensaje.css') }}">
  
 <!-- Bootstrap CSS -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
 <!-- Font Awesome CSS -->
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> 
 <!-- Select2 CSS -->
 <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
 <!-- jQuery -->
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <meta name="csrf-token" content="{{ csrf_token() }}">
 <meta name="current-user-id" content="{{ auth()->user()->id }}">

</head>

<body>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Logo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="collapsibleNavbar">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" type="button"  data-bs-toggle="offcanvas" data-bs-target="#chat">
                            <span class="message-icon">
                                <i class="fa-sharp fa-solid fa-comment-dots" style="color: #63E6BE;"></i>                      
                                <span style="color: red;" id="CountMen"></span>
                            </span>
                        </a>
                    </li>
    
                    <li class="nav-item" style="margin-top: 5px;">
                        <a class="nav-link" href="#" id="notificationIcon">
                            <span class="notification-icon">
                                <i class="fas fa-bell"></i>
                                <span style="color: red" id="CountNot"></span>
                            </span>
                        </a>
                    </li>
                    @if(Auth::check())
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Perfil</a>
                        <ul class="dropdown-menu">
                        </ul>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

<div class="offcanvas offcanvas-end" id="chat">
  <div class="offcanvas-header" style="background-color:#238C79">
    <h1 class="offcanvas-title text-white p-2" >Chat Sofia</h1>
    <!-- <img src="{{ asset('Imagen/sofia2.png') }}" alt="Sofia" class="img-fluid mt-4 mb-4" style="max-height: 50px;"> -->
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="list-group" id="groupListContainer">
    @if(auth()->user()->role_id == 1 ) 
      <button class="btn" style="background-color: #BFFFF3" data-bs-toggle="collapse" data-bs-target="#createGroupForm">Nuevo Grupo</button>
      <br>
      <!-- Formulario para crear un grupo -->
      

        <form id="createGroupForm" class="collapse">
            
            <div class="mb-3">
                <label for="groupName" class="form-label">Nombre del Grupo:</label>
                <input type="text" class="form-control" id="groupName" placeholder="Nombre del Grupo" required>
            </div>
            <!-- Parte del formulario donde se carga el select múltiple -->
            <div class="mb-3">
                <label for="groupUsers" class="form-label">Seleccionar Usuarios:</label>
                <select id="groupUsers" name="groupUsers[]" class="form-control" style="width: 100%" multiple required>
                    <!-- Opciones se cargarán dinámicamente con JavaScript -->
                </select>
            </div>

            <div class="mb-3">
                <select class="form-select"  id="colorPicker" name="color">
                    <option selected>Selecciona el Tema del Grupo</option>
                    <option value="#FADD73" style="background-color: #FADD73; color: black;">Amarillo</option>
                    <option value="#AAFA6B" style="background-color: #AAFA6B; color: black;">Verde</option>
                    <option value="#FA6BE6" style="background-color: #FA6BE6; color: black;">Rosa</option>
                    <option value="#814DFA" style="background-color: #814DFA; color: white;">Púrpura</option>
                    <option value="#4DD4FA" style="background-color: #4DD4FA; color: black;">Azul</option>
                    <option value="#E1EAFA" style="background-color: #E1EAFA; color: black;">Gris</option>
                    <option value="#2D2612" style="background-color: #2D2612; color: white;">Carboncillo</option>
                </select>
                
                
            </div>
            <button type="submit" class="btn text-white" style="background-color: #264D45;">Crear Grupo</button>
        </form>
        @endif

        
    
      <br>
              <!-- Grupos se cargarán dinámicamente con JavaScript -->

      <li id="groupList"> 
        
      </li>
  
  </div>
</div>
<!-- Vista del chat por grupo al dar clic al grupo -->
<div class="offcanvas offcanvas-end" id="grupos">
  <div class="offcanvas-header ">
    <h3 class="offcanvas-title text-white">Nombre Grupo</h3>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>
  <div class="offcanvas-body">

    {{-- <button class="btn btn-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#chat">Regresar</button> --}}
    <!-- Formulario para enviar mensajes -->
    <form id="sendMessageForm" style="position: absolute; bottom: 10px; width: calc(100% - 20px);">
      <div class="input-group mb-3">
        <input type="text" id="messageContent" class="form-control" placeholder="Escribe tu mensaje aquí...">
        <input type="file" id="messageFile" style="display: none;">
        <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('messageFile').click();">
          <i class="fas fa-paperclip"></i>
        </button>
        <button class="btn" id="enviar" type="submit">
          <i class="fa fa-paper-plane" aria-hidden="true" style="color: azure"></i>
        </button>
      </div>
    </form>
    <div id="chatMessagesContainer">
        <div id="chatMessages"></div>
    </div>
    
   
  </div>
</div>

<!-- Modal de visualización del grupo -->
<div class="modal fade" id="groupModal" tabindex="-1" aria-labelledby="groupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="groupModalLabel">Nombre del Grupo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Contenido del modal se llenará dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de edición del grupo -->
<div class="modal fade" id="editGroupModal" tabindex="-1" aria-labelledby="groupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="groupModalLabel">Editar Grupo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Contenido del modal de edición se llenará dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="saveChangesBtn">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 text-center">
        <h1>Laboratorio de Análisis</h1>
        
      </div>
    </div>
  </div>
  
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Bootstrap JS Bundle (Popper included) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Tu script personalizado -->
<script src="{{ asset('public/js/chat/mensaje.js') }}?v=1.1.2"></script>


</body>
</html>
