<?php
/**
 * DokuWiki Plugin pagestats (Admin Component)
 * Administration interface for PageStats plugin
 */

if (!defined('DOKU_INC')) die();

class admin_plugin_pagestats extends DokuWiki_Admin_Plugin {

    /**
     * Access for managers
     */
    public function forAdminOnly() {
        return false;
    }

    /**
     * Return sort order for position in admin menu
     */
    public function getMenuSort() {
        return 200;
    }

    /**
     * Display name in admin menu
     */
    public function getMenuText($language) {
        return 'Page Stats';
    }

    /**
     * Handle user request
     */
    public function handle() {
        global $INPUT;
        
        if ($INPUT->str('action') === 'clearCache' && checkSecurityToken()) {
            /** @var helper_plugin_pagestats $helper */
            $helper = plugin_load('helper', 'pagestats');
            if ($helper) {
                $helper->clearCache();
                msg($this->getLang('admin_cache_cleared'), 1);
            }
        }
    }

    /**
     * Output HTML for the admin page
     */
    public function html() {
        global $ID;
        
        /** @var helper_plugin_pagestats $helper */
        $helper = plugin_load('helper', 'pagestats');
        if (!$helper) {
            echo '<div class="error">Failed to load PageStats helper</div>';
            return;
        }
        
        $stats = $helper->getStats();
        
        echo '<h1>' . $this->getLang('admin_title') . '</h1>';
        
        echo '<div class="level1">';
        echo '<p>' . $this->getLang('admin_intro') . '</p>';
        echo '</div>';
        
        echo '<h2>' . $this->getLang('admin_current_stats') . '</h2>';
        echo '<div class="table">';
        echo '<table class="inline">';
        echo '<tr><th>Statistic</th><th>Value</th></tr>';
        echo '<tr><td>' . $this->getLang('admin_total_pages') . '</td><td>' . hsc($stats['PAGESTATSPAGE']) . '</td></tr>';
        echo '<tr><td>' . $this->getLang('admin_pages_size') . '</td><td>' . hsc($stats['PAGESTATSMB']) . ' ' . $this->getLang('unit_mb') . '</td></tr>';
        echo '<tr><td>' . $this->getLang('admin_total_media') . '</td><td>' . hsc($stats['MEDIASTATSPAGE']) . '</td></tr>';
        echo '<tr><td>' . $this->getLang('admin_media_size') . '</td><td>' . hsc($stats['MEDIASTATSMB']) . ' ' . $this->getLang('unit_mb') . '</td></tr>';
        echo '</table>';
        echo '</div>';
        
        echo '<h2>' . $this->getLang('admin_usage_title') . '</h2>';
        echo '<div class="level2">';
        echo '<p>' . $this->getLang('admin_usage_text') . '</p>';
        echo '<ul>';
        echo '<li><code>~~PAGESTATSPAGE~~</code> - ' . $this->getLang('page_stats_count') . '</li>';
        echo '<li><code>~~PAGESTATSMB~~</code> - ' . $this->getLang('page_stats_size') . '</li>';
        echo '<li><code>~~MEDIASTATSPAGE~~</code> - ' . $this->getLang('media_stats_count') . '</li>';
        echo '<li><code>~~MEDIASTATSMB~~</code> - ' . $this->getLang('media_stats_size') . '</li>';
        echo '</ul>';
        echo '<p><strong>' . $this->getLang('admin_nocache_note') . '</strong></p>';
        echo '</div>';
        
        echo '<h2>' . $this->getLang('admin_cache_title') . '</h2>';
        echo '<div class="level2">';
        echo '<p>' . $this->getLang('admin_cache_text') . '</p>';
        
        // Cache-Informationen anzeigen
        $cacheTime = $this->getConf('cacheTime');
        echo '<p>';
        if ($cacheTime > 0) {
            echo sprintf('Cache-Zeit: %d Sekunden (%.1f Stunden)', 
                $cacheTime, 
                $cacheTime / 3600
            );
            echo '<br/><small>Die Cache-Zeit kann im DokuWiki Admin → Konfiguration geändert werden.</small>';
        } else {
            echo 'Cache ist aktuell deaktiviert (cacheTime = 0)';
            echo '<br/><small>Der Cache kann im DokuWiki Admin → Konfiguration aktiviert werden.</small>';
        }
        echo '</p>';
        
        // Cache leeren Formular
        $form = new Doku_Form(array('method' => 'post', 'id' => 'pagestats_form'));
        $form->addHidden('id', $ID);
        $form->addHidden('action', 'clearCache');
        $form->addHidden('sectok', getSecurityToken());
        $form->addElement(form_makeButton('submit', '', $this->getLang('admin_clear_cache')));
        echo $form->getForm();
        
        echo '</div>';
    }
}