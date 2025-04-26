<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation</title>
    <style>
        :root {
            --primary: #3b82f6;
            --primary-light: #dbeafe;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --info: #06b6d4;
            --dark: #1f2937;
            --gray: #9ca3af;
            --light: #f9fafb;
            --white: #ffffff;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --radius: 6px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
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
            box-shadow: var(--shadow);
            padding: 2rem;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        h1 {
            font-size: 1.8rem;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: var(--gray);
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
        }

        .filters {
            background-color: var(--light);
            padding: 1rem;
            border-radius: var(--radius);
            margin-bottom: 2rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-end;
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
            padding: 0.5rem;
            border: 1px solid #e5e7eb;
            border-radius: var(--radius);
            font-size: 0.9rem;
            background-color: var(--white);
        }

        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px var(--primary-light);
        }

        .reset-btn {
            background-color: var(--light);
            border: 1px solid #e5e7eb;
            color: var(--dark);
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .reset-btn:hover {
            background-color: #e5e7eb;
        }

        .group {
            margin-bottom: 2rem;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .group-title {
            background: var(--primary-light);
            color: var(--primary);
            padding: 0.8rem 1rem;
            border-radius: var(--radius) var(--radius) 0 0;
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
        }

        .get { background-color: var(--info); }
        .post { background-color: var(--success); }
        .put, .patch { background-color: var(--warning); }
        .delete { background-color: var(--danger); }

        .uri-cell {
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
            font-size: 0.85rem;
            word-break: break-all;
        }

        .controller-cell {
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .controller-cell:hover {
            overflow: visible;
            white-space: normal;
            background-color: var(--white);
            position: relative;
            z-index: 1;
        }

        .rules {
            font-family: 'SFMono-Regular', Consolas, 'Liberation Mono', Menlo, monospace;
            background: #f8fafc;
            padding: 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            max-height: 200px;
            overflow-y: auto;
        }

        .rules div {
            margin-bottom: 0.3rem;
        }

        .preview-link {
            display: inline-block;
            margin-left: 0.5rem;
            font-size: 0.75rem;
            color: var(--primary);
            text-decoration: none;
            vertical-align: middle;
        }

        .preview-link:hover {
            text-decoration: underline;
        }

        .auth-badge {
            display: inline-block;
            background-color: var(--primary-light);
            color: var(--primary);
            font-size: 0.7rem;
            padding: 0.2rem 0.4rem;
            border-radius: 4px;
            margin-left: 0.5rem;
            font-weight: 600;
        }

        .no-results {
            padding: 2rem;
            text-align: center;
            color: var(--gray);
            font-style: italic;
        }

        .hidden {
            display: none;
        }

        @media (max-width: 768px) {
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
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div>
                <h1>API Documentation</h1>
                <p class="subtitle">Explore and test your application's API endpoints</p>
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
            <button class="reset-btn" id="reset-filters">Reset Filters</button>
        </div>

        <div id="no-results" class="no-results hidden">
            No routes match your filters. Try adjusting your criteria.
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
                                <th>Validation Rules</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($routes as $route)
                                <tr class="route-row"
                                    data-methods="{{ implode(',', array_filter($route['methods'], function($m) { return $m !== 'HEAD'; })) }}"
                                    data-controller="{{ $route['controller'] ?? 'Closure' }}"
                                    data-uri="{{ $route['uri'] }}"
                                    data-auth="{{ isset($route['middleware']) && in_array('auth', $route['middleware']) ? 'auth' : 'public' }}">
                                    <td class="uri-cell">
                                        {{ $route['uri'] }}
                                        @if(isset($route['middleware']) && in_array('auth', $route['middleware']))
                                            <span class="auth-badge">Auth</span>
                                        @endif
                                        @if(config('api-visibility.enable_preview') && $route['name'])
                                            <a href="{{ route('api-visibility.preview.show', ['routeName' => $route['name']]) }}" class="preview-link">Preview</a>
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
                                        @if(!empty($route['validation_rules']))
                                            <div class="rules">
                                                @foreach($route['validation_rules'] as $field => $rules)
                                                    <div>{{ $field }}: {{ is_array($rules) ? implode('/', $rules) : $rules }}</div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const methodFilter = document.getElementById('method-filter');
            const controllerFilter = document.getElementById('controller-filter');
            const routeFilter = document.getElementById('route-filter');
            const authFilter = document.getElementById('auth-filter');
            const resetButton = document.getElementById('reset-filters');
            const noResults = document.getElementById('no-results');

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

                    if (isVisible) {
                        visibleRows++;
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
