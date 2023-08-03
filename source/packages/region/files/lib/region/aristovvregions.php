<?
namespace {Vendor}\{Module}\Region;

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

class AristovVregions extends \{Vendor}\{Module}\Region
{
	public function getName()
	{
		return Loc::getMessage('EWP_AVIONIKA_OPTIONS_OPTION_REGION_TYPE_ARISTOV_VREGIONS');
	}

	public function getCode()
	{
		return 'aristov.vregions';
	}

	public function isActive()
	{
		return !!Loader::includeSharewareModule('aristov.vregions');
	}

	public function getRegionName()
	{
		return $_SESSION["VREGIONS_REGION"]['NAME'] ?? $_SESSION["VREGIONS_DEFAULT"]['NAME'] ?? '';
	}

	public function getRegionPhone()
	{
		if ($_SESSION["VREGIONS_REGION"]['TELEFON'])
		{
			return [
				'VALUE' => $_SESSION["VREGIONS_REGION"]['TELEFON'],
				'TEL' => preg_replace('/[^\d+]/', '', $_SESSION["VREGIONS_REGION"]['TELEFON']),
			];
		}
		return false;
	}
}