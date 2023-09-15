<?php

use arajcany\ToolBox\ZipPackager;

require __DIR__ . '/config/paths.php';
require __DIR__ . '/vendor/autoload.php';

$appName = 'SampleApp'; //no spaces please!

$date = date("Y-m-d--H-i-s");
$zipFilePath = ROOT . DS . "../{$date}_{$appName}.zip";
$baseDir = ROOT . DS;

print_r("Creating ZIP file {$zipFilePath}...\r\n");

$rejectFilesFolders = [
    ".idea/",
    ".git/",
    "logs/",
    "config/config_database.php",
    "config/config_mail.php",
    ".gitattributes",
    ".gitignore",
    "composer.json",
    "composer.lock",
    "web.config",
    "package.php",
];

$otherFileNamesInVendor = [
    "Dockerfile",
    "docs.Dockerfile",
    "CREDITS",
    "composer.json",
    "composer.lock",
    "psalm.xml",
    "phpstan.neon.dist",
    ".editorconfig",
    ".pullapprove.yml",
    ".gitignore",
];

$zp = new ZipPackager();
$rawFileList = $zp->rawFileList($baseDir);
$rawFileList = $zp->filterOutVendorExtras($rawFileList);
$rawFileList = $zp->filterOutByFileName($rawFileList, $otherFileNamesInVendor);
$rawFileList = $zp->filterOutFoldersAndFiles($rawFileList, $rejectFilesFolders);
$zipList = $zp->convertRawFileListToZipList($rawFileList, $baseDir, $appName);
$result = $zp->makeZipFromZipList($zipFilePath, $zipList);

if ($result) {
    print_r("Created ZIP... Exiting!\r\n");
} else {
    print_r("Failed to created ZIP... Exiting!\r\n");
}
