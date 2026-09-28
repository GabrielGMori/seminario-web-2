<!DOCTYPE html>

<html lang="pt_BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.8/dist/litera/bootstrap.min.css">

  <title>LiveShop</title>
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg bg-light" data-bs-theme="light">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">LiveShop</a>
        <button type="button" class="btn btn-primary">Default button</button>
        <button type="button" class="btn btn-primary">Default button</button>
      </div>
      </div>
    </nav>
  </header>

  <main>
    @yield('content')
  </main>
</body>

</html>