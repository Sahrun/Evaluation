const { required, minLength } = window.validators
var navigationSetting = new Vue({
    el: '#navigationSetting',
    data: {
    Navigations:[],
    Group:[],
    Navigation:{},
    Submitted:false,
    },
    mounted: function () {
      this.GetNavigationSetting();
    },
    validations:{
        Navigation:{
            NavigationName:
            {
                required
            },
            Url:
            {
               required
            },
            GroupId:
            {
               required
            },
            Icon:
            {
               required
            },
            Order:
            {
               required
            }
        }
    },
    methods: {
        NewNavigation:function()
        {
            this.Submitted  = false;
            this.Navigation =
            {
                NavigationId:null,
                NavigationName:null,
                Url:null,
                GroupId:null,
                Icon:null,
                Order:null
            };
            this.GetGroup();
            $('#NavigationModal').modal('show');
        },
        Submit:function()
        {
            this.$v.$touch();
            this.Submitted = true;
            if(!this.$v.$invalid){
                this.SaveNavigation();
            }
        },
        SaveNavigation:function()
        {
            $('#load').show();
            axios.post('http://localhost/Evaluation/Api/SettingService.php?route=navigationsave', 
            this.Navigation,
            { 
            headers: 
            {
            'Content-type': 'application/x-www-form-urlencoded',
            }
            }).then(response => {
                $('#load').hide();
                $('#NavigationModal').modal('hide');
                this.GetNavigationSetting();
            })
            .catch((err) => {
            console.log(err.data)
            });
        },
        GetNavigationSetting: function () 
        {
          $('#load').show();
          axios.get('http://localhost/Evaluation/Api/SettingService.php?route=getnavigation')
          .then(response => {
              this.Navigations = response.data;
              $('#load').hide();
          })
          .catch((err) => {
            console.log(err);
          });
      },
      GetGroup:function()
      {
        $('#load').show();
          axios.get('http://localhost/Evaluation/Api/SettingService.php?route=getgroup')
          .then(response => {
              this.Group = response.data;
              $('#load').hide();
              $('#NavigationModal').modal('show');
          })
          .catch((err) => {
            console.log(err);
          });
      },
      Edit:function(NavigationId)
      {
        this.Submitted  = false;
        $('#load').show();
        axios.get('http://localhost/Evaluation/Api/SettingService.php?id='+NavigationId+'&route=getnavigationbyid')
        .then(response => {
            this.Navigation = response.data;
            $('#load').hide();
            this.GetGroup();
        })
        .catch((err) => {
          console.log(err);
        });
      }
    }
});
