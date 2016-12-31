<?
/*
 * v1.01 2016-12-14 Smap
 */
if(!defined('IN_SITESET')) die('Hacking attempt');
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
class CAppClearSMapContainer extends CCustomContainer {
	function Init(){
		parent::Init ();
		$this->Content = &OpenDM ( 'content', 'Common.Document' );
		return TRUE;
	}
	function CreateTree(&$MainCN,$obj,$path='/',$add_path=''){
		$this->Content->Clear();
		$itemsRS = $this->Content->Select($obj['journal'], $path, $obj['template']);
		if($itemsRS->FStorage){
			$path = $path==='/'?$path:$path.'/';
			foreach($itemsRS->FStorage as $itemR){
				$CNI = &$MainCN->AddNode ( 'Item' );
				$CNI->SetAttribute('alias',$alias = $itemR->GetValue('alias'));
				$CNI->SetAttribute('name',$itemR->GetValue('name'));
				$alias = $obj['rename']?str_replace('*',$alias,$obj['rename']):$alias;
				foreach(explode('/',$path.$alias) as $key=>$value){
					$path_new[$key] = urlencode($value);
				}
				$CNI->SetAttribute('path',$add_path.implode('/',$path_new).'/');
				/* если присутствует рекурсивная страница, типа рубрика в рубрике в рубрике... и т.д., то используется 'recursion'
				 * 
				 */
				if(isset($obj['recursion']) && is_array($obj['recursion']) && count($obj['recursion']) && $obj['recursion']['branch']===$itemR->GetValue('template_alias')){
					$this->CreateTree($CNI,array(
						'journal'=>$obj['journal'],
						'template'=>$obj['recursion']['template'],
						'recursion'=>$obj['recursion']
					),$path.$alias,$add_path);
				}
				if(isset($obj['next'])){
					if(!isset($obj['next']['journal']))$obj['next']['journal']=$obj['journal'];
					$this->CreateTree($CNI,$obj['next'],$path.$alias,$add_path);
				}
			}
		}
	}
	function GetCNI(&$MainCN,$obj,$params='',$path='/'){
		$CNI=$MainCN->AddNode('Item');
		$CNI->SetAttribute('name',$params['name']);
		$CNI->SetAttribute('path',($add_path = '/'.(isset($params['path'])?$params['path']:$obj['journal'])).'/');
		if($obj)$this->CreateTree($CNI,$obj,$path,$add_path);
	}
	function GetSiteMap(){
		$SmapCN = $this->DOM->AddNode('Smap');
		$this->GetCNI($SmapCN,'',array('name'=>'О сайте','path'=>'about'));
		$this->GetCNI($SmapCN,array(
				'journal'=>'actions',
				'template'=>array('#actions'),
			),array('name'=>'Акции'));
		$this->GetCNI($SmapCN,array(
				'journal'=>'delivery',
				'template'=>array('#roubrics'),
			),array('name'=>'Оплата и доставка'));
		$this->GetCNI($SmapCN,array(
				'journal'=>'kredit',
				'template'=>array('#roubrics'),
			),array('name'=>'Кредит'));
		$this->GetCNI($SmapCN,'',array('name'=>'Новости','path'=>'news'));
		$this->GetCNI($SmapCN,'',array('name'=>'Отзывы','path'=>'reviews'));
		$this->GetCNI($SmapCN,array(
				'journal'=>'catalogue',
				'template'=>array('#roubrics'),
				'recursion'=>array(
					'branch'=>'roubric',
					'template'=>array('#roubric','#production')
				)
			),array('name'=>'Каталог')
		);
		$this->GetCNI($SmapCN,'',array('name'=>'Контакты', 'path'=>'contacts'));
		$this->GetCNI($SmapCN,array(
				'journal'=>'articles',
				'template'=>array('#roubrics')
			),array('name'=>'Статьи'));
	}
	function GetResponse(){
		$this->GetSiteMap();
		return TRUE;
	}
}
