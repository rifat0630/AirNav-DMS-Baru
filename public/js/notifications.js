document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | VARIABLE
    |--------------------------------------------------------------------------
    */

    let previousCount = null;

    let audioUnlocked = false;

    const notificationSound =
        document.getElementById('notificationSound');


    /*
    |--------------------------------------------------------------------------
    | UNLOCK AUDIO
    |--------------------------------------------------------------------------
    |
    | Browser biasanya memblokir audio otomatis.
    | Kita unlock audio setelah user melakukan klik pertama.
    |
    */

    function unlockAudio() {

        if (
            audioUnlocked ||
            !notificationSound
        ) {
            return;
        }


        notificationSound.volume = 0;


        const playPromise =
            notificationSound.play();


        if (playPromise !== undefined) {

            playPromise
                .then(function () {

                    notificationSound.pause();

                    notificationSound.currentTime = 0;

                    notificationSound.volume = 1;

                    audioUnlocked = true;

                    console.log(
                        'Notification audio unlocked.'
                    );

                })
                .catch(function () {

                    notificationSound.volume = 1;

                });

        }

    }


    /*
    |--------------------------------------------------------------------------
    | UNLOCK SAAT USER BERINTERAKSI
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'click',
        unlockAudio,
        {
            once: true
        }
    );


    document.addEventListener(
        'keydown',
        unlockAudio,
        {
            once: true
        }
    );


    /*
    |--------------------------------------------------------------------------
    | PLAY NOTIFICATION SOUND
    |--------------------------------------------------------------------------
    */

    function playNotificationSound() {

        if (!notificationSound) {

            console.log(
                'notificationSound tidak ditemukan.'
            );

            return;

        }


        notificationSound.volume = 1;

        notificationSound.currentTime = 0;


        const playPromise =
            notificationSound.play();


        if (playPromise !== undefined) {

            playPromise
                .then(function () {

                    console.log(
                        'Notification sound played.'
                    );

                })
                .catch(function (error) {

                    console.log(
                        'Audio gagal diputar:',
                        error
                    );

                });

        }

    }


    /*
    |--------------------------------------------------------------------------
    | CHECK NOTIFICATIONS
    |--------------------------------------------------------------------------
    */

    function checkNotifications() {

        fetch(
            '/notifications/unread-count',
            {
                method: 'GET',

                headers: {
                    'X-Requested-With':
                        'XMLHttpRequest',

                    'Accept':
                        'application/json'
                }
            }
        )

        .then(function (response) {

            if (!response.ok) {

                throw new Error(
                    'Gagal mengambil notifikasi.'
                );

            }

            return response.json();

        })

        .then(function (data) {

            const currentCount =
                parseInt(
                    data.count || 0
                );


            /*
            |--------------------------------------------------------------------------
            | PENGECEKAN PERTAMA
            |--------------------------------------------------------------------------
            */

            if (
                previousCount === null
            ) {

                previousCount =
                    currentCount;

                return;

            }


            /*
            |--------------------------------------------------------------------------
            | ADA NOTIFIKASI BARU
            |--------------------------------------------------------------------------
            */

            if (
                currentCount >
                previousCount
            ) {

                const newCount =
                    currentCount -
                    previousCount;


                /*
                |--------------------------------------------------------------
                | PLAY SOUND
                |--------------------------------------------------------------
                */

                playNotificationSound();


                /*
                |--------------------------------------------------------------
                | SHOW POPUP
                |--------------------------------------------------------------
                */

                showNotificationPopup(
                    newCount
                );


                /*
                |--------------------------------------------------------------
                | UPDATE BADGE
                |--------------------------------------------------------------
                */

                updateBadge(
                    currentCount
                );

            }


            previousCount =
                currentCount;

        })

        .catch(function (error) {

            console.log(
                'Notification error:',
                error.message
            );

        });

    }


    /*
    |--------------------------------------------------------------------------
    | POPUP
    |--------------------------------------------------------------------------
    */

    function showNotificationPopup(
        newCount
    ) {

        if (
            typeof Swal === 'undefined'
        ) {

            return;

        }


        Swal.fire({

            title:
                '🔔 Notifikasi Baru',

            text:
                'Ada ' +
                newCount +
                ' notifikasi baru yang perlu diperiksa.',

            icon:
                'warning',

            showCancelButton:
                true,

            confirmButtonText:
                'Lihat Notifikasi',

            cancelButtonText:
                'Tutup',

            confirmButtonColor:
                '#007bff',

            cancelButtonColor:
                '#6c757d',

            reverseButtons:
                true,

            allowOutsideClick:
                true

        })

        .then(function (result) {

            if (
                result.isConfirmed
            ) {

                window.location.href =
                    '/notifications';

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE BADGE
    |--------------------------------------------------------------------------
    */

    function updateBadge(
        count
    ) {

        let badge =
            document.querySelector(
                '.navbar-badge'
            );


        /*
        | Badge sudah ada
        */

        if (badge) {

            badge.textContent =
                count;

            badge.style.display =
                count > 0
                    ? 'inline-block'
                    : 'none';

            return;

        }


        /*
        | Buat badge jika belum ada
        */

        if (
            count > 0
        ) {

            const notificationLink =
                document.querySelector(
                    '.nav-item.dropdown .nav-link'
                );


            if (
                !notificationLink
            ) {

                return;

            }


            badge =
                document.createElement(
                    'span'
                );


            badge.className =
                'badge badge-danger navbar-badge';


            badge.textContent =
                count;


            notificationLink.appendChild(
                badge
            );

        }

    }


    /*
    |--------------------------------------------------------------------------
    | INITIAL CHECK
    |--------------------------------------------------------------------------
    */

    checkNotifications();


    /*
    |--------------------------------------------------------------------------
    | CHECK EVERY 10 SECONDS
    |--------------------------------------------------------------------------
    */

    setInterval(
        checkNotifications,
        10000
    );

});