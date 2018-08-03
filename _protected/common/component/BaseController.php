<?php
namespace common\component;

use Yii;
use yii\web\Controller;
use frontend\models\PendingBook;

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

    public function buildEmail($array)
    {
    	return $this->renderAjax('customerBookDetail', ['array' => $array]);
    }
}