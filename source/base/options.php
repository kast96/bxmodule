<?
use \Bitrix\Main\Localization\Loc;
use	\Bitrix\Main\HttpApplication;
use \Bitrix\Main\Loader;
use \{Vendor}\{Module}\Main;
{include:options_use}

Loc::loadMessages(__FILE__);
$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = '{vendor}.{module}';
Loader::includeSharewareModule($module_id);

$right = $APPLICATION->GetGroupRight($module_id);
$right_w = $right >= 'W';

if($right < 'W') $APPLICATION->AuthForm(Loc::getMessage('ACCESS_DENIED'));

Loc::loadMessages($_SERVER['DOCUMENT_ROOT'].BX_ROOT.'/modules/main/options.php');

{include:options_before_tabs}

$aTabs = [
	[
		'DIV' => 'ewp_settings',
		'TAB' => Loc::getMessage('{VENDOR}_{MODULE}_OPTIONS_TAB_SETTINGS_TITLE'),
		'ICON' => 'main_settings',
		'TITLE' => Loc::getMessage('{VENDOR}_{MODULE}_OPTIONS_TAB_SETTINGS_TITLE_COMMON')
	],
	[
		'DIV' => 'ewp_rights',
		'TAB' => Loc::getMessage('{VENDOR}_{MODULE}_OPTIONS_TAB_RIGHTS_TITLE'),
		'ICON' => 'main_settings',
		'TITLE' => Loc::getMessage('{VENDOR}_{MODULE}_OPTIONS_TAB_RIGHTS_TITLE_COMMON')
	],
];

$arSettingsOptions = [
	[
		'ID' => 'phone',
		'NAME' => Loc::getMessage('{VENDOR}_{MODULE}_OPTIONS_OPTION_PHONE'),
		'TYPE' => 'phone',
	],
	[
		'ID' => 'show_licence',
		'NAME' => Loc::getMessage('{VENDOR}_{MODULE}_OPTIONS_OPTION_SHOW_LICENCE'),
		'TYPE' => 'checkbox',
	],
	[
		'ID' => 'licence_checked',
		'NAME' => Loc::getMessage('{VENDOR}_{MODULE}_OPTIONS_OPTION_LICENCE_CKECKED'),
		'TYPE' => 'checkbox',
	],
{include:options_setting_options}
];

{include:options_after_tabs}

$tabControl = new CAdminTabControl('tabControl', $aTabs);

if ($REQUEST_METHOD == 'POST' && strlen($Update.$Apply.$RestoreDefaults) > 0 && $right_w && check_bitrix_sessid())
{
	if(strlen($RestoreDefaults) > 0)
	{
		COption::RemoveOption($module_id);
	}
	else
	{
		foreach ($arSettingsOptions as $arOption)
		{
			if ($arOption['TYPE'] == 'phone' || $arOption['TYPE'] == 'email')
			{
				$val = $_REQUEST[$arOption['ID']];
			}
			else
			{
				$val = trim($_REQUEST[$arOption['ID']], " \t\n\r");
			}

			if ($arOption['TYPE'] == 'checkbox' && $val != 'Y')
			{
				$val = 'N';
			}

			if ($arOption['TYPE'] == 'phone' || $arOption['TYPE'] == 'email')
			{
				if ($arOption['MULTIPLE'] == 'Y')
				{
					foreach ($val as $key => $arValue)
					{
						if(!$arValue['VALUE'])
						{
							unset($val[$key]);
						}
					}
				}
				$val = serialize($val);
			}

			COption::SetOptionString($module_id, $arOption['ID'], $val, $arOption['NAME']);
		}
	}

	ob_start();
	$Update = $Update.$Apply;
	require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/admin/group_rights.php');
	ob_end_clean();

	if(strlen($_REQUEST['back_url_settings']) > 0)
	{
		if((strlen($Apply) > 0) || (strlen($RestoreDefaults) > 0))
			LocalRedirect($APPLICATION->GetCurPage().'?mid='.urlencode($module_id).'&lang='.urlencode(LANGUAGE_ID).'&back_url_settings='.urlencode($_REQUEST['back_url_settings']).'&'.$tabControl->ActiveTabParam());
		else
			LocalRedirect($_REQUEST['back_url_settings']);
	}
	else
	{
		LocalRedirect($APPLICATION->GetCurPage().'?mid='.urlencode($module_id).'&lang='.urlencode(LANGUAGE_ID).'&'.$tabControl->ActiveTabParam());
	}
}

