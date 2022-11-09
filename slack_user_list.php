<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slackユーザー一覧</title>
</head>
<body>

<?php
// https://api.slack.com/apps/ の OAuth & Permissions から事前に追加が必要
// 『OAuth Token』の取得、User Token Scopesに『channels:read』を追加
$API_KEY = 'xoxp-xxxxxxxxxxxxx-xxxxxxxxxxxxx-xxxxxxxxxxxxx-xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';	// 各自キーは取得
$url = "https://slack.com/api/users.list";

$headers = [
	'Authorization: Bearer '.$API_KEY,
	'Content-Type: application/x-www-form-urlencoded'
];

$options = [
    CURLOPT_URL => $url,
    CURLOPT_HTTPHEADER => $headers,
	CURLOPT_RETURNTRANSFER => true
];
$ch = curl_init();
curl_setopt_array($ch, $options);
$res = curl_exec($ch); 
curl_close($ch);

$user_list = json_decode($res);

if($user_list->ok){
	foreach($user_list->members as $v){
		if($v->id != 'USLACKBOT' && $v->deleted != true){
			$d = rawurlencode("from:<@$v->id|@$v->real_name>");
			$r = rawurlencode("from:<@$v->id>");
			$search = base64_encode('{"d":"'.$d.'","r":"'.$r.'"}');

			$url = "https://app.slack.com/client/$v->team_id/search/search-$search";
			echo "<a href=\"$url\"target=\"_blank\">$v->real_name ( $v->name )</a><br />";
		}
	}
}
?>
</body>
</html>