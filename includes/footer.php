<footer class="mt-12 border-t border-sky/60 bg-white">
    <div class="max-w-6xl mx-auto px-4 py-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-ink/70">
        <p>&copy; <?= date('Y'); ?> Green Haven Cafe &mdash; Sistem Pemesanan Berbasis Web</p>
        <p class="flex items-center gap-2">
            <img src="<?= asset('assets/logo-brand.png'); ?>" alt="Green Haven" class="h-5 w-auto">
            <span>Green Haven Cafe</span>
        </p>
    </div>
</footer>

<script>
    if (window.lucide) lucide.createIcons();
</script>
</body>
</html>