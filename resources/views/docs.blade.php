<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>API Documentation</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;font-size:13px;background:#0d1117;color:#e6edf3;min-height:100vh}
a{color:inherit;text-decoration:none}
.topbar{display:flex;align-items:center;justify-content:space-between;padding:10px 20px;background:#161b22;border-bottom:1px solid #30363d;position:sticky;top:0;z-index:100}
.topbar-left{display:flex;align-items:center;gap:16px}
.topbar h1{font-size:14px;font-weight:600;color:#e6edf3}
.stats{display:flex;gap:8px}
.stat{font-size:11px;padding:2px 8px;border-radius:4px;background:#1c2128;border:1px solid #30363d;color:#8b949e}
.stat span{color:#e6edf3;font-weight:600}
.filters{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
.filters input,.filters select{background:#0d1117;border:1px solid #30363d;border-radius:6px;padding:5px 10px;color:#e6edf3;font-size:12px;outline:none;height:30px}
.filters input:focus,.filters select:focus{border-color:#388bfd}
.filters input{width:160px}
.filters select{width:120px}
.filters button{background:#21262d;border:1px solid #30363d;color:#8b949e;padding:5px 10px;border-radius:6px;font-size:12px;cursor:pointer;height:30px}
.filters button:hover{border-color:#8b949e;color:#e6edf3}
.content{padding:20px}
.group{margin-bottom:16px;border:1px solid #30363d;border-radius:8px;overflow:hidden}
.group-header{padding:8px 16px;background:#1c2128;border-bottom:1px solid #30363d;display:flex;align-items:center;justify-content:space-between}
.group-title{font-size:12px;font-weight:600;color:#8b949e;font-family:monospace}
.group-count{font-size:10px;font-weight:700;padding:1px 7px;border-radius:10px;background:#30363d;color:#8b949e}
table{width:100%;border-collapse:collapse}
th{text-align:left;padding:7px 14px;background:#161b22;color:#8b949e;font-weight:600;font-size:10px;text-transform:uppercase;letter-spacing:.5px;border-bottom:1px solid #30363d}
td{padding:8px 14px;border-bottom:1px solid #21262d;vertical-align:middle;font-size:12px}
tr:last-child td{border-bottom:none}
tr.route-row:hover td{background:#1c2128}
.uri{font-family:monospace;color:#e6edf3;word-break:break-all}
.ctrl{color:#8b949e;font-family:monospace;font-size:11px;max-width:280px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.badge{display:inline-block;font-size:10px;font-weight:700;padding:2px 6px;border-radius:4px;font-family:monospace;color:#fff;margin-right:3px}
.GET{background:#1f6feb}.POST{background:#238636}.PUT,.PATCH{background:#9e6a03}.DELETE{background:#da3633}
.auth-tag{display:inline-block;font-size:10px;padding:1px 6px;border-radius:3px;background:#3d1f1f;color:#f85149;border:1px solid #6e2020;font-family:monospace;margin-left:6px}
.actions{display:flex;gap:6px;align-items:center;white-space:nowrap}
.btn-preview{font-size:11px;padding:3px 10px;border-radius:5px;background:#1f3a5f;border:1px solid #1f6feb;color:#79c0ff;cursor:pointer}
.btn-preview:hover{background:#1f6feb;color:#fff}
.btn-copy{font-size:11px;padding:3px 10px;border-radius:5px;background:#21262d;border:1px solid #30363d;color:#8b949e;cursor:pointer}
.btn-copy:hover{background:#30363d;color:#e6edf3}
.btn-copy.copied{color:#3fb950;border-color:#1f4a1f}
.btn-export{font-size:11px;padding:4px 12px;border-radius:5px;background:#1a2f1a;border:1px solid #238636;color:#3fb950;cursor:pointer;display:flex;align-items:center;gap:5px;font-weight:600}
.btn-export:hover{background:#238636;color:#fff}
.no-results{text-align:center;padding:60px 20px;color:#8b949e;font-size:13px}
.hidden{display:none!important}
::-webkit-scrollbar{width:6px;height:6px}
::-webkit-scrollbar-track{background:#0d1117}
::-webkit-scrollbar-thumb{background:#30363d;border-radius:3px}
</style>
</head>
<body>

@php
    $totalRoutes  = count($routes ?? []);
    $methodCounts = [];
    $authCount    = 0;
    foreach ($routes ?? [] as $route) {
        foreach ($route['methods'] as $m) {
            if ($m !== 'HEAD') $methodCounts[$m] = ($methodCounts[$m] ?? 0) + 1;
        }
        if (!empty($route['middleware']) && collect($route['middleware'])->contains(fn($x) => str_contains($x, 'auth'))) {
            $authCount++;
        }
    }
@endphp

<div class="topbar">
    <div class="topbar-left">
        <h1>API Docs</h1>
        <div class="stats">
            <div class="stat"><span>{{ $totalRoutes }}</span> routes</div>
            @foreach(['GET','POST','PUT','PATCH','DELETE'] as $m)
                @if(!empty($methodCounts[$m]))
                    <div class="stat"><span>{{ $methodCounts[$m] }}</span> {{ $m }}</div>
                @endif
            @endforeach
            @if($authCount)
                <div class="stat"><span>{{ $authCount }}</span> auth</div>
            @endif
        </div>
    </div>
    <div class="filters">
        <input type="text" id="f-route" placeholder="Filter route…">
        <input type="text" id="f-ctrl" placeholder="Filter controller…">
        <select id="f-method">
            <option value="">All methods</option>
            @foreach(['GET','POST','PUT','PATCH','DELETE'] as $m)
                <option>{{ $m }}</option>
            @endforeach
        </select>
        <select id="f-auth">
            <option value="">All</option>
            <option value="auth">Auth only</option>
            <option value="public">Public only</option>
        </select>
        <button id="f-reset">Reset</button>
        <button class="btn-export" id="btn-export">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Export JSON
        </button>
    </div>
</div>

<div class="content">
    <div id="no-results" class="no-results hidden">No routes match your filters.</div>

    @foreach($groupedRoutes as $prefix => $groupRoutes)
    <div class="group" data-group>
        <div class="group-header">
            <span class="group-title">{{ $prefix ?: '/' }}</span>
            <span class="group-count" data-count>{{ count($groupRoutes) }}</span>
        </div>
        <table>
            <thead>
                <tr>
                    <th style="width:80px">Method</th>
                    <th>URI</th>
                    <th>Controller</th>
                    <th style="width:140px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groupRoutes as $route)
                @php
                    $methods    = array_filter($route['methods'], fn($m) => $m !== 'HEAD');
                    $isAuth     = !empty($route['middleware']) && collect($route['middleware'])->contains(fn($x) => str_contains($x, 'auth'));
                    $methodStr  = implode(',', $methods);
                @endphp
                <tr class="route-row"
                    data-methods="{{ $methodStr }}"
                    data-uri="{{ $route['uri'] }}"
                    data-ctrl="{{ $route['controller'] ?? '' }}"
                    data-auth="{{ $isAuth ? 'auth' : 'public' }}">
                    <td>
                        @foreach($methods as $m)
                            <span class="badge {{ $m }}">{{ $m }}</span>
                        @endforeach
                    </td>
                    <td>
                        <span class="uri">{{ $route['uri'] }}</span>
                        @if($isAuth)<span class="auth-tag">auth</span>@endif
                    </td>
                    <td><span class="ctrl" title="{{ $route['controller'] ?? '' }}">{{ $route['controller'] ?? '—' }}</span></td>
                    <td>
                        <div class="actions">
                            @if(config('api-visibility.enable_preview') && !empty($route['name']))
                                <a href="{{ route('api-visibility.preview.show', ['routeName' => $route['name']]) }}" class="btn-preview">Details</a>
                            @endif
                            <button class="btn-copy" data-url="{{ url($route['uri']) }}">Copy URL</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach
</div>

<script>
// ── Export data (server-rendered) ────────────────────────────────────────────
const EXPORT_META = {
    appName:   {{ Js::from(config('app.name', 'Laravel')) }},
    appUrl:    {{ Js::from(config('app.url', url('/'))) }},
    appEnv:    {{ Js::from(config('app.env', 'local')) }},
    exportedAt: new Date().toISOString(),
};

const EXPORT_ROUTES = {{ Js::from(collect($routes ?? [])->map(function($route) {
    $methods = array_values(array_filter($route['methods'] ?? [], fn($m) => $m !== 'HEAD'));
    $isAuth  = !empty($route['middleware']) && collect($route['middleware'])->contains(fn($x) => str_contains($x, 'auth'));
    $rules   = $route['validation_rules'] ?? [];

    // Build request body fields from validation rules
    $fields = [];
    foreach ($rules as $field => $ruleList) {
        $ruleList = is_array($ruleList) ? $ruleList : explode('|', $ruleList);
        $ruleList = array_map(fn($r) => is_object($r) ? class_basename(get_class($r)) : (string)$r, $ruleList);
        $fields[$field] = [
            'rules'    => $ruleList,
            'required' => in_array('required', $ruleList),
        ];
    }

    // Extract URI path params
    preg_match_all('/\{(\w+?)(\?)?\}/', $route['uri'] ?? '', $m);
    $pathParams = [];
    foreach ($m[1] as $i => $pname) {
        $pathParams[] = ['name' => $pname, 'required' => ($m[2][$i] ?? '') !== '?'];
    }

    return [
        'name'        => $route['name'] ?? null,
        'uri'         => $route['uri'] ?? '',
        'methods'     => $methods,
        'controller'  => $route['controller'] ?? null,
        'middleware'  => $route['middleware'] ?? [],
        'auth'        => $isAuth,
        'fields'      => $fields,
        'path_params' => $pathParams,
    ];
})->values()) }};

// ── Postman Collection v2.1 builder ──────────────────────────────────────────
function buildPostmanCollection() {
    const baseUrl = EXPORT_META.appUrl.replace(/\/$/, '');
    const urlObj  = new URL(baseUrl);
    const host    = urlObj.hostname.split('.');
    const proto   = urlObj.protocol.replace(':', '');

    // Group routes by first URI segment (mirrors the docs grouping)
    const groups = {};
    EXPORT_ROUTES.forEach(route => {
        const seg = route.uri.split('/')[0] || '/';
        if (!groups[seg]) groups[seg] = [];
        groups[seg].push(route);
    });

    const folders = Object.entries(groups).map(([folder, routes]) => ({
        name: folder,
        item: routes.map(route => {
            const method  = route.methods[0] || 'GET';
            const isBody  = ['POST', 'PUT', 'PATCH'].includes(method);
            const hasAuth = route.auth;

            // Build URL path segments, converting {param} → :param (Postman style)
            const rawPath = route.uri.replace(/\{(\w+?)\??}/g, ':$1');
            const pathSegs = rawPath.split('/').filter(Boolean);

            // Path variables
            const pathVars = route.path_params.map(p => ({
                key:         p.name,
                value:       '',
                description: p.required ? 'required' : 'optional',
            }));

            // Request body (raw JSON)
            let body = null;
            if (isBody && Object.keys(route.fields).length > 0) {
                const example = {};
                Object.entries(route.fields).forEach(([field, info]) => {
                    example[field] = guessExampleValue(field, info.rules);
                });
                body = {
                    mode: 'raw',
                    raw:  JSON.stringify(example, null, 2),
                    options: { raw: { language: 'json' } },
                };
            }

            // Auth header
            const headers = [
                { key: 'Accept',       value: 'application/json', type: 'text' },
                { key: 'Content-Type', value: 'application/json', type: 'text' },
            ];
            if (hasAuth) {
                headers.push({ key: 'Authorization', value: 'Bearer @{{token}}', type: 'text' });
            }

            // Build description
            const descParts = [];
            if (route.controller) descParts.push(`Controller: ${route.controller}`);
            if (route.middleware.length) descParts.push(`Middleware: ${route.middleware.join(', ')}`);
            if (hasAuth) descParts.push('Authentication: Required (Bearer token)');
            if (Object.keys(route.fields).length) {
                descParts.push('\nRequest Fields:');
                Object.entries(route.fields).forEach(([f, info]) => {
                    descParts.push(`  - ${f}: ${info.rules.join(', ')}`);
                });
            }

            return {
                name: route.name || `${method} /${route.uri}`,
                request: {
                    method,
                    header: headers,
                    body:   body || undefined,
                    url: {
                        raw:      `${baseUrl}/${rawPath}`,
                        protocol: proto,
                        host,
                        path:     pathSegs,
                        variable: pathVars.length ? pathVars : undefined,
                    },
                    description: descParts.join('\n'),
                    auth: hasAuth ? {
                        type:   'bearer',
                        bearer: [{ key: 'token', value: '@{{token}}', type: 'string' }],
                    } : undefined,
                },
                response: [],
            };
        }),
    }));

    return {
        info: {
            name:        `${EXPORT_META.appName} — API Collection`,
            description: `Auto-generated by Laravel API Visibility\nApp: ${EXPORT_META.appUrl}\nEnvironment: ${EXPORT_META.appEnv}\nExported: ${EXPORT_META.exportedAt}`,
            schema:      'https://schema.getpostman.com/json/collection/v2.1.0/collection.json',
            version:     '2.1.0',
        },
        variable: [
            { key: 'baseUrl', value: baseUrl,  type: 'string' },
            { key: 'token',   value: '',        type: 'string', description: 'Bearer auth token' },
        ],
        item: folders,
    };
}

// ── Example value heuristics (mirrors PHP ResponseStructureExtractor) ─────────
function guessExampleValue(field, rules) {
    const f = field.toLowerCase();
    if (f.includes('email'))    return 'user@example.com';
    if (f.includes('password')) return 'Secret123!';
    if (f === 'name' || f.endsWith('_name')) return 'John Doe';
    if (f.includes('phone'))    return '+1234567890';
    if (f.includes('url'))      return 'https://example.com';
    if (f.includes('token'))    return 'your-token-here';
    if (f === 'id' || f.endsWith('_id')) return 1;
    if (f.includes('date'))     return new Date().toISOString().split('T')[0];
    if (f.includes('amount') || f.includes('price')) return 99.99;
    for (const rule of rules) {
        if (rule.startsWith('in:')) return rule.split(':')[1].split(',')[0].trim();
        if (rule === 'boolean')     return true;
        if (rule === 'integer' || rule === 'numeric') return 1;
        if (rule === 'array')       return [];
    }
    return `example_${field}`;
}

// ── Download trigger ──────────────────────────────────────────────────────────
document.getElementById('btn-export').addEventListener('click', () => {
    const collection = buildPostmanCollection();
    const json       = JSON.stringify(collection, null, 2);
    const blob       = new Blob([json], { type: 'application/json' });
    const url        = URL.createObjectURL(blob);
    const a          = document.createElement('a');
    const appSlug    = EXPORT_META.appName.toLowerCase().replace(/\s+/g, '-');
    a.href           = url;
    a.download       = `${appSlug}-api-collection.json`;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);

    const btn  = document.getElementById('btn-export');
    const orig = btn.innerHTML;
    btn.innerHTML = '✓ Exported!';
    btn.style.background = '#238636';
    btn.style.color = '#fff';
    setTimeout(() => { btn.innerHTML = orig; btn.style.background = ''; btn.style.color = ''; }, 2000);
});

// ── Filters ───────────────────────────────────────────────────────────────────
const fRoute  = document.getElementById('f-route');
const fCtrl   = document.getElementById('f-ctrl');
const fMethod = document.getElementById('f-method');
const fAuth   = document.getElementById('f-auth');
const noRes   = document.getElementById('no-results');

function applyFilters() {
    const route  = fRoute.value.toLowerCase();
    const ctrl   = fCtrl.value.toLowerCase();
    const method = fMethod.value.toUpperCase();
    const auth   = fAuth.value;

    let total = 0;
    document.querySelectorAll('[data-group]').forEach(group => {
        let visible = 0;
        group.querySelectorAll('.route-row').forEach(row => {
            const ok =
                (!route  || row.dataset.uri.toLowerCase().includes(route)) &&
                (!ctrl   || row.dataset.ctrl.toLowerCase().includes(ctrl)) &&
                (!method || row.dataset.methods.split(',').includes(method)) &&
                (!auth   || row.dataset.auth === auth);
            row.classList.toggle('hidden', !ok);
            if (ok) visible++;
        });
        group.classList.toggle('hidden', visible === 0);
        const cnt = group.querySelector('[data-count]');
        if (cnt) cnt.textContent = visible;
        total += visible;
    });
    noRes.classList.toggle('hidden', total > 0);
}

[fRoute, fCtrl, fMethod, fAuth].forEach(el => el.addEventListener('input', applyFilters));
document.getElementById('f-reset').addEventListener('click', () => {
    [fRoute, fCtrl].forEach(el => el.value = '');
    [fMethod, fAuth].forEach(el => el.selectedIndex = 0);
    applyFilters();
});

document.addEventListener('click', function(e) {
    const btn = e.target.closest('.btn-copy');
    if (!btn) return;
    const url = btn.dataset.url;
    navigator.clipboard.writeText(url).catch(() => {
        const ta = document.createElement('textarea');
        ta.value = url; document.body.appendChild(ta); ta.select();
        document.execCommand('copy'); document.body.removeChild(ta);
    });
    const orig = btn.textContent;
    btn.textContent = 'Copied!'; btn.classList.add('copied');
    setTimeout(() => { btn.textContent = orig; btn.classList.remove('copied'); }, 1500);
});
</script>
</body>
</html>
