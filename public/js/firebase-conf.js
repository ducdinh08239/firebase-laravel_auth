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
   var facebookProvider = new firebase.auth.FacebookAuthProvider();
   var googleProvider = new firebase.auth.GoogleAuthProvider();
   var facebookCallbackLink = '/login/facebook/callback';
   var googleCallbackLink = '/login/google/callback';
   async function socialSignin(provider) {
      var socialProvider = null;
      if (provider == "facebook") {
         socialProvider = facebookProvider;
         document.getElementById('social-login-form').action = facebookCallbackLink;
      } else if (provider == "google") {
         socialProvider = googleProvider;
         document.getElementById('social-login-form').action = googleCallbackLink;
      } else {
         return;
      }
   firebase.auth().signInWithPopup(socialProvider).then(function(result) {
         result.user.getIdToken().then(function(result) {
            document.getElementById('social-login-tokenId').value = result;
            document.getElementById('social-login-form').submit();
         });
      }).catch(function(error) {
         // do error handling
         console.log(error);
      });
   }