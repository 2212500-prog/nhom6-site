<?php
header('Content-Type: text/html; charset=UTF-8');
$server_name = gethostname();
$server_ip   = $_SERVER['SERVER_ADDR'] ?? '127.0.0.1';
$version     = trim(file_exists(__DIR__.'/.version') ? file_get_contents(__DIR__.'/.version') : 'v1.0.0');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nhóm 6 - CTK46MMT</title>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300..700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
  <style>
    *{box-sizing:border-box;margin:0;padding:0}
    :root{--bg:#080b10;--surface:#0d1117;--border:rgba(255,255,255,0.08);--text:#e6edf3;--muted:#7d8590;--accent:#00d4aa;--accent-2:#4f8eff;--font:'Space Grotesk',sans-serif;--mono:'Space Mono',monospace}
    body{background:var(--bg);color:var(--text);font-family:var(--font);min-height:100vh;overflow-x:hidden}
    #bg-canvas{position:fixed;inset:0;z-index:0;opacity:.4}
    nav{position:fixed;top:0;left:0;right:0;z-index:100;padding:1rem 2rem;background:rgba(8,11,16,.9);backdrop-filter:blur(12px);display:flex;align-items:center;justify-content:space-between}
    .logo{font-weight:700;font-size:1.1rem;display:flex;align-items:center;gap:.5rem}
    .logo span{color:var(--accent)}
    .badge{display:inline-flex;align-items:center;gap:.5rem;padding:.3rem .8rem;background:rgba(0,212,170,.08);border:1px solid rgba(0,212,170,.25);border-radius:999px;font-family:var(--mono);font-size:.75rem;color:var(--accent)}
    .dot-pulse{width:6px;height:6px;background:var(--accent);border-radius:50%;animation:pulse 2s infinite}
    @keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.4;transform:scale(.7)}}
    main{position:relative;z-index:1;min-height:100vh;display:flex;flex-direction:column;justify-content:center;align-items:center;text-align:center;padding:6rem 2rem 2rem}
    .eyebrow{font-family:var(--mono);font-size:.8rem;color:var(--muted);letter-spacing:.12em;text-transform:uppercase;margin-bottom:1.5rem;opacity:0;animation:fadeUp .7s .2s forwards}
    h1{font-size:clamp(2.5rem,7vw,5.5rem);font-weight:700;line-height:1.05;letter-spacing:-.02em;margin-bottom:1.5rem;opacity:0;animation:fadeUp .7s .35s forwards}
    h1 .a1{color:var(--accent)} h1 .a2{color:var(--accent-2)}
    .desc{max-width:500px;color:var(--muted);font-size:1.05rem;margin-bottom:3rem;opacity:0;animation:fadeUp .7s .5s forwards}
    .version-card{display:flex;flex-wrap:wrap;gap:1.5rem;justify-content:center;padding:1.25rem 2rem;background:var(--surface);border:1px solid var(--border);border-radius:.75rem;opacity:0;animation:fadeUp .7s .65s forwards}
    .vi{display:flex;flex-direction:column;align-items:center;gap:.2rem}
    .vi-label{font-family:var(--mono);font-size:.7rem;color:var(--muted);text-transform:uppercase;letter-spacing:.1em}
    .vi-val{font-family:var(--mono);font-size:.95rem;font-weight:700}
    .vi-val.green{color:var(--accent)} .vi-val.blue{color:var(--accent-2)}
    .cards{display:flex;flex-wrap:wrap;gap:1rem;justify-content:center;margin-top:3rem;opacity:0;animation:fadeUp .7s .8s forwards}
    .card{background:var(--surface);border:1px solid var(--border);border-radius:.75rem;padding:1.25rem 1.5rem;min-width:140px;text-align:center;transition:transform .3s,border-color .3s}
    .card:hover{transform:translateY(-4px);border-color:rgba(255,255,255,.2)}
    .card-icon{font-size:1.4rem;margin-bottom:.4rem} .card-title{font-weight:700;font-size:.85rem;margin-bottom:.2rem}
    .card-ip{font-family:var(--mono);font-size:.72rem;color:var(--muted)}
    .ha-section{position:relative;z-index:1;width:100%;max-width:620px;margin:2rem auto 0;padding:0 2rem 4rem;opacity:0;animation:fadeUp .7s 1s forwards}
    .ha-title{font-family:var(--mono);font-size:.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:.12em;margin-bottom:1rem;text-align:center;display:flex;align-items:center;justify-content:center;gap:.5rem}
    .ha-refresh-dot{width:6px;height:6px;border-radius:50%;background:var(--accent);animation:pulse 2s infinite}
    .ha-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
    .ha-card{background:var(--surface);border:1px solid var(--border);border-radius:.75rem;padding:1rem 1.25rem;display:flex;flex-direction:column;gap:.75rem;transition:border-color .4s}
    .ha-card.up{border-color:rgba(0,212,170,.35)} .ha-card.down{border-color:rgba(244,67,54,.35)}
    .ha-header{display:flex;align-items:center;justify-content:space-between}
    .ha-name{font-weight:700;font-size:1rem}
    .ha-status{display:inline-flex;align-items:center;gap:.4rem;font-family:var(--mono);font-size:.7rem;padding:.2rem .6rem;border-radius:999px}
    .ha-status.up{background:rgba(0,212,170,.12);color:var(--accent)} .ha-status.down{background:rgba(244,67,54,.12);color:#f44336}
    .ha-sdot{width:6px;height:6px;border-radius:50%;flex-shrink:0}
    .ha-status.up .ha-sdot{background:var(--accent);animation:pulse 2s infinite} .ha-status.down .ha-sdot{background:#f44336}
    .ha-metrics{display:grid;grid-template-columns:1fr 1fr;gap:.5rem}
    .ha-metric-label{font-family:var(--mono);font-size:.62rem;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.15rem}
    .ha-metric-val{font-family:var(--mono);font-size:.85rem;font-weight:700;color:var(--text)}
    .ha-loading{text-align:center;color:var(--muted);font-family:var(--mono);font-size:.75rem;padding:1.5rem;grid-column:span 2}
    footer{position:relative;z-index:1;text-align:center;padding:1.5rem;color:var(--muted);font-size:.8rem;border-top:1px solid var(--border);font-family:var(--mono)}
    @keyframes fadeUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
  </style>
</head>
<body>
<canvas id="bg-canvas"></canvas>
<nav>
  <div class="logo">
    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#00d4aa" stroke-width="2"><polygon points="12 2 22 8.5 22 15.5 12 22 2 15.5 2 8.5"/><line x1="12" y1="2" x2="12" y2="22"/><line x1="2" y1="8.5" x2="22" y2="8.5"/><line x1="2" y1="15.5" x2="22" y2="15.5"/></svg>
    Group <span>6</span>
  </div>
  <div class="badge"><span class="dot-pulse"></span><?= htmlspecialchars($server_name) ?></div>
</nav>
<main>
  <p class="eyebrow">CTK46MMT &mdash; CHUYÊN Ð? M?NG MÁY TÍNH 2</p>
  <h1>HA System <span class="a1">HA</span><br><span class="a2">WordPress</span></h1>
  <p class="desc">Load Balancing &middot; NFS Shared Storage &middot; Rolling Update &middot; Auto Backup</p>
  <div class="version-card">
    <div class="vi"><span class="vi-label">version</span><span class="vi-val green"><?= htmlspecialchars($version) ?></span></div>
    <div class="vi"><span class="vi-label">server</span><span class="vi-val blue"><?= htmlspecialchars($server_name) ?></span></div>
    <div class="vi"><span class="vi-label">ip</span><span class="vi-val"><?= htmlspecialchars($server_ip) ?></span></div>
    <div class="vi"><span class="vi-label">status</span><span class="vi-val green">&#9679; online</span></div>
    <div class="vi"><span class="vi-label">time</span><span class="vi-val" id="clock"><?= date('H:i:s') ?></span></div>
  </div>
  <div class="cards">
    <div class="card"><div class="card-icon">&#9878;</div><div class="card-title">Load Balancer</div><div class="card-ip">192.168.150.10</div></div>
    <div class="card"><div class="card-icon">&#128421;</div><div class="card-title">Web Server 1</div><div class="card-ip">192.168.150.11</div></div>
    <div class="card"><div class="card-icon">&#128421;</div><div class="card-title">Web Server 2</div><div class="card-ip">192.168.150.12</div></div>
    <div class="card"><div class="card-icon">&#128230;</div><div class="card-title">NFS Server</div><div class="card-ip">192.168.150.20</div></div>
    <div class="card"><div class="card-icon">&#128193;</div><div class="card-title">Database</div><div class="card-ip">192.168.150.30</div></div>
  </div>
</main>
<section class="ha-section">
  <p class="ha-title"><span class="ha-refresh-dot"></span> HAPROXY BACKEND &mdash; LIVE STATUS</p>
  <div class="ha-grid" id="ha-grid"><div class="ha-loading">Loading...</div></div>
</section>
<footer><?= htmlspecialchars($server_name) ?> &middot; <?= htmlspecialchars($version) ?> &middot; Nhóm 6 CTK46MMT</footer>
<script>
setInterval(()=>{const d=new Date();document.getElementById('clock').textContent=d.getHours().toString().padStart(2,'0')+':'+d.getMinutes().toString().padStart(2,'0')+':'+d.getSeconds().toString().padStart(2,'0');},1000);
function fetchStats(){fetch('stats.php?t='+Date.now()).then(r=>r.json()).then(renderStats).catch(()=>renderStats(null));}
function renderStats(data){
  const grid=document.getElementById('ha-grid');
  if(!data||!Object.keys(data).length){grid.innerHTML='<div class="ha-loading">Cannot connect to HAProxy</div>';return;}
  const now=new Date(),t=now.getHours().toString().padStart(2,'0')+':'+now.getMinutes().toString().padStart(2,'0')+':'+now.getSeconds().toString().padStart(2,'0');
  grid.innerHTML=['web1','web2'].map(srv=>{
    const s=data[srv],up=s&&s.status==='UP';
    return`<div class="ha-card ${up?'up':'down'}"><div class="ha-header"><span class="ha-name">${srv}</span><span class="ha-status ${up?'up':'down'}"><span class="ha-sdot"></span>${s?s.status:'N/A'}</span></div>${s?`<div class="ha-metrics"><div><div class="ha-metric-label">Requests</div><div class="ha-metric-val">${s.requests}</div></div><div><div class="ha-metric-label">Active</div><div class="ha-metric-val">${s.cur_conn}</div></div><div><div class="ha-metric-label">Data In</div><div class="ha-metric-val">${s.bytes_in} KB</div></div><div><div class="ha-metric-label">Updated</div><div class="ha-metric-val">${t}</div></div></div>`:''}</div>`;
  }).join('');
}
fetchStats();setInterval(fetchStats,5000);
const c=document.getElementById('bg-canvas'),x=c.getContext('2d');let W,H,P=[];
function resize(){W=c.width=innerWidth;H=c.height=innerHeight}resize();addEventListener('resize',resize);
class Par{constructor(){this.reset()}reset(){this.x=Math.random()*W;this.y=Math.random()*H;this.vx=(Math.random()-.5)*.4;this.vy=(Math.random()-.5)*.4;this.r=Math.random()*1.5+.5;this.a=Math.random()*.5+.1;this.col=['#00d4aa','#4f8eff','#9b4fff'][Math.floor(Math.random()*3)]}update(){this.x+=this.vx;this.y+=this.vy;if(this.x<0||this.x>W||this.y<0||this.y>H)this.reset()}draw(){x.beginPath();x.arc(this.x,this.y,this.r,0,Math.PI*2);x.fillStyle=this.col;x.globalAlpha=this.a;x.fill();x.globalAlpha=1}}
for(let i=0;i<100;i++)P.push(new Par());
function frame(){x.clearRect(0,0,W,H);P.forEach(p=>{p.update();p.draw()});for(let i=0;i<P.length;i++)for(let j=i+1;j<P.length;j++){const dx=P[i].x-P[j].x,dy=P[i].y-P[j].y,d=Math.sqrt(dx*dx+dy*dy);if(d<100){x.beginPath();x.moveTo(P[i].x,P[i].y);x.lineTo(P[j].x,P[j].y);x.strokeStyle=P[i].col;x.globalAlpha=(1-d/100)*.12;x.lineWidth=.5;x.stroke();x.globalAlpha=1}}requestAnimationFrame(frame)}frame();
</script>
</body>
</html>