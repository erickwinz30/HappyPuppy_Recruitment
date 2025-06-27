<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>

    <body>
        <form action="/input" method="post">
            @csrf
            <div>
                <div>
                    <label for="email">Email: </label>
                    <input type="email" name="email" id="email" required>
                </div>
                {{-- <div>
                    <label for="email">Phone Number: </label>
                    <input type="text" inputmode="numeric" name="phone_number" id="phone_number" required>
                </div> --}}
                <div>
                    <label for="text">Text to send: </label>
                    <input type="text" name="text" id="text" required>
                </div>
            </div>

            <button type="submit">Sumbit</button>
        </form>
    </body>

</html>
