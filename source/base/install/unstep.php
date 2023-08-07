<?use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

if (!check_bitrix_sessid())
{
	return;
}

echo(CAdminMessage::ShowNote(Loc::getMessage('{VENDOR}_{MODULE}_UNSTEP_TITLE')));
?>

<form action="<?=$APPLICATION->GetCurPage()?>">
	<input type="hidden" name="lang" value="<?=LANG?>" />
	<input type="submit" value="<?=Loc::getMessage('{VENDOR}_{MODULE}_UNSTEP_SUBMIT_BACK')?>">
</form>