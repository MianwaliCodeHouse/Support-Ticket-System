<x-app-layout>
    <style>
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 10px;
        }

        .dataTables_wrapper .dataTables_length select {
            min-width: 80px;
        }
    </style>

    
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tickets') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                   
                        <div class="text-center">
                            <select name="student_filter" id="studentFilter" class="mb-4 w-full md:w-1/2">
                                <option value="">All Students</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    <div class="overflow-x-auto">
                        <table id="ticketsDataTable"
                            class="min-w-full bg-white border border-gray-300 rounded-lg shadow-lg">
                            <thead>
                                <tr class="bg-slate-700 text-white uppercase text-sm leading-normal">
                                    <th class="py-3 px-6 text-left" style="width: 100px;">#</th>
                                 
                                        <th class="py-3 px-6 text-left">Student Name</th>
                                 
                                    <th class="py-3 px-6 text-left">Title</th>
                                    <th class="py-3 px-6 text-left">Description</th>
                                    <th class="py-3 px-6 text-center" style="width: 200px;">Status</th>
                                  
                                        <th class="py-3 px-6 text-left">Created At</th>
                                  
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
    @include('adminDashboard.tickets.includes.index_script')
@endpush
</x-app-layout>
