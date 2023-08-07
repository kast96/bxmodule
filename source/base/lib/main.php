<?
namespace {Vendor}\{Module};

use \Bitrix\Main\Config\Option;

class Main
{
	const moduleId = '{vendor}.{module}';

	public static function getOption($name, $default = '', $siteId = false)
	{
		$value = Option::get(self::moduleId, $name, $default, $siteId);
		$value = unserialize($value) ?: $value;
		return $value;
	}

	public static function setOption($name, $value, $siteId = false)
	{
		return Option::set(self::moduleId, $name, $value, $siteId);
	}

	public static function getMenuChilds(array $arItems, int &$start = 0, int $level = 0)
	{
		$arChilds = [];

		if(!$level)
		{
			$lastDepthLevel = 1;
			if(is_array($arItems))
			{
				foreach($arItems as $i => $arItem)
				{
					if($arItem['DEPTH_LEVEL'] > $lastDepthLevel)
					{
						if($i > 0)
						{
							$arItems[$i - 1]['IS_PARENT'] = 1;
						}
					}
					$lastDepthLevel = $arItem['DEPTH_LEVEL'];
				}
			}
		}

		for($i = $start, $count = count($arItems); $i < $count; ++$i)
		{
			$item = $arItems[$i];
			if($level > $item['DEPTH_LEVEL'] - 1)
			{
				break;
			}
			elseif(!empty($item['IS_PARENT']))
			{
				++$i;
				$item['CHILD'] = self::getMenuChilds($arItems, $i, $item['DEPTH_LEVEL']);
				--$i;
			}
			$arChilds[] = $item;
		}

		$start = $i;

		return $arChilds;
	}
}