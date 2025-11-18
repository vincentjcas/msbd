<div class="navbar">
    <div class="navbar-brand">
        <img src="{{ asset('images/yapim.png') }}" alt="Logo" style="height: 40px; width: auto; border-radius: 8px;">
        <a href="{{ url('/') }}" style="color: white; text-decoration: none;">
            SMK YAPIM BIRU-BIRU
        </a>
    </div>
    <div class="user-info">
        <div class="user-name">
            <span>{{ auth()->user()->nama_lengkap }}</span>
            <span class="role-badge">{{ strtoupper(auth()->user()->role) }}</span>
        </div>
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
    </div>
</div>
