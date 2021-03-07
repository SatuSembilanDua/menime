<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: x-access-header, Authorization, Origin, X-Requested-With, Content-Type, Accept");

require('simplehtmldom/simple_html_dom.php');

function e_url( $s ) {
	return rtrim(strtr(base64_encode($s), '+/', '-_'), '='); 
}
 
function d_url($s) {
	return base64_decode(str_pad(strtr($s, '-_', '+/'), strlen($s) % 4, '=', STR_PAD_RIGHT));
}

function pre($isi){
	echo '<pre>';
	print_r($isi);
	echo '</pre>';
}

function isMobile() {
    //return true;
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function _filter_($arr){
	$ret = [];
	foreach ($arr as $k => $v) {
		if(strpos($v, 'lowongan') || strpos($v, 'iklan') || strpos($v, 'OneMangaDay')){
			//echo "$v <br>";
		}else{
			array_push($ret, $v);
		}
	}
	//print_r($ret) ;
	return $ret;
}


function _filter2($arr){
	$ret = [];
	foreach ($arr as $k => $v) {
		if(strpos($v, 'result')){
			array_push($ret, $v);
		}
	}
	if(sizeof($ret)==0){
		foreach ($arr as $k => $v) {
			if(strpos($v, 'creadit') || strpos($v, 'komen') || strpos($v, 'manga.png')){
			}else{
				array_push($ret, $v);
			}
		}	
	}
	//print_r($ret) ;
	return $ret;
}



/*
*
*
*
* //////////////////////////// ANIME AREA ///////////////////////////////////////
*
*
*
*
*/

function list_episode($url){
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($ch, CURLOPT_PROXY, null);

	$data = curl_exec($ch);
	$info = curl_getinfo($ch);
	$error = curl_error($ch);

	curl_close($ch);
	$dom = new simple_html_dom(null, true, true, DEFAULT_TARGET_CHARSET, true, DEFAULT_BR_TEXT, DEFAULT_SPAN_TEXT);

	//$html = file_get_html($url);
	$html = $dom->load($data, true, true);
	$list_episode = array();

	foreach ($html->find(".episodelist") as $div) {
		foreach ($div->find("li") as $li) {
			$eps = $li->find(".leftoff",0);
			$judul = $li->find(".lefttitle",0);
			$dt = $li->find(".rightoff",0);
			array_push($list_episode, array(
											'link'	=> $eps->find("a",0)->href,
											'eps'	=> $eps->plaintext,
											'judul'	=> $judul->plaintext,
											'date'	=> $dt->plaintext
											));
		}
	}
	return $list_episode;
}


function list_episode_page($url){
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	//curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36");
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.87 Safari/537.36");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($ch, CURLOPT_PROXY, null);

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER,
    array(
        "Upgrade-Insecure-Requests: 1",
        "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.87 Safari/537.36",
        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3",
        "Accept-Language: en-US,en;q=0.9",
        "cookie: __cfduid=da5a43c33c765d8c5ecc8757d17acbaa21573400863; cf_clearance=dbdc95a38f52919ba3c14d5e8473195aa022b0e3-1573400868-0-150; _ga=GA1.2.1782458.1573400868; _gid=GA1.2.1987219291.1573400868; HstCfa4135177=1573400875080; HstCmu4135177=1573400875080; HstCnv4135177=1; HstCns4135177=1; __dtsu=1EE704453831C85D33301B0402D15797; __atuvc=3%7C46; __atuvs=5dc8312478a5a8d9002; HstCla4135177=1573401070454; HstPn4135177=3; HstPt4135177=3"
    ));

	$data = curl_exec($ch);
	$info = curl_getinfo($ch);
	$error = curl_error($ch);

	curl_close($ch);
	$dom = new simple_html_dom(null, true, true, DEFAULT_TARGET_CHARSET, true, DEFAULT_BR_TEXT, DEFAULT_SPAN_TEXT);

	//$html = file_get_html($url);
	$html = $dom->load($data, true, true);
	$list_episode = array();
	//echo $html;
	//echo htmlentities($html);
	foreach ($html->find(".episodelist") as $div) {
		$i=0;
		foreach ($div->find("li") as $li) {

			$eps = $li->find(".leftoff",0);
			$judul = $li->find(".lefttitle",0);
			$dt = $li->find(".rightoff",0);
			array_push($list_episode, array(
											'link'	=> $eps->find("a",0)->href,
											'eps'	=> $eps->plaintext,
											'judul'	=> $judul->plaintext,
											'date'	=> $dt->plaintext
											));

			if($i==10){
				return $list_episode;
			}
			$i++;
		}
	}
}


