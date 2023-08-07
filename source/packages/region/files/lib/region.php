<?
namespace {Vendor}\{Module};

use \{Vendor}\{Module}\Main;

abstract class Region
{
	public static function getInstance()
	{
		$arRegions = self::getRegions();
		foreach ($arRegions as $region)
		{
			if ($region->getCode() == Main::getOption('region_type')) return $region;
		}
		return false;
	}

	public static function getRegions(bool $returnArray = false)
	{
		$arRegions = [];
		$dir = __DIR__.'/region/';

		$arFiles = scandir($dir);
		$arFiles = array_filter($arFiles, function($file)
		{
			return pathinfo($file, PATHINFO_EXTENSION) === 'php';
		});

		foreach ($arFiles as $file)
		{
			include $dir.$file;
		}

		$arClasses = get_declared_classes();
		foreach ($arClasses as $class)
		{
			if (is_subclass_of($class, '\{Vendor}\{Module}\Region'))
			{
				$region = (new $class);
				if ($returnArray)
				{
					$arRegions[] = [
						'NAME' => $region->getName(),
						'CODE' => $region->getCode(),
						'ACTIVE' => $region->isActive(),
					];
				}
				else
				{
					$arRegions[] = $region;
				}
	 		}
		}

		return $arRegions;
	}

	abstract public function getName();
	abstract public function getCode();
	abstract public function isActive();
	abstract public function getRegionName();
	abstract public function getRegionPhone();
}