<style>
  .shop-card .card-media { transition: width .2s; }
  .list-mode { grid-template-columns: 1fr !important; }
  .list-mode .shop-card { flex-direction: row; gap: 1rem; }
  .list-mode .shop-card .card-media { width: 11rem; flex-shrink: 0; margin-bottom: 0; }
  .list-mode .shop-card > div:last-child { flex: 1; }
  .dual-range { position: relative; height: 1.25rem; }
  .dual-range input[type=range] { position: absolute; inset: 0; width: 100%; margin: 0; background: none; pointer-events: none; -webkit-appearance: none; appearance: none; }
  .dual-range input[type=range]::-webkit-slider-thumb { pointer-events: auto; -webkit-appearance: none; width: 1rem; height: 1rem; border-radius: 9999px; background: #fff; border: 2px solid #805600; box-shadow: 0 1px 4px rgba(0,0,0,.2); cursor: pointer; }
  .dual-range input[type=range]::-moz-range-thumb { pointer-events: auto; width: .75rem; height: .75rem; border-radius: 9999px; background: #fff; border: 2px solid #805600; cursor: pointer; }
  .dual-range input[type=range]::-webkit-slider-runnable-track { background: transparent; }
  .dual-range input[type=range]::-moz-range-track { background: transparent; }
  @media (max-width: 1023px) {
    #shop-filters { display: none; position: fixed; inset: 0; z-index: 90; background: rgba(31,27,21,.55); align-items: flex-end; }
    #shop-filters.open { display: flex; }
    #shop-filters > form { width: 100%; max-height: 88vh; overflow-y: auto; border-radius: 1.25rem 1.25rem 0 0; }
  }
</style>
