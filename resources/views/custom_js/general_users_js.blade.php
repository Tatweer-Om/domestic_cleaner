<script>
      $('#general_users').DataTable({
    "sAjaxSource": "{{ url('show_general_users') }}",
    "bFilter": true,
    'pagingType': 'numbers',
    "ordering": true,
    "order": [[5, "desc"]],
    "language": {
        "search": "{{ trans('messages.search', [], session('locale')) }}",
        "lengthMenu": "{{ trans('messages.show_entries', [], session('locale')) }}",
        "zeroRecords": "{{ trans('messages.no_matching_records', [], session('locale')) }}",
        "info": "{{ trans('messages.showing_entries', [], session('locale')) }}",
        "infoEmpty": "{{ trans('messages.no_entries', [], session('locale')) }}",
        "infoFiltered": "{{ trans('messages.filtered_from_total', [], session('locale')) }}",
        "paginate": {
            "first": "{{ trans('messages.first', [], session('locale')) }}",
            "last": "{{ trans('messages.last', [], session('locale')) }}",
            "next": "{{ trans('messages.next', [], session('locale')) }}",
            "previous": "{{ trans('messages.previous', [], session('locale')) }}"
        }
    }
});

</script>