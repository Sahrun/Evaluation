
var Register = new Vue({
    el: '#register',
    data: {
    User:{}
    },
    mounted: function () {
      this.User = {
        UserId:null,
        UserName:null,
        Password:null,
        ConfirmPassword: null
      };
    },
    methods: {
      Register: function(){
        if(!this.Validasi()){
          alert("UserName atau Password harus diisi");
        }else if(this.User.Password == this.User.ConfirmPassword)
        {
          alert("Password harus beda dengan password sebelumnya");
        }
        else{
          $('#load').show();
          axios.post('http://localhost/Evaluation/Api/UserService.php?route=register',
           this.User,
          {
            headers:
            {
             'Content-type': 'application/x-www-form-urlencoded',
             }
          }).then(response => {
             var user = response.data;
             $('#load').hide();
             window.location.href = "http://localhost/Evaluation/index.php";
          })
          .catch((err) => {
             console.log(err.data);
             alert("gagal register..!");
          });
        }
      },
     Validasi : function(){
        var valid = true;
        if((this.User.ConfirmPassword == null || this.User.ConfirmPassword == "" || this.User.UserName == undefined) || (this.User.UserName == null || this.User.UserName == "" || this.User.UserName == undefined) || (this.User.Password == null || this.User.Password == "" || this.User.Password == undefined)){
          valid = false;
        }
        return valid;
     }
  }
});
