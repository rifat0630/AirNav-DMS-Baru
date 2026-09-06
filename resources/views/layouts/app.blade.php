<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>
        @yield('title', 'AirNav DMS')
    </title>



    <!-- Bootstrap -->

    <link 
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet">



    <!-- Font Awesome -->

    <link 
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    rel="stylesheet">


</head>



<body>



<nav class="navbar navbar-expand-lg navbar-dark bg-dark">


    <div class="container">



        <a class="navbar-brand"
           href="/dashboard">

            <i class="fas fa-plane"></i>
            AirNav DMS

        </a>





        <div>


            @auth


            <span class="text-white me-3">

                <i class="fas fa-user"></i>

                {{ auth()->user()->name }}

            </span>





            <form action="/logout"
                  method="POST"
                  class="d-inline">


                @csrf


                <button class="btn btn-danger btn-sm">

                    <i class="fas fa-sign-out-alt"></i>

                    Logout

                </button>


            </form>



            @endauth



        </div>


    </div>


</nav>







<div class="container mt-4">





    @if(session('success'))


        <div class="alert alert-success">

            {{ session('success') }}

        </div>


    @endif






    @if(session('error'))


        <div class="alert alert-danger">

            {{ session('error') }}

        </div>


    @endif






    @yield('content')





</div>









<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>






{{-- ================================================= --}}
{{-- SWEET ALERT NOTIFICATION SYSTEM --}}
{{-- ================================================= --}}



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>





@auth


@php


$unreadCount = \App\Models\Notification::where(
    'is_read',
    0
)->count();


@endphp







@if($unreadCount > 0)





<audio id="notificationSound">


    <source src="/sounds/notification.mp3"
            type="audio/mpeg">


</audio>







<script>


document.addEventListener(
"DOMContentLoaded",
function(){



    let popupShown =
    sessionStorage.getItem(
        'airnav_notification_popup'
    );





    if(!popupShown){





        Swal.fire({


            title:
            '🔔 Notifikasi Baru',



            html:

            `
            <b>
            {{ $unreadCount }}
            </b>
            notifikasi membutuhkan perhatian
            `,



            icon:
            'warning',



            confirmButtonText:
            'Lihat Notifikasi',



            confirmButtonColor:
            '#0d6efd'


        })
        .then((result)=>{


            if(result.isConfirmed){


                window.location.href =
                "/notifications";


            }


        });







        let sound =
        document.getElementById(
            'notificationSound'
        );





        if(sound){


            sound.volume = 0.5;



            sound.play()
            .catch(
            function(error){


                console.log(
                "Audio autoplay diblokir browser"
                );


            });



        }







        sessionStorage.setItem(

            'airnav_notification_popup',

            'true'

        );




    }





});



</script>





@endif


@endauth







</body>


</html>