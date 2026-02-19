
<style>
    
#sonner-container {
    position: fixed;
    top: 1.25rem;
    right: 1.25rem;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
    pointer-events: none;
}

.sonner-toast {
    pointer-events: all;
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    min-width: 300px;
    max-width: 380px;
    padding: 0.85rem 1rem 0.85rem 0.9rem;
    border-radius: 14px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.13), 0 1.5px 4px rgba(0,0,0,0.07);
    background: #fff;
    border: 1.5px solid #ebebeb;
    position: relative;
    cursor: pointer;
    overflow: hidden;

    /* slide in from right */
    animation: sonner-in 0.38s cubic-bezier(0.21, 1.02, 0.73, 1) forwards;
    transition: box-shadow 0.2s, transform 0.2s;
}

.sonner-toast:hover {
    box-shadow: 0 12px 40px rgba(0,0,0,0.18), 0 2px 8px rgba(0,0,0,0.09);
    transform: translateY(-2px) scale(1.01);
}

.sonner-toast.sonner-out {
    animation: sonner-out 0.35s cubic-bezier(0.06, 0.71, 0.55, 1) forwards;
}

/* type accent bar */
.sonner-toast::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 4px;
    border-radius: 14px 0 0 14px;
}

/* progress bar */
.sonner-progress {
    position: absolute;
    bottom: 0; left: 0;
    height: 3px;
    border-radius: 0 0 14px 14px;
    transform-origin: left;
    animation: sonner-progress linear forwards;
}

/* icon wrapper */
.sonner-icon {
    flex-shrink: 0;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    margin-top: 1px;
}

.sonner-body { flex: 1; min-width: 0; }

.sonner-type-label {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    margin-bottom: 2px;
}

.sonner-message {
    font-size: 13.5px;
    font-weight: 500;
    color: #1a1a2e;
    line-height: 1.45;
    word-break: break-word;
}

.sonner-close {
    flex-shrink: 0;
    background: none;
    border: none;
    cursor: pointer;
    color: #aaa;
    font-size: 16px;
    line-height: 1;
    padding: 0;
    margin-top: 1px;
    transition: color 0.15s;
}
.sonner-close:hover { color: #555; }

/* ── type themes ─────────────────────────── */
.sonner-success::before          { background: #22c55e; }
.sonner-success .sonner-icon      { background: #dcfce7; color: #16a34a; }
.sonner-success .sonner-type-label{ color: #16a34a; }
.sonner-success .sonner-progress  { background: #22c55e; }

.sonner-error::before             { background: #ef4444; }
.sonner-error .sonner-icon        { background: #fee2e2; color: #dc2626; }
.sonner-error .sonner-type-label  { color: #dc2626; }
.sonner-error .sonner-progress    { background: #ef4444; }

.sonner-warning::before           { background: #f59e0b; }
.sonner-warning .sonner-icon      { background: #fef3c7; color: #d97706; }
.sonner-warning .sonner-type-label{ color: #d97706; }
.sonner-warning .sonner-progress  { background: #f59e0b; }

.sonner-info::before              { background: #3b82f6; }
.sonner-info .sonner-icon         { background: #dbeafe; color: #2563eb; }
.sonner-info .sonner-type-label   { color: #2563eb; }
.sonner-info .sonner-progress     { background: #3b82f6; }

/* ── keyframes ───────────────────────────── */
@keyframes sonner-in {
    from { opacity: 0; transform: translateX(110%) scale(0.92); }
    to   { opacity: 1; transform: translateX(0)   scale(1); }
}
@keyframes sonner-out {
    from { opacity: 1; transform: translateX(0)    scale(1);    max-height: 120px; margin-bottom: 0; }
    to   { opacity: 0; transform: translateX(110%) scale(0.88); max-height: 0;    margin-bottom: -0.6rem; }
}
@keyframes sonner-progress {
    from { transform: scaleX(1); }
    to   { transform: scaleX(0); }
}

</style>
<?php




ob_start();
function showToast(string $message, string $type = 'info', int $duration = 5000): void
{
    $allowed = ['success', 'error', 'warning', 'info'];
    if (!in_array($type, $allowed)) $type = 'info';
    $duration = (int) $duration;


    $messageJs = json_encode($message, JSON_UNESCAPED_UNICODE);

 

    echo "<script>if(window.sonnerShowToast) sonnerShowToast({$messageJs},'{$type}',{$duration});</script>";
}
?>
<script>
    (function () {
    if (window.sonnerInitialized) return;
    window.sonnerInitialized = true;

    function ensureContainer() {
        let container = document.getElementById('sonner-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'sonner-container';
            document.body.appendChild(container);
        }
        return container;
    }

    const cfg = {
        success: { label: 'Success', icon: '✓' },
        error:   { label: 'Error',   icon: '✕' },
        warning: { label: 'Warning', icon: '⚠' },
        info:    { label: 'Info',    icon: 'i' },
    };

    window.sonnerShowToast = function(message, type = 'info', duration = 5000) {
        const container = ensureContainer();
        if (!cfg[type]) type = 'info';
        const { label, icon } = cfg[type];
        duration = Number(duration) || 5000;

        const toast = document.createElement('div');
        toast.className = 'sonner-toast sonner-' + type;
        toast.innerHTML = `
            <div class="sonner-icon">${icon}</div>
            <div class="sonner-body">
                <div class="sonner-type-label">${label}</div>
                <div class="sonner-message">${message}</div>
            </div>
            <button class="sonner-close" aria-label="Dismiss">&#x2715;</button>
            <div class="sonner-progress" style="animation-duration:${duration}ms"></div>
        `;

        container.appendChild(toast);

        let timer;
        let paused = false;
        let elapsed = 0;
        let startTime = Date.now();

        function dismiss() {
            if (toast.classList.contains('sonner-out')) return;
            toast.classList.add('sonner-out');
            const progress = toast.querySelector('.sonner-progress');
            if (progress) progress.style.animationPlayState = 'paused';
            clearTimeout(timer);
            setTimeout(() => toast.remove(), 360);
        }

        function startTimer(remaining) {
            startTime = Date.now();
            timer = setTimeout(dismiss, remaining);
        }

        toast.addEventListener('click', dismiss);

        toast.addEventListener('mouseenter', (ev) => {
            paused = true;
            clearTimeout(timer);
            elapsed += Date.now() - startTime;
            const progress = toast.querySelector('.sonner-progress');
            if (progress) progress.style.animationPlayState = 'paused';
        });

        toast.addEventListener('mouseleave', (ev) => {
            paused = false;
            const remaining = duration - elapsed;
            if (remaining <= 0) { dismiss(); return; }
            const progress = toast.querySelector('.sonner-progress');
            if (progress) progress.style.animationPlayState = 'running';
            startTimer(remaining);
        });

        const closeBtn = toast.querySelector('.sonner-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', (ev) => {
                ev.stopPropagation();
                dismiss();
            });
        }

        startTimer(duration);
    };

})();

</script>