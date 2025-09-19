<script>
      $('#all_customers').DataTable({
            "sAjaxSource": "{{ url('show_customer') }}",
            "bFilter": true,
            'pagingType': 'numbers',
            "ordering": true,
        });

</script>