<?php

class StudentRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Obtiene datos básicos del estudiante por gibbonPersonID
     */
    public function getByPersonId(int $gibbonPersonID): ?array
    {
        $sql = "
            SELECT 
                gibbonPersonID,
                username,
                firstName,
                surname,
                email,
                gibbonRoleIDPrimary
            FROM gibbonPerson
            WHERE gibbonPersonID = :id
              AND status = 'Full'
            LIMIT 1
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $gibbonPersonID]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    /**
     * Obtiene el rol del usuario (Student, Staff, etc.)
     */
    public function getUserRoleByPersonId(int $gibbonPersonID): ?string
    {
        $sql = "
            SELECT r.name
            FROM gibbonPerson p
            JOIN gibbonRole r 
              ON p.gibbonRoleIDPrimary = r.gibbonRoleID
            WHERE p.gibbonPersonID = :id
            LIMIT 1
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $gibbonPersonID]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? $row['name'] : null;
    }

    /**
     * Obtiene el DNI del estudiante
     */
    public function getDniByPersonId(int $gibbonPersonID): ?string
    {
        // Obtener tipo de documento "Documento"
        $sqlTipo = "
            SELECT gibbonPersonalDocumentTypeID
            FROM gibbonPersonalDocumentType
            WHERE name = 'Documento'
            LIMIT 1
        ";

        $stmtTipo = $this->connection->prepare($sqlTipo);
        $stmtTipo->execute();
        $tipo = $stmtTipo->fetch(PDO::FETCH_ASSOC);

        if (!$tipo) {
            return null;
        }

        // Obtener número de documento
        $sqlDoc = "
            SELECT documentNumber
            FROM gibbonPersonalDocument
            WHERE foreignTable = 'gibbonPerson'
              AND foreignTableID = :personId
              AND gibbonPersonalDocumentTypeID = :typeId
            LIMIT 1
        ";

        $stmtDoc = $this->connection->prepare($sqlDoc);
        $stmtDoc->execute([
            'personId' => $gibbonPersonID,
            'typeId'   => $tipo['gibbonPersonalDocumentTypeID']
        ]);

        $row = $stmtDoc->fetch(PDO::FETCH_ASSOC);

        return $row ? $row['documentNumber'] : null;
    }
}
