<?php

namespace App\Controllers;

class TableController
{
    public function handle(): string
    {
        global $connection2;

        // 🔹 1. Obtener parámetros
        $gibbonPersonID = $_GET['gibbonPersonID'] ?? null;
        $pageNumber = $_GET['page'] ?? 1;

        if (!$gibbonPersonID) {
            return '<div class="alert alert-danger">No se pudo identificar al estudiante.</div>';
        }

        // 🔹 2. Obtener tipo de documento
        $sqlTipo = "SELECT gibbonPersonalDocumentTypeID 
                    FROM gibbonPersonalDocumentType 
                    WHERE name = 'Documento'";
        $stmtTipo = $connection2->prepare($sqlTipo);
        $stmtTipo->execute();
        $tipoRow = $stmtTipo->fetch();

        if (!$tipoRow) {
            return '<div class="alert alert-danger">No se encontró el tipo de documento.</div>';
        }

        $tipoID = $tipoRow['gibbonPersonalDocumentTypeID'];

        // 🔹 3. Obtener DNI
        $sqlDoc = "SELECT documentNumber 
                   FROM gibbonPersonalDocument 
                   WHERE foreignTable = 'gibbonPerson' 
                   AND foreignTableID = :gibbonPersonID 
                   AND gibbonPersonalDocumentTypeID = :tipoID 
                   LIMIT 1";

        $stmtDoc = $connection2->prepare($sqlDoc);
        $stmtDoc->execute([
            'gibbonPersonID' => $gibbonPersonID,
            'tipoID' => $tipoID
        ]);

        if ($stmtDoc->rowCount() != 1) {
            return '<div class="alert alert-danger">No se encontró el documento.</div>';
        }

        $dniAlumno = $stmtDoc->fetch()['documentNumber'];

        // 🔹 4. Obtener constancias (Firestore)
        $constancias = getStudentConstancias($dniAlumno);

        $tableData = [];
        foreach ($constancias as $doc) {
            $data = parseFirestoreDocument($doc);
            $data['constanciaId'] = getFirestoreDocumentId($doc);
            $tableData[] = $data;
        }

        // 🔹 5. Ordenar
        usort($tableData, function ($a, $b) {
            $statusOrder = [
                'pendiente' => 1,
                'completado' => 2,
                'rechazado' => 3,
            ];

            $aOrder = $statusOrder[$a['estado']] ?? 99;
            $bOrder = $statusOrder[$b['estado']] ?? 99;

            if ($aOrder !== $bOrder) {
                return $aOrder <=> $bOrder;
            }

            $aDate = strtotime($a['fechaPedido'] ?? 0);
            $bDate = strtotime($b['fechaPedido'] ?? 0);

            return $bDate <=> $aDate;
        });

        // 🔹 6. Paginación
        $rowsPerPage = 10;
        $totalRows = count($tableData);
        $totalPages = ceil($totalRows / $rowsPerPage);

        $pageNumber = max(1, min($pageNumber, $totalPages));
        $offset = ($pageNumber - 1) * $rowsPerPage;

        $paginatedData = array_slice($tableData, $offset, $rowsPerPage);

        // 🔹 7. Render
        return $this->render($paginatedData, $pageNumber, $totalPages);
    }

    private function render(array $paginatedData, int $pageNumber, int $totalPages): string
    {
        ob_start();

        include __DIR__ . '/../views/table.view.php';

        return ob_get_clean();
    }
}