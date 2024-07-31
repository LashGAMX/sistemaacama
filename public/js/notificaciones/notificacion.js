
$(document).ready(function() {
    var modal = document.getElementById('notificationModal');
    var closeButton = document.getElementsByClassName("close")[0];
    var notificationIcon = $("#notificationIcon");
    var notificationList = $("#notificationList");


    notificationIcon.on('click', function() {
        $.ajax({
            url: base_url + "/admin/notificacion/obtenerYMarcarLeidas",
            type: 'GET',
            success: function(response) {
                var notificacionesHtml = '';
    
                if (response.length === 0) {
                    notificacionesHtml = '<div><h4 class="fs-5">Te salvaste por el momento.</h4></div>';
                } else {
                    response.forEach(function(notificacion) {
                       notificacionesHtml += '<div class="modal-body"><div class="card" style="margin-top: 0px; margin-bottom: 0px;">'+ notificacion.Mensaje +'</div></div>';


                    });
                }
                notificationList.html(notificacionesHtml);
                $('#notificationModal').modal('show');
                $('.modal-body').addClass('overflow-auto');
            },
            error: function(xhr, status, error) {
                console.error("Error al obtener y marcar notificaciones como leídas:", error);
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
    
    setInterval(ContNot, 1000);
    ContNot(); 
});




