<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta http-equiv="Content-Language" content="en">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>@yield("title")</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
  <meta name="description" content="super admin dash">
  <meta name="msapplication-tap-highlight" content="no">
  <!--
  =========================================================
  * ArchitectUI HTML Theme Dashboard - v1.0.0
  =========================================================
  * Product Page: https://dashboardpack.com
  * Copyright 2019 DashboardPack (https://dashboardpack.com)
  * Licensed under MIT (https://github.com/DashboardPack/architectui-html-theme-free/blob/master/LICENSE)
  =========================================================
  * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
  -->
  <link rel="icon" href="{{ asset('favicon.png') }}">
  <link href="{{ asset('css/main.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/super/bootstrap4.0.css') }}" rel="stylesheet">
  @yield("styles")
  <style>
    .btn-group-xs > .btn, .btn-xs {
      padding: .25rem .4rem;
      font-size: .875rem;
      line-height: .5;
      border-radius: .2rem;
    }
  </style>
</head>
<body>
  {{ session()->put('prevUrl', url()->current())}}
  <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
    @yield("header")      
    {{-- @include("includes.layout-options") --}}

    <div class="app-main">
      @yield('sidebar')
      <div class="app-main__outer">
          <div class="app-main__inner">
            @include("includes.page-heading")           
            @yield("page-content")
          </div>
          {{-- @include("includes.footer")  --}}
      </div>
    </div>
  </div>
  <script type="text/javascript" src="{{ asset('css/assets/scripts/main.js') }}"></script>
  <script src="{{ asset('js/jquery.min.js') }}"></script>
  @yield("scripts")

  <script type="text/javascript">
    var idleTime = 0;
    $(document).ready(function () {
        // Increment the idle time counter every minute.
        var idleInterval = setInterval(timerIncrement, 60000); // 1 minute

        // Zero the idle timer on mouse movement.
        $(this).mousemove(function (e) {
            idleTime = 0;
        });
        $(this).keypress(function (e) {
            idleTime = 0;
        });
    });

    function timerIncrement() {
        idleTime = idleTime + 1;
        if (idleTime > 30) { // 20 minutes
            window.location.replace("/logout/autologout")
        }
    }

  </script>
</body>
</html>
