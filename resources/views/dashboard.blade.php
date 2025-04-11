<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RFID Registration System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
            background-color: #f8f9fa;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="text-center">RFID Registration System</h1>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-secondary">Logout</button>
            </form>
        </div>
        <table id="cardsTable" class="table table-striped table-bordered" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>UID</th>
                    <th>Registration Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#cardsTable').DataTable({
                paging: true,
                searching: true,
                ordering: true,
                info: true,
                lengthMenu: [5, 10, 25, 50],
                pageLength: 10,
                columnDefs: [
                    {
                        targets: 0,
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        orderable: false,
                        targets: 3
                    }
                ]
            });

            const API_KEY = '{{ config('app.api_key', 'X7K9P2M4Q8R3T6W5') }}';
        const CSRF_TOKEN = '{{ csrf_token() }}';

        function fetchData() {
            $.ajax({
                url: '/api/fetch?apikey=' + encodeURIComponent(API_KEY),
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    table.clear();
                    data.forEach(function(card) {
                        table.row.add([
                            null,
                            card.uid,
                            card.created_at,
                            '<button class="btn btn-danger btn-sm delete-btn" data-id="' + card.id + '">Delete</button>'
                        ]);
                    });
                    table.draw();
                },
                error: function(xhr, status, error) {
                    console.error('Fetch error:', { status: xhr.status, statusText: xhr.statusText, response: xhr.responseText, error });
                }
            });
        }

        $('#cardsTable').on('click', '.delete-btn', function() {
            if (confirm('Are you sure you want to delete this record?')) {
                var id = $(this).data('id');
                console.log('Attempting to delete ID:', id);
                $.ajax({
                    url: '/api/delete?apikey=' + encodeURIComponent(API_KEY),
                    method: 'POST',
                    data: {
                        id: id,
                        _token: CSRF_TOKEN // Include CSRF token
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            fetchData();
                            alert('Record deleted successfully');
                        } else {
                            alert('Error deleting record: ' + (response.error || 'Unknown server error'));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Delete error details:', {
                            status: xhr.status,
                            statusText: xhr.statusText,
                            responseText: xhr.responseText,
                            readyState: xhr.readyState,
                            error: error
                        });
                        alert('Error deleting record: ' + (xhr.responseJSON?.error || xhr.statusText || 'Unknown error - check console'));
                    }
                });
            }
        });

        fetchData();
        setInterval(fetchData, 1000);
        });
    </script>
</body>

</html>