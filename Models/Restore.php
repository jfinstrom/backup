<?php
/**
 * Copyright Sangoma Technologies, Inc 2017
 */
namespace FreePBX\modules\Backup\Models;
use FreePBX\modules\Backup\Models\SplFileInfo as BackupFileSplFileInfo;
use Exception;
use FreePBX\modules\Backup\Handlers\FreePBXModule;
abstract class Restore extends ModelBase {
	protected $moduleHandler;

	/**
	 * Constructor, do not allow overridding this
	 *
	 * @param FreePBX $freepbx The FreePBX BMO Object
	 * @param array $modData The Module Data
	 * @param string $backupModVer The Backup Module's Version
	 * @param string $backupTmpDir The Backup Temporary file location
	 */
	final public function __construct($freepbx, $backupModVer, $logger, $transactionId, $modData, $backupTmpDir) {
		parent::__construct($freepbx, $backupModVer, $logger, $transactionId, $modData);
		$this->FreePBX = $freepbx;
		$this->tmpdir = $backupTmpDir;
		//Load the FreePBX Module Handler
		$this->moduleHandler = new FreePBXModule($freepbx);

		foreach($this->data['files'] as &$file) {
			$file = new BackupFileSplFileInfo(
				$this->tmpdir.'/files'.$file['pathto'].'/'.$file['filename'],
				$file['type'],
				$file['pathto'],
				$file['base']
			);
		}

		foreach($this->data['dirs'] as &$file) {
			$file = new BackupFileSplFileInfo(
				$this->tmpdir.'/files'.$file,
				'dir',
				$file,
				''
			);
		}
	}

	public function reset() {
		$this->moduleHandler->reset($this->data['module'], $this->data['version']);
	}
}
