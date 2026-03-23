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
