
 -- ============================================================
-- KIDS ROUTINE — Script Corregido (Entrega 2)
-- Aplicando correcciones de tutoría (CASCADE, UNIQUE, PIN LOCK)
-- ============================================================

CREATE DATABASE IF NOT EXISTS kids_routine
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE kids_routine;

-- 1. USUARIOS (Padre)
CREATE TABLE usuarios (
  id_usuario     INT           AUTO_INCREMENT PRIMARY KEY,
  nombre         VARCHAR(100)  NOT NULL,
  email          VARCHAR(150)  NOT NULL UNIQUE,
  password_hash  VARCHAR(255)  NOT NULL,
  rol            ENUM('PADRE','ADMIN') NOT NULL DEFAULT 'PADRE',
  fecha_registro DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  activo         BOOLEAN       NOT NULL DEFAULT TRUE
) ENGINE=InnoDB;

-- 2. HIJOS (Relación 1:N con Usuario)
-- Corrección: Añadidos intentos_fallidos y bloqueo para Seguridad 4.2.2
CREATE TABLE hijos (
  id_hijo           INT           AUTO_INCREMENT PRIMARY KEY,
  nombre            VARCHAR(100)  NOT NULL,
  edad              INT           NOT NULL,
  avatar            VARCHAR(255)  NULL,
  pin_hash          VARCHAR(255)  NOT NULL,
  monedas           INT           NOT NULL DEFAULT 0 CHECK (monedas >= 0),
  monedas_tope      INT           NULL,
  intentos_fallidos INT           NOT NULL DEFAULT 0, -- Para bloqueo PIN
  bloqueado_hasta   DATETIME      NULL,               -- Para bloqueo PIN
  id_padre          INT           NOT NULL,
  activo            BOOLEAN       NOT NULL DEFAULT TRUE,
  CONSTRAINT fk_hijo_padre FOREIGN KEY (id_padre) 
    REFERENCES usuarios(id_usuario) ON DELETE CASCADE -- Borrar padre borra hijos
) ENGINE=InnoDB;

-- 3. TAREAS (Plantillas)
CREATE TABLE tareas (
  id_tarea           INT          AUTO_INCREMENT PRIMARY KEY,
  titulo             VARCHAR(200) NOT NULL,
  descripcion        TEXT         NULL,
  monedas_recompensa INT          NOT NULL DEFAULT 0,
  tipo               ENUM('PUNTUAL','RECURRENTE') NOT NULL,
  recurrencia        ENUM('DIARIA','SEMANAL','PERSONALIZADA') NULL,
  dias_semana        VARCHAR(20)  NULL,
  estado             ENUM('ACTIVA','ARCHIVADA') NOT NULL DEFAULT 'ACTIVA',
  fecha_creacion     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  fecha_fin          DATE         NULL,
  id_hijo            INT          NOT NULL,
  CONSTRAINT fk_tarea_hijo FOREIGN KEY (id_hijo) 
    REFERENCES hijos(id_hijo) ON DELETE CASCADE -- Borrar hijo borra sus tareas
) ENGINE=InnoDB;

-- 4. TAREA_INSTANCIAS (El día a día)
-- Corrección: Unicidad (id_tarea, fecha_programada) y CASCADES
CREATE TABLE tarea_instancias (
  id_instancia     INT  AUTO_INCREMENT PRIMARY KEY,
  id_tarea         INT  NOT NULL,
  id_hijo          INT  NOT NULL,
  fecha_programada DATE NOT NULL,
  estado           ENUM('PENDIENTE','COMPLETADA','VALIDADA','RECHAZADA','CADUCADA')
                   NOT NULL DEFAULT 'PENDIENTE',
  fecha_completada DATETIME     NULL, -- NULL hasta que el hijo marca "hecha"
  fecha_validada   DATETIME     NULL, -- NULL hasta que el padre decide
  comentario_padre VARCHAR(500) NULL,
  CONSTRAINT fk_instancia_tarea FOREIGN KEY (id_tarea) 
    REFERENCES tareas(id_tarea) ON DELETE CASCADE,
  CONSTRAINT fk_instancia_hijo FOREIGN KEY (id_hijo) 
    REFERENCES hijos(id_hijo) ON DELETE CASCADE,
  -- Evita que se genere la misma tarea para el mismo niño el mismo día
  UNIQUE KEY uk_tarea_hijo_fecha (id_tarea, id_hijo, fecha_programada)
) ENGINE=InnoDB;

-- 5. RECOMPENSAS
CREATE TABLE recompensas (
  id_recompensa      INT          AUTO_INCREMENT PRIMARY KEY,
  nombre             VARCHAR(150) NOT NULL,
  descripcion        TEXT         NULL,
  monedas_necesarias INT          NOT NULL,
  imagen_url         VARCHAR(255) NULL,
  activa             BOOLEAN      NOT NULL DEFAULT TRUE,
  id_padre           INT          NOT NULL,
  CONSTRAINT fk_recompensa_padre FOREIGN KEY (id_padre) 
    REFERENCES usuarios(id_usuario) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 6. CANJES
-- Corrección: Se mantiene RESTRICT en id_recompensa para no borrar algo canjeado
CREATE TABLE canjes (
  id_canje         INT      AUTO_INCREMENT PRIMARY KEY,
  id_hijo          INT      NOT NULL,
  id_recompensa    INT      NOT NULL,
  monedas_gastadas INT      NOT NULL,
  fecha_solicitud  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  fecha_resolucion DATETIME NULL, -- NULL mientras esté pendiente
  estado           ENUM('PENDIENTE','APROBADO','RECHAZADO','ENTREGADO')
                   NOT NULL DEFAULT 'PENDIENTE',
  comentario       VARCHAR(500) NULL,
  CONSTRAINT fk_canje_hijo FOREIGN KEY (id_hijo) REFERENCES hijos(id_hijo) ON DELETE CASCADE,
  CONSTRAINT fk_canje_recompensa FOREIGN KEY (id_recompensa) REFERENCES recompensas(id_recompensa) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 7. HISTORIAL_MONEDAS
CREATE TABLE historial_monedas (
  id_historia     INT      AUTO_INCREMENT PRIMARY KEY,
  id_hijo         INT      NOT NULL,
  cantidad        INT      NOT NULL,
  saldo_anterior  INT      NOT NULL,
  saldo_posterior INT      NOT NULL,
  motivo          ENUM('TAREA','ACTIVIDAD','CANJE','AJUSTE_PADRE','BONIFICACION') NOT NULL,
  id_referencia   INT      NULL,
  fecha           DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_historia_hijo FOREIGN KEY (id_hijo) REFERENCES hijos(id_hijo) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 8. AUDITORIA
CREATE TABLE auditoria (
  id_auditoria INT          AUTO_INCREMENT PRIMARY KEY,
  id_usuario   INT          NULL, -- NULL si actúa el hijo
  id_hijo      INT          NULL, -- NULL si actúa el padre
  accion       VARCHAR(100) NOT NULL,
  entidad      VARCHAR(50)  NULL,
  id_entidad   INT          NULL,
  detalle      TEXT         NULL,
  ip_origen    VARCHAR(45)  NULL,
  fecha        DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_auditoria_usuario FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario) ON DELETE SET NULL,
  CONSTRAINT fk_auditoria_hijo FOREIGN KEY (id_hijo) REFERENCES hijos(id_hijo) ON DELETE SET NULL
) ENGINE=InnoDB;