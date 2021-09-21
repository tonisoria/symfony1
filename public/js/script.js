function mostraContrasenya() {
    var x = document.getElementById("inputPassword");
    if (x.type == "password") {
        x.type = "text";
    } else {
        x.type = "password";
    }
}

function eliminar(){
    return confirm("Estàs segur que vols eliminar?");
}