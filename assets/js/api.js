

const horariosBase = [
    "09:00",
    "10:00",
    "11:00",
    "12:00",
    "14:00",
    "15:00",
    "16:00",
    "17:00"
];

export const API = {

    // 🔹 SERVICIOS DESDE MYSQL
    getServicios: async () => {

        const response = await fetch("php/obtener_servicios.php");

        return await response.json();
    },

    getPeluqueros: async () => {

        const response = await fetch("php/obtener_profesionales.php");

        return await response.json();
    },

    // 🔹 horarios
    getHorarios: async (fecha, peluqueroId) => {

    const response = await fetch(

        `php/obtener_horarios.php?fecha=${fecha}&peluquero=${peluqueroId}`

    );

    return await response.json();
},

    // 🔹 guardar turno
    reservarTurno: async (data) => {

        const res = await fetch("php/guardar_turno.php", {

            method: "POST",

            headers: {
                "Content-Type": "application/json"
            },

            body: JSON.stringify(data)
        });

        return await res.json();
    }

};