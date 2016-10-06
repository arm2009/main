-- phpMyAdmin SQL Dump
-- version 
-- http://www.phpmyadmin.net
--
-- Хост: kctrud.mysql
-- Время создания: Мар 23 2015 г., 19:21
-- Версия сервера: 5.1.73
-- Версия PHP: 5.2.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `kctrud_arm2009`
--

-- --------------------------------------------------------

--
-- Структура таблицы `Arm_acredit`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Мар 23 2015 г., 09:14
--

DROP TABLE IF EXISTS `Arm_acredit`;
CREATE TABLE IF NOT EXISTS `Arm_acredit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sName` varchar(150) CHARACTER SET utf8 NOT NULL,
  `dDateCreate` date NOT NULL,
  `dDateFinish` date NOT NULL,
  `idParent` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Arm_activity`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Мар 23 2015 г., 10:15
--

DROP TABLE IF EXISTS `Arm_activity`;
CREATE TABLE IF NOT EXISTS `Arm_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iRmId` int(11) NOT NULL,
  `sActivityName` text NOT NULL,
  `sActivityTarget` text NOT NULL,
  `sTerm` text NOT NULL,
  `sInvolved` text NOT NULL,
  `sMark` text NOT NULL,
  `iType` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3895 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Arm_comiss`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Мар 23 2015 г., 10:49
--

DROP TABLE IF EXISTS `Arm_comiss`;
CREATE TABLE IF NOT EXISTS `Arm_comiss` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idParent` int(11) NOT NULL,
  `sName` varchar(150) CHARACTER SET utf8 NOT NULL,
  `sPost` varchar(150) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=463 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Arm_devices`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Мар 23 2015 г., 09:42
--

