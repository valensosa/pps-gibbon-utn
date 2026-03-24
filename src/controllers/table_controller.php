<?php

namespace App\Controllers;

use App\Infrastructure\Repository\StudentRepository;
use App\Infrastructure\Repository\FirestoreRepository;

class TableController
{
    private StudentRepository $studentRepo;
    private FirestoreRepository $firestoreRepo;

    public function __construct(StudentRepository $studentRepo, FirestoreRepository $firestoreRepo)
    {
        $this->studentRepo   = $studentRepo;
        $this->firestoreRepo = $firestoreRepo;
    }

    public function handle(): string
    {
        $gibbonPersonID = isset($_GET['gibbonPersonID']) ? (int) $_GET['gibbonPersonID'] : null;
        $pageNumber     = isset($_GET['page']) ? (int) $_GET['page'] : 1;

        if (!$gibbonPersonID) {
            return '<div class="alert alert-danger">No se pudo identificar al estudiante.</div>';
        }

        $dni = $this->studentRepo->getDniByPersonId($gibbonPersonID);

        if (!$dni) {
            return '<div class="alert alert-danger">No se encontró el documento del estudiante.</div>';
        }

        $docs = $this->firestoreRepo->getByDni($dni);

        $tableData = array_map(function ($doc) {
            $data = FirestoreRepository::parseDocument($doc);
            $data['constanciaId'] = FirestoreRepository::getDocumentId($doc);
            return $data;
        }, $docs);

        $tableData = $this->sort($tableData);

        [$paginatedData, $pageNumber, $totalPages] = $this->paginate($tableData, $pageNumber);

        return $this->render($paginatedData, $pageNumber, $totalPages);
    }

    // ─────────────────────────────────────────
    // Privados
    // ─────────────────────────────────────────

    private function sort(array $data): array
    {
        $statusOrder = ['pendiente' => 1, 'completado' => 2, 'rechazado' => 3];

        usort($data, function ($a, $b) use ($statusOrder) {
            $aOrder = $statusOrder[$a['estado']] ?? 99;
            $bOrder = $statusOrder[$b['estado']] ?? 99;

            if ($aOrder !== $bOrder) {
                return $aOrder <=> $bOrder;
            }

            return strtotime($b['fechaPedido'] ?? 0) <=> strtotime($a['fechaPedido'] ?? 0);
        });

        return $data;
    }

    private function paginate(array $data, int $pageNumber, int $rowsPerPage = 10): array
    {
        $totalRows  = count($data);
        $totalPages = max(1, (int) ceil($totalRows / $rowsPerPage));
        $pageNumber = max(1, min($pageNumber, $totalPages));
        $offset     = ($pageNumber - 1) * $rowsPerPage;

        return [array_slice($data, $offset, $rowsPerPage), $pageNumber, $totalPages];
    }

    private function render(array $paginatedData, int $pageNumber, int $totalPages): string
    {
        ob_start();
        include __DIR__ . '/../views/table_view.php';
        return ob_get_clean();
    }
}