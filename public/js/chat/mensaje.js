$(document).ready(function() {
    $('#groupCreationForm').on('submit', crearGrupo);
    setInterval(loadGroups, 1000);
    setInterval(countGen, 1000);  
    setInterval(() => { if (currentGroupId) { loadMessages(currentGroupId);} }, 1000);
   

    
   const currentUserId = document.querySelector('meta[name="current-user-id"]').getAttribute('content');
   const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
   const sendMessageForm = document.getElementById('sendMessageForm');
   const messageContentInput = document.getElementById('messageContent');
   const messageFileInput = document.getElementById('messageFile');
   
   let groupColors = [];
   let currentGroupId = null;


//FUNCTION PARA CONSULTAR TODOS LOS USUARIOS DE ACAMA
function selectUser() {
    $('#groupUsers').select2({
        dropdownParent: $('#chat'),
        placeholder: 'Seleccionar Usuarios',
        allowClear: true,
        ajax: {
            url: base_url + "/admin/chat/asignarUser",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term || '' 
                };
            },
            processResults: function (data) {
                return {
                    results: data.map(item => ({
                        id: item.id,
                        text: item.name
                    }))
                };
            },
            cache: true
        },
        multiple: true,
        width: '100%'
    });
}

// Llama a la función para inicializar el select2
$(document).ready(function() {
    selectUser();
});

//FUNCTION PARA CREAR UN NUEVO GRUPO SI role_id==1
function crearGrupo(event) {
    event.preventDefault(); // Previene el envío del formulario tradicional

    const groupData = {
        name: $("#groupName").val(),
        usuarios: $("#groupUsers").val(),
        color: $("#colorPicker").val(),
        _token: $('input[name="_token"]').val()
    };

    $.ajax({
        type: 'POST',
        url: base_url + "/admin/chat/store",
        data: groupData,
        dataType: "json",
        success: function(response) {
            alert(response.msg);
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            alert('Error al crear el grupo');
        }
    });
}


function loadGroups() {
    // Realizar la solicitud para obtener grupos
    const groupsPromise = fetch(base_url + '/admin/chat/getGroups')
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cargar grupos');
            }
            return response.json();
        });

    // Realizar la solicitud para obtener colores
    const colorsPromise = loadColors();

    // Esperar a que ambas promesas se resuelvan
    Promise.all([groupsPromise, colorsPromise])
        .then(([groupsData, groupColors]) => {
            // Imprimir para depuración
            // console.log('Datos de grupos:', groupsData);

            const groupList = document.getElementById('groupList');
            groupList.innerHTML = '';

            if (groupsData.length === 0) {
                groupList.innerHTML = '<h3>No tienes grupos disponibles</h3>';
                return;
            }

            groupsData.forEach(group => {
                const li = document.createElement('li');
                li.className = 'list-group-item list-group-item-action d-flex align-items-center';
                li.style.marginBottom = '10px';
                li.dataset.groupId = group.id;

                // Crear contenedor de iconos
                const iconContainer = document.createElement('div');
                iconContainer.className = 'd-flex align-items-center';

                // Agregar el icono de edición solo si el usuario puede editar
                if (group.canEdit) {
                    const iconEdit = document.createElement('i');
                    iconEdit.className = 'fa fa-pen';
                    iconEdit.style.marginRight = '10px';
                    iconEdit.style.cursor = 'pointer';
                    iconContainer.appendChild(iconEdit);

                    let hasBeenClicked = false;

                    iconEdit.addEventListener('click', (event) => {
                        if (hasBeenClicked) return; 

                        hasBeenClicked = true;
                        event.stopPropagation();
                        openEditModal(group.id);
                    });
                }

                // Icono de vista
                const iconView = document.createElement('i');
                iconView.className = 'fa fa-eye';
                iconView.style.marginRight = '10px';
                iconView.style.cursor = 'pointer';
                iconContainer.appendChild(iconView);

                // Nombre del grupo
                const text = document.createTextNode(group.name);

                // Contador de mensajes
                const countMessage = group.count_message > 0 ? ` (${group.count_message})` : '';
                const countSpan = document.createElement('span');
                countSpan.className = 'badge rounded-pill';
                countSpan.style.marginLeft = '20px';
                countSpan.style.backgroundColor = '#D92525';
                countSpan.style.color = 'white';
                countSpan.textContent = countMessage;

                // Aplicar color de fondo del grupo si existe
                const groupColor = groupColors.find(color => color.groupId === group.id);
                if (groupColor) {
                    li.style.backgroundColor = groupColor.color;
                }

                // Agregar contenedor de iconos al li
                li.appendChild(iconContainer);
                li.appendChild(text);

                if (group.count_message > 0) {
                    li.appendChild(countSpan);
                }

                iconView.addEventListener('click', (event) => {
                    event.stopPropagation();
                    showModal(group);
                });

                li.addEventListener('click', () => {
                    count(group.id);
                    loadMessages(group.id);
                    openGroupModal(group.id, group.name);
                });

                groupList.appendChild(li);
            });
        })
        .catch(error => {
            console.error('Error al cargar grupos o colores:', error);
        });
}


