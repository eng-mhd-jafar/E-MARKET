<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Form-Date</title>
</head>


<body>
    <h1>Laravel Firebase CRUD</h1>

    <form action="/StoreDate" method="POST">
        @csrf
        <div class="row">
            <label for="exampleInputEmail1" class="form-label">Enter your name</label>
            <div class="col">
                <input type="text" class="form-control" placeholder="Name" aria-label="First name">
        </div>
        <label for="exampleInputEmail1" class="form-label">Enter your email</label>
        <div class="col">
            <input type="email" class="form-control" placeholder="Email" aria-label="Email">
        </div>
        <label for="exampleInputEmail1" class="form-label">Enter your age</label>
        <div class="col">
            <input type="text" class="form-control" placeholder="Age" aria-label="Last name">
        </div><br>
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</body>

</html>
