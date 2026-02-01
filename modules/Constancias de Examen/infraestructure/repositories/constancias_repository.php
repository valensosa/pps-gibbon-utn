<?php

class ConstanciasRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Obtiene todas las solicitudes de constancias
     * (para admin / preceptor)
     */
    public function findAll(): array
    {
        $sql = "
            SELECT 
                sc.id,
                sc.materia,
                sc.fechaExamen,
                sc.presentarAnte,
                sc.estado,
                sc.createdAt,
                p.firstName,
                p.surname
            FROM examCertificateRequests sc
            JOIN gibbonPerson p 
              ON sc.gibbonPersonID = p.gibbonPersonID
            ORDER BY sc.createdAt DESC
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Solo solicitudes pendientes
     */
    public function findPending(): array
    {
        $sql = "
            SELECT 
                sc.id,
                sc.materia,
                sc.fechaExamen,
                sc.presentarAnte,
                sc.estado,
                sc.createdAt,
                p.firstName,
                p.surname
            FROM examCertificateRequests sc
            JOIN gibbonPerson p 
              ON sc.gibbonPersonID = p.gibbonPersonID
            WHERE sc.estado = 'Pendiente'
            ORDER BY sc.createdAt ASC
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cambiar estado de una solicitud
     */
    public function updateStatus(int $requestId, string $status): void
    {
        $sql = "
            UPDATE examCertificateRequests
            SET estado = :estado
            WHERE id = :id
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'estado' => $status,
            'id' => $requestId
        ]);
    }

    /**
     * Obtener solicitudes de un alumno
     */
    public function findByStudent(int $gibbonPersonID): array
    {
        $sql = "
            SELECT 
                id,
                materia,
                fechaExamen,
                presentarAnte,
                estado,
                createdAt
            FROM examCertificateRequests
            WHERE gibbonPersonID = :personId
            ORDER BY createdAt DESC
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'personId' => $gibbonPersonID
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crear nueva solicitud (lo que hoy hace submit.php)
     */
    public function create(
        int $gibbonPersonID,
        string $materia,
        string $fechaExamen,
        string $presentarAnte
    ): void {
        $sql = "
            INSERT INTO examCertificateRequests
                (gibbonPersonID, materia, fechaExamen, presentarAnte, estado, createdAt)
            VALUES
                (:personId, :materia, :fecha, :presentar, 'Pendiente', NOW())
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'personId' => $gibbonPersonID,
            'materia' => $materia,
            'fecha' => $fechaExamen,
            'presentar' => $presentarAnte
        ]);
    }
}
