const { required, minLength } = window.validators
var User = new Vue({
    el: '#User',
    data: {
      User:{

      },
      Users:[],
      Groups:[],
      Submitted:false
    },
    validations:{
        User:{
            UserName : {
               required
            },
            Department  : {
                required
            },
            GroupId :{
              required
            },
            FullName:{
              required
            }
        }
    },
    mounted: function () {
      this.GetUsers();
    },
    methods: {
     NewUser:function()
     {
        this.Submitted = false;
        $('#load').show();
        axios.get('http://localhost/Evaluation/Api/GroupService.php?route=getgroup')
        .then(response => {
            this.Groups = response.data;
           	this.User = {
                  UserName  : null,
                  Department: null,
                  GroupId   : null,
                  FullName  : null,
                  Status    : 'Active',
           	}
            $('#load').hide();
           	$('#UserModal').modal('show');
        })
        .catch((err) => {
           console.log(err.data)
        });
     },
     Submit:function()
     {
        this.$v.$touch();
        this.Submitted = true;
        if(!this.$v.$invalid){
         	if(this.User.UserId != null && this.User.Status == 'Non-Active')
         	{
    	     	var r = confirm("Apakah anda akan meng non-aktivkan");
    			if (r == true)
    			{
    			   this.SaveUser();
    			}
         	}
         	else
         	{
         		this.SaveUser();
         	}
        }
     },
     SaveUser:function()
     {
        $('#load').show();
     	axios.post('http://localhost/Evaluation/Api/UserService.php',
         this.User,
        {
          headers:
          {
           'Content-type': 'application/x-www-form-urlencoded',
           }
        }).then(response => {
           $('#load').hide();
           $('#UserModal').modal('hide');
           this.GetUsers();
        })
        .catch((err) => {
           console.log(err.data)
        });
     },
     GetUsers:function()
     {
        $('#load').show();
     	axios.get('http://localhost/Evaluation/Api/UserService.php?route=getuser')
        .then(response => {
            this.Users = response.data;
            $('#load').hide();
        })
        .catch((err) => {
           console.log(err.data)
        });
     },
     Edit:function(IDRId)
     {
        $('#load').show();
     	axios.get('http://localhost/Evaluation/Api/IDRService.php?route=edit&id='+IDRId+'')
        .then(response => {
            this.IDR = response.data;
            this.Submitted = false;
            $('#load').hide();
            $('#IDRModal').modal('show');
        })
        .catch((err) => {
           console.log(err.data)
        });
     }
    }
});
