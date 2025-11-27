function abrirModal(id, estado, descripcionPago){

    document.getElementById("modal").style.display = "flex";

    document.getElementById("modal-titulo").innerText = "Actualizar estado: " + estado;
    document.getElementById("id_pedido").value = id;
    document.getElementById("estado").value = estado;

    // Confirmado → mostrar descripción del pago
    if(estado === "confirmado"){
        document.getElementById("descripcion_pago").innerHTML =
            "<strong>Descripción del pago:</strong><br>" + descripcionPago;
        document.getElementById("botonesAprobacion").style.display = "none";
    }
    else {
        document.getElementById("descripcion_pago").innerHTML = "";
        document.getElementById("botonesAprobacion").style.display = "block";
    }
}

function cerrarModal(){
    document.getElementById("modal").style.display = "none";
}

function setAprobado(valor){
    document.getElementById("aprobado").value = valor;
}