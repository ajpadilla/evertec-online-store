<!DOCTYPE html>
<html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="description" content="Simple CMS"/>
    <meta name="author" content="Sheikh Heera"/>
    <link rel="shortcut icon" href={{ asset("favicon.png") }} />

    <title>Register</title>

    <!-- Bootstrap core CSS -->
    <link href={{ asset("assets/bootstrap/css/bootstrap.css") }} rel="stylesheet"/>

</head>
<body>

<div class="container">
    <div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">Register</div>
            </div>

            <div style="padding-top:30px" class="panel-body">

                <div class="row">
                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                            <div id="login-alert" class="alert alert-danger col-sm-12">{{ $error }}</div>
                        @endforeach
                    @endif
                </div>

                <div class="row">
                    @if (session('alert_success'))
                        <div class="alert alert-success">
                            {{ session('alert_success') }}
                        </div>
                    @endif

                <form id="loginform" class="form-horizontal" action="{{ route('create_user') }}" method="post" role="form">

                    @csrf
                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="login-username" type="text" class="form-control" name="first_name" value=""
                               placeholder="first name">
                    </div>

                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="login-username" type="text" class="form-control" name="last_name" value=""
                               placeholder="last name">
                    </div>

                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input id="login-username" type="text" class="form-control" name="email" value=""
                               placeholder="email">
                    </div>

                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                        <input id="login-password" type="text" class="form-control" name="phone"
                               placeholder="phone">
                    </div>

                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-credit-card"></i></span>
                        <select class="form-control" id="sel1" name="document_type">
                            <option value="" selected disabled>document type</option>
                            <option value="CC">CC</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                        <input id="login-password" type="text" class="form-control" name="document_number"
                               placeholder="document number">
                    </div>

                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input id="login-password" type="password" class="form-control" name="password"
                               placeholder="password">
                    </div>

                    <div style="margin-bottom: 25px" class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input id="login-password" type="password" class="form-control" name="password_confirmation"
                               placeholder="confirm password">
                    </div>

                    <div style="margin-top:10px" class="form-group">
                        <!-- Button -->
                        <div class="col-sm-12 controls">
                            <button id="btn-fbsignup" type="submit" class="btn btn-success"><i
                                    class="icon-facebook"></i>  Register
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12 control">
                            <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%">
                                You already have an account!
                                <a href="{{ route('login')  }}">
                                    Login Here
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
