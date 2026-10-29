<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quản trị') | Computer Store</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(Auth::check())
        <meta name="api-token" content="{{ Auth::user()->api_token }}">
    @endif
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <style>
        :root { --admin-blue: #2563eb; --admin-ink: #172033; --admin-muted: #718096; --admin-line: #e8edf5; --admin-bg: #f7f9fc; }
        * { box-sizing: border-box; }
        body { margin: 0; background: var(--admin-bg); color: var(--admin-ink); font-family: "DM Sans", sans-serif; font-size: .95rem; line-height: 1.55; -webkit-font-smoothing: antialiased; }
        .admin-content, .admin-content button, .admin-content input, .admin-content select, .admin-content textarea { font-family: inherit; }
        .admin-content h1, .admin-content h2, .admin-content h3, .admin-content h4, .admin-content h5, .admin-content h6 { color: var(--admin-ink); font-weight: 700; letter-spacing: -.025em; }
        .admin-content h1, .admin-content .h1 { font-size: 1.65rem; line-height: 1.25; }
        .admin-content h2, .admin-content .h2 { font-size: 1.35rem; line-height: 1.3; }
        .admin-content h3, .admin-content .h3 { font-size: 1.15rem; line-height: 1.35; }
        .admin-content h4, .admin-content .h4 { font-size: 1.05rem; line-height: 1.4; }
        .admin-content h5, .admin-content .h5 { font-size: .98rem; line-height: 1.45; }
        .admin-content h6, .admin-content .h6 { font-size: .9rem; line-height: 1.45; }
        .admin-content h1 i, .admin-content h2 i, .admin-content h3 i, .admin-content h4 i, .admin-content h5 i, .admin-content h6 i { display: none; }
        .admin-content .fs-5 { font-size: 1.05rem !important; }
        .admin-content .fs-6 { font-size: .9rem !important; }
        .admin-content small, .admin-content .small { font-size: .82rem; }
        .admin-shell { min-height: 100vh; display: flex; }
        .admin-sidebar { position: fixed; inset: 0 auto 0 0; z-index: 1030; width: 248px; display: flex; flex-direction: column; padding: 22px 14px; background: #fff; border-right: 1px solid var(--admin-line); }
        .brand { display: flex; align-items: center; gap: 10px; padding: 0 12px 26px; color: var(--admin-ink); text-decoration: none; font-weight: 700; letter-spacing: -.02em; }
        .brand-mark { width: 34px; height: 34px; display: grid; place-items: center; border-radius: 10px; color: #fff; background: linear-gradient(135deg, #2563eb, #60a5fa); box-shadow: 0 6px 14px #2563eb33; }
        .nav-label { margin: 18px 12px 8px; color: #a0aec0; font-size: .65rem; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
        .admin-nav { display: grid; gap: 4px; }
        .admin-nav a { display: flex; align-items: center; gap: 11px; padding: 10px 12px; border-radius: 9px; color: #718096; text-decoration: none; font-size: .88rem; font-weight: 500; transition: .2s ease; }
        .admin-nav a:hover, .admin-nav a.active { color: var(--admin-blue); background: #eff6ff; }
        .admin-nav a i { width: 18px; font-size: 1rem; text-align: center; }
        .sidebar-footer { margin-top: auto; padding-top: 16px; border-top: 1px solid var(--admin-line); }
        .sidebar-footer button { width: 100%; padding: 10px 12px; color: #718096; background: transparent; border: 0; text-align: left; }
        .admin-main { width: calc(100% - 248px); margin-left: 248px; }
        .admin-topbar { height: 70px; display: flex; align-items: center; justify-content: space-between; padding: 0 32px; background: #fff; border-bottom: 1px solid var(--admin-line); }
        .topbar-title { color: var(--admin-muted); font-size: .8rem; font-weight: 500; }
        .admin-user { display: flex; align-items: center; gap: 10px; }
        .user-avatar { width: 34px; height: 34px; display: grid; place-items: center; border-radius: 50%; color: #1d4ed8; background: #dbeafe; font-size: .75rem; font-weight: 700; }
        .admin-content { max-width: none; margin: 0; padding: 20px 40px 34px; }
        .admin-content > .container-fluid { margin-top: 0 !important; }
        .page-heading { display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; margin-bottom: 26px; }
        .page-heading h1 { margin: 0 0 6px; font-size: 1.65rem; font-weight: 700; letter-spacing: -.03em; }
        .page-heading p { margin: 0; color: var(--admin-muted); font-size: .88rem; }
        .card { border: 1px solid var(--admin-line) !important; border-radius: 14px !important; box-shadow: 0 5px 18px #17203308 !important; }
        .card-header { border-color: var(--admin-line); }
        .admin-filter-card { margin-bottom: 18px !important; border: 1px solid #e6ebf2 !important; border-radius: 12px !important; background: #fff; box-shadow: 0 4px 14px #17203308 !important; }
        .admin-filter-card .card-body { padding: 14px 16px; }
        .admin-filter-card .form-label { display: block; margin-bottom: 5px; color: #64748b; font-size: .72rem; font-weight: 700; }
        .admin-filter-card .form-control, .admin-filter-card .form-select { min-height: 38px; border-color: #dce4ef; border-radius: 7px; background: #fff; font-size: .82rem; }
        .admin-filter-card .btn { min-height: 38px; border-radius: 7px; font-size: .8rem; }
        .admin-filter-card .btn-light { border-color: #dce4ef; color: #536174; background: #fff; }
        .admin-filter-card .btn-light:hover { background: #f8fafc; border-color: #cbd5e1; }
        .admin-filter-card .input-group .form-control { border-radius: 7px 0 0 7px; }
        .admin-filter-card .input-group .btn { border-radius: 0 7px 7px 0; }
        .admin-notification { position: fixed; top: 18px; right: 18px; z-index: 2000; width: min(350px, calc(100vw - 32px)); overflow: hidden; background: #fff; border: 1px solid #dfe4eb; border-radius: 8px; box-shadow: 0 10px 28px #17203324; animation: admin-notification-in .22s ease-out; }
        .admin-notification__header { display: flex; align-items: center; gap: 8px; min-height: 38px; padding: 8px 12px; border-bottom: 1px solid #e5e7eb; }
        .admin-notification__icon { display: inline-grid; place-items: center; width: 16px; height: 16px; font-size: .82rem; }
        .admin-notification--success .admin-notification__icon { color: #2563eb; }
        .admin-notification--error .admin-notification__icon { color: #f59e0b; }
        .admin-notification__title { color: #4b5563; font-size: .82rem; font-weight: 700; }
        .admin-notification__time { margin-left: auto; color: #6b7280; font-size: .72rem; }
        .admin-notification__close { width: 20px; height: 20px; margin-left: 8px; padding: 0; border: 0; color: #6b7280; background: transparent; font-size: 1.35rem; font-weight: 300; line-height: 1; }
        .admin-notification__close:hover { color: #1f2937; }
        .admin-notification__body { padding: 14px 12px; color: #374151; font-size: .84rem; }
        @keyframes admin-notification-in { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 575.98px) { .admin-notification { top: 12px; right: 16px; } }
        .table { --bs-table-bg: transparent; --bs-table-hover-bg: #f8fbff; }
        .table > :not(caption) > * > * { padding: .9rem .85rem; border-bottom-color: var(--admin-line); font-size: .88rem; vertical-align: middle; }
        .table thead th { color: #8a96a8; font-size: .74rem; font-weight: 700; letter-spacing: .055em; text-transform: uppercase; white-space: nowrap; }
        .btn { border-radius: 8px; font-size: .88rem; font-weight: 600; }
        .btn-primary { background: var(--admin-blue); border-color: var(--admin-blue); box-shadow: 0 5px 12px #2563eb24; }
        .form-label { margin-bottom: .4rem; color: #536174; font-size: .84rem; font-weight: 600; }
        .form-control, .form-select { min-height: 42px; border-color: #dfe6f0; border-radius: 8px; font-size: .88rem; }
        .form-control:focus, .form-select:focus { border-color: #93c5fd; box-shadow: 0 0 0 .2rem #2563eb14; }
        .badge { padding: .42em .68em; font-weight: 600; border-radius: 6px; }
        #adminPageModal .modal-dialog { width: min(96vw, 1320px); max-width: 1320px; margin: 1rem auto; }
        #adminPageModal .modal-content { overflow: hidden; border-radius: 16px !important; }
        #adminPageModal .modal-header { padding: 18px 24px; background: #fff; }
        #adminPageModal .modal-title { margin: 0; color: var(--admin-ink); font-size: .98rem; font-weight: 700; }
        #adminPageModal .modal-body { overflow-x: hidden; background: #f8fafc; font-family: "DM Sans", sans-serif; }
        #adminPageModal .modal-body { max-height: calc(100vh - 100px); }
        #adminPageModal .modal-body button, #adminPageModal .modal-body input, #adminPageModal .modal-body select, #adminPageModal .modal-body textarea { font-family: inherit; }
        #adminPageModal .modal-body > .d-flex.justify-content-between,
        #adminPageModal .modal-body > .page-heading,
        #adminPageModal .modal-body > .container-fluid > .row:first-child { display: none !important; }
        #adminPageModal .modal-body > .card,
        #adminPageModal .modal-body > .container-fluid > .card,
        #adminPageModal .modal-body > .container-fluid > .row:not(:first-child) .card { border: 0 !important; border-radius: 0 !important; box-shadow: none !important; }
        #adminPageModal .modal-body > .card .card-body,
        #adminPageModal .modal-body > .container-fluid > .card .card-body { padding: 24px; }
        #adminPageModal .modal-body > .card-header { padding: 18px 24px; }
        #adminPageModal .modal-body .btn-outline-secondary { display: none; }
        #adminPageModal .modal-body .container-fluid { padding: 0; }
        #adminPageModal .modal-body .container-fluid > .d-flex { display: none !important; }
        #adminPageModal .modal-body .form-control, #adminPageModal .modal-body .form-select { background: #fff; }
        #adminPageModal .modal-body input[type="file"].form-control { min-height: 36px; padding: 0; border-radius: 0; }
        #adminPageModal .modal-body input[type="file"].form-control::file-selector-button { margin: 0 .5rem 0 0; padding: .25rem .5rem; border: 0; border-right: 1px solid #dee2e6; border-radius: 0; }
        #adminPageModal .modal-body input[type="file"].form-control:focus { border-color: #ced4da; box-shadow: none; outline: 0; }
        #adminPageModal .modal-body .border-bottom { border-color: var(--admin-line) !important; }
        #adminPageModal .modal-body .btn i { display: none; }
        #adminPageModal .product-media-help { margin: 6px 0 0; color: #718096; font-size: .72rem; line-height: 1.4; }
        #adminPageModal .product-thumbnail-preview { position: relative; display: grid; width: 100px; height: 100px; margin-top: 10px; place-items: center; overflow: visible; border: 1px solid #dce5ef; border-radius: 7px; background: #f8fafc; }
        #adminPageModal .product-thumbnail-preview img { width: 100%; height: 100%; border-radius: 5px; object-fit: cover; }
        #adminPageModal .product-image-placeholder { position: absolute; inset: 0; display: grid; place-items: center; padding: 10px; color: #8995a5; background: #f8fafc; font-size: .72rem; text-align: center; }
        #adminPageModal .product-image-placeholder[hidden] { display: none; }
        #adminPageModal .product-gallery-section { margin-top: 10px; padding-top: 10px; border-top: 1px solid #edf1f6; }
        #adminPageModal .product-gallery-section__header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; color: #4a5b70; font-size: .75rem; font-weight: 700; }
        #adminPageModal .product-gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, 100px); gap: 10px; }
        #adminPageModal .product-gallery-item { position: relative; width: 100px; height: 100px; overflow: visible; border: 1px solid #dce5ef; border-radius: 8px; background: #f8fafc; transition: border-color .15s ease, opacity .15s ease; }
        #adminPageModal .product-gallery-item.is-current { overflow: hidden; }
        #adminPageModal .product-gallery-item img { width: 100%; height: 100%; border-radius: 5px; object-fit: cover; }
        #adminPageModal .product-gallery-item.is-new { border: 1px solid #dce5ef; }
        #adminPageModal .product-replace-input { position: absolute; width: 1px; height: 1px; overflow: hidden; opacity: 0; }
        #adminPageModal .product-image-replace { position: absolute; inset: 0; z-index: 2; display: grid; place-items: center; margin: 0; border-radius: 5px; color: #fff; background: #17203355; font-size: .68rem; font-weight: 700; text-align: center; cursor: pointer; opacity: 0; transition: opacity .15s ease; }
        #adminPageModal .product-gallery-item:hover .product-image-replace,
        #adminPageModal .product-gallery-item:focus-within .product-image-replace { opacity: 1; }
        #adminPageModal .product-gallery-item.is-marked-for-deletion { border-color: #c88080; opacity: .48; }
        #adminPageModal .product-gallery-item.is-marked-for-deletion img { filter: grayscale(1); }
        #adminPageModal .product-image-remove { position: absolute; top: 4px; right: 4px; display: grid; width: 25px; height: 25px; place-items: center; padding: 0; border: 1px solid #eab308; border-radius: 50%; color: #422006; background: #facc15; font-size: .75rem; font-weight: 700; }
        #adminPageModal .product-image-remove { z-index: 5; cursor: pointer; }
        #adminPageModal .product-image-remove i, #adminPageModal #resetThumbnail i { display: none !important; }
        #adminPageModal .product-image-remove:hover { border-color: #ca8a04; background: #eab308; }
        #adminPageModal .product-gallery-item__status { position: absolute; right: 0; bottom: 0; left: 0; z-index: 3; padding: 3px; color: #893e3e; background: #f7eeee; font-size: .62rem; font-weight: 700; text-align: center; opacity: 0; visibility: hidden; transition: .15s ease; }
        #adminPageModal .product-gallery-item.is-marked-for-deletion .product-gallery-item__status { opacity: 1; visibility: visible; }
        @media (hover: none) { #adminPageModal .product-gallery-item .product-image-replace { opacity: 1; background: #17203355; } }
        #adminPageModal .product-gallery-empty { grid-column: 1 / -1; margin: 0; padding: 10px; border: 1px dashed #d7e0ea; border-radius: 8px; color: #8a97a8; font-size: .74rem; text-align: center; }
        #adminPageModal #productForm { display: grid; grid-template-columns: minmax(0, 3fr) minmax(0, 2fr); gap: 0 22px; }
        #adminPageModal #productForm > .product-edit-section--basic { grid-column: 1 / -1; }
        #adminPageModal #productForm > .product-edit-section--pricing { grid-column: 1; }
        #adminPageModal #productForm > .product-edit-section--warranty { grid-column: 2; }
        #adminPageModal #productForm > .product-edit-section--classification { grid-column: 1 / -1; }
        #adminPageModal #productForm > .product-edit-section--status { grid-column: 1; align-self: center; }
        #adminPageModal #productForm > .product-edit-actions { grid-column: 2; align-self: end; justify-content: flex-end; margin-bottom: 24px !important; }
        #adminPageModal #productForm > .product-edit-section--basic textarea { min-height: 70px; }
        #adminPageModal #productForm > .product-edit-section--pricing .mb-3 { margin-bottom: 12px !important; }
        #adminPageModal #productForm > .product-edit-section--classification > .row { margin-bottom: 12px; }
        #adminPageModal #productForm > .product-edit-section--classification .product-media-grid { margin-bottom: 0 !important; }
        #adminPageModal #productForm > .product-edit-section--status .form-check { display: inline-flex; margin: 4px 22px 4px 0; }
        #adminPageModal #productForm .product-edit-actions .btn { min-width: 150px; }
        #deleteModal .modal-dialog { width: min(92vw, 720px); max-width: 720px; }
        #deleteModal .modal-content { border: 1px solid #e5eaf1; border-radius: 14px; box-shadow: 0 18px 48px #17203324; }
        #deleteModal .modal-header { padding: 20px 24px; border-bottom-color: #edf1f6; }
        #deleteModal .modal-title { color: var(--admin-ink); font-size: 1rem; font-weight: 700; }
        #deleteModal .modal-body { display: grid; grid-template-columns: 1.2fr .8fr; align-items: center; gap: 22px; padding: 24px; color: #64748b; }
        #deleteModal .delete-product-name { display: block; margin-top: 8px; padding: 12px 14px; border: 1px solid #e4eaf2; border-radius: 8px; color: #1e293b; background: #f8fafc; font-weight: 700; }
        #deleteModal .delete-product-warning { padding: 14px; border: 1px solid #f1d5d5; border-radius: 8px; color: #8b3a3a; background: #fff8f8; font-size: .84rem; }
        #deleteModal .modal-footer { gap: 8px; padding: 14px 24px 20px; border-top: 0; }
        @media (max-width: 767.98px) { #adminPageModal #productForm { display: block; } #adminPageModal #productForm > .product-edit-actions { justify-content: flex-start; margin-bottom: 20px !important; } }
        @media (max-width: 575.98px) { #adminPageModal .product-gallery-grid { grid-template-columns: repeat(auto-fill, minmax(76px, 100px)); } }
        @media (max-width: 575.98px) { #deleteModal .modal-body { grid-template-columns: 1fr; gap: 12px; } }
        #adminPageModal .admin-user-form,
        #adminPageModal .admin-user-detail { margin-top: 0 !important; padding: 24px; }
        #adminPageModal .admin-user-form > .row,
        #adminPageModal .admin-user-detail > .row { --bs-gutter-x: 20px; --bs-gutter-y: 20px; }
        #adminPageModal .admin-user-form .card,
        #adminPageModal .admin-user-detail .card { height: auto; margin-bottom: 0 !important; background: #fff; }
        #adminPageModal .admin-user-form .card-body,
        #adminPageModal .admin-user-detail .card-body { padding: 22px; }
        #adminPageModal .admin-user-form .col-lg-8,
        #adminPageModal .admin-user-detail .col-lg-8 { flex: 0 0 64%; max-width: 64%; }
        #adminPageModal .admin-user-form .col-lg-4,
        #adminPageModal .admin-user-detail .col-lg-4 { flex: 0 0 36%; max-width: 36%; }
        #adminPageModal .admin-user-form h5,
        #adminPageModal .admin-user-detail h5 { display: flex; align-items: center; gap: 8px; margin-bottom: 18px !important; padding-bottom: 10px !important; color: var(--admin-ink); font-size: 1rem; }
        #adminPageModal .admin-user-form h5 i,
        #adminPageModal .admin-user-detail h5 i { display: inline-block; color: var(--admin-blue); font-size: .95rem; }
        #adminPageModal .admin-user-form .mb-4 { margin-bottom: 24px !important; }
        #adminPageModal .admin-user-form .row.mb-3 { row-gap: 14px; }
        #adminPageModal .admin-user-form .form-label { margin-bottom: 7px; font-size: .78rem; }
        #adminPageModal .admin-user-form .form-control,
        #adminPageModal .admin-user-form .form-select { min-height: 40px; }
        #adminPageModal .admin-user-form textarea.form-control { min-height: 92px; resize: vertical; }
        #adminPageModal .admin-user-form .d-flex.gap-2 { padding-top: 2px; }
        #adminPageModal .admin-user-form .col-lg-4 .card,
        #adminPageModal .admin-user-detail .col-lg-4 .card { border-radius: 12px !important; }
        #adminPageModal .admin-user-form .col-lg-4 .card-body,
        #adminPageModal .admin-user-detail .col-lg-4 .card-body { padding: 20px; }
        #adminPageModal .admin-user-form .avatar-circle,
        #adminPageModal .admin-user-detail .avatar-circle { width: 68px !important; height: 68px !important; font-size: 26px !important; }
        #adminPageModal .admin-user-form .col-lg-4 .card:first-child .card-body { display: flex; flex-direction: column; align-items: center; text-align: center; }
        #adminPageModal .admin-user-form .col-lg-4 .card:first-child h5,
        #adminPageModal .admin-user-form .col-lg-4 .card:first-child p { width: 100%; text-align: center; overflow-wrap: anywhere; }
        #adminPageModal .admin-user-form .col-lg-4 .card:first-child h5 { justify-content: center; }
        #adminPageModal .admin-user-detail .col-lg-4 .card:first-child .card-body { display: flex; flex-direction: column; align-items: center; text-align: center; }
        #adminPageModal .admin-user-detail .col-lg-4 .card:first-child h5,
        #adminPageModal .admin-user-detail .col-lg-4 .card:first-child p { width: 100%; text-align: center; overflow-wrap: anywhere; }
        #adminPageModal .admin-user-detail .col-lg-4 .card:first-child h5 { justify-content: center; }
        #adminPageModal .admin-user-form .card-header,
        #adminPageModal .admin-user-detail .card-header { padding: 14px 18px; background: #fff !important; }
        #adminPageModal .admin-user-form .card-header h6 { color: var(--admin-ink); font-size: .9rem; }
        #adminPageModal .admin-user-form ul { color: var(--admin-muted); line-height: 1.55; }
        #adminPageModal .admin-user-detail .form-label { font-size: .78rem; line-height: 1.4; }
        #adminPageModal .admin-user-detail p, #adminPageModal .admin-user-detail .small { font-size: .84rem; line-height: 1.5; }
        #adminPageModal .admin-user-detail .fs-5 { font-size: .95rem !important; overflow-wrap: anywhere; }
        #adminPageModal .admin-user-detail > .row > .col-lg-8 > .card { margin-bottom: 14px !important; }
        #adminPageModal .admin-user-detail > .row > .col-lg-8 > .card:first-child .card-body { padding: 18px 22px 14px; }
        #adminPageModal .admin-user-detail > .row > .col-lg-8 > .card:first-child .row { margin-bottom: 14px !important; }
        #adminPageModal .admin-user-detail > .row > .col-lg-8 > .card:nth-child(2) .card-body { padding: 16px 22px; }
        #adminPageModal .admin-user-detail > .row > .col-lg-8 > .card:nth-child(3) .card-body { padding: 16px 22px; }
        #adminPageModal .admin-user-detail .account-status-actions { justify-content: flex-start; }
        #adminPageModal .admin-user-detail .account-status-actions .badge { margin-left: 0; }
        #adminPageModal .admin-user-detail .user-status-badge { min-width: 88px; min-height: 30px; padding: .42rem .68rem; display: inline-flex; align-items: center; justify-content: center; font-size: .78rem; line-height: 1; }
        #adminPageModal .admin-user-detail > .row > .col-lg-4 { padding-left: 10px; padding-right: 10px; }
        #adminPageModal .admin-user-detail > .row > .col-lg-4 > .card { margin-bottom: 10px !important; }
        #adminPageModal .admin-user-detail .col-lg-4 .card:first-child .card-body { padding: 16px 18px 14px; }
        #adminPageModal .admin-user-detail .col-lg-4 .card:first-child .mb-3 { margin-bottom: 10px !important; }
        #adminPageModal .admin-user-detail .col-lg-4 .card:first-child h5 { margin-bottom: 4px !important; }
        #adminPageModal .admin-user-detail .col-lg-4 .card:first-child p { margin-bottom: 0 !important; }
        #adminPageModal .admin-user-detail .account-sidebar-status { margin-top: 10px; padding-top: 10px; }
        #adminPageModal .admin-user-detail .col-lg-4 .card:nth-child(2) .card-body { padding: 14px 18px; }
        #adminPageModal .admin-user-detail .col-lg-4 .card:nth-child(3) .card-body { padding: 14px 18px; }
        #adminPageModal .admin-user-detail .card-header h5 { margin: 0 !important; padding: 0 !important; }
        #adminPageModal .admin-user-detail .card-header h5 i { display: inline-block; }
        #adminPageModal .admin-user-detail .card-body { min-height: 0; }
        #adminPageModal .admin-user-detail .account-sidebar-status { width: 100%; margin-top: 14px; padding-top: 12px; border-top: 1px solid var(--admin-line); text-align: center; }
        #adminPageModal .admin-user-detail .account-sidebar-status .account-lock-form { display: flex; justify-content: center; margin-top: 8px; }
        #adminPageModal .account-status-actions { display: flex; align-items: center; justify-content: center; gap: 8px; flex-wrap: wrap; }
        #adminPageModal .account-status-actions .badge,
        #adminPageModal .account-lock-button,
        #adminPageModal .account-lock-form .btn { min-height: 30px; display: inline-flex; align-items: center; justify-content: center; }
        #adminPageModal .account-status-actions .badge { padding: .42rem .68rem; }
        #adminPageModal .account-lock-form { margin: 0; }
        #adminPageModal .account-lock-form .btn,
        #adminPageModal .account-lock-button { min-width: 124px; padding: .38rem .7rem; font-size: .76rem; }
        #adminPageModal .admin-user-detail .account-lock-form { margin-top: 6px; }
        #adminPageModal .admin-user-form .d-flex.gap-2 > .btn { min-width: 66px; min-height: 38px; }
        #adminPageModal .admin-user-detail > .row { align-items: flex-start; }
        #adminPageModal .admin-user-detail .col-lg-4 > .card { box-shadow: none !important; }
        #adminPageModal .admin-user-detail .col-lg-4 > .card:first-child { border-bottom: 1px solid var(--admin-line) !important; border-radius: 0 !important; }
        @media (max-width: 767.98px) {
            #adminPageModal .modal-dialog { width: calc(100% - 1rem); margin: .5rem auto; }
            #adminPageModal .admin-user-form,
            #adminPageModal .admin-user-detail { padding: 16px; }
            #adminPageModal .admin-user-form .col-lg-8,
            #adminPageModal .admin-user-form .col-lg-4,
            #adminPageModal .admin-user-detail .col-lg-8,
            #adminPageModal .admin-user-detail .col-lg-4 { flex: 0 0 100%; max-width: 100%; }
        }
        .stat-card { position: relative; overflow: hidden; min-height: 132px; }
        .stat-card::after { content: ""; position: absolute; right: -25px; bottom: -34px; width: 110px; height: 110px; border-radius: 50%; background: #eff6ff; }
        .stat-icon { position: relative; z-index: 1; width: 36px; height: 36px; display: grid; place-items: center; border-radius: 10px; color: #2563eb; background: #eff6ff; }
        .stat-card h2 { position: relative; z-index: 1; margin: 14px 0 2px; font-size: 1.35rem; }
        .stat-card p { position: relative; z-index: 1; margin: 0; color: var(--admin-muted); font-size: .76rem; }
        .mobile-menu { display: none; }
        @media (max-width: 991.98px) {
            .admin-sidebar { transform: translateX(-100%); transition: transform .2s ease; }
            .admin-sidebar.open { transform: translateX(0); }
            .admin-main { width: 100%; margin-left: 0; }
            .admin-topbar { padding: 0 18px; }
            .mobile-menu { display: inline-flex; margin-right: 12px; }
            .admin-content { padding: 26px 20px; }
        }
        @media (max-width: 575.98px) {
            .admin-content { padding: 22px 14px; }
            .page-heading { display: block; }
            .page-heading .btn { margin-top: 14px; }
        }
    </style>
    @stack('styles')
</head>

<body>
    <div class="admin-shell">
        <aside class="admin-sidebar" id="adminSidebar">
            <a class="brand" href="{{ route('admin.dashboard') }}">
                <span class="brand-mark"><i class="bi bi-grid-1x2-fill"></i></span>
                <span>Computer Store</span>
            </a>
            <div class="nav-label">Tổng quan</div>
            <nav class="admin-nav">
                <a class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-grid"></i> Dashboard</a>
            </nav>
            <div class="nav-label">Quản lý cửa hàng</div>
            <nav class="admin-nav">
                <a class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}"><i class="bi bi-bag"></i> Đơn hàng</a>
                <a class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}"><i class="bi bi-box-seam"></i> Sản phẩm</a>
                <a class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}"><i class="bi bi-tags"></i> Danh mục</a>
                <a class="{{ request()->routeIs('admin.brands.*') ? 'active' : '' }}" href="{{ route('admin.brands.index') }}"><i class="bi bi-award"></i> Thương hiệu</a>
            </nav>
            <div class="nav-label">Hệ thống</div>
            <nav class="admin-nav">
                <a class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}"><i class="bi bi-people"></i> Người dùng</a>
                <a class="{{ request()->routeIs('admin.audit-logs.*') ? 'active' : '' }}" href="{{ route('admin.audit-logs.index') }}"><i class="bi bi-clock-history"></i> Nhật ký hoạt động</a>
            </nav>
            <div class="sidebar-footer">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit"><i class="bi bi-box-arrow-left me-2"></i> Đăng xuất</button>
                </form>
            </div>
        </aside>

        <section class="admin-main">
            <header class="admin-topbar">
                <div class="d-flex align-items-center">
                    <button class="btn btn-light mobile-menu" type="button" id="sidebarToggle" aria-label="Mở menu"><i class="bi bi-list fs-5"></i></button>
                    <span class="topbar-title">@yield('title', 'Dashboard')</span>
                </div>
                <div class="admin-user">
                    <div class="text-end d-none d-sm-block">
                        <div class="small fw-semibold">{{ Auth::user()->name ?? 'Quản trị viên' }}</div>
                        <div class="small text-muted">Administrator</div>
                    </div>
                    <span class="user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</span>
                </div>
            </header>
            <main class="admin-content">
                @if(session('success'))
                    <div class="admin-notification admin-notification--success" role="status" data-admin-notification>
                        <div class="admin-notification__header">
                            <i class="bi bi-info-circle-fill admin-notification__icon" aria-hidden="true"></i>
                            <span class="admin-notification__title">Thông báo</span>
                            <span class="admin-notification__time">vừa xong</span>
                            <button type="button" class="admin-notification__close" data-admin-notification-close aria-label="Đóng">&times;</button>
                        </div>
                        <div class="admin-notification__body">{{ session('success') }}</div>
                    </div>
                @elseif(session('error'))
                    <div class="admin-notification admin-notification--error" role="alert" data-admin-notification>
                        <div class="admin-notification__header">
                            <i class="bi bi-exclamation-triangle-fill admin-notification__icon" aria-hidden="true"></i>
                            <span class="admin-notification__title">Cảnh báo</span>
                            <span class="admin-notification__time">vừa xong</span>
                            <button type="button" class="admin-notification__close" data-admin-notification-close aria-label="Đóng">&times;</button>
                        </div>
                        <div class="admin-notification__body">{{ session('error') }}</div>
                    </div>
                @endif
                @yield('content')
            </main>
        </section>
    </div>
    <div class="modal fade" id="adminPageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="adminPageModalTitle">Chi tiết</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body p-0" id="adminPageModalBody">
                    <div class="d-flex justify-content-center align-items-center py-5 text-muted">
                        <div class="spinner-border spinner-border-sm me-2" role="status"></div> Đang tải...
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', () => document.getElementById('adminSidebar').classList.toggle('open'));
        window.previewProductThumbnail = (input) => {
            const file = input.files?.[0];
            const form = input.form;
            const preview = form?.querySelector('#thumbnailPreview');
            if (!file || !preview) return;
            const deleteFlag = form.querySelector('#deleteThumbnailFlag');
            if (deleteFlag) deleteFlag.value = '0';
            input.required = false;
            form.querySelector('#currentThumbnailCard')?.classList.remove('is-marked-for-deletion');

            const reader = new FileReader();
            reader.onload = (event) => {
                preview.querySelector('.product-gallery-item.is-new')?.remove();
                const card = document.createElement('article');
                card.className = 'product-gallery-item is-new';
                const image = document.createElement('img');
                image.src = event.target.result;
                image.alt = 'Ảnh đại diện';
                const replace = document.createElement('label');
                replace.className = 'product-image-replace';
                replace.htmlFor = input.id;
                replace.textContent = 'Thay ảnh';
                const remove = document.createElement('button');
                remove.type = 'button';
                remove.className = 'product-image-remove';
                remove.textContent = '×';
                remove.title = 'Xoá ảnh này';
                remove.setAttribute('aria-label', 'Xoá ảnh này');
                remove.addEventListener('click', () => window.resetProductThumbnail(remove));
                card.append(image, replace, remove);
                preview.appendChild(card);
                preview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        };
        window.resetProductThumbnail = (button) => {
            const form = button.closest('form');
            const input = form?.querySelector('#thumbnail');
            const preview = form?.querySelector('#thumbnailPreview');
            if (!form || !input || !preview) return;

            input.value = '';
            preview.querySelector('.product-gallery-item.is-new')?.remove();
            input.required = form.querySelector('#deleteThumbnailFlag')?.value === '1';
            if (!preview.querySelector('.product-gallery-item')) preview.classList.add('d-none');
        };
        window.previewExistingGalleryImage = (input) => {
            const file = input.files?.[0];
            const card = input.closest('.product-gallery-item');
            const container = card?.parentElement;
            if (!file || !card || !container) return;

            const reader = new FileReader();
            reader.onload = (event) => {
                container.querySelector(`[data-replacement-preview="${CSS.escape(input.id)}"]`)?.remove();
                const previewCard = document.createElement('article');
                previewCard.className = 'product-gallery-item is-new is-replacement-preview';
                previewCard.dataset.replacementPreview = input.id;
                const image = document.createElement('img');
                image.src = event.target.result;
                image.alt = 'Ảnh chi tiết';
                const replaceLabel = document.createElement('label');
                replaceLabel.className = 'product-image-replace';
                replaceLabel.htmlFor = input.id;
                replaceLabel.textContent = 'Thay ảnh';
                const remove = document.createElement('button');
                remove.type = 'button';
                remove.className = 'product-image-remove';
                remove.textContent = '×';
                remove.title = 'Bỏ ảnh mới';
                remove.setAttribute('aria-label', 'Bỏ ảnh mới');
                remove.addEventListener('click', () => {
                    input.value = '';
                    card.classList.remove('is-replaced');
                    previewCard.remove();
                });
                previewCard.append(image, replaceLabel, remove);
                card.classList.add('is-replaced');
                card.after(previewCard);
            };
            reader.readAsDataURL(file);
        };
        window.previewProductGallery = (input) => {
            input._productImageFiles = Array.from(input.files || []);
            const form = input.form;
            const container = form?.querySelector('#imagesPreview');
            const counter = form?.querySelector('#newImagesCount');
            if (!container) return;

            const render = () => {
                const files = input._productImageFiles || [];
                container.querySelectorAll('.product-gallery-item.is-added-image').forEach((card) => card.remove());
                if (counter) counter.textContent = files.length;

                files.forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        if (input._productImageFiles?.[index] !== file) return;
                        const card = document.createElement('article');
                        card.className = 'product-gallery-item is-new is-added-image';
                        card.dataset.newIndex = index;
                        const image = document.createElement('img');
                        image.src = event.target.result;
                        image.alt = `Ảnh chi tiết ${index + 1}`;
                        const replaceInput = document.createElement('input');
                        replaceInput.type = 'file';
                        replaceInput.id = `replaceNewImage-${Date.now()}-${index}`;
                        replaceInput.className = 'product-replace-input';
                        replaceInput.accept = 'image/png,image/jpeg,image/webp';
                        replaceInput.onchange = () => window.replaceNewProductGalleryImage(replaceInput);
                        const replace = document.createElement('label');
                        replace.className = 'product-image-replace';
                        replace.htmlFor = replaceInput.id;
                        replace.textContent = 'Thay ảnh';
                        const remove = document.createElement('button');
                        remove.type = 'button';
                        remove.className = 'product-image-remove';
                        remove.textContent = '×';
                        remove.title = 'Bỏ ảnh này';
                        remove.setAttribute('aria-label', 'Bỏ ảnh này');
                        remove.addEventListener('click', () => {
                            input._productImageFiles.splice(index, 1);
                            const transfer = new DataTransfer();
                            input._productImageFiles.forEach((selectedFile) => transfer.items.add(selectedFile));
                            input.files = transfer.files;
                            render();
                        });
                        card.append(image, replaceInput, replace, remove);
                        container.appendChild(card);
                    };
                    reader.readAsDataURL(file);
                });
            };

            render();
        };
        window.replaceNewProductGalleryImage = (replaceInput) => {
            const file = replaceInput.files?.[0];
            const form = replaceInput.closest('form');
            const imagesInput = form?.querySelector('#images');
            const card = replaceInput.closest('.product-gallery-item');
            const index = Number(card?.dataset.newIndex);
            if (!file || !imagesInput || !Number.isInteger(index)) return;

            imagesInput._productImageFiles[index] = file;
            const transfer = new DataTransfer();
            imagesInput._productImageFiles.forEach((selectedFile) => transfer.items.add(selectedFile));
            imagesInput.files = transfer.files;
            window.previewProductGallery(imagesInput);
        };
        window.adminNotify = (message, type = 'success') => {
            const notification = document.createElement('div');
            const isError = type === 'error';
            notification.className = `admin-notification admin-notification--${isError ? 'error' : 'success'}`;
            notification.setAttribute('role', isError ? 'alert' : 'status');
            notification.setAttribute('data-admin-notification', '');
            notification.innerHTML = `
                <div class="admin-notification__header">
                    <i class="bi ${isError ? 'bi-exclamation-triangle-fill' : 'bi-info-circle-fill'} admin-notification__icon" aria-hidden="true"></i>
                    <span class="admin-notification__title">${isError ? 'Cảnh báo' : 'Thông báo'}</span>
                    <span class="admin-notification__time">vừa xong</span>
                    <button type="button" class="admin-notification__close" data-admin-notification-close aria-label="Đóng">&times;</button>
                </div>
                <div class="admin-notification__body"></div>
            `;
            notification.querySelector('.admin-notification__body').textContent = message;
            document.body.appendChild(notification);
            const close = () => notification.remove();
            notification.querySelector('[data-admin-notification-close]').addEventListener('click', close);
            window.setTimeout(close, 5000);
        };
        const pendingNotification = sessionStorage.getItem('admin-notification');
        if (pendingNotification) {
            sessionStorage.removeItem('admin-notification');
            try {
                const notification = JSON.parse(pendingNotification);
                window.adminNotify(notification.message, notification.type);
            } catch (error) {
                sessionStorage.removeItem('admin-notification');
            }
        }
        document.querySelectorAll('[data-admin-notification]').forEach((notification) => {
            const close = () => notification.remove();
            notification.querySelector('[data-admin-notification-close]')?.addEventListener('click', close);
            window.setTimeout(close, 5000);
        });

        const adminPageModal = document.getElementById('adminPageModal');
        const adminPageModalBody = document.getElementById('adminPageModalBody');
        const adminPageModalTitle = document.getElementById('adminPageModalTitle');
        const modalInstance = adminPageModal ? new bootstrap.Modal(adminPageModal) : null;

        document.addEventListener('submit', async (event) => {
            const form = event.target.closest('form[data-admin-product-form]');
            if (!form) return;

            event.preventDefault();
            if (!form.reportValidity()) return;

            const submitButton = form.querySelector('[type="submit"]');
            if (submitButton?.disabled) return;
            if (submitButton) submitButton.disabled = true;

            try {
                const formData = new FormData(form);
                if (form.dataset.apiMethod) formData.set('_method', form.dataset.apiMethod);

                const response = await fetch(form.dataset.apiUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const data = await response.json().catch(() => ({}));

                if (response.ok && data.success) {
                    sessionStorage.setItem('admin-notification', JSON.stringify({
                        message: data.message || 'Sản phẩm đã được lưu thành công.',
                        type: 'success'
                    }));
                    window.location.reload();
                    return;
                }

                const errors = data.errors || data.data?.errors;
                const errorMessage = errors
                    ? (typeof errors === 'string' ? errors : Object.values(errors).flat().join(' | '))
                    : (data.message || 'Không thể lưu sản phẩm. Vui lòng thử lại.');
                if (typeof window.adminNotify === 'function') {
                    window.adminNotify(errorMessage, 'error');
                } else {
                    alert(errorMessage);
                }
            } catch (error) {
                console.error('Error saving product:', error);
                const errorMessage = 'Có lỗi kết nối khi lưu sản phẩm. Vui lòng thử lại.';
                if (typeof window.adminNotify === 'function') {
                    window.adminNotify(errorMessage, 'error');
                } else {
                    alert(errorMessage);
                }
            } finally {
                if (submitButton?.isConnected) submitButton.disabled = false;
            }
        });

        document.addEventListener('click', async (event) => {
            const link = event.target.closest('a[data-admin-modal]');
            if (!link || !modalInstance) return;

            event.preventDefault();
            adminPageModalTitle.textContent = link.dataset.modalTitle || 'Chi tiết';
            adminPageModalBody.innerHTML = '<div class="d-flex justify-content-center align-items-center py-5 text-muted"><div class="spinner-border spinner-border-sm me-2" role="status"></div> Đang tải...</div>';
            modalInstance.show();

            try {
                const response = await fetch(link.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!response.ok) throw new Error('Không thể tải nội dung');
                const html = await response.text();
                const documentFragment = new DOMParser().parseFromString(html, 'text/html');
                const content = documentFragment.querySelector('.admin-content');
                if (!content) throw new Error('Nội dung modal không hợp lệ');
                adminPageModalBody.innerHTML = content.innerHTML;
                adminPageModalBody.querySelectorAll('.page-heading, .admin-content > .d-flex.justify-content-between, .admin-content > .container-fluid > .row:first-child').forEach((element) => element.remove());
                adminPageModalBody.querySelectorAll('a[href*="/admin/"][class*="btn-outline-secondary"]').forEach((element) => element.remove());
                adminPageModalBody.querySelectorAll('script').forEach((script) => {
                    const executable = document.createElement('script');
                    executable.textContent = script.textContent;
                    document.body.appendChild(executable);
                    script.remove();
                });
            } catch (error) {
                adminPageModalBody.innerHTML = '<div class="alert alert-danger m-4">Không thể tải nội dung. Vui lòng thử lại.</div>';
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
