<x-pos-layout>
    <div class="container-fluid h-100 d-flex flex-column py-2">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 fw-bold mb-1">Activity Logs</h1>
                <p class="text-muted mb-0">System audit trail of all major actions.</p>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 flex-grow-1 overflow-hidden">
            <div class="card-body p-0 d-flex flex-column h-100">
                <div class="table-responsive flex-grow-1">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Time</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Model</th>
                                <th>Properties</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td class="text-nowrap text-muted small">
                                        {{ $log->created_at->format('Y-m-d h:i A') }}
                                        <br><span style="font-size: 0.7rem;">{{ $log->created_at->diffForHumans() }}</span>
                                    </td>
                                    <td class="fw-bold">
                                        {{ $log->causer ? $log->causer->name : 'System' }}
                                    </td>
                                    <td>
                                        @if($log->event === 'created')
                                            <span class="badge bg-success-subtle text-success">Created</span>
                                        @elseif($log->event === 'updated')
                                            <span class="badge bg-primary-subtle text-primary">Updated</span>
                                        @elseif($log->event === 'deleted')
                                            <span class="badge bg-danger-subtle text-danger">Deleted</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">{{ ucfirst($log->event) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-muted small">
                                        {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                    </td>
                                    <td style="max-width: 300px;">
                                        @if($log->properties && $log->properties->count() > 0)
                                            <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="collapse" data-bs-target="#props-{{ $log->id }}">
                                                View Details
                                            </button>
                                            <div class="collapse mt-2" id="props-{{ $log->id }}">
                                                <div class="card card-body bg-body-tertiary border-0 p-2" style="font-size: 0.75rem; max-height: 150px; overflow-y: auto;">
                                                    <pre class="mb-0"><code>{{ json_encode($log->properties, JSON_PRETTY_PRINT) }}</code></pre>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">No activity logs found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="border-top p-3 bg-body-tertiary">
                    {{ $logs->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</x-pos-layout>
