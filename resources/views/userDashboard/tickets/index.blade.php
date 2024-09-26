<x-app-layout>

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
                @csrf
                <label for="createTitle" class="block text-sm font-medium text-gray-700">Title:</label>
                <input type="text" id="createTitle" name="title" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500" />
                <div class="text-red-600" class="createTicketFormErrors" id="createTitleError"></div>

                <label for="createDescription" class="block mt-4 text-sm font-medium text-gray-700">Description:</label>
                <textarea id="createDescription" name="description" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500" rows="4"></textarea>
                <div class="text-red-600" class="createTicketFormErrors" id="createDescriptionError"></div>
                <button type="button"
                    class="flex justify-center mt-4 w-full px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-700"
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
                @csrf
                <input type="number" name="id" id="editId" hidden>
                <input type="hidden" name="_method" value="PUT"> <!-- Hidden input for method spoofing -->

                <label for="editTitle" class="block text-sm font-medium text-gray-700">Title:</label>
                <input type="text" id="editTitle" name="title"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500" />
                <div class="text-red-600" class="editTicketFormErrors" id="editTitleError"></div>

                <label for="editDescription" class="block mt-4 text-sm font-medium text-gray-700">Description:</label>
                <textarea id="editDescription" name="description"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500" rows="4"></textarea>
                <div class="text-red-600" class="editTicketFormErrors" id="editDescriptionError"></div>
                <button type="button"
                    class="flex justify-center mt-4 w-full px-4 py-2 text-white bg-green-500 rounded hover:bg-green-700"
                    onclick="updateTicket(editId.value)" id="updateTicketFormBtn">Update</button>
            </form>
        </div>
    </div>



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="flex justify-end mb-4">
                        <button
                            class="bg-slate-700 text-sm text-white font-semibold px-4 py-2 rounded hover:bg-slate-800"
                            id="createTicketBtn">
                            Create Ticket
                        </button>
                    </div>


                    <div class="overflow-x-auto">
                        <table id="ticketsDataTable"
                            class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                            <thead>
                                <tr class="bg-slate-700 text-white uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left" style="width: 100px;">#</th>

                                    <th class="py-3 px-6 text-left">Title</th>
                                    <th class="py-3 px-6 text-left">Description</th>
                                    <th class="py-3 px-6 text-center" style="width: 200px;">Status</th>

                                    <th class="py-3 px-6 text-center" style="width: 300px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm font-light">

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('custom-scripts')
        @include('userDashboard.tickets.includes.index_script')
    @endpush

</x-app-layout>
