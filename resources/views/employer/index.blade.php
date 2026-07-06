<x-pos-layout>
    <div class="pb-5">
        
        <div class="d-flex flex-column gap-3 mb-4">
            <h2 class="fs-4 fw-bold text-body">Employer Management</h2>
        </div>

        <!-- Attendance Mark Section -->
        <div class="mb-4">
            <div class="d-flex align-items-center gap-2 mb-4">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                <h5 class="fw-bold text-body mb-0 fs-6">Attendant Mark</h5>
            </div>
            
            <div class="d-flex justify-content-between align-items-end mb-3">
                <a href="{{ route('employer.attendance') }}" class="btn btn-primary-custom px-4 py-2 fw-bold d-inline-flex align-items-center gap-2">
                    Attendence Mark 
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                <button class="btn btn-primary-custom px-3 py-1 fw-bold rounded-2 small d-inline-flex align-items-center gap-2" style="font-size: 0.85rem;">
                    Edit Sheet <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </button>
            </div>

            <div class="card border-0 rounded-4 shadow-sm bg-body overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless text-start align-middle mb-0 text-body">
                        <thead>
                            <tr>
                                <th class="py-3 px-4 fw-bold" style="background-color: #e0f2fe;">Date</th>
                                <th class="py-3 px-4 fw-bold" style="background-color: #e0f2fe;">Emp. ID</th>
                                <th class="py-3 px-4 fw-bold" style="background-color: #e0f2fe;">Name</th>
                                <th class="py-3 px-4 fw-bold" style="background-color: #e0f2fe;">Designation</th>
                                <th class="py-3 px-4 fw-bold" style="background-color: #e0f2fe;">Present</th>
                                <th class="py-3 px-4 fw-bold" style="background-color: #e0f2fe;">Absent</th>
                                <th class="py-3 px-4 fw-bold" style="background-color: #e0f2fe;">Start Time</th>
                                <th class="py-3 px-4 fw-bold" style="background-color: #e0f2fe;">End Time</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-body">
                            @php
                                $attendances = [
                                    ['id' => '001', 'name' => 'Dasun Daluwaththa', 'present' => true],
                                    ['id' => '002', 'name' => 'Hasith Jayantha', 'present' => true],
                                    ['id' => '001', 'name' => 'Kasun', 'present' => true],
                                    ['id' => '002', 'name' => 'Jayanidu', 'present' => true],
                                    ['id' => '001', 'name' => 'Thusala', 'present' => false],
                                    ['id' => '002', 'name' => 'Abdul Salam', 'present' => true],
                                ];
                            @endphp
                            @foreach($attendances as $index => $emp)
                            <tr>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">2024.04.02</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">{{ $emp['id'] }}</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">{{ $emp['name'] }}</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">Manager</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">
                                    @if($emp['present'])
                                        <div class="bg-success text-white d-inline-flex align-items-center justify-content-center rounded" style="width: 24px; height: 24px;">✓</div>
                                    @else
                                        <div class="bg-danger text-white d-inline-flex align-items-center justify-content-center rounded" style="width: 24px; height: 24px;">✕</div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">
                                    @if(!$emp['present'])
                                        <div class="bg-success text-white d-inline-flex align-items-center justify-content-center rounded" style="width: 24px; height: 24px;">✓</div>
                                    @else
                                        <div class="bg-danger text-white d-inline-flex align-items-center justify-content-center rounded" style="width: 24px; height: 24px;">✕</div>
                                    @endif
                                </td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">06.00AM</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">03.00PM</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Salary Management Section -->
        <div class="mb-4">
            <div class="d-flex align-items-center gap-2 mb-4">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <h5 class="fw-bold text-body mb-0 fs-6">Salary Management</h5>
            </div>

            <div class="card border-0 rounded-4 shadow-sm bg-body overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless text-start align-middle mb-0 text-body">
                        <thead>
                            <tr>
                                <th class="py-3 px-4 fw-bold" style="background-color: #e0f2fe;">Date</th>
                                <th class="py-3 px-4 fw-bold" style="background-color: #e0f2fe;">Emp. ID</th>
                                <th class="py-3 px-4 fw-bold" style="background-color: #e0f2fe;">Name</th>
                                <th class="py-3 px-4 fw-bold" style="background-color: #e0f2fe;">Designation</th>
                                <th class="py-3 px-4 fw-bold" style="background-color: #e0f2fe;">Salary</th>
                                <th class="py-3 px-4 fw-bold" style="background-color: #e0f2fe;"></th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-body">
                            @foreach($attendances as $index => $emp)
                            <tr>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">2024.04.02</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">{{ $emp['id'] }}</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">{{ $emp['name'] }}</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">{{ $index < 1 ? 'Manager' : ($index < 2 ? 'Assistern' : 'Pumper') }}</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">LKR{{ $index < 1 ? '60,000' : ($index < 2 ? '40,000' : '35,000') }}</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">
                                    <a href="{{ route('employer.paysheet.select') }}" class="btn btn-primary-custom btn-sm fw-bold px-3 py-1 rounded-2" style="font-size: 0.75rem;">Get Pay Sheet</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Employer Details Section -->
        <div class="mb-4">
            <div class="d-flex align-items-center gap-2 mb-4">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <h5 class="fw-bold text-body mb-0 fs-6">Employer Details</h5>
            </div>

            <!-- Banner -->
            <div class="card border-0 rounded-4 shadow-sm mb-4" style="background: linear-gradient(90deg, #ffffff 0%, #f0f9ff 100%);">
                <div class="card-body p-3 p-md-5 d-flex flex-column flex-md-row align-items-center justify-content-between">
                    <div>
                        <h4 class="fw-bold text-body mb-1">Welcome For</h4>
                        <h2 class="fs-4 fw-bolder text-primary-custom mb-3">New Employer</h2>
                        <p class="text-secondary small fw-semibold mb-4">Enter Details for add New employer of your company</p>
                        <a href="{{ route('employer.create') }}" class="btn btn-primary-custom fw-bold px-4 py-2 rounded-3 d-inline-flex align-items-center gap-2">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            Add New Employer
                        </a>
                    </div>
                    <!-- Placeholder for illustration -->
                    <div class="d-none d-md-block" style="width: 300px; height: 150px; background-color: #e0f2fe; border-radius: 20px; opacity: 0.5;">
                        <!-- You can place an SVG illustration here -->
                    </div>
                </div>
            </div>

            <!-- Employer List Table -->
            <div class="card border-0 rounded-4 shadow-sm bg-body overflow-hidden" style="max-width: 600px;">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless text-start align-middle mb-0 text-body">
                        <thead>
                            <tr>
                                <th class="py-3 px-4 fw-bold" style="background-color: #e0f2fe;">Date</th>
                                <th class="py-3 px-4 fw-bold" style="background-color: #e0f2fe;">Emp. ID</th>
                                <th class="py-3 px-4 fw-bold" style="background-color: #e0f2fe;">Name</th>
                                <th class="py-3 px-4 fw-bold" style="background-color: #e0f2fe;">Designation</th>
                            </tr>
                        </thead>
                        <tbody class="fw-semibold text-body">
                            @foreach($attendances as $index => $emp)
                            <tr>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">2024.04.02</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">{{ $emp['id'] }}</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">{{ $emp['name'] }}</td>
                                <td class="py-3 px-4 {{ $index % 2 == 0 ? '' : 'bg-body-tertiary' }}">{{ $index < 1 ? 'Manager' : ($index < 2 ? 'Assistern' : 'Pumper') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
</x-pos-layout>
