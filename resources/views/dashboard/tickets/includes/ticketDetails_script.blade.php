<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });

    function addMessage() {
        $("#submitMessageBtn").html(`Adding
                                        <div class="ml-3 border-8 border-t-8 border-gray-200 rounded-full animate-spin border-t-blue-500" style="width: 20px;height:20px;"></div>
                                    </div></button>`)
        let message = $('#message').val();
        let ticket_id = {{ $ticket->id }}
        $.ajax({
            url: "{{ route('ticket.message.store') }}",
            type: "POST",
            data: {
                message: message,
                ticket_id: ticket_id
            },
            success: function(response) {
                if (response.status == 1) {
                    Toastify({
                        text: "Your Query has been added",
                        duration: 2000
                    }).showToast();
                    $('#message').val('');
                    $("#submitMessageBtn").html(`Added`)
                    setTimeout(() => {
                        $("#submitMessageBtn").html(`Added Message`)
                    }, 1000);
                    @if (auth()->user()->hasRole('admin'))
                        $('#messagesBox').append(`<div class="mb-4 text-right">
                                            <div class="flex items-start justify-end">
                                                <div class="bg-green-100 text-green-800 p-2 rounded-lg">
                                                    <strong>
                                                        You: 
                                                    </strong> ${response.data.message}
                                                </div>
                                            </div>
                                        </div>`)
                    @else
                        $('#messagesBox').append(` <div class="mb-4">
                                            <div class="flex items-start">
                                                <div class="bg-blue-100 text-blue-800 p-2 rounded-lg">
                                                    <strong>
                                                        You:
                                                    </strong> ${response.data.message}
                                                </div>
                                            </div>
                                        </div>`)
                    @endif
                }
            },
            error: function(error) {

            }
        })
    }
</script>
<script>
    function closeTicket(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, Closed it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('ticket.close', '') }}/" + id,
                    type: "GET",
                    success: function(response) {
                        if (response.status == 1) {
                            Swal.fire({
                                title: "Closed Token!",
                                icon: "success"
                            });
                            setTimeout(() => {
                                window.location.href = response.url;
                            }, 1000);
                        }
                    }
                })
            }
        });
    }
</script>
