<script>
document.querySelectorAll('[data-tool]').forEach(function (page) {
    const type = page.dataset.tool;
    const result = page.querySelector('#tool-result');
    const get = (name) => page.querySelector('[data-input="' + name + '"]')?.value || 0;
    const money = (value) => 'KES ' + Math.round(value).toLocaleString();
    const button = page.querySelector('[data-calculate]');

    function write(title, text) {
        result.innerHTML = '<strong>' + title + '</strong>' + text;
    }

    const calculators = {
        cost: function () {
            const distance = Number(get('distance'));
            const route = get('route');
            const terminations = Number(get('terminations'));
            const splices = Number(get('splices'));
            const equipment = Number(get('equipment'));
            const labour = get('labour');
            const routeRate = route === 'trenching' ? 850 : route === 'outdoor' ? 260 : 120;
            const labourBase = labour === 'urgent' ? 30000 : labour === 'complex' ? 22000 : 12000;
            const total = (distance * routeRate) + (terminations * 2500) + (splices * 900) + equipment + labourBase;
            write('Estimated range: ' + money(total * 0.9) + ' - ' + money(total * 1.18), 'Use this as a planning estimate only. A site survey should confirm route access, cable type, civil works, and equipment.');
        },
        loss: function () {
            const loss = (Number(get('distance')) * Number(get('attenuation'))) + (Number(get('connectors')) * Number(get('connectorLoss'))) + (Number(get('splices')) * Number(get('spliceLoss'))) + Number(get('margin'));
            const remaining = Number(get('budget')) - loss;
            const status = remaining >= 3 ? 'Healthy margin' : remaining >= 0 ? 'Tight margin' : 'Design likely fails';
            write(status + ': ' + loss.toFixed(2) + ' dB planned loss', 'Remaining optical margin: ' + remaining.toFixed(2) + ' dB. Confirm with power meter and OTDR testing after installation.');
        },
        coverage: function () {
            let score = 45;
            score += get('area') === 'major' ? 30 : get('area') === 'town' ? 20 : get('area') === 'estate' ? 15 : -5;
            score += get('property') === 'business' ? 10 : get('property') === 'estate' ? 6 : 0;
            score += Number(get('road')) <= 100 ? 15 : Number(get('road')) <= 500 ? 4 : -12;
            score += get('duct') === 'yes' ? 12 : get('duct') === 'no' ? -10 : 0;
            const title = score >= 75 ? 'High likelihood of fiber nearby' : score >= 50 ? 'Possible, survey recommended' : 'Likely needs special build or alternative';
            write(title, 'Coverage around ' + get('location') + ' should be confirmed with an address check and route survey before committing to installation timelines.');
        },
        bandwidth: function () {
            const base = (Number(get('users')) * 2) + (Number(get('calls')) * 4) + (Number(get('streams')) * 8) + (Number(get('cloud')) * 6) + (Number(get('cctv')) * 3);
            const recommended = Math.ceil(base * Number(get('reserve')) / 10) * 10;
            write('Recommended package target: ' + recommended + ' Mbps', 'For business sites, also check upload speed, latency, router capacity, access points, and peak-hour contention.');
        },
        color: function () {
            const colors = [
                ['Blue', '#2563eb'], ['Orange', '#f97316'], ['Green', '#16a34a'], ['Brown', '#92400e'],
                ['Slate', '#64748b'], ['White', '#ffffff'], ['Red', '#dc2626'], ['Black', '#111827'],
                ['Yellow', '#facc15'], ['Violet', '#7c3aed'], ['Rose', '#fb7185'], ['Aqua', '#22d3ee']
            ];
            const core = Math.max(1, Number(get('core')));
            const item = colors[(core - 1) % 12];
            const group = Math.ceil(core / 12);
            const chip = page.querySelector('#color-chip');
            chip.hidden = false;
            chip.querySelector('.color-dot').style.background = item[1];
            chip.querySelector('span:last-child').textContent = 'Core ' + core + ': ' + item[0] + ' (group ' + group + ')';
            write('Core colour: ' + item[0], 'This follows the common 12-colour sequence. Confirm with the cable datasheet and site labels before splicing.');
        },
        request: function () {
            const summary = 'Name: ' + get('name') + '<br>Phone: ' + get('phone') + '<br>Location: ' + get('location') + '<br>Site: ' + get('site') + '<br>Service: ' + get('service') + '<br>Urgency: ' + get('urgency') + '<br>Notes: ' + get('notes');
            write('Technician request prepared', summary + '<br><br>Use this summary when requesting a free site survey or technician callback.');
        }
    };

    if (button && calculators[type]) {
        button.addEventListener('click', calculators[type]);
        calculators[type]();
    }

    page.querySelectorAll('[data-wizard] button').forEach(function (item) {
        item.addEventListener('click', function () {
            const advice = {
                'no-link': ['No link checklist', 'Check power, patch cord seating, correct port, SFP compatibility, provider status, and visible cable damage. If the link stays down, request optical power testing.'],
                'slow': ['Slow speed checklist', 'Test using Ethernet, bypass Wi-Fi, check router CPU, stop backups, compare upload and download, then test optical levels if wired speed is still poor.'],
                'intermittent': ['Intermittent link checklist', 'Look for tight bends, loose patch cords, dirty connectors, failing power adapters, overheating equipment, and recent construction near the cable route.'],
                'wifi': ['Wi-Fi checklist', 'Fiber may be fine. Check access point placement, channel congestion, router location, mesh backhaul, and whether users are on 2.4 GHz or 5 GHz.'],
                'damage': ['Possible physical damage', 'Avoid bending or opening fiber closures. Record the location, take photos, and request a technician with splicing and testing tools.']
            }[item.dataset.issue];
            write(advice[0], advice[1]);
        });
    });
});
</script>
