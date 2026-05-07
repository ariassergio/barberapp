import { servicios } from "./data/servicios.js";
import { peluqueros } from "./data/peluqueros.js";
import { reservas } from "./data/reservas.js";

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

    getServicios: async () => {
        return servicios;
    },

    getPeluqueros: async () => {
        return peluqueros;
    },

    getHorarios: async (fecha, peluqueroId) => {

        return horariosBase.filter(hora => {

            return !reservas.some(reserva =>
                reserva.fecha === fecha &&
                reserva.peluqueroId === peluqueroId &&
                reserva.hora === hora
            );

        });

    },

    reservarTurno: async (data) => {

        const res = await fetch("api/reservar.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        });
    
        return await res.json();
    }

};