# PageStats Plugin for DokuWiki

This plugin counts the number of pages and media files in your DokuWiki and calculates their total size.

## Features
- Display the total number of pages.
- Display the total size of all pages in megabytes (MB).
- Display the total number of media files.
- Display the total size of all media files in megabytes (MB).
- Performance optimization through caching (default: 1 hour)
- Admin interface for cache management
- Configuration options for excluding namespaces
- Multilingual support (English/German)

## Installation
1. Download the ZIP file from the latest release.
2. Go to your DokuWiki Admin -> Plugins -> Install Plugin.
3. Upload the ZIP file and activate the plugin.

## Configuration
You can configure the plugin in the Admin -> Configuration Settings area:

- **cacheTime**: Cache lifetime in seconds (default: 3600 = 1 hour, 0 to disable caching)
- **excludeNamespaces**: Comma-separated list of namespaces to exclude from statistics
- **showUnit**: Whether to show "MB" unit after size values

## Usage
You can use the following syntax in any page to display the statistics:

- `~~PAGESTATSPAGE~~`: Displays the total number of pages.
- `~~PAGESTATSMB~~`: Displays the total size of all pages in MB.
- `~~MEDIASTATSPAGE~~`: Displays the total number of media files.
- `~~MEDIASTATSMB~~`: Displays the total size of all media files in MB.

### Important Note:
To avoid incorrect or outdated statistics due to page caching, add the `~~NOCACHE~~` directive at the beginning of the page where you use these commands.

## Admin Interface
The plugin adds an admin page under "Admin -> Page Stats" where you can:
- View current statistics
- See information about the current cache settings
- Clear the statistics cache manually
- See usage instructions

## Performance
The plugin caches results to improve performance. By default, the cache is set to 1 hour (3600 seconds). The cache is automatically cleared when:
- Pages are created, edited, or deleted
- Media files are uploaded or deleted
- The cache expires (configurable in Admin -> Configuration)
- The cache is manually cleared via the admin interface

## Notes
- Make sure that the `data/pages` and `data/media` directories are accessible.
- Statistics are calculated dynamically and reflect the current state of your DokuWiki files.
- For large wikis, consider increasing the cache time to reduce server load.

## Changelog

### 2.1.1 (2025-03-03)
- Fixed cache time configuration issue (cache is now enabled by default with 1 hour lifetime)
- Improved multilingual support for cache management settings
- Updated admin interface to show cache time information in the current language
- Fixed documentation to clarify cache settings

### 2.1.0 (2025-03-03)
- Added multilingual admin interface (English/German)
- Improved configuration management with UI controls
- Added automatic cache invalidation when settings change
- Enhanced cache management with visual feedback
- Better integration with DokuWiki's language system

### 2.0.0 (2025-03-03)
- Added caching mechanism for better performance
- New admin interface for viewing stats and managing cache
- Added configuration options for excluding namespaces
- Added multilingual support
- Code refactoring to eliminate duplication
- Better error handling
- Automatic cache clearing when content changes

### 1.2.0 (2024-11-24)
- Added media file statistics with new tags (~~MEDIASTATSPAGE~~ and ~~MEDIASTATSMB~~)
- Improved code structure
- Enhanced documentation

### 1.1.0 (2024-11-22)
- Added separate syntax tags for individual statistics (~~PAGESTATSPAGE~~ and ~~PAGESTATSMB~~)
- Improved flexibility in how statistics can be displayed
- Added basic internationalization support
- Better documentation

### 1.0.0 (2024-11-22)
- Initial release with basic page counting functionality
- Single ~~PAGESTATS~~ syntax for displaying all statistics at once