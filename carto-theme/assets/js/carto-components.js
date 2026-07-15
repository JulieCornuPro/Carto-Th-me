/* =====================================================================
   CARTO // COMPOSANTS DYNAMIQUES
   Bibliothèque de schémas animés réutilisables — thème AppSec cyber.
   Usage:  Carto.barChart(el, {...})  ·  el = conteneur HTML
   Chaque composant s'anime à l'entrée dans le viewport et expose .play().
   ===================================================================== */
(function (global) {
  'use strict';

  var TEAL = '#00CFCF', ORANGE = '#FF6B35', AMBER = '#E8B84B',
      TEXT = '#C8D8E8', TEXTLO = '#6A8AAA', MUTED = '#3A5070',
      BORDER = '#1A2840', SURFACE = '#0C1428', BG = '#070C18';
  var EASE = 'cubic-bezier(0.16,1,0.3,1)';
  var NS = 'http://www.w3.org/2000/svg';

  /* inject shared keyframes once */
  if (!document.getElementById('carto-kf')) {
    var st = document.createElement('style');
    st.id = 'carto-kf';
    st.textContent =
      '@keyframes carto-dash{to{stroke-dashoffset:-1000}}' +
      '@keyframes carto-pulse{0%,100%{opacity:.35;transform:scale(1)}50%{opacity:.9;transform:scale(1.25)}}' +
      '.carto-mono{font-family:Inconsolata,monospace}' +
      '.carto-disp{font-family:Syncopate,sans-serif}';
    document.head.appendChild(st);
  }

  function svg(tag, attrs) {
    var e = document.createElementNS(NS, tag);
    for (var k in attrs) e.setAttribute(k, attrs[k]);
    return e;
  }
  function onView(el, cb) {
    var io = new IntersectionObserver(function (es) {
      es.forEach(function (en) { if (en.isIntersecting) { cb(); io.unobserve(el); } });
    }, { threshold: 0.35 });
    io.observe(el);
  }
  function easeOut(t) { return 1 - Math.pow(1 - t, 3); }
  function animateNum(node, from, to, dur, fmt) {
    var t0 = performance.now();
    (function step(now) {
      var p = Math.min(1, (now - t0) / dur), v = from + (to - from) * easeOut(p);
      node.textContent = fmt ? fmt(v) : Math.round(v);
      if (p < 1) requestAnimationFrame(step);
    })(t0);
  }

  /* ============ 1 · BAR CHART ============ */
  function barChart(el, o) {
    o = o || {}; var data = o.data || [], max = o.max || Math.max.apply(null, data.map(function (d) { return d.value; })) * 1.1;
    el.innerHTML = ''; el.style.cssText = 'display:flex;align-items:flex-end;gap:' + (o.gap || 28) + 'px;height:' + (o.height || 280) + 'px;padding-top:20px;position:relative';
    var bars = [];
    data.forEach(function (d) {
      var col = document.createElement('div');
      col.style.cssText = 'flex:1;display:flex;flex-direction:column;align-items:center;justify-content:flex-end;height:100%';
      var val = document.createElement('div');
      val.className = 'carto-mono'; val.style.cssText = 'font-size:24px;font-weight:700;color:' + (d.color || TEAL) + ';margin-bottom:10px';
      val.textContent = '0';
      var track = document.createElement('div');
      track.style.cssText = 'width:100%;height:100%;display:flex;align-items:flex-end';
      var fill = document.createElement('div');
      fill.style.cssText = 'width:100%;height:0;background:linear-gradient(' + (d.color || TEAL) + ',' + (d.color || TEAL) + '22);border-top:2px solid ' + (d.color || TEAL) + ';transition:height 1.1s ' + EASE;
      track.appendChild(fill);
      var lab = document.createElement('div');
      lab.className = 'carto-mono'; lab.style.cssText = 'font-size:14px;letter-spacing:.08em;text-transform:uppercase;color:' + TEXTLO + ';margin-top:14px;text-align:center';
      lab.textContent = d.label;
      col.appendChild(val); col.appendChild(track); col.appendChild(lab);
      el.appendChild(col);
      bars.push({ fill: fill, val: val, d: d });
    });
    function play() {
      bars.forEach(function (b, i) {
        setTimeout(function () {
          b.fill.style.height = (b.d.value / max * 100) + '%';
          animateNum(b.val, 0, b.d.value, 1100, function (v) { return Math.round(v) + (o.unit || ''); });
        }, i * 130);
      });
    }
    onView(el, play); return { play: play };
  }

  /* ============ 2 · HEATMAP ============ */
  function heatmap(el, o) {
    o = o || {}; var values = o.values || [], xl = o.xLabels || [], yl = o.yLabels || [];
    var rows = values.length, cols = values[0] ? values[0].length : 0;
    el.innerHTML = '';
    var wrap = document.createElement('div');
    wrap.style.cssText = 'display:grid;grid-template-columns:auto 1fr;gap:14px;align-items:center';
    // y labels + grid
    var ycol = document.createElement('div');
    ycol.style.cssText = 'display:grid;grid-template-rows:repeat(' + rows + ',1fr);gap:8px;height:100%';
    yl.forEach(function (t) { var d = document.createElement('div'); d.className = 'carto-mono'; d.style.cssText = 'font-size:13px;letter-spacing:.06em;text-transform:uppercase;color:' + TEXTLO + ';display:flex;align-items:center;justify-content:flex-end;text-align:right'; d.textContent = t; ycol.appendChild(d); });
    var grid = document.createElement('div');
    grid.style.cssText = 'display:grid;grid-template-columns:repeat(' + cols + ',1fr);grid-template-rows:repeat(' + rows + ',1fr);gap:8px';
    var cells = [];
    for (var r = 0; r < rows; r++) for (var c = 0; c < cols; c++) {
      var v = values[r][c]; // 0..1
      var cell = document.createElement('div');
      cell.style.cssText = 'aspect-ratio:1.6/1;border:1px solid ' + BORDER + ';background:' + SURFACE + ';opacity:0;transform:scale(.8);transition:opacity .5s ' + EASE + ',transform .5s ' + EASE + ',background .6s ' + EASE + ';display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:700;color:#fff';
      cell.className = 'carto-mono';
      cells.push({ el: cell, v: v, i: r * cols + c });
      grid.appendChild(cell);
    }
    // x labels
    var xrow = document.createElement('div');
    xrow.style.cssText = 'grid-column:2;display:grid;grid-template-columns:repeat(' + cols + ',1fr);gap:8px;margin-top:10px';
    xl.forEach(function (t) { var d = document.createElement('div'); d.className = 'carto-mono'; d.style.cssText = 'font-size:13px;letter-spacing:.06em;text-transform:uppercase;color:' + TEXTLO + ';text-align:center'; d.textContent = t; xrow.appendChild(d); });
    wrap.appendChild(ycol); wrap.appendChild(grid);
    var spacer = document.createElement('div'); wrap.appendChild(spacer);
    wrap.appendChild(xrow);
    el.appendChild(wrap);
    function color(v) {
      // teal (cold) -> amber -> orange (hot)
      if (v < 0.5) return mix(TEAL, AMBER, v / 0.5, 0.12 + v * 0.5);
      return mix(AMBER, ORANGE, (v - 0.5) / 0.5, 0.4 + v * 0.55);
    }
    function mix(a, b, t, alpha) {
      function h2(x) { return parseInt(x, 16); }
      var ar = h2(a.slice(1, 3)), ag = h2(a.slice(3, 5)), ab = h2(a.slice(5, 7));
      var br = h2(b.slice(1, 3)), bg = h2(b.slice(3, 5)), bb = h2(b.slice(5, 7));
      var R = Math.round(ar + (br - ar) * t), G = Math.round(ag + (bg - ag) * t), B = Math.round(ab + (bb - ab) * t);
      return 'rgba(' + R + ',' + G + ',' + B + ',' + alpha + ')';
    }
    function play() {
      cells.forEach(function (c) {
        setTimeout(function () {
          c.el.style.opacity = '1'; c.el.style.transform = 'scale(1)';
          c.el.style.background = color(c.v);
          if (c.v >= 0.75) c.el.style.boxShadow = '0 0 18px ' + ORANGE + '66';
        }, c.i * 45);
      });
    }
    onView(el, play); return { play: play };
  }

  /* ============ 3 · RADIAL GAUGE ============ */
  function gauge(el, o) {
    o = o || {}; var value = o.value != null ? o.value : 72, max = o.max || 100;
    var size = o.size || 260, sw = 18, r = (size - sw) / 2 - 6, cx = size / 2, cy = size / 2;
    var arc = 0.75; // fraction of circle used (270°)
    var circ = 2 * Math.PI * r, used = circ * arc;
    el.innerHTML = '';
    var s = svg('svg', { viewBox: '0 0 ' + size + ' ' + size, width: '100%', style: 'max-width:' + size + 'px;display:block;margin:0 auto' });
    var rot = 'rotate(135 ' + cx + ' ' + cy + ')';
    var track = svg('circle', { cx: cx, cy: cy, r: r, fill: 'none', stroke: BORDER, 'stroke-width': sw, 'stroke-dasharray': used + ' ' + circ, 'stroke-linecap': 'round', transform: rot });
    var prog = svg('circle', { cx: cx, cy: cy, r: r, fill: 'none', stroke: o.color || TEAL, 'stroke-width': sw, 'stroke-linecap': 'round', 'stroke-dasharray': used + ' ' + circ, 'stroke-dashoffset': used, transform: rot, style: 'transition:stroke-dashoffset 1.3s ' + EASE + ';filter:drop-shadow(0 0 8px ' + (o.color || TEAL) + '88)' });
    s.appendChild(track); s.appendChild(prog);
    el.appendChild(s);
    var center = document.createElement('div');
    center.style.cssText = 'position:relative;margin-top:-' + (size * 0.62) + 'px;text-align:center;pointer-events:none';
    var num = document.createElement('div'); num.className = 'carto-disp'; num.style.cssText = 'font-size:56px;font-weight:700;color:#fff;line-height:1'; num.textContent = '0';
    var cap = document.createElement('div'); cap.className = 'carto-mono'; cap.style.cssText = 'font-size:14px;letter-spacing:.18em;text-transform:uppercase;color:' + (o.color || TEAL) + ';margin-top:10px'; cap.textContent = o.label || 'Score';
    center.appendChild(num); center.appendChild(cap);
    el.appendChild(center);
    el.style.paddingBottom = (size * 0.62 - size * 0.5 + 20) + 'px';
    function play() {
      prog.style.strokeDashoffset = (used * (1 - value / max));
      animateNum(num, 0, value, 1300, function (v) { return Math.round(v) + (o.suffix || ''); });
    }
    onView(el, play); return { play: play };
  }

  /* ============ 4 · FLOW / ATTACK-PATH GRAPH ============ */
  function flowGraph(el, o) {
    o = o || {}; var W = o.width || 720, H = o.height || 300;
    var nodes = o.nodes || [], edges = o.edges || [];
    el.innerHTML = '';
    var s = svg('svg', { viewBox: '0 0 ' + W + ' ' + H, width: '100%', style: 'display:block' });
    var defs = svg('defs', {});
    var m = svg('marker', { id: 'cg-arr', markerWidth: 9, markerHeight: 9, refX: 7, refY: 3, orient: 'auto' });
    m.appendChild(svg('path', { d: 'M0,0 L0,6 L8,3 z', fill: TEXTLO })); defs.appendChild(m); s.appendChild(defs);
    function nodeById(id) { return nodes.filter(function (n) { return n.id === id; })[0]; }
    // edges
    edges.forEach(function (e, i) {
      var a = nodeById(e.from), b = nodeById(e.to);
      var danger = e.danger;
      var p = svg('path', { d: 'M' + a.x + ',' + a.y + ' L' + b.x + ',' + b.y, fill: 'none', stroke: danger ? ORANGE : MUTED, 'stroke-width': danger ? 2 : 1.5, 'stroke-dasharray': '8 7', 'stroke-opacity': danger ? 0.9 : 0.5, 'marker-end': 'url(#cg-arr)' });
      p.style.animation = 'carto-dash ' + (danger ? 1.1 : 2.2) + 's linear infinite';
      p.style.opacity = '0'; p.style.transition = 'opacity .6s ' + EASE;
      p.setAttribute('data-i', i);
      s.appendChild(p);
    });
    // nodes
    nodes.forEach(function (n) {
      var col = n.danger ? ORANGE : TEAL;
      var halo = svg('circle', { cx: n.x, cy: n.y, r: 0, fill: col, 'fill-opacity': 0.12 });
      halo.style.transition = 'r .6s ' + EASE;
      var dot = svg('circle', { cx: n.x, cy: n.y, r: 0, fill: col });
      dot.style.transition = 'r .5s ' + EASE;
      if (n.danger) { var pls = svg('circle', { cx: n.x, cy: n.y, r: 7, fill: 'none', stroke: ORANGE, 'stroke-width': 1.5, opacity: 0 }); pls.style.transformOrigin = n.x + 'px ' + n.y + 'px'; pls.style.animation = 'carto-pulse 1.8s ease-in-out infinite'; s.appendChild(pls); }
      s.appendChild(halo); s.appendChild(dot);
      var t = svg('text', { x: n.x, y: n.y + (n.below ? 30 : -22), 'text-anchor': 'middle', fill: n.danger ? ORANGE : TEXTLO, 'font-size': 14, 'font-family': 'Inconsolata,monospace' });
      t.textContent = n.label; t.style.opacity = '0'; t.style.transition = 'opacity .6s ' + EASE;
      s.appendChild(t);
      n._dot = dot; n._halo = halo; n._t = t; n._col = col;
    });
    el.appendChild(s);
    function play() {
      nodes.forEach(function (n, i) {
        setTimeout(function () { n._dot.setAttribute('r', n.danger ? 7 : 6); n._halo.setAttribute('r', n.danger ? 16 : 13); n._t.style.opacity = '1'; }, i * 160);
      });
      var paths = s.querySelectorAll('path[data-i]');
      setTimeout(function () { paths.forEach(function (p) { p.style.opacity = '1'; }); }, nodes.length * 160 * 0.5);
    }
    onView(el, play); return { play: play };
  }

  /* ============ 5 · KPI COUNTER ============ */
  function counter(el, o) {
    o = o || {};
    el.innerHTML = '';
    var row = document.createElement('div'); row.style.cssText = 'display:flex;align-items:baseline;gap:4px';
    var pre = document.createElement('span'); pre.className = 'carto-disp'; pre.style.cssText = 'font-size:40px;font-weight:700;color:' + (o.color || TEAL); pre.textContent = o.prefix || '';
    var num = document.createElement('span'); num.className = 'carto-disp'; num.style.cssText = 'font-size:72px;font-weight:700;color:#fff;line-height:1;letter-spacing:-1px'; num.textContent = '0';
    var suf = document.createElement('span'); suf.className = 'carto-disp'; suf.style.cssText = 'font-size:40px;font-weight:700;color:' + (o.color || TEAL); suf.textContent = o.suffix || '';
    row.appendChild(pre); row.appendChild(num); row.appendChild(suf);
    var lab = document.createElement('div'); lab.className = 'carto-mono'; lab.style.cssText = 'font-size:15px;letter-spacing:.16em;text-transform:uppercase;color:' + TEXTLO + ';margin-top:14px'; lab.textContent = o.label || '';
    var bar = document.createElement('div'); bar.style.cssText = 'height:3px;width:64px;background:' + (o.color || TEAL) + ';margin-top:18px;transform:scaleX(0);transform-origin:left;transition:transform .9s ' + EASE;
    el.appendChild(row); el.appendChild(lab); el.appendChild(bar);
    var dec = o.decimals || 0, val = o.value != null ? o.value : 0;
    function play() {
      animateNum(num, 0, val, 1400, function (v) { return v.toFixed(dec); });
      bar.style.transform = 'scaleX(1)';
    }
    onView(el, play); return { play: play };
  }

  /* ============ 6 · SPARKLINE / TREND ============ */
  function sparkline(el, o) {
    o = o || {}; var pts = o.points || [], W = o.width || 520, H = o.height || 180, pad = 14;
    var min = Math.min.apply(null, pts), max = Math.max.apply(null, pts), span = (max - min) || 1;
    el.innerHTML = '';
    var s = svg('svg', { viewBox: '0 0 ' + W + ' ' + H, width: '100%', style: 'display:block' });
    var xs = pts.map(function (_, i) { return pad + i * (W - 2 * pad) / (pts.length - 1); });
    var ys = pts.map(function (v) { return H - pad - (v - min) / span * (H - 2 * pad); });
    var d = pts.map(function (_, i) { return (i ? 'L' : 'M') + xs[i] + ',' + ys[i]; }).join(' ');
    var col = o.color || TEAL;
    // baseline
    s.appendChild(svg('line', { x1: pad, y1: H - pad, x2: W - pad, y2: H - pad, stroke: BORDER, 'stroke-width': 1 }));
    // area
    var area = svg('path', { d: d + ' L' + xs[xs.length - 1] + ',' + (H - pad) + ' L' + xs[0] + ',' + (H - pad) + ' Z', fill: col, 'fill-opacity': 0, style: 'transition:fill-opacity 1s ' + EASE });
    s.appendChild(area);
    var line = svg('path', { d: d, fill: 'none', stroke: col, 'stroke-width': 2.5, 'stroke-linecap': 'round', 'stroke-linejoin': 'round', style: 'filter:drop-shadow(0 0 6px ' + col + '66)' });
    var len = 1400; line.setAttribute('stroke-dasharray', len); line.setAttribute('stroke-dashoffset', len);
    line.style.transition = 'stroke-dashoffset 1.6s ' + EASE; s.appendChild(line);
    var end = svg('circle', { cx: xs[xs.length - 1], cy: ys[ys.length - 1], r: 0, fill: col, style: 'transition:r .4s ' + EASE + ' 1.2s;filter:drop-shadow(0 0 8px ' + col + ')' });
    s.appendChild(end);
    el.appendChild(s);
    function play() { line.style.strokeDashoffset = '0'; area.style.fillOpacity = '0.14'; end.setAttribute('r', 5); }
    onView(el, play); return { play: play };
  }

  global.Carto = { barChart: barChart, heatmap: heatmap, gauge: gauge, flowGraph: flowGraph, counter: counter, sparkline: sparkline, colors: { TEAL: TEAL, ORANGE: ORANGE, AMBER: AMBER } };
})(window);
