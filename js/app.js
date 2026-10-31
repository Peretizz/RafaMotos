(function () {
  'use strict';
  var $ = function (s, r) { return (r || document).querySelector(s); };
  var $$ = function (s, r) { return Array.prototype.slice.call((r || document).querySelectorAll(s)); };

  /* ---------- Menu no celular ---------- */
  var btn = $('#menu-btn'), menu = $('#menu');
  if (btn && menu) {
    var fechaMenu = function () { menu.classList.remove('is-aberto'); btn.setAttribute('aria-expanded', 'false'); };
    btn.addEventListener('click', function () {
      var aberto = menu.classList.toggle('is-aberto');
      btn.setAttribute('aria-expanded', aberto ? 'true' : 'false');
    });
    $$('a', menu).forEach(function (a) { a.addEventListener('click', fechaMenu); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') fechaMenu(); });
  }

  /* ---------- Prévia das fotos escolhidas nos formulários ---------- */
  $$('input[type=file][data-previa]').forEach(function (inp) {
    inp.addEventListener('change', function () {
      var alvo = $(inp.getAttribute('data-previa'));
      if (!alvo) return;
      alvo.textContent = '';
      Array.prototype.slice.call(inp.files).slice(0, 20).forEach(function (f) {
        if (!/^image\//.test(f.type)) return;
        var img = document.createElement('img');
        img.alt = f.name;
        img.src = URL.createObjectURL(f);
        img.onload = function () { URL.revokeObjectURL(img.src); };
        alvo.appendChild(img);
      });
    });
  });

  var DADOS = window.RAFA;
  var grade = $('#grade');
  if (!DADOS || !grade) return;

  var motos = {};
  DADOS.motos.forEach(function (m) { motos[m.id] = m; });
  var brl = function (n) { return n.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL', maximumFractionDigits: 0 }); };

  /* ---------- Favoritas (ficam salvas só neste aparelho) ---------- */
  var favs = [];
  try { favs = JSON.parse(localStorage.getItem('rafa_favs') || '[]'); } catch (e) { favs = []; }
  if (!Array.isArray(favs)) favs = [];
  var salvaFavs = function () { try { localStorage.setItem('rafa_favs', JSON.stringify(favs)); } catch (e) {} };

  function pintaFavs() {
    $$('[data-fav]').forEach(function (b) {
      var on = favs.indexOf(+b.getAttribute('data-fav')) > -1;
      b.setAttribute('aria-pressed', on ? 'true' : 'false');
    });
    var c = $('#fav-count'); if (c) c.textContent = favs.filter(function (id) { return motos[id]; }).length;
  }

  grade.addEventListener('click', function (e) {
    var f = e.target.closest('[data-fav]');
    if (f) {
      var id = +f.getAttribute('data-fav'), i = favs.indexOf(id);
      if (i > -1) favs.splice(i, 1); else favs.push(id);
      salvaFavs(); pintaFavs(); aplica();
      return;
    }
    var a = e.target.closest('[data-abrir]');
    if (a) abre(+a.getAttribute('data-abrir'), a);
  });

  /* ---------- Filtros e ordenação ---------- */
  var cards = $$('.card', grade);
  var est = { busca: '', marca: '', cat: '', preco: '', ordem: 'recentes', fav: false };
  var elBusca = $('#f-busca'), elOrdem = $('#f-ordem'), elPreco = $('#f-preco');
  var elRes = $('#resultado'), elVazio = $('#vazio');

  function norm(s) { return (s || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-z0-9 ]/g, ' ').replace(/\s+/g, ' ').trim(); }
  cards.forEach(function (c) { c._busca = norm(c.getAttribute('data-busca')); });

  function aplica() {
    var termos = norm(est.busca).split(' ').filter(Boolean);
    var faixa = est.preco ? est.preco.split('-').map(Number) : null;
    var visiveis = [];
    cards.forEach(function (c) {
      var ok = true;
      if (est.marca && c.getAttribute('data-marca') !== est.marca) ok = false;
      if (ok && est.cat && c.getAttribute('data-cat') !== est.cat) ok = false;
      if (ok && faixa) { var p = +c.getAttribute('data-preco'); if (p < faixa[0] || p > faixa[1]) ok = false; }
      if (ok && est.fav && favs.indexOf(+c.getAttribute('data-id')) < 0) ok = false;
      if (ok && termos.length) ok = termos.every(function (t) { return c._busca.indexOf(t) > -1; });
      c.hidden = !ok;
      if (ok) visiveis.push(c);
    });

    var n = function (c, k) { return +c.getAttribute('data-' + k); };
    var ordens = {
      'recentes': function (a, b) { return n(a, 'ordem') - n(b, 'ordem'); },
      'preco-asc': function (a, b) { return n(a, 'preco') - n(b, 'preco'); },
      'preco-desc': function (a, b) { return n(b, 'preco') - n(a, 'preco'); },
      'km-asc': function (a, b) { return n(a, 'km') - n(b, 'km'); },
      'ano-desc': function (a, b) { return n(b, 'ano') - n(a, 'ano') || n(a, 'ordem') - n(b, 'ordem'); }
    };
    cards.slice().sort(ordens[est.ordem]).forEach(function (c) { grade.appendChild(c); });

    var total = cards.length;
    var filtrando = est.busca || est.marca || est.cat || est.preco || est.fav;
    elRes.textContent = visiveis.length === 0 ? '' :
      (filtrando ? visiveis.length + (visiveis.length === 1 ? ' moto encontrada' : ' motos encontradas') + ' de ' + total
                 : total + (total === 1 ? ' moto disponível' : ' motos disponíveis'));
    elVazio.hidden = visiveis.length > 0;
    grade.hidden = visiveis.length === 0;
  }

  function marcaChips(sel, attr, valor) {
    $$(sel).forEach(function (c) { c.classList.toggle('is-on', c.getAttribute(attr) === valor); });
  }
  $$('.chip[data-marca]').forEach(function (c) {
    c.addEventListener('click', function () { est.marca = c.getAttribute('data-marca'); marcaChips('.chip[data-marca]', 'data-marca', est.marca); aplica(); });
  });
  $$('.chip[data-cat]').forEach(function (c) {
    c.addEventListener('click', function () { est.cat = c.getAttribute('data-cat'); marcaChips('.chip[data-cat]', 'data-cat', est.cat); aplica(); });
  });
  var chipFav = $('#chip-fav');
  if (chipFav) chipFav.addEventListener('click', function () {
    est.fav = !est.fav; chipFav.setAttribute('aria-pressed', est.fav ? 'true' : 'false'); aplica();
  });
  var t;
  elBusca.addEventListener('input', function () { clearTimeout(t); t = setTimeout(function () { est.busca = elBusca.value; aplica(); }, 120); });
  elOrdem.addEventListener('change', function () { est.ordem = elOrdem.value; aplica(); });
  elPreco.addEventListener('change', function () { est.preco = elPreco.value; aplica(); });

  function limpa() {
    est = { busca: '', marca: '', cat: '', preco: '', ordem: est.ordem, fav: false };
    elBusca.value = ''; elPreco.value = '';
    marcaChips('.chip[data-marca]', 'data-marca', ''); marcaChips('.chip[data-cat]', 'data-cat', '');
    if (chipFav) chipFav.setAttribute('aria-pressed', 'false');
    aplica();
  }
  $('#limpar-filtros').addEventListener('click', limpa);

  /* busca da capa (hero) */
  var hero = $('#busca-hero');
  if (hero) hero.addEventListener('submit', function (e) {
    e.preventDefault();
    est.marca = $('#hero-marca').value;
    var max = $('#hero-preco').value;
    est.preco = max ? '0-' + max : '';
    elPreco.value = est.preco;
    marcaChips('.chip[data-marca]', 'data-marca', est.marca);
    aplica();
    $('#motos').scrollIntoView({ behavior: 'smooth' });
  });

  /* ---------- Janela de detalhes ---------- */
  var modal = $('#modal'), caixa = $('.modal__caixa', modal);
  var atual = null, foto = 0, voltarFoco = null;
  var elImg = $('#m-img'), elMini = $('#m-mini'), elCont = $('#m-contador'), elPalco = $('#m-palco');

  function linkMoto(m) { return location.href.split('#')[0] + '#moto-' + m.id; }

  function mostraFoto(i) {
    var fs = atual.thumbs, n = fs.length;
    if (!n) return;
    foto = (i + n) % n;
    elImg.src = fs[foto];
    elImg.alt = atual.titulo + ' — foto ' + (foto + 1) + ' de ' + n;
    elCont.textContent = (foto + 1) + ' / ' + n;
    $$('button', elMini).forEach(function (b, k) { b.classList.toggle('is-on', k === foto); });
    var ativo = $$('button', elMini)[foto];
    if (ativo && ativo.scrollIntoView) ativo.scrollIntoView({ block: 'nearest', inline: 'center' });
    // já deixa a próxima foto carregando
    if (n > 1) { var pre = new Image(); pre.src = fs[(foto + 1) % n]; }
  }

  function abre(id, origem) {
    var m = motos[id]; if (!m) return;
    atual = m; voltarFoco = origem || document.activeElement;
    $('#m-titulo').textContent = m.titulo;
    $('#m-preco').textContent = brl(m.preco);

    var ficha = $('#m-ficha'); ficha.textContent = '';
    [['Ano', m.ano || '—'], ['Quilometragem', m.km_txt], ['Cilindrada', m.cc_txt], ['Potência', m.cv_txt], ['Transmissão', m.trans]]
      .forEach(function (r) {
        var dt = document.createElement('dt'), dd = document.createElement('dd');
        dt.textContent = r[0]; dd.textContent = r[1]; ficha.appendChild(dt); ficha.appendChild(dd);
      });

    elMini.textContent = '';
    m.mini.forEach(function (src, k) {
      var b = document.createElement('button'), im = document.createElement('img');
      b.type = 'button'; b.setAttribute('aria-label', 'Ver foto ' + (k + 1));
      im.src = src; im.alt = ''; im.loading = 'lazy'; b.appendChild(im);
      b.addEventListener('click', function () { mostraFoto(k); });
      elMini.appendChild(b);
    });
    var varias = m.thumbs.length > 1;
    $('#m-ant').hidden = $('#m-prox').hidden = !varias;
    elMini.hidden = elCont.hidden = !varias;

    var msg = 'Olá! Tenho interesse na ' + m.titulo + ' (' + brl(m.preco) + ') que vi no site: ' + linkMoto(m);
    $('#m-zap').href = 'https://wa.me/' + DADOS.zap + '?text=' + encodeURIComponent(msg);
    $('#m-compartilhar').textContent = 'Compartilhar';

    var sim = $('#m-sim');
    if (sim) { sim.open = false; $('#sim-entrada').value = ''; simula(); }

    mostraFoto(0);
    modal.hidden = false;
    document.body.classList.add('modal-aberto');
    caixa.focus();
    history.replaceState(null, '', '#moto-' + id);
  }

  function fecha() {
    if (modal.hidden) return;
    modal.hidden = true;
    document.body.classList.remove('modal-aberto');
    history.replaceState(null, '', location.pathname + location.search + '#motos');
    if (voltarFoco && voltarFoco.focus) voltarFoco.focus();
  }

  $$('[data-fechar]', modal).forEach(function (el) { el.addEventListener('click', fecha); });
  $('#m-ant').addEventListener('click', function () { mostraFoto(foto - 1); });
  $('#m-prox').addEventListener('click', function () { mostraFoto(foto + 1); });

  document.addEventListener('keydown', function (e) {
    if (modal.hidden) return;
    if (e.key === 'Escape') fecha();
    else if (e.key === 'ArrowLeft') mostraFoto(foto - 1);
    else if (e.key === 'ArrowRight') mostraFoto(foto + 1);
    else if (e.key === 'Tab') {                       // mantém o foco dentro da janela
      var foc = $$('a[href], button:not([hidden]), input, select, summary', caixa).filter(function (x) { return x.offsetParent !== null; });
      if (!foc.length) return;
      var p = foc[0], u = foc[foc.length - 1];
      if (e.shiftKey && (document.activeElement === p || document.activeElement === caixa)) { e.preventDefault(); u.focus(); }
      else if (!e.shiftKey && document.activeElement === u) { e.preventDefault(); p.focus(); }
    }
  });

  var x0 = null;                                       // arrastar o dedo para trocar de foto
  elPalco.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
  elPalco.addEventListener('touchend', function (e) {
    if (x0 === null) return;
    var dx = e.changedTouches[0].clientX - x0; x0 = null;
    if (Math.abs(dx) > 45) mostraFoto(foto + (dx < 0 ? 1 : -1));
  });

  $('#m-compartilhar').addEventListener('click', function () {
    var url = linkMoto(atual), self = this;
    if (navigator.share) {
      navigator.share({ title: atual.titulo + ' — Rafa Motos', text: atual.titulo + ' por ' + brl(atual.preco), url: url }).catch(function () {});
    } else if (navigator.clipboard) {
      navigator.clipboard.writeText(url).then(function () { self.textContent = 'Link copiado!'; });
    } else { prompt('Copie o link:', url); }
  });

  /* ---------- Simulador de parcelas ---------- */
  var elEnt = $('#sim-entrada'), elPrazo = $('#sim-prazo'), elSim = $('#sim-res');
  function simula() {
    if (!elEnt || !atual) return;
    var ent = parseInt((elEnt.value || '').replace(/\D/g, ''), 10) || 0;
    elEnt.value = ent ? ent.toLocaleString('pt-BR') : '';
    var financiado = atual.preco - ent, n = +elPrazo.value, i = DADOS.taxa;
    elSim.textContent = '';
    if (financiado <= 0) { elSim.textContent = 'Com essa entrada não é preciso financiar.'; return; }
    var parcela = financiado * i / (1 - Math.pow(1 + i, -n));
    var forte = document.createElement('strong');
    forte.textContent = brl(parcela);
    elSim.appendChild(document.createTextNode(n + 'x de '));
    elSim.appendChild(forte);
  }
  if (elEnt) { elEnt.addEventListener('input', simula); elPrazo.addEventListener('change', simula); }

  /* ---------- Início ---------- */
  pintaFavs();
  aplica();
  var m = location.hash.match(/^#moto-(\d+)$/);
  if (m && motos[+m[1]]) abre(+m[1]);
})();
