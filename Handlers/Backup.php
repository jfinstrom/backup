<?php
/**
 * Copyright Sangoma T
 * chnologies, Inc 2018
 */
namespace FreePBX\modules\Backup\Handlers;
use FreePBX\modules\Backup\Handlers as Handlers;
use FreePBX\modules\Backup\Modules as Module;
use FreePBX\modules\Backup\Models as Models;

class Backup{
	public function __construct($freepbx = null) {
		if ($freepbx == null) {
			throw new \InvalidArgumentException('Not given a BMO Object');
		}
		$this->FreePBX = $freepbx;
		$this->Backup = $freepbx->Backup;
	}

	/**
	 * Run the backup for the given id
	 * @param  string $id            Backup id
	 * @param  string $transactionId UUIDv4 string, if empty one will be generated
	 * @return mixed               true or array of errors
	 */
	public function process($id = '',$transactionId = '', $base64Backup = null, $pid = '') {
		if(empty($id) && empty($base64Backup)){
			throw new \Exception("Backup id not provided", 500);
		}
		$this->Backup->delById('monolog');
		$handler = new Handlers\MonologKVStore($this->Backup);
		$this->Backup->logger->customLog->pushHandler($handler);
		$this->Backup->attachLoggers('backup');
		$pid = !empty($pid)?$pid:posix_getpid();
		$external = !empty($base64Backup);
		$transactionId = !empty($transactionId)?$transactionId:$this->Backup->generateId();
		$this->Backup->setConfig($transactionId,$pid,'running');
		$this->Backup->log($transactionId,sprintf(_("Running Backup ID: %s"),$id));
		$this->Backup->log($transactionId,sprintf(_("Transaction: %s"),$transactionId));
		$this->Backup->log($transactionId,_("Running pre backup hooks"));
		$this->preHooks($id, $transactionId);
		$base64Backup = !empty($base64Backup)?json_decode(base64_decode($base64Backup),true):false;
		$backupInfo = $external?$base64Backup:$this->Backup->getBackup($id);
		$this->Backup->attachEmail($backupInfo);
		$underscoreName = str_replace(' ', '_', $backupInfo['backup_name']);
		$this->Backup->log($transactionId,sprintf(_("Starting backup %s"),$underscoreName));
		$spooldir = $this->FreePBX->Config->get("ASTSPOOLDIR");
		$serverName = str_replace(' ', '_',$this->FreePBX->Config->get('FREEPBX_SYSTEM_IDENT'));
		$localPath = sprintf('%s/backup/%s',$spooldir,$underscoreName);
		$remotePath =  sprintf('/%s/%s',$serverName,$underscoreName);
		$tmpdir = sprintf('%s/backup/%s','/var/spool/asterisk/tmp',$underscoreName);
		@unlink($tmpdir);
		$this->Backup->fs->mkdir($tmpdir);
		//Use Legacy backup naming
		$pharfilename = sprintf('%s%s-%s-%s',date("Ymd-His-"),time(),get_framework_version(),rand());
		$pharnamebase = sprintf('%s/%s',$localPath,$pharfilename);
		$phargzname = sprintf('%s.tar.gz',$pharnamebase);
		$pharname = sprintf('%s.tar',$pharnamebase);
		$this->Backup->log($transactionId,_("This backup will be stored locally is subject to maintinance settings"));
		$this->Backup->log($transactionId,sprintf(_("Storage Location: %s"),$phargzname));
		$phar = new \PharData($pharname);
		$phar->addEmptyDir('/modulejson');
		$phar->addEmptyDir('/files');
		$phar->setSignatureAlgorithm(\Phar::SHA256);
		$storage_ids = $this->Backup->getStorageById($id);
		$this->dependencies = [];
		$processQueue = new \SplQueue();
		$processQueue->setIteratorMode(\SplQueue::IT_MODE_DELETE);
		$data = [];
		$dirs = [];
		$files = [];
		$cleanup = [];
		$manifest = [
			'modules' => [],
			'skipped' => [],
			'date' => time(),
			'backupInfo' => $backupInfo,
		];
		$validmods = $this->getModules();
		$backupItems = $this->Backup->getAll('modules_'.$id);
		if($external){
			$backupItems = $backupInfo['backup_items'];
		}
		$selectedmods = is_array($backupItems)?array_keys($backupItems):[];
		foreach($selectedmods as $mod) {
			$raw = \strtolower($mod);
			$this->sortDepends($raw,false,true);
			if(!in_array($mod, $validmods)){
				$err = sprintf(_("Could not backup module %s, it may not be installed or enabled"),$mod);
				$warnings[] = $err;
				$this->Backup->manifest['skipped'][] = $mod;
				$this->Backup->log($transactionId,$err,'DEBUG');
				continue;
			}
			$mod = $this->FreePBX->Modules->getInfo($raw);
			$processQueue->enqueue(['name' => $mod[$raw]['rawname']]);
		}
		$errors = [];
		$warnings = [];
		if(!$external){
			$maint = new Module\Maintinance($this->FreePBX,$id);
		}
		foreach($processQueue as $mod) {
			$backup = new Models\Backup($this->FreePBX);
			$backup->setBackupId($id);
			\modgettext::push_textdomain(strtolower($mod['name']));
			$class = sprintf('\\FreePBX\\modules\\%s\\Backup', ucfirst($mod['name']));
			if(!class_exists($class)){
				$err = sprintf(_("Couldn't find class %s"),$class);
				$this->Backup->log($transactionId,$err,'WARNING');
				continue;
			}
			$class = new $class($backup,$this->FreePBX);
			$class->runBackup($id,$transactionId);
			\modgettext::pop_textdomain();
			//Skip empty.
			if($backup->getModified() === false){
				$this->Backup->log($transactionId,sprintf(_("%s returned no data. This module may not impliment the new backup yet. Skipping"), $mod['name']));
				$this->Backup->manifest['skipped'][] = $mod['name'];
				continue;
			}
			$dependencies = $backup->getDependencies();
			foreach($dependencies as $depend){
				/** If we are already backing up the module we don't need to put it in the queue
				 * If we haven't implimented backup it won't be there anyway
				 */
				if(in_array($depend, $selectedmods) || !in_array($depend, $validmods)){
					continue;
				}
				/** Add the dependency to the top of the lineup */
				if(in_array($depend, $validmods)){
					$raw = \strtolower($depend);
					$mod = $this->FreePBX->Modules->getInfo($raw);
					$this->sortDepends($mod[$raw]['rawname'],$mod[$raw]['version']);
					$this->processQueue->enqueue($depend);
				}
			}
			$rawname = strtolower($mod['name']);
			$moduleinfo = $this->FreePBX->Modules->getInfo($rawname);
			$manifest['modules'][] = ['module' => $mod['name'], 'version' => $moduleinfo[$rawname]['version']];
			$moddata = $backup->getData();
			foreach ($moddata['dirs'] as $dir) {
				$dirs[] = $this->Backup->getPath('files/' . ltrim($dir['pathto'],'/'));
			}
			foreach ($moddata['files'] as $file) {
				$srcpath = isset($file['pathto'])?$file['pathto']:'';
				if (empty($srcpath)) {
					continue;
				}
				$srcfile = $srcpath .'/'. $file['filename'];
				$destpath = $this->Backup->getPath('files/' . ltrim($file['pathto'],'/'));
				$destfile = $destpath . $file['filename'];
				$dirs[] = $destpath;
				$files[$srcfile] = $destfile;
				$phar->addFile($srcfile,$destfile);
			}
			$mod['name'] = ucfirst($mod['name']);
			$modjson = $tmpdir . '/modulejson/' . $mod['name'] . '.json';
			if (!$this->Backup->fs->exists(dirname($modjson))) {
				$this->Backup->fs->mkdir(dirname($modjson));
			}
			file_put_contents($modjson, json_encode($moddata, JSON_PRETTY_PRINT));
			$phar->addFile($modjson,'modulejson/'.$mod['name'].'.json');
			$data[$mod['name']] = $moddata;
			$cleanup[$mod['name']] = $moddata['garbage'];
		}

		foreach ($dirs as $dir) {
			$phar->addEmptyDir($dir);
		}
		$manifest['processorder'] = $this->dependencies;
		$phar->setMetadata($manifest);
		$phar->compress(\Phar::GZ);
		$signatures = $phar->getSignature();
		//Done with Phar, unlock the file so we can do stuff..
		unset($phar);
		$this->Backup->fs->rename($pharname, $phargzname);
		@unlink($pharname);
		if(!$external){
			$remote = $remotePath.'/'.$pharnamebase.'.tar.gz';
			$this->Backup->log($transactionId,_("Saving to selected Filestore locations"));
			$hash = false;
			if(isset($signatures['hash'])){
				$hash = $signatures['hash'];
					$msg = sprintf(_("SHA256: %s"),$hash);
					$this->Backup->log($transactionId,$msg,'DEBUG');
			}
			foreach ($storage_ids as $location) {
				try {
					$location = explode('_', $location);
					$this->Backup->FreePBX->Filestore->put($location[0],$location[1],file_get_contents($phargzname),$remote);
					if($hash){
						$this->Backup->FreePBX->Filestore->put($location[0],$location[1],$hash,$remote.'.sha256sum');
					}
					$msg = sprintf(_("Saving to: %s instance"),$location[0]);
					$this->Backup->log($transactionId,$msg,'DEBUG');
				} catch (\Exception $e) {
					$err = $e->getMessage();
					$this->Backup->log($transactionId,$err,'ERROR');
					$errors[] = $err;
				}
			}
		}
		$this->Backup->log($transactionId,_("Cleaning up"));
		foreach ($cleanup as $key => $value) {
			$this->Backup->log($transactionId,sprintf(_("Cleaning up data generated by %s"),$key));
			$this->Backup->fs->remove($value);
		}

		if($external && empty($errors)){
			$this->Backup->fs->rename($phargzname,getcwd().'/'.$transactionId.'.tar.gz');
			$this->Backup->log($transactionId,sprintf(_("Remote transaction complete, file saved to %s"),getcwd().'/'.$transactionId.'tar.gz'));
		}
		$this->Backup->fs->remove($tmpdir);
		$this->Backup->fs->remove($pharname);

		if(!$external){
			$this->Backup->log($transactionId,_("Performing Local Maintnance"));
			$maint->processLocal();
			$this->Backup->log($transactionId,_("Performing Remote Maintnance"));
			$maint->processRemote();
		}
		$this->Backup->log($transactionId,_("Running post backup hooks"));
		$this->postHooks($id, $signatures, $errors, $transactionId);
		if(!empty($errors)){
			$this->Backup->log($transactionId,_("Backup finished with but with errors"),'WARNING');
			$this->Backup->processNotifications($id, $transactionId, $errors,true);
			//TODO: Don't think I need this because monolog
			return $errors;
		}
		$this->Backup->log($transactionId,_("Backup completed successfully"));
		$this->Backup->processNotifications($id, $transactionId, [],true);
		$this->Backup->setConfig('log',$this->sessionlog[$transactionId],$transactionId);
		$this->Backup->delConfig($transactionId,'running');
		return $signatures;
	}

