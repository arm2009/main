<?php
	class StringWork
	{
		//Возращаяет приставку к количеству рабочих мест
		public static function Rms($iRm)
		{
			switch($iRm)
			{
				case 0:
					return $iRm. " рабочих мест";
				break;
				case 1:
					return $iRm. " рабочем месте";
				break;
				default:
					return $iRm. " рабочих местах";
				break;
			}

		}

		//Возращаяет приставку к количеству дней/дня/день
		public static function Days($iDays)
		{
			$y = $iDays % 10;
			$x = $iDays / 10 % 10;
			if ($x && $x == 1) return $iDays. "&nbsp;дней";
			if ($y == 1) return $iDays. "&nbsp;день";
			$array = array("2","3","4");
			if (in_array($y,$array)) return $iDays. "&nbsp;дня";
			return $iDays. "&nbsp;дней";
		}

		public static function DateFormatLite($dDate)
		{
			return $dDate->format('d.m.Y');
		}

		public static function StrToDateFormatLite($dDate)
		{
			$dDate = new DateTime((string)$dDate);
			return $dDate->format('d.m.Y');
		}

		public static function StrToDateMysqlFormatLite($dDate)
		{
			$dDate = new DateTime((string)$dDate);
			return $dDate->format('Y-m-d');
		}

		public static function DateFormatFull($dDate)
		{
			$dDate = $dDate->format("d M Y");
			$dDate = str_replace ("Jan", "Января",$dDate);
			$dDate = str_replace ("Feb", "Февраля",$dDate);
			$dDate = str_replace ("Mar", "Марта",$dDate);
			$dDate = str_replace ("Apr", "Апреля",$dDate);
			$dDate = str_replace ("May", "Мая",$dDate);
			$dDate = str_replace ("Jun", "Июня",$dDate);
			$dDate = str_replace ("Jul", "Июля",$dDate);
			$dDate = str_replace ("Aug", "Августа",$dDate);
			$dDate = str_replace ("Sep", "Сентября",$dDate);
			$dDate = str_replace ("Oct", "Октября",$dDate);
			$dDate = str_replace ("Nov", "Ноября",$dDate);
			$dDate = str_replace ("Dec", "Декабря",$dDate);
			return $dDate .' г.';
		}

		public static function StrToDateFormatFull($dDate)
		{
			$dDate = new DateTime((string)$dDate);
			$dDate = $dDate->format("d M Y");
			$dDate = str_replace ("Jan", "Января",$dDate);
			$dDate = str_replace ("Feb", "Февраля",$dDate);
			$dDate = str_replace ("Mar", "Марта",$dDate);
			$dDate = str_replace ("Apr", "Апреля",$dDate);
			$dDate = str_replace ("May", "Мая",$dDate);
			$dDate = str_replace ("Jun", "Июня",$dDate);
			$dDate = str_replace ("Jul", "Июля",$dDate);
			$dDate = str_replace ("Aug", "Августа",$dDate);
			$dDate = str_replace ("Sep", "Сентября",$dDate);
			$dDate = str_replace ("Oct", "Октября",$dDate);
			$dDate = str_replace ("Nov", "Ноября",$dDate);
			$dDate = str_replace ("Dec", "Декабря",$dDate);
			return $dDate .' г.';
		}

		//Замена пустых значений
		public static function CheckNullStrLite($sDate='')
		{
			if(strlen(trim($sDate)) > 0)
			return $sDate;
			else
			return '—';
		}

		public static function CheckNullStrFull($sDate='')
		{
			if(strlen(trim($sDate)) > 0)
			return $sDate;
			else
			return 'Отсутствует.';
		}

		public static function iToClassNameLite($dAsset)
		{
			switch($dAsset)
			{
				case 0:
					$dAsset = StringWork::CheckNullStrLite('');
				break;
				case 1:
					$dAsset = '1.0';
				break;
				case 2:
					$dAsset = '2.0';
				break;
				case 3:
					$dAsset = '3.1';
				break;
				case 4:
					$dAsset = '3.2';
				break;
				case 5:
					$dAsset = '3.3';
				break;
				case 6:
					$dAsset = '3.4';
				break;
				case 7:
					$dAsset = '4.0';
				break;
			}
			return $dAsset;
		}

		public static function iToCompString($iFact)
		{
			if($iFact == 0)
			return 'Нет.';
			else
			return 'Да.';
		}
		public static function NullCatchZero($sFact)
		{
			if($sFact == null)
			return '0';
			else
			return $sFact;
		}
		public static function FullNameToInitials($sFullName)
		{
			if(strlen(trim($sFullName)) > 0 && substr_count(trim($sFullName),' ') == 2)
			{
				$Name = explode(' ',trim($sFullName));
				$sFullName = $Name[0].' '.utf8char($Name[1],0).'.'.utf8char($Name[2],0).'.';
			}
			return $sFullName;
		}
	}



//Возвращает сумму прописью
function num2str($num) {
    $nul='ноль';
    $ten=array(
        array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
        array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
    );
    $a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
    $tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
    $hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
    $unit=array( // Units
        array('копейка' ,'копейки' ,'копеек',	 1),
        array('рубль'   ,'рубля'   ,'рублей'    ,0),
        array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
        array('миллион' ,'миллиона','миллионов' ,0),
        array('миллиард','милиарда','миллиардов',0),
    );
    //
    list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
    $out = array();
    if (intval($rub)>0) {
        foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
            if (!intval($v)) continue;
            $uk = sizeof($unit)-$uk-1; // unit key
            $gender = $unit[$uk][3];
            list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
            // mega-logic
            $out[] = $hundred[$i1]; # 1xx-9xx
            if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
            else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
            // units without rub & kop
            if ($uk>1) $out[]= morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
        } //foreach
    }
    else $out[] = $nul;
    $out[] = morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
    $out[] = $kop.' '.morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
    return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
}

//Склоняем словоформу
function morph($n, $f1, $f2, $f5) {
    $n = abs(intval($n)) % 100;
    if ($n>10 && $n<20) return $f5;
    $n = $n % 10;
    if ($n>1 && $n<5) return $f2;
    if ($n==1) return $f1;
    return $f5;
}

//Конвертация кода
function ConvCod($value)
{
	//$value= iconv('utf-8','cp1251//IGNORE',$value);
	$vowels = array("a", "e", "i", "o", "u", "A", "E", "I", "O", "U");
	$value = str_replace('\"', '"', $value);
	$value = str_replace('\\\\', '', $value);
	$value = htmlspecialchars($value);
	return $value; // aica?auaaony cia?aiea ia?aiaiiie $ret
}

function utf8char($str, $pos) {
    return mb_substr($str,$pos,1,'UTF-8');
}

function makeToFloat ($inValue)
{
	$inValue = str_replace(',','.',$inValue);
	return($inValue);
}
?>
