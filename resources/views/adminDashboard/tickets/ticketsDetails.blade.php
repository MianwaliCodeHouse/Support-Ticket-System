<x-app-layout>
    @push('custom-stylesheets')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    @endpush
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $ticket->title }}
        </h2>

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="container mx-auto p-4">


                        <!-- Message Input Area -->
                        <div class="mb-4 p-4 bg-white rounded shadow">
                            @if ($ticket->status != 'closed')
                                <textarea placeholder="Type your message here..." class="w-full p-2 border border-gray-300 rounded-md" rows="3"
                                    id="message"></textarea>
                                <div class="flex justify-end mt-2">
                                    <button class="px-4 py-2 mb-3 text-white bg-blue-500 rounded hover:bg-blue-700"
                                        onclick="addMessage()">
                                        <div class="flex items-center justify-center" id="submitMessageBtn">
                                            @if (auth()->user()->hasRole('admin'))
                                                Add
                                                Response
                                            @else
                                                Add
                                                Message
                                            @endif
                                        </div>
                                </div>
                            @endif
                            <!-- Messages Display Area -->
                            <div class="bg-white rounded shadow p-4 mb-4 max-h-96 overflow-y-auto" id="messagesBox">
                                <!-- Student Message -->
                                <div class="mb-4">
                                    <div class="flex items-start">
                                        <div class="bg-blue-100 text-blue-800 p-2 rounded-lg">
                                            <strong>
                                                @if (auth()->user()->hasRole('student'))
                                                    You:
                                                @else
                                                    {{ $ticket->user->name }}:
                                                @endif
                                            </strong> {{ $ticket->description }}.
                                        </div>
                                    </div>
                                </div>

                                @foreach ($ticket_details as $message)
                                    @if ($message->user->getRoleNames()[0] == 'student')
                                        <!-- Student Message -->
                                        <div class="mb-4">
                                            <div class="flex items-start">
                                                <div class="bg-blue-100 text-blue-800 p-2 rounded-lg">
                                                    <strong>
                                                        @if (auth()->user()->hasRole('student'))
                                                            You:
                                                        @else
                                                            {{ $message->user->name }}:
                                                        @endif
                                                    </strong> {{ $message->message }}
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($message->user->getRoleNames()[0] == 'admin')
                                        <!-- Admin Message -->
                                        <div class="mb-4 text-right">
                                            <div class="flex items-start justify-end">
                                                <div class="bg-green-100 text-green-800 p-2 rounded-lg">
                                                    <strong>
                                                        @if (auth()->user()->hasRole('admin'))
                                                            You:
                                                        @else
                                                            Admin:
                                                        @endif
                                                    </strong> {{ $message->message }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                            </div>

                            @if (auth()->user()->hasRole('student'))
                                @if ($ticket->status != 'closed')
                                    <!-- Close Ticket Button -->
                                    <div class="flex justify-end">
                                        <button class="px-4 py-2 text-white bg-red-500 rounded hover:bg-red-700"
                                            onclick="closeTicket({{ $ticket->id }})">Close
                                            Ticket</button>
                                    </div>
                                @endif
                            @endif
                        </div>


                    </div>
                </div>
            </div>
        </div>
        @push('custom-scripts')
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
        @include('adminDashboard.tickets.includes.ticketDetails_script')
        @endpush
        
</x-app-layout>
