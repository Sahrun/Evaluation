
var LogOut = new Vue({
    el: '#logout',
    data: {
    User:{}
    },
    mounted: function () {
      this.User = {
        UserId:null,
        UserName:null,
        Password:null,
      };
      this.GetUserLogin();
    },
    methods: {
     GetUserLogin : function() {
       $('#load').show();
         axios
       .get('http://localhost/Evaluation/Api/LoginUserService.php?route=getuserlogin')
       .then(response => {
         this.User = response.data;
          $('#load').hide();
          if(this.User !== null)
          {
            if(this.User.HasConfirmation == 0)
            {
              var currentPage = location.pathname.split("/").slice(-1)[0].replace(/^\/|\/$/g, '');
              if (currentPage.toLowerCase() !== ('register.php').toLowerCase()) {
               window.location.href = "http://localhost/Evaluation/pages/User/register.php";
              }
            }
          }else
          {
             window.location.href = "http://localhost/Evaluation/pages/User/login.php";
          }
       })
       .catch((err) => {
          console.log(err);
          window.location.href = "http://localhost/Evaluation/pages/User/login.php";
       });
     },
     LogOut: function(){
         $('#load').show();
          axios
          .get('http://localhost/Evaluation/Api/LoginUserService.php?route=logout')
          .then(response =>
            {
              $('#load').hide();
              window.location.href = "http://localhost/Evaluation/pages/User/login.php";
            })
          .catch((err) => {
           console.log(err.data)
          });
     }
  }
});
