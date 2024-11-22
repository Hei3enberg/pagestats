<?php
/**
 * DokuWiki Plugin pagestats (Action Component)
 * Counts the number of pages and calculates the total size.
 */

if (!defined('DOKU_INC')) die();

class action_plugin_pagestats extends DokuWiki_Action_Plugin {

    public function register(Doku_Event_Handler $controller) {
        // Füge eine Aktion hinzu, z. B. für eine Ausgabe im Footer oder einer speziellen Syntax
        $controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE', $this, 'add_stats_to_page');
    }

    public function add_stats_to_page(Doku_Event $event, $param) {
        $dataPath = DOKU_INC . 'data/pages';
        $pageCount = 0;
        $totalSize = 0;

        // Alle Dateien im data/pages Verzeichnis durchsuchen
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dataPath));
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'txt') {
                $pageCount++;
                $totalSize += $file->getSize();
            }
        }

        // Speicherplatz berechnen und formatieren
        $totalSizeMB = round($totalSize / (1024 * 1024), 2);

        // Ausgabe als Footer-Komponente oder an einer anderen Stelle
        $stats = "Anzahl der Seiten: $pageCount | Gesamter Speicherplatz: $totalSizeMB MB";
        $event->data['meta'][] = ['name' => 'pagestats', 'content' => $stats];
    }
}
