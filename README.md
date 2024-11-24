# PageStats Plugin for DokuWiki

This plugin counts the number of pages and media files in your DokuWiki and calculates their total size.

## Features
- Display the total number of pages.
- Display the total size of all pages in megabytes (MB).
- Display the total number of media files.
- Display the total size of all media files in megabytes (MB).

## Installation
1. Download the ZIP file from the latest release.
2. Go to your DokuWiki Admin -> Plugins -> Install Plugin.
3. Upload the ZIP file and activate the plugin.

## Usage
You can use the following syntax in any page to display the statistics:

- `~~PAGESTATSPAGE~~`: Displays the total number of pages.
- `~~PAGESTATSMB~~`: Displays the total size of all pages in MB.
- `~~MEDIASTATSPAGE~~`: Displays the total number of media files.
- `~~MEDIASTATSMB~~`: Displays the total size of all media files in MB.

### Important Note:
To avoid incorrect or outdated statistics due to page caching, add the `~~NOCACHE~~` directive at the beginning of the page where you use these commands.

## Notes
- Make sure that the `data/pages` and `data/media` directories are accessible.
- Statistics are calculated dynamically and reflect the current state of your DokuWiki files.
