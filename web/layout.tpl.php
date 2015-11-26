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
    "Beltáv Kft.",
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
    "Trilobita Informatikai Rt.",
    "G4S Biztonsági Szolgáltatások Zrt.",
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

    <!--
    <section class="block part-headline" id="home" tabindex="<?=$page->getTabIndex()?>">
        <div class="back" property="primaryImageOfPage" typeof="ImageObject"><meta property="url" content="<?=Http::getHost()."{$meta->lead}"?>"/></div>
        <div class="fade"></div>
        <div class="text">
            <h2 class="title">
                <span>Felelősség-, teljes telephelyi- és gépjármű flottára szóló</span>
                <span class="lead">biztosítások</span>
            </h2>
            <strong class="lead">Hosszú távon</strong>
            <em class="shape">Szakértelmünk és tapasztalatunk alapján a piac teljes kínálatának áttekintése után választjuk ki az ügyfél számára legmegfelelőbb konstrukciót</em>
        </div>
    </section> 
    -->
    <!--
    <section class="block part-headline" id="home" tabindex="<?=$page->getTabIndex()?>">
        <div class="background">
            <div class="text">
                 <h6 class="title">
                    <span>Felelősség-, teljes telephelyi- és gépjármű flottára szóló </span>
                    <span class="lead">biztosítások</span>
                </h6>
                <div><strong class="lead">Hosszú távon</strong></div>
                <em class="shape">Szakértelmünk és tapasztalatunk alapján a piac teljes kínálatának áttekintése után választjuk ki az ügyfél számára legmegfelelőbb konstrukciót</em>
            </div>
        </div>
    </section>
    -->

    <section class="part-headline" id="home" tabindex="<?=$page->getTabIndex()?>">
        <div id="sub-home">
            <div id="home-text" class="left">
                <div id="headline-title-1" class="left">
                    <span>Felelősség- teljes telephelyi-, gépjármű flottára szóló </span>
                    <span class="white bold">biztosítások</span>
                </div>
                <div id="headline-title-2" class="capital white">
                    <div class="md-3 inline-block center left-points">...</div>
                    <div class="md-3 inline-block left" id="the-title">Hosszú távon</div>
                    <div class="md-3 inline-block left">.......</div>
                </div>
                <div>
                    <div class="md-3 inline-block left-points"></div>
                    <div class="md-4 inline-block left" id="headline-title-3">
                        Szakértelmünk és<br />
                        tapasztalatunk alapján a plac<br />
                        teljes kínálatának áttekintése<br/>
                        után választjuk ki az ügyfél<br />
                        számára legmegfelelőbb<br />
                        konstrukciót
                    </div>
                    <div class="md-3 inline-block"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- [LAYOUT.CONTENT] PORTFOLIO -->

    <section class="block part-portfolio" id="rolunk" tabindex="<?=$page->getTabIndex()?>">
        <div class="wrapper bg">
            <input class="hidden t-state" id="x-p" type="checkbox" aria-hidden="true"/>

            <div class="container">
                <h3 class="title" id="about-us-title">Rólunk</h3>

                <div class="content about-us-content">
                    <p>Cégünk a Soron Magyarország Alkusz 2003. február 21.-én kapta meg az engedélyt a Pénzügyi Szervezetek Állami Felügyeletétől, biztosítási alkuszi tevékenység folytatására.
                    <br />
                    A társaság alapítói 1997 óta foglalkoznak biztosítási tanácsadással. A társaság megalapításának a célja, egy olyan független, professzionális tanácsadócég létrehozása volt, amely a nemzetközi elvárásoknak megfelelő biztosítási tanácsadói szolgáltatásokat nyújt, elsősorban cégeknek.</p>
                </div>
                <div class="content t-target about-us-content" id="about-us-footer">
                    <p>Társaságunk többéves biztosításszakmai gyakorlattal rendelkező tulajdonosokból és munkatársakból áll, amely biztosítéka az ügyfeleket mind teljesebben kiszolgálni tudó munkánknak.</p>
                    <p>Partneri kapcsolatainkat a bizalomra építjük, legfontosabb mércénk ügyfeleink hosszú távú elégedettsége. Alapvető értékeink közé tartozik a megbízhatóság, szakértelem, előrelátás és gondoskodás. Nagy hangsúlyt helyezünk a folyamatos képzésre, hogy ügyfeleink számára valóban értékes szakmai segítséget nyújthassunk.</p>
                    <p><strong>Fő tevékenységi körünk a vállalkozásoknak nyújtott szakmai-, és tevékenységükhöz kapcsolódó felelősségbiztosítások, teljes telephelyi-, gépjármű flottára szóló biztosítások. Egyedülálló megoldásokkal rendelkezünk vagyonkezelő, és társasházkezelők részére.</strong></p>
                    <p>Az általunk kínált szolgáltatások legnagyobb előnye, hogy szakértelmünk és tapasztalatunk alapján a piac teljes kínálatának áttekintése után választjuk ki az ügyfél számára legmegfelelőbb konstrukciót.</p>
                    <p>A Soron Magyarország Alkusznál kiemelt hangsúlyt kap az ügyfelek valós kockázatának felmérése alapján, személyes, egyedi igényeinek figyelembe vétele és maradéktalan kiszolgálása.</p>
                    <p><strong>Káresemény esetén tanácsot adunk, illetve közreműködünk a szakszerű és gyors kárrendezésben.</strong></p>
                </div>
            </div>

            <div class="button-holder">
                <label for="x-p" class="link t-trigger" id="first-button" aria-hidden="true" tabindex="<?=$page->getTabIndex()?>">
                    <span class="text show">Bővebben</span>
                    <span class="text hide">Bezár</span>
                </label>
            </div>
        </div>
    </section>

    <!-- [LAYOUT.CONTENT] ILLUMINATION -->

    <div class="block part-illumination" role="presentation" aria-hidden="true" tabindex="-1"></div>

    <!-- [LAYOUT.CONTENT] SERVICES -->

    <section class="block part-services" id="szolgaltatasunk" tabindex="<?=$page->getTabIndex()?>">
        <div class="wrapper bg">
            <input class="hidden t-state" id="x-s" type="checkbox" aria-hidden="true"/>

            <div class="container">
                <h2 class="title">Szolgáltatásunk</h2>

                <div class="inline xs-12 sm-6">
                    <h4 class="title-sub">Ügyfeleink az alábbi szolgáltatásokat kapják</h4>

                    <ul class="list list-text">
                        <li class="item">biztosítási szolgáltatások</li>
                        <li class="item">kockázatfelmérés és kockázatelemzés</li>
                        <li class="item">a kockázatok csökkentésére, ill. kiküszöbölésére vonatkozó módszerek kidolgozása</li>
                    </ul>
                    <ul class="list list-text t-target">
                        <li class="item">az elkerülhetetlen kockázatok áthárítására biztosítási program kialakítása</li>
                        <li class="item">a biztosítási programban megfogalmazott kockázatokra vonatkozó biztosítási fedezet megszervezése a biztosítók versenyeztetésével</li>
                        <li class="item">a biztosítók ajánlatainak elemzése a biztosított részére, a biztosító által nyújtott szolgáltatások és feltételrendszerek, valamint a biztosítási díj figyelembevételével</li>
                        <li class="item">biztosítottal egyeztetett program alapján a szerződések megkötése</li>
                    </ul>
                </div>
                <div class="inline xs-12 sm-6">
                    <h4 class="title-sub">A partneri kapcsolat fennállása alatt az alábbi szolgáltatásokat kínáljuk</h4>

                    <ul class="list list-text">
                        <li class="item">a felmerülő új biztosítási igények szakszerű megfogalmazása</li>
                        <li class="item">Partnerünk meglévő biztosítási szerződéseinek kezelése, felülvizsgálata és az esetlegesen szükséges módosításokra vonatkozó javaslatok kidolgozása</li>
                        <li class="item">káresemény alkalmával szakszerű felvilágosítás nyújtása a követendő eljárásról</li>
                    </ul>
                    <ul class="list list-text t-target">
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

    <section class="block part-about" id="miert-a-soron" tabindex="<?=$page->getTabIndex()?>">
        <div class="wrapper bg">
            <div class="container">
                <h3 class="title">Miért a Soron Magyarország Alkusz?</h3>

                <ul class="list list-text">
                    <li class="item">Mert munkatársaink egytől egyig többéves biztosítónál szerzett értékesítési tapasztalattal rendelkeznek.</li>
                    <li class="item">Mert a Soron Magyarország Alkusz adminisztratív, informatikai, továbbá 15 éves szaktanácsadói hátterével garantálja ügyfeleinek a magas színvonalú kiszolgálását.</li>
                    <li class="item">Mert a többéves biztosítási szakmában eltöltött idõ alatt kiépült kapcsolati rendszerünk megalapozta a gyorsabb, rugalmasabb és eredményesebb ügyintézést minden területen (értékesítési, kárrendezési, informatikai, pénzügyi, szerződéskezelési).</li>
                    <li class="item"><strong>Mert az eddigi működésünk alatt hozzánk forduló ügyfelek megismerve különleges, ügyfélbarát szolgáltatási rendszerünket, minket választottak és mellettünk döntöttek egy hosszú távú együttműködésben.</strong></li>
                </ul>
            </div>
        </div>
    </section>

    <!-- [LAYOUT.CONTENT] PARTNERS -->

    <section class="block part-partners" id="partnereink" tabindex="<?=$page->getTabIndex()?>">
        <div class="wrapper bg">
            <div class="container">
                <h3 class="title title-big">Partnereink</h3>

                <ul class="list">
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

    <footer id="kapcsolat" class="block part-footer" property="mainEntity" typeof="InsuranceAgency" tabindex="<?=$page->getTabIndex()?>">
        <div class="wrapper bg">
            <?php
                $about = "#{$org->getSlug()}";
            ?>
            <div class="container decorated">

                <!-- [LAYOUT.FOOTER] CONTACT -->

                <div class="inline xs-12 md-4">
                    <h4 class="inline col title xs-12 sm-6 md-12">Kapcsolat</h4>

                    <div class="inline col content xs-12 sm-6 md-12">
                        <strong class="company" property="legalName"><?=$org->getName(true)?></strong>
                        <dl class="term clearfix">
                            <dt class="name">Telefon</dt>
                            <dd class="data" property="telephone">+36 (1) 220-9393</dd>
                            <dt class="name">Fax</dt>
                            <dd class="data" property="faxNumber">+36 (1) 220-3400</dd>
                            <dt class="name">E-mail</dt>
                            <dd class="data">
                                <a class="link" href="mailto:info@soron.hu" property="email" content="info@soron.hu">info@soron.hu</a>
                            </dd>
                        </dl>
                    </div>
                </div>

                <!-- [LAYOUT.FOOTER] ADDRESS -->

                <div class="inline col xs-12 sm-6 md-4">
                    <h4 class="title">Cégünk székhelye</h4>

                    <address class="content" property="address" typeof="PostalAddress">
                        <meta property="addressCountry" content="Hungary"/>
                        <span property="postalCode">1142</span>
                        <span property="addressLocality">Budapest</span>
                        <span property="streetAddress">Sárrét park 5/A.</span>
                    </address>

                    <h4 class="title">Irodánk címe</h4>

                    <address class="content" property="location" typeof="PostalAddress">
                        <meta property="addressCountry" content="Hungary"/>
                        <span property="postalCode">1093</span>
                        <span property="addressLocality">Budapest</span>
                        <span property="streetAddress">Lónyay u. 36.</span>
                    </address>

                </div>

                <!-- [LAYOUT.FOOTER] OPENING-HOURS -->

                <div class="inline col xs-12 sm-6 md-4 opening">
                    <h4 class="title">Nyitvatartás</h4>

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

                <div class="inline col xs-12 sm-6 md-4">
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

                <div class="inline col xs-12 sm-6 md-4">
                    <strong class="bank">Budapest Bank</strong>
                    <dl class="term clearfix">
                        <dt class="name">Főszámla</dt>
                        <dd class="data">10100840-57517600-01000008</dd>
                        <dt class="name">Ügyfélszámla</dt>
                        <dd class="data">10100840-57517600-02000001</dd>
                    </dl>
                </div>

                <!-- [LAYOUT.FOOTER] DOWNLOADABLE CONTENT -->

                <div class="inline col xs-12 md-4 docs">
                    <a href="/assets/other_files/fogyasztoi_panaszbejelento.pdf" class="link" tabindex="<?=$page->getTabIndex()?>">Fogyasztói panaszbejelentő</a>
                    <a href="/assets/other_files/panaszkezelesi_szabalyzat_soron.pdf" class="link" tabindex="<?=$page->getTabIndex()?>">Panaszkezelési információk</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ================================== -->
    <!-- [LAYOUT] HEADER -->
    <!-- ================================== -->

    <header id="header">
        <div class="container clearfix">

            <!-- [LAYOUT.HEADER] LOGO -->

            <a id="logo" class="inline-group link" href="/#home">
                <h1 class="inline site" about="mainEntity">
                    <strong class="name" property="name">Soron</strong>
                    <small class="type"><?=$org->getText('typo')?></small>
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

    <!-- [SCRIPTS] -->

    <script src="/src/js/app.js" defer="defer"></script>
</body>
</html>
