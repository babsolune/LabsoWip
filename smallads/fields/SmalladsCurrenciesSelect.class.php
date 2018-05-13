<?php
/*##################################################
 *                       SmalladsUrlBuilder.class.php
 *                            -------------------
 *   begin                : March 15, 2018
 *   copyright            : (C) 2018 Sebastien LARTIGUE
 *   email                : babsolune@phpboost.com
 *
 *
 ###################################################
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 ###################################################*/

/**
 * @author Sebastien LARTIGUE <babsolune@phpboost.com>
 */

class SmalladsCurrenciesSelect extends FormFieldSimpleSelectChoice
{
		const AFN = '؋';
		const ALL = 'Lek';
		const ANG = 'ƒ';
		const ARS = '$';
		const AUD = '$';
		const AWG = 'ƒ';
		const AZN = 'ман';
		const BAM = 'KM';
		const BBD = '$';
		const BDT = '৳';
		const BGN = 'лв';
		const BMD = '$';
		const BND = '$';
		const BOB = '$b';
		const BRL = 'R$';
		const BSD = '$';
		const BWP = 'P';
		const BYR = 'p.';
		const BZD = 'BZ$';
		const CAD = '$';
		const CHF = 'CHF';
		const CLP = '$';
		const CNY = '¥';
		const COP = '$';
		const CRC = '₡';
		const CUP = '₱;';
		const CZK = 'Kč';
		const DKK = 'kr';
		const DOP = 'RD$';
		const EGP = '£';
		const EUR = '€';
		const FJD = '$';
		const FKP = '£';
		const GBP = '£';
		const GIP = '£';
		const GTQ = 'Q';
		const GYD = '$';
		const HKD = '$';
		const HNL = 'L';
		const HRK = 'kn';
		const HUF = 'Ft';
		const IDR = 'Rp';
		const ILS = '₪';
		const INR = '₹';
		const IRR = '﷼';
		const ISK = 'kr';
		const JEP = '£';
		const JMD = 'J$';
		const JPY = '¥';
		const KGS = 'лв';
		const KHR = '៛';
		const KPW = '₩';
		const KRW = '₩';
		const KYD = '$';
		const KZT = 'лв';
		const LAK = '₭';
		const LBP = '£';
		const LKR = '₨';
		const LRD = '$';
		const LTL = 'Lt';
		const LVL = 'Ls';
		const MKD = 'ден';
		const MNT = '₮';
		const MUR = '₨';
		const MXN = '$';
		const MYR = 'RM';
		const MZN = 'MT';
		const NAD = '$';
		const NGN = '₦';
		const NIO = 'C$';
		const NOK = 'kr';
		const NPR = '₨';
		const NZD = '$';
		const OMR = '﷼';
		const PAB = 'B/.';
		const PEN = 'S/.';
		const PHP = '₱';
		const PKR = '₨';
		const PLN = 'zł';
		const PYG = 'Gs';
		const QAR = '﷼';
		const RON = 'lei';
		const RSD = 'Дин.';
		const RUB = 'руб';
		const SAR = '﷼';
		const SBD = '$';
		const SCR = '₨';
		const SEK = 'kr';
		const SGD = '$';
		const SHP = '£';
		const SOS = 'S';
		const SRD = '$';
		const SVC = '$';
		const SYP = '£';
		const THB = '฿';
		const TRY = '₤';
		const TTD = '$';
		const TWD = 'NT$';
		const UAH = '₴';
		const USD = '$';
		const UYU = '$U';
		const UZS = 'лв';
		const VEF = 'Bs';
		const VND = '₫';
		const XCD = '$';
		const YER = '﷼';
		const ZAR = 'R';

		public function __construct($sym, $label, $value = 0, $field_options = array(), array $constraints = array())
		{
			parent::__construct($sym, $label, $value, $this->generate_options(), $field_options, $constraints);
		}