function showModal(group) {
    const modal = document.getElementById('groupModal');
    modal.querySelector('.modal-title').textContent = group.name;
    modal.querySelector('.modal-body').innerHTML = `
        <p>Creado por: ${group.creatorName}</p>
        <p>Integrantes:</p>
        <ul>${group.members.map(member => `<li>${member.name}</li>`).join('')}</ul>
    `;
    // Mostrar el modal 
    $('#groupModal').modal('show');
}

//aqui me quede convertir a ajax 

function openEditModal(groupId) {
    const modal = document.getElementById('editGroupModal');
    const modalBody = modal.querySelector('.modal-body');
    const modalFooter = modal.querySelector('.modal-footer');

    modalBody.innerHTML = '';
    modalFooter.innerHTML = '';

    // Fetch group details
    $.ajax({
        url: base_url + "/admin/chat/getGroupDetails/" + groupId,
        type: 'GET',
        dataType: 'json',
        success: function (groupDetails) {
            // Create form for editing group
            const form = document.createElement('form');
            form.id = 'editGroupForm';
            form.dataset.groupId = groupId;

            // Create and append the form elements
            const groupNameDiv = document.createElement('div');
            groupNameDiv.classList.add('mb-4');
            const groupNameLabel = document.createElement('label');
            groupNameLabel.textContent = 'Nombre del Grupo';
            groupNameLabel.classList.add('form-label');
            const groupNameInput = document.createElement('input');
            groupNameInput.type = 'text';
            groupNameInput.name = 'name';
            groupNameInput.value = groupDetails.name || '';
            groupNameInput.classList.add('form-control');
            groupNameDiv.appendChild(groupNameLabel);
            groupNameDiv.appendChild(groupNameInput);
            form.appendChild(groupNameDiv);

            // Color Select
            const colorDiv = document.createElement('div');
            colorDiv.classList.add('mb-4');
            const colorLabel = document.createElement('label');
            colorLabel.textContent = 'Color del Grupo';
            colorLabel.classList.add('form-label');
            const colorSelect = document.createElement('select');
            colorSelect.classList.add('form-select');
            colorSelect.id = 'colorPicker';
            colorSelect.name = 'color';
            colorSelect.className="form-control custom-select";

            // Opciones de color
            const colors = [
                { value: '', text: 'No Seleccionado', style: 'background-color: transparent;' }, // Usar valor vacío para "No Seleccionado"
                { value: '#F2D06B', text: 'Amarillo', style: 'background-color: #FADD73;' },
                { value: '#99D98F', text: 'Verde', style: 'background-color: #AAFA6B;' },
                { value: '#F2ACDA', text: 'Rosa', style: 'background-color: #FA6BE6;' },
                { value: '#C9ACF2', text: 'Púrpura', style: 'background-color: #814DFA;' },
                { value: '#9EDFFF', text: 'Azul', style: 'background-color: #4DD4FA;' },
                { value: '#F2F2F2', text: 'Gris', style: 'background-color: #E1EAFA;' },
                { value: '#767676', text: 'Carboncillo', style: 'background-color: #2D2612;' }
            ];

            colors.forEach(color => {
                const option = document.createElement('option');
                option.value = color.value;
                option.textContent = color.text;
                option.style = color.style;
                colorSelect.appendChild(option);
            });

            // Establecer el valor seleccionado por defecto
            colorSelect.value = groupDetails.color || ''; // Valor vacío para opción no seleccionada

            colorDiv.appendChild(colorLabel);
            colorDiv.appendChild(colorSelect);
            form.appendChild(colorDiv);

            // Members Select
            const membersDiv = document.createElement('div');
            membersDiv.classList.add('mb-4');
            const membersLabel = document.createElement('label');
            membersLabel.textContent = 'Integrantes del Grupo';
            membersLabel.classList.add('form-label');
            const membersSelect = document.createElement('select');
            membersSelect.id = 'groupUsers2';
            membersSelect.name = 'users[]';
            membersSelect.classList.add('form-select');
            membersSelect.multiple = true;

            groupDetails.members.forEach(member => {
                const option = document.createElement('option');
                option.value = member.id;
                option.textContent = member.name;
                option.selected = true;
                membersSelect.appendChild(option);
            });

            membersDiv.appendChild(membersLabel);
            membersDiv.appendChild(membersSelect);
            form.appendChild(membersDiv);

            modalBody.appendChild(form);

            // Inicializar Select2 después de agregar el select al DOM
            $('#groupUsers2').select2({
                placeholder: 'Seleccionar Usuarios',
                allowClear: true,
                ajax: {
                    url: base_url + "/admin/chat/asignarUser",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term || ''
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data.map(item => ({
                                id: item.id,
                                text: item.name
                            }))
                        };
                    },
                    cache: true
                },
                multiple: true,
                width: '100%'
            });

            // Save button
            const saveButton = document.createElement('button');
            saveButton.type = 'button';
            saveButton.classList.add('btn', 'btn-primary');
            saveButton.textContent = 'Guardar Cambios';
            saveButton.addEventListener('click', () => {
                const formData = new FormData(form);

                // Enviar datos del formulario
                $.ajax({
                    url: base_url + "/admin/chat/editGroup/" + groupId,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    success: function () {
                        const colorValue = colorSelect.value;

                        if (colorValue === '') {
                            // Solo mostrar un mensaje si no se ha seleccionado un color
                            $('#editGroupModal').modal('hide');
                            loadGroups();
                            alert('Cambios Realizados en el Grupo');
                        } else if (colorValue !== groupDetails.color) {
                            // Solo actualiza el color si ha cambiado
                            $.ajax({
                                url: base_url + "/admin/chat/updateColor/" + groupId,
                                type: 'POST',
                                contentType: 'application/json',
                                data: JSON.stringify({ color: colorValue }),
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                },
                                success: function () {
                                    $('#editGroupModal').modal('hide');
                                    loadGroups();
                                    alert('Cambios Guardados en el Grupo');
                                },
                                error: function () {
                                    console.error('Error al actualizar el color');
                                }
                            });
                        } else {
                            $('#editGroupModal').modal('hide');
                            loadGroups();
                            alert('Cambios Realizados en el Grupo');
                        }
                    },
                    error: function () {
                        console.error('Error al guardar cambios');
                    }
                });
            });

            modalFooter.appendChild(saveButton);
            $('#editGroupModal').modal('show');
        },
        error: function () {
            console.error('Error al cargar detalles del grupo');
            modalBody.innerHTML = '<p>Error al cargar detalles del grupo.</p>';
        }
    });
}

