<!doctype html>
<html lang="es" class="h-100">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Laravel - MuPDF</title>
</head>
<body class="d-flex flex-column h-100">
<!-- Begin page content -->
<main role="main" class="flex-shrink-0">
    <div class="container">
        <h1 class="mt-5">Laravel Mutool</h1>
        <p class="lead">MuPDF es un visor ligero de PDF, XPS y libros electrónicos.</p>

        @if(session()->has('info') && session('info')!='')
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <strong>Holy guacamole!</strong>
                <p class="m-0 p-0">{!! session('info') !!}</p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <form method="post" enctype="multipart/form-data" action="{{route('process')}}">
            @csrf
            <h4>Extracción de páginas</h4>
            <div class="row">
                <div class="form-group col-12">
                    <input type="file" name="archivo_extraccion" id="archivo_extraccion" accept="application/pdf">
                </div>
                <div class="form-group col-6">
                    <input type="text" class="form-control" placeholder="Num páginas" name="paginas_extraccion" id="paginas_extraccion">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-6">
                    <h4>Reparación</h4>
                    <input type="file" name="archivo_reparacion" id="archivo_reparacion" accept="application/pdf">
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <h4>Fusión</h4>
                </div>
                <div class="form-group col-4">
                    <label for="">Archivo 1</label>
                    <input type="file" name="archivo_fusion1" id="archivo_fusion1" accept="application/pdf">
                </div>
                <div class="form-group col-4">
                    <label for="">Archivo 2</label>
                    <input type="file" name="archivo_fusion2" id="archivo_fusion2" accept="application/pdf">
                </div>
            </div>
            <div class="row">
                <div class="col-2">
                    <button class="btn btn-primary">Enviar</button>
                </div>
            </div>
        </form>
    </div>
</main>

<footer class="footer mt-auto py-3 bg-secondary">
    <div class="container">
        <span class="text-white">Alonso Parra | AIR | Happy Valentine's Day ♥ </span>
    </div>
</footer>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
    $("#archivo_extraccion").on("change",function(){
        $("#paginas_extraccion").attr("required",$(this)[0].files.length>0);
        $("#archivo_fusion1").attr("required",false);
        $("#archivo_fusion2").attr("required",false);
    });
    $("#archivo_reparacion").on("change",function(){
        $("#paginas_extraccion").attr("required",false);
        $("#archivo_fusion1").attr("required",false);
        $("#archivo_fusion2").attr("required",false);
    });
    $("#archivo_fusion1,#archivo_fusion2").on("change",function(){
        $("#paginas_extraccion").attr("required",false);
        $("#archivo_fusion1").attr("required",$(this)[0].files.length>0);
        $("#archivo_fusion2").attr("required",$(this)[0].files.length>0);
    });
</script>
</body>
</html>
