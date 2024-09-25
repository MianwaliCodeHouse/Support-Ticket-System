 <!-- Include DataTables JS -->
 <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
 <script>
     // Filter based on student selection
     $('#studentFilter').on('change', function() {
         table.draw();
     });
     var table = $('#ticketsDataTable').DataTable({
         processing: true,
         serverSide: true,
         @if (auth()->user()->hasRole('admin'))
             ajax: {
                 url: "{{ route('tickets.data') }}",
                 data: function(d) {
                     return $.extend({}, d, {
                         student_filter: $('#studentFilter').val()
                     });
                 }
             },
         @else
             ajax: "{{ route('tickets.data', auth()->user()->id) }}",
         @endif
         columns: [{
                 data: null,
                 orderable: false,
                 searchable: false,
                 render: function(data, type, row, meta) {
                     return meta.row + meta.settings._iDisplayStart + 1;
                 }
             },
             @if (auth()->user()->hasRole('admin'))
                 {
                     data: 'student_name',
                     name: 'student_name'
                 },
             @endif {
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
             @if (auth()->user()->hasRole('admin'))
                 {
                     data: 'created_at',
                     name: 'created_at'
                 },
             @endif {
                 data: 'actions',
                 name: 'actions',
                 orderable: false,
                 searchable: false
             },
         ],

     });

     $.ajaxSetup({
         headers: {
             'X-CSRF-TOKEN': "{{ csrf_token() }}"
         }
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
                     success: function(response) {
                         if (response == 1) {
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
 </script>

 @if (auth()->user()->hasRole('student'))
     <script>
         const createModal = document.getElementById("createTicketModal");
         const editModal = document.getElementById("editTicketModal");

         // Open Create Ticket Modal
         document.getElementById("createTicketBtn").onclick = function() {
             createTitleError.innerHTML = '';
             createDescriptionError.innerHTML = '';
             $('#createTicketForm')[0].reset()
             createModal.classList.remove("hidden");
         };

         // Open Edit Ticket Modal (dummy data for demonstration)
         function openEditModel(ticket) {

             // Populate the edit modal with existing ticket data
             document.getElementById("editTitle").value = ticket.title; // Assuming 'title' is a field in your ticket model
             document.getElementById("editDescription").value = ticket
                 .description; // Assuming 'description' is a field in your ticket model
             document.getElementById("editId").value = ticket.id; // Optional: if you want to store the ticket ID
             editModal.classList.remove("hidden");
         };

         // Close Modal Function
         function closeModal(modal) {

             modal.classList.add("hidden");
         }

         // Attach close event to close buttons
         const closeButtons = document.querySelectorAll(".close");
         closeButtons.forEach(button => {
             button.onclick = function() {

                 const modalId = this.getAttribute("data-modal");
                 closeModal(document.getElementById(modalId));
             };
         });


         function createTicket() {
             $('.createTicketFormErrors').html('');
             $("#createTicketFormBtn").html(`Creating
                                     <div class="ml-3 border-8 border-t-8 border-gray-200 rounded-full animate-spin border-t-blue-500" style="width: 20px;height:20px;"></div>
                                 </div></button>`)
             var formData = new FormData($('#createTicketForm')[0]);
             $.ajax({
                 url: '{{ route('tickets.store') }}',
                 method: 'POST',
                 data: formData,
                 contentType: false,
                 processData: false,
                 success: function(response) {
                     if (response == 1) {
                         Swal.fire({
                             title: "Ticket Created Successfully!",
                             icon: "success"
                         });
                         $("#createTicketFormBtn").html(`Submit`)
                         $('#createTicketForm')[0].reset()
                         createTicketModal.classList.add("hidden");
                         table.draw()
                     }

                 },
                 error: function(error) {
                     $("#createTicketFormBtn").html(`Submit`)
                     // Clear previous error messages
                     createTitleError.innerHTML = '';
                     createDescriptionError.innerHTML = '';

                     if (error.responseJSON && error.responseJSON.errors) {
                         if (error.responseJSON.errors.title) {
                             createTitleError.innerHTML = error.responseJSON.errors.title[0] || '';
                         }
                         if (error.responseJSON.errors.description) {
                             createDescriptionError.innerHTML = error.responseJSON.errors.description[0] || '';
                         }
                     } else {
                         console.error("Unexpected error:", error); // Log unexpected errors for debugging
                     }
                 }
             });
         }

         function updateTicket(id) {
             $('.editTicketFormErrors').html('')
             $("#updateTicketFormBtn").html(`Updating
                                     <div class="ml-3 border-8 border-t-8 border-gray-200 rounded-full animate-spin border-t-blue-500" style="width: 20px;height:20px;"></div>
                                 </div></button>`)
             var formData2 = new FormData($('#editTicketForm')[0]);

             $.ajax({
                 url: '{{ route('tickets.update', '') }}/' + id,
                 type: 'post',
                 data: formData2,
                 contentType: false,
                 processData: false,
                 success: function(response) {
                     if (response == 1) {
                         Swal.fire({
                             title: "Ticket Updated Successfully!",
                             icon: "success"
                         });
                         $("#updateTicketFormBtn").html(`Update`)
                         $('#editTicketForm')[0].reset()
                         editTicketModal.classList.add("hidden");
                         table.draw()
                     }

                 },
                 error: function(error) {
                     $("#createTicketFormBtn").html(`Submit`)
                     // Clear previous error messages
                     editTitleError.innerHTML = '';
                     editDescriptionError.innerHTML = '';

                     if (error.responseJSON && error.responseJSON.errors) {
                         if (error.responseJSON.errors.title) {
                             editTitleError.innerHTML = error.responseJSON.errors.title[0] || '';
                         }
                         if (error.responseJSON.errors.description) {
                             editDescriptionError.innerHTML = error.responseJSON.errors.description[0] || '';
                         }
                     } else {
                         console.error("Unexpected error:", error); // Log unexpected errors for debugging
                     }
                 }
             });
         }
     </script>
 @endif
 @if (auth()->user()->hasRole('admin'))
     <script>
         function acceptTicket(id) {
             console.log(id);
             $.ajax({
                 url: "{{ route('ticket.accept', '') }}/" + id,
                 type: "GET",
                 success: function(response) {
                     if (response == 1) {
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
 @endif
