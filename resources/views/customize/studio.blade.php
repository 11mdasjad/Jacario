@extends('layouts.app')

@section('title', 'JACARIO Studio — ' . $product->name)
@section('meta_description', 'Customize your ' . $product->name . ' with custom logo, typography, and live real-time preview. Tailored by JACARIO.')

@push('styles')
<!-- Google Fonts for customizer typography -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Cinzel+Decorative:wght@700&family=Cinzel:wght@600;700;800&family=Inter:wght@400;600;800&family=Montserrat:wght@500;700;900&family=Outfit:wght@500;700&family=Playfair+Display:ital,wght@0,600;0,800;1,600&family=Plus+Jakarta+Sans:wght@500;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">

<style>
    .font-cinzel { font-family: 'Cinzel', serif; }
    .font-plus-jakarta { font-family: 'Plus Jakarta Sans', sans-serif; }
    .font-inter { font-family: 'Inter', sans-serif; }
    .font-playfair { font-family: 'Playfair Display', serif; }
    .font-montserrat { font-family: 'Montserrat', sans-serif; }
    .font-bebas { font-family: 'Bebas Neue', cursive; }
    .font-outfit { font-family: 'Outfit', sans-serif; }
    .font-space { font-family: 'Space Grotesk', sans-serif; }
    
    .print-boundary {
        border: 2px dashed rgba(164, 132, 91, 0.6);
        box-shadow: 0 0 15px rgba(164, 132, 91, 0.15);
    }
    
    .layer-selected {
        outline: 2px solid #A4845B;
        box-shadow: 0 0 0 4px rgba(164, 132, 91, 0.25);
    }
</style>
@endpush

