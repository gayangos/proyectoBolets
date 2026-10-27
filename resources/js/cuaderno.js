// Cuaderno de hallazgos cifrado en el navegador (Web Crypto, AES-GCM).
// La clave se deriva de la contraseña del cuaderno con PBKDF2 y nunca sale del navegador.

const codificador = new TextEncoder();
const decodificador = new TextDecoder();

const aBase64 = (bytes) => btoa(String.fromCharCode(...new Uint8Array(bytes)));
const deBase64 = (texto) => Uint8Array.from(atob(texto), (c) => c.charCodeAt(0));

async function derivarClave(contrasena, sal) {
    const base = await crypto.subtle.importKey('raw', codificador.encode(contrasena), 'PBKDF2', false, ['deriveKey']);

    return crypto.subtle.deriveKey(
        { name: 'PBKDF2', salt: deBase64(sal), iterations: 250000, hash: 'SHA-256' },
        base,
        { name: 'AES-GCM', length: 256 },
        false,
        ['encrypt', 'decrypt'],
    );
}

async function cifrar(clave, objeto) {
    const iv = crypto.getRandomValues(new Uint8Array(12));
    const datos = await crypto.subtle.encrypt({ name: 'AES-GCM', iv }, clave, codificador.encode(JSON.stringify(objeto)));

    return { iv: aBase64(iv), datos: aBase64(datos) };
}

async function descifrar(clave, { iv, datos }) {
    const plano = await crypto.subtle.decrypt({ name: 'AES-GCM', iv: deBase64(iv) }, clave, deBase64(datos));

    return JSON.parse(decodificador.decode(plano));
}

window.cuaderno = (config) => ({
    sal: config.sal,
    verificador: config.verificador,
    textos: config.textos,
    clave: null,
    hallazgos: [],
    contrasena: '',
    repetida: '',
    aceptado: false,
    especie: '',
    notas: '',
    error: '',
    ocupado: false,
    mapa: null,
    capa: null,

    get creado() {
        return Boolean(this.sal);
    },

    enviar(url, metodo, cuerpo = null) {
        return fetch(url, {
            method: metodo,
            headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': config.csrf },
            body: cuerpo ? JSON.stringify(cuerpo) : null,
        });
    },

    // Primera vez: se genera la sal, se deriva la clave y se cifra un texto conocido para
    // poder comprobar la contraseña más adelante sin guardarla.
    async crear() {
        this.error = '';
        if (this.contrasena.length < 8) return (this.error = this.textos.corta);
        if (this.contrasena !== this.repetida) return (this.error = this.textos.noCoinciden);
        if (! this.aceptado) return (this.error = this.textos.aviso);

        this.ocupado = true;
        const sal = aBase64(crypto.getRandomValues(new Uint8Array(16)));
        const clave = await derivarClave(this.contrasena, sal);
        const verificador = JSON.stringify(await cifrar(clave, { comprobacion: 'bolets' }));

        const respuesta = await this.enviar(config.rutas.crear, 'POST', { sal, verificador, contrasena: this.contrasena });
        this.ocupado = false;

        if (! respuesta.ok) {
            this.error = (await respuesta.json()).message ?? this.textos.error;
            return;
        }

        this.sal = sal;
        this.verificador = verificador;
        this.abierto(clave, []);
    },

    async abrir() {
        this.error = '';
        this.ocupado = true;
        const clave = await derivarClave(this.contrasena, this.sal);

        try {
            await descifrar(clave, JSON.parse(this.verificador));
        } catch {
            this.ocupado = false;
            this.error = this.textos.incorrecta;
            return;
        }

        const hallazgos = await Promise.all(config.hallazgos.map(async (h) => ({ id: h.id, fecha: h.fecha, ...(await descifrar(clave, h)) })));
        this.ocupado = false;
        this.abierto(clave, hallazgos);
    },

    abierto(clave, hallazgos) {
        this.clave = clave;
        this.hallazgos = hallazgos;
        this.contrasena = '';
        this.repetida = '';
        this.$nextTick(() => this.pintarMapa());
    },

    desar() {
        this.error = '';
        if (! this.especie) return (this.error = this.textos.especie);

        this.ocupado = true;
        navigator.geolocation.getCurrentPosition(
            async (posicion) => {
                const hallazgo = {
                    especie: this.especie,
                    notas: this.notas,
                    lat: posicion.coords.latitude,
                    lon: posicion.coords.longitude,
                };
                const respuesta = await this.enviar(config.rutas.guardar, 'POST', await cifrar(this.clave, hallazgo));
                this.ocupado = false;

                if (! respuesta.ok) return (this.error = this.textos.error);

                const { id, fecha } = await respuesta.json();
                this.hallazgos.unshift({ id, fecha, ...hallazgo });
                this.notas = '';
                this.pintarMapa();
            },
            () => {
                this.ocupado = false;
                this.error = this.textos.sinGps;
            },
            { enableHighAccuracy: true, timeout: 20000 },
        );
    },

    async esborrar(hallazgo) {
        if (! confirm(this.textos.confirmar)) return;

        const respuesta = await this.enviar(config.rutas.borrar.replace('__ID__', hallazgo.id), 'DELETE');
        if (! respuesta.ok) return (this.error = this.textos.error);

        this.hallazgos = this.hallazgos.filter((h) => h.id !== hallazgo.id);
        this.pintarMapa();
    },

    pintarMapa() {
        if (! this.mapa) {
            this.mapa = L.map(this.$refs.mapa);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 18, attribution: '&copy; OpenStreetMap' }).addTo(this.mapa);
        }
        this.mapa.invalidateSize();

        const icono = L.icon({ iconUrl: '/imagenes/marca/isologo.svg', iconSize: [40, 40], iconAnchor: [20, 40], popupAnchor: [0, -36], className: 'hallazgo' });

        if (this.capa) this.capa.remove();
        this.capa = L.layerGroup(this.hallazgos.map((h) => {
            const texto = document.createElement('div');
            texto.append(Object.assign(document.createElement('strong'), { textContent: h.especie }), document.createElement('br'), h.fecha);

            return L.marker([h.lat, h.lon], { icon: icono }).bindPopup(texto);
        })).addTo(this.mapa);

        const zona = config.zona;
        this.mapa.fitBounds(this.hallazgos.length
            ? L.latLngBounds(this.hallazgos.map((h) => [h.lat, h.lon])).pad(0.2)
            : [[zona.latitud[0], zona.longitud[0]], [zona.latitud[1], zona.longitud[1]]], { maxZoom: 15 });
    },
});