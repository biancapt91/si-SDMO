<div class="sidebar-left">
    <div class="sidebar-modern">
        
        @if(auth()->check() && !auth()->user()->isAdmin())
        <a href="{{ route('pegawai.profil') }}" 
           class="sidebar-item {{ request()->is('pegawai/profil') ? 'active' : '' }}">
            <div class="sidebar-icon">
                <i class="bi bi-person-circle"></i>
            </div>
            <div class="sidebar-text">
                <span class="sidebar-title">Profil Pegawai</span>
            </div>
        </a>
        @endif

        <a href="{{ route('pegawai.index') }}" 
           class="sidebar-item {{ request()->is('pegawai') ? 'active' : '' }}">
            <div class="sidebar-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="sidebar-text">
                <span class="sidebar-title">Daftar Pegawai</span>
            </div>
        </a>

    </div>
</div>

<style>
/* SIDEBAR */
        .sidebar-left {
            position: fixed;
            top: var(--navbar-height);
            left: 0;
            width: 230px;
            height: calc(100vh - var(--navbar-height));
            background: #9ca3b2;
            border-right: 1px solid #ffffff;
            padding: 10px;
            overflow-y: auto;
        }

        /* CONTENT */
        .content-wrapper {
            margin-top: var(--navbar-height);
            margin-left: 200px;
            padding: 60px;
            min-height: calc(100vh - var(--navbar-height));
            background: #f5f6fa;
        }
.sidebar-modern {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.sidebar-item {
    display: flex;
    align-items: center;
    padding: 6px;
    border-radius: 12px;
    text-decoration: none;
    color: #1c345c;
    background: white;
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
}

.sidebar-item:hover {
    background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
    border-color: #d1d5db;
    transform: translateX(5px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    color: #111827;
}

.sidebar-item.active {
    background: linear-gradient(135deg, #171979, #3c33ea);
    border-color: #191c76;
    color: white;
    box-shadow: 0 4px 12px rgba(124, 58, 237, 0.4);
}

.sidebar-icon {
    font-size: 28px;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: rgba(124, 58, 237, 0.1);
    color: #11138f;
    flex-shrink: 0;
    transition: all 0.3s ease;
}

.sidebar-item:hover .sidebar-icon {
    background: rgba(124, 58, 237, 0.15);
    transform: scale(1.1);
}

.sidebar-item.active .sidebar-icon {
    background: rgba(255, 255, 255, 0.2);
    color: white;
}

.sidebar-text {
    display: flex;
    flex-direction: column;
    margin-left: 14px;
    flex: 1;
}

.sidebar-title {
    font-size: 15px;
    font-weight: 600;
    line-height: 1.3;
}

.sidebar-subtitle {
    font-size: 12px;
    opacity: 0.7;
    margin-top: 2px;
}

.sidebar-item.active .sidebar-subtitle {
    opacity: 0.9;
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar-item {
        padding: 12px;
    }
    
    .sidebar-icon {
        width: 40px;
        height: 40px;
        font-size: 20px;
    }
    
    .sidebar-title {
        font-size: 14px;
    }
    
    .sidebar-subtitle {
        font-size: 11px;
    }
}
</style>
