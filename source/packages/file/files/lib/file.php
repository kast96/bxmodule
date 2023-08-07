<?
namespace {Vendor}\{Module};

use \Bitrix\Main\Config\Option;
use \Bitrix\Main\FileTable;
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class File
{
	/** 
	* Возвращает путь к файлу по его id
	* @param string|int $id - Id файла
	* @return string
	*/
	public static function getFilePath($id)
	{
		if (!$id || !intval($id)) return false;

		$arFile = FileTable::getList([
			'filter' => ['ID' => $id],
			'select' => ['SUBDIR', 'FILE_NAME']
		])->fetch();

		$uploadDir = Option::get('main', 'upload_dir', 'upload');

		return $arFile ? '/'.$uploadDir.'/'.$arFile['SUBDIR'].'/'.$arFile['FILE_NAME'] : false;
	}

	/** 
	* Подключает файл. Обертка над функцией include. Вторым параметром передается массив параметров $arParams для обработки в подключаемом файле
	* @param string $path - Путь к файлу
	* @param array $arParams - Массив параметров для обработки в подключаемом файле
	*/
	public static function include(string $path, array $arParams = [])
	{
		if (!is_array($arParams)) $arParams = [];
		include($path);
	}

	/** 
	* Возвращает информацию о файле по его id
	* @param string|int $id - Id файла
	* @return array
	*/
	public static function getFileInfo(int $id)
	{
		$arFile = FileTable::getList([
			'filter' => ['ID' => $id],
		])->fetch();

		return $arFile;
	}

	/** 
	* Возвращает читаемый размер файла
	* @param float $fileSize - Размер файла в байтах
	* @param int $step - Шаг итерации рассчета
	* @return string
	*/
	public static function getFileSizeText(float $fileSize, int $step = 0)
	{
		$arMeasures = [
			Loc::getMessage('{VENDOR}_{MODULE}_FILE_MEASURE_B'),
			Loc::getMessage('{VENDOR}_{MODULE}_FILE_MEASURE_KB'),
			Loc::getMessage('{VENDOR}_{MODULE}_FILE_MEASURE_MB'),
			Loc::getMessage('{VENDOR}_{MODULE}_FILE_MEASURE_GB'),
		];

		$upperMeasureValue = $fileSize / 1000;
		if ($arMeasures[$step + 1] && $upperMeasureValue >= 1)
		{
			return self::getFileSizeText($upperMeasureValue, $step + 1);
		}
		else
		{
			return round($fileSize, 1) . ' ' . $arMeasures[$step];
		}
	}
}