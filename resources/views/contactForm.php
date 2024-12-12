<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <h2>Contact Form</h2>

    <form id="contactForm" enctype="multipart/form-data">
        <input type="text" id="name" name="name" placeholder="Name" required><br><br>
        <input type="email" id="email" name="email" placeholder="Email" required><br><br>
        <input type="text" id="phone" name="phone" placeholder="Phone" required><br><br>
        <input type="file" id="pdf" name="pdf" accept="application/pdf"><br><br>
        <input type="file" id="image" name="image" accept="image/*"><br><br>
        <button type="submit">Submit</button>
    </form>

    <h3>Contact List</h3>
    <ul id="contactList"></ul>

    <script>
        $(document).ready(function() {
            function fetchContacts() {
                $.get('/api/contacts', function(data) {
                    $('#contactList').empty();
                    data.forEach(function(contact) {
                        $('#contactList').append(`
                            <li>
                                ${contact.name} - ${contact.email} - ${contact.phone}
                                <button onclick="deleteContact(${contact.id})">Delete</button>
                            </li>
                        `);
                    });
                });
            }

            fetchContacts();

            $('#contactForm').submit(function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                $.ajax({
                    url: '/api/contacts',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function() {
                        fetchContacts();
                        alert('Contact added successfully');
                    },
                    error: function(error) {
                        alert('Error: ' + JSON.stringify(error.responseJSON.error));
                    }
                });
            });

            window.deleteContact = function(id) {
                $.ajax({
                    url: '/api/contacts/' + id,
                    type: 'DELETE',
                    success: function() {
                        fetchContacts();
                        alert('Contact deleted successfully');
                    },
                    error: function() {
                        alert('Error deleting contact');
                    }
                });
            };
        });
    </script>

</body>
</html>
