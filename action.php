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
        list($pageCount, $totalSizeMB) = $this->getPageStats();

        // Statistiken einzeln im Meta-Header speichern
        $event->data['meta'][] = ['name' => 'pagestatspage', 'content' => $pageCount];
        $event->data['meta'][] = ['name' => 'pagestatsmb', 'content' => $totalSizeMB];
    }

    private function getPageStats() {
        $dataPath = DOKU_INC . 'data/pages';

        if (!is_dir($dataPath)) return [0, 0];

        $pageCount = 0;
        $totalSize = 0;

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dataPath));
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'txt') {
                $pageCount++;
                $totalSize += $file->getSize();
            }
        }

        return [$pageCount, round($totalSize / (1024 * 1024), 2)];
    }
}
