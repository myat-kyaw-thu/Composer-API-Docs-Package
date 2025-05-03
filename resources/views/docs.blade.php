<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation</title>
    <style>
        :root {
            --primary: #3a86ff;
            --primary-dark: #2667cc;
            --primary-light: rgba(58, 134, 255, 0.1);
            --primary-lighter: #dbeafe;
            --secondary: #8338ec;
            --success: #06d6a0;
            --success-light: #d1fae5;
            --warning: #ffbe0b;
            --warning-light: #fff3cd;
            --danger: #ef476f;
            --danger-light: #ffdce0;
            --info: #06b6d4;
            --info-light: #e0f7fa;
            --dark: #1f2937;
            --gray: #6c757d;
            --gray-light: #e9ecef;
            --light: #f8f9fa;
            --white: #ffffff;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --radius: 6px;
            --font-main: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            --font-mono: SFMono-Regular, Consolas, 'Liberation Mono', Menlo, monospace;
            --transition: all 0.2s ease-in-out;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-main);
            line-height: 1.6;
            color: var(--dark);
            background-color: var(--light);
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .container {
            background-color: var(--white);
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            padding: 2rem;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
            border-bottom: 1px solid var(--gray-light);
            padding-bottom: 1.5rem;
        }

        .header-left {
            flex: 1;
        }

        .header-right {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        h1 {
            font-size: 1.8rem;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: var(--gray);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .stats {
            display: flex;
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: var(--light);
            padding: 0.75rem 1.25rem;
            border-radius: var(--radius);
            min-width: 100px;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--primary);
        }

        .stat-label {
            font-size: 0.8rem;
            color: var(--gray);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .filters {
            background-color: var(--light);
            padding: 1.5rem;
            border-radius: var(--radius);
            margin-bottom: 2rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-end;
            box-shadow: var(--shadow);
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            min-width: 200px;
            flex: 1;
        }

        .filter-group label {
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
            color: var(--dark);
        }

        .filter-group select,
        .filter-group input {
            padding: 0.6rem 0.8rem;
            border: 1px solid #e5e7eb;
            border-radius: var(--radius);
            font-size: 0.9rem;
            background-color: var(--white);
            transition: var(--transition);
        }

        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px var(--primary-light);
        }

        .btn {
            padding: 0.6rem 1rem;
            border-radius: var(--radius);
            cursor: pointer;
            font-size: 0.9rem;
            font-weight: 500;
            transition: var(--transition);
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid #e5e7eb;
            color: var(--dark);
        }

        .btn-outline:hover {
            background-color: #e5e7eb;
        }

        .btn-icon {
            padding: 0.5rem;
            border-radius: 50%;
            width: 36px;
            height: 36px;
        }

        .group {
            margin-bottom: 2rem;
            animation: fadeIn 0.3s ease-in-out;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .group-title {
            background: var(--primary-lighter);
            color: var(--primary-dark);
            padding: 1rem 1.2rem;
            font-weight: 600;
            font-size: 0.95rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .group-title .count {
            background-color: var(--primary);
            color: white;
            border-radius: 20px;
            padding: 0.2rem 0.6rem;
            font-size: 0.75rem;
        }

        .table-container {
            overflow-x: auto;
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 var(--radius) var(--radius);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            text-align: left;
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background-color: #f8fafc;
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--dark);
            position: sticky;
            top: 0;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background-color: #f8fafc;
        }

        .method {
            display: inline-block;
            padding: 0.3rem 0.6rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            color: white;
            margin-right: 0.3rem;
            margin-bottom: 0.3rem;
            min-width: 60px;
            text-align: center;
        }

        .get {
            background-color: var(--info);
            box-shadow: 0 2px 5px rgba(6, 182, 212, 0.2);
        }

        .post {
            background-color: var(--success);
            box-shadow: 0 2px 5px rgba(16, 185, 129, 0.2);
        }

        .put, .patch {
            background-color: var(--warning);
            box-shadow: 0 2px 5px rgba(245, 158, 11, 0.2);
        }

        .delete {
            background-color: var(--danger);
            box-shadow: 0 2px 5px rgba(239, 68, 68, 0.2);
        }

        .uri-cell {
            font-family: var(--font-mono);
            font-size: 0.85rem;
            word-break: break-all;
        }

        .controller-cell {
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            transition: var(--transition);
        }

        .controller-cell:hover {
            overflow: visible;
            white-space: normal;
            background-color: var(--white);
            position: relative;
            z-index: 1;
            box-shadow: var(--shadow);
            border-radius: var(--radius);
            padding: 0.5rem;
        }

        .rules {
            font-family: var(--font-mono);
            background: #f8fafc;
            padding: 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid var(--gray-light);
        }

        .rules div {
            margin-bottom: 0.3rem;
            display: flex;
            justify-content: space-between;
        }

        .rules .field-name {
            font-weight: 600;
            margin-right: 0.5rem;
        }

        .rules .field-rules {
            color: var(--gray);
        }

        .required-rule {
            color: var(--danger) !important;
            font-weight: 500;
        }

        .preview-link {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            margin-left: 0.5rem;
            font-size: 0.75rem;
            color: var(--primary);
            text-decoration: none;
            vertical-align: middle;
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
            background-color: var(--primary-lighter);
            transition: var(--transition);
        }

        .preview-link:hover {
            background-color: var(--primary-light);
        }

        .badge {
            display: inline-block;
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
            font-size: 0.7rem;
            font-weight: 600;
            margin-left: 0.5rem;
        }

        .auth-badge {
            background-color: var(--primary-lighter);
            color: var(--primary-dark);
        }

        .middleware-badge {
            background-color: var(--info-light);
            color: var(--info);
        }

        .no-results {
            padding: 3rem;
            text-align: center;
            color: var(--gray);
            font-style: italic;
            background-color: var(--light);
            border-radius: var(--radius);
            margin-bottom: 2rem;
        }

        .no-results-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .hidden {
            display: none;
        }

        .route-details {
            display: none;
            padding: 1rem;
            background-color: var(--light);
            border-top: 1px solid #e5e7eb;
        }

        .route-details.active {
            display: block;
            animation: slideDown 0.3s ease-in-out;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1rem;
        }

        .detail-card {
            background-color: var(--white);
            border-radius: var(--radius);
            padding: 1rem;
            box-shadow: var(--shadow);
        }

        .detail-card-title {
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .detail-card-content {
            font-size: 0.85rem;
        }

        .middleware-list {
            list-style: none;
            padding: 0;
        }

        .middleware-list li {
            display: inline-block;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            padding: 0.2rem 0.4rem;
            background-color: var(--info-light);
            color: var(--info);
            border-radius: 4px;
            font-size: 0.75rem;
        }

        .parameter-list {
            list-style: none;
            padding: 0;
        }

        .parameter-list li {
            margin-bottom: 0.5rem;
            padding: 0.5rem;
            background-color: var(--light);
            border-radius: 4px;
            font-size: 0.8rem;
            border: 1px solid var(--gray-light);
        }

        .parameter-name {
            font-weight: 600;
            font-family: var(--font-mono);
        }

        .parameter-required {
            color: var(--danger);
            font-weight: 500;
            margin-left: 0.3rem;
        }

        .toggle-details {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--primary);
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.3rem 0.5rem;
            border-radius: 4px;
            transition: var(--transition);
        }

        .toggle-details:hover {
            background-color: var(--primary-lighter);
        }

        .toggle-details svg {
            transition: transform 0.2s;
        }

        .toggle-details.active svg {
            transform: rotate(180deg);
        }

        .copy-url {
            background: none;
            border: none;
            cursor: pointer;
            color: var(--primary);
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.3rem 0.5rem;
            border-radius: 4px;
            transition: var(--transition);
        }

        .copy-url:hover {
            background-color: var(--primary-lighter);
        }

        .tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip .tooltip-text {
            visibility: hidden;
            width: 120px;
            background-color: var(--dark);
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -60px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 0.75rem;
        }

        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        .copy-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: var(--success);
            color: white;
            padding: 0.75rem 1rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow-md);
            z-index: 1000;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.3s, transform 0.3s;
        }

        .copy-notification.show {
            opacity: 1;
            transform: translateY(0);
        }

        .search-highlight {
            background-color: #fff3cd;
            padding: 0 2px;
            border-radius: 2px;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1rem;
            }

            .filters {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-group {
                min-width: 100%;
            }

            th, td {
                padding: 0.75rem 0.5rem;
            }

            .controller-cell {
                max-width: 150px;
            }

            .details-grid {
                grid-template-columns: 1fr;
            }

            .stats {
                flex-wrap: wrap;
            }

            .stat-item {
                min-width: 80px;
                flex: 1;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="header-left">
                <h1>API Documentation</h1>
                <p class="subtitle">Explore and test your application's API endpoints</p>

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

                <div class="stats">
                    <div class="stat-item">
                        <div class="stat-value">{{ $totalRoutes }}</div>
                        <div class="stat-label">Total Routes</div>
                    </div>
                    @foreach(['GET', 'POST', 'PUT', 'DELETE'] as $method)
                        @if(isset($methodCounts[$method]))
                            <div class="stat-item">
                                <div class="stat-value">{{ $methodCounts[$method] }}</div>
                                <div class="stat-label">{{ $method }}</div>
                            </div>
                        @endif
                    @endforeach
                    <div class="stat-item">
                        <div class="stat-value">{{ $authCount }}</div>
                        <div class="stat-label">Auth Required</div>
                    </div>
                </div>
            </div>
        </header>

        <div class="filters">
            <div class="filter-group">
                <label for="method-filter">HTTP Method</label>
                <select id="method-filter">
                    <option value="">All Methods</option>
                    <option value="GET">GET</option>
                    <option value="POST">POST</option>
                    <option value="PUT">PUT</option>
                    <option value="PATCH">PATCH</option>
                    <option value="DELETE">DELETE</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="controller-filter">Controller</label>
                <input type="text" id="controller-filter" placeholder="Filter by controller...">
            </div>
            <div class="filter-group">
                <label for="route-filter">Route</label>
                <input type="text" id="route-filter" placeholder="Filter by route...">
            </div>
            <div class="filter-group">
                <label for="auth-filter">Authentication</label>
                <select id="auth-filter">
                    <option value="">All Routes</option>
                    <option value="auth">Authenticated</option>
                    <option value="public">Public</option>
                </select>
            </div>
            <button class="btn btn-outline" id="reset-filters">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 12a9 9 0 1 0 18 0 9 9 0 0 0-18 0z"></path>
                    <path d="M9 12l2 2 4-4"></path>
                </svg>
                Reset Filters
            </button>
        </div>

        <div id="no-results" class="no-results hidden">
            <div class="no-results-icon">🔍</div>
            <div>No routes match your filters. Try adjusting your criteria.</div>
        </div>

        @foreach($groupedRoutes as $prefix => $routes)
            <div class="group" data-prefix="{{ $prefix }}">
                <div class="group-title">
                    <span>{{ $prefix }}</span>
                    <span class="count">{{ count($routes) }}</span>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>URI</th>
                                <th>Method</th>
                                <th>Controller</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($routes as $route)
                                <tr class="route-row"
                                    data-methods="{{ implode(',', array_filter($route['methods'], function($m) { return $m !== 'HEAD'; })) }}"
                                    data-controller="{{ $route['controller'] ?? 'Closure' }}"
                                    data-uri="{{ $route['uri'] }}"
                                    data-auth="{{ isset($route['middleware']) && in_array('auth', $route['middleware']) ? 'auth' : 'public' }}"
                                    data-route-id="{{ $loop->index }}-{{ str_replace('/', '-', $route['uri']) }}">
                                    <td class="uri-cell">
                                        {{ $route['uri'] }}
                                        @if(isset($route['middleware']) && in_array('auth', $route['middleware']))
                                            <span class="badge auth-badge">Auth</span>
                                        @endif
                                    </td>
                                    <td>
                                        @foreach($route['methods'] as $method)
                                            @if($method !== 'HEAD')
                                                <span class="method {{ strtolower($method) }}">{{ $method }}</span>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td class="controller-cell">{{ $route['controller'] ?? 'Closure' }}</td>
                                    <td>
                                        <div class="actions">
                                            <button class="toggle-details" data-route-id="{{ $loop->index }}-{{ str_replace('/', '-', $route['uri']) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="6 9 12 15 18 9"></polyline>
                                                </svg>
                                                Details
                                            </button>

                                            @if(config('api-visibility.enable_preview') && $route['name'])
                                                <a href="{{ route('api-visibility.preview.show', ['routeName' => $route['name']]) }}" class="preview-link">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                        <circle cx="12" cy="12" r="3"></circle>
                                                    </svg>
                                                    Preview
                                                </a>
                                            @endif

                                            <button class="copy-url" data-url="{{ url($route['uri']) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                                </svg>
                                                Copy URL
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="route-details-row">
                                    <td colspan="4" class="route-details" id="details-{{ $loop->index }}-{{ str_replace('/', '-', $route['uri']) }}">
                                        <div class="details-grid">
                                            @if(!empty($route['validation_rules']))
                                                <div class="detail-card">
                                                    <div class="detail-card-title">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                            <polyline points="14 2 14 8 20 8"></polyline>
                                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                                            <polyline points="10 9 9 9 8 9"></polyline>
                                                        </svg>
                                                        Validation Rules
                                                    </div>
                                                    <div class="detail-card-content">
                                                        <div class="rules">
                                                            @foreach($route['validation_rules'] as $field => $rules)
                                                                <div>
                                                                    <span class="field-name">{{ $field }}:</span>
                                                                    <span class="field-rules">
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
                                                </div>
                                            @endif

                                            @if(!empty($route['middleware']))
                                                <div class="detail-card">
                                                    <div class="detail-card-title">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                                        </svg>
                                                        Middleware
                                                    </div>
                                                    <div class="detail-card-content">
                                                        <ul class="middleware-list">
                                                            @foreach($route['middleware'] as $middleware)
                                                                <li>{{ $middleware }}</li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endif

                                            @php
                                                // Extract route parameters
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
                                                    <div class="detail-card-title">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                            <polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline>
                                                            <path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>
                                                        </svg>
                                                        Route Parameters
                                                    </div>
                                                    <div class="detail-card-content">
                                                        <ul class="parameter-list">
                                                            @foreach($parameters as $param)
                                                                <li>
                                                                    <span class="parameter-name">{{ $param['name'] }}</span>
                                                                    @if($param['required'])
                                                                        <span class="parameter-required">(required)</span>
                                                                    @else
                                                                        <span>(optional)</span>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="detail-card">
                                                <div class="detail-card-title">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <circle cx="12" cy="12" r="10"></circle>
                                                        <line x1="12" y1="16" x2="12" y2="12"></line>
                                                        <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                                    </svg>
                                                    Route Information
                                                </div>
                                                <div class="detail-card-content">
                                                    <ul class="parameter-list">
                                                        @if(isset($route['name']))
                                                            <li>
                                                                <strong>Name:</strong> {{ $route['name'] }}
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <strong>Full URL:</strong> {{ url($route['uri']) }}
                                                        </li>
                                                        @if(isset($route['action']))
                                                            <li>
                                                                <strong>Action:</strong> {{ $route['action'] }}
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

                            // Find and update the corresponding button
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
                    const routeId = row.getAttribute('data-route-id');
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

                                // Only update the text part, preserve badges and links
                                const badgesAndLinks = uriHtml.match(/<span class="[^"]+">.*?<\/span>|<a [^>]+>.*?<\/a>/g) || [];
                                uriCell.innerHTML = highlightedHtml + badgesAndLinks.join('');
                            }
                        } else {
                            // Remove highlights if no search term
                            const uriCell = row.querySelector('.uri-cell');
                            uriCell.innerHTML = uriCell.innerHTML.replace(/<span class="search-highlight">([^<]+)<\/span>/g, '$1');
                        }
                    }
                });

                // Update group visibility based on visible rows
                const groups = document.querySelectorAll('.group');
                let visibleGroups = 0;

                groups.forEach(group => {
                    const hasVisibleRows = group.querySelector('.route-row:not(.hidden)') !== null;
                    group.classList.toggle('hidden', !hasVisibleRows);

                    if (hasVisibleRows) {
                        visibleGroups++;
                    }

                    // Update count in group title
                    const visibleRowsCount = group.querySelectorAll('.route-row:not(.hidden)').length;
                    const countElement = group.querySelector('.count');
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