function count(groupId) {
    $.ajax({
        url: base_url + "/admin/chat/CountGrupo/" + groupId,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            // console.log(response); // Imprime la respuesta del servidor
        },
        error: function(xhr, status, error) {
            console.error('Error al procesar la solicitud:', error);
            console.error('Respuesta:', xhr.responseText);
        }
    });
}

function countGen(){
    $.ajax({
        url: base_url +'/admin/chat/ContGen',
        type: 'GET',
        success:function(response){
            if(response>0)
            {
                $("#CountMen").text(response).addClass('badge-red');

            }else{
                $("#CountMen").text('').removeClass('badge-red');

            }
        },
        error:function(xhr, status,error){
            console.error("Error al obtener el contador de mensajes ")
        }
    });
}

function openGroupModal(groupId, groupName) {
    loadColors()
        .then(colors => {
            // Verifica que colors sea un array
            if (!Array.isArray(colors)) {
                console.error('Los colores no están en el formato esperado:', colors);
                return;
            }

            const modalElement = document.getElementById('groupModal2');
            
            // Actualizar el nombre del grupo en el modal
            const cleanGroupName = groupName.replace(/\(\d+\)/, '').trim();
            modalElement.querySelector('.modal-title').innerText = cleanGroupName;

            // Buscar el color del encabezado del modal usando el array `colors`
            const groupColor = colors.find(color => color.groupId === groupId);
            if (groupColor) {
                modalElement.querySelector('.modal-header').style.backgroundColor = groupColor.color;
            } else {
                console.warn('Color no encontrado para el grupo:', groupId);
            }

            // Cargar los mensajes del grupo
            loadMessages(groupId);

            $('#groupModal2').modal('show');
        })
        .catch(error => {
            console.error('Error al cargar los colores:', error);
        });
}

