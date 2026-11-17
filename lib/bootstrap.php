<?php
declare(strict_types=1);

const SITE_ROOT = __DIR__ . '/..';
const PRIVATE_ROOT = SITE_ROOT . '/private';

function h(mixed $value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function text_lines(string $text): array {
    return array_values(array_filter(array_map('trim', preg_split('/\R/u', $text) ?: []), fn($line) => $line !== ''));
}
function site_content(): array {
    $defaults = require __DIR__ . '/defaults.php';
    $file = PRIVATE_ROOT . '/content.php';
    $custom = is_file($file) ? require $file : [];
    // Pirmojo diegimo admin skydelis saugo visus buvusius numatytuosius tekstus.
    // Atnaujiname tik tiksliai su sena versija sutampančias kopijas; savo redaguotus tekstus paliekame.
    if (!is_array($custom)) $custom = [];
    $oldRegionalDefaults = [
        'hero_eyebrow' => 'VILNIUS IR APLINKINĖS TERITORIJOS',
        'area' => 'Vilnius ir kiti Lietuvos miestai pagal susitarimą.',
        'address_caption' => 'Vilnius • Lietuva',
        'contact_text' => 'Palikite kontaktus ir trumpai aprašykite, ko reikia. Pateiksime pasiūlymą konkrečiai jūsų patalpai.',
        'hero_v2_description' => 'Kondicionierių pardavimas, montavimas ir parinkimas butams, namams bei biurams Vilniuje. Įvertiname patalpas, pasiūlome tinkamą įrangą ir pasirūpiname jos paleidimu.',
        'hero_winter_description' => 'Kondicionierių ir šilumos siurblių pardavimas, montavimas, remontas bei priežiūra Vilniuje. Šildymas žiemą, vėsinimas vasarą – padedame parinkti sprendimą butui, namui ar biurui.',
        'seo_title' => 'Kondicionierių montavimas Vilniuje ir Lietuvoje | Kondicionierių Įrengimas',
        'seo_description' => 'Kondicionierių pardavimas ir montavimas Vilniuje bei Lietuvoje. Gree Multi-Split dviem kambariams nuo 1 400 €, kondicionieriai su montavimu nuo 700 €. Gaukite pasiūlymą.',
        'seo_og_description' => 'Kondicionieriai ir montavimas iš vienų rankų. Aiškios kainos ir profesionalus įrengimas.',
        'seo_winter_title' => 'Kondicionieriai ir šilumos siurbliai Vilniuje | Montavimas, remontas',
        'seo_winter_description' => 'Kondicionieriai ir šilumos siurbliai oras–oras Vilniuje: pardavimas, montavimas, priežiūra, valymas, remontas. Konsultacijos dėl oras–vanduo ir rekuperacijos. Gaukite pasiūlymą.',
        'seo_article1_p1' => 'Kondicionierių montavimas Vilniuje prasideda nuo poreikio įvertinimo: kiek patalpų reikia vėsinti ar šildyti, koks jų plotas ir išplanavimas, kur bus vidinis bei išorinis blokai. Kai žinome objekto ypatybes, galime pasiūlyti tinkamą „Split“ arba „Multi-Split“ sprendimą ir aiškiai įvardyti, kokie darbai numatyti sąmatoje.',
        'seo_local_title' => 'Kondicionierių montavimas Vilniuje ir aplinkiniuose rajonuose',
        'seo_local_lead' => 'Konsultuojame dėl įrengimo butuose, namuose ir komercinėse patalpose. Užsakymo galimybes bei atvykimo sąlygas suderiname individualiai.',
        'seo_local_text' => 'Jei ieškote kondicionierių montavimo Vilniuje – Žirmūnuose, Šnipiškėse, Naujamiestyje, Pilaitėje, Pašilaičiuose, Lazdynuose, Antakalnyje, Fabijoniškėse ar kituose rajonuose – atsiųskite užklausą su adresu ir trumpu objekto aprašymu. Pasiteirauti galite ir dėl Vilniaus rajono ar kitų Lietuvos miestų: galimybes, terminus bei galimą atvykimo mokestį patvirtinsime prieš darbų pradžią.',
        'work_section_lead' => 'Vienoje vietoje galite kreiptis tiek dėl naujos sistemos, tiek dėl jau naudojamos įrangos priežiūros ar gedimo. Konkretų darbų sąrašą, reikalingas medžiagas ir kainą suderiname įvertinę įrenginį bei objektą.',
    ];
    // Paskutinio (2026-11-17) paketo šabloniniai tekstai. Administratoriaus pakeitimai išlieka.
    $lastReleaseDefaults = [
        'hero_winter_title' => 'Kondicionieriai ir šilumos siurbliai Vilniuje',
        'hero_winter_description' => 'Kondicionierių ir šilumos siurblių pardavimas, montavimas, remontas bei priežiūra Vilniuje ir visoje Lietuvoje. Šildymas žiemą, vėsinimas vasarą – pasirūpiname sprendimais įvairiuose Lietuvos miestuose.',
        'seo_title' => 'Kondicionierių montavimas Vilniuje ir visoje Lietuvoje | Šilumos siurbliai',
        'seo_description' => 'Kondicionierių ir šilumos siurblių pardavimas, montavimas, priežiūra bei remontas Vilniuje ir visoje Lietuvoje. Pasiūlymas pagal jūsų objektą.',
        'seo_og_description' => 'Kondicionieriai ir šilumos siurbliai Vilniuje bei visoje Lietuvoje: parinkimas, pardavimas, montavimas, remontas ir priežiūra.',
        'form_intro' => 'Palikite užklausą – susisieksime dėl detalių.',
        'seo_local_title' => 'Kondicionierių montavimas Vilniuje ir visoje Lietuvoje',
        'seo_local_lead' => 'Dirbame Vilniuje, Vilniaus rajone ir visoje Lietuvoje. Dėl įrengimo butuose, namuose ar komercinėse patalpose galite kreiptis iš bet kurio miesto – atvykimą ir darbų terminus suderinsime iš anksto.',
        'seo_local_text' => 'Vilniuje kondicionierius montuojame Žirmūnuose, Šnipiškėse, Naujamiestyje, Pilaitėje, Pašilaičiuose, Lazdynuose, Antakalnyje, Fabijoniškėse ir kituose miesto rajonuose. Taip pat dirbame Kaune, Klaipėdoje, Šiauliuose, Panevėžyje ir kituose Lietuvos miestuose bei rajonuose. Nesvarbu, kur yra jūsų objektas – atsiųskite užklausą, nurodykite miestą, patalpų plotą bei pageidaujamus darbus. Atsakysime dėl galimo atvykimo, montavimo sąlygų ir individualios sąmatos.',
        'seo_winter_title' => 'Kondicionieriai ir šilumos siurbliai Vilniuje | Visa Lietuva',
        'seo_winter_description' => 'Kondicionierių ir šilumos siurblių montavimas, pardavimas, remontas bei priežiūra Vilniuje ir visoje Lietuvoje. Susisiekite dėl pasiūlymo.',
        'hero_eyebrow' => 'VILNIUS IR VISA LIETUVA',
        'area' => 'Dirbame Vilniuje ir visoje Lietuvoje. Atvykimą ir darbų terminus suderiname individualiai.',
        'address_caption' => 'Vilnius · Visa Lietuva',
        'contact_text' => 'Palikite kontaktus ir trumpai aprašykite, kokių darbų reikia. Dirbame Vilniuje ir visoje Lietuvoje – pasiūlymą pateiksime pagal jūsų objektą ir sutarsime atvykimo sąlygas.',
        'work_section_lead' => 'Vilniuje ir visoje Lietuvoje galite kreiptis tiek dėl naujos sistemos, tiek dėl jau naudojamos įrangos priežiūros ar gedimo. Konkretų darbų sąrašą, reikalingas medžiagas ir kainą suderiname įvertinę įrenginį bei objektą.',

    ];
    $previousNationwideDefaults = [
        'hero_winter_title' => 'Kondicionieriai ir šilumos siurbliai Vilniuje ir visoje Lietuvoje',
        'hero_eyebrow' => 'VILNIUS IR VISA LIETUVA',
    ];
    foreach ([$oldRegionalDefaults, $lastReleaseDefaults, $previousNationwideDefaults] as $previousDefaults) {
        foreach ($previousDefaults as $key => $previousValue) {
            if (isset($custom[$key]) && $custom[$key] === $previousValue && array_key_exists($key, $defaults)) {
                $custom[$key] = $defaults[$key];
            }
        }
    }
    if (($custom['hero_eyebrow'] ?? '') === 'VILNIUS • VISA LIETUVA') { $custom['hero_eyebrow'] = $defaults['hero_eyebrow']; }
    return array_replace($defaults, $custom);
}
function save_php_data(string $path, array $value): bool {
    if (!str_starts_with($path, PRIVATE_ROOT . '/')) return false;
    $text = "<?php\ndeclare(strict_types=1);\nreturn " . var_export($value, true) . ";\n";
    $temp = tempnam(dirname($path), 'save_');
    if ($temp === false) return false;
    @chmod($temp, 0600);
    if (file_put_contents($temp, $text, LOCK_EX) === false) { @unlink($temp); return false; }
    $ok = @rename($temp, $path);
    if (!$ok) @unlink($temp);
    if ($ok && function_exists('opcache_invalidate')) @opcache_invalidate($path, true);
    return $ok;
}
/** Vienodas ženklo HTML navigacijoje ir puslapio apačioje. */
function site_brand(string $href = '#top', string $className = ''): string {
    $classes = trim('match-brand ' . $className);
    return '<a class="' . h($classes) . '" href="' . h($href) . '" aria-label="Kondicionierių įrengimas – pradžia">'
        . '<span class="match-snow" aria-hidden="true">❄</span>'
        . '<span><strong>Kondicionierių įrengimas</strong><small>Montavimas · Pardavimas · Priežiūra</small></span></a>';
}
function phone_uri(string $number): string {
    return preg_replace('/[^+0-9]/', '', $number) ?? '';
}
function public_image(string $path): bool {
    return preg_match('~^uploads/[a-f0-9]{24}\.(?:jpg|png|webp)$~D', $path) === 1 && is_file(SITE_ROOT . '/' . $path);
}
function site_schema(array $c): array {
    return [
        '@context'=>'https://schema.org','@type'=>'Service',
        'name'=>'Kondicionierių pardavimas ir montavimas',
        'serviceType'=>'Kondicionierių montavimas',
        'areaServed'=>['@type'=>'Country','name'=>'Lithuania'],
        'provider'=>['@type'=>'Organization','name'=>$c['site_name'], 'url'=>'https://kondicionieriuirengimas.lt/','telephone'=>$c['phone'],'email'=>$c['email']],
        'offers'=>['@type'=>'Offer','priceCurrency'=>'EUR','price'=>preg_replace('/[^0-9]/','',$c['price3_price']), 'description'=>$c['price3_hint']],
    ];
}
/** Returns a validated Google Tag Manager container ID; empty when not configured. */
function gtm_container_id(): string {
    $file = PRIVATE_ROOT . '/gtm.php';
    $saved = is_file($file) ? require $file : [];
    $value = is_array($saved) ? (string)($saved['container_id'] ?? '') : '';
    return preg_match('/^GTM-[A-Z0-9]{4,20}$/D', $value) ? $value : '';
}
function gtm_head_snippet(): string {
    $id = gtm_container_id();
    if ($id === '') return '';
    // Standard Google Tag Manager web container snippet. ID is strictly validated above.
    return "<!-- Google Tag Manager -->\n" .
       "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});" .
       "var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';" .
       "j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;" .
       "f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','" . $id . "');</script>\n<!-- End Google Tag Manager -->\n";
}
function gtm_body_snippet(): string {
    $id = gtm_container_id();
    if ($id === '') return '';
    return '<!-- Google Tag Manager (noscript) --><noscript><iframe src="https://www.googletagmanager.com/ns.html?id=' . $id . '" height="0" width="0" style="display:none;visibility:hidden" title="Google Tag Manager"></iframe></noscript><!-- End Google Tag Manager (noscript) -->';
}
function telegram_settings(): array {
    $file = PRIVATE_ROOT . '/telegram.php';
    $saved = is_file($file) ? require $file : [];
    return array_replace(['bot_token'=>'','chat_id'=>'','message_thread_id'=>''], is_array($saved) ? $saved : []);
}
function telegram_api(string $method, array $params): array {
    $settings = telegram_settings();
    $token = (string) $settings['bot_token'];
    if (!preg_match('/^\d+:[A-Za-z0-9_-]{30,}$/D', $token)) {
        return ['ok'=>false,'reason'=>'Neteisingas arba neįrašytas boto raktas.'];
    }
    $url = 'https://api.telegram.org/bot'.$token.'/'.$method;
    $body = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
    $code = 0; $response = false;
    if (function_exists('curl_init')) {
        $handle = curl_init($url);
        curl_setopt_array($handle, [
            CURLOPT_POST=>true, CURLOPT_POSTFIELDS=>$body, CURLOPT_RETURNTRANSFER=>true,
            CURLOPT_CONNECTTIMEOUT=>4, CURLOPT_TIMEOUT=>12,
            CURLOPT_HTTPHEADER=>['Content-Type: application/x-www-form-urlencoded; charset=utf-8'],
            CURLOPT_SSL_VERIFYPEER=>true, CURLOPT_SSL_VERIFYHOST=>2,
        ]);
        $response = curl_exec($handle);
        $code = (int) curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);
    } elseif (filter_var(ini_get('allow_url_fopen'), FILTER_VALIDATE_BOOL)) {
        $context = stream_context_create(['http'=>[
            'method'=>'POST','header'=>"Content-Type: application/x-www-form-urlencoded; charset=utf-8\r\n",
            'content'=>$body,'timeout'=>12,'ignore_errors'=>true,
        ],'ssl'=>['verify_peer'=>true,'verify_peer_name'=>true]]);
        $response = @file_get_contents($url, false, $context);
        foreach (($http_response_header ?? []) as $header) {
            if (preg_match('~^HTTP/\S+\s+(\d{3})~', $header, $matches)) $code = (int) $matches[1];
        }
    }
    $decoded = is_string($response) ? json_decode($response, true) : null;
    if ($code !== 200 || !is_array($decoded) || ($decoded['ok'] ?? false) !== true) {
        error_log('[kondicionieriuirengimas.lt] Telegram API failure HTTP '.$code.'; error code '.(int)($decoded['error_code'] ?? 0));
        $reason = is_array($decoded) && isset($decoded['description']) ? (string)$decoded['description'] : 'Nepavyko susisiekti su Telegram API. Patikrinkite hostingą ir duomenis.';
        return ['ok'=>false,'reason'=>mb_safe_short($reason,250)];
    }
    return ['ok'=>true,'data'=>$decoded['result'] ?? null];
}
function mb_safe_short(string $text, int $max): string {
    return function_exists('mb_substr') ? mb_substr($text,0,$max,'UTF-8') : substr($text,0,$max);
}
function send_telegram_message(string $text): array {
    $settings = telegram_settings();
    if (!preg_match('/^-?\d+$/D',(string)$settings['chat_id'])) {
        return ['ok'=>false,'reason'=>'Neteisingas arba neįrašytas pokalbio (Chat ID) numeris.'];
    }
    $data=['chat_id'=>$settings['chat_id'],'text'=>$text,'parse_mode'=>'HTML','disable_web_page_preview'=>'true'];
    if (preg_match('/^\d+$/D', (string)$settings['message_thread_id'])) $data['message_thread_id']=$settings['message_thread_id'];
    return telegram_api('sendMessage',$data);
}
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_ok(): bool {
    return is_string($_POST['csrf'] ?? null) && hash_equals(csrf_token(),$_POST['csrf']);
}
function start_site_session(string $name): void {
    if (session_status() === PHP_SESSION_ACTIVE) return;
    session_name($name);
    session_set_cookie_params(['lifetime'=>0,'path'=>'/','secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off','httponly'=>true,'samesite'=>'Lax']);
    ini_set('session.use_strict_mode','1');
    session_start();
}
function rate_allow(string $scope, int $limit, int $window, string $extra=''): bool {
    // Skaičiuojame užklausas iš to paties IP. Be išorinės DB.
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $key = hash('sha256', $scope . '|' . $ip . '|' . $extra);
    $path = PRIVATE_ROOT . '/ratelimits/' . $key . '.txt';
    $fp = @fopen($path,'c+');
    if ($fp === false) return true;
    if (!flock($fp,LOCK_EX)) { fclose($fp); return true; }
    $value = stream_get_contents($fp) ?: '';
    $timestamps = array_values(array_filter(array_map('intval',explode(',',$value)),fn($t)=>$t>time()-$window));
    $allowed = count($timestamps) < $limit;
    if ($allowed) $timestamps[] = time();
    ftruncate($fp,0); rewind($fp); fwrite($fp,implode(',',$timestamps)); fflush($fp);
    flock($fp,LOCK_UN); fclose($fp);
    return $allowed;
}