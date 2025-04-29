<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Response Preview</title>
    <style>
        :root {
            --primary: #3a86ff;
            --primary-dark: #2667cc;
            --secondary: #8338ec;
            --success: #06d6a0;
            --warning: #ffbe0b;
            --danger: #ef476f;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --gray-light: #e9ecef;
            --border-radius: 6px;
            --font-main: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            --font-mono: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            --shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
            background-color: #fff;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            padding: 20px;
            border-bottom: 1px solid var(--gray-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: white;
            z-index: 10;
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        h1 {
            font-size: 22px;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            color: var(--primary);
            text-decoration: none;
            font-size: 14px;
            transition: var(--transition);
        }

        .back-link:hover {
            color: var(--primary-dark);
        }

        .back-link svg {
            margin-right: 5px;
        }

        .container {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        .sidebar {
            width: 300px;
            border-right: 1px solid var(--gray-light);
            overflow-y: auto;
            background-color: var(--light);
        }

        .sidebar-header {
            padding: 15px;
            border-bottom: 1px solid var(--gray-light);
            position: sticky;
            top: 0;
            background-color: var(--light);
            z-index: 5;
        }

        .search-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--gray-light);
            border-radius: var(--border-radius);
            font-size: 14px;
            transition: var(--transition);
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(58, 134, 255, 0.2);
        }

        .route-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .route-group {
            margin-bottom: 10px;
        }

        .route-group-title {
            padding: 10px 15px;
            font-size: 14px;
            font-weight: 600;
            color: var(--gray);
            background-color: rgba(0, 0, 0, 0.03);
            border-bottom: 1px solid var(--gray-light);
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .route-group-title:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .route-group-title .count {
            background-color: var(--gray-light);
            color: var(--gray);
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 500;
        }

        .route-item {
            border-bottom: 1px solid var(--gray-light);
        }

        .route-link {
            text-decoration: none;
            color: var(--dark);
            display: block;
            padding: 10px 15px;
            font-size: 14px;
            transition: var(--transition);
        }

        .route-link:hover {
            background-color: rgba(58, 134, 255, 0.05);
        }

        .route-link.active {
            background-color: rgba(58, 134, 255, 0.1);
            border-left: 3px solid var(--primary);
        }

        .method {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            color: white;
            margin-right: 5px;
            min-width: 50px;
            text-align: center;
        }

        .get { background-color: var(--primary); }
        .post { background-color: var(--success); }
        .put { background-color: var(--warning); }
        .delete { background-color: var(--danger); }
        .patch { background-color: var(--secondary); }

        .route-uri {
            font-family: var(--font-mono);
            font-size: 13px;
            margin-top: 3px;
            color: var(--gray);
        }

        .content {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .route-info {
            background-color: var(--light);
            padding: 15px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            box-shadow: var(--shadow);
        }

        .route-info-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .route-info-title {
            font-size: 18px;
            font-weight: 600;
            margin-left: 10px;
        }

        .route-info-details {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            font-size: 14px;
        }

        .route-info-detail {
            display: flex;
            align-items: center;
        }

        .route-info-label {
            color: var(--gray);
            margin-right: 5px;
        }

        .route-info-value {
            font-family: var(--font-mono);
            font-weight: 500;
        }

        .form-container {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .form-header {
            background-color: var(--light);
            padding: 15px;
            border-bottom: 1px solid var(--gray-light);
            font-weight: 600;
        }

        .form-body {
            padding: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            font-size: 14px;
        }

        .form-hint {
            font-size: 12px;
            color: var(--gray);
            margin-top: 3px;
        }

        .form-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--gray-light);
            border-radius: var(--border-radius);
            font-size: 14px;
            transition: var(--transition);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(58, 134, 255, 0.2);
        }

        .form-select {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--gray-light);
            border-radius: var(--border-radius);
            font-size: 14px;
            background-color: white;
            transition: var(--transition);
        }

        .form-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(58, 134, 255, 0.2);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: var(--border-radius);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
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
            border: 1px solid var(--gray-light);
            color: var(--gray);
        }

        .btn-outline:hover {
            background-color: var(--gray-light);
        }

        .dynamic-params {
            margin-top: 10px;
        }

        .dynamic-param-row {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .response-container {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .response-tabs {
            display: flex;
            background-color: var(--light);
            border-bottom: 1px solid var(--gray-light);
        }

        .response-tab {
            padding: 10px 15px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: var(--transition);
        }

        .response-tab:hover {
            background-color: rgba(0, 0, 0, 0.03);
        }

        .response-tab.active {
            border-bottom-color: var(--primary);
            color: var(--primary);
        }

        .response-body {
            flex: 1;
            overflow: auto;
            position: relative;
        }

        .response-content {
            display: none;
            padding: 0;
        }

        .response-content.active {
            display: block;
        }

        .status-bar {
            padding: 10px 15px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid var(--gray-light);
        }

        .status-indicator {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            color: white;
            margin-right: 10px;
        }

        .status-2xx { background-color: var(--success); }
        .status-3xx { background-color: var(--warning); }
        .status-4xx { background-color: var(--danger); }
        .status-5xx { background-color: var(--danger); }

        .response-time {
            font-size: 12px;
            color: var(--gray);
            margin-left: auto;
        }

        .response-data {
            padding: 15px;
            font-family: var(--font-mono);
            font-size: 13px;
            line-height: 1.5;
            white-space: pre-wrap;
            overflow-x: auto;
        }

        .json-key {
            color: var(--primary);
        }

        .json-string {
            color: var(--success);
        }

        .json-number {
            color: var(--warning);
        }

        .json-boolean {
            color: var(--secondary);
        }

        .json-null {
            color: var(--gray);
        }

        .headers-list {
            list-style: none;
            padding: 15px;
        }

        .header-item {
            display: flex;
            margin-bottom: 8px;
            font-size: 13px;
        }

        .header-name {
            font-weight: 600;
            min-width: 150px;
            color: var(--primary);
        }

        .header-value {
            color: var(--dark);
            word-break: break-all;
        }

        .error {
            background-color: #ffdce0;
            color: #86181d;
            padding: 15px;
            border-radius: var(--border-radius);
            margin-bottom: 15px;
            font-size: 14px;
        }

        .empty-state {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            color: var(--gray);
            text-align: center;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }

        .empty-state-message {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 10px;
        }

        .empty-state-description {
            font-size: 14px;
            max-width: 400px;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                max-height: 300px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-title">
            <a href="{{ route('api-visibility.docs') }}" class="back-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Back to Documentation
            </a>
            <h1>API Response Preview</h1>
        </div>
    </header>

    <div class="container">
        <div class="sidebar">
            <div class="sidebar-header">
                <input type="text" id="route-search" class="search-input" placeholder="Search routes...">
            </div>

            @php
                // Group routes by prefix
                $routesByPrefix = collect($routes)->groupBy(function ($route) {
                    return $route['prefix'] ?? 'api';
                });
            @endphp

            @foreach($routesByPrefix as $prefix => $prefixRoutes)
                <div class="route-group">
                    <div class="route-group-title">
                        <span>{{ $prefix }}</span>
                        <span class="count">{{ count($prefixRoutes) }}</span>
                    </div>
                    <ul class="route-list">
                        @foreach($prefixRoutes as $route)
                            <li class="route-item">
                                <a href="{{ route('api-visibility.preview.show', ['routeName' => $route['name']]) }}"
                                   class="route-link {{ isset($selectedRoute) && $selectedRoute === $route['name'] ? 'active' : '' }}"
                                   data-route="{{ $route['name'] }}"
                                   data-uri="{{ $route['uri'] }}">
                                    <div>
                                        @foreach($route['methods'] as $method)
                                            @if($method !== 'HEAD')
                                                <span class="method {{ strtolower($method) }}">{{ $method }}</span>
                                            @endif
                                        @endforeach
                                        {{ $route['name'] }}
                                    </div>
                                    <div class="route-uri">{{ $route['uri'] }}</div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <div class="content">
            @if(isset($error))
                <div class="error">
                    <strong>Error:</strong> {{ $error }}
                </div>
            @endif

            @if(isset($selectedRoute))
                @php
                    $selectedRouteInfo = collect($routes)->firstWhere('name', $selectedRoute);
                    $method = $selectedRouteInfo ? $selectedRouteInfo['methods'][0] : 'GET';
                    $hasRequestBody = in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE']);
                @endphp

                <div class="route-info">
                    <div class="route-info-header">
                        <span class="method {{ strtolower($method) }}">{{ $method }}</span>
                        <h2 class="route-info-title">{{ $selectedRouteInfo['uri'] }}</h2>
                    </div>
                    <div class="route-info-details">
                        <div class="route-info-detail">
                            <span class="route-info-label">Route Name:</span>
                            <span class="route-info-value">{{ $selectedRouteInfo['name'] }}</span>
                        </div>
                        @if($selectedRouteInfo['controller'] ?? null)
                            <div class="route-info-detail">
                                <span class="route-info-label">Controller:</span>
                                <span class="route-info-value">{{ $selectedRouteInfo['controller'] }}</span>
                            </div>
                        @endif
                        @if(!empty($selectedRouteInfo['middleware']))
                            <div class="route-info-detail">
                                <span class="route-info-label">Middleware:</span>
                                <span class="route-info-value">{{ implode(', ', $selectedRouteInfo['middleware']) }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                @if($hasRequestBody)
                    <div class="form-container">
                        <div class="form-header">
                            Request Parameters
                        </div>
                        <div class="form-body">
                            <form id="previewForm" method="GET" action="{{ route('api-visibility.preview.show', ['routeName' => $selectedRoute]) }}">
                                @if(!empty($selectedRouteInfo['validation_rules']))
                                    @foreach($selectedRouteInfo['validation_rules'] as $field => $rules)
                                        <div class="form-group">
                                            <label class="form-label" for="{{ $field }}">{{ $field }}</label>
                                            <input type="text" id="{{ $field }}" name="{{ $field }}" class="form-input" placeholder="Enter {{ $field }}">
                                            <div class="form-hint">Rules: {{ is_array($rules) ? implode(' | ', $rules) : $rules }}</div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="form-group">
                                        <label class="form-label">Custom Parameters</label>
                                        <div class="form-hint">No validation rules defined. Add parameters as needed:</div>
                                        <div id="dynamic-params" class="dynamic-params">
                                            <div class="dynamic-param-row">
                                                <input type="text" name="param_key[]" class="form-input" placeholder="Parameter name" style="flex: 1;">
                                                <input type="text" name="param_value[]" class="form-input" placeholder="Value" style="flex: 1;">
                                                <button type="button" class="btn btn-outline remove-param" style="display: none;">✕</button>
                                            </div>
                                        </div>
                                        <button type="button" id="add-param" class="btn btn-outline" style="margin-top: 10px;">Add Parameter</button>
                                    </div>
                                @endif
                                <div class="form-actions">
                                    <button type="reset" class="btn btn-outline">Reset</button>
                                    <button type="submit" class="btn btn-primary">Send Request</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                @if(isset($result))
                    <div class="response-container">
                        <div class="response-tabs">
                            <div class="response-tab active" data-tab="response">Response</div>
                            <div class="response-tab" data-tab="headers">Headers</div>
                            <div class="response-tab" data-tab="info">Info</div>
                        </div>

                        <div class="response-body">
                            <div class="response-content active" id="response-tab">
                                <div class="status-bar">
                                    <span class="status-indicator status-{{ substr($result['status'], 0, 1) }}xx">
                                        {{ $result['status'] }}
                                    </span>
                                    <span class="response-time">Response time: <span id="response-time">0</span> ms</span>
                                </div>
                                <pre class="response-data" id="json-response">{{ $result['formatted'] }}</pre>
                            </div>

                            <div class="response-content" id="headers-tab">
                                <ul class="headers-list">
                                    @if(isset($result['headers']))
                                        @foreach($result['headers'] as $name => $values)
                                            <li class="header-item">
                                                <span class="header-name">{{ $name }}:</span>
                                                <span class="header-value">{{ implode(', ', $values) }}</span>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>

                            <div class="response-content" id="info-tab">
                                <ul class="headers-list">
                                    <li class="header-item">
                                        <span class="header-name">Route Name:</span>
                                        <span class="header-value">{{ $selectedRoute }}</span>
                                    </li>
                                    <li class="header-item">
                                        <span class="header-name">URI:</span>
                                        <span class="header-value">{{ $selectedRouteInfo['uri'] }}</span>
                                    </li>
                                    <li class="header-item">
                                        <span class="header-name">Method:</span>
                                        <span class="header-value">{{ implode(', ', $selectedRouteInfo['methods']) }}</span>
                                    </li>
                                    @if($selectedRouteInfo['controller'] ?? null)
                                        <li class="header-item">
                                            <span class="header-name">Controller:</span>
                                            <span class="header-value">{{ $selectedRouteInfo['controller'] }}</span>
                                        </li>
                                    @endif
                                    @if(!empty($selectedRouteInfo['middleware']))
                                        <li class="header-item">
                                            <span class="header-name">Middleware:</span>
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
                        <div class="empty-state-message">Select a route to preview</div>
                        <div class="empty-state-description">
                            Choose a route from the sidebar to see its response preview.
                        </div>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">📋</div>
                    <div class="empty-state-message">No route selected</div>
                    <div class="empty-state-description">
                        Select a route from the sidebar to preview its response.
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab functionality
            const tabs = document.querySelectorAll('.response-tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    tabs.forEach(t => t.classList.remove('active'));
                    // Add active class to clicked tab
                    this.classList.add('active');

                    // Hide all tab contents
                    const tabContents = document.querySelectorAll('.response-content');
                    tabContents.forEach(content => content.classList.remove('active'));

                    // Show the selected tab content
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
                            link.style.display = 'block';
                        } else {
                            link.style.display = 'none';
                        }
                    });

                    // Show/hide group headers based on visible routes
                    document.querySelectorAll('.route-group').forEach(group => {
                        const visibleRoutes = group.querySelectorAll('.route-link[style="display: block"]');
                        if (visibleRoutes.length === 0) {
                            group.style.display = 'none';
                        } else {
                            group.style.display = 'block';
                        }
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
                        <input type="text" name="param_key[]" class="form-input" placeholder="Parameter name" style="flex: 1;">
                        <input type="text" name="param_value[]" class="form-input" placeholder="Value" style="flex: 1;">
                        <button type="button" class="btn btn-outline remove-param">✕</button>
                    `;
                    dynamicParams.appendChild(newRow);

                    // Show remove buttons
                    document.querySelectorAll('.remove-param').forEach(btn => {
                        btn.style.display = 'block';
                    });

                    // Add event listener to remove button
                    newRow.querySelector('.remove-param').addEventListener('click', function() {
                        this.parentElement.remove();

                        // Hide remove button if only one row left
                        const rows = document.querySelectorAll('.dynamic-param-row');
                        if (rows.length === 1) {
                            rows[0].querySelector('.remove-param').style.display = 'none';
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

                    // Apply syntax highlighting
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

            // Set a random response time for demo purposes
            const responseTimeElement = document.getElementById('response-time');
            if (responseTimeElement) {
                responseTimeElement.textContent = Math.floor(Math.random() * 500) + 50;
            }
        });
    </script>
</body>
</html>
