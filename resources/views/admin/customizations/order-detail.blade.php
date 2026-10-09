@extends('layouts.admin')

@section('title', 'Inspect Custom Item #' . $item->id)
@section('header_title', 'Bespoke Item Inspection & Production')

@section('content')
<div class="p-6 sm:p-8 space-y-6 max-w-6xl mx-auto">
    
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-3">
                <h1 class="text-xl sm:text-2xl font-bold font-serif-luxury text-zinc-950">Custom Item #{{ $item->id }}</h1>
                <span class="text-xs font-extrabold uppercase px-3 py-1 rounded-full {{ $item->custom_production_status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-900' }}">
                    {{ ucfirst(str_replace('_', ' ', $item->custom_production_status ?? 'Pending')) }}
                </span>
            </div>
            @if($item->order)
                <p class="text-xs text-zinc-500 mt-1">Part of Order <a href="{{ route('admin.orders.show', $item->order->id) }}" class="font-mono font-bold text-[#A4845B] hover:underline">{{ $item->order->order_number }}</a> • Placed on {{ $item->order->created_at->format('M d, Y g:i A') }}</p>
            @endif
        </div>

        <a href="{{ route('admin.customizations.orders') }}" class="px-4 py-2 rounded-xl bg-white border border-zinc-200 text-xs font-bold text-zinc-700 hover:bg-zinc-50">
            ← Back to Production Queue
        </a>
    </div>

    <!-- Main Grid: Left Preview & Artworks (5 Cols) vs Right Specs & Production Status (7 Cols) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
        
        <!-- Left: High-Res Composite Snapshot Preview (5 Cols) -->
        <div class="lg:col-span-5 bg-white rounded-3xl border border-zinc-200 shadow-sm p-6 space-y-5">
            <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-500 pb-3 border-b border-zinc-100">
                Customer's Final Design Render
            </h2>

            <div class="w-full aspect-[6/7] bg-zinc-50 rounded-2xl border border-zinc-200 p-4 flex items-center justify-center overflow-hidden relative">
                @if($item->customization_preview_path)
                    <img src="{{ asset($item->customization_preview_path) }}" class="w-full h-full object-contain filter drop-shadow-md">
                @else
                    <img src="{{ asset('images/placeholder-polo.svg') }}" class="w-full h-full object-contain">
                @endif
            </div>

            <!-- Uploaded Artwork Downloads -->
            <div class="pt-3 border-t border-zinc-100 space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-zinc-950">Original Customer Artwork Files</h3>
                
                @if($item->artwork_file_path || !empty($item->artwork_files))
                    <div class="space-y-2">
                        @if($item->artwork_file_path)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-amber-50/60 border border-amber-200/80">
                                <div class="flex items-center space-x-3 overflow-hidden">
                                    <div class="w-8 h-8 rounded-lg bg-white border border-zinc-200 p-0.5 flex items-center justify-center shrink-0">
                                        <img src="{{ asset($item->artwork_file_path) }}" class="w-full h-full object-contain">
                                    </div>
                                    <span class="text-xs font-bold text-zinc-900 truncate">Primary High-Res Artwork</span>
                                </div>
                                <a href="{{ route('admin.customizations.orders.download-artwork', $item->id) }}" class="px-3 py-1.5 rounded-lg bg-zinc-950 text-[#DFCAAB] text-xs font-bold uppercase tracking-wider hover:bg-black transition-colors shrink-0">
                                    Download
                                </a>
                            </div>
                        @endif

                        @if(!empty($item->artwork_files) && count($item->artwork_files) > 1)
                            @foreach($item->artwork_files as $idx => $extraArt)
                                @if($extraArt !== $item->artwork_file_path)
                                    <div class="flex items-center justify-between p-3 rounded-xl bg-zinc-50 border border-zinc-200">
                                        <span class="text-xs font-semibold text-zinc-800">Additional Artwork #{{ $idx + 1 }}</span>
                                        <a href="{{ route('admin.customizations.orders.download-artwork', ['itemId' => $item->id, 'path' => $extraArt]) }}" class="px-3 py-1.5 rounded-lg bg-white border border-zinc-300 text-zinc-800 text-xs font-bold hover:bg-zinc-100">
                                            Download
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                @else
                    <p class="text-xs text-zinc-500 italic">No image artwork uploaded (Typography-only design).</p>
                @endif
            </div>

        </div>

        <!-- Right: Specifications, Layers, Customer & Production Controls (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- 1. Production Status Update Form -->
            <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm p-6 space-y-4">
                <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-950 pb-2 border-b border-zinc-100">
                    Production Stage Update
                </h2>

                <form method="POST" action="{{ route('admin.customizations.orders.production-status', $item->id) }}" class="flex flex-wrap items-center gap-3">
                    @csrf
                    <div class="flex-1 min-w-[200px]">
                        <select name="status" class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-zinc-300 focus:outline-none focus:border-black font-bold bg-white">
                            <option value="pending" {{ $item->custom_production_status === 'pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="artwork_approved" {{ $item->custom_production_status === 'artwork_approved' ? 'selected' : '' }}>Artwork Approved & Sent to Machine</option>
                            <option value="in_printing" {{ $item->custom_production_status === 'in_printing' ? 'selected' : '' }}>In Printing / Embroidery Process</option>
                            <option value="completed" {{ $item->custom_production_status === 'completed' ? 'selected' : '' }}>Production Completed</option>
                        </select>
                    </div>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-zinc-950 hover:bg-black text-[#DFCAAB] text-xs font-bold uppercase tracking-wider shadow-md">
                        Update Status
                    </button>
                </form>
            </div>

            <!-- 2. Garment & Pricing Specifications -->
            <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm p-6 space-y-4">
                <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-950 pb-2 border-b border-zinc-100">
                    Garment & Commercial Breakdown
                </h2>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                    <div>
                        <span class="text-zinc-400 block font-semibold uppercase text-[10px]">Garment</span>
                        <span class="font-bold text-zinc-950 text-sm">{{ $item->product_name }}</span>
                    </div>
                    <div>
                        <span class="text-zinc-400 block font-semibold uppercase text-[10px]">Size</span>
                        <span class="font-bold text-zinc-950 text-sm">{{ $item->size_name }}</span>
                    </div>
                    <div>
                        <span class="text-zinc-400 block font-semibold uppercase text-[10px]">Color</span>
                        <div class="flex items-center space-x-1 mt-0.5">
                            <span class="w-3.5 h-3.5 rounded-full border border-zinc-300 inline-block" style="background-color: {{ $item->color_hex }}"></span>
                            <span class="font-bold text-zinc-950">{{ $item->color_name }}</span>
                        </div>
                    </div>
                    <div>
                        <span class="text-zinc-400 block font-semibold uppercase text-[10px]">Quantity</span>
                        <span class="font-bold text-zinc-950 text-sm">{{ $item->quantity }} units</span>
                    </div>
                </div>

                <div class="pt-3 border-t border-zinc-100 grid grid-cols-3 gap-4 text-xs">
                    <div>
                        <span class="text-zinc-500 block">Unit Price:</span>
                        <span class="font-bold text-zinc-900">₹{{ number_format($item->unit_price, 2) }}</span>
                    </div>
                    <div>
                        <span class="text-zinc-500 block">Customization Fee:</span>
                        <span class="font-bold text-zinc-900">₹{{ number_format($item->customization_fee, 2) }}</span>
                    </div>
                    <div>
                        <span class="text-zinc-500 block">Item Subtotal:</span>
                        <span class="font-bold text-zinc-950 text-sm">₹{{ number_format($item->subtotal, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- 3. Positioning & Design Layer Metadata -->
            @php
                $customData = $item->customization_data ?: [];
                $layers = $customData['layers'] ?? [];
            @endphp
            <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm p-6 space-y-4">
                <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-950 pb-2 border-b border-zinc-100">
                    Design Layer & Placement Coordinates
                </h2>

                @if(empty($layers))
                    <p class="text-xs text-zinc-500 italic">No structured layer data recorded.</p>
                @else
                    <div class="space-y-3">
                        @foreach($layers as $idx => $layer)
                            <div class="p-3.5 rounded-2xl bg-zinc-50 border border-zinc-200/90 text-xs space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-zinc-950 uppercase tracking-wider text-[11px]">
                                        Layer #{{ $idx + 1 }}: {{ ucfirst($layer['type'] ?? 'Unknown') }}
                                    </span>
                                    <span class="text-[10px] text-zinc-400 font-mono">
                                        Pos: (X: {{ $layer['x'] ?? 50 }}%, Y: {{ $layer['y'] ?? 50 }}%)
                                    </span>
                                </div>

                                @if(($layer['type'] ?? '') === 'text')
                                    <div class="grid grid-cols-2 gap-2 text-[11px] text-zinc-600">
                                        <div>Text: <strong class="text-zinc-950 font-serif text-xs">"{{ $layer['text'] ?? '' }}"</strong></div>
                                        <div>Font: <strong class="text-zinc-950">{{ $layer['fontFamily'] ?? 'Cinzel' }}</strong></div>
                                        <div>Color: <strong class="text-zinc-950">{{ $layer['color'] ?? '#FFFFFF' }}</strong></div>
                                        <div>Size: <strong class="text-zinc-950">{{ $layer['fontSize'] ?? 20 }}px</strong></div>
                                        <div>Rotation: <strong class="text-zinc-950">{{ $layer['rotation'] ?? 0 }}°</strong></div>
                                    </div>
                                @elseif(($layer['type'] ?? '') === 'image')
                                    <div class="grid grid-cols-2 gap-2 text-[11px] text-zinc-600">
                                        <div>Scale: <strong class="text-zinc-950">{{ round(($layer['scale'] ?? 1) * 100) }}%</strong></div>
                                        <div>Rotation: <strong class="text-zinc-950">{{ $layer['rotation'] ?? 0 }}°</strong></div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- 4. Customer Contact Card -->
            @if($item->order)
                <div class="bg-white rounded-3xl border border-zinc-200 shadow-sm p-6 space-y-3">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-zinc-950 pb-2 border-b border-zinc-100">
                        Customer & Shipping Destination
                    </h2>

                    <div class="text-xs space-y-1 text-zinc-700">
                        <p class="font-bold text-zinc-950 text-sm">{{ $item->order->customer_name }}</p>
                        <p>Phone: <strong class="font-mono text-zinc-900">{{ $item->order->customer_phone }}</strong></p>
                        <p>Email: {{ $item->order->customer_email }}</p>
                        <div class="pt-2 border-t border-zinc-100 text-zinc-600">
                            @php $addr = $item->order->shipping_address; @endphp
                            @if(is_array($addr))
                                <p>{{ $addr['address_line_1'] ?? '' }}</p>
                                @if(!empty($addr['address_line_2'])) <p>{{ $addr['address_line_2'] }}</p> @endif
                                <p>{{ $addr['city'] ?? '' }}, {{ $addr['state'] ?? '' }} - {{ $addr['postal_code'] ?? '' }}</p>
                                <p>{{ $addr['country'] ?? 'India' }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

        </div>

    </div>

</div>
@endsection
