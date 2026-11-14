<script>
(function () {
  // Weight pills: switch the variant the card submits + its price.
  document.querySelectorAll('.shop-card').forEach(function (card) {
    card.querySelectorAll('.shop-pill').forEach(function (pill) {
      pill.addEventListener('click', function () {
        if (pill.disabled) return;
        card.querySelector('.shop-variant-input').value = pill.dataset.id;
        card.querySelectorAll('.shop-pill').forEach(function (p) { p.classList.remove('bg-primary', 'text-on-primary'); p.classList.add('bg-surface-container', 'text-on-surface'); });
        pill.classList.add('bg-primary', 'text-on-primary'); pill.classList.remove('bg-surface-container', 'text-on-surface');
        card.querySelector('.shop-price').textContent = pill.dataset.price;
        var d = parseInt(pill.dataset.discount || '0', 10), o = card.querySelector('.shop-original'), b = card.querySelector('.shop-discount');
        o.textContent = pill.dataset.original; b.textContent = String(d).replace(/\d/g, function (x) { return '০১২৩৪৫৬৭৮৯'[x]; }) + '% ছাড়';
        o.classList.toggle('hidden', !d); b.classList.toggle('hidden', !d);
      });
    });
  });
  // Grid / list view
  var grid = document.getElementById('shop-grid'), g = document.getElementById('view-grid'), l = document.getElementById('view-list');
  function setView(list) {
    if (!grid) return;
    grid.classList.toggle('list-mode', list);
    [[g, !list], [l, list]].forEach(function (x) {
      x[0].classList.toggle('bg-surface-container-lowest', x[1]); x[0].classList.toggle('text-primary', x[1]); x[0].classList.toggle('shadow-sm', x[1]);
      x[0].classList.toggle('text-on-surface-variant', !x[1]);
    });
    try { localStorage.setItem('shopView', list ? 'list' : 'grid'); } catch (e) {}
  }
  if (g && l) { g.onclick = function () { setView(false); }; l.onclick = function () { setView(true); }; try { if (localStorage.getItem('shopView') === 'list') setView(true); } catch (e) {} }
  // Mobile filter drawer
  var drawer = document.getElementById('shop-filters'), openBtn = document.getElementById('open-filters');
  if (openBtn) openBtn.onclick = function () { drawer.classList.add('open'); };
  // Dual price range
  var rmin = document.getElementById('range-min'), rmax = document.getElementById('range-max'), nmin = document.getElementById('price-min'), nmax = document.getElementById('price-max'), fill = document.getElementById('price-fill'), label = document.getElementById('price-label');
  function paint(fromRange) {
    var lo = +rmin.value, hi = +rmax.value, a = +rmin.min, b = +rmin.max;
    if (lo > hi) { if (fromRange === rmin) { lo = hi; rmin.value = lo; } else { hi = lo; rmax.value = hi; } }
    fill.style.left = ((lo - a) / (b - a) * 100) + '%'; fill.style.right = (100 - (hi - a) / (b - a) * 100) + '%';
    label.textContent = '৳' + lo + ' - ৳' + hi;
    if (fromRange) { nmin.value = lo > a ? lo : ''; nmax.value = hi < b ? hi : ''; }
  }
  if (rmin) {
    rmin.oninput = function () { paint(rmin); }; rmax.oninput = function () { paint(rmax); };
    nmin.oninput = function () { rmin.value = nmin.value || rmin.min; paint(); }; nmax.oninput = function () { rmax.value = nmax.value || rmax.max; paint(); };
    paint();
  }
})();
</script>
