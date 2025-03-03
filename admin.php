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
                msg('PageStats cache cleared successfully', 1);
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
        
        echo '<h1>Page Stats Overview</h1>';
        
        echo '<div class="level1">';
        echo '<p>This plugin provides statistics about your DokuWiki installation.</p>';
        echo '</div>';
        
        echo '<h2>Current Statistics</h2>';
        echo '<div class="table">';
        echo '<table class="inline">';
        echo '<tr><th>Statistic</th><th>Value</th></tr>';
        echo '<tr><td>Total Pages</td><td>' . hsc($stats['PAGESTATSPAGE']) . '</td></tr>';
        echo '<tr><td>Pages Size</td><td>' . hsc($stats['PAGESTATSMB']) . ' MB</td></tr>';
        echo '<tr><td>Total Media Files</td><td>' . hsc($stats['MEDIASTATSPAGE']) . '</td></tr>';
        echo '<tr><td>Media Size</td><td>' . hsc($stats['MEDIASTATSMB']) . ' MB</td></tr>';
        echo '</table>';
        echo '</div>';
        
        echo '<h2>Usage Instructions</h2>';
        echo '<div class="level2">';
        echo '<p>You can use the following syntax in any wiki page:</p>';
        echo '<ul>';
        echo '<li><code>~~PAGESTATSPAGE~~</code> - Displays the total number of pages</li>';
        echo '<li><code>~~PAGESTATSMB~~</code> - Displays the total size of all pages in MB</li>';
        echo '<li><code>~~MEDIASTATSPAGE~~</code> - Displays the total number of media files</li>';
        echo '<li><code>~~MEDIASTATSMB~~</code> - Displays the total size of all media files in MB</li>';
        echo '</ul>';
        echo '<p><strong>Note:</strong> To avoid cached statistics, add <code>~~NOCACHE~~</code> at the beginning of the page.</p>';
        echo '</div>';
        
        echo '<h2>Cache Management</h2>';
        echo '<div class="level2">';
        echo '<p>The statistics are cached to improve performance. You can clear the cache if needed:</p>';
        
        $form = new Doku_Form(array('method' => 'post', 'id' => 'pagestats_form'));
        $form->addHidden('id', $ID);
        $form->addHidden('action', 'clearCache');
        $form->addHidden('sectok', getSecurityToken());
        $form->addElement(form_makeButton('submit', '', 'Clear Statistics Cache'));
        echo $form->getForm();
        
        echo '</div>';
    }
}