	public function settingsMagic() {
		$settings = '';
		$mods = $this->FreePBX->Modules->getModulesByMethod("backupSettings");
		$mods = $this->getModules();
		foreach($mods as $mod) {
			\modgettext::push_textdomain(strtolower($mod));
			$settings .= $this->FreePBX->$mod->backupSettings();
			\modgettext::pop_textdomain();
		}
		return $settings;
	}

	public function processSettings($id,$settings){
		 $this->FreePBX->Hooks->processHooks($id,$settings);
	}
	public function getSettings($id){
		 return $this->FreePBX->Hooks->processHooks($id);
	}
	public function preHooks($id = '', $transactionId = ''){
		$err = [];
		$args = escapeshellarg($id).' '.escapeshellarg($transactionId);
		$this->FreePBX->Hooks->processHooks($id,$transactionId);
		$this->Backup->getHooks('backup');
		foreach($this->Backup->preBackup as $command){
			$cmd  = escapeshellcmd($command).' '.$args;
			exec($cmd,$out,$ret);
			if($ret !== 0){
				$errors[] = sprintf(_("%s finished with a non-zero status"),$cmd);
			}
		}
		unset($this->Backup->preBackup);
		return !empty($errors)?$errors:true;
	}
	public function postHooks($id = '', $signatures = [], $errors = [], $transactionId = ''){
		$err = [];
		$args = escapeshellarg($id).' '.escapeshellarg($transactionId).' '.base64_encode(json_encode($signatures,\JSON_PRETTY_PRINT)).' '.base64_encode(json_encode($errors,\JSON_PRETTY_PRINT));
		$this->FreePBX->Hooks->processHooks($id,$transactionId);
		$this->Backup->getHooks('backup');
		foreach($this->Backup->postBackup as $command){
			$cmd  = escapeshellcmd($command).' '.$args;
			exec($cmd,$out,$ret);
			if($ret !== 0){
				$errors[] = sprintf(_("%s finished with a non-zero status"),$cmd);
			}
		}
		unset($this->Backup->postBackup);
		return !empty($errors)?$errors:true;
	}
	

