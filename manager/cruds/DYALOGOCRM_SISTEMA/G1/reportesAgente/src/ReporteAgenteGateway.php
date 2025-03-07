<?php


class ReporteAgenteGateway
{
    // private PDO $cann;

    public function __construct(Database $database)
    {
        $this->cann = $database->getConnection();
        $this->BaseDatos = $database->BaseDatos;
    }

    private function processResourceSQLById(string $sql, int $id): array
    {
        # code...

        $stmt = $this->cann->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            # code...
            array_push($data, $row);
        }

        return $data;
    }

    public function getAll(): array
    {
        # code...
        $sql = "SELECT * FROM DYALOGOCRM_SISTEMA.CONDIA WHERE CONDIA_ConsInte__USUARI_b = 2007";

        $stmt = $this->cann->query($sql);
        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            # code...
            array_push($data, $row);
        }

        return $data;
    }

    // obtengo todos los canales por usuario
    public function getCanal(int $id): array
    {
        # id = 2007
        // echo "idxxx => $id";

        // $sql = "SELECT * FROM  {$this->BaseDatos}.DY_V_CONDIA WHERE AGENTE_ID = :id";
        $sql = "SELECT CANAL FROM {$this->BaseDatos}.DY_V_CONDIA WHERE AGENTE_ID = :id AND CANAL <> '' GROUP BY CANAL";

        return $this->processResourceSQLById($sql, $id);
    }

    // obtengo todas las campañas por usuario
    public function getCampanas(int $id): array
    {
        # id = 2007
        // echo "idxxx => $id";


        $sql = "SELECT CAMPANA FROM {$this->BaseDatos}.DY_V_CONDIA WHERE AGENTE_ID = :id AND CAMPANA <> '' GROUP BY CAMPANA";

        // "SELECT CANAL FROM {$this->BaseDatos}.DY_V_CONDIA WHERE AGENTE_ID = :id AND CANAL <> '' GROUP BY CANAL";


        return $this->processResourceSQLById($sql, $id);
    }

    // obtengo todas las estrategias por usuario
    public function getEstrategia(int $id): array
    {
        # id = 2007
        // echo "idxxx => $id";

        $sql = "SELECT ESTRATEGIA FROM {$this->BaseDatos}.DY_V_CONDIA WHERE AGENTE_ID = :id AND ESTRATEGIA <> '' GROUP BY ESTRATEGIA";

        return $this->processResourceSQLById($sql, $id);
    }


    // obtengo todos los datos ultima gestion por usuario
    public function getUltimaGestion(int $id): array
    {
        # id = 2007
        // echo "idxxx => $id";


        $sql = "SELECT ULTIMA_GESTION FROM {$this->BaseDatos}.DY_V_CONDIA WHERE AGENTE_ID = :id AND ULTIMA_GESTION <> '' GROUP BY ULTIMA_GESTION";


        return $this->processResourceSQLById($sql, $id);
    }

    // obtengo todas las clasificaciones por usuario
    public function getClasificacion(int $id): array
    {
        # id = 2007
        // echo "idxxx => $id";


        $sql = "SELECT CLASIFICACION FROM {$this->BaseDatos}.DY_V_CONDIA WHERE AGENTE_ID = :id AND CLASIFICACION <> '' GROUP BY CLASIFICACION";


        return $this->processResourceSQLById($sql, $id);
    }


    // obtengo todas los reintentos por usuario
    public function getReintento(int $id): array
    {
        # id = 2007
        // echo "idxxx => $id";


        $sql = "SELECT REINTENTO FROM {$this->BaseDatos}.DY_V_CONDIA WHERE AGENTE_ID = :id AND REINTENTO <> '' GROUP BY REINTENTO";


        return $this->processResourceSQLById($sql, $id);
    }


    // obtengo todas los formularios gestion por usuario
    public function getFormulario(array $data): array
    {
        # id = 2007
        // echo "idxxx => $data['intIdUsuario']";
        // echo "data", json_encode($data['strTipo']);

        $formularios = [];
        $sql = "";
        switch ($data['strTipo']) {
            case 'condia':
                # code...
                // echo "entro case condia", $data['strTipo'];
                $sql = "SELECT FORMULARIO_GESTION FROM {$this->BaseDatos}.DY_V_CONDIA WHERE AGENTE_ID = :id AND FORMULARIO_GESTION <> '' GROUP BY FORMULARIO_GESTION";

                break;
            case 'calidad':
                # code...
                // echo "entro case calidad", $data['strTipo'];

                $sql = "SELECT FORMULARIO_GESTION FROM {$this->BaseDatos}.DY_V_CALHIS WHERE AGENTE_ID = :id AND FORMULARIO_GESTION <> '' GROUP BY FORMULARIO_GESTION";
                break;

            default:
                $formularios = [];
                break;
        }


        return $this->processResourceSQLById($sql, $data['intIdUsuario']);
    }


    // obtengo todas las bases de datos por usuario
    public function getBaseDatos(int $id): array
    {
        # id = 2007
        // echo "idxxx => $id";


        $sql = "SELECT BASE_DATOS FROM {$this->BaseDatos}.DY_V_CONDIA WHERE AGENTE_ID = :id AND BASE_DATOS <> '' GROUP BY BASE_DATOS";

        // "SELECT FORMULARIO_GESTION FROM {$this->BaseDatos}.DY_V_CONDIA WHERE AGENTE_ID = :id AND FORMULARIO_GESTION <> '' GROUP BY FORMULARIO_GESTION";

        return $this->processResourceSQLById($sql, $id);
    }


    // obtengo todas las bases de datos por usuario
    public function getDatoPrincipal(int $id): array
    {
        # id = 2007
        // echo "idxxx => $id";


        $sql = "SELECT DATO_PRINCIPAL_SCRIPT FROM {$this->BaseDatos}.DY_V_CALHI WHERE AGENTE_ID = :id AND DATO_PRINCIPAL_SCRIPT <> '' GROUP BY DATO_PRINCIPAL_SCRIPT ORDER BY DATO_PRINCIPAL_SCRIPT";

        return $this->processResourceSQLById($sql, $id);
    }


    // obtengo todas las bases de datos por usuario
    public function getDatoSecundario(int $id): array
    {
        # id = 2007
        // echo "idxxx => $id";


        $sql = "SELECT DATO_SECUNDARIO_SCRIPT FROM {$this->BaseDatos}.DY_V_CALHIS WHERE AGENTE_ID = :id AND DATO_SECUNDARIO_SCRIPT <> '' GROUP BY DATO_SECUNDARIO_SCRIPT ORDER BY DATO_SECUNDARIO_SCRIPT";

        return $this->processResourceSQLById($sql, $id);
    }

    // obtengo todos los usuarios que califican calidad, por usuario agente calidad
    public function getUsuarioCalifica(int $id): array
    {
        $sql = "SELECT USUARIO_CALIFICA FROM {$this->BaseDatos}.DY_V_CALHIS WHERE AGENTE_ID = :id AND AGENTE_ID <> '' GROUP BY USUARIO_CALIFICA ORDER BY USUARIO_CALIFICA";

        return $this->processResourceSQLById($sql, $id);
    }
}
