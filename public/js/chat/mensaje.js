$(document).ready(function() {
    $('#groupUsers').select2();

    // Constantes
    let currentGroupId = null;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const groupListElement = document.getElementById('groupList');
    const sendMessageForm = document.getElementById('sendMessageForm');
    const messageContentInput = document.getElementById('messageContent');
    const messageFileInput = document.getElementById('messageFile');
    const chatMessagesContainer = document.getElementById('chatMessagesContainer');
    const chatMessages = document.getElementById('chatMessages');
    const createGroupForm = document.getElementById('createGroupForm');
    const currentUserId = document.querySelector('meta[name="current-user-id"]').getAttribute('content');
    let groupColors = [];

    function fetchColors() {
        return $.ajax({
            type: 'GET',
            url: '/js/color.json',
            dataType: 'json'
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error('Error al cargar colores:', errorThrown);
            return [];
        });
    }

    function loadGroups() {
        $.when(
            $.ajax({
                type: 'GET',
                url: '/admin/chat/Group/index',
                dataType: 'json'
            }),
            fetchColors()
        ).done(function(groupsResponse, colors) {
            const groups = groupsResponse[0];
            groupListElement.innerHTML = '';

            groups.forEach(function(group) {
                const li = document.createElement('li');
                li.className = 'list-group-item list-group-item-action d-flex align-items-center';
                li.style.marginBottom = '10px';
                li.dataset.groupId = group.id;

                const iconView = document.createElement('i');
                iconView.className = 'fa fa-eye';
                iconView.style.marginRight = '10px';
                iconView.style.cursor = 'pointer';

                if (group.canEdit) {
                    const iconEdit = document.createElement('i');
                    iconEdit.className = 'fa fa-pencil-square';
                    iconEdit.style.marginRight = '5px';
                    iconEdit.style.cursor = 'pointer';

                    li.appendChild(iconEdit);

                    $(iconEdit).on('click', function(event) {
                        event.stopPropagation();
                        openEditModal(group.id);
                    });
                }

                const text = document.createTextNode(group.name);
                li.appendChild(iconView);
                li.appendChild(text);

                const countMessage = group.count_message > 0 ? ` (${group.count_message})` : '';

                const countSpan = document.createElement('span');
                countSpan.className = 'badge rounded-pill';
                countSpan.style.marginLeft = '10px';
                countSpan.style.backgroundColor = '#014040';
                countSpan.textContent = countMessage;

                if (group.count_message > 0) {
                    li.appendChild(countSpan);
                }

                // Aquí deberías adaptar el código para manejar los colores de los grupos si fuera necesario
                // Utiliza la lógica que corresponda para obtener y aplicar los colores de los grupos

                $(iconView).on('click', function(event) {
                    event.stopPropagation();
                    showModal(group);
                });

                $(li).on('click', function() {
                    // Aquí puedes adaptar la lógica según las necesidades de tu aplicación
                    // Por ejemplo, cargar mensajes, abrir un chat, etc.
                    // loadMessages(group.id);
                    // openChatOffcanvas(group.id, group.name);
                });

                groupListElement.appendChild(li);
            });
        }).fail(function(error) {
            console.error('Error al cargar grupos:', error);
        });
    }

    function openChatOffcanvas(groupId, groupName) {
        const offcanvasElement = document.getElementById('grupos');
        const cleanGroupName = groupName.replace(/\(\d+\)/, '').trim();
        offcanvasElement.querySelector('.offcanvas-title').innerText = cleanGroupName;

        const groupColor = groupColors.find(function(color) {
            return color.groupId === groupId;
        });

        if (groupColor) {
            offcanvasElement.querySelector('.offcanvas-header').style.backgroundColor = groupColor.color;
        }

        const bsOffcanvas = new bootstrap.Offcanvas(offcanvasElement);
        bsOffcanvas.show();
    }

    // Event listener for group selection
    $(groupListElement).on('click', 'li', function() {
        const groupId = $(this).data('groupId');
        loadMessages(groupId);
        count(groupId);
        openChatOffcanvas(groupId, $(this).text());
    });

    function loadMessages(groupId) {
        currentGroupId = groupId;

        // Guardar la posición actual del scroll
        const currentScrollHeight = chatMessages.scrollHeight;
        const currentScrollTop = chatMessages.scrollTop;

        $.ajax({
            type: 'GET',
            url: `/groups/${groupId}/messages`,
            dataType: 'json'
        }).done(function(messages) {
            chatMessages.innerHTML = '';

            if (messages.length === 0) {
                chatMessages.innerHTML = '<div id="no-messages"></div>';
            } else {
                messages.forEach(function(message) {
                    displayMessage(message);
                });
            }

            chatMessagesContainer.style.display = 'block';
            sendMessageForm.style.display = 'block';

            // Restaura  la posición del scroll
            if (currentScrollHeight > chatMessages.clientHeight) {
                chatMessages.scrollTop = currentScrollTop + (chatMessages.scrollHeight - currentScrollHeight);
            }
        }).fail(function(error) {
            console.error('Error al cargar mensajes:', error);
        });
    }

    function displayMessage(message) {
        const div = document.createElement('div');
        const isCurrentUser = message.user.id == currentUserId; // currentUserId debe ser la ID del usuario logueado
        div.className = isCurrentUser ? 'message-item current-user' : 'message-item';

        let messageContent = `<strong>${message.user.name}:</strong> ${message.message || ''}`;
        div.innerHTML = messageContent;

        // Si hay un archivo adjunto en el mensaje
        if (message.file) {
            // Obtener la extensión del archivo
            const fileExtension = message.file.split('.').pop().toLowerCase();
            // Elegir el ícono basado en la extensión del archivo
            let fileIcon;

            switch (fileExtension) {
                case 'pdf':
                    fileIcon = '<i class="fas fa-file-pdf"></i>';
                    break;
                case 'doc':
                case 'docx':
                    fileIcon = '<i class="fas fa-file-word"></i>';
                    break;
                case 'xls':
                case 'xlsx':
                    fileIcon = '<i class="fas fa-file-excel"></i>';
                    break;
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':
                    fileIcon = '<i class="fas fa-file-image"></i>';
                    break;
                default:
                    fileIcon = '<i class="fas fa-file"></i>';
            }

            // Crear un enlace para descargar el archivo
            const a = document.createElement('a');
            a.style.textDecoration = 'none';
            a.style.color = "#422DEB ";
            a.style.fontFamily = "Times Roman";
            a.style.backgroundColor = '#7AB4EB';
            a.href = `/download/${encodeURIComponent(message.file)}`; // Ruta para descargar el archivo
            a.innerHTML = `${fileIcon} ${message.file}`;
            a.setAttribute('download', message.file); // Asegurar que el enlace sea descargable
            div.appendChild(document.createElement('br'));
            div.appendChild(a);
        }

        // Agregar el mensaje al contenedor de mensajes del chat
        chatMessages.appendChild(div);
    }
// mensaje.js

function loadUsers() {
    $.ajax({
        type: 'GET',
        url: '/admin/notificacion/asignarUser',
        dataType: 'json'
    }).done(function(users) {
        const groupUsersSelect = document.getElementById('groupUsers');
        groupUsersSelect.innerHTML = '';

        users.forEach(function(user) {
            const option = document.createElement('option');
            option.value = user.id;
            option.textContent = user.name;
            groupUsersSelect.appendChild(option);
        });

        // Reinitialize Select2 after adding options
        $('#groupUsers').select2({
            width: '100%',
            multiple: true,
            placeholder: 'Selecciona usuarios',
            // Otros parámetros de configuración si es necesario
        });
    }).fail(function(error) {
        console.error('Error al cargar usuarios:', error);
    });
}
loadUsers();


    function count(groupId) {
        $.ajax({
            type: 'POST',
            url: `/count/${groupId}`,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        }).fail(function(error) {
            console.error('Error al resetear el contador de mensajes:', error);
        });
    }

    function openEditModal(groupId) {
        const modal = document.getElementById('editGroupModal');
        const modalBody = modal.querySelector('.modal-body');
        const modalFooter = modal.querySelector('.modal-footer');

        modalBody.innerHTML = '';
        modalFooter.innerHTML = '';

        // Fetch group details
        $.ajax({
            type: 'GET',
            url: `/groups/${groupId}/details`,
            dataType: 'json'
        }).done(function(groupDetails) {
            // Create form for editing group
            const form = document.createElement('form');
            form.id = 'editGroupForm';
            form.dataset.groupId = groupId;

            // Group Name Input
            const groupNameLabel = document.createElement('label');
            groupNameLabel.textContent = 'Nombre del Grupo';
            const groupNameInput = document.createElement('input');
            groupNameInput.type = 'text';
            groupNameInput.name = 'name';
            groupNameInput.className = 'form-control';
            groupNameInput.value = groupDetails.name;

            // Add other fields as necessary

            // Submit Button
            const submitButton = document.createElement('button');
            submitButton.type = 'submit';
            submitButton.className = 'btn btn-primary';
            submitButton.textContent = 'Guardar Cambios';

            // Append inputs to form
            form.appendChild(groupNameLabel);
            form.appendChild(groupNameInput);
            
            // Add event listener for form submission
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                saveGroupChanges(groupId, form);
            });

            modalBody.appendChild(form);
            modalFooter.appendChild(submitButton);

            // Show modal
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        }).fail(function(error) {
            console.error('Error al cargar detalles del grupo:', error);
        });
    }

    function saveGroupChanges(groupId, form) {
        const formData = new FormData(form);
        formData.append('_token', csrfToken);

        $.ajax({
            type: 'POST',
            url: `/groups/${groupId}/update`,
            data: formData,
            processData: false,
            contentType: false
        }).done(function(response) {
            if (response.success) {
                $('#editGroupModal').modal('hide');
                loadGroups();
            } else {
                console.error('Error al actualizar el grupo:', response.error);
            }
        }).fail(function(error) {
            console.error('Error al actualizar el grupo:', error);
        });
    }

    // Event listener for sending messages
    sendMessageForm.addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(sendMessageForm);
        formData.append('_token', csrfToken);
        formData.append('group_id', currentGroupId);

        $.ajax({
            type: 'POST',
            url: '/messages/send',
            data: formData,
            processData: false,
            contentType: false
        }).done(function(response) {
            if (response.success) {
                messageContentInput.value = '';
                messageFileInput.value = '';
                loadMessages(currentGroupId);
            } else {
                console.error('Error al enviar el mensaje:', response.error);
            }
        }).fail(function(error) {
            console.error('Error al enviar el mensaje:', error);
        });
    });

 // Event listener for creating groups
document.addEventListener('DOMContentLoaded', function() {
    const createGroupForm = document.getElementById('createGroupForm');
    if (createGroupForm) {
        createGroupForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(createGroupForm);
            formData.append('_token', csrfToken);

            $.ajax({
                type: 'POST',
                url: '/groups/create',
                data: formData,
                processData: false,
                contentType: false
            }).done(function(response) {
                if (response.success) {
                    $('#createGroupModal').modal('hide');
                    loadGroups();
                } else {
                    console.error('Error al crear el grupo:', response.error);
                }
            }).fail(function(error) {
                console.error('Error al crear el grupo:', error);
            });
        });
    }
});

    // Initial load of groups and users
    loadGroups();
    loadUsers();
});
