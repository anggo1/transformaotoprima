<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$a = isset($aplikasi) && $aplikasi ? $aplikasi : (object) array('title' => 'TOP', 'nama_aplikasi' => 'Transforma Oto Prima',
    'nama_owner' => '', 'copy_right' => 'Copyright &copy;', 'tahun' => date('Y'), 'status' => '', 'lokasi' => '', 'versi' => '');
$nama   = trim(preg_replace('/^PT\.?\s*/i', '', $a->nama_owner ?: $a->nama_aplikasi));
$kata   = preg_split('/\s+/', $nama);
$baris1 = $kata[0] ?? $nama;
$baris2 = implode(' ', array_slice($kata, 1));
$lokasi = trim($a->lokasi . (isset($a->kota) && $a->kota ? ', ' . $a->kota : ''), ', ');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#15181B">
    <title><?php echo html_escape($a->title); ?> | Masuk</title>
    <link rel="icon" href="<?php echo base_url('assets/dist/img/Logo.png'); ?>" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:opsz,wght@6..96,400;6..96,500&family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --night: #15181B; --night-2: #1C2024; --graphite: #2A2F35;
            --silver: #C9CED3; --silver-hi: #EEF0F2; --silver-lo: #8A9097;
            --hair: rgba(201, 206, 211, .18); --hair-strong: rgba(201, 206, 211, .38);
            --danger: #F2A49B; --ok: #A9DCC0;
            --serif: 'Bodoni Moda', 'Didot', 'Bodoni 72', Georgia, serif;
            --sans: 'Manrope', 'Segoe UI', Roboto, Arial, sans-serif;
        }
        * { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; }
        body {
            font-family: var(--sans); color: var(--silver); -webkit-font-smoothing: antialiased;
            background:
                radial-gradient(900px 520px at 38% -8%, rgba(238, 240, 242, .13), transparent 62%),
                linear-gradient(180deg, var(--night-2) 0%, var(--night) 58%, #0F1113 100%);
            background-color: var(--night);
        }
        .stage { min-height: 100%; display: grid; grid-template-rows: auto 1fr auto auto; padding: 2.2rem clamp(1.25rem, 4vw, 4rem) 1.6rem; position: relative; overflow: hidden; }

        /* kepala */
        .top { display: flex; align-items: center; justify-content: space-between; gap: 1rem; position: relative; z-index: 2; }
        .mark { display: flex; align-items: center; gap: .9rem; }
        .mark img { width: 42px; height: 42px; object-fit: contain; border-radius: 50%; background: var(--silver-hi); padding: 3px; }
        .mark strong { display: block; color: var(--silver-hi); font-weight: 600; font-size: .92rem; letter-spacing: .01em; }
        .mark span { color: var(--silver-lo); font-size: .8rem; }
        .top__loc { color: var(--silver-lo); font-size: .82rem; text-align: right; }

        /* tengah */
        .main { display: grid; grid-template-columns: minmax(0, 1.25fr) minmax(320px, 400px); gap: clamp(2rem, 5vw, 5rem); align-items: end; position: relative; z-index: 2; padding: 2rem 0 0; }
        .intro h1 {
            font-family: var(--serif); font-weight: 400; color: var(--silver-hi); margin: 0 0 1.4rem;
            font-size: clamp(3rem, 7.2vw, 6.6rem); line-height: .95; letter-spacing: -.015em;
        }
        .intro h1 span { display: block; }
        .intro h1 .second { color: transparent; -webkit-text-stroke: 1px var(--silver); }
        .intro p { margin: 0; max-width: 34rem; font-size: 1.02rem; line-height: 1.65; color: var(--silver); }

        /* form */
        .card {
            background: linear-gradient(180deg, rgba(42, 47, 53, .72), rgba(28, 32, 36, .72));
            border: 1px solid var(--hair); border-radius: 18px; padding: 2.2rem 2rem 1.8rem;
            box-shadow: 0 30px 80px rgba(0, 0, 0, .45), inset 0 1px 0 rgba(238, 240, 242, .06);
            -webkit-backdrop-filter: blur(14px); backdrop-filter: blur(14px);
        }
        .card h2 { font-family: var(--serif); font-weight: 400; font-size: 2.1rem; color: var(--silver-hi); margin: 0 0 .4rem; }
        .card .lead { margin: 0 0 1.9rem; color: var(--silver-lo); font-size: .9rem; line-height: 1.5; }

        .field { margin-bottom: 1.35rem; }
        .field label { display: block; font-size: .82rem; font-weight: 600; color: var(--silver); margin-bottom: .2rem; }
        .control { position: relative; }
        .control input {
            width: 100%; height: 46px; border: 0; border-bottom: 1px solid var(--hair-strong); border-radius: 0; background: transparent;
            padding: 0 2.6rem 0 0; font: inherit; font-size: 1.02rem; color: var(--silver-hi); outline: none; transition: border-color .2s;
        }
        .control input:hover { border-bottom-color: var(--silver-lo); }
        .control input:focus { border-bottom-color: var(--silver-hi); box-shadow: 0 1px 0 0 var(--silver-hi); }
        .control input.is-invalid { border-bottom-color: var(--danger); box-shadow: 0 1px 0 0 var(--danger); }
        .control input:-webkit-autofill { -webkit-text-fill-color: var(--silver-hi); -webkit-box-shadow: 0 0 0 40px #23282D inset; caret-color: var(--silver-hi); }
        .toggle-pass {
            position: absolute; right: -.4rem; top: 50%; transform: translateY(-50%); width: 40px; height: 40px;
            border: 0; border-radius: 50%; background: transparent; color: var(--silver-lo); cursor: pointer; display: grid; place-items: center;
        }
        .toggle-pass:hover { color: var(--silver-hi); background: rgba(238, 240, 242, .06); }
        .toggle-pass svg { width: 19px; height: 19px; }
        .toggle-pass .off { display: none; }
        .toggle-pass[aria-pressed="true"] .on { display: none; }
        .toggle-pass[aria-pressed="true"] .off { display: block; }
        .invalid { color: var(--danger); font-size: .8rem; margin-top: .4rem; }
        .invalid:empty { display: none; }
        .caps { display: none; color: #E8C98E; font-size: .8rem; margin-top: .4rem; }

        .row-opt { margin: .1rem 0 1.7rem; font-size: .86rem; color: var(--silver-lo); }
        .row-opt label { display: inline-flex; align-items: center; gap: .55rem; cursor: pointer; }
        .row-opt input { accent-color: var(--silver); width: 16px; height: 16px; margin: 0; }

        .btn {
            width: 100%; height: 52px; border: 0; border-radius: 999px; cursor: pointer;
            background: linear-gradient(180deg, #F4F5F6 0%, #C9CED3 100%); color: #15181B;
            font: inherit; font-weight: 700; font-size: .98rem; letter-spacing: .02em;
            display: inline-flex; align-items: center; justify-content: center; gap: .65rem;
            box-shadow: 0 10px 28px rgba(0, 0, 0, .35), inset 0 1px 0 rgba(255, 255, 255, .7);
            transition: filter .15s, transform .05s;
        }
        .btn:hover { filter: brightness(1.06); }
        .btn:active { transform: translateY(1px); }
        .btn[disabled] { cursor: progress; filter: saturate(.6) brightness(.92); }
        .spin { width: 17px; height: 17px; border: 2px solid rgba(21, 24, 27, .25); border-top-color: #15181B; border-radius: 50%; animation: s .7s linear infinite; display: none; }
        .btn.loading .spin { display: inline-block; }
        @keyframes s { to { transform: rotate(360deg); } }

        :focus-visible { outline: 2px solid var(--silver-hi); outline-offset: 3px; }
        .control input:focus-visible { outline: none; }

        .alert { display: none; padding: .75rem .9rem; font-size: .88rem; line-height: 1.45; margin-bottom: 1.4rem; border-radius: 10px; border: 1px solid; }
        .alert.show { display: block; }
        .alert--err { color: var(--danger); border-color: rgba(242, 164, 155, .35); background: rgba(242, 164, 155, .07); }
        .alert--ok { color: var(--ok); border-color: rgba(169, 220, 192, .35); background: rgba(169, 220, 192, .07); }

        /* siluet di lantai pamer */
        .floor { position: relative; height: clamp(150px, 24vh, 230px); margin: 1.5rem calc(-1 * clamp(1.25rem, 4vw, 4rem)) 1.4rem; pointer-events: none; }
        .floor::before {
            content: ''; position: absolute; left: 0; right: 0; top: 62%; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(201, 206, 211, .32) 20%, rgba(201, 206, 211, .32) 80%, transparent);
        }
        .floor svg { position: absolute; left: clamp(1.25rem, 4vw, 4rem); height: 62%; width: auto; bottom: 38%; overflow: visible; }
        .floor svg.reflect { bottom: auto; top: 62%; height: 62%; transform: scaleY(-1); transform-origin: top; opacity: .18;
            -webkit-mask-image: linear-gradient(0deg, transparent 45%, #000); mask-image: linear-gradient(0deg, transparent 45%, #000); }
        .bus path, .bus rect, .bus circle, .bus line { fill: none; stroke: var(--silver); stroke-width: 1.15; stroke-linecap: round; stroke-linejoin: round; opacity: .55; }
        .bus .glass { fill: rgba(201, 206, 211, .035); }
        .draw-in { stroke-dasharray: 1700; stroke-dashoffset: 1700; animation: draw 2.4s cubic-bezier(.55, .1, .2, 1) .2s forwards; }
        @keyframes draw { to { stroke-dashoffset: 0; } }

        /* kaki */
        .bottom { display: flex; justify-content: space-between; align-items: flex-end; gap: 1.5rem; position: relative; z-index: 2; font-size: .82rem; color: var(--silver-lo); }
        .pillars { display: flex; flex-wrap: wrap; margin: 0; padding: 0; list-style: none; }
        .pillars li { padding: 0 1.1rem; border-left: 1px solid var(--hair-strong); color: var(--silver); }
        .pillars li:first-child { padding-left: 0; border-left: 0; }

        @media (max-width: 980px) {
            .main { grid-template-columns: 1fr; padding: 2rem 0 2.2rem; }
            .intro h1 { font-size: clamp(2.6rem, 11vw, 4.4rem); }
            .floor { display: none; }
            .top__loc { display: none; }
        }
        @media (max-width: 560px) {
            .stage { padding: 1.4rem 1rem 1.2rem; }
            .intro p { font-size: .95rem; }
            .card { padding: 1.8rem 1.25rem 1.5rem; border-radius: 14px; }
            .bottom { flex-direction: column; align-items: flex-start; }
            .pillars li { padding: 0 .7rem; }
        }
        @media (max-height: 780px) and (min-width: 981px) {
            .stage { padding-top: 1.6rem; padding-bottom: 1.1rem; }
            .main { padding-top: 1rem; }
            .intro h1 { font-size: clamp(3rem, 6vw, 5.2rem); margin-bottom: 1rem; }
            .card { padding: 1.8rem 1.8rem 1.5rem; }
            .card .lead { margin-bottom: 1.3rem; }
            .floor { height: 120px; margin-top: .8rem; margin-bottom: .8rem; }
        }
        @media (prefers-reduced-motion: reduce) {
            .draw-in { animation: none; stroke-dashoffset: 0; }
            * { transition: none !important; }
        }
    </style>
</head>
<body>
<?php ob_start(); ?>
<g class="bus">
    <path class="draw-in" d="M70 300 V200 Q70 180 82 160 L112 116 Q118 108 130 108 H820 Q842 108 842 130 V300 H794 M622 300 H278 M186 300 H70"/>
    <path class="draw-in glass" d="M78 196 L114 122 Q117 117 124 117 H140 V196 Z"/>
    <rect class="draw-in glass" x="182" y="122" width="96" height="68" rx="4"/>
    <rect class="draw-in glass" x="290" y="122" width="96" height="68" rx="4"/>
    <rect class="draw-in glass" x="398" y="122" width="96" height="68" rx="4"/>
    <rect class="draw-in glass" x="506" y="122" width="96" height="68" rx="4"/>
    <rect class="draw-in glass" x="614" y="122" width="96" height="68" rx="4"/>
    <rect class="draw-in glass" x="722" y="122" width="108" height="68" rx="4"/>
    <rect class="draw-in" x="148" y="122" width="26" height="174" rx="3"/>
    <line class="draw-in" x1="70" y1="212" x2="842" y2="212"/>
    <rect class="draw-in" x="292" y="226" width="96" height="58" rx="3"/>
    <rect class="draw-in" x="400" y="226" width="96" height="58" rx="3"/>
    <rect class="draw-in" x="508" y="226" width="96" height="58" rx="3"/>
    <path class="draw-in" d="M186 300 A46 46 0 0 1 278 300"/>
    <path class="draw-in" d="M622 300 A46 46 0 0 1 711 290"/><path class="draw-in" d="M705 290 A46 46 0 0 1 794 300"/>
    <circle class="draw-in" cx="232" cy="306" r="34"/><circle class="draw-in" cx="232" cy="306" r="14"/>
    <circle class="draw-in" cx="668" cy="306" r="34"/><circle class="draw-in" cx="668" cy="306" r="14"/>
    <circle class="draw-in" cx="748" cy="306" r="34"/><circle class="draw-in" cx="748" cy="306" r="14"/>
    <rect class="draw-in" x="72" y="262" width="22" height="12" rx="2"/>
</g>
<?php $bus = ob_get_clean(); ?>
<?php ob_start(); ?>
<g class="bus truck">
    <path class="draw-in" d="M44 300 V178 Q44 152 58 138 L76 120 Q82 112 94 112 H200 Q214 112 214 126 V282 H164 M72 300 H44"/>
    <path class="draw-in glass" d="M52 182 L74 134 Q77 128 84 128 H104 V182 Z"/>
    <rect class="draw-in glass" x="116" y="128" width="84" height="54" rx="4"/>
    <path class="draw-in" d="M110 124 V270 H164"/>
    <line class="draw-in" x1="44" y1="214" x2="214" y2="214"/>
    <rect class="draw-in" x="40" y="252" width="14" height="40" rx="3"/>
    <line class="draw-in" x1="124" y1="282" x2="156" y2="282"/>
    <rect class="draw-in" x="222" y="132" width="8" height="128" rx="3"/>
    <rect class="draw-in" x="214" y="262" width="560" height="16" rx="2"/>
    <line class="draw-in" x1="214" y1="270" x2="774" y2="270"/>
    <rect class="draw-in" x="250" y="236" width="96" height="40" rx="10"/>
    <rect class="draw-in" x="370" y="244" width="64" height="26" rx="13"/>
    <rect class="draw-in" x="452" y="248" width="40" height="22" rx="4"/>
    <line class="draw-in" x1="300" y1="278" x2="300" y2="290"/><line class="draw-in" x1="520" y1="278" x2="520" y2="290"/>
    <path class="draw-in" d="M72 300 A46 46 0 0 1 164 300"/>
    <path class="draw-in" d="M576 284 A42 42 0 0 1 660 284"/><path class="draw-in" d="M660 284 A42 42 0 0 1 744 284"/>
    <circle class="draw-in" cx="118" cy="306" r="34"/><circle class="draw-in" cx="118" cy="306" r="14"/>
    <circle class="draw-in" cx="618" cy="306" r="34"/><circle class="draw-in" cx="618" cy="306" r="14"/>
    <circle class="draw-in" cx="702" cy="306" r="34"/><circle class="draw-in" cx="702" cy="306" r="14"/>
    <rect class="draw-in" x="766" y="282" width="10" height="18" rx="2"/>
</g>
<?php $truck = ob_get_clean(); ?>
    <div class="stage">
        <header class="top">
            <div class="mark">
                <img src="<?php echo base_url('assets/dist/img/Logo.png'); ?>" alt="">
                <div>
                    <strong><?php echo html_escape($a->nama_owner ?: $a->nama_aplikasi); ?></strong>
                    <span>Mercedes-Benz Truck &amp; Bus</span>
                </div>
            </div>
            <?php if ($lokasi): ?><div class="top__loc"><?php echo html_escape($lokasi); ?></div><?php endif; ?>
        </header>

        <main class="main">
            <section class="intro">
                <h1><span><?php echo html_escape($baris1); ?></span><?php if ($baris2): ?><span class="second"><?php echo html_escape($baris2); ?></span><?php endif; ?></h1>
                <p>Dealer resmi Mercedes-Benz Truck &amp; Bus dalam jaringan PT Daimler Commercial Vehicles Indonesia. Penjualan, servis, suku cadang, dan body repair dikelola dalam satu sistem.</p>
            </section>

            <section class="card" aria-labelledby="judul">
                <h2 id="judul">Masuk</h2>
                <p class="lead">Gunakan akun yang diberikan administrator.</p>

                <div class="alert alert--err" id="alert" role="alert" aria-live="assertive"><span></span></div>

                <form id="quickForm" method="post" novalidate autocomplete="on">
                    <div class="field">
                        <label for="username">Username</label>
                        <div class="control">
                            <input type="text" id="username" name="username" autocomplete="username" autocapitalize="none" spellcheck="false" autofocus>
                        </div>
                        <div class="invalid" data-for="username"></div>
                    </div>
                    <div class="field">
                        <label for="password">Password</label>
                        <div class="control">
                            <input type="password" id="password" name="password" autocomplete="current-password">
                            <button type="button" class="toggle-pass" id="togglePass" aria-label="Tampilkan password" aria-pressed="false">
                                <svg class="on" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg class="off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3l18 18"/><path d="M10.6 5.1A10.4 10.4 0 0 1 12 5c6.4 0 10 7 10 7a17 17 0 0 1-3.2 4.1M6.6 6.6C3.9 8.4 2 12 2 12s3.6 7 10 7a10 10 0 0 0 5.4-1.6"/><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"/></svg>
                            </button>
                        </div>
                        <div class="caps" id="caps">Caps Lock sedang aktif.</div>
                        <div class="invalid" data-for="password"></div>
                    </div>
                    <div class="row-opt">
                        <label><input type="checkbox" id="remember"> Ingat username di komputer ini</label>
                    </div>
                    <button type="submit" id="login" class="btn"><span class="spin"></span><span class="lbl">Masuk</span></button>
                </form>
            </section>
        </main>

        <div class="floor" aria-hidden="true">
            <svg viewBox="30 100 1660 242"><?php echo $truck; ?><g transform="translate(840 0)"><?php echo $bus; ?></g></svg>
            <svg class="reflect" viewBox="30 100 1660 242"><?php echo $truck; ?><g transform="translate(840 0)"><?php echo $bus; ?></g></svg>
        </div>

        <footer class="bottom">
            <ul class="pillars" aria-label="Layanan dealer">
                <li>Penjualan</li><li>Servis</li><li>Suku cadang</li><li>Body repair</li>
            </ul>
            <div><?php echo $a->copy_right; ?> <?php echo html_escape($a->tahun); ?> <?php echo html_escape($a->nama_owner); ?><?php echo $a->versi ? ', versi ' . html_escape($a->versi) : ''; ?></div>
        </footer>
    </div>

    <script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
    <script>
    (function ($) {
        var $f = $('#quickForm'), $btn = $('#login'), $alert = $('#alert'), KEY = 'top.login.user';
        try { var u = localStorage.getItem(KEY); if (u) { $('#username').val(u); $('#remember').prop('checked', true); $('#password').trigger('focus'); } } catch (e) {}

        function pesan(tipe, txt) {
            $alert.removeClass('alert--err alert--ok').addClass(tipe === 'ok' ? 'alert--ok' : 'alert--err').find('span').text(txt).end().addClass('show');
        }
        function resetErr() { $alert.removeClass('show'); $f.find('input').removeClass('is-invalid').removeAttr('aria-invalid'); $f.find('.invalid').text(''); }
        function loading(on) { $btn.prop('disabled', on).toggleClass('loading', on).find('.lbl').text(on ? 'Memeriksa akun' : 'Masuk'); }

        $('#togglePass').on('click', function () {
            var $p = $('#password'), show = $p.attr('type') === 'password';
            $p.attr('type', show ? 'text' : 'password');
            $(this).attr({ 'aria-pressed': show ? 'true' : 'false', 'aria-label': show ? 'Sembunyikan password' : 'Tampilkan password' });
        });
        $('#password').on('keyup keydown', function (e) {
            if (e.originalEvent && e.originalEvent.getModifierState) $('#caps').toggle(e.originalEvent.getModifierState('CapsLock'));
        });
        $f.on('input', 'input', function () { $(this).removeClass('is-invalid').removeAttr('aria-invalid'); $f.find('.invalid[data-for="' + this.name + '"]').text(''); });

        $f.on('submit', function (e) {
            e.preventDefault();
            resetErr(); loading(true);
            $.ajax({ method: 'POST', url: '<?php echo site_url('Login/login'); ?>', data: $f.serialize(), dataType: 'text' })
                .done(function (raw) {
                    var data; try { data = JSON.parse(raw); } catch (er) { pesan('err', 'Server mengirim jawaban yang tidak dikenali. Muat ulang halaman lalu coba lagi.'); loading(false); return; }
                    if (data.status) {
                        try { if ($('#remember').is(':checked')) localStorage.setItem(KEY, $('#username').val()); else localStorage.removeItem(KEY); } catch (e2) {}
                        pesan('ok', 'Berhasil masuk. Membuka dashboard.');
                        window.location = '<?php echo site_url('dashboard'); ?>';
                        return;
                    }
                    loading(false);
                    if (data.error) { pesan('err', data.pesan); $('#password').val('').trigger('focus'); return; }
                    if (data.inputerror) {
                        for (var i = 0; i < data.inputerror.length; i++) {
                            $('[name="' + data.inputerror[i] + '"]').addClass('is-invalid').attr('aria-invalid', 'true');
                            $f.find('.invalid[data-for="' + data.inputerror[i] + '"]').text(data.error_string[i]);
                        }
                        $('.is-invalid').first().trigger('focus');
                    }
                })
                .fail(function () { loading(false); pesan('err', 'Server tidak bisa dihubungi. Periksa jaringan kantor lalu coba lagi.'); });
        });
    })(jQuery);
    </script>
</body>
</html>
