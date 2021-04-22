// (function(){
//     let btnConfiguraciones = document.getElementById("btnConfiguraciones");
//     let showModalConfig = function(){
//         alert("modal");
//     };
//     btnConfiguraciones.addEventListener("click",showModalConfig);
// }); 
function showModal(){
    let contenido = [
        "<livewire:historial.config/>"
    ];
    itemModal[0]=contenido;
    newModal("divModal","historialModal","modalConfiguraciones","lg",1,1,0,inputBtn("","","hecho","","",""));
}