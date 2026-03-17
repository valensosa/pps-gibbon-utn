<?php

namespace App\Controllers;

class TableController
{
    public function handle(): string
    {
        // 🔹 1. Obtener datos (mock por ahora)
        $data = [
            ['materia' => 'Matemática', 'fecha' => '2026-03-10'],
            ['materia' => 'Física', 'fecha' => '2026-03-12'],
        ];

        // 🔹 2. Renderizar vista
        return $this->render($data);
    }

    private function render(array $data): string
    {
        ob_start();

        foreach ($data as $row) {
            echo "<tr>";
            echo "<td>{$row['materia']}</td>";
            echo "<td>{$row['fecha']}</td>";
            echo "</tr>";
        }

        return ob_get_clean();
    }
}