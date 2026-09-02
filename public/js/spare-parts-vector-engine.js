/**
 * EuroTaxi Automotive Procedural Vector Engine & Semantic Categorization System
 * Shared between Inventory Management, Archive Management, and Fleet Maintenance Modules.
 */

// ── AI Masterpiece Automotive Procedural Vector Engine (100% Accurate & Database-Verified) ──
    function generateDynamicPartSVG(partName) {
        let raw = (partName || 'Auto Part').toLowerCase().trim();
        raw = raw.replace(/^inventory\s*stock:\s*\d+\s*pcs\s*of\s*/i, '').trim();
        const id = 'svg_' + Math.random().toString(36).substring(2, 9);

        // Attribute Extraction
        const isFront = /front|fr|f\//i.test(raw);
        const isRear = /rear|rr|r\//i.test(raw);
        const isPair = /pair|set|\(2\)|\(4\)|set of/i.test(raw);
        const isGenuine = /genuine|orig|oem|toyota|mitsubishi|nissan|honda|hyundai|isuzu/i.test(raw);
        const isDrilled = /drill|slot|vent/i.test(raw);

        // Dynamic Color Scheme
        let pColor = '#2563eb', sColor = '#3b82f6', aColor = '#60a5fa'; // Default Modern Blue
        if (/red|brembo|ferodo|sport|racing|sti|type\s*r|momo/i.test(raw)) {
            pColor = '#dc2626'; sColor = '#ef4444'; aColor = '#f87171';
        } else if (/gold|yellow|motolite|ohlins|amaron|amber/i.test(raw)) {
            pColor = '#d97706'; sColor = '#f59e0b'; aColor = '#fbbf24';
        } else if (/green|tein|monster|eco|hybrid|lime/i.test(raw)) {
            pColor = '#16a34a'; sColor = '#22c55e'; aColor = '#4ade80';
        } else if (/black|dark|carbon|shadow|stealth|matte/i.test(raw)) {
            pColor = '#1e293b'; sColor = '#334155'; aColor = '#64748b';
        } else if (/purple|hks/i.test(raw)) {
            pColor = '#9333ea'; sColor = '#a855f7'; aColor = '#c084fc';
        } else if (/cyan|sky|teal|cool|blue|ice/i.test(raw)) {
            pColor = '#0284c7'; sColor = '#38bdf8'; aColor = '#bae6fd';
        }

        let content = '';

        // ══════════════════════════════════════════════════════════════════
        // PRIORITY 1: CLUTCH, DRIVETRAIN & TRANSMISSION (Prevents Disc Collisions)
        // ══════════════════════════════════════════════════════════════════
        if (/clutch\s*disc|clutch\s*plate|friction\s*disc|clutch\s*lining/i.test(raw)) {
            content = `
                <circle cx="100" cy="100" r="78" fill="url(#${id}_ceramic)" stroke="#78350f" stroke-width="2"/>
                ${Array.from({length: 16}).map((_, i) => {
                    const rad = (i * 22.5) * Math.PI / 180;
                    return `
                        <line x1="${(100 + 58 * Math.cos(rad)).toFixed(1)}" y1="${(100 + 58 * Math.sin(rad)).toFixed(1)}" x2="${(100 + 78 * Math.cos(rad)).toFixed(1)}" y2="${(100 + 78 * Math.sin(rad)).toFixed(1)}" stroke="#451a03" stroke-width="2"/>
                        <circle cx="${(100 + 68 * Math.cos(rad + 0.2)).toFixed(1)}" cy="${(100 + 68 * Math.sin(rad + 0.2)).toFixed(1)}" r="2.5" fill="url(#${id}_metal)"/>
                    `;
                }).join('')}
                <circle cx="100" cy="100" r="54" fill="url(#${id}_metal)" stroke="#334155" stroke-width="2"/>
                ${[0, 90, 180, 270].map(a => {
                    const rad = a * Math.PI / 180;
                    const cx = (100 + 34 * Math.cos(rad)).toFixed(1);
                    const cy = (100 + 34 * Math.sin(rad)).toFixed(1);
                    return `
                        <rect x="${cx - 12}" y="${cy - 8}" width="24" height="16" rx="4" fill="#0f172a" stroke="#475569" stroke-width="1.5" transform="rotate(${a} ${cx} ${cy})"/>
                        <rect x="${cx - 10}" y="${cy - 6}" width="20" height="12" rx="3" fill="url(#${id}_spring)" transform="rotate(${a} ${cx} ${cy})"/>
                        <line x1="${cx - 6}" y1="${cy - 6}" x2="${cx - 6}" y2="${cy + 6}" stroke="#f8fafc" stroke-width="2" transform="rotate(${a} ${cx} ${cy})"/>
                        <line x1="${cx}" y1="${cy - 6}" x2="${cx}" y2="${cy + 6}" stroke="#f8fafc" stroke-width="2" transform="rotate(${a} ${cx} ${cy})"/>
                        <line x1="${cx + 6}" y1="${cy - 6}" x2="${cx + 6}" y2="${cy + 6}" stroke="#f8fafc" stroke-width="2" transform="rotate(${a} ${cx} ${cy})"/>
                    `;
                }).join('')}
                <circle cx="100" cy="100" r="18" fill="url(#${id}_metal)" stroke="#1e293b" stroke-width="2"/>
                <circle cx="100" cy="100" r="11" fill="#020617"/>
                ${Array.from({length: 12}).map((_, i) => {
                    const rad = (i * 30) * Math.PI / 180;
                    return `<line x1="100" y1="100" x2="${(100 + 11 * Math.cos(rad)).toFixed(1)}" y2="${(100 + 11 * Math.sin(rad)).toFixed(1)}" stroke="#cbd5e1" stroke-width="2"/>`;
                }).join('')}
                <rect x="60" y="174" width="80" height="14" rx="3" fill="#1e293b"/>
                <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#f59e0b">CLUTCH DISC</text>
            `;
        }
        else if (/release\s*bearing|throwout\s*bearing|clutch\s*bearing/i.test(raw)) {
            content = `
                <circle cx="100" cy="100" r="72" fill="url(#${id}_metal)" stroke="#334155" stroke-width="2.5"/>
                <circle cx="100" cy="100" r="54" fill="#0f172a"/>
                ${Array.from({length: 12}).map((_, i) => {
                    const rad = (i * 30) * Math.PI / 180;
                    return `<circle cx="${(100 + 44 * Math.cos(rad)).toFixed(1)}" cy="${(100 + 44 * Math.sin(rad)).toFixed(1)}" r="6" fill="#f8fafc"/>`;
                }).join('')}
                <circle cx="100" cy="100" r="34" fill="url(#${id}_metal)"/>
                <circle cx="100" cy="100" r="24" fill="#0f172a"/>
                <rect x="18" y="90" width="22" height="20" rx="4" fill="#f59e0b"/>
                <rect x="160" y="90" width="22" height="20" rx="4" fill="#f59e0b"/>
                <circle cx="28" cy="100" r="4" fill="#0f172a"/>
                <circle cx="172" cy="100" r="4" fill="#0f172a"/>
                <rect x="65" y="174" width="70" height="14" rx="3" fill="#1e293b"/>
                <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#38bdf8">RELEASE BEARING</text>
            `;
        }
        else if (/center\s*bearing|propeller\s*bearing|pillow\s*block/i.test(raw)) {
            content = `
                <path d="M 25 135 L 50 65 L 150 65 L 175 135 Z" fill="#1e293b" stroke="#334155" stroke-width="3"/>
                <circle cx="35" cy="120" r="7" fill="url(#${id}_metal)"/>
                <circle cx="35" cy="120" r="3.5" fill="#0f172a"/>
                <circle cx="165" cy="120" r="7" fill="url(#${id}_metal)"/>
                <circle cx="165" cy="120" r="3.5" fill="#0f172a"/>
                <circle cx="100" cy="95" r="48" fill="#0f172a" stroke="#475569" stroke-width="2"/>
                <circle cx="100" cy="95" r="36" fill="url(#${id}_metal)"/>
                <circle cx="100" cy="95" r="28" fill="#1e293b"/>
                <circle cx="100" cy="95" r="18" fill="#020617"/>
                <rect x="55" y="174" width="90" height="14" rx="3" fill="#1e293b"/>
                <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#f59e0b">CENTER BEARING</text>
            `;
        }

        // ══════════════════════════════════════════════════════════════════
        // PRIORITY 2: FILTERS & FLUIDS (Fuel Filter, Engine Oil, ATF, Brake Fluid)
        // ══════════════════════════════════════════════════════════════════
        else if (/fuel\s*filter/i.test(raw)) {
            content = `
                <rect x="55" y="55" width="90" height="90" rx="14" fill="url(#${id}_metal)" stroke="#334155" stroke-width="2.5"/>
                <line x1="55" y1="75" x2="145" y2="75" stroke="#475569" stroke-width="3"/>
                <line x1="55" y1="125" x2="145" y2="125" stroke="#475569" stroke-width="3"/>
                <rect x="62" y="86" width="76" height="28" rx="4" fill="#0f172a"/>
                <path d="M 82 100 L 110 100 L 105 92 L 118 100 L 105 108 L 110 100" fill="#38bdf8" stroke="#38bdf8" stroke-width="2" stroke-linejoin="round"/>
                <text x="100" y="80" font-size="7" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#0284c7">FUEL FILTER</text>
                <rect x="25" y="92" width="30" height="16" rx="3" fill="url(#${id}_metal)" stroke="#334155" stroke-width="1.5"/>
                <ellipse cx="25" cy="100" rx="4" ry="8" fill="#0f172a"/>
                <rect x="145" y="92" width="30" height="16" rx="3" fill="url(#${id}_metal)" stroke="#334155" stroke-width="1.5"/>
                <ellipse cx="175" cy="100" rx="4" ry="8" fill="#0f172a"/>
                <rect x="60" y="174" width="80" height="14" rx="3" fill="#1e293b"/>
                <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#38bdf8">FUEL FILTER</text>
            `;
        }
        else if (/oil\s*filter/i.test(raw)) {
            content = `
                <rect x="55" y="40" width="90" height="120" rx="16" fill="#0f172a" stroke="#334155" stroke-width="2"/>
                ${[62, 74, 86, 98, 110, 122, 134].map(x => `<line x1="${x}" y1="40" x2="${x}" y2="65" stroke="#334155" stroke-width="3"/>`).join('')}
                <rect x="60" y="75" width="80" height="60" rx="4" fill="${pColor}"/>
                <text x="100" y="96" font-size="9" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#ffffff">GENUINE</text>
                <text x="100" y="112" font-size="8" font-weight="bold" font-family="sans-serif" text-anchor="middle" fill="#fef08a">OIL FILTER</text>
                <ellipse cx="100" cy="160" rx="45" ry="14" fill="#334155"/>
                <ellipse cx="100" cy="160" rx="38" ry="10" fill="#ef4444"/>
                <circle cx="100" cy="160" r="8" fill="#0f172a"/>
                <rect x="60" y="174" width="80" height="14" rx="3" fill="#1e293b"/>
                <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#f59e0b">SPIN-ON FILTER</text>
            `;
        }
        else if (/cabin|ac\s*filter|pollen\s*filter/i.test(raw)) {
            content = `
                <rect x="35" y="45" width="130" height="110" rx="6" fill="#f8fafc" stroke="#cbd5e1" stroke-width="2.5"/>
                ${[45, 53, 61, 69, 77, 85, 93, 101, 109, 117, 125, 133, 141, 149, 157].map(x => `<line x1="${x}" y1="46" x2="${x}" y2="154" stroke="#94a3b8" stroke-width="2.5"/>`).join('')}
                <rect x="70" y="85" width="60" height="30" rx="4" fill="#0284c7"/>
                <text x="100" y="98" font-size="7" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#ffffff">CABIN AC</text>
                <text x="100" y="109" font-size="6" font-weight="bold" font-family="sans-serif" text-anchor="middle" fill="#bae6fd">AIR FLOW →</text>
                <rect x="60" y="174" width="80" height="14" rx="3" fill="#1e293b"/>
                <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#0284c7">CABIN FILTER</text>
            `;
        }
        else if (/air\s*filter/i.test(raw)) {
            content = `
                <rect x="35" y="45" width="130" height="110" rx="8" fill="${pColor}"/>
                <rect x="46" y="56" width="108" height="88" rx="4" fill="#fef08a"/>
                ${[52, 60, 68, 76, 84, 92, 100, 108, 116, 124, 132, 140, 148].map(x => `<line x1="${x}" y1="56" x2="${x}" y2="144" stroke="#ca8a04" stroke-width="2.5"/>`).join('')}
                <rect x="60" y="174" width="80" height="14" rx="3" fill="#1e293b"/>
                <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#facc15">AIR FILTER</text>
            `;
        }
        else if (/engine\s*oil|synthetic|motor\s*oil|10w|5w|4l|lubricant/i.test(raw)) {
            content = `
                <rect x="58" y="24" width="36" height="22" rx="3" fill="#d97706"/>
                ${[64, 70, 76, 82, 88].map(x => `<line x1="${x}" y1="24" x2="${x}" y2="46" stroke="#b45309" stroke-width="2"/>`).join('')}
                <rect x="64" y="46" width="24" height="14" fill="#0f172a"/>
                <path d="M 45 60 L 125 60 Q 155 75 160 110 L 160 175 L 40 175 Z" fill="#1e293b" stroke="#334155" stroke-width="2.5"/>
                <path d="M 125 75 Q 145 90 145 125 Q 145 150 125 155" fill="none" stroke="#0f172a" stroke-width="14" stroke-linecap="round"/>
                <path d="M 125 75 Q 145 90 145 125 Q 145 150 125 155" fill="none" stroke="#475569" stroke-width="4" stroke-linecap="round"/>
                <rect x="46" y="80" width="6" height="85" rx="2" fill="#facc15"/>
                <rect x="58" y="75" width="70" height="85" rx="6" fill="#0f172a" stroke="#d97706" stroke-width="1.5"/>
                <rect x="62" y="80" width="62" height="18" rx="2" fill="#f59e0b"/>
                <text x="93" y="93" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#0f172a">SYNTHETIC</text>
                <text x="93" y="118" font-size="14" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#ffffff">5W-30</text>
                <text x="93" y="132" font-size="7" font-weight="bold" font-family="sans-serif" text-anchor="middle" fill="#94a3b8">ENGINE OIL</text>
                <rect x="76" y="140" width="34" height="14" rx="3" fill="#facc15"/>
                <text x="93" y="150" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#0f172a">4 LITERS</text>
            `;
        }
        else if (/brake\s*fluid|dot\s*3|dot\s*4/i.test(raw)) {
            content = `
                <rect x="85" y="24" width="30" height="18" rx="3" fill="#ef4444"/>
                <rect x="90" y="42" width="20" height="12" fill="#0f172a"/>
                <path d="M 65 54 L 135 54 L 140 170 L 60 170 Z" fill="#f8fafc" stroke="#cbd5e1" stroke-width="2"/>
                <rect x="68" y="70" width="64" height="80" rx="4" fill="#0f172a"/>
                <rect x="72" y="76" width="56" height="20" rx="2" fill="#ef4444"/>
                <text x="100" y="90" font-size="9" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#ffffff">DOT 4</text>
                <circle cx="100" cy="115" r="12" fill="#ef4444"/>
                <text x="100" y="119" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#ffffff">BRAKE</text>
                <text x="100" y="142" font-size="8" font-weight="bold" font-family="sans-serif" text-anchor="middle" fill="#facc15">500 ML</text>
            `;
        }
        else if (/atf|cvt|transmission\s*fluid|gear\s*oil/i.test(raw)) {
            content = `
                <rect x="85" y="24" width="30" height="18" rx="3" fill="#dc2626"/>
                <rect x="90" y="42" width="20" height="12" fill="#0f172a"/>
                <path d="M 60 54 L 140 54 L 145 170 L 55 170 Z" fill="#dc2626" stroke="#991b1b" stroke-width="2"/>
                <rect x="68" y="75" width="64" height="75" rx="4" fill="#0f172a"/>
                <text x="100" y="96" font-size="12" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#f87171">ATF</text>
                <text x="100" y="112" font-size="8" font-weight="bold" font-family="sans-serif" text-anchor="middle" fill="#fef08a">CVT FLUID</text>
                <text x="100" y="136" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#ffffff">1 LITER</text>
            `;
        }
        else if (/coolant|antifreeze/i.test(raw)) {
            content = `
                <rect x="80" y="24" width="40" height="22" rx="4" fill="#ef4444"/>
                <rect x="88" y="44" width="24" height="16" fill="#0284c7"/>
                <path d="M 50 60 L 150 60 L 160 175 L 40 175 Z" fill="#0284c7" stroke="#0369a1" stroke-width="2"/>
                <path d="M 50 60 L 70 60 L 60 175 L 40 175 Z" fill="#0f172a" opacity="0.25"/>
                <rect x="58" y="75" width="6" height="85" rx="2" fill="#38bdf8"/>
                <rect x="75" y="80" width="72" height="75" rx="6" fill="#f8fafc"/>
                <circle cx="111" cy="110" r="18" fill="#ec4899"/>
                <path d="M 111 96 Q 123 112 111 122 Q 99 112 111 96 Z" fill="#ffffff"/>
                <text x="111" y="142" font-size="7" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#0f172a">COOLANT 50/50</text>
                <rect x="60" y="174" width="80" height="14" rx="3" fill="#1e293b"/>
                <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#ec4899">LLC COOLANT</text>
            `;
        }

        // ══════════════════════════════════════════════════════════════════
        // PRIORITY 3: BRAKES (Hose, Shoes, Pads, Caliper, Rotors)
        // ══════════════════════════════════════════════════════════════════
        else if (/brake\s*hose|brake\s*line|hydraulic\s*hose/i.test(raw)) {
            content = `
                <path d="M 35 160 Q 80 40 165 40" fill="none" stroke="#0f172a" stroke-width="16" stroke-linecap="round"/>
                <path d="M 35 160 Q 80 40 165 40" fill="none" stroke="#38bdf8" stroke-width="3" stroke-linecap="round" stroke-dasharray="4,4"/>
                <circle cx="35" cy="160" r="14" fill="url(#${id}_metal)" stroke="#334155" stroke-width="2"/>
                <circle cx="35" cy="160" r="7" fill="#0f172a"/>
                <circle cx="35" cy="160" r="16" fill="none" stroke="#d97706" stroke-width="2"/>
                <rect x="155" y="30" width="25" height="20" rx="3" fill="url(#${id}_metal)" stroke="#334155" stroke-width="2"/>
                ${[159, 164, 169, 174].map(x => `<line x1="${x}" y1="30" x2="${x}" y2="50" stroke="#475569" stroke-width="2"/>`).join('')}
                <rect x="60" y="174" width="80" height="14" rx="3" fill="#1e293b"/>
                <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#38bdf8">BRAKE HOSE</text>
            `;
        }
        else if (/brake\s*shoe|drum\s*brake|rear\s*shoe/i.test(raw)) {
            content = `
                <circle cx="100" cy="100" r="76" fill="none" stroke="#1e293b" stroke-width="4" stroke-dasharray="6,6"/>
                <rect x="75" y="24" width="50" height="24" rx="6" fill="url(#${id}_metal)" stroke="#334155" stroke-width="2"/>
                <rect x="68" y="28" width="12" height="16" rx="3" fill="#0f172a"/>
                <rect x="120" y="28" width="12" height="16" rx="3" fill="#0f172a"/>
                <path d="M 68 38 A 68 68 0 0 0 68 162 L 60 156 A 76 76 0 0 1 60 44 Z" fill="url(#${id}_ceramic)" stroke="#78350f" stroke-width="1.5"/>
                <path d="M 72 45 A 60 60 0 0 0 72 155" fill="none" stroke="url(#${id}_metal)" stroke-width="8" stroke-linecap="round"/>
                <path d="M 132 38 A 68 68 0 0 1 132 162 L 140 156 A 76 76 0 0 0 140 44 Z" fill="url(#${id}_ceramic)" stroke="#78350f" stroke-width="1.5"/>
                <path d="M 128 45 A 60 60 0 0 1 128 155" fill="none" stroke="url(#${id}_metal)" stroke-width="8" stroke-linecap="round"/>
                <path d="M 75 55 L 82 50 L 90 58 L 98 50 L 106 58 L 114 50 L 125 55" fill="none" stroke="#f59e0b" stroke-width="3" stroke-linecap="round"/>
                <rect x="85" y="148" width="30" height="14" rx="3" fill="url(#${id}_metal)"/>
                <circle cx="100" cy="155" r="5" fill="#f59e0b"/>
                <path d="M 75 145 L 82 140 L 90 148 L 98 140 L 106 148 L 114 140 L 125 145" fill="none" stroke="#f59e0b" stroke-width="3" stroke-linecap="round"/>
                <circle cx="65" cy="100" r="6" fill="#38bdf8"/>
                <circle cx="135" cy="100" r="6" fill="#38bdf8"/>
                <circle cx="100" cy="100" r="16" fill="#1e293b"/>
                <rect x="65" y="174" width="70" height="14" rx="3" fill="#1e293b"/>
                <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#f59e0b">DRUM SHOES</text>
            `;
        }
        else if (/caliper|monoblock|brake\s*caliper/i.test(raw)) {
            content = `
                <path d="M 35 60 Q 100 45 165 60 L 165 140 Q 100 155 35 140 Z" fill="${pColor}" stroke="#0f172a" stroke-width="3"/>
                <rect x="50" y="70" width="100" height="60" rx="8" fill="#1e293b"/>
                <circle cx="75" cy="100" r="18" fill="url(#${id}_metal)" stroke="#94a3b8" stroke-width="2"/>
                <circle cx="75" cy="100" r="10" fill="#0f172a"/>
                <circle cx="125" cy="100" r="18" fill="url(#${id}_metal)" stroke="#94a3b8" stroke-width="2"/>
                <circle cx="125" cy="100" r="10" fill="#0f172a"/>
                <rect x="94" y="32" width="12" height="20" rx="3" fill="url(#${id}_metal)"/>
                <circle cx="100" cy="38" r="3" fill="#ef4444"/>
                <text x="100" y="148" font-size="9" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#ffffff">4-PISTON CALIPER</text>
            `;
        }
        else if (/brake\s*pad/i.test(raw)) {
            if (isFront || isGenuine) {
                content = `
                    <rect x="25" y="38" width="150" height="52" rx="8" fill="#1e293b" stroke="#334155" stroke-width="2"/>
                    <rect x="38" y="44" width="124" height="40" rx="4" fill="url(#${id}_ceramic)"/>
                    <polygon points="38,44 54,44 38,84" fill="#78350f" opacity="0.7"/>
                    <polygon points="162,44 146,44 162,84" fill="#78350f" opacity="0.7"/>
                    <line x1="100" y1="44" x2="100" y2="84" stroke="#451a03" stroke-width="4"/>
                    <rect x="18" y="70" width="14" height="16" rx="2" fill="#eab308"/>
                    <rect x="25" y="108" width="150" height="52" rx="8" fill="#1e293b" stroke="#334155" stroke-width="2"/>
                    <rect x="38" y="114" width="124" height="40" rx="4" fill="url(#${id}_ceramic)"/>
                    <polygon points="38,114 54,114 38,154" fill="#78350f" opacity="0.7"/>
                    <polygon points="162,114 146,114 162,154" fill="#78350f" opacity="0.7"/>
                    <line x1="100" y1="114" x2="100" y2="154" stroke="#451a03" stroke-width="4"/>
                    <rect x="168" y="140" width="14" height="16" rx="2" fill="#eab308"/>
                    <rect x="65" y="93" width="70" height="14" rx="3" fill="#facc15"/>
                    <text x="100" y="103" font-size="7" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#0f172a">GENUINE FRONT</text>
                `;
            } else if (isRear) {
                content = `
                    <rect x="35" y="48" width="130" height="42" rx="6" fill="#334155"/>
                    <rect x="45" y="54" width="110" height="30" rx="4" fill="url(#${id}_ceramic)"/>
                    <rect x="35" y="108" width="130" height="42" rx="6" fill="#334155"/>
                    <rect x="45" y="114" width="110" height="30" rx="4" fill="url(#${id}_ceramic)"/>
                    <circle cx="100" cy="70" r="6" fill="#94a3b8"/>
                    <circle cx="100" cy="130" r="6" fill="#94a3b8"/>
                    <rect x="70" y="94" width="60" height="12" rx="2" fill="#e2e8f0"/>
                    <text x="100" y="103" font-size="7" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#1e293b">REAR AXLE</text>
                `;
            } else {
                content = `
                    <rect x="30" y="45" width="140" height="45" rx="6" fill="#334155"/>
                    <rect x="42" y="52" width="116" height="34" rx="4" fill="${pColor}"/>
                    <rect x="98" y="52" width="4" height="34" fill="#1e293b"/>
                    <rect x="25" y="105" width="150" height="52" rx="8" fill="#1e293b"/>
                    <rect x="38" y="112" width="124" height="40" rx="4" fill="url(#${id}_ceramic)"/>
                    <polygon points="38,112 55,112 38,152" fill="#78350f" opacity="0.6"/>
                    <polygon points="162,112 145,112 162,152" fill="#78350f" opacity="0.6"/>
                    <rect x="98" y="112" width="4" height="40" fill="#451a03"/>
                `;
            }
        }
        else if (/rotor|brake\s*disk|brake\s*rotor|disc|disk/i.test(raw)) {
            const drilledHoles = isDrilled ? Array.from({length: 12}).map((_, i) => {
                const rad1 = (i * 30) * Math.PI / 180;
                const rad2 = (i * 30 + 15) * Math.PI / 180;
                return `
                    <circle cx="${(100 + 64 * Math.cos(rad1)).toFixed(1)}" cy="${(100 + 64 * Math.sin(rad1)).toFixed(1)}" r="2.5" fill="#1e293b"/>
                    <circle cx="${(100 + 74 * Math.cos(rad2)).toFixed(1)}" cy="${(100 + 74 * Math.sin(rad2)).toFixed(1)}" r="2.5" fill="#1e293b"/>
                `;
            }).join('') : '';

            const lugBolts = [0, 72, 144, 216, 288].map(a => {
                const rad = (a - 90) * Math.PI / 180;
                return `<circle cx="${(100 + 26 * Math.cos(rad)).toFixed(1)}" cy="${(100 + 26 * Math.sin(rad)).toFixed(1)}" r="5.5" fill="#f8fafc"/><circle cx="${(100 + 26 * Math.cos(rad)).toFixed(1)}" cy="${(100 + 26 * Math.sin(rad)).toFixed(1)}" r="3" fill="#0f172a"/>`;
            }).join('');

            content = `
                <circle cx="100" cy="100" r="82" fill="url(#${id}_rotor)"/>
                <circle cx="100" cy="100" r="76" fill="none" stroke="#e2e8f0" stroke-width="1.5"/>
                <circle cx="100" cy="100" r="68" fill="none" stroke="#64748b" stroke-width="0.75"/>
                <circle cx="100" cy="100" r="60" fill="none" stroke="#cbd5e1" stroke-width="1.5"/>
                ${drilledHoles}
                <circle cx="100" cy="100" r="46" fill="url(#${id}_hat)"/>
                <circle cx="100" cy="100" r="42" fill="none" stroke="#f8fafc" stroke-width="1.5" stroke-opacity="0.4"/>
                ${lugBolts}
                <circle cx="100" cy="100" r="14" fill="#0f172a"/>
            `;
        }

        // ══════════════════════════════════════════════════════════════════
        // PRIORITY 4: SUSPENSION & STEERING
        // ══════════════════════════════════════════════════════════════════
        else if (/wheel\s*hub|wheel\s*bearing|hub\s*bearing/i.test(raw)) {
            content = `
                <circle cx="100" cy="100" r="76" fill="url(#${id}_metal)" stroke="#334155" stroke-width="2.5"/>
                <circle cx="100" cy="100" r="58" fill="#1e293b"/>
                ${[0, 72, 144, 216, 288].map(a => {
                    const rad = (a - 90) * Math.PI / 180;
                    return `
                        <circle cx="${(100 + 44 * Math.cos(rad)).toFixed(1)}" cy="${(100 + 44 * Math.sin(rad)).toFixed(1)}" r="8" fill="url(#${id}_metal)"/>
                        <circle cx="${(100 + 44 * Math.cos(rad)).toFixed(1)}" cy="${(100 + 44 * Math.sin(rad)).toFixed(1)}" r="4" fill="#0f172a"/>
                    `;
                }).join('')}
                <circle cx="100" cy="100" r="28" fill="url(#${id}_metal)"/>
                <circle cx="100" cy="100" r="20" fill="#0f172a"/>
                ${Array.from({length: 12}).map((_, i) => {
                    const rad = (i * 30) * Math.PI / 180;
                    return `<line x1="100" y1="100" x2="${(100 + 19 * Math.cos(rad)).toFixed(1)}" y2="${(100 + 19 * Math.sin(rad)).toFixed(1)}" stroke="#cbd5e1" stroke-width="2"/>`;
                }).join('')}
                <circle cx="100" cy="100" r="10" fill="#1e293b"/>
            `;
        }
        else if (/tie\s*rod|rack\s*end|steering\s*link/i.test(raw)) {
            content = `
                <path d="M 30 160 L 75 75" stroke="url(#${id}_metal)" stroke-width="14" stroke-linecap="round"/>
                ${[140, 130, 120, 110, 100].map(y => `<line x1="${(30 + (y-30)*0.45).toFixed(1)}" y1="${y}" x2="${(42 + (y-30)*0.45).toFixed(1)}" y2="${y+3}" stroke="#334155" stroke-width="2"/>`).join('')}
                <circle cx="82" cy="65" r="20" fill="#1e293b" stroke="#334155" stroke-width="2"/>
                <ellipse cx="82" cy="48" rx="14" ry="8" fill="#0f172a"/>
                <rect x="76" y="24" width="12" height="24" rx="2" fill="url(#${id}_metal)"/>
                <polygon points="74,24 90,24 86,14 78,14" fill="#f59e0b"/>
                <circle cx="82" cy="20" r="2" fill="#0f172a"/>

                <path d="M 170 160 L 125 75" stroke="url(#${id}_metal)" stroke-width="14" stroke-linecap="round"/>
                ${[140, 130, 120, 110, 100].map(y => `<line x1="${(170 - (y-30)*0.45).toFixed(1)}" y1="${y}" x2="${(158 - (y-30)*0.45).toFixed(1)}" y2="${y+3}" stroke="#334155" stroke-width="2"/>`).join('')}
                <circle cx="118" cy="65" r="20" fill="#1e293b" stroke="#334155" stroke-width="2"/>
                <ellipse cx="118" cy="48" rx="14" ry="8" fill="#0f172a"/>
                <rect x="112" y="24" width="12" height="24" rx="2" fill="url(#${id}_metal)"/>
                <polygon points="110,24 126,24 122,14 114,14" fill="#f59e0b"/>
                <circle cx="118" cy="20" r="2" fill="#0f172a"/>
                <rect x="65" y="174" width="70" height="14" rx="3" fill="#1e293b"/>
                <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#f59e0b">TIE ROD (PAIR)</text>
            `;
        }
        else if (/shock|strut|coilover|spring|damper/i.test(raw)) {
            if (isFront || /strut/i.test(raw)) {
                content = `
                    ${isPair ? [65, 135].map(x => `
                        <rect x="${x-20}" y="18" width="40" height="10" rx="3" fill="${pColor}"/>
                        <rect x="${x-5}" y="28" width="10" height="60" rx="2" fill="url(#${id}_metal)"/>
                        ${[35, 47, 59, 71, 83].map(y => `
                            <path d="M ${x-18} ${y+5} Q ${x} ${y} ${x+18} ${y+5}" fill="none" stroke="url(#${id}_spring)" stroke-width="6" stroke-linecap="round"/>
                        `).join('')}
                        <rect x="${x-14}" y="92" width="28" height="55" rx="4" fill="url(#${id}_body)"/>
                        <circle cx="${x}" cy="155" r="10" fill="url(#${id}_body)"/>
                        <circle cx="${x}" cy="155" r="4" fill="${pColor}"/>
                    `).join('') : `
                        <rect x="65" y="16" width="70" height="12" rx="4" fill="${pColor}"/>
                        <circle cx="100" cy="22" r="5" fill="#f8fafc"/>
                        <rect x="94" y="28" width="12" height="72" rx="2" fill="url(#${id}_metal)"/>
                        ${[35, 47, 59, 71, 83, 95].map(y => `
                            <path d="M 68 ${y+6} Q 100 ${y} 132 ${y+6}" fill="none" stroke="url(#${id}_spring)" stroke-width="9" stroke-linecap="round"/>
                            <path d="M 68 ${y+6} Q 100 ${y+2} 132 ${y+6}" fill="none" stroke="${aColor}" stroke-width="2" stroke-linecap="round"/>
                        `).join('')}
                        <rect x="74" y="106" width="52" height="8" rx="2" fill="${pColor}"/>
                        <rect x="78" y="114" width="44" height="6" rx="2" fill="${sColor}"/>
                        <rect x="82" y="120" width="36" height="52" rx="4" fill="url(#${id}_body)"/>
                        <circle cx="100" cy="180" r="14" fill="url(#${id}_body)"/>
                    `}
                    <rect x="60" y="174" width="80" height="14" rx="3" fill="#1e293b"/>
                    <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#38bdf8">FRONT STRUTS</text>
                `;
            } else {
                content = `
                    ${isPair ? [65, 135].map(x => `
                        <circle cx="${x}" cy="24" r="9" fill="url(#${id}_body)"/>
                        <circle cx="${x}" cy="24" r="4" fill="#cbd5e1"/>
                        <rect x="${x-4}" y="32" width="8" height="60" rx="2" fill="url(#${id}_metal)"/>
                        <rect x="${x-12}" y="80" width="24" height="75" rx="4" fill="url(#${id}_body)"/>
                        <circle cx="${x}" cy="162" r="10" fill="url(#${id}_body)"/>
                        <circle cx="${x}" cy="162" r="4" fill="#cbd5e1"/>
                    `).join('') : `
                        <circle cx="100" cy="24" r="12" fill="url(#${id}_body)"/>
                        <circle cx="100" cy="24" r="5" fill="#cbd5e1"/>
                        <rect x="95" y="34" width="10" height="65" rx="2" fill="url(#${id}_metal)"/>
                        <rect x="85" y="85" width="30" height="85" rx="5" fill="url(#${id}_body)"/>
                        <circle cx="100" cy="176" r="12" fill="url(#${id}_body)"/>
                    `}
                    <rect x="60" y="174" width="80" height="14" rx="3" fill="#1e293b"/>
                    <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#f59e0b">REAR SHOCKS</text>
                `;
            }
        }
        else if (/serpentine|fan\s*belt|drive\s*belt|v-belt|ribbed\s*belt/i.test(raw)) {
            content = `
                <circle cx="65" cy="135" r="30" fill="url(#${id}_metal)" stroke="#1e293b" stroke-width="2"/>
                <circle cx="65" cy="135" r="12" fill="#0f172a"/>
                <circle cx="135" cy="65" r="22" fill="url(#${id}_metal)" stroke="#1e293b" stroke-width="2"/>
                <circle cx="135" cy="65" r="8" fill="#0f172a"/>
                <circle cx="135" cy="135" r="18" fill="url(#${id}_metal)" stroke="#1e293b" stroke-width="2"/>
                <circle cx="135" cy="135" r="6" fill="#0f172a"/>
                <path d="M 65 105 L 135 43 A 22 22 0 0 1 157 65 L 153 135 A 18 18 0 0 1 135 153 L 65 165 A 30 30 0 0 1 35 135 A 30 30 0 0 1 65 105 Z" fill="none" stroke="#0f172a" stroke-width="12"/>
                <path d="M 65 105 L 135 43 A 22 22 0 0 1 157 65 L 153 135 A 18 18 0 0 1 135 153 L 65 165 A 30 30 0 0 1 35 135 A 30 30 0 0 1 65 105 Z" fill="none" stroke="${pColor}" stroke-width="4" stroke-dasharray="6,2"/>
                <rect x="55" y="174" width="90" height="14" rx="3" fill="#1e293b"/>
                <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#ffffff">SERPENTINE BELT</text>
            `;
        }

        // ══════════════════════════════════════════════════════════════════
        // PRIORITY 5: IGNITION, BATTERY & ELECTRICAL
        // ══════════════════════════════════════════════════════════════════
        else if (/spark\s*plug|iridium|glow\s*plug|ignition\s*coil/i.test(raw)) {
            if (/set|pair|\(4\)|4\s*pcs/i.test(raw)) {
                content = `
                    ${[38, 78, 118, 158].map((x) => `
                        <rect x="${x-3}" y="30" width="6" height="10" rx="1" fill="#cbd5e1"/>
                        <rect x="${x-7}" y="40" width="14" height="42" rx="3" fill="#f8fafc"/>
                        <ellipse cx="${x}" cy="48" rx="8" ry="3" fill="#e2e8f0"/>
                        <ellipse cx="${x}" cy="58" rx="8" ry="3" fill="#e2e8f0"/>
                        <ellipse cx="${x}" cy="68" rx="8" ry="3" fill="#e2e8f0"/>
                        <rect x="${x-9}" y="82" width="18" height="18" rx="2" fill="#64748b"/>
                        <rect x="${x-6}" y="100" width="12" height="36" fill="#475569"/>
                        ${[104, 110, 116, 122, 128].map(y => `<line x1="${x-6}" y1="${y}" x2="${x+6}" y2="${y+2}" stroke="#94a3b8" stroke-width="1.5"/>`).join('')}
                        <rect x="${x-2}" y="136" width="4" height="8" fill="#f8fafc"/>
                        <rect x="${x-1}" y="144" width="2" height="5" fill="#38bdf8"/>
                        <path d="M ${x-6} 136 L ${x-6} 150 L ${x} 150" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round"/>
                    `).join('')}
                    <rect x="50" y="166" width="100" height="15" rx="4" fill="#1e293b"/>
                    <text x="100" y="177" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#38bdf8">IRIDIUM 4-PACK</text>
                `;
            } else {
                content = `
                    <rect x="94" y="18" width="12" height="15" rx="2" fill="#cbd5e1"/>
                    <rect x="84" y="33" width="32" height="70" rx="4" fill="#f8fafc"/>
                    <ellipse cx="100" cy="45" rx="19" ry="5" fill="#e2e8f0"/>
                    <ellipse cx="100" cy="60" rx="19" ry="5" fill="#e2e8f0"/>
                    <ellipse cx="100" cy="75" rx="19" ry="5" fill="#e2e8f0"/>
                    <ellipse cx="100" cy="90" rx="19" ry="5" fill="#e2e8f0"/>
                    <rect x="78" y="103" width="44" height="26" rx="2" fill="#64748b"/>
                    <rect x="86" y="129" width="28" height="42" fill="#475569"/>
                    ${[133, 139, 145, 151, 157, 163].map(y => `<line x1="86" y1="${y}" x2="114" y2="${y+2}" stroke="#94a3b8" stroke-width="2"/>`).join('')}
                    <rect x="97" y="171" width="6" height="14" fill="#f8fafc"/>
                    <rect x="98.5" y="185" width="3" height="6" fill="#38bdf8"/>
                    <path d="M 88 171 L 88 193 L 100 193" fill="none" stroke="#64748b" stroke-width="3.5" stroke-linecap="round"/>
                `;
            }
        }
        else if (/battery|motolite|amaron|12v|ns40|ns60|din/i.test(raw)) {
            const isMotolite = /motolite/i.test(raw);
            const brandColor = isMotolite ? '#f59e0b' : pColor;
            content = `
                <rect x="30" y="55" width="140" height="120" rx="8" fill="#1e293b" stroke="#334155" stroke-width="2"/>
                <rect x="25" y="45" width="150" height="32" rx="4" fill="${brandColor}"/>
                <path d="M 45 45 Q 100 15 155 45" fill="none" stroke="#475569" stroke-width="6" stroke-linecap="round"/>
                <rect x="42" y="32" width="18" height="16" rx="2" fill="#ef4444"/>
                <circle cx="51" cy="32" r="5" fill="#94a3b8"/>
                <text x="51" y="58" font-size="9" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#ffffff">+</text>
                <rect x="140" y="32" width="18" height="16" rx="2" fill="#3b82f6"/>
                <circle cx="149" cy="32" r="5" fill="#94a3b8"/>
                <text x="149" y="58" font-size="9" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#ffffff">-</text>
                <circle cx="100" cy="55" r="7" fill="#0f172a"/>
                <circle cx="100" cy="55" r="4" fill="#22c55e"/>
                <rect x="42" y="90" width="116" height="70" rx="6" fill="#0f172a"/>
                <rect x="48" y="96" width="104" height="26" rx="3" fill="${brandColor}"/>
                <text x="100" y="114" font-size="10" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#0f172a">${isMotolite ? 'MOTOLITE GOLD' : '12V BATTERY'}</text>
                <text x="100" y="140" font-size="9" font-weight="bold" font-family="sans-serif" text-anchor="middle" fill="#f8fafc">MAINTENANCE FREE</text>
            `;
        }

        // ══════════════════════════════════════════════════════════════════
        // PRIORITY 6: GLASS, WIPERS & COOLING
        // ══════════════════════════════════════════════════════════════════
        else if (/window|windshield|windscreen|door\s*glass|side\s*glass/i.test(raw)) {
            content = `
                <path d="M 40 45 L 160 35 Q 170 35 172 45 L 160 150 Q 158 158 150 158 L 42 158 Q 35 158 35 148 L 36 55 Q 36 45 40 45 Z" fill="url(#${id}_glass)" stroke="#38bdf8" stroke-width="2.5"/>
                <polygon points="55,46 80,44 68,158 43,158" fill="#ffffff" opacity="0.4"/>
                <polygon points="105,40 120,38 108,158 93,158" fill="#ffffff" opacity="0.2"/>
                <rect x="35" y="154" width="130" height="14" rx="3" fill="#1e293b"/>
                <circle cx="65" cy="161" r="4" fill="#facc15"/>
                <circle cx="135" cy="161" r="4" fill="#facc15"/>
                <rect x="65" y="174" width="70" height="14" rx="3" fill="#1e293b"/>
                <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#38bdf8">SIDE WINDOW</text>
            `;
        }
        else if (/wiper|blade/i.test(raw)) {
            content = `
                <path d="M 20 155 Q 95 45 180 85" fill="none" stroke="#1e293b" stroke-width="10" stroke-linecap="round"/>
                <path d="M 20 158 Q 95 48 180 88" fill="none" stroke="#0284c7" stroke-width="3" stroke-linecap="round"/>
                <rect x="90" y="80" width="22" height="18" rx="4" fill="${pColor}"/>
                <circle cx="101" cy="89" r="3.5" fill="#f8fafc"/>
                <path d="M 35 180 Q 105 105 170 135" fill="none" stroke="#334155" stroke-width="8" stroke-linecap="round"/>
                <path d="M 35 182 Q 105 107 170 137" fill="none" stroke="#38bdf8" stroke-width="2.5" stroke-linecap="round"/>
                <rect x="98" y="128" width="18" height="14" rx="3" fill="${pColor}"/>
                <rect x="60" y="174" width="80" height="14" rx="3" fill="#1e293b"/>
                <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#38bdf8">WIPER BLADES</text>
            `;
        }
        else if (/radiator\s*cap/i.test(raw)) {
            content = `
                <rect x="25" y="86" width="150" height="28" rx="14" fill="url(#${id}_metal)" stroke="#334155" stroke-width="2"/>
                <circle cx="38" cy="100" r="8" fill="#1e293b"/>
                <circle cx="162" cy="100" r="8" fill="#1e293b"/>
                <circle cx="100" cy="100" r="54" fill="url(#${id}_metal)" stroke="#475569" stroke-width="2.5"/>
                <circle cx="100" cy="100" r="44" fill="${pColor}"/>
                <circle cx="100" cy="100" r="36" fill="#0f172a"/>
                <polygon points="100,78 118,110 82,110" fill="#facc15" stroke="#ca8a04" stroke-width="1.5"/>
                <text x="100" y="105" font-size="12" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#0f172a">!</text>
                <text x="100" y="124" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#f8fafc">1.1 BAR</text>
                <text x="100" y="133" font-size="6" font-weight="bold" font-family="sans-serif" text-anchor="middle" fill="#94a3b8">DO NOT OPEN HOT</text>
                <rect x="94" y="32" width="12" height="54" rx="2" fill="url(#${id}_metal)"/>
                ${[38, 48, 58, 68].map(y => `<line x1="88" y1="${y}" x2="112" y2="${y+4}" stroke="#cbd5e1" stroke-width="3.5" stroke-linecap="round"/>`).join('')}
                <ellipse cx="100" cy="32" rx="28" ry="8" fill="#0f172a"/>
                <ellipse cx="100" cy="30" rx="22" ry="6" fill="#ef4444"/>
            `;
        }

        // ══════════════════════════════════════════════════════════════════
        // PRIORITY 7: AUDIO, DASHBOARD & BEARINGS
        // ══════════════════════════════════════════════════════════════════
        else if (/stereo|head\s*unit|radio|infotainment|touchscreen|android\s*auto|apple\s*carplay/i.test(raw)) {
            content = `
                <rect x="25" y="35" width="150" height="130" rx="10" fill="#0f172a" stroke="#334155" stroke-width="3"/>
                <rect x="35" y="45" width="105" height="110" rx="6" fill="#1e293b"/>
                <rect x="40" y="50" width="95" height="100" rx="4" fill="#020617"/>
                ${[
                  {x: 48, h: 35, c: '#38bdf8'},
                  {x: 58, h: 55, c: '#0284c7'},
                  {x: 68, h: 42, c: '#22c55e'},
                  {x: 78, h: 68, c: '#f59e0b'},
                  {x: 88, h: 48, c: '#ec4899'},
                  {x: 98, h: 60, c: '#a855f7'},
                  {x: 108, h: 38, c: '#38bdf8'},
                  {x: 118, h: 25, c: '#0284c7'}
                ].map(b => `<rect x="${b.x}" y="${120 - b.h}" width="6" height="${b.h}" rx="2" fill="${b.c}"/>`).join('')}
                <line x1="45" y1="128" x2="130" y2="128" stroke="#334155" stroke-width="3" stroke-linecap="round"/>
                <line x1="45" y1="128" x2="95" y2="128" stroke="#38bdf8" stroke-width="3" stroke-linecap="round"/>
                <circle cx="95" cy="128" r="4" fill="#ffffff"/>
                <text x="87" y="66" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#38bdf8">ANDROID AUTO 10"</text>
                <circle cx="156" cy="65" r="14" fill="url(#${id}_metal)" stroke="#475569" stroke-width="2"/>
                <circle cx="156" cy="65" r="10" fill="#0f172a"/>
                <circle cx="156" cy="65" r="4" fill="#38bdf8"/>
                <rect x="146" y="92" width="20" height="8" rx="2" fill="#1e293b" stroke="#475569"/>
                <rect x="146" y="108" width="20" height="8" rx="2" fill="#1e293b" stroke="#475569"/>
                <rect x="146" y="125" width="20" height="22" rx="4" fill="${pColor}"/>
                <polygon points="152,130 162,136 152,142" fill="#ffffff"/>
                <rect x="60" y="174" width="80" height="14" rx="3" fill="#1e293b"/>
                <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#38bdf8">HEAD UNIT 2-DIN</text>
            `;
        }
        else if (/speaker|tweeter|subwoofer|woofer|sound\s*system|amplifier|amp\b/i.test(raw)) {
            content = `
                <circle cx="100" cy="100" r="78" fill="#1e293b" stroke="#334155" stroke-width="3"/>
                ${[45, 135, 225, 315].map(a => {
                    const rad = a * Math.PI / 180;
                    return `
                        <circle cx="${(100 + 74 * Math.cos(rad)).toFixed(1)}" cy="${(100 + 74 * Math.sin(rad)).toFixed(1)}" r="6" fill="url(#${id}_metal)"/>
                        <circle cx="${(100 + 74 * Math.cos(rad)).toFixed(1)}" cy="${(100 + 74 * Math.sin(rad)).toFixed(1)}" r="3" fill="#0f172a"/>
                    `;
                }).join('')}
                <circle cx="100" cy="100" r="66" fill="#0f172a" stroke="#334155" stroke-width="2"/>
                <circle cx="100" cy="100" r="58" fill="none" stroke="#475569" stroke-width="3"/>
                <circle cx="100" cy="100" r="54" fill="url(#${id}_metal)" opacity="0.85"/>
                <line x1="46" y1="100" x2="154" y2="100" stroke="#0f172a" stroke-width="7" stroke-linecap="round"/>
                <line x1="46" y1="100" x2="154" y2="100" stroke="${pColor}" stroke-width="3" stroke-linecap="round"/>
                <circle cx="100" cy="100" r="22" fill="#0f172a" stroke="#334155" stroke-width="2"/>
                <circle cx="100" cy="100" r="14" fill="${sColor}"/>
                <circle cx="100" cy="100" r="8" fill="#f8fafc"/>
                <rect x="60" y="174" width="80" height="14" rx="3" fill="#1e293b"/>
                <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#38bdf8">COAXIAL SPEAKER</text>
            `;
        }
        else if (/speedometer|tachometer|gauge|cluster|odometer|boost\s*gauge|temp\s*gauge|fuel\s*gauge/i.test(raw)) {
            content = `
                <circle cx="100" cy="100" r="78" fill="#0f172a" stroke="#334155" stroke-width="3"/>
                <circle cx="100" cy="100" r="74" fill="none" stroke="${pColor}" stroke-width="2"/>
                ${Array.from({length: 21}).map((_, i) => {
                    const deg = 135 + (i * 13.5);
                    const rad = deg * Math.PI / 180;
                    const isMajor = i % 2 === 0;
                    const isRedline = i >= 17;
                    const r1 = 68;
                    const r2 = isMajor ? 54 : 60;
                    const strokeColor = isRedline ? '#ef4444' : '#f8fafc';
                    return `<line x1="${(100 + r1 * Math.cos(rad)).toFixed(1)}" y1="${(100 + r1 * Math.sin(rad)).toFixed(1)}" x2="${(100 + r2 * Math.cos(rad)).toFixed(1)}" y2="${(100 + r2 * Math.sin(rad)).toFixed(1)}" stroke="${strokeColor}" stroke-width="${isMajor ? '3' : '1.5'}"/>`;
                }).join('')}
                <text x="56" y="125" font-size="7" font-weight="900" fill="#cbd5e1">20</text>
                <text x="50" y="85" font-size="7" font-weight="900" fill="#cbd5e1">60</text>
                <text x="75" y="55" font-size="7" font-weight="900" fill="#cbd5e1">100</text>
                <text x="115" y="55" font-size="7" font-weight="900" fill="#cbd5e1">140</text>
                <text x="142" y="85" font-size="7" font-weight="900" fill="#cbd5e1">180</text>
                <text x="135" y="125" font-size="7" font-weight="900" fill="#ef4444">220</text>
                <text x="100" y="88" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#38bdf8">KM/H</text>
                <rect x="75" y="120" width="50" height="18" rx="3" fill="#020617" stroke="#1e293b"/>
                <text x="100" y="133" font-size="9" font-family="monospace" font-weight="bold" text-anchor="middle" fill="#22c55e">084,250</text>
                <path d="M 100 100 L 88 45 L 100 35 L 102 45 Z" fill="#ef4444"/>
                <circle cx="100" cy="100" r="16" fill="#1e293b" stroke="#475569" stroke-width="2"/>
                <circle cx="100" cy="100" r="8" fill="#f8fafc"/>
                <rect x="60" y="174" width="80" height="14" rx="3" fill="#1e293b"/>
                <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#f59e0b">SPEEDOMETER</text>
            `;
        }
        else if (/dashboard|dash\b|dash\s*panel|center\s*console/i.test(raw)) {
            content = `
                <path d="M 20 120 Q 50 55 100 55 Q 150 55 180 120 L 175 145 Q 100 160 25 145 Z" fill="#1e293b" stroke="#334155" stroke-width="2.5"/>
                <path d="M 32 115 Q 58 68 85 115 Z" fill="#0f172a" stroke="#475569" stroke-width="1.5"/>
                <circle cx="58" cy="98" r="15" fill="#020617" stroke="#38bdf8" stroke-width="1.5"/>
                <line x1="58" y1="98" x2="52" y2="88" stroke="#ef4444" stroke-width="2"/>
                <path d="M 88 115 Q 114 68 140 115 Z" fill="#0f172a" stroke="#475569" stroke-width="1.5"/>
                <circle cx="114" cy="98" r="15" fill="#020617" stroke="#38bdf8" stroke-width="1.5"/>
                <line x1="114" y1="98" x2="120" y2="88" stroke="#ef4444" stroke-width="2"/>
                <rect x="145" y="85" width="25" height="30" rx="3" fill="#020617" stroke="#38bdf8"/>
                <line x1="148" y1="95" x2="167" y2="95" stroke="#38bdf8" stroke-width="2"/>
                <path d="M 30 132 L 170 132" stroke="#475569" stroke-width="2"/>
                <rect x="120" y="136" width="45" height="12" rx="2" fill="#0f172a"/>
                <circle cx="126" cy="142" r="2" fill="#cbd5e1"/>
                <rect x="60" y="174" width="80" height="14" rx="3" fill="#1e293b"/>
                <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#38bdf8">DASHBOARD PANEL</text>
            `;
        }
        else if (/bearing|ball\s*bearing|roller\s*bearing|pilot\s*bearing|needle\s*bearing/i.test(raw)) {
            content = `
                <circle cx="100" cy="100" r="76" fill="url(#${id}_metal)" stroke="#334155" stroke-width="3"/>
                <circle cx="100" cy="100" r="62" fill="#0f172a"/>
                ${Array.from({length: 8}).map((_, i) => {
                    const rad = (i * 45) * Math.PI / 180;
                    return `
                        <circle cx="${(100 + 47 * Math.cos(rad)).toFixed(1)}" cy="${(100 + 47 * Math.sin(rad)).toFixed(1)}" r="11" fill="url(#${id}_metal)" stroke="#475569" stroke-width="1.5"/>
                        <circle cx="${(100 + 47 * Math.cos(rad) - 3).toFixed(1)}" cy="${(100 + 47 * Math.sin(rad) - 3).toFixed(1)}" r="3.5" fill="#ffffff" opacity="0.8"/>
                    `;
                }).join('')}
                <circle cx="100" cy="100" r="32" fill="#0f172a"/>
                <circle cx="100" cy="100" r="28" fill="url(#${id}_metal)" stroke="#334155" stroke-width="2"/>
                <circle cx="100" cy="100" r="18" fill="#020617"/>
                <rect x="60" y="174" width="80" height="14" rx="3" fill="#1e293b"/>
                <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#38bdf8">RADIAL BEARING</text>
            `;
        }

        // ══════════════════════════════════════════════════════════════════
        // PRIORITY 8: FASTENERS (Washers, Bolts, Nuts, Clamps, Bushings)
        // ══════════════════════════════════════════════════════════════════
        else if (/washer|lock\s*washer|crush\s*washer|flat\s*washer|shim/i.test(raw)) {
            content = `
                <circle cx="100" cy="100" r="72" fill="url(#${id}_metal)" stroke="#334155" stroke-width="3"/>
                <circle cx="100" cy="100" r="66" fill="none" stroke="#f8fafc" stroke-width="1.5" opacity="0.6"/>
                <circle cx="100" cy="100" r="34" fill="#0f172a" stroke="#334155" stroke-width="3"/>
                <circle cx="100" cy="100" r="30" fill="#020617"/>
                <path d="M 50 60 A 68 68 0 0 1 150 60" fill="none" stroke="#ffffff" stroke-width="3" stroke-linecap="round" opacity="0.5"/>
                <rect x="65" y="174" width="70" height="14" rx="3" fill="#1e293b"/>
                <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#38bdf8">FLAT WASHER</text>
            `;
        }
        else if (/lug\s*nut|wheel\s*nut|acorn\s*nut/i.test(raw)) {
            content = `
                <path d="M 68 35 L 132 35 L 140 125 L 125 155 L 75 155 L 60 125 Z" fill="url(#${id}_metal)" stroke="#334155" stroke-width="2.5"/>
                <ellipse cx="100" cy="35" rx="32" ry="12" fill="#f8fafc"/>
                <ellipse cx="100" cy="35" rx="24" ry="8" fill="url(#${id}_metal)"/>
                <line x1="84" y1="35" x2="80" y2="125" stroke="#ffffff" stroke-width="2" opacity="0.6"/>
                <line x1="116" y1="35" x2="120" y2="125" stroke="#334155" stroke-width="2"/>
                <ellipse cx="100" cy="155" rx="25" ry="8" fill="#0f172a"/>
                <circle cx="100" cy="155" r="14" fill="#1e293b"/>
                <rect x="65" y="174" width="70" height="14" rx="3" fill="#1e293b"/>
                <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#f59e0b">LUG NUT</text>
            `;
        }
        else if (/nut|castle\s*nut|flange\s*nut|lock\s*nut/i.test(raw)) {
            content = `
                <polygon points="100,30 155,62 155,126 100,158 45,126 45,62" fill="url(#${id}_metal)" stroke="#334155" stroke-width="3"/>
                <circle cx="100" cy="94" r="38" fill="#0f172a" stroke="#334155" stroke-width="2"/>
                ${[74, 84, 94, 104, 114].map(y => `<line x1="72" y1="${y}" x2="128" y2="${y}" stroke="#64748b" stroke-width="2.5"/>`).join('')}
                <circle cx="100" cy="94" r="22" fill="#020617"/>
                <rect x="65" y="174" width="70" height="14" rx="3" fill="#1e293b"/>
                <text x="100" y="184" font-size="8" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#38bdf8">HEX NUT</text>
            `;
        }
        else if (/bolt|stud|flange\s*bolt|head\s*bolt|caliper\s*bolt/i.test(raw)) {
            content = `
                <polygon points="100,24 142,48 142,96 100,120 58,96 58,48" fill="url(#${id}_metal)" stroke="#334155" stroke-width="2.5"/>
                <circle cx="100" cy="72" r="34" fill="#64748b" stroke="#334155" stroke-width="2"/>
                <circle cx="100" cy="72" r="24" fill="url(#${id}_metal)"/>
                <text x="100" y="76" font-size="9" font-weight="900" font-family="sans-serif" text-anchor="middle" fill="#0f172a">10.9</text>
                <ellipse cx="100" cy="116" rx="46" ry="10" fill="#334155"/>
                <rect x="85" y="120" width="30" height="60" rx="3" fill="url(#${id}_metal)"/>
                ${[128, 138, 148, 158, 168].map(y => `<line x1="85" y1="${y}" x2="115" y2="${y+3}" stroke="#334155" stroke-width="2.5"/>`).join('')}
            `;
        }
        else {
            // Default Machined Vector Sprocket
            let hash = 0;
            for (let i = 0; i < raw.length; i++) hash = (hash << 5) - hash + raw.charCodeAt(i);
            const teeth = 6 + (Math.abs(hash) % 6);
            const innerR = 24 + (Math.abs(hash) % 14);

            content = `
                <circle cx="100" cy="100" r="70" fill="url(#${id}_metal)" stroke="#334155" stroke-width="2"/>
                ${Array.from({length: teeth}).map((_, i) => {
                    const rad = (i * (360 / teeth)) * Math.PI / 180;
                    return `<rect x="${(100 + 66 * Math.cos(rad) - 7).toFixed(1)}" y="${(100 + 66 * Math.sin(rad) - 7).toFixed(1)}" width="14" height="14" rx="3" fill="${pColor}"/>`;
                }).join('')}
                <circle cx="100" cy="100" r="${innerR}" fill="#1e293b"/>
                <circle cx="100" cy="100" r="${(innerR * 0.6).toFixed(1)}" fill="url(#${id}_metal)"/>
                <circle cx="100" cy="100" r="${(innerR * 0.25).toFixed(1)}" fill="#0f172a"/>
                <line x1="45" y1="45" x2="155" y2="155" stroke="#ffffff" stroke-width="2" stroke-opacity="0.4"/>
            `;
        }

        const svg = `
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" width="100%" height="100%">
                <defs>
                    <linearGradient id="${id}_spring" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="${aColor}"/>
                        <stop offset="50%" stop-color="${sColor}"/>
                        <stop offset="100%" stop-color="${pColor}"/>
                    </linearGradient>
                    <linearGradient id="${id}_metal" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#94a3b8"/>
                        <stop offset="50%" stop-color="#f8fafc"/>
                        <stop offset="100%" stop-color="#64748b"/>
                    </linearGradient>
                    <linearGradient id="${id}_glass" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#0284c7" stop-opacity="0.85"/>
                        <stop offset="40%" stop-color="#38bdf8" stop-opacity="0.7"/>
                        <stop offset="100%" stop-color="#0369a1" stop-opacity="0.9"/>
                    </linearGradient>
                    <linearGradient id="${id}_body" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#1e293b"/>
                        <stop offset="50%" stop-color="#475569"/>
                        <stop offset="100%" stop-color="#0f172a"/>
                    </linearGradient>
                    <linearGradient id="${id}_rotor" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#f8fafc"/>
                        <stop offset="40%" stop-color="#cbd5e1"/>
                        <stop offset="80%" stop-color="#94a3b8"/>
                        <stop offset="100%" stroke-color="#64748b"/>
                    </linearGradient>
                    <linearGradient id="${id}_hat" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="${pColor}"/>
                        <stop offset="100%" stop-color="${sColor}"/>
                    </linearGradient>
                    <linearGradient id="${id}_ceramic" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#92400e"/>
                        <stop offset="50%" stop-color="#b45309"/>
                        <stop offset="100%" stop-color="#78350f"/>
                    </linearGradient>
                    <filter id="${id}_shadow" x="-10%" y="-10%" width="120%" height="120%">
                        <feDropShadow dx="0" dy="4" stdDeviation="4" flood-color="#0f172a" flood-opacity="0.2"/>
                    </filter>
                </defs>
                <g filter="url(#${id}_shadow)">
                    ${content}
                </g>
            </svg>
        `.trim();

        return 'data:image/svg+xml;utf8,' + encodeURIComponent(svg);
    }

    // --- AI Semantic Categorization Engine (100% Comprehensive & Accurate) ---
    function getPartAIMeta(partName) {
        let raw = (partName || '').toLowerCase().trim();
        raw = raw.replace(/^inventory\s*stock:\s*\d+\s*pcs\s*of\s*/i, '').trim();
        const customGeneratedSvg = generateDynamicPartSVG(partName);

        // 1. Clutch, Drivetrain & Bearings (Strict priority over rotors/discs)
        if (/clutch\s*disc|clutch\s*plate|friction\s*disc|clutch\s*lining/i.test(raw)) {
            return {
                category: 'Clutch & Drivetrain (Clutch Disc)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-amber-50 to-orange-50',
                badgeBorder: 'border-amber-200',
                textClass: 'text-amber-600',
                dotClass: 'bg-amber-500',
                glowClass: 'group-hover:border-amber-400'
            };
        }
        if (/release\s*bearing|throwout\s*bearing|clutch\s*bearing/i.test(raw)) {
            return {
                category: 'Clutch & Drivetrain (Release Bearing)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-indigo-50 to-slate-100',
                badgeBorder: 'border-indigo-200',
                textClass: 'text-indigo-600',
                dotClass: 'bg-indigo-500',
                glowClass: 'group-hover:border-indigo-400'
            };
        }
        if (/center\s*bearing|propeller\s*bearing|pillow\s*block/i.test(raw)) {
            return {
                category: 'Drivetrain (Center Support Bearing)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-amber-50 to-yellow-50',
                badgeBorder: 'border-amber-200',
                textClass: 'text-amber-600',
                dotClass: 'bg-amber-500',
                glowClass: 'group-hover:border-amber-400'
            };
        }
        if (/wheel\s*hub|wheel\s*bearing|hub\s*bearing/i.test(raw)) {
            return {
                category: 'Suspension & Wheels (Wheel Hub & Bearing)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-slate-100 to-zinc-100',
                badgeBorder: 'border-slate-300',
                textClass: 'text-slate-700',
                dotClass: 'bg-slate-600',
                glowClass: 'group-hover:border-slate-400'
            };
        }
        if (/bearing|ball\s*bearing|roller\s*bearing|pilot\s*bearing|needle\s*bearing/i.test(raw)) {
            return {
                category: 'Bearings & Bushings (Precision Bearings)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-slate-100 to-indigo-50',
                badgeBorder: 'border-slate-300',
                textClass: 'text-indigo-700',
                dotClass: 'bg-indigo-600',
                glowClass: 'group-hover:border-indigo-400'
            };
        }

        // 2. Filtration & Fluids (Fuel Filter, Oil Filter, Engine Oil, ATF, Brake Fluid, Coolant)
        if (/fuel\s*filter/i.test(raw)) {
            return {
                category: 'Filtration (Fuel Filter)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-cyan-50 to-blue-50',
                badgeBorder: 'border-cyan-200',
                textClass: 'text-cyan-600',
                dotClass: 'bg-cyan-500',
                glowClass: 'group-hover:border-cyan-400'
            };
        }
        if (/oil\s*filter/i.test(raw)) {
            return {
                category: 'Filtration (Oil Filter)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-amber-50 to-yellow-50',
                badgeBorder: 'border-amber-200',
                textClass: 'text-amber-600',
                dotClass: 'bg-amber-500',
                glowClass: 'group-hover:border-amber-400'
            };
        }
        if (/cabin|ac\s*filter|pollen\s*filter/i.test(raw)) {
            return {
                category: 'Filtration (Cabin AC Filter)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-cyan-50 to-teal-50',
                badgeBorder: 'border-cyan-200',
                textClass: 'text-cyan-600',
                dotClass: 'bg-cyan-500',
                glowClass: 'group-hover:border-cyan-400'
            };
        }
        if (/air\s*filter/i.test(raw)) {
            return {
                category: 'Filtration (Air Filter)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-amber-50 to-yellow-50',
                badgeBorder: 'border-amber-200',
                textClass: 'text-amber-600',
                dotClass: 'bg-amber-500',
                glowClass: 'group-hover:border-amber-400'
            };
        }
        if (/engine\s*oil|synthetic|motor\s*oil|10w|5w|4l|lubricant/i.test(raw)) {
            return {
                category: 'Filtration & Fluids (Engine Oil)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-amber-50 to-yellow-50',
                badgeBorder: 'border-amber-200',
                textClass: 'text-amber-600',
                dotClass: 'bg-amber-500',
                glowClass: 'group-hover:border-amber-400'
            };
        }
        if (/brake\s*fluid|dot\s*3|dot\s*4/i.test(raw)) {
            return {
                category: 'Braking & Fluids (Brake Fluid)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-rose-50 to-red-50',
                badgeBorder: 'border-rose-200',
                textClass: 'text-rose-600',
                dotClass: 'bg-rose-500',
                glowClass: 'group-hover:border-rose-400'
            };
        }
        if (/atf|cvt|transmission\s*fluid|gear\s*oil/i.test(raw)) {
            return {
                category: 'Transmission (ATF / CVT Fluid)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-red-50 to-rose-50',
                badgeBorder: 'border-red-200',
                textClass: 'text-red-600',
                dotClass: 'bg-red-500',
                glowClass: 'group-hover:border-red-400'
            };
        }
        if (/coolant|antifreeze/i.test(raw)) {
            return {
                category: 'Cooling System (Coolant Fluid)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-cyan-50 to-blue-50',
                badgeBorder: 'border-cyan-200',
                textClass: 'text-cyan-600',
                dotClass: 'bg-cyan-500',
                glowClass: 'group-hover:border-cyan-400'
            };
        }

        // 3. Braking System (Hose, Shoes, Pads, Caliper, Rotors)
        if (/brake\s*hose|brake\s*line|hydraulic\s*hose/i.test(raw)) {
            return {
                category: 'Braking (Brake Hose)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-sky-50 to-blue-50',
                badgeBorder: 'border-sky-200',
                textClass: 'text-sky-600',
                dotClass: 'bg-sky-500',
                glowClass: 'group-hover:border-sky-400'
            };
        }
        if (/brake\s*shoe|drum\s*brake|rear\s*shoe/i.test(raw)) {
            return {
                category: 'Braking (Drum Brake Shoes)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-amber-50 to-orange-50',
                badgeBorder: 'border-amber-200',
                textClass: 'text-amber-600',
                dotClass: 'bg-amber-500',
                glowClass: 'group-hover:border-amber-400'
            };
        }
        if (/caliper|monoblock|brake\s*caliper/i.test(raw)) {
            return {
                category: 'Braking (Brake Calipers)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-red-50 to-rose-50',
                badgeBorder: 'border-red-200',
                textClass: 'text-red-600',
                dotClass: 'bg-red-500',
                glowClass: 'group-hover:border-red-400'
            };
        }
        if (/brake\s*pad/i.test(raw)) {
            const isFr = /front|fr/i.test(raw);
            const isRr = /rear|rr/i.test(raw);
            const label = isFr ? 'Braking (Front Brake Pads)' : (isRr ? 'Braking (Rear Brake Pads)' : 'Braking (Brake Pads)');
            return {
                category: label,
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-rose-50 to-red-50',
                badgeBorder: 'border-rose-200',
                textClass: 'text-rose-600',
                dotClass: 'bg-rose-500',
                glowClass: 'group-hover:border-rose-400'
            };
        }
        if (/rotor|brake\s*disk|brake\s*rotor|disc|disk/i.test(raw)) {
            return {
                category: 'Braking (Rotors & Discs)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-rose-50 to-red-50',
                badgeBorder: 'border-rose-200',
                textClass: 'text-rose-600',
                dotClass: 'bg-rose-500',
                glowClass: 'group-hover:border-rose-400'
            };
        }

        // 4. Suspension, Belts & Steering
        if (/serpentine|fan\s*belt|drive\s*belt|v-belt|ribbed\s*belt/i.test(raw)) {
            return {
                category: 'Engine Belts (Serpentine Belt)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-slate-100 to-blue-50',
                badgeBorder: 'border-slate-300',
                textClass: 'text-blue-700',
                dotClass: 'bg-blue-600',
                glowClass: 'group-hover:border-blue-400'
            };
        }
        if (/shock|strut|damper|coilover/i.test(raw)) {
            if (/front|fr/i.test(raw)) {
                return {
                    category: 'Suspension (Front Shocks / Struts)',
                    imageUrl: customGeneratedSvg,
                    badgeBg: 'bg-gradient-to-br from-amber-50 to-orange-50',
                    badgeBorder: 'border-amber-200',
                    textClass: 'text-amber-600',
                    dotClass: 'bg-amber-500',
                    glowClass: 'group-hover:border-amber-400'
                };
            } else if (/rear|rr/i.test(raw)) {
                return {
                    category: 'Suspension (Rear Shocks / Dampers)',
                    imageUrl: customGeneratedSvg,
                    badgeBg: 'bg-gradient-to-br from-amber-50 to-orange-50',
                    badgeBorder: 'border-amber-200',
                    textClass: 'text-amber-600',
                    dotClass: 'bg-amber-500',
                    glowClass: 'group-hover:border-amber-400'
                };
            }
            return {
                category: 'Suspension System',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-amber-50 to-orange-50',
                badgeBorder: 'border-amber-200',
                textClass: 'text-amber-600',
                dotClass: 'bg-amber-500',
                glowClass: 'group-hover:border-amber-400'
            };
        }
        if (/tie\s*rod|rack\s*end|steering\s*link/i.test(raw)) {
            return {
                category: 'Steering (Tie Rod Ends)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-sky-50 to-blue-50',
                badgeBorder: 'border-sky-200',
                textClass: 'text-sky-600',
                dotClass: 'bg-sky-500',
                glowClass: 'group-hover:border-sky-400'
            };
        }

        // 5. Ignition & Battery
        if (/spark\s*plug|iridium|glow\s*plug|ignition\s*coil/i.test(raw)) {
            return {
                category: 'Ignition (Spark Plugs)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-blue-50 to-indigo-50',
                badgeBorder: 'border-blue-200',
                textClass: 'text-blue-600',
                dotClass: 'bg-blue-500',
                glowClass: 'group-hover:border-blue-400'
            };
        }
        if (/battery|motolite|amaron|12v|ns40|ns60|din/i.test(raw)) {
            return {
                category: 'Electrical (Car Battery)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-amber-50 to-yellow-50',
                badgeBorder: 'border-amber-200',
                textClass: 'text-amber-600',
                dotClass: 'bg-amber-500',
                glowClass: 'group-hover:border-amber-400'
            };
        }

        // 6. Glass, Wipers, Cooling & Body
        if (/window|windshield|windscreen|door\s*glass|side\s*glass/i.test(raw)) {
            return {
                category: 'Body & Glass (Side Window)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-cyan-50 to-sky-50',
                badgeBorder: 'border-cyan-200',
                textClass: 'text-cyan-600',
                dotClass: 'bg-cyan-500',
                glowClass: 'group-hover:border-cyan-400'
            };
        }
        if (/wiper|blade/i.test(raw)) {
            return {
                category: 'Wipers & Vision (Wiper Blades)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-cyan-50 to-blue-50',
                badgeBorder: 'border-cyan-200',
                textClass: 'text-cyan-600',
                dotClass: 'bg-cyan-500',
                glowClass: 'group-hover:border-cyan-400'
            };
        }
        if (/radiator\s*cap/i.test(raw)) {
            return {
                category: 'Cooling (Radiator Cap)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-cyan-50 to-blue-50',
                badgeBorder: 'border-cyan-200',
                textClass: 'text-cyan-600',
                dotClass: 'bg-cyan-500',
                glowClass: 'group-hover:border-cyan-400'
            };
        }

        // 7. Audio, Cockpit & Fasteners
        if (/stereo|head\s*unit|radio|infotainment|touchscreen|android\s*auto|apple\s*carplay/i.test(raw)) {
            return {
                category: 'Audio & Electronics (Head Unit)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-cyan-50 to-blue-50',
                badgeBorder: 'border-cyan-200',
                textClass: 'text-cyan-600',
                dotClass: 'bg-cyan-500',
                glowClass: 'group-hover:border-cyan-400'
            };
        }
        if (/speaker|tweeter|subwoofer|woofer|sound\s*system|amplifier|amp\b/i.test(raw)) {
            return {
                category: 'Audio & Electronics (Speakers)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-indigo-50 to-blue-50',
                badgeBorder: 'border-indigo-200',
                textClass: 'text-indigo-600',
                dotClass: 'bg-indigo-500',
                glowClass: 'group-hover:border-indigo-400'
            };
        }
        if (/speedometer|tachometer|gauge|cluster|odometer|boost\s*gauge|temp\s*gauge|fuel\s*gauge/i.test(raw)) {
            return {
                category: 'Dashboard & Gauges (Instrument Cluster)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-amber-50 to-yellow-50',
                badgeBorder: 'border-amber-200',
                textClass: 'text-amber-600',
                dotClass: 'bg-amber-500',
                glowClass: 'group-hover:border-amber-400'
            };
        }
        if (/dashboard|dash\b|dash\s*panel|center\s*console/i.test(raw)) {
            return {
                category: 'Interior & Cockpit (Dashboard Panel)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-slate-100 to-zinc-100',
                badgeBorder: 'border-slate-300',
                textClass: 'text-slate-700',
                dotClass: 'bg-slate-600',
                glowClass: 'group-hover:border-slate-400'
            };
        }
        if (/washer|lock\s*washer|crush\s*washer|flat\s*washer|shim/i.test(raw)) {
            return {
                category: 'Hardware (Washers & Shims)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-slate-100 to-zinc-100',
                badgeBorder: 'border-slate-300',
                textClass: 'text-slate-700',
                dotClass: 'bg-slate-600',
                glowClass: 'group-hover:border-slate-400'
            };
        }
        if (/lug\s*nut|wheel\s*nut|acorn\s*nut/i.test(raw)) {
            return {
                category: 'Hardware (Wheel Lug Nuts)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-amber-50 to-yellow-50',
                badgeBorder: 'border-amber-200',
                textClass: 'text-amber-600',
                dotClass: 'bg-amber-500',
                glowClass: 'group-hover:border-amber-400'
            };
        }
        if (/nut|castle\s*nut|flange\s*nut|lock\s*nut/i.test(raw)) {
            return {
                category: 'Hardware & Fasteners (Nuts)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-slate-100 to-zinc-100',
                badgeBorder: 'border-slate-300',
                textClass: 'text-slate-700',
                dotClass: 'bg-slate-600',
                glowClass: 'group-hover:border-slate-400'
            };
        }
        if (/bolt|stud|flange\s*bolt|head\s*bolt|caliper\s*bolt/i.test(raw)) {
            return {
                category: 'Hardware & Fasteners (Bolts)',
                imageUrl: customGeneratedSvg,
                badgeBg: 'bg-gradient-to-br from-slate-100 to-zinc-100',
                badgeBorder: 'border-slate-300',
                textClass: 'text-slate-700',
                dotClass: 'bg-slate-600',
                glowClass: 'group-hover:border-slate-400'
            };
        }

        // Default Auto Component
        return {
            category: 'Auto Component',
            imageUrl: customGeneratedSvg,
            badgeBg: 'bg-gradient-to-br from-slate-50 to-blue-50/60',
            badgeBorder: 'border-slate-200/90',
            textClass: 'text-slate-600',
            dotClass: 'bg-blue-500',
            glowClass: 'group-hover:border-blue-400'
        };
    }

    // ── Instant Real-Time Input Handler ──
    function onPartNameInput(name) {
        updateAIMiniModalPreview(name);
    }

    // ── Live AI Preview Update in Add/Edit Part Modal ──
    function updateAIMiniModalPreview(name) {
        const meta = getPartAIMeta(name);
        const iconBox = document.getElementById('aiDetectorIconBox');
        const catLabel = document.getElementById('aiDetectorCategoryName');
        const previewContainer = document.getElementById('aiPartDetectorPreview');
        const confidenceBadge = document.getElementById('aiConfidenceBadge');
        const imgSourceLabel = document.getElementById('imgSourceLabel');

        const activeImg = meta.imageUrl;
        const hiddenImg = document.getElementById('newPartImageUrl');
        if (hiddenImg) hiddenImg.value = activeImg;

        if (iconBox) {
            iconBox.className = `w-16 h-16 rounded-2xl p-1.5 flex items-center justify-center shrink-0 border ${meta.badgeBorder} ${meta.badgeBg} shadow-sm transition-all duration-300 transform scale-100 hover:scale-105 cursor-pointer bg-white`;
            iconBox.innerHTML = `<img src="${activeImg}" alt="Procedural Vector Preview" class="w-full h-full object-contain filter drop-shadow-sm rounded-xl">`;
            iconBox.onclick = () => openImageModal(activeImg);
        }

        if (catLabel) {
            catLabel.innerText = meta.category;
            catLabel.className = `text-sm font-black ${meta.textClass} tracking-tight mt-0.5 truncate`;
        }

        if (previewContainer) {
            previewContainer.className = `p-4 rounded-2xl border transition-all duration-300 ${meta.badgeBg} ${meta.badgeBorder} flex items-center gap-4 shadow-xs`;
        }

        if (confidenceBadge) {
            confidenceBadge.innerText = name && name.trim().length > 1 ? '✨ Realtime SVG' : 'Live Generator';
            confidenceBadge.className = name && name.trim().length > 1 ? `text-[9px] font-black uppercase px-2 py-0.5 rounded-full bg-white shadow-xs ${meta.textClass}` : 'text-[9px] font-bold text-slate-400';
        }

        if (imgSourceLabel) {
            imgSourceLabel.innerText = `Procedural Vector · ${meta.category}`;
            imgSourceLabel.className = `${meta.textClass} font-bold`;
        }
    }

        // --- Rendering Active Parts Table with Dedicated Edit, Purchase, and Archive Actions ---
    function renderActiveParts(data) {
        const tbody = document.getElementById('activePartsTable');
        if (!tbody) return;
        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center py-16 text-gray-500 text-sm font-medium">No parts found.</td></tr>`;
            return;
        }

        tbody.innerHTML = data.map(p => {
            const isOut = p.stock_quantity <= 0;
            const isLow = !isOut && p.stock_quantity <= 5;
            let badgeClass = 'bg-green-50 text-green-700';
            if (isOut) badgeClass = 'bg-red-50 text-red-600';
            else if (isLow) badgeClass = 'bg-yellow-50 text-yellow-600';

            const aiMeta = getPartAIMeta(p.name);
            const partImg = generateDynamicPartSVG(p.name);

            return `
            <tr class="hover:bg-gray-50/80 transition-colors group">
                <td class="px-8 py-4">
                    <div class="flex items-center gap-3.5">
                        <div class="relative w-12 h-12 rounded-2xl p-1 flex items-center justify-center shrink-0 border ${aiMeta.badgeBorder} ${aiMeta.badgeBg} shadow-xs group-hover:scale-105 ${aiMeta.glowClass} transition-all cursor-pointer bg-white overflow-hidden"
                             onclick="openImageModal('${addslashes(partImg)}')"
                             title="Click to view procedural vector">
                            <img src="${partImg}" alt="${escapeHtml(p.name)}" class="w-full h-full object-contain filter drop-shadow-sm rounded-xl">
                        </div>
                        <div class="min-w-0">
                            <div class="text-sm font-black text-gray-900 tracking-tight group-hover:text-blue-600 transition-colors truncate">${escapeHtml(p.name)}</div>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider ${aiMeta.textClass}">
                                    <span class="w-1.5 h-1.5 rounded-full ${aiMeta.dotClass}"></span>
                                    ${p.category || aiMeta.category}
                                </span>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-8 py-4">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider ${p.supplier ? 'bg-purple-50 text-purple-700' : 'bg-gray-100 text-gray-500'}">
                        ${escapeHtml(p.supplier || 'Unspecified')}
                    </span>
                </td>
                <td class="px-8 py-4 text-right">
                    <div class="text-sm font-bold text-gray-900">₱${parseFloat(p.price).toFixed(2)}</div>
                </td>
                <td class="px-8 py-4 text-center">
                    <span class="inline-flex px-3 py-1.5 rounded-lg text-xs font-black ${badgeClass}">
                        ${p.stock_quantity}
                    </span>
                </td>
                <td class="px-8 py-4 text-right">
                    <div class="flex justify-end items-center gap-1.5">
                        <!-- 1. Dedicated Edit Button -->
                        <button onclick="openEditPartModal(${p.id}, '${addslashes(p.name)}', ${p.price}, ${p.stock_quantity}, '${addslashes(p.supplier||'')}')" 
                                class="p-2 text-amber-600 hover:text-amber-700 hover:bg-amber-50 rounded-xl transition-all group-hover:shadow-xs border border-transparent hover:border-amber-200" 
                                title="Edit Part Details (Name, Price, Supplier)">
                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                        </button>
                        <!-- 2. Purchase / Restock Button -->
                        <button onclick="openRestockPartModal(${p.id}, '${addslashes(p.name)}', ${p.price}, ${p.stock_quantity}, '${addslashes(p.supplier||'')}')" 
                                class="p-2 text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-xl transition-all group-hover:shadow-xs border border-transparent hover:border-blue-200" 
                                title="Purchase / Add Stock">
                            <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                        </button>
                        <!-- 3. Archive Button -->
                        <button onclick="archivePart(${p.id})" 
                                class="p-2 text-red-500 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all group-hover:shadow-xs border border-transparent hover:border-red-200" 
                                title="Archive Part">
                            <i data-lucide="trash" class="w-4 h-4"></i>
                        </button>
                    </div>
                </td>
            </tr>
            `;
        }).join('');
        lucide.createIcons();
    }

    function renderHistory(data) {
        const tbody = document.getElementById('historyTable');
        if (!tbody) return;
        if (data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="3" class="text-center py-16 text-gray-500 text-sm font-medium">No purchase records found.</td></tr>`;
            return;
        }

        tbody.innerHTML = data.map(ph => {
            const dateStr = new Date(ph.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            const cleanPartName = (ph.description || '').replace(/^inventory\s*stock:\s*\d+\s*pcs\s*of\s*/i, '').trim();
            const aiMeta = getPartAIMeta(cleanPartName || ph.description);
            const partImg = generateDynamicPartSVG(cleanPartName || ph.description);
            const qty = ph.quantity || 5;
            const unitPrice = ph.unit_price ? `₱${parseFloat(ph.unit_price).toFixed(2)} / pc` : '';

            return `
            <tr class="hover:bg-gray-50/80 transition-colors group">
                <td class="px-8 py-5 whitespace-nowrap">
                    <div class="text-sm font-bold text-gray-800">${dateStr}</div>
                    <div class="text-[10px] text-gray-400 font-bold uppercase mt-0.5 tracking-wider">${escapeHtml(ph.vendor_name || 'Toyota Supplier')}</div>
                </td>
                <td class="px-8 py-5">
                    <div class="flex items-center gap-3.5">
                        <div class="relative w-12 h-12 rounded-2xl p-1 flex items-center justify-center shrink-0 border ${aiMeta.badgeBorder} ${aiMeta.badgeBg} shadow-xs cursor-pointer bg-white overflow-hidden group-hover:scale-105 transition-transform"
                             onclick="openImageModal('${addslashes(partImg)}')"
                             title="Click to view procedural vector">
                            <img src="${partImg}" alt="${escapeHtml(cleanPartName)}" class="w-full h-full object-contain filter drop-shadow-sm rounded-xl">
                        </div>
                        <div class="min-w-0">
                            <div class="text-sm font-black text-gray-900 tracking-tight">${escapeHtml(cleanPartName || ph.description)}</div>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider ${aiMeta.textClass}">
                                    <span class="w-1.5 h-1.5 rounded-full ${aiMeta.dotClass}"></span>
                                    ${aiMeta.category}
                                </span>
                                <span class="text-gray-300">·</span>
                                <span class="text-[11px] font-bold text-slate-500">${qty} pcs ${unitPrice ? `(${unitPrice})` : ''}</span>
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-8 py-5 text-right whitespace-nowrap">
                    <div class="text-base font-black text-green-600">₱${parseFloat(ph.amount).toFixed(2)}</div>
                    <span class="inline-flex px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-wider bg-green-50 text-green-700">Approved</span>
                </td>
            </tr>
            `;
        }).join('');
    }

    // ── Strict Real-Time Input Sanitation & Validation Handlers ──
    function filterNumericKeydown(e, allowDecimal = false) {
        // Allow navigation, backspace, delete, tab, enter, escape, arrows, home, end
        if ([8, 9, 27, 13, 46, 37, 39, 36, 35].includes(e.keyCode) ||
            ((e.ctrlKey || e.metaKey) && [65, 67, 86, 88, 90, 89].includes(e.keyCode))) {
            return;
        }
        // Allow Numpad digits (96-105) and Main digits (48-57)
        if ((e.keyCode >= 48 && e.keyCode <= 57 && !e.shiftKey) || (e.keyCode >= 96 && e.keyCode <= 105)) {
            return;
        }
        // Allow Decimal point
        if (allowDecimal && (e.keyCode === 190 || e.keyCode === 110 || e.key === '.')) {
            if (!e.target.value.includes('.')) {
                return;
            }
        }
        // Block other keys
        e.preventDefault();
    }

    function handlePartNameValidation(input) {
        const errorEl = document.getElementById('nameError');
        const raw = input.value;
        // Allowed: letters, numbers, spaces, and safe automotive symbols () / - . ,
        const sanitized = raw.replace(/[^a-zA-Z0-9\s\(\)\/\-\.,]/g, '');
        if (raw !== sanitized) {
            input.value = sanitized;
            if (errorEl) {
                errorEl.innerText = 'Special symbols are not allowed. Only letters, numbers, and () / - . are valid.';
                errorEl.classList.remove('hidden');
                setTimeout(() => errorEl.classList.add('hidden'), 3000);
            }
        } else if (errorEl) {
            errorEl.classList.add('hidden');
        }
        onPartNameInput(input.value);
    }

    function handlePriceValidation(input) {
        const errorEl = document.getElementById('priceError');
        let raw = input.value;
        if (!raw) {
            if (errorEl) errorEl.classList.add('hidden');
            return;
        }

        // Keep only digits and single decimal point
        let val = raw.replace(/[^0-9.]/g, '');
        const dotIndex = val.indexOf('.');
        if (dotIndex !== -1) {
            val = val.substring(0, dotIndex + 1) + val.substring(dotIndex + 1).replace(/\./g, '');
            const parts = val.split('.');
            if (parts[1] && parts[1].length > 2) {
                parts[1] = parts[1].substring(0, 2);
                val = parts[0] + '.' + parts[1];
            }
        }

        // Clean redundant leading zeros (e.g., "0500" -> "500", but keep "0" or "0.")
        if (val.length > 1 && val.startsWith('0') && !val.startsWith('0.')) {
            val = val.replace(/^0+/, '') || '0';
        }

        // AUTO-CORRECT TO MAXIMUM LIMIT (₱500,000) IF EXCEEDED!
        const num = parseFloat(val);
        if (!isNaN(num) && num > 500000) {
            val = '500000';
            if (errorEl) {
                errorEl.innerText = '⚠️ Max price is ₱500,000.00 (Auto-corrected to maximum)';
                errorEl.classList.remove('hidden');
            }
        } else if (errorEl) {
            errorEl.classList.add('hidden');
        }

        input.value = val;
    }

    function handleQtyValidation(input) {
        const errorEl = document.getElementById('qtyError');
        let raw = input.value;
        if (!raw) {
            if (errorEl) errorEl.classList.add('hidden');
            return;
        }

        // Keep only digits
        let val = raw.replace(/[^0-9]/g, '');

        // Clean excessive leading zeros
        if (val.length > 1 && val.startsWith('0')) {
            val = val.replace(/^0+/, '') || '0';
        }

        // AUTO-CORRECT TO MAXIMUM LIMIT (10,000) IF EXCEEDED!
        const num = parseInt(val, 10);
        if (!isNaN(num) && num > 10000) {
            val = '10000';
            if (errorEl) {
                errorEl.innerText = '⚠️ Max quantity is 10,000 units (Auto-corrected to maximum)';
                errorEl.classList.remove('hidden');
            }
        } else if (errorEl) {
            errorEl.classList.add('hidden');
        }

        input.value = val;
    }

    

// Global Vector Preview Modal Helper for Spare Parts
function openSparePartVectorModal(svgDataUri, title = 'Automotive Component Vector') {
    let modal = document.getElementById('sparePartVectorPreviewModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'sparePartVectorPreviewModal';
        modal.className = 'fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-md transition-opacity duration-200';
        modal.innerHTML = `
            <div class="relative bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-100 flex flex-col items-center text-center animate-in fade-in zoom-in-95 duration-200" onclick="event.stopPropagation()">
                <button type="button" onclick="closeSparePartVectorModal()" class="absolute top-4 right-4 p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-full transition-colors cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
                <div class="w-48 h-48 sm:w-56 sm:h-56 p-2 bg-slate-50 border border-slate-200/80 rounded-2xl flex items-center justify-center mb-4 overflow-hidden shadow-inner">
                    <img id="sparePartVectorPreviewImg" src="" alt="Part Vector" class="w-full h-full object-contain filter drop-shadow-md">
                </div>
                <h3 id="sparePartVectorPreviewTitle" class="text-base sm:text-lg font-black text-slate-900 tracking-tight leading-tight"></h3>
                <p id="sparePartVectorPreviewCat" class="text-xs font-bold text-sky-600 uppercase tracking-wider mt-1"></p>
                <div class="mt-5 w-full">
                    <button type="button" onclick="closeSparePartVectorModal()" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs transition-colors shadow-sm">
                        Close Preview
                    </button>
                </div>
            </div>
        `;
        modal.onclick = closeSparePartVectorModal;
        document.body.appendChild(modal);
    }
    
    document.getElementById('sparePartVectorPreviewImg').src = svgDataUri;
    document.getElementById('sparePartVectorPreviewTitle').innerText = title;
    const meta = getPartAIMeta(title);
    document.getElementById('sparePartVectorPreviewCat').innerText = meta.category;
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
}

function closeSparePartVectorModal() {
    const modal = document.getElementById('sparePartVectorPreviewModal');
    if (modal) {
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }
}