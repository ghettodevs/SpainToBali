CREATE TABLE IF NOT EXISTS mensajes (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL,
    fecha_viaje DATE NOT NULL,
    num_personas SMALLINT NOT NULL DEFAULT 1,
    mensaje TEXT,
    fecha_envio TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX IF NOT EXISTS idx_mensajes_email ON mensajes (email);
CREATE INDEX IF NOT EXISTS idx_mensajes_fecha_viaje ON mensajes (fecha_viaje);
CREATE INDEX IF NOT EXISTS idx_mensajes_fecha_envio ON mensajes (fecha_envio);

CREATE TABLE IF NOT EXISTS leads (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    origen VARCHAR(100) NOT NULL,
    fecha_registro TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (email, origen)
);

CREATE INDEX IF NOT EXISTS idx_leads_email ON leads (email);
CREATE INDEX IF NOT EXISTS idx_leads_origen ON leads (origen);
CREATE INDEX IF NOT EXISTS idx_leads_fecha_registro ON leads (fecha_registro);
