$arRegions = Region::getRegions(true);

foreach ($arRegions as $arRegion)
{
	$arSettingsOptions['region_type']['VALUES'][$arRegion['CODE']] = $arRegion['NAME'];
	if (!$arRegion['ACTIVE']) $arSettingsOptions['region_type']['DISABLED_VALUES'][] = $arRegion['CODE'];
}