<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation</title>
    <style>
        :root {
            /* Graphite Color Palette */
            --graphite-50: #f8f9fa;
            --graphite-100: #f1f3f4;
            --graphite-200: #e8eaed;
            --graphite-300: #dadce0;
            --graphite-400: #bdc1c6;
            --graphite-500: #9aa0a6;
            --graphite-600: #80868b;
            --graphite-700: #5f6368;
            --graphite-800: #3c4043;
            --graphite-900: #202124;
            
            /* Semantic Colors */
            --primary: var(--graphite-700);
            --primary-light: var(--graphite-500);
            --primary-lighter: var(--graphite-200);
            --surface: #ffffff;
            --surface-variant: var(--graphite-50);
            --surface-container: var(--graphite-100);
            --on-surface: var(--graphite-900);
            --on-surface-variant: var(--graphite-700);
            --outline: var(--graphite-300);
            --outline-variant: var(--graphite-200);
            
            /* Method Colors */
            --method-get: #1a73e8;
            --method-post: #137333;
            --method-put: #ea8600;
            --method-patch: #ea8600;
            --method-delete: #d93025;
            
            /* Status Colors */
            --success: #137333;
            --warning: #ea8600;
            --error: #d93025;
            --info: #1a73e8;
            
            /* Typography */
            --font-family: 'Google Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --font-mono: 'Google Sans Mono', 'Roboto Mono', Consolas, 'Liberation Mono', monospace;
            
            /* Spacing */
            --space-xs: 4px;
            --space-sm: 8px;
            --space-md: 16px;
            --space-lg: 24px;
            --space-xl: 32px;
            --space-2xl: 48px;
            
            /* Border Radius */
            --radius-sm: 4px;
            --radius-md: 8px;
            --radius-lg: 12px;
            
            /* Shadows */
            --shadow-sm: 0 1px 2px 0 rgba(60, 64, 67, 0.3), 0 1px 3px 1px rgba(60, 64, 67, 0.15);
            --shadow-md: 0 1px 2px 0 rgba(60, 64, 67, 0.3), 0 2px 6px 2px rgba(60, 64, 67, 0.15);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-family);
            font-size: 14px;
            line-height: 1.5;
            color: var(--on-surface);
            background-color: var(--surface-variant);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .container {
            max-width: 1440px;
            margin: 0 auto;
            padding: var(--space-lg);
        }

        .main-card {
            background-color: var(--surface);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            overflow: hidden;
        }

        /* Header */
        .header {
            padding: var(--space-2xl) var(--space-xl) var(--space-xl);
            border-bottom: 1px solid var(--outline-variant);
            background-color: var(--surface);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: var(--space-xl);
            flex-wrap: wrap;
        }

        .header-text {
            flex: 1;
            min-width: 300px;
        }

        .header-title {
            font-size: 28px;
            font-weight: 500;
            color: var(--on-surface);
            margin-bottom: var(--space-sm);
            letter-spacing: -0.5px;
        }

        .header-subtitle {
            font-size: 16px;
            color: var(--on-surface-variant);
            margin-bottom: var(--space-lg);
            font-weight: 400;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: var(--space-md);
            margin-top: var(--space-lg);
        }

        .stat-card {
            background-color: var(--surface-container);
            border-radius: var(--radius-md);
            padding: var(--space-md) var(--space-lg);
            text-align: center;
            border: 1px solid var(--outline-variant);
        }

        .stat-value {
            font-size: 24px;
            font-weight: 500;
            color: var(--primary);
            margin-bottom: var(--space-xs);
            font-feature-settings: 'tnum';
        }

        .stat-label {
            font-size: 12px;
            color: var(--on-surface-variant);
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Filters */
        .filters-section {
            padding: var(--space-xl);
            background-color: var(--surface-container);
            border-bottom: 1px solid var(--outline-variant);
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: var(--space-lg);
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: var(--space-sm);
        }

        .filter-label {
            font-size: 12px;
            font-weight: 500;
            color: var(--on-surface);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .filter-input,
        .filter-select {
            padding: 12px 16px;
            border: 1px solid var(--outline);
            border-radius: var(--radius-md);
            font-size: 14px;
            font-family: var(--font-family);
            background-color: var(--surface);
            color: var(--on-surface);
            outline: none;
        }

        .filter-input:focus,
        .filter-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(95, 99, 104, 0.2);
        }

        .reset-button {
            padding: 12px 20px;
            background-color: var(--surface);
            border: 1px solid var(--outline);
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 500;
            color: var(--on-surface);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            justify-self: start;
        }

        .reset-button:active {
            background-color: var(--surface-container);
        }

        /* Content */
        .content {
            padding: var(--space-xl);
        }

        .no-results {
            text-align: center;
            padding: var(--space-2xl);
            color: var(--on-surface-variant);
            background-color: var(--surface-container);
            border-radius: var(--radius-lg);
            margin-bottom: var(--space-xl);
        }

        .no-results-icon {
            font-size: 48px;
            margin-bottom: var(--space-md);
            opacity: 0.6;
        }

        .no-results-text {
            font-size: 16px;
        }

        /* Route Groups */
        .route-group {
            margin-bottom: var(--space-xl);
            border: 1px solid var(--outline-variant);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .group-header {
            background-color: var(--surface-container);
            padding: var(--space-lg) var(--space-xl);
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--outline-variant);
        }

        .group-title {
            font-size: 16px;
            font-weight: 500;
            color: var(--on-surface);
        }

        .group-count {
            background-color: var(--primary);
            color: var(--surface);
            padding: var(--space-xs) var(--space-md);
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            min-width: 24px;
            text-align: center;
        }

        /* Table */
        .table-container {
            overflow-x: auto;
        }

        .routes-table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-header {
            background-color: var(--surface-variant);
            border-bottom: 1px solid var(--outline-variant);
        }

        .table-header th {
            padding: var(--space-lg) var(--space-xl);
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            color: var(--on-surface-variant);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-row {
            border-bottom: 1px solid var(--outline-variant);
        }

        .table-row:last-child {
            border-bottom: none;
        }

        .table-cell {
            padding: var(--space-lg) var(--space-xl);
            vertical-align: top;
        }

        .uri-cell {
            font-family: var(--font-mono);
            font-size: 13px;
            color: var(--on-surface);
            word-break: break-all;
            max-width: 300px;
        }

        .controller-cell {
            font-size: 13px;
            color: var(--on-surface-variant);
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Method Badges */
        .method-badge {
            display: inline-block;
            padding: var(--space-xs) var(--space-md);
            border-radius: var(--radius-sm);
            font-size: 11px;
            font-weight: 600;
            color: white;
            margin-right: var(--space-sm);
            margin-bottom: var(--space-xs);
            min-width: 56px;
            text-align: center;
            font-family: var(--font-mono);
        }

        .method-get { background-color: var(--method-get); }
        .method-post { background-color: var(--method-post); }
        .method-put { background-color: var(--method-put); }
        .method-patch { background-color: var(--method-patch); }
        .method-delete { background-color: var(--method-delete); }

        /* Status Badges */
        .auth-badge {
            background-color: var(--warning);
            color: white;
            padding: var(--space-xs) var(--space-sm);
            border-radius: var(--radius-sm);
            font-size: 10px;
            font-weight: 600;
            margin-left: var(--space-sm);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Actions */
        .actions-cell {
            white-space: nowrap;
        }

        .action-button {
            background: none;
            border: 1px solid var(--outline);
            border-radius: var(--radius-sm);
            padding: var(--space-sm) var(--space-md);
            font-size: 12px;
            font-weight: 500;
            color: var(--on-surface-variant);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: var(--space-xs);
            margin-right: var(--space-sm);
            margin-bottom: var(--space-xs);
        }

        .action-button:active {
            background-color: var(--surface-container);
        }

        .action-button.active {
            background-color: var(--primary-lighter);
            border-color: var(--primary);
            color: var(--primary);
        }

        .preview-link {
            color: var(--info);
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: var(--space-xs);
            padding: var(--space-sm) var(--space-md);
            border: 1px solid var(--info);
            border-radius: var(--radius-sm);
            margin-right: var(--space-sm);
            margin-bottom: var(--space-xs);
        }

        /* Route Details */
        .route-details {
            display: none;
            background-color: var(--surface-variant);
            border-top: 1px solid var(--outline-variant);
        }

        .route-details.active {
            display: block;
        }

        .details-content {
            padding: var(--space-xl);
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: var(--space-lg);
        }

        .detail-card {
            background-color: var(--surface);
            border: 1px solid var(--outline-variant);
            border-radius: var(--radius-md);
            padding: var(--space-lg);
        }

        .detail-card-header {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            margin-bottom: var(--space-md);
        }

        .detail-card-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--on-surface);
        }

        .detail-card-icon {
            width: 16px;
            height: 16px;
            color: var(--on-surface-variant);
        }

        /* Validation Rules */
        .rules-container {
            background-color: var(--surface-container);
            border: 1px solid var(--outline-variant);
            border-radius: var(--radius-sm);
            padding: var(--space-md);
            font-family: var(--font-mono);
            font-size: 12px;
            max-height: 200px;
            overflow-y: auto;
        }

        .rule-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: var(--space-sm);
            gap: var(--space-md);
        }

        .rule-item:last-child {
            margin-bottom: 0;
        }

        .rule-field {
            font-weight: 600;
            color: var(--on-surface);
            flex-shrink: 0;
        }

        .rule-constraints {
            color: var(--on-surface-variant);
            text-align: right;
            flex: 1;
        }

        .required-rule {
            color: var(--error);
            font-weight: 600;
        }

        /* Middleware List */
        .middleware-list {
            display: flex;
            flex-wrap: wrap;
            gap: var(--space-sm);
            list-style: none;
        }

        .middleware-item {
            background-color: var(--info);
            color: white;
            padding: var(--space-xs) var(--space-md);
            border-radius: var(--radius-sm);
            font-size: 11px;
            font-weight: 500;
            font-family: var(--font-mono);
        }

        /* Parameter List */
        .parameter-list {
            list-style: none;
        }

        .parameter-item {
            background-color: var(--surface-container);
            border: 1px solid var(--outline-variant);
            border-radius: var(--radius-sm);
            padding: var(--space-md);
            margin-bottom: var(--space-sm);
            font-size: 13px;
        }

        .parameter-item:last-child {
            margin-bottom: 0;
        }

        .parameter-name {
            font-family: var(--font-mono);
            font-weight: 600;
            color: var(--on-surface);
        }

        .parameter-required {
            color: var(--error);
            font-weight: 600;
            margin-left: var(--space-sm);
        }

        .parameter-optional {
            color: var(--on-surface-variant);
            margin-left: var(--space-sm);
        }

        /* Info List */
        .info-list {
            list-style: none;
        }

        .info-item {
            background-color: var(--surface-container);
            border: 1px solid var(--outline-variant);
            border-radius: var(--radius-sm);
            padding: var(--space-md);
            margin-bottom: var(--space-sm);
            font-size: 13px;
        }

        .info-item:last-child {
            margin-bottom: 0;
        }

        .info-label {
            font-weight: 600;
            color: var(--on-surface);
            margin-right: var(--space-sm);
        }

        .info-value {
            color: var(--on-surface-variant);
            font-family: var(--font-mono);
            word-break: break-all;
        }

        /* Copy Notification */
        .copy-notification {
            position: fixed;
            bottom: var(--space-lg);
            right: var(--space-lg);
            background-color: var(--success);
            color: white;
            padding: var(--space-md) var(--space-lg);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-md);
            font-size: 14px;
            font-weight: 500;
            z-index: 1000;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .copy-notification.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Search Highlight */
        .search-highlight {
            background-color: #fff3cd;
            padding: 0 2px;
            border-radius: 2px;
        }

        /* Utility Classes */
        .hidden {
            display: none !important;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: var(--space-md);
            }

            .header {
                padding: var(--space-lg);
            }

            .header-content {
                flex-direction: column;
                align-items: stretch;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .filters-section {
                padding: var(--space-lg);
            }

            .filters-grid {
                grid-template-columns: 1fr;
                gap: var(--space-md);
            }

            .content {
                padding: var(--space-lg);
            }

            .table-header th,
            .table-cell {
                padding: var(--space-md);
            }

            .uri-cell {
                max-width: 200px;
            }

            .controller-cell {
                max-width: 150px;
            }

            .details-grid {
                grid-template-columns: 1fr;
            }

            .details-content {
                padding: var(--space-lg);
            }
        }

        @media (max-width: 480px) {
            .header-title {
                font-size: 24px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-card {
                padding: var(--space-sm) var(--space-md);
            }

            .stat-value {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="main-card">
            <header class="header">
                <div class="header-content">
                    <div class="header-text">
                        <h1 class="header-title">API Documentation</h1>
                        <p class="header-subtitle">Comprehensive overview of your application's API endpoints</p>

                        @php
                            $totalRoutes = count($routes);
                            $methodCounts = [];
                            $authCount = 0;

                            foreach ($routes as $route) {
                                foreach ($route['methods'] as $method) {
                                    if ($method !== 'HEAD') {
                                        if (!isset($methodCounts[$method])) {
                                            $methodCounts[$method] = 0;
                                        }
                                        $methodCounts[$method]++;
                                    }
                                }

                                if (isset($route['middleware']) && in_array('auth', $route['middleware'])) {
                                    $authCount++;
                                }
                            }
                        @endphp

                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-value">{{ $totalRoutes }}</div>
                                <div class="stat-label">Total Routes</div>
                            </div>
                            @foreach(['GET', 'POST', 'PUT', 'DELETE'] as $method)
                                @if(isset($methodCounts[$method]))
                                    <div class="stat-card">
                                        <div class="stat-value">{{ $methodCounts[$method] }}</div>
                                        <div class="stat-label">{{ $method }}</div>
                                    </div>
                                @endif
                            @endforeach
                            <div class="stat-card">
                                <div class="stat-value">{{ $authCount }}</div>
                                <div class="stat-label">Auth Required</div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="filters-section">
                <div class="filters-grid">
                    <div class="filter-group">
                        <label class="filter-label" for="method-filter">HTTP Method</label>
                        <select class="filter-select" id="method-filter">
                            <option value="">All Methods</option>
                            <option value="GET">GET</option>
                            <option value="POST">POST</option>
                            <option value="PUT">PUT</option>
                            <option value="PATCH">PATCH</option>
                            <option value="DELETE">DELETE</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="filter-label" for="controller-filter">Controller</label>
                        <input class="filter-input" type="text" id="controller-filter" placeholder="Filter by controller...">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label" for="route-filter">Route</label>
                        <input class="filter-input" type="text" id="route-filter" placeholder="Filter by route...">
                    </div>
                    <div class="filter-group">
                        <label class="filter-label" for="auth-filter">Authentication</label>
                        <select class="filter-select" id="auth-filter">
                            <option value="">All Routes</option>
                            <option value="auth">Authenticated</option>
                            <option value="public">Public</option>
                        </select>
                    </div>
                    <button class="reset-button" id="reset-filters">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 12a9 9 0 1 0 18 0 9 9 0 0 0-18 0z"></path>
                            <path d="M9 12l2 2 4-4"></path>
                        </svg>
                        Reset Filters
                    </button>
                </div>
            </div>

            <div class="content">
                <div id="no-results" class="no-results hidden">
                    <div class="no-results-icon">🔍</div>
                    <div class="no-results-text">No routes match your current filters</div>
                </div>

                @foreach($groupedRoutes as $prefix => $routes)
                    <div class="route-group" data-prefix="{{ $prefix }}">
                        <div class="group-header">
                            <span class="group-title">{{ $prefix }}</span>
                            <span class="group-count">{{ count($routes) }}</span>
                        </div>
                        <div class="table-container">
                            <table class="routes-table">
                                <thead class="table-header">
                                    <tr>
                                        <th>URI</th>
                                        <th>Method</th>
                                        <th>Controller</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($routes as $route)
                                        <tr class="table-row route-row"
                                            data-methods="{{ implode(',', array_filter($route['methods'], function($m) { return $m !== 'HEAD'; })) }}"
                                            data-controller="{{ $route['controller'] ?? 'Closure' }}"
                                            data-uri="{{ $route['uri'] }}"
                                            data-auth="{{ isset($route['middleware']) && in_array('auth', $route['middleware']) ? 'auth' : 'public' }}"
                                            data-route-id="{{ $loop->index }}-{{ str_replace('/', '-', $route['uri']) }}">
                                            <td class="table-cell uri-cell">
                                                {{ $route['uri'] }}
                                                @if(isset($route['middleware']) && in_array('auth', $route['middleware']))
                                                    <span class="auth-badge">Auth</span>
                                                @endif
                                            </td>
                                            <td class="table-cell">
                                                @foreach($route['methods'] as $method)
                                                    @if($method !== 'HEAD')
                                                        <span class="method-badge method-{{ strtolower($method) }}">{{ $method }}</span>
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td class="table-cell controller-cell">{{ $route['controller'] ?? 'Closure' }}</td>
                                            <td class="table-cell actions-cell">
                                                <button class="action-button toggle-details" data-route-id="{{ $loop->index }}-{{ str_replace('/', '-', $route['uri']) }}">
                                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <polyline points="6 9 12 15 18 9"></polyline>
                                                    </svg>
                                                    Details
                                                </button>

                                                @if(config('api-visibility.enable_preview') && $route['name'])
                                                    <a href="{{ route('api-visibility.preview.show', ['routeName' => $route['name']]) }}" class="preview-link">
                                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                            <circle cx="12" cy="12" r="3"></circle>
                                                        </svg>
                                                        Preview
                                                    </a>
                                                @endif

                                                <button class="action-button copy-url" data-url="{{ url($route['uri']) }}">
                                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                                    </svg>
                                                    Copy URL
                                                </button>
                                            </td>
                                        </tr>
                                        <tr class="route-details-row">
                                            <td colspan="4" class="route-details" id="details-{{ $loop->index }}-{{ str_replace('/', '-', $route['uri']) }}">
                                                <div class="details-content">
                                                    <div class="details-grid">
                                                        @if(!empty($route['validation_rules']))
                                                            <div class="detail-card">
                                                                <div class="detail-card-header">
                                                                    <svg class="detail-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                                        <polyline points="14 2 14 8 20 8"></polyline>
                                                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                                                    </svg>
                                                                    <span class="detail-card-title">Validation Rules</span>
                                                                </div>
                                                                <div class="rules-container">
                                                                    @foreach($route['validation_rules'] as $field => $rules)
                                                                        <div class="rule-item">
                                                                            <span class="rule-field">{{ $field }}</span>
                                                                            <span class="rule-constraints">
                                                                                @php
                                                                                    $ruleArray = is_array($rules) ? $rules : explode('|', $rules);
                                                                                    $formattedRules = [];
                                                                                    foreach($ruleArray as $rule) {
                                                                                        if($rule === 'required') {
                                                                                            $formattedRules[] = '<span class="required-rule">required</span>';
                                                                                        } else {
                                                                                            $formattedRules[] = $rule;
                                                                                        }
                                                                                    }
                                                                                    echo implode(' | ', $formattedRules);
                                                                                @endphp
                                                                            </span>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if(!empty($route['middleware']))
                                                            <div class="detail-card">
                                                                <div class="detail-card-header">
                                                                    <svg class="detail-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                                                    </svg>
                                                                    <span class="detail-card-title">Middleware</span>
                                                                </div>
                                                                <ul class="middleware-list">
                                                                    @foreach($route['middleware'] as $middleware)
                                                                        <li class="middleware-item">{{ $middleware }}</li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif

                                                        @php
                                                            $parameters = [];
                                                            if (isset($route['uri'])) {
                                                                preg_match_all('/\{([^}]+)\}/', $route['uri'], $matches);
                                                                if (isset($matches[1])) {
                                                                    foreach ($matches[1] as $param) {
                                                                        $isOptional = str_ends_with($param, '?');
                                                                        $parameters[] = [
                                                                            'name' => $isOptional ? rtrim($param, '?') : $param,
                                                                            'required' => !$isOptional
                                                                        ];
                                                                    }
                                                                }
                                                            }
                                                        @endphp

                                                        @if(!empty($parameters))
                                                            <div class="detail-card">
                                                                <div class="detail-card-header">
                                                                    <svg class="detail-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                        <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline>
                                                                        <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>
                                                                    </svg>
                                                                    <span class="detail-card-title">Route Parameters</span>
                                                                </div>
                                                                <ul class="parameter-list">
                                                                    @foreach($parameters as $param)
                                                                        <li class="parameter-item">
                                                                            <span class="parameter-name">{{ $param['name'] }}</span>
                                                                            @if($param['required'])
                                                                                <span class="parameter-required">(required)</span>
                                                                            @else
                                                                                <span class="parameter-optional">(optional)</span>
                                                                            @endif
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        @endif

                                                        <div class="detail-card">
                                                            <div class="detail-card-header">
                                                                <svg class="detail-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                                    <circle cx="12" cy="12" r="10"></circle>
                                                                    <line x1="12" y1="16" x2="12" y2="12"></line>
                                                                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                                                </svg>
                                                                <span class="detail-card-title">Route Information</span>
                                                            </div>
                                                            <ul class="info-list">
                                                                @if(isset($route['name']))
                                                                    <li class="info-item">
                                                                        <span class="info-label">Name:</span>
                                                                        <span class="info-value">{{ $route['name'] }}</span>
                                                                    </li>
                                                                @endif
                                                                <li class="info-item">
                                                                    <span class="info-label">Full URL:</span>
                                                                    <span class="info-value">{{ url($route['uri']) }}</span>
                                                                </li>
                                                                @if(isset($route['action']))
                                                                    <li class="info-item">
                                                                        <span class="info-label">Action:</span>
                                                                        <span class="info-value">{{ $route['action'] }}</span>
                                                                    </li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="copy-notification" id="copy-notification">Copied to clipboard!</div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const methodFilter = document.getElementById('method-filter');
            const controllerFilter = document.getElementById('controller-filter');
            const routeFilter = document.getElementById('route-filter');
            const authFilter = document.getElementById('auth-filter');
            const resetButton = document.getElementById('reset-filters');
            const noResults = document.getElementById('no-results');
            const copyNotification = document.getElementById('copy-notification');

            const filters = [methodFilter, controllerFilter, routeFilter, authFilter];

            // Apply filters when any filter changes
            filters.forEach(filter => {
                filter.addEventListener('input', applyFilters);
                filter.addEventListener('change', applyFilters);
            });

            // Reset filters
            resetButton.addEventListener('click', function() {
                filters.forEach(filter => {
                    filter.value = '';
                });
                applyFilters();
            });

            // Toggle route details
            document.querySelectorAll('.toggle-details').forEach(button => {
                button.addEventListener('click', function() {
                    const routeId = this.getAttribute('data-route-id');
                    const detailsElement = document.getElementById('details-' + routeId);

                    // Close all other details
                    document.querySelectorAll('.route-details.active').forEach(el => {
                        if (el.id !== 'details-' + routeId) {
                            el.classList.remove('active');
                            const otherRouteId = el.id.replace('details-', '');
                            document.querySelector(`.toggle-details[data-route-id="${otherRouteId}"]`).classList.remove('active');
                        }
                    });

                    // Toggle current details
                    detailsElement.classList.toggle('active');
                    this.classList.toggle('active');
                });
            });

            // Copy URL functionality
            document.querySelectorAll('.copy-url').forEach(button => {
                button.addEventListener('click', function() {
                    const url = this.getAttribute('data-url');
                    navigator.clipboard.writeText(url).then(() => {
                        copyNotification.textContent = 'URL copied to clipboard!';
                        copyNotification.classList.add('show');

                        setTimeout(() => {
                            copyNotification.classList.remove('show');
                        }, 2000);
                    });
                });
            });

            function applyFilters() {
                const methodValue = methodFilter.value.toUpperCase();
                const controllerValue = controllerFilter.value.toLowerCase();
                const routeValue = routeFilter.value.toLowerCase();
                const authValue = authFilter.value;

                const rows = document.querySelectorAll('.route-row');
                let visibleRows = 0;

                rows.forEach(row => {
                    const methods = row.getAttribute('data-methods').split(',');
                    const controller = row.getAttribute('data-controller').toLowerCase();
                    const uri = row.getAttribute('data-uri').toLowerCase();
                    const auth = row.getAttribute('data-auth');

                    const methodMatch = !methodValue || methods.includes(methodValue);
                    const controllerMatch = !controllerValue || controller.includes(controllerValue);
                    const routeMatch = !routeValue || uri.includes(routeValue);
                    const authMatch = !authValue || auth === authValue;

                    const isVisible = methodMatch && controllerMatch && routeMatch && authMatch;

                    row.classList.toggle('hidden', !isVisible);

                    // Also hide the details row
                    const detailsRow = row.nextElementSibling;
                    if (detailsRow && detailsRow.classList.contains('route-details-row')) {
                        detailsRow.classList.toggle('hidden', !isVisible);
                    }

                    if (isVisible) {
                        visibleRows++;

                        // Highlight search terms in the URI
                        if (routeValue) {
                            const uriCell = row.querySelector('.uri-cell');
                            let uriHtml = uriCell.innerHTML;

                            // Remove previous highlights
                            uriHtml = uriHtml.replace(/<span class="search-highlight">([^<]+)<\/span>/g, '$1');

                            // Add new highlights
                            const parts = uri.split(routeValue);
                            if (parts.length > 1) {
                                let highlightedHtml = '';
                                for (let i = 0; i < parts.length; i++) {
                                    highlightedHtml += parts[i];
                                    if (i < parts.length - 1) {
                                        highlightedHtml += '<span class="search-highlight">' + routeValue + '</span>';
                                    }
                                }

                                const badgesAndLinks = uriHtml.match(/<span class="[^"]+">.*?<\/span>|<a [^>]+>.*?<\/a>/g) || [];
                                uriCell.innerHTML = highlightedHtml + badgesAndLinks.join('');
                            }
                        } else {
                            const uriCell = row.querySelector('.uri-cell');
                            uriCell.innerHTML = uriCell.innerHTML.replace(/<span class="search-highlight">([^<]+)<\/span>/g, '$1');
                        }
                    }
                });

                // Update group visibility based on visible rows
                const groups = document.querySelectorAll('.route-group');
                let visibleGroups = 0;

                groups.forEach(group => {
                    const hasVisibleRows = group.querySelector('.route-row:not(.hidden)') !== null;
                    group.classList.toggle('hidden', !hasVisibleRows);

                    if (hasVisibleRows) {
                        visibleGroups++;
                    }

                    // Update count in group title
                    const visibleRowsCount = group.querySelectorAll('.route-row:not(.hidden)').length;
                    const countElement = group.querySelector('.group-count');
                    if (countElement) {
                        countElement.textContent = visibleRowsCount;
                    }
                });

                // Show "no results" message if needed
                noResults.classList.toggle('hidden', visibleRows > 0);
            }
        });
    </script>
</body>
</html>