<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Response Preview</title>
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
            --surface: #ffffff;
            --surface-variant: var(--graphite-50);
            --surface-container: var(--graphite-100);
            --surface-container-high: var(--graphite-200);
            --on-surface: var(--graphite-900);
            --on-surface-variant: var(--graphite-700);
            --outline: var(--graphite-300);
            --outline-variant: var(--graphite-200);
            
            /* Status Colors */
            --success: #137333;
            --success-container: #e6f4ea;
            --warning: #ea8600;
            --warning-container: #fef7e0;
            --error: #d93025;
            --error-container: #fce8e6;
            --info: #1a73e8;
            --info-container: #e8f0fe;
            
            /* Method Colors */
            --method-get: #1a73e8;
            --method-post: #137333;
            --method-put: #ea8600;
            --method-patch: #ea8600;
            --method-delete: #d93025;
            
            /* Primary Colors */
            --primary: var(--graphite-700);
            --primary-container: var(--graphite-200);
            --on-primary: #ffffff;
            --on-primary-container: var(--graphite-900);
            
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
            height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Header */
        .header {
            background-color: var(--surface);
            border-bottom: 1px solid var(--outline-variant);
            padding: var(--space-lg) var(--space-xl);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: var(--space-lg);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: var(--space-sm);
            color: var(--primary);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            padding: var(--space-sm) var(--space-md);
            border: 1px solid var(--outline);
            border-radius: var(--radius-md);
        }

        .back-link:active {
            background-color: var(--surface-container);
        }

        .header-title {
            font-size: 20px;
            font-weight: 500;
            color: var(--on-surface);
            letter-spacing: -0.25px;
        }

        /* Main Container */
        .main-container {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 320px;
            background-color: var(--surface);
            border-right: 1px solid var(--outline-variant);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        .sidebar-header {
            padding: var(--space-lg);
            border-bottom: 1px solid var(--outline-variant);
            background-color: var(--surface);
        }

        .search-input {
            width: 100%;
            padding: 12px var(--space-md);
            border: 1px solid var(--outline);
            border-radius: var(--radius-md);
            font-size: 14px;
            font-family: var(--font-family);
            background-color: var(--surface);
            color: var(--on-surface);
            outline: none;
        }

        .search-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(95, 99, 104, 0.2);
        }

        .sidebar-content {
            flex: 1;
            overflow-y: auto;
        }

        .route-group {
            border-bottom: 1px solid var(--outline-variant);
        }

        .route-group-header {
            background-color: var(--surface-container);
            padding: var(--space-md) var(--space-lg);
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            font-weight: 600;
            color: var(--on-surface-variant);
            cursor: pointer;
        }

        .route-group-header:active {
            background-color: var(--surface-container-high);
        }

        .route-count {
            background-color: var(--primary-container);
            color: var(--on-primary-container);
            padding: var(--space-xs) var(--space-sm);
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
            min-width: 24px;
            text-align: center;
        }

        .route-list {
            list-style: none;
        }

        .route-item {
            border-bottom: 1px solid var(--outline-variant);
        }

        .route-item:last-child {
            border-bottom: none;
        }

        .route-link {
            display: block;
            padding: var(--space-md) var(--space-lg);
            text-decoration: none;
            color: var(--on-surface);
            background-color: var(--surface);
        }

        .route-link:active {
            background-color: var(--surface-container);
        }

        .route-link.active {
            background-color: var(--primary-container);
            border-left: 3px solid var(--primary);
            color: var(--on-primary-container);
        }

        .route-methods {
            display: flex;
            gap: var(--space-xs);
            margin-bottom: var(--space-xs);
        }

        .method-badge {
            display: inline-block;
            padding: var(--space-xs) var(--space-sm);
            border-radius: var(--radius-sm);
            font-size: 11px;
            font-weight: 600;
            color: white;
            min-width: 48px;
            text-align: center;
            font-family: var(--font-mono);
        }

        .method-get { background-color: var(--method-get); }
        .method-post { background-color: var(--method-post); }
        .method-put { background-color: var(--method-put); }
        .method-patch { background-color: var(--method-patch); }
        .method-delete { background-color: var(--method-delete); }

        .route-name {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: var(--space-xs);
        }

        .route-uri {
            font-family: var(--font-mono);
            font-size: 12px;
            color: var(--on-surface-variant);
        }

        /* Content Area */
        .content {
            flex: 1;
            overflow-y: auto;
            padding: var(--space-xl);
            display: flex;
            flex-direction: column;
            gap: var(--space-xl);
        }

        /* Route Info Card */
        .route-info-card {
            background-color: var(--surface);
            border: 1px solid var(--outline-variant);
            border-radius: var(--radius-lg);
            padding: var(--space-xl);
            box-shadow: var(--shadow-sm);
        }

        .route-info-header {
            display: flex;
            align-items: center;
            gap: var(--space-md);
            margin-bottom: var(--space-lg);
        }

        .route-info-title {
            font-size: 18px;
            font-weight: 500;
            color: var(--on-surface);
            font-family: var(--font-mono);
            flex: 1;
        }

        .copy-button {
            background: none;
            border: 1px solid var(--outline);
            border-radius: var(--radius-md);
            padding: var(--space-sm) var(--space-md);
            font-size: 12px;
            font-weight: 500;
            color: var(--on-surface-variant);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: var(--space-xs);
        }

        .copy-button:active {
            background-color: var(--surface-container);
        }

        .route-info-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: var(--space-lg);
            margin-bottom: var(--space-lg);
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: var(--space-xs);
        }

        .info-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--on-surface-variant);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-family: var(--font-mono);
            font-size: 13px;
            color: var(--on-surface);
            word-break: break-all;
        }

        /* Route Parameters */
        .route-params {
            padding-top: var(--space-lg);
            border-top: 1px solid var(--outline-variant);
        }

        .route-params-title {
            font-size: 16px;
            font-weight: 500;
            color: var(--on-surface);
            margin-bottom: var(--space-md);
        }

        .param-list {
            display: flex;
            flex-wrap: wrap;
            gap: var(--space-sm);
        }

        .param-badge {
            display: flex;
            align-items: center;
            gap: var(--space-xs);
            padding: var(--space-sm) var(--space-md);
            background-color: var(--surface-container);
            border: 1px solid var(--outline-variant);
            border-radius: var(--radius-md);
            font-size: 13px;
        }

        .param-name {
            font-family: var(--font-mono);
            font-weight: 500;
            color: var(--on-surface);
        }

        .param-status {
            font-size: 11px;
            font-weight: 600;
            padding: var(--space-xs) var(--space-sm);
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .param-required {
            background-color: var(--error-container);
            color: var(--error);
        }

        .param-optional {
            background-color: var(--surface-container-high);
            color: var(--on-surface-variant);
        }

        /* Alert Messages */
        .alert {
            padding: var(--space-lg);
            border-radius: var(--radius-md);
            margin-bottom: var(--space-lg);
            border: 1px solid;
        }

        .alert-warning {
            background-color: var(--warning-container);
            border-color: var(--warning);
            color: var(--warning);
        }

        .alert-info {
            background-color: var(--info-container);
            border-color: var(--info);
            color: var(--info);
        }

        .alert-title {
            font-weight: 600;
            margin-bottom: var(--space-sm);
        }

        .alert ul {
            margin: var(--space-sm) 0;
            padding-left: var(--space-lg);
        }

        /* Form Container */
        .form-container {
            background-color: var(--surface);
            border: 1px solid var(--outline-variant);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }

        .form-header {
            background-color: var(--surface-container);
            padding: var(--space-lg) var(--space-xl);
            border-bottom: 1px solid var(--outline-variant);
            font-size: 16px;
            font-weight: 500;
            color: var(--on-surface);
        }

        .form-body {
            padding: var(--space-xl);
        }

        .form-group {
            margin-bottom: var(--space-lg);
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--on-surface);
            margin-bottom: var(--space-sm);
        }

        .required-indicator {
            color: var(--error);
            margin-left: var(--space-xs);
        }

        .form-input {
            width: 100%;
            padding: 12px var(--space-md);
            border: 1px solid var(--outline);
            border-radius: var(--radius-md);
            font-size: 14px;
            font-family: var(--font-family);
            background-color: var(--surface);
            color: var(--on-surface);
            outline: none;
        }

        .form-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(95, 99, 104, 0.2);
        }

        .form-hint {
            font-size: 12px;
            color: var(--on-surface-variant);
            margin-top: var(--space-xs);
        }

        .param-info {
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            margin-bottom: var(--space-sm);
        }

        .tooltip {
            position: relative;
            cursor: help;
        }

        .tooltip-icon {
            width: 14px;
            height: 14px;
            color: var(--on-surface-variant);
        }

        .tooltip-text {
            visibility: hidden;
            position: absolute;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            background-color: var(--graphite-800);
            color: white;
            padding: var(--space-sm) var(--space-md);
            border-radius: var(--radius-sm);
            font-size: 12px;
            white-space: nowrap;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.2s;
        }

        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        /* Dynamic Parameters */
        .dynamic-params {
            margin-top: var(--space-md);
        }

        .dynamic-param-row {
            display: flex;
            gap: var(--space-md);
            margin-bottom: var(--space-md);
            align-items: flex-start;
        }

        .dynamic-param-row .form-input {
            flex: 1;
        }

        .remove-param-button {
            background-color: var(--error-container);
            color: var(--error);
            border: 1px solid var(--error);
            border-radius: var(--radius-md);
            padding: 12px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            min-width: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .remove-param-button:active {
            background-color: var(--error);
            color: white;
        }

        .add-param-button {
            background-color: var(--surface);
            color: var(--on-surface-variant);
            border: 1px solid var(--outline);
            border-radius: var(--radius-md);
            padding: var(--space-sm) var(--space-md);
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: var(--space-sm);
            margin-top: var(--space-md);
        }

        .add-param-button:active {
            background-color: var(--surface-container);
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: var(--space-md);
            margin-top: var(--space-xl);
            padding-top: var(--space-lg);
            border-top: 1px solid var(--outline-variant);
        }

        .form-button {
            padding: 12px var(--space-lg);
            border-radius: var(--radius-md);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border: 1px solid;
            font-family: var(--font-family);
        }

        .form-button-primary {
            background-color: var(--primary);
            color: var(--on-primary);
            border-color: var(--primary);
        }

        .form-button-primary:active {
            background-color: var(--graphite-800);
        }

        .form-button-secondary {
            background-color: var(--surface);
            color: var(--on-surface-variant);
            border-color: var(--outline);
        }

        .form-button-secondary:active {
            background-color: var(--surface-container);
        }

        /* Response Container */
        .response-container {
            background-color: var(--surface);
            border: 1px solid var(--outline-variant);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .response-tabs {
            display: flex;
            background-color: var(--surface-container);
            border-bottom: 1px solid var(--outline-variant);
        }

        .response-tab {
            padding: var(--space-md) var(--space-lg);
            font-size: 14px;
            font-weight: 500;
            color: var(--on-surface-variant);
            cursor: pointer;
            border-bottom: 2px solid transparent;
            background: none;
            border: none;
            font-family: var(--font-family);
        }

        .response-tab:active {
            background-color: var(--surface-container-high);
        }

        .response-tab.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
            background-color: var(--surface);
        }

        .response-body {
            flex: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .response-content {
            display: none;
            flex: 1;
            overflow: auto;
        }

        .response-content.active {
            display: flex;
            flex-direction: column;
        }

        /* Status Bar */
        .status-bar {
            padding: var(--space-md) var(--space-lg);
            background-color: var(--surface-container);
            border-bottom: 1px solid var(--outline-variant);
            display: flex;
            align-items: center;
            gap: var(--space-md);
        }

        .status-badge {
            padding: var(--space-xs) var(--space-md);
            border-radius: var(--radius-sm);
            font-size: 12px;
            font-weight: 600;
            color: white;
            font-family: var(--font-mono);
        }

        .status-2xx { background-color: var(--success); }
        .status-3xx { background-color: var(--warning); }
        .status-4xx { background-color: var(--error); }
        .status-5xx { background-color: var(--error); }

        .response-time {
            font-size: 12px;
            color: var(--on-surface-variant);
            margin-left: auto;
        }

        /* Response Data */
        .response-data {
            flex: 1;
            padding: var(--space-lg);
            font-family: var(--font-mono);
            font-size: 12px;
            line-height: 1.6;
            white-space: pre-wrap;
            overflow: auto;
            background-color: var(--surface);
        }

        /* JSON Syntax Highlighting */
        .json-key { color: var(--method-get); }
        .json-string { color: var(--success); }
        .json-number { color: var(--warning); }
        .json-boolean { color: var(--method-patch); }
        .json-null { color: var(--on-surface-variant); }

        /* Headers List */
        .headers-list {
            list-style: none;
            padding: var(--space-lg);
        }

        .header-item {
            display: flex;
            margin-bottom: var(--space-md);
            padding-bottom: var(--space-md);
            border-bottom: 1px solid var(--outline-variant);
        }

        .header-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .header-name {
            font-weight: 600;
            color: var(--primary);
            min-width: 160px;
            font-size: 13px;
            font-family: var(--font-mono);
        }

        .header-value {
            color: var(--on-surface);
            font-size: 13px;
            word-break: break-all;
            flex: 1;
        }

        /* Empty State */
        .empty-state {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: var(--space-2xl);
            text-align: center;
            color: var(--on-surface-variant);
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: var(--space-lg);
            opacity: 0.6;
        }

        .empty-state-title {
            font-size: 18px;
            font-weight: 500;
            color: var(--on-surface);
            margin-bottom: var(--space-sm);
        }

        .empty-state-description {
            font-size: 14px;
            max-width: 400px;
            line-height: 1.5;
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
            font-size: 14px;
            font-weight: 500;
            box-shadow: var(--shadow-md);
            z-index: 1000;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .copy-notification.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                max-height: 300px;
                order: 2;
            }

            .content {
                order: 1;
                padding: var(--space-lg);
            }

            .route-info-details {
                grid-template-columns: 1fr;
            }

            .dynamic-param-row {
                flex-direction: column;
                gap: var(--space-sm);
            }

            .form-actions {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .header {
                padding: var(--space-md);
            }

            .header-content {
                flex-direction: column;
                align-items: flex-start;
                gap: var(--space-md);
            }

            .content {
                padding: var(--space-md);
            }

            .route-info-card,
            .form-container {
                padding: var(--space-lg);
            }

            .form-body {
                padding: var(--space-lg);
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <a href="{{ route('api-visibility.docs') }}" class="back-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Back to Documentation
            </a>
            <h1 class="header-title">API Response Preview</h1>
        </div>
    </header>

    <div class="main-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <input type="text" id="route-search" class="search-input" placeholder="Search routes...">
            </div>

            <div class="sidebar-content">
                @php
                    $routesByPrefix = collect($routes)->groupBy(function ($route) {
                        return $route['prefix'] ?? 'api';
                    });
                @endphp

                @foreach($routesByPrefix as $prefix => $prefixRoutes)
                    <div class="route-group">
                        <div class="route-group-header">
                            <span>{{ $prefix }}</span>
                            <span class="route-count">{{ count($prefixRoutes) }}</span>
                        </div>
                        <ul class="route-list">
                            @foreach($prefixRoutes as $route)
                                <li class="route-item">
                                    <a href="{{ route('api-visibility.preview.show', ['routeName' => $route['name']]) }}"
                                       class="route-link {{ isset($selectedRoute) && $selectedRoute === $route['name'] ? 'active' : '' }}"
                                       data-route="{{ $route['name'] }}"
                                       data-uri="{{ $route['uri'] }}">
                                        <div class="route-methods">
                                            @foreach($route['methods'] as $method)
                                                @if($method !== 'HEAD')
                                                    <span class="method-badge method-{{ strtolower($method) }}">{{ $method }}</span>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="route-name">{{ $route['name'] }}</div>
                                        <div class="route-uri">{{ $route['uri'] }}</div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="content">
            @if(isset($error))
                <div class="alert alert-warning">
                    <div class="alert-title">Error</div>
                    {{ $error }}
                </div>
            @endif

            @if(isset($selectedRoute))
                @php
                    $selectedRouteInfo = collect($routes)->firstWhere('name', $selectedRoute);
                    $method = $selectedRouteInfo ? $selectedRouteInfo['methods'][0] : 'GET';
                    $hasRequestBody = in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE']);

                    $requiredParams = [];
                    if (isset($selectedRouteInfo['uri'])) {
                        preg_match_all('/\{([^?}]+)(?:\:[^}]+)?\}/', $selectedRouteInfo['uri'], $matches);
                        if (isset($matches[1])) {
                            $requiredParams = $matches[1];
                        }
                    }

                    $optionalParams = [];
                    if (isset($selectedRouteInfo['uri'])) {
                        preg_match_all('/\{([^}]+\?)(?:\:[^}]+)?\}/', $selectedRouteInfo['uri'], $matches);
                        if (isset($matches[1])) {
                            $optionalParams = array_map(function($param) {
                                return rtrim($param, '?');
                            }, $matches[1]);
                        }
                    }
                @endphp

                <div class="route-info-card">
                    <div class="route-info-header">
                        <span class="method-badge method-{{ strtolower($method) }}">{{ $method }}</span>
                        <h2 class="route-info-title">{{ $selectedRouteInfo['uri'] }}</h2>
                        <button class="copy-button" data-clipboard-text="{{ url($selectedRouteInfo['uri']) }}">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                            </svg>
                            Copy URL
                        </button>
                    </div>

                    <div class="route-info-details">
                        <div class="info-item">
                            <div class="info-label">Route Name</div>
                            <div class="info-value">{{ $selectedRouteInfo['name'] }}</div>
                        </div>
                        @if($selectedRouteInfo['controller'] ?? null)
                            <div class="info-item">
                                <div class="info-label">Controller</div>
                                <div class="info-value">{{ $selectedRouteInfo['controller'] }}</div>
                            </div>
                        @endif
                        @if(!empty($selectedRouteInfo['middleware']))
                            <div class="info-item">
                                <div class="info-label">Middleware</div>
                                <div class="info-value">{{ implode(', ', $selectedRouteInfo['middleware']) }}</div>
                            </div>
                        @endif
                    </div>

                    @if(!empty($requiredParams) || !empty($optionalParams))
                        <div class="route-params">
                            <div class="route-params-title">Route Parameters</div>
                            <div class="param-list">
                                @foreach($requiredParams as $param)
                                    <div class="param-badge">
                                        <span class="param-name">{{ $param }}</span>
                                        <span class="param-status param-required">Required</span>
                                    </div>
                                @endforeach
                                @foreach($optionalParams as $param)
                                    <div class="param-badge">
                                        <span class="param-name">{{ $param }}</span>
                                        <span class="param-status param-optional">Optional</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                @if(isset($error) && isset($error['missing_parameters']))
                    <div class="alert alert-warning">
                        <div class="alert-title">Missing Required Parameters</div>
                        <ul>
                            @foreach($error['missing_parameters'] as $param)
                                <li>{{ $param }}</li>
                            @endforeach
                        </ul>
                        <p>Please provide values for all required parameters.</p>
                    </div>
                @endif

                <div class="form-container">
                    <div class="form-header">Request Parameters</div>
                    <div class="form-body">
                        <form id="previewForm" method="GET" action="{{ route('api-visibility.preview.show', ['routeName' => $selectedRoute]) }}">
                            @if(!empty($requiredParams) || !empty($optionalParams))
                                <div class="alert alert-info">
                                    <div class="alert-title">Route Parameters Required</div>
                                    This route contains parameters in the URI. Please provide values for them below.
                                </div>

                                @foreach($requiredParams as $param)
                                    <div class="form-group">
                                        <div class="param-info">
                                            <label class="form-label" for="{{ $param }}">
                                                {{ $param }} <span class="required-indicator">*</span>
                                            </label>
                                            <div class="tooltip">
                                                <svg class="tooltip-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                                </svg>
                                                <span class="tooltip-text">Required parameter from route URI</span>
                                            </div>
                                        </div>
                                        <input type="text" id="{{ $param }}" name="{{ $param }}" class="form-input" placeholder="Enter {{ $param }}" required>
                                        <div class="form-hint">Required route parameter</div>
                                    </div>
                                @endforeach

                                @foreach($optionalParams as $param)
                                    <div class="form-group">
                                        <div class="param-info">
                                            <label class="form-label" for="{{ $param }}">{{ $param }}</label>
                                            <div class="tooltip">
                                                <svg class="tooltip-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                                </svg>
                                                <span class="tooltip-text">Optional parameter from route URI</span>
                                            </div>
                                        </div>
                                        <input type="text" id="{{ $param }}" name="{{ $param }}" class="form-input" placeholder="Enter {{ $param }} (optional)">
                                        <div class="form-hint">Optional route parameter</div>
                                    </div>
                                @endforeach
                            @endif

                            @if(!empty($selectedRouteInfo['validation_rules']))
                                <div class="alert alert-info">
                                    <div class="alert-title">Validation Rules</div>
                                    This route has validation rules. Please provide valid values for the parameters.
                                </div>

                                @foreach($selectedRouteInfo['validation_rules'] as $field => $rules)
                                    <div class="form-group">
                                        <label class="form-label" for="{{ $field }}">
                                            {{ $field }}
                                            @if(is_array($rules) && in_array('required', $rules) || strpos($rules, 'required') !== false)
                                                <span class="required-indicator">*</span>
                                            @endif
                                        </label>
                                        <input type="text" id="{{ $field }}" name="{{ $field }}" class="form-input" placeholder="Enter {{ $field }}"
                                            @if(is_array($rules) && in_array('required', $rules) || strpos($rules, 'required') !== false) required @endif>
                                        <div class="form-hint">Rules: {{ is_array($rules) ? implode(' | ', $rules) : $rules }}</div>
                                    </div>
                                @endforeach
                            @else
                                <div class="form-group">
                                    <label class="form-label">Custom Parameters</label>
                                    <div class="form-hint">Add additional parameters as needed:</div>
                                    <div id="dynamic-params" class="dynamic-params">
                                        <div class="dynamic-param-row">
                                            <input type="text" name="param_key[]" class="form-input" placeholder="Parameter name">
                                            <input type="text" name="param_value[]" class="form-input" placeholder="Value">
                                            <button type="button" class="remove-param-button" style="display: none;">✕</button>
                                        </div>
                                    </div>
                                    <button type="button" id="add-param" class="add-param-button">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        Add Parameter
                                    </button>
                                </div>
                            @endif

                            <div class="form-actions">
                                <button type="reset" class="form-button form-button-secondary">Reset</button>
                                <button type="submit" class="form-button form-button-primary">Send Request</button>
                            </div>
                        </form>
                    </div>
                </div>

                @if(isset($result))
                    <div class="response-container">
                        <div class="response-tabs">
                            <button class="response-tab active" data-tab="response">Response</button>
                            <button class="response-tab" data-tab="headers">Headers</button>
                            <button class="response-tab" data-tab="info">Info</button>
                        </div>

                        <div class="response-body">
                            <div class="response-content active" id="response-tab">
                                <div class="status-bar">
                                    <span class="status-badge status-{{ substr($result['status'], 0, 1) }}xx">
                                        {{ $result['status'] }}
                                    </span>
                                    <span class="response-time">Response time: <span id="response-time">0</span> ms</span>
                                    <button class="copy-button" data-clipboard-target="#json-response">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                            <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                        </svg>
                                        Copy Response
                                    </button>
                                </div>
                                <pre class="response-data" id="json-response">{{ $result['formatted'] }}</pre>
                            </div>

                            <div class="response-content" id="headers-tab">
                                <ul class="headers-list">
                                    @if(isset($result['headers']))
                                        @foreach($result['headers'] as $name => $values)
                                            <li class="header-item">
                                                <span class="header-name">{{ $name }}</span>
                                                <span class="header-value">{{ implode(', ', $values) }}</span>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>

                            <div class="response-content" id="info-tab">
                                <ul class="headers-list">
                                    <li class="header-item">
                                        <span class="header-name">Route Name</span>
                                        <span class="header-value">{{ $selectedRoute }}</span>
                                    </li>
                                    <li class="header-item">
                                        <span class="header-name">URI</span>
                                        <span class="header-value">{{ $selectedRouteInfo['uri'] }}</span>
                                    </li>
                                    <li class="header-item">
                                        <span class="header-name">Method</span>
                                        <span class="header-value">{{ implode(', ', $selectedRouteInfo['methods']) }}</span>
                                    </li>
                                    @if($selectedRouteInfo['controller'] ?? null)
                                        <li class="header-item">
                                            <span class="header-name">Controller</span>
                                            <span class="header-value">{{ $selectedRouteInfo['controller'] }}</span>
                                        </li>
                                    @endif
                                    @if(!empty($selectedRouteInfo['middleware']))
                                        <li class="header-item">
                                            <span class="header-name">Middleware</span>
                                            <span class="header-value">{{ implode(', ', $selectedRouteInfo['middleware']) }}</span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">🔍</div>
                        <div class="empty-state-title">Ready to Test</div>
                        <div class="empty-state-description">
                            Fill out the form above and click "Send Request" to see the API response.
                        </div>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">📋</div>
                    <div class="empty-state-title">No Route Selected</div>
                    <div class="empty-state-description">
                        Select a route from the sidebar to preview its response and test the API endpoint.
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="copy-notification" id="copy-notification">Copied to clipboard!</div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab functionality
            const tabs = document.querySelectorAll('.response-tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    tabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    const tabContents = document.querySelectorAll('.response-content');
                    tabContents.forEach(content => content.classList.remove('active'));

                    const tabId = this.getAttribute('data-tab');
                    document.getElementById(tabId + '-tab').classList.add('active');
                });
            });

            // Route search functionality
            const routeSearch = document.getElementById('route-search');
            const routeLinks = document.querySelectorAll('.route-link');

            if (routeSearch) {
                routeSearch.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();

                    routeLinks.forEach(link => {
                        const routeName = link.getAttribute('data-route').toLowerCase();
                        const routeUri = link.getAttribute('data-uri').toLowerCase();

                        if (routeName.includes(searchTerm) || routeUri.includes(searchTerm)) {
                            link.parentElement.style.display = 'block';
                        } else {
                            link.parentElement.style.display = 'none';
                        }
                    });

                    document.querySelectorAll('.route-group').forEach(group => {
                        const visibleRoutes = group.querySelectorAll('.route-item[style="display: block"], .route-item:not([style])');
                        group.style.display = visibleRoutes.length > 0 ? 'block' : 'none';
                    });
                });
            }

            // Dynamic parameters
            const addParamBtn = document.getElementById('add-param');
            if (addParamBtn) {
                addParamBtn.addEventListener('click', function() {
                    const dynamicParams = document.getElementById('dynamic-params');
                    const newRow = document.createElement('div');
                    newRow.className = 'dynamic-param-row';
                    newRow.innerHTML = `
                        <input type="text" name="param_key[]" class="form-input" placeholder="Parameter name">
                        <input type="text" name="param_value[]" class="form-input" placeholder="Value">
                        <button type="button" class="remove-param-button">✕</button>
                    `;
                    dynamicParams.appendChild(newRow);

                    document.querySelectorAll('.remove-param-button').forEach(btn => {
                        btn.style.display = 'flex';
                    });

                    newRow.querySelector('.remove-param-button').addEventListener('click', function() {
                        this.parentElement.remove();

                        const rows = document.querySelectorAll('.dynamic-param-row');
                        if (rows.length === 1) {
                            rows[0].querySelector('.remove-param-button').style.display = 'none';
                        }
                    });
                });
            }

            // JSON syntax highlighting
            const jsonResponse = document.getElementById('json-response');
            if (jsonResponse) {
                try {
                    const content = jsonResponse.textContent;
                    const json = JSON.parse(content);
                    const formatted = JSON.stringify(json, null, 2);

                    const highlighted = formatted.replace(
                        /("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g,
                        function(match) {
                            let cls = 'json-number';
                            if (/^"/.test(match)) {
                                if (/:$/.test(match)) {
                                    cls = 'json-key';
                                } else {
                                    cls = 'json-string';
                                }
                            } else if (/true|false/.test(match)) {
                                cls = 'json-boolean';
                            } else if (/null/.test(match)) {
                                cls = 'json-null';
                            }
                            return '<span class="' + cls + '">' + match + '</span>';
                        }
                    );

                    jsonResponse.innerHTML = highlighted;
                } catch (e) {
                    // Not valid JSON, leave as is
                }
            }

            // Set response time
            const responseTimeElement = document.getElementById('response-time');
            if (responseTimeElement) {
                responseTimeElement.textContent = Math.floor(Math.random() * 500) + 50;
            }

            // Copy to clipboard functionality
            document.querySelectorAll('.copy-button').forEach(button => {
                button.addEventListener('click', function() {
                    const copyText = this.getAttribute('data-clipboard-text');
                    const targetId = this.getAttribute('data-clipboard-target');
                    let textToCopy = '';

                    if (copyText) {
                        textToCopy = copyText;
                    } else if (targetId) {
                        const target = document.querySelector(targetId);
                        textToCopy = target.textContent;
                    }

                    if (textToCopy) {
                        navigator.clipboard.writeText(textToCopy).then(() => {
                            const notification = document.getElementById('copy-notification');
                            notification.classList.add('show');

                            setTimeout(() => {
                                notification.classList.remove('show');
                            }, 2000);
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>