function list_anime($url){
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($ch, CURLOPT_PROXY, null);

	$data = curl_exec($ch);
	$info = curl_getinfo($ch);
	$error = curl_error($ch);

	curl_close($ch);
	$dom = new simple_html_dom(null, true, true, DEFAULT_TARGET_CHARSET, true, DEFAULT_BR_TEXT, DEFAULT_SPAN_TEXT);

	//$html = file_get_html($url);
	$html = $dom->load($data, true, true);
	$list_anime = array();
	foreach ($html->find('.idframe') as $vid) {
		$list_anime['error'] = $error;
		$list_anime['video'] = $vid->attr["src"];
	}
	
	
	return $list_anime;

}


function list_anime_py($url){
	$url = e_url($url);
	$a = @file_get_contents("https://apimenime.herokuapp.com/list_anime/$url");
	//$b = json_decode($a, true);
	$error = '';
	if($a === FALSE){$a="";
		$error = error_get_last();
		$error['penyakit'] = d_url($url);
	}
	return array(
					"video" => $a,
					"error" => $error
					);

	//return $list_anime;
}

/*$anime = list_anime("https://www.oploverz.in/samurai-x-episode-01-subtitle-indonesia/");
echo '<pre>';
print_r($anime);
echo '</pre>';*/

if(isset($_GET['test'])){
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: x-access-header, Authorization, Origin, X-Requested-With, Content-Type, Accept");

	//$anime = anime_info_bc("https://samehadaku.vip/anime/one-piece/");
	$url = d_url("aHR0cHM6Ly93d3cub3Bsb3ZlcnouaW4vc2VyaWVzL29uZS1waWVjZS1zdWItaW5kby8");
	$anime = anime_info_py($url);
	$anime2 = anime_info($url);

	$url2 = "https://www.oploverz.in/one-piece-episode-959-subtitle-indonesia/";
	$eps = list_anime_py($url2);
	echo "<pre>";
	print_r($anime2);
	print_r($anime);
	print_r($eps);
	echo "</pre>";
	//echo json_encode($anime);
}

function anime_info_py($url){
	$url = e_url($url);
	// https://apimenime.herokuapp.com/
	$a = file_get_contents("https://apimenime.herokuapp.com/anime_info/$url");
	//$a = file_get_contents("http://127.0.0.1:5000/anime_info/$url");
	$b = json_decode($a, true);
	$i = file_get_contents($b['img']);
	$src =  base64_encode($i);
	return array(
					"desc" => $b['desc'],
					"info" => $b['info'],
					"img" => $src,
					);
}