function loadColors() {
    const url = `/sofiadev/public/js/chat/color.json?t=${new Date().getTime()}`;

    return fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cargar colores');
            }
            return response.json();
        })
        .then(colors => {
            groupColors = colors;
            // console.log('Colores cargados:', colors);
            return colors;
        })
        
}


function loadMessages(groupId) {
    currentGroupId = groupId;

    // Guardar la posición actual del scroll
    const currentScrollHeight = chatMessages.scrollHeight;
    const currentScrollTop = chatMessages.scrollTop;

    $.ajax({
        url: base_url + "/admin/chat/messages/" + groupId,
        method: 'GET',
        dataType: 'json',
        success: function(messages) {
            chatMessages.innerHTML = '';

            if (messages.length === 0) {
                chatMessages.innerHTML = '<div id="no-messages"></div>';
            } else {
                messages.forEach(message => displayMessage(message));
            }
            chatMessagesContainer.style.display = 'block';
            sendMessageForm.style.display = 'block';

            // Restaura la posición del scroll
            if (currentScrollHeight > chatMessages.clientHeight) {
                chatMessages.scrollTop = currentScrollTop + (chatMessages.scrollHeight - currentScrollHeight);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error al cargar mensajes:', textStatus, errorThrown);
        }
    });
}

function displayMessage(message) {
    const chatMessages = document.getElementById('chatMessages');
    const div = document.createElement('div');

    const isCurrentUser = message.user.id == currentUserId; 
    div.className = isCurrentUser ? 'message-item current-user' : 'message-item';
    const baseUrl = '/sofiadev/storage/app/public/'; 

        let avatarUrl = `${baseUrl}${message.user.avatar}`;

        let name = message.user.name;
        let shortName = name.substring(0, 15);

        let messageContent = `
            <img src="${avatarUrl}" alt="${name}'s avatar" style="width: 25px; height: 25px; border-radius: 50%; margin-right: 5px;"><strong style="color:#020202">${shortName}:</strong> ${message.message || ''}`;


    // let messageContent = `<strong style="color:#020202">${message.user.name}:</strong> ${message.message || ''}`;
    div.innerHTML = messageContent; 

    // Si hay un archivo adjunto en el mensaje
    if (message.file) {
        // Obtener la extensión del archivo
        const fileExtension = message.file.split('.').pop().toLowerCase();
        // Elegir el ícono basado en la extensión del archivo
        let fileIcon;
        let icon;
        switch (fileExtension) {
           
            default:
                icon='<i class="fa-solid fa-cloud-arrow-down"></i>';

        } 

        // Crear un enlace para descargar el archivo
        const a = document.createElement('a');

        a.style.textDecoration='none';
        a.style.color="#0127fd";
        a.style.fontFamily="Times Roman";
        a.className="border border-danger rounded w-50 h-50";
        // a.style.borderColor = "red";
        // a.style.backgroundColor='red';
        a.style.borderRadius='10px';

        a.href = `/sofiadev/storage/app/files/${encodeURIComponent(message.file)}`; 
        a.innerHTML = `${icon} ${message.file} `;
        a.setAttribute('download', message.file); 
        div.appendChild(document.createElement('br'));
        div.appendChild(a);
    }

    // Agregar el mensaje al contenedor de mensajes del chat
    chatMessages.appendChild(div);
    
}


sendMessageForm.addEventListener('submit', function(e) {
    e.preventDefault();
    if (!currentGroupId) return alert('Por favor selecciona un grupo primero.');

    const messageContent = messageContentInput.value.trim();
    const messageFile = messageFileInput.files[0];

    if (!messageContent && !messageFile) {
        alert('No has escrito nada ni adjuntado un archivo.');
        return;
    }

    const formData = new FormData();
    formData.append('group_id', currentGroupId);
    formData.append('message', messageContent || '');
    if (messageFile) formData.append('file', messageFile);

    $.ajax({
        url: base_url + "/admin/chat/mensaje",
        method: 'POST',
        data: formData,
        processData: false,  
        contentType: false, 
        headers: {
            'X-CSRF-TOKEN': csrfToken, 
        },
        success: function(data) {
            messageContentInput.value = '';
            messageFileInput.value = '';
            console.log('Mensaje enviado exitosamente:', data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            if (jqXHR.status === 413) {
                alert('El archivo que tratas de enviar pasa de los 50MB. Escoge otro.');
            } else {
                alert('Error al enviar el mensaje');
            }
            console.error('Error:', textStatus, errorThrown);
        }
    });
});

});

