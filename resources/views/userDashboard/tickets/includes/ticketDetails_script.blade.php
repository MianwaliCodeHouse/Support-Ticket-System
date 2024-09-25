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
            url: "{{ route('ticket-details.store') }}",
            type: "POST",
            data: {
                message: message,
                ticket_id: ticket_id
            },
            success: function(response) {
                if (response.status == 200) {
                    Toastify({
                        text: "Your Query has been added",
                        duration: 2000
                    }).showToast();
                    $('#message').val('');
                    $("#submitMessageBtn").html(`Added`)
                    setTimeout(() => {
                        $("#submitMessageBtn").html(`Add Message`)
                    }, 1000);
                   
                        $('#messagesBox').append(` <div class="mb-4">
                                            <div class="flex items-start">
                                                <div class="bg-blue-100 text-blue-800 p-2 rounded-lg">
                                                    <strong>
                                                        You:
                                                    </strong> ${response.data.message}
                                                </div>
                                            </div>
                                        </div>`)
                   
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
                        if (response.status == 200) {
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
