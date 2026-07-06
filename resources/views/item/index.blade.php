<x-pos-layout>
    <div class="pb-5">
        
        <!-- Enter Item Stock Section -->
        <div class="d-flex flex-column gap-3 mb-4">
            <h2 class="fs-4 fw-bold text-body">Enter Item Stock</h2>
            <div>
                <a href="{{ route('item.stock.create') }}" class="btn btn-primary-custom px-4 py-2 fw-bold d-inline-flex align-items-center gap-2">
                    Enter New Stock 
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-end mb-3">
            <h5 class="fw-bold text-body mb-0 fs-6">Stock Table</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-light bg-body border shadow-sm fw-bold d-flex align-items-center gap-2 px-3 small rounded-3" style="font-size: 0.85rem;">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M3.5 2.5a.5.5 0 0 0-1 0v8.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L3.5 11.293V2.5zm3.5 1a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 4a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3z"/></svg> Sort
                </button>
                <button class="btn btn-light bg-body border shadow-sm fw-bold d-flex align-items-center gap-2 px-3 small rounded-3" style="font-size: 0.85rem;">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/></svg> Filter
                </button>
            </div>
        </div>

        <div class="card border-0 rounded-4 shadow-sm bg-body overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table table-sm table-borderless text-start align-middle mb-0 text-body">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 fw-bold" style="background-color: #dbeafe;">Date</th>
                            <th class="py-3 px-4 fw-bold" style="background-color: #dbeafe;">GRN No</th>
                            <th class="py-3 px-4 fw-bold" style="background-color: #dbeafe;">Supplier</th>
                            <th class="py-3 px-4 fw-bold" style="background-color: #dbeafe;">Item Qut.</th>
                            <th class="py-3 px-4 fw-bold" style="background-color: #dbeafe;">Amount(Rs.)</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-body">
                        <tr><td class="py-3 px-4">04/06/2024</td><td class="py-3 px-4">GRN012</td><td class="py-3 px-4">New Line Supplier</td><td class="py-3 px-4">25</td><td class="py-3 px-4">25,000</td></tr>
                        <tr><td class="py-3 px-4 bg-body-tertiary">04/06/2024</td><td class="py-3 px-4 bg-body-tertiary">GRN013</td><td class="py-3 px-4 bg-body-tertiary">Perera and Sons</td><td class="py-3 px-4 bg-body-tertiary">50</td><td class="py-3 px-4 bg-body-tertiary">35,000</td></tr>
                        <tr><td class="py-3 px-4">04/06/2024</td><td class="py-3 px-4">GRN014</td><td class="py-3 px-4">UMK Supplier</td><td class="py-3 px-4">13</td><td class="py-3 px-4">25,000</td></tr>
                        <tr><td class="py-3 px-4 bg-body-tertiary">04/06/2024</td><td class="py-3 px-4 bg-body-tertiary">GRN015</td><td class="py-3 px-4 bg-body-tertiary">KM Supplier</td><td class="py-3 px-4 bg-body-tertiary">04</td><td class="py-3 px-4 bg-body-tertiary">25,000</td></tr>
                        <tr><td class="py-3 px-4 border-0">04/06/2024</td><td class="py-3 px-4 border-0">GRN016</td><td class="py-3 px-4 border-0">Amila Ent.</td><td class="py-3 px-4 border-0">60</td><td class="py-3 px-4 border-0">25,000</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <hr class="my-5 opacity-10">

        <!-- Add New Item Section -->
        <div class="d-flex flex-column gap-3 mb-4">
            <h2 class="fs-5 fw-bold text-body">Add New Item</h2>
            <div class="d-flex gap-3 flex-wrap">
                <a href="{{ route('item.create') }}" class="btn btn-primary-custom px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 small rounded-3" style="font-size: 0.9rem;">
                    Add New Item 
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                <a href="{{ route('category.create') }}" class="btn btn-outline-primary-custom bg-body px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 small rounded-3" style="font-size: 0.9rem;">
                    Add New Category 
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                <a href="{{ route('subcategory.create') }}" class="btn btn-outline-primary-custom bg-body px-4 py-2 fw-bold d-inline-flex align-items-center gap-2 small rounded-3" style="font-size: 0.9rem;">
                    Add New Sub Category 
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-end mb-3">
            <h5 class="fw-bold text-body mb-0 fs-6">Item Table</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-light bg-body border shadow-sm fw-bold d-flex align-items-center gap-2 px-3 small rounded-3" style="font-size: 0.85rem;">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M3.5 2.5a.5.5 0 0 0-1 0v8.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L3.5 11.293V2.5zm3.5 1a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 4a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3z"/></svg> Sort
                </button>
                <button class="btn btn-light bg-body border shadow-sm fw-bold d-flex align-items-center gap-2 px-3 small rounded-3" style="font-size: 0.85rem;">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/></svg> Filter
                </button>
            </div>
        </div>

        <div class="card border-0 rounded-4 shadow-sm bg-body overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table table-sm table-borderless text-start align-middle mb-0 text-body">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 fw-bold" style="background-color: #dbeafe;">Item Code</th>
                            <th class="py-3 px-4 fw-bold" style="background-color: #dbeafe;">Item</th>
                            <th class="py-3 px-4 fw-bold" style="background-color: #dbeafe;">Sub Category</th>
                            <th class="py-3 px-4 fw-bold" style="background-color: #dbeafe;">Category</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-body">
                        @for($i=0; $i<5; $i++)
                        <tr>
                            <td class="py-3 px-4 {{ $i%2 != 0 ? 'bg-body-tertiary' : '' }}">0225</td>
                            <td class="py-3 px-4 {{ $i%2 != 0 ? 'bg-body-tertiary' : '' }}">BMW Car Tire</td>
                            <td class="py-3 px-4 {{ $i%2 != 0 ? 'bg-body-tertiary' : '' }}">Tire</td>
                            <td class="py-3 px-4 {{ $i%2 != 0 ? 'bg-body-tertiary' : '' }}">Car Tire</td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>

        <hr class="my-5 opacity-10">

        <!-- Expenses Section -->
        <div class="d-flex flex-column gap-3 mb-4">
            <h2 class="fs-5 fw-bold text-body">Expenses</h2>
        </div>

        <div class="d-flex justify-content-between align-items-end mb-3">
            <h5 class="fw-bold text-body mb-0 fs-6">Expenses Table(Styles)</h5>
            <div class="d-flex gap-2">
                <button class="btn btn-light bg-body border shadow-sm fw-bold d-flex align-items-center gap-2 px-3 small rounded-3" style="font-size: 0.85rem;">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M3.5 2.5a.5.5 0 0 0-1 0v8.793l-1.146-1.147a.5.5 0 0 0-.708.708l2 1.999.007.007a.497.497 0 0 0 .7-.006l2-2a.5.5 0 0 0-.707-.708L3.5 11.293V2.5zm3.5 1a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zM7.5 6a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zm0 4a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3z"/></svg> Sort
                </button>
                <button class="btn btn-light bg-body border shadow-sm fw-bold d-flex align-items-center gap-2 px-3 small rounded-3" style="font-size: 0.85rem;">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/></svg> Filter
                </button>
            </div>
        </div>

        <div class="card border-0 rounded-4 shadow-sm bg-body overflow-hidden mb-4">
            <div class="table-responsive">
                <table class="table table-sm table-borderless text-start align-middle mb-0 text-body">
                    <thead>
                        <tr>
                            <th class="py-3 px-4 fw-bold" style="background-color: #dbeafe;">GRN No</th>
                            <th class="py-3 px-4 fw-bold" style="background-color: #dbeafe;">Supplier Name</th>
                            <th class="py-3 px-4 fw-bold" style="background-color: #dbeafe;">Qut.</th>
                            <th class="py-3 px-4 fw-bold" style="background-color: #dbeafe;">Amount(Rs.)</th>
                        </tr>
                    </thead>
                    <tbody class="fw-semibold text-body">
                        @for($i=0; $i<6; $i++)
                        <tr>
                            <td class="py-3 px-4 {{ $i%2 != 0 ? 'bg-body-tertiary' : '' }}">GRN025</td>
                            <td class="py-3 px-4 {{ $i%2 != 0 ? 'bg-body-tertiary' : '' }}">New Line Sup.</td>
                            <td class="py-3 px-4 {{ $i%2 != 0 ? 'bg-body-tertiary' : '' }}">{{ [25, 25, 14, 20, 5, 25][$i] }}</td>
                            <td class="py-3 px-4 {{ $i%2 != 0 ? 'bg-body-tertiary' : '' }}">25,000</td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-pos-layout>
