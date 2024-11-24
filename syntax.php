<?php
/**
 * DokuWiki Plugin pagestats (Syntax Component)
 * Allows using ~~PAGESTATSPAGE~~, ~~PAGESTATSMB~~, ~~MEDIASTATSPAGE~~, and ~~MEDIASTATSMB~~ to display stats.
 */

if (!defined('DOKU_INC')) die();

class syntax_plugin_pagestats extends DokuWiki_Syntax_Plugin {

    public function getType() {
        return 'substition';
    }

    public function getSort() {
        return 999;
    }

    public function connectTo($mode) {
        $this->Lexer->addSpecialPattern('~~PAGESTATSPAGE~~', $mode, 'plugin_pagestats');
        $this->Lexer->addSpecialPattern('~~PAGESTATSMB~~', $mode, 'plugin_pagestats');
        $this->Lexer->addSpecialPattern('~~MEDIASTATSPAGE~~', $mode, 'plugin_pagestats');
        $this->Lexer->addSpecialPattern('~~MEDIASTATSMB~~', $mode, 'plugin_pagestats');
    }

    public function handle($match, $state, $pos, Doku_Handler $handler) {
        return trim($match, '~'); // Gibt die genaue Syntax zurück
    }

public function render($mode, Doku_Renderer $renderer, $data) {
    if ($mode !== 'xhtml') return false;

    $dataPathPages = DOKU_INC . 'data/pages';
    $dataPathMedia = DOKU_INC . 'data/media';

    $stats = [
        'PAGESTATSPAGE' => $this->countFiles($dataPathPages, 'txt'),
        'PAGESTATSMB' => $this->calculateSize($dataPathPages, 'txt'),
        'MEDIASTATSPAGE' => $this->countFiles($dataPathMedia, ''),
        'MEDIASTATSMB' => $this->calculateSize($dataPathMedia, '')
    ];

    if (isset($stats[$data])) {
        // Zahl direkt ausgeben
        $value = $stats[$data];

        // "MB" bei Speicherangaben anhängen
        if (in_array($data, ['PAGESTATSMB', 'MEDIASTATSMB'])) {
            $value .= " MB";
        }

        // Ausgabe in den Renderer einfügen
        $renderer->doc .= hsc($value);
    }

    return true;
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
