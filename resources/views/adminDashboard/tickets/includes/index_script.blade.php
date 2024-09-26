 
 <script>
     // Filter based on student selection
     $('#studentFilter').on('change', function() {
         table.draw();
     });
     var table = $('#ticketsDataTable').DataTable({
         processing: true,
         serverSide: true,
         ajax: {
             url: "{{ route('tickets.data') }}",
             data: function(d) {
                 return $.extend({}, d, {
                     student_filter: $('#studentFilter').val()
                 });
             }
         },

         columns: [{
                 data: null,
                 orderable: false,
                 searchable: false,
                 render: function(data, type, row, meta) {
                     return meta.row + meta.settings._iDisplayStart + 1;
                 }
             },

             {
                 data: 'student_name',
                 name: 'student_name'
             },
             {
                 data: 'title',
                 name: 'title'
             },
             {
                 data: 'description',
                 name: 'description'
             },
             {
                 data: 'status',
                 name: 'status',
                 orderable: false,
             },

             {
                 data: 'created_at',
                 name: 'created_at'
             },
             {
                 data: 'actions',
                 name: 'actions',
                 orderable: false,
                 searchable: false
             },
         ],

     });

    

     function destroy(id) {
         Swal.fire({
             title: "Are you sure?",
             text: "You won't be able to revert this!",
             icon: "warning",
             showCancelButton: true,
             confirmButtonColor: "#3085d6",
             cancelButtonColor: "#d33",
             confirmButtonText: "Yes, delete it!"
         }).then((result) => {
             if (result.isConfirmed) {
                 console.log(id);
                 $.ajax({
                     url: "{{ route('tickets.destroy', '') }}/" + id,
                     type: "DELETE",
                     headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                     success: function(response) {
                         if (response.status == 200) {
                             Swal.fire({
                                 title: "Deleted!",
                                 text: "Your file has been deleted.",
                                 icon: "success"
                             });
                             table.draw();
                         }
                     }
                 })

             }
         });
     }

     function acceptTicket(id) {
         $.ajax({
             url: "{{ route('ticket.accept', '') }}/" + id,
             type: "GET",
             success: function(response) {
                 if (response.status == 200) {
                     Swal.fire({
                         title: "Accepted Token!",
                         icon: "success"
                     });
                     table.draw();
                 }
             }
         })
     }
 </script>
 
