<?php
/**
 * English language file for pagestats plugin
 */

$lang['unit_mb'] = 'MB';

// Statistics output
$lang['page_stats_count'] = 'Total Pages:';
$lang['page_stats_size'] = 'Total Size:';
$lang['media_stats_count'] = 'Total Media Files:';
$lang['media_stats_size'] = 'Media Size:';

// Admin interface
$lang['admin_title'] = 'Page Stats Overview';
$lang['admin_intro'] = 'This plugin provides statistics about your DokuWiki installation.';
$lang['admin_current_stats'] = 'Current Statistics';
$lang['admin_total_pages'] = 'Total Pages';
$lang['admin_pages_size'] = 'Pages Size';
$lang['admin_total_media'] = 'Total Media Files';
$lang['admin_media_size'] = 'Media Size';
$lang['admin_usage_title'] = 'Usage Instructions';
$lang['admin_usage_text'] = 'You can use the following syntax in any wiki page:';
$lang['admin_nocache_note'] = 'Note: To avoid outdated statistics, add <code>~~NOCACHE~~</code> at the beginning of the page.';
$lang['admin_cache_title'] = 'Cache Management';
$lang['admin_cache_text'] = 'The statistics are cached to improve performance. You can clear the cache if needed:';
$lang['admin_clear_cache'] = 'Clear Statistics Cache';
$lang['admin_cache_cleared'] = 'PageStats cache cleared successfully';

// Cache information
$lang['admin_cache_time'] = 'Cache Lifetime:';
$lang['admin_cache_time_seconds'] = 'seconds';
$lang['admin_cache_time_hours'] = 'hours';
$lang['admin_cache_time_config_hint'] = 'The cache lifetime can be changed in DokuWiki Admin → Configuration.';
$lang['admin_cache_disabled'] = 'Cache is currently disabled (cacheTime = 0)';
$lang['admin_cache_enable_hint'] = 'The cache can be enabled in DokuWiki Admin → Configuration.';