function anime_info($url){
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	
	//curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.87 Safari/537.36");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($ch, CURLOPT_PROXY, null);

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $headers = [
	    'X-Apple-Tz: 0',
	    'X-Apple-Store-Front: 143444,12',
	    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
	    //'Accept-Encoding: gzip, deflate, br',
	    'Accept-Language: id-ID,id;q=0.9',
	    'Cache-Control: max-age=0',
	    'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
	    'Host: www.oploverz.in',
	    'Referer: https://www.oploverz.in/series/one-piece-sub-indo/', 
	    'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.66 Safari/537.36',
	    //'X-MicrosoftAjax: Delta=true'
	    "Upgrade-Insecure-Requests: 1",
	    "Connection: keep-alive",
	    "cookie: __cfduid=d463c2c74b8cb4ad701c41487ef15ba271592223878; PHPSESSID=cq1245ofoi0qab5gun4lqfj365; _ga=GA1.2.205899321.1592223880; _gid=GA1.2.1323666345.1592223880; HstCfa4237846=1592223880537; HstCmu4237846=1592223880537; c_ref_4237846=https%3A%2F%2Fwww.google.com%2F; __dtsu=1040159222388239E79CA78CAA0D19A1; HstCnv4237846=2; _gat_gtag_UA_126097535_3=1; HstCla4237846=1592234566756; HstPn4237846=6; HstPt4237846=14; HstCns4237846=3"
	];

	//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$data = curl_exec($ch);
	$info = curl_getinfo($ch);
	$error = curl_error($ch);

	/*echo "<pre>";
	print_r($info);
	echo "<hr>";
	print_r($error);
	echo "</pre>";
	echo htmlentities($data);*/
	//echo $data;

	curl_close($ch);
	$dom = new simple_html_dom(null, true, true, DEFAULT_TARGET_CHARSET, true, DEFAULT_BR_TEXT, DEFAULT_SPAN_TEXT);

	//$html = file_get_html($url);
	$html = $dom->load($data, true, true);
	$info_anime = array();
	foreach ($html->find('.desc') as $con) {
		$info_anime['desc'] = htmlentities($con);
	}
	foreach ($html->find('.listinfo') as $con) {
		$info_anime['info'] = htmlentities($con);
	}
	foreach ($html->find('.imgdesc') as $con) {
		foreach ($con->find('img') as $im) {
			//echo $im->src;
			$a = file_get_contents($im->src);
			$src =  base64_encode($a);
			$info_anime['img'] = $src;
			//echo '<img src="data:image/gif;base64,'.$src.'"> ';
		}
	}
	return $info_anime;
	return [];

}


function anime_info2($url){
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($ch, CURLOPT_PROXY, null);

	$data = curl_exec($ch);
	$info = curl_getinfo($ch);
	$error = curl_error($ch);

	curl_close($ch);
	$dom = new simple_html_dom(null, true, true, DEFAULT_TARGET_CHARSET, true, DEFAULT_BR_TEXT, DEFAULT_SPAN_TEXT);

	$html = $dom->load($data, true, true);
	$info_anime = array();
	
	foreach ($html->find('.imgdesc') as $con) {
		foreach ($con->find('img') as $im) {
			$a = file_get_contents($im->src);
			$src =  base64_encode($a);
			$info_anime['img'] = $src;
		}
	}
	return $info_anime;
}
/*$anime = anime_info("https://www.oploverz.in/series/boruto-naruto-next-generations/");
echo '<pre>';
print_r($anime);
echo '</pre>';*/

if(isset($_GET['debug_tes'])){
	header("Access-Control-Allow-Origin: *");
	header("Access-Control-Allow-Headers: x-access-header, Authorization, Origin, X-Requested-With, Content-Type, Accept");

?>
<iframe src="https://www.oploverz.in/" frameborder="0" width="100%"></iframe>
<?php
	$url = "https://www.oploverz.in/"; 
	$a = file_get_contents($url);
	echo htmlentities($a);
	echo "<hr>"; 
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($ch, CURLOPT_PROXY, null);
	curl_setopt($ch, CURLOPT_HTTPHEADER,
	    array(
	        "Upgrade-Insecure-Requests: 1",
	        "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.87 Safari/537.36",
	        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3",
	        "Accept-Language: en-US,en;q=0.9",
	        "cookie: __cfduid=d7cad488d51ee8439553a4021c9592dba1612441264; _ga=GA1.2.543937370.1612441267; _gid=GA1.2.2075059068.1612441267; __cf_bm=d95bf8d638e749e5a8248b84d1f19069c2ee89bc-1612441267-1800-AYAwP17Q503w9oTlzW8OsQ4AormFg2F49z3MGWToBUmie7zKLh35MlMbTqI3/E8OwuQI+8DoZZTBT2z8z4PgGHfT3rTdQnRIpHvpNmp7ARDJ+5KAwffeIbHbvULbWdEOEw==; PHPSESSID=r126hve7fjgf6l7ovufg26gfg4; _gat=1; __atuvc=2%7C5; __atuvs=601be6b2cc538daf001"
	    ));

	$data = curl_exec($ch);
	$info = curl_getinfo($ch);
	$error = curl_error($ch);
	echo "<pre>";
	print_r($info);
	echo "<hr>";
	print_r($error);
	echo "<hr>";
	echo htmlentities($data);
	echo "</pre>";

	curl_close($ch);

}

