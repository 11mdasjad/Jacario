<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateCustomizationAssets extends Command
{
    protected $signature = 'jacario:generate-customization-assets';
    protected $description = 'Generate luxury vector Polo and Crewneck T-Shirt studio templates for JACARIO Customizer';

    public function handle()
    {
        $colors = [
            'black' => ['name' => 'Obsidian Black', 'hex' => '#18181B', 'main' => '#18181B', 'dark' => '#09090B', 'light' => '#27272A', 'rib' => '#121214', 'accent' => '#C5A880'],
            'white' => ['name' => 'Pure White', 'hex' => '#F8FAFC', 'main' => '#F8FAFC', 'dark' => '#E2E8F0', 'light' => '#FFFFFF', 'rib' => '#EDEFEF', 'accent' => '#C5A880'],
            'navy' => ['name' => 'Royal Navy', 'hex' => '#1E293B', 'main' => '#1E293B', 'dark' => '#0F172A', 'light' => '#334155', 'rib' => '#151D2A', 'accent' => '#D4AF37'],
            'charcoal' => ['name' => 'Slate Charcoal', 'hex' => '#334155', 'main' => '#334155', 'dark' => '#1E293B', 'light' => '#475569', 'rib' => '#151D29', 'accent' => '#C5A880'],
            'burgundy' => ['name' => 'Burgundy Royale', 'hex' => '#5C1D24', 'main' => '#5C1D24', 'dark' => '#3B1015', 'light' => '#7A2832', 'rib' => '#320B0F', 'accent' => '#E5B887'],
            'forest' => ['name' => 'Forest Green', 'hex' => '#1B4332', 'main' => '#1B4332', 'dark' => '#081C15', 'light' => '#2D6A4F', 'rib' => '#0D2B1D', 'accent' => '#D4AF37'],
            'sand' => ['name' => 'Sand Beige', 'hex' => '#D6C7B2', 'main' => '#D6C7B2', 'dark' => '#B8A58B', 'light' => '#EAE2D5', 'rib' => '#A89478', 'accent' => '#374151'],
            'royal-blue' => ['name' => 'Royal Blue', 'hex' => '#1D4ED8', 'main' => '#1D4ED8', 'dark' => '#1E40AF', 'light' => '#3B82F6', 'rib' => '#172554', 'accent' => '#FEF08A'],
            'grey' => ['name' => 'Heather Grey', 'hex' => '#64748B', 'main' => '#64748B', 'dark' => '#475569', 'light' => '#94A3B8', 'rib' => '#3E4C5E', 'accent' => '#CBD5E1'],
            'olive' => ['name' => 'Olive Dusk', 'hex' => '#3F4E3F', 'main' => '#3F4E3F', 'dark' => '#2B372B', 'light' => '#536553', 'rib' => '#232D23', 'accent' => '#C5A880'],
        ];

        $poloDir = public_path('images/customizer/polo');
        $tshirtDir = public_path('images/customizer/tshirt');

        File::ensureDirectoryExists($poloDir);
        File::ensureDirectoryExists($tshirtDir);

        foreach ($colors as $slug => $c) {
            File::put("{$poloDir}/{$slug}.svg", $this->renderPoloFront($slug, $c));
            File::put("{$tshirtDir}/{$slug}.svg", $this->renderTshirtFront($slug, $c));
        }

        $this->info('Generated Customizer SVG assets for Polo and Crewneck T-Shirt successfully.');
        return 0;
    }

    private function renderPoloFront(string $slug, array $c): string
    {
        $id = "polo_cust_{$slug}";
        $isLight = in_array($slug, ['white', 'sand', 'grey']);
        $textColor = $isLight ? '#18181B' : '#FFFFFF';

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
      <stop offset="100%" stop-color="{$c['rib']}" />
    </linearGradient>
    <filter id="{$id}_shadow" x="-10%" y="-10%" width="120%" height="130%">
      <feDropShadow dx="0" dy="16" stdDeviation="20" flood-color="#000000" flood-opacity="0.18"/>
    </filter>
    <pattern id="{$id}_pique" width="6" height="6" patternUnits="userSpaceOnUse">
      <circle cx="2" cy="2" r="0.8" fill="#ffffff" fill-opacity="0.04" />
      <circle cx="5" cy="5" r="0.8" fill="#000000" fill-opacity="0.06" />
    </pattern>
  </defs>

  <g filter="url(#{$id}_shadow)">
    <!-- Main Body Contour -->
    <path d="M 210,130 L 130,195 L 85,280 L 140,310 L 175,255 L 180,600 C 180,615 420,615 420,600 L 425,255 L 460,310 L 515,280 L 470,195 L 390,130 C 340,145 260,145 210,130 Z" 
          fill="url(#{$id}_body)" stroke="{$c['dark']}" stroke-width="2" stroke-linejoin="round" />

    <!-- Pique Texture Overlay -->
    <path d="M 210,130 L 130,195 L 85,280 L 140,310 L 175,255 L 180,600 C 180,615 420,615 420,600 L 425,255 L 460,310 L 515,280 L 470,195 L 390,130 C 340,145 260,145 210,130 Z" 
          fill="url(#{$id}_pique)" />

    <!-- Left Sleeve Cuff -->
    <path d="M 85,280 L 140,310 L 135,322 L 78,290 Z" fill="{$c['rib']}" stroke="{$c['dark']}" stroke-width="1.5"/>
    <line x1="86" y1="284" x2="137" y2="312" stroke="{$c['accent']}" stroke-width="1.5" opacity="0.6"/>

    <!-- Right Sleeve Cuff -->
    <path d="M 515,280 L 460,310 L 465,322 L 522,290 Z" fill="{$c['rib']}" stroke="{$c['dark']}" stroke-width="1.5"/>
    <line x1="514" y1="284" x2="463" y2="312" stroke="{$c['accent']}" stroke-width="1.5" opacity="0.6"/>

    <!-- Bottom Hem Details -->
    <path d="M 180,590 C 240,598 360,598 420,590" stroke="{$c['dark']}" stroke-width="2" fill="none" opacity="0.5"/>
    <path d="M 180,594 C 240,602 360,602 420,594" stroke="{$c['light']}" stroke-width="1" stroke-dasharray="4,4" fill="none" opacity="0.7"/>

    <!-- Inner Neck Label Patch -->
    <path d="M 260,138 C 280,148 320,148 340,138 L 335,185 C 315,190 285,190 265,185 Z" fill="#0B0D10" />
    <text x="300" y="165" fill="#C5A880" font-family="'Cinzel', 'Playfair Display', serif" font-size="10" font-weight="bold" letter-spacing="2" text-anchor="middle">JACARIO</text>
    <text x="300" y="177" fill="#A1A1AA" font-family="sans-serif" font-size="6" letter-spacing="1" text-anchor="middle">CUSTOM STUDIO</text>

    <!-- Placket & Buttons -->
    <path d="M 285,145 L 315,145 L 315,280 L 285,280 Z" fill="{$c['rib']}" stroke="{$c['dark']}" stroke-width="1.5" />
    <line x1="287" y1="145" x2="287" y2="280" stroke="{$c['dark']}" stroke-width="1" stroke-dasharray="2,2" opacity="0.4"/>
    <line x1="313" y1="145" x2="313" y2="280" stroke="{$c['dark']}" stroke-width="1" stroke-dasharray="2,2" opacity="0.4"/>
    
    <!-- Top & Lower Buttons -->
    <circle cx="300" cy="180" r="5" fill="#FAF9F6" stroke="#D1D5DB" stroke-width="1"/>
    <circle cx="300" cy="225" r="5" fill="#FAF9F6" stroke="#D1D5DB" stroke-width="1"/>
    <circle cx="300" cy="265" r="5" fill="#FAF9F6" stroke="#D1D5DB" stroke-width="1"/>

    <!-- Collar Left -->
    <path d="M 210,130 C 240,150 270,185 285,215 L 260,195 C 235,175 220,155 210,130 Z" 
          fill="url(#{$id}_collar)" stroke="{$c['dark']}" stroke-width="2" />
    <!-- Collar Right -->
    <path d="M 390,130 C 360,150 330,185 315,215 L 340,195 C 365,175 380,155 390,130 Z" 
          fill="url(#{$id}_collar)" stroke="{$c['dark']}" stroke-width="2" />

    <!-- Subdued Jacario Crown Tone on Tone Chest Crest -->
    <path d="M 360,250 L 366,244 L 372,250 L 378,244 L 384,250 L 381,257 L 363,257 Z" fill="{$c['accent']}" opacity="0.4" />
  </g>
</svg>
SVG;
    }

    private function renderTshirtFront(string $slug, array $c): string
    {
        $id = "tshirt_cust_{$slug}";
        $isLight = in_array($slug, ['white', 'sand', 'grey']);
        $textColor = $isLight ? '#18181B' : '#FFFFFF';

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 700" width="100%" height="100%" class="w-full h-full object-contain">
  <defs>
    <linearGradient id="{$id}_body" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="{$c['light']}" />
      <stop offset="50%" stop-color="{$c['main']}" />
      <stop offset="100%" stop-color="{$c['dark']}" />
    </linearGradient>
    <linearGradient id="{$id}_crew" x1="0%" y1="0%" x2="100%" y2="50%">
      <stop offset="0%" stop-color="{$c['light']}" />
      <stop offset="100%" stop-color="{$c['rib']}" />
    </linearGradient>
    <filter id="{$id}_shadow" x="-10%" y="-10%" width="120%" height="130%">
      <feDropShadow dx="0" dy="16" stdDeviation="20" flood-color="#000000" flood-opacity="0.18"/>
    </filter>
    <pattern id="{$id}_jersey" width="4" height="4" patternUnits="userSpaceOnUse">
      <circle cx="2" cy="2" r="0.6" fill="#ffffff" fill-opacity="0.03" />
      <circle cx="3" cy="3" r="0.6" fill="#000000" fill-opacity="0.04" />
    </pattern>
  </defs>

  <g filter="url(#{$id}_shadow)">
    <!-- Main Body Contour -->
    <path d="M 215,120 L 130,185 L 80,268 L 140,298 L 175,248 L 180,600 C 180,615 420,615 420,600 L 425,248 L 460,298 L 520,268 L 470,185 L 385,120 C 335,138 265,138 215,120 Z" 
          fill="url(#{$id}_body)" stroke="{$c['dark']}" stroke-width="2" stroke-linejoin="round" />

    <!-- Fine Jersey Cotton Texture -->
    <path d="M 215,120 L 130,185 L 80,268 L 140,298 L 175,248 L 180,600 C 180,615 420,615 420,600 L 425,248 L 460,298 L 520,268 L 470,185 L 385,120 C 335,138 265,138 215,120 Z" 
          fill="url(#{$id}_jersey)" />

    <!-- Left Sleeve Cuff -->
    <path d="M 80,268 L 140,298 L 135,310 L 73,278 Z" fill="{$c['rib']}" stroke="{$c['dark']}" stroke-width="1.5"/>
    <line x1="82" y1="272" x2="137" y2="300" stroke="{$c['light']}" stroke-width="1" opacity="0.5"/>

    <!-- Right Sleeve Cuff -->
    <path d="M 520,268 L 460,298 L 465,310 L 527,278 Z" fill="{$c['rib']}" stroke="{$c['dark']}" stroke-width="1.5"/>
    <line x1="518" y1="272" x2="463" y2="300" stroke="{$c['light']}" stroke-width="1" opacity="0.5"/>

    <!-- Bottom Hem Details -->
    <path d="M 180,590 C 240,598 360,598 420,590" stroke="{$c['dark']}" stroke-width="2" fill="none" opacity="0.5"/>
    <path d="M 180,594 C 240,602 360,602 420,594" stroke="{$c['light']}" stroke-width="1" stroke-dasharray="4,4" fill="none" opacity="0.7"/>

    <!-- Inner Neck Label Patch -->
    <path d="M 245,125 C 275,140 325,140 355,125 L 350,175 C 320,182 280,182 250,175 Z" fill="#0B0D10" />
    <text x="300" y="152" fill="#C5A880" font-family="'Cinzel', 'Playfair Display', serif" font-size="10" font-weight="bold" letter-spacing="2" text-anchor="middle">JACARIO</text>
    <text x="300" y="166" fill="#A1A1AA" font-family="sans-serif" font-size="6" letter-spacing="1" text-anchor="middle">100% SUPIMA® CREW</text>

    <!-- Ribbed Crewneck Collar -->
    <path d="M 230,120 C 260,158 340,158 370,120 C 350,135 250,135 230,120 Z" 
          fill="url(#{$id}_crew)" stroke="{$c['dark']}" stroke-width="2.5" />
    <path d="M 235,122 C 265,152 335,152 365,122" fill="none" stroke="{$c['light']}" stroke-width="1" stroke-dasharray="3,2" opacity="0.6"/>
  </g>
</svg>
SVG;
    }
}
