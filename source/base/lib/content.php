<?
namespace {Vendor}\{Module};

use \Bitrix\Main\Data\Cache;
use \{Vendor}\{Module}\Main;

class Content
{
	/** 
	* Изменяет html-код полей, который формируется стандартным компонентом модуля форм. Добавляет классы для шаблона и аттрибуты
	* @param string $FIELD_SID - Id поля
	* @param array $arQuestion - Массив параметров поля
	* @param array|null $arErrors - Массив ошибок
	* @param array $arParams - Массив параметров
	* @return string
	*/
	public static function getFormField(string $FIELD_SID, array $arQuestion, ?array $arErrors, array $arParams = []): string
	{
		$arQuestion["HTML_CODE"] = str_replace('inputtext', '', $arQuestion["HTML_CODE"]);
		$arQuestion["HTML_CODE"] = str_replace('left', '', $arQuestion["HTML_CODE"]);
		$arQuestion["HTML_CODE"] = str_replace('size="0"', '', $arQuestion["HTML_CODE"]);

		if ($arQuestion['INPUT_ID'])
		{
			$arQuestion["HTML_CODE"] = str_replace('<input', '<input id="'.$arQuestion['INPUT_ID'].'"', $arQuestion["HTML_CODE"]);
			$arQuestion["HTML_CODE"] = str_replace('<textarea', '<textarea id="'.$arQuestion['INPUT_ID'].'"', $arQuestion["HTML_CODE"]);
		}

		$str = '';

		if ($arParams['VALUES'][$FIELD_SID])
		{
			$arQuestion["HTML_CODE"] = str_replace('/value((=")([^\"]+)?("))?/', '', $arQuestion["HTML_CODE"]);
			$arQuestion["HTML_CODE"] = str_replace('input', 'input value="'.$arParams['VALUES'][$FIELD_SID].'"', $arQuestion["HTML_CODE"]);
		}

		if (is_array($arParams['READONLY']) && in_array($FIELD_SID, $arParams['READONLY'])) {
			$arQuestion["HTML_CODE"] = str_replace('input', 'input readonly', $arQuestion["HTML_CODE"]);
		}

		if($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden')
		{
			$str .= $arQuestion["HTML_CODE"];
		}
		else
		{
			$required = ($arQuestion['REQUIRED'] == 'Y') ? '*' : '';

			if(is_array($arErrors) && array_key_exists($FIELD_SID, $arErrors))
			{
				$str .= '<span class="alert alert-danger" title="'.$arErrors[$FIELD_SID].'"></span>';
			}

			$str .= '<label class="form-error" for="'.$arQuestion["INPUT_ID"].'"></label>';

			if($arQuestion["STRUCTURE"][0]["FIELD_TYPE"] == "dropdown")
			{
				$arQuestion["HTML_CODE"] = preg_replace('/(<select[^>]+>)(.*)/', '$1<option selected disabled>*'.$arQuestion['CAPTION'].'</option>$2', $arQuestion["HTML_CODE"]);
				$arQuestion["HTML_CODE"] = str_replace('class="', 'class="js-select ', $arQuestion["HTML_CODE"]);
				$arQuestion["HTML_CODE"] = str_replace(' inputselect', '', $arQuestion["HTML_CODE"]);
				$arQuestion["HTML_CODE"] = '<div class="select-modified">'.$arQuestion["HTML_CODE"].'</div>';
				
				foreach ($arQuestion['STRUCTURE'] as $arStructure)
				{
					if ($arStructure['FIELD_PARAM'])
					{
						$arQuestion["HTML_CODE"] = str_replace('<option value="'.$arStructure['ID'].'"', '<option value="'.$arStructure['ID'].'" '.$arStructure['FIELD_PARAM'], $arQuestion["HTML_CODE"]);
					}
				}
			}
			else
			{
				if(strpos($arQuestion["HTML_CODE"], "class=") === false)
				{
					$arQuestion["HTML_CODE"] = str_replace('input', 'input class=""', $arQuestion["HTML_CODE"]);
				}

				$arQuestion["HTML_CODE"] = str_replace('class="', 'class="input ', $arQuestion["HTML_CODE"]);

				$arQuestion["HTML_CODE"] = str_replace('name=', 'placeholder="'.$required.$arQuestion['CAPTION'].'" name=', $arQuestion["HTML_CODE"]);

				if($arQuestion["REQUIRED"] == "Y")
				{
					$arQuestion["HTML_CODE"] = str_replace('name=', 'required name=', $arQuestion["HTML_CODE"]);
				}

				if($arQuestion["STRUCTURE"][0]["FIELD_TYPE"] == "email")
				{
					$arQuestion["HTML_CODE"] = str_replace('type="text"', 'type="text" inputmode="email"', $arQuestion["HTML_CODE"]);
					$arQuestion["HTML_CODE"] = str_replace('class="', 'class="js-mask-email ', $arQuestion["HTML_CODE"]);
				}

				if((strpos($arQuestion["HTML_CODE"], "phone") !== false) || (strpos(strToLower($FIELD_SID), "phone") !== false))
				{
					$arQuestion["HTML_CODE"] = str_replace('type="text"', 'type="tel"', $arQuestion["HTML_CODE"]);
					$arQuestion["HTML_CODE"] = str_replace('class="', 'class="js-mask-phone ', $arQuestion["HTML_CODE"]);
				}
			}

			if($arQuestion["STRUCTURE"][0]["FIELD_TYPE"] == "textarea")
			{
				$arQuestion["HTML_CODE"] = str_replace('class="', 'class="input_textarea js-textarea-size ', $arQuestion["HTML_CODE"]);
			}

			$str .= $arQuestion["HTML_CODE"];
		}
		return $str;
	}

	/** 
	* Возвращает html блока согласия на обработку персональных данных
	* @param bool $isCheckbox - Использовать чек-бокс ля подтверждения персональных данных
	* @param bool|null $showLicense - Показывать ли лицензию. Если null - то значение возьмется из настроек модуля
	* @return string
	*/
	public static function getLicenseLabel(bool $isCheckbox = true, ?bool $showLicense = null): ?string
	{
		$str = '';

		if (is_null($showLicense)) $showLicense = Main::getOption('show_licence') == 'Y';
		if($showLicense)
		{
			if ($isCheckbox)
			{
				$str .= '<div class="switch-field">';
					$str .= '<label class="switch">';
						$str .= '<input class="switch-checkbox js-form-checkbox" type="checkbox" name="agreement" '.(Main::getOption('licence_checked') == 'Y' ? 'checked' : '').' required value="Y" />';
						$str .= '<span class="switch-decor">';
							$str .= '<span class="switch-icon"></span>';
						$str .= '</span>';
						$str .= '<span class="switch-label">Я согласен на <a href="/policy/" target="_blank">обработку персональных данных</a></span>';
					$str .= '</label>';
				$str .= '</div>';
			}
			else
			{
				$str .= '<div class="popup-add-info">Нажимая кнопку «Получить код», вы подтверждаете своё согласие на <a href="/policy/" target="_blank">обработку персональных данных</a></div>';
			}
		}

		return $str;
	}

	/** 
	* Возвращает html-код каптчи
	* @param string $catchaCode - Код каптчи
	* @return string
	*/
	public static function getCaptcha(string $catchaCode)
	{
		$html = '';

		$html .= '<div class="captcha">';
			$html .= '<input type="hidden" name="captcha_sid" value="'.$catchaCode.'" />';
			$html .= '<div class="field">';
				$html .= '<span class="field-input JS-FieldText" data-fieldtext="{\'classActive\':\'field-input_active\'}">';
					$html .= '<label class="field-label field-label_simple JS-FieldText-Label" for="captcha_word">Введите слово на картинке*</label>';
					$html .= '<input class="input input_simple JS-FieldText-Input" type="text" id="captcha_word" name="captcha_word" maxlength="50" value="" autocomplete="off" />';
					$html .= '<img class="captcha-img" src="/bitrix/tools/captcha.php?captcha_sid='.$catchaCode.'" width="180" height="40" alt="CAPTCHA" />';
				$html .= '</span>';
			$html .= '</div>';
		$html .= '</div>';

		return $html;
	}

	/** 
	* Формирует html строку с информацией о товаре для полей форм (купить в 1 клик)
	* Array('ID' => '1', 'NAME' => 'Товар', 'DETAIL_PAGE_URL' => '/tovar/', 'QUANTITY' => 5) --> '[1] Товар [<a href="/tovar/">Ссылка</a>] (5 шт.)'
	* @param array $arFields - Массив с полями о товаре
	* @return string
	*/
	public static function getProductStringForInputValue(array $arFields)
	{
		$html = '';
		if ($arFields['ID']) {
			$html .= '[' . $arFields['ID'] . ']';
		}
		if ($arFields['NAME']) {
			$html .= ' ' . $arFields['NAME'];
		}
		if ($arFields['DETAIL_PAGE_URL']) {
			$html .= ' [<a href="' . $arFields['DETAIL_PAGE_URL'] . '">Ссылка</a>]';
		}
		if ($arFields['QUANTITY']) {
			$html .= ' (' . $arFields['QUANTITY'] . ' шт.)';
		}

		return $html;
	}

	/** 
	* Формирует html строку с информацией о товарах для полей форм (купить в 1 клик) из массива товаров
	* Тоже самое что и GetProductStringForInputValue, только для массива товаров
	* @param array $arFields - Массив массивов с полями о товаре
	* @return string
	*/
	public static function getProductStringForInputValueArray(array $arItems)
	{
		foreach($arItems as $arItem) {
			$arHtml[] = self::getProductStringForInputValue($arItem);
		}

		return implode('<br>', $arHtml);
	}

	/** 
	* Возвращает html строку со стрелочками слайдеров
	* @param string $class - Дополнительный класс для стрелочек
	* @return string
	*/
	public static function getSliderNavButtons(string $class = '')
	{
		$html = '
			<div class="'.$class.' slider-button slider-button_prev js-slider-prev">
				<svg class="slider-button-icon" width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M6.91504 1.23002L1.75337 6.39169C1.14379 7.00127 1.14379 7.99877 1.75337 8.60835L6.91504 13.77" stroke="#33AFAC" stroke-width="2.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</div>
			<div class="'.$class.' slider-button slider-button_next js-slider-next">
				<svg class="slider-button-icon" width="9" height="15" viewBox="0 0 9 15" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M2.1157 13.7817L7.25219 8.59495C7.8588 7.98241 7.85395 6.98492 7.24141 6.37831L2.05469 1.24182" stroke="#33AFAC" stroke-width="2.2" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</div>
		';

		return $html;
	}

	/** 
	* Возвращает картинку-заглушку
	* @param int $width - Ширина
	* @param int $height - Высота
	* @return string
	*/
	public static function getImagePlaceholder(int $width, int $height)
	{
		$cache = Cache::createInstance();
		if ($cache->initCache(36000000, $width.'x'.$height, __CLASS__.'/'.__FUNCTION__.'/'.$width.'x'.$height)) {
			$arResult = $cache->getVars();
		} elseif ($cache->startDataCache()) {
			$image = imagecreate($width, $height);
			imagecolorallocate($image, 240, 240, 240);

			ob_start(); 
			imagejpeg($image);
			$image_data = ob_get_contents(); 
			ob_end_clean();

			$arResult = 'data:image/jpeg;base64,'.base64_encode($image_data);

			$cache->endDataCache($arResult);
		}
		return $arResult;
	}
}