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
