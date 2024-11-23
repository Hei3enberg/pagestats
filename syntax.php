<?php
/**
 * DokuWiki Plugin pagestats (Syntax Component)
 * Allows using ~~PAGESTATS~~, ~~PAGESTATSPAGE~~, and ~~PAGESTATSMB~~ to display stats.
 */

if (!defined('DOKU_INC')) die();

class syntax_plugin_pagestats extends DokuWiki_Syntax_Plugin {

    /**
     * Gibt an, wo die Syntax verarbeitet wird.
     */
    public function getType() {
        return 'substition'; // Die Syntax wird ersetzt.
    }

    /**
     * Reihenfolge der Plugin-Verarbeitung.
     */
    public function getSort() {
        return 999; // Geringere Zahl = höhere Priorität
    }

    /**
     * Verbindung zur Syntax herstellen.
     */
    public function connectTo($mode) {
        $this->Lexer->addSpecialPattern('~~PAGESTATS(?:PAGE|MB)?~~', $mode, 'plugin_pagestats');
    }

    /**
     * Verarbeitung der Syntax und Rückgabe der Daten.
     */
    public function handle($match, $state, $pos, Doku_Handler $handler) {
        $type = 'all'; // Standard: alles anzeigen
        if (strpos($match, 'PAGE') !== false) $type = 'page';
        if (strpos($match, 'MB') !== false) $type = 'mb';

        return ['type' => $type];
    }

    /**
     * Verarbeitung und Ausgabe der Syntax.
     */
    public function render($mode, Doku_Renderer $renderer, $data) {
        if ($mode !== 'xhtml') return false;

        list($pageCount, $totalSizeMB) = $this->getPageStats();

        // Statistiken je nach Typ ausgeben
        switch ($data['type']) {
            case 'page':
                $renderer->doc .= "<span><strong>" . hsc($this->getLang('page_stats_count')) . "</strong> " . hsc($pageCount) . "</span>";
                break;
            case 'mb':
                $renderer->doc .= "<span><strong>" . hsc($this->getLang('page_stats_size')) . "</strong> " . hsc($totalSizeMB) . " MB</span>";
                break;
            default:
                $renderer->doc .= "<span><strong>" . hsc($this->getLang('page_stats_count')) . "</strong> " . hsc($pageCount) . "</span> | ";
                $renderer->doc .= "<span><strong>" . hsc($this->getLang('page_stats_size')) . "</strong> " . hsc($totalSizeMB) . " MB</span>";
                break;
        }

        return true;
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
