<style>
/* ============================================================
   Sunset Construction SaaS — Custom Filament Theme Overrides
   Warm Sunset Glassmorphism & High-Contrast Dark Styling
============================================================ */

@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Noto+Sans+Ethiopic:wght@400;600;700&display=swap');

:root {
  --sunset-grad: linear-gradient(135deg, #f97316 0%, #f59e0b 50%, #fb7185 100%);
  --sunset-glow: 0 0 25px rgba(249, 115, 22, 0.25);
}

body, .fi-body {
  font-family: 'Plus Jakarta Sans', 'Noto Sans Ethiopic', system-ui, sans-serif !important;
  background-color: #070a12 !important;
}

/* ── Sidebar Styling ───────────────────────────────────────── */
.fi-sidebar {
  background: linear-gradient(180deg, #0b1120 0%, #070a12 100%) !important;
  border-right: 1px solid #1e2d4d !important;
}

.fi-sidebar-header {
  border-bottom: 1px solid #1e2d4d !important;
  background: rgba(11, 17, 32, 0.8) !important;
  backdrop-filter: blur(12px) !important;
}

.fi-sidebar-item-active .fi-sidebar-item-button {
  background: linear-gradient(90deg, rgba(249, 115, 22, 0.18) 0%, rgba(245, 158, 11, 0.08) 100%) !important;
  border-left: 3px solid #f97316 !important;
  color: #fb923c !important;
}

.fi-sidebar-group-label {
  font-size: 0.72rem !important;
  font-weight: 800 !important;
  text-transform: uppercase !important;
  letter-spacing: 0.08em !important;
  color: #64748b !important;
}

/* ── Topbar / Header ───────────────────────────────────────── */
.fi-topbar {
  background: rgba(11, 17, 32, 0.85) !important;
  backdrop-filter: blur(16px) !important;
  -webkit-backdrop-filter: blur(16px) !important;
  border-bottom: 1px solid #1e2d4d !important;
}

/* ── Widgets & Cards ───────────────────────────────────────── */
.fi-wi-stats-overview-stat, .fi-section, .fi-ta-ctn {
  background: #111827 !important;
  border: 1px solid #1e2d4d !important;
  border-radius: 16px !important;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.45) !important;
  transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease !important;
}

.fi-wi-stats-overview-stat:hover {
  transform: translateY(-3px) !important;
  border-color: rgba(249, 115, 22, 0.4) !important;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6), var(--sunset-glow) !important;
}

.fi-wi-stats-overview-stat-value {
  font-size: 1.75rem !important;
  font-weight: 800 !important;
  letter-spacing: -0.02em !important;
  background: linear-gradient(135deg, #f8fafc 0%, #cbd5e1 100%) !important;
  -webkit-background-clip: text !important;
  -webkit-text-fill-color: transparent !important;
}

/* ── Tables ─────────────────────────────────────────────────── */
.fi-ta-header-cell {
  background: #0f172a !important;
  font-size: 0.72rem !important;
  font-weight: 800 !important;
  text-transform: uppercase !important;
  letter-spacing: 0.06em !important;
  color: #94a3b8 !important;
}

.fi-ta-row:hover {
  background: rgba(30, 45, 79, 0.4) !important;
}

/* ── Buttons & Badges ──────────────────────────────────────── */
.fi-btn-primary {
  background: var(--sunset-grad) !important;
  color: #070a12 !important;
  font-weight: 800 !important;
  border: none !important;
  box-shadow: 0 4px 14px rgba(249, 115, 22, 0.3) !important;
}
.fi-btn-primary:hover {
  filter: brightness(1.1) !important;
  transform: translateY(-1px) !important;
  box-shadow: 0 6px 20px rgba(249, 115, 22, 0.45) !important;
}

.fi-badge {
  font-weight: 800 !important;
  letter-spacing: 0.04em !important;
  text-transform: uppercase !important;
  border-radius: 99px !important;
}

/* ── Custom Scrollbar ──────────────────────────────────────── */
::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: #070a12; }
::-webkit-scrollbar-thumb { background: #1e2d4d; border-radius: 3px; }
::-webkit-scrollbar-thumb:hover { background: #f97316; }
</style>
