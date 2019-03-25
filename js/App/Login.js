
var Login = new Vue({
    el: '#login',
    data: {
    User:{}
    },
    mounted: function () {
      this.User = {
        UserId:null,
        UserName:null,
        Password:null,
      };
    },
    methods: {
      Login: function(){
        if(this.Validasi()){
        $('#load').show();
        axios.post('http://localhost/Evaluation/Api/LoginUserService.php',
         this.User,
        {
          headers:
          {
           'Content-type': 'application/x-www-form-urlencoded',
           }
        }).then(response => {
          var user = response.data;
          $('#load').hide();
          if(user.HasConfirmation == 0)
          {
            window.location.href = "http://localhost/Evaluation/pages/User/register.php";
          }
          else
          {
             window.location.href = "http://localhost/Evaluation/index.php";
          }
        })
        .catch((err) => {
           console.log(err.data)
           alert("Gagal Login");
        });
        }
        else{
          alert("UserName atau Password harus diisi");
        }
      },
     Validasi : function(){
        var valid = true;
        if((this.User.UserName == null || this.User.UserName == "" || this.User.UserName == undefined) || (this.User.Password == null || this.User.Password == "" || this.User.Password == undefined)){
          valid = false;
        }
        return valid;
     }
  }
});
