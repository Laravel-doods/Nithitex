<html>
    <head>
        <title>Laravel 8 Mobile Number OTP Authentication using Firebase - Techsolutionstuff</title>    
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    </head>
<body>  
<div class="container">
    <h3 style="margin-top: 30px;">Laravel 8 Mobile Number OTP Authentication using Firebase - Techsolutionstuff</h3><br>  
    <div class="alert alert-light" id="error"></div>  
    <div class="card">
      <div class="card-header">
        Enter Phone Number
      </div> 
      <div class="card-body">  
        <div class="alert alert-success" id="sentSuccess" style="display: none;"></div>
        <form>
            <label>Phone Number:</label>
            <input type="text" id="number" class="form-control" placeholder="+91 9876543210"><br>
            <div id="recaptcha-container"></div><br>
            <button type="button" class="btn btn-success" onclick="SendCode();">Send Code</button>
        </form>
      </div>
    </div>
      
    <div class="card" style="margin-top: 10px">
      <div class="card-header">Enter Verification code</div>
      <div class="card-body">
        <div class="alert alert-success" id="successRegsiter" style="display: none;"></div>
        <form>
            <input type="text" id="verificationCode" class="form-control" placeholder="Enter Verification Code"><br>
            <button type="button" class="btn btn-success" onclick="VerifyCode();">Verify Code</button>
        </form>
      </div>
    </div>
  
</div>
  
<script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>
  
<script>
      
// For Firebase JS SDK v7.20.0 and later, measurementId is optional
const firebaseConfig = {
  apiKey: "AIzaSyDR7tANxvh0yD4UKY4yjDM4rX8ARQABOTY",
  authDomain: "phoneauth-cc4b3.firebaseapp.com",
  projectId: "phoneauth-cc4b3",
  storageBucket: "phoneauth-cc4b3.appspot.com",
  messagingSenderId: "645077910902",
  appId: "1:645077910902:web:fcd6c9d74e38759da12657",
  measurementId: "G-CPZZJNBX1V"
};
    
  firebase.initializeApp(firebaseConfig);
</script>
  
<script type="text/javascript">
  
    window.onload=function () {
      render();
    };
  
    function render() {
        window.recaptchaVerifier=new firebase.auth.RecaptchaVerifier('recaptcha-container');
        recaptchaVerifier.render();
    }
  
    function SendCode() {
           
        var number = $("#number").val();
          
        firebase.auth().signInWithPhoneNumber(number,window.recaptchaVerifier).then(function (confirmationResult) {
              
            window.confirmationResult=confirmationResult;
            coderesult=confirmationResult;            
  
            $("#sentSuccess").text("Message Sent Successfully.");
            $("#sentSuccess").show();
              
        }).catch(function (error) {
            $("#error").text(error.message);
            $("#error").delay(1000).slideUp(300);
        });
  
    }
  
    function VerifyCode() {
  
        var code = $("#verificationCode").val();
  
        coderesult.confirm(code).then(function (result) {
            var user=result.user;            
  
            $("#successRegsiter").text("You Are Register Successfully.");
            $("#successRegsiter").show();
  
        }).catch(function (error) {
            $("#error").text(error.message);
            $("#error").delay(1000).slideUp(300);
        });
    }
  
</script>
  
</body>
</html>