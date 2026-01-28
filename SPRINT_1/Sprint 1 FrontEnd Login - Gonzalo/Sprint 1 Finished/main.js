function MenuDesplegable(){
    const nav = document.querySelector("#nav");
    const abrir = document.querySelector("#abrir");
    const cerrar = document.querySelector("#cerrar");

    abrir.addEventListener("click", ()=>{
        nav.classList.add("visible");
    });

    cerrar.addEventListener("click", ()=>{
        nav.classList.remove("visible");
    });
}

function Temperatura(){
    const apiKey = "fb8500e655644420abb114028251712";
    const ciudad = "Sevilla"; // o la ciudad de tu zoológico


    async function obtenerTemperatura() {
        const url = `https://api.weatherapi.com/v1/current.json?key=${apiKey}&q=${ciudad}&lang=es`;

        try {
            const respuesta = await fetch(url);
            const datos = await respuesta.json();

            const temp = Math.trunc(datos.current.temp_c);
            const icono = datos.current.condition.icon;

            document.getElementById("temperatura").innerHTML =
                `${temp}°C | <img src="${icono}" width="35">`;
        } catch {
            document.getElementById("temperatura").innerHTML =
                `<i class="bi bi-thermometer-high"></i> No disponible`;
        }
    }

    function ajustarTemperatura() {
        const tempDiv = document.getElementById("temperatura");
        const navFlex = document.querySelector(".navegacion-flex");
        const header = document.querySelector("header");

        if (window.innerWidth <= 900) {
            navFlex.prepend(tempDiv);
            tempDiv.style.marginBottom = "10px"; 
        } else {
            header.prepend(tempDiv);
            tempDiv.style.marginBottom = "0";
        }
    }

    

    window.addEventListener("resize", ajustarTemperatura);
    window.addEventListener("load", ajustarTemperatura);

    obtenerTemperatura();
}

MenuDesplegable();
Temperatura();

setInterval(Temperatura,30000);