<?
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\ModuleManager;

Loc::loadMessages(__FILE__);

Class {vendor}_{module} extends CModule
{
	var $MODULE_ID = '{vendor}.{module}';
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $PARTNER_NAME;
	var $PARTNER_URI;
	var $MODULE_GROUP_RIGHTS = 'Y';

	function __construct()
	{
		$arModuleVersion = [];
		include(__DIR__.'/version.php');
				
		$this->MODULE_VERSION = $arModuleVersion['VERSION'];
		$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
		$this->MODULE_NAME = Loc::getMessage('{VENDOR}_{MODULE}_NAME');
		$this->MODULE_DESCRIPTION = Loc::getMessage('{VENDOR}_{MODULE}_DESCRIPTION');
		$this->PARTNER_NAME = Loc::getMessage('{VENDOR}_{MODULE}_PARTNER_NAME');
		$this->PARTNER_URI = Loc::getMessage('{VENDOR}_{MODULE}_PARTNER_URI');
	}

	public function DoInstall()
	{
		global $APPLICATION;
		if (CheckVersion(ModuleManager::getVersion('main'), '14.00.00'))
		{
			$this->InstallFiles();
			ModuleManager::registerModule($this->MODULE_ID);
			$this->InstallDB();
			$this->InstallEvents();
		}
		else
		{
			$APPLICATION->ThrowException(Loc::getMessage('{VENDOR}_{MODULE}_INSTALL_ERROR_VERSION'));
		}
		$APPLICATION->IncludeAdminFile(Loc::getMessage('{VENDOR}_{MODULE}_INSTALL_TITLE').' \"'.Loc::getMessage('{VENDOR}_{MODULE}_NAME').'\"', __DIR__.'/step.php');
		
		return true;
	}
	
	public function InstallFiles()
	{
		foreach (['bitrix', 'local'] as $vendor)
		{
			if(file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$vendor.'/modules/'.$this->MODULE_ID.'/install/admin'))
			{
				CopyDirFiles($_SERVER['DOCUMENT_ROOT'].'/'.$vendor.'/modules/'.$this->MODULE_ID.'/install/js/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/js/'.$this->MODULE_ID.'/', true, true);
			}
		}
		return true;
	}
	
	public function InstallDB()
	{
		return true;
	}
	
	public function InstallEvents()
	{
		return true;
	}
	
	public function DoUninstall()
	{
		global $APPLICATION;
		$this->UnInstallFiles();
		$this->UnInstallDB();
		$this->UnInstallEvents();
		ModuleManager::unRegisterModule($this->MODULE_ID);
		$APPLICATION->IncludeAdminFile(Loc::getMessage('{VENDOR}_{MODULE}_UNINSTALL_TITLE').' \"'.Loc::getMessage('{VENDOR}_{MODULE}_NAME').'\"', __DIR__.'/unstep.php');
		
		return true;
	}
	
	public function UnInstallFiles()
	{
		foreach (['bitrix', 'local'] as $vendor)
		{
			if(file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$vendor.'/modules/'.$this->MODULE_ID.'/install/admin'))
			{
				DeleteDirFiles($_SERVER["DOCUMENT_ROOT"].'/'.$vendor.'/modules/'.$this->MODULE_ID.'/install/js/', $_SERVER["DOCUMENT_ROOT"].'/bitrix/js/'.$this->MODULE_ID.'/');
			}
		}
		return true;
	}
	
	public function UnInstallDB()
	{
		return true;
	}
	
	public function UnInstallEvents()
	{
		return true;
	}
}
?>