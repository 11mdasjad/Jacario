<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GeneratePoloAssets extends Command
{
    protected $signature = 'jacario:generate-assets';
    protected $description = 'Generate luxury vector Polo T-shirt assets for JACARIO';

    public function handle()
    {
        $colors = [
            'black' => ['main' => '#18181B', 'dark' => '#09090B', 'light' => '#27272A', 'collar' => '#121214', 'button' => '#D4D4D8', 'accent' => '#C5A880'],
            'white' => ['main' => '#F8FAFC', 'dark' => '#E2E8F0', 'light' => '#FFFFFF', 'collar' => '#EDEFEF', 'button' => '#94A3B8', 'accent' => '#C5A880'],
            'navy' => ['main' => '#1E293B', 'dark' => '#0F172A', 'light' => '#334155', 'collar' => '#151D2A', 'button' => '#CBD5E1', 'accent' => '#D4AF37'],
            'olive' => ['main' => '#3F4E3F', 'dark' => '#2B372B', 'light' => '#536553', 'collar' => '#232D23', 'button' => '#D1D5DB', 'accent' => '#C5A880'],
            'grey' => ['main' => '#64748B', 'dark' => '#475569', 'light' => '#94A3B8', 'collar' => '#3E4C5E', 'button' => '#F1F5F9', 'accent' => '#CBD5E1'],
            'burgundy' => ['main' => '#5C1D24', 'dark' => '#3B1015', 'light' => '#7A2832', 'collar' => '#320B0F', 'button' => '#FDE047', 'accent' => '#E5B887'],
            'sand' => ['main' => '#D6C7B2', 'dark' => '#B8A58B', 'light' => '#EAE2D5', 'collar' => '#A89478', 'button' => '#4B5563', 'accent' => '#374151'],
            'forest' => ['main' => '#1B4332', 'dark' => '#081C15', 'light' => '#2D6A4F', 'collar' => '#0D2B1D', 'button' => '#E5E7EB', 'accent' => '#D4AF37'],
            'charcoal' => ['main' => '#334155', 'dark' => '#1E293B', 'light' => '#475569', 'collar' => '#151D29', 'button' => '#F8FAFC', 'accent' => '#C5A880'],
            'royal-blue' => ['main' => '#1D4ED8', 'dark' => '#1E40AF', 'light' => '#3B82F6', 'collar' => '#172554', 'button' => '#FFFFFF', 'accent' => '#FEF08A'],
        ];

        $dir = public_path('images/polos');
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        foreach ($colors as $name => $c) {
            $svg = $this->renderPoloSvg($name, $c);
            File::put("{$dir}/{$name}-polo.svg", $svg);
            // Also generate a side/detail view
            $detailSvg = $this->renderDetailPoloSvg($name, $c);
            File::put("{$dir}/{$name}-polo-detail.svg", $detailSvg);
        }

        // Default placeholder
        File::copy("{$dir}/black-polo.svg", public_path('images/placeholder-polo.svg'));

        $this->info('Generated luxury vector polo artwork successfully.');
        return 0;
    }

    private function renderPoloSvg(string $name, array $c): string
    {
        $id = 'polo_' . $name;
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 700" width="100%" height="100%" class="w-full h-full object-contain">
  <defs>
    <linearGradient id="{$id}_body" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="{$c['light']}" />
      <stop offset="50%" stop-color="{$c['main']}" />
      <stop offset="100%" stop-color="{$c['dark']}" />
    </linearGradient>
    <linearGradient id="{$id}_collar" x1="0%" y1="0%" x2="100%" y2="50%">
      <stop offset="0%" stop-color="{$c['light']}" />
      <stop offset="100%" stop-color="{$c['collar']}" />
    </linearGradient>
    <linearGradient id="{$id}_sleeve_left" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="{$c['light']}" />
      <stop offset="100%" stop-color="{$c['dark']}" />
    </linearGradient>
    <linearGradient id="{$id}_sleeve_right" x1="100%" y1="0%" x2="0%" y2="100%">
      <stop offset="0%" stop-color="{$c['light']}" />
      <stop offset="100%" stop-color="{$c['dark']}" />
    </linearGradient>
    <filter id="{$id}_shadow" x="-10%" y="-10%" width="120%" height="130%">
      <feDropShadow dx="0" dy="16" stdDeviation="20" flood-color="#000000" flood-opacity="0.18"/>
    </filter>
    <pattern id="{$id}_pique" width="6" height="6" patternUnits="userSpaceOnUse">
      <circle cx="2" cy="2" r="0.8" fill="#ffffff" fill-opacity="0.04" />
      <circle cx="5" cy="5" r="0.8" fill="#000000" fill-opacity="0.06" />
    </pattern>
  </defs>

  <!-- Background Soft Radial -->
  <rect width="600" height="700" fill="#f8f9fa" opacity="0.4" rx="20"/>

  <g filter="url(#{$id}_shadow)">
    <!-- Main Body Contour -->
    <path d="M 210,130 L 130,195 L 85,280 L 140,310 L 175,255 L 180,600 C 180,615 420,615 420,600 L 425,255 L 460,310 L 515,280 L 470,195 L 390,130 C 340,145 260,145 210,130 Z" 
          fill="url(#{$id}_body)" stroke="{$c['dark']}" stroke-width="2" stroke-linejoin="round" />

    <!-- Pique Texture Overlay -->
    <path d="M 210,130 L 130,195 L 85,280 L 140,310 L 175,255 L 180,600 C 180,615 420,615 420,600 L 425,255 L 460,310 L 515,280 L 470,195 L 390,130 C 340,145 260,145 210,130 Z" 
          fill="url(#{$id}_pique)" />

    <!-- Left Sleeve Cuff -->
    <path d="M 85,280 L 140,310 L 135,322 L 78,290 Z" fill="{$c['collar']}" stroke="{$c['dark']}" stroke-width="1.5"/>
    <line x1="86" y1="284" x2="137" y2="312" stroke="{$c['accent']}" stroke-width="1.5" opacity="0.6"/>

    <!-- Right Sleeve Cuff -->
    <path d="M 515,280 L 460,310 L 465,322 L 522,290 Z" fill="{$c['collar']}" stroke="{$c['dark']}" stroke-width="1.5"/>
    <line x1="514" y1="284" x2="463" y2="312" stroke="{$c['accent']}" stroke-width="1.5" opacity="0.6"/>

    <!-- Bottom Hem Details -->
    <path d="M 180,590 C 240,598 360,598 420,590" stroke="{$c['dark']}" stroke-width="2" fill="none" opacity="0.5"/>
    <path d="M 180,594 C 240,602 360,602 420,594" stroke="{$c['light']}" stroke-width="1" stroke-dasharray="4,4" fill="none" opacity="0.7"/>

    <!-- Side Slits -->
    <line x1="180" y1="575" x2="180" y2="600" stroke="{$c['dark']}" stroke-width="3"/>
    <line x1="420" y1="575" x2="420" y2="600" stroke="{$c['dark']}" stroke-width="3"/>

    <!-- Inner Neck Label Patch -->
    <path d="M 260,138 C 280,148 320,148 340,138 L 335,185 C 315,190 285,190 265,185 Z" fill="#0B0D10" />
    <text x="300" y="165" fill="#C5A880" font-family="'Cinzel', 'Playfair Display', serif" font-size="10" font-weight="bold" letter-spacing="2" text-anchor="middle">JACARIO</text>
    <text x="300" y="177" fill="#A1A1AA" font-family="sans-serif" font-size="6" letter-spacing="1" text-anchor="middle">100% SUPIMA</text>

    <!-- Placket Center Box -->
    <path d="M 276,140 L 324,140 L 324,285 L 300,305 L 276,285 Z" fill="{$c['main']}" stroke="{$c['dark']}" stroke-width="1.5"/>
    <path d="M 276,140 L 324,140 L 324,285 L 300,305 L 276,285 Z" fill="url(#{$id}_pique)"/>

    <!-- Placket Stitching Lines -->
    <line x1="280" y1="145" x2="280" y2="280" stroke="{$c['dark']}" stroke-width="1" stroke-dasharray="3,3" opacity="0.6"/>
    <line x1="320" y1="145" x2="320" y2="280" stroke="{$c['dark']}" stroke-width="1" stroke-dasharray="3,3" opacity="0.6"/>

    <!-- Mother of Pearl Buttons -->
    <g transform="translate(300, 180)">
      <circle cx="0" cy="0" r="6" fill="{$c['button']}" stroke="#64748B" stroke-width="0.8"/>
      <circle cx="0" cy="0" r="4" fill="none" stroke="#475569" stroke-width="0.5"/>
      <circle cx="-1.5" cy="-1.5" r="0.6" fill="#1E293B"/>
      <circle cx="1.5" cy="-1.5" r="0.6" fill="#1E293B"/>
      <circle cx="-1.5" cy="1.5" r="0.6" fill="#1E293B"/>
      <circle cx="1.5" cy="1.5" r="0.6" fill="#1E293B"/>
    </g>

    <g transform="translate(300, 220)">
      <circle cx="0" cy="0" r="6" fill="{$c['button']}" stroke="#64748B" stroke-width="0.8"/>
      <circle cx="0" cy="0" r="4" fill="none" stroke="#475569" stroke-width="0.5"/>
      <circle cx="-1.5" cy="-1.5" r="0.6" fill="#1E293B"/>
      <circle cx="1.5" cy="-1.5" r="0.6" fill="#1E293B"/>
      <circle cx="-1.5" cy="1.5" r="0.6" fill="#1E293B"/>
      <circle cx="1.5" cy="1.5" r="0.6" fill="#1E293B"/>
    </g>

    <g transform="translate(300, 260)">
      <circle cx="0" cy="0" r="6" fill="{$c['button']}" stroke="#64748B" stroke-width="0.8"/>
      <circle cx="0" cy="0" r="4" fill="none" stroke="#475569" stroke-width="0.5"/>
      <circle cx="-1.5" cy="-1.5" r="0.6" fill="#1E293B"/>
      <circle cx="1.5" cy="-1.5" r="0.6" fill="#1E293B"/>
      <circle cx="-1.5" cy="1.5" r="0.6" fill="#1E293B"/>
      <circle cx="1.5" cy="1.5" r="0.6" fill="#1E293B"/>
    </g>

    <!-- Ribbed Collar Left -->
    <path d="M 210,130 C 235,138 275,145 285,150 L 265,225 C 245,190 220,165 210,130 Z" 
          fill="url(#{$id}_collar)" stroke="{$c['dark']}" stroke-width="2"/>
    <path d="M 212,133 C 235,141 273,148 283,153 L 265,221" stroke="{$c['accent']}" stroke-width="1.5" fill="none" opacity="0.6"/>

    <!-- Ribbed Collar Right -->
    <path d="M 390,130 C 365,138 325,145 315,150 L 335,225 C 355,190 380,165 390,130 Z" 
          fill="url(#{$id}_collar)" stroke="{$c['dark']}" stroke-width="2"/>
    <path d="M 388,133 C 365,141 327,148 317,153 L 335,221" stroke="{$c['accent']}" stroke-width="1.5" fill="none" opacity="0.6"/>

    <!-- Chest JACARIO Emblem -->
    <g transform="translate(365, 255)">
      <!-- Minimalist Luxury Monogram Emblem -->
      <circle cx="0" cy="0" r="11" fill="none" stroke="{$c['accent']}" stroke-width="1.2" opacity="0.85"/>
      <path d="M -4,-5 L 2,-5 C 5,-5 5,-1 2,-1 L -4,-1 M -1,-1 L -1,5 C -1,7 -4,7 -4,5" 
            fill="none" stroke="{$c['accent']}" stroke-width="1.4" stroke-linecap="round" opacity="0.95"/>
    </g>

    <!-- Subtle Folds & Muscle Contours -->
    <path d="M 185,270 C 210,300 220,380 215,480" stroke="{$c['dark']}" stroke-width="3" fill="none" opacity="0.12"/>
    <path d="M 415,270 C 390,300 380,380 385,480" stroke="{$c['dark']}" stroke-width="3" fill="none" opacity="0.12"/>
  </g>
</svg>
SVG;
    }

    private function renderDetailPoloSvg(string $name, array $c): string
    {
        $id = 'polo_det_' . $name;
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 700" width="100%" height="100%" class="w-full h-full object-contain">
  <defs>
    <linearGradient id="{$id}_bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="{$c['light']}" />
      <stop offset="60%" stop-color="{$c['main']}" />
      <stop offset="100%" stop-color="{$c['dark']}" />
    </linearGradient>
    <pattern id="{$id}_pique" width="5" height="5" patternUnits="userSpaceOnUse">
      <circle cx="2" cy="2" r="0.9" fill="#ffffff" fill-opacity="0.06" />
      <circle cx="4" cy="4" r="0.9" fill="#000000" fill-opacity="0.08" />
    </pattern>
  </defs>

  <rect width="600" height="700" fill="#f8f9fa" opacity="0.4" rx="20"/>

  <!-- Zoomed Close-up Placket & Collar & Luxury Fabric View -->
  <g transform="translate(0, 0)">
    <!-- Fabric Background -->
    <rect x="50" y="50" width="500" height="600" rx="16" fill="url(#{$id}_bg)"/>
    <rect x="50" y="50" width="500" height="600" rx="16" fill="url(#{$id}_pique)"/>

    <!-- Close-up Placket Section -->
    <rect x="220" y="80" width="160" height="500" fill="{$c['main']}" stroke="{$c['dark']}" stroke-width="3"/>
    <rect x="220" y="80" width="160" height="500" fill="url(#{$id}_pique)"/>
    
    <!-- Fine Stitching -->
    <line x1="235" y1="80" x2="235" y2="580" stroke="{$c['dark']}" stroke-width="2" stroke-dasharray="6,6"/>
    <line x1="365" y1="80" x2="365" y2="580" stroke="{$c['dark']}" stroke-width="2" stroke-dasharray="6,6"/>

    <!-- Top Button -->
    <g transform="translate(300, 180) scale(1.8)">
      <circle cx="0" cy="0" r="10" fill="{$c['button']}" stroke="#64748B" stroke-width="1.2"/>
      <circle cx="0" cy="0" r="7" fill="none" stroke="#475569" stroke-width="0.8"/>
      <circle cx="-3" cy="-3" r="1" fill="#1E293B"/>
      <circle cx="3" cy="-3" r="1" fill="#1E293B"/>
      <circle cx="-3" cy="3" r="1" fill="#1E293B"/>
      <circle cx="3" cy="3" r="1" fill="#1E293B"/>
      <line x1="-3" y1="-3" x2="3" y2="3" stroke="#D4AF37" stroke-width="0.8"/>
      <line x1="3" y1="-3" x2="-3" y2="3" stroke="#D4AF37" stroke-width="0.8"/>
    </g>

    <!-- Bottom Button -->
    <g transform="translate(300, 360) scale(1.8)">
      <circle cx="0" cy="0" r="10" fill="{$c['button']}" stroke="#64748B" stroke-width="1.2"/>
      <circle cx="0" cy="0" r="7" fill="none" stroke="#475569" stroke-width="0.8"/>
      <circle cx="-3" cy="-3" r="1" fill="#1E293B"/>
      <circle cx="3" cy="-3" r="1" fill="#1E293B"/>
      <circle cx="-3" cy="3" r="1" fill="#1E293B"/>
      <circle cx="3" cy="3" r="1" fill="#1E293B"/>
      <line x1="-3" y1="-3" x2="3" y2="3" stroke="#D4AF37" stroke-width="0.8"/>
      <line x1="3" y1="-3" x2="-3" y2="3" stroke="#D4AF37" stroke-width="0.8"/>
    </g>

    <!-- Luxury Seal Label Badge -->
    <g transform="translate(300, 520)">
      <rect x="-120" y="-30" width="240" height="60" rx="8" fill="#0B0D10" stroke="{$c['accent']}" stroke-width="1.5"/>
      <text x="0" y="-5" fill="{$c['accent']}" font-family="'Cinzel', serif" font-size="14" font-weight="bold" letter-spacing="3" text-anchor="middle">JACARIO</text>
      <text x="0" y="16" fill="#A1A1AA" font-family="sans-serif" font-size="9" letter-spacing="2" text-anchor="middle">CRAFTED ELEGANCE</text>
    </g>
  </g>
</svg>
SVG;
    }
}
