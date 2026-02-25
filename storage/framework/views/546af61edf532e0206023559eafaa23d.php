<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tenant Ops Console</title>
    <style>
        :root {
            color-scheme: dark;
            --bg: #0a1020;
            --panel: #11192d;
            --border: #283656;
            --text: #e8eefc;
            --muted: #9fb0cb;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Inter, ui-sans-serif, system-ui, sans-serif;
            background: linear-gradient(180deg, #070d19 0%, var(--bg) 100%);
            color: var(--text);
        }
        .shell {
            max-width: 1220px;
            margin: 0 auto;
            padding: 40px 24px 72px;
        }
        h1 {
            margin: 0 0 12px;
            font-size: clamp(2.2rem, 4vw, 3.3rem);
        }
        .lede {
            margin: 0 0 30px;
            max-width: 760px;
            color: var(--muted);
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 16px;
            margin-bottom: 28px;
        }
        .stat, .panel {
            background: rgba(17, 25, 45, 0.92);
            border: 1px solid var(--border);
            border-radius: 18px;
        }
        .stat { padding: 18px 20px; }
        .stat strong {
            display: block;
            margin-top: 8px;
            font-size: 1.9rem;
        }
        .stat span, .muted { color: var(--muted); }
        .grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }
        .panel { padding: 20px; }
        .tenant + .tenant {
            margin-top: 18px;
            padding-top: 18px;
            border-top: 1px solid rgba(159, 176, 203, 0.18);
        }
        .top {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: start;
        }
        h2 {
            margin: 0 0 16px;
            font-size: 1.2rem;
        }
        h3 {
            margin: 0 0 6px;
            font-size: 1.05rem;
        }
        .pill {
            white-space: nowrap;
            padding: 6px 10px;
            border-radius: 999px;
            border: 1px solid #3b82f6;
            background: rgba(59, 130, 246, 0.14);
            text-transform: capitalize;
            font-size: 0.82rem;
            font-weight: 700;
        }
        .pill[data-status="attention"], .pill[data-status="warn"] {
            border-color: #f59e0b;
            background: rgba(245, 158, 11, 0.15);
        }
        .pill[data-status="paused"], .pill[data-status="fail"] {
            border-color: #ef4444;
            background: rgba(239, 68, 68, 0.16);
        }
        .pill[data-status="active"], .pill[data-status="ok"] {
            border-color: #10b981;
            background: rgba(16, 185, 129, 0.14);
        }
        .meta {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 12px 0;
        }
        .meta span {
            padding: 7px 10px;
            border-radius: 10px;
            background: rgba(255,255,255,0.04);
            color: var(--muted);
            font-size: 0.92rem;
        }
        ul {
            margin: 12px 0 0;
            padding-left: 18px;
        }
        li + li {
            margin-top: 10px;
        }
        @media (max-width: 920px) {
            .grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <main class="shell">
        <h1>Tenant Ops Console</h1>
        <p class="lede">
            Operator-facing starter for SaaS tenant oversight. This first slice tracks tenant account state,
            renewal timing, and service checks that need intervention.
        </p>

        <section class="stats">
            <?php $__currentLoopData = $tenantStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <article class="stat">
                    <span><?php echo e($status); ?> tenants</span>
                    <strong><?php echo e($tenantStatusCounts[$status] ?? 0); ?></strong>
                </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <article class="stat">
                <span>Healthy checks</span>
                <strong><?php echo e($checkStatusCounts['ok'] ?? 0); ?></strong>
            </article>
            <article class="stat">
                <span>Warnings</span>
                <strong><?php echo e($checkStatusCounts['warn'] ?? 0); ?></strong>
            </article>
            <article class="stat">
                <span>Failures</span>
                <strong><?php echo e($checkStatusCounts['fail'] ?? 0); ?></strong>
            </article>
        </section>

        <section class="grid">
            <div class="panel">
                <h2>Tenant accounts</h2>
                <?php $__currentLoopData = $tenants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tenant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <article class="tenant">
                        <div class="top">
                            <div>
                                <h3><?php echo e($tenant->name); ?></h3>
                                <div class="muted"><?php echo e($tenant->plan_name); ?> plan · owner <?php echo e($tenant->owner_name); ?></div>
                            </div>
                            <span class="pill" data-status="<?php echo e($tenant->status); ?>"><?php echo e($tenant->status); ?></span>
                        </div>

                        <div class="meta">
                            <span>Region <?php echo e($tenant->region); ?></span>
                            <span><?php echo e($tenant->seat_count); ?> seats</span>
                            <span>Renewal <?php echo e(optional($tenant->renewal_at)->format('Y-m-d') ?? 'n/a'); ?></span>
                            <span>Last incident <?php echo e(optional($tenant->last_incident_at)->diffForHumans() ?? 'none'); ?></span>
                        </div>

                        <ul>
                            <?php $__currentLoopData = $tenant->serviceChecks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $check): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li>
                                    <strong><?php echo e($check->service_name); ?></strong>
                                    <span class="pill" data-status="<?php echo e($check->status); ?>"><?php echo e($check->status); ?></span>
                                    <div class="muted"><?php echo e($check->message); ?></div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <aside class="panel">
                <h2>Active alerts</h2>
                <?php $__currentLoopData = $alerts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <article class="tenant">
                        <div class="top">
                            <div>
                                <h3><?php echo e($alert->tenant?->name); ?></h3>
                                <div class="muted"><?php echo e($alert->service_name); ?></div>
                            </div>
                            <span class="pill" data-status="<?php echo e($alert->status); ?>"><?php echo e($alert->status); ?></span>
                        </div>
                        <div class="meta">
                            <span><?php echo e($alert->checked_at?->format('Y-m-d H:i')); ?></span>
                        </div>
                        <div class="muted"><?php echo e($alert->message); ?></div>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </aside>
        </section>
    </main>
</body>
</html>
<?php /**PATH /home/aat/tenant_ops_console/resources/views/dashboard.blade.php ENDPATH**/ ?>