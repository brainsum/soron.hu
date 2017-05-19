<?php

    setlocale(LC_ALL, "en_US.UTF8");

    // Basic development branching (UnCSS)

    /** @var string $uncss */
    $uncss = null;

    if (false === empty($_SERVER['QUERY_STRING'])) {
        $tld = explode(".", $_SERVER['SERVER_NAME']);
        $tld = array_pop($tld);

        if ($tld === "dev") {
            $dev = array();
            parse_str($_SERVER['QUERY_STRING'], $dev);
            true === isset($dev['uncss']) && $uncss = str_replace('.', '/', $dev['uncss']).".css";
        }
    }

    // Basic site configuration

    $year   = idate('Y');

    // Partners

    $partners = array(
    "Ventwest Kft.",
    "Tampon-Mix Kft.",
    "Zura Kft.",
    "Pet Hungária Kft.",
    "Sargent Iskolabusz Kft.",
    "S.E.F.T. Kft.",
    "Mikropo Rendszerház Kft.",
    "Dunakanyar Informatikai és Komm. Kft.",
    "Trafikbrand Zrt.",
    "Zengő Futómű és Gumiszervíz",
    "Expresso Szervíz Bt.",
    "Zenit Logisztikai Eszközök Kereskedőháza Kft.",
    "Persecutor Vagyonvédemi Kft.",
    "Liberatus Hungary Kft.",
    "BAH Center Irodaház",
    "Raiker Kft.",
    "Mc Mediacompany Kft.",
    "Zengő Motorsport Kft.",
    "Torrex Chiesi Kft.",
    "Duplex Drink Kft.",
    "MT Displays Hungary Kft.",
    "Duplex Drink Kft",
    "Perint Kft.",
    "Intego Vagyonkezelő és Szolgáltató Kft.",
    "Melon Fx Kft.",
    "Közbeszerzési Hatóság"
);

    // Accessibility

    $access = array(
        'api'       => 'ARIA',
        'control'   => array('Keyboard', 'Mouse', 'Touch'),
        'hazard'    => array('motionSimulation')
    );

    // ----------------------------------
    // @todo - Quick workaround to prevent replacing the logic
    // ----------------------------------

    interface VoidInterface {
        public function render();
    }

    abstract class Atomic implements VoidInterface {
        protected $data;

        public function __construct(array $data) {
            $this->data = $data;
        }

        public function __toString() {
            return $this->render();
        }

        public function set(array $value) {
            $this->data = $value;
        }

        public function get() {
            return $this->data;
        }
    }

    class Keyword extends Atomic {
        public function render() {return implode(',', $this->data);}
    }

    class PrefixedNamespace extends Atomic {
        public function render() {
            $attr = '';
            foreach ($this->data as $prefix => $descriptor) $attr.= "{$prefix}: {$descriptor} ";
            return substr($attr, 0, -1);
        }
    }

    class Http {
        public static function getHost() {
            static $_host = null;
            return $_host === null ? $_host = self::getProtocol()."//{$_SERVER['SERVER_NAME']}" : $_host;
        }

        public static function getProtocol() {
            static $_protocol = null;
            return $_protocol === null ? $_protocol = $_SERVER['SERVER_PORT'] == 80 ? 'http:' : 'https:' : $_protocol;
        }

        public static function getAssetUrl($path, $absolute = false) {
            return $absolute === false ? "/assets{$path}" : "//{$_SERVER['SERVER_NAME']}/assets{$path}";
        }
    }

    class TextHelper {
        const WHITESPACE_URI = '-';
        const WHITESPACE_CLI = '_';

        public static function normalize($text, $space = self::WHITESPACE_URI) {
            $text = preg_replace('/[^\p{Ll}\p{Lu}\p{N}]+/u', ' ', $text);
            $text = trim($text);

            if (strlen($text) !== 0) {
                $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
                $text = preg_replace('/[^a-z0-9 ]/', '', strtolower($text));
                $text = str_replace(' ', $space, $text);
            }
            return $text;
        }
    }

    class Page {
        protected $meta;
        protected $tab = 1;

        public function __construct(stdClass $meta = null) {
            $meta !== null && $this->meta = $meta;
        }

        public function getNamespaces() {
            return new PrefixedNamespace(array(
                'og' => 'http://ogp.me/ns/#'
            ));
        }

        public function getMetaHeader() {
            return $this->meta;
        }

        public function getTabIndex() {
            return $this->tab ++;
        }

        public function getTitle() {
            return $this->meta->name;
        }

        public function getDescription() {
            return $this->meta->text;
        }

        public function getKeywords() {
            return new Keyword(array('alkusz', 'biztosítás', 'kockázatelemzés', 'kockázat felmérés', 'biztosító ajánlat'));
        }

        public function getLang($qualified = false) {
            return $qualified === true ? 'hu-HU' : 'hu';
        }
    }

    class Organization {
        protected $data;

        public function __construct(array $data = null) {
            $this->data = $data;
        }

        public function getName($legal = false) {
            return $this->data['name'][$legal === false ? 'short' : 'legal'];
        }

        public function getSlug() {
            static $_slug = "";
            return $_slug === "" ? ($_slug = TextHelper::normalize($this->getName())) : $_slug;
        }

        public function getLink() {
            return $this->data['link'];
        }

        public function getMeta($role) {
            return $this->data['meta'][$role];
        }

        public function getText($role) {
            return $this->data['text'][$role];
        }
    }

    // ----------------------------------

    // Owner company (publisher)

    $org = new Organization(array(
        "name" => array(
            "short" => 'SORON Magyarország',
            "legal" => 'SORON Magyarország Alkusz Kft.'
        ),
        "link" => Http::getHost(),
        "meta" => array(
            "code-org" => '01-09-710091',
            "code-tax" => '12921308-1-42'
        ),
        "text" => array(
            "typo" => 'Biztosítás és kockázatelemzés',
            "info" => 'Fő tevékenységi körünk a vállalkozásoknak nyújtott szakmai-, és tevékenységükhöz kapcsolódó felelősség-, teljes telephelyi- és gépjármű flottára szóló biztosítások.'
        )
    ));

    // Developer guild (creator)

    $dev = new Organization(array(
        "name" => array(
            "short" => 'Brainsum',
            "legal" => 'Brainsum Korlátolt Felelősségű Társaság'
        ),
        "link" => 'http://www.brainsum.com',
        "meta" => array(),
        "text" => array(
            "typo" => 'Enterprise mobile and web application development'
        )
    ));

