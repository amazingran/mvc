<?php
/**
* 采集慧聪网公司数据
*/
header("Content-Type:text/html;charset=gb2312");

class CollectHcController extends BaseController
{
	
	public function collectHcAction($business=''){
		//获取行业页面内容		
		$ch=Curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://top.hc360.com/up/wujin-0-1.html");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);

		$output=curl_exec($ch);
		if($output===false){
			echo "CURL Error:".curl_error($ch);
		}

		curl_close($ch);

		$output=iconv("gbk", "utf-8", $output);

		//匹配行业链接
		$type_links = array();
		$pat="/title=\"(.+)\" href=\"(http:\/\/top\.hc360\.com\/up\/(\w+)-0-1.html)\"/";
		preg_match_all($pat, $output, $type_links);
		// var_dump($type_links[2]);
		//匹配该行业的关键字链接
		$kword_links=array();
		$pat_kword="/title=\"(.+)\" href=\"(http:\/\/www\.hc360\.com\/(.+|-.+)\/\d+.html)\"/";
		preg_match_all($pat_kword,$output,$kword_links);
		// var_dump($kword_links[2]);

		//获取关键字页面内容
		$ch_kword=Curl_init();
		curl_setopt($ch_kword, CURLOPT_URL, "http://www.hc360.com/hots-9lt/900464417.html");
		curl_setopt($ch_kword, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch_kword, CURLOPT_HEADER, 0);

		$output_kword=curl_exec($ch_kword);
		if($output_kword===false){
			echo "CURL Error:".curl_error($ch_kword);
		}

		curl_close($ch_kword);

		$output_kword=iconv("gbk", "utf-8", $output_kword);

		//匹配该关键字的公司链接
		//获取公司名称
		//获取公司省市
		$company_links=array();
		$pat_company="/href=\"(http:\/\/(.+)\.b2b\.hc360\.com\/)\" title=\"(.+?)\"/";
		preg_match_all($pat_company,$output_kword,$company_links);
		//公司链接
		// var_dump($company_links[1]);
		//公司昵称
		// var_dump($company_links[2]);
		//公司名称
		// var_dump($company_links[3]);
		//公司省、市
		$company_areas=array();
		$pat_company_areas="/<div class=\"province\">(.+)<\/div>/";
		preg_match_all($pat_company_areas,$output_kword,$company_areas);
		// var_dump($company_areas[1]);
		$prevince="";
		$city="";
		for($i=0;$i<count($company_areas[1]);$i++){

			$prevince_city=explode(" ", $company_areas[1][$i]);
			if(count($prevince_city)==1){
				$prevince=$prevince_city[0];
				$city="";
			}elseif(count($prevince_city)==2){
				$prevince=$prevince_city[0];
				$city=$prevince_city[1];
			}

			// echo $prevince."-->".$city;
		}

		//获取城市ID
		$city=trim(mb_substr($city, 0,-1,"utf-8"));
		$sql_city="select * from b2b_city where name='{$city}'";

		$select_city=new UserModel("b2b_city");
		$city_info=$select_city->selectData($sql_city);
		echo $sql_city;
		echo "<br>";
		var_dump($city_info);
		gettype($city_info);
		die("end");


		//获取公司介绍信息页面内容
		$ch_company=Curl_init();
		curl_setopt($ch_company, CURLOPT_URL, "http://jnwzsk.b2b.hc360.com/shop/show.html");
		// curl_setopt($ch_company, CURLOPT_URL, "http://hcwsjcut.b2b.hc360.com/shop/show.html");
		curl_setopt($ch_company, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch_company, CURLOPT_HEADER, 0);

		$output_company=curl_exec($ch_company);
		if($output_company===false){
			echo "CURL Error:".curl_error($ch_company);
		}

		curl_close($ch_company);

		$output_company=iconv("gbk", "utf-8", $output_company);


		//获得联系人
		//获得电话、手机号码
		//经营地址
		//公司介绍
		$hc_linkman="";
		$hc_telephone="";
		$hc_cellphone="";
		$hc_address="";
		$hc_intruduce="";		

		$pat_linkman="/联系人<\/span><em>：(.*?)<\/em>|<div class=\"p current\"><span>(.+)<\/span>/";
		$pat_telephone="/电话<\/span><em>\s*：(.*?)<\/em>/";
		$pat_cellphone="/(手机|手机号)<\/span><em>\s*：(.*?)<\/em>/";
		$pat_address="/经营地址<\/span><em>：(.*?)<\/em>|经营地址：<\/td>\s*<td align=\"left\">(.*?)<\/td>/";
		$pat_intruduce="/&nbsp;\s+?<\/div>([\S\s]*?)<\/div>|class=\"company-words\">\s*<p>(.*)<\/p>/";

		preg_match_all($pat_linkman,$output_company,$linkman);
		preg_match_all($pat_telephone,$output_company,$telephone);
		preg_match_all($pat_cellphone,$output_company,$cellphone);
		preg_match_all($pat_address,$output_company,$address);
		preg_match_all($pat_intruduce,$output_company,$intruduce);

		if(!empty($linkman[1][0])){
			$hc_linkman =$linkman[1][0];
		}elseif(!empty($linkman[2][0])){
			$hc_linkman= $linkman[2][0];			
		}
		echo $hc_linkman;
		echo "<hr>";
		$hc_telephone=$telephone[1][0];
		echo $hc_telephone;
		echo "<hr>";

		$hc_cellphone=$cellphone[2][0];
		echo $hc_cellphone;
		echo "<hr>";

		if(!empty($address[1][0])){
			$hc_address =$address[1][0];
		}elseif(!empty($address[2][0])){
			$hc_address= $address[2][0];			
		}

		echo $hc_address;
		echo "<hr>";

		if(!empty($intruduce[1][0])){
			$hc_intruduce =$intruduce[1][0];
		}elseif(!empty($intruduce[2][0])){
			$hc_intruduce= $intruduce[2][0];			
		}

		echo $hc_intruduce;
		echo "<hr>";
		exit;


		$insertData=new UserModel("b2b_hc_keyword");
		for($i=0;$i<count($type_links[0]);$i++){
			$sql="insert ignore into b2b_hc_keyword(`pid`,`name`,`alias`,`kid`,`link`) values(0,'{$type_links[1][$i]}','{$type_links[3][$i]}',".($i+10).",'".urldecode($type_links[2][$i])."')";
			$result=$insertData->insertData($sql);
			if(!$result){
				echo "the data exitent";
				continue;
			}else{
				echo "insert one data<br>";
				echo "<br>";
				$sql=iconv("utf-8","gb2312",$sql);
				echo $sql;
				echo "<br>";
			}
		}

		// var_dump($type_links);


	}

}