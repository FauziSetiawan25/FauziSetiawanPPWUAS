{{-- resources/views/components/datatable.blade.php --}}

<link rel="stylesheet" href="{{ asset('datatables/dataTables.bootstrap5.min.css') }}" />

<script src="{{ asset('datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('datatables/dataTables.bootstrap5.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('.datatable').DataTable({
            responsive: true,
            autoWidth: false,
            language: {
                url: "{{ asset('datatables/i18n/Indonesian.json') }}" // Opsional: Untuk Bahasa Indonesia
            },
            columnDefs: [
                { targets: [0, -1], orderable: false } // Kolom pertama dan aksi tidak bisa diurutkan
            ]
        });
    });
</script>