// Instantiating dev-test instance

    $page = new Page((object) array(
        "name"  => "{$org->getName()} | {$org->getText('typo')}",
        "lead"  => Http::getAssetUrl("/img/soron-magyarorszag-alkusz.jpg"),
        "text"  => $org->getText('info')
    ));
    $meta = $page->getMetaHeader();


?><!DOCTYPE html>

<html id="root" lang="<?=$page->getLang()?>" prefix="<?=$page->getNamespaces()?>">
<head>
    <!--[if (IE 9)&!(IEMobile)]><meta http-equiv="X-UA-Compatible" content="IE=9"/><![endif]-->
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width,maximum-scale=1,user-scalable=no"/>

    <title><?=$page->getTitle()?></title>

    <meta name="keywords" content="<?=$page->getKeywords()?>"/>
    <meta name="description" content="<?=$page->getDescription()?>"/>
    <meta name="robots" content="all"/>
    <meta name="revisit-after" content="7 days"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE"/>

    <meta property="og:type" content="website"/>
    <meta property="og:site_name" content="<?=$org->getName()?>"/>
    <meta property="og:description" content="<?=$page->getDescription()?>"/>
    <meta property="og:url" content="<?=Http::getHost()?>/"/>
    <!--meta property="og:image" content=""/-->

    <link rel="shortcut icon" href="<?=Http::getAssetUrl('/favicon.ico')?>" type="image/vnd.microsoft.icon"/>

