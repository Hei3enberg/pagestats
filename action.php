<?php
/**
 * DokuWiki Plugin pagestats (Action Component)
 * Counts the number of pages and media files and calculates their total size.
 */

if (!defined('DOKU_INC')) die();

class action_plugin_pagestats extends DokuWiki_Action_Plugin {

    public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE', $this, 'add_stats_to_page');
    }

    public function add_stats_to_page(Doku_Event $event, $param) {
        $dataPathPages = DOKU_INC . 'data/pages';
        $dataPathMedia = DOKU_INC . 'data/media';

        $stats = [
            'pagestatspage' => $this->countFiles($dataPathPages, 'txt'),
            'pagestatsmb' => $this->calculateSize($dataPathPages, 'txt'),
            'mediastatspage' => $this->countFiles($dataPathMedia, ''),
            'mediastatsmb' => $this->calculateSize($dataPathMedia, '')
        ];

        foreach ($stats as $name => $value) {
            $event->data['meta'][] = ['name' => $name, 'content' => $value];
        }
    }

    private function countFiles($path, $extension) {
        if (!is_dir($path)) return 0;

        $count = 0;
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        foreach ($iterator as $file) {
            if ($file->isFile() && ($extension === '' || $file->getExtension() === $extension)) {
                $count++;
            }
        }

        return $count;
    }

    private function calculateSize($path, $extension) {
        if (!is_dir($path)) return 0;

        $totalSize = 0;
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        foreach ($iterator as $file) {
            if ($file->isFile() && ($extension === '' || $file->getExtension() === $extension)) {
                $totalSize += $file->getSize();
            }
        }

        return round($totalSize / (1024 * 1024), 2); // In MB
    }
}