@section('content')
<div class="bg-[#F8FAFC] min-h-screen py-6 sm:py-10" 
     x-data="jacarioCustomizer({
        productId: {{ $product->id }},
        productName: '{{ addslashes($product->name) }}',
        productType: '{{ $product->type }}',
        productSlug: '{{ $product->slug }}',
        basePrice: {{ (float) $product->effective_price }},
        settings: {{ json_encode($product->settings ?: new \stdClass()) }},
        colors: {{ json_encode($colors) }},
        sizes: {{ json_encode($sizes) }},
        defaultColorId: {{ $colors->first() ? $colors->first()->id : 'null' }},
        defaultSizeId: {{ $sizes->first() ? $sizes->first()->id : 'null' }},
        csrfToken: '{{ csrf_token() }}',
        uploadUrl: '{{ route('customize.upload-artwork') }}',
        addToCartUrl: '{{ route('customize.add-to-cart') }}',
        saveDesignUrl: '{{ route('customize.save-design') }}',
        isLoggedIn: {{ Auth::check() ? 'true' : 'false' }}
     })"
     x-init="initCustomizer()">

    <!-- Studio Top Bar -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4 bg-white p-4 sm:p-5 rounded-2xl border border-zinc-200 shadow-xs">
            
            <div class="flex items-center space-x-3 sm:space-x-4">
                <a href="{{ route('customize.index') }}" class="p-2 rounded-xl text-zinc-500 hover:text-zinc-950 hover:bg-zinc-100 transition-colors" title="Back to All Customizable Garments">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                
                <div>
                    <div class="flex items-center space-x-2">
                        <h1 class="text-base sm:text-xl font-bold font-serif-luxury text-zinc-950">{{ $product->name }}</h1>
                        <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded-full bg-[#0B0D10] text-[#DFCAAB] border border-[#A4845B]/40">Studio 3.0</span>
                    </div>
                    <p class="text-xs text-zinc-500 hidden sm:block">Live front-side interactive personalization & bulk tier pricing</p>
                </div>
            </div>

            <!-- Studio Actions -->
            <div class="flex items-center space-x-2 sm:space-x-3">
                <button type="button" @click="resetDesign()" class="inline-flex items-center space-x-1.5 px-3 py-2 rounded-xl text-xs font-semibold text-zinc-600 hover:text-rose-600 hover:bg-rose-50 border border-zinc-200 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    <span>Reset</span>
                </button>

                <button type="button" @click="saveDesignModalOpen = true" class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-zinc-700 hover:text-zinc-950 bg-zinc-100 hover:bg-zinc-200 border border-zinc-200 transition-colors">
                    <svg class="w-3.5 h-3.5 text-[#A4845B]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                    <span>Save Design</span>
                </button>

                <button type="button" @click="showPreviewModal = true" class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl text-xs font-bold text-zinc-950 bg-amber-50 hover:bg-amber-100 border border-amber-300/80 transition-colors">
                    <svg class="w-3.5 h-3.5 text-[#8C6D46]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <span>Full Preview</span>
                </button>
            </div>

        </div>
    </div>

    <!-- Main Studio Workspace Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
            
            <!-- Left Column: Interactive Canvas Mockup (Col 7) -->
            <div class="lg:col-span-7 bg-white rounded-3xl p-6 border border-zinc-200 shadow-sm flex flex-col items-center justify-between relative overflow-hidden">
                
                <!-- Mockup Controls Bar (Color indicator & Grid Toggle) -->
                <div class="w-full flex items-center justify-between pb-4 border-b border-zinc-100">
                    <div class="flex items-center space-x-2">
                        <span class="w-3.5 h-3.5 rounded-full border border-zinc-300 shadow-xs" :style="'background-color: ' + currentColor.hex_code"></span>
                        <span class="text-xs font-bold text-zinc-800" x-text="currentColor.name"></span>
                        <span class="text-zinc-400 text-xs">•</span>
                        <span class="text-xs font-semibold text-zinc-500 uppercase">Front View</span>
                    </div>

                    <div class="flex items-center space-x-2">
                        <button type="button" @click="showBoundary = !showBoundary" class="text-[11px] font-semibold px-2.5 py-1 rounded-lg border transition-colors flex items-center space-x-1" :class="showBoundary ? 'bg-amber-50 text-[#8C6D46] border-amber-300' : 'bg-zinc-50 text-zinc-600 border-zinc-200'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                            <span x-text="showBoundary ? 'Safe Zone: ON' : 'Safe Zone: OFF'"></span>
                        </button>
                    </div>
                </div>

                <!-- Canvas / Mockup Interactive Box -->
                <div class="relative w-full max-w-[480px] aspect-[6/7] my-4 select-none flex items-center justify-center" id="customizer-capture-container">
                    
                    <!-- Garment Vector Template Background -->
                    <img :src="getGarmentMockupUrl()" 
                         alt="Garment Mockup" 
                         class="w-full h-full object-contain pointer-events-none filter drop-shadow-md transition-all duration-300"
                         crossorigin="anonymous">

                    <!-- Defined Safe Printable Area Bounding Box -->
                    <div class="absolute"
                         :style="`top: ${printArea.top}%; left: ${printArea.left}%; width: ${printArea.width}%; height: ${printArea.height}%;`"
                         :class="{ 'print-boundary': showBoundary }"
                         @click.self="selectedLayerIndex = null">

                        <!-- Safe Print Watermark when editing -->
                        <div x-show="showBoundary && layers.length === 0" class="w-full h-full flex flex-col items-center justify-center text-center p-3 pointer-events-none opacity-60">
                            <svg class="w-7 h-7 text-[#A4845B] mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                            <span class="text-[11px] font-bold uppercase tracking-wider text-zinc-700">Printable Chest Area</span>
                            <span class="text-[9px] text-zinc-500">Upload your logo or add text from the sidebar</span>
                        </div>

                        <!-- Rendered Customizable Interactive Layers -->
                        <template x-for="(layer, idx) in layers" :key="layer.id">
                            <div class="absolute cursor-move transition-shadow"
                                 :class="{ 'layer-selected rounded-sm': selectedLayerIndex === idx }"
                                 :style="`left: ${layer.x}%; top: ${layer.y}%; transform: translate(-50%, -50%) rotate(${layer.rotation}deg) scale(${layer.scale}); z-index: ${idx + 10};`"
                                 @mousedown.stop="startDrag($event, idx)"
                                 @touchstart.stop="startDrag($event, idx)"
                                 @click.stop="selectLayer(idx)">

                                <!-- 1. Image / Artwork Layer -->
                                <template x-if="layer.type === 'image'">
                                    <div class="relative group">
                                        <img :src="layer.url" 
                                             class="max-w-[120px] max-h-[120px] object-contain pointer-events-none filter drop-shadow-xs"
                                             crossorigin="anonymous">
                                        
                                        <!-- Quick Delete Handle on Layer -->
                                        <button x-show="selectedLayerIndex === idx" 
                                                @click.stop="removeLayer(idx)"
                                                type="button"
                                                class="absolute -top-3 -right-3 w-6 h-6 rounded-full bg-rose-600 text-white flex items-center justify-center text-xs shadow-md hover:bg-rose-700 transition-colors">
                                            ×
                                        </button>
                                    </div>
                                </template>

                                <!-- 2. Text Layer -->
                                <template x-if="layer.type === 'text'">
                                    <div class="relative whitespace-nowrap"
                                         :style="`font-family: ${layer.fontFamily}; color: ${layer.color}; font-weight: ${layer.bold ? 'bold' : 'normal'}; font-style: ${layer.italic ? 'italic' : 'normal'}; font-size: ${layer.fontSize}px; text-align: ${layer.align};`">
                                        <span x-text="layer.text || 'Enter Text'"></span>

                                        <!-- Quick Delete Handle on Layer -->
                                        <button x-show="selectedLayerIndex === idx" 
                                                @click.stop="removeLayer(idx)"
                                                type="button"
                                                class="absolute -top-3 -right-3 w-6 h-6 rounded-full bg-rose-600 text-white flex items-center justify-center text-xs shadow-md hover:bg-rose-700 transition-colors">
                                            ×
                                        </button>
                                    </div>
                                </template>

                            </div>
                        </template>

                    </div>

                </div>

                <!-- Layer Manipulation Quick Toolbar (Visible when a layer is selected) -->
                <div class="w-full bg-zinc-50 rounded-2xl p-3 border border-zinc-200/90 flex flex-wrap items-center justify-between gap-3 text-xs" x-show="selectedLayer !== null" x-transition>
                    <div class="flex items-center space-x-2 font-bold text-zinc-800">
                        <span class="w-2 h-2 rounded-full bg-[#A4845B]"></span>
                        <span x-text="selectedLayer ? (selectedLayer.type === 'image' ? 'Artwork Layer' : 'Text Layer: &quot;' + selectedLayer.text.substring(0, 12) + '...&quot;') : ''"></span>
                    </div>

                    <div class="flex items-center space-x-2">
                        <!-- Center Horizontally -->
                        <button type="button" @click="centerLayerX()" class="p-1.5 rounded-lg bg-white border border-zinc-200 hover:bg-zinc-100 text-zinc-700" title="Center Horizontally">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m-6-8h12"/></svg>
                        </button>
                        <!-- Center Vertically -->
                        <button type="button" @click="centerLayerY()" class="p-1.5 rounded-lg bg-white border border-zinc-200 hover:bg-zinc-100 text-zinc-700" title="Center Vertically">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12h16m-8-6v12"/></svg>
                        </button>
                        <!-- Rotate 90deg -->
                        <button type="button" @click="rotateLayerBy(45)" class="p-1.5 rounded-lg bg-white border border-zinc-200 hover:bg-zinc-100 text-zinc-700" title="Rotate 45°">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </button>
                        <!-- Delete -->
                        <button type="button" @click="removeLayer(selectedLayerIndex)" class="p-1.5 rounded-lg bg-rose-50 border border-rose-200 hover:bg-rose-100 text-rose-600 font-bold" title="Delete Layer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Drag instructions hint -->
                <p class="text-[11px] text-zinc-400 mt-2 text-center">
                    💡 Click and drag any element on the garment to position it precisely within the chest area.
                </p>

            </div>

            <!-- Right Column: Studio Controls Panel & Checkout Bar (Col 5) -->
            <div class="lg:col-span-5 space-y-6">
                
                <!-- Studio Tabs Box -->
                <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm p-6 overflow-hidden">
                    
                    <!-- Navigation Tabs -->
                    <div class="flex items-center border-b border-zinc-200 pb-3 mb-5 space-x-1 sm:space-x-2">
                        <button type="button" @click="activeTab = 'artwork'" class="flex-1 py-2 text-center rounded-xl text-xs font-bold uppercase tracking-wider transition-colors flex items-center justify-center space-x-1" :class="activeTab === 'artwork' ? 'bg-[#0B0D10] text-[#DFCAAB]' : 'text-zinc-600 hover:bg-zinc-100'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Logo / Art</span>
                        </button>

                        <button type="button" @click="activeTab = 'text'" class="flex-1 py-2 text-center rounded-xl text-xs font-bold uppercase tracking-wider transition-colors flex items-center justify-center space-x-1" :class="activeTab === 'text' ? 'bg-[#0B0D10] text-[#DFCAAB]' : 'text-zinc-600 hover:bg-zinc-100'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            <span>Custom Text</span>
                        </button>

                        <button type="button" @click="activeTab = 'garment'" class="flex-1 py-2 text-center rounded-xl text-xs font-bold uppercase tracking-wider transition-colors flex items-center justify-center space-x-1" :class="activeTab === 'garment' ? 'bg-[#0B0D10] text-[#DFCAAB]' : 'text-zinc-600 hover:bg-zinc-100'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                            <span>Color & Size</span>
                        </button>
                    </div>

                    <!-- TAB 1: LOGO / ARTWORK UPLOAD -->
                    <div x-show="activeTab === 'artwork'" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-900 mb-2">Upload Your Brand Logo / Artwork</label>
                            
                            <!-- Dropzone File Upload -->
                            <div class="border-2 border-dashed border-zinc-300 hover:border-[#A4845B] rounded-2xl p-6 text-center transition-colors bg-zinc-50 hover:bg-amber-50/20 cursor-pointer relative"
                                 @dragover.prevent=""
                                 @drop.prevent="handleFileDrop($event)"
                                 @click="$refs.artworkInput.click()">
                                
                                <input type="file" 
                                       x-ref="artworkInput" 
                                       @change="handleFileUpload($event)" 
                                       accept="image/png, image/jpeg, image/jpg, image/svg+xml, image/webp" 
                                       class="hidden">

                                <div x-show="!isUploading">
                                    <div class="w-10 h-10 rounded-full bg-white shadow-xs border border-zinc-200 text-[#A4845B] flex items-center justify-center mx-auto mb-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                    </div>
                                    <p class="text-xs font-bold text-zinc-800">Click to upload or drag & drop</p>
                                    <p class="text-[10px] text-zinc-500 mt-1">Supported: PNG, JPG, SVG, WebP (Max 15MB)</p>
                                </div>

                                <div x-show="isUploading" class="py-3">
                                    <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-[#A4845B]"></div>
                                    <p class="text-xs font-bold text-zinc-800 mt-2">Uploading artwork securely...</p>
                                </div>
                            </div>
                        </div>

                        <!-- Active Image Controls (if image layer selected) -->
                        <template x-if="selectedLayer && selectedLayer.type === 'image'">
                            <div class="pt-3 border-t border-zinc-100 space-y-3">
                                <div>
                                    <div class="flex justify-between text-xs font-bold text-zinc-700 mb-1">
                                        <span>Scale / Size</span>
                                        <span x-text="Math.round(selectedLayer.scale * 100) + '%'"></span>
                                    </div>
                                    <input type="range" min="0.3" max="2.0" step="0.05" x-model.number="selectedLayer.scale" class="w-full accent-[#A4845B]">
                                </div>

                                <div>
                                    <div class="flex justify-between text-xs font-bold text-zinc-700 mb-1">
                                        <span>Rotation Angle</span>
                                        <span x-text="selectedLayer.rotation + '°'"></span>
                                    </div>
                                    <input type="range" min="-180" max="180" step="5" x-model.number="selectedLayer.rotation" class="w-full accent-[#A4845B]">
                                </div>
                            </div>
                        </template>

                    </div>

                    <!-- TAB 2: CUSTOM TEXT -->
                    <div x-show="activeTab === 'text'" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-zinc-900 mb-1">Custom Text / Slogan</label>
                            <div class="flex items-center space-x-2">
                                <input type="text" 
                                       x-model="newTextInput" 
                                       @keyup.enter="addTextLayer()"
                                       placeholder="e.g. JACARIO CREW or Founder Initials"
                                       maxlength="40"
                                       class="flex-1 text-xs px-3.5 py-2.5 rounded-xl border border-zinc-300 focus:outline-none focus:border-[#A4845B] focus:ring-1 focus:ring-[#A4845B]">
                                <button type="button" @click="addTextLayer()" class="px-4 py-2.5 rounded-xl bg-zinc-950 text-[#DFCAAB] text-xs font-bold uppercase tracking-wider hover:bg-zinc-800 transition-colors">
                                    Add Text
                                </button>
                            </div>
                        </div>

                        <!-- Active Text Settings -->
                        <template x-if="selectedLayer && selectedLayer.type === 'text'">
                            <div class="pt-3 border-t border-zinc-100 space-y-4">
                                
                                <!-- Edit Text Input -->
                                <div>
                                    <label class="block text-[11px] font-bold text-zinc-600 uppercase mb-1">Edit Content</label>
                                    <input type="text" x-model="selectedLayer.text" class="w-full text-xs px-3 py-2 rounded-lg border border-zinc-300 focus:outline-none focus:border-[#A4845B]">
                                </div>

                                <!-- Font Family Dropdown -->
                                <div>
                                    <label class="block text-[11px] font-bold text-zinc-600 uppercase mb-1">Font Family</label>
                                    <select x-model="selectedLayer.fontFamily" class="w-full text-xs px-3 py-2 rounded-lg border border-zinc-300 focus:outline-none focus:border-[#A4845B] bg-white font-bold">
                                        <option value="'Cinzel', serif">Cinzel (Luxury Serif)</option>
                                        <option value="'Plus Jakarta Sans', sans-serif">Plus Jakarta Sans (Modern Clean)</option>
                                        <option value="'Playfair Display', serif">Playfair Display (Editorial)</option>
                                        <option value="'Inter', sans-serif">Inter (Minimalist)</option>
                                        <option value="'Montserrat', sans-serif">Montserrat (Geometric Bold)</option>
                                        <option value="'Bebas Neue', cursive">Bebas Neue (Athletic Poster)</option>
                                        <option value="'Outfit', sans-serif">Outfit (Premium Sans)</option>
                                        <option value="'Space Grotesk', sans-serif">Space Grotesk (Tech Monospace)</option>
                                    </select>
                                </div>

                                <!-- Font Color Swatches -->
                                <div>
                                    <label class="block text-[11px] font-bold text-zinc-600 uppercase mb-2">Text Color</label>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <template x-for="cHex in ['#FFFFFF', '#18181B', '#C5A880', '#D4AF37', '#DC2626', '#1E3A8A', '#047857', '#F59E0B']" :key="cHex">
                                            <button type="button" 
                                                    @click="selectedLayer.color = cHex" 
                                                    class="w-7 h-7 rounded-full border-2 transition-transform hover:scale-110 shadow-xs"
                                                    :style="`background-color: ${cHex}; border-color: ${selectedLayer.color === cHex ? '#A4845B' : '#E5E7EB'};`">
                                            </button>
                                        </template>
                                        <input type="color" x-model="selectedLayer.color" class="w-7 h-7 p-0 border-0 rounded-full cursor-pointer" title="Custom Color Picker">
                                    </div>
                                </div>

                                <!-- Font Size & Style -->
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[11px] font-bold text-zinc-600 uppercase mb-1">Font Size (<span x-text="selectedLayer.fontSize"></span>px)</label>
                                        <input type="range" min="12" max="48" step="1" x-model.number="selectedLayer.fontSize" class="w-full accent-[#A4845B]">
                                    </div>

                                    <div>
                                        <label class="block text-[11px] font-bold text-zinc-600 uppercase mb-1">Style</label>
                                        <div class="flex items-center space-x-1">
                                            <button type="button" @click="selectedLayer.bold = !selectedLayer.bold" class="flex-1 py-1.5 rounded-lg border text-xs font-black transition-colors" :class="selectedLayer.bold ? 'bg-[#0B0D10] text-[#DFCAAB] border-zinc-950' : 'bg-white text-zinc-700 border-zinc-300'">B</button>
                                            <button type="button" @click="selectedLayer.italic = !selectedLayer.italic" class="flex-1 py-1.5 rounded-lg border text-xs italic font-serif transition-colors" :class="selectedLayer.italic ? 'bg-[#0B0D10] text-[#DFCAAB] border-zinc-950' : 'bg-white text-zinc-700 border-zinc-300'">I</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </template>

                    </div>

                    <!-- TAB 3: COLOR & SIZE -->
                    <div x-show="activeTab === 'garment'" class="space-y-5">
                        <!-- Garment Color Picker -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-zinc-900">Garment Color</label>
                                <span class="text-xs font-bold text-[#A4845B]" x-text="currentColor.name"></span>
                            </div>
                            <div class="grid grid-cols-5 gap-2.5">
                                <template x-for="col in colors" :key="col.id">
                                    <button type="button" 
                                            @click="selectColor(col)" 
                                            class="flex flex-col items-center p-2 rounded-xl border transition-all"
                                            :class="selectedColorId === col.id ? 'border-[#A4845B] bg-amber-50/50 ring-2 ring-[#A4845B]/30' : 'border-zinc-200 hover:border-zinc-300 bg-white'">
                                        <span class="w-6 h-6 rounded-full border border-black/10 shadow-xs mb-1" :style="'background-color: ' + col.hex_code"></span>
                                        <span class="text-[9px] font-bold text-zinc-700 truncate w-full text-center" x-text="col.name"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <!-- Size Picker -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-zinc-900">Select Size</label>
                                <button type="button" @click="showSizeGuide = true" class="text-xs font-semibold text-[#8C6D46] hover:underline flex items-center space-x-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Size Chart</span>
                                </button>
                            </div>
                            <div class="grid grid-cols-5 gap-2">
                                <template x-for="sz in sizes" :key="sz.id">
                                    <button type="button" 
                                            @click="selectedSizeId = sz.id"
                                            class="py-2.5 rounded-xl border text-xs font-bold transition-all text-center"
                                            :class="selectedSizeId === sz.id ? 'bg-[#0B0D10] text-white border-[#0B0D10] shadow-sm' : 'bg-white text-zinc-800 border-zinc-200 hover:border-zinc-400'">
                                        <span x-text="sz.name"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Quantity & Live Bulk Discount Bar Box -->
                <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm p-6 space-y-4">
                    
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="text-xs font-bold uppercase tracking-wider text-zinc-900 block">Quantity</label>
                            <span class="text-[10px] text-zinc-500">Zero minimums required</span>
                        </div>

                        <!-- Quantity Selector -->
                        <div class="flex items-center border border-zinc-300 rounded-xl overflow-hidden bg-white shadow-xs">
                            <button type="button" @click="changeQuantity(-1)" class="px-3 py-2 text-zinc-600 hover:bg-zinc-100 font-bold transition-colors">−</button>
                            <input type="number" min="1" max="500" x-model.number="quantity" @change="sanitizeQuantity()" class="w-14 text-center text-xs font-extrabold focus:outline-none border-x border-zinc-200 py-2">
                            <button type="button" @click="changeQuantity(1)" class="px-3 py-2 text-zinc-600 hover:bg-zinc-100 font-bold transition-colors">+</button>
                        </div>
                    </div>

                    <!-- Dynamic Bulk Tier Pricing Notice -->
                    <div class="p-3 rounded-2xl bg-amber-50/80 border border-amber-200/80 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-zinc-900">Bulk Tier Discount:</span>
                            <span class="font-extrabold text-emerald-700" x-text="bulkDiscountPercentage > 0 ? bulkDiscountPercentage + '% OFF APPLIED' : 'Standard Rate'"></span>
                        </div>
                        <p class="text-[10px] text-zinc-600 mt-1">
                            Order 5+ get 10% off • Order 15+ get 18% off • Order 30+ get 25% off • Order 50+ get 35% off.
                        </p>
                    </div>

                    <!-- Pricing Breakdown & Add to Bag CTA -->
                    <div class="pt-3 border-t border-zinc-100">
                        <div class="flex items-baseline justify-between mb-4">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-zinc-500 block">Total Est. Price</span>
                                <div class="flex items-baseline space-x-2">
                                    <span class="text-2xl sm:text-3xl font-extrabold text-zinc-950" x-text="formatCurrency(calculateTotalPrice())"></span>
                                    <span class="text-xs text-zinc-500" x-text="'(' + formatCurrency(calculateUnitPrice()) + '/unit)'"></span>
                                </div>
                            </div>

                            <span class="text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200" x-show="bulkDiscountPercentage > 0">
                                Saving <span x-text="formatCurrency(calculateSavings())"></span>
                            </span>
                        </div>

                        <!-- Add to Bag Button -->
                        <button type="button" 
                                @click="addToCart()" 
                                :disabled="isAddingToCart"
                                class="w-full py-4 rounded-2xl bg-zinc-950 hover:bg-[#8C6D46] text-white text-xs font-bold uppercase tracking-widest shadow-xl hover:shadow-2xl transition-all duration-300 flex items-center justify-center space-x-2 transform active:scale-98 disabled:opacity-50">
                            <span x-show="!isAddingToCart" class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-[#DFCAAB]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                <span>Add Customized Item to Bag</span>
                            </span>
                            <span x-show="isAddingToCart" class="flex items-center space-x-2">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                                <span>Rendering Design Snapshot...</span>
                            </span>
                        </button>
                    </div>

                </div>

            </div>

        </div>
    </div>

    <!-- Size Guide Modal -->
    <div x-show="showSizeGuide" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs" style="display: none;" @keydown.window.escape="showSizeGuide = false">
        <div class="bg-white rounded-3xl p-6 max-w-lg w-full shadow-2xl border border-zinc-200" @click.away="showSizeGuide = false">
            <div class="flex items-center justify-between pb-4 border-b border-zinc-200">
                <h3 class="text-base font-bold font-serif-luxury text-zinc-950">JACARIO Garment Size Guide</h3>
                <button type="button" @click="showSizeGuide = false" class="text-zinc-400 hover:text-zinc-950 text-xl font-bold">×</button>
            </div>
            <div class="mt-4 overflow-x-auto">
                <table class="w-full text-xs text-left">
                    <thead class="bg-zinc-100 text-zinc-700 font-bold uppercase">
                        <tr>
                            <th class="p-2.5">Size</th>
                            <th class="p-2.5">Chest (Inches)</th>
                            <th class="p-2.5">Length (Inches)</th>
                            <th class="p-2.5">Shoulder</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 text-zinc-800">
                        <tr><td class="p-2.5 font-bold">S</td><td class="p-2.5">38"</td><td class="p-2.5">27"</td><td class="p-2.5">17"</td></tr>
                        <tr><td class="p-2.5 font-bold">M</td><td class="p-2.5">40"</td><td class="p-2.5">28"</td><td class="p-2.5">18"</td></tr>
                        <tr><td class="p-2.5 font-bold">L</td><td class="p-2.5">42"</td><td class="p-2.5">29"</td><td class="p-2.5">19"</td></tr>
                        <tr><td class="p-2.5 font-bold">XL</td><td class="p-2.5">44"</td><td class="p-2.5">30"</td><td class="p-2.5">20"</td></tr>
                        <tr><td class="p-2.5 font-bold">XXL</td><td class="p-2.5">46"</td><td class="p-2.5">30.5"</td><td class="p-2.5">20.5"</td></tr>
                        <tr><td class="p-2.5 font-bold">3XL</td><td class="p-2.5">48"</td><td class="p-2.5">31"</td><td class="p-2.5">21"</td></tr>
                    </tbody>
                </table>
            </div>
            <button type="button" @click="showSizeGuide = false" class="w-full mt-5 py-2.5 bg-zinc-950 text-white rounded-xl text-xs font-bold uppercase tracking-wider">
                Got It
            </button>
        </div>
    </div>

    <!-- Full Preview Modal -->
    <div x-show="showPreviewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-sm" style="display: none;" @keydown.window.escape="showPreviewModal = false">
        <div class="bg-white rounded-3xl p-6 max-w-2xl w-full shadow-2xl border border-zinc-200 text-center" @click.away="showPreviewModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-zinc-100">
                <h3 class="text-base font-bold font-serif-luxury text-zinc-950">High-Definition Customizer Preview</h3>
                <button type="button" @click="showPreviewModal = false" class="text-zinc-400 hover:text-zinc-950 text-xl font-bold">×</button>
            </div>
            
            <div class="my-6 flex items-center justify-center">
                <div class="w-80 aspect-[6/7] relative">
                    <img :src="getGarmentMockupUrl()" class="w-full h-full object-contain filter drop-shadow-lg">
                    <!-- Layer duplication in preview -->
                    <div class="absolute" :style="`top: ${printArea.top}%; left: ${printArea.left}%; width: ${printArea.width}%; height: ${printArea.height}%; pointer-events: none;`">
                        <template x-for="layer in layers" :key="'prev-' + layer.id">
                            <div class="absolute" :style="`left: ${layer.x}%; top: ${layer.y}%; transform: translate(-50%, -50%) rotate(${layer.rotation}deg) scale(${layer.scale});`">
                                <template x-if="layer.type === 'image'">
                                    <img :src="layer.url" class="max-w-[120px] max-h-[120px] object-contain">
                                </template>
                                <template x-if="layer.type === 'text'">
                                    <span :style="`font-family: ${layer.fontFamily}; color: ${layer.color}; font-weight: ${layer.bold ? 'bold' : 'normal'}; font-style: ${layer.italic ? 'italic' : 'normal'}; font-size: ${layer.fontSize}px;`" x-text="layer.text"></span>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-center space-x-3">
                <button type="button" @click="showPreviewModal = false" class="px-6 py-2.5 rounded-xl border border-zinc-300 text-xs font-bold text-zinc-700">Close Preview</button>
                <button type="button" @click="showPreviewModal = false; addToCart()" class="px-6 py-2.5 rounded-xl bg-zinc-950 text-[#DFCAAB] text-xs font-bold uppercase tracking-wider">Confirm & Add to Bag</button>
            </div>
        </div>
    </div>

    <!-- Save Design Modal -->
    <div x-show="saveDesignModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs" style="display: none;" @keydown.window.escape="saveDesignModalOpen = false">
        <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl border border-zinc-200" @click.away="saveDesignModalOpen = false">
            <h3 class="text-base font-bold font-serif-luxury text-zinc-950 mb-2">Save Custom Design</h3>
            <p class="text-xs text-zinc-600 mb-4">Save your artwork arrangement to your Jacario account to order or re-edit later.</p>

            <div class="space-y-3">
                <div>
                    <label class="block text-[11px] font-bold text-zinc-700 uppercase mb-1">Design Name</label>
                    <input type="text" x-model="saveDesignTitle" placeholder="e.g. Summer Corporate Black Polo" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-300 focus:outline-none focus:border-[#A4845B]">
                </div>

                <div class="flex items-center space-x-2 pt-2">
                    <button type="button" @click="saveDesignModalOpen = false" class="flex-1 py-2.5 rounded-xl border border-zinc-300 text-xs font-bold text-zinc-700">Cancel</button>
                    <button type="button" @click="executeSaveDesign()" class="flex-1 py-2.5 rounded-xl bg-zinc-950 text-[#DFCAAB] text-xs font-bold uppercase tracking-wider">Save Now</button>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Customizer JavaScript Engine using HTML5 Canvas for Snapshot rendering -->
<script>
function jacarioCustomizer(config) {
    return {
        productId: config.productId,
        productName: config.productName,
        productType: config.productType,
        productSlug: config.productSlug,
        basePrice: config.basePrice,
        settings: config.settings || {},
        colors: config.colors || [],
        sizes: config.sizes || [],
        selectedColorId: config.defaultColorId,
        selectedSizeId: config.defaultSizeId,
        quantity: 1,
        
        activeTab: 'artwork',
        showBoundary: true,
        showSizeGuide: false,
        showPreviewModal: false,
        saveDesignModalOpen: false,
        saveDesignTitle: 'My ' + config.productName + ' Design',
        
        isUploading: false,
        isAddingToCart: false,
        
        // Print Area percentage boundaries on real photorealistic mockup
        printArea: {
            top: 28,
            left: 32,
            width: 36,
            height: 42
        },
        
        // Layers: array of objects { id, type: 'image'|'text', url, text, fontFamily, color, fontSize, bold, italic, x: %, y: %, scale: 1, rotation: 0 }
        layers: [],
        selectedLayerIndex: null,
        newTextInput: '',
        
        // Drag state
        draggingIndex: null,
        dragStartX: 0,
        dragStartY: 0,
        layerStartX: 0,
        layerStartY: 0,

        initCustomizer() {
            if (this.settings.print_area) {
                this.printArea = Object.assign({}, this.printArea, this.settings.print_area);
            }
        },

        get currentColor() {
            return this.colors.find(c => c.id === this.selectedColorId) || this.colors[0] || { name: 'Obsidian Black', hex_code: '#18181B' };
        },

        get selectedLayer() {
            if (this.selectedLayerIndex !== null && this.layers[this.selectedLayerIndex]) {
                return this.layers[this.selectedLayerIndex];
            }
            return null;
        },

        get bulkDiscountPercentage() {
            const tiers = this.settings.bulk_pricing || [
                { min_qty: 5, discount_percent: 10 },
                { min_qty: 15, discount_percent: 18 },
                { min_qty: 30, discount_percent: 25 },
                { min_qty: 50, discount_percent: 35 }
            ];

            let best = 0;
            for (let t of tiers) {
                if (this.quantity >= t.min_qty && t.discount_percent > best) {
                    best = t.discount_percent;
                }
            }
            return best;
        },

        getGarmentMockupUrl() {
            const colorSlugMap = {
                '#18181B': 'black',
                '#F8FAFC': 'white',
                '#1E293B': 'navy',
                '#334155': 'charcoal',
                '#5C1D24': 'burgundy',
                '#1B4332': 'forest',
                '#D6C7B2': 'sand',
                '#1D4ED8': 'royal-blue',
                '#64748B': 'grey',
                '#3F4E3F': 'olive'
            };

            const hex = this.currentColor.hex_code;
            const slug = colorSlugMap[hex] || 'black';
            const folder = this.productType === 'polo_tshirt' ? 'polo' : 'tshirt';
            return `/images/customizer/${folder}/${slug}.jpg`;
        },

        selectColor(color) {
            this.selectedColorId = color.id;
        },

        changeQuantity(delta) {
            this.quantity = Math.max(1, Math.min(500, this.quantity + delta));
        },

        sanitizeQuantity() {
            if (!this.quantity || this.quantity < 1) this.quantity = 1;
            if (this.quantity > 500) this.quantity = 500;
        },

        calculateCustomizationFee() {
            let fee = Number(this.settings.base_customization_fee || 0);
            let imgCount = this.layers.filter(l => l.type === 'image').length;
            let txtCount = this.layers.filter(l => l.type === 'text').length;

            if (imgCount > 1) {
                fee += (imgCount - 1) * Number(this.settings.extra_image_fee || 99);
            }
            if (txtCount > 1) {
                fee += (txtCount - 1) * Number(this.settings.extra_text_fee || 49);
            }
            return fee;
        },

        calculateUnitPrice() {
            let unitBeforeBulk = Number(this.basePrice) + this.calculateCustomizationFee();
            if (this.bulkDiscountPercentage > 0) {
                return unitBeforeBulk * (1 - (this.bulkDiscountPercentage / 100));
            }
            return unitBeforeBulk;
        },

        calculateTotalPrice() {
            return this.calculateUnitPrice() * this.quantity;
        },

        calculateSavings() {
            let standardTotal = (Number(this.basePrice) + this.calculateCustomizationFee()) * this.quantity;
            return Math.max(0, standardTotal - this.calculateTotalPrice());
        },

        formatCurrency(num) {
            return '₹' + Number(num).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        },

        selectLayer(idx) {
            this.selectedLayerIndex = idx;
        },

        removeLayer(idx) {
            this.layers.splice(idx, 1);
            this.selectedLayerIndex = null;
        },

        resetDesign() {
            if (confirm('Are you sure you want to reset all custom artwork and text layers?')) {
                this.layers = [];
                this.selectedLayerIndex = null;
            }
        },

        centerLayerX() {
            if (this.selectedLayer) {
                this.selectedLayer.x = 50;
            }
        },

        centerLayerY() {
            if (this.selectedLayer) {
                this.selectedLayer.y = 50;
            }
        },

        rotateLayerBy(degrees) {
            if (this.selectedLayer) {
                this.selectedLayer.rotation = (this.selectedLayer.rotation + degrees) % 360;
            }
        },

        addTextLayer() {
            let text = (this.newTextInput || '').trim();
            if (!text) return;

            let isLight = ['#F8FAFC', '#D6C7B2', '#64748B'].includes(this.currentColor.hex_code);
            let defaultColor = isLight ? '#18181B' : '#FFFFFF';

            this.layers.push({
                id: Date.now() + Math.random(),
                type: 'text',
                text: text,
                fontFamily: "'Cinzel', serif",
                color: defaultColor,
                fontSize: 22,
                bold: true,
                italic: false,
                align: 'center',
                x: 50,
                y: 50,
                scale: 1.0,
                rotation: 0
            });

            this.selectedLayerIndex = this.layers.length - 1;
            this.newTextInput = '';
            this.activeTab = 'text';
        },

        handleFileUpload(event) {
            let file = event.target.files[0];
            if (file) {
                this.uploadArtworkFile(file);
            }
        },

        handleFileDrop(event) {
            let file = event.dataTransfer.files[0];
            if (file) {
                this.uploadArtworkFile(file);
            }
        },

        uploadArtworkFile(file) {
            // Frontend validation
            let allowedTypes = ['image/png', 'image/jpeg', 'image/jpg', 'image/svg+xml', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                alert('Please select a valid image file (PNG, JPG, SVG, or WebP).');
                return;
            }

            if (file.size > 15 * 1024 * 1024) {
                alert('File size exceeds maximum limit of 15MB.');
                return;
            }

            this.isUploading = true;
            let formData = new FormData();
            formData.append('artwork', file);
            formData.append('_token', config.csrfToken);

            fetch(config.uploadUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                this.isUploading = false;
                if (data.success) {
                    this.layers.push({
                        id: Date.now() + Math.random(),
                        type: 'image',
                        url: data.file_url,
                        filePath: data.file_path,
                        originalName: data.original_name,
                        x: 50,
                        y: 45,
                        scale: 1.0,
                        rotation: 0
                    });
                    this.selectedLayerIndex = this.layers.length - 1;
                } else {
                    alert(data.message || 'Artwork upload failed.');
                }
            })
            .catch(err => {
                this.isUploading = false;
                console.error(err);
                alert('An error occurred during artwork upload.');
            });
        },

        startDrag(event, idx) {
            this.selectedLayerIndex = idx;
            this.draggingIndex = idx;
            
            let clientX = event.touches ? event.touches[0].clientX : event.clientX;
            let clientY = event.touches ? event.touches[0].clientY : event.clientY;
            
            this.dragStartX = clientX;
            this.dragStartY = clientY;
            this.layerStartX = this.layers[idx].x;
            this.layerStartY = this.layers[idx].y;

            let onMove = (e) => {
                if (this.draggingIndex === null) return;
                let curX = e.touches ? e.touches[0].clientX : e.clientX;
                let curY = e.touches ? e.touches[0].clientY : e.clientY;

                let deltaX = (curX - this.dragStartX) / 2.5;
                let deltaY = (curY - this.dragStartY) / 2.5;

                let newX = Math.max(5, Math.min(95, this.layerStartX + deltaX));
                let newY = Math.max(5, Math.min(95, this.layerStartY + deltaY));

                this.layers[this.draggingIndex].x = newX;
                this.layers[this.draggingIndex].y = newY;
            };

            let onEnd = () => {
                this.draggingIndex = null;
                window.removeEventListener('mousemove', onMove);
                window.removeEventListener('mouseup', onEnd);
                window.removeEventListener('touchmove', onMove);
                window.removeEventListener('touchend', onEnd);
            };

            window.addEventListener('mousemove', onMove);
            window.addEventListener('mouseup', onEnd);
            window.addEventListener('touchmove', onMove);
            window.addEventListener('touchend', onEnd);
        },

        generateCanvasSnapshot() {
            return new Promise((resolve) => {
                const canvas = document.createElement('canvas');
                canvas.width = 600;
                canvas.height = 700;
                const ctx = canvas.getContext('2d');

                // Draw background color
                ctx.fillStyle = '#FAFAFA';
                ctx.fillRect(0, 0, 600, 700);

                const garmentImg = new Image();
                garmentImg.crossOrigin = 'anonymous';
                garmentImg.onload = () => {
                    ctx.drawImage(garmentImg, 0, 0, 600, 700);

                    // Print area coordinates in 600x700 canvas
                    const areaX = 600 * (this.printArea.left / 100);
                    const areaY = 700 * (this.printArea.top / 100);
                    const areaW = 600 * (this.printArea.width / 100);
                    const areaH = 700 * (this.printArea.height / 100);

                    let layerPromises = this.layers.map(layer => {
                        return new Promise((layerRes) => {
                            const posX = areaX + (areaW * (layer.x / 100));
                            const posY = areaY + (areaH * (layer.y / 100));

                            ctx.save();
                            ctx.translate(posX, posY);
                            ctx.rotate((layer.rotation * Math.PI) / 180);
                            ctx.scale(layer.scale, layer.scale);

                            if (layer.type === 'text') {
                                const styleStr = (layer.italic ? 'italic ' : '') + (layer.bold ? 'bold ' : '') + (layer.fontSize * 1.5) + 'px ' + layer.fontFamily;
                                ctx.font = styleStr;
                                ctx.fillStyle = layer.color || '#FFFFFF';
                                ctx.textAlign = 'center';
                                ctx.textBaseline = 'middle';
                                ctx.fillText(layer.text, 0, 0);
                                ctx.restore();
                                layerRes();
                            } else if (layer.type === 'image') {
                                const artImg = new Image();
                                artImg.crossOrigin = 'anonymous';
                                artImg.onload = () => {
                                    const w = Math.min(150, artImg.width);
                                    const h = (artImg.height / artImg.width) * w;
                                    ctx.drawImage(artImg, -w / 2, -h / 2, w, h);
                                    ctx.restore();
                                    layerRes();
                                };
                                artImg.onerror = () => {
                                    ctx.restore();
                                    layerRes();
                                };
                                artImg.src = layer.url;
                            } else {
                                ctx.restore();
                                layerRes();
                            }
                        });
                    });

                    Promise.all(layerPromises).then(() => {
                        resolve(canvas.toDataURL('image/png', 0.9));
                    });
                };
                garmentImg.onerror = () => {
                    resolve(canvas.toDataURL('image/png', 0.9));
                };
                garmentImg.src = this.getGarmentMockupUrl();
            });
        },

        async addToCart() {
            this.isAddingToCart = true;

            try {
                let previewDataUrl = await this.generateCanvasSnapshot();

                let artworkPaths = this.layers
                    .filter(l => l.type === 'image' && l.filePath)
                    .map(l => l.filePath);

                let payload = {
                    product_id: this.productId,
                    color_id: this.selectedColorId,
                    size_id: this.selectedSizeId,
                    quantity: this.quantity,
                    customization_data: {
                        product_type: this.productType,
                        layers: this.layers,
                        color: this.currentColor,
                        print_area: this.printArea
                    },
                    preview_image: previewDataUrl,
                    artwork_file_path: artworkPaths[0] || null,
                    artwork_files: artworkPaths,
                    _token: config.csrfToken
                };

                let response = await fetch(config.addToCartUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(payload)
                });

                let result = await response.json();
                this.isAddingToCart = false;

                if (result.success) {
                    // Update global Alpine cart store
                    if (window.Alpine && Alpine.store('cartDrawer')) {
                        Alpine.store('cartDrawer').cartData = result.cart;
                        Alpine.store('cartDrawer').open();
                    }
                    if (window.toast) {
                        window.toast(result.message, 'success');
                    }
                } else {
                    if (window.toast) {
                        window.toast(result.message || 'Failed to add custom garment to bag.', 'error');
                    } else {
                        alert(result.message || 'Failed to add custom garment to bag.');
                    }
                }
            } catch (err) {
                this.isAddingToCart = false;
                console.error(err);
                alert('An error occurred while finalizing customized garment.');
            }
        },

        async executeSaveDesign() {
            if (!config.isLoggedIn) {
                alert('Please sign in to save your customized designs to your Jacario profile.');
                window.location.href = '/login';
                return;
            }

            try {
                let previewDataUrl = await this.generateCanvasSnapshot();
                let artworkPaths = this.layers.filter(l => l.type === 'image' && l.filePath).map(l => l.filePath);

                let payload = {
                    product_id: this.productId,
                    color_id: this.selectedColorId,
                    size_id: this.selectedSizeId,
                    title: this.saveDesignTitle,
                    design_data: {
                        layers: this.layers,
                        color: this.currentColor
                    },
                    preview_image: previewDataUrl,
                    artwork_paths: artworkPaths,
                    _token: config.csrfToken
                };

                let response = await fetch(config.saveDesignUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(payload)
                });

                let data = await response.json();
                this.saveDesignModalOpen = false;

                if (data.success) {
                    alert('Design saved successfully to your Jacario account!');
                } else {
                    alert(data.message || 'Failed to save design.');
                }
            } catch (e) {
                console.error(e);
                alert('An error occurred while saving design.');
            }
        }
    };
}
</script>
@endsection
