<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Blog</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/bootstrap.min.css')}}" rel="stylesheet" />
    <script src="{{ asset('js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('js/jquery.min.js')}}"></script>
    <style>
        /* body{
            background-color: #000;
            } */
            .card{
            width: 400px;
            border:none;
            }
            .btr{
            border-top-right-radius: 5px !important;
            }
            .btl{
            border-top-left-radius: 5px !important;
            }
            .btn-dark {
            color: #fff;
            background-color: #0d6efd;
            border-color: #0d6efd;
            }
            .btn-dark:hover {
            color: #fff;
            background-color: #0d6efd;
            border-color: #0d6efd;
            }
            .nav-pills{
            display:table !important;
            width:100%;
            }
            .nav-pills .nav-link {
            border-radius: 0px;
                border-bottom: 1px solid #0d6efd40;
            }
            .nav-item{
                display: table-cell;
                background: #0d6efd2e;
            }
            .form{
            padding: 10px;
                height: 300px;
            }
            .form input{
            margin-bottom: 12px;
            border-radius: 3px;
            }
            .form input:focus{
            box-shadow: none;
            }
            .form button{
            margin-top: 20px;
            }
    </style>
  </head>
  <body>
    <div class="row" id="loginrow">
        <div class="col-xl-10"><br><br><br>
            <button id="mymodal" class="btn btn-info float-right">Login</button>
        </div>
    </div>
    @if(Session::has('msg'))
    <div class="alert alert-success">
      <strong>Success!</strong> {{Session('msg')}}
    </div>
    @endif
<div id="signup" style="display: none;">
    <div class="d-flex justify-content-center align-items-center mt-5" >
      <div class="card">
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
          <li class="nav-item text-center">
            <a class="nav-link active btl" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Login</a>
          </li>
          <li class="nav-item text-center">
            <a class="nav-link btr" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Signup</a>
          </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
          <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            <div class="form px-4 pt-5">
              <form method="post" action="/login">
                @csrf
                  <input type="text" name="email" class="form-control" placeholder="Email">
                  <input type="password" name="password" class="form-control" placeholder="Password">
                  <input type="submit" class="btn btn-dark btn-block" value="Login">
              </form>
            </div>
          </div>

          <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            <div class="form px-4">

              <form method="post" action="/signup">
                @csrf
                  <input type="text" name="name" class="form-control" placeholder="Name">
                  <input type="text" name="email" class="form-control" placeholder="Email">
                  <input type="password" name="password" class="form-control" placeholder="Password">
                  <input type="submit" class="btn btn-dark btn-block" value="Signup">
              </form>
            </div>
          </div>
        </div>
        <button class="btn btn-info btn-block mt-4" id="close">close</button>
      </div>
    </div>
</div>

<div class="container" id="mycontainer">
    <table class="table">
        <thead>
            <tr>
                <th>Sr.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Mobile</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $list)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$list->name}}</td>
                <td>{{$list->email}}</td>
                <td>{{$list->mobile}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div>
        {!! $data->withQueryString()->links() !!}
    </div>
</div>

<script src="{{ asset('js/jquery.min.js')}}"></script>
<script>
    $(document).ready(function(){
        $("#mymodal").click(function(){
            document.body.style.backgroundColor='black';
            $("#signup").show();
            $("#loginrow").hide();
            $("#mycontainer").hide();
        });

        $("#close").click(function(){
            document.body.style.backgroundColor='';
            $("#signup").hide();
            $("#loginrow").show();
        });
    });
</script>
  </body>
</html>