import { API } from "./api.js";

document.addEventListener("DOMContentLoaded", async () => {

    const serviciosDiv = document.getElementById("servicios");
    const peluquerosDiv = document.getElementById("peluqueros");
    const fechaInput = document.getElementById("fecha");
    const horariosDiv = document.getElementById("horarios");
    const resumenDiv = document.getElementById("resumen");

    const clienteNombre = document.getElementById("clienteNombre");
    const clienteTelefono = document.getElementById("clienteTelefono");

    let servicioSeleccionado = null;
    let peluqueroSeleccionado = null;
    let horarioSeleccionado = null;

    // 🔹 cargar servicios
    const servicios = await API.getServicios();

    servicios.forEach(s => {

        const col = document.createElement("div");
        col.className = "col-6";

        col.innerHTML = `
            <div class="servicio-card">
                <h6>${s.nombre}</h6>
                <small>$${s.precio}</small>
            </div>
        `;

        col.onclick = () => {

            document.querySelectorAll(".servicio-card")
                .forEach(c => c.classList.remove("active"));

            col.querySelector(".servicio-card")
                .classList.add("active");

            servicioSeleccionado = s;

            actualizarResumen();
        };

        serviciosDiv.appendChild(col);

    });

    // 🔹 cargar peluqueros
    const peluqueros = await API.getPeluqueros();

    peluqueros.forEach(p => {

        const btn = document.createElement("button");

        btn.className = "btn btn-outline-dark peluquero-btn";
        btn.textContent = p.nombre;

        btn.onclick = () => {

            document.querySelectorAll(".peluquero-btn")
                .forEach(b => b.classList.remove("active"));

            btn.classList.add("active");

            peluqueroSeleccionado = p;

            actualizarResumen();
        };

        peluquerosDiv.appendChild(btn);

    });

    // 🔹 horarios
    async function renderHorarios() {

        const fecha = fechaInput.value;

        if (!fecha || !peluqueroSeleccionado) return;

        const horarios = await API.getHorarios(
            fecha,
            peluqueroSeleccionado.id
        );

        horariosDiv.innerHTML = "";

        horarios.forEach(hora => {

            const btn = document.createElement("button");

            btn.className = "btn btn-outline-primary";
            btn.textContent = hora;

            btn.onclick = () => {

                document.querySelectorAll("#horarios button")
                    .forEach(b => b.classList.remove("active"));

                btn.classList.add("active");

                horarioSeleccionado = hora;

                actualizarResumen();
            };

            horariosDiv.appendChild(btn);

        });

    }

    fechaInput.addEventListener("change", renderHorarios);

    // 🔹 resumen
    function actualizarResumen() {

        resumenDiv.innerHTML = `
            <strong>Resumen:</strong><br>

            Servicio:
            ${servicioSeleccionado?.nombre || "-"} <br>

            Peluquero:
            ${peluqueroSeleccionado?.nombre || "-"} <br>

            Fecha:
            ${fechaInput.value || "-"} <br>

            Horario:
            ${horarioSeleccionado || "-"} <br>

            Cliente:
            ${clienteNombre?.value || "-"} <br>

            Teléfono:
            ${clienteTelefono?.value || "-"}
        `;
    }

    fechaInput.addEventListener("change", actualizarResumen);

    clienteNombre.addEventListener("input", actualizarResumen);
    clienteTelefono.addEventListener("input", actualizarResumen);

    // 🔥 wizard
    let currentStep = 1;

    const steps = document.querySelectorAll(".step");
    const contents = document.querySelectorAll(".step-content");

    const nextBtn = document.getElementById("next");
    const prevBtn = document.getElementById("prev");

    function updateSteps() {

        steps.forEach((step, i) => {
            step.classList.toggle("active", i < currentStep);
        });

        contents.forEach(content => {

            content.classList.remove("active");

            if (
                parseInt(content.dataset.step) === currentStep
            ) {
                content.classList.add("active");
            }

        });
    }

    // 🔹 validaciones
    function validarPaso() {

        if (currentStep === 1 && !servicioSeleccionado) {

            alert("Elegí un servicio");

            return false;
        }

        if (currentStep === 2 && !peluqueroSeleccionado) {

            alert("Elegí un peluquero");

            return false;
        }

        if (currentStep === 3 && !fechaInput.value) {

            alert("Seleccioná una fecha");

            return false;
        }

        if (currentStep === 4 && !horarioSeleccionado) {

            alert("Elegí un horario");

            return false;
        }

        if (currentStep === 5) {

            if (!clienteNombre.value.trim()) {

                alert("Ingresá tu nombre");

                return false;
            }

            if (!clienteTelefono.value.trim()) {

                alert("Ingresá tu teléfono");

                return false;
            }
        }

        return true;
    }

    // 🔹 siguiente
    nextBtn.onclick = async () => {

        if (!validarPaso()) return;

        if (currentStep < 5) {

            currentStep++;

            updateSteps();

        } else {

            // 🔹 guardar reserva
            await API.reservarTurno({

                cliente: clienteNombre.value,

                telefono: clienteTelefono.value,

                servicioId: servicioSeleccionado.id,
                servicio: servicioSeleccionado.nombre,

                precio: servicioSeleccionado.precio,


                peluqueroId: peluqueroSeleccionado.id,
                peluquero: peluqueroSeleccionado.nombre,

                fecha: fechaInput.value,

                hora: horarioSeleccionado,

                estado: "pendiente"

            });

            // 🔹 ocultar wizard
            document.querySelector(".reserva-card")
                .style.display = "none";

            // 🔹 éxito
            const successScreen =
                document.getElementById("successScreen");

            const successResumen =
                document.getElementById("successResumen");

            successResumen.innerHTML = `
                <strong>Cliente:</strong>
                ${clienteNombre.value} <br>

                <strong>Teléfono:</strong>
                ${clienteTelefono.value} <br>

                <strong>Servicio:</strong>
                ${servicioSeleccionado.nombre} <br>

                <strong>Peluquero:</strong>
                ${peluqueroSeleccionado.nombre} <br>

                <strong>Fecha:</strong>
                ${fechaInput.value} <br>

                <strong>Horario:</strong>
                ${horarioSeleccionado}
            `;

            successScreen.style.display = "flex";
        }
    };

    // 🔹 atrás
    prevBtn.onclick = () => {

        if (currentStep > 1) {

            currentStep--;

            updateSteps();
        }
    };

    updateSteps();

});