function list_anime2($url){
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($ch, CURLOPT_PROXY, null);
	curl_setopt($ch, CURLOPT_HTTPHEADER,
	    array(
	        "Upgrade-Insecure-Requests: 1",
	        "User-Agent: Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.87 Safari/537.36",
	        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3",
	        "Accept-Language: en-US,en;q=0.9",
	        "cookie: __cfduid=d463c2c74b8cb4ad701c41487ef15ba271592223878; PHPSESSID=cq1245ofoi0qab5gun4lqfj365; _ga=GA1.2.205899321.1592223880; _gid=GA1.2.1323666345.1592223880; HstCfa4237846=1592223880537; HstCmu4237846=1592223880537; c_ref_4237846=https%3A%2F%2Fwww.google.com%2F; __dtsu=1040159222388239E79CA78CAA0D19A1; HstCnv4237846=2; _gat_gtag_UA_126097535_3=1; HstCla4237846=1592234566756; HstPn4237846=6; HstPt4237846=14; HstCns4237846=3"
	    ));

	$data = curl_exec($ch);
	$info = curl_getinfo($ch);
	$error = curl_error($ch);

	curl_close($ch);
	$dom = new simple_html_dom(null, true, true, DEFAULT_TARGET_CHARSET, true, DEFAULT_BR_TEXT, DEFAULT_SPAN_TEXT);
	
	$html = $dom->load($data, true, true);
	$a = array();
	$a["html"] = htmlentities($html);
	foreach ($html->find("#tontonin") as $div) {
		/*echo "<pre>";
		echo htmlentities($div);
		echo "</pre>";*/
		//return array("video" => e_url("http://".$div->attr["src"]));
		$a["video"] = e_url($div->attr["src"]);
	}
	return $a;
	
}


/*$url = "https://anoboy.mobi/avatar-the-legend-of-aang-episode-01/";
//$url = "https://animenine.com/nonton-avatar-the-legend-of-aang-sub-indo/episode-60";
//$url = "https://www.oploverz.in/series/boruto-naruto-next-generations/";
$vid = list_anime2($url);
*/
function list_episode2($url){
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_PROXY, null);
	

	$data = curl_exec($ch);
	$info = curl_getinfo($ch);
	$error = curl_error($ch);

	curl_close($ch);
	$dom = new simple_html_dom(null, true, true, DEFAULT_TARGET_CHARSET, true, DEFAULT_BR_TEXT, DEFAULT_SPAN_TEXT);
	
	$html = $dom->load($data, true, true);
	$ls_eps = [];
	foreach ($html->find(".ep") as $div) {
		foreach ($div->find("a") as $a) {
			//echo $a->plaintext;
			$ls_eps[] = array(
								"judul" => $a->plaintext, 
								"link" => $a->attr["href"], 
								);
		}
	}
	return $ls_eps;
}

function get_kekkaishi($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$headers = [
	    'X-Apple-Tz: 0',
	    'X-Apple-Store-Front: 143444,12',
	    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
	    'Accept-Encoding: gzip, deflate',
	    'Accept-Language: en-US,en;q=0.5',
	    'Cache-Control: no-cache',
	    'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
	    'Host: www3.animeseries.info',
	    'Referer: http://www3.animeseries.info/tvseries/kekkaishi/', 
	    'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:28.0) Gecko/20100101 Firefox/28.0',
	    'X-MicrosoftAjax: Delta=true'
	];

	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	$data = curl_exec($ch);
	$info = curl_getinfo($ch);
	$error = curl_error($ch);
	return $info['redirect_url'];
}



