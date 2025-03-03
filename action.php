<?php
/**
 * DokuWiki Plugin pagestats (Action Component)
 * Counts the number of pages and media files and calculates their total size.
 */

if (!defined('DOKU_INC')) die();

class action_plugin_pagestats extends DokuWiki_Action_Plugin {

    /**
     * Register hooks
     */
    public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE', $this, 'add_stats_to_page');
        $controller->register_hook('IO_WIKIPAGE_WRITE', 'AFTER', $this, 'clear_cache_on_change');
        $controller->register_hook('MEDIA_UPLOAD_FINISH', 'AFTER', $this, 'clear_cache_on_change');
        $controller->register_hook('MEDIA_DELETE_FILE', 'AFTER', $this, 'clear_cache_on_change');
    }

    /**
     * Add page stats to meta headers
     */
    public function add_stats_to_page(Doku_Event $event, $param) {
        /** @var helper_plugin_pagestats $helper */
        $helper = plugin_load('helper', 'pagestats');
        if (!$helper) return;

        $stats = $helper->getStats();

        foreach ($stats as $name => $value) {
            $event->data['meta'][] = ['name' => strtolower($name), 'content' => $value];
        }
    }

    /**
     * Clear cache when pages or media files change
     */
    public function clear_cache_on_change(Doku_Event $event, $param) {
        /** @var helper_plugin_pagestats $helper */
        $helper = plugin_load('helper', 'pagestats');
        if ($helper) {
            $helper->clearCache();
        }
    }
}