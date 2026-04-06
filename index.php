false, $ctx);
if (!$csv) { echo '{}'; exit; }
$lines   = explode("\n", trim($csv));
$headers = str_getcsv(ltrim($lines[0], '# '));
$result  = [];
foreach (array_slice($lines, 1) as $line) {
    if (!$line) continue;
    $row = array_combine($headers, str_getcsv($line));
    if (in_array($row['svname'], ['web1','web2'])) {
        $result[$row['svname']] = [
            'status'   => $row['status'],
            'requests' => number_format((int)$row['stot']),
            'cur_conn' => $row['scur'],
            'bytes_in' => round((int)$row['bin'] / 1024, 1),
        ];
    }
}
echo json_encode($result);
EOF
Bu?c 2 — Xóa index.php cu r?i t?o m?i
bash
cat /dev/null > ~/project/web/index.php
nano ~/project/web/index.php
Paste toàn b? n?i dung này vào:

<details>
<summary>?? N?i dung index.php (click d? m?)</summary>

php
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
  <title>Nhom 6 - CTK46MMT</title>
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
    footer{position:relative;z-index:1;text-align:center;padding:1.5rem;color:var(--muted);font-size:.8rem;border-top:1px 
Generated Code