		private function generate_options()
		{
			$lang = LangLoader::get('currencies', 'smallads');
			$options = array();
            $options[] = new FormFieldSelectChoiceOption($lang['EUR'], self::EUR);
			$options[] = new FormFieldSelectChoiceOption($lang['GBP'], self::GBP);
			$options[] = new FormFieldSelectChoiceOption($lang['USD'], self::USD);
            $options[] = new FormFieldSelectChoiceOption($lang['AFN'], self::AFN);
            $options[] = new FormFieldSelectChoiceOption($lang['ALL'], self::ALL);
            $options[] = new FormFieldSelectChoiceOption($lang['ANG'], self::ANG);
            $options[] = new FormFieldSelectChoiceOption($lang['ARS'], self::ARS);
            $options[] = new FormFieldSelectChoiceOption($lang['AUD'], self::AUD);
            $options[] = new FormFieldSelectChoiceOption($lang['AWG'], self::AWG);
            $options[] = new FormFieldSelectChoiceOption($lang['AZN'], self::AZN);
            $options[] = new FormFieldSelectChoiceOption($lang['BAM'], self::BAM);
            $options[] = new FormFieldSelectChoiceOption($lang['BBD'], self::BBD);
            $options[] = new FormFieldSelectChoiceOption($lang['BDT'], self::BDT);
            $options[] = new FormFieldSelectChoiceOption($lang['BGN'], self::BGN);
            $options[] = new FormFieldSelectChoiceOption($lang['BMD'], self::BMD);
            $options[] = new FormFieldSelectChoiceOption($lang['BND'], self::BND);
            $options[] = new FormFieldSelectChoiceOption($lang['BOB'], self::BOB);
            $options[] = new FormFieldSelectChoiceOption($lang['BRL'], self::BRL);
            $options[] = new FormFieldSelectChoiceOption($lang['BSD'], self::BSD);
            $options[] = new FormFieldSelectChoiceOption($lang['BWP'], self::BWP);
            $options[] = new FormFieldSelectChoiceOption($lang['BYR'], self::BYR);
            $options[] = new FormFieldSelectChoiceOption($lang['BZD'], self::BZD);
            $options[] = new FormFieldSelectChoiceOption($lang['CAD'], self::CAD);
            $options[] = new FormFieldSelectChoiceOption($lang['CHF'], self::CHF);
            $options[] = new FormFieldSelectChoiceOption($lang['CLP'], self::CLP);
            $options[] = new FormFieldSelectChoiceOption($lang['CNY'], self::CNY);
            $options[] = new FormFieldSelectChoiceOption($lang['COP'], self::COP);
            $options[] = new FormFieldSelectChoiceOption($lang['CRC'], self::CRC);
            $options[] = new FormFieldSelectChoiceOption($lang['CUP'], self::CUP);
            $options[] = new FormFieldSelectChoiceOption($lang['CZK'], self::CZK);
            $options[] = new FormFieldSelectChoiceOption($lang['DKK'], self::DKK);
            $options[] = new FormFieldSelectChoiceOption($lang['DOP'], self::DOP);
            $options[] = new FormFieldSelectChoiceOption($lang['EGP'], self::EGP);
            $options[] = new FormFieldSelectChoiceOption($lang['FJD'], self::FJD);
            $options[] = new FormFieldSelectChoiceOption($lang['FKP'], self::FKP);
            $options[] = new FormFieldSelectChoiceOption($lang['GIP'], self::GIP);
            $options[] = new FormFieldSelectChoiceOption($lang['GTQ'], self::GTQ);
            $options[] = new FormFieldSelectChoiceOption($lang['GYD'], self::GYD);
            $options[] = new FormFieldSelectChoiceOption($lang['HKD'], self::HKD);
            $options[] = new FormFieldSelectChoiceOption($lang['HNL'], self::HNL);
            $options[] = new FormFieldSelectChoiceOption($lang['HRK'], self::HRK);
            $options[] = new FormFieldSelectChoiceOption($lang['HUF'], self::HUF);
            $options[] = new FormFieldSelectChoiceOption($lang['IDR'], self::IDR);
            $options[] = new FormFieldSelectChoiceOption($lang['ILS'], self::ILS);
            $options[] = new FormFieldSelectChoiceOption($lang['INR'], self::INR);
            $options[] = new FormFieldSelectChoiceOption($lang['IRR'], self::IRR);
            $options[] = new FormFieldSelectChoiceOption($lang['ISK'], self::ISK);
            $options[] = new FormFieldSelectChoiceOption($lang['JEP'], self::JEP);
            $options[] = new FormFieldSelectChoiceOption($lang['JMD'], self::JMD);
            $options[] = new FormFieldSelectChoiceOption($lang['JPY'], self::JPY);
            $options[] = new FormFieldSelectChoiceOption($lang['KGS'], self::KGS);
            $options[] = new FormFieldSelectChoiceOption($lang['KHR'], self::KHR);
            $options[] = new FormFieldSelectChoiceOption($lang['KPW'], self::KPW);
            $options[] = new FormFieldSelectChoiceOption($lang['KRW'], self::KRW);
            $options[] = new FormFieldSelectChoiceOption($lang['KYD'], self::KYD);
            $options[] = new FormFieldSelectChoiceOption($lang['KZT'], self::KZT);
            $options[] = new FormFieldSelectChoiceOption($lang['LAK'], self::LAK);
            $options[] = new FormFieldSelectChoiceOption($lang['LBP'], self::LBP);
            $options[] = new FormFieldSelectChoiceOption($lang['LKR'], self::LKR);
            $options[] = new FormFieldSelectChoiceOption($lang['LRD'], self::LRD);
            $options[] = new FormFieldSelectChoiceOption($lang['LTL'], self::LTL);
            $options[] = new FormFieldSelectChoiceOption($lang['LVL'], self::LVL);
            $options[] = new FormFieldSelectChoiceOption($lang['MKD'], self::MKD);
            $options[] = new FormFieldSelectChoiceOption($lang['MNT'], self::MNT);
            $options[] = new FormFieldSelectChoiceOption($lang['MUR'], self::MUR);
            $options[] = new FormFieldSelectChoiceOption($lang['MXN'], self::MXN);
            $options[] = new FormFieldSelectChoiceOption($lang['MYR'], self::MYR);
            $options[] = new FormFieldSelectChoiceOption($lang['MZN'], self::MZN);
            $options[] = new FormFieldSelectChoiceOption($lang['NAD'], self::NAD);
            $options[] = new FormFieldSelectChoiceOption($lang['NGN'], self::NGN);
            $options[] = new FormFieldSelectChoiceOption($lang['NIO'], self::NIO);
            $options[] = new FormFieldSelectChoiceOption($lang['NOK'], self::NOK);
            $options[] = new FormFieldSelectChoiceOption($lang['NPR'], self::NPR);
            $options[] = new FormFieldSelectChoiceOption($lang['NZD'], self::NZD);
            $options[] = new FormFieldSelectChoiceOption($lang['OMR'], self::OMR);
            $options[] = new FormFieldSelectChoiceOption($lang['PAB'], self::PAB);
            $options[] = new FormFieldSelectChoiceOption($lang['PEN'], self::PEN);
            $options[] = new FormFieldSelectChoiceOption($lang['PHP'], self::PHP);
            $options[] = new FormFieldSelectChoiceOption($lang['PKR'], self::PKR);
            $options[] = new FormFieldSelectChoiceOption($lang['PLN'], self::PLN);
            $options[] = new FormFieldSelectChoiceOption($lang['PYG'], self::PYG);
            $options[] = new FormFieldSelectChoiceOption($lang['QAR'], self::QAR);
            $options[] = new FormFieldSelectChoiceOption($lang['RON'], self::RON);
            $options[] = new FormFieldSelectChoiceOption($lang['RSD'], self::RSD);
            $options[] = new FormFieldSelectChoiceOption($lang['RUB'], self::RUB);
            $options[] = new FormFieldSelectChoiceOption($lang['SAR'], self::SAR);
            $options[] = new FormFieldSelectChoiceOption($lang['SBD'], self::SBD);
            $options[] = new FormFieldSelectChoiceOption($lang['SCR'], self::SCR);
            $options[] = new FormFieldSelectChoiceOption($lang['SEK'], self::SEK);
            $options[] = new FormFieldSelectChoiceOption($lang['SGD'], self::SGD);
            $options[] = new FormFieldSelectChoiceOption($lang['SHP'], self::SHP);
            $options[] = new FormFieldSelectChoiceOption($lang['SOS'], self::SOS);
            $options[] = new FormFieldSelectChoiceOption($lang['SRD'], self::SRD);
            $options[] = new FormFieldSelectChoiceOption($lang['SVC'], self::SVC);
            $options[] = new FormFieldSelectChoiceOption($lang['SYP'], self::SYP);
            $options[] = new FormFieldSelectChoiceOption($lang['THB'], self::THB);
            $options[] = new FormFieldSelectChoiceOption($lang['TRY'], self::TRY);
            $options[] = new FormFieldSelectChoiceOption($lang['TTD'], self::TTD);
            $options[] = new FormFieldSelectChoiceOption($lang['TWD'], self::TWD);
            $options[] = new FormFieldSelectChoiceOption($lang['UAH'], self::UAH);
            $options[] = new FormFieldSelectChoiceOption($lang['UYU'], self::UYU);
            $options[] = new FormFieldSelectChoiceOption($lang['UZS'], self::UZS);
            $options[] = new FormFieldSelectChoiceOption($lang['VEF'], self::VEF);
            $options[] = new FormFieldSelectChoiceOption($lang['VND'], self::VND);
            $options[] = new FormFieldSelectChoiceOption($lang['XCD'], self::XCD);
            $options[] = new FormFieldSelectChoiceOption($lang['YER'], self::YER);
            $options[] = new FormFieldSelectChoiceOption($lang['ZAR'], self::ZAR);
			return $options;
		}
	}
?>
