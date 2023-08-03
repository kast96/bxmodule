<?
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;

Loc::loadMessages(__FILE__);

global $APPLICATION;

Loader::registerAutoLoadClasses(
	'{vendor}.{module}',
	[
		'\\{Vendor}\\{Module}\\Main' => 'lib/main.php',
{include:autoload}
	]
);
?>