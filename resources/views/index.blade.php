<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        a {
            text-decoration: none;
            color: black;
        }
    </style>
</head>

<body>
    <form action="javascript:;">
        @csrf
        <button>
            <a href="javascript:;" onClick="socialSignin('facebook');">Sign with Facebook</a>
        </button>

        <button>
            <a href="javascript:;" onClick="socialSignin('google');">Sign with Google</a>
        </button>
        <br>
        <br>
        <div id="recaptcha-container"></div>
        <input type="text" name="phone_number" id="phone_number" placeholder="Enter your phone number">
        <button>
            <a href="#" id="getcode">Get OTP</a>
        </button>
        <input type="text" name="opt" id="opt" placeholder="Enter your OPT">
        <button>
            <a href="#" id="verifyWithOtp">Verify Phone Number</a>
        </button>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- Insert these scripts at the bottom of the HTML, but before you use any Firebase services -->

    <!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
    <script src="https://www.gstatic.com/firebasejs/8.6.1/firebase-app.js"></script>

    <!-- If you enabled Analytics in your project, add the Firebase SDK for Analytics -->
    <script src="https://www.gstatic.com/firebasejs/8.6.1/firebase-analytics.js"></script>

    <!-- Add Firebase products that you want to use -->
    <script src="https://www.gstatic.com/firebasejs/8.6.1/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.6.1/firebase-firestore.js"></script>
    <script>
        var config = {
            apiKey: "AIzaSyAhQc1buJOw6tZlKsTfMabVuVAH078fiHw",
            authDomain: "authdemo-d6230.firebaseapp.com",
            projectId: "authdemo-d6230",
            storageBucket: "authdemo-d6230.appspot.com",
            messagingSenderId: "222772332670",
            appId: "1:222772332670:web:69fbf6c97086a247a2702f",
            measurementId: "G-NH4QS0C5Z1"

        };
        firebase.initializeApp(config);

        // Google && Facebook Auth
        var facebookProvider = new firebase.auth.FacebookAuthProvider();
        var googleProvider = new firebase.auth.GoogleAuthProvider();
        var facebookCallbackLink = '/login/facebook';
        var googleCallbackLink = '/login/google';
        async function socialSignin(provider) {
            var socialProvider = null;
            if (provider == "facebook") {
                socialProvider = facebookProvider;
            } else if (provider == "google") {
                socialProvider = googleProvider;
            }
            firebase
                .auth()
                .signInWithPopup(socialProvider)
                .then(function(result) {
                    var credential = result.credential;
                    var user = result.user;
                    var accessToken = credential.accessToken;

                    var userInfo = {
                        remember_token: user.uid,
                        name: user.displayName,
                        email: user.email,
                        image: user.photoURL
                    }

                    console.log(user.phoneNumber);

                    $.ajax({
                        url: "/login/redirect",
                        type: "post",
                        dataType: 'json',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "type": "facebook",
                            accessToken,
                            userInfo,
                        },
                        complete: function(data) {
                            window.location.replace(data.responseText);
                        }
                    })

                }).catch(function(error) {
                    console.log(error);
                });
        }

        // Phone Number Auth
        window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
            'size': 'invisible',
            'callback': (response) => {
                // reCAPTCHA solved, allow signInWithPhoneNumber.
            }
        });

        $('#getcode').click(function() {
            const phoneNumber = $('#phone_number').val();
            console.log('sendding');
            const appVerifier = window.recaptchaVerifier;
            firebase.auth().signInWithPhoneNumber(phoneNumber, appVerifier)
                .then((confirmationResult) => {
                    window.confirmationResult = confirmationResult;
                    // ...
                }).catch((error) => {
                    console.log(error);
                });
        })

        $('#verifyWithOtp').click(function() {
            const code = $('#opt').val();
            confirmationResult.confirm(code).then((result) => {
                const user = result.user;
                console.log(user);
                $.ajax({
                        url: "/login/redirect",
                        type: "post",
                        dataType: 'json',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "type": "phone",
                            "phone_number": user.phoneNumber,
                            "uid": user.uid
                        },
                        complete: function(data) {
                            window.location.replace(data.responseText);
                        }
                    })
            }).catch((error) => {
                console.log(error.message);
            });
        })
    </script>
</body>

</html>