<nav class="navbar navbar-default navbar-fixed-top navbar-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button class="hamburger btn-link">
                <span class="hamburger-inner"></span>
            </button>
            @section('breadcrumbs')
            <ol class="breadcrumb hidden-xs">
                @php
                $segments = array_filter(explode('/', str_replace(route('voyager.dashboard'), '', Request::url())));
                $url = route('voyager.dashboard');
                @endphp
                @if(count($segments) == 0)
                    <li class="active"><i class="voyager-boat"></i> {{ __('voyager::generic.dashboard') }}</li>
                @else
                    <li class="active">
                        <a href="{{ route('voyager.dashboard')}}"><i class="voyager-boat"></i> {{ __('voyager::generic.dashboard') }}</a>
                    </li>
                    @foreach ($segments as $segment)
                        @php
                        $url .= '/'.$segment;
                        @endphp
                        @if ($loop->last)
                            <li>{{ ucfirst(urldecode($segment)) }}</li>
                        @else
                            <li>
                                <a href="{{ $url }}">{{ ucfirst(urldecode($segment)) }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            </ol>
            @show
        </div>
        <ul class="nav navbar-nav @if (__('voyager::generic.is_rtl') == 'true') navbar-left @else navbar-right @endif">
        <li style="margin-top: 5px;" class="nav-item">
        <a class="nav-link" href="#" data-toggle="modal" data-target="#chat">
            <span class="message-icon">
                <i class="fa fa-comment-dots" style="color: #63E6BE;"></i>
                <span style="color: red;" id="CountMen"></span>
            </span>
        </a>
    </li>
            <li style="margin-top: 5px;">
            <a class="nav-link" href="#" id="notificationIcon">
          <span class="notification-icon">
            <i class="fas fa-bell"></i>
            <span style="color: red" id="CountNot"></span>
          </span>
        </a>
            </li>
           
            <li class="dropdown profile">
       
                <a href="#" class="dropdown-toggle text-right" data-toggle="dropdown" role="button"
                 aria-expanded="false"><img src="{{ $user_avatar }}" class="profile-img"> <span class="caret"></span><span class="icon-bell"></span>
                </a>
           
                <ul class="dropdown-menu dropdown-menu-animated">
                    <li class="profile-img">
                        <img src="{{ $user_avatar }}" class="profile-img">
                        <div class="profile-body">
                            <h5>{{ Auth::user()->name }}</h5>
                            <h6>{{ Auth::user()->email }}</h6>
                        </div>
                    </li>
            
                    <li class="divider"></li>
                    <?php $nav_items = config('voyager.dashboard.navbar_items'); ?>
                    @if(is_array($nav_items) && !empty($nav_items))
                    @foreach($nav_items as $name => $item)
                    <li {!! isset($item['classes']) && !empty($item['classes']) ? 'class="'.$item['classes'].'"' : '' !!}>
                        @if(isset($item['route']) && $item['route'] == 'voyager.logout')
                        <form action="{{ route('voyager.logout') }}" method="POST">
                            {{ csrf_field() }}
                            <button type="submit" class="btn btn-danger btn-block">
                                @if(isset($item['icon_class']) && !empty($item['icon_class']))
                                <i class="{!! $item['icon_class'] !!}"></i>
                                @endif
                                {{__($name)}}
                            </button>
                        </form>
                        @else
                        <a href="{{ isset($item['route']) && Route::has($item['route']) ? route($item['route']) : (isset($item['route']) ? $item['route'] : '#') }}" {!! isset($item['target_blank']) && $item['target_blank'] ? 'target="_blank"' : '' !!}>
                            @if(isset($item['icon_class']) && !empty($item['icon_class']))
                            <i class="{!! $item['icon_class'] !!}"></i>
                            @endif
                            {{__($name)}}
                        </a>
                        @endif
                    </li>
                    @endforeach
                    @endif
                </ul>
            </li>
        </ul>
    </div>
</nav>

<!-- Modal for chat -->
<div class="modal fade" id="chat" tabindex="-1" role="dialog" aria-labelledby="chatLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-right" role="document">
        <div class="modal-content modal-content-right">
            <div class="modal-header" style="background-color:#238C79">
         
            <div class="mb-3">
              <h5 class="modal-title p-2" id="chatLabel" style="color: aliceblue">Chat Sofia</h5>
              <img src="{{ asset('public/assets/imagen/sofia2.png') }}" alt="sofia" class="img-fluid" style="max-height: 50px;">
              
              <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
               
            </div>
            <div class="modal-body">
                <ul class="list-group" id="groupListContainer">
                 
                    <button class="btn" style="background-color: #BFFFF3" data-toggle="collapse" data-target="#createGroupForm" aria-expanded="false" aria-controls="createGroupForm">Nuevo Grupo</button>
                    <br>
                    <div id="createGroupForm" class="collapse">
                    @if(Auth::user()->role_id == 1)
                         <form id="groupCreationForm" method="POST" action="{{ url('/admin/notificacion/store') }}">
                             @csrf
                             <div class="mb-3">
                                 <label for="groupName" class="form-label">Nombre del Grupo:</label>
                                 <input type="text" class="form-control" id="groupName" name="name" placeholder="Nombre del Grupo" required>
                             </div>
                             <br>
                             <div class="mb-3">
                                 <label for="groupUsers" class="form-label">Seleccionar Usuarios:</label>
                                 <select id="groupUsers" name="usuarios[]" class="form-control" multiple required>
                                   <!-- Opciones dinámicas de usuarios -->
                                 </select>

                             </div>
                             <br>
                             <div class="mb-3">
                                 <select class="form-control custom-select" id="colorPicker" name="color" aria-placeholder="Selecciona el Tema del Grupo">
                                     <option selected>Selecciona el Tema del Grupo</option>
                                     <option value="#F2D06B" style="background-color: #F2D06B">Amarillo</option>
                                     <option value="#99D98F" style="background-color: #99D98F">Verde</option>
                                     <option value="#F2ACDA" style="background-color: #F2ACDA">Rosa</option>
                                     <option value="#C9ACF2" style="background-color: #C9ACF2">Púrpura</option>
                                     <option value="#9EDFFF" style="background-color: #9EDFFF">Azul</option>
                                     <option value="#F2F2F2" style="background-color: #F2F2F2">Gris</option>
                                     <option value="#767676" style="background-color: #767676">Carboncillo</option>
                                 </select>
                             </div>
                             <button type="submit" class="btn text-white" style="background-color: #264D45; color:white;">Crear Grupo</button>
                         </form>
                    @endif
                    </div>
                  
                    <br>
                </ul>
                <li id="groupList"></li>
            </div>
        </div>
    </div>
</div>

<!-- Modal del chat del grupo  selecionado-->
<div class="modal fade" id="groupModal2" tabindex="-1" role="dialog" aria-labelledby="groupModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-right" role="document">
    <div class="modal-content modal-content-right">
      <div class="modal-header">
        <h5 class="modal-title" id="groupModalLabel">Nombre Grupo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="chatMessagesContainer">
          <div id="chatMessages"></div>
        </div>
        <!-- Formulario para enviar mensajes -->
        <form id="sendMessageForm">
        <div class="mb-3">
            <div id="fileStatusMessage" style="margin-top: 10px; color: green;"></div>
        </div>
        <div class="mensaje">
            <input type="text" id="messageContent" class="form-control" placeholder="Escribe tu mensaje aquí...">
            <input type="file" id="messageFile" style="display: none;" onchange="seleccion()">
            <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('messageFile').click();">
                <i class="fas fa-paperclip"></i>
            </button>
            <button class="btn btn-outline-secondary" type="button" id="emojiButton">
                <i class="fas fa-smile"></i>
            </button>
            <button class="btn" id="enviar" type="submit">
                <i class="fa fa-paper-plane" aria-hidden="true" style="color: azure"></i>
            </button>
            <!-- Emoji Picker Element -->
            <div id="emojiPicker">
                <select id="emojiSelect">
                    <option value="">Selecciona un emoji</option>
                </select>
            </div>
        </div>
    </form>



      </div>
    </div>
  </div>
</div>



<!-- Modal de visualización del grupo -->
<div class="modal fade" id="groupModal" tabindex="-1" role="dialog" aria-labelledby="groupModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="groupModalLabel">Nombre del Grupo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Contenido del modal se llenará dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal de edición del grupo -->
<div class="modal fade" id="editGroupModal" tabindex="-1" role="dialog" aria-labelledby="editGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editGroupModalLabel">Editar Grupo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Contenido del modal de edición se llenará dinámicamente -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="saveChangesBtn">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>
 



<div id="notificationModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Notificaciones</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
       
        <div id="notificationList"></div>
      </div>
      <div class="modal-footer">
        <a href="{{ route('verNotificaciones') }}" class="btn btn-primary">Ver Todas</a>
        <button type="button" class="btn btn-secondary" id="close" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
<script>
function seleccion() {
    const fileInput = document.getElementById('messageFile');
    const fileStatusMessage = document.getElementById('fileStatusMessage');
    
    if (fileInput.files.length > 0) {
        const fileName = fileInput.files[0].name;
        fileStatusMessage.textContent = `Archivo seleccionado: ${fileName}`;
        fileStatusMessage.style.color = 'green';  
    } else {
        fileStatusMessage.textContent = 'No se ha seleccionado ningún archivo.';
        fileStatusMessage.style.color = 'red'; 
    }
}

document.getElementById('sendMessageForm').addEventListener('submit', function(event) {
    event.preventDefault();  
    const fileStatusMessage = document.getElementById('fileStatusMessage');
    
   
    fileStatusMessage.textContent = '';
    
    const formData = new FormData(this);  

    fetch('/tu-endpoint', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            throw new Error('Error en la carga del archivo');
        }
    })
    .then(data => {
        fileStatusMessage.textContent = 'Archivo cargado con éxito';
        fileStatusMessage.style.color = 'green';
    })
 
});


</script>
