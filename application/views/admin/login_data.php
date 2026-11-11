<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$a = isset($aplikasi) && $aplikasi ? $aplikasi : (object) array('title' => 'TOP', 'nama_aplikasi' => 'Transforma Oto Prima',
    'nama_owner' => '', 'copy_right' => 'Copyright &copy;', 'tahun' => date('Y'), 'status' => '', 'lokasi' => '', 'versi' => '');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0f1b2d">
    <title><?php echo html_escape($a->title); ?> | Masuk</title>
    <link rel="icon" href="<?php echo base_url('assets/dist/img/Logo.png'); ?>" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/plugins/fontawesome-5.5.0/css/all.min.css'); ?>">
    <style>
        :root {
            --ink: #0f1b2d; --ink2: #16253b; --accent: #0e7c86; --accent2: #0b6a73; --gold: #c8a25a;
            --line: #e3e8ef; --text: #1d2939; --muted: #667085; --danger: #c0392b;
            --font: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
        }
        * { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; }
        body { font-family: var(--font); color: var(--text); background: #f3f5f9; -webkit-font-smoothing: antialiased; }
        .login { min-height: 100%; display: grid; grid-template-columns: 1.1fr 1fr; }

        /* panel merek */
        .brand {
            position: relative; overflow: hidden; color: #fff; padding: 3rem 3.5rem; display: flex; flex-direction: column; justify-content: space-between;
            background:
                radial-gradient(1200px 600px at -10% 110%, rgba(14, 124, 134, .45), transparent 60%),
                radial-gradient(900px 500px at 110% -10%, rgba(200, 162, 90, .18), transparent 55%),
                linear-gradient(160deg, #0f1b2d 0%, #0b1422 100%);
        }
        .brand::before {
            content: ''; position: absolute; inset: 0; opacity: .5; pointer-events: none;
            background-image: linear-gradient(rgba(255, 255, 255, .035) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, .035) 1px, transparent 1px);
            background-size: 44px 44px; -webkit-mask-image: linear-gradient(180deg, transparent, #000 30%, #000 70%, transparent); mask-image: linear-gradient(180deg, transparent, #000 30%, #000 70%, transparent);
        }
        .brand > * { position: relative; }
        .brand__top { display: flex; align-items: center; gap: .9rem; }
        .brand__logo { width: 52px; height: 52px; border-radius: 14px; background: #fff; padding: 6px; object-fit: contain; box-shadow: 0 8px 24px rgba(0, 0, 0, .25); }
        .brand__name { font-weight: 800; font-size: 1.05rem; letter-spacing: .02em; }
        .brand__loc { color: #9fb1c8; font-size: .8rem; }
        .brand__hero h1 { font-size: clamp(1.9rem, 3vw, 2.7rem); line-height: 1.15; margin: 0 0 1rem; font-weight: 800; letter-spacing: -.02em; }
        .brand__hero h1 span { color: #6fd3da; }
        .brand__hero p { color: #b6c3d4; max-width: 30rem; line-height: 1.65; margin: 0; }
        .brand__rule { width: 56px; height: 3px; background: var(--gold); border-radius: 3px; margin-bottom: 1.4rem; }
        .brand__modules { display: flex; flex-wrap: wrap; gap: .5rem; margin-top: 1.8rem; }
        .brand__modules span { border: 1px solid rgba(255, 255, 255, .14); background: rgba(255, 255, 255, .04); color: #d6e0ec; border-radius: 999px; padding: .35rem .8rem; font-size: .78rem; }
        .brand__foot { color: #7f93ae; font-size: .78rem; }

        /* panel form */
        .panel { display: flex; align-items: center; justify-content: center; padding: 2.5rem 1.5rem; }
        .card { width: 100%; max-width: 400px; }
        .card__mobile-logo { display: none; }
        .card h2 { font-size: 1.6rem; margin: 0 0 .35rem; font-weight: 800; letter-spacing: -.02em; }
        .card .lead { color: var(--muted); margin: 0 0 2rem; font-size: .92rem; }
        .field { margin-bottom: 1.1rem; }
        .field label { display: block; font-size: .8rem; font-weight: 700; color: #344054; margin-bottom: .45rem; }
        .control { position: relative; }
        .control > i.lead-ico { position: absolute; left: .95rem; top: 50%; transform: translateY(-50%); color: #98a2b3; font-size: .9rem; }
        .control input {
            width: 100%; height: 48px; border: 1.5px solid #d5dbe4; border-radius: 12px; background: #fff; padding: 0 2.8rem 0 2.6rem;
            font: inherit; font-size: .95rem; color: var(--text); outline: none; transition: border-color .15s, box-shadow .15s;
        }
        .control input:focus { border-color: var(--accent); box-shadow: 0 0 0 4px rgba(14, 124, 134, .14); }
        .control input.is-invalid { border-color: var(--danger); box-shadow: 0 0 0 4px rgba(192, 57, 43, .1); }
        .toggle-pass { position: absolute; right: .5rem; top: 50%; transform: translateY(-50%); border: 0; background: transparent; color: #98a2b3; width: 36px; height: 36px; border-radius: 8px; cursor: pointer; }
        .toggle-pass:hover { color: var(--text); background: #f2f4f7; }
        .invalid { color: var(--danger); font-size: .78rem; margin-top: .35rem; min-height: 1em; }
        .caps { display: none; color: #b7791f; font-size: .78rem; margin-top: .35rem; }
        .row-opt { display: flex; align-items: center; justify-content: space-between; margin: .3rem 0 1.4rem; font-size: .85rem; color: var(--muted); }
        .row-opt label { display: inline-flex; align-items: center; gap: .45rem; cursor: pointer; font-weight: 500; }
        .row-opt input { accent-color: var(--accent); width: 16px; height: 16px; }
        .btn {
            width: 100%; height: 48px; border: 0; border-radius: 12px; background: var(--ink); color: #fff; font: inherit; font-weight: 700; font-size: .95rem;
            cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: .6rem; transition: background .15s, transform .05s, box-shadow .15s;
            box-shadow: 0 10px 22px rgba(15, 27, 45, .18);
        }
        .btn:hover { background: #18294a; }
        .btn:active { transform: translateY(1px); }
        .btn[disabled] { opacity: .75; cursor: progress; }
        .spin { width: 18px; height: 18px; border: 2.5px solid rgba(255, 255, 255, .35); border-top-color: #fff; border-radius: 50%; animation: s .7s linear infinite; display: none; }
        .btn.loading .spin { display: inline-block; }
        .btn.loading .lbl-ico { display: none; }
        @keyframes s { to { transform: rotate(360deg); } }
        .alert { display: none; border-radius: 12px; padding: .8rem 1rem; font-size: .86rem; margin-bottom: 1.2rem; align-items: flex-start; gap: .6rem; }
        .alert.show { display: flex; }
        .alert--err { background: #fdecea; color: #8a2a20; border: 1px solid #f6c9c3; }
        .alert--ok { background: #e6f4ee; color: #0e6447; border: 1px solid #bfe3d3; }
        .foot { margin-top: 2.2rem; text-align: center; color: #98a2b3; font-size: .78rem; line-height: 1.6; }

        @media (max-width: 900px) {
            .login { grid-template-columns: 1fr; }
            .brand { display: none; }
            .panel { min-height: 100vh; background:
                radial-gradient(700px 300px at 50% -10%, rgba(14, 124, 134, .14), transparent 70%), #f3f5f9; }
            .card__mobile-logo { display: flex; align-items: center; gap: .7rem; margin-bottom: 2rem; }
            .card__mobile-logo img { width: 44px; height: 44px; border-radius: 12px; background: #fff; padding: 4px; box-shadow: 0 6px 16px rgba(15, 27, 45, .12); object-fit: contain; }
            .card__mobile-logo strong { display: block; }
            .card__mobile-logo small { color: var(--muted); }
        }
        @media (prefers-reduced-motion: reduce) { * { animation: none !important; transition: none !important; } }
    </style>
</head>

<body>
    <main class="login">
        <section class="brand" aria-hidden="true">
            <div class="brand__top">
                <img class="brand__logo" src="<?php echo base_url('assets/dist/img/Logo.png'); ?>" alt="">
                <div>
                    <div class="brand__name"><?php echo html_escape($a->nama_owner ?: $a->nama_aplikasi); ?></div>
                    <div class="brand__loc"><?php echo html_escape(trim($a->lokasi . ' ' . (isset($a->kota) ? '· ' . $a->kota : ''), ' ·')); ?></div>
                </div>
            </div>
            <div class="brand__hero">
                <div class="brand__rule"></div>
                <h1>Satu sistem untuk<br><span>bengkel, gudang &amp; keuangan</span>.</h1>
                <p><?php echo html_escape($a->status ?: 'Sistem informasi terpadu ' . $a->nama_aplikasi); ?></p>
                <div class="brand__modules">
                    <span>Marketing</span><span>After Sales</span><span>Warehouse</span><span>Accounting</span><span>HRMS</span>
                </div>
            </div>
            <div class="brand__foot"><?php echo $a->copy_right; ?> <?php echo html_escape($a->tahun); ?> <?php echo html_escape($a->nama_owner); ?><?php echo $a->versi ? ' · v' . html_escape($a->versi) : ''; ?></div>
        </section>

        <section class="panel">
            <div class="card">
                <div class="card__mobile-logo">
                    <img src="<?php echo base_url('assets/dist/img/Logo.png'); ?>" alt="">
                    <div><strong><?php echo html_escape($a->nama_aplikasi); ?></strong><small><?php echo html_escape($a->lokasi); ?></small></div>
                </div>

                <h2>Selamat datang</h2>
                <p class="lead">Masuk untuk melanjutkan ke <?php echo html_escape($a->nama_aplikasi); ?>.</p>

                <div class="alert alert--err" id="alert" role="alert"><i class="fas fa-exclamation-circle" style="margin-top:2px"></i><span></span></div>

                <form id="quickForm" method="post" novalidate autocomplete="on">
                    <div class="field">
                        <label for="username">Username</label>
                        <div class="control">
                            <i class="fas fa-user lead-ico"></i>
                            <input type="text" id="username" name="username" placeholder="Masukkan username" autocomplete="username" autocapitalize="none" spellcheck="false" autofocus>
                        </div>
                        <div class="invalid" data-for="username"></div>
                    </div>
                    <div class="field">
                        <label for="password">Password</label>
                        <div class="control">
                            <i class="fas fa-lock lead-ico"></i>
                            <input type="password" id="password" name="password" placeholder="Masukkan password" autocomplete="current-password">
                            <button type="button" class="toggle-pass" id="togglePass" aria-label="Tampilkan password"><i class="far fa-eye"></i></button>
                        </div>
                        <div class="caps" id="caps"><i class="fas fa-exclamation-triangle"></i> Caps Lock aktif</div>
                        <div class="invalid" data-for="password"></div>
                    </div>
                    <div class="row-opt">
                        <label><input type="checkbox" id="remember"> Ingat username</label>
                    </div>
                    <button type="submit" id="login" class="btn"><span class="spin"></span><i class="fas fa-arrow-right lbl-ico"></i> <span class="lbl">Masuk</span></button>
                </form>

                <div class="foot">
                    <?php echo $a->copy_right; ?> <?php echo html_escape($a->tahun); ?><br><?php echo html_escape($a->nama_owner); ?>
                </div>
            </div>
        </section>
    </main>

    <script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
    <script>
    (function ($) {
        var $f = $('#quickForm'), $btn = $('#login'), $alert = $('#alert'), KEY = 'top.login.user';
        try { var u = localStorage.getItem(KEY); if (u) { $('#username').val(u); $('#remember').prop('checked', true); $('#password').trigger('focus'); } } catch (e) {}

        function pesan(tipe, txt) {
            $alert.removeClass('alert--err alert--ok').addClass(tipe === 'ok' ? 'alert--ok' : 'alert--err')
                .find('i').attr('class', tipe === 'ok' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle').end()
                .find('span').text(txt).end().addClass('show');
        }
        function resetErr() { $alert.removeClass('show'); $f.find('input').removeClass('is-invalid'); $f.find('.invalid').text(''); }
        function loading(on) { $btn.prop('disabled', on).toggleClass('loading', on).find('.lbl').text(on ? 'Memeriksa...' : 'Masuk'); }

        $('#togglePass').on('click', function () {
            var $p = $('#password'), show = $p.attr('type') === 'password';
            $p.attr('type', show ? 'text' : 'password');
            $(this).find('i').attr('class', show ? 'far fa-eye-slash' : 'far fa-eye');
        });
        $('#password').on('keyup keydown', function (e) {
            if (e.originalEvent && e.originalEvent.getModifierState) $('#caps').toggle(e.originalEvent.getModifierState('CapsLock'));
        });
        $f.on('input', 'input', function () { $(this).removeClass('is-invalid'); $f.find('.invalid[data-for="' + this.name + '"]').text(''); });

        $f.on('submit', function (e) {
            e.preventDefault();
            resetErr(); loading(true);
            $.ajax({ method: 'POST', url: '<?php echo site_url('Login/login'); ?>', data: $f.serialize(), dataType: 'text' })
                .done(function (raw) {
                    var data; try { data = JSON.parse(raw); } catch (er) { pesan('err', 'Respon server tidak valid.'); loading(false); return; }
                    if (data.status) {
                        try { if ($('#remember').is(':checked')) localStorage.setItem(KEY, $('#username').val()); else localStorage.removeItem(KEY); } catch (e2) {}
                        pesan('ok', 'Login berhasil, mengalihkan...');
                        window.location = '<?php echo site_url('dashboard'); ?>';
                        return;
                    }
                    loading(false);
                    if (data.error) { pesan('err', data.pesan); $('#password').val('').trigger('focus'); return; }
                    if (data.inputerror) {
                        for (var i = 0; i < data.inputerror.length; i++) {
                            $('[name="' + data.inputerror[i] + '"]').addClass('is-invalid');
                            $f.find('.invalid[data-for="' + data.inputerror[i] + '"]').text(data.error_string[i]);
                        }
                        $('.is-invalid').first().trigger('focus');
                    }
                })
                .fail(function () { loading(false); pesan('err', 'Tidak dapat terhubung ke server. Periksa koneksi jaringan.'); });
        });
    })(jQuery);
    </script>
</body>

</html>
