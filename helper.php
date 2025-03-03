<?php
/**
 * DokuWiki Plugin pagestats (Helper Component)
 * Common functionality for page stats calculation
 */

if (!defined('DOKU_INC')) die();

class helper_plugin_pagestats extends DokuWiki_Plugin {
    private $cache = null;
    private $cacheTime = 3600; // Cache for 1 hour by default

    /**
     * Constructor - reads configuration
     */
    public function __construct() {
        // Get cache lifetime from configuration (if set)
        $cacheTime = $this->getConf('cacheTime');
        if (is_numeric($cacheTime)) {
            $this->cacheTime = (int)$cacheTime;
        }
    }

    /**
     * Calculate all stats at once to avoid multiple directory scans
     *
     * @return array Associative array with all statistics
     */
    public function getStats() {
        // Check if we have cached values
        $cache = $this->loadCache();
        if ($cache !== null) {
            return $cache;
        }

        // Calculate all stats
        try {
            $dataPathPages = DOKU_INC . 'data/pages';
            $dataPathMedia = DOKU_INC . 'data/media';
            
            $excludeNamespaces = array_map('trim', explode(',', $this->getConf('excludeNamespaces')));

            $stats = [
                'PAGESTATSPAGE' => 0,
                'PAGESTATSMB' => 0,
                'MEDIASTATSPAGE' => 0,
                'MEDIASTATSMB' => 0
            ];

            // Pages stats
            list($count, $size) = $this->calculateStats($dataPathPages, 'txt', $excludeNamespaces);
            $stats['PAGESTATSPAGE'] = $count;
            $stats['PAGESTATSMB'] = round($size / (1024 * 1024), 2);

            // Media stats
            list($count, $size) = $this->calculateStats($dataPathMedia, '', $excludeNamespaces);
            $stats['MEDIASTATSPAGE'] = $count;
            $stats['MEDIASTATSMB'] = round($size / (1024 * 1024), 2);

            // Cache the results
            $this->saveCache($stats);

            return $stats;
        } catch (Exception $e) {
            $this->log('pagestats', 'Error calculating stats: '.$e->getMessage(), DOKU_INC.'pagestats_error.log');
            return [
                'PAGESTATSPAGE' => 0,
                'PAGESTATSMB' => 0,
                'MEDIASTATSPAGE' => 0,
                'MEDIASTATSMB' => 0
            ];
        }
    }

    /**
     * Calculate both count and size in one iteration
     *
     * @param string $path Directory path
     * @param string $extension File extension to filter, empty for all
     * @param array $excludeNamespaces Namespaces to exclude
     * @return array [count, size]
     */
    private function calculateStats($path, $extension, $excludeNamespaces = []) {
        if (!is_dir($path)) return [0, 0];

        $count = 0;
        $totalSize = 0;
        
        try {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                // Skip if not a file or doesn't match extension
                if (!$file->isFile() || ($extension !== '' && $file->getExtension() !== $extension)) {
                    continue;
                }
                
                // Skip if in excluded namespace
                $relativePath = str_replace($path, '', $file->getPathname());
                $shouldExclude = false;
                
                foreach ($excludeNamespaces as $ns) {
                    if (empty($ns)) continue;
                    if (strpos($relativePath, '/'.$ns.'/') !== false) {
                        $shouldExclude = true;
                        break;
                    }
                }
                
                if ($shouldExclude) continue;
                
                // Count and add size
                $count++;
                $totalSize += $file->getSize();
            }
        } catch (Exception $e) {
            // Log error but continue with what we have
            $this->log('pagestats', 'Error scanning directory: '.$e->getMessage(), DOKU_INC.'pagestats_error.log');
        }

        return [$count, $totalSize];
    }

    /**
     * Save stats to cache
     * 
     * @param array $stats The stats to cache
     */
    private function saveCache($stats) {
        if ($this->cacheTime <= 0) return; // Caching disabled
        
        $cacheFile = $this->getCacheFilename();
        $data = [
            'time' => time(),
            'stats' => $stats
        ];
        
        io_saveFile($cacheFile, serialize($data));
    }

    /**
     * Load stats from cache if available and not expired
     * 
     * @return array|null Stats or null if cache invalid/expired
     */
    private function loadCache() {
        if ($this->cacheTime <= 0) return null; // Caching disabled
        if ($this->cache !== null) return $this->cache; // Already loaded
        
        $cacheFile = $this->getCacheFilename();
        
        if (!file_exists($cacheFile)) return null;
        
        $data = unserialize(io_readFile($cacheFile, false));
        
        if (!$data || !isset($data['time']) || !isset($data['stats'])) {
            return null;
        }
        
        // Check if cache expired
        if (time() - $data['time'] > $this->cacheTime) {
            return null;
        }
        
        $this->cache = $data['stats'];
        return $this->cache;
    }

    /**
     * Get the cache filename
     * 
     * @return string Full path to cache file
     */
    private function getCacheFilename() {
        return DOKU_INC . 'data/cache/pagestats.cache';
    }

    /**
     * Simple logging function
     * 
     * @param string $plugin Plugin name
     * @param string $message Log message
     * @param string $file Log file
     */
    private function log($plugin, $message, $file) {
        $time = date('Y-m-d H:i:s');
        $logline = "[$time] [$plugin] $message\n";
        io_saveFile($file, $logline, true);
    }

    /**
     * Clear the cache (e.g. if called from admin)
     */
    public function clearCache() {
        $cacheFile = $this->getCacheFilename();
        if (file_exists($cacheFile)) {
            @unlink($cacheFile);
        }
        $this->cache = null;
    }
}