	/**
	 * Get a list of modules that implement the backup method
	 * @return array list of modules
	 */
	public function getModules($force = false){
		//Cache
		if(isset($this->backupMods) && !empty($this->backupMods) && !$force) {
			return $this->backupMods;
		}
		//All modules impliment the "backup" method so it is a horrible way to know
		//which modules are valid. With the autploader we can do this magic :)
		$webrootpath = $this->FreePBX->Config->get('AMPWEBROOT');
		$webrootpath = (isset($webrootpath) && !empty($webrootpath))?$webrootpath:'/var/www/html';
		$amodules = $this->FreePBX->Modules->getActiveModules();
		$validmods = [];
		foreach ($amodules as $module) {
			$bufile = $webrootpath . '/admin/modules/' . $module['rawname'].'/Backup.php';
			if(file_exists($bufile)){
				$validmods[] = ucfirst($module['rawname']);
			}
		}
		return $validmods;
	}
	public function sortDepends($dependency,$version = false,$skipsort = false){
		if(!$version){
			$moduleinfo = $this->FreePBX->Modules->getInfo($dependency);
			$version = $moduleinfo[$dependency]['version'];
		}
		$tmp[$dependency] = $version;
		foreach ($this->dependencies as $key => $value) {
			$tmp[$key] = $value;
		}
		$this->dependencies = $tmp;
		if(!$skipsort){
			$this->dependencies = array_unique($this->dependencies);
		}
	}
}
