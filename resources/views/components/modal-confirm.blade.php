{{-- Premium Custom Modal --}}
<div id="global-modal" style="display:none;position:fixed;inset:0;z-index:10000;background:rgba(0,0,0,.5);backdrop-filter:blur(6px);align-items:center;justify-content:center;padding:20px;">
    <div style="background:#fff;border-radius:24px;padding:32px;width:100%;max-width:420px;box-shadow:0 24px 48px rgba(0,0,0,.2);animation:modalSlideUp .3s cubic-bezier(0.4, 0, 0.2, 1);position:relative;">
        
        {{-- Close button --}}
        <button onclick="closeGlobalModal()" style="position:absolute;top:20px;right:20px;background:var(--color-surface-soft);border:none;border-radius:12px;width:36px;height:36px;cursor:pointer;font-size:16px;color:var(--color-text-tertiary);transition:all .2s;"
                onmouseover="this.style.background='var(--color-border)';this.style.color='var(--color-text-primary)';"
                onmouseout="this.style.background='var(--color-surface-soft)';this.style.color='var(--color-text-tertiary)';">
            <i class="fas fa-times"></i>
        </button>

        {{-- Icon Container --}}
        <div id="modal-icon-container" style="width:64px;height:64px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:28px;">
            <i id="modal-icon" class="fas fa-info-circle"></i>
        </div>

        {{-- Content --}}
        <div style="text-align:center;">
            <h3 id="modal-title" style="font-size:20px;font-weight:900;color:var(--color-text-primary);margin-bottom:8px;">Modal Title</h3>
            <p id="modal-message" style="font-size:14px;color:var(--color-text-secondary);line-height:1.6;margin-bottom:24px;">Modal message goes here.</p>
        </div>

        {{-- Actions --}}
        <div id="modal-actions" style="display:flex;gap:12px;justify-content:center;">
            {{-- Buttons will be injected here dynamically --}}
        </div>

    </div>
</div>

<style>
@keyframes modalSlideUp {
    from { transform: translateY(30px) scale(0.95); opacity: 0; }
    to { transform: translateY(0) scale(1); opacity: 1; }
}
</style>

<script>
window.currentModalResolve = null;

function closeGlobalModal(result = false) {
    const modal = document.getElementById('global-modal');
    modal.style.display = 'none';
    if (window.currentModalResolve) {
        window.currentModalResolve(result);
        window.currentModalResolve = null;
    }
}

/**
 * Show a premium alert modal
 * @param {string} title 
 * @param {string} message 
 * @param {string} type 'success' | 'danger' | 'warning' | 'info'
 */
window.showAlert = function(title, message, type = 'info') {
    return new Promise((resolve) => {
        window.currentModalResolve = resolve;
        setupModal(title, message, type);
        
        const actions = document.getElementById('modal-actions');
        actions.innerHTML = `
            <button onclick="closeGlobalModal(true)" style="background:var(--color-primary);color:#fff;border:none;padding:12px 32px;border-radius:100px;font-weight:700;cursor:pointer;flex:1;box-shadow:0 8px 16px rgba(29,179,73,.2);">
                Mengerti
            </button>
        `;
        
        document.getElementById('global-modal').style.display = 'flex';
    });
};

/**
 * Show a premium confirm modal
 * @param {string} title 
 * @param {string} message 
 * @param {string} type 'danger' | 'warning' | 'info'
 */
window.showConfirm = function(title, message, type = 'warning') {
    return new Promise((resolve) => {
        window.currentModalResolve = resolve;
        setupModal(title, message, type);
        
        const actions = document.getElementById('modal-actions');
        const confirmColor = type === 'danger' ? '#ef4444' : (type === 'warning' ? '#f59e0b' : 'var(--color-primary)');
        const confirmShadow = type === 'danger' ? 'rgba(239,68,68,.2)' : (type === 'warning' ? 'rgba(245,158,11,.2)' : 'rgba(29,179,73,.2)');

        actions.innerHTML = `
            <button onclick="closeGlobalModal(false)" style="background:var(--color-surface-soft);color:var(--color-text-secondary);border:none;padding:12px 24px;border-radius:100px;font-weight:700;cursor:pointer;flex:1;">
                Batal
            </button>
            <button onclick="closeGlobalModal(true)" style="background:${confirmColor};color:#fff;border:none;padding:12px 24px;border-radius:100px;font-weight:700;cursor:pointer;flex:1;box-shadow:0 8px 16px ${confirmShadow};">
                Ya, Lanjutkan
            </button>
        `;
        
        document.getElementById('global-modal').style.display = 'flex';
    });
};

function setupModal(title, message, type) {
    document.getElementById('modal-title').innerHTML = title;
    document.getElementById('modal-message').innerHTML = message;
    
    const iconContainer = document.getElementById('modal-icon-container');
    const icon = document.getElementById('modal-icon');
    
    switch(type) {
        case 'success':
            iconContainer.style.background = '#dcfce7'; // green-100
            iconContainer.style.color = '#16a34a'; // green-600
            icon.className = 'fas fa-check-circle';
            break;
        case 'danger':
            iconContainer.style.background = '#fee2e2'; // red-100
            iconContainer.style.color = '#dc2626'; // red-600
            icon.className = 'fas fa-exclamation-triangle';
            break;
        case 'warning':
            iconContainer.style.background = '#fef3c7'; // amber-100
            iconContainer.style.color = '#d97706'; // amber-600
            icon.className = 'fas fa-exclamation-circle';
            break;
        default: // info
            iconContainer.style.background = '#e0f2fe'; // sky-100
            iconContainer.style.color = '#0284c7'; // sky-600
            icon.className = 'fas fa-info-circle';
            break;
    }
}

// Close on backdrop click
document.getElementById('global-modal').addEventListener('click', function(e) {
    if (e.target === this) closeGlobalModal(false);
});
</script>
