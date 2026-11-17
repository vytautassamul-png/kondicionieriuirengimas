<?php
declare(strict_types=1);
require dirname(__DIR__) . '/lib/bootstrap.php';
start_site_session('ki_admin_session');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: no-referrer');

$tabKeys = [
  'pradinis'=>'Pradinis puslapis',
  'paslaugos'=>'Paslaugos',
  'sildymas'=>'Šilumos siurbliai',
  'visi_darbai'=>'Visos paslaugos',
  'paketai'=>'Sprendimų variantai',
  'kainos'=>'Kainos',
  'gree'=>'Gree Multi-Split',
  'eiga'=>'Darbo eiga',
  'duk'=>'DUK',
  'kontaktai'=>'Kontaktai ir SEO',
  'seo'=>'SEO straipsniai',
  'nuotraukos'=>'Nuotraukos',
  'telegram'=>'Telegram',
  'gtm'=>'Google Tag Manager',
  'saugumas'=>'Saugumas',
];
$schema = require dirname(__DIR__) . '/lib/schema.php';
$tab = (string)($_GET['tab'] ?? 'pradinis');
if (!isset($tabKeys[$tab])) $tab='pradinis';
$authFile=PRIVATE_ROOT.'/admin-auth.php';
$auth=require $authFile;
$loggedIn = !empty($_SESSION['admin_ok']) && (int)($_SESSION['last_seen'] ?? 0)>time()-8*3600;
if (!$loggedIn) { unset($_SESSION['admin_ok'],$_SESSION['last_seen']); }
$msg='';$type='success';$foundChats=[];
$act = is_string($_POST['action'] ?? null) ? $_POST['action'] : '';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    if (!csrf_ok()) { http_response_code(400); $msg='Saugumo patikra nepavyko. Atnaujinkite puslapį.'; $type='error'; }
    elseif ($act==='login' && !$loggedIn) {
        if (!rate_allow('admin-login',8,900)) { $msg='Per daug bandymų. Bandykite po 15 minučių.'; $type='error'; }
        elseif (password_verify((string)($_POST['password'] ?? ''),(string)$auth['hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_ok']=true; $_SESSION['last_seen']=time();
            header('Location: index.php?tab='.(empty($auth['must_change'])?'pradinis':'saugumas'),true,303); exit;
        } else { $msg='Neteisingas slaptažodis.'; $type='error'; }
    }
    elseif ($act==='logout' && $loggedIn) {
        $_SESSION=[]; session_destroy(); header('Location: index.php',true,303);exit;
    }
    elseif ($loggedIn) {
        if ($act==='password') {
            $old=(string)($_POST['old_password'] ?? '');$new=(string)($_POST['new_password'] ?? '');$again=(string)($_POST['repeat_password'] ?? '');
            if (!password_verify($old,(string)$auth['hash'])) { $msg='Dabartinis slaptažodis neteisingas.'; $type='error'; }
            elseif (strlen($new)<12 || strlen($new)>128) { $msg='Naujas slaptažodis turi būti 12–128 simbolių.';$type='error'; }
            elseif ($new!==$again) { $msg='Nauji slaptažodžiai nesutampa.';$type='error'; }
            else {
                if (save_php_data($authFile,['hash'=>password_hash($new,PASSWORD_DEFAULT),'must_change'=>false])) {
                    $auth=['must_change'=>false]; $msg='Slaptažodis pakeistas. Nuo šiol naudokite naują.';
                } else { $msg='Nepavyko išsaugoti. Patikrinkite private/ katalogo rašymo teises.'; $type='error'; }
            }
            $tab='saugumas';
        }
        elseif (!empty($auth['must_change'])) { $msg='Pirmiausia pakeiskite pradinį slaptažodį.'; $type='error'; $tab='saugumas'; }
        elseif ($act==='save_content') {
            $group=(string)($_POST['group'] ?? '');
            if (!isset($schema[$group]) || !in_array($group,$tabKeys,true)) {
                $msg='Nežinoma turinio grupė.'; $type='error';
            } else {
                $current=site_content(); $errors=[];
                foreach ($schema[$group] as $field) {
                    $key=$field['key'];$kind=$field['type'];
                    if ($kind==='image') {
                        if (!empty($_POST['remove_'.$key])) { $current[$key]=''; }
                        if (isset($_FILES['upload_'.$key]) && $_FILES['upload_'.$key]['error'] !== UPLOAD_ERR_NO_FILE) {
                            $file=$_FILES['upload_'.$key];
                            if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 5*1024*1024 || $file['size'] <= 0) {
                                $errors[]=$field['label'].': failas per didelis arba nepavyko įkelti.'; continue;
                            }
                            $tmp=$file['tmp_name']; $size=@getimagesize($tmp); $mime=$size['mime'] ?? '';
                            $ext=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'][$mime] ?? '';
                            if (!$ext || $size[0]>8000 || $size[1]>8000 || !$size[0] || !$size[1]) {
                                $errors[]=$field['label'].': galima tik JPG, PNG, WEBP iki 5 MB.';continue;
                            }
                            $name='uploads/'.bin2hex(random_bytes(12)).'.'.$ext;
                            if (!move_uploaded_file($tmp,SITE_ROOT.'/'.$name)) { $errors[]=$field['label'].': nepavyko įrašyti į uploads/.';continue; }
                            @chmod(SITE_ROOT.'/'.$name,0644); $current[$key]=$name;
                        }
                    } else {
                        $v=$_POST[$key] ?? null;
                        if (!is_string($v) || strlen($v)>18000 || !preg_match('//u',$v)) { $errors[]=$field['label'].': netinkamas tekstas.'; continue; }
                        $current[$key]=trim(str_replace("\r",'', $v));
                    }
                }
                if (!$errors && $group==='Kontaktai ir SEO' && $current['email']!=='' && !filter_var($current['email'],FILTER_VALIDATE_EMAIL)) $errors[]='Neteisingas el. pašto adresas.';
                if (!$errors && !save_php_data(PRIVATE_ROOT.'/content.php',$current)) $errors[]='Serveris negali išsaugoti pakeitimų. Patikrinkite private/ teises.';
                if ($errors) { $msg=implode(' ', $errors);$type='error'; } else { $msg='Pakeitimai sėkmingai išsaugoti.'; }
                $tab=(string)(array_search($group,$tabKeys,true) ?: 'pradinis');
            }
        }
        elseif ($act==='save_telegram') {
            $existing=telegram_settings();
            $newToken=trim((string)($_POST['bot_token'] ?? ''));
            $chat=trim((string)($_POST['chat_id'] ?? ''));
            $thread=trim((string)($_POST['message_thread_id'] ?? ''));
            if (!empty($_POST['clear_token'])) $existing['bot_token']='';
            elseif ($newToken!=='') $existing['bot_token']=$newToken;
            $existing['chat_id']=$chat;
            $existing['message_thread_id']=$thread;
            if ($existing['bot_token']!=='' && !preg_match('/^\d+:[A-Za-z0-9_-]{30,}$/D',$existing['bot_token'])) {
                $msg='Boto raktas neteisingo formato.';$type='error';
            } elseif ($chat!=='' && !preg_match('/^-?\d+$/D',$chat)) {
                $msg='Chat ID turi būti skaičius (grupės ID gali būti neigiamas).';$type='error';
            } elseif ($thread!=='' && !preg_match('/^\d+$/D',$thread)) {
                $msg='Grupės temos ID turi būti teigiamas skaičius.';$type='error';
            } elseif (save_php_data(PRIVATE_ROOT.'/telegram.php',$existing)) { $msg='Telegram nustatymai išsaugoti. Dabar išsiųskite bandomąją žinutę.'; }
            else { $msg='Nepavyko išsaugoti. Patikrinkite private/ teises.';$type='error'; }
            $tab='telegram';
        }
        elseif ($act==='save_gtm') {
            $id = strtoupper(trim((string)($_POST['container_id'] ?? '')));
            if ($id !== '' && !preg_match('/^GTM-[A-Z0-9]{4,20}$/D', $id)) {
                $msg='Įrašykite taisyklingą GTM ID, pvz., GTM-ABC1234. Kiti kodai čia netinka.'; $type='error';
            } elseif (save_php_data(PRIVATE_ROOT.'/gtm.php', ['container_id'=>$id])) {
                $msg=$id === '' ? 'Google Tag Manager išjungtas.' : 'Google Tag Manager išsaugotas: '.$id;
            } else {
                $msg='Nepavyko išsaugoti GTM ID. Patikrinkite private/ katalogo rašymo teises.'; $type='error';
            }
            $tab='gtm';
        }
        elseif ($act==='test_telegram') {
            $result=send_telegram_message('✅ <b>Kondicionieriuirengimas.lt</b>'."\n".'Bandomoji žinutė iš administravimo panelės.'."\n".'Laikas: '.date('Y-m-d H:i:s'));
            if ($result['ok']) { $msg='Bandomoji žinutė išsiųsta į Telegram!'; }
            else { $msg='Siuntimo klaida: '.$result['reason']; $type='error'; }
            $tab='telegram';
        }
        elseif ($act==='find_chats') {
            $result=telegram_api('getUpdates',['limit'=>'30','timeout'=>'0']);
            if (!$result['ok']) { $msg='Nepavyko gauti pokalbių: '.$result['reason'];$type='error'; }
            else {
                foreach (($result['data'] ?? []) as $update) {
                    $m=$update['message'] ?? $update['edited_message'] ?? $update['my_chat_member'] ?? null;
                    if (!is_array($m) || empty($m['chat']['id'])) continue;
                    $chat=$m['chat'];$id=(string)$chat['id'];
                    $foundChats[$id]=$id.' — '.($chat['title'] ?? trim(($chat['first_name'] ?? '').' '.($chat['last_name'] ?? '')) ?: 'Pokalbis');
                }
                $msg = $foundChats ? 'Galimi pokalbiai rasti (žr. žemiau).' : 'Pokalbių nerasta. Telegram botui parašykite /start ir bandykite dar kartą. Jei naudojate webhook, getUpdates gali neveikti.';
            }
            $tab='telegram';
        }
    }
}
$loggedIn = !empty($_SESSION['admin_ok']);
if ($loggedIn) $_SESSION['last_seen']=time();
if ($loggedIn && !empty($auth['must_change'])) $tab='saugumas';
$c=site_content();$tg=telegram_settings();$gtmId=gtm_container_id();
$groups=array_flip($tabKeys);
?>
<!doctype html><html lang="lt"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="robots" content="noindex,nofollow"><title>Administravimas | Kondicionierių Įrengimas</title><link rel="icon" href="../assets/favicon.svg" type="image/svg+xml"><link rel="stylesheet" href="admin.css?v=2"></head><body>
<?php if (!$loggedIn): ?>
<div class="login-screen"><div class="login-card"><div class="logo-mark">❄</div><span class="kicker">KONDICIONIERIUIRENGIMAS.LT</span><h1>Administravimas</h1><p>Prisijunkite, kad redaguotumėte svetainės turinį ir Telegram nustatymus.</p><?php if ($msg): ?><div class="alert <?= h($type) ?>"><?= h($msg) ?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>"><input type="hidden" name="action" value="login"><label>Slaptažodis<input name="password" type="password" required autocomplete="current-password" autofocus></label><button class="button primary" type="submit">Prisijungti <span>↗</span></button></form><a class="small-link" href="../">← Atgal į svetainę</a></div></div>
<?php else: ?>
<div class="admin-shell">
<aside class="sidebar"><div class="sidebar-head"><span class="logo-mark">❄</span><div><b>ĮRENGIMAS.LT</b><small>Valdymo skydelis</small></div></div><nav class="sidebar-nav" aria-label="Administravimo skyriai"><?php foreach ($tabKeys as $key=>$label): ?><a class="<?= $tab===$key?'active':'' ?>" href="?tab=<?= h($key) ?>"><span class="nav-icon"><?= ['pradinis'=>'⌂','paslaugos'=>'▣','sildymas'=>'◉','visi_darbai'=>'⚒','paketai'=>'≋','kainos'=>'€','gree'=>'❄','eiga'=>'↔','duk'=>'?','kontaktai'=>'✉','seo'=>'✎','nuotraukos'=>'▧','telegram'=>'➤','gtm'=>'⌁','saugumas'=>'⚿'][$key] ?></span><?= h($label) ?></a><?php endforeach; ?></nav><div class="sidebar-foot"><a href="../" target="_blank" rel="noopener">Peržiūrėti svetainę ↗</a><form method="post"><input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>"><input type="hidden" name="action" value="logout"><button type="submit">Atsijungti</button></form></div></aside>
<main class="main"><div class="mobile-head"><b>ĮRENGIMAS.LT</b><a href="../" target="_blank" rel="noopener">Svetainė ↗</a></div><header class="page-header"><div><div class="kicker">SVETAINĖS VALDYMAS</div><h1><?= h($tabKeys[$tab]) ?></h1><p><?= $tab==='telegram'?'Įdiekite ir patikrinkite užklausų gavimą Telegram botu.':($tab==='gtm'?'Įveskite Google Tag Manager konteinerio ID be papildomo kodo.':($tab==='saugumas'?'Apsaugokite administravimo prieigą.':'Pakeitimai iš karto matomi viešoje svetainėje.')) ?></p></div><a class="visit" href="../" target="_blank" rel="noopener">Atidaryti puslapį ↗</a></header>
<?php if ($msg): ?><div class="alert <?= h($type) ?>"><?= h($msg) ?></div><?php endif; ?>
<?php if (!empty($auth['must_change']) && $tab==='saugumas'): ?><div class="alert warn"><strong>Privalomas žingsnis:</strong> pakeiskite pradinį administratoriaus slaptažodį prieš redaguodami svetainę.</div><?php endif; ?>
<?php if (isset($schema[$tabKeys[$tab]]) && empty($auth['must_change'])): ?>
<section class="panel"><form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>"><input type="hidden" name="action" value="save_content"><input type="hidden" name="group" value="<?= h($tabKeys[$tab]) ?>"><div class="fields">
<?php foreach ($schema[$tabKeys[$tab]] as $field): $key=$field['key'];$value=(string)($c[$key]??''); ?>
<div class="field"><label for="<?= h($key) ?>"><?= h($field['label']) ?></label><?php if (!empty($field['help'])): ?><div class="hint"><?= h($field['help']) ?></div><?php endif; ?>
<?php if ($field['type']==='textarea'): ?><textarea id="<?= h($key) ?>" name="<?= h($key) ?>" rows="<?= str_contains($key,'features')?5:3 ?>" maxlength="18000"><?= h($value) ?></textarea>
<?php elseif ($field['type']==='image'): ?>
<div class="upload"><div class="preview"><?php if (public_image($value)): ?><img src="../<?= h($value) ?>" alt="<?= h($field['label']) ?>"><?php else: ?><span>Nuotraukos nėra</span><?php endif; ?></div><input type="file" id="<?= h($key) ?>" name="upload_<?= h($key) ?>" accept="image/jpeg,image/png,image/webp"><label class="remove"><input type="checkbox" name="remove_<?= h($key) ?>" value="1"> Pašalinti nuotrauką</label></div>
<?php else: ?><input type="text" id="<?= h($key) ?>" name="<?= h($key) ?>" value="<?= h($value) ?>" maxlength="18000"><?php endif; ?></div>
<?php endforeach; ?></div><div class="savebar"><span>Atnaujinus puslapį bus matoma nauja versija.</span><button type="submit" class="button primary">Išsaugoti pakeitimus ✓</button></div></form></section>
<?php elseif ($tab==='telegram' && empty($auth['must_change'])): ?>
<section class="panel"><div class="panel-title"><h2>Telegram prijungimas</h2><p>Boto raktas saugomas serveryje, o ne viešame puslapio kode.</p></div><div class="help-box"><b>Kaip prijungti:</b><ol><li>Telegram programėlėje atidarykite <b>@BotFather</b> ir sukurkite botą naudodami <b>/newbot</b>.</li><li>Atidarykite sukurtą botą, paspauskite <b>Start</b> ir nusiųskite <b>/start</b>. Jei užklausų reikia grupėje – pridėkite botą į grupę ir ten parašykite komandą.</li><li>Čia įrašykite gautą <b>Bot Token</b>, išsaugokite ir spauskite <b>Surasti Chat ID</b>.</li><li>Įrašykite pokalbio ID, išsaugokite ir paspauskite <b>Siųsti bandomąją žinutę</b>.</li></ol></div>
<form method="post"><input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>"><input type="hidden" name="action" value="save_telegram"><div class="fields"><div class="field"><label for="token">Bot Token</label><div class="hint"><?= $tg['bot_token']!==''?'Raktas yra išsaugotas. Palikite tuščią, kad jo nekeistumėte.':'Boto raktas dar nenustatytas.' ?></div><input type="password" id="token" name="bot_token" autocomplete="new-password" placeholder="Pvz. 123456789:ABC..."><label class="remove"><input type="checkbox" name="clear_token" value="1"> Ištrinti įrašytą boto raktą</label></div><div class="field"><label for="chat">Chat ID</label><input type="text" id="chat" name="chat_id" value="<?= h($tg['chat_id']) ?>" placeholder="Pvz. 123456789 arba -100..." autocomplete="off"></div><div class="field"><label for="thread">Temos ID (neprivalomas)</label><input type="text" id="thread" name="message_thread_id" value="<?= h($tg['message_thread_id']) ?>" placeholder="Tik grupių temoms"><div class="hint">Jei siunčiate į asmeninį pokalbį ar bendrą grupę, palikite tuščią.</div></div></div><div class="savebar"><span>Svarbu: prieš bandymą pirmiausia išsaugokite nustatymus.</span><button class="button primary" type="submit">Išsaugoti Telegram</button></div></form></section>
<section class="panel"><h2>Ryšio patikrinimas</h2><p class="muted">Pirmiausia išsiųskite botui /start. Tada galite nuskaityti paskutinių pranešimų pokalbių ID ir išsiųsti testą.</p><div class="button-row"><form method="post"><input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>"><input type="hidden" name="action" value="find_chats"><button class="button secondary" type="submit">Surasti Chat ID</button></form><form method="post"><input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>"><input type="hidden" name="action" value="test_telegram"><button class="button primary" type="submit">Siųsti bandomąją žinutę ↗</button></form></div><?php if ($foundChats): ?><div class="chat-results"><h3>Rasti pokalbiai:</h3><?php foreach ($foundChats as $chat): ?><p><?= h($chat) ?></p><?php endforeach; ?></div><?php endif; ?></section>
<?php elseif ($tab==='gtm' && empty($auth['must_change'])): ?>
<section class="panel">
  <div class="panel-title"><h2>Google Tag Manager</h2><p>Vieną kartą įrašykite konteinerio ID. Svetainė pati įterps reikalingą kodą į viešų puslapių HEAD ir BODY.</p></div>
  <div class="help-box"><b>Kaip naudoti:</b><ol>
    <li>Google Tag Manager paskyroje atidarykite savo <b>Web</b> konteinerį.</li>
    <li>Nukopijuokite jo ID, prasidedantį <b>GTM-</b> (ne GA4 matavimo ID <b>G-</b>).</li>
    <li>Įrašykite žemiau ir paspauskite <b>Išsaugoti GTM</b>.</li>
    <li>Patikrinkite su <b>Google Tag Assistant</b> peržiūros režimu. Analitikos ir reklamų žymas konfigūruokite pačiame GTM.</li>
  </ol></div>
  <form method="post">
    <input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>">
    <input type="hidden" name="action" value="save_gtm">
    <div class="fields"><div class="field"><label for="container_id">Google Tag Manager ID</label>
      <input type="text" id="container_id" name="container_id" value="<?= h($gtmId) ?>" placeholder="GTM-ABCDEFG" maxlength="24" pattern="GTM-[A-Za-z0-9]{4,20}" autocapitalize="characters" autocomplete="off" spellcheck="false">
      <div class="hint"><?= $gtmId!==''?'Dabar prijungtas konteineris: '.h($gtmId):'GTM dar neįjungtas.' ?> Palikite tuščią ir išsaugokite, jei norite GTM išjungti.</div>
    </div></div>
    <div class="savebar"><span>Administravimo puslapis nėra sekamas. Užklausų siuntimas per Telegram nesikeičia.</span><button class="button primary" type="submit">Išsaugoti GTM ✓</button></div>
  </form>
</section>
<?php elseif ($tab==='saugumas'): ?><section class="panel"><h2>Pakeisti prisijungimo slaptažodį</h2><p class="muted">Naudokite ne trumpesnį nei 12 simbolių unikalų slaptažodį.</p><form method="post" class="password-form"><input type="hidden" name="csrf" value="<?= h(csrf_token()) ?>"><input type="hidden" name="action" value="password"><div class="field"><label>Dabartinis slaptažodis</label><input type="password" name="old_password" required autocomplete="current-password"></div><div class="field"><label>Naujas slaptažodis</label><input type="password" name="new_password" minlength="12" maxlength="128" required autocomplete="new-password"></div><div class="field"><label>Pakartokite naują slaptažodį</label><input type="password" name="repeat_password" minlength="12" maxlength="128" required autocomplete="new-password"></div><button class="button primary" type="submit">Pakeisti slaptažodį</button></form></section><?php endif; ?>
<footer class="admin-foot">kondicionieriuirengimas.lt • PHP administravimo panelė • <?= date('Y') ?></footer></main></div>
<?php endif; ?>
</body></html>