<?php if (null === $uncss) : ?>

    <style><?=file_get_contents('assets/css/xs.css') ?></style>
    <noscript id="load"><link rel="stylesheet" href="<?=Http::getAssetUrl('/css/fx.css') ?>"></noscript>
    <script><?=file_get_contents("assets/js/app-inline.js")?></script>

<?php else : ?>

    <link rel="stylesheet" href="/<?=htmlspecialchars($uncss)?>"/>

<?php endif ?>
</head>

<body id="page" vocab="http://schema.org/" typeof="AboutPage">

    <!-- [SCHEMA] BASIC PAGE DEFINITIONS -->

    <meta property="name" content="<?=$org->getName()?>"/>
    <meta property="headline" content="<?=$page->getDescription()?>"/>
    <meta property="isFamilyFriendly" content="true"/>
    <meta property="inLanguage" content="<?=$page->getLang(true)?>"/>

    <!-- ================================== -->
    <!-- [LAYOUT] CONTENTS LIST -->
    <!-- ================================== -->

    <!-- [LAYOUT.CONTENT] HEADLINE -->

    <section class="part-headline" id="home" tabindex="<?=$page->getTabIndex()?>">
        <div id="sub-home">
            <div id="home-text" class="left">
                <div id="headline-title-1">
                    <span>Cégeknek szóló vállalati vagyon-, és felelősség</span>
                    <span class="white bold">biztosítások</span>
                </div>
                <div id="headline-2-3-container">
                    <div id="headline-title-2" class="capital white">
                        <div class="md-3 inline-block center left-points points">...</div>
                        <div class="md-3 inline-block left" id="the-title">Hosszú távon</div>
                        <div class="md-3 inline-block left points">.......</div>
                    </div>
                    <div id="headline-3-container">
                        <div class="md-3 inline-block left-points points"></div>
                        <div class="md-4 inline-block left" id="headline-title-3">
                            Jöhet bármi!<br />
                            A SORON szakértői profi segítséget adnak, hogy<br />
                            Ön és Cége védve legyen,<br />
                            bármi történjen.<br />
                            <div class="top-button-container center">
                                <span class="link t-trigger" id="services-button-top">
                                    <a href="#szolgaltatasunk">Megnézem</a>
                                </span>
                            </div>
                        </div>
                        <div class="md-3 inline-block points"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- [LAYOUT.CONTENT] PORTFOLIO -->

    <section class="block part-portfolio" tabindex="<?=$page->getTabIndex()?>">
        <a class="anchor" id="rolunk"></a>
        <div class="wrapper bg">
            <input class="hidden t-state" id="x-p" type="checkbox" aria-hidden="true"/>

            <div class="container">
                <h3 class="title" id="about-us-title">Rólunk</h3>

                <div class="content about-us-content">
                    <p>A SORON Magyarország Alkusz 2003. februárja óta áll az ügyfelek védelmének szolgálatában.
                    <br />
                    A cég szakértői 1997 óta foglalkoznak ügyfél támogatással a biztosítások területén. Ezért egy olyan független, professzionális tanácsadócéget hoztunk létre, amely a nemzetközi elvárásoknak is megfelelő szolgáltatásokat nyújt, elsősorban cégeknek.</p>
                </div>
                <div class="content t-target about-us-content" id="about-us-footer">
                    <div class="people">
                        <div class="person-holder">
                            <img src="/assets/img/somodi-krisztian.jpg" class="person-image"><br />
                            <span class="person-name">Somodi Krisztián</span><br />
                            <span class="person-job">Ügyvezető</span>
                        </div>

                        <div class="person-holder">
                            <img src="/assets/img/csizmadia-ronaszeki-noemi.jpg" class="person-image"><br />
                            <span class="person-name">Csizmadia-Rónaszéki Noémi</span><br />
                            <span class="person-job">Szakmai vezető</span>
                        </div>
                    </div>
                    <p id="first-hidden-list-elem">Társaságunk többéves biztosításszakmai gyakorlattal rendelkező tulajdonosokból és munkatársakból áll, amely biztosítéka az ügyfeleket mind teljesebben kiszolgálni tudó munkánknak.</p>
                    <p>Partneri kapcsolatainkat a bizalomra építjük, legfontosabb mércénk ügyfeleink hosszú távú elégedettsége. Alapvető értékeink közé tartozik a megbízhatóság, szakértelem, előrelátás és gondoskodás. Nagy hangsúlyt helyezünk a folyamatos képzésre, hogy ügyfeleink számára valóban értékes szakmai segítséget nyújthassunk.</p>
                    <p><strong>Fő tevékenységi körünk a vállalkozásoknak nyújtott szakmai-, és tevékenységükhöz kapcsolódó felelősségbiztosítások, teljes telephelyi-, gépjármű flottára szóló biztosítások. Egyedülálló megoldásokkal rendelkezünk vagyonkezelő, és társasházkezelők részére.</strong></p>
                    <p>Az általunk kínált szolgáltatások legnagyobb előnye, hogy szakértelmünk és tapasztalatunk alapján a piac teljes kínálatának áttekintése után választjuk ki az ügyfél számára legmegfelelőbb konstrukciót.</p>
                    <p>A Soron Magyarország Alkusznál kiemelt hangsúlyt kap az ügyfelek valós kockázatának felmérése alapján, személyes, egyedi igényeinek figyelembe vétele és maradéktalan kiszolgálása.</p>
                    <p><strong>Káresemény esetén értékes segítséget nyújtunk, hogy ügyfeleink maradéktalan igénye érvényesüljön.</strong></p>
                </div>
            </div>

            <!--<div class="button-holder">-->
                <label for="x-p" class="link t-trigger" id="first-button2" aria-hidden="true" tabindex="<?=$page->getTabIndex()?>">
                    <span class="text show">Bővebben</span>
                    <span class="text hide">Bezár</span>
                </label>
            <!--</div>-->
        </div>
    </section>

    <!-- [LAYOUT.CONTENT] ILLUMINATION -->

    <div class="block part-illumination" role="presentation" aria-hidden="true" tabindex="-1"></div>

    <!-- [LAYOUT.CONTENT] SERVICES -->

    <section class="block part-services" id="" tabindex="<?=$page->getTabIndex()?>">
        <a class="anchor" id="szolgaltatasunk"></a>
        <div class="wrapper bg">
            <input class="hidden t-state" id="x-s" type="checkbox" aria-hidden="true"/>

            <div class="container">
                <h2 class="title" id="services-title">Szolgáltatásunk</h2>

                <div class="inline xs-12 sm-6">
                    <h4 class="title-sub">Ügyfeleink az alábbi szolgáltatásokat kapják</h4>

                    <ul class="list list-text service-list first-row-list">
                        <li class="item">biztosítási szolgáltatások</li>
                        <li class="item">kockázatfelmérés és kockázatelemzés</li>
                        <li class="item">a kockázatok csökkentésére, ill. kiküszöbölésére vonatkozó módszerek kidolgozása</li>
                    </ul>
                    <ul class="list list-text t-target service-list">
                        <li class="item">az elkerülhetetlen kockázatok áthárítására biztosítási program kialakítása</li>
                        <li class="item">a biztosítási programban megfogalmazott kockázatokra vonatkozó biztosítási fedezet megszervezése a biztosítók versenyeztetésével</li>
                        <li class="item">a biztosítók ajánlatainak elemzése a biztosított részére, a biztosító által nyújtott szolgáltatások és feltételrendszerek, valamint a biztosítási díj figyelembevételével</li>
                        <li class="item">biztosítottal egyeztetett program alapján a szerződések megkötése</li>
                    </ul>
                </div>
                <div class="inline xs-12 sm-6">
                    <h4 class="title-sub">A partneri kapcsolat fennállása alatt az alábbi szolgáltatásokat kínáljuk</h4>

                    <ul class="list list-text service-list first-row-list">
                        <li class="item">a felmerülő új biztosítási igények szakszerű megfogalmazása</li>
                        <li class="item">Partnerünk meglévő biztosítási szerződéseinek kezelése, felülvizsgálata és az esetlegesen szükséges módosításokra vonatkozó javaslatok kidolgozása</li>
                        <li class="item">káresemény alkalmával szakszerű felvilágosítás nyújtása a követendő eljárásról</li>
                    </ul>
                    <ul class="list list-text t-target service-list">
                        <li class="item">kárrendezés során közreműködünk abban, hogy Partnerünk törvényes érdeke maradéktalanul érvényesüljön tájékoztatás a biztosítási piacon felmerülő új lehetőségekről</li>
                        <li class="item">egyéni igények alapján testre szabott szolgáltatás nyújtása</li>
                    </ul>
                </div>
            </div>

            <label for="x-s" class="link t-trigger" aria-hidden="true" tabindex="<?=$page->getTabIndex()?>">
                <span class="text show">Bővebben</span>
                <span class="text hide">Bezár</span>
            </label>
        </div>
    </section>

    <!-- [LAYOUT.CONTENT] WHY US -->

    <section class="block part-about" tabindex="<?=$page->getTabIndex()?>">
    <a class="anchor" id="miert-a-soron"></a>
        <div class="wrapper bg">
            <div class="container">
                <h3 class="title" id="why-soron-title">Miért a Soron Magyarország Alkusz?</h3>

                <ul class="list list-text" id="why-soron-list">
                    <li class="item">Mert a Soron Magyarország Alkusz adminisztratív, informatikai, továbbá szaktanácsadói hátterével garantálja ügyfeleinek a magas színvonalú kiszolgálást.</li>
                    <li class="item">Mert a többéves biztosítási szakmában eltöltött idő alatt kiépült kapcsolati rendszerünk megalapozta a gyorsabb, rugalmasabb és eredményesebb ügyintézést minden területen.</li>
                    <li class="item"><strong>Mert az eddigi működésünk alatt hozzánk forduló ügyfelek megismerve különleges, ügyfélbarát szolgáltatási rendszerünket, minket választottak és mellettünk döntöttek egy hosszú távú együttműködésben.</strong></li>
                </ul>
            </div>
        </div>
    </section>

    <!-- [LAYOUT.CONTENT] PARTNERS -->

    <section class="block part-partners" id="our-partners" tabindex="<?=$page->getTabIndex()?>">
    <a class="anchor" id="partnereink"></a>
        <div class="wrapper bg">
            <div class="container">
                <h3 class="title title-big" id="partners-title">Partnereink</h3>

                <ul class="list" id="partners-list">
                    <?php foreach ($partners as $partnerName) : ?>
                        <li class="inline item xs-12 sm-6 md-4"><em class="text"><?=htmlspecialchars($partnerName)?></em></li>
                    <?php endforeach ?>
                </ul>
            </div>
        </div>
    </section>

    <!-- ================================== -->
    <!-- [LAYOUT] FOOTER -->
    <!-- ================================== -->

    <footer class="block part-footer" property="mainEntity" typeof="InsuranceAgency" tabindex="<?=$page->getTabIndex()?>">
        <a class="anchor" id="kapcsolat"></a>
        <div class="wrapper bg">
            <?php
                $about = "#{$org->getSlug()}";
            ?>
            <div class="container decorated" id="footer-whole-container">

                <!-- [LAYOUT.FOOTER] CONTACT -->

                <div class="inline xs-12 md-4">
                    <h4 class="inline col title xs-12 sm-6 md-12 footer-h4">Kapcsolat</h4>

                    <div class="inline col content xs-12 sm-6 md-12">
                        <strong class="company" property="legalName" id="company-name"><?=$org->getName(true)?></strong>
                        <dl class="term clearfix">
                            <dt class="name basic-name">Telefon</dt>
                            <dd class="data basic-data" property="telephone">+36 (1) 615-2805</dd>
                            <dt class="name basic-name">Fax</dt>
                            <dd class="data basic-data" property="faxNumber">+36 (1) 615-2805</dd>
                            <dt class="name basic-name">E-mail</dt>
                            <dd class="data basic-data">
                                <a class="link" href="mailto:info@soron.hu" property="email" content="info@soron.hu">info@soron.hu</a>
                            </dd>
                        </dl>
                    </div>
                </div>

                <!-- [LAYOUT.FOOTER] ADDRESS -->

                <div class="inline col xs-12 sm-6 md-4">
                    <h4 class="title footer-h4">Cégünk székhelye</h4>

                    <address class="content" property="address" typeof="PostalAddress">
                        <meta property="addressCountry" content="Hungary"/>
                        <span property="postalCode">1142 </span>
                        <span property="addressLocality">Budapest </span>
                        <span property="streetAddress">Sárrét park 5/A.</span>
                    </address>

                    <h4 class="title footer-h4">Irodánk címe</h4>

                    <address class="content" property="location" typeof="PostalAddress">
                        <meta property="addressCountry" content="Hungary"/>
                        <span property="postalCode">1116 </span>
                        <span property="addressLocality">Budapest </span>
                        <span property="streetAddress">Temesvár u. 20.</span>
                    </address>

                </div>

                <!-- [LAYOUT.FOOTER] OPENING-HOURS -->

                <div class="inline col xs-12 sm-6 md-4 opening">
                    <h4 class="title footer-h4">Nyitvatartás</h4>

                    <dl class="term clearfix">
                        <dt class="item name">Hétfő</dt>
                        <dd class="item data" property="openingHours" content="Mo 09:00-20:00">09:00 - 20:00</dd>
                        <dt class="item name">Kedd</dt>
                        <dd class="item data" property="openingHours" content="Tu 09:00-17:00">09:00 - 17:00</dd>
                        <dt class="item name">Szerda</dt>
                        <dd class="item data" property="openingHours" content="We 09:00-17:00">09:00 - 17:00</dd>
                        <dt class="item name">Csütörtök</dt>
                        <dd class="item data" property="openingHours" content="Th 09:00-17:00">09:00 - 17:00</dd>
                        <dt class="item name">Péntek</dt>
                        <dd class="item data" property="openingHours" content="Fr 09:00-16:00">09:00 - 16:00</dd>
                    </dl>
                </div>
            </div>

            <div class="container account">

                <!-- [LAYOUT.FOOTER] FORMAL COMPANY INFORMATIONS -->

                <div class="inline col xs-12 sm-6 md-4" id="tax-data">
                    <dl class="term clearfix">
                        <dt class="name">Adószám</dt>
                        <dd class="data" property="taxID">12921308-1-42</dd>
                        <dt class="name">Cégjegyzékszám</dt>
                        <dd class="data" property="vatID">01-09-710091</dd>
                        <dt class="name">Felügyeleti engedély száma</dt>
                        <dd class="data">II-43/2003</dd>
                    </dl>
                </div>

                <!-- [LAYOUT.FOOTER] ACCOUNT -->

                <div class="inline col xs-12 sm-6 md-4" id="company-data">
                    <div class="bank">Budapest Bank</div>
                    <dl class="term clearfix">
                        <dt class="name">Főszámla</dt>
                        <dd class="data">10100840-57517600-01000008</dd>
                        <dt class="name">Ügyfélszámla</dt>
                        <dd class="data">10100840-57517600-02000001</dd>
                    </dl>
                </div>

                <!-- [LAYOUT.FOOTER] DOWNLOADABLE CONTENT -->

                <div class="inline col xs-12 md-4 docs">
                    <a target="_blank" href="/assets/other_files/fogyasztoi_panaszbejelento.pdf" class="link" tabindex="<?=$page->getTabIndex()?>">Fogyasztói panaszbejelentő</a>
                    <a target="_blank" href="/assets/other_files/panaszkezelesi_szabalyzat_soron.pdf" class="link" tabindex="<?=$page->getTabIndex()?>">Panaszkezelési információk</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ================================== -->
    <!-- [LAYOUT] HEADER -->
    <!-- ================================== -->

    <!-- header id is only for js, don't add more class names, because js changes it back to 'header'
        on scroll down the class name will change to scroll-header
     -->
    <header id="header" class="header">
        <div id="green-line-left"> </div>
        <div id="yellow-line-right"> </div>
        <div class="container clearfix relative">
            <!-- [LAYOUT.HEADER] LOGO -->

            <a id="logo" class="inline-group link" href="/#home">
                <h1 class="inline site" about="mainEntity">
                    <img src="/assets/img/logo.png" class="logo-img">
                </h1>
            </a>

            <!-- [LAYOUT.HEADER] MENU -->

            <nav id="menu">
                <ul class="inline-group list">
                    <li class="inline item">
                        <a class="link link-portfolio" href="/#rolunk">Rólunk</a>
                    </li>
                    <li class="inline item">
                        <a class="link link-services" href="/#szolgaltatasunk">Szolgáltatásunk</a>
                    </li>
                    <li class="inline item">
                        <a class="link link-about" href="/#miert-a-soron">Miért a Soron?</a>
                    </li>
                    <li class="inline item">
                        <a class="link link-partners" href="/#partnereink">Partnereink</a>
                    </li>
                    <li class="inline item">
                        <a class="link link-footer" href="/#kapcsolat">Kapcsolat</a>
                    </li>
                </ul>
            </nav>

        </div>
    </header>

    <!-- [SCHEMA] HTML+RDF -->

    <div class="page-info" role="contentinfo">

        <!-- [SCHEMA.HTML+RDF] CREATOR -->

        <div property="creator" typeof="Organization">
            <?php $name = $dev->getName(); ?>
            <meta property="name" content="<?=$name?>"/>
            <meta property="legalName" content="<?=$dev->getName(true)?>"/>
            <a href="<?=$dev->getLink()?>" property="url" content="<?=$dev->getLink()?>" title="<?="{$name} - {$dev->getText('typo')}"?>"><?=$name?></a>
        </div>

        <!-- [SCHEMA.HTML+RDF] LICENSE -->

        <a rel="license" href="http://creativecommons.org/licenses/by/4.0/">CC-BY/4</a>

        <!-- [SCHEMA.HTML+RDF] COPYRIGHT -->

        <small class="info-legal" property="copyrightYear" content="<?=$year?>" rel="copyright">&copy; <?=$year?>, Minden jog fenntartva!</small>

        <!-- [SCHEMA.HTML+RDF] ACCESSIBILITY -->

        <meta property="accessibilityAPI" content="<?=$access['api']?>"/>

        <?php foreach ($access['hazard'] as $option) echo '<meta property="accessibilityHazard" content="'.$option.'"/>' ?>
        <?php foreach ($access['control'] as $option) echo '<meta property="accessibilityControl" content="full'.$option.'Control"/>' ?>
    </div>

    <div id="blue-line" class="line"> </div>
    <div id="blue-line-right" class="line"> </div>
    <div id="green-line-right" class="line"> </div>

    <!-- [SCRIPTS] -->

    <script src="/src/js/app.js" defer="defer"></script>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
      ga('create', 'UA-72245470-1', 'auto');
      ga('send', 'pageview');
    </script>
</body>
</html>