?>
<form method="post" action="<?=$APPLICATION->GetCurPage()?>?mid=<?=urlencode($module_id)?>&amp;lang=<?=LANGUAGE_ID?>">
	<?
	$tabControl->Begin();
	$tabControl->BeginNextTab();

	foreach($arSettingsOptions as $arOption):
		$val = Main::GetOption($arOption['ID']);
	?>
	<tr>
		<td width="40%" nowrap <?=($arOption['TYPE'] == 'textarea') ? 'class="adm-detail-valign-top"' : ''?>>
			<label for="<?=htmlspecialcharsbx($arOption['ID'])?>"><?=$arOption['NAME']?>:</label>
		<td width="60%">
			<?
				switch ($arOption['TYPE']) {
					case 'checkbox':
						?><input type="checkbox" name="<?=htmlspecialcharsbx($arOption['ID'])?>" id="<?=htmlspecialcharsbx($arOption['ID'])?>" value="Y"<?=($val == 'Y') ? ' checked' : ''?>><?
						break;

					case 'text':
						?><input type="text" maxlength="255" name="<?=htmlspecialcharsbx($arOption['ID'])?>" id="<?=htmlspecialcharsbx($arOption['ID'])?>" value="<?=htmlspecialcharsbx($val)?>"><?
						break;

					case 'textarea':
						?><textarea rows="<?=$arOption['ROWS']?>" cols="<?=$arOption['COLS']?>" name="<?=htmlspecialcharsbx($arOption["ID"])?>" id="<?=htmlspecialcharsbx($arOption['ID'])?>"><?=htmlspecialcharsbx($val)?></textarea><?
						break;

					case 'selectbox':
						?><select name="<?=htmlspecialcharsbx($arOption['ID'])?>">
						<?foreach ($arOption['VALUES'] as $key => $value):?>
							<option value="<?=$key?>"<?=$val == $key ? ' selected' : ''?><?=in_array($key, $arOption['DISABLED_VALUES']) ? ' disabled' : ''?>><?=htmlspecialcharsbx($value)?></option>
						<?endforeach?>
						</select><?
						break;

					case 'phone':
						if (!is_array($val)) $val = [];
						if ($arOption['MULTIPLE'] == 'Y')
						{
							for ($i=0; $i < count($val) + 2; $i++)
							{
							?>
								<div class="js-phone-option">
									<input class="phone-option-value js-phone-option-value" type="text" maxlength="255" name="<?=htmlspecialcharsbx($arOption['ID'])?>[<?=$i?>][VALUE]" id="<?=htmlspecialcharsbx($arOption['ID'])?>_<?=$i?>" value="<?=htmlspecialcharsbx($val[$i]['VALUE'])?>" placeholder="+7 (999) 999-99-99">
									<input class="phone-option-tel js-phone-option-tel" type="text" maxlength="255" name="<?=htmlspecialcharsbx($arOption['ID'])?>[<?=$i?>][TEL]" id="<?=htmlspecialcharsbx($arOption['ID'])?>_<?=$i?>_tel" value="<?=htmlspecialcharsbx($val[$i]['TEL'])?>" placeholder="tel" readonly>
									<input class="phone-option-name" type="text" maxlength="255" name="<?=htmlspecialcharsbx($arOption['ID'])?>[<?=$i?>][NAME]" id="<?=htmlspecialcharsbx($arOption['ID'])?>_<?=$i?>_name" value="<?=htmlspecialcharsbx($val[$i]['NAME'])?>" placeholder="Название телефона">
								</div>
							<?
							}
						}
						else
						{
							?>
								<div class="js-phone-option">
									<input class="phone-option-value js-phone-option-value" type="text" maxlength="255" name="<?=htmlspecialcharsbx($arOption['ID'])?>[VALUE]" id="<?=htmlspecialcharsbx($arOption['ID'])?>" value="<?=htmlspecialcharsbx($val['VALUE'])?>" placeholder="+7 (999) 999-99-99">
									<input class="phone-option-tel js-phone-option-tel" type="text" maxlength="255" name="<?=htmlspecialcharsbx($arOption['ID'])?>[TEL]" id="<?=htmlspecialcharsbx($arOption['ID'])?>_tel" value="<?=htmlspecialcharsbx($val['TEL'])?>" placeholder="tel" readonly>
									<input class="phone-option-name" type="text" maxlength="255" name="<?=htmlspecialcharsbx($arOption['ID'])?>[NAME]" id="<?=htmlspecialcharsbx($arOption['ID'])?>_name" value="<?=htmlspecialcharsbx($val['NAME'])?>" placeholder="Название телефона">
								</div>
							<?
						}
						break;

					case 'email':
						if (!is_array($val)) $val = [];
						if ($arOption['MULTIPLE'] == 'Y') {
							for ($i=0; $i < count($val) + 2; $i++) {
							?>
								<div>
									<input class="phone-option-value" type="text" maxlength="255" name="<?=htmlspecialcharsbx($arOption['ID'])?>[<?=$i?>][VALUE]" id="<?=htmlspecialcharsbx($arOption['ID'])?>_<?=$i?>" value="<?=htmlspecialcharsbx($val[$i]['VALUE'])?>" placeholder="admin@site.ru">
									<input class="phone-option-name" type="text" maxlength="255" name="<?=htmlspecialcharsbx($arOption['ID'])?>[<?=$i?>][NAME]" id="<?=htmlspecialcharsbx($arOption['ID'])?>_<?=$i?>_name" value="<?=htmlspecialcharsbx($val[$i]['NAME'])?>" placeholder="Название почты">
								</div>
							<?
							}
						} else {
							?>
								<div>
									<input class="phone-option-value" type="text" maxlength="255" name="<?=htmlspecialcharsbx($arOption['ID'])?>[VALUE]" id="<?=htmlspecialcharsbx($arOption['ID'])?>" value="<?=htmlspecialcharsbx($val['VALUE'])?>" placeholder="admin@site.ru">
									<input class="phone-option-name" type="text" maxlength="255" name="<?=htmlspecialcharsbx($arOption['ID'])?>[NAME]" id="<?=htmlspecialcharsbx($arOption['ID'])?>_name" value="<?=htmlspecialcharsbx($val['NAME'])?>" placeholder="Название почты">
								</div>
							<?
						}
						break;
					
					default:
						break;
				}
			?>
		</td>
	</tr>
	<?endforeach?>
