<?php
namespace common\component;

use Yii;
use yii\web\Controller;

class BaseController extends Controller
{
	public $paramKeys;
	public $paramValues;
	public $languages = array('en','it');
        public $showBookList = true;
	
    public function init()
    {
        parent::init();

		if(isset($_REQUEST['lang']))
			$usedLanguage = $_REQUEST['lang'];
		
		// Setting the language.....................
		if(isset($usedLanguage) && $this->checkLanguage($usedLanguage))
		{
			Yii::$app->language = $usedLanguage;
		}
		else
		{
			$usedLanguage = $this->getNavigatorLanguage();
			if(!$this->checkLanguage($usedLanguage))
				$usedLanguage = 'en';
			Yii::$app->language = $usedLanguage;
		}
		
		$this->paramKeys = array_keys($_GET);
		$this->paramValues = array_values($_GET);
    }
	
	public function getNavigatorLanguage()
	{
		$language = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
		$language = strtolower(substr(chop($language[0]),0,2));
		return $language;
	}
	
	public function checkLanguage($language)
	{
		foreach($this->languages as $lang)
		{
			if($language === $lang)
				return true;	
		}
		return false;				
	}

	public function buildEmailBody($array, $lang = "en") {
		Yii::$app->language = $lang;
		//if ($lang == "it") {
			setlocale(LC_TIME, 'it_IT.UTF8', 'it_IT.utf8', 'ita_ita', 'italian', 'Italian_Swiss', 'Italian_Italy.1250', 'ITALY');
		//}
        return 
      
        '<div><b>- ' . $array["seat"][0] == 1 ? Yii::t('messages', 'Sunshade') : Yii::t('messages', 'Sunshade') .': </b>' . $array["seat"] . '</div>
        '.
        '<div><b>- ' . Yii::t('messages', 'Service Type') .': </b>' . $array["servicetype"] . '</div>
        '.
        '<div><b>- ' . Yii::t('messages', 'Number of Guests') .': </b>' . $array["guests"] . '</div>
        '.
        '<div><b>- ' . Yii::t('messages', 'Paid Money') .' (€): </b>' . $array["paidprice"] . '/' . $array["price"] . '</div>
        '.
          '<div><b>- ' . Yii::t('messages', 'Username') . ': </b>' . $array["username"] . '</div>
        '.
        '<div><b>- ' . Yii::t('messages', 'Address') .': </b>' . $array["address"] . '</div>
        '.
        '<div><b>- ' . Yii::t('messages', 'Email') .': </b>' . $array["email"] . '</div>
        '.
        '<div><b>- ' . Yii::t('messages', 'Phone Number') .': </b>' . $array["phonenumber"] . '</div>
        '.
        '<div><b>- ' . Yii::t('messages', 'Arrival Date') .': </b>' . date_format(date_create($array["arrival"]), 'jS F Y') . '</div>
        '.
        '<div><b>- ' . Yii::t('messages', 'Checkout Date') .': </b>' . date_format(date_create($array["checkout"]), 'jS F Y')  . '</div>
        '.
        '--------------------------------------------------------';


    }
}