DROP TABLE IF EXISTS `Arm_devices`;
CREATE TABLE IF NOT EXISTS `Arm_devices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sName` varchar(150) CHARACTER SET utf8 NOT NULL,
  `sReestrNum` varchar(100) CHARACTER SET utf8 NOT NULL,
  `dCheckDate` date NOT NULL,
  `idParent` int(11) NOT NULL,
  `sCheckNum` varchar(150) CHARACTER SET utf8 NOT NULL,
  `sFactoryNum` varchar(150) CHARACTER SET utf8 NOT NULL,
  `sFactName` text CHARACTER SET utf8 NOT NULL,
  `sMethodName` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=158 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Arm_group`
--
-- Создание: Янв 20 2015 г., 14:08
--

DROP TABLE IF EXISTS `Arm_group`;
CREATE TABLE IF NOT EXISTS `Arm_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idParent` int(11) NOT NULL,
  `bTemp` tinyint(1) NOT NULL,
  `sName` varchar(255) NOT NULL,
  `sFullName` varchar(255) NOT NULL,
  `sPlace` varchar(150) NOT NULL,
  `sEmail` varchar(100) NOT NULL,
  `sPostDirector` varchar(150) NOT NULL,
  `sNameDirector` varchar(150) NOT NULL,
  `sInn` varchar(50) NOT NULL,
  `sOgrn` varchar(50) NOT NULL,
  `sOkved` varchar(50) NOT NULL,
  `sOkpo` varchar(50) NOT NULL,
  `sOkogu` varchar(50) NOT NULL,
  `sOkato` varchar(50) NOT NULL,
  `sPredsName` varchar(150) NOT NULL,
  `sPredsPost` varchar(150) NOT NULL,
  `sStatus` varchar(50) NOT NULL,
  `sPhone` varchar(255) NOT NULL,
  `sPNumTenesy` varchar(255) NOT NULL,
  `sPNumHeavy` varchar(255) NOT NULL,
  `sPNumAir` varchar(255) NOT NULL,
  `sPNumLight` varchar(255) NOT NULL,
  `sPNumNoise` varchar(255) NOT NULL,
  `sPNumClimate` varchar(255) NOT NULL,
  `sExpEndDoc` text NOT NULL,
  `sExpEndDate` date NOT NULL,
  `iRmTotalCount` int(11) NOT NULL,
  `iWorkerTotal` int(11) NOT NULL DEFAULT '0',
  `iWorkerTotalWoman` int(11) NOT NULL DEFAULT '0',
  `iWorkerTotalYang` int(11) NOT NULL DEFAULT '0',
  `iWorkerTotalMedical` int(11) NOT NULL DEFAULT '0',
  `dStartDate` date NOT NULL,
  `dEndDate` date NOT NULL,
  `sDocName` text NOT NULL,
  `dLastChangeDate` date NOT NULL,
  `idLastChangeUser` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idParent` (`idParent`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=651 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Arm_groupAcredit`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Мар 23 2015 г., 10:09
--

DROP TABLE IF EXISTS `Arm_groupAcredit`;
CREATE TABLE IF NOT EXISTS `Arm_groupAcredit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sName` varchar(150) CHARACTER SET utf8 NOT NULL,
  `dDateCreate` date NOT NULL,
  `dDateFinish` date NOT NULL,
  `idGroup` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=234 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Arm_groupDevices`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Мар 23 2015 г., 05:44
--

DROP TABLE IF EXISTS `Arm_groupDevices`;
CREATE TABLE IF NOT EXISTS `Arm_groupDevices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sName` varchar(150) CHARACTER SET utf8 NOT NULL,
  `sReestrNum` varchar(100) CHARACTER SET utf8 NOT NULL,
  `dCheckDate` date NOT NULL,
  `idGroup` int(11) NOT NULL,
  `sCheckNum` varchar(150) CHARACTER SET utf8 NOT NULL,
  `sFactoryNum` varchar(150) CHARACTER SET utf8 NOT NULL,
  `sFactName` text CHARACTER SET utf8 NOT NULL,
  `sMethodName` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=980 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Arm_groupStuff`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Мар 23 2015 г., 10:02
--

DROP TABLE IF EXISTS `Arm_groupStuff`;
CREATE TABLE IF NOT EXISTS `Arm_groupStuff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sName` varchar(150) CHARACTER SET utf8 NOT NULL,
  `sSertNum` varchar(150) CHARACTER SET utf8 NOT NULL,
  `dSertDate` date NOT NULL,
  `idGroup` int(11) NOT NULL,
  `sPost` varchar(150) CHARACTER SET utf8 NOT NULL,
  `sReestrNum` varchar(150) CHARACTER SET utf8 NOT NULL,
  `bExpert` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=557 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Arm_log`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Мар 23 2015 г., 15:15
--

DROP TABLE IF EXISTS `Arm_log`;
CREATE TABLE IF NOT EXISTS `Arm_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sMessage` text CHARACTER SET utf8 NOT NULL,
  `sType` varchar(50) CHARACTER SET utf8 NOT NULL,
  `iIdUser` int(11) NOT NULL,
  `dTimeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=19559 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Arm_PayOut`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Мар 23 2015 г., 08:46
--

DROP TABLE IF EXISTS `Arm_PayOut`;
CREATE TABLE IF NOT EXISTS `Arm_PayOut` (
  `iNum` int(11) NOT NULL AUTO_INCREMENT,
  `dtStamp` datetime NOT NULL,
  `iState` int(11) NOT NULL,
  `sTarif` varchar(100) CHARACTER SET utf8 NOT NULL,
  `iMonth` int(11) NOT NULL,
  `sOrgName` text CHARACTER SET utf8 NOT NULL,
  `sAdress` text CHARACTER SET utf8 NOT NULL,
  `sInn` text CHARACTER SET utf8 NOT NULL,
  `sKpp` text CHARACTER SET utf8 NOT NULL,
  `sBank` text CHARACTER SET utf8 NOT NULL,
  `sBik` text CHARACTER SET utf8 NOT NULL,
  `iSum` int(11) NOT NULL,
  `iUserId` int(11) NOT NULL,
  PRIMARY KEY (`iNum`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=75 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Arm_rmFactors`
--
-- Создание: Дек 25 2014 г., 18:05
-- Последнее обновление: Мар 23 2015 г., 11:10
--

DROP TABLE IF EXISTS `Arm_rmFactors`;
CREATE TABLE IF NOT EXISTS `Arm_rmFactors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idPoint` int(11) NOT NULL,
  `sName` varchar(150) CHARACTER SET utf8 NOT NULL,
  `idFactorGroup` int(11) NOT NULL,
  `idFactor` int(11) NOT NULL,
  `var1` float NOT NULL DEFAULT '0',
  `var2` float NOT NULL,
  `var3` float NOT NULL,
  `var4` float NOT NULL,
  `var5` float NOT NULL,
  `dtControl` datetime NOT NULL,
  `idMed` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=12142 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Arm_rmFactorsPdu`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Мар 23 2015 г., 11:10
--

DROP TABLE IF EXISTS `Arm_rmFactorsPdu`;
CREATE TABLE IF NOT EXISTS `Arm_rmFactorsPdu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idRm` int(11) NOT NULL,
  `idFactor` int(11) NOT NULL,
  `fPdu1` float NOT NULL,
  `fPdu2` float NOT NULL,
  `fPdu3` float NOT NULL,
  `fPdu4` float NOT NULL,
  `fPdu5` float NOT NULL,
  `iAsset` int(50) NOT NULL,
  `sAddonAsset` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=23189 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Arm_rmPoints`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Мар 23 2015 г., 11:10
--

DROP TABLE IF EXISTS `Arm_rmPoints`;
CREATE TABLE IF NOT EXISTS `Arm_rmPoints` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sName` varchar(150) CHARACTER SET utf8 NOT NULL,
  `iType` int(11) NOT NULL DEFAULT '0',
  `sLightPolygone` varchar(50) CHARACTER SET utf8 NOT NULL,
  `sLightHeight` varchar(50) CHARACTER SET utf8 NOT NULL,
  `sLightDark` varchar(50) CHARACTER SET utf8 NOT NULL,
  `sLightType` varchar(50) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4252 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Arm_rmPointsRm`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Мар 23 2015 г., 11:09
--

DROP TABLE IF EXISTS `Arm_rmPointsRm`;
CREATE TABLE IF NOT EXISTS `Arm_rmPointsRm` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idPoint` int(11) NOT NULL,
  `idRm` int(11) NOT NULL,
  `sTime` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8642 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Arm_Siz`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Мар 23 2015 г., 10:16
--

DROP TABLE IF EXISTS `Arm_Siz`;
CREATE TABLE IF NOT EXISTS `Arm_Siz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rmId` int(11) NOT NULL,
  `SizName` text NOT NULL,
  `Fact` tinyint(1) NOT NULL,
  `Sert` text NOT NULL,
  `protectFactor` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9853 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Arm_soworkers`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Мар 23 2015 г., 09:06
--

DROP TABLE IF EXISTS `Arm_soworkers`;
CREATE TABLE IF NOT EXISTS `Arm_soworkers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idParent` int(11) NOT NULL,
  `idChild` int(11) NOT NULL,
  `sEmail` varchar(150) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=140 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Arm_stuff`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Мар 23 2015 г., 09:38
--

DROP TABLE IF EXISTS `Arm_stuff`;
CREATE TABLE IF NOT EXISTS `Arm_stuff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sName` varchar(150) CHARACTER SET utf8 NOT NULL,
  `sSertNum` varchar(150) CHARACTER SET utf8 NOT NULL,
  `dSertDate` date NOT NULL,
  `idParent` int(11) NOT NULL,
  `sPost` varchar(150) CHARACTER SET utf8 NOT NULL,
  `sReestrNum` varchar(150) CHARACTER SET utf8 NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=52 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Arm_users`
--
-- Создание: Дек 24 2014 г., 06:59
--

DROP TABLE IF EXISTS `Arm_users`;
CREATE TABLE IF NOT EXISTS `Arm_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sName` blob NOT NULL,
  `sPassword` varchar(150) NOT NULL,
  `sHash2` varchar(150) NOT NULL,
  `sName1` blob NOT NULL,
  `sName2` blob NOT NULL,
  `sPhone` blob NOT NULL,
  `sRestoreCode` varchar(100) NOT NULL,
  `dRestoreDate` date NOT NULL,
  `sTariffName` varchar(150) NOT NULL,
  `dTariffDate` date NOT NULL DEFAULT '2033-12-31',
  `dTariffDateStart` date NOT NULL,
  `iTariffSoWorkers` int(11) NOT NULL,
  `iTariffMoney` int(11) NOT NULL,
  `dCreateDate` date NOT NULL,
  `sOrgName` blob NOT NULL,
  `sOrgPlace` blob NOT NULL,
  `sOrgPhone` blob NOT NULL,
  `sOrgAdress` blob NOT NULL,
  `sOrgInn` blob NOT NULL,
  `sOrgOgrn` blob NOT NULL,
  `sOrgRegNum` blob NOT NULL,
  `sOrgDate` blob NOT NULL,
  `sFirstFacePost` blob NOT NULL,
  `sFirstFaceName` blob NOT NULL,
  `sSecondFaceName` blob NOT NULL,
  `sSecondFacePost` blob NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=124 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Arm_workplace`
--
-- Создание: Дек 24 2014 г., 06:59
--

DROP TABLE IF EXISTS `Arm_workplace`;
CREATE TABLE IF NOT EXISTS `Arm_workplace` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idParent` int(11) NOT NULL,
  `iLevel` int(11) NOT NULL,
  `iNumber` int(20) NOT NULL,
  `sName` varchar(150) CHARACTER SET utf8 NOT NULL,
  `sOk` varchar(50) CHARACTER SET utf8 NOT NULL,
  `sPrefix` varchar(50) CHARACTER SET utf8 NOT NULL,
  `idGroup` int(11) NOT NULL,
  `dDate` date NOT NULL,
  `sNumAnalog` text CHARACTER SET utf8 NOT NULL,
  `sETKS` text CHARACTER SET utf8 NOT NULL,
  `iCount` int(11) NOT NULL,
  `iCountWoman` int(11) NOT NULL,
  `iCountYouth` int(11) NOT NULL,
  `iCountDisabled` int(11) NOT NULL,
  `sSnils` text CHARACTER SET utf8 NOT NULL,
  `dCreateDate` date NOT NULL,
  `fWorkDay` float NOT NULL DEFAULT '8',
  `iAChem` int(11) NOT NULL DEFAULT '0',
  `iABio` int(11) NOT NULL DEFAULT '0',
  `iAAPFD` int(11) NOT NULL DEFAULT '0',
  `iANoise` int(11) NOT NULL DEFAULT '0',
  `iAInfraNoise` int(11) NOT NULL DEFAULT '0',
  `iAUltraNoise` int(11) NOT NULL DEFAULT '0',
  `iAVibroO` int(11) NOT NULL DEFAULT '0',
  `iAVibroL` int(11) NOT NULL DEFAULT '0',
  `iANoIon` int(11) NOT NULL DEFAULT '0',
  `iAIon` int(11) NOT NULL DEFAULT '0',
  `iAMicroclimat` int(11) NOT NULL DEFAULT '0',
  `iALight` int(11) NOT NULL DEFAULT '0',
  `iAHeavy` int(11) NOT NULL DEFAULT '0',
  `iAHeavyW` int(11) NOT NULL DEFAULT '0',
  `iAHeavyM` int(11) NOT NULL DEFAULT '0',
  `iATennese` int(11) NOT NULL DEFAULT '0',
  `iATotal` int(11) NOT NULL DEFAULT '0',
  `iCompSurcharge` int(11) NOT NULL DEFAULT '0',
  `sCompBaseSurcharge` text CHARACTER SET utf8 NOT NULL,
  `sCompFactSurcharge` int(11) NOT NULL DEFAULT '0',
  `iCompVacation` int(11) NOT NULL DEFAULT '0',
  `sCompBaseVacation` text CHARACTER SET utf8 NOT NULL,
  `sCompFactVacation` int(11) NOT NULL DEFAULT '0',
  `iCompShortWorkDay` int(11) NOT NULL DEFAULT '0',
  `sCompBaseShortWorkDay` text CHARACTER SET utf8 NOT NULL,
  `sCompFactShortWorkDay` int(11) NOT NULL DEFAULT '0',
  `iCompMilk` int(11) NOT NULL DEFAULT '0',
  `sCompBaseMilk` text CHARACTER SET utf8 NOT NULL,
  `sCompFactMilk` int(11) NOT NULL DEFAULT '0',
  `iCompFood` int(11) NOT NULL DEFAULT '0',
  `sCompBaseFood` text CHARACTER SET utf8 NOT NULL,
  `sCompFactFood` int(11) NOT NULL DEFAULT '0',
  `iCompPension` int(11) NOT NULL DEFAULT '0',
  `sCompBasePension` text CHARACTER SET utf8 NOT NULL,
  `sCompFactPension` int(11) NOT NULL DEFAULT '0',
  `iCompPhysical` int(11) NOT NULL DEFAULT '0',
  `sCompBasePhysical` text CHARACTER SET utf8 NOT NULL,
  `sCompFactPhysical` int(11) NOT NULL DEFAULT '0',
  `sSIZbase` text CHARACTER SET utf8 NOT NULL,
  `dSizDate` date NOT NULL,
  `iSIZCard` int(11) NOT NULL DEFAULT '1',
  `iSIZEffect` int(11) NOT NULL DEFAULT '1',
  `iSIZOFact` int(11) NOT NULL DEFAULT '1',
  `iSIZOProtect` int(11) NOT NULL DEFAULT '1',
  `iSIZOEffect` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4305 ;

-- --------------------------------------------------------

--
-- Структура таблицы `CreateDoc_Session`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Мар 23 2015 г., 15:17
--

DROP TABLE IF EXISTS `CreateDoc_Session`;
CREATE TABLE IF NOT EXISTS `CreateDoc_Session` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iUserId` int(11) NOT NULL,
  `dBegin` datetime NOT NULL,
  `sPath` varchar(255) CHARACTER SET utf8 NOT NULL,
  `iDocCount` int(11) NOT NULL,
  `iState` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5651 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Nd_162`
--
-- Создание: Дек 24 2014 г., 06:59
--

DROP TABLE IF EXISTS `Nd_162`;
CREATE TABLE IF NOT EXISTS `Nd_162` (
  `Razdel` text,
  `PRazdel` text,
  `Punkt` text,
  `NPunkt` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Sovp` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=457 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Nd_163`
--
-- Создание: Дек 24 2014 г., 06:59
--

DROP TABLE IF EXISTS `Nd_163`;
CREATE TABLE IF NOT EXISTS `Nd_163` (
  `Razdel` text,
  `PRazdel` text,
  `Punkt` text,
  `NPunkt` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Sovp` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2199 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Nd_Etks`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Дек 24 2014 г., 06:59
--

DROP TABLE IF EXISTS `Nd_Etks`;
CREATE TABLE IF NOT EXISTS `Nd_Etks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `iCode` int(11) NOT NULL,
  `sName` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=109 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Nd_factors`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Фев 16 2015 г., 05:31
--

DROP TABLE IF EXISTS `Nd_factors`;
CREATE TABLE IF NOT EXISTS `Nd_factors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sPP` varchar(6) CHARACTER SET utf8 NOT NULL,
  `sName` varchar(200) CHARACTER SET utf8 NOT NULL,
  `idParent` int(11) NOT NULL,
  `tScribe` text CHARACTER SET utf8 NOT NULL,
  `idMed` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=67 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Nd_gn1313`
--
-- Создание: Дек 25 2014 г., 18:42
-- Последнее обновление: Янв 20 2015 г., 07:53
--

DROP TABLE IF EXISTS `Nd_gn1313`;
CREATE TABLE IF NOT EXISTS `Nd_gn1313` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sNum` varchar(4) CHARACTER SET utf8 NOT NULL,
  `sName` varchar(150) CHARACTER SET utf8 NOT NULL,
  `sNCas` varchar(20) CHARACTER SET utf8 NOT NULL,
  `sForm` varchar(50) CHARACTER SET utf8 NOT NULL,
  `sPdk` varchar(9) CHARACTER SET utf8 NOT NULL,
  `sAgr` varchar(4) CHARACTER SET utf8 NOT NULL,
  `sClass` varchar(4) CHARACTER SET utf8 NOT NULL,
  `sFeat` varchar(4) CHARACTER SET utf8 NOT NULL,
  `bFirstSelect` tinyint(1) NOT NULL,
  `fMM` float NOT NULL,
  `fSS` float NOT NULL,
  `gnversion` int(11) NOT NULL DEFAULT '0',
  `sAddonAsset` varchar(50) COLLATE utf8_bin NOT NULL,
  `sFeatCode` varchar(10) CHARACTER SET utf8 NOT NULL,
  `iTemp` int(11) NOT NULL,
  `idMed` varchar(40) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=8680 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Nd_Link_Ok01694_Etks`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Фев 09 2015 г., 03:31
--

DROP TABLE IF EXISTS `Nd_Link_Ok01694_Etks`;
CREATE TABLE IF NOT EXISTS `Nd_Link_Ok01694_Etks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idOk01694` int(11) NOT NULL,
  `idEtks` int(11) NOT NULL,
  `sDolgnName` text CHARACTER SET utf8 NOT NULL,
  `sRazdel` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=2846 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Nd_med1`
--
-- Создание: Мар 23 2015 г., 04:12
-- Последнее обновление: Мар 23 2015 г., 04:20
--

DROP TABLE IF EXISTS `Nd_med1`;
CREATE TABLE IF NOT EXISTS `Nd_med1` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sPunkt` varchar(50) NOT NULL,
  `sName` text NOT NULL,
  `sPer` varchar(150) NOT NULL,
  `sDocs` text NOT NULL,
  `sLab` text NOT NULL,
  `sInfo` text NOT NULL,
  `idFactor` int(11) NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=201 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Nd_med2`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Дек 24 2014 г., 06:59
--

DROP TABLE IF EXISTS `Nd_med2`;
CREATE TABLE IF NOT EXISTS `Nd_med2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sPunkt` varchar(50) NOT NULL,
  `sName` text NOT NULL,
  `sPer` varchar(50) NOT NULL,
  `sDocs` text NOT NULL,
  `sLab` text NOT NULL,
  `sInfo` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=56 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Nd_ok01694`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Дек 24 2014 г., 06:59
--

DROP TABLE IF EXISTS `Nd_ok01694`;
CREATE TABLE IF NOT EXISTS `Nd_ok01694` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sKch` varchar(5) NOT NULL,
  `sCode` varchar(9) NOT NULL,
  `sName` varchar(150) NOT NULL,
  `sRazr` varchar(10) NOT NULL,
  `sEtks` varchar(4) NOT NULL,
  `sOkz` varchar(5) NOT NULL,
  `sKat` varchar(3) NOT NULL,
  `sRazdel` text NOT NULL,
  `iPrioritet` int(11) NOT NULL,
  `sNoChild` text NOT NULL,
  `sNoWoman` text NOT NULL,
  `sBasePension` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=32129 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Nd_Pdu`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Дек 24 2014 г., 06:59
--

DROP TABLE IF EXISTS `Nd_Pdu`;
CREATE TABLE IF NOT EXISTS `Nd_Pdu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idFactors` int(11) NOT NULL,
  `sNdName` text CHARACTER SET utf8 NOT NULL,
  `sComment` text CHARACTER SET utf8 NOT NULL,
  `sName` text CHARACTER SET utf8 NOT NULL,
  `fPdu1` float NOT NULL,
  `fPdu2` float NOT NULL,
  `fPdu3` float NOT NULL,
  `fPdu4` float NOT NULL,
  `fPdu5` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Nd_pens`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Дек 24 2014 г., 06:59
--

DROP TABLE IF EXISTS `Nd_pens`;
CREATE TABLE IF NOT EXISTS `Nd_pens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idParent` int(11) NOT NULL,
  `sNum` varchar(50) NOT NULL,
  `sName` text NOT NULL,
  `sInfo` text NOT NULL,
  `iMark` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4817 ;

-- --------------------------------------------------------

--
-- Структура таблицы `Nd_pensFz`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Дек 24 2014 г., 06:59
--

DROP TABLE IF EXISTS `Nd_pensFz`;
CREATE TABLE IF NOT EXISTS `Nd_pensFz` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sName` text NOT NULL,
  `iState` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- Структура таблицы `nu_downloadstatistic`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Мар 22 2015 г., 23:46
--

DROP TABLE IF EXISTS `nu_downloadstatistic`;
CREATE TABLE IF NOT EXISTS `nu_downloadstatistic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(100) NOT NULL,
  `download_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1629 ;

-- --------------------------------------------------------

--
-- Структура таблицы `nu_subscribers`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Фев 11 2015 г., 04:19
--

DROP TABLE IF EXISTS `nu_subscribers`;
CREATE TABLE IF NOT EXISTS `nu_subscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` text NOT NULL,
  `create_datetime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=44 ;

-- --------------------------------------------------------

--
-- Структура таблицы `tmp_Etks_Razd`
--
-- Создание: Дек 24 2014 г., 06:59
-- Последнее обновление: Фев 09 2015 г., 03:30
--

DROP TABLE IF EXISTS `tmp_Etks_Razd`;
CREATE TABLE IF NOT EXISTS `tmp_Etks_Razd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sDolgnName` text CHARACTER SET utf8 NOT NULL,
  `sKS` int(5) NOT NULL,
  `sRazdel` text CHARACTER SET utf8 NOT NULL,
  `iBegin` int(11) NOT NULL DEFAULT '0',
  `iEnd` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=14507 ;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `Arm_group`
--
ALTER TABLE `Arm_group`
  ADD CONSTRAINT `Arm_group_1` FOREIGN KEY (`idParent`) REFERENCES `Arm_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
