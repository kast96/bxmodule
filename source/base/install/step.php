<?use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

if (!check_bitrix_sessid())
{
	return;
}

if ($errorException = $APPLICATION->GetException())
{
	echo(CAdminMessage::ShowMessage($errorException->GetString()));
}
else
{
	echo(CAdminMessage::ShowNote(Loc::getMessage('{VENDOR}_{MODULE}_STEP_TITLE')));
}
?>

<form action="<?=$APPLICATION->GetCurPage()?>">
	<input type="hidden" name="lang" value="<?=LANG?>" />
	<input type="submit" value="<?=Loc::getMessage('{VENDOR}_{MODULE}_STEP_SUBMIT_BACK')?>">
</form>