<?php

namespace App\Http\Controllers;

class FiberToolController extends Controller
{
    public function show(string $slug)
    {
        $tools = $this->tools();
        abort_unless(isset($tools[$slug]), 404);

        $tool = $tools[$slug];
        $related = collect($tools)
            ->except($slug)
            ->take(4)
            ->map(fn ($item, $itemSlug) => [
                'title' => $item['title'],
                'slug' => $itemSlug,
                'summary' => $item['summary'],
            ])
            ->values()
            ->all();

        return view('tools.show', [
            'tool' => $tool,
            'slug' => $slug,
            'related' => $related,
            'tools' => $tools,
        ]);
    }

    public static function slugs(): array
    {
        return array_keys((new self())->tools());
    }

    public static function links(): array
    {
        return collect((new self())->tools())
            ->map(fn ($tool, $slug) => [
                'slug' => $slug,
                'title' => $tool['title'],
                'summary' => $tool['summary'],
            ])
            ->values()
            ->all();
    }

    private function tools(): array
    {
        $sharedCta = [
            'title' => 'Need Fiber Installation in Kenya?',
            'text' => 'Request a free site survey from Fiber Optics Kenya. We can review your route, distance, termination points, expected speed, equipment list, and installation budget before work starts.',
            'button' => 'Request a Free Site Survey',
        ];

        return [
            'fiber-installation-cost-calculator' => [
                'type' => 'cost',
                'title' => 'Fiber Installation Cost Calculator',
                'meta' => 'Estimate fiber installation costs in Kenya for homes, offices, estates, CCTV networks, and business sites.',
                'summary' => 'Estimate cable, trenching, ducting, termination, equipment, and labour costs before requesting a fiber site survey.',
                'cta' => $sharedCta,
                'examples' => [
                    'A 60 metre office link with indoor trunking, two terminations, and standard testing may fall into a modest budget if no civil works are required.',
                    'A 300 metre estate link with outdoor duct, pulling, splicing, and ODF termination will usually need a site survey because civil works dominate the final price.',
                ],
                'faqs' => [
                    ['q' => 'Is this a final quotation?', 'a' => 'No. It is a planning estimate. Final pricing depends on route survey, cable type, duct availability, splicing count, access, and equipment requirements.'],
                    ['q' => 'What usually increases fiber installation cost?', 'a' => 'Long distance, trenching, wayleave constraints, armoured cable, many splice points, difficult access, and after-hours installation increase cost.'],
                    ['q' => 'Can I use existing conduit?', 'a' => 'Yes, if it is clear, accessible, correctly sized, and safe for pulling fiber without damaging the cable.'],
                ],
                'content' => [
                    ['h2' => 'How the Fiber Installation Cost Calculator Works', 'p' => [
                        'Fiber installation cost in Kenya is not determined by cable length alone. The practical budget is shaped by route distance, cable type, ducting, trenching, wall penetrations, poles, splicing, patch panels, media converters, switches, labour, testing, and documentation. A short link inside an office can be affordable because the route is clean and easy to access. A longer outdoor link can cost much more because the team may need armoured cable, protective duct, civil works, and extra labour.',
                        'This calculator gives a planning estimate before you call a technician. It separates the major cost drivers so homeowners, IT managers, contractors, landlords, cyber cafes, schools, hotels, warehouses, and SMEs can understand what affects the quote. The result is not meant to replace a site survey, but it helps you ask better questions and compare quotations more confidently.',
                    ]],
                    ['h2' => 'What to Include in a Fiber Installation Budget', 'p' => [
                        'A complete fiber budget should include the optical cable, route protection, termination accessories, splice sleeves, pigtails, patch cords, ODF or wall boxes, active devices where needed, labour, testing, and transport. If the installation crosses outdoor areas, the quote may also need ducting, trenching, backfilling, warning tape, draw wire, inspection chambers, poles, or aerial support hardware. These items are often missed in quick estimates, which is why cheap quotes can become expensive during installation.',
                        'The most reliable approach is to identify the route first. Measure the approximate distance, confirm whether the route is indoor or outdoor, count the termination points, note where switches or routers will sit, and decide whether the link is for internet, LAN backbone, CCTV, inter-building connectivity, or ISP distribution. Each use case can change the cable core count and the termination method.',
                    ]],
                    ['h2' => 'Worked Example', 'p' => [
                        'Suppose a business in Nairobi wants to connect an office router room to a second office block 120 metres away. The route includes 40 metres indoor trunking, 80 metres outdoor duct, two termination points, and basic testing. The calculator combines the cable cost, outdoor protection, termination, labour, and testing allowance. If trenching is already available, the estimate stays moderate. If a new trench must be opened, the civil works may become the biggest line item.',
                        'For a home installation, the main variables may be shorter: cable route from the entry point to the router area, neat trunking, wall clips, patch cord, termination box, and labour. For an estate or campus, the estimate should be treated as an early planning figure only because wayleave, ducts, chambers, route protection, and documentation become more important.',
                    ]],
                ],
            ],
            'fiber-optic-loss-budget-calculator' => [
                'type' => 'loss',
                'title' => 'Fiber Optic Loss Budget Calculator',
                'meta' => 'Calculate estimated fiber optic loss budget for single-mode and multimode links using distance, splices, connectors, and safety margin.',
                'summary' => 'Check whether a fiber link has enough optical power after cable attenuation, connectors, splices, and safety margin.',
                'cta' => $sharedCta,
                'examples' => [
                    'A 2 km single-mode link with two connectors and four splices may pass easily if the optics have a healthy power budget.',
                    'A high-splice route can fail even when the cable distance looks acceptable because every splice and connector adds loss.',
                ],
                'faqs' => [
                    ['q' => 'What is a fiber loss budget?', 'a' => 'It is the maximum optical loss a link can tolerate while still allowing the transmitter and receiver to communicate reliably.'],
                    ['q' => 'What loss values should I use?', 'a' => 'Use manufacturer values where available. Common planning values are about 0.35 dB/km for single-mode at 1310 nm, 0.25 dB/km at 1550 nm, 0.3 dB per connector, and 0.1 dB per fusion splice.'],
                    ['q' => 'Does this replace OTDR testing?', 'a' => 'No. It is a design estimate. OTDR and light-source/power-meter testing confirm the real installed link.'],
                ],
                'content' => [
                    ['h2' => 'Why Loss Budget Matters', 'p' => [
                        'A fiber link can look correct physically and still perform poorly if the optical loss is too high. Every kilometre of cable, connector, splice, patch panel, and bend contributes some attenuation. The receiver needs enough light to decode the signal. If the received power is too low, users may see intermittent drops, unstable links, low throughput, CRC errors, or complete link failure.',
                        'The loss budget calculator helps network designers, ISP technicians, installers, CCTV integrators, and IT managers estimate whether a planned route is realistic before buying optics or installing cable. It is especially useful for long campus links, inter-building links, estate distribution, GPON planning, and business backbones.',
                    ]],
                    ['h2' => 'How to Read the Result', 'p' => [
                        'The calculator adds cable attenuation, connector loss, splice loss, and a safety margin. It then compares the expected loss with the optical module power budget. If the margin is positive, the design has room for real-world variation. If the margin is close to zero, the link may work on paper but fail after patching, bending, dirt, or future repairs. If the margin is negative, you should reduce loss or use optics with a higher budget.',
                        'Good design includes reserve margin. Fiber links age, connectors get dirty, patch cords change, and routes may be repaired later. A design with no reserve is fragile. For business-critical links, a site survey and proper test report should be part of the installation package.',
                    ]],
                    ['h2' => 'Worked Example', 'p' => [
                        'Consider a 5 km single-mode link at 1310 nm with four connectors, six fusion splices, and a 3 dB safety margin. Cable loss at 0.35 dB/km is 1.75 dB. Connector loss at 0.3 dB each is 1.2 dB. Splice loss at 0.1 dB each is 0.6 dB. Total planned loss is 6.55 dB after adding safety margin. If the optics support 10 dB, the remaining margin is 3.45 dB, which is workable for planning.',
                    ]],
                ],
            ],
            'fiber-coverage-checker-kenya' => [
                'type' => 'coverage',
                'title' => 'Fiber Coverage Checker Kenya',
                'meta' => 'Check fiber availability planning factors in Kenya by county, town, property type, distance to road, and installation urgency.',
                'summary' => 'Estimate whether fiber installation is likely, needs a survey, or may require wireless or dedicated build-out alternatives.',
                'cta' => $sharedCta,
                'examples' => [
                    'A business on a main road in Nairobi or Mombasa is more likely to have nearby fiber infrastructure than a remote property far from duct routes.',
                    'A gated estate may have coverage nearby but still need internal wayleave, duct inspection, and estate management approval.',
                ],
                'faqs' => [
                    ['q' => 'Can this confirm exact ISP coverage?', 'a' => 'No. It estimates likelihood. Exact coverage requires address checks, provider maps, route survey, and sometimes physical inspection.'],
                    ['q' => 'What if fiber is not nearby?', 'a' => 'Options include dedicated build-out, microwave, 4G/5G, satellite, or waiting for provider expansion.'],
                    ['q' => 'Why does distance from the road matter?', 'a' => 'Long private access routes can require extra ducting, trenching, permissions, and cable, even when the main road has fiber.'],
                ],
                'content' => [
                    ['h2' => 'Understanding Fiber Coverage in Kenya', 'p' => [
                        'Fiber coverage in Kenya is strongest in major towns, business districts, estates, apartment blocks, schools, hospitals, malls, industrial areas, and along major roads where providers have existing backbone or last-mile infrastructure. Coverage becomes less predictable in rural areas, new developments, private roads, farms, and sites far from ducts or poles.',
                        'The Fiber Coverage Checker is designed as a planning tool. It asks for location type, county, property type, distance from the nearest main road, and urgency. The result helps you decide whether to request a normal installation, a site survey, a dedicated build quote, or an alternative connectivity option while fiber is being planned.',
                    ]],
                    ['h2' => 'Why Coverage Is Not Only About Town Name', 'p' => [
                        'Two customers in the same town can have very different installation outcomes. One building may sit beside a provider duct, while another may be several hundred metres away behind private land, railway reserve, a river crossing, or an estate road with no draw pipe. Apartment blocks may already have risers and termination points, while standalone homes may need a new drop cable route.',
                        'Coverage also depends on permissions. Landlords, estate managers, county authorities, road agencies, and utility owners can affect timelines. A provider may have fiber nearby but still require wayleave, duct clearance, pole access, or trenching approval before installation.',
                    ]],
                    ['h2' => 'Worked Example', 'p' => [
                        'A small office in Westlands, Nairobi, located on a serviced commercial road, is likely to have good coverage options. A warehouse on the edge of an industrial zone may need a survey to confirm the nearest chamber or pole. A home 800 metres from the main road may need a special build, even if neighbours near the road already have fiber.',
                    ]],
                ],
            ],
            'fiber-bandwidth-calculator' => [
                'type' => 'bandwidth',
                'title' => 'Fiber Bandwidth Calculator',
                'meta' => 'Estimate recommended fiber internet bandwidth for homes, offices, CCTV, VoIP, cloud apps, and business users in Kenya.',
                'summary' => 'Calculate a practical bandwidth target based on users, video calls, streaming, cloud work, CCTV upload, and reserve capacity.',
                'cta' => $sharedCta,
                'examples' => [
                    'A 20-user office using cloud apps and video meetings may need more stable bandwidth than a home with the same peak download usage.',
                    'CCTV upload, backups, and VoIP should be counted separately because they affect upstream performance and user experience.',
                ],
                'faqs' => [
                    ['q' => 'Is download speed enough?', 'a' => 'No. Upload speed, latency, jitter, contention, Wi-Fi quality, and router capacity also affect performance.'],
                    ['q' => 'How much reserve should I add?', 'a' => 'For business use, 25% to 40% reserve is sensible so the link remains stable during peak usage.'],
                    ['q' => 'Can fiber solve bad Wi-Fi?', 'a' => 'Fiber improves the internet link, but poor router placement, weak access points, and congested Wi-Fi still need local network fixes.'],
                ],
                'content' => [
                    ['h2' => 'Choosing the Right Fiber Bandwidth', 'p' => [
                        'The best fiber package is not always the highest advertised speed. A good package matches the number of users, the applications they run, the upload requirement, and the quality of the local network. A home that streams, games, and works remotely needs stable latency and enough bandwidth for peak hours. A business needs consistent performance for cloud systems, video calls, VoIP, POS, backups, and shared files.',
                        'This calculator estimates a recommended bandwidth target by combining common usage categories. It adds normal browsing, video meetings, streaming, cloud work, CCTV upload, VoIP, and a reserve margin. The result helps you compare packages and avoid buying too little or overpaying for capacity that the local network cannot use.',
                    ]],
                    ['h2' => 'Bandwidth Planning for Business Sites', 'p' => [
                        'Business bandwidth should be planned around workflows, not only headcount. Ten users doing email and browsing need far less than ten users working with cloud design files, video meetings, and remote desktops. CCTV also matters because cameras upload continuously to NVRs, cloud storage, or remote monitoring stations. Backups can saturate a link if they run during working hours.',
                        'For offices, a reserve margin is important. Without reserve, users may experience slow systems whenever meetings, downloads, and backups overlap. A properly designed network also includes traffic shaping, good access points, gigabit switching, and router capacity that matches the internet package.',
                    ]],
                    ['h2' => 'Worked Example', 'p' => [
                        'A 25-user office with six regular video calls, cloud accounting, shared drives, VoIP, and four cloud-monitored cameras may calculate a target above a basic home package. The business may also require symmetric or higher upload speeds. If the calculator recommends 120 Mbps, choosing a 150 Mbps or 200 Mbps package may provide a healthier operating margin.',
                    ]],
                ],
            ],
            'fiber-color-code-tool' => [
                'type' => 'color',
                'title' => 'Fiber Color Code Tool',
                'meta' => 'Look up standard fiber optic color codes for core numbers, buffer tubes, splicing, patching, and field identification.',
                'summary' => 'Quickly identify fiber core colors for splicing, termination, documentation, and troubleshooting.',
                'cta' => $sharedCta,
                'examples' => [
                    'Core 1 is commonly blue, core 2 orange, core 3 green, and core 4 brown in the standard sequence.',
                    'The same sequence repeats by tube or binder group in higher-count cables, so documentation remains essential.',
                ],
                'faqs' => [
                    ['q' => 'Are all cables colour coded the same way?', 'a' => 'Many use common TIA/EIA colour sequences, but installers should always confirm manufacturer documentation and site labels.'],
                    ['q' => 'Why document colours after splicing?', 'a' => 'Documentation prevents wrong patching, speeds repairs, and helps future technicians understand the route.'],
                    ['q' => 'Can colour identify single-mode or multimode?', 'a' => 'Jacket colours can indicate type, but core sequence colours identify fibre positions inside the cable.'],
                ],
                'content' => [
                    ['h2' => 'Using Fiber Colour Codes in the Field', 'p' => [
                        'Fiber colour codes help technicians identify individual cores during splicing, termination, testing, and repairs. Without a consistent colour sequence, it becomes easy to cross-connect the wrong fiber, break a live service, or waste time tracing links. Colour codes are especially important in ODFs, joint closures, FTTx distribution points, CCTV backbones, and campus networks.',
                        'This tool provides a fast lookup for common core colours. Enter a core number and the tool returns the expected colour in the standard sequence. For high-count cables, the sequence repeats by tube, ribbon, or binder group depending on the cable construction. Always compare the result with the cable datasheet, site labels, and test documentation before cutting or splicing.',
                    ]],
                    ['h2' => 'Why Colour Coding Must Be Paired With Labels', 'p' => [
                        'Colour coding is a guide, not a complete documentation system. A good fiber installation should include cable labels, ODF labels, port maps, splice records, OTDR traces where required, and as-built route notes. This matters because future repairs may be handled by a different technician who did not install the original cable.',
                        'In Kenya, many business and estate networks grow over time. A two-core link may later become a multi-building backbone. If the first installation is not documented, expansion becomes slower and riskier. Clean colour discipline and clear records reduce downtime when links are upgraded or repaired.',
                    ]],
                    ['h2' => 'Worked Example', 'p' => [
                        'If a technician needs core 7 in a standard sequence, the tool returns red. In a 24-core cable, core 19 is also red in the repeated sequence, but it belongs to the next group. That is why tube, tray, closure, and ODF labels must be checked before splicing.',
                    ]],
                ],
            ],
            'fiber-troubleshooting-wizard' => [
                'type' => 'troubleshooting',
                'title' => 'Fiber Troubleshooting Wizard',
                'meta' => 'Diagnose common fiber internet and LAN problems such as no link, low speed, dirty connectors, high loss, and unstable service.',
                'summary' => 'Use a guided checklist to identify likely fiber faults and decide when to call a technician.',
                'cta' => $sharedCta,
                'examples' => [
                    'No link light may point to power, patching, SFP mismatch, broken cable, dirty connector, or provider outage.',
                    'Slow speed may be caused by Wi-Fi, router limits, bad patch cords, duplex mismatch, congestion, or high optical loss.',
                ],
                'faqs' => [
                    ['q' => 'Should I clean fiber connectors myself?', 'a' => 'Only if you have proper fiber cleaning tools and know the safety procedure. Never look into a live fiber.'],
                    ['q' => 'What information should I give a technician?', 'a' => 'Share symptoms, when the fault started, affected users, link lights, router model, recent changes, and any test results.'],
                    ['q' => 'Can a speed test prove the fiber is bad?', 'a' => 'Not by itself. Speed tests can be affected by Wi-Fi, device performance, server choice, router capacity, and congestion.'],
                ],
                'content' => [
                    ['h2' => 'A Practical Fiber Troubleshooting Process', 'p' => [
                        'Fiber faults should be checked methodically. Jumping straight to cable replacement can waste money, while ignoring simple issues can create long downtime. The troubleshooting wizard helps users classify symptoms and follow a logical path: power, link lights, patching, optics, connectors, router, Wi-Fi, provider status, and physical route damage.',
                        'The goal is not to turn every user into a fiber technician. The goal is to collect better evidence before escalation. When you know whether the problem is no link, unstable link, low speed, high latency, or local Wi-Fi weakness, a technician can respond faster and bring the right tools.',
                    ]],
                    ['h2' => 'Common Fiber Fault Categories', 'p' => [
                        'No link can be caused by a powered-off device, wrong port, damaged patch cord, incompatible SFP, dirty connector, cut cable, bad splice, or provider outage. Intermittent link may come from a stressed bend, loose patch cord, dirty connector, failing optic, or power issue. Slow internet may not be a fiber fault at all; it can be router CPU, Wi-Fi interference, overloaded access points, malware, background backups, or contention.',
                        'Good troubleshooting records include photos of link lights, router status screenshots, port names, time of failure, affected areas, and any recent construction or cleaning activity near the cable route. This information helps separate physical faults from configuration or provider issues.',
                    ]],
                    ['h2' => 'Worked Example', 'p' => [
                        'A hotel reports slow internet in rooms but the router speed test is normal. The likely issue may be Wi-Fi coverage, access point placement, or internal switching rather than the fiber feed. A warehouse reports no link after construction near a duct route; that case needs physical inspection and possibly OTDR testing.',
                    ]],
                ],
            ],
            'free-fiber-technician-request-service' => [
                'type' => 'request',
                'title' => 'Free Fiber Technician Request Service',
                'meta' => 'Request help from a fiber technician in Kenya for installation, troubleshooting, splicing, testing, Wi-Fi, and business connectivity.',
                'summary' => 'Submit your location, site type, and fiber issue to prepare a free technician request or site survey enquiry.',
                'cta' => $sharedCta,
                'examples' => [
                    'A business can request a survey for office fiber installation, LAN backbone, cabinet cleanup, or CCTV fiber link.',
                    'A homeowner can request help for router relocation, weak Wi-Fi after fiber installation, or cable route planning.',
                ],
                'faqs' => [
                    ['q' => 'Is the request free?', 'a' => 'Submitting the request is free. Any paid work should be quoted after the technician understands the site and scope.'],
                    ['q' => 'What details should I provide?', 'a' => 'Provide town, estate or building, site type, issue, preferred date, contact details, and photos if available.'],
                    ['q' => 'Can this be used for emergency faults?', 'a' => 'Yes, but response depends on location, technician availability, access, and whether provider escalation is also required.'],
                ],
                'content' => [
                    ['h2' => 'How the Free Technician Request Service Helps', 'p' => [
                        'Many fiber problems start with unclear information. A customer may say the internet is down, but the real issue could be a provider outage, router fault, cut cable, dirty connector, weak Wi-Fi, wrong package, bad patch cord, or internal network congestion. A structured request helps collect the facts before a technician is dispatched.',
                        'This page lets users prepare a technician request with the key details a field team needs: location, site type, service needed, urgency, symptoms, access notes, and contact information. The tool generates a clean request summary that can be sent to Fiber Optics Kenya for a free site survey or support follow-up.',
                    ]],
                    ['h2' => 'When to Request a Fiber Technician', 'p' => [
                        'You should request a technician when installing a new fiber route, relocating a router, extending fiber to another building, connecting CCTV over fiber, troubleshooting intermittent drops, cleaning up a network cabinet, testing optical loss, splicing a damaged cable, or planning reliable internet for a business site. You should also request help when several users are affected and simple checks such as power, router restart, and provider status do not solve the issue.',
                        'For businesses, a technician request should include operating hours, downtime windows, cabinet access, landlord approvals, and whether the work affects POS systems, CCTV, VoIP, or cloud applications. This helps the technician plan the visit with less disruption.',
                    ]],
                    ['h2' => 'Worked Example', 'p' => [
                        'A school in Kiambu wants fiber extended from the administration block to a computer lab. The request should mention the approximate distance, whether ducts exist, where the router and switch are located, desired speed, number of users, and whether the route crosses open ground. With this information, the technician can advise whether the job needs trenching, aerial cable, armoured cable, or a simple indoor route.',
                    ]],
                ],
            ],
        ];
    }
}
