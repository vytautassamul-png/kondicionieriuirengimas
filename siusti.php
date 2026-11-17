<?php
declare(strict_types=1);
require __DIR__ . '/lib/bootstrap.php';
start_site_session('ki_lead_session');
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
header('X-Content-Type-Options: nosniff');
$isAjax = str_contains((string)($_SERVER['HTTP_ACCEPT'] ?? ''),'application/json');
function respond_lead(int $code, bool $ok, string $text, bool $ajax): never {
    http_response_code($code);
    if (!$ajax && $ok) { header('Location: aciu.php',true,303);exit; }
    if (!$ajax) {
        header('Content-Type: text/html; charset=utf-8');
        echo '<!doctype html><html lang="lt"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Užklausa</title><main style="max-width:550px;margin:10vh auto;padding:20px;font:16px system-ui"><h1>Užklausos pateikimas</h1><p>'.h($text).'</p><a href="/">Grįžti į svetainę</a></main></html>';
    } else echo json_encode(['ok'=>$ok,'message'=>$text],JSON_UNESCAPED_UNICODE);
    exit;
}
function posted(string $name, int $limit=200): string {
    $v=$_POST[$name] ?? '';
    if (!is_string($v) || !preg_match('//u',$v)) return '';
    $v=trim(str_replace(["\r","\0"],'',$v));
    return mb_safe_short($v,$limit);
}
if (($_SERVER['REQUEST_METHOD']??'')!=='POST') respond_lead(405,false,'Užklausą galima siųsti tik iš formos.',$isAjax);
if ((int)($_SERVER['CONTENT_LENGTH']??0)>32768) respond_lead(413,false,'Užklausa per didelė.',$isAjax);
if (posted('website',100)!=='') respond_lead(200,true,'Ačiū.',$isAjax);
$name=posted('vardas',100);
$phone=posted('telefonas',30);
$email=posted('elpastas',120);
$city=posted('miestas',80);
$service=posted('paslauga',150);
$message=posted('zinute',1500);
if (strlen($name)<2 || !preg_match('/^[+0-9()\-\s]{7,30}$/D',$phone)) respond_lead(422,false,'Patikrinkite vardą ir telefono numerį.',$isAjax);
if ($email!=='' && !filter_var($email,FILTER_VALIDATE_EMAIL)) respond_lead(422,false,'Neteisingas el. pašto adresas.',$isAjax);
if (!rate_allow('lead',5,900)) respond_lead(429,false,'Per daug užklausų iš šio ryšio. Pabandykite vėliau arba paskambinkite.',$isAjax);
$utm=posted('utm_source',100);
$medium=posted('utm_medium',100);
$campaign=posted('utm_campaign',150);
$gclid=posted('gclid',150);
$msclkid=posted('msclkid',150);
$fbclid=posted('fbclid',150);
$referrer=posted('referrer',150);
$landing=posted('landing_page',150);
$source='Tiesioginis / nenustatytas';
if ($utm!=='') $source=$utm;
if ($gclid!=='') $source='Google Ads';
elseif ($msclkid!=='') $source='Microsoft Ads';
elseif ($fbclid!=='') $source='Facebook / Instagram (Meta)';
elseif ($utm==='' && $referrer!=='') {
    $source='Nuoroda iš '.$referrer;
    if (str_contains(strtolower($referrer),'chatgpt.com')) $source='ChatGPT (nuoroda)';
}
if (in_array(strtolower($utm),['chatgpt','chatgpt.com','openai'],true)) $source='ChatGPT (UTM)';
$esc=fn(string $v)=>h($v);
$lines=[
    '❄️ <b>NAUJA UŽKLAUSA</b>',
    '<b>kondicionieriuirengimas.lt</b>','',
    '👤 <b>Vardas:</b> '.$esc($name),
    '📞 <b>Telefonas:</b> '.$esc($phone),
    '✉️ <b>El. paštas:</b> '.$esc($email!==''?$email:'Nenurodytas'),
    '📍 <b>Miestas:</b> '.$esc($city!==''?$city:'Nenurodytas'),
    '🛠️ <b>Paslauga:</b> '.$esc($service!==''?$service:'Nenurodyta'),
    '','📝 <b>Žinutė:</b>',$esc($message!==''?$message:'Žinutės nėra'),
    '','📊 <b>Šaltinis:</b> '.$esc($source),
];
if ($medium!=='') $lines[]='<b>UTM medium:</b> '.$esc($medium);
if ($campaign!=='') $lines[]='<b>UTM campaign:</b> '.$esc($campaign);
if ($gclid!=='') $lines[]='<b>GCLID:</b> '.$esc($gclid);
if ($landing!=='') $lines[]='<b>Puslapis:</b> '.$esc($landing);
$time=new DateTimeImmutable('now',new DateTimeZone('Europe/Vilnius'));
$lines[]='🕒 <b>Laikas:</b> '.$time->format('Y-m-d H:i').' (Lietuvos laiku)';
$result=send_telegram_message(implode("\n",$lines));
if (!$result['ok']) {
    // Klientui neatskleidžiame Bot Token / Telegram API klaidų.
    respond_lead(503,false,'Šiuo metu užklausos išsiųsti nepavyko. Paskambinkite nurodytu telefonu.',$isAjax);
}
$_SESSION['lead_sent']=time();
respond_lead(200,true,'Užklausa išsiųsta.',$isAjax);