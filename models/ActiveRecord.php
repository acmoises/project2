<?php
namespace Model;

use PDO;
use PDOException;

class ActiveRecord {
    // Base de datos
    protected static $db;
    protected static $tabla = '';
    protected static $columnasDB = [];

    // Alertas y Mensajes
    protected static $alertas = [];

    // Definir la conexión a la BD
    public static function setDB($database) {
        self::$db = $database;
    }

    // Setear un tipo de alerta
    public static function setAlerta($tipo, $mensaje) {
        static::$alertas[$tipo][] = $mensaje;
    }

    // Obtener las alertas
    public static function getAlertas() {
        return static::$alertas;
    }

    // Validación que se hereda en modelos
    public function validar() {
        static::$alertas = [];
        return static::$alertas;
    }

    // Consulta SQL para obtener resultados
    public static function consultarSQL($query, $params = []) {
        try {
            $stmt = self::$db->prepare($query);
            $stmt->execute($params);
            $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array_map([static::class, 'crearObjeto'], $registros);
        } catch (PDOException $e) {
            die("Error en la consulta: " . $e->getMessage());
        }
    }

    // Crear objeto en memoria a partir de datos de la BD
    protected static function crearObjeto($registro) {
        $objeto = new static;
        foreach ($registro as $key => $value) {
            if (property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }
        return $objeto;
    }

    // Identificar y unir los atributos de la BD
    public function atributos() {
        $atributos = [];
        foreach (static::$columnasDB as $columna) {
            if ($columna === 'id') continue;
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    // Sanitizar los datos
    public function sanitizarAtributos() {
        return $this->atributos();
    }

    // Sincronizar con los datos enviados
    public function sincronizar($args = []) {
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

    // Guardar un registro (crear o actualizar)
    public function guardar() {
        return !is_null($this->id) ? $this->actualizar() : $this->crear();
    }

    // Obtener todos los registros
    public static function all($orden = 'DESC') {
        $query = "SELECT * FROM " . static::$tabla . " ORDER BY id ${orden}";
        return self::consultarSQL($query);
    }

    // Encontrar un registro por ID
    public static function find($id) {
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = :id";
        $resultado = self::consultarSQL($query, ['id' => $id]);
        return array_shift($resultado);
    }

    // Consulta Plana de SQL (Utilizar cuando los métodos del modelo no son suficientes)
    public static function SQL($query) {
        $resultado = self::consultarSQL($query);
        return $resultado;
    }

    // Crear un nuevo registro
    public function crear() {
        try {
            $atributos = $this->sanitizarAtributos();
            $columnas = join(', ', array_keys($atributos));
            $placeholders = ":" . join(', :', array_keys($atributos));

            $query = "INSERT INTO " . static::$tabla . " (${columnas}) VALUES (${placeholders})";
            $stmt = self::$db->prepare($query);
            $stmt->execute($atributos);

            $this->id = self::$db->lastInsertId();
            return true;
        } catch (PDOException $e) {
            die("Error al crear: " . $e->getMessage());
        }
    }

    // Actualizar un registro existente
    public function actualizar() {
        try {
            $atributos = $this->sanitizarAtributos();
            $valores = [];
            foreach ($atributos as $key => $value) {
                $valores[] = "{$key} = :{$key}";
            }
            $valores = join(', ', $valores);

            $query = "UPDATE " . static::$tabla . " SET ${valores} WHERE id = :id";
            $atributos['id'] = $this->id;

            $stmt = self::$db->prepare($query);
            return $stmt->execute($atributos);
        } catch (PDOException $e) {
            die("Error al actualizar: " . $e->getMessage());
        }
    }

    // Eliminar un registro por ID
    public function eliminar() {
        try {
            $query = "DELETE FROM " . static::$tabla . " WHERE id = :id LIMIT 1";
            $stmt = self::$db->prepare($query);
            return $stmt->execute(['id' => $this->id]);
        } catch (PDOException $e) {
            die("Error al eliminar: " . $e->getMessage());
        }
    }
}