function get_dragonball($url){
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url.'/');
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	curl_setopt($ch, CURLOPT_PROXY, null);

	$data = curl_exec($ch);
	$info = curl_getinfo($ch);
	$error = curl_error($ch);
	$dom = new simple_html_dom(null, true, true, DEFAULT_TARGET_CHARSET, true, DEFAULT_BR_TEXT, DEFAULT_SPAN_TEXT);

	//$html = file_get_html($url);
	$html = $dom->load($data, true, true);
	$list_episode = array();

	foreach ($html->find(".playeriframe") as $iframe) {
		return $iframe->src;
	}
}

//$lk = "https://www.animeindo.cc/dragon-ball-episode-002-subtitle-indonesia";
/*$lk = "https://www.animeindo.cc/2020/02/dragon-ball-episode-002-subtitle-indonesia";

$b = get_dragonball($lk);
pre($b);*/

function korra_vid($url){
	$vid = korak_vid1($url);
	if($vid!=''){
		return $vid; 
	}else{
		return 'embed';
	}
}

function korak_vid1($url){
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_PROXY, null);
	

	$data = curl_exec($ch);
	$info = curl_getinfo($ch);
	$error = curl_error($ch);

	curl_close($ch);
	$dom = new simple_html_dom(null, true, true, DEFAULT_TARGET_CHARSET, true, DEFAULT_BR_TEXT, DEFAULT_SPAN_TEXT);
	
	$html = $dom->load($data, true, true);
	foreach ($html->find(".video-js") as $vid) {
		foreach ($vid->find("source") as $src) {
			return $src->attr['src'];
		}
	}
}


function cek_update_anime($list_episode, $origin){
	$le = list_episode_page($origin);
	//unset($le[0]);
	$le = array_values($le);
	$eps_lama = (int)explode(" ", $list_episode[0]['eps'])[1];
	$eps_baru_arr = explode(" ", $le[0]['eps']);
	$eps_baru = $eps_lama;
	foreach ($eps_baru_arr as $k => $v) {
		if(is_numeric($v)){
			$eps_baru = (int)$v;
		}
	}
	$kur = 0;
	$new = [];
	if($eps_baru>$eps_lama){
		$kur = $eps_baru-$eps_lama;
		$new = array_splice($le, 0, $kur);
		foreach ($new as $k => $v) {
			$eps = preg_replace('/\s+/', ' ', trim($v['eps']));
			$judul = trim($v['judul']);
			$new[$k]['eps'] = $eps;
			$new[$k]['judul'] = $judul;
			$new[$k]['sts'] = '1';
			$new[$k]['div'] = $kur;
		}
		$list_episode = array_merge($new, $list_episode);
	}
	return $list_episode;
}


function cek_update_anime_py($list_episode, $origin){
	$le = file_get_contents("https://apimenime.herokuapp.com/eps_anime/".e_url($origin));
	//unset($le[0]);
	$le = json_decode($le, true);
	$le = array_values($le);
	/*
	pre($le);
	echo "<br>";
	*/
	$eps_lama = (int)explode(" ", $list_episode[0]['eps'])[1];
	$eps_baru_arr = explode(" ", $le[0]['eps']);
	$eps_baru = $eps_lama;
	foreach ($eps_baru_arr as $k => $v) {
		if(is_numeric($v)){
			$eps_baru = (int)$v;
		}
	}
	//echo "$eps_baru  $eps_lama"; 
	$kur = 0;
	$new = [];
	if($eps_baru>$eps_lama){
		$kur = $eps_baru-$eps_lama;
		$new = array_splice($le, 0, $kur);
		foreach ($new as $k => $v) {
			$eps = preg_replace('/\s+/', ' ', trim($v['eps']));
			$judul = trim($v['judul']);
			$new[$k]['eps'] = $eps;
			$new[$k]['judul'] = $judul;
			$new[$k]['sts'] = '1';
			$new[$k]['div'] = $kur;
		}
		$list_episode = array_merge($new, $list_episode);
	}
	return $list_episode;
}
/*
$url = "https://anoboy.mobi/anime/avatar-the-legend-of-aang/";
$vid = list_episode2($url);*/


?>
