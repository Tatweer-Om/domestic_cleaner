<script>
    $('#worker_users').DataTable({
        "sAjaxSource": "{{ url('show_worker_users') }}",
        "bFilter": true,
        'pagingType': 'numbers',
        "ordering": true,
        "order": [
            [5, "desc"] // fix here
        ]
    });
</script>
