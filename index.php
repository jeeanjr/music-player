<?php
$audio_url = isset($_GET['url']) ? urldecode($_GET['url']) : '';
$nome = isset($_GET['nome']) ? htmlspecialchars(urldecode($_GET['nome'])) : 'Sua Música';

// Validate URL is from our Supabase
if (!empty($audio_url) && !str_contains($audio_url, 'supabase.co')) {
    $audio_url = '';
}

$share_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$whatsapp_text = urlencode("Olha a música personalizada que eu criei! 🎵 " . $share_url);
$checkout_url = "https://digitalagencia.store/criesuamusica?utm_source=player&utm_medium=recompra&utm_campaign=player-musica";
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta property="og:title" content="<?= $nome ?> — Crie Sua Música">
<meta property="og:description" content="Uma música criada especialmente para você 🎵">
<title><?= $nome ?> — Crie Sua Música</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --gold: #C9A84C;
  --gold-light: #E8C97A;
  --gold-dim: rgba(201,168,76,0.12);
  --gold-border: rgba(201,168,76,0.25);
  --bg: #0B0A07;
  --bg2: #111008;
  --white: #F5F0E8;
  --muted: rgba(245,240,232,0.5);
  --green: #25D366;
}

html, body {
  min-height: 100vh;
  background: var(--bg);
  color: var(--white);
  font-family: 'DM Sans', sans-serif;
  overflow-x: hidden;
}

body::before {
  content: '';
  position: fixed; inset: 0;
  background:
    radial-gradient(ellipse 100% 60% at 50% 0%, rgba(201,168,76,0.08) 0%, transparent 70%),
    radial-gradient(ellipse 60% 40% at 100% 100%, rgba(201,168,76,0.04) 0%, transparent 60%);
  pointer-events: none; z-index: 0;
}

.wrap {
  position: relative; z-index: 1;
  min-height: 100vh;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  padding: 48px 24px;
  gap: 0;
}

/* Animations */
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
@keyframes pulse {
  0%, 100% { transform: scale(1); opacity: 1; }
  50% { transform: scale(1.05); opacity: 0.8; }
}
@keyframes barDance {
  0%, 100% { transform: scaleY(0.4); }
  50% { transform: scaleY(1); }
}

/* Logo */
.logo {
  font-family: 'DM Sans', sans-serif;
  font-size: 11px; letter-spacing: 0.3em; text-transform: uppercase;
  color: var(--gold); margin-bottom: 56px;
  opacity: 0; animation: fadeUp 0.8s ease 0.1s forwards;
}

/* Card */
.card {
  background: var(--bg2);
  border: 1px solid var(--gold-border);
  border-radius: 24px;
  padding: 48px 40px;
  max-width: 480px; width: 100%;
  text-align: center;
  position: relative; overflow: hidden;
  opacity: 0; animation: fadeUp 0.8s ease 0.3s forwards;
}

.card::before {
  content: '';
  position: absolute; top: 0; left: 0; right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--gold), transparent);
}

/* Waveform */
.waveform {
  display: flex; align-items: center; justify-content: center;
  gap: 4px; margin-bottom: 32px; height: 48px;
}
.waveform.playing .bar { animation: barDance 1s ease-in-out infinite; }
.bar {
  width: 3px; border-radius: 2px;
  background: var(--gold);
  animation: none;
  transition: height 0.3s ease;
}
.bar:nth-child(1)  { height: 12px; animation-delay: 0s; }
.bar:nth-child(2)  { height: 24px; animation-delay: 0.1s; }
.bar:nth-child(3)  { height: 36px; animation-delay: 0.2s; }
.bar:nth-child(4)  { height: 20px; animation-delay: 0.15s; }
.bar:nth-child(5)  { height: 44px; animation-delay: 0.05s; }
.bar:nth-child(6)  { height: 28px; animation-delay: 0.25s; }
.bar:nth-child(7)  { height: 44px; animation-delay: 0.1s; }
.bar:nth-child(8)  { height: 20px; animation-delay: 0.3s; }
.bar:nth-child(9)  { height: 36px; animation-delay: 0.2s; }
.bar:nth-child(10) { height: 16px; animation-delay: 0s; }