<?$tabControl->BeginNextTab()?>
	<?require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/admin/group_rights.php');?>
<?$tabControl->Buttons();?>
	<input type="submit" name="Update" value="<?=Loc::getMessage('MAIN_SAVE')?>" title="<?=Loc::getMessage('MAIN_OPT_SAVE_TITLE')?>" class="adm-btn-save">
	<input type="submit" name="Apply" value="<?=Loc::getMessage('MAIN_OPT_APPLY')?>" title="<?=Loc::getMessage('MAIN_OPT_APPLY_TITLE')?>">
	<?if(strlen($_REQUEST['back_url_settings'])>0):?>
		<input type="button" name="Cancel" value="<?=Loc::getMessage('MAIN_OPT_CANCEL')?>" title="<?=Loc::getMessage('MAIN_OPT_CANCEL_TITLE')?>" onclick="window.location='<?=htmlspecialcharsbx(CUtil::addslashes($_REQUEST['back_url_settings']))?>'">
		<input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($_REQUEST['back_url_settings'])?>">
	<?endif?>
	<input type="submit" name="RestoreDefaults" title="<?=Loc::getMessage('MAIN_HINT_RESTORE_DEFAULTS')?>" onclick="return confirm('<?=AddSlashes(Loc::getMessage('MAIN_HINT_RESTORE_DEFAULTS_WARNING'))?>')" value="<?=Loc::getMessage('MAIN_RESTORE_DEFAULTS')?>">
	<?=bitrix_sessid_post()?>
<?$tabControl->End()?>
</form>

<script>
	let phoneOptions = document.querySelectorAll('.js-phone-option');
	for (let i = 0; i < phoneOptions.length; i++) {
		let phoneOption = phoneOptions[i];
		let phoneOptionValue = phoneOption.querySelector('.js-phone-option-value');
		let phoneOptionTel = phoneOption.querySelector('.js-phone-option-tel');
		if (!phoneOptionValue && !phoneOptionTel) continue;

		phoneOptionValue.addEventListener('input', function(){
			phoneOptionTel.value = this.value.replace(/[^\d+]/g, '');
		})
	}
</script>