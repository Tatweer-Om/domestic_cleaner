<script>
        $('#general_users').DataTable({
            "sAjaxSource": "{{ url('show_general_users') }}",
            "bFilter": true,
            'pagingType': 'numbers',
            "ordering": true,
            "order": [
                [5, "dsc"]
            ]
        });
</script>