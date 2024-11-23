##PageStats Plugin for DokuWiki

This plugin counts the number of pages in your DokuWiki and calculates the total size of the pages.

##Features

Display the total number of pages in your DokuWiki.

Display the total size of all pages in megabytes (MB).

Separate outputs for pages (~~PAGESTATSPAGE~~) and size (~~PAGESTATSMB~~).

Combined output for both statistics (~~PAGESTATS~~).

##Installation

Download the ZIP file from the latest release.

Go to your DokuWiki Admin -> Plugins -> Install Plugin.

Upload the ZIP file and activate the plugin.

##Usage

Add the following syntax to any page to display the desired statistics:

`~~PAGESTATS~~`: Displays both the total number of pages and the total size in MB.

`~~PAGESTATSPAGE~~`: Displays only the total number of pages.

`~~PAGESTATSMB~~`: Displays only the total size in MB.

##Notes

Ensure that the data/pages directory is accessible and contains your DokuWiki content.

Statistics are calculated dynamically and reflect the current state of your DokuWiki pages.