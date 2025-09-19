<script>
          $('#driver_users').DataTable({
            "sAjaxSource": "{{ url('show_driver_users') }}",
            "bFilter": true,
            'pagingType': 'numbers',
            "ordering": true,
            "order": [
                [5, "dsc"]
            ]
        });
</script>