
$(document).ready(function() {
    var modal = document.getElementById('notificationModal');
    var closeButton = document.getElementsByClassName("close")[0];
    var notificationIcon = $("#notificationIcon");
    var notificationList = $("#notificationList");

    notificationIcon.on('click', function() {
        $.ajax({
            url: base_url + "/admin/notificacion/obtenerNotificaciones",
            type: 'GET',
            success: function(response) {
                var notificacionesHtml = '';
                var idNotificaciones = [];
                response.forEach(function(notificacion) {
                    notificacionesHtml += '<div class="d-flex flex-nowrap"><p>' + notificacion.Id_notificacion + '</p>'
                    notificacionesHtml += '<p>' + notificacion.Mensaje + '</p></div>';
                    idNotificaciones.push(notificacion.Id_notificacion); 
                });
                notificationList.html(notificacionesHtml);
                modal.style.display = "block";
               
                MarcarLeido(idNotificaciones); 
            },
            error: function(xhr, status, error) {
                console.error("Error al obtener las notificaciones:", error);
            }
        });
    });

    closeButton.onclick = function() {
        modal.style.display = "none";
    }
    $("#close").on('click', function() {
        modal.style.display = "none";
    });
    $(window).on('click', function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });
function MarcarLeido(idNotificaciones) {
    idNotificaciones.forEach(function(idNotificacion) {
       
        $.ajax({
            url: base_url + "/admin/notificacion/Marcarleido",
            type: 'POST',
            data: { Id_notificacion: idNotificacion },
            success: function(response) {
                console.log("Notificación marcada como leída:", response);
            },
            error: function(xhr, status, error) {
                console.error("Error al marcar la notificación como leída:", error);
            }
        });
    });
}

    
    function ContNot() {
        $.ajax({
            url: base_url + "/admin/notificacion/ContNot",
            type: 'GET',
            success: function(response) {
                if (response > 0) {
                    $("#CountNot").text(response).addClass('badge-red');
                } else {
                    $("#CountNot").text('').removeClass('badge-red');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error al obtener el conteo de notificaciones:", error);
            }
        });
    }

    ContNot();
});