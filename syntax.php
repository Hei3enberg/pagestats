<?php
/**
 * DokuWiki Plugin pagestats (Syntax Component)
 * Allows using ~~PAGESTATS~~ to display stats.
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
        $this->Lexer->addSpecialPattern('~~PAGESTATS~~', $mode, 'plugin_pagestats');
    }

    /**
     * Keine zusätzlichen Daten erforderlich, daher leer.
     */
    public function handle($match, $state, $pos, Doku_Handler $handler) {
        return [];
    }

    /**
     * Verarbeitung und Ausgabe der Syntax.
     */
    public function render($mode, Doku_Renderer $renderer, $data) {
        if ($mode !== 'xhtml') return false;

        $dataPath = DOKU_INC . 'data/pages';
        $pageCount = 0;
        $totalSize = 0;

        // Alle Dateien im Seitenverzeichnis durchgehen
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dataPath));
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'txt') {
                $pageCount++;
                $totalSize += $file->getSize();
            }
        }

        // Speicherplatz berechnen und formatieren
        $totalSizeMB = round($totalSize / (1024 * 1024), 2);

        // Statistiken ausgeben
        $renderer->doc .= "<div><strong>Anzahl der Seiten:</strong> $pageCount</div>";
        $renderer->doc .= "<div><strong>Gesamter Speicherplatz:</strong> $totalSizeMB MB</div>";

        return true;
    }
}