/* Title */
.music-label {
  font-size: 10px; letter-spacing: 0.25em; text-transform: uppercase;
  color: var(--gold); margin-bottom: 12px;
}
.music-title {
  font-family: 'Playfair Display', serif;
  font-size: clamp(22px, 4vw, 30px);
  font-weight: 700; color: var(--white);
  margin-bottom: 32px; line-height: 1.3;
}

/* Progress bar */
.progress-wrap {
  width: 100%; height: 3px;
  background: rgba(255,255,255,0.08);
  border-radius: 2px; margin-bottom: 12px;
  cursor: pointer; position: relative;
}
.progress-fill {
  height: 100%; width: 0%;
  background: linear-gradient(90deg, var(--gold), var(--gold-light));
  border-radius: 2px; transition: width 0.1s linear;
  pointer-events: none;
}
.time-row {
  display: flex; justify-content: space-between;
  font-size: 11px; color: var(--muted);
  margin-bottom: 32px;
}

/* Play button */
.play-btn {
  width: 72px; height: 72px; border-radius: 50%;
  background: var(--gold);
  border: none; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 32px;
  transition: all 0.2s ease;
  box-shadow: 0 8px 32px rgba(201,168,76,0.3);
}
.play-btn:hover {
  transform: scale(1.08);
  box-shadow: 0 12px 40px rgba(201,168,76,0.45);
}
.play-btn svg { width: 28px; height: 28px; fill: #0B0A07; }

/* Action buttons */
.actions {
  display: flex; gap: 12px; justify-content: center;
  flex-wrap: wrap; margin-bottom: 0;
}
.btn {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 12px 20px; border-radius: 50px;
  font-family: 'DM Sans', sans-serif;
  font-size: 13px; font-weight: 600;
  text-decoration: none; cursor: pointer;
  border: none; transition: all 0.2s;
}
.btn-outline {
  background: transparent;
  border: 1px solid var(--gold-border);
  color: var(--gold);
}
.btn-outline:hover {
  background: var(--gold-dim);
  border-color: var(--gold);
}
.btn-whatsapp {
  background: var(--green);
  color: #fff;
}
.btn-whatsapp:hover { filter: brightness(1.1); }
.btn svg { width: 16px; height: 16px; }

/* Divider */
.divider {
  width: 100%; height: 1px;
  background: var(--gold-border);
  margin: 36px 0;
}

/* Repurchase */
.repurchase {
  max-width: 480px; width: 100%;
  text-align: center;
  opacity: 0; animation: fadeUp 0.8s ease 0.5s forwards;
}
.repurchase p {
  font-size: 13px; color: var(--muted);
  margin-bottom: 16px; line-height: 1.7;
}
.btn-repurchase {
  display: inline-flex; align-items: center; gap: 10px;
  background: transparent;
  border: 1px solid var(--gold-border);
  color: var(--gold-light);
  padding: 14px 28px; border-radius: 50px;
  font-family: 'DM Sans', sans-serif;
  font-size: 14px; font-weight: 600;
  text-decoration: none;
  transition: all 0.2s;
}
.btn-repurchase:hover {
  background: var(--gold-dim);
  border-color: var(--gold);
  transform: translateY(-1px);
}

/* No audio */
.no-audio {
  color: var(--muted); font-size: 14px; line-height: 1.8;
  padding: 24px 0;
}

/* Footer */
.footer {
  font-size: 11px; color: rgba(245,240,232,0.2);
  margin-top: 48px;
  opacity: 0; animation: fadeUp 0.8s ease 0.7s forwards;
}

@media (max-width: 480px) {
  .card { padding: 36px 24px; border-radius: 20px; }
  .actions { gap: 8px; }
  .btn { padding: 11px 16px; font-size: 12px; }
}
</style>
</head>
<body>
<div class="wrap">

  <div class="logo">✦ Crie Sua Música ✦</div>

  <div class="card">

    <?php if (!empty($audio_url)): ?>

    <!-- Waveform -->
    <div class="waveform" id="waveform">
      <?php for ($i = 0; $i < 10; $i++): ?>
      <div class="bar"></div>
      <?php endfor; ?>
    </div>

    <!-- Title -->
    <p class="music-label">Sua música personalizada</p>
    <h1 class="music-title"><?= $nome ?></h1>

    <!-- Hidden audio -->
    <audio id="audio" preload="metadata">
      <source src="<?= htmlspecialchars($audio_url) ?>" type="audio/mpeg">
    </audio>

    <!-- Progress -->
    <div class="progress-wrap" id="progress-wrap">
      <div class="progress-fill" id="progress-fill"></div>
    </div>
    <div class="time-row">
      <span id="time-current">0:00</span>
      <span id="time-total">0:00</span>
    </div>

    <!-- Play button -->
    <button class="play-btn" id="play-btn" onclick="togglePlay()">
      <svg id="icon-play" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
      <svg id="icon-pause" viewBox="0 0 24 24" style="display:none"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
    </button>

    <!-- Actions -->
    <div class="actions">
      <a href="<?= htmlspecialchars($audio_url) ?>" download class="btn btn-outline">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
        Baixar música
      </a>
      <a href="https://wa.me/?text=<?= $whatsapp_text ?>" target="_blank" class="btn btn-whatsapp">
        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        Compartilhar
      </a>
    </div>

    <?php else: ?>
    <div class="no-audio">
      <p>Link de áudio inválido ou não informado.</p>
    </div>
    <?php endif; ?>

    <div class="divider"></div>

    <!-- Repurchase -->
    <p style="font-size:13px;color:var(--muted);margin-bottom:16px;line-height:1.7;">
      Gostou? Crie uma música para outra pessoa especial 🎁
    </p>
    <a href="<?= $checkout_url ?>" class="btn-repurchase" target="_blank">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/></svg>
      Quero criar outra música
    </a>

  </div>

  <p class="footer">© 2026 Agência Digital LTDA · Crie Sua Música</p>

</div>

<script>
const audio = document.getElementById('audio');
const playBtn = document.getElementById('play-btn');
const iconPlay = document.getElementById('icon-play');
const iconPause = document.getElementById('icon-pause');
const progressFill = document.getElementById('progress-fill');
const progressWrap = document.getElementById('progress-wrap');
const timeCurrent = document.getElementById('time-current');
const timeTotal = document.getElementById('time-total');
const waveform = document.getElementById('waveform');

function fmt(s) {
  const m = Math.floor(s / 60);
  return m + ':' + String(Math.floor(s % 60)).padStart(2, '0');
}

function togglePlay() {
  if (audio.paused) {
    audio.play();
    iconPlay.style.display = 'none';
    iconPause.style.display = 'block';
    waveform.classList.add('playing');
  } else {
    audio.pause();
    iconPlay.style.display = 'block';
    iconPause.style.display = 'none';
    waveform.classList.remove('playing');
  }
}

audio.addEventListener('loadedmetadata', () => {
  timeTotal.textContent = fmt(audio.duration);
});

audio.addEventListener('timeupdate', () => {
  if (audio.duration) {
    progressFill.style.width = (audio.currentTime / audio.duration * 100) + '%';
    timeCurrent.textContent = fmt(audio.currentTime);
  }
});

audio.addEventListener('ended', () => {
  iconPlay.style.display = 'block';
  iconPause.style.display = 'none';
  waveform.classList.remove('playing');
  progressFill.style.width = '0%';
  timeCurrent.textContent = '0:00';
  audio.currentTime = 0;
});

progressWrap.addEventListener('click', (e) => {
  const rect = progressWrap.getBoundingClientRect();
  const pct = (e.clientX - rect.left) / rect.width;
  audio.currentTime = pct * audio.duration;
});
</script>
</body>
</html>
