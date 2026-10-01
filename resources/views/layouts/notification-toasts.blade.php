@auth
@php
    $notifUnread = Auth::user()->unreadNotifications()->count();
@endphp
<script id="notif-popup-driver" data-initial="{{ $notifUnread }}" data-poll="{{ route('notifications.poll') }}">
// Dropdown lonceng dibuka langsung oleh server bila ada yang belum dibaca.
// Script ini hanya menutup otomatis + membuka ulang saat ada notifikasi baru.
(function () {
    var AUTO_CLOSE_MS = 15000;
    var COUNT_KEY = 'bkkmu_notif_lastcount';
    var closeTimer = null;

    function scheduleClose() {
        clearTimeout(closeTimer);
        closeTimer = setTimeout(function () {
            window.dispatchEvent(new Event('notif-popup-close'));
        }, AUTO_CLOSE_MS);
    }
    function openPopup() {
        window.dispatchEvent(new Event('notif-popup-open'));
        scheduleClose();
    }
    function flashBell() {
        var btn = document.getElementById('notif-bell-btn');
        if (!btn) return;
        btn.classList.add('bg-blue-100');
        setTimeout(function () { btn.classList.remove('bg-blue-100'); }, 4000);
    }
    function getLastCount(fallback) {
        try {
            var v = sessionStorage.getItem(COUNT_KEY);
            return v === null ? fallback : parseInt(v, 10);
        } catch (e) { return fallback; }
    }
    function setLastCount(v) {
        try { sessionStorage.setItem(COUNT_KEY, String(v)); } catch (e) {}
    }

    var driver = document.getElementById('notif-popup-driver');
    var initial = parseInt(driver.getAttribute('data-initial') || '0', 10);
    var pollUrl = driver.getAttribute('data-poll');

    // Nongol halus 700ms setelah halaman siap bila ada yang belum dibaca
    if (initial > 0) {
        setLastCount(initial);
        setTimeout(function () {
            flashBell();
            openPopup();
        }, 700);
    } else {
        setLastCount(getLastCount(0));
    }

    // Pantau notifikasi baru: kalau bertambah, buka dropdown loncengnya
    setInterval(function () {
        fetch(pollUrl, { headers: { 'Accept': 'application/json' } })
            .then(function (r) { return r.ok ? r.json() : null; })
            .then(function (d) {
                if (!d) return;
                var before = getLastCount(d.unread_count || 0);
                var now = d.unread_count || 0;
                setLastCount(now);
                if (now > before) {
                    flashBell();
                    openPopup();
                }
            })
            .catch(function () {});
    }, 60000);
})();
</script>
@endauth
