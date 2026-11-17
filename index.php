<?php
declare(strict_types=1);
require __DIR__ . '/lib/bootstrap.php';
$c = site_content();
header('X-Content-Type-Options: nosniff');
?>
<!doctype html>
<html lang="lt">
<head>
  <?= gtm_head_snippet() ?>  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="theme-color" content="#082c45">
  <meta name="description" content="<?= h($c['seo_winter_description']) ?>">
  <meta property="og:type" content="website">
  <meta property="og:locale" content="lt_LT">
  <meta property="og:title" content="<?= h($c['seo_winter_title']) ?>">
  <meta property="og:description" content="<?= h($c['seo_og_description']) ?>">
  <meta property="og:url" content="https://kondicionieriuirengimas.lt/">
  <meta property="og:image" content="https://kondicionieriuirengimas.lt/assets/og-cover.png">
  <link rel="canonical" href="https://kondicionieriuirengimas.lt/">
  <link rel="icon" type="image/svg+xml" href="assets/favicon.svg">
  <link rel="stylesheet" href="assets/style.css?v=1">
  <link rel="stylesheet" href="assets/refresh.css?v=3">
  <link rel="stylesheet" href="assets/match.css?v=2">
  <link rel="stylesheet" href="assets/season.css?v=4">
  <title><?= h($c['seo_winter_title']) ?></title>
  <script type="application/ld+json"><?= json_encode(site_schema($c), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
</head>
<body>
  <?= gtm_body_snippet() ?>
  <a class="skip-link" href="#main">Pereiti prie turinio</a>
  <header class="site-header match-header" id="top">
    <div class="container match-nav">
      <?= site_brand() ?>
      <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="nav-links" aria-label="Atidaryti meniu"><span></span><span></span><span></span></button>
      <nav class="nav-links match-links" id="nav-links" aria-label="Pagrindinis meniu"><a class="match-current" href="#top">Pagrindinis</a><a href="#paslaugos">Paslaugos</a><a href="#kainos">Kainos</a><a href="#montavimas-vilniuje">Apie mus</a><a href="#duk">DUK</a><a href="#susisiekime">Kontaktai</a></nav>
      <a class="match-phone" href="tel:<?= h(phone_uri($c['phone'])) ?>" aria-label="Skambinti"><span aria-hidden="true">☎</span> <?= h($c['phone']) ?></a>
      <a class="btn btn-primary match-nav-cta" href="#kontaktai"><span aria-hidden="true">➤</span> Gauti pasiūlymą</a>
    </div>
  </header>
  <main id="main">
    <section class="match-hero" aria-labelledby="hero-main-title">
      <div class="match-hero-photo" aria-hidden="true"></div>
      <div class="container match-hero-inner">
        <div class="match-hero-copy">
          <div class="match-location"><?= h($c['hero_eyebrow']) ?></div>
          <h1 id="hero-main-title"><?= h($c['hero_winter_title']) ?></h1>
          <p><?= h($c['hero_winter_description']) ?></p>
          <div class="winter-pill"><span aria-hidden="true">❄</span> Vėsina vasarą <span class="pill-divider">•</span> Šildo žiemą</div>
          <div class="match-advantages">
            <div><span class="match-adv-icon" aria-hidden="true">♢</span><span><b>Aiškios sąlygos</b><small>Prieš pradedant darbus</small></span></div>
            <div><span class="match-adv-icon" aria-hidden="true">▦</span><span><b>Patogus laikas</b><small>Suderiname montavimą</small></span></div>
            <div><span class="match-adv-icon" aria-hidden="true">⚒</span><span><b>Pilnas aptarnavimas</b><small>Parinkimas ir montavimas</small></span></div>
          </div>
        </div>
        <div class="contact-form-wrap hero-form-card match-form-card" id="kontaktai">
          <div class="form-header"><div><div class="form-kicker">NEMOKAMA KONSULTACIJA</div><h2><?= h($c['form_title']) ?></h2><p><?= h($c['form_intro']) ?></p></div></div>
          <form id="lead-form" action="siusti.php" method="post" accept-charset="utf-8">
            <div class="honeypot" aria-hidden="true"><label>Šios eilutės nepildykite <input name="website" autocomplete="off" tabindex="-1"></label></div>
            <label for="name">Jūsų vardas <span>*</span><input id="name" name="vardas" type="text" required maxlength="100" placeholder="Jūsų vardas" autocomplete="name"></label>
            <label for="phone">Telefono numeris <span>*</span><input id="phone" name="telefonas" type="tel" required maxlength="30" placeholder="Telefono numeris" autocomplete="tel"></label>
            <label for="city">Miestas / rajonas<input id="city" name="miestas" type="text" maxlength="80" placeholder="Miestas ar rajonas (pvz., Kaunas)" autocomplete="address-level2"></label>
            <label for="interest">Domina paslauga<select name="paslauga" id="interest" required><option value="" selected disabled>Pasirinkite paslaugą</option><option value="Montavimas">Montavimas</option><option value="Konsultacija">Konsultacija</option><option value="Tvarkymas">Tvarkymas</option><option value="Kita">Kita</option></select></label>
            <label for="details">Jūsų žinutė (nebūtina)<textarea id="details" name="zinute" rows="2" maxlength="2000" placeholder="Jūsų žinutė (nebūtina)"></textarea></label>
            <details class="form-extra"><summary>Pridėti el. paštą (nebūtina)</summary><label for="email">El. paštas<input id="email" name="elpastas" type="email" maxlength="120" placeholder="vardas@pastas.lt" autocomplete="email"></label></details>
            <input type="hidden" name="utm_source" id="utm_source"><input type="hidden" name="utm_medium" id="utm_medium"><input type="hidden" name="utm_campaign" id="utm_campaign"><input type="hidden" name="gclid" id="gclid"><input type="hidden" name="msclkid" id="msclkid"><input type="hidden" name="fbclid" id="fbclid"><input type="hidden" name="referrer" id="referrer"><input type="hidden" name="landing_page" id="landing_page">
            <button class="btn btn-primary btn-submit" type="submit">Siųsti užklausą <span>↗</span></button><p id="form-status" role="status" aria-live="polite"></p>
          </form>
        </div>
      </div>
    </section>
    <section class="match-highlights" id="paslaugos">
      <div class="container match-highlights-grid">
        <div class="match-service-column">
          <h2>Mūsų paslaugos</h2>
          <div class="match-service"><span class="match-service-icon" aria-hidden="true">▣</span><div><h3><?= h($c['service2_title']) ?></h3><p><?= h($c['service2_desc']) ?></p></div></div>
          <div class="match-service"><span class="match-service-icon" aria-hidden="true">⚙</span><div><h3><?= h($c['service1_title']) ?></h3><p><?= h($c['service1_desc']) ?></p></div></div>
          <div class="match-service"><span class="match-service-icon" aria-hidden="true">⚒</span><div><h3><?= h($c['service3_title']) ?></h3><p><?= h($c['service3_desc']) ?></p></div></div>
        </div>
        <div class="match-reasons-column">
          <h2>Kodėl verta rinktis mus?</h2>
          <ul><li>Įrangą parenkame pagal patalpų poreikius</li><li>Montavimo sąlygas suderiname iš anksto</li><li>Aptariame vamzdynų ir kondensato nuvedimą</li><li>Padedame su valdymo ir Wi-Fi nustatymais</li><li>Atliekame sistemos paleidimą ir instruktažą</li></ul>
        </div>
        <div class="match-steps-column" id="kaip-dirbame">
          <h2>Kaip vyksta įrengimas?</h2>
          <div class="match-steps">
            <div><span class="match-step-icon">☏</span><b>1. Užklausa</b><small><?= h($c['step1_desc']) ?></small></div>
            <div><span class="match-step-icon">▦</span><b>2. Derinimas</b><small><?= h($c['step2_desc']) ?></small></div>
            <div><span class="match-step-icon">⚒</span><b>3. Montavimas</b><small><?= h($c['step3_desc']) ?></small></div>
            <div><span class="match-step-icon">✓</span><b>4. Paleidimas</b><small><?= h($c['step4_desc']) ?></small></div>
          </div>
          <div class="match-brands"><span>Įrangos gamintojai:</span><b>Daikin</b><b>Mitsubishi Electric</b><b>LG</b><b>Samsung</b><b>Gree</b></div>
        </div>
      </div>
    </section>

    <!-- Žiemos sezono turinys: naudingi paaiškinimai, o ne raktinių žodžių kartojimas -->
    <section class="winter-section" id="silumos-siurbliai" aria-labelledby="heat-heading">
      <div class="container">
        <div class="winter-head"><span class="winter-eyebrow">ŠILDYMAS IR VĖSINIMAS</span><h2 id="heat-heading"><?= h($c['heat_section_title']) ?></h2><p><?= h($c['heat_section_lead']) ?></p></div>
        <div class="winter-system-grid">
          <article class="winter-system-card"><div class="winter-symbol" aria-hidden="true">♨</div><h3><?= h($c['heat_airair_title']) ?></h3><p><?= h($c['heat_airair_desc']) ?></p><a href="#kontaktai" data-interest="Konsultacija">Pasitarti dėl oras–oras <span aria-hidden="true">↗</span></a></article>
          <article class="winter-system-card"><div class="winter-symbol" aria-hidden="true">◌</div><h3><?= h($c['heat_airwater_title']) ?></h3><p><?= h($c['heat_airwater_desc']) ?></p><a href="#kontaktai" data-interest="Konsultacija">Pasitarti dėl oras–vanduo <span aria-hidden="true">↗</span></a></article>
          <article class="winter-system-card"><div class="winter-symbol" aria-hidden="true">≋</div><h3><?= h($c['heat_recup_title']) ?></h3><p><?= h($c['heat_recup_desc']) ?></p><a href="#kontaktai" data-interest="Konsultacija">Pasitarti dėl vėdinimo <span aria-hidden="true">↗</span></a></article>
        </div>
      </div>
    </section>
    <section class="work-section" id="visi-darbai" aria-labelledby="work-heading"><div class="container">
      <div class="winter-head"><span class="winter-eyebrow">NUO PARDAVIMO IKI PRIEŽIŪROS</span><h2 id="work-heading"><?= h($c['work_section_title']) ?></h2><p><?= h($c['work_section_lead']) ?></p></div>
      <div class="work-grid">
        <?php $serviceIcons = ['shopping-bag','wrench','activity','clipboard-check','sparkles','droplet','move','wind']; foreach (range(1,8) as $workIndex): ?>
          <article class="work-tile"><span class="work-number"><?= str_pad((string)$workIndex,2,'0',STR_PAD_LEFT) ?></span><h3><?= h($c['work'.$workIndex.'_title']) ?></h3><p><?= h($c['work'.$workIndex.'_desc']) ?></p></article>
        <?php endforeach; ?>
      </div>
      <div class="work-bottom"><p><strong>Turite neįprastą užduotį?</strong> Nurodykite sistemos gamintoją, modelį ir gedimo požymius – patikslinsime, ar galime padėti.</p><a class="btn btn-primary" href="#kontaktai" data-interest="Tvarkymas">Pateikti užklausą <span aria-hidden="true">↗</span></a></div>
    </div></section>
    <section class="packages-section" id="sprendimai" aria-labelledby="packages-heading"><div class="container">
      <div class="winter-head"><span class="winter-eyebrow">ĮRANGOS PASIRINKIMAS</span><h2 id="packages-heading"><?= h($c['package_section_title']) ?></h2><p><?= h($c['package_section_lead']) ?></p></div>
      <div class="packages-grid">
        <?php foreach (range(1,3) as $pi): ?>
        <article class="package-card<?= $pi===2?' popular':'' ?>"><span class="package-no"><?= str_pad((string)$pi,2,'0',STR_PAD_LEFT) ?></span><h3><?= h($c['package'.$pi.'_name']) ?></h3><p><?= h($c['package'.$pi.'_desc']) ?></p><div class="package-detail"><?= h($c['package'.$pi.'_detail']) ?></div><a href="#kontaktai" data-interest="Konsultacija">Gauti individualų pasiūlymą <span aria-hidden="true">↗</span></a></article>
        <?php endforeach; ?>
      </div>
      <p class="package-disclaimer">Tai orientacinės pasirinkimo kryptys, o ne fiksuoti įrangos paketai. Tiksli komplektacija, garantijos ir kaina priklauso nuo konkretaus gaminio bei montavimo sąlygų.</p>
    </div></section>
    <section class="winter-faq-section" id="papildomi-duk"><div class="container winter-faq-wrap">
      <div><span class="winter-eyebrow">NAUDINGA ŽINOTI</span><h2>Dažniausi klausimai apie šilumos siurblius ir priežiūrą</h2><p>Prieš pasirenkant ar remontuojant įrangą svarbu atskirti skirtingų sistemų funkcijas ir žinoti, kada reikalinga specialisto apžiūra.</p></div>
      <div class="winter-faq-list"><?php foreach (range(1,4) as $faqIndex): ?><details><summary><?= h($c['winter_faq'.$faqIndex.'_q']) ?></summary><p><?= h($c['winter_faq'.$faqIndex.'_a']) ?></p></details><?php endforeach; ?></div>
    </div></section>

    <section class="match-lower" id="duk"><div class="container match-lower-grid">
      <div><h2>Dažniausiai užduodami klausimai</h2><div class="match-faq">
        <details><summary><?= h($c['faq1_q']) ?></summary><p><?= h($c['faq1_a']) ?></p></details>
        <details><summary><?= h($c['faq2_q']) ?></summary><p><?= h($c['faq2_a']) ?></p></details>
        <details><summary><?= h($c['faq3_q']) ?></summary><p><?= h($c['faq3_a']) ?></p></details>
        <details><summary><?= h($c['faq7_q']) ?></summary><p><?= h($c['faq7_a']) ?></p></details>
      </div></div>
      <div class="match-intro"><h2>Kondicionieriai – komfortui ir energijos taupymui</h2><p><?= h($c['seo_article5_lead']) ?></p><p><?= h($c['seo_article1_lead']) ?></p><p>Rinkdamiesi įrenginį atsižvelkite į patalpų plotą, saulės kryptį, montavimo vietą ir naudojimo poreikius. Toliau rasite išsamią informaciją apie „Split“ bei „Multi-Split“ sistemas, jų įrengimą ir priežiūrą.</p></div>
    </div></section>
    <section class="section prices-section" id="kainos"><div class="container">
      <div class="section-heading center-heading"><span class="eyebrow">ORIENTACINĖS KAINOS</span><h2><?= h($c['prices_title1']) ?><br><em><?= h($c['prices_title2']) ?></em></h2><p><?= h($c['prices_intro']) ?></p></div>
      <div class="pricing-grid">
        <article class="price-card"><div class="price-tag"><?= h($c['price1_badge']) ?></div><h3><?= h($c['price1_title']) ?></h3><p><?= h($c['price1_desc']) ?></p><div class="price-line"><span>nuo</span><strong><?= h($c['price1_price']) ?></strong></div><div class="price-hint"><?= h($c['price1_hint']) ?></div><ul><?php foreach (text_lines($c['price1_features']) as $line): ?><li><?= h($line) ?></li><?php endforeach; ?></ul><a class="btn btn-dark-outline" href="#kontaktai" data-interest="Montavimas">Gauti pasiūlymą <span>↗</span></a></article>
        <article class="price-card"><div class="price-tag"><?= h($c['price2_badge']) ?></div><h3><?= h($c['price2_title']) ?></h3><p><?= h($c['price2_desc']) ?></p><div class="price-line"><span>nuo</span><strong><?= h($c['price2_price']) ?></strong></div><div class="price-hint"><?= h($c['price2_hint']) ?></div><ul><?php foreach (text_lines($c['price2_features']) as $line): ?><li><?= h($line) ?></li><?php endforeach; ?></ul><a class="btn btn-dark-outline" href="#kontaktai" data-interest="Montavimas">Gauti pasiūlymą <span>↗</span></a></article>
        <article class="price-card featured"><div class="featured-ribbon">REKOMENDUOJAME 2 KAMBARIAMS</div><div class="price-tag"><?= h($c['price3_badge']) ?></div><h3><?= h($c['price3_title']) ?></h3><p><?= h($c['price3_desc']) ?></p><div class="price-line"><span>nuo</span><strong><?= h($c['price3_price']) ?></strong></div><div class="price-hint"><?= h($c['price3_hint']) ?></div><ul><?php foreach (text_lines($c['price3_features']) as $line): ?><li><?= h($line) ?></li><?php endforeach; ?></ul><a class="btn btn-primary" href="#kontaktai" data-interest="Montavimas">Domina šis pasiūlymas <span>↗</span></a></article>
      </div>
      <p class="pricing-note"><?= h($c['prices_note']) ?></p>
    </div></section>
    <section class="section spotlight-section" id="gree"><div class="container spotlight-grid">
      <div class="spotlight-left"><span class="eyebrow">IŠSKIRTINIS PASIŪLYMAS</span><h2><?= h($c['gree_title1']) ?><br><em><?= h($c['gree_title2']) ?></em></h2><p class="spotlight-lead"><?= h($c['gree_text']) ?></p>
        <div class="tech-grid"><div><span><?= h($c['gree_cooling']) ?></span><small>Išorinio bloko šaldymo galia</small></div><div><span><?= h($c['gree_heating']) ?></span><small>Išorinio bloko šildymo galia</small></div><div><span><?= h($c['gree_inner']) ?></span><small>Vidinių blokų šaldymo galia</small></div><div><span>Wi-Fi</span><small>Nuotolinis valdymas</small></div></div>
        <div class="model-details"><strong>Komplektacija</strong><p>Išorinis: <?= h($c['gree_ext_model']) ?><br>Vidiniai (2 vnt.): <?= h($c['gree_int_model']) ?></p></div>
        <a class="btn btn-primary btn-large" href="#kontaktai" data-interest="Montavimas">Gauti šios sistemos pasiūlymą <span>↗</span></a>
      </div>
      <div class="spotlight-right"><div class="how-title"><?= h($c['gree_included_title']) ?></div><div class="included-list">
          <div><i>01</i><p><strong><?= h($c['gree_item1_title']) ?></strong><span><?= h($c['gree_item1_desc']) ?></span></p></div>
          <div><i>02</i><p><strong><?= h($c['gree_item2_title']) ?></strong><span><?= h($c['gree_item2_desc']) ?></span></p></div>
          <div><i>03</i><p><strong><?= h($c['gree_item3_title']) ?></strong><span><?= h($c['gree_item3_desc']) ?></span></p></div>
          <div><i>04</i><p><strong><?= h($c['gree_item4_title']) ?></strong><span><?= h($c['gree_item4_desc']) ?></span></p></div>
          <div><i>05</i><p><strong><?= h($c['gree_item5_title']) ?></strong><span><?= h($c['gree_item5_desc']) ?></span></p></div>
        </div><div class="sp-note"><?= h($c['gree_note']) ?></div></div>
    </div></section>
    <section class="section editorial-section" id="montavimas-vilniuje"><div class="container">
      <div class="section-heading split-heading"><div><span class="eyebrow">KONDICIONIERIŲ MONTAVIMAS VILNIUJE</span><h2><?= h($c['seo_article1_title']) ?></h2></div><p><?= h($c['seo_article1_lead']) ?></p></div>
      <div class="editorial-grid editorial-grid-2"><article class="editorial-panel"><h3>Ką apima įrengimo paslauga?</h3><p><?= h($c['seo_article1_p1']) ?></p><p><?= h($c['seo_article1_p2']) ?></p></article><article class="editorial-panel editorial-highlight"><h3>Montavimas pagal patalpos ypatybes</h3><p><?= h($c['seo_article1_p3']) ?></p><p><?= h($c['seo_article1_p4']) ?></p></article></div>
    </div></section>
    <section class="section editorial-section editorial-soft" id="kaip-issirinkti"><div class="container">
      <div class="section-heading"><span class="eyebrow">PRAKTIŠKAS GIDAS</span><h2><?= h($c['seo_article2_title']) ?></h2><p><?= h($c['seo_article2_lead']) ?></p></div>
      <div class="editorial-grid editorial-grid-3">
        <article class="editorial-panel"><span class="editorial-count">01</span><h3>Plotas ir patalpos</h3><p><?= h($c['seo_article2_p1']) ?></p></article>
        <article class="editorial-panel"><span class="editorial-count">02</span><h3>Triukšmas ir valdymas</h3><p><?= h($c['seo_article2_p2']) ?></p></article>
        <article class="editorial-panel"><span class="editorial-count">03</span><h3>Šildymas ir elektros sąnaudos</h3><p><?= h($c['seo_article2_p3']) ?></p></article>
      </div>
    </div></section>
    <section class="section editorial-section" id="split-multisplit"><div class="container"><div class="section-heading split-heading"><div><span class="eyebrow">PALYGINIMAS</span><h2><?= h($c['seo_article3_title']) ?></h2></div><p><?= h($c['seo_article3_lead']) ?></p></div>
      <div class="editorial-grid editorial-grid-2"><article class="editorial-panel"><h3>Split sistema vienai patalpai</h3><p><?= h($c['seo_article3_p1']) ?></p></article><article class="editorial-panel"><h3>Multi-Split kelioms patalpoms</h3><p><?= h($c['seo_article3_p2']) ?></p></article></div>
      <p class="editorial-footnote"><?= h($c['seo_article3_note']) ?></p>
    </div></section>
    <section class="section editorial-section editorial-soft" id="montavimo-reikalavimai"><div class="container"><div class="section-heading split-heading"><div><span class="eyebrow">KĄ VERTA ŽINOTI</span><h2><?= h($c['seo_article4_title']) ?></h2></div><p><?= h($c['seo_article4_lead']) ?></p></div>
      <div class="editorial-grid editorial-grid-2"><article class="editorial-panel"><h3>Vieta, vamzdynai ir kondensatas</h3><p><?= h($c['seo_article4_p1']) ?></p><p><?= h($c['seo_article4_p2']) ?></p></article><article class="editorial-panel"><h3>Daugiabutis, fasadas ir balkonas</h3><p><?= h($c['seo_article4_p3']) ?></p><p><?= h($c['seo_article4_p4']) ?></p></article></div>
    </div></section>
    <section class="section editorial-section" id="prieziura-ir-sildymas"><div class="container"><div class="section-heading split-heading"><div><span class="eyebrow">KOMFORTAS VISAIS METAIS</span><h2><?= h($c['seo_article5_title']) ?></h2></div><p><?= h($c['seo_article5_lead']) ?></p></div>
      <div class="editorial-grid editorial-grid-2"><article class="editorial-panel"><h3>Kondicionierius šildymui</h3><p><?= h($c['seo_article5_p1']) ?></p><p><?= h($c['seo_article5_p2']) ?></p></article><article class="editorial-panel"><h3>Profilaktika ir ilgaamžiškumas</h3><p><?= h($c['seo_article5_p3']) ?></p><p><?= h($c['seo_article5_p4']) ?></p></article></div>
    </div></section>
    <section class="section editorial-section editorial-local" id="aptarnavimo-teritorija"><div class="container"><div class="section-heading split-heading"><div><span class="eyebrow">KUR DIRBAME</span><h2><?= h($c['seo_local_title']) ?></h2></div><p><?= h($c['seo_local_lead']) ?></p></div><p><?= h($c['seo_local_text']) ?></p>
      <ul class="nationwide-city-list" aria-label="Miestai, kuriuose priimame užklausas"><?php foreach (text_lines($c['coverage_cities']) as $coverageCity): ?><li><?= h($coverageCity) ?></li><?php endforeach; ?></ul>
      <p class="nationwide-disclaimer">Neradote savo miesto? Vis tiek kreipkitės – dirbame visoje Lietuvoje, o atvykimą ir darbų kainą suderiname individualiai.</p>
      <a class="btn btn-primary btn-large" href="#kontaktai">Pasiteirauti dėl montavimo <span>↗</span></a></div></section>
<?php $gallery = []; foreach (range(1, 4) as $galleryIndex) { $img = $c['gallery'.$galleryIndex.'_image'] ?? ''; if (public_image($img)) $gallery[] = ['src' => $img, 'caption' => $c['gallery'.$galleryIndex.'_caption'] ?? '']; } ?>
    <?php if ($gallery): ?>
    <section class="section gallery-section" id="darbai"><div class="container"><div class="section-heading center-heading"><span class="eyebrow">GALERIJA</span><h2><?= h($c['gallery_title']) ?></h2></div><div class="gallery-grid"><?php foreach ($gallery as $photo): ?><figure><img src="<?= h($photo['src']) ?>" alt="<?= h($photo['caption']) ?>" loading="lazy"><figcaption><?= h($photo['caption']) ?></figcaption></figure><?php endforeach; ?></div></div></section>
    <?php endif; ?>
    <section class="contact-section" id="susisiekime"><div class="container contact-grid"><div class="contact-info"><span class="eyebrow eyebrow-light">SUSISIEKIME</span><h2><?= h($c['contact_title1']) ?><br><em><?= h($c['contact_title2']) ?></em></h2><p><?= h($c['contact_text']) ?></p><div class="contact-method"><span>SKAMBINKITE</span><a href="tel:<?= h(phone_uri($c['phone'])) ?>"><?= h($c['phone']) ?></a></div><div class="contact-method"><span>RAŠYKITE</span><a href="mailto:<?= h($c['email']) ?>"><?= h($c['email']) ?></a></div><div class="contact-small"><?= h($c['area']) ?></div></div><div class="contact-final-card"><div class="contact-final-icon">↗</div><h3>Norite sužinoti tikslią kainą?</h3><p>Trumpai nurodykite, kur reikia montuoti įrangą ir kiek patalpų planuojate vėsinti. Pasiūlymą parengsime atsižvelgę į konkrečias sąlygas.</p><a class="btn btn-primary btn-large" href="#kontaktai">Užpildyti užklausą <span>↗</span></a></div></div></section>
  </main>
  <footer class="footer"><div class="container footer-main"><div><?= site_brand("#top", "footer-brand") ?><p>Kondicionierių ir šilumos siurblių pardavimas, montavimas ir priežiūra Vilniuje bei visoje Lietuvoje.</p></div><div><strong>Nuorodos</strong><a href="#paslaugos">Paslaugos</a><a href="#kainos">Kainos</a><a href="#gree">Gree Multi-Split</a><a href="#duk">D.U.K.</a></div><div><strong>Kontaktai</strong><a href="tel:<?= h(phone_uri($c['phone'])) ?>"><?= h($c['phone']) ?></a><a href="mailto:<?= h($c['email']) ?>"><?= h($c['email']) ?></a><span><?= h($c['address_caption']) ?></span></div></div><div class="container footer-bottom"><span>© <span id="year">2026</span> Kondicionierių Įrengimas</span><a href="privatumo-politika.php">Privatumo politika</a><a href="#top">Į viršų ↑</a></div></footer>
  <div class="mobile-bottom" aria-label="Greitieji veiksmai">
    <a class="mobile-phone-button" href="tel:<?= h(phone_uri($c['phone'])) ?>" aria-label="Skambinti <?= h($c['phone']) ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="5" y="2" width="14" height="20" rx="2"/><path d="M12 18h.01"/></svg><span><?= h($c['phone']) ?></span></a>
    <a class="mobile-offer-button" href="#kontaktai">Gauti pasiūlymą <span aria-hidden="true">↗</span></a>
  </div>
  <script src="assets/main.js?v=3" defer></script>
</body>
</html>