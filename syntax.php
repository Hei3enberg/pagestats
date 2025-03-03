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
        return trim($match, '~');
    }

    public function render($mode, Doku_Renderer $renderer, $data) {
        if ($mode !== 'xhtml') return false;

        /** @var helper_plugin_pagestats $helper */
        $helper = plugin_load('helper', 'pagestats');
        if (!$helper) return false;

        $stats = $helper->getStats();

        if (isset($stats[$data])) {
            $value = $stats[$data];

            // Add "MB" unit if configured and it's a size value
            if ($this->getConf('showUnit') && in_array($data, ['PAGESTATSMB', 'MEDIASTATSMB'])) {
                $value .= " " . $this->getLang('unit_mb');
            }

            // Debug-Modus: Zeige die Sprachschlüssel
            // $renderer->doc .= '<pre>Verfügbare Sprachschlüssel: ' . print_r($this->lang, true) . '</pre>';

            // Output zu renderer
            $renderer->doc .= hsc($value);
        }

        return true;
    }
}