<?php

// autoload_classmap.php @generated by Composer

$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'Touki\\FTP\\ConnectionInterface' => $vendorDir . '/touki/ftp/lib/Touki/FTP/ConnectionInterface.php',
    'Touki\\FTP\\Connection\\AnonymousConnection' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Connection/AnonymousConnection.php',
    'Touki\\FTP\\Connection\\Connection' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Connection/Connection.php',
    'Touki\\FTP\\Connection\\SSLConnection' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Connection/SSLConnection.php',
    'Touki\\FTP\\CreatorInterface' => $vendorDir . '/touki/ftp/lib/Touki/FTP/CreatorInterface.php',
    'Touki\\FTP\\CreatorVotableInterface' => $vendorDir . '/touki/ftp/lib/Touki/FTP/CreatorVotableInterface.php',
    'Touki\\FTP\\CreatorVoter' => $vendorDir . '/touki/ftp/lib/Touki/FTP/CreatorVoter.php',
    'Touki\\FTP\\CreatorVoterInterface' => $vendorDir . '/touki/ftp/lib/Touki/FTP/CreatorVoterInterface.php',
    'Touki\\FTP\\Creator\\RecursiveDirectoryCreator' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Creator/RecursiveDirectoryCreator.php',
    'Touki\\FTP\\DeleterInterface' => $vendorDir . '/touki/ftp/lib/Touki/FTP/DeleterInterface.php',
    'Touki\\FTP\\DeleterVotableInterface' => $vendorDir . '/touki/ftp/lib/Touki/FTP/DeleterVotableInterface.php',
    'Touki\\FTP\\DeleterVoter' => $vendorDir . '/touki/ftp/lib/Touki/FTP/DeleterVoter.php',
    'Touki\\FTP\\DeleterVoterInterface' => $vendorDir . '/touki/ftp/lib/Touki/FTP/DeleterVoterInterface.php',
    'Touki\\FTP\\Deleter\\FileDeleter' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Deleter/FileDeleter.php',
    'Touki\\FTP\\Deleter\\RecursiveDirectoryDeleter' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Deleter/RecursiveDirectoryDeleter.php',
    'Touki\\FTP\\DownloaderInterface' => $vendorDir . '/touki/ftp/lib/Touki/FTP/DownloaderInterface.php',
    'Touki\\FTP\\DownloaderVotableInterface' => $vendorDir . '/touki/ftp/lib/Touki/FTP/DownloaderVotableInterface.php',
    'Touki\\FTP\\DownloaderVoter' => $vendorDir . '/touki/ftp/lib/Touki/FTP/DownloaderVoter.php',
    'Touki\\FTP\\DownloaderVoterInterface' => $vendorDir . '/touki/ftp/lib/Touki/FTP/DownloaderVoterInterface.php',
    'Touki\\FTP\\Downloader\\FileDownloader' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Downloader/FileDownloader.php',
    'Touki\\FTP\\Downloader\\NbFileDownloader' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Downloader/NbFileDownloader.php',
    'Touki\\FTP\\Downloader\\NbResourceDownloader' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Downloader/NbResourceDownloader.php',
    'Touki\\FTP\\Downloader\\ResourceDownloader' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Downloader/ResourceDownloader.php',
    'Touki\\FTP\\Exception\\ConnectionEstablishedException' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Exception/ConnectionEstablishedException.php',
    'Touki\\FTP\\Exception\\ConnectionException' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Exception/ConnectionException.php',
    'Touki\\FTP\\Exception\\ConnectionUnestablishedException' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Exception/ConnectionUnestablishedException.php',
    'Touki\\FTP\\Exception\\DirectoryException' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Exception/DirectoryException.php',
    'Touki\\FTP\\Exception\\DownloadException' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Exception/DownloadException.php',
    'Touki\\FTP\\Exception\\FTPException' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Exception/FTPException.php',
    'Touki\\FTP\\Exception\\UploadException' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Exception/UploadException.php',
    'Touki\\FTP\\FTP' => $vendorDir . '/touki/ftp/lib/Touki/FTP/FTP.php',
    'Touki\\FTP\\FTPFactory' => $vendorDir . '/touki/ftp/lib/Touki/FTP/FTPFactory.php',
    'Touki\\FTP\\FTPInterface' => $vendorDir . '/touki/ftp/lib/Touki/FTP/FTPInterface.php',
    'Touki\\FTP\\FTPWrapper' => $vendorDir . '/touki/ftp/lib/Touki/FTP/FTPWrapper.php',
    'Touki\\FTP\\FilesystemFactory' => $vendorDir . '/touki/ftp/lib/Touki/FTP/FilesystemFactory.php',
    'Touki\\FTP\\FilesystemFactoryInterface' => $vendorDir . '/touki/ftp/lib/Touki/FTP/FilesystemFactoryInterface.php',
    'Touki\\FTP\\Manager\\FTPFilesystemManager' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Manager/FTPFilesystemManager.php',
    'Touki\\FTP\\Model\\Directory' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Model/Directory.php',
    'Touki\\FTP\\Model\\File' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Model/File.php',
    'Touki\\FTP\\Model\\Filesystem' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Model/Filesystem.php',
    'Touki\\FTP\\Model\\Permissions' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Model/Permissions.php',
    'Touki\\FTP\\PermissionsFactory' => $vendorDir . '/touki/ftp/lib/Touki/FTP/PermissionsFactory.php',
    'Touki\\FTP\\Tests\\ConnectionAwareTestCase' => $vendorDir . '/touki/ftp/tests/Touki/FTP/Tests/ConnectionAwareTestCase.php',
    'Touki\\FTP\\Tests\\Connection\\ConnectionTest' => $vendorDir . '/touki/ftp/tests/Touki/FTP/Tests/Connection/ConnectionTest.php',
    'Touki\\FTP\\Tests\\CreatorVoterTest' => $vendorDir . '/touki/ftp/tests/Touki/FTP/Tests/CreatorVoterTest.php',
    'Touki\\FTP\\Tests\\Creator\\RecursiveDirectoryCreatorTest' => $vendorDir . '/touki/ftp/tests/Touki/FTP/Tests/Creator/RecursiveDirectoryCreatorTest.php',
    'Touki\\FTP\\Tests\\Deleter\\FTP\\RecursiveDirectoryDeleterTest' => $vendorDir . '/touki/ftp/tests/Touki/FTP/Tests/Deleter/RecursiveDirectoryDeleterTest.php',
    'Touki\\FTP\\Tests\\DownloaderVoterFTPTest' => $vendorDir . '/touki/ftp/tests/Touki/FTP/Tests/DownloaderVoterFTPTest.php',
    'Touki\\FTP\\Tests\\DownloaderVoterTest' => $vendorDir . '/touki/ftp/tests/Touki/FTP/Tests/DownloaderVoterTest.php',
    'Touki\\FTP\\Tests\\Downloader\\FileDownloaderTest' => $vendorDir . '/touki/ftp/tests/Touki/FTP/Tests/Downloader/FileDownloaderTest.php',
    'Touki\\FTP\\Tests\\Downloader\\NbFileDownloaderTest' => $vendorDir . '/touki/ftp/tests/Touki/FTP/Tests/Downloader/NbFileDownloaderTest.php',
    'Touki\\FTP\\Tests\\Downloader\\NbResourceDownloaderTest' => $vendorDir . '/touki/ftp/tests/Touki/FTP/Tests/Downloader/NbResourceDownloaderTest.php',
    'Touki\\FTP\\Tests\\Downloader\\ResourceDownloaderTest' => $vendorDir . '/touki/ftp/tests/Touki/FTP/Tests/Downloader/ResourceDownloaderTest.php',
    'Touki\\FTP\\Tests\\FTPFactoryTest' => $vendorDir . '/touki/ftp/tests/Touki/FTP/Tests/FTPFactoryTest.php',
    'Touki\\FTP\\Tests\\FTPTest' => $vendorDir . '/touki/ftp/tests/Touki/FTP/Tests/FTPTest.php',
    'Touki\\FTP\\Tests\\FilesystemFactoryTest' => $vendorDir . '/touki/ftp/tests/Touki/FTP/Tests/FilesystemFactoryTest.php',
    'Touki\\FTP\\Tests\\Manager\\FTPFilesystemManagerTest' => $vendorDir . '/touki/ftp/tests/Touki/FTP/Tests/Manager/FTPFilesystemManagerTest.php',
    'Touki\\FTP\\Tests\\PermissionsFactoryTest' => $vendorDir . '/touki/ftp/tests/Touki/FTP/Tests/PermissionsFactoryTest.php',
    'Touki\\FTP\\Tests\\UploaderVoterFTPTest' => $vendorDir . '/touki/ftp/tests/Touki/FTP/Tests/UploaderVoterFTPTest.php',
    'Touki\\FTP\\Tests\\UploaderVoterTest' => $vendorDir . '/touki/ftp/tests/Touki/FTP/Tests/UploaderVoterTest.php',
    'Touki\\FTP\\Tests\\Uploader\\FileUploaderTest' => $vendorDir . '/touki/ftp/tests/Touki/FTP/Tests/Uploader/FileUploaderTest.php',
    'Touki\\FTP\\Tests\\Uploader\\NbFileUploaderTest' => $vendorDir . '/touki/ftp/tests/Touki/FTP/Tests/Uploader/NbFileUploaderTest.php',
    'Touki\\FTP\\Tests\\Uploader\\NbResourceUploaderTest' => $vendorDir . '/touki/ftp/tests/Touki/FTP/Tests/Uploader/NbResourceUploaderTest.php',
    'Touki\\FTP\\Tests\\Uploader\\ResourceUploaderTest' => $vendorDir . '/touki/ftp/tests/Touki/FTP/Tests/Uploader/ResourceUploaderTest.php',
    'Touki\\FTP\\Tests\\WindowsFileFactoryTest' => $vendorDir . '/touki/ftp/tests/Touki/FTP/Tests/WindowsFilesystemFactoryTest.php',
    'Touki\\FTP\\UploaderInterface' => $vendorDir . '/touki/ftp/lib/Touki/FTP/UploaderInterface.php',
    'Touki\\FTP\\UploaderVotableInterface' => $vendorDir . '/touki/ftp/lib/Touki/FTP/UploaderVotableInterface.php',
    'Touki\\FTP\\UploaderVoter' => $vendorDir . '/touki/ftp/lib/Touki/FTP/UploaderVoter.php',
    'Touki\\FTP\\UploaderVoterInterface' => $vendorDir . '/touki/ftp/lib/Touki/FTP/UploaderVoterInterface.php',
    'Touki\\FTP\\Uploader\\FileUploader' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Uploader/FileUploader.php',
    'Touki\\FTP\\Uploader\\NbFileUploader' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Uploader/NbFileUploader.php',
    'Touki\\FTP\\Uploader\\NbResourceUploader' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Uploader/NbResourceUploader.php',
    'Touki\\FTP\\Uploader\\ResourceUploader' => $vendorDir . '/touki/ftp/lib/Touki/FTP/Uploader/ResourceUploader.php',
    'Touki\\FTP\\WindowsFilesystemFactory' => $vendorDir . '/touki/ftp/lib/Touki/FTP/WindowsFilesystemFactory.php',
);
