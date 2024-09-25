<x-app-layout>
    <style>
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 10px;
        }

        .dataTables_wrapper .dataTables_length select {
            min-width: 80px;
        }
    </style>

    <!-- Include DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tickets') }}
        </h2>
    </x-slot>





    <!-- Create Ticket Modal -->
    <div id="createTicketModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-10 hidden">
        <div class="bg-white rounded-lg shadow-lg w-96">
            <div class="flex justify-between items-center p-4 border-b">
                <h2 class="text-lg font-semibold">Create Ticket</h2>
                <span class="close cursor-pointer text-gray-500" data-modal="createTicketModal">&times;</span>
            </div>
            <form id="createTicketForm" class="p-4">
                <label for="createTitle" class="block text-sm font-medium text-gray-700">Title:</label>
                <input type="text" id="createTitle" name="title" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500" />
                <div class="text-red-600" class="createTicketFormErrors" id="createTitleError"></div>

                <label for="createDescription" class="block mt-4 text-sm font-medium text-gray-700">Description:</label>
                <textarea id="createDescription" name="description" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500" rows="4"></textarea>
                <div class="text-red-600" class="createTicketFormErrors" id="createDescriptionError"></div>
                <button type="button" class="flex justify-center mt-4 w-full px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-700"
                    onclick="createTicket()" id="createTicketFormBtn">Submit</button>
            </form>
        </div>
    </div>

    <!-- Edit Ticket Modal -->
    <div id="editTicketModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-10 hidden">
        <div class="bg-white rounded-lg shadow-lg w-96">
            <div class="flex justify-between items-center p-4 border-b">
                <h2 class="text-lg font-semibold">Edit Ticket</h2>
                <span class="close cursor-pointer text-gray-500" data-modal="editTicketModal">&times;</span>
            </div>
            <form id="editTicketForm" class="p-4">
                <input type="number" name="id" id="editId" hidden>
                <label for="editTitle" class="block text-sm font-medium text-gray-700">Title:</label>
                <input type="text" id="editTitle" name="title"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500" />
                <div class="text-red-600" class="editTicketFormErrors" id="editTitleError"></div>

                <label for="editDescription" class="block mt-4 text-sm font-medium text-gray-700">Description:</label>
                <textarea id="editDescription" name="description"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500" rows="4"></textarea>
                <div class="text-red-600" class="editTicketFormErrors" id="editDescriptionError"></div>
                <button type="button" class="flex justify-center mt-4 w-full px-4 py-2 text-white bg-green-500 rounded hover:bg-green-700"
                    onclick="updateTicket(editId.value)" id="updateTicketFormBtn">Update</button>
            </form>
        </div>
    </div>



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (auth()->user()->hasRole('student'))
                        <div class="flex justify-end mb-4">
                            <button class="bg-slate-700 text-sm text-white font-semibold px-4 py-2 rounded hover:bg-slate-800"
                                id="createTicketBtn">
                                Create Ticket
                            </button>
                        </div>
                    @endif
                    @if (auth()->user()->hasRole('admin'))
                    <div class="text-center">
                        <select name="student_filter" id="studentFilter" class="mb-4 w-full md:w-1/2">
                            <option value="">All Students</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="overflow-x-auto">
                        <table id="ticketsDataTable"
                            class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                            <thead>
                                <tr class="bg-slate-700 text-white uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left" style="width: 100px;">#</th>
                                    @if (auth()->user()->hasRole('admin'))
                                        <th class="py-3 px-6 text-left">Student Name</th>
                                    @endif
                                    <th class="py-3 px-6 text-left">Title</th>
                                    <th class="py-3 px-6 text-left">Description</th>
                                    <th class="py-3 px-6 text-center" style="width: 200px;">Status</th>
                                    @if (auth()->user()->hasRole('admin'))
                                        <th class="py-3 px-6 text-left">Created At</th>
                                    @endif
                                    <th class="py-3 px-6 text-center" style="width: 300px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light">

                            </tbody>
                        </table>
                        {{-- <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                            <thead>
                                <tr class="bg-slate-700 text-white uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left" style="width: 200px !important;">#</th>
                                    @if (auth()->user()->hasRole('admin'))
                                        <th class="py-3 px-6 text-left">Student Name</th>
                                    @endif
                                    <th class="py-3 px-6 text-left">Title</th>
                                    <th class="py-3 px-6 text-left">Description</th>
                                    <th class="py-3 px-6 text-center" style="width: 200px !important;">Status</th>
                                    <th class="py-3 px-6 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light" id="ticketsDataTable">
                                @if (auth()->user()->hasRole('student'))
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                        <td class="py-3 px-6" data-label="Name">1</td>
                                        <td class="py-3 px-6" data-label="Email">Title</td>
                                        <td class="py-3 px-6" data-label="Email">Description</td>

                                        <td class="py-3 px-6 text-center" data-label="Name">
                                            <span
                                                class="bg-yellow-500 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                                Pending
                                            </span>
                                        </td>
                                        <td class="py-3 px-6 text-center" data-label="Actions">
                                            <a href="{{ route('tickets.details') }}"
                                                class="bg-slate-700 text-white py-2 px-4 rounded hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">View
                                                Details</a>
                                            <button
                                                class="bg-slate-700 text-white py-2 px-4 rounded hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 ml-2"
                                                id="editTicketBtn">Edit</button>
                                            <button
                                                class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-opacity-75 ml-2"
                                                onclick="destroy()">Delete</button>
                                        </td>
                                    </tr>
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                        <td class="py-3 px-6" data-label="Name">1</td>
                                        <td class="py-3 px-6" data-label="Email">Title</td>
                                        <td class="py-3 px-6" data-label="Email">Description</td>

                                        <td class="py-3 px-6 text-center" data-label="Name">
                                            <span
                                                class="bg-blue-500 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                                In Progress
                                            </span>
                                        </td>
                                        <td class="py-3 px-6 text-center" data-label="Actions">
                                            <button
                                                class="bg-slate-700 text-white py-2 px-4 rounded hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">View
                                                Details</button>
                                            <button
                                                class="bg-slate-700 text-white py-2 px-4 rounded hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 ml-2">Edit</button>
                                            <button
                                                class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-opacity-75 ml-2"
                                                oncli>Delete</button>
                                        </td>
                                    </tr>
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                        <td class="py-3 px-6" data-label="Name">1</td>
                                        <td class="py-3 px-6" data-label="Email">Title</td>
                                        <td class="py-3 px-6" data-label="Email">Description</td>

                                        <td class="py-3 px-6 text-center" data-label="Name">
                                            <span
                                                class="bg-gray-500 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                                Closed
                                            </span>
                                        </td>
                                        <td class="py-3 px-6 text-center" data-label="Actions">
                                            <button
                                                class="bg-slate-700 text-white py-2 px-4 rounded hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">View
                                                Details</button>
                                            <button
                                                class="bg-slate-700 text-white py-2 px-4 rounded hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 ml-2">Edit</button>
                                            <button
                                                class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-opacity-75 ml-2">Delete</button>
                                        </td>
                                    </tr>
                                @endif

                                @if (auth()->user()->hasRole('admin'))
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                        <td class="py-3 px-6" data-label="Name">1</td>
                                        <td class="py-3 px-6" data-label="Email">Student Name</td>
                                        <td class="py-3 px-6" data-label="Email">Title</td>
                                        <td class="py-3 px-6" data-label="Email">Description</td>

                                        <td class="py-3 px-6 text-center" data-label="Name">
                                            <span
                                                class="bg-yellow-500 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                                Pending
                                            </span>
                                        </td>
                                        <td class="py-3 px-6 text-center" data-label="Actions">
                                            <button
                                                class="bg-slate-700 text-white py-2 px-4 rounded hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">Accept</button>
                                            <button
                                                class="bg-slate-700 text-white py-2 px-4 rounded hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 ml-2">Edit</button>
                                            <button
                                                class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-opacity-75 ml-2"
                                                onclick="destroy()">Delete</button>
                                        </td>
                                    </tr>
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                        <td class="py-3 px-6" data-label="Name">1</td>
                                        <td class="py-3 px-6" data-label="Email">Student Name</td>
                                        <td class="py-3 px-6" data-label="Email">Title</td>
                                        <td class="py-3 px-6" data-label="Email">Description</td>

                                        <td class="py-3 px-6 text-center" data-label="Name">
                                            <span
                                                class="bg-blue-500 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                                In Progress
                                            </span>
                                        </td>
                                        <td class="py-3 px-6 text-center" data-label="Actions">
                                            <button
                                                class="bg-slate-700 text-white py-2 px-4 rounded hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">View
                                                Details</button>
                                            <button
                                                class="bg-slate-700 text-white py-2 px-4 rounded hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 ml-2">Edit</button>
                                            <button
                                                class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-opacity-75 ml-2">Delete</button>
                                        </td>
                                    </tr>
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                        <td class="py-3 px-6" data-label="Name">1</td>
                                        <td class="py-3 px-6" data-label="Email">Student Name</td>
                                        <td class="py-3 px-6" data-label="Email">Title</td>
                                        <td class="py-3 px-6" data-label="Email">Description</td>

                                        <td class="py-3 px-6 text-center" data-label="Name">
                                            <span
                                                class="bg-gray-500 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                                Closed
                                            </span>
                                        </td>
                                        <td class="py-3 px-6 text-center" data-label="Actions">
                                            <button
                                                class="bg-slate-700 text-white py-2 px-4 rounded hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75">View
                                                Details</button>
                                            <button
                                                class="bg-slate-700 text-white py-2 px-4 rounded hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75 ml-2">Edit</button>
                                            <button
                                                class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-opacity-75 ml-2">Delete</button>
                                        </td>
                                    </tr>
                                @endif

                            </tbody>
                        </table> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>



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
                    url: '{{ route('tickets.data') }}',
                    data: function(d) {
                        return $.extend({}, d, {
                            student_filter: $('#studentFilter').val()
                        });
                    }
                },
            @else
                ajax: '{{ route('tickets.data', auth()->user()->id) }}',
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
                        url: "{{ route('ticket.destroy', '') }}/" + id,
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
                    url: '{{ route('ticket.update', '') }}/' + id,
                    type: 'POST',
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
</x-app-layout>
