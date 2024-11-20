<!-- Floating Button for Timeframe Modal -->
<button type="button" class="btn btn-primary btn-floating position-fixed bottom-0 end-0 m-4" style="z-index: 1100;"
    data-bs-toggle="modal" data-bs-target="#timeframeModal">
    <i class='bx bx-calendar'></i> <!-- Boxicons calendar icon -->
</button>
<!-- Modal for Timeframe -->
<div class="modal fade" id="timeframeModal" tabindex="-1" aria-labelledby="timeframeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="timeframeModalLabel">Select Timeframe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="filterForm" action="{{ route('change_datetime') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="fromDate" class="form-label">From Date</label>
                    <input type="datetime-local" class="form-control" id="fromDate" name="from_datetime" value="{{ session('from_datetime') }}">
                </div>
                <div class="mb-3">
                    <label for="toDate" class="form-label">To Date</label>
                    <input type="datetime-local" class="form-control" id="toDate" name="to_datetime" value="{{ session('to_datetime') }}">
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</a>
                <button type="submit" class="btn btn-primary">Filter</button>
                </form>
            </div>
        </div>
    </div>
</div>
