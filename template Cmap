<?if ( !defined( 'IN_SITESET' ) ) die( 'Hacking attempt' );
require_once( TPL.'/systpl/layouts/lib.layout.php' );
function url_loc($path = '/'){
 return '<url><loc>http://'.$_SERVER[ 'HTTP_HOST' ].iconv('Koi8-r', 'UTF-8', $path).'</loc></url>';
}
function url_loc_tree(&$MainCN){
	$body = '';
	if($MainCN->Count('Item')){
		foreach($MainCN->Children('Item') as $Item){
			$body.=url_loc($Item->ValueOf( '@path',true));
			if($Item->Count('Item')) $body.=url_loc_tree($Item);
		}
	}
	return $body;
}
function ul_li_a(&$MainCN){
	if($MainCN->Count('Item')){
		?><ul><?
			foreach($MainCN->Children('Item') as $Item){
				?><li><a href="<?=$Item->ValueOf('@path');?>"><?=$Item->ValueOf('@name',true);?></a></li><?
				if($Item->Count('Item')){?><li><?ul_li_a($Item);?></li><?}
			}
		?></ul><?
	}
}
if(isset($_REQUEST['xml'])){
	function Template_Main( $DOM ){
		header( 'Content-type: text/xml' );
		header( 'Cache-Control: no-store, no-cache, must-revalidate' );
		echo '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.url_loc_tree($DOM->GetNode('Smap')).'</urlset>';
		die();
	}
}else{
	require_once( TPL.'/sitemap/common.layout.php' );
	function Template_Content( $DOM ){
		?><ul class="sitemap"><?
			ul_li_a($DOM->GetNode('Smap'));
		?></ul><?
	}
}
