<?php
$audio_url = isset($_GET['url']) ? urldecode($_GET['url']) : '';
$nome = isset($_GET['nome']) ? htmlspecialchars(urldecode($_GET['nome'])) : 'Tu Canción';
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'es';

if (!empty($audio_url) && !str_contains($audio_url, 'supabase.co')) {
    $audio_url = '';
}

$share_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$whatsapp_text = urlencode("¡Escucha la canción personalizada que creé! 🎵 " . $share_url);
$checkout_url = "https://chat.digitalagencia.store/es?utm_source=player&utm_medium=recompra&utm_campaign=player-musica";
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta property="og:title" content="<?= $nome ?> — Tuned4U">
<meta property="og:description" content="Una canción creada especialmente para ti 🎵">
<title><?= $nome ?> — Tuned4U</title>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400;1,600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
:root{
  --gold:#C9A84C;
  --gold2:#E8C97A;
  --gold-grad:linear-gradient(135deg,#C9A84C,#E8C97A);
  --gold-dim:rgba(201,168,76,0.12);
  --gold-border:rgba(201,168,76,0.25);
  --bg:#0f0e0c;
  --bg2:#1C1A16;
  --text:#F5F0E8;
  --muted:rgba(245,240,232,0.45);
  --green:#25D366;
}
html,body{min-height:100vh;background:var(--bg);color:var(--text);font-family:'Inter',sans-serif;overflow-x:hidden}
body::before{content:'';position:fixed;inset:0;background:radial-gradient(ellipse 100% 60% at 50% 0%,rgba(201,168,76,0.08) 0%,transparent 70%),radial-gradient(ellipse 60% 40% at 100% 100%,rgba(201,168,76,0.04) 0%,transparent 60%);pointer-events:none;z-index:0}
.wrap{position:relative;z-index:1;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:48px 20px;gap:0}

@keyframes fadeUp{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
@keyframes pulse{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(1.05);opacity:.8}}
@keyframes barDance{0%,100%{transform:scaleY(0.4)}50%{transform:scaleY(1)}}

/* Logo */
.logo-wrap{margin-bottom:40px;opacity:0;animation:fadeUp .8s ease .1s forwards}
.logo-wrap img{height:36px;width:auto;display:block}

/* Card */
.card{background:var(--bg2);border:1px solid var(--gold-border);border-radius:24px;padding:44px 36px;max-width:460px;width:100%;text-align:center;position:relative;overflow:hidden;opacity:0;animation:fadeUp .8s ease .3s forwards}
.card::before{content:'';position:absolute;top:0;left:0;right:0;height:1px;background:linear-gradient(90deg,transparent,var(--gold),transparent)}

/* Waveform */
.waveform{display:flex;align-items:center;justify-content:center;gap:4px;margin-bottom:28px;height:48px}
.waveform.playing .bar{animation:barDance 1s ease-in-out infinite}
.bar{width:3px;border-radius:2px;background:var(--gold);animation:none;transition:height .3s ease}
.bar:nth-child(1){height:12px;animation-delay:0s}
.bar:nth-child(2){height:24px;animation-delay:.1s}
.bar:nth-child(3){height:36px;animation-delay:.2s}
.bar:nth-child(4){height:20px;animation-delay:.15s}
.bar:nth-child(5){height:44px;animation-delay:.05s}
.bar:nth-child(6){height:28px;animation-delay:.25s}
.bar:nth-child(7){height:44px;animation-delay:.1s}
.bar:nth-child(8){height:20px;animation-delay:.3s}
.bar:nth-child(9){height:36px;animation-delay:.2s}
.bar:nth-child(10){height:16px;animation-delay:0s}

/* Title */
.music-label{font-size:.62rem;letter-spacing:.25em;text-transform:uppercase;color:var(--gold);margin-bottom:10px}
.music-title{font-family:'Cormorant Garamond',serif;font-size:clamp(1.5rem,4vw,2rem);font-weight:600;color:var(--text);margin-bottom:28px;line-height:1.25;font-style:italic}

/* Progress */
.progress-wrap{width:100%;height:3px;background:rgba(255,255,255,0.08);border-radius:2px;margin-bottom:10px;cursor:pointer;position:relative}
.progress-fill{height:100%;width:0%;background:var(--gold-grad);border-radius:2px;transition:width .1s linear;pointer-events:none}
.time-row{display:flex;justify-content:space-between;font-size:.68rem;color:var(--muted);margin-bottom:28px}

/* Play button */
.play-btn{width:68px;height:68px;border-radius:50%;background:var(--gold-grad);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;margin:0 auto 28px;transition:transform .2s,opacity .2s;box-shadow:0 8px 32px rgba(201,168,76,.28)}
.play-btn:hover{transform:scale(1.07);box-shadow:0 12px 40px rgba(201,168,76,.4)}
.play-btn svg{width:26px;height:26px;fill:#0f0e0c}

/* Actions */
.actions{display:flex;gap:10px;justify-content:center;flex-wrap:wrap}
.btn{display:inline-flex;align-items:center;gap:7px;padding:11px 18px;border-radius:50px;font-family:'Inter',sans-serif;font-size:.78rem;font-weight:600;text-decoration:none;cursor:pointer;border:none;transition:all .2s}
.btn-outline{background:transparent;border:1px solid var(--gold-border);color:var(--gold)}
.btn-outline:hover{background:var(--gold-dim);border-color:var(--gold)}
.btn-whatsapp{background:var(--green);color:#fff}
.btn-whatsapp:hover{filter:brightness(1.1)}
.btn svg{width:15px;height:15px;flex-shrink:0}

/* Divider */
.divider{width:100%;height:1px;background:var(--gold-border);margin:32px 0}

/* Repurchase */
.repurchase-text{font-size:.78rem;color:var(--muted);margin-bottom:14px;line-height:1.7}
.btn-repurchase{display:inline-flex;align-items:center;gap:9px;background:var(--gold-grad);color:#0f0e0c;padding:13px 26px;border-radius:10px;font-family:'Inter',sans-serif;font-size:.85rem;font-weight:600;text-decoration:none;transition:opacity .2s}
.btn-repurchase:hover{opacity:.88}

/* No audio */
.no-audio{color:var(--muted);font-size:.85rem;line-height:1.8;padding:20px 0}

/* Repurchase section */
.repurchase-section{max-width:460px;width:100%;text-align:center;opacity:0;animation:fadeUp .8s ease .5s forwards;margin-top:0}

/* Footer */
.footer{font-size:.65rem;color:rgba(245,240,232,.18);margin-top:40px;opacity:0;animation:fadeUp .8s ease .7s forwards}

@media(max-width:480px){.card{padding:32px 20px;border-radius:20px}.btn{padding:10px 14px;font-size:.72rem}}
</style>
</head>
<body>
<div class="wrap">

  <div class="logo-wrap">
    <img src="https://qqxmdszwvwooqonmnvzf.supabase.co/storage/v1/object/public/assets/logo.tuned4u.nobackground.png" alt="Tuned4U"/>
  </div>

  <div class="card">

    <?php if (!empty($audio_url)): ?>

    <div class="waveform" id="waveform">
      <?php for ($i = 0; $i < 10; $i++): ?>
      <div class="bar"></div>
      <?php endfor; ?>
    </div>

    <p class="music-label">Tu canción personalizada</p>
    <h1 class="music-title"><?= $nome ?></h1>

    <audio id="audio" preload="metadata">
      <source src="<?= htmlspecialchars($audio_url) ?>" type="audio/mpeg">
    </audio>

    <div class="progress-wrap" id="progress-wrap">
      <div class="progress-fill" id="progress-fill"></div>
    </div>
    <div class="time-row">
      <span id="time-current">0:00</span>
      <span id="time-total">0:00</span>
    </div>

    <button class="play-btn" id="play-btn" onclick="togglePlay()">
      <svg id="icon-play" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
      <svg id="icon-pause" viewBox="0 0 24 24" style="display:none"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
    </button>

    <div class="actions">
      <a href="<?= htmlspecialchars($audio_url) ?>" download class="btn btn-outline">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
        Descargar canción
      </a>
      <a href="https://wa.me/?text=<?= $whatsapp_text ?>" target="_blank" class="btn btn-whatsapp">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        Compartir
      </a>
    </div>

    <?php else: ?>
    <div class="no-audio">
      <p>Enlace de audio inválido o no proporcionado.</p>
    </div>
    <?php endif; ?>

    <div class="divider"></div>

    <p class="repurchase-text">¿Te gustó? Crea una canción para otra persona especial 🎁</p>
    <a href="<?= $checkout_url ?>" class="btn-repurchase">
      ✨ Crear otra canción
    </a>

  </div>

  <p class="footer">© 2026 Tuned4U · Canciones creadas de historias reales</p>

</div>

<script>
const audio = document.getElementById('audio');
const iconPlay = document.getElementById('icon-play');
const iconPause = document.getElementById('icon-pause');
const progressFill = document.getElementById('progress-fill');
const progressWrap = document.getElementById('progress-wrap');
const timeCurrent = document.getElementById('time-current');
const timeTotal = document.getElementById('time-total');
const waveform = document.getElementById('waveform');

function fmt(s){
  const m=Math.floor(s/60);
  return m+':'+String(Math.floor(s%60)).padStart(2,'0');
}

function togglePlay(){
  if(audio.paused){
    audio.play();
    iconPlay.style.display='none';
    iconPause.style.display='block';
    waveform.classList.add('playing');
  }else{
    audio.pause();
    iconPlay.style.display='block';
    iconPause.style.display='none';
    waveform.classList.remove('playing');
  }
}

audio.addEventListener('loadedmetadata',()=>{
  timeTotal.textContent=fmt(audio.duration);
});

audio.addEventListener('timeupdate',()=>{
  if(audio.duration){
    progressFill.style.width=(audio.currentTime/audio.duration*100)+'%';
    timeCurrent.textContent=fmt(audio.currentTime);
  }
});

audio.addEventListener('ended',()=>{
  iconPlay.style.display='block';
  iconPause.style.display='none';
  waveform.classList.remove('playing');
  progressFill.style.width='0%';
  timeCurrent.textContent='0:00';
  audio.currentTime=0;
});

progressWrap.addEventListener('click',(e)=>{
  const rect=progressWrap.getBoundingClientRect();
  const pct=(e.clientX-rect.left)/rect.width;
  audio.currentTime=pct*audio.duration;
});
</script>
</body>
</html>
