<?
namespace {Vendor}\{Module};

use \Bitrix\Main\Loader;
use \Bitrix\Main\Data\Cache as BxCache;

Loader::includeModule('iblock');

class Cache {
	public static function _prepareCacheParams($functionName, $arCache)
	{
		$arCache['TAG'] = ($arCache['TAG']) ?: '_all';
		$arCache['PATH'] = ($arCache['PATH']) ?: '/'.__CLASS__.'/'.$functionName.'/'.$arCache['TAG'].'/';
		$arCache['TIME'] = (intVal($arCache['TIME'])) ?: 36000000;
		return $arCache;
	}

	public static function CIBlock_GetList($arCache = ['ID' => '', 'PATH' => '', 'TIME' => 36000000, 'TAG' => ''], $arOrder = ['SORT' => 'ASC'], $arFilter = [], $bIncCnt = false)
	{
		$arCache['ID'] = ($arCache['ID']) ?: __FUNCTION__.'_'.md5(serialize(array_merge((array)$arOrder, (array)$arFilter, (array)$bIncCnt)));
		$arCache = self::_prepareCacheParams(__FUNCTION__, $arCache);

		$cache = BxCache::createInstance();
		if ($cache->initCache($arCache['TIME'], $arCache['ID'], $arCache['PATH']))
		{
			$arResult = $cache->getVars();
		}
		elseif ($cache->startDataCache())
		{
			$arResult = array();
			$dbRes = \CIBlock::GetList($arOrder, $arFilter, $bIncCnt);
			while($item = $dbRes->Fetch())
			{
				$arResult[$item['ID']] = $item;
			}
			$cache->endDataCache($arResult);
		}
		return $arResult;
	}

	public static function CIBlockElement_GetList($arCache = ['ID' => '', 'PATH' => '', 'TIME' => 36000000, 'TAG' => ''], $arOrder = ['SORT' => 'ASC'], $arFilter = [], $arGroupBy = false, $arNavStartParams = false, $arSelect = [])
	{
		$arCache['ID'] = ($arCache['ID']) ?: __FUNCTION__.'_'.md5(serialize(array_merge((array)$arOrder, (array)$arFilter, (array)$arGroupBy, (array)$arNavStartParams, (array)$arSelect)));
		$arCache = self::_prepareCacheParams(__FUNCTION__, $arCache);

		if (!in_array('ID', $arSelect)) $arSelect[] = 'ID';
		$cache = BxCache::createInstance();
		if ($cache->initCache($arCache['TIME'], $arCache['ID'], $arCache['PATH']))
		{
			$arResult = $cache->getVars();
		}
		elseif ($cache->startDataCache())
		{
			$arResult = [];
			$dbRes = \CIBlockElement::GetList($arOrder, $arFilter, $arGroupBy, $arNavStartParams, $arSelect);
			while($obItem = $dbRes->GetNextElement())
			{
				$arItem = $obItem->GetFields();
				$arItem['PROPERTIES'] = $obItem->GetProperties();
				$arResult[$arItem['ID']] = $arItem;
			}
			$cache->endDataCache($arResult);
		}
		return $arResult;
	}

	public static function CIBlockSection_GetList($arCache = ['ID' => '', 'PATH' => '', 'TIME' => 36000000, 'TAG' => ''], $arOrder = ['SORT' => 'ASC'], $arFilter = [], $bIncCnt = false, $arSelect = [], $arNavStartParams = false)
	{
		$arCache['ID'] = ($arCache['ID']) ?: __FUNCTION__.'_'.md5(serialize(array_merge((array)$arOrder, (array)$arFilter, (array)$bIncCnt, (array)$arSelect, (array)$arNavStartParams)));
		$arCache = self::_prepareCacheParams(__FUNCTION__, $arCache);

		if (!in_array('ID', $arSelect)) $arSelect[] = 'ID';
		$cache = BxCache::createInstance();
		if ($cache->initCache($arCache['TIME'], $arCache['ID'], $arCache['PATH']))
		{
			$arResult = $cache->getVars();
		}
		elseif ($cache->startDataCache())
		{
			$arResult = [];
			$dbRes = \CIBlockSection::GetList($arOrder, $arFilter, $bIncCnt, $arSelect, $arNavStartParams);
			while($item = $dbRes->GetNext())
			{
				$arResult[$item['ID']] = $item;
			}
			$cache->endDataCache($arResult);
		}
		return $arResult;
	}

	public static function CUserTypeEntity_GetList($arCache = ['ID' => '', 'PATH' => '', 'TIME' => 36000000, 'TAG' => ''], $arOrder = ['SORT' => 'ASC'], $arFilter = [])
	{
		$arCache['ID'] = ($arCache['ID']) ?: __FUNCTION__.'_'.md5(serialize(array_merge((array)$arOrder, (array)$arFilter)));
		$arCache = self::_prepareCacheParams(__FUNCTION__, $arCache);

		$cache = BxCache::createInstance();
		if ($cache->initCache($arCache['TIME'], $arCache['ID'], $arCache['PATH']))
		{
			$arResult = $cache->getVars();
		}
		elseif ($cache->startDataCache())
		{
			$arResult = [];
			$dbRes = \CUserTypeEntity::GetList($arOrder, $arFilter);
			while($item = $dbRes->GetNext())
			{
				$arResult[$item['ID']] = $item;
			}
			$cache->endDataCache($arResult);
		}
		return $arResult;
	}

	public static function CUser_GetList($arCache = ['ID' => '', 'PATH' => '', 'TIME' => 36000000, 'TAG' => ''], &$by = 'timestamp_x', &$order = "desc", $arFilter = [], $arParams = [])
	{
		$arCache['ID'] = ($arCache['ID']) ?: __FUNCTION__.'_'.md5(serialize(array_merge((array)$by, (array)$order, (array)$arFilter, (array)$arParams)));
		$arCache = self::_prepareCacheParams(__FUNCTION__, $arCache);

		$cache = BxCache::createInstance();
		if ($cache->initCache($arCache['TIME'], $arCache['ID'], $arCache['PATH']))
		{
			$arResult = $cache->getVars();
		}
		elseif ($cache->startDataCache())
		{
			$arResult = [];
			$dbRes = \CUser::GetList($by, $order, $arFilter, $arParams);
			while($item = $dbRes->GetNext())
			{
				$arResult[$item['ID']] = $item;
			}
			$cache->endDataCache($arResult);
		}
		return $arResult;
	}
}