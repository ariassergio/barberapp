document.addEventListener("DOMContentLoaded", () => {
    
    // Buscamos todos los selectores de estado que pusimos en la tabla
    const selectoresEstado = document.querySelectorAll(".select-estado");

    selectoresEstado.forEach(select => {
        select.addEventListener("change", async (e) => {
            const idTurno = e.target.dataset.id;
            const nuevoEstado = e.target.value;

            try {
                // Enviamos los datos al backend en segundo plano
                const response = await fetch("php/actualizar_estado.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ id: idTurno, estado: nuevoEstado })
                });

                const data = await response.json();

                if (data.success) {
                    // Refrescamos la pantalla para que las tarjetas recalculen los ingresos
                    window.location.reload();
                } else {
                    alert("Error en la base de datos: " + data.error);
                }
            } catch (error) {
                console.error("Error en la petición fetch:", error);
            }
        });
    });

    // Mobile sidebar toggle
    const btnToggle = document.getElementById("mobile-toggle");
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebarOverlay");
    if (btnToggle && sidebar && overlay) {
        btnToggle.addEventListener("click", () => {
            sidebar.classList.toggle("show-mobile");
            overlay.classList.toggle("show");
        });
        // Clicking overlay closes the sidebar
        overlay.addEventListener("click", () => {
            sidebar.classList.remove("show-mobile");
            overlay.classList.remove("show");
        });
    }
});