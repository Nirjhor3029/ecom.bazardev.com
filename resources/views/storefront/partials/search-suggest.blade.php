{{-- Live search suggestions under the header search boxes (inputs marked [data-suggest]). --}}
<script>
(function () {
  var url = @json(route('search.suggest'));
  var esc = function (s) { return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) { return {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[c]; }); };
  var bn = function (n) { return String(n).replace(/\d/g, function (d) { return '০১২৩৪৫৬৭৮৯'[d]; }); };

  document.querySelectorAll('input[data-suggest]').forEach(function (input) {
    var box = document.createElement('div');
    box.className = 'hidden absolute left-0 right-0 top-full mt-2 z-[80] bg-surface-container-lowest rounded-xl shadow-xl overflow-hidden text-left';
    input.parentElement.appendChild(box);
    var timer, seq = 0;

    function render(d, term) {
      var h = '';
      if (d.products.length) {
        h += '<div class="px-4 pt-3 pb-1 font-label-sm text-label-sm uppercase tracking-wider text-tertiary">পণ্য</div>';
        d.products.forEach(function (p) {
          h += '<a href="' + esc(p.url) + '" class="flex items-center gap-3 px-4 py-2 hover:bg-surface-container-low transition-colors">' +
            (p.image ? '<img src="' + esc(p.image) + '" alt="" class="w-10 h-10 rounded-lg object-cover shrink-0">' : '<span class="w-10 h-10 rounded-lg bg-surface-container shrink-0 flex items-center justify-center">🍯</span>') +
            '<span class="flex-1 min-w-0"><span class="block font-title-md text-body-md text-on-surface font-semibold truncate">' + esc(p.name) + '</span>' +
            '<span class="block font-label-sm text-label-sm text-on-surface-variant truncate">' + esc(p.meta || '') + '</span></span>' +
            '<span class="font-label-md text-label-md text-primary font-bold">' + esc(p.price) + '</span></a>';
        });
      }
      if (d.categories.length) {
        h += '<div class="px-4 pt-3 pb-1 font-label-sm text-label-sm uppercase tracking-wider text-tertiary">ক্যাটাগরি</div><div class="px-4 pb-2 flex flex-wrap gap-2">';
        d.categories.forEach(function (c) { h += '<a href="' + esc(c.url) + '" class="px-3 py-1 rounded-full bg-surface-container hover:bg-surface-container-high font-label-sm text-label-sm text-on-surface">' + esc(c.name) + '</a>'; });
        h += '</div>';
      }
      if (!d.products.length && !d.categories.length) {
        h = '<div class="px-4 py-4 font-body-sm text-body-sm text-on-surface-variant">“' + esc(term) + '” এর জন্য কোনো মিল পাওয়া যায়নি।</div>';
      } else {
        h += '<a href="' + esc(d.url) + '" class="flex items-center justify-between px-4 py-3 bg-surface-container-low hover:bg-surface-container font-label-md text-label-md text-primary font-bold"><span>সকল ' + bn(d.total) + 'টি ফলাফল দেখুন</span><span class="material-symbols-outlined text-[16px]">arrow_forward</span></a>';
      }
      box.innerHTML = h;
      box.classList.remove('hidden');
    }

    input.addEventListener('input', function () {
      clearTimeout(timer);
      var term = input.value.trim();
      if (!term) { box.classList.add('hidden'); return; }
      timer = setTimeout(function () {
        var mine = ++seq;
        fetch(url + '?q=' + encodeURIComponent(term), { headers: { 'Accept': 'application/json' } })
          .then(function (r) { return r.json(); })
          .then(function (d) { if (mine === seq) render(d, term); })
          .catch(function () {});
      }, 200);
    });
    input.addEventListener('focus', function () { if (box.innerHTML && input.value.trim()) box.classList.remove('hidden'); });
    document.addEventListener('click', function (e) { if (!input.parentElement.contains(e.target)) box.classList.add('hidden'); });
    input.addEventListener('keydown', function (e) { if (e.key === 'Escape') box.classList.add('hidden'); });
  });
})();
</script>
