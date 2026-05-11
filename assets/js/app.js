import { API } from "./api.js";

document.addEventListener("DOMContentLoaded", async () => {

    const serviciosDiv = document.getElementById("servicios");
    const peluquerosDiv = document.getElementById("peluqueros");
    const fechaInput = document.getElementById("fecha");
    const horariosDiv = document.getElementById("horarios");
    const resumenDiv = document.getElementById("resumen");
    const modalResumen =
    document.getElementById("modalResumen");

    const confirmModal = new bootstrap.Modal(
        document.getElementById("confirmModal")
    );

    const confirmarReservaBtn =
    document.getElementById("confirmarReserva");

    const clienteNombre = document.getElementById("clienteNombre");
    const clienteTelefono = document.getElementById("clienteTelefono");

    let serviciosSeleccionados = [];
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

            const card = col.querySelector(".servicio-card");
        
            const existe = serviciosSeleccionados.find(
                servicio => servicio.id === s.id
            );
        
            // 🔹 si ya existe → quitar
            if (existe) {
        
                serviciosSeleccionados =
                    serviciosSeleccionados.filter(
                        servicio => servicio.id !== s.id
                    );
        
                card.classList.remove("active");
        
            }
        
            // 🔹 si no existe → agregar
            else {
        
                serviciosSeleccionados.push(s);
        
                card.classList.add("active");
        
            }
        
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

            Servicios:
            ${serviciosSeleccionados.map(s => s.nombre).join(", ") || "-"} <br>

            Total:
            $${serviciosSeleccionados.reduce(
                (acc, s) => acc + s.precio,
                0
            )} <br>
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

        if (currentStep === 1 && serviciosSeleccionados.length === 0) {

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

    nextBtn.onclick = async () => {

        if (!validarPaso()) return;
    
        // 🔹 avanzar pasos
        if (currentStep < 5) {
    
            currentStep++;
    
            updateSteps();
    
        }
    
        // 🔹 abrir modal confirmación
        else {
    
            modalResumen.innerHTML = `

                <div class="ticket-grid">

                    <!-- SERVICIOS -->
                    <div class="ticket-card full">

                        <span class="ticket-label">
                            ✂ Servicios
                        </span>

                        <div class="services-chips">

                            ${serviciosSeleccionados.map(servicio => `

                                <span class="service-chip">
                                    ${servicio.nombre}
                                </span>

                            `).join("")}

                        </div>

                    </div>

                    <!-- PELUQUERO -->
                    <div class="ticket-card">

                        <span class="ticket-label">
                            👤 Peluquero
                        </span>

                        <strong>
                            ${peluqueroSeleccionado.nombre}
                        </strong>

                    </div>

                    <!-- FECHA -->
                    <div class="ticket-card">

                        <span class="ticket-label">
                            📅 Fecha
                        </span>

                        <strong>
                            ${fechaInput.value}
                        </strong>

                    </div>

                    <!-- HORARIO -->
                    <div class="ticket-card">

                        <span class="ticket-label">
                            🕒 Horario
                        </span>

                        <strong>
                            ${horarioSeleccionado}
                        </strong>

                    </div>

                    <!-- CLIENTE -->
                    <div class="ticket-card">

                        <span class="ticket-label">
                            🙍 Cliente
                        </span>

                        <strong>
                            ${clienteNombre.value}
                        </strong>

                    </div>

                    <!-- TELEFONO -->
                    <div class="ticket-card">

                        <span class="ticket-label">
                            📞 Teléfono
                        </span>

                        <strong>
                            ${clienteTelefono.value}
                        </strong>

                    </div>

                    <!-- TOTAL -->
                    <div class="ticket-total full">

                        <span>Total</span>

                        <strong>

                            $${serviciosSeleccionados.reduce(
                                (acc, s) => acc + s.precio,
                                0
                            )}

                        </strong>

                    </div>

                </div>

            `;
    
            confirmModal.show();
        }
    };
    confirmarReservaBtn.onclick = async () => {

        // 🔹 guardar reserva
        await API.reservarTurno({
    
            cliente: clienteNombre.value,
    
            telefono: clienteTelefono.value,
    
            servicios: serviciosSeleccionados,
    
            serviciosTexto: serviciosSeleccionados
                .map(s => s.nombre)
                .join(", "),
    
            precioTotal: serviciosSeleccionados
                .reduce((acc, s) => acc + s.precio, 0),
    
            peluqueroId: peluqueroSeleccionado.id,
    
            peluquero: peluqueroSeleccionado.nombre,
    
            fecha: fechaInput.value,
    
            hora: horarioSeleccionado,
    
            estado: "pendiente"
    
        });
    
        // 🔹 cerrar modal
        confirmModal.hide();
    
        // 🔹 ocultar wizard
        document.querySelector(".reserva-card")
            .style.display = "none";
    
        // 🔹 mostrar éxito
        const successScreen =
            document.getElementById("successScreen");
    
        const successResumen =
            document.getElementById("successResumen");
    
        successResumen.innerHTML = `
    
            <strong>Cliente:</strong>
            ${clienteNombre.value} <br>
    
            <strong>Servicios:</strong>
            ${serviciosSeleccionados
                .map(s => s.nombre)
                .join(", ")} <br>
    
            <strong>Total:</strong>
            $${serviciosSeleccionados
                .reduce((acc, s) => acc + s.precio, 0)} <br>
    
            <strong>Peluquero:</strong>
            ${peluqueroSeleccionado.nombre} <br>
    
            <strong>Fecha:</strong>
            ${fechaInput.value} <br>
    
            <strong>Horario:</strong>
            ${horarioSeleccionado}
    
        `;
    
        successScreen.style.display = "flex";
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